<?php

namespace App\Http\Controllers;

use App\Models\CashWithdrawal;
use App\Models\Classroom;
use App\Models\WasteDeposit;
use App\Models\WastePrice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(): View
    {
        $title = 'SudiCan - Dashboard';
        $today = now()->translatedFormat('l, d F Y');

        $totalWaste = (float) WasteDeposit::sum('weight');
        $totalDepositValue = (float) WasteDeposit::sum('value');
        $totalApprovedWithdrawal = (float) CashWithdrawal::approved()->sum('amount');
        $totalCash = max(0, $totalDepositValue - $totalApprovedWithdrawal);
        $activeClassCount = Classroom::count();
        $pendingCount = CashWithdrawal::pending()->count();

        $stats = [
            [
                'label' => 'Total Sampah Terkumpul',
                'value' => number_format($totalWaste, 0, ',', '.').' kg',
                'trend' => 'bulan ini',
                'trendUp' => true,
            ],
            [
                'label' => 'Total Perputaran Kas Sekolah',
                'value' => 'Rp '.number_format($totalCash, 0, ',', '.'),
                'trend' => 'bulan ini',
                'trendUp' => true,
            ],
            [
                'label' => 'Kelas Aktif',
                'value' => $activeClassCount.' Kelas',
                'trend' => 'terdaftar di sistem',
                'trendUp' => null,
            ],
            [
                'label' => 'Pengajuan Pending',
                'value' => (string) $pendingCount,
                'trend' => $pendingCount > 0 ? 'menunggu validasi Anda' : 'semua pengajuan selesai',
                'trendUp' => null,
                'warn' => $pendingCount > 0,
            ],
        ];

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $sum = (float) WasteDeposit::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('weight');
            $months[] = [
                'label' => $date->translatedFormat('M'),
                'value' => (int) round($sum),
            ];
        }

        $topClasses = Classroom::orderByDesc('points')->take(5)->get()->map(fn ($c, $idx) => [
            'id' => $c->id,
            'rank' => $idx + 1,
            'name' => $c->name,
            'points' => (int) $c->points,
        ])->all();

        $recentDeposits = WasteDeposit::with('classroom')->latest()->take(3)->get()->map(fn ($d) => [
            'date' => $d->created_at ? $d->created_at->translatedFormat('d M Y') : '-',
            'class' => $d->classroom?->name ?? '-',
            'type' => 'Setoran',
            'detail' => $d->waste_type.' · '.(float) $d->weight.' kg',
            'amount' => '+Rp'.number_format($d->value, 0, ',', '.'),
            'positive' => true,
            'raw_date' => $d->created_at,
        ]);

        $recentWithdrawals = CashWithdrawal::with('classroom')->approved()->latest()->take(2)->get()->map(fn ($w) => [
            'date' => $w->created_at ? $w->created_at->translatedFormat('d M Y') : '-',
            'class' => $w->classroom?->name ?? '-',
            'type' => 'Penarikan',
            'detail' => $w->purpose,
            'amount' => '-Rp'.number_format($w->amount, 0, ',', '.'),
            'positive' => false,
            'raw_date' => $w->created_at,
        ]);

        $activities = $recentDeposits->concat($recentWithdrawals)->sortByDesc('raw_date')->values()->all();

        return view('teachers.index', [
            'title' => $title,
            'today' => $today,
            'stats' => $stats,
            'monthly' => $months,
            'topClasses' => $topClasses,
            'activities' => $activities,
        ]);
    }

    public function show(string $id): View
    {
        $title = 'SudiCan - Detail Kelas';
        $classroom = Classroom::with(['wasteDeposits' => fn ($q) => $q->latest()->take(15)])->findOrFail($id);

        $history = $classroom->wasteDeposits->map(fn ($d) => [
            'date' => $d->created_at ? $d->created_at->translatedFormat('d M Y') : '-',
            'type' => $d->waste_type,
            'weight' => (float) $d->weight.' kg',
            'amount' => '+Rp'.number_format($d->value, 0, ',', '.'),
        ])->all();

        return view('teachers.admin.show', [
            'title' => $title,
            'class' => $classroom,
            'history' => $history,
        ]);
    }

    public function create(): View
    {
        return view('teachers.admin.create', [
            'title' => 'SudiCan - Tambah Kelas',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:classrooms,name'],
            'homeroom_teacher' => ['required', 'string', 'max:100'],
            'members' => ['required', 'integer', 'min:1'],
        ]);

        Classroom::create([
            'name' => $validated['name'],
            'homeroom_teacher' => $validated['homeroom_teacher'],
            'members' => $validated['members'],
            'points' => 0,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function wasteDeposit(): View
    {
        $title = 'SudiCan - Waste Deposit';

        $classes = Classroom::orderBy('name')->pluck('name')->all();

        $wastePrices = WastePrice::all()->map(fn ($wp) => [
            'name' => $wp->name,
            'price' => (int) $wp->price,
        ])->all();

        $history = WasteDeposit::with('classroom')->latest()->take(10)->get()->map(fn ($d) => [
            'time' => $d->created_at ? $d->created_at->translatedFormat('d M · H:i') : '-',
            'class' => $d->classroom?->name ?? '-',
            'type' => $d->waste_type,
            'weight' => (float) $d->weight.' kg',
            'amount' => '+Rp'.number_format($d->value, 0, ',', '.'),
            'points' => (int) $d->points,
        ])->all();

        $selectedClass = $classes[0] ?? 'Kelas Belum Terdaftar';
        $selectedWaste = $wastePrices[0]['name'] ?? 'Botol Plastik';
        $unitPrice = $wastePrices[0]['price'] ?? 3000;
        $defaultWeight = 1.0;

        return view('teachers.waste-deposit', [
            'title' => $title,
            'classes' => $classes,
            'wasteTypes' => $wastePrices,
            'history' => $history,
            'selectedClass' => $selectedClass,
            'selectedWaste' => $selectedWaste,
            'weight' => $defaultWeight,
            'previewRupiah' => 'Rp '.number_format($unitPrice * $defaultWeight, 0, ',', '.'),
            'previewPoints' => round($defaultWeight * 10).' pts',
        ]);
    }

    public function storeWasteDeposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class' => ['required', 'string'],
            'waste_type' => ['required', 'string'],
            'weight' => ['required', 'numeric', 'min:0.1'],
            'note' => ['nullable', 'string'],
        ]);

        $classroom = Classroom::where('name', $validated['class'])->firstOrFail();
        $wastePrice = WastePrice::where('name', $validated['waste_type'])->first();

        $unitPrice = $wastePrice ? (float) $wastePrice->price : 3000;
        $value = round($validated['weight'] * $unitPrice);
        $points = (int) round($validated['weight'] * 10);

        $classroom->wasteDeposits()->create([
            'waste_price_id' => $wastePrice?->id,
            'waste_type' => $validated['waste_type'],
            'weight' => $validated['weight'],
            'value' => $value,
            'points' => $points,
            'validator_id' => Auth::id(),
            'validator_name' => Auth::user()?->name ?? 'Admin SudiCan',
            'deposit_date' => now(),
            'note' => $validated['note'] ?? null,
        ]);

        $classroom->increment('points', $points);

        return redirect()->route('teachers.waste-deposit')->with('success', 'Setoran berhasil dicatat.');
    }

    public function cashReport(): View
    {
        $title = 'SudiCan - Cash Report';

        $totalDepositValue = (float) WasteDeposit::sum('value');
        $totalApprovedWithdrawal = (float) CashWithdrawal::approved()->sum('amount');
        $totalSchoolCash = max(0, $totalDepositValue - $totalApprovedWithdrawal);

        $monthWithdrawal = (float) CashWithdrawal::approved()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $monthWithdrawalCount = CashWithdrawal::approved()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingCount = CashWithdrawal::pending()->count();
        $classCount = Classroom::count();

        $stats = [
            [
                'label' => 'Saldo Kas Sekolah',
                'value' => 'Rp '.number_format($totalSchoolCash, 0, ',', '.'),
                'note' => 'terkumpul dari '.$classCount.' kelas',
            ],
            [
                'label' => 'Total Penarikan Bulan Ini',
                'value' => 'Rp '.number_format($monthWithdrawal, 0, ',', '.'),
                'note' => 'dari '.$monthWithdrawalCount.' transaksi',
            ],
            [
                'label' => 'Menunggu Validasi',
                'value' => (string) $pendingCount,
                'note' => 'pengajuan perlu diproses',
                'warn' => $pendingCount > 0,
            ],
        ];

        $pending = CashWithdrawal::with(['classroom', 'user'])
            ->pending()
            ->latest()
            ->get()
            ->map(fn ($w) => [
                'id' => $w->id,
                'class' => $w->classroom?->name ?? 'Kelas tidak diketahui',
                'requester' => $w->user?->name ?? 'Pengurus Kelas',
                'classBalance' => $w->classroom ? 'Rp '.number_format($w->classroom->availableBalance(), 0, ',', '.') : '-',
                'submittedAt' => $w->created_at ? $w->created_at->translatedFormat('d M Y · H:i') : '-',
                'amount' => 'Rp '.number_format($w->amount, 0, ',', '.'),
                'purpose' => $w->purpose,
                'neededAt' => $w->needed_at ? $w->needed_at->translatedFormat('d M Y') : '-',
                'note' => $w->note ?? '-',
            ])->all();

        $history = CashWithdrawal::with(['classroom', 'user', 'approver'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->take(15)
            ->get()
            ->map(fn ($w) => [
                'id' => $w->id,
                'class' => $w->classroom?->name ?? 'Kelas tidak diketahui',
                'requester' => $w->user?->name ?? 'Pengurus Kelas',
                'date' => $w->updated_at ? $w->updated_at->translatedFormat('d M Y · H:i') : '-',
                'amount' => 'Rp '.number_format($w->amount, 0, ',', '.'),
                'purpose' => $w->purpose,
                'status' => $w->status,
                'approver' => $w->approver?->name ?? 'Admin Sekolah',
            ])->all();

        return view('teachers.cash-report', [
            'title' => $title,
            'stats' => $stats,
            'pending' => $pending,
            'history' => $history,
        ]);
    }

    public function approveWithdrawal(string $id): RedirectResponse
    {
        $withdrawal = CashWithdrawal::findOrFail($id);
        $withdrawal->approve(Auth::id());

        return redirect()->route('teachers.cash-report')->with('success', "Pengajuan #{$id} disetujui dan dicairkan.");
    }

    public function rejectWithdrawal(string $id): RedirectResponse
    {
        $withdrawal = CashWithdrawal::findOrFail($id);
        $withdrawal->reject();

        return redirect()->route('teachers.cash-report')->with('success', "Pengajuan #{$id} ditolak.");
    }

    public function edit(string $id): View
    {
        $classroom = Classroom::findOrFail($id);

        return view('teachers.admin.edit', [
            'title' => 'SudiCan - Edit Kelas',
            'class' => $classroom,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $classroom = Classroom::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:classrooms,name,'.$classroom->id],
            'homeroom_teacher' => ['required', 'string', 'max:100'],
            'members' => ['required', 'integer', 'min:1'],
        ]);

        $classroom->update($validated);

        return redirect()->route('teachers.show', $classroom->id)->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        Classroom::findOrFail($id)->delete();

        return redirect()->route('teachers.index')->with('success', "Kelas #{$id} berhasil dihapus.");
    }

    public function wastePrices(): View
    {
        return view('teachers.admin.waste-prices', [
            'title' => 'SudiCan - Manajemen Harga Sampah',
            'prices' => WastePrice::latest()->get(),
        ]);
    }

    public function wastePricesCreate(): View
    {
        return view('teachers.admin.waste-price-form', [
            'title' => 'SudiCan - Tambah Jenis Sampah',
            'formAction' => route('teachers.waste-prices.store'),
            'method' => 'POST',
            'item' => null,
        ]);
    }

    public function wastePricesStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        WastePrice::create($validated);

        return redirect()->route('teachers.waste-prices.index')->with('success', 'Jenis sampah baru berhasil ditambahkan.');
    }

    public function wastePricesEdit(string $id): View
    {
        $wastePrice = WastePrice::findOrFail($id);

        return view('teachers.admin.waste-price-form', [
            'title' => 'SudiCan - Edit Jenis Sampah',
            'formAction' => route('teachers.waste-prices.update', $wastePrice->id),
            'method' => 'PUT',
            'item' => $wastePrice,
        ]);
    }

    public function wastePricesUpdate(Request $request, string $id): RedirectResponse
    {
        $wastePrice = WastePrice::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $wastePrice->update($validated);

        return redirect()->route('teachers.waste-prices.index')->with('success', 'Harga jenis sampah berhasil diperbarui.');
    }

    public function wastePricesDestroy(string $id): RedirectResponse
    {
        WastePrice::findOrFail($id)->delete();

        return redirect()->route('teachers.waste-prices.index')->with('success', 'Jenis sampah berhasil dihapus.');
    }

    public function accounts(): View
    {
        return view('teachers.admin.accounts', [
            'title' => 'SudiCan - Manajemen Akun',
        ]);
    }
}

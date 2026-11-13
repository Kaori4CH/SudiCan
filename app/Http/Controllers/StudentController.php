<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\WasteDeposit;
use App\Models\WastePrice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected function resolveClassroom(): ?Classroom
    {
        $user = Auth::user();
        if ($user && $user->classroom) {
            return $user->classroom;
        }
        if ($user && $user->classroom_id) {
            return Classroom::find($user->classroom_id);
        }

        return Classroom::first();
    }

    public function dashboard(): View
    {
        $classroom = $this->resolveClassroom();

        $totalWaste = $classroom ? $classroom->totalWaste() : 0;
        $totalCash = $classroom ? $classroom->availableBalance() : 0;

        $activeClass = Classroom::orderByDesc('points')->first()?->name ?? ($classroom?->name ?? 'Belum ada kelas');

        $wasteTypes = WastePrice::all()->map(fn ($wp) => [
            'label' => $wp->name,
            'price' => (int) $wp->price,
        ])->all();

        if (empty($wasteTypes)) {
            $wasteTypes = [
                ['label' => 'Botol Plastik', 'price' => 3000],
                ['label' => 'Kertas Karton', 'price' => 2000],
                ['label' => 'Kaleng Logam', 'price' => 4000],
            ];
        }

        $rankings = Classroom::orderByDesc('points')->take(5)->get()->map(fn ($c) => [
            'class' => $c->name,
            'points' => (int) $c->points,
        ])->all();

        $recentDepositsQuery = $classroom ? $classroom->wasteDeposits()->latest() : WasteDeposit::with('classroom')->latest();
        $recentDeposits = $recentDepositsQuery->take(5)->get()->map(fn ($d) => [
            'date' => $d->created_at ? $d->created_at->translatedFormat('d M Y') : '-',
            'class' => $d->classroom?->name ?? ($classroom?->name ?? '-'),
            'type' => $d->waste_type,
            'weight' => (float) $d->weight,
            'value' => (int) $d->value,
            'points' => (int) $d->points,
        ])->all();

        $trendLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $trendData = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = now()->subWeeks($i + 1)->startOfWeek();
            $end = now()->subWeeks($i)->endOfWeek();
            $query = $classroom ? $classroom->wasteDeposits() : WasteDeposit::query();
            $trendData[] = round((float) $query->whereBetween('created_at', [$start, $end])->sum('weight'), 1);
        }

        return view('student.dashboard', [
            'totalWaste' => $totalWaste,
            'totalCash' => $totalCash,
            'activeClass' => $activeClass,
            'wasteTypes' => $wasteTypes,
            'rankings' => $rankings,
            'recentDeposits' => $recentDeposits,
            'trendLabels' => $trendLabels,
            'trendData' => $trendData,
        ]);
    }

    public function cashReport(): View
    {
        $classroom = $this->resolveClassroom();

        $totalIncome = $classroom ? $classroom->totalIncome() : 0;
        $totalWithdrawal = $classroom ? $classroom->totalApprovedWithdrawal() : 0;
        $availableBalance = $classroom ? $classroom->availableBalance() : 0;

        $pendingWithdrawals = $classroom
            ? $classroom->cashWithdrawals()->pending()->latest()->get()
            : collect();

        $deposits = $classroom
            ? $classroom->wasteDeposits()->latest()->get()->map(fn ($d) => [
                'raw_date' => $d->created_at,
                'date' => $d->created_at ? $d->created_at->translatedFormat('d M Y') : '-',
                'type' => 'Deposit',
                'source' => 'Penyetoran '.$d->waste_type,
                'deposit' => (int) $d->value,
                'withdrawal' => null,
                'is_pending' => false,
            ])
            : collect();

        $approvedWithdrawals = $classroom
            ? $classroom->cashWithdrawals()->approved()->latest()->get()->map(fn ($w) => [
                'raw_date' => $w->created_at,
                'date' => $w->created_at ? $w->created_at->translatedFormat('d M Y') : '-',
                'type' => 'Penarikan',
                'source' => $w->purpose,
                'deposit' => null,
                'withdrawal' => (int) $w->amount,
                'is_pending' => false,
            ])
            : collect();

        $pendingTransactions = $pendingWithdrawals->map(fn ($w) => [
            'raw_date' => $w->created_at,
            'date' => $w->created_at ? $w->created_at->translatedFormat('d M Y') : '-',
            'type' => 'Penarikan (Menunggu)',
            'source' => $w->purpose.' (Menunggu Validasi)',
            'deposit' => null,
            'withdrawal' => (int) $w->amount,
            'is_pending' => true,
        ]);

        $sorted = $deposits->concat($approvedWithdrawals)->sortBy('raw_date')->values();
        $runningBalance = 0;
        $transactions = [];

        foreach ($sorted as $item) {
            if ($item['deposit'] !== null) {
                $runningBalance += $item['deposit'];
            }
            if ($item['withdrawal'] !== null) {
                $runningBalance -= $item['withdrawal'];
            }
            $item['balance'] = max(0, $runningBalance);
            $transactions[] = $item;
        }

        $allTransactions = collect($transactions)->concat($pendingTransactions->map(function ($item) {
            $item['balance'] = null;

            return $item;
        }))->sortByDesc('raw_date')->values()->all();

        return view('student.cash-report', [
            'className' => $classroom?->name ?? 'Belum terdaftar',
            'totalIncome' => (int) $totalIncome,
            'totalWithdrawal' => (int) $totalWithdrawal,
            'availableBalance' => (int) $availableBalance,
            'transactions' => $allTransactions,
            'pendingWithdrawals' => $pendingWithdrawals,
        ]);
    }

    public function wasteDeposit(): View
    {
        $classroom = $this->resolveClassroom();

        $totalWaste = $classroom ? $classroom->totalWaste() : 0;
        $activeClass = Classroom::orderByDesc('points')->first()?->name ?? ($classroom?->name ?? 'Belum ada');

        $topWasteType = 'Belum ada';
        if ($classroom) {
            $top = $classroom->wasteDeposits()
                ->selectRaw('waste_type, sum(weight) as total_weight')
                ->groupBy('waste_type')
                ->orderByDesc('total_weight')
                ->first();
            if ($top) {
                $topWasteType = $top->waste_type;
            }
        }

        $deposits = ($classroom ? $classroom->wasteDeposits() : WasteDeposit::query())
            ->with('classroom')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($d) => [
                'date' => $d->created_at ? $d->created_at->translatedFormat('d M Y') : '-',
                'class' => $d->classroom?->name ?? ($classroom?->name ?? '-'),
                'type' => $d->waste_type,
                'weight' => (float) $d->weight,
                'value' => (int) $d->value,
                'validator' => $d->validator_name ?? 'Petugas Bank Sampah',
            ])->all();

        return view('student.waste-deposit', [
            'totalWaste' => $totalWaste,
            'activeClass' => $activeClass,
            'topWasteType' => $topWasteType,
            'deposits' => $deposits,
        ]);
    }

    public function greenRewards(): View
    {
        $classroom = $this->resolveClassroom();
        $points = $classroom ? (int) $classroom->points : 0;

        return view('student.greenrewards', [
            'points' => $points,
            'classroom' => $classroom,
        ]);
    }

    public function cashWithdrawal(): View
    {
        $classroom = $this->resolveClassroom();
        $availableBalance = $classroom ? $classroom->availableBalance() : 0;

        return view('student.cash-withdrawal', [
            'availableBalance' => (int) $availableBalance,
            'balanceDate' => now()->translatedFormat('d F Y'),
        ]);
    }

    public function storeCashWithdrawal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
            'purpose' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'needed_at' => ['nullable', 'date'],
        ]);

        $classroom = $this->resolveClassroom();
        if (! $classroom) {
            return back()->withErrors(['amount' => 'Akun Anda belum terikat dengan kelas manapun.'])->withInput();
        }

        if ((float) $validated['amount'] > $classroom->availableBalance()) {
            return back()->withErrors(['amount' => 'Jumlah penarikan melebihi saldo kas yang tersedia.'])->withInput();
        }

        $classroom->cashWithdrawals()->create([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'purpose' => $validated['purpose'],
            'needed_at' => $validated['needed_at'] ?? null,
            'note' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('student.cash-report')->with('success', 'Pengajuan penarikan dana berhasil dikirim dan menunggu validasi.');
    }
}

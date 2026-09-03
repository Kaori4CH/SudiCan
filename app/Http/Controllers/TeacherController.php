<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * GET /teachers -> name('teachers.index')
     * Dashboard utama Admin/Pengelola Bank Sampah + ringkasan pengajuan pending.
     */
    public function index()
    {
        $title = 'SudiCan - Dashboard';
        $today = $this->formatIndonesianDate(now());

        $stats = [
            ['label' => 'Total Sampah Terkumpul', 'value' => '4.820 kg', 'trend' => '9% bulan ini', 'trendUp' => true],
            ['label' => 'Total Perputaran Kas Sekolah', 'value' => 'Rp 42.350.000', 'trend' => '14% bulan ini', 'trendUp' => true],
            ['label' => 'Kelas Aktif', 'value' => '24 Kelas', 'trend' => 'dari 26 kelas terdaftar', 'trendUp' => null],
            ['label' => 'Pengajuan Pending', 'value' => '2', 'trend' => 'menunggu validasi Anda', 'trendUp' => null, 'warn' => true],
        ];

        $monthly = [
            ['label' => 'Mar', 'value' => 610],
            ['label' => 'Apr', 'value' => 705],
            ['label' => 'Mei', 'value' => 668],
            ['label' => 'Jun', 'value' => 788],
            ['label' => 'Jul', 'value' => 845],
            ['label' => 'Agu', 'value' => 920],
        ];

        $topClasses = [
            ['id' => 1, 'rank' => 1, 'name' => 'XII TKJ 1', 'points' => 945],
            ['id' => 2, 'rank' => 2, 'name' => 'XII TKJ 2', 'points' => 780],
            ['id' => 3, 'rank' => 3, 'name' => 'XII TKJ 3', 'points' => 512],
            ['id' => 4, 'rank' => 4, 'name' => 'XII AKL', 'points' => 490],
            ['id' => 5, 'rank' => 5, 'name' => 'XII BiD', 'points' => 385],
        ];

        $activities = [
            ['date' => '18 Agu 2026', 'class' => 'XII TKJ 1', 'type' => 'Setoran', 'detail' => 'Botol Plastik · 5.2 kg', 'amount' => '+Rp15.600', 'positive' => true],
            ['date' => '18 Agu 2026', 'class' => 'Kelas 8B', 'type' => 'Setoran', 'detail' => 'Kaleng Logam · 3 kg', 'amount' => '+Rp12.000', 'positive' => true],
            ['date' => '17 Agu 2026', 'class' => 'Kelas 7A', 'type' => 'Penarikan', 'detail' => 'Pembelian alat kebersihan', 'amount' => '-Rp150.000', 'positive' => false],
            ['date' => '17 Agu 2026', 'class' => 'Kelas 8A', 'type' => 'Setoran', 'detail' => 'Kertas Karton · 8 kg', 'amount' => '+Rp16.000', 'positive' => true],
            ['date' => '16 Agu 2026', 'class' => 'Kelas 9C', 'type' => 'Setoran', 'detail' => 'Botol Plastik · 4 kg', 'amount' => '+Rp12.000', 'positive' => true],
        ];

        return view('Teachers.index', [
            'title' => $title,
            'today' => $today,
            'stats' => $stats,
            'monthly' => $monthly,
            'topClasses' => $topClasses,
            'activities' => $activities,
        ]);
    }

    /**
     * GET /teachers/{id} -> name('teachers.show')
     * Detail 1 kelas.
     */
    public function show(string $id)
    {
        $title = 'SudiCan - Detail Kelas';

        $class = [
            'id' => $id,
            'name' => 'XII TKJ 1',
            'totalWaste' => '945 kg',
            'totalCash' => 'Rp 4.250.000',
            'points' => 945,
            'members' => 32,
        ];

        $history = [
            ['date' => '18 Agu 2026', 'type' => 'Botol Plastik', 'weight' => '5.2 kg', 'amount' => '+Rp15.600'],
            ['date' => '15 Agu 2026', 'type' => 'Kaleng Logam', 'weight' => '3.0 kg', 'amount' => '+Rp12.000'],
            ['date' => '10 Agu 2026', 'type' => 'Kertas/Karton', 'weight' => '8.0 kg', 'amount' => '+Rp16.000'],
        ];

        return view('Teachers.show', [
            'title' => $title,
            'class' => $class,
            'history' => $history,
        ]);
    }

    /**
     * GET /teachers/create -> name('teachers.create')
     * Form Tambah Kelas baru.
     */
    public function create()
    {
        $title = 'SudiCan - Tambah Kelas';

        return view('Teachers.create', [
            'title' => $title,
        ]);
    }

    /**
     * POST /teachers -> name('teachers.store')
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'homeroom_teacher' => 'required|string|max:100',
            'members' => 'required|integer|min:1',
        ]);

        // NOTE: penyimpanan ke database belum diimplementasikan (masih data dummy).

        return redirect()->route('teachers.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    /**
     * GET /teachers/waste-deposit -> name('teachers.waste-deposit')
     * Form Waste Deposit — Input Transaksi.
     */
    public function wasteDeposit()
    {
        $title = 'SudiCan - Waste Deposit';

        $classes = ['Kelas 7A', 'Kelas 8A', 'Kelas 8B', 'Kelas 9C', 'XI TKJ 1', 'XII TKJ 1', 'XII TKJ 2', 'XII TKJ 3', 'XII AKL', 'XII BiD'];

        $wasteTypes = [
            ['name' => 'Botol Plastik', 'price' => 3000],
            ['name' => 'Kaleng Logam', 'price' => 4000],
            ['name' => 'Kertas/Karton', 'price' => 2000],
            ['name' => 'Kaca', 'price' => 1500],
            ['name' => 'Minyak Jelantah', 'price' => 5000],
        ];

        $history = [
            ['time' => '18 Agu · 09:12', 'class' => 'XII TKJ 1', 'type' => 'Botol Plastik', 'weight' => '5.2 kg', 'amount' => '+Rp15.600', 'points' => 52],
            ['time' => '18 Agu · 08:47', 'class' => 'XII AKL', 'type' => 'Kaleng Logam', 'weight' => '3.0 kg', 'amount' => '+Rp12.000', 'points' => 40],
            ['time' => '17 Agu · 14:20', 'class' => 'XII TKJ 2', 'type' => 'Kertas/Karton', 'weight' => '8.0 kg', 'amount' => '+Rp16.000', 'points' => 53],
            ['time' => '17 Agu · 10:05', 'class' => 'XII TKJ 3', 'type' => 'Botol Plastik', 'weight' => '4.0 kg', 'amount' => '+Rp12.000', 'points' => 40],
            ['time' => '16 Agu · 13:30', 'class' => 'XII BiD', 'type' => 'Kaca', 'weight' => '6.5 kg', 'amount' => '+Rp9.750', 'points' => 33],
            ['time' => '15 Agu · 09:50', 'class' => 'XI TKJ 1', 'type' => 'Minyak Jelantah', 'weight' => '2.0 kg', 'amount' => '+Rp10.000', 'points' => 33],
        ];

        return view('Teachers.waste-deposit', [
            'title' => $title,
            'classes' => $classes,
            'wasteTypes' => $wasteTypes,
            'history' => $history,
            'selectedClass' => 'Kelas 8B',
            'selectedWaste' => 'Botol Plastik',
            'weight' => 5.2,
            'previewRupiah' => 'Rp 15.600',
            'previewPoints' => '52 pts',
        ]);
    }

    /**
     * POST /teachers/waste-deposit -> name('teachers.waste-deposit.store')
     * Simpan setoran sampah baru.
     */
    public function storeWasteDeposit(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'waste_type' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'note' => 'nullable|string',
        ]);

        // NOTE: penyimpanan ke database belum diimplementasikan (masih data dummy).

        return redirect()->route('teachers.waste-deposit')->with('success', 'Setoran berhasil dicatat.');
    }

    /**
     * GET /teachers/cash-report -> name('teachers.cash-report')
     * Halaman Cash Report — Validasi Penarikan (daftar semua pengajuan pending).
     */
    public function cashReport()
    {
        $title = 'SudiCan - Cash Report';

        $stats = [
            ['label' => 'Saldo Kas Sekolah', 'value' => 'Rp 38.920.400', 'note' => 'terkumpul dari 24 kelas'],
            ['label' => 'Total Penarikan Bulan Ini', 'value' => 'Rp 1.925.000', 'note' => 'dari 9 transaksi'],
            ['label' => 'Menunggu Validasi', 'value' => '2', 'note' => 'pengajuan perlu diproses', 'warn' => true],
        ];

        return view('Teachers.cash-report', [
            'title' => $title,
            'stats' => $stats,
            'pending' => $this->dummyPendingWithdrawals(),
        ]);
    }

    /**
     * PUT /teachers/cash-report/{id}/approve -> name('teachers.cash-report.approve')
     * Setujui & cairkan pengajuan penarikan kas.
     */
    public function approveWithdrawal(string $id)
    {
        // NOTE: proses pencairan dana belum terhubung ke database (masih data dummy).

        return redirect()->route('teachers.cash-report')->with('success', "Pengajuan #{$id} disetujui dan dicairkan.");
    }

    /**
     * DELETE /teachers/cash-report/{id}/reject -> name('teachers.cash-report.reject')
     * Tolak pengajuan penarikan kas.
     */
    public function rejectWithdrawal(string $id)
    {
        // NOTE: proses penolakan belum terhubung ke database (masih data dummy).

        return redirect()->route('teachers.cash-report')->with('success', "Pengajuan #{$id} ditolak.");
    }

    /**
     * GET /teachers/{id}/edit -> name('teachers.edit')
     * Form Edit Kelas.
     */
    public function edit(string $id)
    {
        $title = 'SudiCan - Edit Kelas';

        $class = [
            'id' => $id,
            'name' => 'XII TKJ 1',
            'homeroom_teacher' => 'Bu Sari Wulandari',
            'members' => 32,
        ];

        return view('Teachers.edit', [
            'title' => $title,
            'class' => $class,
        ]);
    }

    /**
     * PUT /teachers/{id} -> name('teachers.update')
     * Simpan perubahan data kelas.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'homeroom_teacher' => 'required|string|max:100',
            'members' => 'required|integer|min:1',
        ]);

        // NOTE: penyimpanan ke database belum diimplementasikan (masih data dummy).

        return redirect()->route('teachers.show', $id)->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * DELETE /teachers/{id} -> name('teachers.destroy')
     * Hapus kelas.
     */
    public function destroy(string $id)
    {
        // NOTE: penghapusan dari database belum diimplementasikan (masih data dummy).

        return redirect()->route('teachers.index')->with('success', "Kelas #{$id} berhasil dihapus.");
    }

    private function dummyPendingWithdrawals(): array
    {
        return [
            [
                'id' => 1,
                'class' => 'XII TKJ 1',
                'submittedAt' => '18 Agu 2026',
                'amount' => 'Rp 200.000',
                'purpose' => 'Pembelian alat kebersihan',
                'neededAt' => '20 Agu 2026',
                'note' => 'Sapu, pel, dan tempat sampah baru untuk ruang kelas.',
            ],
            [
                'id' => 2,
                'class' => 'Kelas 8A',
                'submittedAt' => '17 Agu 2026',
                'amount' => 'Rp 100.000',
                'purpose' => 'Dana kegiatan kelas',
                'neededAt' => '19 Agu 2026',
                'note' => 'Konsumsi rapat wali kelas bulanan.',
            ],
        ];
    }

    private function formatIndonesianDate(\DateTimeInterface $date): string
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return sprintf(
            '%s, %d %s %d',
            $days[(int) $date->format('w')],
            (int) $date->format('j'),
            $months[(int) $date->format('n')],
            (int) $date->format('Y')
        );
    }
}

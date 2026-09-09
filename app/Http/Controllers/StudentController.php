<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        return view('student.dashboard', [
            'totalWaste' => 0,
            'totalCash' => 0,
            'rankings' => [],
            'recentDeposits' => []
        ]);
    }

    public function cashReport()
    {
        return view('student.cash-report', [
            'className' => 'XII TKJ 1',
            'totalIncome' => 3175100,
            'totalWithdrawal' => 1250000,
            'availableBalance' => 1925100,
            'transactions' => [
                ['date' => '18 Agu 2026', 'type' => 'Deposit', 'source' => 'Penyetoran Botol Plastik', 'deposit' => 15600, 'withdrawal' => null, 'balance' => 1925100],
                ['date' => '17 Agu 2026', 'type' => 'Deposit', 'source' => 'Penyetoran Kertas Karton', 'deposit' => 24000, 'withdrawal' => null, 'balance' => 1909500],
                ['date' => '16 Agu 2026', 'type' => 'Penarikan', 'source' => 'Pembelian Alat Kebersihan', 'deposit' => null, 'withdrawal' => 150000, 'balance' => 1885500],
                ['date' => '15 Agu 2026', 'type' => 'Deposit', 'source' => 'Penyetoran Kaleng Logam', 'deposit' => 10000, 'withdrawal' => null, 'balance' => 2035500],
                ['date' => '14 Agu 2026', 'type' => 'Penarikan', 'source' => 'Pembelian Bahan Kegiatan', 'deposit' => null, 'withdrawal' => 75000, 'balance' => 2025500],
            ]
        ]);
    }

    public function wasteDeposit()
    {
        return view('student.waste-deposit', [
            'totalWaste' => 328,
            'activeClass' => 'XII TKJ 1',
            'topWasteType' => 'Botol Plastik',
            'deposits' => [
                ['date' => '18 Agu 2026', 'class' => 'XII TKJ 1', 'type' => 'Botol Plastik', 'weight' => 6,  'value' => 21000, 'validator' => 'Elvan Emmanuel F'],
                ['date' => '17 Agu 2026', 'class' => 'XI AKL',    'type' => 'Kertas Karton', 'weight' => 8,  'value' => 28000, 'validator' => 'Charles'],
                ['date' => '16 Agu 2026', 'class' => 'XII TKJ 2', 'type' => 'Botol Plastik', 'weight' => 10, 'value' => 35000, 'validator' => 'Elvan Emmanuel F'],
                ['date' => '15 Agu 2026', 'class' => 'XII TKJ 1', 'type' => 'Kaleng Logam',  'weight' => 15, 'value' => 52500, 'validator' => 'Elvan Emmanuel F'],
                ['date' => '14 Agu 2026', 'class' => 'X BID 2',   'type' => 'Kardus',        'weight' => 12, 'value' => 42000, 'validator' => 'Charles'],
                ['date' => '12 Agu 2026', 'class' => 'XI TKJ 1',  'type' => 'Botol Plastik', 'weight' => 7,  'value' => 24500, 'validator' => 'Michael Robert Y'],
                ['date' => '10 Agu 2026', 'class' => 'XII TKJ 1', 'type' => 'Kertas Karton', 'weight' => 14, 'value' => 49000, 'validator' => 'Arthur Sebastian F'],
            ]
        ]);
    }

    public function cashWithdrawal()
    {
        return view('student.cash-withdrawal', [
            'availableBalance' => 1925100,
            'balanceDate' => '18 Agustus 2026',
        ]);
    }

}

@extends('layouts.student')

@section('title', 'Cash Report')

@section('content')

    @php
        $formatRp = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <h1 class="text-2xl font-bold">Cash Report - Kelas {{ $className }}</h1>
    <p class="text-sm text-gray-500 mb-6">Ringkasan transaksi keuangan dari hasil penukaran sampah menjadi saldo kas kelas.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Pendapatan</p>
                <p class="text-2xl font-bold mt-1">{{ $formatRp($totalPendapatan) }}</p>
                <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                    <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> 12% minggu ini
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/cashout.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Penarikan</p>
                <p class="text-2xl font-bold mt-1">{{ $formatRp($totalPenarikan) }}</p>
                <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <img src="{{ asset('images/redtriangle.png') }}" alt="" class="w-2.5 h-2.5"> 8% minggu ini
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Saldo Tersedia</p>
                <p class="text-2xl font-bold mt-1">{{ $formatRp($saldoTersedia) }}</p>
                <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                    <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> 15% dari minggu lalu
                </p>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <div class="relative">
            <select class="appearance-none bg-white border border-gray-200 rounded-lg pl-9 pr-8 py-2 text-sm">
                <option>Agustus 2026</option>
                <option>Juli 2026</option>
                <option>Juni 2026</option>
            </select>
            <span class="absolute left-3 top-1/2 -translate-y-1/2"><img src="{{ asset('images/calendar.png') }}" alt="" class="w-4 h-4"></span>
        </div>

        <a href="{{ Route::has('student.cash-withdrawal') ? route('student.cash-withdrawal') : '#' }}"
                class="flex items-center gap-2 bg-green-800 hover:bg-green-900 text-white text-sm font-medium px-4 py-2 rounded-lg">
            <img src="{{ asset('images/plusicon.png') }}" alt="" class="w-3.5 h-3.5"> Pengajuan Penarikan Dana
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-5 pt-5 pb-3">
            <h2 class="font-semibold">Riwayat Transaksi</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 bg-gray-50">
                    <th class="px-5 py-2 font-medium">Tanggal</th>
                    <th class="px-5 py-2 font-medium">Jenis</th>
                    <th class="px-5 py-2 font-medium">Penyetor</th>
                    <th class="px-5 py-2 font-medium text-right">Deposit</th>
                    <th class="px-5 py-2 font-medium text-right">Penarikan</th>
                    <th class="px-5 py-2 font-medium text-right">Saldo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $t)
                    <tr>
                        <td class="px-5 py-3">{{ $t['date'] }}</td>
                        <td class="px-5 py-3 font-medium">{{ $t['type'] }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $t['source'] }}</td>
                        <td class="px-5 py-3 text-right">{{ $t['deposit'] ? $formatRp($t['deposit']) : '-' }}</td>
                        <td class="px-5 py-3 text-right">{{ $t['withdrawal'] ? $formatRp($t['withdrawal']) : '-' }}</td>
                        <td class="px-5 py-3 text-right font-medium">{{ $formatRp($t['balance']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-gray-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
        <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center shrink-0 mt-0.5">
            <img src="{{ asset('images/infogreen.png') }}" alt="" class="w-3.5 h-3.5 brightness-0 invert">
        </div>
        <div>
            <p class="text-sm font-semibold text-blue-800">Informasi Penarikan Dana</p>
            <p class="text-sm text-blue-700">Penarikan dana hanya dapat dilakukan oleh bendahara kelas atau guru wali kelas. Pastikan penggunaan dana sesuai dengan kebutuhan kelas.</p>
        </div>
    </div>

@endsection
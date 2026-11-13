@extends('layouts.student')

@section('title', 'Cash Report')

@section('content')

    @php
        $formatCurrency = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <h1 class="text-2xl font-bold">Cash Report - Kelas {{ $className }}</h1>
    <p class="text-sm text-gray-500 mb-6">Ringkasan transaksi keuangan dari hasil penukaran sampah menjadi saldo kas kelas.</p>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($pendingWithdrawals) && $pendingWithdrawals->isNotEmpty())
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <img src="{{ asset('images/clock.png') }}" alt="" class="h-4 w-4">
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-amber-900">Pengajuan Penarikan Menunggu Persetujuan Guru</h3>
                        <span class="rounded-full bg-amber-200/70 px-2 py-0.5 text-xs font-semibold text-amber-800">
                            {{ $pendingWithdrawals->count() }} Pengajuan Pending
                        </span>
                    </div>
                    <div class="mt-2 divide-y divide-amber-200/60">
                        @foreach ($pendingWithdrawals as $pw)
                            <div class="flex flex-wrap items-center justify-between gap-2 py-2 first:pt-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-medium text-amber-950">{{ $pw->purpose }}</p>
                                    @if ($pw->note)
                                        <p class="text-xs text-amber-700">"{{ $pw->note }}"</p>
                                    @endif
                                    <p class="text-[11px] text-amber-600">Diajukan: {{ $pw->created_at ? $pw->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                                </div>
                                <span class="text-base font-bold text-red-600">-{{ $formatCurrency($pw->amount) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Pendapatan</p>
                <p class="text-2xl font-bold mt-1">{{ $formatCurrency($totalIncome) }}</p>
                @if ($totalIncome > 0)
                    <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                        <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> Akumulasi setoran
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Belum ada pendapatan</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/cashout.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Penarikan</p>
                <p class="text-2xl font-bold mt-1">{{ $formatCurrency($totalWithdrawal) }}</p>
                @if ($totalWithdrawal > 0)
                    <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                        <img src="{{ asset('images/redtriangle.png') }}" alt="" class="w-2.5 h-2.5"> Penarikan disetujui
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Belum ada penarikan dana</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Saldo Tersedia</p>
                <p class="text-2xl font-bold mt-1">{{ $formatCurrency($availableBalance) }}</p>
                @if ($availableBalance > 0)
                    <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                        <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> Saldo siap digunakan
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Saldo kas saat ini Rp 0</p>
                @endif
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
                    <tr class="{{ ($t['is_pending'] ?? false) ? 'bg-amber-50/50' : '' }}">
                        <td class="px-5 py-3">{{ $t['date'] }}</td>
                        <td class="px-5 py-3 font-medium">
                            @if ($t['is_pending'] ?? false)
                                <span class="inline-flex items-center gap-1 rounded bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Menunggu Persetujuan
                                </span>
                            @else
                                {{ $t['type'] }}
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $t['source'] }}</td>
                        <td class="px-5 py-3 text-right">{{ $t['deposit'] ? $formatCurrency($t['deposit']) : '-' }}</td>
                        <td class="px-5 py-3 text-right {{ ($t['is_pending'] ?? false) ? 'text-amber-600 font-medium' : '' }}">
                            {{ $t['withdrawal'] ? $formatCurrency($t['withdrawal']) : '-' }}
                        </td>
                        <td class="px-5 py-3 text-right font-medium">
                            {{ $t['balance'] !== null ? $formatCurrency($t['balance']) : '-' }}
                        </td>
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
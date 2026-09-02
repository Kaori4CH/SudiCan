@extends('layouts.student')

@section('title', 'Waste Deposit')

@section('content')

    @php
        $formatRp = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <h1 class="text-2xl font-bold">Waste Deposit</h1>
    <p class="text-sm text-gray-500 mb-6">Ringkasan sampah-sampah yang sudah di deposit</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Sampah Terkumpul</p>
                <p class="text-2xl font-bold mt-1">{{ $totalSampah }} kg</p>
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
                <p class="text-xs text-gray-400 uppercase tracking-wide">Kelas Teraktif</p>
                <p class="text-2xl font-bold mt-1">{{ $activeClass }}</p>
                <p class="text-xs text-gray-400 mt-1">Peringkat 1 Green Kelas</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Jenis Sampah Terbanyak</p>
                <p class="text-2xl font-bold mt-1">{{ $topWasteType }}</p>
                <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                    <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> 12% dari minggu lalu
                </p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="relative inline-block">
            <select class="appearance-none bg-white border border-gray-200 rounded-lg pl-9 pr-8 py-2 text-sm">
                <option>Agustus 2026</option>
                <option>Juli 2026</option>
                <option>Juni 2026</option>
            </select>
            <span class="absolute left-3 top-1/2 -translate-y-1/2"><img src="{{ asset('images/calendar.png') }}" alt="" class="w-4 h-4"></span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 pt-5 pb-3">
            <h2 class="font-semibold">Riwayat Setoran</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 bg-gray-50">
                    <th class="px-5 py-2 font-medium">Tanggal</th>
                    <th class="px-5 py-2 font-medium">Kelas</th>
                    <th class="px-5 py-2 font-medium">Penyetor</th>
                    <th class="px-5 py-2 font-medium text-right">Berat</th>
                    <th class="px-5 py-2 font-medium text-right">Saldo</th>
                    <th class="px-5 py-2 font-medium text-right">Validator</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($deposits as $d)
                    <tr>
                        <td class="px-5 py-3">{{ $d['date'] }}</td>
                        <td class="px-5 py-3 font-medium">{{ $d['class'] }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $d['type'] }}</td>
                        <td class="px-5 py-3 text-right">{{ $d['weight'] }} kg</td>
                        <td class="px-5 py-3 text-right">{{ $formatRp($d['value']) }}</td>
                        <td class="px-5 py-3 text-right text-gray-500">{{ $d['validator'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-gray-400">Belum ada setoran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
@extends('layouts.app-admin')

@section('title', $title)

@section('content')

    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Halo, Admin SudiCan</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $today }} · Rekapitulasi Seluruh Sekolah</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-3.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Tampilan Siswa
            </a>
            <span class="flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Full Access &amp; Eksekutor
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-xl border border-[#E4EFE4] bg-white p-5">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">{{ $stat['value'] }}</p>
                <p class="mt-2 flex items-center gap-1 text-xs font-medium {{ $stat['trendUp'] === true ? 'text-emerald-600' : (($stat['warn'] ?? false) ? 'text-amber-600' : 'text-slate-400') }}">
                    @if ($stat['trendUp'] === true)
                        <img src="{{ asset('images/greentriangle.png') }}" alt="" class="h-3 w-3">
                    @endif
                    {{ $stat['trend'] }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="mb-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <h2 class="text-base font-semibold text-slate-800">Aktivitas Bulanan · Sampah Terkumpul</h2>
        <p class="mb-4 text-sm text-slate-400">6 bulan terakhir, seluruh kelas</p>

        @php
            $maxVal = max(1, (int) collect($monthly)->max('value'));
        @endphp
        <div class="mt-4 flex h-48 items-end justify-between gap-3">
            @foreach ($monthly as $month)
                @php
                    $val = (int) $month['value'];
                    $heightPercent = $val > 0 ? max(12, min(100, round(($val / $maxVal) * 100))) : 4;
                @endphp
                <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                    <span class="text-xs font-semibold {{ $val > 0 ? 'text-slate-800 font-bold' : 'text-slate-400' }}">
                        {{ $val }} <span class="text-[10px] font-normal text-slate-400">kg</span>
                    </span>
                    <div class="flex h-32 w-full items-end justify-center rounded-t-md bg-slate-50 p-1">
                        <div class="w-full max-w-[42px] rounded-t-md transition-all duration-300 {{ $val > 0 ? 'bg-emerald-600 shadow-sm' : 'bg-slate-200' }}"
                             style="height: {{ $heightPercent }}%;"></div>
                    </div>
                    <span class="text-xs font-medium text-slate-500">{{ $month['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-800">Kelas Teraktif Bulan Ini</h2>
            <a href="{{ route('teachers.create') }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">+ Tambah Kelas</a>
        </div>
        <div class="flex flex-col divide-y divide-[#EFF4EE]">
            @foreach ($topClasses as $class)
                <a href="{{ route('teachers.show', $class['id']) }}" class="flex items-center justify-between py-3 hover:bg-slate-50">
                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold {{ $class['rank'] === 1 ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700' }}">
                            {{ $class['rank'] }}
                        </span>
                        <span class="text-sm font-medium text-slate-700">{{ $class['name'] }}</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-800">{{ $class['points'] }} <span class="font-normal text-slate-400">pts</span></span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
        <h2 class="text-base font-semibold text-slate-800">Aktivitas Terbaru — Seluruh Kelas</h2>
        <p class="mb-4 text-sm text-slate-400">Gabungan setoran &amp; penarikan dari semua kelas</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E4EFE4] text-[11px] uppercase tracking-wide text-slate-400">
                        <th class="py-3 pr-4 font-semibold">Tanggal</th>
                        <th class="py-3 pr-4 font-semibold">Kelas</th>
                        <th class="py-3 pr-4 font-semibold">Aktivitas</th>
                        <th class="py-3 pr-4 font-semibold">Detail</th>
                        <th class="py-3 pl-4 text-right font-semibold">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr class="border-b border-[#F2F6F1] last:border-0">
                            <td class="py-3.5 pr-4 text-slate-500">{{ $activity['date'] }}</td>
                            <td class="py-3.5 pr-4 font-medium text-slate-800">{{ $activity['class'] }}</td>
                            <td class="py-3.5 pr-4"><x-pill-badge :status="$activity['type']" /></td>
                            <td class="py-3.5 pr-4 text-slate-500">{{ $activity['detail'] }}</td>
                            <td class="py-3.5 pl-4 text-right font-semibold {{ $activity['positive'] ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $activity['amount'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

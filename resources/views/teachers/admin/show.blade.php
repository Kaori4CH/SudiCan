@extends('layouts.app-admin')

@section('title', $title)

@section('content')

    <div class="mb-6 flex items-start justify-between">
        <div>
            <a href="{{ route('teachers.index') }}" class="mb-2 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700">
                <img src="{{ asset('images/kembali.png') }}" alt="" class="h-3.5 w-3.5"> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Detail Kelas — {{ $class['name'] }}</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan aktivitas sampah &amp; kas kelas ini</p>
        </div>
        <a href="{{ route('teachers.edit', $class['id']) }}" class="flex shrink-0 items-center gap-2 rounded-lg border border-[#DDE7DD] bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
            Edit Kelas
        </a>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-[#E4EFE4] bg-white p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Total Sampah</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $class['totalWaste'] }}</p>
        </div>
        <div class="rounded-xl border border-[#E4EFE4] bg-white p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Total Kas</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $class['totalCash'] }}</p>
        </div>
        <div class="rounded-xl border border-[#E4EFE4] bg-white p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Poin</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $class['points'] }} pts</p>
        </div>
        <div class="rounded-xl border border-[#E4EFE4] bg-white p-5">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Anggota</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $class['members'] }} siswa</p>
        </div>
    </div>

    <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
        <h2 class="mb-4 text-base font-semibold text-slate-800">Riwayat Setoran Kelas Ini</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E4EFE4] text-[11px] uppercase tracking-wide text-slate-400">
                        <th class="py-3 pr-4 font-semibold">Tanggal</th>
                        <th class="py-3 pr-4 font-semibold">Jenis</th>
                        <th class="py-3 pr-4 font-semibold">Berat</th>
                        <th class="py-3 pl-4 text-right font-semibold">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $row)
                        <tr class="border-b border-[#F2F6F1] last:border-0">
                            <td class="py-3.5 pr-4 text-slate-500">{{ $row['date'] }}</td>
                            <td class="py-3.5 pr-4 font-medium text-slate-800">{{ $row['type'] }}</td>
                            <td class="py-3.5 pr-4 text-slate-600">{{ $row['weight'] }}</td>
                            <td class="py-3.5 pl-4 text-right font-semibold text-emerald-600">{{ $row['amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection


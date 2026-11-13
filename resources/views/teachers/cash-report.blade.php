@extends('layouts.app-admin')

@section('title', $title)

@section('content')

    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cash Report — Validasi Penarikan</h1>
            <p class="mt-1 text-sm text-slate-500">Verifikasi dan proses pencairan kas kelas secara fisik</p>
        </div>
        <span class="flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Mode Eksekutor
        </span>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-xl border border-[#E4EFE4] bg-white p-5">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-bold {{ ($stat['warn'] ?? false) ? 'text-red-600' : 'text-slate-800' }}">{{ $stat['value'] }}</p>
                <p class="mt-2 text-xs text-slate-400">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mb-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Pengajuan Menunggu Validasi</h2>
                <p class="text-sm text-slate-400">Konfirmasi setelah pengurus kelas mengambil dana secara fisik</p>
            </div>
            @if (count($pending) > 0)
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                    {{ count($pending) }} Menunggu Persetujuan
                </span>
            @endif
        </div>

        <div class="flex flex-col gap-4">
            @forelse ($pending as $item)
                <div class="rounded-xl border-2 border-amber-300 bg-amber-50/20 p-5 shadow-sm">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-slate-800">{{ $item['class'] }}</h3>
                                <span class="rounded bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-800">Menunggu Persetujuan</span>
                            </div>
                            <p class="mt-0.5 text-xs text-slate-500">Diajukan oleh <span class="font-medium text-slate-700">{{ $item['requester'] }}</span> pada {{ $item['submittedAt'] }}</p>
                        </div>
                        <span class="text-xl font-bold text-red-600">-{{ $item['amount'] }}</span>
                    </div>

                    <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-3 rounded-lg bg-white p-4 border border-[#E4EFE4]">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tujuan Penggunaan</p>
                            <p class="mt-1 text-sm font-medium text-slate-800">{{ $item['purpose'] }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tanggal Diperlukan</p>
                            <p class="mt-1 text-sm font-medium text-slate-800">{{ $item['neededAt'] }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Saldo Kas Kelas Saat Ini</p>
                            <p class="mt-1 text-sm font-semibold text-emerald-700">{{ $item['classBalance'] }}</p>
                        </div>
                        <div class="sm:col-span-3 border-t border-slate-100 pt-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Keterangan / Alasan</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $item['note'] }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <form action="{{ route('teachers.cash-report.reject', $item['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                                <img src="{{ asset('images/redtriangle.png') }}" alt="" class="h-3.5 w-3.5">
                                Tolak Pengajuan
                            </button>
                        </form>
                        <form action="{{ route('teachers.cash-report.approve', $item['id']) }}" method="POST" onsubmit="return confirm('Konfirmasi pencairan dana kas kelas ini?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                                Setujui &amp; Cairkan
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-[#DDE7DD] py-10 text-center">
                    <img src="{{ asset('images/infogreen.png') }}" alt="" class="mx-auto mb-2 h-8 w-8 opacity-60">
                    <p class="text-sm font-medium text-slate-600">Tidak ada pengajuan yang menunggu validasi</p>
                    <p class="mt-1 text-xs text-slate-400">Saat pengurus kelas mengajukan penarikan dana, rincian pengajuan akan langsung muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if (isset($history) && count($history) > 0)
        <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-800">Riwayat Penarikan Dana Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-[#E4EFE4] text-[11px] uppercase tracking-wide text-slate-400">
                            <th class="py-3 pr-4 font-semibold">Waktu Proses</th>
                            <th class="py-3 pr-4 font-semibold">Kelas</th>
                            <th class="py-3 pr-4 font-semibold">Tujuan</th>
                            <th class="py-3 pr-4 font-semibold">Nominal</th>
                            <th class="py-3 pr-4 font-semibold">Status</th>
                            <th class="py-3 pl-4 text-right font-semibold">Eksekutor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F2F6F1]">
                        @foreach ($history as $row)
                            <tr>
                                <td class="py-3.5 pr-4 text-slate-500">{{ $row['date'] }}</td>
                                <td class="py-3.5 pr-4 font-medium text-slate-800">{{ $row['class'] }}</td>
                                <td class="py-3.5 pr-4 text-slate-600">{{ $row['purpose'] }}</td>
                                <td class="py-3.5 pr-4 font-semibold text-slate-800">{{ $row['amount'] }}</td>
                                <td class="py-3.5 pr-4">
                                    @if ($row['status'] === 'approved')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 pl-4 text-right text-slate-500">{{ $row['approver'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection

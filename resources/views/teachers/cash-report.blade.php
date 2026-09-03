@extends(('Layouts.app-admin'))

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

    <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
        <h2 class="text-base font-semibold text-slate-800">Pengajuan Menunggu Validasi</h2>
        <p class="mb-5 text-sm text-slate-400">Konfirmasi setelah pengurus kelas mengambil dana secara fisik</p>

        <div class="flex flex-col gap-4">
            @forelse ($pending as $item)
                <div class="rounded-xl border border-[#E4EFE4] p-5">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800">{{ $item['class'] }}</h3>
                            <p class="text-xs text-slate-400">Diajukan {{ $item['submittedAt'] }}</p>
                        </div>
                        <span class="text-lg font-bold text-red-600">{{ $item['amount'] }}</span>
                    </div>

                    <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tujuan Penggunaan</p>
                            <p class="mt-1 text-sm font-medium text-slate-700">{{ $item['purpose'] }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tanggal Diperlukan</p>
                            <p class="mt-1 text-sm font-medium text-slate-700">{{ $item['neededAt'] }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Keterangan</p>
                            <p class="mt-1 text-sm font-medium text-slate-700">{{ $item['note'] }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <form action="{{ route('teachers.cash-report.reject', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                                <x-nav-icon name="x" class="h-4 w-4 text-red-500" />
                                Tolak
                            </button>
                        </form>
                        <form action="{{ route('teachers.cash-report.approve', $item['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                <x-nav-icon name="check" class="h-4 w-4" />
                                Setujui &amp; Cairkan
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="py-8 text-center text-sm text-slate-400">Tidak ada pengajuan yang menunggu validasi.</p>
            @endforelse
        </div>
    </div>

@endsection

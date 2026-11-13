<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPass – Green Rewards</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-[#eef2ec] text-gray-800">

<div class="max-w-5xl mx-auto px-4 py-8" x-data="{ activeTab: 'Semua' }">

    {{-- Back Arrow --}}
    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center mb-6">
        <img src="{{ asset('images/backgreen.png') }}" alt="Kembali ke Dashboard" class="h-8 w-8">
    </a>

    {{-- Card wrapper --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">EcoPass</h1>
            <p class="text-sm text-slate-500 mt-0.5">Tukarkan poin kelas dengan reward ramah lingkungan dari sekolah</p>
        </div>

        {{-- Points + Tip row --}}
        @php $remaining = max(0, 1200 - $points); @endphp
        <div class="flex flex-col sm:flex-row gap-4 items-start mb-6">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-0.5">Poin Kelas Tersedia</p>
                <p class="text-5xl font-extrabold text-slate-900 leading-none">
                    {{ number_format($points) }} <span class="text-2xl font-semibold">pts</span>
                </p>
                <p class="text-xs font-semibold text-emerald-600 mt-1">Peringkat 1 bulan ini</p>
            </div>

            <div class="flex-1 rounded-xl bg-[#f0f7f0] border border-emerald-100 px-5 py-4 text-sm text-slate-700 leading-snug">
                @if ($remaining > 0)
                    Tinggal <span class="font-bold text-emerald-700">{{ number_format($remaining) }} pts</span>
                    lagi untuk menukar Piagam Penghargaan Lingkungan. Terus setor sampah!
                @else
                    Selamat! Poin kelas Anda sudah cukup untuk menukar
                    <span class="font-bold text-emerald-700">Piagam Penghargaan Lingkungan</span>. Tukar sekarang!
                @endif
            </div>
        </div>

        {{-- Category Tabs --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach (['Semua', 'Tanaman', 'Fasilitas', 'Voucher', 'Penghargaan'] as $tab)
                <button
                    @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}'
                        ? 'bg-emerald-700 text-white border-emerald-700'
                        : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-400 hover:text-emerald-700'"
                    class="rounded-full border px-4 py-1.5 text-sm font-medium transition">
                    {{ $tab }}
                </button>
            @endforeach
        </div>

        {{-- Progress milestone bar --}}
        <div class="relative mb-8 px-2">
            <div class="h-0.5 bg-slate-900 w-full rounded-full"></div>
            @php $positions = [14, 38, 62, 88]; $milestones = [300, 600, 400, 1200]; @endphp
            @foreach ($milestones as $i => $m)
                <div class="absolute top-1/2 -translate-y-1/2" style="left: {{ $positions[$i] }}%">
                    <div class="h-4 w-4 rounded-full border-2 -translate-x-1/2
                        {{ $points >= $m ? 'bg-emerald-700 border-emerald-700' : 'bg-white border-slate-900' }}">
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reward Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Card 1: Tanaman --}}
            <div x-show="activeTab === 'Semua' || activeTab === 'Tanaman'"
                 class="rounded-xl border border-slate-100 overflow-hidden flex flex-col">
                <div class="bg-[#eef6ee] flex items-center justify-center py-8">
                    <img src="{{ asset('images/gicon1.png') }}" alt="Tanaman" class="h-14 w-14 object-contain">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="inline-block rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 mb-2 w-fit">Tanaman</span>
                    <p class="font-bold text-slate-900 text-sm mb-1">Bibit Tanaman Sekolah</p>
                    <p class="text-xs text-slate-500 flex-1">Paket 10 bibit (cabai, tomat, atau pohon peneduh) untuk kebun kelas.</p>
                    <div class="flex items-center justify-between mt-3 text-xs">
                        <span class="font-bold text-slate-900 text-sm">300 pts</span>
                        <span class="{{ $points >= 300 ? 'text-emerald-600' : 'text-slate-400' }} font-medium">
                            {{ $points >= 300 ? 'Cukup' : 'Kurang '.(300 - $points).' pts' }}
                        </span>
                    </div>
                    <button class="mt-3 w-full rounded-lg py-2 text-sm font-semibold transition
                        {{ $points >= 300 ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                        {{ $points < 300 ? 'disabled' : '' }}>
                        {{ $points >= 300 ? 'Tukar Sekarang' : 'Poin Belum Cukup' }}
                    </button>
                </div>
            </div>

            {{-- Card 2: Fasilitas --}}
            <div x-show="activeTab === 'Semua' || activeTab === 'Fasilitas'"
                 class="rounded-xl border border-slate-100 overflow-hidden flex flex-col">
                <div class="bg-[#eef6ee] flex items-center justify-center py-8">
                    <img src="{{ asset('images/gicon2.png') }}" alt="Fasilitas" class="h-14 w-14 object-contain">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="inline-block rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 mb-2 w-fit">Fasilitas</span>
                    <p class="font-bold text-slate-900 text-sm mb-1">Tempat Sampah Daur Ulang</p>
                    <p class="text-xs text-slate-500 flex-1">Satu set tempat sampah terpilah 3 warna untuk ruang kelas.</p>
                    <div class="flex items-center justify-between mt-3 text-xs">
                        <span class="font-bold text-slate-900 text-sm">600 pts</span>
                        <span class="{{ $points >= 600 ? 'text-emerald-600' : 'text-slate-400' }} font-medium">
                            {{ $points >= 600 ? 'Cukup' : 'Kurang '.(600 - $points).' pts' }}
                        </span>
                    </div>
                    <button class="mt-3 w-full rounded-lg py-2 text-sm font-semibold transition
                        {{ $points >= 600 ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                        {{ $points < 600 ? 'disabled' : '' }}>
                        {{ $points >= 600 ? 'Tukar Sekarang' : 'Poin Belum Cukup' }}
                    </button>
                </div>
            </div>

            {{-- Card 3: Voucher --}}
            <div x-show="activeTab === 'Semua' || activeTab === 'Voucher'"
                 class="rounded-xl border border-slate-100 overflow-hidden flex flex-col">
                <div class="bg-[#eef6ee] flex items-center justify-center py-8">
                    <img src="{{ asset('images/gicon3.png') }}" alt="Voucher" class="h-14 w-14 object-contain">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="inline-block rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 mb-2 w-fit">Voucher</span>
                    <p class="font-bold text-slate-900 text-sm mb-1">Voucher Kantin</p>
                    <p class="text-xs text-slate-500 flex-1">Voucher jajan Rp100.000 untuk acara kelas, berlaku di kantin sekolah.</p>
                    <div class="flex items-center justify-between mt-3 text-xs">
                        <span class="font-bold text-slate-900 text-sm">400 pts</span>
                        <span class="{{ $points >= 400 ? 'text-emerald-600' : 'text-slate-400' }} font-medium">
                            {{ $points >= 400 ? 'Cukup' : 'Kurang '.(400 - $points).' pts' }}
                        </span>
                    </div>
                    <button class="mt-3 w-full rounded-lg py-2 text-sm font-semibold transition
                        {{ $points >= 400 ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                        {{ $points < 400 ? 'disabled' : '' }}>
                        {{ $points >= 400 ? 'Tukar Sekarang' : 'Poin Belum Cukup' }}
                    </button>
                </div>
            </div>

            {{-- Card 4: Penghargaan --}}
            <div x-show="activeTab === 'Semua' || activeTab === 'Penghargaan'"
                 class="rounded-xl border border-slate-100 overflow-hidden flex flex-col">
                <div class="bg-[#f5f5f5] flex items-center justify-center py-8">
                    <img src="{{ asset('images/gicon4.png') }}" alt="Penghargaan" class="h-14 w-14 object-contain">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="inline-block rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold px-2 py-0.5 mb-2 w-fit">Penghargaan</span>
                    <p class="font-bold text-slate-900 text-sm mb-1">Piagam Penghargaan Lingkungan</p>
                    <p class="text-xs text-slate-500 flex-1">Piagam resmi dari sekolah untuk kelas.</p>
                    <div class="flex items-center justify-between mt-3 text-xs">
                        <span class="font-bold text-slate-900 text-sm">1200 pts</span>
                        <span class="{{ $points >= 1200 ? 'text-emerald-600' : 'text-slate-400' }} font-medium">
                            {{ $points >= 1200 ? 'Cukup' : 'Kurang '.(1200 - $points).' pts' }}
                        </span>
                    </div>
                    <button class="mt-3 w-full rounded-lg py-2 text-sm font-semibold transition
                        {{ $points >= 1200 ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                        {{ $points < 1200 ? 'disabled' : '' }}>
                        {{ $points >= 1200 ? 'Tukar Sekarang' : 'Poin Belum Cukup' }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>

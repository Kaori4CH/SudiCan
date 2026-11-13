<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SudiCan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen bg-[#eef2ec] text-gray-800">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white border-r border-[#E4EFE4] flex flex-col justify-between p-5 sticky top-0 h-screen overflow-y-auto shrink-0">
            <div>
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 mb-6 px-1">
                    <img src="{{ asset('images/logo.png') }}" alt="SudiCan Logo" class="w-full h-auto object-contain">
                </a>

                @auth
                    <div class="mb-5 flex items-center gap-3 rounded-xl bg-[#F4FAF5] px-3 py-2.5 border border-emerald-100">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs uppercase shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1 leading-tight">
                            <p class="truncate text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="truncate text-[11px] text-emerald-700 font-medium">
                                {{ auth()->user()->student?->class ?? (auth()->user()->classroom?->name ?? 'Siswa') }}
                                @if (auth()->user()->student?->nis)
                                    · {{ auth()->user()->student->nis }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endauth

                <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-widest text-slate-400">Menu Siswa</p>
                <nav class="space-y-1">
                    <a href="{{ route('student.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('student.dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <img src="{{ asset('images/dashboardicon.png') }}" alt="" class="w-4 h-4"> Dashboard
                    </a>
                    <a href="{{ route('student.deposit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('student.deposit') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <img src="{{ asset('images/wasteicon.png') }}" alt="" class="w-4 h-4"> Waste Deposit
                    </a>
                    <a href="{{ route('student.cash-report') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('student.cash-report') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <img src="{{ asset('images/reporticon.png') }}" alt="" class="w-4 h-4"> Cash Report
                    </a>
                    <a href="{{ route('student.greenrewards') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('student.greenrewards') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <img src="{{ asset('images/rewards.png') }}" alt="" class="w-4 h-4"> Green Rewards
                    </a>
                </nav>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700">
                        <img src="{{ asset('images/logouticon.png') }}" alt="" class="w-4 h-4">
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex flex-1 flex-col min-w-0">
            <header class="flex h-16 items-center justify-between border-b border-[#E4EFE4] bg-white px-8">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Portal Siswa SudiCan
                    </span>
                    @auth
                        <span class="hidden sm:inline-block text-xs text-slate-400">
                            Kelas: <strong class="text-slate-700 font-semibold">{{ auth()->user()->student?->class ?? (auth()->user()->classroom?->name ?? 'Belum terdaftar') }}</strong>
                        </span>
                    @endauth
                </div>
                <div class="flex items-center gap-4">
                    @if (auth()->check() && (auth()->user()->role === 'teacher' || auth()->user()->role === 'admin'))
                        <a href="{{ route('teachers.index') }}"
                           class="flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800">
                            &larr; Panel Guru
                        </a>
                        <span class="text-xs text-slate-300">|</span>
                    @endif
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-slate-600">{{ auth()->user()->name ?? 'Siswa' }}</span>
                    </div>
                    <span class="text-xs text-slate-300">|</span>
                    <form action="{{ route('auth.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100 hover:text-red-700">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </header>

            @if (auth()->check() && (auth()->user()->role === 'teacher' || auth()->user()->role === 'admin'))
                <div class="flex items-center justify-between border-b border-emerald-200 bg-emerald-50 px-8 py-2.5 text-xs text-emerald-800">
                    <span class="flex items-center gap-2 font-medium">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        Mode Pratinjau Siswa — Anda sedang melihat sistem dari sudut pandang siswa (Login sebagai: {{ auth()->user()->name }}).
                    </span>
                    <a href="{{ route('teachers.index') }}" class="flex items-center gap-1 font-semibold text-emerald-700 underline hover:text-emerald-900">
                        &larr; Kembali ke Panel Guru
                    </a>
                </div>
            @endif

            <main class="flex-1 p-8">
                @if (session('error'))
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

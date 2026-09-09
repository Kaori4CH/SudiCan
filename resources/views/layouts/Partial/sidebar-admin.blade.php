@php
    $menuItems = [
        ['label' => 'Dashboard', 'route' => 'teachers.index', 'icon' => 'grid'],
        ['label' => 'Waste Deposit', 'route' => 'teachers.waste-deposit', 'icon' => 'arrow-up'],
    ];
@endphp

<aside class="flex w-72 shrink-0 flex-col justify-between border-r border-[#E4EFE4] bg-white px-5 py-6">

    <div>
        <a href="{{ route('teachers.index') }}" class="mb-8 flex items-center gap-3 px-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c4 1 7 4 7 8a7 7 0 1 1-14 0c0-1.5.5-2.7 1.3-3.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v9" />
                </svg>
            </span>
            <span class="text-lg font-bold text-[#0F3D33]">SudiCan</span>
        </a>

        <div class="mb-6 flex items-center gap-3 rounded-xl bg-[#F4FAF5] px-3 py-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">A</span>
            <div class="leading-tight">
                <p class="text-sm font-semibold text-slate-800">Admin Sekolah</p>
                <p class="text-xs text-slate-500">Full Access &amp; Eksekutor</p>
            </div>
        </div>

        <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-widest text-slate-400">Menu</p>
        <nav class="flex flex-col gap-1">
            @foreach ($menuItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="flex items-center gap-3">
                        <x-nav-icon :name="$item['icon']" class="h-4 w-4 {{ $isActive ? 'text-emerald-700' : 'text-slate-400' }}" />
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach

            @php $cashReportActive = request()->routeIs('teachers.cash-report'); @endphp
            <a href="{{ route('teachers.cash-report') }}"
                class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $cashReportActive ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="flex items-center gap-3">
                    <x-nav-icon name="document" class="h-4 w-4 {{ $cashReportActive ? 'text-emerald-700' : 'text-slate-400' }}" />
                    Cash Report
                </span>
                <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[11px] font-semibold text-white">
                    2
                </span>
            </a>
        </nav>
    </div>

    <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
            <x-nav-icon name="logout" class="h-4 w-4 text-red-500" />
            Log Out
        </button>
    </form>

</aside>

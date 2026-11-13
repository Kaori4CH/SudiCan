@php
    $menuItems = [
        ['label' => 'Dashboard', 'route' => 'teachers.index', 'icon' => 'dashboardicon.png'],
        ['label' => 'Waste Deposit', 'route' => 'teachers.waste-deposit', 'icon' => 'wasteicon.png'],
    ];

    $masterDataItems = [
        ['label' => 'Managemen Sampah', 'route' => 'teachers.waste-prices.index', 'icon' => 'wasteicon.png'],
        ['label' => 'Managemen Akun', 'route' => 'teachers.accounts', 'icon' => 'infogreen.png'],
    ];
@endphp

<aside class="flex w-72 shrink-0 flex-col justify-between border-r border-[#E4EFE4] bg-white px-5 py-6">

    <div>
        <a href="{{ route('teachers.index') }}" class="mb-8 flex items-center gap-3 px-2">
            <img src="{{ asset('images/Logo.png') }}" alt="SudiCan" class="h-full w-full rounded-full object-cover">
        </a>

        <div class="mb-4 flex items-center gap-3 rounded-xl bg-[#F4FAF5] px-3 py-3">
            <img src="{{ asset('images/admin.png') }}"class="h-full w-[30px] rounded-full object-cover">
            <div class="leading-tight">
                <p class="text-sm font-semibold text-slate-800">Admin Sekolah</p>
                <p class="text-xs text-slate-500">Full Access &amp; Eksekutor</p>
            </div>
        </div>

        <div class="mb-6">
            <a href="{{ route('student.dashboard') }}"
               class="flex w-full items-center justify-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-800 transition hover:bg-emerald-100 hover:text-emerald-900 shadow-xs">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Tampilan Siswa &rarr;
            </a>
        </div>

        <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-widest text-slate-400">Menu</p>
        <nav class="flex flex-col gap-1">
            @foreach ($menuItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="flex items-center gap-3">
                        <img src="{{ asset('images/'.$item['icon']) }}" alt="" class="h-4 w-4">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach

            @php
                $cashReportActive = request()->routeIs('teachers.cash-report');
                $pendingApprovalsCount = \App\Models\CashWithdrawal::pending()->count();
            @endphp
            <a href="{{ route('teachers.cash-report') }}"
                class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $cashReportActive ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <span class="flex items-center gap-3">
                    <img src="{{ asset('images/reporticon.png') }}" alt="" class="h-4 w-4">
                    Cash Report
                </span>
                @if ($pendingApprovalsCount > 0)
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[11px] font-semibold text-white">
                        {{ $pendingApprovalsCount }}
                    </span>
                @endif
            </a>
        </nav>

        <p class="mb-2 mt-6 px-2 text-[11px] font-semibold uppercase tracking-widest text-slate-400">Master Data</p>
        <nav class="flex flex-col gap-1">
            @foreach ($masterDataItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <img src="{{ asset('images/'.$item['icon']) }}" alt="" class="h-4 w-4">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
            <img src="{{ asset('images/logouticon.png') }}" alt="" class="h-4 w-4">
            Log Out
        </button>
    </form>

</aside>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - SudiCan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#eef2ec] text-gray-800">
    <div class="flex min-h-screen">
        <aside class="w-60 bg-white border-r border-gray-100 flex flex-col justify-between p-5 sticky top-0 h-screen overflow-y-auto">
            <div>
                <div class="flex items-center gap-2 mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="SudiCan Logo" class="w-8 h-8 rounded-full object-cover">
                    <span class="font-semibold text-lg">SudiCan</span>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('student.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                              {{ request()->routeIs('student.dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <img src="{{ asset('images/dashboardicon.png') }}" alt="" class="w-4 h-4"> Dashboard
                    </a>
                    <a href="{{ Route::has('student.deposit') ? route('student.deposit') : '#' }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                              {{ request()->routeIs('student.deposit') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <img src="{{ asset('images/wasteicon.png') }}" alt="" class="w-4 h-4"> Waste Deposit
                    </a>
                    <a href="{{ Route::has('student.cash-report') ? route('student.cash-report') : '#' }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
                              {{ request()->routeIs('student.cash-report') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <img src="{{ asset('images/reporticon.png') }}" alt="" class="w-4 h-4"> Cash Report
                    </a>
                </nav>
            </div>

            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm text-red-500 px-3 py-2">
                    <img src="{{ asset('images/logouticon.png') }}" alt="" class="w-4 h-4"> Log Out
                </button>
            </form>
        </aside>

        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
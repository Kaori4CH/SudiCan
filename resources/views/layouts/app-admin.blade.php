<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SudiCan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4FAF5] text-slate-700">

    <div class="flex min-h-screen">

        @include('layouts.Partial.sidebar-admin')

        <div class="flex flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b border-[#E4EFE4] bg-white px-10">
                <div></div>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-slate-300">|</span>
                    <span class="text-xs font-medium text-slate-600">{{ auth()->user()->name ?? 'Admin Sekolah' }}</span>
                    <span class="text-xs text-slate-300">|</span>
                    <form action="{{ route('auth.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-100 hover:text-red-700">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 px-10 py-8">
                @yield('content')
            </main>
        </div>

    </div>

</body>

</html>

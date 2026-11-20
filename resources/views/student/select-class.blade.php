<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Kelas - SudiCan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#EEF2EC] text-slate-800 flex flex-col justify-between">

    {{-- Header Bar --}}
    <header class="bg-[#243325] px-6 sm:px-10 py-4 flex items-center justify-between text-white shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold tracking-wide">Pemilihan Kelas</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-emerald-200 font-medium">{{ auth()->user()->name }}</span>
            <form action="{{ route('auth.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs font-semibold text-red-300 hover:text-red-200 transition">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8 sm:p-10"
             x-data="{ selectedClass: '{{ old('class', $currentClass) }}' }">

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Selamat Datang Di SudiCan
            </h1>
            <p class="mt-2 text-sm font-semibold text-slate-700">
                Pengurus Kelas Manakah Kamu?
            </p>

            @if ($errors->any())
                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('student.select-class.store') }}" method="POST" class="mt-6">
                @csrf

                {{-- Hidden Input for Selected Class --}}
                <input type="hidden" name="class" :value="selectedClass" required>

                {{-- Class Button Grid (3 Rows x 5 Cols) --}}
                <div class="space-y-2.5">
                    @foreach ($classes as $row)
                        <div class="grid grid-cols-5 gap-2 sm:gap-2.5">
                            @foreach ($row as $cls)
                                <button type="button"
                                        @click="selectedClass = '{{ $cls }}'"
                                        :class="selectedClass === '{{ $cls }}'
                                            ? 'bg-[#43634B] text-white shadow-xs'
                                            : 'bg-[#E9EFE9] text-slate-800 hover:bg-[#dce6dc]'"
                                        class="rounded-lg py-2 px-1 text-center text-xs sm:text-sm font-semibold transition cursor-pointer select-none">
                                    {{ $cls }}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- Inputs --}}
                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-800 mb-1.5">Nama Lengkap</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               placeholder="Nama lengkap pengurus kelas"
                               class="w-full max-w-sm rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-800 mb-1.5">NIS</label>
                        <input type="text"
                               name="nis"
                               value="{{ old('nis', $user->student?->nis ?? '') }}"
                               required
                               placeholder="Nomor Induk Siswa"
                               class="w-full max-w-sm rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="rounded-lg bg-[#43634B] hover:bg-[#35503b] px-8 py-2.5 text-sm font-semibold text-white shadow-sm transition cursor-pointer">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} SudiCan · Sistem Pengelolaan Sampah &amp; Kas Sekolah
    </footer>

</body>
</html>

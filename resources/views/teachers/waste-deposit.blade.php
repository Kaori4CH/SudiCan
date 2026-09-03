@extends(('Layouts.app-admin'))

@section('title', $title)

@section('content')

    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Waste Deposit — Input Transaksi</h1>
            <p class="mt-1 text-sm text-slate-500">Catat setoran sampah dari kelas penyetor secara langsung</p>
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

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <h2 class="mb-5 text-base font-semibold text-slate-800">Form Setoran Baru</h2>

        <form action="{{ route('teachers.waste-deposit.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Kelas Penyetor</label>
                <select name="class" class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    @foreach ($classes as $class)
                        <option value="{{ $class }}" @selected($class === $selectedClass)>{{ $class }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Sampah</label>
                <select name="waste_type" class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    @foreach ($wasteTypes as $waste)
                        <option value="{{ $waste['name'] }}" @selected($waste['name'] === $selectedWaste)>
                            {{ $waste['name'] }} — Rp{{ number_format($waste['price'], 0, ',', '.') }}/kg
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Berat (kg)</label>
                <input type="number" step="0.1" min="0.1" name="weight" value="{{ $weight }}"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label>
                <textarea name="note" rows="3" placeholder="Contoh: kondisi sampah, keterangan tambahan..."
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
            </div>

            <div class="rounded-xl bg-emerald-50 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700/70">Nilai Rupiah</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $previewRupiah }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700/70">Poin Kas Kelas</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $previewPoints }}</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                <x-nav-icon name="check" class="h-4 w-4" />
                Simpan Setoran
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Riwayat Setoran</h2>
                <p class="text-sm text-slate-400">{{ count($history) }} setoran tercatat</p>
            </div>
            <select class="rounded-lg border border-[#DDE7DD] bg-white px-3 py-2 text-sm text-slate-600 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                <option>Semua Kelas</option>
                @foreach ($classes as $class)
                    <option>{{ $class }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E4EFE4] text-[11px] uppercase tracking-wide text-slate-400">
                        <th class="py-3 pr-4 font-semibold">Waktu</th>
                        <th class="py-3 pr-4 font-semibold">Kelas</th>
                        <th class="py-3 pr-4 font-semibold">Jenis</th>
                        <th class="py-3 pr-4 font-semibold">Berat</th>
                        <th class="py-3 pr-4 text-right font-semibold">Nilai</th>
                        <th class="py-3 pl-4 text-right font-semibold">Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $row)
                        <tr class="border-b border-[#F2F6F1] last:border-0">
                            <td class="py-3.5 pr-4 text-slate-500">{{ $row['time'] }}</td>
                            <td class="py-3.5 pr-4 font-medium text-slate-800">{{ $row['class'] }}</td>
                            <td class="py-3.5 pr-4 text-slate-600">{{ $row['type'] }}</td>
                            <td class="py-3.5 pr-4 text-slate-600">{{ $row['weight'] }}</td>
                            <td class="py-3.5 pr-4 text-right font-semibold text-emerald-600">{{ $row['amount'] }}</td>
                            <td class="py-3.5 pl-4 text-right text-slate-500">{{ $row['points'] }} pts</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

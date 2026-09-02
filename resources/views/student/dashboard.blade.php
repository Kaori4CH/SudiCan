@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')

    @php
        $wasteTypes = $wasteTypes ?? [
            ['label' => 'Botol Plastik', 'price' => 3000],
            ['label' => 'Kertas Karton', 'price' => 2000],
            ['label' => 'Kaleng Logam', 'price' => 4000],
        ];
        $wastePrices = collect($wasteTypes)->pluck('price');
        $trendLabelsData = $trendLabels ?? ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $trendValuesData = $trendData ?? [0, 0, 0, 0];
    @endphp

    <h1 class="text-2xl font-bold">Halo, {{ auth()->user()->name ?? 'Pengurus Kelas' }}</h1>
    <p class="text-sm text-gray-500 mb-6">{{ now()->translatedFormat('l, d F Y') }} . Semester Ganjil</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Sampah Terkumpul</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($totalSampah, 0, ',', '.') }} kg</p>
            <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> 12% minggu ini
            </p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Kas Terkumpul</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalKas, 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                <img src="{{ asset('images/greentriangle.png') }}" alt="" class="w-2.5 h-2.5"> 12% minggu ini
            </p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Kelas Teraktif</p>
            <p class="text-2xl font-bold mt-1">{{ $activeClass ?? 'XII TKJ 1' }}</p>
            <p class="text-xs text-gray-400 mt-1">Peringkat 1 Green Class</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm" x-data="calculator()">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold">Kalkulator Estimasi</h2>
                <span class="text-xs text-gray-400">Input otomatis konversi ke Rp & poin</span>
            </div>

            <label class="text-xs text-gray-500">Jenis Sampah</label>
            <select x-model="selected" class="w-full mt-1 mb-4 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                @foreach($wasteTypes as $i => $type)
                    <option value="{{ $i }}" data-price="{{ $type['price'] }}">
                        {{ $type['label'] }} — Rp {{ number_format($type['price'], 0, ',', '.') }}/kg
                    </option>
                @endforeach
            </select>

            <label class="text-xs text-gray-500">Berat (kg)</label>
            <input type="number" min="0" step="0.1" x-model.number="weight"
                   class="w-full mt-1 mb-4 border border-gray-200 rounded-lg px-3 py-2 text-sm">

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-400">NILAI RUPIAH</p>
                    <p class="text-lg font-bold text-orange-500" x-text="'Rp ' + rupiah.toLocaleString('id-ID')"></p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-400">POIN KAS KELAS</p>
                    <p class="text-lg font-bold text-green-600" x-text="poin + ' pts'"></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <h2 class="font-semibold">Green Class - Ranking Minggu Ini</h2>
            <p class="text-xs text-gray-400 mb-3">Diperbarui otomatis setiap setoran masuk</p>
            <ul class="divide-y divide-gray-100 text-sm">
                @forelse($rankings as $i => $rank)
                    <li class="flex justify-between py-2">
                        <span class="font-medium">#{{ $i + 1 }} {{ $rank['class'] }}</span>
                        <span>{{ $rank['points'] }} pts</span>
                    </li>
                @empty
                    <li class="py-2 text-gray-400 text-center">Belum ada data ranking</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-semibold">Setoran Terbaru</h2>
                <span class="text-xs text-gray-400">{{ count($recentDeposits) }} setoran terakhir</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400">
                        <th class="pb-2">Tanggal</th>
                        <th class="pb-2">Kelas</th>
                        <th class="pb-2">Jenis</th>
                        <th class="pb-2">Berat</th>
                        <th class="pb-2">Nilai</th>
                        <th class="pb-2">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentDeposits as $d)
                        <tr>
                            <td class="py-2">{{ $d['date'] }}</td>
                            <td class="py-2 font-medium">{{ $d['class'] }}</td>
                            <td class="py-2">{{ $d['type'] }}</td>
                            <td class="py-2">{{ $d['weight'] }} kg</td>
                            <td class="py-2">Rp {{ number_format($d['value'], 0, ',', '.') }}</td>
                            <td class="py-2">{{ $d['points'] }} pts</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-4 text-center text-gray-400">Belum ada setoran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-semibold">Tren 4 Minggu Terakhir</h2>
                <span class="text-xs text-gray-400">Total kg sampah</span>
            </div>
            <canvas id="trendChart" height="160"></canvas>
        </div>
    </div>

    <script>
        function calculator() {
            const prices = @json($wastePrices);
            return {
                selected: 0,
                weight: 1,
                get rupiah() { return Math.round((prices[this.selected] || 0) * this.weight); },
                get poin() { return Math.round(this.rupiah / 300); } 
            }
        }

        const trendLabels = @json($trendLabelsData);
        const trendData = @json($trendValuesData);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    data: trendData,
                    borderColor: '#4b7c4b',
                    backgroundColor: 'rgba(75,124,75,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { display: false }, x: { grid: { display: false } } }
            }
        });
    </script>
@endsection
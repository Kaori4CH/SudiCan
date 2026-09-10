@extends('layouts.app-admin')

@section('title', $title)

@section('content')

    <a href="{{ route('teachers.index') }}" class="mb-5 inline-flex items-center gap-2 rounded-lg border border-[#DDE7DD] bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
        <img src="{{ asset('images/kembali.png') }}" alt="" class="h-4 w-4">
        Kembali
    </a>

    <h1 class="text-2xl font-bold text-slate-800">Manajemen Harga Sampah</h1>
    <p class="mt-1 text-sm text-slate-500">Pengaturan data harga sampah</p>

    @if (session('success'))
        <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-800">Harga Sampah</h2>
            <a href="{{ route('teachers.waste-prices.create') }}" class="flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700">
                <img src="{{ asset('images/plusicon.png') }}" alt="" class="h-3 w-3">
                Tambah Jenis Sampah
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E4EFE4] bg-slate-50 text-[11px] uppercase tracking-wide text-slate-400">
                        <th class="px-4 py-3 font-semibold">Waktu Terakhir di Edit</th>
                        <th class="px-4 py-3 font-semibold">Jenis Sampah</th>
                        <th class="px-4 py-3 text-right font-semibold">Saldo/kg</th>
                        <th class="px-4 py-3 text-right font-semibold">Command</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prices as $item)
                        <tr class="border-b border-[#F2F6F1] last:border-0">
                            <td class="px-4 py-3.5 text-slate-500">{{ $item['updatedAt'] }}</td>
                            <td class="px-4 py-3.5 font-medium text-slate-800">{{ $item['name'] }}</td>
                            <td class="px-4 py-3.5 text-right font-semibold text-slate-800">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('teachers.waste-prices.edit', $item['id']) }}" class="font-medium text-emerald-700 hover:text-emerald-800">Edit</a>
                                    <form action="{{ route('teachers.waste-prices.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Yakin hapus jenis sampah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-700">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

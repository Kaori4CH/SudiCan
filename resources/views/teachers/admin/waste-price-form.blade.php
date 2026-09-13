@extends('layouts.app-admin')

@section('title', $title)

@section('content')

    <a href="{{ route('teachers.waste-prices.index') }}" class="mb-5 inline-flex items-center gap-2 rounded-lg border border-[#DDE7DD] bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
        <img src="{{ asset('images/kembali.png') }}" alt="" class="h-4 w-4">
        Kembali
    </a>

    <h1 class="text-2xl font-bold text-slate-800">{{ $item ? 'Edit Jenis Sampah' : 'Tambah Jenis Sampah' }}</h1>
    <p class="mt-1 text-sm text-slate-500">Nama item, satuan, dan harga dasar per satuan</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <form action="{{ $formAction }}" method="POST" class="space-y-5">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Item</label>
                <input type="text" name="name" value="{{ old('name', $item['name'] ?? '') }}" placeholder="Contoh: Botol Plastik"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', $item['unit'] ?? 'kg') }}" placeholder="Contoh: kg"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Harga Dasar (Rp)</label>
                <input type="number" min="0" step="100" name="price" value="{{ old('price', $item['price'] ?? '') }}" placeholder="Contoh: 8500"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('teachers.waste-prices.index') }}" class="flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

@endsection


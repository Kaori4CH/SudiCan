@extends('layouts.app-admin')

@section('title', $title)

@section('content')

    <div class="mb-6">
        <a href="{{ route('teachers.index') }}" class="mb-2 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700">
            <img src="{{ asset('images/kembali.png') }}" alt="" class="h-3.5 w-3.5"> Kembali ke Dashboard
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Tambah Kelas</h1>
        <p class="mt-1 text-sm text-slate-500">Daftarkan kelas baru ke dalam sistem SudiCan</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
        <form action="{{ route('teachers.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: XII TKJ 1"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Wali Kelas</label>
                <input type="text" name="homeroom_teacher" value="{{ old('homeroom_teacher') }}" placeholder="Contoh: Bu Sari Wulandari"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Siswa</label>
                <input type="number" min="1" name="members" value="{{ old('members') }}" placeholder="Contoh: 32"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('teachers.index') }}" class="flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>

@endsection


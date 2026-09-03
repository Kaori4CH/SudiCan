@extends(('Layouts.app-admin'))

@section('title', $title)

@section('content')

    <div class="mb-6">
        <a href="{{ route('teachers.show', $class['id']) }}" class="mb-2 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700">
            &larr; Kembali ke Detail Kelas
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Edit Kelas — {{ $class['name'] }}</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui data kelas ini</p>
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

    <div class="mb-6 rounded-xl border border-[#E4EFE4] bg-white p-6">
        <form action="{{ route('teachers.update', $class['id']) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name', $class['name']) }}"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Wali Kelas</label>
                <input type="text" name="homeroom_teacher" value="{{ old('homeroom_teacher', $class['homeroom_teacher']) }}"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Siswa</label>
                <input type="number" min="1" name="members" value="{{ old('members', $class['members']) }}"
                    class="w-full rounded-lg border border-[#DDE7DD] bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('teachers.show', $class['id']) }}" class="flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    <x-nav-icon name="check" class="h-4 w-4" />
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-red-200 bg-red-50 p-6">
        <h2 class="text-sm font-semibold text-red-700">Hapus Kelas</h2>
        <p class="mb-4 mt-1 text-sm text-red-600">Tindakan ini tidak dapat dibatalkan. Semua data setoran &amp; kas kelas ini akan ikut hilang.</p>
        <form action="{{ route('teachers.destroy', $class['id']) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100">
                <x-nav-icon name="x" class="h-4 w-4" />
                Hapus Kelas Ini
            </button>
        </form>
    </div>

@endsection

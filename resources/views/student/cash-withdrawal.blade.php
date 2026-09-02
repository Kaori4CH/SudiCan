@extends('layouts.student')

@section('title', 'Pengajuan Penarikan Dana')

@section('content')

    @php
        $formatRp = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    <a href="{{ route('student.cash-report') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 mb-4">
        <img src="{{ asset('images/kembali.png') }}" alt="" class="w-3.5 h-3.5"> Kembali
    </a>

    <h1 class="text-2xl font-bold">Pengajuan Penarikan Dana</h1>
    <p class="text-sm text-gray-500 mb-6">Ajukan penarikan dana dari saldo kas kelas yang tersedia.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/wallet.png') }}" alt="" class="w-5 h-5">
            </div>
            <div>
                <p class="text-xs text-gray-400">Saldo Tersedia</p>
                <p class="text-2xl font-bold mt-1">{{ $formatRp($saldoTersedia) }}</p>
                <p class="text-xs text-gray-400 mt-1">Per {{ $saldoDate }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center shrink-0 mt-0.5">
                <img src="{{ asset('images/infogreen.png') }}" alt="" class="w-3.5 h-3.5">
            </div>
            <div>
                <p class="text-sm font-semibold">Informasi</p>
                <p class="text-sm text-gray-500">Penarikan dana hanya dapat dilakukan oleh bendahara kelas atau guru wali kelas.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm" x-data="{ notes: '' }">
            <h2 class="font-semibold mb-5">Form Pengajuan Penarikan Dana</h2>

            <form method="POST" action="{{ Route::has('student.cash-withdrawal.store') ? route('student.cash-withdrawal.store') : '#' }}">
                @csrf

                <label class="text-sm font-medium block mb-1">Jumlah Penarikan</label>
                <div class="flex items-stretch mb-1">
                    <span class="inline-flex items-center px-3 border border-r-0 border-gray-200 rounded-l-lg bg-gray-50 text-sm text-gray-500">Rp</span>
                    <input type="number" name="amount" placeholder="Masukkan jumlah penarikan"
                           class="w-full border border-gray-200 rounded-r-lg px-3 py-2 text-sm">
                </div>
                <p class="text-xs text-gray-400 mb-4">Minimal Rp 10.000</p>

                <label class="text-sm font-medium block mb-1">Tujuan Penggunaan Dana</label>
                <div class="relative mb-4">
                    <select name="purpose" class="w-full appearance-none border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-500">
                        <option value="">Pilih tujuan penggunaan dana</option>
                        <option value="alat_kebersihan">Pembelian Alat Kebersihan</option>
                        <option value="bahan_kegiatan">Pembelian Bahan Kegiatan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <img src="{{ asset('images/down.png') }}" alt="" class="w-3 h-3 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                </div>

                <label class="text-sm font-medium block mb-1">Deskripsi / Keterangan (Opsional)</label>
                <textarea name="description" rows="3" maxlength="200" x-model="notes"
                          placeholder="Jelaskan secara singkat penggunaan dana"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-1"></textarea>
                <p class="text-xs text-gray-400 text-right mb-4" x-text="notes.length + ' / 200'"></p>

                <label class="text-sm font-medium block mb-1">Tanggal Diperlukan</label>
                <div class="relative mb-6 max-w-xs">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2"><img src="{{ asset('images/calendar.png') }}" alt="" class="w-4 h-4"></span>
                    <select name="needed_date" class="w-full appearance-none border border-gray-200 rounded-lg pl-9 pr-8 py-2 text-sm text-gray-500">
                        <option value="">Pilih tanggal</option>
                    </select>
                    <img src="{{ asset('images/down.png') }}" alt="" class="w-3 h-3 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('student.cash-report') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 bg-green-800 hover:bg-green-900 text-white text-sm font-medium px-4 py-2 rounded-lg">
                        <img src="{{ asset('images/kirim.png') }}" alt="" class="w-3.5 h-3.5"> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <h2 class="font-semibold mb-4">Syarat &amp; Ketentuan</h2>

            <ul class="space-y-4 mb-4">
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/centang.png') }}" alt="" class="w-3.5 h-3.5">
                    </div>
                    <div>
                        <p class="text-sm font-medium">Pastikan jumlah saldo mencukupi</p>
                        <p class="text-xs text-gray-500">Jumlah penarikan tidak boleh melebihi saldo tersedia.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/spiral.png') }}" alt="" class="w-3.5 h-3.5">
                    </div>
                    <div>
                        <p class="text-sm font-medium">Gunakan dana sesuai tujuan</p>
                        <p class="text-xs text-gray-500">Pilih tujuan penggunaan dana dengan jelas dan sesuai kebutuhan kelas.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/klip.png') }}" alt="" class="w-3.5 h-3.5">
                    </div>
                    <div>
                        <p class="text-sm font-medium">Lampirkan bukti pendukung (jika ada)</p>
                        <p class="text-xs text-gray-500">Lampiran bersifat opsional namun disarankan untuk memperjelas pengajuan.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/clock.png') }}" alt="" class="w-3.5 h-3.5">
                    </div>
                    <div>
                        <p class="text-sm font-medium">Proses verifikasi</p>
                        <p class="text-xs text-gray-500">Pengajuan akan diverifikasi oleh guru wali kelas. Mohon tunggu konfirmasi melalui sistem.</p>
                    </div>
                </li>
            </ul>

            <div class="bg-gray-50 rounded-lg p-4 flex items-start gap-3">
                <img src="{{ asset('images/infogreen.png') }}" alt="" class="w-5 h-5 mt-0.5 shrink-0">
                <div>
                    <p class="text-sm font-medium">Catatan</p>
                    <p class="text-xs text-gray-500">Setelah pengajuan dikirim, Anda tidak dapat mengubah data. Pastikan semua informasi sudah benar.</p>
                </div>
            </div>
        </div>
    </div>

@endsection
@extends('Layouts.app-admin')

@section('title', $title)

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Leaderboard Kelas</h1>
        <p class="mt-1 text-sm text-slate-500">Peringkat kelas berdasarkan poin kas terkumpul</p>
    </div>

    <div class="rounded-xl border border-[#E4EFE4] bg-white p-6">
        <div class="flex flex-col divide-y divide-[#EFF4EE]">
            @foreach ($classes as $class)
                <div class="flex items-center justify-between py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold {{ $class['rank'] === 1 ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700' }}">
                            {{ $class['rank'] }}
                        </span>
                        <span class="text-sm font-medium text-slate-700">{{ $class['name'] }}</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-800">{{ $class['points'] }} <span class="font-normal text-slate-400">pts</span></span>
                </div>
            @endforeach
        </div>
    </div>

@endsection

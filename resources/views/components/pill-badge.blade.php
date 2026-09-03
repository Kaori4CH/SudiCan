@props(['status' => 'Setoran'])

@php
    $styles = match ($status) {
        'Setoran' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Penarikan' => 'bg-red-50 text-red-600 border-red-200',
        'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        default => 'bg-slate-100 text-slate-600 border-slate-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-block rounded-md border px-2.5 py-1 text-xs font-semibold $styles"]) }}>
    {{ $status }}
</span>

@props(['name'])

@php
    $paths = [
        'grid' => '<rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect>',
        'arrow-up' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5"></path><path stroke-linecap="round" stroke-linejoin="round" d="M6 11l6-6 6 6"></path>',
        'document' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6M9 17h6M14 3v5h5"></path>',
        'star' => '<path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.6 5.6 6.1.6-4.6 4.1 1.3 6-5.4-3.2L6.6 19.3l1.3-6-4.6-4.1 6.1-.6L12 3Z"></path>',
        'logout' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4"></path><path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5"></path><path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9"></path>',
        'trend-up' => '<path stroke-linecap="round" stroke-linejoin="round" d="m4 15 6-6 4 4 6-7"></path><path stroke-linecap="round" stroke-linejoin="round" d="M14 6h6v6"></path>',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"></path>',
        'x' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"></path>',
    ];
    $path = $paths[$name] ?? '';
@endphp

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
    {{ $attributes->merge(['class' => 'h-4 w-4']) }}>
    {!! $path !!}
</svg>

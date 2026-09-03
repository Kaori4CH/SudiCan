<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SudiCan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F4FAF5] text-slate-700">

    <div class="flex min-h-screen">

        @include('Layouts.Partial.sidebar-admin')

        <main class="flex-1 px-10 py-8">
            @yield('content')
        </main>

    </div>

</body>

</html>

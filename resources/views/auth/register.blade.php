<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SudiCan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#556F50] text-gray-800 antialiased">
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
        <div class="bg-[#556F50] flex flex-col justify-center items-center px-6 py-12 md:px-12 order-2 md:order-1">
            <div class="w-full max-w-sm">
                <h1 class="text-4xl md:text-5xl font-bold text-white text-center mb-8">Register</h1>

                @if ($errors->any())
                    <div class="mb-5 p-3 rounded-2xl bg-red-500/20 border border-red-300/40 text-white text-xs">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('auth.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Username"
                               required
                               class="w-full px-5 py-3.5 rounded-2xl bg-white text-gray-800 placeholder-gray-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    </div>

                    <div>
                        <input type="password"
                               name="password"
                               placeholder="Password"
                               required
                               class="w-full px-5 py-3.5 rounded-2xl bg-white text-gray-800 placeholder-gray-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    </div>

                    <div>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Email"
                               required
                               class="w-full px-5 py-3.5 rounded-2xl bg-white text-gray-800 placeholder-gray-400 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-xs md:text-sm text-gray-100">
                            Have an account?
                            <a href="{{ route('auth.login') }}" class="text-blue-500 font-medium hover:underline ml-1">Login</a>
                        </p>
                    </div>

                    <div class="pt-4 flex justify-center">
                        <button type="submit"
                                class="w-48 bg-[#E57853] hover:bg-[#d96a45] text-white font-medium py-3 px-6 rounded-2xl shadow-sm transition-colors text-center text-sm md:text-base">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white relative flex flex-col justify-center items-center p-8 md:p-12 order-1 md:order-2 min-h-[300px] md:min-h-screen">
            <div class="absolute top-6 right-6 md:top-8 md:right-10 flex items-center gap-2">
                <img src="{{ asset('images/Logo.png') }}" alt="SudiCan Logo" class="w-8 h-8 object-contain">
                <span class="text-lg font-bold text-[#1E3E34]">SudiCan</span>
            </div>

            <div class="w-full flex items-center justify-center pt-12 md:pt-0">
                <img src="{{ asset('images/loginlogo.png') }}" alt="SudiCan Banner" class="w-full max-w-sm md:max-w-md max-h-[75vh] object-contain">
            </div>
        </div>
    </div>
</body>
</html>


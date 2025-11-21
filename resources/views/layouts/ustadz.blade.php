<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Area Ustadz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #f0f4f8; } /* Warna background sedikit abu */
    </style>
</head>
<body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col">

    <div class="bg-emerald-700 shadow-md text-white sticky top-0 z-20">
        <div class="max-w-md mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold leading-tight">Pondok App</h1>
                    <p class="text-[10px] text-emerald-200">Pendidikan Diniyah</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Keluar dari aplikasi?');" class="text-emerald-100 hover:text-white p-2 rounded-full hover:bg-emerald-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <main class="flex-grow max-w-md mx-auto w-full px-4 py-6">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                <p class="font-bold text-sm">Berhasil</p>
                <p class="text-xs">{{ session('success') }}</p>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="text-center py-6 text-gray-400 text-xs">
        &copy; {{ date('Y') }} Sistem Pondok Pesantren
    </footer>

</body>
</html>
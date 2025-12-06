<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Petugas Lab</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen pb-20">

    <div class="bg-blue-600 text-white p-6 rounded-b-3xl shadow-lg relative z-10">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-blue-200 text-sm">Selamat Datang,</p>
                <h1 class="text-2xl font-bold">{{ Auth::user()->name }}</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 p-2 rounded-full transition">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
        
        @if (isset($headerStats))
            {{ $headerStats }}
        @endif
    </div>

    <main class="max-w-md mx-auto px-4 -mt-6 relative z-20">
        {{ $slot }}
    </main>

</body>
</html>
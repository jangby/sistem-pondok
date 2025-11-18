<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Script SweetAlert (diambil dari app.blade.php) --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            {{-- Kita TIDAK menyertakan 'layouts.navigation' (menu atas) --}}

            @isset($header)
                <header class="bg-white shadow sticky top-0 z-40">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="pb-20"> {{-- Padding bawah agar tidak tertutup nav --}}
                {{ $slot }}
            </main>
            
            {{-- Navigasi Bawah (Mobile Style) --}}
            @include('layouts.guru-nav-bottom')
        </div>
        
        {{-- Skrip SweetAlert Global (dari app.blade.php) --}}
        <script>
            document.addEventListener('turbo:load', () => {
                @if (session('success'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif
                @if (session('error'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: '{{ session('error') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif
            });
        </script>

        {{-- Stack untuk skrip per halaman (penting untuk GPS) --}}
        @stack('scripts')
    </body>
</html>
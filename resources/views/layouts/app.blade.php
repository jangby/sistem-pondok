<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        {{-- SweetAlert2 CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        {{-- Tom Select CDN (Untuk Dropdown Pencarian) --}}
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        {{-- Chart.js (Untuk Grafik) --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- Vite Assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            
            {{-- Cek atribut 'hide-nav' sebelum menampilkan navigasi --}}
            @if(!isset($attributes) || !$attributes->has('hide-nav'))
                {{-- LOGIKA PEMILIHAN NAVIGASI BERDASARKAN ROLE --}}
            @if(!isset($attributes) || !$attributes->has('hide-nav'))
                
                @if(Auth::user()->hasRole('super-admin'))
                    @include('layouts.navigation') {{-- Super Admin pakai Nav Default --}}
                
                @elseif(Auth::user()->hasRole('admin-pondok'))
                    @include('layouts.navigation') {{-- Admin Pondok pakai Nav Default --}}
                
                @elseif(Auth::user()->hasRole('bendahara'))
                    @include('layouts.bendahara-nav')
                
                @elseif(Auth::user()->hasRole('pos_warung'))
                    @include('layouts.pos-nav')
                
                @elseif(Auth::user()->hasRole('pengurus_pondok'))
                    @include('layouts.pengurus-nav') {{-- INI NAV BARU KITA --}}
                
                @elseif(Auth::user()->hasRole('orang-tua'))
                    {{-- Orang tua biasanya punya layout sendiri, tapi jika pakai app.blade: --}}
                    @include('layouts.navigation') 

                @elseif (Auth::user()->hasRole('admin-pendidikan'))
                    @include('layouts.pendidikan-admin-nav')
                
                @else
                    @include('layouts.navigation')
                @endif

            @endif
            @endif

            {{-- Page Heading --}}
            @if (isset($header) && $header->isNotEmpty())
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Page Content --}}
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- SCRIPT SWEETALERT (PERBAIKAN) --}}
        <script>
            // Notifikasi Sukses
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{!! session('success') !!}", // Menggunakan tanda kutip ganda & unescaped agar aman
                    showConfirmButton: true, // KITA AKTIFKAN TOMBOL OK
                    confirmButtonText: 'Oke',
                    timer: 3000, // Tetap pakai timer 3 detik
                    timerProgressBar: true // Tampilkan progress bar timer
                });
            @endif

            // Notifikasi Error
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{!! session('error') !!}",
                    showConfirmButton: true,
                    confirmButtonText: 'Tutup'
                });
            @endif
        </script>

        @stack('scripts')
    </body>
</html>
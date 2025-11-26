{{-- 
|--------------------------------------------------------------------------
| Navigasi Khusus untuk Super Admin Sekolah (Re-organized)
|--------------------------------------------------------------------------
|
| Menggunakan kombinasi x-nav-link dan x-dropdown agar rapi di header.
|
--}}

{{-- 1. DASHBOARD --}}
<x-nav-link :href="route('sekolah.superadmin.dashboard')" :active="request()->routeIs('sekolah.superadmin.dashboard')">
    {{ __('Dashboard') }}
</x-nav-link>

{{-- 2. DROPDOWN: DATA MASTER (Sekolah & Admin) --}}
@php
    $masterActive = request()->routeIs('sekolah.superadmin.sekolah.*') || 
                    request()->routeIs('sekolah.superadmin.admin-sekolah.*') || 
                    request()->routeIs('sekolah.superadmin.tahun-ajaran.*');
    
    $masterClasses = $masterActive 
        ? 'bg-emerald-800 text-white shadow-inner inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out' 
        : 'text-emerald-100 hover:bg-emerald-500 hover:text-white inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out';
@endphp

<div class="relative">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="{{ $masterClasses }}">
                <div>Data Master</div>
                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>
        <x-slot name="content">
            {{-- Header Kecil --}}
            <div class="block px-4 py-2 text-xs text-gray-400 uppercase font-bold">
                Institusi
            </div>
            <x-dropdown-link :href="route('sekolah.superadmin.sekolah.index')">
                {{ __('Manajemen Unit Sekolah') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.superadmin.tahun-ajaran.index')">
                {{ __('Manajemen Tahun Ajaran') }}
            </x-dropdown-link>

            <div class="border-t border-gray-100 my-1"></div>

            {{-- Header Kecil --}}
            <div class="block px-4 py-2 text-xs text-gray-400 uppercase font-bold">
                Pengguna
            </div>
            <x-dropdown-link :href="route('sekolah.superadmin.admin-sekolah.index')">
                {{ __('Manajemen Admin Sekolah') }}
            </x-dropdown-link>
            <x-nav-link :href="route('sekolah.superadmin.admin-pendidikan.index')" :active="request()->routeIs('sekolah.superadmin.admin-pendidikan.*')">
    {{ __('Admin Pendidikan (Madin)') }}
</x-nav-link>
        </x-slot>
    </x-dropdown>
</div>

{{-- 3. DROPDOWN: KEPEGAWAIAN (Guru & Izin) --}}
@php
    $pegawaiActive = request()->routeIs('sekolah.superadmin.guru.*') || 
                     request()->routeIs('sekolah.superadmin.persetujuan-izin.*');

    $pegawaiClasses = $pegawaiActive 
        ? 'bg-emerald-800 text-white shadow-inner inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out' 
        : 'text-emerald-100 hover:bg-emerald-500 hover:text-white inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out';
@endphp

<div class="relative">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="{{ $pegawaiClasses }}">
                <div>Kepegawaian</div>
                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>
        <x-slot name="content">
            <x-dropdown-link :href="route('sekolah.superadmin.guru.index')">
                {{ __('Manajemen Guru') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.superadmin.persetujuan-izin.index')">
                {{ __('Persetujuan Izin Guru') }}
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div>

{{-- 4. DROPDOWN: PERPUSTAKAAN --}}
@php
    // Menu aktif jika route mengandung kata 'perpustakaan'
    $perpusActive = request()->routeIs('sekolah.superadmin.perpustakaan.*');

    $perpusClasses = $perpusActive 
        ? 'bg-emerald-800 text-white shadow-inner inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out' 
        : 'text-emerald-100 hover:bg-emerald-500 hover:text-white inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out';
@endphp

<div class="relative">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="{{ $perpusClasses }}">
                <div>Perpustakaan</div>
                <div class="ms-1">
                    {{-- Icon Panah Bawah --}}
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>
        
        <x-slot name="content">
            {{-- Bagian 1: Sirkulasi Harian --}}
            <div class="block px-4 py-2 text-xs text-gray-400 uppercase font-bold">
                Sirkulasi
            </div>
            <x-dropdown-link :href="route('sekolah.superadmin.perpustakaan.sirkulasi.index')">
                {{ __('Transaksi Peminjaman') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.superadmin.perpustakaan.kunjungan.index')">
                {{ __('Buku Tamu / Kunjungan') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.superadmin.perpustakaan.kunjungan.kiosk')" target="_blank">
                {{ __('Layar Kiosk (Scan Mode)') }}
            </x-dropdown-link>

            <div class="border-t border-gray-100 my-1"></div>

            {{-- Bagian 2: Manajemen Data --}}
            <div class="block px-4 py-2 text-xs text-gray-400 uppercase font-bold">
                Data & Alat
            </div>
            <x-dropdown-link :href="route('sekolah.superadmin.perpustakaan.buku.index')">
                {{ __('Data Buku') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.superadmin.perpustakaan.anggota.kartu')">
                {{ __('Cetak Kartu Anggota') }}
            </x-dropdown-link>

            <div class="block px-4 py-2 text-xs text-gray-400 uppercase font-bold">
    Manajemen SDM
</div>

{{-- Menu Baru --}}
<x-dropdown-link :href="route('sekolah.superadmin.perpustakaan.petugas.index')">
    {{ __('Kelola Petugas Perpus') }}
</x-dropdown-link>

<div class="border-t border-gray-100 my-1"></div>
        </x-slot>
    </x-dropdown>
</div>
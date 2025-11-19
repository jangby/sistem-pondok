{{-- 
|--------------------------------------------------------------------------
| Navigasi Link Admin Sekolah (Child Component)
|--------------------------------------------------------------------------
| File ini HANYA berisi link menu saja.
| Logo dan Profile sudah dihandle oleh layouts/navigation.blade.php
--}}

{{-- 1. DASHBOARD --}}
<x-nav-link :href="route('sekolah.admin.dashboard')" :active="request()->routeIs('sekolah.admin.dashboard')">
    {{ __('Dashboard') }}
</x-nav-link>

{{-- 2. MENU AKADEMIK (DROPDOWN) --}}
@php
    $akademikActive = request()->routeIs('sekolah.admin.mata-pelajaran.*') || 
                      request()->routeIs('sekolah.admin.jadwal-pelajaran.*') || 
                      request()->routeIs('sekolah.admin.kegiatan-akademik.*');
    
    // Styling agar sesuai tema Emerald di navigation.blade.php
    $akademikTriggerClasses = $akademikActive 
        ? 'bg-emerald-800 text-white shadow-inner inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out' 
        : 'text-emerald-100 hover:bg-emerald-500 hover:text-white inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out';
@endphp

<div class="relative">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="{{ $akademikTriggerClasses }}">
                <div>Akademik</div>
                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>
        <x-slot name="content">
            <x-dropdown-link :href="route('sekolah.admin.mata-pelajaran.index')">Mata Pelajaran</x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.admin.jadwal-pelajaran.index')">Jadwal Pelajaran</x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.admin.kegiatan-akademik.index')">Jadwal Ujian</x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div>

{{-- 3. MENU ABSENSI & GURU (DROPDOWN) --}}
@php
    $absensiActive = request()->routeIs('sekolah.admin.monitoring.*') || 
                     request()->routeIs('sekolah.admin.konfigurasi.*') || 
                     request()->routeIs('sekolah.admin.guru-pengganti.*');

    $absensiTriggerClasses = $absensiActive 
        ? 'bg-emerald-800 text-white shadow-inner inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out' 
        : 'text-emerald-100 hover:bg-emerald-500 hover:text-white inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out';
@endphp

<div class="relative">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="{{ $absensiTriggerClasses }}">
                <div>Absensi & Guru</div>
                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>
        <x-slot name="content">
            <x-dropdown-link :href="route('sekolah.admin.monitoring.guru')">Monitoring Absensi</x-dropdown-link>
            <x-dropdown-link :href="route('sekolah.admin.konfigurasi.index')">Konfigurasi Absensi</x-dropdown-link>
            <div class="border-t border-gray-100"></div>
            <x-dropdown-link :href="route('sekolah.admin.guru-pengganti.index')">Guru Pengganti</x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div>

{{-- 4. LAPORAN --}}
<x-nav-link :href="route('sekolah.admin.laporan.index')" :active="request()->routeIs('sekolah.admin.laporan.*')">
    {{ __('Laporan & Ledger') }}
</x-nav-link>
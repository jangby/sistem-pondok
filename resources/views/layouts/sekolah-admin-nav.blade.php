{{-- 
|--------------------------------------------------------------------------
| Navigasi Khusus untuk Admin Sekolah
|--------------------------------------------------------------------------
|
| Berisi link menu untuk peran 'admin-sekolah'.
|
--}}

<x-dropdown-link :href="route('sekolah.admin.dashboard')" 
                 :active="request()->routeIs('sekolah.admin.dashboard')">
    {{ __('Dashboard') }}
</x-dropdown-link>



<x-dropdown-link :href="route('sekolah.admin.mata-pelajaran.index')" 
                 :active="request()->routeIs('sekolah.admin.mata-pelajaran.*')">
    {{ __('Mata Pelajaran') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.admin.jadwal-pelajaran.index')" 
                 :active="request()->routeIs('sekolah.admin.jadwal-pelajaran.*')">
    {{ __('Jadwal Pelajaran') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.admin.kegiatan-akademik.index')" 
                 :active="request()->routeIs('sekolah.admin.kegiatan-akademik.*')">
    {{ __('Jadwal Ujian') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.admin.konfigurasi.index')" 
                 :active="request()->routeIs('sekolah.admin.konfigurasi.*')">
    {{ __('Konfigurasi Absensi') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.admin.monitoring.guru')" 
                 :active="request()->routeIs('sekolah.admin.monitoring.*')">
    {{ __('Monitoring Absensi') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.admin.guru-pengganti.index')" 
                 :active="request()->routeIs('sekolah.admin.guru-pengganti.*')">
    {{ __('Manajemen Guru Pengganti') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.admin.laporan.index')" 
                 :active="request()->routeIs('sekolah.admin.laporan.*')">
    {{ __('Laporan & Ledger') }}
</x-dropdown-link>
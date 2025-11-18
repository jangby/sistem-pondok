{{-- 
|--------------------------------------------------------------------------
| Navigasi Khusus untuk Super Admin Sekolah
|--------------------------------------------------------------------------
|
| PERBAIKAN: Menggunakan <x-dropdown-link> agar sesuai dengan layout dropdown
|
--}}

<x-dropdown-link :href="route('sekolah.superadmin.dashboard')" 
                 :active="request()->routeIs('sekolah.superadmin.dashboard')">
    {{ __('Dashboard') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.superadmin.sekolah.index')" 
                 :active="request()->routeIs('sekolah.superadmin.sekolah.*')">
    {{ __('Manajemen Unit Sekolah') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.superadmin.admin-sekolah.index')" 
                 :active="request()->routeIs('sekolah.superadmin.admin-sekolah.*')">
    {{ __('Manajemen Admin Sekolah') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.superadmin.guru.index')" 
                 :active="request()->routeIs('sekolah.superadmin.guru.*')">
    {{ __('Manajemen Guru') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.superadmin.tahun-ajaran.index')" 
                 :active="request()->routeIs('sekolah.superadmin.tahun-ajaran.*')">
    {{ __('Manajemen Tahun Ajaran') }}
</x-dropdown-link>

<x-dropdown-link :href="route('sekolah.superadmin.persetujuan-izin.index')" 
                 :active="request()->routeIs('sekolah.superadmin.persetujuan-izin.*')">
    {{ __('Persetujuan Izin Guru') }}
</x-dropdown-link>
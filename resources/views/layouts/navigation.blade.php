@php
    // Helper untuk mengecek status langganan
    $user = Auth::user();
    $isPremium = false;

    // Logika ini sebaiknya hanya berjalan untuk role yang relevan (cth: admin-pondok)
    // untuk efisiensi.
    if ($user && $user->hasRole('admin-pondok')) {
        $pondok = $user->pondokStaff?->pondok; // Asumsi relasi dari user staff
        $subscription = $pondok?->subscription;
        
        // Cek apakah paket premium aktif
        // Rekomendasi: Ganti string hardcode 'Paket Premium' dengan ID atau Enum jika memungkinkan
        $isPremium = ($subscription && $subscription->plan?->name == 'Paket Premium');
    }
@endphp

<nav x-data="{ open: false }" class="bg-emerald-600 border-b border-emerald-500 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- LOGO --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                {{-- MENU DESKTOP --}}
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex sm:items-center">

                    {{-- ======================================== --}}
                    {{-- MENU SUPER ADMIN (Desktop)               --}}
                    {{-- ======================================== --}}
                    @role('super-admin')
                        <x-nav-link :href="route('superadmin.dashboard')" :active="request()->routeIs('superadmin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('superadmin.pondoks.index')" :active="request()->routeIs('superadmin.pondoks.*')">
                            {{ __('Pondok') }}
                        </x-nav-link>

<x-nav-link :href="route('superadmin.computer.index')" :active="request()->routeIs('superadmin.computer.index')">
    {{ __('Password Komputer') }}
</x-nav-link>
<x-nav-link :href="route('superadmin.petugas-lab.index')" :active="request()->routeIs('superadmin.petugas-lab.*')">
    {{ __('Kelola Petugas Lab') }}
</x-nav-link>

                        
                        <x-nav-link :href="route('superadmin.plans.index')" :active="request()->routeIs('superadmin.plans.*')">
                            {{ __('Paket') }}
                        </x-nav-link>

                        {{-- Dropdown Keuangan Super Admin --}}
                        @php
                            $saKeuanganActive = request()->routeIs('superadmin.midtrans.*') || request()->routeIs('superadmin.payouts.*') || request()->routeIs('superadmin.uuj-payout.*');
                            $saKeuanganClasses = $saKeuanganActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $saKeuanganClasses }}">
                                        <div>Keuangan</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('superadmin.midtrans.report')">Laporan Midtrans</x-dropdown-link>
                                    <x-dropdown-link :href="route('superadmin.payouts.index')">Payout SPP (Pondok)</x-dropdown-link>
                                    <x-dropdown-link :href="route('superadmin.uuj-payout.index')">Payout Uang Jajan</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    {{-- MENU SUPER ADMIN SEKOLAH --}}
                    @role('super-admin-sekolah')
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            @include('layouts.sekolah-superadmin-nav')
                        </div>
                    @endrole

                    {{-- MENU ADMIN SEKOLAH --}}
                    @role('admin-sekolah')
                        @include('layouts.sekolah-admin-nav')
                    @endrole

                    {{-- ======================================== --}}
                    {{-- MENU ADMIN PONDOK (Desktop)              --}}
                    {{-- ======================================== --}}
                    @role('admin-pondok')
                        <x-nav-link :href="route('adminpondok.dashboard')" :active="request()->routeIs('adminpondok.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        {{-- Data Master Dropdown --}}
                        @php
                            $dataMasterActive = request()->routeIs('adminpondok.santris.*') || request()->routeIs('adminpondok.orang-tuas.*') || request()->routeIs('adminpondok.bendahara.*');
                            $dmClasses = $dataMasterActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $dmClasses }}">
                                        <div>Data Master</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('adminpondok.santris.index')">Data Santri</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.orang-tuas.index')">Data Orang Tua</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.bendahara.index')">Manajemen Bendahara</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Keuangan & Tagihan Dropdown --}}
                        @php
                            $keuanganActive = request()->routeIs('adminpondok.jenis-pembayarans.*') || request()->routeIs('adminpondok.keringanans.*') || request()->routeIs('adminpondok.tagihan.*') || request()->routeIs('adminpondok.payout.*');
                            $kClasses = $keuanganActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $kClasses }}">
                                        <div>Keuangan & Tagihan</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('adminpondok.jenis-pembayarans.index')">Jenis Pembayaran</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.keringanans.index')">Keringanan</x-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                    <x-dropdown-link :href="route('adminpondok.tagihan.create')">Generate Tagihan</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.tagihan.index')">Manajemen Tagihan</x-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                    <x-dropdown-link :href="route('adminpondok.payout.index')">Penarikan Midtrans</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Laporan & Setoran Dropdown --}}
                        @php
                            $laporanActive = request()->routeIs('adminpondok.laporan.*') || request()->routeIs('adminpondok.setoran.*') || request()->routeIs('adminpondok.buku-besar.*');
                            $lClasses = $laporanActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $lClasses }}">
                                        <div>Laporan</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('adminpondok.laporan.bulanan')">Laporan Bulanan</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.laporan.tunggakan')">Laporan Tunggakan</x-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                    <x-dropdown-link :href="route('adminpondok.setoran.index')">Setoran ke Bendahara</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.buku-besar.index')">Buku Besar</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Menu Fitur Premium --}}
                        @if($isPremium)
                            @php
                                $premiumActive = request()->routeIs('adminpondok.pengurus.*') || request()->routeIs('adminpondok.uuj.admin.*') || request()->routeIs('adminpondok.manajemen-sekolah.*') || request()->routeIs('uuj-admin.warung.*') || request()->routeIs('adminpondok.payout.*');
                            @endphp
                            <div class="hidden sm:flex sm:items-center">
                                <x-dropdown align="top" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 h-full transition duration-150 ease-in-out
                                            {{ $premiumActive 
                                                ? 'bg-yellow-100 text-yellow-800 shadow-inner' 
                                                : 'text-yellow-100 hover:bg-emerald-500 hover:text-white' }}
                                            ">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                Fitur Premium
                                            </div>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        {{-- Sub-Header: Manajemen Akun --}}
                                        <div class="px-4 py-2 text-xs text-gray-400 uppercase font-bold bg-gray-50">Akun Staf Khusus</div>
                                        
                                        <x-dropdown-link :href="route('adminpondok.pengurus.index')">
                                            {{ __('Akun Pengurus Pondok') }}
                                        </x-dropdown-link>
                                        
                                        <x-dropdown-link :href="route('adminpondok.uuj.admin.index')">
                                            {{ __('Akun Admin Uang Jajan') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('adminpondok.manajemen-sekolah.index')">
                                            {{ __('Manajemen Sekolah') }}
                                        </x-dropdown-link>

                                        <div class="border-t border-gray-100 my-1"></div>

                                        {{-- Sub-Header: Keuangan Digital --}}
                                        <div class="px-4 py-2 text-xs text-gray-400 uppercase font-bold bg-gray-50">E-Wallet & Kantin</div>
                                        <x-dropdown-link :href="route('uuj-admin.warung.index')">
                                            {{ __('Manajemen Warung') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('adminpondok.payout.index')">
                                            {{ __('Dompet & Payout') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif

                        {{-- Pengaturan --}}
                        @php
                            $pengaturanActive = request()->routeIs('adminpondok.pengaturan.*') || request()->routeIs('adminpondok.kelas.*');
                            $pClasses = $pengaturanActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $pClasses }}">
                                        <div>Pengaturan</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('adminpondok.pengaturan.index')">Identitas Pondok</x-dropdown-link>
                                    <x-dropdown-link :href="route('adminpondok.kelas.index')">Manajemen Kelas</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    {{-- ======================================== --}}
                    {{-- MENU ADMIN UANG JAJAN (Desktop)          --}}
                    {{-- ======================================== --}}
                    @role('admin_uang_jajan')
                        <x-nav-link :href="route('uuj-admin.dashboard')" :active="request()->routeIs('uuj-admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('uuj-admin.warung.index')" :active="request()->routeIs('uuj-admin.warung.*')">
                            {{ __('Manajemen Warung') }}
                        </x-nav-link>
                        <x-nav-link :href="route('uuj-admin.dompet.index')" :active="request()->routeIs('uuj-admin.dompet.*')">
                            {{ __('Manajemen Dompet') }}
                        </x-nav-link>
                        
                        {{-- Dropdown Transaksi Manual --}}
                        @php
                            $manualActive = request()->routeIs('uuj-admin.topup.*') || request()->routeIs('uuj-admin.tarik.*');
                            $manualClasses = $manualActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $manualClasses }}">
                                        <div>Transaksi Manual</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('uuj-admin.topup.create')">Top-up Manual</x-dropdown-link>
                                    <x-dropdown-link :href="route('uuj-admin.tarik.create')">Tarik Tunai</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Dropdown Keuangan (Menu Baru) --}}
                        @php
                            $keuanganUujActive = request()->routeIs('uuj-admin.payout.*') || request()->routeIs('uuj-admin.pencairan.*');
                            $keuanganUujClasses = $keuanganUujActive 
                                ? 'bg-emerald-800 text-white shadow-inner' 
                                : 'text-emerald-100 hover:bg-emerald-500 hover:text-white';
                        @endphp
                        <div class="relative">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 transition duration-150 ease-in-out {{ $keuanganUujClasses }}">
                                        <div>Keuangan</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('uuj-admin.payout.index')">Konfirmasi Penarikan Warung</x-dropdown-link>
                                    <x-dropdown-link :href="route('uuj-admin.pencairan.index')">Pencairan Dana Midtrans</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    {{-- ======================================== --}}
                    {{-- MENU KASIR POS (Desktop)                 --}}
                    {{-- ======================================== --}}
                    @role('pos_warung')
                        <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">
                            {{ __('Halaman Kasir') }}
                        </x-nav-link>
                    @endrole

                    {{-- ======================================== --}}
                    {{-- MENU ORANG TUA (Desktop)                 --}}
                    {{-- ======================================== --}}
                    @role('orang-tua')
                        <x-nav-link :href="route('orangtua.dashboard')" :active="request()->routeIs('orangtua.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('orangtua.tagihan.index')" :active="request()->routeIs('orangtua.tagihan.*')">
                            {{ __('Tagihan Anak') }}
                        </x-nav-link>
                        <x-nav-link :href="route('orangtua.dompet.index')" :active="request()->routeIs('orangtua.dompet.*')">
                            {{ __('Uang Jajan') }}
                        </x-nav-link>
                    @endrole

                    {{-- ======================================== --}}
                    {{-- MENU BENDAHARA (Desktop)                 --}}
                    {{-- ======================================== --}}
                    @role('bendahara')
                         <x-nav-link :href="route('bendahara.dashboard')" :active="request()->routeIs('bendahara.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('bendahara.setoran.index')" :active="request()->routeIs('bendahara.setoran.*')">
                            {{ __('Setoran Masuk') }}
                        </x-nav-link>
                        <x-nav-link :href="route('bendahara.kas.index')" :active="request()->routeIs('bendahara.kas.*')">
                            {{ __('Buku Kas') }}
                        </x-nav-link>
                    @endrole

                </div>
            </div>

            {{-- Settings Dropdown (Desktop) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-emerald-100 hover:bg-emerald-500 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger (Mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-emerald-100 hover:text-white hover:bg-emerald-500 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================== --}}
    {{-- MENU MOBILE (RESPONSIVE) - DIPERBAIKI    --}}
    {{-- ======================================== --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">

            @role('super-admin')
                <x-responsive-nav-link :href="route('superadmin.dashboard')" :active="request()->routeIs('superadmin.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.pondoks.index')" :active="request()->routeIs('superadmin.pondoks.*')">{{ __('Pondok') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('superadmin.plans.index')" :active="request()->routeIs('superadmin.plans.*')">{{ __('Paket') }}</x-responsive-nav-link>
                
                {{-- Sub-menu Keuangan SA --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Keuangan SA</div>
                    <x-responsive-nav-link :href="route('superadmin.midtrans.report')" class="ps-8">Laporan Midtrans</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('superadmin.payouts.index')" class="ps-8">Payout SPP (Pondok)</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('superadmin.uuj-payout.index')" class="ps-8">Payout Uang Jajan</x-responsive-nav-link>
                </div>
            @endrole

            @role('super-admin-sekolah')
                {{-- Include menu mobile untuk super-admin-sekolah jika ada --}}
                {{-- @include('layouts.sekolah-superadmin-nav-mobile') --}}
                <div class="px-4 py-2 text-xs text-gray-400">Menu Super Admin Sekolah (Mobile) Belum Dibuat</div>
            @endrole

            @role('admin-sekolah')
                {{-- Include menu mobile untuk admin-sekolah jika ada --}}
                {{-- @include('layouts.sekolah-admin-nav-mobile') --}}
                 <div class="px-4 py-2 text-xs text-gray-400">Menu Admin Sekolah (Mobile) Belum Dibuat</div>
            @endrole

            @role('admin-pondok')
                <x-responsive-nav-link :href="route('adminpondok.dashboard')" :active="request()->routeIs('adminpondok.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                
                {{-- Sub-menu Data Master --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Data Master</div>
                    <x-responsive-nav-link :href="route('adminpondok.santris.index')" :active="request()->routeIs('adminpondok.santris.*')" class="ps-8">Data Santri</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.orang-tuas.index')" :active="request()->routeIs('adminpondok.orang-tuas.*')" class="ps-8">Data Orang Tua</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.bendahara.index')" :active="request()->routeIs('adminpondok.bendahara.*')" class="ps-8">Manajemen Bendahara</x-responsive-nav-link>
                </div>

                {{-- Sub-menu Keuangan & Tagihan --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Keuangan & Tagihan</div>
                    <x-responsive-nav-link :href="route('adminpondok.jenis-pembayarans.index')" :active="request()->routeIs('adminpondok.jenis-pembayarans.*')" class="ps-8">Jenis Pembayaran</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.keringanans.index')" :active="request()->routeIs('adminpondok.keringanans.*')" class="ps-8">Keringanan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.tagihan.create')" :active="request()->routeIs('adminpondok.tagihan.create')" class="ps-8">Generate Tagihan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.tagihan.index')" :active="request()->routeIs('adminpondok.tagihan.index')" class="ps-8">Manajemen Tagihan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.payout.index')" :active="request()->routeIs('adminpondok.payout.*')" class="ps-8">Penarikan Midtrans</x-responsive-nav-link>
                </div>

                {{-- Sub-menu Laporan --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Laporan</div>
                    <x-responsive-nav-link :href="route('adminpondok.laporan.bulanan')" :active="request()->routeIs('adminpondok.laporan.bulanan')" class="ps-8">Laporan Bulanan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.laporan.tunggakan')" :active="request()->routeIs('adminpondok.laporan.tunggakan')" class="ps-8">Laporan Tunggakan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.setoran.index')" :active="request()->routeIs('adminpondok.setoran.*')" class="ps-8">Setoran ke Bendahara</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.buku-besar.index')" :active="request()->routeIs('adminpondok.buku-besar.*')" class="ps-8">Buku Besar</x-responsive-nav-link>
                </div>

                {{-- Sub-menu Pengaturan --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Pengaturan</div>
                    <x-responsive-nav-link :href="route('adminpondok.pengaturan.index')" :active="request()->routeIs('adminpondok.pengaturan.*')" class="ps-8">Identitas Pondok</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.kelas.index')" :active="request()->routeIs('adminpondok.kelas.*')" class="ps-8">Manajemen Kelas</x-responsive-nav-link>
                </div>

                {{-- Sub-menu Fitur Premium --}}
                @if($isPremium)
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-yellow-600">Fitur Premium</div>
                    <x-responsive-nav-link :href="route('adminpondok.pengurus.index')" :active="request()->routeIs('adminpondok.pengurus.*')" class="ps-8">Akun Pengurus Pondok</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.uuj.admin.index')" :active="request()->routeIs('adminpondok.uuj.admin.*')" class="ps-8">Akun Admin Uang Jajan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.manajemen-sekolah.index')" :active="request()->routeIs('adminpondok.manajemen-sekolah.*')" class="ps-8">Manajemen Sekolah</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('uuj-admin.warung.index')" :active="request()->routeIs('uuj-admin.warung.*')" class="ps-8">Manajemen Warung</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('adminpondok.payout.index')" :active="request()->routeIs('adminpondok.payout.*')" class="ps-8">Dompet & Payout</x-responsive-nav-link>
                </div>
                @endif
            @endrole

            @role('admin_uang_jajan')
                <x-responsive-nav-link :href="route('uuj-admin.dashboard')" :active="request()->routeIs('uuj-admin.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('uuj-admin.warung.index')" :active="request()->routeIs('uuj-admin.warung.*')">{{ __('Manajemen Warung') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('uuj-admin.dompet.index')" :active="request()->routeIs('uuj-admin.dompet.*')">{{ __('Manajemen Dompet') }}</x-responsive-nav-link>

                {{-- Sub-menu Transaksi Manual --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Transaksi Manual</div>
                    <x-responsive-nav-link :href="route('uuj-admin.topup.create')" :active="request()->routeIs('uuj-admin.topup.*')" class="ps-8">Top-up Manual</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('uuj-admin.tarik.create')" :active="request()->routeIs('uuj-admin.tarik.*')" class="ps-8">Tarik Tunai</x-responsive-nav-link>
                </div>

                {{-- Sub-menu Keuangan --}}
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-semibold uppercase text-gray-500">Keuangan</div>
                    <x-responsive-nav-link :href="route('uuj-admin.payout.index')" :active="request()->routeIs('uuj-admin.payout.*')" class="ps-8">Konfirmasi Penarikan Warung</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('uuj-admin.pencairan.index')" :active="request()->routeIs('uuj-admin.pencairan.*')" class="ps-8">Pencairan Dana Midtrans</x-responsive-nav-link>
                </div>
            @endrole

            @role('pos_warung')
                <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">{{ __('Halaman Kasir') }}</x-responsive-nav-link>
            @endrole

            @role('orang-tua')
                <x-responsive-nav-link :href="route('orangtua.dashboard')" :active="request()->routeIs('orangtua.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orangtua.tagihan.index')" :active="request()->routeIs('orangtua.tagihan.*')">{{ __('Tagihan Anak') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orangtua.dompet.index')" :active="request()->routeIs('orangtua.dompet.*')">{{ __('Uang Jajan') }}</x-responsive-nav-link>
            @endrole

            @role('bendahara')
                <x-responsive-nav-link :href="route('bendahara.dashboard')" :active="request()->routeIs('bendahara.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bendahara.setoran.index')" :active="request()->routeIs('bendahara.setoran.*')">{{ __('Setoran Masuk') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bendahara.kas.index')" :active="request()->routeIs('bendahara.kas.*')">{{ __('Buku Kas') }}</x-responsive-nav-link>
            @endrole

        </div>

        {{-- Menu Profil & Logout (Mobile) --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('pendidikan.admin.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    {{-- 1. DASHBOARD --}}
                    <x-nav-link :href="route('pendidikan.admin.dashboard')" :active="request()->routeIs('pendidikan.admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- 2. DATA MASTER (Dropdown) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Data Master</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('pendidikan.admin.ustadz.index')">
                                    {{ __('Data Ustadz (Pengajar)') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.mustawa.index')">
                                    {{ __('Data Mustawa (Kelas)') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.mapel.index')">
                                    {{ __('Data Kitab (Mapel)') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 3. AKADEMIK (Dropdown) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Akademik</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('pendidikan.admin.jadwal.index')">
                                    {{ __('Jadwal Pelajaran') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                                <x-dropdown-link :href="route('pendidikan.admin.anggota-kelas.index')">
                                    {{ __('Anggota Kelas (Rombel)') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.kenaikan-kelas.index')">
                                    {{ __('Proses Kenaikan Kelas') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 4. UJIAN & EVALUASI (Dropdown) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Ujian & Nilai</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('pendidikan.admin.ujian.index')">
                                    {{ __('Jadwal Ujian') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.rapor.index')">
                                    {{ __('Rapor & Ledger') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 5. MONITORING --}}
                    <x-nav-link :href="route('pendidikan.admin.absensi.rekap')" :active="request()->routeIs('pendidikan.admin.absensi.*')">
                        {{ __('Monitoring Absensi') }}
                    </x-nav-link>

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
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

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('pendidikan.admin.dashboard')" :active="request()->routeIs('pendidikan.admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Group: Master --}}
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase">Data Master</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.ustadz.index')" :active="request()->routeIs('pendidikan.admin.ustadz.*')">
                {{ __('Data Ustadz') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.mustawa.index')" :active="request()->routeIs('pendidikan.admin.mustawa.*')">
                {{ __('Data Mustawa') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.mapel.index')" :active="request()->routeIs('pendidikan.admin.mapel.*')">
                {{ __('Data Kitab') }}
            </x-responsive-nav-link>

            {{-- Group: Akademik --}}
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase">Akademik</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.jadwal.index')" :active="request()->routeIs('pendidikan.admin.jadwal.*')">
                {{ __('Jadwal Pelajaran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.anggota-kelas.index')" :active="request()->routeIs('pendidikan.admin.anggota-kelas.*')">
                {{ __('Anggota Kelas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.kenaikan-kelas.index')" :active="request()->routeIs('pendidikan.admin.kenaikan-kelas.*')">
                {{ __('Kenaikan Kelas') }}
            </x-responsive-nav-link>

            {{-- Group: Ujian --}}
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase">Ujian & Nilai</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.ujian.index')" :active="request()->routeIs('pendidikan.admin.ujian.*')">
                {{ __('Jadwal Ujian') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.rapor.index')" :active="request()->routeIs('pendidikan.admin.rapor.*')">
                {{ __('Rapor / Ledger') }}
            </x-responsive-nav-link>

            {{-- Group: Monitoring --}}
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="px-4 text-xs font-semibold text-gray-400 uppercase">Monitoring</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.absensi.rekap')" :active="request()->routeIs('pendidikan.admin.absensi.*')">
                {{ __('Rekap Absensi') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
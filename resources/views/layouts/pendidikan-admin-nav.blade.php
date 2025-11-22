<nav x-data="{ open: false }" class="bg-emerald-600 border-b border-emerald-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('pendidikan.admin.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    {{-- 1. DASHBOARD --}}
                    <x-nav-link :href="route('pendidikan.admin.dashboard')" :active="request()->routeIs('pendidikan.admin.dashboard')" class="text-white hover:text-emerald-100 hover:border-emerald-200 focus:text-emerald-100 focus:border-emerald-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- 2. DATA MASTER (Dropdown) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-emerald-100 focus:outline-none transition ease-in-out duration-150">
                                    <div>Data Master</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('pendidikan.admin.ustadz.index')">Data Ustadz</x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.mustawa.index')">Data Kelas (Mustawa)</x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.mapel.index')">Data Kitab (Mapel)</x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                                <x-dropdown-link :href="route('pendidikan.admin.rapor-template.index')">Desain Template Rapor</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 3. AKADEMIK (Dropdown) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-emerald-100 focus:outline-none transition ease-in-out duration-150">
                                    <div>Akademik</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('pendidikan.admin.jadwal.index')">Jadwal Pelajaran</x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                                <x-dropdown-link :href="route('pendidikan.admin.anggota-kelas.index')">Anggota Kelas (Rombel)</x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.kenaikan-kelas.index')">Proses Kenaikan Kelas</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 4. UJIAN & NILAI (Dropdown) --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-emerald-100 focus:outline-none transition ease-in-out duration-150">
                                    <div>Ujian & Nilai</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('pendidikan.admin.ujian.index')">Jadwal Ujian</x-dropdown-link>
                                <x-dropdown-link :href="route('pendidikan.admin.rapor.index')">Cetak Rapor & Ledger</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- 5. MONITORING --}}
                    <x-nav-link :href="route('pendidikan.admin.absensi.rekap')" :active="request()->routeIs('pendidikan.admin.absensi.*')" class="text-white hover:text-emerald-100 hover:border-emerald-200 focus:text-emerald-100 focus:border-emerald-200">
                        {{ __('Monitoring Absensi') }}
                    </x-nav-link>

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-emerald-100 focus:outline-none transition ease-in-out duration-150">
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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-emerald-100 hover:text-white hover:bg-emerald-500 focus:outline-none focus:bg-emerald-500 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-emerald-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('pendidikan.admin.dashboard')" :active="request()->routeIs('pendidikan.admin.dashboard')" class="text-white border-emerald-300 hover:bg-emerald-600">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <div class="pt-2 pb-1 border-t border-emerald-500">
                <div class="px-4 text-xs font-semibold text-emerald-200 uppercase">Data Master</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.ustadz.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Data Ustadz') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.mustawa.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Data Mustawa') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.mapel.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Data Kitab') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.rapor-template.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Template Rapor') }}</x-responsive-nav-link>

            <div class="pt-2 pb-1 border-t border-emerald-500">
                <div class="px-4 text-xs font-semibold text-emerald-200 uppercase">Akademik</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.jadwal.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Jadwal Pelajaran') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.anggota-kelas.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Anggota Kelas') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.kenaikan-kelas.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Kenaikan Kelas') }}</x-responsive-nav-link>

            <div class="pt-2 pb-1 border-t border-emerald-500">
                <div class="px-4 text-xs font-semibold text-emerald-200 uppercase">Ujian & Nilai</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.ujian.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Jadwal Ujian') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pendidikan.admin.rapor.index')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Cetak Rapor') }}</x-responsive-nav-link>

            <div class="pt-2 pb-1 border-t border-emerald-500">
                <div class="px-4 text-xs font-semibold text-emerald-200 uppercase">Monitoring</div>
            </div>
            <x-responsive-nav-link :href="route('pendidikan.admin.absensi.rekap')" class="text-emerald-100 border-transparent hover:bg-emerald-600">{{ __('Rekap Absensi') }}</x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-emerald-500">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-emerald-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-emerald-100 hover:bg-emerald-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-emerald-100 hover:bg-emerald-600"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
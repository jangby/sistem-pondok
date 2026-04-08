<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assa'adah Smart Digital - Yayasan Pendidikan Islam</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1423313253267364" crossorigin="anonymous"></script>
</head>
<body class="bg-slate-50 antialiased min-h-screen text-slate-800 font-sans selection:bg-emerald-500 selection:text-white">

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    {{-- Ganti src dengan logo sesungguhnya --}}
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    </div>
                    <span class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">Assa'adah <span class="text-emerald-600">Smart Digital</span></span>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">Beranda</a>
                    <a href="#program" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">Keunggulan</a>
                    <a href="#info-ppdb" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">Info PPDB</a>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-full border border-emerald-200 transition">Dashboard Sistem</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-500 hover:text-slate-900 transition font-semibold text-sm">Masuk Portal</a>
                        @if(isset($ppdbActive) && $ppdbActive)
                            <a href="{{ route('ppdb.register') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-600 hover:text-emerald-600 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-100 absolute w-full shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#" class="block px-3 py-3 text-base font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">Beranda</a>
                <a href="#program" class="block px-3 py-3 text-base font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">Keunggulan</a>
                <a href="#info-ppdb" class="block px-3 py-3 text-base font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">Info PPDB</a>
                <hr class="my-2 border-slate-100">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block text-center px-3 py-3 text-base font-bold text-emerald-700 bg-emerald-50 rounded-lg">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-3 text-base font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Masuk Portal</a>
                    @if(isset($ppdbActive) && $ppdbActive)
                        <a href="{{ route('ppdb.register') }}" class="block mt-2 text-center px-3 py-3 text-base font-bold text-white bg-emerald-600 rounded-lg shadow-md">Daftar Santri Baru</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden bg-white">
        {{-- Background Pattern/Gradient --}}
        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-emerald-400/20 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-400/10 rounded-full blur-3xl -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                
                @if(isset($ppdbActive) && $ppdbActive)
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 mb-8 shadow-sm">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="text-sm font-bold tracking-wide">Penerimaan Santri Baru {{ $ppdbActive->tahun_ajaran }} Dibuka</span>
                    </div>
                @endif

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 text-slate-900 leading-tight">
                    Mewujudkan Generasi Islami <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-blue-600">Cerdas & Berteknologi</span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-3xl mx-auto leading-relaxed">
                    Assa'adah Smart Digital memadukan pendidikan pesantren tradisional dengan kurikulum modern dan sistem digital terintegrasi untuk mempersiapkan pemimpin masa depan.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if(isset($ppdbActive) && $ppdbActive)
                        <a href="{{ route('ppdb.register') }}" class="px-8 py-4 text-lg font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-xl shadow-emerald-600/30 transition transform hover:-translate-y-1">
                            Daftar Sekarang
                        </a>
                    @else
                        <button disabled class="px-8 py-4 text-lg font-bold text-slate-500 bg-slate-200 rounded-xl cursor-not-allowed">
                            Pendaftaran Belum Dibuka
                        </button>
                    @endif
                    <a href="#info-ppdb" class="px-8 py-4 text-lg font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-sm">
                        Pelajari Alur & Biaya
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- KEUNGGULAN / PROGRAM SECTION --}}
    <section id="program" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Mengapa Memilih Assa'adah?</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Kami menyediakan ekosistem pendidikan yang mengintegrasikan nilai agama dengan kemajuan teknologi.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 text-emerald-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pendidikan Karakter & Tahfidz</h3>
                    <p class="text-slate-600 leading-relaxed">Pembinaan akhlakul karimah dan program hafalan Al-Qur'an terstruktur yang diawasi langsung oleh para asatidz berpengalaman.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6 text-blue-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Smart Digital System</h3>
                    <p class="text-slate-600 leading-relaxed">Sistem informasi manajemen pondok yang terintegrasi. Memudahkan orang tua memantau nilai, hafalan, dan keuangan santri dari rumah.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-6 text-amber-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Fasilitas Modern</h3>
                    <p class="text-slate-600 leading-relaxed">Ruang belajar yang nyaman, laboratorium komputer, perpustakaan digital, serta asrama santri yang bersih dan aman.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- INFO PPDB SECTION --}}
    @if(isset($ppdbActive) && $ppdbActive)
    <section id="info-ppdb" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-emerald-600 font-bold tracking-wider uppercase text-sm mb-2 block">Pendaftaran Dibuka</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-6 text-slate-900">Informasi PPDB <br><span class="text-emerald-600">Gelombang {{ $ppdbActive->nama_gelombang ?? 'Berjalan' }}</span></h2>
                    
                    <div class="space-y-6 mt-8">
                        <div class="flex gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0 border border-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Waktu Pendaftaran</h3>
                                <p class="text-slate-600 mt-1">
                                    {{ $ppdbActive->tanggal_mulai->translatedFormat('d F Y') }} <span class="font-medium">s/d</span> {{ $ppdbActive->tanggal_akhir->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0 border border-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Biaya Pendaftaran</h3>
                                <p class="text-emerald-600 font-bold text-2xl mt-1">Rp {{ number_format($ppdbActive->biaya_pendaftaran, 0, ',', '.') }}</p>
                                <p class="text-sm text-slate-500 mt-1">Sistem pembayaran otomatis (VA Bank / QRIS)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="font-bold text-slate-800 mb-1">Catatan Panitia:</p>
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    {{ $ppdbActive->deskripsi ?? 'Pendaftaran dapat ditutup sewaktu-waktu jika kuota santri baru telah terpenuhi. Harap segera melengkapi berkas setelah melakukan pendaftaran akun.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-800 shadow-2xl">
                    <div class="absolute top-0 right-0 p-6 opacity-10 pointer-events-none">
                        <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-8">Alur Pendaftaran Online</h3>
                    
                    <ol class="relative border-l-2 border-slate-700 ml-4 space-y-8">                  
                        <li class="ml-8">
                            <span class="absolute flex items-center justify-center w-10 h-10 bg-emerald-500 rounded-full -left-5 ring-4 ring-slate-900 shadow-lg">
                                <span class="text-white font-bold text-base">1</span>
                            </span>
                            <h3 class="font-bold text-lg text-white mb-1">Pendaftaran Akun</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Klik tombol daftar, lalu isi formulir awal seperti Nama Lengkap, Email, dan Password.</p>
                        </li>
                        <li class="ml-8">
                            <span class="absolute flex items-center justify-center w-10 h-10 bg-slate-800 rounded-full -left-5 ring-4 ring-slate-900 border border-slate-600">
                                <span class="text-slate-300 font-bold text-base">2</span>
                            </span>
                            <h3 class="font-bold text-lg text-white mb-1">Pembayaran Biaya</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Lakukan pembayaran melalui Virtual Account atau QRIS yang tampil di layar Anda.</p>
                        </li>
                        <li class="ml-8">
                            <span class="absolute flex items-center justify-center w-10 h-10 bg-slate-800 rounded-full -left-5 ring-4 ring-slate-900 border border-slate-600">
                                <span class="text-slate-300 font-bold text-base">3</span>
                            </span>
                            <h3 class="font-bold text-lg text-white mb-1">Pengisian Biodata</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">Setelah pembayaran lunas, isi biodata lengkap santri, orang tua, dan upload berkas pendukung.</p>
                        </li>
                    </ol>

                    <div class="mt-10">
                        <a href="{{ route('ppdb.register') }}" class="flex justify-center items-center gap-2 w-full py-4 bg-emerald-500 hover:bg-emerald-400 rounded-xl font-bold text-slate-900 transition shadow-lg shadow-emerald-500/20">
                            Mulai Pendaftaran
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CALL TO ACTION (CTA) --}}
    <section class="py-16 bg-emerald-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Bergabung dengan Assa'adah Smart Digital?</h2>
            <p class="text-emerald-100 mb-8 text-lg">Wujudkan masa depan pendidikan yang lebih baik, terintegrasi, dan berkah bersama kami.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#info-ppdb" class="px-8 py-3 bg-white text-emerald-700 font-bold rounded-full shadow-lg hover:bg-slate-50 transition">Informasi Lengkap</a>
                <a href="https://wa.me/628123456789" target="_blank" class="px-8 py-3 bg-emerald-700 text-white font-bold rounded-full border border-emerald-500 hover:bg-emerald-800 transition shadow-lg">Hubungi Admin</a>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-emerald-500 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">Assa'adah</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-sm">
                        Sistem manajemen pendidikan pesantren pintar dan modern yang terintegrasi secara digital. Mencetak generasi tangguh, berakhlak, dan mandiri.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-emerald-600 transition">
                            <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-emerald-600 transition">
                            <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-3 text-slate-400 text-sm">
                        <li><a href="#" class="hover:text-emerald-500 transition">Beranda Utama</a></li>
                        <li><a href="#program" class="hover:text-emerald-500 transition">Tentang Program</a></li>
                        <li><a href="#info-ppdb" class="hover:text-emerald-500 transition">Informasi PPDB</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-500 transition">Login Sistem</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-slate-400 text-sm">
                        <li class="flex gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jl. Raya Pendidikan No. 123, Kota Cerdas, Indonesia
                        </li>
                        <li class="flex gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            info@assaadah.sch.id
                        </li>
                        <li class="flex gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0812-3456-7890
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} Assa'adah Smart Digital. All rights reserved.
                </p>
                <div class="text-slate-500 text-sm">
                    Ditenagai oleh <span class="text-emerald-500 font-semibold">Sistem Pondok</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
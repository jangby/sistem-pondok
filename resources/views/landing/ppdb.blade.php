@extends('layouts.landing')

@section('title', 'Informasi PPDB & Pendaftaran')

@section('content')

    {{-- 1. HERO SECTION --}}
    <section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
        {{-- Background Image --}}
        <div class="absolute inset-0 w-full h-full z-0">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop" 
                 alt="Pendaftaran Santri Baru" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full pt-32 pb-20 text-center">
            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full glass-dark mb-6 border-emerald-500/30 shadow-lg">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-emerald-300 font-bold tracking-widest text-xs uppercase">Pendaftaran Dibuka</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-6 leading-tight tracking-tight">
                Informasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">PPDB Terkini</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto font-medium leading-relaxed mb-10">
                Panduan lengkap, syarat, dan alur pendaftaran santri baru Assa'adah Smart Digital. Persiapkan dokumen Anda dan bergabunglah bersama kami.
            </p>
            
            <a href="{{ route('ppdb.register') }}" class="inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-slate-900 bg-white rounded-full hover:bg-slate-100 shadow-[0_0_40px_rgba(16,185,129,0.3)] transition transform hover:-translate-y-1">
                Daftar Akun Sekarang
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    {{-- 2. ALUR PENDAFTARAN (STEP-BY-STEP GRID) --}}
    <section class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-emerald-600 font-extrabold tracking-widest uppercase text-sm mb-4 block">Bagaimana Caranya?</span>
                <h2 class="text-4xl font-extrabold text-slate-900 mb-6 tracking-tight">4 Langkah Mudah <span class="text-emerald-600">Mendaftar</span></h2>
                <p class="text-slate-600 text-lg">Sistem kami 100% online. Anda tidak perlu datang ke pondok untuk melakukan pendaftaran awal.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 text-9xl font-black text-slate-50 group-hover:text-emerald-50 transition-colors duration-300">1</div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Buat Akun</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Klik tombol daftar dan isi nama, email, serta password untuk membuat akun PPDB Anda.</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 text-9xl font-black text-slate-50 group-hover:text-emerald-50 transition-colors duration-300">2</div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Bayar Pendaftaran</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Sistem akan memunculkan Virtual Account (VA) / QRIS. Lakukan pembayaran untuk mengaktifkan akun.</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 text-9xl font-black text-slate-50 group-hover:text-emerald-50 transition-colors duration-300">3</div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Lengkapi Berkas</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Login ke Dashboard PPDB, lengkapi formulir biodata santri, orang tua, dan unggah dokumen.</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 text-9xl font-black text-slate-50 group-hover:text-emerald-50 transition-colors duration-300">4</div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Tes & Pengumuman</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Ikuti tes seleksi (jika ada) dan cetak bukti pendaftaran/kelulusan langsung dari dashboard Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. SYARAT & BIAYA (BENTO SPLIT) --}}
    <section class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-7">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8 tracking-tight">Persyaratan <span class="text-emerald-600">Berkas Pendukung</span></h2>
                    <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                        Siapkan hasil *scan* atau foto dokumen berikut dengan jelas (format JPG/PNG/PDF) untuk diunggah pada tahap ketiga pengisian formulir:
                    </p>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700 text-sm">Kartu Keluarga (KK)</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700 text-sm">Akte Kelahiran</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700 text-sm">KTP Ayah & Ibu</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700 text-sm">Pas Foto Terbaru (Latar Merah/Biru)</span>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100 sm:col-span-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-semibold text-slate-700 text-sm">Ijazah / Surat Keterangan Lulus (Bisa menyusul)</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-slate-900 rounded-[2rem] p-8 md:p-10 shadow-2xl border border-slate-800 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500 rounded-full blur-[60px] opacity-20"></div>
                        
                        <h3 class="text-xl font-bold text-white mb-2">Biaya Pendaftaran Awal</h3>
                        <p class="text-slate-400 text-sm mb-8">Dibayarkan saat pembuatan akun untuk mengakses formulir dan jadwal tes.</p>
                        
                        <div class="flex items-baseline gap-2 mb-8">
                            <span class="text-2xl font-bold text-emerald-400">Rp</span>
                            @if(isset($ppdbActive) && $ppdbActive)
                                <span class="text-5xl font-black text-white tracking-tight">{{ number_format($ppdbActive->biaya_pendaftaran, 0, ',', '.') }}</span>
                            @else
                                {{-- Harga Dummy/Default jika variabel tidak dilempar --}}
                                <span class="text-5xl font-black text-white tracking-tight">250.000</span>
                            @endif
                        </div>

                        <ul class="space-y-4 mb-10">
                            <li class="flex items-center gap-3 text-slate-300 text-sm">
                                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Termasuk Map & Panduan (PDF)
                            </li>
                            <li class="flex items-center gap-3 text-slate-300 text-sm">
                                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Biaya Administrasi Server
                            </li>
                            <li class="flex items-center gap-3 text-slate-300 text-sm">
                                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Biaya Ujian Masuk / Tes Seleksi
                            </li>
                        </ul>

                        <div class="p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                            <p class="text-xs text-slate-400 leading-relaxed">
                                <span class="text-emerald-400 font-bold">*Catatan:</span> Rincian lengkap biaya Daftar Ulang (Uang Pangkal, Seragam, Asrama, dll) dapat dilihat dan diunduh di dalam Dashboard setelah akun Anda aktif.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 4. FAQ SECTION --}}
    <section class="py-24 bg-slate-50 border-t border-slate-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Pertanyaan Seputar <span class="text-emerald-600">PPDB</span></h2>
                <p class="text-slate-600">Berikut adalah beberapa pertanyaan yang paling sering diajukan oleh calon wali santri.</p>
            </div>

            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                    <button class="w-full px-6 py-5 text-left font-bold text-slate-800 hover:text-emerald-600 flex justify-between items-center focus:outline-none" onclick="toggleFaq('faq1')">
                        <span>Apakah pendaftaran bisa dilakukan secara offline (datang langsung)?</span>
                        <svg id="icon-faq1" class="w-5 h-5 text-slate-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="faq1" class="px-6 pb-5 text-slate-600 hidden">
                        Pendaftaran utama kami sarankan 100% online untuk kemudahan Anda. Namun, jika Anda mengalami kesulitan teknis, Anda boleh datang ke Ruang Tata Usaha (TU) Pondok pada jam kerja, panitia kami akan membantu mendaftarkan Anda melalui komputer yang disediakan.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                    <button class="w-full px-6 py-5 text-left font-bold text-slate-800 hover:text-emerald-600 flex justify-between items-center focus:outline-none" onclick="toggleFaq('faq2')">
                        <span>Metode pembayaran apa saja yang didukung?</span>
                        <svg id="icon-faq2" class="w-5 h-5 text-slate-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="faq2" class="px-6 pb-5 text-slate-600 hidden">
                        Sistem kami telah terintegrasi dengan Payment Gateway *Real-time*. Anda bisa membayar menggunakan Virtual Account (BCA, BNI, Mandiri, BRI, BSI, dll), QRIS, maupun lewat Alfamart/Indomaret. Akun akan otomatis aktif beberapa detik setelah pembayaran berhasil.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                    <button class="w-full px-6 py-5 text-left font-bold text-slate-800 hover:text-emerald-600 flex justify-between items-center focus:outline-none" onclick="toggleFaq('faq3')">
                        <span>Apakah Ijazah harus diunggah sekarang? Anak saya belum lulus.</span>
                        <svg id="icon-faq3" class="w-5 h-5 text-slate-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="faq3" class="px-6 pb-5 text-slate-600 hidden">
                        Tidak wajib. Jika ijazah atau Surat Keterangan Lulus (SKL) belum keluar, kolom tersebut bisa dikosongkan terlebih dahulu atau diisi dengan Surat Keterangan Siswa Aktif dari sekolah asal. Ijazah asli bisa diserahkan saat daftar ulang di pondok.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. CALL TO ACTION --}}
    <section class="py-20 bg-emerald-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Jangan Tunda Kebaikan</h2>
            <p class="text-emerald-100 mb-10 text-lg md:text-xl font-medium">Kuota penerimaan terbatas untuk menjaga rasio ideal dan kualitas pendidikan.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('ppdb.register') }}" class="px-8 py-4 bg-white text-emerald-700 font-bold text-lg rounded-full shadow-xl hover:bg-slate-50 hover:scale-105 transition-transform duration-300">
                    Mulai Pendaftaran
                </a>
                <a href="https://wa.me/628123456789" target="_blank" class="px-8 py-4 bg-emerald-800 text-white font-bold text-lg rounded-full border border-emerald-500 hover:bg-emerald-900 transition-colors shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Tanya Panitia (WA)
                </a>
            </div>
        </div>
    </section>

    {{-- SCRIPT SEDERHANA UNTUK FAQ --}}
    <script>
        function toggleFaq(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
@endsection
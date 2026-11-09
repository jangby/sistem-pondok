<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="pengurus-dashboard">
        <section class="pengurus-welcome">
            <div>
                <p class="pengurus-eyebrow">{{ now()->format('l, d F Y') }}</p>
                <h2>Selamat bertugas, {{ Auth::user()->name }}</h2>
                <p class="pengurus-welcome-copy">Semua kebutuhan operasional pondok, dalam satu ruang kerja.</p>
            </div>
            <a href="{{ route('pengurus.santri.index') }}" class="pengurus-primary-action">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/>
                </svg>
                Kelola Santri
            </a>
        </section>

        <section class="pengurus-stat-grid" aria-label="Ringkasan hari ini">
            <article class="pengurus-stat-card">
                <span class="pengurus-stat-icon pengurus-stat-icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                </span>
                <span class="pengurus-stat-label">Kehadiran hari ini</span>
                <strong>{{ number_format($hadirHariIni) }}</strong>
                <small>Santri tercatat hadir</small>
            </article>
            <article class="pengurus-stat-card">
                <span class="pengurus-stat-icon pengurus-stat-icon-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </span>
                <span class="pengurus-stat-label">Izin sedang aktif</span>
                <strong>{{ number_format($izinKeluarAktif) }}</strong>
                <small>Santri sedang di luar pondok</small>
            </article>
            <article class="pengurus-stat-card">
                <span class="pengurus-stat-icon pengurus-stat-icon-rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4M14 3h4a2 2 0 0 1 2 2v4m-6-6v6h6M8 13h3m-3 4h8"/></svg>
                </span>
                <span class="pengurus-stat-label">Kasus kesehatan</span>
                <strong>{{ number_format($sakitHariIni) }}</strong>
                <small>Kasus tercatat hari ini</small>
            </article>
        </section>

        <section class="pengurus-dashboard-columns">
            <div class="pengurus-dashboard-panel">
                <div class="pengurus-section-heading">
                    <div>
                        <span class="pengurus-eyebrow">AKSES CEPAT</span>
                        <h3>Operasional pondok</h3>
                    </div>
                    <span class="pengurus-section-note">Pilih menu untuk mulai bekerja</span>
                </div>

                <div class="pengurus-quick-grid">
                    <a href="{{ route('pengurus.absensi.gerbang') }}" class="pengurus-quick-link">
                        <span class="pengurus-quick-icon pengurus-quick-icon-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16M4 10h16M9 7h.01M13 7h.01M2 21h20"/></svg>
                        </span>
                        <span><strong>Absensi gerbang</strong><small>Keluar masuk santri</small></span>
                        <span class="pengurus-quick-arrow">→</span>
                    </a>
                    <a href="{{ route('pengurus.perizinan.scan') }}" class="pengurus-quick-link">
                        <span class="pengurus-quick-icon pengurus-quick-icon-amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18m-12 5h6"/></svg>
                        </span>
                        <span><strong>Proses perizinan</strong><small>Catat izin santri</small></span>
                        <span class="pengurus-quick-arrow">→</span>
                    </a>
                    <a href="{{ route('pengurus.uks.scan') }}" class="pengurus-quick-link">
                        <span class="pengurus-quick-icon pengurus-quick-icon-rose">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4M14 3h4a2 2 0 0 1 2 2v4m-6-6v6h6M12 12v6m-3-3h6"/></svg>
                        </span>
                        <span><strong>Catat kesehatan</strong><small>Input kunjungan UKS</small></span>
                        <span class="pengurus-quick-arrow">→</span>
                    </a>
                    <a href="{{ route('pengurus.santri.index') }}" class="pengurus-quick-link">
                        <span class="pengurus-quick-icon pengurus-quick-icon-violet">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm10 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span><strong>Data santri</strong><small>Profil dan status santri</small></span>
                        <span class="pengurus-quick-arrow">→</span>
                    </a>
                </div>
            </div>

            <aside class="pengurus-dashboard-panel pengurus-overview-panel">
                <div class="pengurus-section-heading">
                    <div>
                        <span class="pengurus-eyebrow">PINTASAN</span>
                        <h3>Kelola unit</h3>
                    </div>
                </div>
                <a href="{{ route('pengurus.asrama.index') }}" class="pengurus-overview-link">
                    <span class="pengurus-overview-dot pengurus-dot-violet"></span>
                    <span>Manajemen asrama</span><span>→</span>
                </a>
                <a href="{{ route('pengurus.perpulangan.index') }}" class="pengurus-overview-link">
                    <span class="pengurus-overview-dot pengurus-dot-blue"></span>
                    <span>Jadwal perpulangan</span><span>→</span>
                </a>
                <a href="{{ route('pengurus.inventaris.index') }}" class="pengurus-overview-link">
                    <span class="pengurus-overview-dot pengurus-dot-amber"></span>
                    <span>Inventaris pondok</span><span>→</span>
                </a>
                <a href="{{ route('pengurus.absensi.index') }}" class="pengurus-overview-link">
                    <span class="pengurus-overview-dot pengurus-dot-green"></span>
                    <span>Rekap kehadiran</span><span>→</span>
                </a>
                <div class="pengurus-overview-footnote">
                    <span class="pengurus-status-indicator"></span>
                    Sistem siap digunakan
                </div>
            </aside>
        </section>
    </div>

    @include('layouts.pengurus-nav')
</x-app-layout>

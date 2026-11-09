<aside class="pengurus-sidebar" aria-label="Navigasi pengurus">
    <a href="{{ route('pengurus.dashboard') }}" class="pengurus-sidebar-brand">
        <span class="pengurus-sidebar-mark">SP</span>
        <span class="pengurus-sidebar-brand-copy">
            <strong>SISTEM PONDOK</strong>
            <small>OPERASIONAL</small>
        </span>
    </a>

    <nav class="pengurus-sidebar-menu">
        <span class="pengurus-sidebar-label">WORKSPACE</span>
        <a href="{{ route('pengurus.dashboard') }}" class="{{ request()->routeIs('pengurus.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
            <span>Ringkasan</span>
        </a>

        <span class="pengurus-sidebar-label">KEHIDUPAN SANTRI</span>
        <a href="{{ route('pengurus.santri.index') }}" class="{{ request()->routeIs('pengurus.santri.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87m-1-11.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Data Santri</span>
        </a>
        <a href="{{ route('pengurus.asrama.index') }}" class="{{ request()->routeIs('pengurus.asrama.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 21V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v16M3 10h18M8 7h.01M12 7h.01M16 7h.01M8 14h2v3H8zm6 0h2v3h-2zM2 21h20"/></svg>
            <span>Asrama</span>
        </a>
        <a href="{{ route('pengurus.perizinan.index') }}" class="{{ request()->routeIs('pengurus.perizinan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18m-12 5 2 2 4-4"/></svg>
            <span>Perizinan</span>
        </a>
        <a href="{{ route('pengurus.perpulangan.index') }}" class="{{ request()->routeIs('pengurus.perpulangan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1zM14 11l3 3-3 3m3-3h-7"/></svg>
            <span>Perpulangan</span>
        </a>

        <span class="pengurus-sidebar-label">OPERASIONAL</span>
        <a href="{{ route('pengurus.absensi.index') }}" class="{{ request()->routeIs('pengurus.absensi.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3.5h6M8 10h8m-8 4h8m-8 4h5"/></svg>
            <span>Absensi</span>
        </a>
        <a href="{{ route('pengurus.uks.index') }}" class="{{ request()->routeIs('pengurus.uks.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 3h4m-3 0v6L5 19a2 2 0 0 0 2 3h10a2 2 0 0 0 2-3l-6-10V3m-7 12h12m-7-3v6m-3-3h6"/></svg>
            <span>Kesehatan / UKS</span>
        </a>
        <a href="{{ route('pengurus.kios.index') }}" class="{{ request()->routeIs('pengurus.kios.*', 'pengurus.jadwal.*', 'pengurus.buku-tamu.*', 'pengurus.rekap-gerbang.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 21V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16M4 10h16M9 7h.01M13 7h.01M8 14h3v3H8zm7 0h2v3h-2zM2 21h20"/></svg>
            <span>Gerbang</span>
        </a>
        <a href="{{ route('pengurus.inventaris.index') }}" class="{{ request()->routeIs('pengurus.inventaris.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5zm-9 5v9l9 5 9-5V8m-9 5v9"/></svg>
            <span>Inventaris</span>
        </a>
    </nav>

    <div class="pengurus-sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="pengurus-sidebar-account {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
            <span>Pengaturan akun</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="pengurus-sidebar-logout">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></svg>
                <span>Keluar</span>
            </button>
        </form>
        <div class="pengurus-sidebar-version">Sistem Pondok <span>•</span> Panel Pengurus</div>
    </div>
</aside>

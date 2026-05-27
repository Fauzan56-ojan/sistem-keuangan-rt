<aside id="sidebar" class="glass-sidebar sidebar-transition overflow-hidden fixed left-0 top-0 h-screen w-64 z-50 flex flex-col shadow-xl">

    <!-- LOGO -->
    <div class="pt-8 pb-5 px-6 border-b border-emerald-100/30">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">account_balance</span>
            </div>
            <div>
                <p class="font-bold text-slate-800">Sistem Keuangan</p>
                <p class="text-xs text-slate-800">RT 01</p>
            </div>
        </div>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-3 py-5 space-y-1.5 text-sm">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>

        {{-- Iuran --}}
        <a href="{{ in_array(auth()->user()->role, ['admin','bendahara','ketua_rt']) 
                    ? url('/iuran-warga') 
                    : url('/warga/' . auth()->id() . '/iuran') }}"
           class="nav-item {{ request()->is('iuran*') || request()->is('warga/*/iuran*') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">payments</span>
            {{ auth()->user()->role === 'warga' ? 'Iuran Saya' : 'Iuran Warga' }}
        </a>

        {{-- Riwayat --}}
        @if(auth()->user()->role !== 'ketua_rt')
        <a href="{{ url('/riwayat') }}"
           class="nav-item {{ request()->is('riwayat') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">receipt_long</span>
            Riwayat
        </a>
        @endif

        {{-- Tunggakan --}}
        <a href="{{ in_array(auth()->user()->role, ['admin','bendahara','ketua_rt']) 
                    ? route('tunggakan.index') 
                    : url('/tunggakan/' . auth()->id()) }}"
           class="nav-item {{ request()->is('tunggakan*') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">warning</span>
            Tunggakan
        </a>

        {{-- Pemasukan --}}
        <a href="{{ url('/pemasukan') }}"
           class="nav-item {{ request()->is('pemasukan') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">trending_up</span>
            Pemasukan
        </a>

        {{-- Pengeluaran --}}
        <a href="{{ url('/pengeluaran') }}"
           class="nav-item {{ request()->is('pengeluaran') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">trending_down</span>
            Pengeluaran
        </a>

        {{-- Laporan --}}
        @if(in_array(auth()->user()->role, ['admin','bendahara','ketua_rt']))
        <a href="{{ url('/laporan') }}"
           class="nav-item {{ request()->is('laporan') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">bar_chart</span>
            Laporan
        </a>
        @endif

        {{-- Users --}}
        @if(auth()->user()->role == 'admin')
        <a href="{{ url('/users') }}"
           class="nav-item {{ request()->is('users') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">group</span>
            User
        </a>
        @endif

        {{-- Settings --}}
        <a href="{{ auth()->user()->role === 'admin' 
                    ? route('settings.index') 
                    : route('settings.profile') }}"
           class="nav-item {{ request()->routeIs('settings.*') ? 'nav-active' : 'text-slate-600' }}">
            <span class="material-symbols-outlined">settings</span>
            Setting
        </a>

    </nav>

    <div class="mt-auto px-3 pb-5">

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit"
            class="nav-item w-full text-left text-red-600 hover:text-red-700 hover:bg-red-50 flex items-center gap-3 px-4 py-2.5 rounded-xl transition">

            <span class="material-symbols-outlined">logout</span>
            Logout

        </button>
    </form>

</div>

</aside>
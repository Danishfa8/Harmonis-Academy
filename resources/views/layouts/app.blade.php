<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Harmonis Academy</title>
    <meta name="description" content="Sistem Manajemen Akademi Kursus Barbershop — Harmonis Academy">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>

<body class="bg-[#131927] text-slate-200 font-sans antialiased" x-data="{ sidebarOpen: window.matchMedia('(min-width: 1024px)').matches }">
    <div class="flex min-h-screen">
        {{-- ===== SIDEBAR ===== --}}
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-black/50 lg:hidden"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <aside class="fixed left-0 top-0 z-40 flex h-screen w-64 flex-col bg-[#1C2434] shadow-xl transition-transform duration-300 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-700/50">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500 shadow-lg shadow-emerald-500/20">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-7 7m7-7l-7-7"/></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight">Harmonis Academy</h1>
                    <p class="text-xs text-slate-400">Management System</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
                @php $role = Auth::user()->role; @endphp

                {{-- MENU ADMIN --}}
                @if($role === 'admin')
                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Menu Utama</p>
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                </div>

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Master Data</p>
                    <div class="space-y-1">
                        <a href="{{ route('peserta.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('peserta.*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Peserta Kursus
                        </a>
                        <a href="{{ route('paket-kursus.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('paket-kursus.*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Paket Kursus
                        </a>
                    </div>
                </div>

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Manajemen Kursus</p>
                    <div class="space-y-1">
                        <a href="{{ route('penilaian-skill.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('penilaian-skill.*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Penilaian Skill
                        </a>
                        <a href="{{ route('pembayaran.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Pembayaran
                        </a>
                        <a href="{{ route('riwayat-pembayaran.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('riwayat-pembayaran.*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat Pembayaran
                        </a>
                    </div>
                </div>

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Laporan</p>
                    <div class="space-y-1">
                        <a href="{{ route('laporan.peserta') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('laporan.peserta*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Laporan Peserta
                        </a>
                        <a href="{{ route('laporan.pendapatan') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('laporan.pendapatan*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            Laporan Pendapatan
                        </a>
                    </div>
                </div>
                
                {{-- MENU PESERTA --}}
                @elseif($role === 'peserta')
                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Menu Utama</p>
                    <div class="space-y-1">
                        <a href="{{ route('peserta.dashboard') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard Saya
                        </a>
                        <a href="{{ route('peserta.nilai') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('peserta.nilai') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            Nilai & Progress
                        </a>
                        <a href="{{ route('peserta.pembayaran') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 {{ request()->routeIs('peserta.pembayaran') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Pembayaran Saya
                        </a>
                    </div>
                </div>
                @endif
            </nav>

            {{-- User Profile --}}
            <div class="border-t border-slate-700/50 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Peserta' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-slate-700 hover:text-white" title="Logout">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
            {{-- Top Navbar --}}
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-700/50 bg-[#1C2434]/95 backdrop-blur-sm px-4 sm:px-6">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-slate-400 hover:bg-slate-700 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h2 class="text-lg font-semibold text-white">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline text-sm text-slate-400">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- SweetAlert2 for session flash messages --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', background: '#1C2434', color: '#e2e8f0', confirmButtonColor: '#10B981', timer: 3000, timerProgressBar: true });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', background: '#1C2434', color: '#e2e8f0', confirmButtonColor: '#EF4444' });
        @endif
    });
    </script>

    @yield('scripts')
</body>
</html>
<div class="min-h-screen flex">
    <aside class="w-64 bg-blue-900 text-white flex flex-col sticky top-0 h-screen shadow-lg">
        <div class="p-6 text-center border-b border-blue-800">
            <img src="{{ asset('images/Logo PUTR.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-2">
            <h2 class="text-sm font-bold tracking-wider">PUTR CIANJUR</h2>
        </div>

        <nav class="flex-1 mt-6">
            @php
                $dashboardRoute = auth()->user()->hasRole('admin') ? 'admin.dinas.dashboard' : 'admin.bidang.dashboard';
            @endphp

            <a href="{{ route($dashboardRoute) }}"
               class="block py-3 px-6 transition border-l-4 {{ request()->routeIs($dashboardRoute) ? 'bg-blue-800 border-yellow-400 text-white font-bold' : 'border-transparent hover:bg-blue-800 hover:border-blue-700' }}">
                Dashboard
            </a>

            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin_dinas.kelola') }}"
                   class="block py-3 px-6 transition border-l-4 {{ request()->routeIs('admin_dinas.kelola') ? 'bg-blue-800 border-yellow-400 text-white font-bold' : 'border-transparent hover:bg-blue-800 hover:border-blue-700' }}">
                    Kelola Pengaduan
                </a>
                <a href="{{ route('admin.manajemen.user') }}"
                   class="block py-3 px-6 transition border-l-4 {{ request()->routeIs('admin.manajemen.user') ? 'bg-blue-800 border-yellow-400 text-white font-bold' : 'border-transparent hover:bg-blue-800 hover:border-blue-700' }}">
                    Manajemen User
                </a>
            @else
                <a href="{{ route('admin_bidang.tindaklanjuti') }}"
                   class="block py-3 px-6 transition border-l-4 {{ request()->routeIs('admin_bidang.tindaklanjuti') ? 'bg-blue-800 border-yellow-400 text-white font-bold' : 'border-transparent hover:bg-blue-800 hover:border-blue-700' }}">
                    Tindaklanjuti
                </a>
            @endif

            <!-- Tautan Profil untuk semua user -->
            <a href="{{ route('profile.edit') }}"
               class="block py-3 px-6 transition border-l-4 {{ request()->routeIs('profile.edit') ? 'bg-blue-800 border-yellow-400 text-white font-bold' : 'border-transparent hover:bg-blue-800 hover:border-blue-700' }}">
                Profil Saya
            </a>
        </nav>

        <div class="p-6 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-sm font-semibold transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>
</div>

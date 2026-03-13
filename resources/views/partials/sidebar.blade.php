<aside class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white">
        <!-- Logo & Brand -->
        <div class="flex items-center ms-2 h-20 border-b border-gray-700">
            <div class="flex items-center space-x-2">
                <div class="w-12 h-12 from-primary to-secondary rounded-xl flex items-center justify-center">
                    <img src="{{ asset('/img/ppkdjp.jpg') }}" class="rounded-full">
                </div>
                <span class="text-2xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Dashboard
                </span>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            {{-- Beranda / Dashboard --}}
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
               {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50' }}">
                <i class="fas fa-home w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'group-hover:text-primary' }}"></i>
                <span class="ml-2">Beranda</span>
            </a>
            
            {{-- Reservasi --}}
            <a href="{{ route('reservations.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
               {{ request()->routeIs('reservations.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50' }}">
                <i class="fas fa-calendar-check w-6 {{ request()->routeIs('reservations.*') ? 'text-white' : 'group-hover:text-primary' }}"></i>
                <span class="ml-2">Reservasi</span>
            </a>
        
            {{-- Laporan
            <a href="" 
               class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group
               {{ request()->routeIs('reports.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50' }}">
                <i class="fas fa-file-alt w-6 {{ request()->routeIs('reports.*') ? 'text-white' : 'group-hover:text-primary' }}"></i>
                <span class="ml-2">Laporan</span>
            </a> --}}
        
            {{-- Logout Button --}}
            <div class="pt-6 mt-6 border-t border-gray-700">
                <a href="/logout" 
                   class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-red-500/20 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-sign-out-alt w-6 group-hover:text-red-400"></i>
                    <span class="ml-2">Logout</span>
                </a>
            </div>
        </nav>
    </div>
</aside>
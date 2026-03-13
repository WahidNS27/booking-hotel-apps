<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Mobile menu button -->
        <button class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none" onclick="toggleMobileMenu()">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <!-- Search Bar -->
        <div class="flex-1 max-w-md ml-4 md:ml-0">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Cari sesuatu..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200">
            </div>
        </div>
        
        <!-- Right side icons -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:text-primary hover:bg-gray-100 rounded-xl transition-all duration-200">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- Messages -->
            <button class="relative p-2 text-gray-600 hover:text-primary hover:bg-gray-100 rounded-xl transition-all duration-200">
                <i class="fas fa-envelope text-xl"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-primary rounded-full"></span>
            </button>
            
            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded-xl transition-all duration-200">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-primary to-secondary flex items-center justify-center">
                        <span class="text-sm font-bold text-white">
                            {{ substr(session('user'), 0, 1) }}
                        </span>
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ session('user') }}</span>
                    {{-- <i class="fas fa-chevron-down text-xs text-gray-500"></i> --}}
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile menu (hidden by default) -->
<div id="mobileMenu" class="hidden md:hidden bg-white border-b border-gray-200 p-4">
    <a href="{{ route('dashboard') }}" class="block py-2 text-gray-700 hover:text-primary">Beranda</a>
    <a href="{{ route('reservations.index') }}" class="block py-2 text-gray-700 hover:text-primary">Reservasi</a>
    <a href="{{ route('guests.index') }}" class="block py-2 text-gray-700 hover:text-primary">Tamu</a>
    <a href="" class="block py-2 text-gray-700 hover:text-primary">Laporan</a>
</div>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>
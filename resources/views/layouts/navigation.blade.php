<nav class="navbar">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo/Brand -->
            <div class="text-Blue font-bold text-2xl">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">
                    🗺️ WebGIS Wisata Barru
                </a>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('home') }}" class="text-black hover:text-yellow-300 font-medium transition-colors {{ request()->routeIs('home') ? 'text-yellow-300' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('map.index') }}" class="text-black hover:text-yellow-300 font-medium transition-colors {{ request()->routeIs('map.*') ? 'text-yellow-300' : '' }}">
                    Peta Interaktif
                </a>
                <a href="{{ route('destinations.index') }}" class="text-black hover:text-yellow-300 font-medium transition-colors {{ request()->routeIs('destinations.*') ? 'text-yellow-300' : '' }}">
                    Destinasi Wisata
                </a>
                <a href="{{ route('admin.login') }}" class="text-black hover:text-yellow-300 font-medium transition-colors">
                    Login Admin
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button class="text-white hover:text-yellow-300 text-2xl" onclick="toggleMobileMenu()">
                    ☰
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-white/20">
            <div class="flex flex-col space-y-3 pt-4">
                <a href="{{ route('home') }}" class="text-white hover:text-yellow-300 font-medium">Beranda</a>
                <a href="{{ route('map.index') }}" class="text-white hover:text-yellow-300 font-medium">Peta Interaktif</a>
                <a href="{{ route('destinations.index') }}" class="text-white hover:text-yellow-300 font-medium">Destinasi Wisata</a>
                <a href="#" class="text-white hover:text-yellow-300 font-medium">Tentang</a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
}
</script>
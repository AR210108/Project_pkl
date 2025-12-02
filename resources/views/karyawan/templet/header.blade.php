{{-- karyawan/templet/header.blade.php --}}
<header class="w-full max-w-7xl mx-auto mb-8">
    <div class="flex justify-between items-center py-4 border-b border-border-color">
        <h1 class="text-xl sm:text-2xl font-bold text-text-primary">Brand</h1>
        
        <!-- Mobile Menu Button -->
        <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-md text-text-primary hover:bg-slate-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-4 sm:gap-8 font-medium">
            <a class="nav-link relative px-1 py-2 transition-colors duration-300 {{ request()->is('karyawan') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary' }}" href="/karyawan">
                Beranda
                <span class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->is('karyawan') ? 'w-full' : 'w-0' }}"></span>
            </a>
            <a class="nav-link relative px-1 py-2 transition-colors duration-300 {{ request()->is('absensi') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary' }}" href="/absensi">
                Absensi
                <span class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->is('absensi') ? 'w-full' : 'w-0' }}"></span>
            </a>
            <a class="nav-link relative px-1 py-2 transition-colors duration-300 {{ request()->is('list') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary' }}" href="/list">
                Manage Tugas
                <span class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->is('list') ? 'w-full' : 'w-0' }}"></span>
            </a>
        </nav>
        
        <!-- Desktop Logout Button -->
        <form action="/logout" method="POST" class="hidden md:block">
            @csrf
            <button type="submit" class="bg-slate-100 text-text-primary px-4 sm:px-6 py-2 rounded-md font-semibold hover:bg-slate-200 transition-all duration-300 transform hover:scale-105">
                Logout
            </button>
        </form>
    </div>
    
    <!-- Mobile Navigation -->
    <nav id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-b border-border-color relative z-50">
        <div class="flex flex-col space-y-3">
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ request()->is('karyawan') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}" href="/karyawan">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ request()->is('karyawan') ? 'bg-white' : 'bg-transparent' }}"></span>
                    Beranda
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ request()->is('absensi') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}" href="/absensi">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ request()->is('absensi') ? 'bg-white' : 'bg-transparent' }}"></span>
                    Absensi
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ request()->is('list') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}" href="/list">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ request()->is('list') ? 'bg-white' : 'bg-transparent' }}"></span>
                    Manage Tugas
                </div>
            </a>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition-all duration-300 flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 bg-transparent"></span>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</header>

<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            mobileMenu.classList.toggle('hidden');
            console.log('Menu toggled');
        } else {
            console.error('Mobile menu not found');
        }
    }

    // Add animation for navigation links
    document.addEventListener('DOMContentLoaded', function() {
        // Desktop navigation
        const desktopLinks = document.querySelectorAll('.nav-link');
        desktopLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Remove active class from all links
                desktopLinks.forEach(l => {
                    l.classList.remove('text-primary', 'font-semibold');
                    l.classList.add('text-gray-600');
                    
                    // Reset indicator
                    const indicator = l.querySelector('.nav-indicator');
                    if (indicator) {
                        indicator.classList.remove('w-full');
                        indicator.classList.add('w-0');
                    }
                });
                
                // Add active class to clicked link
                this.classList.remove('text-gray-600');
                this.classList.add('text-primary', 'font-semibold');
                
                // Animate indicator
                const indicator = this.querySelector('.nav-indicator');
                if (indicator) {
                    indicator.classList.remove('w-0');
                    indicator.classList.add('w-full');
                }
            });
        });
        
        // Mobile navigation
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Remove active class from all links
                mobileLinks.forEach(l => {
                    l.classList.remove('bg-primary', 'text-white');
                    l.classList.add('text-gray-600');
                    
                    // Reset dot
                    const dot = l.querySelector('.nav-dot');
                    if (dot) {
                        dot.classList.remove('bg-white');
                        dot.classList.add('bg-transparent');
                    }
                });
                
                // Add active class to clicked link
                this.classList.remove('text-gray-600');
                this.classList.add('bg-primary', 'text-white');
                
                // Animate dot
                const dot = this.querySelector('.nav-dot');
                if (dot) {
                    dot.classList.remove('bg-transparent');
                    dot.classList.add('bg-white');
                }
            });
        });
    });
</script>
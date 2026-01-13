{{-- karyawan/templet/header.blade.php --}}
<header class="w-full max-w-7xl mx-auto mb-8">
    <div class="flex justify-between items-center py-4 border-b border-border-color">
        <h1 class="text-xl sm:text-2xl font-bold text-text-primary dark:text-white">Brand</h1>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-4 sm:gap-8 font-medium">
            <a class="nav-link relative px-1 py-2 transition-colors duration-300 {{ request()->is('karyawan/home') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-white' }}" href="/karyawan/home">
                Beranda
                <span class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->is('karyawan/home') ? 'w-full' : 'w-0' }}"></span>
            </a>
            <a class="nav-link relative px-1 py-2 transition-colors duration-300 {{ request()->is('absensi') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-white' }}" href="/absensi">
                Absensi
                <span class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->is('absensi') ? 'w-full' : 'w-0' }}"></span>
            </a>
            <a class="nav-link relative px-1 py-2 transition-colors duration-300 {{ request()->is('karyawan/list') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-white' }}" href="/karyawan/list">
                Manage Tugas
                <span class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->is('karyawan/list') ? 'w-full' : 'w-0' }}"></span>
            </a>
        </nav>
        
        <!-- Desktop Right Side Controls -->
        <div class="hidden md:flex items-center gap-3">
            <!-- Profile -->
            <a href="{{ route('karyawan.profile') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-text-primary dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="hidden sm:block text-sm font-medium text-text-primary dark:text-gray-300">
                    {{ session('karyawan.name') ?? 'Profile' }}
                </span>
            </a>

            <!-- Dark Mode Toggle -->
            <button id="dark-mode-toggle" class="p-2 rounded-md text-text-primary dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors duration-300">
                <svg id="sun-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg id="moon-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            
            <!-- Logout Button -->
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-slate-100 dark:bg-slate-700 text-text-primary dark:text-white px-4 sm:px-6 py-2 rounded-md font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-300 transform hover:scale-105">
                    Logout
                </button>
            </form>
        </div>
        
        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center gap-3">
            <!-- Dark Mode Toggle -->
            <button id="mobile-dark-mode-toggle" class="p-2 rounded-md text-text-primary dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors duration-300">
                <svg id="mobile-sun-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg id="mobile-moon-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            
            <button onclick="toggleMobileMenu()" class="p-2 rounded-md text-text-primary dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-b border-border-color relative z-50">
        <div class="flex flex-col space-y-3">
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ request()->is('karyawan/home') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/karyawan/home">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ request()->is('karyawan/home') ? 'bg-white' : 'bg-transparent' }}"></span>
                    Beranda
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ request()->is('absensi') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/absensi">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ request()->is('absensi') ? 'bg-white' : 'bg-transparent' }}"></span>
                    Absensi
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ request()->is('karyawan/list') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/karyawan/list">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ request()->is('karyawan/list') ? 'bg-white' : 'bg-transparent' }}"></span>
                    Manage Tugas
                </div>
            </a>
            <a href="{{ route('karyawan.profile') }}" class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Profil Saya
                </div>
            </a>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800 transition-all duration-300 flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 bg-transparent"></span>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') document.documentElement.classList.add('dark');

        const desktopDarkModeToggle = document.getElementById('dark-mode-toggle');
        const mobileDarkModeToggle = document.getElementById('mobile-dark-mode-toggle');

        [desktopDarkModeToggle, mobileDarkModeToggle].forEach(toggle => {
            if(toggle) toggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            });
        });

        // Mobile menu auto-close on link click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => toggleMobileMenu());
        });
    });

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    }
</script>

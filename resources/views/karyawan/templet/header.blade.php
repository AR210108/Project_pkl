{{-- karyawan/templet/header.blade.php --}}
@php
 $currentPage = request()->path();
@endphp

<style>
    /* CSS untuk membuat header fiks dan meningkatkan tampilan */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 50;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    /* Tambahkan padding ke body agar konten tidak tertutup header */
    body {
        padding-top: 80px;
    }
    
    /* Mode gelap untuk header */
    .dark .fixed-header {
        background-color: rgba(31, 41, 55, 0.95);
        border-bottom: 1px solid rgba(75, 85, 99, 0.5);
    }
    
    /* Style untuk Brand */
    .brand-link {
        color: #1e293b;
        font-weight: 700;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        position: relative;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }
    
    .brand-link:hover {
        color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .brand-link:active {
        transform: translateY(0);
        background-color: rgba(59, 130, 246, 0.2);
    }
    
    .brand-link.active {
        color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .brand-link.active:hover {
        background-color: rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }
    
    .brand-link::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background-color: rgba(59, 130, 246, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .brand-link:active::after {
        width: 100px;
        height: 100px;
    }
    
    .dark .brand-link {
        color: #f9fafb;
    }
    
    .dark .brand-link:hover {
        color: #60a5fa;
        background-color: rgba(96, 165, 250, 0.1);
    }
    
    .dark .brand-link:active {
        background-color: rgba(96, 165, 250, 0.2);
    }
    
    .dark .brand-link.active {
        color: #60a5fa;
        background-color: rgba(96, 165, 250, 0.1);
        transform: translateY(-1px);
    }
    
    .dark .brand-link.active:hover {
        background-color: rgba(96, 165, 250, 0.15);
        transform: translateY(-2px);
    }
    
    .dark .brand-link::after {
        background-color: rgba(96, 165, 250, 0.3);
    }
    
    /* Efek hover untuk link navigasi - PASTIKAN SELALUAKU BERFUNGSI */
    .nav-link {
        position: relative;
        transition: all 0.2s ease;
        color: #64748b;
        cursor: pointer;
    }
    
    .nav-link .nav-indicator {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        background-color: #3b82f6;
        width: 0;
        transition: width 0.3s ease;
    }
    
    /* Hover effect yang selalu berfungsi untuk SEMUA link */
    .nav-link:hover {
        color: #3b82f6 !important;
    }
    
    .nav-link:hover .nav-indicator {
        width: 100% !important;
    }
    
    /* Style untuk link aktif */
    .nav-link.active {
        color: #3b82f6 !important;
        font-weight: 600;
    }
    
    .nav-link.active .nav-indicator {
        width: 100% !important;
    }
    
    .nav-link.active:hover {
        color: #2563eb !important;
    }
    
    /* Mode gelap */
    .dark .nav-link {
        color: #d1d5db;
    }
    
    .dark .nav-link:hover {
        color: #60a5fa !important;
    }
    
    .dark .nav-link.active {
        color: #60a5fa !important;
    }
    
    .dark .nav-link.active .nav-indicator {
        width: 100% !important;
    }
    
    .dark .nav-link.active:hover {
        color: #3b82f6 !important;
    }
    
    .dark .nav-link .nav-indicator,
    .dark .nav-link:hover .nav-indicator,
    .dark .nav-link.active .nav-indicator {
        background-color: #60a5fa !important;
    }
    
    /* Profile Link */
    .profile-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        color: #1e293b;
    }
    
    .profile-link:hover {
        background-color: #f1f5f9;
    }
    
    .dark .profile-link {
        color: #d1d5db;
    }
    
    .dark .profile-link:hover {
        background-color: #1e293b;
    }
    
    /* Logout Button */
    .logout-button {
        background-color: #f1f5f9;
        color: #1e293b;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 600;
        transition: all 0.3s ease;
        transform: scale(1);
        border: none;
        cursor: pointer;
    }
    
    .logout-button:hover {
        background-color: #e2e8f0;
        transform: scale(1.05);
    }
    
    .dark .logout-button {
        background-color: #1e293b;
        color: #f9fafb;
    }
    
    .dark .logout-button:hover {
        background-color: #374151;
    }
    
    /* Animasi untuk menu mobile */
    .mobile-menu-enter {
        animation: slideDown 0.3s ease forwards;
    }
    
    .mobile-menu-exit {
        animation: slideUp 0.3s ease forwards;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 500px;
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            max-height: 500px;
        }
        to {
            opacity: 0;
            max-height: 0;
        }
    }
</style>

<header class="fixed-header w-full max-w-7xl mx-auto">
    <div class="flex justify-between items-center py-4 px-4 sm:px-6 lg:px-8">
        <!-- Brand dengan link dan efek hover/active -->
        <a href="/karyawan/home" class="brand-link text-xl sm:text-2xl {{ $currentPage === 'karyawan/home' ? 'active' : '' }}">
            Brand
        </a>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-4 sm:gap-8 font-medium">
            <a class="nav-link {{ $currentPage === 'karyawan/home' ? 'active' : '' }} px-1 py-2" href="/karyawan/home">
                Beranda
                <span class="nav-indicator"></span>
            </a>
            <a class="nav-link {{ strpos($currentPage, 'absensi') !== false ? 'active' : '' }} px-1 py-2" href="/absensi">
                Absensi
                <span class="nav-indicator"></span>
            </a>
            <a class="nav-link {{ strpos($currentPage, 'karyawan/list') !== false ? 'active' : '' }} px-1 py-2" href="/karyawan/list">
                Manage Tugas
                <span class="nav-indicator"></span>
            </a>
            <a class="nav-link {{ strpos($currentPage, 'pengajuan_cuti') !== false ? 'active' : '' }} px-1 py-2" href="/karyawan/pengajuan_cuti">
                Manajemen Cuti
                <span class="nav-indicator"></span>
            </a>
        </nav>
        
        <!-- Desktop Right Side Controls -->
        <div class="hidden md:flex items-center gap-3">
            <!-- Profile -->
            <a href="{{ route('karyawan.profile') }}" class="profile-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="hidden sm:block text-sm font-medium">
                    {{ session('karyawan.name') ?? 'Profile' }}
                </span>
            </a>

            <!-- Dark Mode Toggle -->
            <button id="dark-mode-toggle" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors duration-300" style="color: #1e293b;">
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
                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>
        </div>
        
        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center gap-3">
            <!-- Dark Mode Toggle -->
            <button id="mobile-dark-mode-toggle" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors duration-300" style="color: #1e293b;">
                <svg id="mobile-sun-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg id="mobile-moon-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            
            <button onclick="toggleMobileMenu()" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none" style="color: #1e293b;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav id="mobile-menu" class="md:hidden hidden">
        <div class="flex flex-col space-y-3 px-4 pb-4">
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ $currentPage === 'karyawan/home' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/karyawan/home">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ $currentPage === 'karyawan/home' ? 'bg-white' : 'bg-transparent' }}"></span>
                    Beranda
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ strpos($currentPage, 'absensi') !== false ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/absensi">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ strpos($currentPage, 'absensi') !== false ? 'bg-white' : 'bg-transparent' }}"></span>
                    Absensi
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ strpos($currentPage, 'karyawan/list') !== false ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/karyawan/list">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ strpos($currentPage, 'karyawan/list') !== false ? 'bg-white' : 'bg-transparent' }}"></span>
                    Manage Tugas
                </div>
            </a>
            <a class="mobile-nav-link block px-3 py-2 rounded-md transition-all duration-300 {{ strpos($currentPage, 'pengajuan_cuti') !== false ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-800' }}" href="/karyawan/pengajuan_cuti">
                <div class="flex items-center">
                    <span class="nav-dot inline-block w-2 h-2 rounded-full mr-2 {{ strpos($currentPage, 'pengajuan_cuti') !== false ? 'bg-white' : 'bg-transparent' }}"></span>
                    Manajemen Cuti
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
        // Debug: Tampilkan current path di console
        console.log('Current page path:', '{{ $currentPage }}');
        
        // Inisialisasi tema dari localStorage
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        // Fungsi toggle tema
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            // Update ikon
            document.getElementById('sun-icon').style.display = isDark ? 'block' : 'none';
            document.getElementById('moon-icon').style.display = isDark ? 'none' : 'block';
            document.getElementById('mobile-sun-icon').style.display = isDark ? 'block' : 'none';
            document.getElementById('mobile-moon-icon').style.display = isDark ? 'none' : 'block';
        }

        // Event listener untuk tombol toggle
        document.getElementById('dark-mode-toggle').addEventListener('click', toggleTheme);
        document.getElementById('mobile-dark-mode-toggle').addEventListener('click', toggleTheme);
        
        // Set ikon awal
        const isDark = document.documentElement.classList.contains('dark');
        document.getElementById('sun-icon').style.display = isDark ? 'block' : 'none';
        document.getElementById('moon-icon').style.display = isDark ? 'none' : 'block';
        document.getElementById('mobile-sun-icon').style.display = isDark ? 'block' : 'none';
        document.getElementById('mobile-moon-icon').style.display = isDark ? 'none' : 'block';

        // Mobile menu auto-close on link click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => toggleMobileMenu());
        });
        
        // Tambahkan efek klik untuk navigasi
        document.querySelectorAll('.nav-link, .brand-link').forEach(link => {
            link.addEventListener('click', function(e) {
                // Tambahkan efek klik visual
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        // Debug: Cek apakah class active sudah terpasang dengan benar
        document.querySelectorAll('.nav-link').forEach(link => {
            console.log('Link:', link.textContent.trim(), 'Classes:', link.className);
        });
    });

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('mobile-menu-enter');
        } else {
            mobileMenu.classList.remove('mobile-menu-enter');
            mobileMenu.classList.add('mobile-menu-exit');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('mobile-menu-exit');
            }, 300);
        }
    }
</script>
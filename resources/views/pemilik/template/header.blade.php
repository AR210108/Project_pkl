<header class="py-4 md:py-6 flex justify-center items-center border-b border-border-light dark:border-border-dark relative">
    <div class="flex items-center justify-between w-full max-w-7xl px-4">
        <div class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white flex items-center">
            <span class="bg-primary text-white p-2 rounded-lg mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </span>
            Brand
        </div>
        
        <!-- Menu untuk desktop -->
        <nav class="hidden md:flex items-center space-x-4 lg:space-x-8 text-subtext-light dark:text-subtext-dark">
            <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors relative group" href="{{ url('/pemilik') }}">
                Beranda
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors relative group" href="{{ url('/rekap_absen') }}">
                Rekap Absensi
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors relative group" href="{{ url('/laporan') }}">
                Laporan
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors relative group" href="{{ url('/monitoring') }}">
                Monitoring Progres
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors relative group" href="#">
                Surat Kerjasama
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
            </a>
        </nav>
        
        <!-- Tombol logout dan menu mobile -->
        <div class="flex items-center space-x-3">
            <button class="hidden md:flex items-center bg-primary text-white px-4 md:px-6 py-1 md:py-2 rounded-full text-sm md:text-base font-semibold hover:bg-primary-dark transition-colors shadow-md hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
            
            <!-- Tombol menu untuk mobile -->
            <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-subtext-light dark:text-subtext-dark hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Menu mobile dropdown -->
    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-white dark:bg-gray-900 shadow-lg z-50 transition-all duration-300 transform origin-top">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <nav class="flex flex-col space-y-3 text-subtext-light dark:text-subtext-dark">
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center" href="{{ url('/pemilik') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Beranda
                </a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center" href="{{ url('/rekap_absen') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Rekap Absensi
                </a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center" href="{{ url('/laporan') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan
                </a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center" href="{{ url('/monitoring') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Monitoring Progres
                </a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors py-2 px-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center"  href="{{ url('/surat') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Surat Kerjasama
                </a>
                <button class="flex items-center justify-center bg-primary text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md hover:shadow-lg w-full mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Log Out
                </button>
            </nav>
        </div>
    </div>

    <!-- Script untuk toggle menu mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    
                    // Animasi saat menu dibuka
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('animate-fadeIn');
                        setTimeout(() => {
                            mobileMenu.classList.remove('animate-fadeIn');
                        }, 300);
                    }
                });
                
                // Tutup menu saat klik di luar
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</header>
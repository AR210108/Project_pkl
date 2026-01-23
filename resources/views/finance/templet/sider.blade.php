<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Component</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Custom styles untuk transisi */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Animasi hamburger */
        .hamburger-line {
            transition: all 0.3s ease-in-out;
        }
        
        .hamburger-active .line1 {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger-active .line2 {
            opacity: 0;
        }
        
        .hamburger-active .line3 {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Style untuk efek hover yang lebih menonjol */
        .nav-item {
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }
        
        /* Gaya untuk indikator aktif/hover */
        /* Default untuk mobile: di sebelah kanan */
        .nav-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background-color: #000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        /* Override untuk desktop: di sebelah kiri */
        @media (min-width: 768px) {
            .nav-item::before {
                right: auto;
                left: 0;
                transform: translateX(-100%);
            }
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: translateX(0);
        }
        
        /* Gaya untuk item navigasi yang sedang aktif */
        .nav-item.active {
            background-color: #e5e7eb;
            color: #111827 !important;
            font-weight: 600 !important;
        }
        
        /* Memastikan sidebar tetap di posisinya saat scroll dengan ukuran tetap */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            width: 256px !important;
            min-width: 256px !important;
            max-width: 256px !important;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
            flex-shrink: 0 !important;
        }
        
        /* Menyesuaikan konten utama agar tidak tertutup sidebar */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px !important;
                width: calc(100% - 256px) !important;
            }
        }
        
        /* Scrollbar kustom untuk sidebar */
        .sidebar-fixed::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Memastikan teks di sidebar tidak berubah */
        .sidebar-text {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            font-weight: 500 !important;
            color: #374151 !important;
        }
        
        .sidebar-title {
            font-size: 1.5rem !important;
            line-height: 2rem !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
        }
        
        /* Memastikan ikon tidak berubah ukurannya */
        .sidebar-icon {
            font-size: 1.25rem !important;
            width: 1.25rem !important;
            height: 1.25rem !important;
        }
        
        /* Memastikan padding dan margin tidak berubah */
        .sidebar-nav-item {
            padding: 0.625rem 1rem !important;
        }
        
        .sidebar-header {
            height: 5rem !important;
            min-height: 5rem !important;
            max-height: 5rem !important;
        }
        
        .sidebar-footer {
            padding: 1.5rem 1rem !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Tombol Hamburger untuk Mobile (sekarang di kanan) -->
    <button id="hamburger" class="md:hidden fixed top-4 right-4 z-50 p-2 rounded-md bg-white shadow-md">
        <div class="w-6 h-6 flex flex-col justify-center space-y-1">
            <div class="hamburger-line line1 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line2 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line3 w-6 h-0.5 bg-gray-800"></div>
        </div>
    </button>

    <!-- Overlay untuk Mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-fixed bg-white flex flex-col sidebar-transition transform translate-x-full md:translate-x-0 right-0 md:left-0 md:right-auto shadow-lg">
        <!-- Header Sidebar -->
        <div class="sidebar-header flex items-center justify-center border-b border-gray-200 flex-shrink-0">
            <h1 class="sidebar-title">Brand</h1>
        </div>
        
        <!-- Navigasi Utama -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/finance">
                <span class="material-icons sidebar-icon">home</span>
                <span class="sidebar-text">Beranda</span>
            </a>
            
            <!-- Item Pemasukan & Pengeluaran (menggantikan dropdown Keuangan) -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/pemasukan">
                <span class="material-icons sidebar-icon">swap_vert</span>
                <span class="sidebar-text">Pemasukan & <br>Pengeluaran</span>
            </a>
            
            <!-- Dropdown untuk Dokumen -->
            <div class="relative">
                <button class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors w-full text-left" onclick="toggleDropdown('dokumen-dropdown')">
                    <span class="material-icons sidebar-icon">description</span>
                    <span class="sidebar-text">Dokumen</span>
                    <span class="material-icons sidebar-icon ml-auto transition-transform duration-200" id="dokumen-icon">expand_more</span>
                </button>
                
                <div id="dokumen-dropdown" class="pl-6 mt-1 space-y-1 hidden">
                    <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/finance/invoice">
                        <span class="material-icons sidebar-icon">request_quote</span>
                        <span class="sidebar-text">Invoice</span>
                    </a>
                    
                    <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/kwetansi">
                        <span class="material-icons sidebar-icon">receipt_long</span>
                        <span class="sidebar-text">Kwitansi</span>
                    </a>
                </div>
            </div>
            
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/data">
                <span class="material-icons sidebar-icon">list_alt</span>
                <span class="sidebar-text">Data Layanan</span>
            </a>
            
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/pembayaran">
                <span class="material-icons">receipt_long</span>
                <span class="sidebar-text">Data Orderan</span>
            </a>
            
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/karyawann">
                <span class="material-icons sidebar-icon">people</span>
                <span class="sidebar-text">Data Karyawan</span>
            </a>
        </nav>
        
        <!-- Footer Sidebar -->
        <div class="sidebar-footer border-t border-gray-200 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
            <button type="submit" class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-icons sidebar-icon">logout</span>
                <span class="sidebar-text">Log Out</span>
            </button>
            </form>
        </div>
    </aside>

    <script>
        // Fungsi untuk inisialisasi sidebar
        function initSidebar() {
            // Ambil elemen yang diperlukan
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainContent = document.getElementById('main-content');

            // Fungsi untuk membuka sidebar
            function openSidebar() {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                hamburger.classList.add('hamburger-active');
                document.body.style.overflow = 'hidden'; // Mencegah scroll saat sidebar terbuka
            }

            // Fungsi untuk menutup sidebar
            function closeSidebar() {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                hamburger.classList.remove('hamburger-active');
                document.body.style.overflow = ''; // Kembalikan scroll
            }

            // Event listener untuk hamburger
            hamburger.addEventListener('click', () => {
                // Periksa apakah sidebar sedang tersembunyi (untuk mobile)
                if (sidebar.classList.contains('translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            // Event listener untuk overlay
            overlay.addEventListener('click', closeSidebar);

            // Event listener untuk escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !sidebar.classList.contains('translate-x-full')) {
                    closeSidebar();
                }
            });

            // Event listener untuk resize window
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeSidebar(); // Tutup sidebar jika layar menjadi besar
                    mainContent.style.marginLeft = '256px'; // Lebar sidebar yang tetap
                    mainContent.style.width = 'calc(100% - 256px)'; // Lebar konten utama yang tetap
                } else {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                }
            });

            // Event listener untuk menutup sidebar saat link diklik di mobile
            document.querySelectorAll('nav a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeSidebar();
                    }
                });
            });
            
            // Inisialisasi margin konten utama
            if (window.innerWidth >= 768) {
                mainContent.style.marginLeft = '256px'; // Lebar sidebar yang tetap
                mainContent.style.width = 'calc(100% - 256px)'; // Lebar konten utama yang tetap
            }
            
            // Menandai halaman aktif
            setActiveNavItem();
        }

        // Fungsi untuk menandai item navigasi yang aktif
        function setActiveNavItem() {
            const currentUrl = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            // Hapus kelas aktif dari semua item terlebih dahulu
            navItems.forEach(item => item.classList.remove('active'));
            
            // Cari item yang cocok dengan URL saat ini
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && currentUrl === href) {
                    item.classList.add('active');

                    // Jika item ini berada di dalam dropdown, buka dropdownnya
                    const parentDropdown = item.closest('.relative > div');
                    if (parentDropdown) {
                        parentDropdown.classList.remove('hidden');
                        const dropdownButton = parentDropdown.previousElementSibling;
                        if (dropdownButton && dropdownButton.tagName === 'BUTTON') {
                            const icon = dropdownButton.querySelector('.material-icons:last-child');
                            if (icon) {
                                icon.textContent = 'expand_less';
                            }
                        }
                    }
                }
            });
        }

        // Toggle dropdown function
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const icon = document.getElementById(id.replace('-dropdown', '-icon'));

            dropdown.classList.toggle('hidden');

            if (dropdown.classList.contains('hidden')) {
                icon.textContent = 'expand_more';
            } else {
                icon.textContent = 'expand_less';
            }
        }

        // Inisialisasi sidebar saat DOM dimuat
        document.addEventListener('DOMContentLoaded', initSidebar);
    </script>
</body>
</html>
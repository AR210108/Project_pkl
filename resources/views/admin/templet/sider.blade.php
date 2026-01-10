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
            /* Mencegah teks wrap */
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
            /* Warna indikator hitam */
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
            /* Warna latar yang sedikit lebih gelap dari hover */
            color: #111827 !important;
            /* Warna teks yang lebih gelap, dengan !important */
            font-weight: 600 !important;
            /* Menebalkan teks, dengan !important */
        }

        /* Gaya untuk navbar karyawan */
        .employee-navbar {
            background-color: #000;
            color: #fff;
        }

        /* Memastikan sidebar tetap di posisinya saat scroll dengan ukuran tetap */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            width: 256px !important;
            /* Lebar tetap, dengan !important */
            min-width: 256px !important;
            /* Lebar minimum, dengan !important */
            max-width: 256px !important;
            /* Lebar maksimum, dengan !important */
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
            flex-shrink: 0 !important;
            /* Mencegah sidebar mengecil, dengan !important */
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
                /* Lebar sidebar yang tetap, dengan !important */
                width: calc(100% - 256px) !important;
                /* Lebar konten utama disesuaikan, dengan !important */
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

        /* Container untuk memastikan layout tetap */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Memastikan sidebar tidak berubah ukurannya */
        .sidebar-wrapper {
            width: 256px !important;
            /* Lebar tetap, dengan !important */
            min-width: 256px !important;
            /* Lebar minimum, dengan !important */
            max-width: 256px !important;
            /* Lebar maksimum, dengan !important */
            flex-shrink: 0 !important;
            /* Mencegah sidebar mengecil, dengan !important */
        }

        /* Memastikan teks di sidebar tidak berubah */
        .sidebar-text {
            font-size: 0.875rem !important;
            /* 14px, dengan !important */
            line-height: 1.25rem !important;
            /* 20px, dengan !important */
            font-weight: 500 !important;
            /* Medium, dengan !important */
            color: #374151 !important;
            /* Gray-700, dengan !important */
        }

        .sidebar-title {
            font-size: 1.5rem !important;
            /* 24px, dengan !important */
            line-height: 2rem !important;
            /* 32px, dengan !important */
            font-weight: 700 !important;
            /* Bold, dengan !important */
            color: #1f2937 !important;
            /* Gray-800, dengan !important */
        }

        /* Memastikan ikon tidak berubah ukurannya */
        .sidebar-icon {
            font-size: 1.25rem !important;
            /* 20px, dengan !important */
            width: 1.25rem !important;
            /* 20px, dengan !important */
            height: 1.25rem !important;
            /* 20px, dengan !important */
        }

        /* Memastikan padding dan margin tidak berubah */
        .sidebar-nav-item {
            padding: 0.625rem 1rem !important;
            /* 10px 16px, dengan !important */
        }

        .sidebar-header {
            height: 5rem !important;
            /* 80px, dengan !important */
            min-height: 5rem !important;
            /* 80px, dengan !important */
            max-height: 5rem !important;
            /* 80px, dengan !important */
        }

        .sidebar-footer {
            padding: 1.5rem 1rem !important;
            /* 24px 16px, dengan !important */
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

    <!-- Container utama aplikasi -->

    <aside id="sidebar"
        class="sidebar-fixed bg-white flex flex-col sidebar-transition transform translate-x-full md:translate-x-0 right-0 md:left-0 md:right-auto shadow-lg">

        <div class="sidebar-header flex items-center justify-center border-b border-gray-200 flex-shrink-0">
            <h1 class="sidebar-title">Brand</h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Menu Beranda -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="/admin" data-page="admin">
                <span class="material-icons sidebar-icon">home</span>
                <span class="sidebar-text">Beranda</span>
            </a>

            <!-- Menu Data User -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="/data_user" data-page="data_user">
                <span class="material-icons sidebar-icon">person</span>
                <span class="sidebar-text">Data User</span>
            </a>

            <!-- Menu Data Karyawan -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="/admin/data_karyawan" data-page="data_karyawan">
                <span class="material-icons sidebar-icon">group</span>
                <span class="sidebar-text">Data Karyawan</span>
            </a>

            <!-- Menu Data Layanan -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="/admin/layanan" data-page="layanan">
                <span class="material-icons sidebar-icon">miscellaneous_services</span>
                <span class="sidebar-text">Data Layanan</span>
            </a>


            <!-- Menu Surat Kerjasama (Dropdown) -->
            <div class="relative">
                <button
                    class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors w-full text-left"
                    onclick="toggleDropdown('surat-kerjasama-dropdown')" data-page="surat_kerjasama">
                    <span class="material-icons sidebar-icon">description</span>
                    <span class="sidebar-text">Surat Kerjasama</span>
                    <span class="material-icons sidebar-icon ml-auto transition-transform duration-200"
                        id="surat-kerjasama-icon">expand_more</span>
                </button>

                <!-- Dropdown -->
                <div id="surat-kerjasama-dropdown" class="pl-6 mt-1 space-y-1 hidden">
                    <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        href="/template_surat" data-page="template_surat">
                        <span class="material-icons sidebar-icon">article</span>
                        <span class="sidebar-text">Template Surat</span>
                    </a>

                    <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        href="/admin/surat_kerjasama" data-page="list_surat">
                        <span class="material-icons sidebar-icon">list_alt</span>
                        <span class="sidebar-text">List Surat</span>
                    </a>
                </div>
            </div>

            <!-- Catatan Rapat -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="/catatan_rapat" data-page="catatan_rapat">
                <span class="material-icons sidebar-icon">note</span>
                <span class="sidebar-text">Catatan Rapat</span>
            </a>

            <!-- Pengumuman -->
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="/pengumuman" data-page="pengumuman">
                <span class="material-icons sidebar-icon">campaign</span>
                <span class="sidebar-text">Pengumuman</span>
            </a>
        </nav>

        <div class="sidebar-footer border-t border-gray-200 flex-shrink-0">
            <a class="nav-item flex items-center gap-3 sidebar-nav-item text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-icons sidebar-icon">logout</span>
                <span class="sidebar-text">Log Out</span>
            </a>

            <form id="logout-form" action="/logout" method="POST" class="hidden">
                <input type="hidden" name="_token" value="csrf_token_here">
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
            const mainContent = document.querySelector('.main-content');

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
                    if (mainContent) {
                        mainContent.style.marginLeft = '256px'; // Lebar sidebar yang tetap
                        mainContent.style.width = 'calc(100% - 256px)'; // Lebar konten utama yang tetap
                    }
                } else {
                    if (mainContent) {
                        mainContent.style.marginLeft = '0';
                        mainContent.style.width = '100%';
                    }
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
            if (window.innerWidth >= 768 && mainContent) {
                mainContent.style.marginLeft = '256px'; // Lebar sidebar yang tetap
                mainContent.style.width = 'calc(100% - 256px)'; // Lebar konten utama yang tetap
            }

            // Menandai halaman aktif
            setActiveNavItem();
        }

        // Fungsi untuk menandai item navigasi yang aktif
        function setActiveNavItem() {
            // Dapatkan URL saat ini
            const currentUrl = window.location.pathname;

            // Dapatkan semua item navigasi
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                item.classList.remove('active');
                const href = item.getAttribute('href');
                const page = item.getAttribute('data-page');

                // Check if current URL matches the item's href or data-page
                if (currentUrl === href || (page && currentUrl.includes(page))) {
                    item.classList.add('active');

                    // If it's a dropdown button, also open the dropdown
                    if (item.tagName === 'BUTTON') {
                        const dropdownId = item.getAttribute('onclick').match(/toggleDropdown\('([^']+)'\)/)[1];
                        const dropdown = document.getElementById(dropdownId);
                        const icon = document.getElementById(dropdownId.replace('-dropdown', '-icon'));

                        if (dropdown) {
                            dropdown.classList.remove('hidden');
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

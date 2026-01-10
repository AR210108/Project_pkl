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
            background-color: #000; /* Warna indikator */
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
        
        /* 
         * PERUBAHAN 1: Gaya untuk item navigasi yang sedang aktif.
         * Gaya ini akan diterapkan secara dinamis oleh JavaScript.
         * Ini menggantikan warna hitam yang sebelumnya di-hardcode.
        */
        .nav-item.active {
            background-color: #e5e7eb; /* Warna latar yang sedikit lebih gelap dari hover */
            color: #111827; /* Warna teks yang lebih gelap */
            font-weight: 600; /* Menebalkan teks */
        }
        
        /* Gaya untuk navbar karyawan */
        .employee-navbar {
            background-color: #000;
            color: #fff;
        }
        
        /* Memastikan sidebar tetap di posisinya saat scroll */
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }
        
        /* Menyesuaikan konten utama agar tidak tertutup sidebar */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px; /* Lebar sidebar */
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
    <!-- 
      - Mobile: di kanan, tersembunyi dengan translate-x-full
      - Desktop: di kiri, terlihat dengan md:translate-x-0
    -->
    <aside id="sidebar" class="sidebar-fixed w-64 bg-white flex flex-col sidebar-transition transform translate-x-full md:translate-x-0 right-0 md:left-0 md:right-auto shadow-lg">
        
        <div class="h-20 flex items-center justify-center border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Brand</h1>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- 
              PERUBAHAN 2: Menghapus gaya 'bg-black text-white' yang di-hardcode.
              Semua link sekarang memiliki gaya dasar yang sama.
              Kelas 'active' akan ditambahkan secara dinamis oleh JavaScript.
            -->
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/general_manajer" data-page="general_manajer">
                <span class="material-icons">home</span>
                <span>Beranda</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/data_karyawan" data-page="data_karyawan">
                <span class="material-icons">group</span>
                <span>Data Karyawan</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/layanan" data-page="layanan">
                <span class="material-icons">miscellaneous_services</span>
                <span>Data Layanan</span>
            </a>
            <!-- TAMBAHAN: Menu Kelola Order -->
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/general_manajer/orderan" data-page="general_manajer/orderan">
                <span class="material-icons">receipt_long</span>
                <span>Kelola Order</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/kelola_tugas" data-page="kelola_tugas">
                <span class="material-icons">task_alt</span>
                <span>Kelola Tugas</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/kelola_absen" data-page="kelola_absen">
                <span class="material-icons">manage_accounts</span>
                <span>Kelola Absen</span>
            </a>
        </nav>
        
        <div class="px-4 py-6 border-t border-gray-200">
            <a class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" href="/logout">
                <span class="material-icons">logout</span>
                <span>Log Out</span>
            </a>
        </div>
    </aside>

    <script>
        // Fungsi untuk inisialisasi sidebar
        function initSidebar() {
            // Ambil elemen yang diperlukan
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            /*
             * PERUBAHAN 3: Menggunakan querySelector dengan kelas '.main-content' 
             * agar lebih konsisten dengan file dashboard Anda yang menggunakan kelas, bukan ID.
            */
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
                        mainContent.style.marginLeft = '256px'; // Lebar sidebar
                    }
                } else {
                    if (mainContent) {
                        mainContent.style.marginLeft = '0';
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
                mainContent.style.marginLeft = '256px'; // Lebar sidebar
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
            
            /*
             * PERUBAHAN 4: Memperbaiki logika penandaan item aktif.
             * - Pertama, hapus kelas 'active' dari semua item.
             * - Kedua, tambahkan kelas 'active' hanya pada item yang cocok dengan URL saat ini.
             * - Menghapus gaya inline (style.color, style.backgroundColor) dan mempercayakan semuanya pada CSS.
            */
            navItems.forEach(item => {
                item.classList.remove('active');
                const href = item.getAttribute('href');
                
                if (currentUrl === href) {
                    item.classList.add('active');
                }
            });
        }

        // Inisialisasi sidebar saat DOM dimuat
        document.addEventListener('DOMContentLoaded', initSidebar);
    </script>
</body>
</html>
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
        
        <div class="flex-grow p-6">
            <h1 class="text-3xl font-bold mb-12 text-gray-800">Brand</h1>
            <nav class="space-y-2">
                <a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors" href="/finance">
                    <span class="material-icons">home</span>
                    <span>Beranda</span>
                </a>
               <a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors"
   href="{{ route('finance.pemasukan') }}">
    <span class="material-icons">trending_up</span>
    <span>Pemasukan</span>
</a>

<a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors"
   href="{{ route('finance.pengeluaran') }}">
    <span class="material-icons">trending_down</span>
    <span>Pengeluaran</span>
</a>

<a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors"
   href="{{ route('finance.invoice') }}">
    <span class="material-icons">request_quote</span>
    <span>Invoice</span>
</a>

<a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors"
   href="{{ route('finance.kwitansi') }}">
    <span class="material-icons">receipt_long</span>
    <span>Kwitansi</span>
</a>

                <a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors" href="{{ route('finance.data_layanan') }}">
                    <span class="material-icons">list_alt</span>
                    <span>Data Layanan</span>
                </a>
                <a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors" href="{{ route('finance.data_pembayaran') }}">
                    <span class="material-icons">payment</span>
                    <span>Data Pembayaran</span>
                </a>
                <a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors" href="{{ route('finance.daftar_karyawan') }}">
                    <span class="material-icons">payment</span>
                    <span>Data Karyawan</span>
                </a>
            </nav>
        </div>
        <div class="p-6 border-t border-gray-200">
            <a class="nav-item flex items-center space-x-3 text-gray-700 font-medium hover:bg-gray-100 p-3 rounded-lg transition-colors" href="#">
                <span class="material-icons">logout</span>
                <span>Log Out</span>
            </a>
        </div>
    </aside>

    <!-- Konten Utama -->
    <div id="main-content" class="main-content min-h-screen">
       
    </div>

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
                    mainContent.style.marginLeft = '256px'; // Lebar sidebar
                } else {
                    mainContent.style.marginLeft = '0';
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
            
            // Loop melalui setiap item navigasi
            navItems.forEach(item => {
                // Dapatkan href dari link
                const href = item.getAttribute('href');
                
                // Periksa jika URL saat ini cocok dengan href
                if (currentUrl === href) {
                    // Tambahkan kelas active
                    item.classList.add('active');
                    
                    // Ubah warna teks dan background untuk menunjukkan status aktif
                    item.style.color = '#000';
                    item.style.backgroundColor = '#f3f4f6';
                }
            });
        }

        // Inisialisasi sidebar saat DOM dimuat
        document.addEventListener('DOMContentLoaded', initSidebar);
    </script>
</body>
</html>
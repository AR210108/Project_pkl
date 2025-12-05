<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsif Sidebar</title>
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
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Tombol Hamburger untuk Mobile -->
    <button id="hamburger" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-white dark:bg-gray-800 shadow-md">
        <div class="w-6 h-6 flex flex-col justify-center space-y-1">
            <div class="hamburger-line line1 w-6 h-0.5 bg-gray-800 dark:bg-white"></div>
            <div class="hamburger-line line2 w-6 h-0.5 bg-gray-800 dark:bg-white"></div>
            <div class="hamburger-line line3 w-6 h-0.5 bg-gray-800 dark:bg-white"></div>
        </div>
    </button>

    <!-- Overlay untuk Mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white dark:bg-gray-800 flex flex-col p-6 fixed md:static inset-y-0 left-0 z-50 sidebar-transition transform -translate-x-full md:translate-x-0 shadow-lg md:shadow-none">
        <div class="flex-grow">
            <h1 class="text-3xl font-bold mb-12 text-gray-800 dark:text-white">Brand</h1>
            <nav class="space-y-2">
                <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/finance">
                    <span class="material-icons">home</span>
                    <span>Beranda</span>
                </a>
                <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/data">
                    <span class="material-icons">list_alt</span>
                    <span>Data Layanan</span>
                </a>
                <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/pembayaran">
                    <span class="material-icons">payment</span>
                    <span>Data Pembayaran</span>
                </a>
                <!-- Tombol Data Order yang baru ditambahkan -->
                <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/data_in_out">
                    <span class="material-icons">shopping_cart</span>
                    <span>Data Order</span>
                </a>
            </nav>
        </div>
        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
            <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="#">
                <span class="material-icons">logout</span>
                <span>Log Out</span>
            </a>
        </div>
    </aside>

    <script>
        // Ambil elemen yang diperlukan
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        // Fungsi untuk membuka sidebar
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            hamburger.classList.add('hamburger-active');
            document.body.style.overflow = 'hidden'; // Mencegah scroll saat sidebar terbuka
        }

        // Fungsi untuk menutup sidebar
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            hamburger.classList.remove('hamburger-active');
            document.body.style.overflow = ''; // Kembalikan scroll
        }

        // Event listener untuk hamburger
        hamburger.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        // Event listener untuk overlay
        overlay.addEventListener('click', closeSidebar);

        // Event listener untuk escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        // Event listener untuk resize window
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                closeSidebar(); // Tutup sidebar jika layar menjadi besar
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
    </script>
</body>
</html>
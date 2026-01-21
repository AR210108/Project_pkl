<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar GM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease;
        }
        .hamburger-line {
            transition: all 0.3s ease;
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
        .nav-item.active {
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
        }
        .nav-item.active .material-icons {
            color: #1d4ed8;
        }
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            z-index: 40;
        }
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Hamburger Mobile -->
    <button id="hamburger" class="md:hidden fixed top-4 right-4 z-50 p-2 bg-white shadow rounded">
        <div class="w-6 h-6 flex flex-col justify-center space-y-1">
            <div class="hamburger-line line1 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line2 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line3 w-6 h-0.5 bg-gray-800"></div>
        </div>
    </button>

    <!-- Overlay Mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-fixed w-64 bg-white sidebar-transition transform translate-x-full md:translate-x-0 right-0 md:left-0 shadow">
        
        <!-- Header -->
        <div class="h-20 flex items-center justify-center border-b px-4">
            <h1 class="text-xl font-bold text-gray-800">General Manager</h1>
        </div>
        
        <!-- Menu -->
        <nav class="flex-1 px-4 py-6 space-y-1">
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded" href="/general_manajer/home">
                <span class="material-icons">home</span>
                <span>Beranda</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded" href="/general_manajer/data_karyawan">
                <span class="material-icons">group</span>
                <span>Data Karyawan</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded" href="/general_manajer/layanan">
                <span class="material-icons">miscellaneous_services</span>
                <span>Data Layanan</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded" href="/general_manajer/data_project">
                <span class="material-icons">receipt_long</span>
                <span>Data Project</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded" href="/general_manajer/kelola-tugas">
                <span class="material-icons">task_alt</span>
                <span>Kelola Tugas</span>
            </a>
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 rounded" href="/general_manajer/kelola_absen">
                <span class="material-icons">manage_accounts</span>
                <span>Kelola Absen</span>
            </a>
        </nav>
        
        <!-- Logout -->
        <div class="px-4 py-6 border-t">
            <!-- Logout dengan form POST -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded">
                    <span class="material-icons">logout</span>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            function toggleSidebar() {
                const isHidden = sidebar.classList.contains('translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('translate-x-full');
                    overlay.classList.remove('hidden');
                    hamburger.classList.add('hamburger-active');
                } else {
                    sidebar.classList.add('translate-x-full');
                    overlay.classList.add('hidden');
                    hamburger.classList.remove('hamburger-active');
                }
            }
            
            hamburger.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', function() {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                hamburger.classList.remove('hamburger-active');
            });
            
            // Set active nav item
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
            
            // Handle resize
            function handleResize() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('translate-x-full');
                } else {
                    sidebar.classList.add('translate-x-full');
                }
            }
            
            window.addEventListener('resize', handleResize);
            handleResize();
        });
    </script>
</body>
</html>
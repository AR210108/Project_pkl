<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar GM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Base transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Hamburger animation */
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
        
        /* Nav item styles */
        .nav-item {
            position: relative;
            transition: all 0.2s ease;
        }
        
        .nav-item.active {
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
        }
        
        .nav-item.active .material-icons {
            color: #1d4ed8;
        }
        
        /* Active indicator line */
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background-color: #1d4ed8;
            border-radius: 0 2px 2px 0;
        }
        
        /* Hover effect */
        .nav-item:hover:not(.active) {
            background-color: #f3f4f6;
            transform: translateX(4px);
        }
        
        /* Sidebar positioning */
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            z-index: 40;
            overflow-y: auto;
        }
        
        /* Main content adjustment */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px;
            }
        }
        
        /* Custom scrollbar for sidebar */
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
        
        /* Overlay */
        #overlay {
            transition: opacity 0.3s ease;
        }
        
        /* Hamburger button */
        #hamburger {
            z-index: 50;
            transition: transform 0.3s ease;
        }
        
        #hamburger:hover {
            transform: scale(1.05);
        }
        
        /* Logout button hover */
        form button {
            transition: all 0.2s ease;
        }
        
        form button:hover {
            transform: translateX(4px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Hamburger Menu Button (Mobile) -->
    <button id="hamburger" class="md:hidden fixed top-4 right-4 z-50 p-3 bg-white shadow-lg rounded-lg hover:shadow-xl">
        <div class="w-6 h-6 flex flex-col justify-center space-y-1.5">
            <div class="hamburger-line line1 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line2 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line3 w-6 h-0.5 bg-gray-800"></div>
        </div>
    </button>

    <!-- Overlay for Mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-fixed w-64 bg-white sidebar-transition transform translate-x-full md:translate-x-0 right-0 md:left-0 shadow-2xl">
        
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center justify-center border-b border-gray-200 px-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-blue-600">manage_accounts</span>
                </div>
                <h1 class="text-xl font-bold text-gray-800">General Manager</h1>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 rounded-lg" href="/general-manajer/home">
                <span class="material-icons">home</span>
                <span>Beranda</span>
            </a>
            
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 rounded-lg" href="/general-manajer/data-karyawan">
                <span class="material-icons">group</span>
                <span>Data Karyawan</span>
            </a>
            
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 rounded-lg" href="/general-manajer/layanan">
                <span class="material-icons">miscellaneous_services</span>
                <span>Data Layanan</span>
            </a>
            
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 rounded-lg" href="/general-manajer/kelola-order">
                <span class="material-icons">receipt_long</span>
                <span>Data Project</span>
            </a>
            
          
            
            <a class="nav-item flex items-center gap-3 px-4 py-3 text-sm text-gray-700 rounded-lg" href="/general-manajer/kelola-absen">
                <span class="material-icons">manage_accounts</span>
                <span>Kelola Absen</span>
            </a>
        </nav>
        
        <!-- Logout Section -->
        <div class="px-4 py-6 border-t border-gray-200">
            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-all duration-200">
                    <span class="material-icons">logout</span>
                    <span>Log Out</span>
                    <span class="ml-auto material-icons text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
                </button>
            </form>
            
            <!-- User Info (Optional - bisa diisi dengan data user) -->
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="material-icons text-blue-600 text-sm">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-700 truncate">GM User</p>
                        <p class="text-xs text-gray-500 truncate">General Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const currentPath = window.location.pathname;
            
            // Toggle sidebar function
            function toggleSidebar() {
                const isHidden = sidebar.classList.contains('translate-x-full');
                
                if (isHidden) {
                    // Open sidebar
                    sidebar.classList.remove('translate-x-full');
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                    hamburger.classList.add('hamburger-active');
                    
                    // Disable body scroll on mobile
                    if (window.innerWidth < 768) {
                        document.body.style.overflow = 'hidden';
                    }
                } else {
                    // Close sidebar
                    sidebar.classList.add('translate-x-full');
                    overlay.classList.add('opacity-0');
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                    }, 300);
                    hamburger.classList.remove('hamburger-active');
                    
                    // Enable body scroll
                    document.body.style.overflow = '';
                }
            }
            
            // Set active nav item
            document.querySelectorAll('.nav-item').forEach(item => {
                const href = item.getAttribute('href');
                const normalizedHref = href.replace(/\/$/, '');
                const normalizedPath = currentPath.replace(/\/$/, '');
                
                // Exact match or starts with (for nested routes)
                if (normalizedPath === normalizedHref || 
                    normalizedPath.startsWith(normalizedHref + '/')) {
                    item.classList.add('active');
                }
            });
            
            // Event Listeners
            hamburger.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking overlay
            overlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 768 && 
                    !sidebar.contains(event.target) && 
                    !hamburger.contains(event.target) &&
                    !sidebar.classList.contains('translate-x-full')) {
                    toggleSidebar();
                }
            });
            
            // ESC key to close sidebar
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && window.innerWidth < 768) {
                    if (!sidebar.classList.contains('translate-x-full')) {
                        toggleSidebar();
                    }
                }
            });
            
            // Handle window resize
            function handleResize() {
                if (window.innerWidth >= 768) {
                    // Desktop: always show sidebar
                    sidebar.classList.remove('translate-x-full');
                    overlay.classList.add('hidden');
                    hamburger.classList.remove('hamburger-active');
                    document.body.style.overflow = '';
                } else {
                    // Mobile: hide sidebar by default
                    sidebar.classList.add('translate-x-full');
                }
            }
            
            // Add transition to overlay
            overlay.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            
            // Initialize
            handleResize();
            window.addEventListener('resize', handleResize);
            
            // Close sidebar when a nav item is clicked on mobile
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleSidebar();
                    }
                });
            });
            
            // Prevent body scroll when sidebar is open on mobile
            function preventBodyScroll(event) {
                if (window.innerWidth < 768 && !sidebar.classList.contains('translate-x-full')) {
                    event.preventDefault();
                }
            }
            
            // Add touch event listeners for better mobile UX
            let touchStartX = 0;
            let touchEndX = 0;
            
            document.addEventListener('touchstart', function(event) {
                touchStartX = event.changedTouches[0].screenX;
            }, false);
            
            document.addEventListener('touchend', function(event) {
                touchEndX = event.changedTouches[0].screenX;
                handleSwipe();
            }, false);
            
            function handleSwipe() {
                const swipeThreshold = 50;
                const swipeDistance = touchEndX - touchStartX;
                
                // Swipe left to close (only on mobile)
                if (window.innerWidth < 768 && 
                    swipeDistance < -swipeThreshold && 
                    !sidebar.classList.contains('translate-x-full')) {
                    toggleSidebar();
                }
                
                // Swipe right to open (only when sidebar is closed)
                if (window.innerWidth < 768 && 
                    swipeDistance > swipeThreshold && 
                    sidebar.classList.contains('translate-x-full') &&
                    touchStartX < 50) { // Only if swipe starts from edge
                    toggleSidebar();
                }
            }
        });
    </script>
</body>
</html>
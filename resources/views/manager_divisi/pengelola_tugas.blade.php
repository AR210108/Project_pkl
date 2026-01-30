<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        programmer: "#3b82f6",
                        desainer: "#8b5cf6",
                        marketing: "#10b981",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                },
            },
        };
    </script>

    <style>
        /* =========================================
           GLOBAL RESET & TYPOGRAPHY
           ========================================= */
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #f9fafb; color: #1f2937; overflow-x: hidden; }
        .material-icons-outlined { font-size: 24px; vertical-align: middle; width: 1em; height: 1em; }

        /* =========================================
           LAYOUT CONTAINER
           ========================================= */
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* =========================================
           SIDEBAR STYLES
           ========================================= */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 256px;
            background-color: white;
            border-right: 1px solid #e5e7eb;
            z-index: 40;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .sidebar-fixed { transform: translateX(0); }
        }

        .sidebar-fixed.translate-x-0 { transform: translateX(0) !important; }

        .sidebar-header { height: 5rem; min-height: 5rem; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #e5e7eb; flex-shrink: 0; }
        .sidebar-header img { max-height: 3rem; width: auto; object-fit: contain; }
        
        .sidebar-nav { flex: 1; padding: 1.5rem 1rem; overflow-y: auto; }
        
        .nav-item {
            position: relative;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            color: #4b5563;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }
        .nav-item:hover { background-color: #f3f4f6; color: #111827; }

        .nav-item::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background-color: #3b82f6; transform: scaleY(0); transition: transform 0.2s ease;
            border-top-right-radius: 4px; border-bottom-right-radius: 4px;
        }
        .nav-item.active { background-color: #eff6ff; color: #1d4ed8; font-weight: 600; }
        .nav-item.active::before { transform: scaleY(1); }

        .sidebar-footer { padding: 1.5rem; border-top: 1px solid #e5e7eb; }

        /* =========================================
           MAIN CONTENT AREA
           ========================================= */
        .main-content {
            width: 100%;
            min-height: 100vh;
            margin-left: 0;
            transition: margin-left 0.3s ease;
            position: relative;
            z-index: 10;
        }

        @media (min-width: 768px) {
            .main-content { margin-left: 256px; width: calc(100% - 256px); }
        }

        .sidebar-overlay {
            position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 30;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }

        /* =========================================
           UI COMPONENTS
           ========================================= */
        .stat-card {
            background-color: white; border: 1px solid #e5e7eb; border-radius: 0.75rem;
            padding: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: all 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }

        .btn-primary { background-color: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 500; transition: background-color 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary:hover { background-color: #2563eb; }
        
        .btn-secondary { background-color: #f3f4f6; color: #4b5563; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; cursor: pointer; font-weight: 500; transition: all 0.2s; }
        .btn-secondary:hover { background-color: #e5e7eb; color: #1f2937; }

        .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; line-height: 1; }
        
        .status-pending { background-color: #dbeafe; color: #1e40af; }
        .status-proses { background-color: #fef3c7; color: #92400e; }
        .status-selesai { background-color: #d1fae5; color: #065f46; }
        .status-dibatalkan { background-color: #fee2e2; color: #991b1b; }
        
        .badge-programmer { background-color: #dbeafe; color: #1e40af; }
        .badge-desainer { background-color: #ede9fe; color: #5b21b6; }
        .badge-marketing { background-color: #d1fae5; color: #065f46; }

        .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; transition: border-color 0.2s, box-shadow 0.2s; }
        .form-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        /* =========================================
           TABLE & RESPONSIVE TABLE
           ========================================= */
        .panel { background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden; }
        .panel-header { background: #f8fafc; padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .panel-body { padding: 1.5rem; }

        .scrollable-table-container { width: 100%; overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: white; }
        .scrollable-table-container::-webkit-scrollbar { height: 8px; }
        .scrollable-table-container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .scrollable-table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .scrollable-table-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .data-table { width: 100%; min-width: 1000px; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
        .data-table th { background-color: #f9fafb; font-weight: 600; color: #374151; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .data-table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .data-table tbody tr:hover { background-color: #f3f4f6; }
        .truncate-text { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle; }

        /* Pagination */
        .page-btn { transition: all 0.2s ease; }
        .page-btn:hover:not(:disabled) { transform: scale(1.1); }
        .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .desktop-pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 24px; }
        .desktop-page-btn { min-width: 32px; height: 32px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-size: 14px; font-weight: 500; transition: all 0.2s ease; cursor: pointer; }
        .desktop-page-btn.active { background-color: #3b82f6; color: white; }
        .desktop-page-btn:not(.active) { background-color: #f1f5f9; color: #64748b; }
        .desktop-nav-btn { display: flex; justify-content: center; align-items: center; width: 32px; height: 32px; border-radius: 50%; background-color: #f1f5f9; color: #64748b; transition: all 0.2s ease; cursor: pointer; }
        .desktop-nav-btn:hover:not(:disabled) { background-color: #e2e8f0; }
        .desktop-nav-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        @media (max-width: 767px) { 
            .desktop-only { display: none !important; } 
            .mobile-cards { display: block !important; } 
            .desktop-table { display: none !important; } 
            .desktop-pagination { display: none !important; } 
        }
        @media (min-width: 768px) { 
            .mobile-only { display: none !important; } 
            .mobile-cards { display: none !important; } 
            .desktop-table { display: block !important; } 
        }

        .hamburger-line { transition: all 0.3s ease-in-out; transform-origin: center; }
        .hamburger-active .line1 { transform: rotate(45deg) translate(5px, 6px); }
        .hamburger-active .line2 { opacity: 0; }
        .hamburger-active .line3 { transform: rotate(-45deg) translate(5px, -6px); }

        /* Animations */
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .slide-up { animation: slideUp 0.3s ease-out; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    
    <!-- Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- APP CONTAINER -->
    <div class="app-container">
        
        <!-- SIDEBAR SECTION -->
        <aside id="sidebar" class="sidebar-fixed">
            <!-- Sidebar akan diisi oleh template Laravel -->
            @if(auth()->check())
            <div class="sidebar-header">
                <img src="{{ asset('storage/logo.png') }}" alt="Logo" />
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="material-icons-outlined mr-3">dashboard</span>
                    Dashboard
                </a>
                
                <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <span class="material-icons-outlined mr-3">task_alt</span>
                    Kelola Tugas
                </a>
                
                <a href="{{ route('karyawan.index') }}" class="nav-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                    <span class="material-icons-outlined mr-3">people</span>
                    Karyawan
                </a>
                
                <a href="{{ route('absensi.index') }}" class="nav-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <span class="material-icons-outlined mr-3">schedule</span>
                    Absensi
                </a>
                
                <a href="{{ route('pengumuman.index') }}" class="nav-item {{ request()->routeIs('pengumuman.*') ? 'active' : '' }}">
                    <span class="material-icons-outlined mr-3">announcement</span>
                    Pengumuman
                </a>
                
                <div class="mt-auto"></div>
                
                <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <span class="material-icons-outlined mr-3">person</span>
                    Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item text-red-600 hover:bg-red-50">
                        <span class="material-icons-outlined mr-3">logout</span>
                        Logout
                    </a>
                </form>
            </nav>
            
            <div class="sidebar-footer">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-icons-outlined text-primary">person</span>
                    </div>
                    <div>
                        <p class="font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            
            <!-- Hamburger -->
            <button id="hamburgerBtn" class="md:hidden fixed top-4 right-4 z-50 p-2 bg-white rounded-md shadow-md">
                <div class="w-6 h-6 flex flex-col justify-center space-y-1.5" id="hamburgerIcon">
                    <div class="hamburger-line line1 w-6 h-0.5 bg-gray-800"></div>
                    <div class="hamburger-line line2 w-6 h-0.5 bg-gray-800"></div>
                    <div class="hamburger-line line3 w-6 h-0.5 bg-gray-800"></div>
                </div>
            </button>

            <main class="flex-1 flex flex-col">
                <div class="flex-1 p-3 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-8 gap-4">
                        <h2 class="text-xl sm:text-3xl font-bold">
                            Kelola Tugas
                            @if(auth()->user()->divisi)
                            <span class="text-lg text-gray-600">- Divisi {{ auth()->user()->divisi }}</span>
                            @endif
                        </h2>
                        
                        <div class="flex items-center gap-3">
                            @if(in_array(auth()->user()->role, ['general_manager', 'manager_divisi']))
                            <button id="buatTugasBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                                <span class="material-icons-outlined">add</span>
                                <span class="hidden sm:inline">Buat Tugas</span>
                                <span class="sm:hidden">Buat</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">Total Tugas</p>
                                    <p class="text-2xl font-bold text-gray-800" id="totalTasks">0</p>
                                </div>
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <span class="material-icons-outlined text-blue-600">task_alt</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Divisi Anda</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">Dalam Proses</p>
                                    <p class="text-2xl font-bold text-yellow-600" id="inProgressTasks">0</p>
                                </div>
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <span class="material-icons-outlined text-yellow-600">hourglass_empty</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Belum selesai</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">Selesai</p>
                                    <p class="text-2xl font-bold text-green-600" id="completedTasks">0</p>
                                </div>
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <span class="material-icons-outlined text-green-600">check_circle</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Telah diselesaikan</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-600">Terlambat</p>
                                    <p class="text-2xl font-bold text-red-600" id="overdueTasks">0</p>
                                </div>
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <span class="material-icons-outlined text-red-600">warning</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Lewat deadline</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div class="relative w-full md:w-1/3">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari tugas..." type="text" />
                        </div>
                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <select id="statusFilter" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                                <option value="all">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="proses">Dalam Proses</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                            
                            <select id="priorityFilter" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                                <option value="all">Semua Prioritas</option>
                                <option value="high">Tinggi</option>
                                <option value="medium">Sedang</option>
                                <option value="low">Rendah</option>
                            </select>
                            
                            <button id="refreshBtn" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none flex items-center gap-2">
                                <span class="material-icons-outlined">refresh</span>
                                <span class="hidden sm:inline">Refresh</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Table Panel -->
                    <div class="panel fade-in">
                        <div class="panel-header">
                            <h3 class="flex items-center gap-2 font-bold text-gray-800">
                                <span class="material-icons-outlined text-primary">task_alt</span>
                                <span id="panelTitle">Daftar Tugas</span>
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Total: <span class="font-semibold text-gray-800" id="totalCount">0</span> tugas</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <!-- Loading -->
                            <div id="loadingIndicator" class="text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                                <p class="mt-2 text-gray-600">Memuat data...</p>
                            </div>

                            <!-- Desktop Table -->
                            <div class="desktop-table" id="desktopTable" style="display: none;">
                                <div class="scrollable-table-container">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 60px;">No</th>
                                                <th style="min-width: 200px;">Judul</th>
                                                <th style="min-width: 250px;">Deskripsi</th>
                                                <th style="min-width: 120px;">Deadline</th>
                                                <th style="min-width: 150px;">Ditugaskan Kepada</th>
                                                <th style="min-width: 100px;">Divisi</th>
                                                <th style="min-width: 100px;">Status</th>
                                                <th style="min-width: 100px;">Prioritas</th>
                                                <th style="min-width: 180px; text-align: center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="desktopTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Mobile Cards -->
                            <div class="mobile-cards space-y-4" id="mobile-cards" style="display: none;"></div>
                            
                            <!-- No Data -->
                            <div id="noDataMessage" class="text-center py-8" style="display: none;">
                                <span class="material-icons-outlined text-gray-400 text-4xl mb-2">task_alt</span>
                                <p class="text-gray-600">Tidak ada data tugas</p>
                                @if(in_array(auth()->user()->role, ['general_manager', 'manager_divisi']))
                                <button id="buatTugasBtnMobile" class="btn-primary mt-4">
                                    <span class="material-icons-outlined">add</span>
                                    Buat Tugas Pertama
                                </button>
                                @endif
                            </div>
                            
                            <!-- Pagination -->
                            <div id="desktopPaginationContainer" class="desktop-pagination" style="display: none;">
                                <button id="desktopPrevPage" class="desktop-nav-btn"><span class="material-icons-outlined text-sm">chevron_left</span></button>
                                <div id="desktopPageNumbers" class="flex gap-1"></div>
                                <button id="desktopNextPage" class="desktop-nav-btn"><span class="material-icons-outlined text-sm">chevron_right</span></button>
                            </div>
                            
                            <div class="mobile-pagination md:hidden flex justify-center items-center gap-2 mt-4" style="display: none;">
                                <button id="prevPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"><span class="material-icons-outlined text-sm">chevron_left</span></button>
                                <div id="pageNumbers" class="flex gap-1"></div>
                                <button id="nextPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"><span class="material-icons-outlined text-sm">chevron_right</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="text-center p-4 bg-gray-100 text-gray-600 text-sm border-t border-gray-300">
                    Copyright ©{{ date('Y') }} oleh digicity.id
                </footer>
            </main>
        </div>
    </div>

    <!-- Modal Template -->
    <div id="modalTemplate" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white py-2">
                    <h3 class="text-xl font-bold text-gray-800 modal-title"></h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500"><span class="material-icons-outlined">close</span></button>
                </div>
                <div class="modal-content"></div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center z-50">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200"><span class="material-icons-outlined">close</span></button>
    </div>

    <!-- JavaScript -->
    <script>
        // CSRF Token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // State Management
        const state = {
            currentPage: 1,
            itemsPerPage: 10,
            totalPages: 1,
            allTasks: [],
            filteredTasks: [],
            currentUser: {
                id: {{ auth()->id() }},
                name: '{{ auth()->user()->name }}',
                role: '{{ auth()->user()->role }}',
                divisi: '{{ auth()->user()->divisi }}'
            },
            karyawanList: [],
            isLoading: false
        };

        // Utility Functions
        const utils = {
            showToast: (message, type = 'success') => {
                const t = document.getElementById('toast');
                const m = document.getElementById('toastMessage');
                t.style.backgroundColor = type === 'error' ? '#ef4444' : 
                                         type === 'warning' ? '#f59e0b' : 
                                         type === 'info' ? '#3b82f6' : '#10b981';
                m.textContent = message;
                t.classList.remove('translate-y-20', 'opacity-0');
                setTimeout(() => t.classList.add('translate-y-20', 'opacity-0'), 3000);
            },
            
            showLoading: (show) => {
                const els = { 
                    l: document.getElementById('loadingIndicator'), 
                    d: document.getElementById('desktopTable'), 
                    m: document.getElementById('mobile-cards'), 
                    n: document.getElementById('noDataMessage'), 
                    dp: document.getElementById('desktopPaginationContainer'), 
                    mp: document.querySelector('.mobile-pagination') 
                };
                
                if (show) {
                    els.l.style.display = 'block';
                    els.d.style.display = 'none';
                    els.m.style.display = 'none';
                    els.n.style.display = 'none';
                    els.dp.style.display = 'none';
                    els.mp.style.display = 'none';
                } else {
                    els.l.style.display = 'none';
                }
            },
            
            createModal: (title, content, onSubmit = null) => {
                const tpl = document.getElementById('modalTemplate').cloneNode(true);
                tpl.id = 'activeModal';
                tpl.style.display = 'flex';
                tpl.querySelector('.modal-title').textContent = title;
                tpl.querySelector('.modal-content').innerHTML = content;
                
                const closeModal = () => tpl.remove();
                tpl.querySelectorAll('.close-modal').forEach(b => b.addEventListener('click', closeModal));
                tpl.addEventListener('click', (e) => e.target === tpl && closeModal());
                
                if (onSubmit) {
                    const f = tpl.querySelector('form');
                    f?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const formData = new FormData(f);
                        try { 
                            await onSubmit(formData); 
                            closeModal(); 
                        } catch (err) { 
                            console.error(err);
                            utils.showToast(err.message || 'Terjadi kesalahan', 'error');
                        }
                    });
                }
                
                document.body.appendChild(tpl);
                return tpl;
            },
            
            formatDate: (dateString) => {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },
            
            getStatusClass: (status) => {
                return `status-${status}`;
            },
            
            getStatusText: (status) => {
                const statuses = {
                    'pending': 'Pending',
                    'proses': 'Dalam Proses',
                    'selesai': 'Selesai',
                    'dibatalkan': 'Dibatalkan'
                };
                return statuses[status] || status;
            },
            
            getPriorityClass: (priority) => {
                const colors = {
                    'high': 'bg-red-100 text-red-800',
                    'medium': 'bg-yellow-100 text-yellow-800',
                    'low': 'bg-green-100 text-green-800'
                };
                return colors[priority] || 'bg-gray-100 text-gray-800';
            },
            
            getPriorityText: (priority) => {
                const priorities = {
                    'high': 'Tinggi',
                    'medium': 'Sedang',
                    'low': 'Rendah'
                };
                return priorities[priority] || priority;
            },
            
            getDivisiClass: (divisi) => {
                if (!divisi) return 'badge-programmer';
                const d = divisi.toLowerCase();
                if (d.includes('marketing')) return 'badge-marketing';
                if (d.includes('desain')) return 'badge-desainer';
                return 'badge-programmer';
            }
        };

        // API Functions
        const api = {
            request: async (url, options = {}) => {
                const defaultOptions = {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                };
                
                const mergedOptions = { ...defaultOptions, ...options };
                
                try {
                    const response = await fetch(url, mergedOptions);
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                    
                    return data;
                } catch (error) {
                    console.error('API Error:', error);
                    throw error;
                }
            },
            
            fetchTasks: async () => {
                utils.showLoading(true);
                try {
                    const data = await api.request('{{ route("api.tasks.index") }}');
                    state.allTasks = data.tasks || [];
                    state.filteredTasks = [...state.allTasks];
                    render.renderTable();
                    render.updateStats();
                } catch (error) {
                    utils.showToast('Gagal memuat data: ' + error.message, 'error');
                    state.allTasks = [];
                    state.filteredTasks = [];
                    render.renderTable();
                } finally {
                    utils.showLoading(false);
                }
            },
            
            fetchKaryawan: async () => {
                try {
                    const data = await api.request('{{ route("api.users.index") }}?role=karyawan');
                    state.karyawanList = data.users || [];
                } catch (error) {
                    console.error('Failed to fetch karyawan:', error);
                }
            },
            
            createTask: async (formData) => {
                try {
                    const response = await api.request('{{ route("api.tasks.store") }}', {
                        method: 'POST',
                        body: formData
                    });
                    
                    utils.showToast('Tugas berhasil dibuat');
                    await api.fetchTasks();
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            updateTask: async (id, formData) => {
                try {
                    const response = await api.request(`{{ route("api.tasks.update", ":id") }}`.replace(':id', id), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-HTTP-Method-Override': 'PUT'
                        }
                    });
                    
                    utils.showToast('Tugas berhasil diperbarui');
                    await api.fetchTasks();
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            deleteTask: async (id) => {
                try {
                    const response = await api.request(`{{ route("api.tasks.destroy", ":id") }}`.replace(':id', id), {
                        method: 'DELETE'
                    });
                    
                    utils.showToast('Tugas berhasil dihapus');
                    await api.fetchTasks();
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            getTaskDetail: async (id) => {
                try {
                    return await api.request(`{{ route("api.tasks.show", ":id") }}`.replace(':id', id));
                } catch (error) {
                    throw error;
                }
            },
            
            submitTask: async (id, formData) => {
                try {
                    const response = await api.request(`{{ route("api.tasks.submit", ":id") }}`.replace(':id', id), {
                        method: 'POST',
                        body: formData
                    });
                    
                    utils.showToast('Tugas berhasil disubmit');
                    await api.fetchTasks();
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            fetchComments: async (taskId) => {
                try {
                    const data = await api.request(`{{ route("api.tasks.comments", ":id") }}`.replace(':id', taskId));
                    return data.comments || [];
                } catch (error) {
                    console.error('Failed to fetch comments:', error);
                    return [];
                }
            },
            
            storeComment: async (taskId, content) => {
                try {
                    const response = await api.request(`{{ route("api.tasks.comments.store", ":id") }}`.replace(':id', taskId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ content })
                    });
                    
                    utils.showToast('Komentar berhasil ditambahkan');
                    return response;
                } catch (error) {
                    throw error;
                }
            }
        };

        // Render Functions
        const render = {
            filterTasks: () => {
                const search = document.getElementById('searchInput').value.toLowerCase();
                const status = document.getElementById('statusFilter').value;
                const priority = document.getElementById('priorityFilter').value;
                
                state.filteredTasks = state.allTasks.filter(task => {
                    const matchSearch = task.judul?.toLowerCase().includes(search) || 
                                       task.deskripsi?.toLowerCase().includes(search) ||
                                       task.assignee_name?.toLowerCase().includes(search);
                    const matchStatus = status === 'all' || task.status === status;
                    const matchPriority = priority === 'all' || task.priority === priority;
                    
                    return matchSearch && matchStatus && matchPriority;
                });
                
                state.currentPage = 1;
                render.renderTable();
            },
            
            renderTable: () => {
                const start = (state.currentPage - 1) * state.itemsPerPage;
                const end = Math.min(start + state.itemsPerPage, state.filteredTasks.length);
                const tasks = state.filteredTasks.slice(start, end);
                
                document.getElementById('totalCount').textContent = state.filteredTasks.length;
                document.getElementById('panelTitle').textContent = `Tugas ${state.currentUser.divisi}`;
                
                // Render desktop table
                const tbody = document.getElementById('desktopTableBody');
                tbody.innerHTML = '';
                
                if (tasks.length === 0) {
                    document.getElementById('noDataMessage').style.display = 'block';
                    document.getElementById('desktopTable').style.display = 'none';
                    document.getElementById('mobile-cards').style.display = 'none';
                    document.getElementById('desktopPaginationContainer').style.display = 'none';
                    document.querySelector('.mobile-pagination').style.display = 'none';
                    return;
                }
                
                tasks.forEach((task, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${start + index + 1}</td>
                        <td class="font-medium">${task.judul}</td>
                        <td class="truncate-text" title="${task.deskripsi}">${task.deskripsi?.substring(0, 50) || ''}...</td>
                        <td class="${task.is_overdue ? 'text-red-600 font-semibold' : ''}">
                            ${utils.formatDate(task.deadline)}
                            ${task.is_overdue ? '<br><span class="text-xs text-red-500">Terlambat</span>' : ''}
                        </td>
                        <td>${task.assignee_name || '-'}</td>
                        <td><span class="badge ${utils.getDivisiClass(task.target_divisi)}">${task.target_divisi || '-'}</span></td>
                        <td><span class="badge ${utils.getStatusClass(task.status)}">${utils.getStatusText(task.status)}</span></td>
                        <td><span class="badge ${utils.getPriorityClass(task.priority)}">${utils.getPriorityText(task.priority)}</span></td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="modal.showDetail(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Detail">
                                    <span class="material-icons-outlined text-blue-600">visibility</span>
                                </button>
                                ${state.currentUser.role !== 'karyawan' || task.assigned_to == state.currentUser.id ? `
                                <button onclick="modal.showEdit(${task.id})" class="p-2 rounded-full hover:bg-green-100" title="Edit">
                                    <span class="material-icons-outlined text-green-600">edit</span>
                                </button>
                                ` : ''}
                                ${state.currentUser.role === 'general_manager' || 
                                  (state.currentUser.role === 'manager_divisi' && task.target_divisi === state.currentUser.divisi) ? `
                                <button onclick="modal.showDelete(${task.id})" class="p-2 rounded-full hover:bg-red-100" title="Hapus">
                                    <span class="material-icons-outlined text-red-600">delete</span>
                                </button>
                                ` : ''}
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
                
                // Render mobile cards
                const mobileCards = document.getElementById('mobile-cards');
                mobileCards.innerHTML = tasks.map(task => `
                    <div class="bg-white rounded-lg border p-4 shadow-sm">
                        <div class="flex justify-between mb-2">
                            <h4 class="font-semibold text-gray-800">${task.judul}</h4>
                            <div class="flex gap-1">
                                <button onclick="modal.showDetail(${task.id})"><span class="material-icons-outlined text-blue-600">visibility</span></button>
                                ${state.currentUser.role !== 'karyawan' || task.assigned_to == state.currentUser.id ? `
                                <button onclick="modal.showEdit(${task.id})"><span class="material-icons-outlined text-green-600">edit</span></button>
                                ` : ''}
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span class="badge ${utils.getStatusClass(task.status)}">${utils.getStatusText(task.status)}</span>
                            <span class="badge ${utils.getPriorityClass(task.priority)}">${utils.getPriorityText(task.priority)}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2 truncate">${task.deskripsi?.substring(0, 80) || ''}...</p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>${task.assignee_name || 'Tidak ditugaskan'}</span>
                            <span class="${task.is_overdue ? 'text-red-600 font-semibold' : ''}">
                                ${utils.formatDate(task.deadline)}
                            </span>
                        </div>
                    </div>
                `).join('');
                
                // Show appropriate containers
                document.getElementById('noDataMessage').style.display = 'none';
                document.getElementById('desktopTable').style.display = 'block';
                document.getElementById('mobile-cards').style.display = window.innerWidth < 768 ? 'block' : 'none';
                render.updatePagination();
            },
            
            updatePagination: () => {
                state.totalPages = Math.ceil(state.filteredTasks.length / state.itemsPerPage);
                
                // Desktop pagination
                const desktopPages = document.getElementById('desktopPageNumbers');
                desktopPages.innerHTML = '';
                
                for (let i = 1; i <= state.totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = `desktop-page-btn ${i === state.currentPage ? 'active' : ''}`;
                    button.onclick = () => {
                        state.currentPage = i;
                        render.renderTable();
                    };
                    desktopPages.appendChild(button);
                }
                
                document.getElementById('desktopPrevPage').disabled = state.currentPage === 1;
                document.getElementById('desktopNextPage').disabled = state.currentPage === state.totalPages;
                
                // Mobile pagination
                const mobilePages = document.getElementById('pageNumbers');
                mobilePages.innerHTML = '';
                
                for (let i = 1; i <= state.totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = `w-8 h-8 rounded-full flex items-center justify-center text-sm ${i === state.currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'}`;
                    button.onclick = () => {
                        state.currentPage = i;
                        render.renderTable();
                    };
                    mobilePages.appendChild(button);
                }
                
                document.getElementById('prevPage').disabled = state.currentPage === 1;
                document.getElementById('nextPage').disabled = state.currentPage === state.totalPages;
                
                // Show pagination if needed
                const showPagination = state.totalPages > 1;
                document.getElementById('desktopPaginationContainer').style.display = showPagination ? 'flex' : 'none';
                document.querySelector('.mobile-pagination').style.display = (showPagination && window.innerWidth < 768) ? 'flex' : 'none';
            },
            
            updateStats: () => {
                const tasks = state.allTasks;
                
                document.getElementById('totalTasks').textContent = tasks.length;
                document.getElementById('inProgressTasks').textContent = tasks.filter(t => t.status === 'proses').length;
                document.getElementById('completedTasks').textContent = tasks.filter(t => t.status === 'selesai').length;
                document.getElementById('overdueTasks').textContent = tasks.filter(t => t.is_overdue).length;
            }
        };

        // Modal Logic
        const modal = {
            showDetail: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    const comments = await api.fetchComments(id);
                    
                    const commentsHtml = comments.length > 0 ? 
                        comments.map(comment => `
                            <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-sm text-gray-800">${comment.user?.name || 'User'}</span>
                                        <span class="text-xs text-gray-500">${comment.formatted_created_at || ''}</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm">${comment.content}</p>
                            </div>
                        `).join('') : 
                        '<p class="text-gray-500 text-sm italic text-center py-4">Belum ada komentar.</p>';
                    
                    const modalContent = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div><h4 class="text-sm text-gray-600">Judul</h4><p class="font-medium">${task.judul}</p></div>
                                <div><h4 class="text-sm text-gray-600">Deadline</h4><p class="${task.is_overdue ? 'text-red-600 font-semibold' : ''}">${utils.formatDate(task.deadline)}</p></div>
                                <div><h4 class="text-sm text-gray-600">Status</h4><span class="badge ${utils.getStatusClass(task.status)}">${utils.getStatusText(task.status)}</span></div>
                                <div><h4 class="text-sm text-gray-600">Prioritas</h4><span class="badge ${utils.getPriorityClass(task.priority)}">${utils.getPriorityText(task.priority)}</span></div>
                                <div><h4 class="text-sm text-gray-600">Ditugaskan Kepada</h4><p>${task.assignee_name || '-'}</p></div>
                                <div><h4 class="text-sm text-gray-600">Divisi</h4><p>${task.target_divisi || '-'}</p></div>
                            </div>
                            
                            <div><h4 class="text-sm text-gray-600">Deskripsi</h4><p class="mt-1 whitespace-pre-line text-gray-700">${task.deskripsi}</p></div>
                            
                            ${task.catatan ? `
                            <div><h4 class="text-sm text-gray-600">Catatan</h4><p class="mt-1 whitespace-pre-line text-gray-700">${task.catatan}</p></div>
                            ` : ''}
                            
                            ${task.submission_notes ? `
                            <div><h4 class="text-sm text-gray-600">Catatan Submission</h4><p class="mt-1 whitespace-pre-line text-gray-700">${task.submission_notes}</p></div>
                            ` : ''}
                            
                            <!-- Komentar -->
                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-bold text-gray-800 mb-3">Diskusi Tugas</h4>
                                <div id="commentsContainer-${id}" class="max-h-60 overflow-y-auto space-y-2 mb-4">
                                    ${commentsHtml}
                                </div>
                                
                                ${state.currentUser.id == task.assigned_to || state.currentUser.role !== 'karyawan' ? `
                                <form onsubmit="modal.submitComment(event, ${id})" class="mt-2">
                                    <div class="flex gap-2">
                                        <input type="text" name="comment" required placeholder="Tulis komentar..." class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary form-input">
                                        <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-sm">Kirim</button>
                                    </div>
                                </form>
                                ` : ''}
                            </div>
                            
                            ${state.currentUser.id == task.assigned_to && task.status !== 'selesai' && task.status !== 'dibatalkan' ? `
                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-bold text-gray-800 mb-3">Submit Tugas</h4>
                                <form onsubmit="modal.submitTaskForm(event, ${id})">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">File Hasil Tugas</label>
                                            <input type="file" name="submission_file" class="form-input">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Catatan</label>
                                            <textarea name="submission_notes" rows="2" placeholder="Tambahkan catatan..." class="form-input"></textarea>
                                        </div>
                                        <button type="submit" class="btn-primary w-full py-2">Submit Tugas</button>
                                    </div>
                                </form>
                            </div>
                            ` : ''}
                            
                            <button class="close-modal btn-secondary w-full py-2 mt-4">Tutup</button>
                        </div>
                    `;
                    
                    utils.createModal('Detail Tugas', modalContent);
                } catch (error) {
                    utils.showToast('Gagal memuat detail tugas', 'error');
                }
            },
            
            showEdit: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    const karyawanOptions = state.karyawanList
                        .filter(k => k.divisi === state.currentUser.divisi)
                        .map(k => `<option value="${k.id}" ${task.assigned_to == k.id ? 'selected' : ''}>${k.name}</option>`)
                        .join('');
                    
                    const modalContent = `
                        <form id="editTaskForm">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Judul</label>
                                    <input name="judul" value="${task.judul}" class="form-input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" class="form-input" required>${task.deskripsi}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Deadline</label>
                                    <input type="datetime-local" name="deadline" value="${task.deadline ? new Date(task.deadline).toISOString().slice(0, 16) : ''}" class="form-input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Prioritas</label>
                                    <select name="priority" class="form-input" required>
                                        <option value="low" ${task.priority == 'low' ? 'selected' : ''}>Rendah</option>
                                        <option value="medium" ${task.priority == 'medium' ? 'selected' : ''}>Sedang</option>
                                        <option value="high" ${task.priority == 'high' ? 'selected' : ''}>Tinggi</option>
                                    </select>
                                </div>
                                ${state.currentUser.role !== 'karyawan' ? `
                                <div>
                                    <label class="block text-sm font-medium mb-1">Karyawan</label>
                                    <select name="assigned_to" class="form-input" required>
                                        <option value="">Pilih Karyawan</option>
                                        ${karyawanOptions}
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Status</label>
                                    <select name="status" class="form-input" required>
                                        <option value="pending" ${task.status == 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="proses" ${task.status == 'proses' ? 'selected' : ''}>Dalam Proses</option>
                                        <option value="selesai" ${task.status == 'selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="dibatalkan" ${task.status == 'dibatalkan' ? 'selected' : ''}>Dibatalkan</option>
                                    </select>
                                </div>
                                ` : ''}
                                <div>
                                    <label class="block text-sm font-medium mb-1">Catatan</label>
                                    <textarea name="catatan" rows="2" class="form-input">${task.catatan || ''}</textarea>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="button" class="close-modal btn-secondary flex-1 py-2">Batal</button>
                                    <button type="submit" class="btn-primary flex-1 py-2">Update</button>
                                </div>
                            </div>
                        </form>
                    `;
                    
                    const modalEl = utils.createModal('Edit Tugas', modalContent, async (formData) => {
                        await api.updateTask(id, formData);
                    });
                    
                } catch (error) {
                    utils.showToast('Gagal memuat form edit', 'error');
                }
            },
            
            showDelete: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    const modalContent = `
                        <div class="text-center">
                            <span class="material-icons-outlined text-red-600 text-5xl mb-4">warning</span>
                            <h4 class="font-bold mb-2">Hapus Tugas?</h4>
                            <p class="text-gray-600 mb-4">"${task.judul}"</p>
                            <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                            <div class="flex gap-2 justify-center">
                                <button type="button" class="close-modal btn-secondary px-6 py-2">Batal</button>
                                <button id="confirmDelete" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Hapus</button>
                            </div>
                        </div>
                    `;
                    
                    const modalEl = utils.createModal('Konfirmasi Hapus', modalContent);
                    
                    modalEl.querySelector('#confirmDelete').onclick = async () => {
                        try {
                            modalEl.querySelector('#confirmDelete').disabled = true;
                            await api.deleteTask(id);
                            modalEl.remove();
                        } catch (err) {
                            utils.showToast(err.message, 'error');
                        }
                    };
                    
                } catch (error) {
                    utils.showToast('Gagal memuat konfirmasi hapus', 'error');
                }
            },
            
            showCreate: () => {
                const karyawanOptions = state.karyawanList
                    .filter(k => k.divisi === state.currentUser.divisi)
                    .map(k => `<option value="${k.id}">${k.name}</option>`)
                    .join('');
                
                const modalContent = `
                    <form id="createTaskForm">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Judul</label>
                                <input name="judul" class="form-input" required placeholder="Masukkan judul tugas">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" class="form-input" required placeholder="Deskripsi lengkap tugas"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Deadline</label>
                                <input type="datetime-local" name="deadline" class="form-input" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Prioritas</label>
                                <select name="priority" class="form-input" required>
                                    <option value="medium">Sedang</option>
                                    <option value="high">Tinggi</option>
                                    <option value="low">Rendah</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Karyawan</label>
                                <select name="assigned_to" class="form-input" required>
                                    <option value="">Pilih Karyawan</option>
                                    ${karyawanOptions}
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Catatan</label>
                                <textarea name="catatan" rows="2" class="form-input" placeholder="Tambahkan catatan (opsional)"></textarea>
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="button" class="close-modal btn-secondary flex-1 py-2">Batal</button>
                                <button type="submit" class="btn-primary flex-1 py-2">Simpan</button>
                            </div>
                        </div>
                    </form>
                `;
                
                utils.createModal('Buat Tugas Baru', modalContent, async (formData) => {
                    await api.createTask(formData);
                });
            },
            
            submitComment: async (event, taskId) => {
                event.preventDefault();
                const form = event.target;
                const input = form.querySelector('input[name="comment"]');
                
                if (!input.value.trim()) return;
                
                const btn = form.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                try {
                    btn.disabled = true;
                    btn.innerHTML = 'Mengirim...';
                    
                    await api.storeComment(taskId, input.value);
                    
                    input.value = '';
                    
                    // Refresh comments
                    const commentsContainer = document.getElementById(`commentsContainer-${taskId}`);
                    if (commentsContainer) {
                        const comments = await api.fetchComments(taskId);
                        commentsContainer.innerHTML = comments.length > 0 ? 
                            comments.map(comment => `
                                <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-sm text-gray-800">${comment.user?.name || 'User'}</span>
                                            <span class="text-xs text-gray-500">${comment.formatted_created_at || ''}</span>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 text-sm">${comment.content}</p>
                                </div>
                            `).join('') : 
                            '<p class="text-gray-500 text-sm italic text-center py-4">Belum ada komentar.</p>';
                    }
                    
                } catch (error) {
                    utils.showToast('Gagal mengirim komentar', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            },
            
            submitTaskForm: async (event, taskId) => {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                
                const btn = form.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                try {
                    btn.disabled = true;
                    btn.innerHTML = 'Mengirim...';
                    
                    await api.submitTask(taskId, formData);
                    
                } catch (error) {
                    utils.showToast('Gagal submit tugas', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            }
        };

        // Initialization
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar Toggle
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');
            const hamburgerIcon = document.getElementById('hamburgerIcon');

            function toggleSidebar() {
                sidebar.classList.toggle('translate-x-0');
                overlay.classList.toggle('active');
                hamburgerIcon.classList.toggle('hamburger-active');
            }

            if (hamburger) {
                hamburger.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            // Event Listeners
            document.getElementById('searchInput').addEventListener('input', render.filterTasks);
            document.getElementById('statusFilter').addEventListener('change', render.filterTasks);
            document.getElementById('priorityFilter').addEventListener('change', render.filterTasks);
            document.getElementById('refreshBtn').addEventListener('click', () => {
                api.fetchTasks();
                utils.showToast('Data diperbarui');
            });
            
            document.getElementById('closeToast').addEventListener('click', () => {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Pagination Events
            document.getElementById('desktopPrevPage').addEventListener('click', () => {
                if (state.currentPage > 1) {
                    state.currentPage--;
                    render.renderTable();
                }
            });
            
            document.getElementById('desktopNextPage').addEventListener('click', () => {
                if (state.currentPage < state.totalPages) {
                    state.currentPage++;
                    render.renderTable();
                }
            });
            
            document.getElementById('prevPage').addEventListener('click', () => {
                if (state.currentPage > 1) {
                    state.currentPage--;
                    render.renderTable();
                }
            });
            
            document.getElementById('nextPage').addEventListener('click', () => {
                if (state.currentPage < state.totalPages) {
                    state.currentPage++;
                    render.renderTable();
                }
            });
            
            // Create Task Button
            const createBtn = document.getElementById('buatTugasBtn');
            const createBtnMobile = document.getElementById('buatTugasBtnMobile');
            
            if (createBtn) {
                createBtn.addEventListener('click', modal.showCreate);
            }
            
            if (createBtnMobile) {
                createBtnMobile.addEventListener('click', modal.showCreate);
            }
            
            // Load initial data
            const init = async () => {
                await api.fetchKaryawan();
                await api.fetchTasks();
            };
            
            init();
            
            // Auto refresh every 30 seconds
            setInterval(() => {
                if (!state.isLoading) {
                    api.fetchTasks();
                }
            }, 30000);
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (state.filteredTasks.length > 0) {
                    render.renderTable();
                }
            });
        });
    </script>
</body>
</html>
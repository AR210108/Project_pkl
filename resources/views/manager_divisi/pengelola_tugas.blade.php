<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas - Manager Divisi</title>
    
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
        .badge-default { background-color: #f3f4f6; color: #4b5563; }

        .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; transition: border-color 0.2s, box-shadow 0.2s; font-size: 0.875rem; }
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

        .data-table { width: 100%; min-width: 1100px; border-collapse: collapse; }
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
        
        /* Loading */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    
    <!-- Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- APP CONTAINER -->
    <div class="app-container">
        
        <!-- SIDEBAR SECTION -->
        @include('manager_divisi.templet.sider')

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
                        <div>
                            <h2 class="text-xl sm:text-3xl font-bold text-gray-900">
                                Kelola Tugas
                            </h2>
                            @if(auth()->user()->divisi)
                            <p class="text-sm text-gray-600 mt-1">Divisi {{ auth()->user()->divisi }}</p>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-3">
                            @if(in_array(auth()->user()->role, ['general_manager', 'manager_divisi', 'admin']))
                            <button id="buatTugasBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                                <span class="material-icons-outlined text-lg">add</span>
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
                                <div class="loading-spinner mx-auto"></div>
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
                                                <th style="min-width: 150px;">Deadline</th>
                                                <th style="min-width: 150px;">Project</th>
                                                <th style="min-width: 150px;">Ditugaskan Kepada</th>
                                                <th style="min-width: 100px;">Divisi</th>
                                                <th style="min-width: 100px;">Status</th>
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
                                @if(in_array(auth()->user()->role, ['general_manager', 'manager_divisi', 'admin']))
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
                            
                            <div id="mobilePaginationContainer" class="mobile-pagination md:hidden flex justify-center items-center gap-2 mt-4" style="display: none;">
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
        let csrfToken = '';
        try {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                csrfToken = metaTag.getAttribute('content');
            }
        } catch (e) {
            console.error('Failed to get CSRF token:', e);
        }
        
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
                divisi: '{{ auth()->user()->divisi ?? "" }}',
                divisi_id: {{ auth()->user()->divisi_id ? auth()->user()->divisi_id : 'null' }}
            },
            karyawanList: [],
            projectList: [], // Menampung data project
            isLoading: false,
            sortField: 'created_at',
            sortDirection: 'desc'
        };

        // Utility Functions
        const utils = {
            showToast: (message, type = 'success') => {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toastMessage');
                
                const colors = {
                    success: '#10b981',
                    error: '#ef4444',
                    warning: '#f59e0b',
                    info: '#3b82f6'
                };
                
                toast.style.backgroundColor = colors[type] || colors.success;
                toastMessage.textContent = message;
                
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
                
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            },
            
            showLoading: (show) => {
                const loadingIndicator = document.getElementById('loadingIndicator');
                const desktopTable = document.getElementById('desktopTable');
                const mobileCards = document.getElementById('mobile-cards');
                const noDataMessage = document.getElementById('noDataMessage');
                const desktopPagination = document.getElementById('desktopPaginationContainer');
                const mobilePagination = document.getElementById('mobilePaginationContainer');
                
                if (show) {
                    loadingIndicator.style.display = 'block';
                    desktopTable.style.display = 'none';
                    mobileCards.style.display = 'none';
                    noDataMessage.style.display = 'none';
                    desktopPagination.style.display = 'none';
                    mobilePagination.style.display = 'none';
                } else {
                    loadingIndicator.style.display = 'none';
                }
            },
            
            createModal: (title, content, onSubmit = null) => {
                const modalTemplate = document.getElementById('modalTemplate');
                const modalClone = modalTemplate.cloneNode(true);
                modalClone.id = 'activeModal';
                modalClone.classList.remove('hidden');
                modalClone.querySelector('.modal-title').textContent = title;
                modalClone.querySelector('.modal-content').innerHTML = content;
                
                const closeModal = () => {
                    document.body.removeChild(modalClone);
                };
                
                modalClone.querySelectorAll('.close-modal').forEach(button => {
                    button.addEventListener('click', closeModal);
                });
                
                modalClone.addEventListener('click', (e) => {
                    if (e.target === modalClone) {
                        closeModal();
                    }
                });
                
                if (onSubmit) {
                    const form = modalClone.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const formData = new FormData(form);
                            
                            try {
                                await onSubmit(formData);
                                closeModal();
                            } catch (error) {
                                console.error('Modal submit error:', error);
                                utils.showToast(error.message || 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                }
                
                document.body.appendChild(modalClone);
                
                const firstInput = modalClone.querySelector('input, textarea, select');
                if (firstInput) firstInput.focus();
                
                return modalClone;
            },
            
            formatDate: (dateString) => {
                if (!dateString) return '-';
                
                try {
                    const date = new Date(dateString);
                    if (isNaN(date.getTime())) return '-';
                    
                    return date.toLocaleDateString('id-ID', { 
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    return '-';
                }
            },
            
            // Helper untuk format datetime-local input
            formatDateTimeLocal: (dateString) => {
                if (!dateString) return '';
                try {
                    const date = new Date(dateString);
                    // Handle timezone offset agar sesuai waktu lokal
                    const offset = date.getTimezoneOffset() * 60000;
                    const localISOTime = (new Date(date - offset)).toISOString().slice(0, 16);
                    return localISOTime;
                } catch (e) {
                    return '';
                }
            },
            
            getStatusClass: (status) => {
                return `status-${status}`;
            },
            
            getStatusText: (status) => {
                const statusMap = {
                    'pending': 'Pending',
                    'proses': 'Dalam Proses',
                    'selesai': 'Selesai',
                    'dibatalkan': 'Dibatalkan'
                };
                return statusMap[status] || status;
            },
            
            getDivisiClass: (divisi) => {
                if (!divisi) return 'badge-programmer';
                const divisiLower = divisi.toLowerCase();
                if (divisiLower.includes('marketing')) return 'badge-marketing';
                if (divisiLower.includes('desain')) return 'badge-desainer';
                return 'badge-programmer';
            },
            
            escapeHtml: (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            },
            
            truncateText: (text, length = 50) => !text ? '' : text.length > length ? text.substring(0, length) + '...' : text,
            
            checkOverdue: (deadline, status) => {
                if (!deadline || status === 'selesai' || status === 'dibatalkan') return false;
                
                const now = new Date();
                const deadlineDate = new Date(deadline);
                return deadlineDate < now;
            }
        };

        // API Functions
        const api = {
            getApiEndpoint: () => {
                const userRole = state.currentUser.role;
                
                if (userRole === 'manager_divisi') {
                    return '/manager_divisi/api/tasks-api'; 
                } else if (userRole === 'admin') {
                    return '/admin/api/tasks';
                } else if (userRole === 'general_manager') {
                    return '/api/general-manager/tasks';
                } else {
                    console.warn('Role tidak valid untuk tasks:', userRole);
                    return null;
                }
            },
            
            getStatisticsEndpoint: () => {
                const userRole = state.currentUser.role;
                
                if (userRole === 'manager_divisi') {
                    return '/manager_divisi/api/tasks/statistics';
                } else if (userRole === 'admin') {
                    return '/admin/api/tasks/statistics';
                } else if (userRole === 'general_manager') {
                    return '/api/general-manager/tasks/statistics';
                } else {
                    return '/api/tasks/statistics';
                }
            },
            
            getCreateTaskEndpoint: () => {
                const userRole = state.currentUser.role;
                
                if (userRole === 'manager_divisi') {
                    return '/manager_divisi/tasks';
                } else if (userRole === 'admin') {
                    return '/admin/tasks';
                } else if (userRole === 'general_manager') {
                    return '/general_manager/tasks';
                } else {
                    return null;
                }
            },

            // Fetch Projects
            fetchProjects: async () => {
                try {
                    const endpoint = '/manager_divisi/api/projects-dropdown';
                    
                    const response = await fetch(endpoint, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data project');
                    }

                    const data = await response.json();
                    
                    if (data.success && Array.isArray(data.data)) {
                        state.projectList = data.data;
                    } else {
                        state.projectList = []; 
                    }
                } catch (error) {
                    console.error('Failed to fetch projects:', error);
                    state.projectList = [];
                }
            },

            request: async (url, options = {}) => {
                const defaultOptions = {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                };
                
                const mergedOptions = { ...defaultOptions, ...options };
                
                if (options.body instanceof FormData) {
                    delete mergedOptions.headers['Content-Type'];
                } else if (typeof options.body === 'object' && options.body !== null) {
                    mergedOptions.body = JSON.stringify(options.body);
                }
                
                try {
                    const response = await fetch(url, mergedOptions);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                        return data;
                    }
                    
                    const text = await response.text();
                    return text;
                } catch (error) {
                    console.error('API Error:', error);
                    throw error;
                }
            },
            
            fetchTasks: async () => {
                state.isLoading = true;
                utils.showLoading(true);
                
                try {
                    const endpoint = api.getApiEndpoint();
                    
                    if (!endpoint) {
                        throw new Error('Endpoint tidak tersedia untuk role Anda');
                    }
                    
                    const data = await api.request(endpoint);
                    
                    if (Array.isArray(data)) {
                        state.allTasks = data;
                    } else if (data.success === true && data.data) {
                        state.allTasks = data.data;
                    } else if (data.tasks && Array.isArray(data.tasks)) {
                        state.allTasks = data.tasks;
                    } else {
                        state.allTasks = [];
                    }
                    
                    state.allTasks.forEach(task => {
                        task.is_overdue = utils.checkOverdue(task.deadline, task.status);
                    });
                    
                    state.filteredTasks = [...state.allTasks];
                    render.renderTable();
                    await api.fetchStatistics();
                    
                } catch (error) {
                    console.error('Error fetching tasks:', error);
                    utils.showToast('Gagal memuat data tugas', 'error');
                    state.allTasks = [];
                    state.filteredTasks = [];
                    render.renderTable();
                } finally {
                    state.isLoading = false;
                    utils.showLoading(false);
                }
            },
            
            fetchStatistics: async () => {
                try {
                    const endpoint = api.getStatisticsEndpoint();
                    
                    if (!endpoint) {
                        api.calculateStatsFromTasks();
                        return;
                    }
                    
                    const data = await api.request(endpoint);
                    
                    if (data.success !== false) {
                        const stats = data.data || data;
                        document.getElementById('totalTasks').textContent = stats.total || 0;
                        document.getElementById('inProgressTasks').textContent = stats.in_progress || stats.proses || 0;
                        document.getElementById('completedTasks').textContent = stats.completed || stats.selesai || 0;
                        document.getElementById('overdueTasks').textContent = stats.overdue || 0;
                    } else {
                        api.calculateStatsFromTasks();
                    }
                } catch (error) {
                    api.calculateStatsFromTasks();
                }
            },
            
            calculateStatsFromTasks: () => {
                const now = new Date();
                
                const stats = {
                    total: state.allTasks.length,
                    in_progress: state.allTasks.filter(task => 
                        task.status === 'proses' || task.status === 'pending'
                    ).length,
                    completed: state.allTasks.filter(task => 
                        task.status === 'selesai'
                    ).length,
                    overdue: state.allTasks.filter(task => 
                        utils.checkOverdue(task.deadline, task.status)
                    ).length
                };
                
                document.getElementById('totalTasks').textContent = stats.total;
                document.getElementById('inProgressTasks').textContent = stats.in_progress;
                document.getElementById('completedTasks').textContent = stats.completed;
                document.getElementById('overdueTasks').textContent = stats.overdue;
            },
            
            fetchKaryawan: async () => {
                try {
                    const userRole = state.currentUser.role;
                    const divisi = state.currentUser.divisi;
                    const divisiId = state.currentUser.divisi_id;
                    
                    let endpoint;
                    
                    if (userRole === 'manager_divisi') {
                        endpoint = '/manager_divisi/api/karyawan-dropdown';
                    } else {
                        endpoint = '/api/users/data?role=karyawan';
                    }
                    
                    const data = await api.request(endpoint);
                    
                    if (data.success === true && data.data && Array.isArray(data.data)) {
                        state.karyawanList = data.data;
                    } else if (Array.isArray(data)) {
                        state.karyawanList = data;
                    } else if (data.success === true && Array.isArray(data.karyawan)) {
                        state.karyawanList = data.karyawan;
                    } else if (data.data && Array.isArray(data.data)) {
                        state.karyawanList = data.data;
                    } else {
                        state.karyawanList = [];
                    }
                    
                    // Filter Logic
                    if (state.karyawanList.length > 0) {
                        state.karyawanList = state.karyawanList.filter(k => {
                            let karyawanDivisiId = null;
                            
                            if (k.divisi_id) {
                                karyawanDivisiId = k.divisi_id;
                            } else if (k.user && k.user.divisi_id) {
                                karyawanDivisiId = k.user.divisi_id;
                            }
                            
                            if (divisiId && karyawanDivisiId) {
                                if (parseInt(karyawanDivisiId) === parseInt(divisiId)) return true;
                            }
                            
                            if (divisi && divisi.trim() !== "") {
                                let karyawanDivisi = null;
                                if (k.divisi) karyawanDivisi = k.divisi;
                                else if (k.user && k.user.divisi) karyawanDivisi = k.user.divisi;

                                if (karyawanDivisi && karyawanDivisi.toLowerCase() === divisi.toLowerCase()) return true;
                            }
                            
                            return false;
                        });
                    }
                    
                } catch (error) {
                    console.error('Failed to fetch karyawan:', error);
                    utils.showToast('Gagal memuat daftar karyawan', 'error');
                    state.karyawanList = [];
                }
            },
            
            createTask: async (formData) => {
                try {
                    const endpoint = api.getCreateTaskEndpoint();
                    
                    if (!endpoint) {
                        throw new Error('Anda tidak memiliki izin untuk membuat tugas');
                    }
                    
                    const response = await api.request(endpoint, {
                        method: 'POST',
                        body: formData
                    });
                    
                    utils.showToast('Tugas berhasil dibuat', 'success');
                    await api.fetchTasks();
                    
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            updateTask: async (id, formData) => {
                try {
                    const userRole = state.currentUser.role;
                    let endpoint;
                    
                    if (userRole === 'manager_divisi') {
                        endpoint = `/manager_divisi/tasks/${id}`;
                    } else if (userRole === 'admin') {
                        endpoint = `/admin/tasks/${id}`;
                    } else if (userRole === 'general_manager') {
                        endpoint = `/general_manager/tasks/${id}`;
                    } else {
                        throw new Error('Anda tidak memiliki izin untuk mengedit tugas');
                    }
                    
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    
                    const response = await api.request(endpoint, {
                        method: 'PUT',
                        body: data
                    });
                    
                    utils.showToast('Tugas berhasil diperbarui', 'success');
                    await api.fetchTasks();
                    
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            deleteTask: async (id) => {
                try {
                    const userRole = state.currentUser.role;
                    let endpoint;
                    
                    if (userRole === 'manager_divisi') {
                        endpoint = `/manager_divisi/tasks/${id}`;
                    } else if (userRole === 'admin') {
                        endpoint = `/admin/tasks/${id}`;
                    } else if (userRole === 'general_manager') {
                        endpoint = `/general_manager/tasks/${id}`;
                    } else {
                        throw new Error('Anda tidak memiliki izin untuk menghapus tugas');
                    }
                    
                    const response = await api.request(endpoint, {
                        method: 'DELETE'
                    });
                    
                    utils.showToast('Tugas berhasil dihapus', 'success');
                    await api.fetchTasks();
                    
                    return response;
                } catch (error) {
                    throw error;
                }
            },
            
            getTaskDetail: async (id) => {
                try {
                    const userRole = state.currentUser.role;
                    let endpoint;
                    
                    if (userRole === 'manager_divisi') {
                        endpoint = `/manager_divisi/api/tasks/${id}`; 
                    } else if (userRole === 'admin') {
                        endpoint = `/admin/tasks/${id}`;
                    } else if (userRole === 'general_manager') {
                        endpoint = `/general_manager/tasks/${id}`;
                    } else {
                        endpoint = `/api/tasks/${id}`;
                    }
                    
                    const data = await api.request(endpoint);
                    return data.data || data.task || data;
                } catch (error) {
                    throw error;
                }
            }
        };

        // Render Functions
        const render = {
            filterTasks: () => {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                
                state.filteredTasks = state.allTasks.filter(task => {
                    const searchMatch = !searchTerm || 
                        (task.judul && task.judul.toLowerCase().includes(searchTerm)) ||
                        (task.deskripsi && task.deskripsi.toLowerCase().includes(searchTerm)) ||
                        (task.assignee_name && task.assignee_name.toLowerCase().includes(searchTerm)) ||
                        (task.assigned_to_name && task.assigned_to_name.toLowerCase().includes(searchTerm));
                    
                    const statusMatch = statusFilter === 'all' || task.status === statusFilter;
                    
                    return searchMatch && statusMatch;
                });
                
                state.currentPage = 1;
                render.renderTable();
            },
            
            renderTable: () => {
                const startIndex = (state.currentPage - 1) * state.itemsPerPage;
                const endIndex = Math.min(startIndex + state.itemsPerPage, state.filteredTasks.length);
                const currentTasks = state.filteredTasks.slice(startIndex, endIndex);
                
                // Update counts
                document.getElementById('totalCount').textContent = state.filteredTasks.length;
                document.getElementById('panelTitle').textContent = `Daftar Tugas (${state.filteredTasks.length})`;
                
                // Check if no data
                if (state.filteredTasks.length === 0) {
                    document.getElementById('noDataMessage').style.display = 'block';
                    document.getElementById('desktopTable').style.display = 'none';
                    document.getElementById('mobile-cards').style.display = 'none';
                    document.getElementById('desktopPaginationContainer').style.display = 'none';
                    document.getElementById('mobilePaginationContainer').style.display = 'none';
                    return;
                }
                
                // Render desktop table
                const desktopTableBody = document.getElementById('desktopTableBody');
                desktopTableBody.innerHTML = '';
                
                currentTasks.forEach((task, index) => {
                    const rowNumber = startIndex + index + 1;
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    
                    row.innerHTML = `
                        <td class="text-center">${rowNumber}</td>
                        <td class="font-medium text-gray-900">${utils.escapeHtml(task.judul || '')}</td>
                        <td title="${utils.escapeHtml(task.deskripsi || '')}">
                            <div class="truncate-text">${utils.truncateText(task.deskripsi || '', 50)}</div>
                        </td>
                        <td class="${task.is_overdue ? 'text-red-600 font-semibold' : 'text-gray-700'}">
                            ${utils.formatDate(task.deadline)}
                            ${task.is_overdue ? '<br><span class="text-xs text-red-500">Terlambat</span>' : ''}
                        </td>
                        <td>
                            <span class="badge badge-default">
                                ${utils.escapeHtml(task.project_name || '-')}
                            </span>
                        </td>
                        <td>${utils.escapeHtml(task.assignee_name || task.assigned_to_name || '-')}</td>
                        <td>
                            <span class="badge ${utils.getDivisiClass(task.target_divisi || task.divisi)}">
                                ${utils.escapeHtml(task.target_divisi || task.divisi || '-')}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${utils.getStatusClass(task.status)}">
                                ${utils.getStatusText(task.status)}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="modal.showDetail(${task.id})" class="p-2 rounded-full hover:bg-blue-50 transition-colors" title="Detail">
                                    <span class="material-icons-outlined text-blue-600 text-lg">visibility</span>
                                </button>
                                
                                ${state.currentUser.role !== 'karyawan' || task.assigned_to == state.currentUser.id ? `
                                <button onclick="modal.showEdit(${task.id})" class="p-2 rounded-full hover:bg-green-50 transition-colors" title="Edit">
                                    <span class="material-icons-outlined text-green-600 text-lg">edit</span>
                                </button>
                                ` : ''}
                                
                                ${['admin', 'general_manager'].includes(state.currentUser.role) || 
                                  (state.currentUser.role === 'manager_divisi' && task.target_divisi === state.currentUser.divisi) ? `
                                <button onclick="modal.showDelete(${task.id})" class="p-2 rounded-full hover:bg-red-50 transition-colors" title="Hapus">
                                    <span class="material-icons-outlined text-red-600 text-lg">delete</span>
                                </button>
                                ` : ''}
                            </div>
                        </td>
                    `;
                    
                    desktopTableBody.appendChild(row);
                });
                
                // Render mobile cards
                const mobileCards = document.getElementById('mobile-cards');
                mobileCards.innerHTML = currentTasks.map((task, index) => `
                    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">${utils.escapeHtml(task.judul || '')}</h4>
                                <div class="flex items-center gap-2">
                                    <span class="badge ${utils.getStatusClass(task.status)}">
                                        ${utils.getStatusText(task.status)}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        ${utils.formatDate(task.deadline)}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button onclick="modal.showDetail(${task.id})" class="p-1 hover:bg-blue-50 rounded">
                                    <span class="material-icons-outlined text-blue-600">visibility</span>
                                </button>
                                ${state.currentUser.role !== 'karyawan' || task.assigned_to == state.currentUser.id ? `
                                <button onclick="modal.showEdit(${task.id})" class="p-1 hover:bg-green-50 rounded">
                                    <span class="material-icons-outlined text-green-600">edit</span>
                                </button>
                                ` : ''}
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3">${utils.truncateText(task.deskripsi || '', 80)}</p>
                        
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="text-gray-700 font-medium">${utils.escapeHtml(task.assignee_name || task.assigned_to_name || 'Tidak ditugaskan')}</span>
                                <span class="badge ${utils.getDivisiClass(task.target_divisi || task.divisi)} ml-2">
                                    ${utils.escapeHtml(task.target_divisi || task.divisi || '-')}
                                </span>
                            </div>
                            <div class="mt-1">
                                <span class="badge badge-default">
                                    ${utils.escapeHtml(task.project_name || 'Tidak ada Project')}
                                </span>
                            </div>
                            ${task.is_overdue ? '<span class="text-red-600 text-xs font-semibold">Terlambat</span>' : ''}
                        </div>
                    </div>
                `).join('');
                
                document.getElementById('noDataMessage').style.display = 'none';
                document.getElementById('desktopTable').style.display = 'block';
                document.getElementById('mobile-cards').style.display = window.innerWidth < 768 ? 'block' : 'none';
                
                render.updatePagination();
            },
            
            updatePagination: () => {
                state.totalPages = Math.ceil(state.filteredTasks.length / state.itemsPerPage);
                
                const desktopPageNumbers = document.getElementById('desktopPageNumbers');
                desktopPageNumbers.innerHTML = '';
                
                for (let i = 1; i <= state.totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.className = `desktop-page-btn ${i === state.currentPage ? 'active' : ''}`;
                    pageButton.textContent = i;
                    pageButton.addEventListener('click', () => {
                        state.currentPage = i;
                        render.renderTable();
                    });
                    desktopPageNumbers.appendChild(pageButton);
                }
                
                document.getElementById('desktopPrevPage').disabled = state.currentPage === 1;
                document.getElementById('desktopNextPage').disabled = state.currentPage === state.totalPages;
                
                const mobilePageNumbers = document.getElementById('pageNumbers');
                mobilePageNumbers.innerHTML = '';
                
                for (let i = 1; i <= state.totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.className = `w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium ${i === state.currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600 hover:bg-gray-300'}`;
                    pageButton.textContent = i;
                    pageButton.addEventListener('click', () => {
                        state.currentPage = i;
                        render.renderTable();
                    });
                    mobilePageNumbers.appendChild(pageButton);
                }
                
                document.getElementById('prevPage').disabled = state.currentPage === 1;
                document.getElementById('nextPage').disabled = state.currentPage === state.totalPages;
                
                const showPagination = state.totalPages > 1;
                document.getElementById('desktopPaginationContainer').style.display = showPagination ? 'flex' : 'none';
                document.getElementById('mobilePaginationContainer').style.display = (showPagination && window.innerWidth < 768) ? 'flex' : 'none';
            }
        };

        // Modal Functions
        const modal = {
            showDetail: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    
                    const modalContent = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Judul</h4>
                                    <p class="font-medium text-gray-900">${utils.escapeHtml(task.judul || '')}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Deadline</h4>
                                    <p class="${task.is_overdue ? 'text-red-600 font-semibold' : 'text-gray-900'}">
                                        ${utils.formatDate(task.deadline)}
                                        ${task.is_overdue ? '<br><span class="text-xs text-red-500">Terlambat</span>' : ''}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</h4>
                                    <span class="badge ${utils.getStatusClass(task.status)}">
                                        ${utils.getStatusText(task.status)}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ditugaskan Kepada</h4>
                                    <p class="text-gray-900">${utils.escapeHtml(task.assignee_name || task.assigned_to_name || '-')}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Divisi</h4>
                                    <span class="badge ${utils.getDivisiClass(task.target_divisi || task.divisi)}">
                                        ${utils.escapeHtml(task.target_divisi || task.divisi || '-')}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Project</h4>
                                <p class="text-gray-900">${utils.escapeHtml(task.project_name || 'Tidak ada Project')}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</h4>
                                <div class="bg-gray-50 p-3 rounded-lg mt-1">
                                    <p class="text-gray-700 whitespace-pre-line">${utils.escapeHtml(task.deskripsi || 'Tidak ada deskripsi')}</p>
                                </div>
                            </div>
                            
                            ${task.catatan ? `
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Catatan</h4>
                                <div class="bg-gray-50 p-3 rounded-lg mt-1">
                                    <p class="text-gray-700 whitespace-pre-line">${utils.escapeHtml(task.catatan)}</p>
                                </div>
                            ` : ''}
                            
                            <div class="pt-4 border-t">
                                <button class="close-modal btn-secondary w-full py-2">Tutup</button>
                            </div>
                        </div>
                    `;
                    
                    utils.createModal('Detail Tugas', modalContent);
                    
                } catch (error) {
                    console.error('Error showing detail:', error);
                    utils.showToast('Gagal memuat detail tugas', 'error');
                }
            },
            
            showEdit: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    
                    let karyawanOptions = '';
                    let hasKaryawanInDivisi = false;
                    
                    if (state.karyawanList.length > 0) {
                        state.karyawanList.forEach((k) => {
                            const karyawanName = k.name || k.nama || 'Tanpa Nama';
                            const karyawanId = k.id || k.user_id;
                            const isSelected = task.assigned_to == karyawanId ? 'selected' : '';
                            
                            karyawanOptions += `
                                <option value="${karyawanId}" ${isSelected}>
                                    ${utils.escapeHtml(karyawanName)}
                                </option>
                            `;
                            
                            hasKaryawanInDivisi = true;
                        });
                    }
                    
                    let karyawanSelectHtml = '';
                    if (hasKaryawanInDivisi) {
                        karyawanSelectHtml = `
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Ditugaskan Kepada <span class="text-red-500">*</span>
                                </label>
                                <select name="assigned_to" class="form-input w-full" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    ${karyawanOptions}
                                </select>
                            </div>
                        `;
                    } else {
                        karyawanSelectHtml = `
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="material-icons-outlined text-yellow-600">warning</span>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Tidak Ada Karyawan</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Tidak ditemukan karyawan dalam divisi ini.</p>
                                        </div>
                                    </div>
                                <input type="hidden" name="assigned_to" value="">
                            </div>
                        `;
                    }
                    
                    let projectOptions = '';
                    if (state.projectList.length > 0) {
                        state.projectList.forEach(p => {
                            const isSelected = task.project_id == p.id ? 'selected' : '';
                            projectOptions += `<option value="${p.id}" ${isSelected}>${utils.escapeHtml(p.name)}</option>`;
                        });
                    }
                    
                    const modalContent = `
                        <form id="editTaskForm">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                                    <input type="text" name="judul" value="${utils.escapeHtml(task.judul || '')}" 
                                           class="form-input" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                                    <textarea name="deskripsi" rows="3" class="form-input" required>${utils.escapeHtml(task.deskripsi || '')}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                                    <select name="project_id" class="form-input w-full">
                                        <option value="">-- Tidak Ada Project --</option>
                                        ${projectOptions}
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Pilih project terkait (Opsional)</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="deadline" 
                                           value="${utils.formatDateTimeLocal(task.deadline)}" 
                                           class="form-input" required>
                                </div>
                                
                                ${karyawanSelectHtml}
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select name="status" class="form-input" required>
                                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="proses" ${task.status === 'proses' ? 'selected' : ''}>Dalam Proses</option>
                                        <option value="selesai" ${task.status === 'selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="dibatalkan" ${task.status === 'dibatalkan' ? 'selected' : ''}>Dibatalkan</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                    <textarea name="catatan" rows="2" class="form-input">${utils.escapeHtml(task.catatan || '')}</textarea>
                                </div>
                                
                                <div class="flex gap-2 pt-4">
                                    <button type="button" class="close-modal btn-secondary flex-1 py-2">Batal</button>
                                    <button type="submit" class="btn-primary flex-1 py-2">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    `;
                    
                    const modalElement = utils.createModal('Edit Tugas', modalContent, async (formData) => {
                        await api.updateTask(id, formData);
                    });
                    
                } catch (error) {
                    console.error('Error showing edit form:', error);
                    utils.showToast('Gagal memuat form edit', 'error');
                }
            },
            
            showDelete: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    
                    const modalContent = `
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <span class="material-icons-outlined text-red-600 text-2xl">warning</span>
                            </div>
                            
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Hapus Tugas?</h4>
                            <p class="text-gray-600 mb-4">
                                Tugas "<span class="font-medium">${utils.escapeHtml(task.judul || '')}</span>" akan dihapus permanen.
                            </p>
                            <p class="text-sm text-gray-500 mb-6">
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                            
                            <div class="flex gap-3 justify-center">
                                <button type="button" class="close-modal btn-secondary px-6 py-2">Batal</button>
                                <button id="confirmDeleteBtn" 
                                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    `;
                    
                    const modalElement = utils.createModal('Konfirmasi Hapus', modalContent);
                    
                    const confirmDeleteBtn = modalElement.querySelector('#confirmDeleteBtn');
                    confirmDeleteBtn.addEventListener('click', async () => {
                        try {
                            confirmDeleteBtn.disabled = true;
                            confirmDeleteBtn.textContent = 'Menghapus...';
                            
                            await api.deleteTask(id);
                        } catch (error) {
                            console.error('Error deleting task:', error);
                            utils.showToast('Gagal menghapus tugas', 'error');
                            modalElement.querySelector('.close-modal').click();
                        }
                    });
                    
                } catch (error) {
                    console.error('Error showing delete confirmation:', error);
                    utils.showToast('Gagal memuat konfirmasi hapus', 'error');
                }
            },
            
            showCreate: () => {
                try {
                    let karyawanOptions = '';
                    let hasKaryawanInDivisi = false;
                    
                    if (state.karyawanList.length > 0) {
                        state.karyawanList.forEach((k) => {
                            const karyawanName = k.name || k.nama || 'Tanpa Nama';
                            const karyawanId = k.id || k.user_id;
                            
                            karyawanOptions += `
                                <option value="${karyawanId}">
                                    ${utils.escapeHtml(karyawanName)}
                                </option>
                            `;
                            
                            hasKaryawanInDivisi = true;
                        });
                    }
                    
                    let projectOptions = '';
                    if (state.projectList.length > 0) {
                        state.projectList.forEach(p => {
                            const projectName = p.name || 'Tidak Ada Project';
                            const projectId = p.id;
                            
                            projectOptions += `<option value="${projectId}">${utils.escapeHtml(projectName)}</option>`;
                        });
                    }
                    
                    let karyawanSelectHtml = '';
                    if (hasKaryawanInDivisi) {
                        karyawanSelectHtml = `
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Ditugaskan Kepada <span class="text-red-500">*</span>
                                </label>
                                <select name="assigned_to" class="form-input w-full" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    ${karyawanOptions}
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="font-medium">${state.karyawanList.length} karyawan</span> tersedia di divisi ${state.currentUser.divisi}
                                </p>
                            </div>
                        `;
                    } else {
                        karyawanSelectHtml = `
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="material-icons-outlined text-yellow-600">warning</span>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Tidak Ada Karyawan</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Tidak ditemukan karyawan di divisi ini.</p>
                                            <p class="mt-1">Hubungi administrator untuk menambahkan karyawan ke divisi Anda.</p>
                                        </div>
                                    </div>
                                <input type="hidden" name="assigned_to" value="">
                            </div>
                        `;
                    }
                    
                    let projectSelectHtml = `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                            <select id="create_project_id" name="project_id" class="form-input w-full">
                                <option value="">-- Pilih Project --</option>
                                ${projectOptions}
                            </select>
                        </div>
                    `;
                    
                    const modalContent = `
                        <form id="createTaskForm">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                                    <input type="text" name="judul" class="form-input" required 
                                           placeholder="Masukkan judul tugas">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                                    <textarea name="deskripsi" rows="3" class="form-input" required 
                                              placeholder="Deskripsi lengkap tugas"></textarea>
                                </div>
                                
                                ${projectSelectHtml}
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="deadline" class="form-input" required>
                                </div>
                                
                                ${karyawanSelectHtml}
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select name="status" class="form-input" required>
                                        <option value="pending">Pending</option>
                                        <option value="proses">Dalam Proses</option>
                                        <option value="selesai">Selesai</option>
                                        <option value="dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                    <textarea name="catatan" rows="2" class="form-input" 
                                              placeholder="Tambahkan catatan (opsional)"></textarea>
                                </div>
                                
                                <div>
                                    <input type="hidden" name="target_divisi" value="${state.currentUser.divisi}">
                                    <input type="hidden" name="target_divisi_id" value="${state.currentUser.divisi_id}">
                                </div>
                                
                                <div class="flex gap-2 pt-4">
                                    <button type="button" class="close-modal btn-secondary flex-1 py-2">Batal</button>
                                    <button type="submit" class="btn-primary flex-1 py-2">Buat Tugas</button>
                                </div>
                            </div>
                        </form>
                    `;
                    
                    utils.createModal('Buat Tugas Baru', modalContent, async (formData) => {
                        if (!formData.has('target_divisi') && state.currentUser.divisi) {
                            formData.append('target_divisi', state.currentUser.divisi);
                        }
                        await api.createTask(formData);
                    });
                } catch (error) {
                    console.error('Error showing create form:', error);
                    utils.showToast('Gagal memuat form buat tugas', 'error');
                }
            }
        };

        // Initialization
        document.addEventListener('DOMContentLoaded', () => {
            
            const safeAddEventListener = (elementId, eventType, handler) => {
                const element = document.getElementById(elementId);
                if (element && element.addEventListener) {
                    element.addEventListener(eventType, handler);
                } else {
                    console.warn(`Element #${elementId} not found`);
                }
            };

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');
            const hamburgerIcon = document.getElementById('hamburgerIcon');

            if (sidebar && overlay && hamburger && hamburgerIcon) {
                function toggleSidebar() {
                    sidebar.classList.toggle('translate-x-0');
                    overlay.classList.toggle('active');
                    hamburgerIcon.classList.toggle('hamburger-active');
                }
                hamburger.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            safeAddEventListener('searchInput', 'input', () => {
                render.filterTasks();
            });
            
            safeAddEventListener('statusFilter', 'change', () => {
                render.filterTasks();
            });
            
            safeAddEventListener('refreshBtn', 'click', () => {
                api.fetchTasks();
                utils.showToast('Data tugas diperbarui', 'success');
            });
            
            safeAddEventListener('closeToast', 'click', () => {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0');
                }
            });
            
            safeAddEventListener('desktopPrevPage', 'click', () => {
                if (state.currentPage > 1) {
                    state.currentPage--;
                    render.renderTable();
                }
            });
            
            safeAddEventListener('desktopNextPage', 'click', () => {
                if (state.currentPage < state.totalPages) {
                    state.currentPage++;
                    render.renderTable();
                }
            });
            
            safeAddEventListener('prevPage', 'click', () => {
                if (state.currentPage > 1) {
                    state.currentPage--;
                    render.renderTable();
                }
            });
            
            safeAddEventListener('nextPage', 'click', () => {
                if (state.currentPage < state.totalPages) {
                    state.currentPage++;
                    render.renderTable();
                }
            });
            
            safeAddEventListener('buatTugasBtn', 'click', modal.showCreate);
            safeAddEventListener('buatTugasBtnMobile', 'click', modal.showCreate);
            
            window.addEventListener('resize', () => {
                if (state.filteredTasks.length > 0) {
                    render.renderTable();
                }
            });
            
            const init = async () => {
                try {
                    // 1. Load Projects
                    await api.fetchProjects();
                    
                    // 2. Load Karyawan
                    await api.fetchKaryawan();
                    
                    // 3. Load Tasks
                    await api.fetchTasks();
                    
                    if (state.karyawanList.length === 0) {
                        utils.showToast(`Tidak ada karyawan yang tersedia.`, 'warning');
                    }
                    
                } catch (error) {
                    console.error('Error in initialization:', error);
                    utils.showToast('Gagal memuat data awal', 'error');
                }
            };
            
            init();
        });

        window.modal = modal;
    </script>
</body>
</html>
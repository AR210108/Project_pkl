<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas - General Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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
        body { font-family: 'Poppins', sans-serif; }
        .material-icons-outlined { font-size: 24px; vertical-align: middle; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .btn-primary { background-color: #3b82f6; color: white; transition: all 0.2s ease; }
        .btn-primary:hover { background-color: #2563eb; }
        .btn-secondary { background-color: #f1f5f9; color: #64748b; transition: all 0.2s ease; }
        .btn-secondary:hover { background-color: #e2e8f0; }
        .modal { transition: opacity 0.25s ease; }
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-pending { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .status-proses { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .status-selesai { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .status-dibatalkan { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }
        .nav-item::before { content: ''; position: absolute; right: 0; top: 0; height: 100%; width: 3px; background-color: #3b82f6; transform: translateX(100%); transition: transform 0.3s ease; }
        @media (min-width: 768px) { .nav-item::before { right: auto; left: 0; transform: translateX(-100%); } .main-content { margin-left: 256px; } }
        .nav-item:hover::before, .nav-item.active::before { transform: translateX(0); }
        .form-input { border: 1px solid #e2e8f0; transition: all 0.2s ease; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .page-btn { transition: all 0.2s ease; }
        .page-btn:hover:not(:disabled) { transform: scale(1.1); }
        .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .desktop-pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 24px; }
        .desktop-page-btn { min-width: 32px; height: 32px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-size: 14px; font-weight: 500; transition: all 0.2s ease; cursor: pointer; }
        .desktop-page-btn.active { background-color: #3b82f6; color: white; }
        .desktop-page-btn:not(.active) { background-color: #f1f5f9; color: #64748b; }
        .desktop-page-btn:not(.active):hover { background-color: #e2e8f0; }
        .desktop-nav-btn { display: flex; justify-content: center; align-items: center; width: 32px; height: 32px; border-radius: 50%; background-color: #f1f5f9; color: #64748b; transition: all 0.2s ease; cursor: pointer; }
        .desktop-nav-btn:hover:not(:disabled) { background-color: #e2e8f0; }
        .desktop-nav-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .panel { background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); overflow: hidden; border: 1px solid #e2e8f0; }
        .panel-header { background: #f8fafc; padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .panel-title { font-size: 1.125rem; font-weight: 600; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .panel-body { padding: 1.5rem; }
        .scrollable-table-container { width: 100%; overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 0.5rem; background: white; }
        .scrollable-table-container::-webkit-scrollbar { height: 12px; }
        .scrollable-table-container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 6px; }
        .scrollable-table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; border: 2px solid #f1f5f9; }
        .scrollable-table-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .data-table { width: 100%; min-width: 1400px; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .data-table th { background: #f8fafc; font-weight: 600; color: #374151; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .data-table tbody tr:nth-child(even) { background: #f9fafb; }
        .data-table tbody tr:hover { background: #f3f4f6; }
        .truncate-text { max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .assignee-type-btn { border: 2px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; transition: all 0.2s ease; }
        .assignee-type-btn.active { border-color: #3b82f6; background-color: rgba(59, 130, 246, 0.1); }
        .assignee-type-btn:hover:not(.active) { border-color: #cbd5e1; }
        .tab-btn { padding: 8px 16px; border-radius: 6px; transition: all 0.2s ease; }
        .tab-btn.active { background-color: #3b82f6; color: white; }
        .tab-btn:not(.active):hover { background-color: #f1f5f9; }
        .divisi-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-programmer { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .badge-desainer { background-color: rgba(139, 92, 246, 0.15); color: #5b21b6; }
        .badge-marketing { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .priority-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .priority-tinggi { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }
        .priority-normal { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .priority-rendah { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .type-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .type-karyawan { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .type-divisi { background-color: rgba(139, 92, 246, 0.15); color: #5b21b6; }
        .type-manager { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        @media (max-width: 639px) { .desktop-table { display: none; } .mobile-cards { display: block; } .desktop-pagination { display: none !important; } }
        @media (min-width: 640px) { .desktop-table { display: block; } .mobile-cards { display: none; } .mobile-pagination { display: none !important; } }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Fungsi route helper untuk Laravel
        function route(name, params = {}) {
            let url = '';
            
            const routes = {
                // General Manager Routes
                'general_manager.api.tasks': '/general-manajer/api/tasks',
                'general_manager.api.tasks.statistics': '/general-manajer/api/tasks/statistics',
                'general_manager.tasks.store': '/general-manajer/tasks',
                'general_manager.tasks.show': '/general-manajer/tasks/{id}',
                'general_manager.tasks.update': '/general-manajer/tasks/{id}',
                'general_manager.tasks.update.status': '/general-manajer/tasks/{id}/status',
                'general_manager.tasks.assign': '/general-manajer/tasks/{id}/assign',
                
                // Debug routes
                'debug.general_manager.api.tasks': '/general-manajer/api/tasks',
                'debug.general_manager.api.statistics': '/general-manajer/api/tasks/statistics',
                'debug.general_manager.tasks': '/general-manajer/tasks',
            };
            
            if (routes[name]) {
                url = routes[name];
                
                // Ganti parameter
                Object.keys(params).forEach(key => {
                    url = url.replace(`{${key}}`, params[key]);
                });
                
                console.log(`Route: ${name} -> ${url}`);
                return url;
            }
            
            console.error('Route not found:', name);
            return '#';
        }
    </script>
</head>

<body class="font-display bg-gray-50 text-gray-800">
   @include('general_manajer/templet/header')
    <!-- Main Content Container -->
    <div class="main-content">
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Tugas - General Manager</h2>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Tugas</p>
                                <p class="text-2xl font-bold text-gray-800" id="totalTasks">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <span class="material-icons-outlined text-blue-600">task</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-blue-600" id="pendingTasks">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <span class="material-icons-outlined text-blue-600">pending</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Dalam Proses</p>
                                <p class="text-2xl font-bold text-yellow-600" id="progressTasks">0</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <span class="material-icons-outlined text-yellow-600">timeline</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Selesai</p>
                                <p class="text-2xl font-bold text-green-600" id="completedTasks">0</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <span class="material-icons-outlined text-green-600">check_circle</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Dibatalkan</p>
                                <p class="text-2xl font-bold text-red-600" id="cancelledTasks">0</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full">
                                <span class="material-icons-outlined text-red-600">cancel</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-6">
                    <button id="tabMyTasks" class="tab-btn active" data-tab="my-tasks">Tugas Dibuat</button>
                    <button id="tabTeamTasks" class="tab-btn ml-2" data-tab="team-tasks">Tugas Diterima</button>
                    <button id="tabDivisionTasks" class="tab-btn ml-2" data-tab="division-tasks">Tugas Divisi</button>
                </div>
                
                <!-- Search and Filter Section -->
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
                            <option value="tinggi">Tinggi</option>
                            <option value="normal">Normal</option>
                            <option value="rendah">Rendah</option>
                        </select>
                        <select id="typeFilter" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                            <option value="all">Semua Tipe</option>
                            <option value="karyawan">Karyawan</option>
                            <option value="divisi">Divisi</option>
                            <option value="manager">Manager</option>
                        </select>
                        <select id="divisiFilter" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                            <option value="all">Semua Divisi</option>
                            <option value="Programmer">Programmer</option>
                            <option value="Desainer">Desainer</option>
                            <option value="Digital Marketing">Digital Marketing</option>
                        </select>
                        <button id="refreshBtn" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none flex items-center gap-2">
                            <span class="material-icons-outlined">refresh</span>
                            <span class="hidden sm:inline">Refresh</span>
                        </button>
                        <button id="buatTugasBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Buat Tugas Baru</span>
                            <span class="sm:hidden">Buat</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">task_alt</span>
                            <span id="panelTitle">Daftar Tugas</span>
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Total: <span class="font-semibold text-gray-800" id="totalCount">0</span> tugas</span>
                            <span class="text-sm text-gray-600 ml-2">Menampilkan: <span class="font-semibold text-gray-800" id="showingCount">0</span> tugas</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                            <p class="mt-2 text-gray-600">Memuat data...</p>
                        </div>

                        <!-- Desktop Table -->
                        <div class="desktop-table" id="desktopTable" style="display: none;">
                            <div class="scrollable-table-container" id="scrollableTable">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 200px;">Judul</th>
                                            <th style="min-width: 250px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Deadline</th>
                                            <th style="min-width: 150px;">Penerima</th>
                                            <th style="min-width: 100px;">Divisi</th>
                                            <th style="min-width: 100px;">Tipe</th>
                                            <th style="min-width: 100px;">Prioritas</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 180px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                        <!-- Data akan diisi via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards" style="display: none;"></div>
                        
                        <!-- No Data Message -->
                        <div id="noDataMessage" class="text-center py-8" style="display: none;">
                            <span class="material-icons-outlined text-gray-400 text-4xl mb-2">task_alt</span>
                            <p class="text-gray-600">Tidak ada data tugas</p>
                        </div>
                        
                        <!-- Desktop Pagination -->
                        <div id="desktopPaginationContainer" class="desktop-pagination" style="display: none;">
                            <button id="desktopPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="desktopPageNumbers" class="flex gap-1"></div>
                            <button id="desktopNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                        
                        <!-- Mobile Pagination -->
                        <div class="mobile-pagination md:hidden flex justify-center items-center gap-2 mt-4" style="display: none;">
                            <button id="prevPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="pageNumbers" class="flex gap-1"></div>
                            <button id="nextPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-gray-600 text-sm border-t border-gray-300">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Template -->
    <div id="modalTemplate" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white py-2">
                    <h3 class="text-xl font-bold text-gray-800 modal-title"></h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="modal-content"></div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center z-50">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons-outlined">close</span>
        </button>
    </div>

    <script>
        // State Management
        let currentPage = 1;
        let itemsPerPage = 10;
        let totalPages = 1;
        let allTasks = [];
        let filteredTasks = [];
        let currentUser = @json(auth()->user() ?? null);
        let currentDivisi = currentUser?.divisi || '';
        let karyawanList = @json($karyawan ?? []);
        let divisiList = @json($divisi ?? ['Programmer', 'Desainer', 'Digital Marketing']);
        let managerList = @json($managers ?? []);
        let currentTab = 'my-tasks';

        // Debug: Tampilkan informasi route
        console.log('Current User:', currentUser);
        console.log('Karyawan List:', karyawanList.length);
        console.log('Divisi List:', divisiList);
        console.log('Manager List:', managerList.length);
        
        // Test route URLs
        console.log('API Tasks URL:', route('general_manager.api.tasks'));
        console.log('API Statistics URL:', route('general_manager.api.tasks.statistics'));
        console.log('Store Task URL:', route('general_manager.tasks.store'));

        // Utility Functions
        const getStatusClass = (status) => {
            const classes = { 
                'pending': 'status-pending', 
                'proses': 'status-proses', 
                'selesai': 'status-selesai',
                'dibatalkan': 'status-dibatalkan'
            };
            return classes[status] || 'status-pending';
        };

        const getStatusText = (status) => {
            const texts = {
                'pending': 'Pending',
                'proses': 'Dalam Proses',
                'selesai': 'Selesai',
                'dibatalkan': 'Dibatalkan'
            };
            return texts[status] || status;
        };

        const getPriorityClass = (priority) => {
            const classes = {
                'tinggi': 'priority-tinggi',
                'normal': 'priority-normal',
                'rendah': 'priority-rendah'
            };
            return classes[priority] || 'priority-normal';
        };

        const getPriorityText = (priority) => {
            const texts = {
                'tinggi': 'Tinggi',
                'normal': 'Normal',
                'rendah': 'Rendah'
            };
            return texts[priority] || priority;
        };

        const getDivisiClass = (divisi) => {
            const classes = {
                'Programmer': 'badge-programmer',
                'Desainer': 'badge-desainer',
                'Digital Marketing': 'badge-marketing'
            };
            return classes[divisi] || 'badge-programmer';
        };

        const getTypeClass = (type) => {
            const classes = {
                'karyawan': 'type-karyawan',
                'divisi': 'type-divisi',
                'manager': 'type-manager'
            };
            return classes[type] || 'type-karyawan';
        };

        const getTypeText = (type) => {
            const texts = {
                'karyawan': 'Karyawan',
                'divisi': 'Divisi',
                'manager': 'Manager'
            };
            return texts[type] || type;
        };

        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        };

        const formatDateTime = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const showToast = (message, type = 'success') => {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            if (type === 'error') {
                toast.style.backgroundColor = '#ef4444';
            } else if (type === 'warning') {
                toast.style.backgroundColor = '#f59e0b';
            } else {
                toast.style.backgroundColor = '#10b981';
            }
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        };

        const showLoading = (show) => {
            const loading = document.getElementById('loadingIndicator');
            const desktopTable = document.getElementById('desktopTable');
            const mobileCards = document.getElementById('mobile-cards');
            const noData = document.getElementById('noDataMessage');
            const desktopPagination = document.getElementById('desktopPaginationContainer');
            const mobilePagination = document.querySelector('.mobile-pagination');
            
            if (show) {
                loading.style.display = 'block';
                desktopTable.style.display = 'none';
                mobileCards.style.display = 'none';
                noData.style.display = 'none';
                desktopPagination.style.display = 'none';
                mobilePagination.style.display = 'none';
            } else {
                loading.style.display = 'none';
            }
        };

        const createModal = (title, content, onSubmit = null, onClose = null) => {
            const template = document.getElementById('modalTemplate').cloneNode(true);
            template.id = 'activeModal';
            template.style.display = 'flex';
            template.querySelector('.modal-title').textContent = title;
            template.querySelector('.modal-content').innerHTML = content;
            
            template.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (onClose) onClose();
                    template.remove();
                });
            });
            
            template.addEventListener('click', (e) => {
                if (e.target === template) {
                    if (onClose) onClose();
                    template.remove();
                }
            });
            
            if (onSubmit) {
                const form = template.querySelector('form');
                if (form) {
                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const formData = new FormData(form);
                        const data = Object.fromEntries(formData.entries());
                        
                        try {
                            await onSubmit(data);
                            template.remove();
                        } catch (error) {
                            console.error('Form submit error:', error);
                        }
                    });
                }
            }
            
            document.body.appendChild(template);
            return template;
        };

        // API Functions - MENGGUNAKAN route() helper
        const fetchTasks = async () => {
            try {
                showLoading(true);
                console.log('Fetching tasks...');
                
                const url = route('general_manager.api.tasks');
                console.log('Fetch URL:', url);
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                console.log('Response status:', response.status, response.statusText);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response error text:', errorText);
                    throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                }
                
                const result = await response.json();
                console.log('Response data:', result);
                
                if (Array.isArray(result)) {
                    allTasks = result;
                    filteredTasks = [...allTasks];
                    console.log(`Loaded ${allTasks.length} tasks`);
                    renderTable();
                    updateStatistics();
                } else {
                    console.error('Invalid response format:', result);
                    throw new Error('Invalid response format - expected array');
                }
            } catch (error) {
                console.error('Error fetching tasks:', error);
                showToast('Gagal memuat data tugas: ' + error.message, 'error');
                allTasks = [];
                filteredTasks = [];
                renderTable();
            } finally {
                showLoading(false);
            }
        };

        const updateStatistics = async () => {
            try {
                console.log('Fetching statistics...');
                const url = route('general_manager.api.tasks.statistics');
                console.log('Statistics URL:', url);
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                console.log('Statistics response:', response.status, response.statusText);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Statistics response error:', errorText);
                    throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                }
                
                const result = await response.json();
                console.log('Statistics data:', result);
                
                document.getElementById('totalTasks').textContent = result.total || 0;
                document.getElementById('pendingTasks').textContent = result.pending || 0;
                document.getElementById('progressTasks').textContent = result.in_progress || 0;
                document.getElementById('completedTasks').textContent = result.completed || 0;
                document.getElementById('cancelledTasks').textContent = result.cancelled || 0;
            } catch (error) {
                console.error('Error fetching statistics:', error);
                // Tidak perlu showToast untuk statistics error
            }
        };

        const createTask = async (data) => {
            try {
                console.log('Creating task with data:', data);
                const url = route('general_manager.tasks.store');
                console.log('Create URL:', url);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                console.log('Create response:', response.status, response.statusText);
                
                if (!response.ok) {
                    let errorMessage = `Failed to create task: ${response.status} ${response.statusText}`;
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        // Jika response bukan JSON
                    }
                    throw new Error(errorMessage);
                }
                
                const result = await response.json();
                console.log('Create result:', result);
                
                if (result.success) {
                    showToast('Tugas berhasil dibuat');
                    await fetchTasks();
                    await updateStatistics();
                } else {
                    throw new Error(result.message || 'Failed to create task');
                }
            } catch (error) {
                console.error('Error creating task:', error);
                showToast('Gagal membuat tugas: ' + error.message, 'error');
                throw error;
            }
        };

        const updateTaskStatus = async (id, data) => {
            try {
                const url = route('general_manager.tasks.update.status', { id: id });
                console.log('Update status URL:', url);
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Failed to update task: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Status tugas berhasil diperbarui');
                    await fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to update task');
                }
            } catch (error) {
                console.error('Error updating task:', error);
                showToast('Gagal memperbarui tugas: ' + error.message, 'error');
                throw error;
            }
        };

        const assignTask = async (id, data) => {
            try {
                const url = route('general_manager.tasks.assign', { id: id });
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Failed to assign task: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Tugas berhasil ditugaskan');
                    await fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to assign task');
                }
            } catch (error) {
                console.error('Error assigning task:', error);
                showToast('Gagal menugaskan tugas: ' + error.message, 'error');
                throw error;
            }
        };

        const getTaskDetail = async (id) => {
            try {
                const url = route('general_manager.tasks.show', { id: id });
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Failed to get task detail: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    return result.task;
                } else {
                    throw new Error(result.message || 'Failed to get task detail');
                }
            } catch (error) {
                console.error('Error getting task detail:', error);
                throw error;
            }
        };

        // Fungsi untuk update task (edit)
        const updateTask = async (id, data) => {
            try {
                const url = route('general_manager.tasks.update', { id: id });
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Failed to update task: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Tugas berhasil diperbarui');
                    await fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to update task');
                }
            } catch (error) {
                console.error('Error updating task:', error);
                showToast('Gagal memperbarui tugas: ' + error.message, 'error');
                throw error;
            }
        };

        // Render Functions
        const filterTasks = () => {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const divisiFilter = document.getElementById('divisiFilter').value;
            
            filteredTasks = allTasks.filter(task => {
                const matchesSearch = 
                    task.judul.toLowerCase().includes(searchTerm) ||
                    task.deskripsi.toLowerCase().includes(searchTerm) ||
                    task.assignee_text.toLowerCase().includes(searchTerm);
                
                const matchesStatus = statusFilter === 'all' || task.status === statusFilter;
                const matchesPriority = priorityFilter === 'all' || task.prioritas === priorityFilter;
                const matchesType = typeFilter === 'all' || task.target_type === typeFilter;
                const matchesDivisi = divisiFilter === 'all' || task.assignee_divisi === divisiFilter;
                
                return matchesSearch && matchesStatus && matchesPriority && matchesType && matchesDivisi;
            });
            
            currentPage = 1;
            renderTable();
        };

        const renderTable = () => {
            const start = (currentPage - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, filteredTasks.length);
            const pageTasks = filteredTasks.slice(start, end);
            
            document.getElementById('totalCount').textContent = filteredTasks.length;
            document.getElementById('showingCount').textContent = pageTasks.length;
            
            // Update panel title based on tab
            let panelTitle = 'Daftar Tugas';
            if (currentTab === 'my-tasks') panelTitle = 'Tugas yang Dibuat';
            if (currentTab === 'team-tasks') panelTitle = 'Tugas yang Diterima';
            if (currentTab === 'division-tasks') panelTitle = 'Tugas untuk Divisi ' + currentDivisi;
            document.getElementById('panelTitle').textContent = panelTitle;
            
            const desktopTbody = document.getElementById('desktopTableBody');
            desktopTbody.innerHTML = '';
            
            if (pageTasks.length === 0) {
                document.getElementById('noDataMessage').style.display = 'block';
                document.getElementById('desktopTable').style.display = 'none';
                document.getElementById('desktopPaginationContainer').style.display = 'none';
                document.querySelector('.mobile-pagination').style.display = 'none';
                return;
            }
            
            pageTasks.forEach((task, index) => {
                const rowNum = start + index + 1;
                
                desktopTbody.innerHTML += `
                    <tr>
                        <td>${rowNum}</td>
                        <td class="font-medium">${task.judul}</td>
                        <td class="truncate-text" title="${task.deskripsi}">${task.deskripsi.substring(0, 50)}${task.deskripsi.length > 50 ? '...' : ''}</td>
                        <td class="${task.is_overdue ? 'text-red-600 font-semibold' : ''}">
                            ${formatDate(task.deadline)}
                            ${task.is_overdue ? ' (Terlambat)' : ''}
                        </td>
                        <td>${task.assignee_text}</td>
                        <td>
                            <span class="divisi-badge ${getDivisiClass(task.assignee_divisi)}">
                                ${task.assignee_divisi}
                            </span>
                        </td>
                        <td><span class="type-badge ${getTypeClass(task.target_type)}">${getTypeText(task.target_type)}</span></td>
                        <td><span class="priority-badge ${getPriorityClass(task.prioritas)}">${getPriorityText(task.prioritas)}</span></td>
                        <td>
                            <span class="status-badge ${getStatusClass(task.status)} ${task.is_overdue ? 'status-dibatalkan' : ''}">
                                ${getStatusText(task.status)}
                            </span>
                        </td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <button onclick="showDetail(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Detail">
                                    <span class="material-icons-outlined text-blue-600">visibility</span>
                                </button>
                                ${currentTab === 'my-tasks' && task.created_by === currentUser.id ? `
                                    <button onclick="showEdit(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Edit">
                                        <span class="material-icons-outlined text-green-600">edit</span>
                                    </button>
                                ` : ''}
                                <button onclick="showUpdateStatus(${task.id})" class="p-2 rounded-full hover:bg-yellow-100" title="Update Status">
                                    <span class="material-icons-outlined text-yellow-600">sync</span>
                                </button>
                                ${task.target_type === 'divisi' && task.is_broadcast && task.assignee_divisi === currentDivisi ? `
                                    <button onclick="showAssign(${task.id})" class="p-2 rounded-full hover:bg-purple-100" title="Tugaskan ke Karyawan">
                                        <span class="material-icons-outlined text-purple-600">person_add</span>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            const mobileContainer = document.getElementById('mobile-cards');
            mobileContainer.innerHTML = '';
            
            pageTasks.forEach((task, index) => {
                const rowNum = start + index + 1;
                
                mobileContainer.innerHTML += `
                    <div class="bg-white rounded-lg border border-gray-300 p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-base">${task.judul}</h4>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="type-badge ${getTypeClass(task.target_type)} text-xs">${getTypeText(task.target_type)}</span>
                                    <span class="divisi-badge ${getDivisiClass(task.assignee_divisi)} text-xs">
                                        ${task.assignee_divisi}
                                    </span>
                                    <span class="priority-badge ${getPriorityClass(task.prioritas)} text-xs">
                                        ${getPriorityText(task.prioritas)}
                                    </span>
                                    <span class="status-badge ${getStatusClass(task.status)} ${task.is_overdue ? 'status-dibatalkan' : ''}">
                                        ${getStatusText(task.status)}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button onclick="showDetail(${task.id})" class="p-2" title="Detail">
                                    <span class="material-icons-outlined text-blue-600">visibility</span>
                                </button>
                                ${currentTab === 'my-tasks' && task.created_by === currentUser.id ? `
                                    <button onclick="showEdit(${task.id})" class="p-2" title="Edit">
                                        <span class="material-icons-outlined text-green-600">edit</span>
                                    </button>
                                ` : ''}
                                <button onclick="showUpdateStatus(${task.id})" class="p-2" title="Update Status">
                                    <span class="material-icons-outlined text-yellow-600">sync</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm mb-3">
                            <div><p class="text-gray-600">Penerima</p><p class="font-medium">${task.assignee_text}</p></div>
                            <div><p class="text-gray-600">Deadline</p><p class="font-medium ${task.is_overdue ? 'text-red-600' : ''}">${formatDate(task.deadline)} ${task.is_overdue ? '(Terlambat)' : ''}</p></div>
                            <div><p class="text-gray-600">Dibuat oleh</p><p class="font-medium">${task.creator_name}</p></div>
                        </div>
                        <p class="text-sm text-gray-600 truncate">${task.deskripsi.substring(0, 60)}${task.deskripsi.length > 60 ? '...' : ''}</p>
                    </div>
                `;
            });
            
            document.getElementById('noDataMessage').style.display = 'none';
            document.getElementById('desktopTable').style.display = 'block';
            document.getElementById('mobile-cards').style.display = window.innerWidth < 640 ? 'block' : 'none';
            
            updatePagination();
        };

        const updatePagination = () => {
            totalPages = Math.ceil(filteredTasks.length / itemsPerPage);
            
            const desktopPageNumbers = document.getElementById('desktopPageNumbers');
            desktopPageNumbers.innerHTML = '';
            
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = i === currentPage ? 'desktop-page-btn active' : 'desktop-page-btn';
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderTable();
                });
                desktopPageNumbers.appendChild(btn);
            }
            
            document.getElementById('desktopPrevPage').disabled = currentPage === 1;
            document.getElementById('desktopNextPage').disabled = currentPage === totalPages;
            
            const mobilePageNumbers = document.getElementById('pageNumbers');
            mobilePageNumbers.innerHTML = '';
            
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-8 h-8 rounded-full flex items-center justify-center text-sm ${i === currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'}`;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderTable();
                });
                mobilePageNumbers.appendChild(btn);
            }
            
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
            
            const desktopPagination = document.getElementById('desktopPaginationContainer');
            const mobilePagination = document.querySelector('.mobile-pagination');
            
            if (totalPages > 1) {
                desktopPagination.style.display = 'flex';
                mobilePagination.style.display = window.innerWidth < 640 ? 'flex' : 'none';
            } else {
                desktopPagination.style.display = 'none';
                mobilePagination.style.display = 'none';
            }
        };

        // Modal Functions
        const showDetail = async (id) => {
            try {
                const task = await getTaskDetail(id);
                
                createModal('Detail Tugas', `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div><h4 class="text-sm text-gray-600">ID</h4><p class="font-medium">#${task.id}</p></div>
                            <div><h4 class="text-sm text-gray-600">Judul</h4><p class="font-medium">${task.judul}</p></div>
                            <div><h4 class="text-sm text-gray-600">Deadline</h4><p class="font-medium">${formatDateTime(task.deadline)}</p></div>
                            <div><h4 class="text-sm text-gray-600">Status</h4><span class="status-badge ${getStatusClass(task.status)}">${getStatusText(task.status)}</span></div>
                            <div><h4 class="text-sm text-gray-600">Penerima</h4><p class="font-medium">${task.assigned_user?.name || task.target_divisi || task.target_manager?.name || '-'}</p></div>
                            <div><h4 class="text-sm text-gray-600">Divisi</h4><span class="divisi-badge ${getDivisiClass(task.assigned_user?.divisi || task.target_divisi || task.target_manager?.divisi || '')}">
                                ${task.assigned_user?.divisi || task.target_divisi || task.target_manager?.divisi || '-'}
                            </span></div>
                            <div><h4 class="text-sm text-gray-600">Tipe</h4><span class="type-badge ${getTypeClass(task.target_type)}">${getTypeText(task.target_type)}</span></div>
                            <div><h4 class="text-sm text-gray-600">Prioritas</h4><span class="priority-badge ${getPriorityClass(task.prioritas)}">${getPriorityText(task.prioritas)}</span></div>
                            <div><h4 class="text-sm text-gray-600">Kategori</h4><p class="font-medium">${task.kategori || '-'}</p></div>
                            <div><h4 class="text-sm text-gray-600">Dibuat oleh</h4><p class="font-medium">${task.creator?.name || '-'}</p></div>
                            <div><h4 class="text-sm text-gray-600">Dibuat pada</h4><p class="font-medium">${formatDateTime(task.created_at)}</p></div>
                            ${task.assigned_at ? `<div><h4 class="text-sm text-gray-600">Ditugaskan pada</h4><p class="font-medium">${formatDateTime(task.assigned_at)}</p></div>` : ''}
                            ${task.completed_at ? `<div><h4 class="text-sm text-gray-600">Selesai pada</h4><p class="font-medium">${formatDateTime(task.completed_at)}</p></div>` : ''}
                        </div>
                        <div><h4 class="text-sm text-gray-600">Deskripsi Lengkap</h4><p class="mt-1 whitespace-pre-line">${task.deskripsi}</p></div>
                        ${task.catatan ? `<div><h4 class="text-sm text-gray-600">Catatan</h4><p class="mt-1 whitespace-pre-line">${task.catatan}</p></div>` : ''}
                        ${task.catatan_update ? `<div><h4 class="text-sm text-gray-600">Catatan Update</h4><p class="mt-1 whitespace-pre-line">${task.catatan_update}</p></div>` : ''}
                        <button class="close-modal px-4 py-2 btn-secondary rounded-lg w-full mt-4">Tutup</button>
                    </div>
                `);
            } catch (error) {
                console.error('Error loading task detail:', error);
                showToast('Gagal memuat detail tugas', 'error');
            }
        };

        const showEdit = async (id) => {
            try {
                const task = await getTaskDetail(id);
                
                // Check if user can edit this task
                if (task.created_by !== currentUser.id) {
                    showToast('Anda tidak memiliki izin untuk mengedit tugas ini', 'error');
                    return;
                }
                
                let assigneeSelection = '';
                if (task.target_type === 'karyawan') {
                    assigneeSelection = `
                        <div>
                            <label class="block text-sm font-medium mb-1">Pilih Karyawan *</label>
                            <select name="assigned_to" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Karyawan</option>
                                ${karyawanList.map(k => `<option value="${k.id}" ${task.assigned_to == k.id ? 'selected' : ''}>${k.name} (${k.email}) - ${k.divisi}</option>`).join('')}
                            </select>
                        </div>
                    `;
                } else if (task.target_type === 'divisi') {
                    assigneeSelection = `
                        <div>
                            <label class="block text-sm font-medium mb-1">Pilih Divisi *</label>
                            <select name="target_divisi" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Divisi</option>
                                ${divisiList.map(d => `<option value="${d}" ${task.target_divisi === d ? 'selected' : ''}>${d}</option>`).join('')}
                            </select>
                        </div>
                    `;
                } else if (task.target_type === 'manager') {
                    assigneeSelection = `
                        <div>
                            <label class="block text-sm font-medium mb-1">Pilih Manager *</label>
                            <select name="target_manager_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Manager Divisi</option>
                                ${managerList.map(m => `<option value="${m.id}" ${task.target_manager_id == m.id ? 'selected' : ''}>${m.name} (${m.divisi})</option>`).join('')}
                            </select>
                        </div>
                    `;
                }
                
                createModal('Edit Tugas', `
                    <form id="editTaskForm">
                        <input type="hidden" name="id" value="${task.id}">
                        <input type="hidden" name="target_type" value="${task.target_type}">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Judul Tugas *</label>
                                <input name="judul" value="${task.judul}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Deskripsi *</label>
                                <textarea name="deskripsi" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>${task.deskripsi}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Deadline *</label>
                                <input type="datetime-local" name="deadline" value="${task.deadline ? task.deadline.substring(0, 16) : ''}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                            </div>
                            <div id="assigneeSelection">${assigneeSelection}</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Prioritas *</label>
                                    <select name="prioritas" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="rendah" ${task.prioritas === 'rendah' ? 'selected' : ''}>Rendah</option>
                                        <option value="normal" ${task.prioritas === 'normal' ? 'selected' : ''}>Normal</option>
                                        <option value="tinggi" ${task.prioritas === 'tinggi' ? 'selected' : ''}>Tinggi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Status *</label>
                                    <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="proses" ${task.status === 'proses' ? 'selected' : ''}>Dalam Proses</option>
                                        <option value="selesai" ${task.status === 'selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="dibatalkan" ${task.status === 'dibatalkan' ? 'selected' : ''}>Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori (Opsional)</label>
                                <input name="kategori" value="${task.kategori || ''}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Masukkan kategori">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Catatan (Opsional)</label>
                                <textarea name="catatan" rows="3" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Masukkan catatan">${task.catatan || ''}</textarea>
                            </div>
                            <div class="flex gap-2 pt-4 border-t">
                                <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg flex-1">Batal</button>
                                <button type="submit" class="px-4 py-2 btn-primary rounded-lg flex-1">Update</button>
                            </div>
                        </div>
                    </form>
                `, async (data) => {
                    await updateTask(id, data);
                });
            } catch (error) {
                console.error('Error loading task for edit:', error);
                showToast('Gagal memuat data untuk edit', 'error');
            }
        };

        const showCreateModal = () => {
            const modal = createModal('Buat Tugas Baru', `
                <form id="createTaskForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Judul Tugas *</label>
                            <input name="judul" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required placeholder="Masukkan judul tugas">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Deskripsi *</label>
                            <textarea name="deskripsi" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required placeholder="Masukkan deskripsi tugas"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Deadline *</label>
                            <input type="datetime-local" name="deadline" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Tipe Penerima *</label>
                            <div class="flex gap-2 mb-3" id="assigneeTypeContainer">
                                <button type="button" class="assignee-type-btn active flex-1" data-type="karyawan">
                                    <div class="flex flex-col items-center">
                                        <span class="material-icons-outlined mb-1">person</span>
                                        <span class="text-xs">Karyawan</span>
                                    </div>
                                </button>
                                <button type="button" class="assignee-type-btn flex-1" data-type="divisi">
                                    <div class="flex flex-col items-center">
                                        <span class="material-icons-outlined mb-1">groups</span>
                                        <span class="text-xs">Divisi</span>
                                    </div>
                                </button>
                                <button type="button" class="assignee-type-btn flex-1" data-type="manager">
                                    <div class="flex flex-col items-center">
                                        <span class="material-icons-outlined mb-1">supervisor_account</span>
                                        <span class="text-xs">Manager</span>
                                    </div>
                                </button>
                            </div>
                            <input type="hidden" name="target_type" value="karyawan">
                        </div>
                        <div id="assigneeSelection">
                            <div>
                                <label class="block text-sm font-medium mb-1">Pilih Karyawan *</label>
                                <select name="assigned_to" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                    <option value="">Pilih Karyawan</option>
                                    ${karyawanList.map(k => `<option value="${k.id}">${k.name} (${k.email}) - ${k.divisi}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Prioritas *</label>
                            <select name="prioritas" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="rendah">Rendah</option>
                                <option value="normal" selected>Normal</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Kategori (Opsional)</label>
                            <input name="kategori" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Masukkan kategori">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="3" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Masukkan catatan"></textarea>
                        </div>
                        <div class="flex gap-2 pt-4 border-t">
                            <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg flex-1">Batal</button>
                            <button type="submit" class="px-4 py-2 btn-primary rounded-lg flex-1">Simpan</button>
                        </div>
                    </div>
                </form>
            `, async (data) => {
                await createTask(data);
            });
            
            // Add event listeners for assignee type buttons
            setTimeout(() => {
                const assigneeTypeBtns = modal.querySelectorAll('.assignee-type-btn');
                assigneeTypeBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        assigneeTypeBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        const type = this.dataset.type;
                        modal.querySelector('input[name="target_type"]').value = type;
                        
                        const assigneeDiv = modal.querySelector('#assigneeSelection');
                        let html = '';
                        
                        if (type === 'karyawan') {
                            html = `
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Karyawan *</label>
                                    <select name="assigned_to" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Karyawan</option>
                                        ${karyawanList.map(k => `<option value="${k.id}">${k.name} (${k.email}) - ${k.divisi}</option>`).join('')}
                                    </select>
                                </div>
                            `;
                        } else if (type === 'divisi') {
                            html = `
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Divisi *</label>
                                    <select name="target_divisi" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Divisi</option>
                                        ${divisiList.map(d => `<option value="${d}">${d}</option>`).join('')}
                                    </select>
                                </div>
                            `;
                        } else if (type === 'manager') {
                            html = `
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Manager Divisi *</label>
                                    <select name="target_manager_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Manager Divisi</option>
                                        ${managerList.map(m => `<option value="${m.id}">${m.name} (${m.divisi})</option>`).join('')}
                                    </select>
                                </div>
                            `;
                        }
                        
                        assigneeDiv.innerHTML = html;
                    });
                });
            }, 100);
        };

        const showUpdateStatus = async (id) => {
            try {
                const task = await getTaskDetail(id);
                
                createModal('Update Status Tugas', `
                    <form id="updateStatusForm">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium mb-2">Tugas: ${task.judul}</h4>
                                <p class="text-sm text-gray-600">Status saat ini: <span class="status-badge ${getStatusClass(task.status)}">${getStatusText(task.status)}</span></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Status Baru *</label>
                                <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                    <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="proses" ${task.status === 'proses' ? 'selected' : ''}>Dalam Proses</option>
                                    <option value="selesai" ${task.status === 'selesai' ? 'selected' : ''}>Selesai</option>
                                    <option value="dibatalkan" ${task.status === 'dibatalkan' ? 'selected' : ''}>Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Catatan Update (Opsional)</label>
                                <textarea name="catatan_update" rows="3" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Tambahkan catatan update status"></textarea>
                            </div>
                            <div class="flex gap-2 pt-4 border-t">
                                <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg flex-1">Batal</button>
                                <button type="submit" class="px-4 py-2 btn-primary rounded-lg flex-1">Update Status</button>
                            </div>
                        </div>
                    </form>
                `, async (data) => {
                    await updateTaskStatus(id, data);
                });
            } catch (error) {
                console.error('Error loading task for status update:', error);
                showToast('Gagal memuat data tugas', 'error');
            }
        };

        const showAssign = async (id) => {
            try {
                const task = await getTaskDetail(id);
                
                // Get karyawan in the same division as the task
                const karyawanInDivisi = karyawanList.filter(k => k.divisi === task.target_divisi);
                
                createModal('Tugaskan ke Karyawan', `
                    <form id="assignForm">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium mb-2">Tugas: ${task.judul}</h4>
                                <p class="text-sm text-gray-600">Divisi: <span class="divisi-badge ${getDivisiClass(task.target_divisi)}">${task.target_divisi}</span></p>
                                <p class="text-sm text-gray-600 mt-1">Tugas ini saat ini ditujukan ke seluruh divisi. Anda dapat menugaskannya ke karyawan tertentu.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Pilih Karyawan *</label>
                                ${karyawanInDivisi.length > 0 ? `
                                    <select name="karyawan_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Karyawan</option>
                                        ${karyawanInDivisi.map(k => `<option value="${k.id}">${k.name} (${k.email})</option>`).join('')}
                                    </select>
                                ` : `
                                    <p class="text-sm text-red-600">Tidak ada karyawan dalam divisi ${task.target_divisi}</p>
                                `}
                            </div>
                            <div class="flex gap-2 pt-4 border-t">
                                <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg flex-1">Batal</button>
                                ${karyawanInDivisi.length > 0 ? `
                                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg flex-1">Tugaskan</button>
                                ` : `
                                    <button type="button" class="close-modal px-4 py-2 btn-primary rounded-lg flex-1">Tutup</button>
                                `}
                            </div>
                        </div>
                    </form>
                `, async (data) => {
                    await assignTask(id, data);
                });
            } catch (error) {
                console.error('Error loading task for assign:', error);
                showToast('Gagal memuat data tugas', 'error');
            }
        };

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Initial load
            console.log('DOM Content Loaded, fetching tasks...');
            fetchTasks();
            updateStatistics();
            
            // Tab switching
            const tabs = ['tabMyTasks', 'tabTeamTasks', 'tabDivisionTasks'];
            tabs.forEach(tabId => {
                document.getElementById(tabId).addEventListener('click', function() {
                    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    currentTab = this.dataset.tab;
                    currentPage = 1;
                    console.log('Tab changed to:', currentTab);
                    fetchTasks();
                });
            });
            
            // Event listeners for filters
            document.getElementById('searchInput').addEventListener('input', filterTasks);
            document.getElementById('statusFilter').addEventListener('change', filterTasks);
            document.getElementById('priorityFilter').addEventListener('change', filterTasks);
            document.getElementById('typeFilter').addEventListener('change', filterTasks);
            document.getElementById('divisiFilter').addEventListener('change', filterTasks);
            
            document.getElementById('refreshBtn').addEventListener('click', () => {
                console.log('Manual refresh clicked');
                fetchTasks();
                updateStatistics();
                showToast('Data diperbarui');
            });
            
            document.getElementById('buatTugasBtn').addEventListener('click', showCreateModal);
            
            document.getElementById('closeToast').addEventListener('click', () => {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Pagination event listeners
            document.getElementById('desktopPrevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });
            
            document.getElementById('desktopNextPage').addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });
            
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });
            
            document.getElementById('nextPage').addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });
            
            // Auto-refresh every 60 seconds
            setInterval(() => {
                console.log('Auto-refreshing data...');
                fetchTasks();
                updateStatistics();
            }, 60000);
            
            // Set divisi filter based on current user's divisi
            document.getElementById('divisiFilter').value = 'all';
            
            // Debug button
            const debugButton = document.createElement('button');
            debugButton.className = 'px-4 py-2 bg-yellow-500 text-white rounded-lg';
            debugButton.textContent = 'Debug Routes';
            debugButton.addEventListener('click', () => {
                console.log('=== DEBUG INFO ===');
                console.log('Current User:', currentUser);
                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').content);
                console.log('All Routes:');
                console.log('API Tasks:', route('general_manager.api.tasks'));
                console.log('API Statistics:', route('general_manager.api.tasks.statistics'));
                console.log('Store Task:', route('general_manager.tasks.store'));
                
                // Test fetch langsung
                fetch(route('general_manager.api.tasks'), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    console.log('Direct fetch status:', response.status, response.statusText);
                    return response.text();
                })
                .then(text => {
                    console.log('Direct fetch response:', text.substring(0, 200));
                })
                .catch(error => {
                    console.error('Direct fetch error:', error);
                });
            });
            
            // Tambahkan debug button ke panel header
            document.querySelector('.panel-header').appendChild(debugButton);
        });

        // Make functions available globally
        window.showDetail = showDetail;
        window.showEdit = showEdit;
        window.showUpdateStatus = showUpdateStatus;
        window.showAssign = showAssign;
    </script>
</body>
</html>
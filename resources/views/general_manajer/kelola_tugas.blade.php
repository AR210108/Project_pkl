<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas</title>
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
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-pending { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .status-proses { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .status-selesai { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .status-dibatalkan { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }
        .badge-programmer { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .badge-desainer { background-color: rgba(139, 92, 246, 0.15); color: #5b21b6; }
        .badge-marketing { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .type-divisi { background-color: rgba(139, 92, 246, 0.15); color: #5b21b6; }
        .type-manager { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
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
        .data-table { width: 100%; min-width: 1000px; border-collapse: collapse; }
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
        @media (max-width: 639px) { .desktop-table { display: none; } .mobile-cards { display: block; } .desktop-pagination { display: none !important; } }
        @media (min-width: 640px) { .desktop-table { display: block; } .mobile-cards { display: none; } .mobile-pagination { display: none !important; } }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Helper function untuk route
        function route(name, params = {}) {
            const routes = {
                'general_manager.api.tasks': '/general-manajer/api/tasks',
                'general_manager.api.tasks.statistics': '/general-manajer/api/tasks/statistics',
                'general_manager.tasks.store': '/general-manajer/tasks',
                'general_manager.tasks.show': '/general-manajer/tasks/{id}',
                'general_manager.tasks.update': '/general-manajer/tasks/{id}',
                'general_manager.tasks.update.status': '/general-manajer/tasks/{id}/status',
                'general_manager.tasks.assign': '/general-manajer/tasks/{id}/assign',
                'general_manager.tasks.destroy': '/general-manajer/tasks/{id}',
                'general_manager.tasks.delete': '/general-manajer/tasks/{id}/delete',
                'karyawan.api.tasks': '/api/karyawan/tasks',
                'karyawan.api.today-status': '/api/karyawan/today-status',
                'karyawan.api.history': '/api/karyawan/history',
                'karyawan.api.dashboard-data': '/api/karyawan/dashboard-data',
            };
            
            let url = routes[name] || '#';
            Object.keys(params).forEach(key => {
                url = url.replace(`{${key}}`, params[key]);
            });
            return url;
        }
    </script>
</head>

<body class="font-display bg-gray-50 text-gray-800">
   @include('general_manajer/templet/header')
    <div class="main-content">
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">
                    <span id="pageTitle">Kelola Tugas</span>
                </h2>
                
                <!-- Statistics Cards - Hanya untuk General Manager -->
                <div id="statisticsSection" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
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
                
                <!-- Tabs - Hanya untuk General Manager -->
                <div id="tabsSection" class="flex border-b border-gray-200 mb-6">
                    <button id="tabMyTasks" class="tab-btn active" data-tab="my-tasks">Tugas Dibuat</button>
                    <button id="tabTeamTasks" class="tab-btn ml-2" data-tab="team-tasks">Tugas Diterima</button>
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
                        
                        <!-- Filter Divisi - Hanya untuk General Manager -->
                        <div id="divisiFilterContainer">
                            <select id="divisiFilter" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                                <option value="all">Semua Divisi</option>
                                <option value="Programmer">Programmer</option>
                                <option value="Desainer">Desainer</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                            </select>
                        </div>
                        
                        <button id="refreshBtn" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none flex items-center gap-2">
                            <span class="material-icons-outlined">refresh</span>
                            <span class="hidden sm:inline">Refresh</span>
                        </button>
                        
                        <!-- Button Buat Tugas - Hanya untuk General Manager -->
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
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards" style="display: none;"></div>
                        
                        <!-- No Data Message -->
                        <div id="noDataMessage" class="text-center py-8" style="display: none;">
                            <span class="material-icons-outlined text-gray-400 text-4xl mb-2">task_alt</span>
                            <p class="text-gray-600" id="noDataMessageText">Tidak ada data tugas</p>
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
                Copyright ©2025 oleh digicity.id
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
        const state = {
            currentPage: 1,
            itemsPerPage: 10,
            totalPages: 1,
            allTasks: [],
            filteredTasks: [],
            currentUser: @json(auth()->user() ?? null),
            currentDivisi: @json(auth()->user()?->divisi ?? ''),
            currentRole: @json(auth()->user()?->role ?? ''),
            karyawanList: @json($karyawan ?? []),
            divisiList: @json($divisi ?? ['Programmer', 'Desainer', 'Digital Marketing']),
            managerList: @json($managers ?? []),
            currentTab: 'my-tasks'
        };

        // Utility Functions
        const utils = {
            getStatusClass: (status) => `status-${status}`,
            getStatusText: (status) => ({ 'pending': 'Pending', 'proses': 'Dalam Proses', 'selesai': 'Selesai', 'dibatalkan': 'Dibatalkan' }[status] || status),
            getDivisiClass: (divisi) => {
                if (!divisi) return 'badge-programmer';
                const divisiLower = divisi.toLowerCase();
                if (divisiLower.includes('marketing')) return 'badge-marketing';
                if (divisiLower.includes('program')) return 'badge-programmer';
                if (divisiLower.includes('desain')) return 'badge-desainer';
                return 'badge-programmer';
            },
            getTypeClass: (type) => ({ 'divisi': 'type-divisi', 'manager': 'type-manager' }[type] || 'type-divisi'),
            getTypeText: (type) => ({ 'divisi': 'Divisi', 'manager': 'Manajer' }[type] || type),
            formatDate: (dateString) => dateString ? new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-',
            formatDateTime: (dateString) => dateString ? new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-',
            showToast: (message, type = 'success') => {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toastMessage');
                toast.style.backgroundColor = type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#10b981';
                toastMessage.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 3000);
            },
            showLoading: (show) => {
                const elements = {
                    loading: document.getElementById('loadingIndicator'),
                    desktop: document.getElementById('desktopTable'),
                    mobile: document.getElementById('mobile-cards'),
                    noData: document.getElementById('noDataMessage'),
                    desktopPagination: document.getElementById('desktopPaginationContainer'),
                    mobilePagination: document.querySelector('.mobile-pagination')
                };
                
                if (show) {
                    Object.values(elements).forEach(el => el.style.display = 'none');
                    elements.loading.style.display = 'block';
                } else {
                    elements.loading.style.display = 'none';
                }
            },
            createModal: (title, content, onSubmit = null, onClose = null) => {
                const template = document.getElementById('modalTemplate').cloneNode(true);
                template.id = 'activeModal';
                template.style.display = 'flex';
                template.querySelector('.modal-title').textContent = title;
                template.querySelector('.modal-content').innerHTML = content;
                
                const closeModal = () => {
                    if (onClose) onClose();
                    template.remove();
                };
                
                template.querySelectorAll('.close-modal').forEach(btn => btn.addEventListener('click', closeModal));
                template.addEventListener('click', (e) => e.target === template && closeModal());
                
                if (onSubmit) {
                    const form = template.querySelector('form');
                    form?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const data = Object.fromEntries(new FormData(form).entries());
                        try {
                            await onSubmit(data);
                            closeModal();
                        } catch (error) {
                            console.error('Form submit error:', error);
                        }
                    });
                }
                
                document.body.appendChild(template);
                return template;
            },
            setupUIByRole: () => {
                const isGeneralManager = state.currentRole === 'general_manager';
                const isKaryawan = state.currentRole === 'karyawan';
                
                // Update page title
                const pageTitle = document.getElementById('pageTitle');
                if (isKaryawan) {
                    pageTitle.textContent = `Kelola Tugas - ${state.currentDivisi}`;
                    // Update no data message for karyawan
                    document.getElementById('noDataMessageText').textContent = `Tidak ada tugas untuk divisi ${state.currentDivisi}`;
                } else if (isGeneralManager) {
                    pageTitle.textContent = 'Kelola Tugas - General Manajer';
                }
                
                // Show/hide statistics section
                const statsSection = document.getElementById('statisticsSection');
                statsSection.style.display = isGeneralManager ? 'grid' : 'none';
                
                // Show/hide tabs section
                const tabsSection = document.getElementById('tabsSection');
                tabsSection.style.display = isGeneralManager ? 'flex' : 'none';
                
                // Show/hide divisi filter
                const divisiFilterContainer = document.getElementById('divisiFilterContainer');
                divisiFilterContainer.style.display = isGeneralManager ? 'block' : 'none';
                
                // Show/hide create task button
                const buatTugasBtn = document.getElementById('buatTugasBtn');
                buatTugasBtn.style.display = isGeneralManager ? 'flex' : 'none';
                
                // Set default tab for karyawan
                if (isKaryawan) {
                    state.currentTab = 'team-tasks';
                }
            }
        };

        // API Functions
        const api = {
            // Get CSRF token
            getCsrfToken: () => {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            },
            
            request: async (url, options = {}) => {
                // Default headers
                const headers = {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': api.getCsrfToken(),
                    ...options.headers
                };
                
                // If it's a POST, PUT, or DELETE request, add Content-Type
                if (['POST', 'PUT', 'DELETE'].includes(options.method)) {
                    headers['Content-Type'] = 'application/json';
                }
                
                try {
                    const response = await fetch(url, {
                        headers,
                        ...options
                    });
                    
                    if (!response.ok) {
                        let errorMessage = `Request failed: ${response.status}`;
                        
                        try {
                            const errorData = await response.json();
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            // If response is not JSON, use default error message
                        }
                        
                        throw new Error(errorMessage);
                    }
                    
                    const data = await response.json();
                    return data;
                    
                } catch (error) {
                    throw error;
                }
            },
            
            fetchTasks: async () => {
                utils.showLoading(true);
                try {
                    // Tentukan endpoint berdasarkan role
                    let endpoint;
                    if (state.currentRole === 'karyawan') {
                        endpoint = route('karyawan.api.tasks');
                    } else {
                        endpoint = route('general_manager.api.tasks');
                    }
                    
                    const result = await api.request(endpoint);
                    
                    state.allTasks = Array.isArray(result) ? result : [];
                    state.filteredTasks = [...state.allTasks];
                    
                    render.renderTable();
                    
                    // Hanya update statistics untuk General Manager
                    if (state.currentRole === 'general_manager') {
                        await api.updateStatistics();
                    }
                } catch (error) {
                    utils.showToast('Gagal memuat data tugas: ' + error.message, 'error');
                    state.allTasks = [];
                    state.filteredTasks = [];
                    render.renderTable();
                } finally {
                    utils.showLoading(false);
                }
            },
            
            updateStatistics: async () => {
                try {
                    const result = await api.request(route('general_manager.api.tasks.statistics'));
                    document.getElementById('totalTasks').textContent = result.total || 0;
                    document.getElementById('pendingTasks').textContent = result.pending || 0;
                    document.getElementById('progressTasks').textContent = result.in_progress || 0;
                    document.getElementById('completedTasks').textContent = result.completed || 0;
                    document.getElementById('cancelledTasks').textContent = result.cancelled || 0;
                } catch (error) {
                    console.error('Error fetching statistics:', error);
                }
            },
            
            createTask: async (data) => {
                const result = await api.request(route('general_manager.tasks.store'), {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                
                if (result.success) {
                    utils.showToast('Tugas berhasil dibuat');
                    await api.fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to create task');
                }
            },
            
            deleteTask: async (id) => {
                try {
                    let result;
                    
                    // Try multiple approaches to delete the task
                    try {
                        // Approach 1: Try DELETE method first
                        result = await api.request(route('general_manager.tasks.destroy', { id }), { 
                            method: 'DELETE'
                        });
                    } catch (deleteError) {
                        try {
                            // Approach 2: Try a dedicated delete endpoint
                            result = await api.request(route('general_manager.tasks.delete', { id }), { 
                                method: 'POST'
                            });
                        } catch (postError) {
                            throw new Error('Gagal menghapus tugas');
                        }
                    }
                    
                    if (result.success) {
                        utils.showToast('Tugas berhasil dihapus');
                        await api.fetchTasks();
                    } else {
                        throw new Error(result.message || 'Failed to delete task');
                    }
                } catch (error) {
                    throw error;
                }
            },
            
            assignTask: async (id, data) => {
                const result = await api.request(route('general_manager.tasks.assign', { id }), {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                if (result.success) {
                    utils.showToast('Tugas berhasil ditugaskan');
                    await api.fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to assign task');
                }
            },
            
            getTaskDetail: async (id) => {
                const result = await api.request(route('general_manager.tasks.show', { id }));
                if (result.success) {
                    return result.task;
                } else {
                    throw new Error(result.message || 'Failed to get task detail');
                }
            },
            
            updateTask: async (id, data) => {
                const result = await api.request(route('general_manager.tasks.update', { id }), {
                    method: 'PUT',
                    body: JSON.stringify(data)
                });
                if (result.success) {
                    utils.showToast('Tugas berhasil diperbarui');
                    await api.fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to update task');
                }
            }
        };

        // Render Functions
        const render = {
            filterTasks: () => {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const divisiFilter = document.getElementById('divisiFilter').value;
                
                state.filteredTasks = state.allTasks.filter(task => {
                    const matchesSearch = 
                        task.judul?.toLowerCase().includes(searchTerm) ||
                        task.deskripsi?.toLowerCase().includes(searchTerm) ||
                        task.assignee_text?.toLowerCase().includes(searchTerm) ||
                        task.creator_name?.toLowerCase().includes(searchTerm);
                    
                    const matchesStatus = statusFilter === 'all' || task.status === statusFilter;
                    
                    // Logika filter berdasarkan role
                    let matchesDivisi = true;
                    
                    if (state.currentRole === 'karyawan') {
                        // Untuk karyawan: hanya tampilkan tugas untuk divisinya
                        const taskDivisi = task.target_divisi || 
                                         (task.target_manager ? task.target_manager.divisi : null) ||
                                         (task.assigned_user ? task.assigned_user.divisi : null);
                        
                        // Case-insensitive comparison
                        const userDivisi = state.currentDivisi?.toLowerCase() || '';
                        const targetDivisi = taskDivisi?.toLowerCase() || '';
                        
                        matchesDivisi = taskDivisi && 
                                      (targetDivisi === userDivisi || 
                                       task.is_for_me === true);
                        
                    } else if (state.currentRole === 'general_manager') {
                        // Untuk general manager: gunakan filter dropdown
                        const taskDivisi = task.target_divisi;
                        matchesDivisi = divisiFilter === 'all' || 
                                      (taskDivisi && taskDivisi.toLowerCase() === divisiFilter.toLowerCase());
                    }
                    
                    // Filter berdasarkan tab yang aktif (hanya untuk general manager)
                    if (state.currentRole === 'general_manager') {
                        if (state.currentTab === 'my-tasks') {
                            return matchesSearch && matchesStatus && matchesDivisi && 
                                   task.created_by === state.currentUser.id;
                        } else if (state.currentTab === 'team-tasks') {
                            return matchesSearch && matchesStatus && matchesDivisi;
                        }
                    }
                    
                    // Untuk karyawan, tampilkan semua tugas untuk divisinya
                    return matchesSearch && matchesStatus && matchesDivisi;
                });
                
                state.currentPage = 1;
                render.renderTable();
            },
            
            renderTable: () => {
                const start = (state.currentPage - 1) * state.itemsPerPage;
                const end = Math.min(start + state.itemsPerPage, state.filteredTasks.length);
                const pageTasks = state.filteredTasks.slice(start, end);
                
                document.getElementById('totalCount').textContent = state.filteredTasks.length;
                document.getElementById('showingCount').textContent = pageTasks.length;
                
                // Update panel title berdasarkan role dan tab
                let panelTitle = 'Daftar Tugas';
                if (state.currentRole === 'karyawan') {
                    panelTitle = `Tugas Divisi ${state.currentDivisi}`;
                } else if (state.currentRole === 'general_manager') {
                    const panelTitles = {
                        'my-tasks': 'Tugas yang Dibuat',
                        'team-tasks': 'Tugas yang Diterima'
                    };
                    panelTitle = panelTitles[state.currentTab] || 'Daftar Tugas';
                }
                document.getElementById('panelTitle').textContent = panelTitle;
                
                const desktopTbody = document.getElementById('desktopTableBody');
                desktopTbody.innerHTML = '';
                
                if (pageTasks.length === 0) {
                    document.getElementById('noDataMessage').style.display = 'block';
                    document.getElementById('desktopTable').style.display = 'none';
                    document.getElementById('desktopPaginationContainer').style.display = 'none';
                    document.querySelector('.mobile-pagination').style.display = 'none';
                    
                    // Update no data message based on role
                    const noDataText = document.getElementById('noDataMessageText');
                    if (state.currentRole === 'karyawan') {
                        noDataText.textContent = `Tidak ada tugas ditemukan untuk divisi ${state.currentDivisi}`;
                    }
                    
                    return;
                }
                
                // Render desktop table
                pageTasks.forEach((task, index) => {
                    const rowNum = start + index + 1;
                    const isOwner = state.currentRole === 'general_manager' && 
                                   state.currentTab === 'my-tasks' && 
                                   task.created_by === state.currentUser.id;
                    
                    desktopTbody.innerHTML += `
                        <tr>
                            <td>${rowNum}</td>
                            <td class="font-medium">${task.judul}</td>
                            <td class="truncate-text" title="${task.deskripsi}">${task.deskripsi.substring(0, 50)}${task.deskripsi.length > 50 ? '...' : ''}</td>
                            <td class="${task.is_overdue ? 'text-red-600 font-semibold' : ''}">
                                ${utils.formatDate(task.deadline)}
                                ${task.is_overdue ? ' (Terlambat)' : ''}
                            </td>
                            <td>${task.assignee_text || '-'}</td>
                            <td>
                                <span class="badge ${utils.getDivisiClass(task.target_divisi)}">
                                    ${task.target_divisi || '-'}
                                </span>
                            </td>
                            <td>
                                <span class="badge ${utils.getStatusClass(task.status)} ${task.is_overdue ? 'status-dibatalkan' : ''}">
                                    ${utils.getStatusText(task.status)}
                                </span>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <button onclick="modal.showDetail(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Detail">
                                        <span class="material-icons-outlined text-blue-600">visibility</span>
                                    </button>
                                    ${isOwner ? `
                                        <button onclick="modal.showEdit(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Edit">
                                            <span class="material-icons-outlined text-green-600">edit</span>
                                        </button>
                                        <button onclick="modal.showDelete(${task.id})" class="p-2 rounded-full hover:bg-red-100" title="Hapus">
                                            <span class="material-icons-outlined text-red-600">delete</span>
                                        </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                // Render mobile cards
                const mobileContainer = document.getElementById('mobile-cards');
                mobileContainer.innerHTML = pageTasks.map((task, index) => {
                    const rowNum = start + index + 1;
                    const isOwner = state.currentRole === 'general_manager' && 
                                   state.currentTab === 'my-tasks' && 
                                   task.created_by === state.currentUser.id;
                    
                    return `
                        <div class="bg-white rounded-lg border border-gray-300 p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-base">${task.judul}</h4>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        <span class="badge ${utils.getDivisiClass(task.target_divisi)} text-xs">
                                            ${task.target_divisi || '-'}
                                        </span>
                                        <span class="badge ${utils.getStatusClass(task.status)} ${task.is_overdue ? 'status-dibatalkan' : ''} text-xs">
                                            ${utils.getStatusText(task.status)}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <button onclick="modal.showDetail(${task.id})" class="p-2" title="Detail">
                                        <span class="material-icons-outlined text-blue-600">visibility</span>
                                    </button>
                                    ${isOwner ? `
                                        <button onclick="modal.showEdit(${task.id})" class="p-2" title="Edit">
                                            <span class="material-icons-outlined text-green-600">edit</span>
                                        </button>
                                        <button onclick="modal.showDelete(${task.id})" class="p-2" title="Hapus">
                                            <span class="material-icons-outlined text-red-600">delete</span>
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="space-y-2 text-sm mb-3">
                                <div><p class="text-gray-600">Deadline</p><p class="font-medium ${task.is_overdue ? 'text-red-600' : ''}">${utils.formatDate(task.deadline)} ${task.is_overdue ? '(Terlambat)' : ''}</p></div>
                                <div><p class="text-gray-600">Ditugaskan kepada</p><p class="font-medium">${task.assignee_text || '-'}</p></div>
                                <div><p class="text-gray-600">Dibuat oleh</p><p class="font-medium">${task.creator_name || '-'}</p></div>
                            </div>
                            <p class="text-sm text-gray-600 truncate">${task.deskripsi.substring(0, 60)}${task.deskripsi.length > 60 ? '...' : ''}</p>
                        </div>
                    `;
                }).join('');
                
                document.getElementById('noDataMessage').style.display = 'none';
                document.getElementById('desktopTable').style.display = 'block';
                document.getElementById('mobile-cards').style.display = window.innerWidth < 640 ? 'block' : 'none';
                
                render.updatePagination();
            },
            
            updatePagination: () => {
                state.totalPages = Math.ceil(state.filteredTasks.length / state.itemsPerPage);
                
                // Desktop pagination
                const desktopPageNumbers = document.getElementById('desktopPageNumbers');
                desktopPageNumbers.innerHTML = '';
                
                for (let i = 1; i <= state.totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = i === state.currentPage ? 'desktop-page-btn active' : 'desktop-page-btn';
                    btn.addEventListener('click', () => {
                        state.currentPage = i;
                        render.renderTable();
                    });
                    desktopPageNumbers.appendChild(btn);
                }
                
                document.getElementById('desktopPrevPage').disabled = state.currentPage === 1;
                document.getElementById('desktopNextPage').disabled = state.currentPage === state.totalPages;
                
                // Mobile pagination
                const mobilePageNumbers = document.getElementById('pageNumbers');
                mobilePageNumbers.innerHTML = '';
                
                for (let i = 1; i <= state.totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = `w-8 h-8 rounded-full flex items-center justify-center text-sm ${i === state.currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'}`;
                    btn.addEventListener('click', () => {
                        state.currentPage = i;
                        render.renderTable();
                    });
                    mobilePageNumbers.appendChild(btn);
                }
                
                document.getElementById('prevPage').disabled = state.currentPage === 1;
                document.getElementById('nextPage').disabled = state.currentPage === state.totalPages;
                
                // Show/hide pagination
                const desktopPagination = document.getElementById('desktopPaginationContainer');
                const mobilePagination = document.querySelector('.mobile-pagination');
                
                if (state.totalPages > 1) {
                    desktopPagination.style.display = 'flex';
                    mobilePagination.style.display = window.innerWidth < 640 ? 'flex' : 'none';
                } else {
                    desktopPagination.style.display = 'none';
                    mobilePagination.style.display = 'none';
                }
            }
        };

        // Modal Functions
        const modal = {
            showDetail: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    
                    utils.createModal('Detail Tugas', `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div><h4 class="text-sm text-gray-600">ID</h4><p class="font-medium">#${task.id}</p></div>
                                <div><h4 class="text-sm text-gray-600">Judul</h4><p class="font-medium">${task.judul}</p></div>
                                <div><h4 class="text-sm text-gray-600">Deadline</h4><p class="font-medium">${utils.formatDateTime(task.deadline)}</p></div>
                                <div><h4 class="text-sm text-gray-600">Status</h4><span class="badge ${utils.getStatusClass(task.status)}">${utils.getStatusText(task.status)}</span></div>
                                <div><h4 class="text-sm text-gray-600">Divisi</h4><span class="badge ${utils.getDivisiClass(task.assigned_user?.divisi || task.target_divisi || task.target_manager?.divisi || '')}">
                                    ${task.assigned_user?.divisi || task.target_divisi || task.target_manager?.divisi || '-'}
                                </span></div>
                                <div><h4 class="text-sm text-gray-600">Ditugaskan kepada</h4><p class="font-medium">${task.assigned_user?.name || task.target_manager?.name || '-'}</p></div>
                                <div><h4 class="text-sm text-gray-600">Dibuat oleh</h4><p class="font-medium">${task.creator?.name || '-'}</p></div>
                                <div><h4 class="text-sm text-gray-600">Dibuat pada</h4><p class="font-medium">${utils.formatDateTime(task.created_at)}</p></div>
                                ${task.completed_at ? `<div><h4 class="text-sm text-gray-600">Selesai pada</h4><p class="font-medium">${utils.formatDateTime(task.completed_at)}</p></div>` : ''}
                            </div>
                            <div><h4 class="text-sm text-gray-600">Deskripsi Lengkap</h4><p class="mt-1 whitespace-pre-line">${task.deskripsi}</p></div>
                            ${task.catatan ? `<div><h4 class="text-sm text-gray-600">Catatan</h4><p class="mt-1 whitespace-pre-line">${task.catatan}</p></div>` : ''}
                            ${task.catatan_update ? `<div><h4 class="text-sm text-gray-600">Catatan Update</h4><p class="mt-1 whitespace-pre-line">${task.catatan_update}</p></div>` : ''}
                            <button class="close-modal px-4 py-2 btn-secondary rounded-lg w-full mt-4">Tutup</button>
                        </div>
                    `);
                } catch (error) {
                    utils.showToast('Gagal memuat detail tugas', 'error');
                }
            },
            
            showEdit: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    
                    if (task.created_by !== state.currentUser.id) {
                        utils.showToast('Anda tidak memiliki izin untuk mengedit tugas ini', 'error');
                        return;
                    }
                    
                    const assigneeSelection = task.target_type === 'karyawan' ? `
                        <div>
                            <label class="block text-sm font-medium mb-1">Pilih Karyawan *</label>
                            <select name="assigned_to" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Karyawan</option>
                                ${state.karyawanList.map(k => `<option value="${k.id}" ${task.assigned_to == k.id ? 'selected' : ''}>${k.name} (${k.email}) - ${k.divisi}</option>`).join('')}
                            </select>
                        </div>
                    ` : task.target_type === 'divisi' ? `
                        <div>
                            <label class="block text-sm font-medium mb-1">Pilih Divisi *</label>
                            <select name="target_divisi" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Divisi</option>
                                ${state.divisiList.map(d => `<option value="${d}" ${task.target_divisi === d ? 'selected' : ''}>${d}</option>`).join('')}
                            </select>
                        </div>
                    ` : `
                        <div>
                            <label class="block text-sm font-medium mb-1">Pilih Manajer *</label>
                            <select name="target_manager_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Manajer Divisi</option>
                                ${state.managerList.map(m => `<option value="${m.id}" ${task.target_manager_id == m.id ? 'selected' : ''}>${m.name} (${m.divisi})</option>`).join('')}
                            </select>
                        </div>
                    `;
                    
                    utils.createModal('Edit Tugas', `
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
                                <div>
                                    <label class="block text-sm font-medium mb-1">Status *</label>
                                    <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="proses" ${task.status === 'proses' ? 'selected' : ''}>Dalam Proses</option>
                                        <option value="selesai" ${task.status === 'selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="dibatalkan" ${task.status === 'dibatalkan' ? 'selected' : ''}>Dibatalkan</option>
                                    </select>
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
                        await api.updateTask(id, data);
                    });
                } catch (error) {
                    utils.showToast('Gagal memuat data untuk edit', 'error');
                }
            },
            
            showDelete: async (id) => {
                try {
                    const task = await api.getTaskDetail(id);
                    
                    if (task.created_by !== state.currentUser.id) {
                        utils.showToast('Anda tidak memiliki izin untuk menghapus tugas ini', 'error');
                        return;
                    }
                    
                    const modalEl = utils.createModal('Hapus Tugas', `
                        <div class="space-y-4">
                            <div class="text-center">
                                <span class="material-icons-outlined text-red-600 text-5xl mb-4">warning</span>
                                <h4 class="text-lg font-medium mb-2">Konfirmasi Hapus Tugas</h4>
                                <p class="text-gray-600">Apakah Anda yakin ingin menghapus tugas ini?</p>
                                <p class="font-medium mt-2">"${task.judul}"</p>
                                <p class="text-sm text-gray-500 mt-1">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                            <div class="flex gap-2 pt-4 border-t">
                                <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg flex-1">Batal</button>
                                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg flex-1 hover:bg-red-700 transition-colors">Hapus</button>
                            </div>
                        </div>
                    `);
                    
                    // Fix: Add event listener directly to the button
                    const confirmBtn = modalEl.querySelector('#confirmDeleteBtn');
                    confirmBtn.addEventListener('click', async () => {
                        try {
                            // Disable button to prevent multiple clicks
                            confirmBtn.disabled = true;
                            confirmBtn.innerHTML = '<span class="material-icons-outlined animate-spin mr-2">refresh</span>Menghapus...';
                            
                            await api.deleteTask(id);
                            modalEl.remove();
                        } catch (error) {
                            // Re-enable button if deletion fails
                            confirmBtn.disabled = false;
                            confirmBtn.innerHTML = 'Hapus';
                            utils.showToast('Gagal menghapus tugas: ' + error.message, 'error');
                        }
                    });
                } catch (error) {
                    utils.showToast('Gagal memuat data untuk hapus', 'error');
                }
            },
            
            showCreate: () => {
                const modalEl = utils.createModal('Buat Tugas Baru', `
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
                                <div class="flex gap-2 mb-3" id="assigneeTypeContainer">
                                    <button type="button" class="assignee-type-btn active flex-1" data-type="divisi">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined mb-1">groups</span>
                                            <span class="text-xs">Divisi</span>
                                        </div>
                                    </button>
                                    <button type="button" class="assignee-type-btn flex-1" data-type="manager">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined mb-1">supervisor_account</span>
                                            <span class="text-xs">Manajer</span>
                                        </div>
                                    </button>
                                </div>
                                <input type="hidden" name="target_type" value="divisi">
                            </div>
                            <div id="assigneeSelection">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Divisi *</label>
                                    <select name="target_divisi" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Divisi</option>
                                        ${state.divisiList.map(d => `<option value="${d}">${d}</option>`).join('')}
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Status *</label>
                                <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                    <option value="pending" selected>Pending</option>
                                    <option value="proses">Dalam Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
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
                    await api.createTask(data);
                });
                
                // Add event listeners for assignee type buttons
                setTimeout(() => {
                    const assigneeTypeBtns = modalEl.querySelectorAll('.assignee-type-btn');
                    assigneeTypeBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            assigneeTypeBtns.forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                            
                            const type = this.dataset.type;
                            modalEl.querySelector('input[name="target_type"]').value = type;
                            
                            const assigneeDiv = modalEl.querySelector('#assigneeSelection');
                            const html = type === 'divisi' ? `
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Divisi *</label>
                                    <select name="target_divisi" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Divisi</option>
                                        ${state.divisiList.map(d => `<option value="${d}">${d}</option>`).join('')}
                                    </select>
                                </div>
                            ` : `
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Manajer Divisi *</label>
                                    <select name="target_manager_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Manajer Divisi</option>
                                        ${state.managerList.map(m => `<option value="${m.id}">${m.name} (${m.divisi})</option>`).join('')}
                                    </select>
                                </div>
                            `;
                            
                            assigneeDiv.innerHTML = html;
                        });
                    });
                }, 100);
            }
        };

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Setup UI berdasarkan role
            utils.setupUIByRole();
            
            // Initial load
            api.fetchTasks();
            
            // Tab switching - hanya untuk General Manager
            if (state.currentRole === 'general_manager') {
                ['tabMyTasks', 'tabTeamTasks'].forEach(tabId => {
                    document.getElementById(tabId).addEventListener('click', function() {
                        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        state.currentTab = this.dataset.tab;
                        state.currentPage = 1;
                        api.fetchTasks();
                    });
                });
            }
            
            // Filters
            document.getElementById('searchInput').addEventListener('input', render.filterTasks);
            document.getElementById('statusFilter').addEventListener('change', render.filterTasks);
            
            // Divisi filter hanya untuk General Manager
            if (state.currentRole === 'general_manager') {
                document.getElementById('divisiFilter').addEventListener('change', render.filterTasks);
            }
            
            // Buttons
            document.getElementById('refreshBtn').addEventListener('click', () => {
                api.fetchTasks();
                utils.showToast('Data diperbarui');
            });
            
            // Buat tugas hanya untuk General Manager
            if (state.currentRole === 'general_manager') {
                document.getElementById('buatTugasBtn').addEventListener('click', modal.showCreate);
            }
            
            document.getElementById('closeToast').addEventListener('click', () => {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Pagination
            ['desktopPrevPage', 'prevPage'].forEach(id => {
                document.getElementById(id).addEventListener('click', () => {
                    if (state.currentPage > 1) {
                        state.currentPage--;
                        render.renderTable();
                    }
                });
            });
            
            ['desktopNextPage', 'nextPage'].forEach(id => {
                document.getElementById(id).addEventListener('click', () => {
                    if (state.currentPage < state.totalPages) {
                        state.currentPage++;
                        render.renderTable();
                    }
                });
            });
            
            // Auto-refresh every 60 seconds
            setInterval(() => {
                api.fetchTasks();
            }, 60000);
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
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
            margin-left: 0; /* Mobile: No margin */
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

        @media (max-width: 767px) { .desktop-only { display: none !important; } .mobile-cards { display: block !important; } .desktop-table { display: none !important; } .desktop-pagination { display: none !important; } 
        @media (min-width: 768px) { .mobile-only { display: none !important; } .mobile-cards { display: none !important; } .desktop-table { display: block !important; } 

        .hamburger-line { transition: all 0.3s ease-in-out; transform-origin: center; }
        .hamburger-active .line1 { transform: rotate(45deg) translate(5px, 6px); }
        .hamburger-active .line2 { opacity: 0; }
        .hamburger-active .line3 { transform: rotate(-45deg) translate(5px, -6px); }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    
    <!-- Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- APP CONTAINER -->
    <div class="app-container">
        
        <!-- SIDEBAR SECTION -->
         <div class="sidebar">
            @include('manager_divisi/templet/sider')
        </div>

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
                    <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">
                        <span id="pageTitle">Kelola Tugas</span>
                    </h2>
                    
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
                            
                            <button id="buatTugasBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                                <span class="material-icons-outlined">add</span>
                                <span class="hidden sm:inline">Buat Tugas</span>
                                <span class="sm:hidden">Buat</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Table Panel -->
                    <div class="panel">
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
                    Copyright ©2025 oleh digicity.id
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

    <!-- Toast -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center z-50">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200"><span class="material-icons-outlined">close</span></button>
    </div>

    <script>
        // State Management
        const state = {
            currentPage: 1,
            itemsPerPage: 10,
            totalPages: 1,
            allTasks: [
                { id: 1, judul: 'Fix Bug Login Page', deskripsi: 'Perbaiki error saat login user admin pada browser Safari', deadline: '2023-12-31', assigned_to: 101, assigned_user: { name: 'Budi Santoso' }, target_divisi: 'Programmer', status: 'proses', is_overdue: false },
                { id: 2, judul: 'Update Homepage Banner', deskripsi: 'Ubah banner dan teks hero section sesuai request client', deadline: '2024-01-15', assigned_to: 102, assigned_user: { name: 'Siti Aminah' }, target_divisi: 'Desainer', status: 'pending', is_overdue: false },
                { id: 3, judul: 'SEO Optimization', deskripsi: 'Optimasi kata kunci untuk landing page', deadline: '2024-01-10', assigned_to: 101, assigned_user: { name: 'Budi Santoso' }, target_divisi: 'Marketing', status: 'selesai', is_overdue: false },
                { id: 4, judul: 'Buat API Absensi', deskripsi: 'Buat endpoint untuk absensi masuk dan pulang', deadline: '2023-11-01', assigned_to: 101, assigned_user: { name: 'Budi Santoso' }, target_divisi: 'Programmer', status: 'dibatalkan', is_overdue: true },
                { id: 5, judul: 'Desain Iklan Sosmed', deskripsi: 'Buat desain banner untuk Instagram dan Facebook', deadline: '2024-02-01', assigned_to: 102, assigned_user: { name: 'Siti Aminah' }, target_divisi: 'Desainer', status: 'pending', is_overdue: false },
                { id: 6, judul: 'Maintenance Server', deskripsi: 'Cek performansi server dan backup database', deadline: '2023-12-20', assigned_to: 101, assigned_user: { name: 'Budi Santoso' }, target_divisi: 'Programmer', status: 'selesai', is_overdue: false },
            ],
            filteredTasks: [],
            currentUser: { id: 1, name: 'Manager Divisi', role: 'manager_divisi', divisi: 'Programmer' }, // Mock Data
            currentDivisi: 'Programmer',
            currentRole: 'manager_divisi',
            karyawanList: [
                { id: 101, name: 'Budi Santoso', email: 'budi@mail.com', divisi: 'Programmer' },
                { id: 102, name: 'Siti Aminah', email: 'siti@mail.com', divisi: 'Desainer' },
                { id: 103, name: 'Rudi Hartono', email: 'rudi@mail.com', divisi: 'Marketing' }
            ],
            divisiList: ['Programmer', 'Desainer', 'Marketing']
        };

        const buildUrl = (template, id = null) => template; // Dummy function, tidak ada route backend

        // Utility Functions
        const utils = {
            getStatusClass: (status) => `status-${status}`,
            getStatusText: (status) => ({ 'pending': 'Pending', 'proses': 'Dalam Proses', 'selesai': 'Selesai', 'dibatalkan': 'Dibatalkan' }[status] || status),
            getDivisiClass: (divisi) => {
                if (!divisi) return 'badge-programmer';
                const d = divisi.toLowerCase();
                if (d.includes('marketing')) return 'badge-marketing';
                if (d.includes('program')) return 'badge-programmer';
                if (d.includes('desain')) return 'badge-desainer';
                return 'badge-programmer';
            },
            formatDate: (dateString) => dateString ? new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-',
            formatDateTime: (dateString) => dateString ? new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-',
            showToast: (message, type = 'success') => {
                const t = document.getElementById('toast');
                const m = document.getElementById('toastMessage');
                t.style.backgroundColor = type === 'error' ? '#ef4444' : '#10b981';
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
                if (show) Object.values(els).forEach(e => e.style.display = 'none');
                else els.l.style.display = 'none';
            },
            createModal: (title, content, onSubmit = null) => {
                const tpl = document.getElementById('modalTemplate').cloneNode(true);
                tpl.id = 'activeModal'; tpl.style.display = 'flex';
                tpl.querySelector('.modal-title').textContent = title;
                tpl.querySelector('.modal-content').innerHTML = content;
                const closeModal = () => tpl.remove();
                tpl.querySelectorAll('.close-modal').forEach(b => b.addEventListener('click', closeModal));
                tpl.addEventListener('click', (e) => e.target === tpl && closeModal());
                if (onSubmit) {
                    const f = tpl.querySelector('form');
                    f?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const data = Object.fromEntries(new FormData(f).entries());
                        try { await onSubmit(data); closeModal(); } catch (err) { console.error(err); }
                    });
                }
                document.body.appendChild(tpl); return tpl;
            },
            setupUIByRole: () => {
                document.getElementById('pageTitle').textContent = `Kelola Tugas - ${state.currentDivisi}`;
                document.getElementById('buatTugasBtn').style.display = 'flex';
            }
        };

        // API Functions (NO ROUTES - SEMUA MOCK)
        const api = {
            request: async (url, options = {}) => {
                // Tidak ada request network, simulasi delay
                return new Promise(resolve => setTimeout(() => resolve({ success: true }), 500)); 
            },
            fetchTasks: async () => {
                utils.showLoading(true);
                try {
                    // Tidak ada endpoint, langsung gunakan data dummy di state.allTasks
                    await api.request();
                    
                    state.filteredTasks = [...state.allTasks];
                    render.renderTable();
                } catch (error) {
                    utils.showToast('Gagal memuat data', 'error');
                    state.allTasks = []; 
                    state.filteredTasks = []; 
                    render.renderTable();
                } finally { utils.showLoading(false); }
            },
            createTask: async (data) => {
                // Tidak ada endpoint, manipulasi local state saja
                const newTask = { 
                    id: Date.now(), 
                    ...data, 
                    status: 'pending', 
                    assigned_user: state.karyawanList.find(k => k.id == data.assigned_to),
                    target_divisi: state.karyawanList.find(k => k.id == data.assigned_to)?.divisi,
                    deadline: data.deadline // Simpan format string asli
                };
                state.allTasks.unshift(newTask);
                state.filteredTasks = [...state.allTasks];
                render.renderTable();
                utils.showToast('Tugas dibuat');
            },
            deleteTask: async (id) => {
                state.allTasks = state.allTasks.filter(t => t.id != id);
                state.filteredTasks = [...state.allTasks];
                render.renderTable();
                utils.showToast('Tugas dihapus');
            },
            getTaskDetail: async (id) => {
                return { success: true, task: state.allTasks.find(t => t.id == id) };
            },
            updateTask: async (id, data) => {
                const idx = state.allTasks.findIndex(t => t.id == id);
                if (idx !== -1) {
                    state.allTasks[idx] = { ...state.allTasks[idx], ...data };
                    state.filteredTasks = [...state.allTasks];
                    render.renderTable();
                    utils.showToast('Tugas diupdate');
                }
            },
            // --- FITUR BARU: KOMENTAR ---
            fetchComments: async (taskId) => {
                // Mockup data komentar kosong
                return []; 
            },
            storeComment: async (taskId, content) => {
                // Mockup pengiriman komentar
                return { success: true }; 
            }
        };

        // Render Functions
        const render = {
            filterTasks: () => {
                const s = document.getElementById('searchInput').value.toLowerCase();
                const st = document.getElementById('statusFilter').value;
                
                state.filteredTasks = state.allTasks.filter(t => {
                    const matchS = t.judul?.toLowerCase().includes(s) || t.deskripsi?.toLowerCase().includes(s);
                    const matchSt = st === 'all' || t.status === st;
                    return matchS && matchSt;
                });
                state.currentPage = 1; render.renderTable();
            },
            renderTable: () => {
                const start = (state.currentPage - 1) * state.itemsPerPage;
                const end = Math.min(start + state.itemsPerPage, state.filteredTasks.length);
                const tasks = state.filteredTasks.slice(start, end);
                
                document.getElementById('totalCount').textContent = state.filteredTasks.length;
                document.getElementById('panelTitle').textContent = `Tugas Divisi ${state.currentDivisi}`;
                
                const tb = document.getElementById('desktopTableBody'); tb.innerHTML = '';
                
                if (tasks.length === 0) {
                    document.getElementById('noDataMessage').style.display = 'block';
                    ['desktopTable','desktopPaginationContainer'].forEach(id => document.getElementById(id).style.display = 'none');
                    document.querySelector('.mobile-pagination').style.display = 'none';
                    return;
                }
                
                const isOwner = true; // Di prototype, semua user bisa edit
                
                tasks.forEach((t, i) => {
                    const assigneeName = t.assigned_user?.name || state.karyawanList.find(k=>k.id==t.assigned_to)?.name || '-';
                    tb.innerHTML += `
                        <tr>
                            <td>${start+i+1}</td>
                            <td class="font-medium">${t.judul}</td>
                            <td class="truncate-text" title="${t.deskripsi}">${t.deskripsi.substring(0,50)}...</td>
                            <td class="${t.is_overdue?'text-red-600':''}">${utils.formatDate(t.deadline)}</td>
                            <td>${assigneeName}</td>
                            <td><span class="badge ${utils.getDivisiClass(t.target_divisi)}">${t.target_divisi||'-'}</span></td>
                            <td><span class="badge ${utils.getStatusClass(t.status)}">${utils.getStatusText(t.status)}</span></td>
                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick="modal.showDetail(${t.id})" class="p-2 rounded-full hover:bg-blue-100"><span class="material-icons-outlined text-blue-600">visibility</span></button>
                                    <button onclick="modal.showEdit(${t.id})" class="p-2 rounded-full hover:bg-blue-100"><span class="material-icons-outlined text-green-600">edit</span></button>
                                    <button onclick="modal.showDelete(${t.id})" class="p-2 rounded-full hover:bg-red-100"><span class="material-icons-outlined text-red-600">delete</span></button>
                                </div>
                            </td>
                        </tr>`;
                });

                // Mobile Cards
                document.getElementById('mobile-cards').innerHTML = tasks.map(t => {
                     const assigneeName = t.assigned_user?.name || state.karyawanList.find(k=>k.id==t.assigned_to)?.name || '-';
                    return `
                        <div class="bg-white rounded-lg border p-4 shadow-sm">
                            <div class="flex justify-between mb-2">
                                <h4 class="font-semibold">${t.judul}</h4>
                                <div class="flex gap-1">
                                    <button onclick="modal.showDetail(${t.id})"><span class="material-icons-outlined text-blue-600">visibility</span></button>
                                    ${isOwner?`<button onclick="modal.showEdit(${t.id})"><span class="material-icons-outlined text-green-600">edit</span></button>`:''}
                                </div>
                            </div>
                            <div class="flex gap-2 mb-2"><span class="badge ${utils.getStatusClass(t.status)}">${utils.getStatusText(t.status)}</span></div>
                            <p class="text-sm text-gray-600 truncate">${t.deskripsi}</p>
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
                const dp = document.getElementById('desktopPageNumbers'); dp.innerHTML='';
                for(let i=1;i<=state.totalPages;i++){
                    const b=document.createElement('button'); b.textContent=i;
                    b.className=i===state.currentPage?'desktop-page-btn active':'desktop-page-btn';
                    b.onclick=()=>{state.currentPage=i;render.renderTable();}; dp.appendChild(b);
                }
                document.getElementById('desktopPrevPage').disabled=state.currentPage===1;
                document.getElementById('desktopNextPage').disabled=state.currentPage===state.totalPages;
                
                const mp=document.getElementById('pageNumbers'); mp.innerHTML='';
                for(let i=1;i<=state.totalPages;i++){
                    const b=document.createElement('button'); b.textContent=i;
                    b.className=`w-8 h-8 rounded-full flex items-center justify-center text-sm ${i===state.currentPage?'bg-primary text-white':'bg-gray-200 text-gray-600'}`;
                    b.onclick=()=>{state.currentPage=i;render.renderTable();}; mp.appendChild(b);
                }
                document.getElementById('prevPage').disabled=state.currentPage===1;
                document.getElementById('nextPage').disabled=state.currentPage===state.totalPages;
                
                const show = state.totalPages > 1;
                document.getElementById('desktopPaginationContainer').style.display = show?'flex':'none';
                document.querySelector('.mobile-pagination').style.display = (show && window.innerWidth<640)?'flex':'none';
            }
        };

        // Modal Logic
        const modal = {
            showDetail: async (id) => {
                try {
                    const t = await api.getTaskDetail(id);
                    const assigneeName = t.assigned_user?.name || '-';
                    
                    // Komentar real-time (Mockup)
                    const comments = await api.fetchComments(id);
                    
                    const commentsHtml = comments.length === 0 ? 
                        `<p class="text-gray-500 text-sm italic text-center py-4">Belum ada komentar.</p>` : 
                        comments.map(c => `
                            <div class="bg-gray-50 p-3 rounded-lg mb-2 relative">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-sm text-gray-800">${c.user?.name || 'User'}</span>
                                    <span class="text-xs text-gray-500">${c.created_at ? utils.formatDateTime(c.created_at) : ''}</span>
                                </div>
                                <p class="text-gray-700 text-sm">${c.isi_komentar}</p>
                            </div>
                        `).join('');

                    utils.createModal('Detail Tugas', `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div><h4 class="text-sm text-gray-600">Judul</h4><p class="font-medium">${t.judul}</p></div>
                                <div><h4 class="text-sm text-gray-600">Deadline</h4><p>${utils.formatDate(t.deadline)}</p></div>
                                <div><h4 class="text-sm text-gray-600">Status</h4><span class="badge ${utils.getStatusClass(t.status)}">${utils.getStatusText(t.status)}</span></div>
                                <div><h4 class="text-sm text-gray-600">Kepada</h4><p>${assigneeName}</p></div>
                            </div>
                            <div><h4 class="text-sm text-gray-600">Deskripsi</h4><p class="mt-1 whitespace-pre-line">${t.deskripsi}</p></div>
                            
                            <!-- BAGIAN KOMENTAR -->
                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-bold text-gray-800 mb-3">Diskusi Tugas</h4>
                                <div id="commentsContainer-${id}" class="max-h-60 overflow-y-auto space-y-2 mb-4">
                                    ${commentsHtml}
                                </div>
                                <form onsubmit="modal.submitComment(event, ${id})" class="mt-2">
                                    <div class="flex gap-2">
                                        <input type="text" name="comment" required placeholder="Tulis komentar..." class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary form-input">
                                        <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-sm">Kirim</button>
                                    </div>
                                </form>
                            </div>
                            
                            <button class="close-modal btn-secondary w-full py-2">Tutup</button>
                        </div>
                    `);
                } catch(e) { utils.showToast('Gagal load detail','error'); }
            },
            showEdit: async (id) => {
                try {
                    const t = await api.getTaskDetail(id);
                    const relK = state.karyawanList.filter(k=>k.divisi===state.currentDivisi);
                    utils.createModal('Edit Tugas', `
                        <form id="editTaskForm"><input type="hidden" name="id" value="${t.id}">
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium mb-1">Judul</label><input name="judul" value="${t.judul}" class="form-input" required></div>
                                <div><label class="block text-sm font-medium mb-1">Deskripsi</label><textarea name="deskripsi" rows="3" class="form-input" required>${t.deskripsi}</textarea></div>
                                <div><label class="block text-sm font-medium mb-1">Deadline</label><input type="datetime-local" name="deadline" value="${t.deadline?t.deadline.substring(0,16):''}" class="form-input" required></div>
                                <div><label class="block text-sm font-medium mb-1">Karyawan</label>
                                    <select name="assigned_to" class="form-input" required>
                                        <option value="">Pilih</option>${relK.map(k=>`<option value="${k.id}" ${t.assigned_to==k.id?'selected':''}>${k.name}</option>`).join('')}
                                    </select>
                                </div>
                                <div><label class="block text-sm font-medium mb-1">Status</label>
                                    <select name="status" class="form-input" required>
                                        <option value="pending" ${t.status==='pending'?'selected':''}>Pending</option>
                                        <option value="proses" ${t.status==='proses'?'selected':''}>Proses</option>
                                        <option value="selesai" ${t.status==='selesai'?'selected':''}>Selesai</option>
                                        <option value="dibatalkan" ${t.status==='dibatalkan'?'selected':''}>Batal</option>
                                    </select>
                                </div>
                                <div class="flex gap-2 pt-2"><button type="button" class="close-modal btn-secondary flex-1 py-2">Batal</button><button type="submit" class="btn-primary flex-1 py-2">Update</button></div>
                            </div>
                        </form>
                    `, async(d)=>await api.updateTask(id,d));
                } catch(e) { utils.showToast('Gagal load edit','error'); }
            },
            showDelete: async (id) => {
                try {
                    const t = await api.getTaskDetail(id);
                    const el = utils.createModal('Hapus Tugas', `
                        <div class="text-center">
                            <span class="material-icons-outlined text-red-600 text-5xl mb-4">warning</span>
                            <h4 class="font-bold mb-2">Hapus "${t.judul}"?</h4>
                            <div class="flex gap-2 mt-6 justify-center">
                                <button type="button" class="close-modal btn-secondary px-6 py-2">Batal</button>
                                <button id="confirmDel" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Hapus</button>
                            </div>
                        </div>
                    `);
                    el.querySelector('#confirmDel').onclick=async()=>{
                        try{ el.querySelector('#confirmDel').disabled=true; await api.deleteTask(id); el.remove(); }
                        catch(err){ utils.showToast(err.message,'error'); }
                    };
                } catch(e) { utils.showToast('Gagal load hapus','error'); }
            },
            // Fungsi Submit Komentar
            submitComment: async (event, taskId) => {
                event.preventDefault();
                const form = event.target;
                const input = form.querySelector('input[name="comment"]');
                
                if(input.value.trim() === '') return;
                
                try {
                    const btn = form.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.innerHTML = 'Mengirim...';
                    
                    // Mockup simpan koneksi API
                    await api.storeComment(taskId, input.value);
                    
                    // Update UI
                    input.value = '';
                    // Refresh komentar di modal
                    const commentsContainer = document.getElementById(`commentsContainer-${taskId}`);
                    if(commentsContainer) {
                        // Tambahkan komentar baru ke atas
                        const newCommentHtml = `
                            <div class="bg-gray-50 p-3 rounded-lg mb-2 relative animate-fade-in">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-sm text-gray-800">Anda</span>
                                    <span class="text-xs text-gray-500">Baru saja</span>
                                </div>
                                <p class="text-gray-700 text-sm">${input.value}</p>
                            </div>
                        `;
                        commentsContainer.insertAdjacentHTML('afterbegin', newCommentHtml);
                        utils.showToast('Komentar terkirim');
                    }
                } catch (e) {
                    console.error(e);
                    utils.showToast('Terjadi kesalahan', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerText = 'Kirim';
                }
            },
            showCreate: () => {
                const relK = state.karyawanList.filter(k=>k.divisi===state.currentDivisi);
                utils.createModal('Buat Tugas', `
                    <form id="createTaskForm">
                        <div class="space-y-4">
                            <div><label class="block text-sm font-medium mb-1">Judul</label><input name="judul" class="form-input" required></div>
                            <div><label class="block text-sm font-medium mb-1">Deskripsi</label><textarea name="deskripsi" rows="3" class="form-input" required></textarea></div>
                            <div><label class="block text-sm font-medium mb-1">Deadline</label><input type="datetime-local" name="deadline" class="form-input" required></div>
                            <div><label class="block text-sm font-medium mb-1">Karyawan</label>
                                <select name="assigned_to" class="form-input" required>
                                    <option value="">Pilih</option>${relK.map(k=>`<option value="${k.id}">${k.name}</option>`).join('')}
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium mb-1">Status</label>
                                <select name="status" class="form-input" required><option value="pending">Pending</option><option value="proses">Proses</option></select>
                            </div>
                            <div class="flex gap-2 pt-2"><button type="button" class="close-modal btn-secondary flex-1 py-2">Batal</button><button type="submit" class="btn-primary flex-1 py-2">Simpan</button></div>
                        </div>
                    </form>
                `, async(d)=>await api.createTask(d));
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

            if(hamburger) {
                hamburger.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            // App Logic
            utils.setupUIByRole();
            api.fetchTasks();
            
            document.getElementById('searchInput').addEventListener('input', render.filterTasks);
            document.getElementById('statusFilter').addEventListener('change', render.filterTasks);
            document.getElementById('refreshBtn').addEventListener('click', () => { api.fetchTasks(); utils.showToast('Data di-refresh'); });
            
            const createBtn = document.getElementById('buatTugasBtn');
            if(createBtn && createBtn.style.display !== 'none') createBtn.addEventListener('click', modal.showCreate);
            
            document.getElementById('closeToast').addEventListener('click', () => document.getElementById('toast').classList.add('translate-y-20','opacity-0'));
            
            ['desktopPrevPage','prevPage'].forEach(id=>document.getElementById(id)?.addEventListener('click',()=>{if(state.currentPage>1){state.currentPage--;render.renderTable();}}));
            ['desktopNextPage','nextPage'].forEach(id=>document.getElementById(id)?.addEventListener('click',()=>{if(state.currentPage<state.totalPages){state.currentPage++;render.renderTable();}}));

            setInterval(api.fetchTasks, 60000);
        });
    </script>
</body>
</html>
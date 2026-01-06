<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
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
        .order-table tr:hover { background-color: rgba(59, 130, 246, 0.05); }
        .btn-primary { background-color: #3b82f6; color: white; transition: all 0.2s ease; }
        .btn-primary:hover { background-color: #2563eb; }
        .btn-secondary { background-color: #f1f5f9; color: #64748b; transition: all 0.2s ease; }
        .btn-secondary:hover { background-color: #e2e8f0; }
        .modal { transition: opacity 0.25s ease; }
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-todo { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .status-progress { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .status-done { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .nav-item::before { content: ''; position: absolute; right: 0; top: 0; height: 100%; width: 3px; background-color: #3b82f6; transform: translateX(100%); transition: transform 0.3s ease; }
        @media (min-width: 768px) { .nav-item::before { right: auto; left: 0; transform: translateX(-100%); } .main-content { margin-left: 256px; } }
        .nav-item:hover::before, .nav-item.active::before { transform: translateX(0); }
        @media (max-width: 639px) { .desktop-table { display: none; } .mobile-cards { display: block; } .desktop-pagination { display: none !important; } }
        @media (min-width: 640px) { .desktop-table { display: block; } .mobile-cards { display: none; } .mobile-pagination { display: none !important; } }
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
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-gray-50 text-gray-800">
   @include('general_manajer/templet/header')
    <!-- Main Content Container -->
    <div class="main-content">
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Tugas</h2>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
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
                                <p class="text-2xl font-bold text-yellow-600" id="pendingTasks">0</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <span class="material-icons-outlined text-yellow-600">pending</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">In Progress</p>
                                <p class="text-2xl font-bold text-blue-600" id="progressTasks">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <span class="material-icons-outlined text-blue-600">timeline</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Completed</p>
                                <p class="text-2xl font-bold text-green-600" id="completedTasks">0</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <span class="material-icons-outlined text-green-600">check_circle</span>
                            </div>
                        </div>
                    </div>
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
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
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
                            Daftar Tugas
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
                                            <th style="min-width: 300px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Deadline</th>
                                            <th style="min-width: 150px;">Karyawan</th>
                                            <th style="min-width: 150px;">Pemberi Tugas</th>
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
        let karyawanList = @json($karyawan ?? []);

        // Utility Functions
        const getStatusClass = (status) => {
            const classes = { 
                'pending': 'status-todo', 
                'in_progress': 'status-progress', 
                'completed': 'status-done' 
            };
            return classes[status] || 'status-todo';
        };

        const getStatusText = (status) => {
            const texts = {
                'pending': 'Pending',
                'in_progress': 'In Progress',
                'completed': 'Completed'
            };
            return texts[status] || status;
        };

        const getPriorityText = (priority) => {
            const texts = {
                'low': 'Rendah',
                'medium': 'Sedang',
                'high': 'Tinggi'
            };
            return texts[priority] || priority;
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

        const showToast = (message, type = 'success') => {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Set background color based on type
            if (type === 'error') {
                toast.style.backgroundColor = '#ef4444'; // red-500
            } else if (type === 'warning') {
                toast.style.backgroundColor = '#f59e0b'; // yellow-500
            } else {
                toast.style.backgroundColor = '#10b981'; // green-500
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
            
            // Close button handler
            template.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (onClose) onClose();
                    template.remove();
                });
            });
            
            // Close on background click
            template.addEventListener('click', (e) => {
                if (e.target === template) {
                    if (onClose) onClose();
                    template.remove();
                }
            });
            
            // Form submit handler
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

        // API Functions
        const fetchTasks = async () => {
            try {
                showLoading(true);
                
                const response = await fetch('/api/general-manager/tasks', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const result = await response.json();
                
                if (result.success) {
                    allTasks = result.tasks || [];
                    filteredTasks = [...allTasks];
                    renderTable();
                    updateStatistics();
                } else {
                    throw new Error(result.message || 'Failed to fetch tasks');
                }
            } catch (error) {
                console.error('Error fetching tasks:', error);
                showToast('Gagal memuat data tugas', 'error');
                allTasks = [];
                filteredTasks = [];
                renderTable();
            } finally {
                showLoading(false);
            }
        };

        const updateStatistics = async () => {
            try {
                const response = await fetch('/api/general-manager/tasks/statistics');
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('totalTasks').textContent = result.stats.total;
                    document.getElementById('pendingTasks').textContent = result.stats.pending;
                    document.getElementById('progressTasks').textContent = result.stats.in_progress;
                    document.getElementById('completedTasks').textContent = result.stats.completed;
                }
            } catch (error) {
                console.error('Error fetching statistics:', error);
            }
        };

        const createTask = async (data) => {
            try {
                const response = await fetch('/api/general-manager/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Tugas berhasil dibuat');
                    await fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to create task');
                }
            } catch (error) {
                console.error('Error creating task:', error);
                showToast('Gagal membuat tugas: ' + error.message, 'error');
                throw error;
            }
        };

        const updateTask = async (id, data) => {
            try {
                const response = await fetch(`/api/general-manager/tasks/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
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

        const deleteTask = async (id) => {
            try {
                const response = await fetch(`/api/general-manager/tasks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Tugas berhasil dihapus');
                    await fetchTasks();
                } else {
                    throw new Error(result.message || 'Failed to delete task');
                }
            } catch (error) {
                console.error('Error deleting task:', error);
                showToast('Gagal menghapus tugas: ' + error.message, 'error');
                throw error;
            }
        };

        // Render Functions
        const filterTasks = () => {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            
            filteredTasks = allTasks.filter(task => {
                const matchesSearch = 
                    task.title.toLowerCase().includes(searchTerm) ||
                    task.description.toLowerCase().includes(searchTerm) ||
                    (task.user?.name || '').toLowerCase().includes(searchTerm);
                
                const matchesStatus = statusFilter === 'all' || task.status === statusFilter;
                
                return matchesSearch && matchesStatus;
            });
            
            currentPage = 1;
            renderTable();
        };

        const renderTable = () => {
            const start = (currentPage - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, filteredTasks.length);
            const pageTasks = filteredTasks.slice(start, end);
            
            // Update counters
            document.getElementById('totalCount').textContent = filteredTasks.length;
            document.getElementById('showingCount').textContent = pageTasks.length;
            
            // Render desktop table
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
                        <td class="font-medium">${task.title}</td>
                        <td class="truncate-text" title="${task.description}">${task.description.substring(0, 50)}${task.description.length > 50 ? '...' : ''}</td>
                        <td>${formatDate(task.deadline)}</td>
                        <td>${task.user?.name || '-'}</td>
                        <td>${task.assigner || '-'}</td>
                        <td><span class="status-badge ${getStatusClass(task.status)}">${getStatusText(task.status)}</span></td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <button onclick="showDetail(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Detail">
                                    <span class="material-icons-outlined text-blue-600">visibility</span>
                                </button>
                                <button onclick="showEdit(${task.id})" class="p-2 rounded-full hover:bg-blue-100" title="Edit">
                                    <span class="material-icons-outlined text-green-600">edit</span>
                                </button>
                                <button onclick="showDelete(${task.id})" class="p-2 rounded-full hover:bg-red-100" title="Hapus">
                                    <span class="material-icons-outlined text-red-600">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            // Render mobile cards
            const mobileContainer = document.getElementById('mobile-cards');
            mobileContainer.innerHTML = '';
            
            pageTasks.forEach((task, index) => {
                const rowNum = start + index + 1;
                mobileContainer.innerHTML += `
                    <div class="bg-white rounded-lg border border-gray-300 p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-base">${task.title}</h4>
                                <p class="text-sm text-gray-600">Deadline: ${formatDate(task.deadline)}</p>
                            </div>
                            <div class="flex gap-1">
                                <button onclick="showDetail(${task.id})" class="p-2" title="Detail">
                                    <span class="material-icons-outlined text-blue-600">visibility</span>
                                </button>
                                <button onclick="showEdit(${task.id})" class="p-2" title="Edit">
                                    <span class="material-icons-outlined text-green-600">edit</span>
                                </button>
                                <button onclick="showDelete(${task.id})" class="p-2" title="Hapus">
                                    <span class="material-icons-outlined text-red-600">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                            <div><p class="text-gray-600">Karyawan</p><p class="font-medium">${task.user?.name || '-'}</p></div>
                            <div><p class="text-gray-600">Pemberi</p><p class="font-medium">${task.assigner || '-'}</p></div>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Status</p>
                            <span class="status-badge ${getStatusClass(task.status)}">${getStatusText(task.status)}</span>
                        </div>
                    </div>
                `;
            });
            
            // Show appropriate views
            document.getElementById('noDataMessage').style.display = 'none';
            document.getElementById('desktopTable').style.display = 'block';
            document.getElementById('mobile-cards').style.display = window.innerWidth < 640 ? 'block' : 'none';
            
            // Update pagination
            updatePagination();
        };

        const updatePagination = () => {
            totalPages = Math.ceil(filteredTasks.length / itemsPerPage);
            
            // Desktop pagination
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
            
            // Mobile pagination
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
            
            // Show/hide pagination
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
                const response = await fetch(`/api/general-manager/tasks/${id}`);
                const result = await response.json();
                
                if (result.success) {
                    const task = result.task;
                    createModal('Detail Tugas', `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div><h4 class="text-sm text-gray-600">ID</h4><p class="font-medium">#${task.id}</p></div>
                                <div><h4 class="text-sm text-gray-600">Judul</h4><p class="font-medium">${task.title}</p></div>
                                <div><h4 class="text-sm text-gray-600">Deadline</h4><p class="font-medium">${formatDate(task.deadline)}</p></div>
                                <div><h4 class="text-sm text-gray-600">Status</h4><span class="status-badge ${getStatusClass(task.status)}">${getStatusText(task.status)}</span></div>
                                <div><h4 class="text-sm text-gray-600">Karyawan</h4><p class="font-medium">${task.user?.name || '-'}</p></div>
                                <div><h4 class="text-sm text-gray-600">Pemberi Tugas</h4><p class="font-medium">${task.assigner || '-'}</p></div>
                                <div><h4 class="text-sm text-gray-600">Prioritas</h4><p class="font-medium">${getPriorityText(task.priority)}</p></div>
                                <div><h4 class="text-sm text-gray-600">Kategori</h4><p class="font-medium">${task.category || '-'}</p></div>
                            </div>
                            <div><h4 class="text-sm text-gray-600">Deskripsi Lengkap</h4><p class="mt-1 whitespace-pre-line">${task.full_description || task.description}</p></div>
                            <button class="close-modal px-4 py-2 btn-secondary rounded-lg w-full mt-4">Tutup</button>
                        </div>
                    `);
                }
            } catch (error) {
                console.error('Error loading task detail:', error);
                showToast('Gagal memuat detail tugas', 'error');
            }
        };

        const showEdit = async (id) => {
            try {
                const response = await fetch(`/api/general-manager/tasks/${id}`);
                const result = await response.json();
                
                if (result.success) {
                    const task = result.task;
                    const karyawanOptions = karyawanList.map(k => 
                        `<option value="${k.id}" ${task.user_id == k.id ? 'selected' : ''}>${k.name} (${k.email})</option>`
                    ).join('');
                    
                    createModal('Edit Tugas', `
                        <form id="editTaskForm">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Judul Tugas *</label>
                                    <input name="title" value="${task.title}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Deskripsi *</label>
                                    <textarea name="description" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>${task.description}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Deadline *</label>
                                    <input type="datetime-local" name="deadline" value="${task.deadline ? task.deadline.substring(0, 16) : ''}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Karyawan *</label>
                                    <select name="user_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                        <option value="">Pilih Karyawan</option>
                                        ${karyawanOptions}
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Prioritas *</label>
                                        <select name="priority" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                            <option value="low" ${task.priority === 'low' ? 'selected' : ''}>Rendah</option>
                                            <option value="medium" ${task.priority === 'medium' ? 'selected' : ''}>Sedang</option>
                                            <option value="high" ${task.priority === 'high' ? 'selected' : ''}>Tinggi</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Status *</label>
                                        <select name="status" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                            <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                            <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                            <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kategori</label>
                                    <input name="category" value="${task.category || ''}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Opsional">
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
                }
            } catch (error) {
                console.error('Error loading task for edit:', error);
                showToast('Gagal memuat data untuk edit', 'error');
            }
        };

        const showDelete = (id) => {
            const task = allTasks.find(t => t.id === id);
            if (!task) return;
            
            createModal('Konfirmasi Hapus', `
                <div class="space-y-4">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus tugas ini?</p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="font-medium text-red-800">${task.title}</p>
                        <p class="text-sm text-red-600 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <div class="flex gap-2 pt-4 border-t">
                        <button class="close-modal px-4 py-2 btn-secondary rounded-lg flex-1">Batal</button>
                        <button onclick="confirmDelete(${id})" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg flex-1">Hapus</button>
                    </div>
                </div>
            `);
        };

        const confirmDelete = async (id) => {
            try {
                await deleteTask(id);
                document.querySelector('#activeModal').remove();
            } catch (error) {
                console.error('Error deleting task:', error);
            }
        };

        const showCreateModal = () => {
            const karyawanOptions = karyawanList.map(k => 
                `<option value="${k.id}">${k.name} (${k.email})</option>`
            ).join('');
            
            createModal('Buat Tugas Baru', `
                <form id="createTaskForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Judul Tugas *</label>
                            <input name="title" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required placeholder="Masukkan judul tugas">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Deskripsi *</label>
                            <textarea name="description" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required placeholder="Masukkan deskripsi tugas"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Deadline *</label>
                            <input type="datetime-local" name="deadline" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Karyawan *</label>
                            <select name="user_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                <option value="">Pilih Karyawan</option>
                                ${karyawanOptions}
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Prioritas *</label>
                                <select name="priority" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" required>
                                    <option value="low">Rendah</option>
                                    <option value="medium" selected>Sedang</option>
                                    <option value="high">Tinggi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kategori</label>
                                <input name="category" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Opsional">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Pemberi Tugas</label>
                            <input name="assigner" value="${currentUser?.name || ''}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Opsional">
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
        };

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Initial load
            fetchTasks();
            updateStatistics();
            
            // Event listeners
            document.getElementById('searchInput').addEventListener('input', filterTasks);
            document.getElementById('statusFilter').addEventListener('change', filterTasks);
            document.getElementById('refreshBtn').addEventListener('click', () => {
                fetchTasks();
                updateStatistics();
                showToast('Data diperbarui');
            });
            document.getElementById('buatTugasBtn').addEventListener('click', showCreateModal);
            document.getElementById('closeToast').addEventListener('click', () => {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Pagination
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
                fetchTasks();
                updateStatistics();
            }, 60000);
        });

        // Make functions available globally
        window.showDetail = showDetail;
        window.showEdit = showEdit;
        window.showDelete = showDelete;
        window.confirmDelete = confirmDelete;
    </script>
</body>
</html>
<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengumuman - Sistem Management</title>
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --secondary: #3a0ca3;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        
        .announcement-card {
            border-left: 5px solid var(--primary);
            position: relative;
            overflow: hidden;
        }
        
        .announcement-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .announcement-card.pinned {
            border-left-color: var(--warning);
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
        }
        
        .announcement-card.important {
            border-left-color: var(--danger);
            background: linear-gradient(135deg, #fee 0%, #fff 100%);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-primary {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }
        
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        
        .status-dot.read {
            background: var(--success);
        }
        
        .status-dot.unread {
            background: var(--danger);
        }
        
        .empty-state {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
        }
        
        .modal-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .search-box {
            background: white;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .search-box:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }
        
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
            }
        }
        
        .tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            background: #eef2ff;
            border-radius: 6px;
            margin: 2px;
            font-size: 12px;
            color: var(--primary);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        @include('admin.templet.sider')
        
        <!-- Main Content -->
        <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b">
                <div class="px-6 py-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-3">
                                <span class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg text-white">
                                    <i class="fas fa-bullhorn text-lg"></i>
                                </span>
                                Pengumuman
                            </h1>
                            <p class="text-gray-600 mt-1">Kelola dan lihat pengumuman perusahaan</p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="hidden md:flex items-center gap-3">
                                <span class="text-sm text-gray-500">Status:</span>
                                <div class="flex items-center gap-2">
                                    <span class="status-dot read"></span>
                                    <span class="text-sm">Dibaca</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="status-dot unread"></span>
                                    <span class="text-sm">Belum Dibaca</span>
                                </div>
                            </div>
                            
                            <button id="createBtn" class="btn-primary flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Buat Pengumuman
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="p-4 md:p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="card p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Pengumuman</p>
                                <h3 id="totalCount" class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-full">
                                <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span>Semua pengumuman aktif</span>
                        </div>
                    </div>
                    
                    <div class="card p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Belum Dibaca</p>
                                <h3 id="unreadCount" class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                            </div>
                            <div class="p-3 bg-red-50 rounded-full">
                                <i class="fas fa-envelope text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <span>Pengumuman baru untuk Anda</span>
                        </div>
                    </div>
                    
                    <div class="card p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Penting</p>
                                <h3 id="importantCount" class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded-full">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <span>Pengumuman prioritas tinggi</span>
                        </div>
                    </div>
                    
                    <div class="card p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Dibuat Bulan Ini</p>
                                <h3 id="monthlyCount" class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                            </div>
                            <div class="p-3 bg-green-50 rounded-full">
                                <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>{{ now()->format('F Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Filters & Search -->
                <div class="card p-5 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="search-box flex items-center p-3">
                                <i class="fas fa-search text-gray-400 mr-3"></i>
                                <input id="searchInput" type="text" 
                                       placeholder="Cari judul, isi, atau penerima pengumuman..."
                                       class="flex-1 outline-none text-gray-700">
                                <button id="clearSearch" class="ml-2 text-gray-400 hover:text-gray-600 hidden">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <select id="filterStatus" class="border rounded-lg px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="read">Sudah Dibaca</option>
                                <option value="unread">Belum Dibaca</option>
                            </select>
                            
                            <select id="filterType" class="border rounded-lg px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                <option value="important">Penting</option>
                                <option value="pinned">Disematkan</option>
                                <option value="general">Umum</option>
                            </select>
                            
                            <button id="refreshBtn" class="border rounded-lg px-4 py-2.5 hover:bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="mb-6">
                    <!-- Empty State -->
                    <div id="emptyState" class="empty-state fade-in">
                        <div class="flex justify-center mb-6">
                            <div class="p-6 bg-white/20 rounded-full">
                                <i class="fas fa-bullhorn text-5xl"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Belum Ada Pengumuman</h3>
                        <p class="text-white/80 mb-6 max-w-md mx-auto">
                            Mulai dengan membuat pengumuman pertama Anda. Bagikan informasi penting kepada tim Anda.
                        </p>
                        <button id="createFirstBtn" class="bg-white text-purple-700 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Pengumuman Pertama
                        </button>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loadingState" class="hidden">
                        <div class="flex flex-col items-center justify-center py-16">
                            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500 mb-6"></div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Memuat Pengumuman</h3>
                            <p class="text-gray-500">Mohon tunggu sebentar...</p>
                        </div>
                    </div>
                    
                    <!-- Announcement Grid -->
                    <div id="announcementGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6 hidden">
                        <!-- Cards will be inserted here by JavaScript -->
                    </div>
                    
                    <!-- Table View (Alternative) -->
                    <div id="tableView" class="hidden">
                        <div class="card overflow-hidden">
                            <div class="p-5 border-b flex justify-between items-center">
                                <h3 class="font-semibold text-lg">Daftar Pengumuman</h3>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Tampilan:</span>
                                        <button id="gridViewBtn" class="p-2 text-gray-500 hover:text-blue-600">
                                            <i class="fas fa-th-large"></i>
                                        </button>
                                        <button id="listViewBtn" class="p-2 text-blue-600">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="p-4 text-left font-semibold text-gray-700">Judul</th>
                                            <th class="p-4 text-left font-semibold text-gray-700">Pembuat</th>
                                            <th class="p-4 text-left font-semibold text-gray-700">Penerima</th>
                                            <th class="p-4 text-left font-semibold text-gray-700">Tanggal</th>
                                            <th class="p-4 text-left font-semibold text-gray-700">Status</th>
                                            <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <!-- Table rows will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4 border-t flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    Menampilkan <span id="showingCount">0</span> dari <span id="totalTableCount">0</span>
                                </div>
                                <div class="flex gap-2" id="pagination">
                                    <!-- Pagination will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="mt-8 p-6 border-t text-center text-gray-500 text-sm">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <span class="font-medium text-gray-700">Sistem Pengumuman</span> • 
                        v1.0.0 • 
                        {{ now()->format('d M Y') }}
                    </div>
                    <div class="mt-2 md:mt-0">
                        © 2025 digicity.id • 
                        <span id="lastUpdate" class="text-blue-600">Baru saja diperbarui</span>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    
    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 z-50 modal-overlay flex items-center justify-center p-4">
        <div class="modal-content w-full max-w-4xl max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 id="modalTitle" class="text-2xl font-bold text-gray-800"></h3>
                        <p id="modalSubtitle" class="text-gray-600"></p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="modalContent" class="mb-6"></div>
                
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button onclick="closeModal()" class="px-6 py-3 border rounded-lg hover:bg-gray-50 font-medium">
                        Batal
                    </button>
                    <button id="confirmBtn" class="btn-primary flex items-center gap-2">
                        <span id="confirmBtnText">Simpan</span>
                        <span id="loadingSpinner" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div id="toast" class="hidden notification-toast">
        <div class="card p-4 max-w-sm">
            <div class="flex items-start gap-3">
                <div id="toastIcon" class="p-2 rounded-full">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="flex-1">
                    <h4 id="toastTitle" class="font-semibold"></h4>
                    <p id="toastMessage" class="text-sm text-gray-600 mt-1"></p>
                </div>
                <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Global State
        let currentView = 'grid'; // 'grid' or 'list'
        let announcements = [];
        let filteredAnnouncements = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        let currentAction = '';
        let currentId = null;
        let allUsers = [];
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Pengumuman System Initialized');
            
            // Load data
            loadAnnouncements();
            loadUsers();
            
            // Event Listeners
            document.getElementById('createBtn').addEventListener('click', openCreateModal);
            document.getElementById('createFirstBtn').addEventListener('click', openCreateModal);
            document.getElementById('refreshBtn').addEventListener('click', loadAnnouncements);
            document.getElementById('gridViewBtn').addEventListener('click', () => switchView('grid'));
            document.getElementById('listViewBtn').addEventListener('click', () => switchView('list'));
            document.getElementById('searchInput').addEventListener('input', filterAnnouncements);
            document.getElementById('filterStatus').addEventListener('change', filterAnnouncements);
            document.getElementById('filterType').addEventListener('change', filterAnnouncements);
            document.getElementById('clearSearch').addEventListener('click', clearSearch);
            document.getElementById('confirmBtn').addEventListener('click', handleConfirm);
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    openCreateModal();
                }
                if (e.key === 'Escape') closeModal();
                if (e.key === 'F5') {
                    e.preventDefault();
                    loadAnnouncements();
                }
            });
            
            // Auto refresh every 60 seconds
            setInterval(() => {
                updateLastUpdateTime();
            }, 60000);
        });
        
        // Load announcements
        async function loadAnnouncements() {
            try {
                showLoading(true);
                
                const response = await fetch('/pengumuman/data', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                
                const result = await response.json();
                
                if (result.success) {
                    announcements = result.data;
                    filteredAnnouncements = [...announcements];
                    
                    updateStats();
                    renderAnnouncements();
                    
                    if (announcements.length === 0) {
                        showEmptyState();
                    } else {
                        hideEmptyState();
                        showToast('success', 'Data berhasil dimuat', `Ditemukan ${announcements.length} pengumuman`);
                    }
                    
                    updateLastUpdateTime();
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Error loading announcements:', error);
                showToast('error', 'Gagal memuat data', error.message);
            } finally {
                showLoading(false);
            }
        }
        
        // Load users
        async function loadUsers() {
            try {
                const response = await fetch('/users/data', {
                    headers: { 'Accept': 'application/json' }
                });
                
                const result = await response.json();
                
                if (result.success && result.data) {
                    allUsers = result.data;
                    console.log(`Loaded ${allUsers.length} users`);
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }
        
        // Update statistics
        function updateStats() {
            document.getElementById('totalCount').textContent = announcements.length;
            
            const unreadCount = announcements.filter(a => !a.read_at).length;
            document.getElementById('unreadCount').textContent = unreadCount;
            
            const importantCount = announcements.filter(a => a.is_important).length;
            document.getElementById('importantCount').textContent = importantCount;
            
            const thisMonth = new Date().getMonth();
            const monthlyCount = announcements.filter(a => {
                const date = new Date(a.created_at);
                return date.getMonth() === thisMonth;
            }).length;
            document.getElementById('monthlyCount').textContent = monthlyCount;
        }
        
        // Render announcements based on current view
        function renderAnnouncements() {
            if (currentView === 'grid') {
                renderGrid();
            } else {
                renderTable();
            }
        }
        
        // Render grid view
        function renderGrid() {
            const grid = document.getElementById('announcementGrid');
            grid.innerHTML = '';
            
            if (filteredAnnouncements.length === 0) {
                grid.classList.add('hidden');
                showEmptyState();
                return;
            }
            
            filteredAnnouncements.forEach((announcement, index) => {
                const card = createAnnouncementCard(announcement, index);
                grid.appendChild(card);
            });
            
            grid.classList.remove('hidden');
            document.getElementById('tableView').classList.add('hidden');
        }
        
        // Render table view
        function renderTable() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';
            
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageData = filteredAnnouncements.slice(start, end);
            
            pageData.forEach(announcement => {
                const row = createTableRow(announcement);
                tableBody.appendChild(row);
            });
            
            // Update pagination
            updatePagination();
            
            // Update counts
            document.getElementById('showingCount').textContent = pageData.length;
            document.getElementById('totalTableCount').textContent = filteredAnnouncements.length;
            
            document.getElementById('tableView').classList.remove('hidden');
            document.getElementById('announcementGrid').classList.add('hidden');
        }
        
        // Create announcement card
        function createAnnouncementCard(announcement, index) {
            const card = document.createElement('div');
            card.className = `announcement-card card p-5 fade-in`;
            
            if (announcement.is_pinned) card.classList.add('pinned');
            if (announcement.is_important) card.classList.add('important');
            
            // Format date
            const date = new Date(announcement.created_at);
            const formattedDate = date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Creator info
            const creator = announcement.creator || { name: 'Admin', email: '' };
            
            // Recipients
            const recipients = announcement.users || [];
            const recipientNames = recipients.map(u => u.name).join(', ');
            
            card.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="user-avatar bg-gradient-to-r from-blue-500 to-purple-600">
                            ${creator.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">${creator.name}</h4>
                            <p class="text-sm text-gray-500">${creator.role || 'Admin'}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        ${announcement.is_pinned ? '<span class="badge badge-warning"><i class="fas fa-thumbtack mr-1"></i>Disematkan</span>' : ''}
                        ${announcement.is_important ? '<span class="badge badge-danger"><i class="fas fa-exclamation-circle mr-1"></i>Penting</span>' : ''}
                        ${!announcement.read_at ? '<span class="badge badge-primary pulse"><i class="fas fa-envelope mr-1"></i>Baru</span>' : ''}
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 mb-3">${announcement.judul}</h3>
                
                <p class="text-gray-600 mb-4 line-clamp-3">${announcement.isi_pesan}</p>
                
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-users text-gray-400"></i>
                        <span class="text-sm font-medium text-gray-700">Penerima:</span>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        ${recipients.slice(0, 3).map(u => `
                            <span class="tag">
                                <i class="fas fa-user-circle mr-1"></i>
                                ${u.name}
                            </span>
                        `).join('')}
                        ${recipients.length > 3 ? `<span class="tag">+${recipients.length - 3} lainnya</span>` : ''}
                    </div>
                </div>
                
                ${announcement.lampiran ? `
                    <div class="mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-paperclip text-gray-400"></i>
                            <a href="${announcement.lampiran}" target="_blank" class="text-blue-600 hover:underline">
                                Lihat Lampiran
                            </a>
                        </div>
                    </div>
                ` : ''}
                
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i>
                        ${formattedDate}
                    </div>
                    <div class="flex gap-2">
                        <button onclick="viewAnnouncement(${announcement.id})" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editAnnouncement(${announcement.id})" class="text-green-600 hover:text-green-800 p-2 hover:bg-green-50 rounded-lg">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteAnnouncement(${announcement.id})" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            return card;
        }
        
        // Create table row
        function createTableRow(announcement) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 border-b';
            
            const date = new Date(announcement.created_at);
            const formattedDate = date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            
            const creator = announcement.creator || { name: 'Admin' };
            const recipients = announcement.users || [];
            
            row.innerHTML = `
                <td class="p-4">
                    <div class="font-medium text-gray-800">${announcement.judul}</div>
                    <div class="text-sm text-gray-500 line-clamp-1">${announcement.isi_pesan.substring(0, 60)}...</div>
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-sm">${creator.name.charAt(0)}</span>
                        </div>
                        <span>${creator.name}</span>
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm">
                        ${recipients.length} penerima
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-600">${formattedDate}</div>
                </td>
                <td class="p-4">
                    ${announcement.is_important ? '<span class="badge badge-danger">Penting</span>' : ''}
                    ${!announcement.read_at ? '<span class="badge badge-primary">Baru</span>' : ''}
                </td>
                <td class="p-4">
                    <div class="flex gap-2">
                        <button onclick="viewAnnouncement(${announcement.id})" class="text-blue-600 hover:text-blue-800 p-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editAnnouncement(${announcement.id})" class="text-green-600 hover:text-green-800 p-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteAnnouncement(${announcement.id})" class="text-red-600 hover:text-red-800 p-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            return row;
        }
        
        // Update pagination
        function updatePagination() {
            const pagination = document.getElementById('pagination');
            const totalPages = Math.ceil(filteredAnnouncements.length / itemsPerPage);
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }
            
            let html = '';
            
            // Previous button
            html += `
                <button onclick="changePage(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''}
                        class="px-3 py-2 border rounded-lg ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'}">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `
                        <button onclick="changePage(${i})"
                                class="px-3 py-2 border rounded-lg ${i === currentPage ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-gray-50'}">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += `<span class="px-3 py-2">...</span>`;
                }
            }
            
            // Next button
            html += `
                <button onclick="changePage(${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''}
                        class="px-3 py-2 border rounded-lg ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
            
            pagination.innerHTML = html;
        }
        
        // Change page
        function changePage(page) {
            const totalPages = Math.ceil(filteredAnnouncements.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            
            currentPage = page;
            renderTable();
        }
        
        // Switch view
        function switchView(view) {
            currentView = view;
            
            if (view === 'grid') {
                document.getElementById('gridViewBtn').classList.remove('text-gray-500');
                document.getElementById('gridViewBtn').classList.add('text-blue-600');
                document.getElementById('listViewBtn').classList.remove('text-blue-600');
                document.getElementById('listViewBtn').classList.add('text-gray-500');
                renderGrid();
            } else {
                document.getElementById('listViewBtn').classList.remove('text-gray-500');
                document.getElementById('listViewBtn').classList.add('text-blue-600');
                document.getElementById('gridViewBtn').classList.remove('text-blue-600');
                document.getElementById('gridViewBtn').classList.add('text-gray-500');
                renderTable();
            }
        }
        
        // Filter announcements
        function filterAnnouncements() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const typeFilter = document.getElementById('filterType').value;
            
            filteredAnnouncements = announcements.filter(announcement => {
                // Search filter
                const matchesSearch = searchTerm === '' || 
                    announcement.judul.toLowerCase().includes(searchTerm) ||
                    announcement.isi_pesan.toLowerCase().includes(searchTerm) ||
                    (announcement.users && announcement.users.some(u => 
                        u.name.toLowerCase().includes(searchTerm)
                    ));
                
                // Status filter
                let matchesStatus = true;
                if (statusFilter === 'read') {
                    matchesStatus = announcement.read_at !== null;
                } else if (statusFilter === 'unread') {
                    matchesStatus = announcement.read_at === null;
                }
                
                // Type filter
                let matchesType = true;
                if (typeFilter === 'important') {
                    matchesType = announcement.is_important === true;
                } else if (typeFilter === 'pinned') {
                    matchesType = announcement.is_pinned === true;
                } else if (typeFilter === 'general') {
                    matchesType = !announcement.is_important && !announcement.is_pinned;
                }
                
                return matchesSearch && matchesStatus && matchesType;
            });
            
            // Show/hide clear search button
            if (searchTerm.length > 0) {
                document.getElementById('clearSearch').classList.remove('hidden');
            } else {
                document.getElementById('clearSearch').classList.add('hidden');
            }
            
            // Reset to first page
            currentPage = 1;
            
            // Re-render
            renderAnnouncements();
        }
        
        // Clear search
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('clearSearch').classList.add('hidden');
            filterAnnouncements();
        }
        
        // Modal functions
        function openCreateModal() {
            currentAction = 'create';
            currentId = null;
            
            showModal(
                'Buat Pengumuman Baru',
                'Bagikan informasi penting kepada tim Anda',
                getFormTemplate(),
                'Simpan Pengumuman'
            );
        }
        
        function showModal(title, subtitle, content, confirmText) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalSubtitle').textContent = subtitle;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('confirmBtnText').textContent = confirmText;
            document.getElementById('modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Form template
        function getFormTemplate(data = {}) {
            return `
                <form id="announcementForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">
                                <i class="fas fa-heading mr-2 text-blue-500"></i>
                                Judul Pengumuman *
                            </label>
                            <input type="text" id="titleInput" name="judul"
                                value="${data.judul || ''}"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Contoh: Meeting Rutin Bulanan"
                                required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>
                                Jenis Pengumuman
                            </label>
                            <div class="flex gap-3">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="importantCheck" name="is_important" 
                                           ${data.is_important ? 'checked' : ''}
                                           class="rounded text-red-500 focus:ring-red-500">
                                    <span class="text-sm">Penting</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="pinnedCheck" name="is_pinned"
                                           ${data.is_pinned ? 'checked' : ''}
                                           class="rounded text-yellow-500 focus:ring-yellow-500">
                                    <span class="text-sm">Disematkan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">
                            <i class="fas fa-align-left mr-2 text-blue-500"></i>
                            Isi Pesan *
                        </label>
                        <textarea id="contentInput" name="isi_pesan" rows="5"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none resize-none"
                            placeholder="Tulis isi pengumuman secara detail..."
                            required>${data.isi_pesan || ''}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">
                            <i class="fas fa-users mr-2 text-blue-500"></i>
                            Penerima *
                        </label>
                        <select id="recipientsSelect" name="users[]" multiple
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none min-h-[150px]">
                            <option value="" disabled>Pilih penerima pengumuman...</option>
                            ${allUsers.map(user => `
                                <option value="${user.id}" 
                                        ${data.users && data.users.some(u => u.id == user.id) ? 'selected' : ''}>
                                    ${user.name} (${user.role || 'No role'})
                                </option>
                            `).join('')}
                        </select>
                        <div class="mt-2">
                            <div id="selectedRecipients" class="flex flex-wrap gap-2"></div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tahan Ctrl (Windows) atau Command (Mac) untuk memilih banyak penerima
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">
                            <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                            Lampiran
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 mb-2">Drag & drop file atau klik untuk memilih</p>
                            <input type="file" id="attachmentInput" name="lampiran"
                                class="hidden"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt">
                            <button type="button" onclick="document.getElementById('attachmentInput').click()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                Pilih File
                            </button>
                            <p class="text-xs text-gray-500 mt-3">
                                Maksimal 10MB • PDF, DOC, JPG, PNG, TXT
                            </p>
                            <div id="filePreview" class="mt-3"></div>
                        </div>
                    </div>
                </form>
            `;
        }
        
        // Handle form submission
        async function handleConfirm() {
            try {
                // Get form data
                const formData = new FormData();
                
                // Basic fields
                formData.append('judul', document.getElementById('titleInput').value);
                formData.append('isi_pesan', document.getElementById('contentInput').value);
                
                // Checkboxes
                const isImportant = document.getElementById('importantCheck')?.checked || false;
                const isPinned = document.getElementById('pinnedCheck')?.checked || false;
                formData.append('is_important', isImportant);
                formData.append('is_pinned', isPinned);
                
                // Recipients
                const select = document.getElementById('recipientsSelect');
                const selectedRecipients = Array.from(select.selectedOptions).map(opt => opt.value);
                selectedRecipients.forEach(userId => {
                    formData.append('users[]', userId);
                });
                
                // Attachment
                const fileInput = document.getElementById('attachmentInput');
                if (fileInput && fileInput.files[0]) {
                    formData.append('lampiran', fileInput.files[0]);
                }
                
                // Determine URL and method
                let url = '/pengumuman';
                let method = 'POST';
                
                if (currentAction === 'edit') {
                    url = `/pengumuman/${currentId}`;
                    method = 'PUT';
                    formData.append('_method', 'PUT');
                }
                
                // Show loading
                showSubmitLoading(true);
                
                // Send request
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                // Hide loading
                showSubmitLoading(false);
                
                if (result.success) {
                    showToast('success', 'Berhasil', result.message || 'Pengumuman berhasil disimpan');
                    setTimeout(() => {
                        closeModal();
                        loadAnnouncements();
                    }, 1500);
                } else {
                    let errorMsg = result.message || 'Terjadi kesalahan';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    showToast('error', 'Gagal', errorMsg);
                }
                
            } catch (error) {
                console.error('Error saving announcement:', error);
                showSubmitLoading(false);
                showToast('error', 'Gagal', 'Terjadi kesalahan: ' + error.message);
            }
        }
        
        // View announcement
        function viewAnnouncement(id) {
            const announcement = announcements.find(a => a.id == id);
            if (!announcement) return;
            
            const creator = announcement.creator || { name: 'Admin' };
            const recipients = announcement.users || [];
            
            const content = `
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">${announcement.judul}</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">${announcement.isi_pesan}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card p-5">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-500"></i>
                                Informasi Pembuat
                            </h4>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar bg-gradient-to-r from-blue-500 to-purple-600">
                                    ${creator.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-medium">${creator.name}</p>
                                    <p class="text-sm text-gray-500">${creator.email || 'No email'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card p-5">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                Informasi Waktu
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dibuat:</span>
                                    <span class="font-medium">${new Date(announcement.created_at).toLocaleString('id-ID')}</span>
                                </div>
                                ${announcement.updated_at ? `
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Diupdate:</span>
                                        <span class="font-medium">${new Date(announcement.updated_at).toLocaleString('id-ID')}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-5">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-users text-blue-500"></i>
                            Ditujukan Kepada
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            ${recipients.map(user => `
                                <span class="tag">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    ${user.name}
                                </span>
                            `).join('')}
                        </div>
                    </div>
                    
                    ${announcement.lampiran ? `
                        <div class="card p-5">
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-paperclip text-blue-500"></i>
                                Lampiran
                            </h4>
                            <a href="${announcement.lampiran}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                                <i class="fas fa-download"></i>
                                Download Lampiran
                            </a>
                        </div>
                    ` : ''}
                </div>
            `;
            
            showModal(
                'Detail Pengumuman',
                'Lihat informasi lengkap pengumuman',
                content,
                'Tutup'
            );
            
            // Change confirm button to close
            document.getElementById('confirmBtn').textContent = 'Tutup';
            document.getElementById('confirmBtn').onclick = closeModal;
        }
        
        // Edit announcement
        async function editAnnouncement(id) {
            try {
                const announcement = announcements.find(a => a.id == id);
                if (!announcement) {
                    const response = await fetch(`/pengumuman/${id}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        currentAction = 'edit';
                        currentId = id;
                        
                        showModal(
                            'Edit Pengumuman',
                            'Perbarui informasi pengumuman',
                            getFormTemplate(result.data),
                            'Update Pengumuman'
                        );
                    }
                } else {
                    currentAction = 'edit';
                    currentId = id;
                    
                    showModal(
                        'Edit Pengumuman',
                        'Perbarui informasi pengumuman',
                        getFormTemplate(announcement),
                        'Update Pengumuman'
                    );
                }
            } catch (error) {
                console.error('Error editing announcement:', error);
                showToast('error', 'Gagal', 'Tidak dapat memuat data pengumuman');
            }
        }
        
        // Delete announcement
        async function deleteAnnouncement(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/pengumuman/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('success', 'Berhasil', 'Pengumuman berhasil dihapus');
                    loadAnnouncements();
                } else {
                    showToast('error', 'Gagal', result.message || 'Gagal menghapus pengumuman');
                }
            } catch (error) {
                console.error('Error deleting announcement:', error);
                showToast('error', 'Gagal', 'Terjadi kesalahan saat menghapus');
            }
        }
        
        // Loading states
        function showLoading(show) {
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');
            const announcementGrid = document.getElementById('announcementGrid');
            const tableView = document.getElementById('tableView');
            
            if (show) {
                loadingState.classList.remove('hidden');
                emptyState.classList.add('hidden');
                announcementGrid.classList.add('hidden');
                tableView.classList.add('hidden');
            } else {
                loadingState.classList.add('hidden');
            }
        }
        
        function showEmptyState() {
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('announcementGrid').classList.add('hidden');
            document.getElementById('tableView').classList.add('hidden');
        }
        
        function hideEmptyState() {
            document.getElementById('emptyState').classList.add('hidden');
        }
        
        function showSubmitLoading(show) {
            const confirmBtnText = document.getElementById('confirmBtnText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const confirmBtn = document.getElementById('confirmBtn');
            
            if (show) {
                confirmBtn.disabled = true;
                confirmBtnText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
            } else {
                confirmBtn.disabled = false;
                confirmBtnText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
            }
        }
        
        // Toast notifications
        function showToast(type, title, message) {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            // Set styles and icon
            toast.className = `notification-toast`;
            
            if (type === 'success') {
                toastIcon.className = 'p-2 rounded-full bg-green-500';
                toastIcon.innerHTML = '<i class="fas fa-check text-white"></i>';
                toast.classList.add('bg-green-50', 'border-l-4', 'border-green-500');
            } else if (type === 'error') {
                toastIcon.className = 'p-2 rounded-full bg-red-500';
                toastIcon.innerHTML = '<i class="fas fa-exclamation-triangle text-white"></i>';
                toast.classList.add('bg-red-50', 'border-l-4', 'border-red-500');
            } else if (type === 'info') {
                toastIcon.className = 'p-2 rounded-full bg-blue-500';
                toastIcon.innerHTML = '<i class="fas fa-info-circle text-white"></i>';
                toast.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
            }
            
            // Set content
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            
            // Show toast
            toast.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }
        
        function hideToast() {
            document.getElementById('toast').classList.add('hidden');
        }
        
        // Update last update time
        function updateLastUpdateTime() {
            const now = new Date();
            const formattedTime = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('lastUpdate').textContent = `Terakhir diperbarui: ${formattedTime}`;
        }
    </script>
</body>
</html>
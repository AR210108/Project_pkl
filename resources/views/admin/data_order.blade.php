<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Orderan - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                        "sidebar-light": "#f3f4f6",
                        "sidebar-dark": "#1e293b",
                        "card-light": "#ffffff",
                        "card-dark": "#1e293b",
                        "text-light": "#1e293b",
                        "text-dark": "#f8fafc",
                        "text-muted-light": "#64748b",
                        "text-muted-dark": "#94a3b8",
                        "border-light": "#e2e8f0",
                        "border-dark": "#334155",
                        "success": "#10b981",
                        "warning": "#f59e0b",
                        "danger": "#ef4444"
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                    boxShadow: {
                        card: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                        "card-hover": "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .material-icons-outlined {
            font-size: 24px;
            vertical-align: middle;
        }
        
        /* Card hover effects */
        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Table styles */
        .order-table {
            transition: all 0.2s ease;
        }
        
        .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        /* Button styles */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        
        /* Modal styles */
        .modal {
            transition: opacity 0.25s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Status Badge Styles */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-todo {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-progress {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-done {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .status-active {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .status-inprogress {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
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
        
        /* Style untuk efek hover yang lebih menonjol */
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
        /* Gaya untuk indikator aktif/hover */
        /* Default untuk mobile: di sebelah kanan */
        .nav-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background-color: #3b82f6;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        /* Override untuk desktop: di sebelah kiri */
        @media (min-width: 768px) {
            .nav-item::before {
                right: auto;
                left: 0;
                transform: translateX(-100%);
            }
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: translateX(0);
        }
        
        /* Memastikan sidebar tetap di posisinya saat scroll */
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }
        
        /* Menyesuaikan konten utama agar tidak tertutup sidebar */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px; /* Lebar sidebar */
            }
        }
        
        /* Scrollbar kustom untuk sidebar */
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
        
        /* Table mobile adjustments */
        @media (max-width: 639px) {
            .desktop-table {
                display: none;
            }
            
            .mobile-cards {
                display: block;
            }
            
            /* Hide desktop pagination on mobile */
            .desktop-pagination {
                display: none !important;
            }
        }
        
        @media (min-width: 640px) {
            .desktop-table {
                display: block;
            }
            
            .mobile-cards {
                display: none;
            }
            
            /* Hide mobile pagination on desktop */
            .mobile-pagination {
                display: none !important;
            }
        }
        
        /* Form input styles */
        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Pagination styles */
        .page-btn {
            transition: all 0.2s ease;
        }
        
        .page-btn:hover:not(:disabled) {
            transform: scale(1.1);
        }
        
        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Desktop pagination styles */
        .desktop-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }
        
        .desktop-page-btn {
            min-width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .desktop-page-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .desktop-page-btn:not(.active) {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        .desktop-page-btn:not(.active):hover {
            background-color: #e2e8f0;
        }
        
        .desktop-nav-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .desktop-nav-btn:hover:not(:disabled) {
            background-color: #e2e8f0;
        }
        
        .desktop-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Panel Styles */
        .panel {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .panel-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .panel-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .panel-body {
            padding: 1.5rem;
        }
        
        /* SCROLLABLE TABLE - TANPA TEKS SCROLL */
        .scrollable-table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
        }
        
        /* Force scrollbar to be visible */
        .scrollable-table-container {
            scrollbar-width: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .scrollable-table-container::-webkit-scrollbar {
            height: 12px;
            width: 12px;
        }
        
        .scrollable-table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 6px;
        }
        
        .scrollable-table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
            border: 2px solid #f1f5f9;
        }
        
        .scrollable-table-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Table with fixed width to ensure scrolling */
        .data-table {
            width: 100%;
            min-width: 1400px; /* Fixed minimum width */
            border-collapse: collapse;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        
        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .data-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .data-table tbody tr:hover {
            background: #f3f4f6;
        }
        
        /* Shadow effect */
        .table-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Progress bar styles */
        .progress-bar {
            width: 100%;
            background-color: #e2e8f0;
            border-radius: 9999px;
            height: 8px;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 9999px;
        }
        
        /* Truncate text style */
        .truncate-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
       @include('admin/templet/sider')
        
        <!-- Main Content Container -->
        <div class="main-content flex-1 flex flex-col overflow-y-auto bg-background-light">
            <main class="flex-1 flex flex-col bg-background-light">
                <div class="flex-1 p-3 sm:p-8">

                    <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Data Orderan</h2>
                    
                    <!-- Search and Filter Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div class="relative w-full md:w-1/3">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Search..." type="text" />
                        </div>
                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <button class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                                Filter
                            </button>
                            <button id="tambahOrderanBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                                <span class="material-icons-outlined">add</span>
                                <span class="hidden sm:inline">Tambah Orderan</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Data Table Panel -->
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="panel-title">
                                <span class="material-icons-outlined text-primary">view_list</span>
                                Daftar Orderan
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="totalCount">2</span> orderan</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <!-- SCROLLABLE TABLE - TANPA INDICATOR -->
                            <div class="desktop-table">
                                <div class="scrollable-table-container table-shadow" id="scrollableTable">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 60px;">No</th>
                                                <th style="min-width: 200px;">Nama Orderan</th>
                                                <th style="min-width: 300px;">Deskripsi</th>
                                                <th style="min-width: 120px;">Harga</th>
                                                <th style="min-width: 120px;">Deadline</th>
                                                <th style="min-width: 150px;">Progres</th>
                                                <th style="min-width: 120px;">Status</th>
                                                <th style="min-width: 180px; text-align: center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="desktopTableBody">
                                            <!-- Data rows will be populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Mobile Card View -->
                            <div class="mobile-cards space-y-4" id="mobile-cards">
                                <!-- Mobile cards will be populated by JavaScript -->
                            </div>
                            
                            <!-- Desktop Pagination - SELALU DITAMPILKAN -->
                            <div id="desktopPaginationContainer" class="desktop-pagination">
                                <button id="desktopPrevPage" class="desktop-nav-btn">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <div id="desktopPageNumbers" class="flex gap-1">
                                    <!-- Page numbers will be generated by JavaScript -->
                                </div>
                                <button id="desktopNextPage" class="desktop-nav-btn">
                                    <span class="material-icons-outlined text-sm">chevron_right</span>
                                </button>
                            </div>
                            
                            <!-- Mobile Pagination -->
                            <div class="mobile-pagination md:hidden flex justify-center items-center gap-2 mt-4">
                                <button id="prevPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <div id="pageNumbers" class="flex gap-1">
                                    <!-- Page numbers will be generated by JavaScript -->
                                </div>
                                <button id="nextPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-icons-outlined text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                    Copyright ©2025 by digicity.id
                </footer>
            </main>
        </div>
    </div>

    <!-- Modal Tambah Orderan -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Orderan Baru</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orderan</label>
                        <input type="text" id="tambahNama" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="tambahDeskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <input type="text" id="tambahHarga" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="tambahDeadline" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Progres (%)</label>
                        <input type="number" id="tambahProgres" min="0" max="100" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="tambahStatus" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="In Progress">In Progress</option>
                            <option value="Active">Active</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Orderan -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Orderan</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="editId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orderan</label>
                        <input type="text" id="editNama" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <input type="text" id="editHarga" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="editDeadline" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Progres (%)</label>
                        <input type="number" id="editProgres" min="0" max="100" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="In Progress">In Progress</option>
                            <option value="Active">Active</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Orderan -->
    <div id="detailModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Orderan</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">ID Orderan</h4>
                            <p class="text-base font-medium" id="detailId"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Nama Orderan</h4>
                            <p class="text-base font-medium" id="detailNama"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Harga</h4>
                            <p class="text-base font-medium" id="detailHarga"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Deadline</h4>
                            <p class="text-base font-medium" id="detailDeadline"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                            <p id="detailStatus"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Progres</h4>
                            <div class="flex items-center gap-2">
                                <div class="progress-bar flex-1">
                                    <div class="progress-fill" id="detailProgressBar"></div>
                                </div>
                                <span class="text-sm font-medium" id="detailProgres"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h4>
                        <p class="text-base" id="detailDeskripsi"></p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Orderan -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus orderan <span id="deleteNama" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons-outlined">close</span>
        </button>
    </div>

    <script>
        // Sample data for orderan
        const orderanData = [
            { 
                id: 1, 
                nama: "Website Company Profile", 
                deskripsi: "Redesign corporate branding dengan tampilan modern dan responsif. Meliputi pembuatan ulang UI/UX, optimasi performa, dan integrasi dengan sistem manajemen konten yang sudah ada. Proyek ini bertujuan untuk meningkatkan citra perusahaan di dunia digital dan meningkatkan konversi pengunjung menjadi pelanggan potensial.", 
                harga: "Rp 5.000.000",
                deadline: "2025-12-25", 
                progres: 70,
                status: "In Progress" 
            },
            { 
                id: 2, 
                nama: "Aplikasi Kasir Mobile", 
                deskripsi: "Pengembangan sistem POS android dengan fitur lengkap untuk kebutuhan retail. Aplikasi ini akan memiliki kemampuan untuk mengelola inventaris, melacak penjualan, menghasilkan laporan, dan sinkronisasi data dengan sistem backend. Dengan antarmuka yang intuitif, aplikasi ini dirancang untuk memudahkan proses transaksi dan manajemen toko.", 
                harga: "Rp 12.500.000",
                deadline: "2026-01-10", 
                progres: 45,
                status: "Active" 
            }
        ];

        // Pagination variables
        const itemsPerPage = 3;
        let currentPage = 1;
        const totalPages = Math.ceil(orderanData.length / itemsPerPage);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize pagination - SELALU DIJALANKAN
            initializePagination();
            
            // Modal elements
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            const detailModal = document.getElementById('detailModal');
            const deleteModal = document.getElementById('deleteModal');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Buttons
            const tambahOrderanBtn = document.getElementById('tambahOrderanBtn');
            const closeModals = document.querySelectorAll('.close-modal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const closeToastBtn = document.getElementById('closeToast');
            
            // Forms
            const tambahForm = document.getElementById('tambahForm');
            const editForm = document.getElementById('editForm');
            
            // Show tambah modal
            tambahOrderanBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
                tambahForm.reset();
            });
            
            // Close modals
            closeModals.forEach(btn => {
                btn.addEventListener('click', function() {
                    tambahModal.classList.add('hidden');
                    editModal.classList.add('hidden');
                    detailModal.classList.add('hidden');
                    deleteModal.classList.add('hidden');
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === tambahModal) {
                    tambahModal.classList.add('hidden');
                }
                if (event.target === editModal) {
                    editModal.classList.add('hidden');
                }
                if (event.target === detailModal) {
                    detailModal.classList.add('hidden');
                }
                if (event.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
            });
            
            // Handle tambah form submission
            tambahForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showToast('Orderan berhasil ditambahkan!');
                tambahModal.classList.add('hidden');
                tambahForm.reset();
                
                // Refresh data
                renderDesktopTable(currentPage);
                renderMobileCards(currentPage);
            });
            
            // Handle edit form submission
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showToast('Orderan berhasil diperbarui!');
                editModal.classList.add('hidden');
                
                // Refresh data
                renderDesktopTable(currentPage);
                renderMobileCards(currentPage);
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteId').value;
                showToast('Orderan berhasil dihapus!');
                deleteModal.classList.add('hidden');
                
                // Refresh data
                renderDesktopTable(currentPage);
                renderMobileCards(currentPage);
            });
            
            // Close toast notification
            closeToastBtn.addEventListener('click', function() {
                toast.classList.add('translate-y-20', 'opacity-0');
            });
            
            // Function to show toast notification
            function showToast(message) {
                toastMessage.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                
                // Auto hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }
        });

        // Initialize pagination - SELALU DIJALANKAN
        function initializePagination() {
            // Update total count
            document.getElementById('totalCount').textContent = orderanData.length;
            
            // SELALU tampilkan pagination
            initDesktopPagination();
            initMobilePagination();
            
            // Render first page
            renderDesktopTable(1);
            renderMobileCards(1);
        }

        // Desktop pagination functionality
        function initDesktopPagination() {
            const pageNumbersContainer = document.getElementById('desktopPageNumbers');
            const prevButton = document.getElementById('desktopPrevPage');
            const nextButton = document.getElementById('desktopNextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${
                    i === currentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToDesktopPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) goToDesktopPage(currentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) goToDesktopPage(currentPage + 1);
            });
        }

        // Mobile pagination functionality
        function initMobilePagination() {
            const pageNumbersContainer = document.getElementById('pageNumbers');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm ${
                    i === currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'
                }`;
                pageNumber.addEventListener('click', () => goToMobilePage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) goToMobilePage(currentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) goToMobilePage(currentPage + 1);
            });
        }

        // Go to specific desktop page
        function goToDesktopPage(page) {
            currentPage = page;
            renderDesktopTable(page);
            updateDesktopPaginationButtons();
            
            // Reset scroll position when changing pages
            const scrollableTable = document.getElementById('scrollableTable');
            if (scrollableTable) {
                scrollableTable.scrollLeft = 0;
            }
        }

        // Go to specific mobile page
        function goToMobilePage(page) {
            currentPage = page;
            renderMobileCards(page);
            updateMobilePaginationButtons();
        }

        // Render desktop table for specific page
        function renderDesktopTable(page) {
            const tbody = document.getElementById('desktopTableBody');
            tbody.innerHTML = '';
            
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, orderanData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const orderan = orderanData[i];
                const row = document.createElement('tr');
                
                // Determine status class
                let statusClass = '';
                if (orderan.status === 'In Progress') {
                    statusClass = 'status-inprogress';
                } else if (orderan.status === 'Active') {
                    statusClass = 'status-active';
                } else if (orderan.status === 'Completed') {
                    statusClass = 'status-done';
                } else if (orderan.status === 'Cancelled') {
                    statusClass = 'status-todo';
                }
                
                // Determine progress bar color
                let progressColor = '';
                if (orderan.progres < 50) {
                    progressColor = 'bg-red-500';
                } else if (orderan.progres < 80) {
                    progressColor = 'bg-yellow-500';
                } else {
                    progressColor = 'bg-green-500';
                }
                
                // Truncate description for table view
                const truncatedDesc = orderan.deskripsi.length > 50 
                    ? orderan.deskripsi.substring(0, 50) + '...' 
                    : orderan.deskripsi;
                
                row.innerHTML = `
                    <td style="min-width: 60px;">${i + 1}</td>
                    <td style="min-width: 200px;">${orderan.nama}</td>
                    <td style="min-width: 300px;" class="truncate-text" title="${orderan.deskripsi}">${truncatedDesc}</td>
                    <td style="min-width: 120px;">${orderan.harga}</td>
                    <td style="min-width: 120px;">${orderan.deadline}</td>
                    <td style="min-width: 150px;">
                        <div class="progress-bar">
                            <div class="progress-fill ${progressColor}" style="width: ${orderan.progres}%"></div>
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400 mt-1 block">${orderan.progres}%</span>
                    </td>
                    <td style="min-width: 120px;"><span class="status-badge ${statusClass}">${orderan.status}</span></td>
                    <td style="min-width: 180px; text-align: center;">
                        <div class="flex justify-center gap-2">
                            <button class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                onclick="openDetailModal(${orderan.id}, '${orderan.nama}', '${orderan.deskripsi}', '${orderan.harga}', '${orderan.deadline}', ${orderan.progres}, '${orderan.status}')"
                                title="Lihat Detail">
                                <span class="material-icons-outlined">visibility</span>
                            </button>
                            <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                onclick="openEditModal(${orderan.id}, '${orderan.nama}', '${orderan.deskripsi}', '${orderan.harga}', '${orderan.deadline}', ${orderan.progres}, '${orderan.status}')"
                                title="Edit">
                                <span class="material-icons-outlined">edit</span>
                            </button>
                            <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                onclick="openDeleteModal(${orderan.id}, '${orderan.nama}')"
                                title="Hapus">
                                <span class="material-icons-outlined">delete</span>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            }
        }

        // Render mobile cards for specific page
        function renderMobileCards(page) {
            const container = document.getElementById('mobile-cards');
            container.innerHTML = '';
            
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, orderanData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const orderan = orderanData[i];
                
                // Determine status class
                let statusClass = '';
                if (orderan.status === 'In Progress') {
                    statusClass = 'status-inprogress';
                } else if (orderan.status === 'Active') {
                    statusClass = 'status-active';
                } else if (orderan.status === 'Completed') {
                    statusClass = 'status-done';
                } else if (orderan.status === 'Cancelled') {
                    statusClass = 'status-todo';
                }
                
                // Determine progress bar color
                let progressColor = '';
                if (orderan.progres < 50) {
                    progressColor = 'bg-red-500';
                } else if (orderan.progres < 80) {
                    progressColor = 'bg-yellow-500';
                } else {
                    progressColor = 'bg-green-500';
                }
                
                // Truncate description for mobile card view
                const truncatedDesc = orderan.deskripsi.length > 80 
                    ? orderan.deskripsi.substring(0, 80) + '...' 
                    : orderan.deskripsi;
                
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-base">${orderan.nama}</h4>
                            <p class="text-sm text-text-muted-light">Deadline: ${orderan.deadline}</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                onclick="openDetailModal(${orderan.id}, '${orderan.nama}', '${orderan.deskripsi}', '${orderan.harga}', '${orderan.deadline}', ${orderan.progres}, '${orderan.status}')"
                                title="Lihat Detail">
                            </button>
                            <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                onclick="openEditModal(${orderan.id}, '${orderan.nama}', '${orderan.deskripsi}', '${orderan.harga}', '${orderan.deadline}', ${orderan.progres}, '${orderan.status}')"
                                title="Edit">
                                <span class="material-icons-outlined">edit</span>
                            </button>
                            <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                onclick="openDeleteModal(${orderan.id}, '${orderan.nama}')"
                                title="Hapus">
                                <span class="material-icons-outlined">delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-text-muted-light">Harga</p>
                            <p class="font-medium">${orderan.harga}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Status</p>
                            <p><span class="status-badge ${statusClass}">${orderan.status}</span></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-text-muted-light">Progres</p>
                            <div class="progress-bar mt-1">
                                <div class="progress-fill ${progressColor}" style="width: ${orderan.progres}%"></div>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">${orderan.progres}%</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-text-muted-light">Deskripsi</p>
                        <p class="font-medium">${truncatedDesc}</p>
                        ${orderan.deskripsi.length > 80 ? `
                        <button class="text-primary text-sm mt-1" 
                            onclick="openDetailModal(${orderan.id}, '${orderan.nama}', '${orderan.deskripsi}', '${orderan.harga}', '${orderan.deadline}', ${orderan.progres}, '${orderan.status}')">
                            Lihat selengkapnya
                        </button>` : ''}
                    </div>
                `;
                container.appendChild(card);
            }
        }

        // Update desktop pagination buttons
        function updateDesktopPaginationButtons() {
            const prevButton = document.getElementById('desktopPrevPage');
            const nextButton = document.getElementById('desktopNextPage');
            const pageButtons = document.querySelectorAll('#desktopPageNumbers button');
            
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === currentPage) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Update mobile pagination buttons
        function updateMobilePaginationButtons() {
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            const pageButtons = document.querySelectorAll('#pageNumbers button');
            
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === currentPage) {
                    btn.className = 'page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm bg-primary text-white';
                } else {
                    btn.className = 'page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm bg-gray-200 text-gray-600';
                }
            });
        }

        // Open detail modal with data
        function openDetailModal(id, nama, deskripsi, harga, deadline, progres, status) {
            document.getElementById('detailId').textContent = '#' + id;
            document.getElementById('detailNama').textContent = nama;
            document.getElementById('detailDeskripsi').textContent = deskripsi;
            document.getElementById('detailHarga').textContent = harga;
            document.getElementById('detailDeadline').textContent = deadline;
            document.getElementById('detailProgres').textContent = progres + '%';
            
            // Set status badge
            const statusElement = document.getElementById('detailStatus');
            let statusClass = '';
            if (status === 'In Progress') {
                statusClass = 'status-inprogress';
            } else if (status === 'Active') {
                statusClass = 'status-active';
            } else if (status === 'Completed') {
                statusClass = 'status-done';
            } else if (status === 'Cancelled') {
                statusClass = 'status-todo';
            }
            statusElement.innerHTML = `<span class="status-badge ${statusClass}">${status}</span>`;
            
            // Set progress bar
            const progressBar = document.getElementById('detailProgressBar');
            let progressColor = '';
            if (progres < 50) {
                progressColor = 'bg-red-500';
            } else if (progres < 80) {
                progressColor = 'bg-yellow-500';
            } else {
                progressColor = 'bg-green-500';
            }
            progressBar.className = `progress-fill ${progressColor}`;
            progressBar.style.width = progres + '%';
            
            document.getElementById('detailModal').classList.remove('hidden');
        }

        // Open edit modal with data
        function openEditModal(id, nama, deskripsi, harga, deadline, progres, status) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editDeskripsi').value = deskripsi;
            document.getElementById('editHarga').value = harga;
            document.getElementById('editDeadline').value = deadline;
            document.getElementById('editProgres').value = progres;
            document.getElementById('editStatus').value = status;
            
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Open delete modal with data
        function openDeleteModal(id, nama) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteNama').textContent = nama;
            
            document.getElementById('deleteModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Catatan Rapat Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
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
        
        .status-paid {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .status-unpaid {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }
        
        .status-pending {
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
        
        /* SCROLLABLE TABLE */
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
        
        /* Minimalist Popup Styles */
        .minimal-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: 350px;
            border-left: 4px solid #10b981;
        }
        
        .minimal-popup.show {
            transform: translateX(0);
        }
        
        .minimal-popup.error {
            border-left-color: #ef4444;
        }
        
        .minimal-popup.warning {
            border-left-color: #f59e0b;
        }
        
        .minimal-popup-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .minimal-popup.success .minimal-popup-icon {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .minimal-popup.error .minimal-popup-icon {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .minimal-popup.warning .minimal-popup-icon {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .minimal-popup-content {
            flex-grow: 1;
        }
        
        .minimal-popup-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }
        
        .minimal-popup-message {
            font-size: 14px;
            color: #64748b;
        }
        
        .minimal-popup-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .minimal-popup-close:hover {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        /* Filter Dropdown Styles */
        .filter-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 16px;
            min-width: 200px;
            z-index: 100;
            display: none;
        }
        
        .filter-dropdown.show {
            display: block;
        }
        
        .filter-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .filter-option:hover {
            color: #3b82f6;
        }
        
        .filter-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .filter-option label {
            cursor: pointer;
            user-select: none;
        }
        
        .filter-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }
        
        .filter-actions button {
            flex: 1;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        
        .filter-apply {
            background-color: #3b82f6;
            color: white;
        }
        
        .filter-apply:hover {
            background-color: #2563eb;
        }
        
        .filter-reset {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        .filter-reset:hover {
            background-color: #e2e8f0;
        }
        
        /* Hidden class for filtering */
        .hidden-by-filter {
            display: none !important;
        }
        
        /* Loading spinner */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        @include('admin/templet/sider')
        
        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Catatan Rapat</h2>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari topik, peserta, atau hasil diskusi..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <div class="relative">
                            <button id="filterBtn" class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <span class="material-icons-outlined text-sm">filter_list</span>
                                Filter
                            </button>
                            <div id="filterDropdown" class="filter-dropdown">
                                <div class="filter-option">
                                    <input type="checkbox" id="filterAll" value="all" checked>
                                    <label for="filterAll">Semua Kategori</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterManagement" value="management">
                                    <label for="filterManagement">Management</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterTeknis" value="teknis">
                                    <label for="filterTeknis">Teknis</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterInternal" value="internal">
                                    <label for="filterInternal">Internal</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                        <button id="createBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Buat Catatan Rapat</span>
                            <span class="sm:hidden">Buat</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">description</span>
                            Daftar Catatan Rapat
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount" class="font-semibold text-text-light">0</span> catatan rapat</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container scroll-indicator table-shadow" id="scrollableTable">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 80px;">ID</th>
                                            <th style="min-width: 100px;">ID Users</th>
                                            <th style="min-width: 120px;">Tanggal</th>
                                            <th style="min-width: 150px;">Peserta</th>
                                            <th style="min-width: 200px;">Topik</th>
                                            <th style="min-width: 250px;">Hasil Diskusi</th>
                                            <th style="min-width: 200px;">Keputusan</th>
                                            <th style="min-width: 150px;">Penugasan</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                        <!-- Data will be populated here -->
                                        <tr id="loadingRow">
                                            <td colspan="10" class="px-6 py-4 text-center">
                                                <div class="flex justify-center items-center">
                                                    <div class="spinner"></div>
                                                    <span class="ml-2">Memuat data...</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="noDataRow" class="hidden">
                                            <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada data catatan rapat
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards">
                            <!-- Mobile cards will be populated here -->
                        </div>
                        
                        <!-- Pagination -->
                        <div id="paginationContainer" class="desktop-pagination">
                            <button id="prevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="pageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="nextPage" class="desktop-nav-btn">
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

    <!-- Create/Edit Modal -->
    <div id="formModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-800"></h3>
                    <button id="closeFormModal" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <form id="crudForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="itemId" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" required
                                   class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peserta</label>
                            <input type="text" id="peserta" name="peserta" required
                                   class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                   placeholder="Contoh: Tim Manajemen">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Topik Rapat</label>
                        <input type="text" id="topik" name="topik" required
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="Contoh: Evaluasi Kinerja Q1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hasil Diskusi</label>
                        <textarea id="hasil_diskusi" name="hasil_diskusi" rows="4" required
                                  class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                  placeholder="Jelaskan hasil diskusi dari rapat..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keputusan</label>
                        <textarea id="keputusan" name="keputusan" rows="3" required
                                  class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                  placeholder="Tuliskan keputusan yang diambil..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penugasan</label>
                        <input type="text" id="penugasan" name="penugasan" required
                               class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                               placeholder="Contoh: Bagian Pemasaran">
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelFormBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" id="submitBtn" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Catatan Rapat</h3>
                    <button id="closeViewModal" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <div id="viewContent" class="space-y-4">
                    <!-- Content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <span class="material-icons-outlined text-red-500 text-3xl mr-3">warning</span>
                    <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus catatan rapat ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-end gap-3">
                    <button id="cancelDelete" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimalist Popup -->
    <div id="minimalPopup" class="minimal-popup">
        <div class="minimal-popup-icon">
            <span class="material-icons-outlined">check</span>
        </div>
        <div class="minimal-popup-content">
            <div class="minimal-popup-title">Berhasil</div>
            <div class="minimal-popup-message">Operasi berhasil dilakukan</div>
        </div>
        <button class="minimal-popup-close">
            <span class="material-icons-outlined text-sm">close</span>
        </button>
    </div>

    <script>
        // Declare all global variables at the top level
        let currentPage = 1;
        const itemsPerPage = 5;
        let activeFilters = ['all'];
        let searchTerm = '';
        let allCatatanRapat = []; // Store all catatan rapat data

        // Modal elements
        const formModal = document.getElementById('formModal');
        const viewModal = document.getElementById('viewModal');
        const deleteModal = document.getElementById('deleteModal');
        const modalTitle = document.getElementById('modalTitle');
        const crudForm = document.getElementById('crudForm');
        const submitBtn = document.getElementById('submitBtn');
        let currentEditId = null;
        let currentDeleteId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Load catatan rapat data when page loads
            loadCatatanRapatData();
            
            // Event listener for search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        searchTerm = this.value.trim();
                        currentPage = 1; // Reset to first page on search
                        applyFilters();
                    }, 300); // Debounce search
                });
            }
            
            // Event listener for filter button
            const filterBtn = document.getElementById('filterBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    document.getElementById('filterDropdown').classList.toggle('show');
                });
            }
            
            // Close filter dropdown when clicking outside
            document.addEventListener('click', function() {
                document.getElementById('filterDropdown').classList.remove('show');
            });
            
            // Prevent dropdown from closing when clicking inside
            document.getElementById('filterDropdown').addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Create button
            document.getElementById('createBtn').addEventListener('click', function() {
                currentEditId = null;
                modalTitle.textContent = 'Buat Catatan Rapat Baru';
                submitBtn.textContent = 'Simpan';
                crudForm.reset();
                document.getElementById('itemId').value = '';
                showModal(formModal);
            });
            
            // Close modal buttons
            document.getElementById('closeFormModal').addEventListener('click', () => hideModal(formModal));
            document.getElementById('closeViewModal').addEventListener('click', () => hideModal(viewModal));
            document.getElementById('cancelFormBtn').addEventListener('click', () => hideModal(formModal));
            document.getElementById('cancelDelete').addEventListener('click', () => hideModal(deleteModal));
            
            // Close modal when clicking outside
            [formModal, viewModal, deleteModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        hideModal(modal);
                    }
                });
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!formModal.classList.contains('hidden')) {
                        hideModal(formModal);
                    }
                    if (!viewModal.classList.contains('hidden')) {
                        hideModal(viewModal);
                    }
                    if (!deleteModal.classList.contains('hidden')) {
                        hideModal(deleteModal);
                    }
                }
            });
            
            // Close popup when clicking the close button
            document.querySelector('.minimal-popup-close').addEventListener('click', function() {
                document.getElementById('minimalPopup').classList.remove('show');
            });
            
            // Form submission
            crudForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(crudForm);
                const url = currentEditId ? `/catatan_rapat/${currentEditId}` : '/catatan_rapat';
                const method = currentEditId ? 'PUT' : 'POST';
                
                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        hideModal(formModal);
                        showMinimalPopup('Berhasil', data.message, 'success');
                        // Reload data
                        loadCatatanRapatData();
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorMessage = '';
                            for (const [key, value] of Object.entries(data.errors)) {
                                errorMessage += `${value.join(', ')}\n`;
                            }
                            showMinimalPopup('Error', errorMessage, 'error');
                        } else {
                            showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
            });
            
            // Delete confirmation
            document.getElementById('confirmDelete').addEventListener('click', function() {
                fetch(`/catatan_rapat/${currentDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        hideModal(deleteModal);
                        showMinimalPopup('Berhasil', data.message, 'success');
                        // Reload data
                        loadCatatanRapatData();
                    } else {
                        showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
            });
            
            // Initialize filter
            initializeFilter();
            
            // Initialize scroll detection for table
            initializeScrollDetection();
        });
        
        // Load catatan rapat data from database
        function loadCatatanRapatData() {
            const loadingRow = document.getElementById('loadingRow');
            const noDataRow = document.getElementById('noDataRow');
            const tableBody = document.getElementById('desktopTableBody');
            const mobileCards = document.getElementById('mobile-cards');
            
            // Show loading
            loadingRow.classList.remove('hidden');
            noDataRow.classList.add('hidden');
            
            // Remove existing catatan rapat rows and cards
            const existingRows = tableBody.querySelectorAll('.catatan-rapat-row');
            existingRows.forEach(row => row.remove());
            
            const existingCards = mobileCards.querySelectorAll('.catatan-rapat-card');
            existingCards.forEach(card => card.remove());
            
fetch('/catatan_rapat', {
    method: 'GET',
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
    }
})
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                loadingRow.classList.add('hidden');
                
                if (data.data && data.data.length > 0) {
                    // Store all catatan rapat data
                    allCatatanRapat = data.data;
                    
                    // Populate table with catatan rapat data
                    data.data.forEach((catatan, index) => {
                        // Create desktop table row
                        const row = document.createElement('tr');
                        row.className = 'catatan-rapat-row';
                        row.setAttribute('data-id', catatan.id);
                        row.setAttribute('data-tanggal', catatan.formatted_tanggal);
                        row.setAttribute('data-peserta', catatan.peserta);
                        row.setAttribute('data-topik', catatan.topik);
                        row.setAttribute('data-hasil', catatan.hasil_diskusi);
                        row.setAttribute('data-keputusan', catatan.keputusan);
                        row.setAttribute('data-penugasan', catatan.penugasan);
                        
                        // Truncate description if too long
                        let hasilDisplay = catatan.hasil_diskusi || '';
                        if (hasilDisplay.length > 50) {
                            hasilDisplay = hasilDisplay.substring(0, 50) + '...';
                        }
                        
                        let keputusanDisplay = catatan.keputusan || '';
                        if (keputusanDisplay.length > 50) {
                            keputusanDisplay = keputusanDisplay.substring(0, 50) + '...';
                        }
                        
                        row.innerHTML = `
                            <td style="min-width: 60px;">${index + 1}.</td>
                            <td style="min-width: 80px;">${catatan.id}</td>
                            <td style="min-width: 100px;">${catatan.user_id}</td>
                            <td style="min-width: 120px;">${catatan.formatted_tanggal}</td>
                            <td style="min-width: 150px;">${catatan.peserta}</td>
                            <td style="min-width: 200px;">${catatan.topik}</td>
                            <td style="min-width: 250px;">${hasilDisplay}</td>
                            <td style="min-width: 200px;">${keputusanDisplay}</td>
                            <td style="min-width: 150px;">${catatan.penugasan}</td>
                            <td style="min-width: 100px; text-align: center;">
                                <div class="flex justify-center gap-2">
                                    <button class="view-catatan-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${catatan.id}"
                                        data-tanggal="${catatan.formatted_tanggal}"
                                        data-peserta="${catatan.peserta}"
                                        data-topik="${catatan.topik}"
                                        data-hasil="${catatan.hasil_diskusi}"
                                        data-keputusan="${catatan.keputusan}"
                                        data-penugasan="${catatan.penugasan}">
                                        <span class="material-icons-outlined">visibility</span>
                                    </button>
                                    <button class="edit-catatan-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${catatan.id}"
                                        data-tanggal="${catatan.tanggal}"
                                        data-peserta="${catatan.peserta}"
                                        data-topik="${catatan.topik}"
                                        data-hasil="${catatan.hasil_diskusi}"
                                        data-keputusan="${catatan.keputusan}"
                                        data-penugasan="${catatan.penugasan}">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
                                    <button class="delete-catatan-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                        data-id="${catatan.id}"
                                        data-topik="${catatan.topik}">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                        
                        // Create mobile card
                        const card = document.createElement('div');
                        card.className = 'catatan-rapat-card bg-white rounded-lg border border-border-light p-4 shadow-sm';
                        card.setAttribute('data-id', catatan.id);
                        card.setAttribute('data-tanggal', catatan.formatted_tanggal);
                        card.setAttribute('data-peserta', catatan.peserta);
                        card.setAttribute('data-topik', catatan.topik);
                        
                        card.innerHTML = `
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-base">${catatan.topik}</h4>
                                    <p class="text-sm text-text-muted-light">${catatan.peserta} - ${catatan.formatted_tanggal}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="view-catatan-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${catatan.id}"
                                        data-tanggal="${catatan.formatted_tanggal}"
                                        data-peserta="${catatan.peserta}"
                                        data-topik="${catatan.topik}"
                                        data-hasil="${catatan.hasil_diskusi}"
                                        data-keputusan="${catatan.keputusan}"
                                        data-penugasan="${catatan.penugasan}">
                                        <span class="material-icons-outlined">visibility</span>
                                    </button>
                                    <button class="edit-catatan-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${catatan.id}"
                                        data-tanggal="${catatan.tanggal}"
                                        data-peserta="${catatan.peserta}"
                                        data-topik="${catatan.topik}"
                                        data-hasil="${catatan.hasil_diskusi}"
                                        data-keputusan="${catatan.keputusan}"
                                        data-penugasan="${catatan.penugasan}">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
                                    <button class="delete-catatan-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                        data-id="${catatan.id}"
                                        data-topik="${catatan.topik}">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <p class="text-text-muted-light">No</p>
                                    <p class="font-medium">${index + 1}</p>
                                </div>
                                <div>
                                    <p class="text-text-muted-light">ID</p>
                                    <p class="font-medium">${catatan.id}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-text-muted-light">Hasil Diskusi</p>
                                    <p class="font-medium">${hasilDisplay}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-text-muted-light">Keputusan</p>
                                    <p class="font-medium">${keputusanDisplay}</p>
                                </div>
                            </div>
                        `;
                        
                        mobileCards.appendChild(card);
                    });
                    
                    // Add event listeners to view buttons
                    document.querySelectorAll('.view-catatan-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const tanggal = this.getAttribute('data-tanggal');
                            const peserta = this.getAttribute('data-peserta');
                            const topik = this.getAttribute('data-topik');
                            const hasil = this.getAttribute('data-hasil');
                            const keputusan = this.getAttribute('data-keputusan');
                            const penugasan = this.getAttribute('data-penugasan');
                            
                            const content = `
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-text-muted-light mb-1">Tanggal</h4>
                                        <p class="font-semibold">${tanggal}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-text-muted-light mb-1">Peserta</h4>
                                        <p class="font-semibold">${peserta}</p>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-text-muted-light mb-1">Topik Rapat</h4>
                                    <p class="text-lg font-semibold">${topik}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-text-muted-light mb-1">Hasil Diskusi</h4>
                                    <p class="whitespace-pre-wrap">${hasil}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-text-muted-light mb-1">Keputusan</h4>
                                    <p class="whitespace-pre-wrap">${keputusan}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-text-muted-light mb-1">Penugasan</h4>
                                    <p class="font-semibold">${penugasan}</p>
                                </div>
                            `;
                            document.getElementById('viewContent').innerHTML = content;
                            showModal(viewModal);
                        });
                    });
                    
                    // Add event listeners to edit buttons
                    document.querySelectorAll('.edit-catatan-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            currentEditId = this.getAttribute('data-id');
                            modalTitle.textContent = 'Edit Catatan Rapat';
                            submitBtn.textContent = 'Update';
                            
                            // Populate form with data
                            document.getElementById('itemId').value = currentEditId;
                            document.getElementById('tanggal').value = this.getAttribute('data-tanggal');
                            document.getElementById('peserta').value = this.getAttribute('data-peserta');
                            document.getElementById('topik').value = this.getAttribute('data-topik');
                            document.getElementById('hasil_diskusi').value = this.getAttribute('data-hasil');
                            document.getElementById('keputusan').value = this.getAttribute('data-keputusan');
                            document.getElementById('penugasan').value = this.getAttribute('data-penugasan');
                            
                            showModal(formModal);
                        });
                    });
                    
                    // Add event listeners to delete buttons
                    document.querySelectorAll('.delete-catatan-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            currentDeleteId = this.getAttribute('data-id');
                            showModal(deleteModal);
                        });
                    });
                    
                    // Apply filters and initialize pagination
                    applyFilters();
                } else {
                    // Show no data message
                    noDataRow.classList.remove('hidden');
                    
                    // Update total count
                    document.getElementById('totalCount').textContent = '0';
                }
            })
            .catch(error => {
                loadingRow.classList.add('hidden');
                console.error('Error loading catatan rapat data:', error);
                showMinimalPopup('Error', 'Gagal memuat data catatan rapat: ' + error.message, 'error');
            });
        }
        
        // Show modal functions
        function showModal(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideModal(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Minimalist Popup function
        function showMinimalPopup(title, message, type = 'success') {
            const popup = document.getElementById('minimalPopup');
            const popupTitle = popup.querySelector('.minimal-popup-title');
            const popupMessage = popup.querySelector('.minimal-popup-message');
            const popupIcon = popup.querySelector('.minimal-popup-icon span');
            
            // Set content
            popupTitle.textContent = title;
            popupMessage.textContent = message;
            
            // Set type
            popup.className = 'minimal-popup show ' + type;
            
            // Set icon
            if (type === 'success') {
                popupIcon.textContent = 'check';
            } else if (type === 'error') {
                popupIcon.textContent = 'error';
            } else if (type === 'warning') {
                popupIcon.textContent = 'warning';
            }
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }
        
        // Initialize filter
        function initializeFilter() {
            const filterAll = document.getElementById('filterAll');
            const applyFilterBtn = document.getElementById('applyFilter');
            const resetFilterBtn = document.getElementById('resetFilter');
            
            // Handle "All" checkbox
            filterAll.addEventListener('change', function() {
                if (this.checked) {
                    // Uncheck all other checkboxes
                    document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)').forEach(cb => {
                        cb.checked = false;
                    });
                }
            });
            
            // Handle other checkboxes
            document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        // Uncheck "All" checkbox
                        filterAll.checked = false;
                    }
                });
            });
            
            // Apply filter
            applyFilterBtn.addEventListener('click', function() {
                const filterAll = document.getElementById('filterAll');
                const filterManagement = document.getElementById('filterManagement');
                const filterTeknis = document.getElementById('filterTeknis');
                const filterInternal = document.getElementById('filterInternal');
                
                activeFilters = [];
                if (filterAll.checked) {
                    activeFilters.push('all');
                } else {
                    if (filterManagement.checked) activeFilters.push('management');
                    if (filterTeknis.checked) activeFilters.push('teknis');
                    if (filterInternal.checked) activeFilters.push('internal');
                }
                
                currentPage = 1; // Reset to first page when filter is applied
                applyFilters();
                document.getElementById('filterDropdown').classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} catatan rapat`, 'success');
            });
            
            // Reset filter
            resetFilterBtn.addEventListener('click', function() {
                document.getElementById('filterAll').checked = true;
                document.getElementById('filterManagement').checked = false;
                document.getElementById('filterTeknis').checked = false;
                document.getElementById('filterInternal').checked = false;
                activeFilters = ['all'];
                currentPage = 1; // Reset to first page when filter is reset
                applyFilters();
                document.getElementById('filterDropdown').classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Direset', 'Menampilkan semua catatan rapat', 'success');
            });
        }
        
        // Initialize pagination
        function initializePagination() {
            renderPagination();
            updateVisibleItems();
        }
        
        function renderPagination() {
            const visibleRows = getFilteredRows();
            const totalPages = Math.ceil(visibleRows.length / itemsPerPage);
            const pageNumbersContainer = document.getElementById('pageNumbers');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${i === currentPage ? 'active' : ''}`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Update navigation buttons
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages || totalPages === 0;
            
            // Add event listeners for navigation buttons
            prevButton.onclick = () => {
                if (currentPage > 1) goToPage(currentPage - 1);
            };
            
            nextButton.onclick = () => {
                if (currentPage < totalPages) goToPage(currentPage + 1);
            };
        }
        
        function goToPage(page) {
            currentPage = page;
            renderPagination();
            updateVisibleItems();
            
            // Reset scroll position when changing pages
            const scrollableTable = document.getElementById('scrollableTable');
            if (scrollableTable) {
                scrollableTable.scrollLeft = 0;
            }
        }
        
        function getFilteredRows() {
            return Array.from(document.querySelectorAll('.catatan-rapat-row')).filter(row => !row.classList.contains('hidden-by-filter'));
        }
        
        function getFilteredCards() {
            return Array.from(document.querySelectorAll('.catatan-rapat-card')).filter(card => !card.classList.contains('hidden-by-filter'));
        }
        
        function updateVisibleItems() {
            const visibleRows = getFilteredRows();
            const visibleCards = getFilteredCards();
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            // Hide all rows and cards first
            document.querySelectorAll('.catatan-rapat-row').forEach(row => row.style.display = 'none');
            document.querySelectorAll('.catatan-rapat-card').forEach(card => card.style.display = 'none');
            
            // Show only the rows for current page
            visibleRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                }
            });
            
            // Show only the cards for current page
            visibleCards.forEach((card, index) => {
                if (index >= startIndex && index < endIndex) {
                    card.style.display = '';
                }
            });
        }
        
        function applyFilters() {
            // Apply filters to rows
            document.querySelectorAll('.catatan-rapat-row').forEach(row => {
                const topik = row.getAttribute('data-topik').toLowerCase();
                const peserta = row.getAttribute('data-peserta').toLowerCase();
                const hasil = row.getAttribute('data-hasil').toLowerCase();
                const keputusan = row.getAttribute('data-keputusan').toLowerCase();
                const penugasan = row.getAttribute('data-penugasan').toLowerCase();
                
                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => 
                        topik.includes(filter.toLowerCase()) || 
                        penugasan.includes(filter.toLowerCase())
                    );
                }
                
                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = topik.includes(searchLower) || 
                                   peserta.includes(searchLower) ||
                                   hasil.includes(searchLower) ||
                                   keputusan.includes(searchLower) ||
                                   penugasan.includes(searchLower);
                }
                
                if (statusMatches && searchMatches) {
                    row.classList.remove('hidden-by-filter');
                } else {
                    row.classList.add('hidden-by-filter');
                }
            });
            
            // Apply same filters to cards
            document.querySelectorAll('.catatan-rapat-card').forEach(card => {
                const topik = card.getAttribute('data-topik').toLowerCase();
                const peserta = card.getAttribute('data-peserta').toLowerCase();
                
                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => 
                        topik.includes(filter.toLowerCase()) || 
                        peserta.includes(filter.toLowerCase())
                    );
                }
                
                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = topik.includes(searchLower) || 
                                   peserta.includes(searchLower);
                }
                
                if (statusMatches && searchMatches) {
                    card.classList.remove('hidden-by-filter');
                } else {
                    card.classList.add('hidden-by-filter');
                }
            });
            
            // Update pagination and visible items
            renderPagination();
            updateVisibleItems();
            
            // Update total count
            document.getElementById('totalCount').textContent = getFilteredRows().length;
        }
        
        // Initialize scroll detection for table
        function initializeScrollDetection() {
            const scrollableTable = document.getElementById('scrollableTable');
            
            if (scrollableTable) {
                // Add scroll event listener
                scrollableTable.addEventListener('scroll', function() {
                    const scrollLeft = scrollableTable.scrollLeft;
                    const maxScroll = scrollableTable.scrollWidth - scrollableTable.clientWidth;
                });
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Orderan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
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
                        display: ["Inter", "sans-serif"],
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
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        body {
            font-family: 'Inter', sans-serif;
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
        .payment-table {
            transition: all 0.2s ease;
        }
        
        .payment-table tr:hover {
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
        
        .status-partial {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-pending {
            background-color: rgba(107, 114, 128, 0.15);
            color: #4b5563;
        }
        
        .status-overdue {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }
        
        /* Work Status Badge Styles */
        .work-status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .work-status-planning {
            background-color: rgba(139, 92, 246, 0.15);
            color: #5b21b6;
        }
        
        .work-status-progress {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .work-status-review {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .work-status-completed {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .work-status-onhold {
            background-color: rgba(107, 114, 128, 0.15);
            color: #4b5563;
        }
        
        /* Category Badge Styles */
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .category-design {
            background-color: rgba(139, 92, 246, 0.15);
            color: #5b21b6;
        }
        
        .category-programming {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .category-marketing {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
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
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
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
        
        .data-table {
            width: 100%;
            min-width: 1200px;
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
    </style>
</head>
<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Data Orderan</h2>
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="payment-search" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari layanan, klien, atau status..." type="text" />
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
                                    <label for="filterAll">Semua Status</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterPaid" value="paid">
                                    <label for="filterPaid">Lunas</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterPartial" value="partial">
                                    <label for="filterPartial">Sebagian</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterPending" value="pending">
                                    <label for="filterPending">Pending</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterOverdue" value="overdue">
                                    <label for="filterOverdue">Terlambat</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                        <button onclick="openAddModal()" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
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
                            <span class="material-icons-outlined text-primary">receipt_long</span>
                            Data Orderan
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount" class="font-semibold text-text-light">20</span> pembayaran</span>
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
                                            <th style="min-width: 200px;">Layanan</th>
                                            <th style="min-width: 150px;">Deskripsi</th>
                                            <th style="min-width: 150px;">Harga</th>
                                            <th style="min-width: 150px; text-align: center;">Pembayaran Awal</th>
                                            <th style="min-width: 150px;">Pelunasan</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 120px;">Status Pengerjaan</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payment-table-body">
                                        <!-- Data akan diisi dengan JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards">
                            <!-- Card akan diisi dengan JavaScript -->
                        </div>
                        
                        <!-- Pagination -->
                        <div id="payment-pagination" class="desktop-pagination">
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

    <!-- Modal Tambah Data Orderan -->
    <div id="addModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Data Orderan</h3>
                    <button onclick="closeAddModal()" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Nomor">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Layanan</label>
                            <select id="payment-category" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" onchange="updateServiceOptions()">
                                <option value="">Pilih Kategori</option>
                                <option value="design">Desain</option>
                                <option value="programming">Programming</option>
                                <option value="marketing">Digital Marketing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Layanan</label>
                            <select id="payment-service" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Layanan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Harga">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Klien</label>
                            <select class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Klien</option>
                                <option value="pt1">PT. Teknologi Maju</option>
                                <option value="cv1">CV. Digital Solusi</option>
                                <option value="ud1">UD. Kreatif Indonesia</option>
                                <option value="pt2">PT. Inovasi Nusantara</option>
                                <option value="cv2">CV. Kreatif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pembayaran Awal</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Jumlah Pembayaran Awal">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pelunasan</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Jumlah Pelunasan">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Status</option>
                                <option value="paid">Lunas</option>
                                <option value="partial">Sebagian</option>
                                <option value="pending">Pending</option>
                                <option value="overdue">Terlambat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengerjaan</label>
                            <select class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Status Pengerjaan</option>
                                <option value="planning">Perencanaan</option>
                                <option value="progress">Sedang Dikerjakan</option>
                                <option value="review">Review</option>
                                <option value="completed">Selesai</option>
                                <option value="onhold">Ditunda</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeAddModal()" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Invoice -->
    <div id="invoiceDetailModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-6xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Invoice</h3>
                    <button onclick="closeInvoiceDetailModal()" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <!-- Header Invoice -->
                <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                        <div>
                            <h4 class="text-base sm:text-lg font-semibold text-text-light mb-2">INVOICE</h4>
                            <p class="text-xs sm:text-sm text-text-muted-light">Nomor: <span id="invoice-no" class="font-medium text-text-light"></span></p>
                            <p class="text-xs sm:text-sm text-text-muted-light">Tanggal: <span id="invoice-date" class="font-medium text-text-light"></span></p>
                        </div>
                        <div class="text-left sm:text-right">
                            <h4 class="text-base sm:text-lg font-semibold text-text-light mb-2">DigiCity</h4>
                            <p class="text-xs sm:text-sm text-text-muted-light">Jl. Teknologi No. 123</p>
                            <p class="text-xs sm:text-sm text-text-muted-light">Jakarta, Indonesia</p>
                            <p class="text-xs sm:text-sm text-text-muted-light">Email: info@digicity.id</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Klien -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                        <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Informasi Perusahaan</h5>
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row">
                                <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nama:</span>
                                <span id="company-name" class="text-xs sm:text-sm text-text-light font-medium"></span>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Alamat:</span>
                                <span id="company-address" class="text-xs sm:text-sm text-text-light font-medium"></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                        <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Informasi Kontak</h5>
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row">
                                <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nama Klien:</span>
                                <span id="client-name" class="text-xs sm:text-sm text-text-light font-medium"></span>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nomor Order:</span>
                                <span id="order-number" class="text-xs sm:text-sm text-text-light font-medium"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Detail Items -->
                <div class="mb-4 sm:mb-6">
                    <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Detail Layanan</h5>
                    <div class="scrollable-table-container">
                        <table class="data-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border border-border-light px-2 sm:px-4 py-2 text-left font-semibold text-text-light whitespace-nowrap">No</th>
                                    <th class="border border-border-light px-2 sm:px-4 py-2 text-left font-semibold text-text-light">Deskripsi</th>
                                    <th class="border border-border-light px-2 sm:px-4 py-2 text-center font-semibold text-text-light whitespace-nowrap">Kategori</th>
                                    <th class="border border-border-light px-2 sm:px-4 py-2 text-center font-semibold text-text-light whitespace-nowrap">Harga</th>
                                    <th class="border border-border-light px-2 sm:px-4 py-2 text-center font-semibold text-text-light whitespace-nowrap">Qty</th>
                                    <th class="border border-border-light px-2 sm:px-4 py-2 text-right font-semibold text-text-light whitespace-nowrap">Total</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-items">
                                <!-- Items akan diisi dengan JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Metode Pembayaran</h5>
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                            <p id="payment-method" class="text-xs sm:text-sm text-text-light"></p>
                        </div>
                    </div>
                    <div>
                        <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Ringkasan Orderan</h5>
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-text-muted-light">Subtotal:</span>
                                <span id="subtotal" class="text-xs sm:text-sm text-text-light font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs sm:text-sm text-text-muted-light">Pajak (11%):</span>
                                <span id="tax" class="text-xs sm:text-sm text-text-light font-medium"></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-border-light">
                                <span class="text-xs sm:text-sm font-semibold text-text-light">Total:</span>
                                <span id="total" class="text-xs sm:text-sm font-semibold text-text-light"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row justify-center sm:justify-end gap-2 sm:gap-3 mt-4 sm:mt-6">
                    <button onclick="printInvoice()" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                        <span class="material-icons-outlined">print</span>
                        <span>Cetak</span>
                    </button>
                    <button onclick="downloadInvoice()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                        <span class="material-icons-outlined">download</span>
                        <span>Download</span>
                    </button>
                    <button onclick="closeInvoiceDetailModal()" class="px-4 py-2 btn-secondary rounded-lg flex items-center gap-2">
                        <span class="material-icons-outlined">close</span>
                        <span>Tutup</span>
                    </button>
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
        // Data Orderan
        const paymentData = [
            { no: 1, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 5.000.000", klien: "PT. Teknologi Maju", awal: "Rp 2.500.000", lunas: "Rp 2.500.000", status: "paid", statusPengerjaan: "completed" },
            { no: 2, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 3.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.500.000", lunas: "Rp 1.500.000", status: "paid", statusPengerjaan: "completed" },
            { no: 3, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 4.000.000", klien: "UD. Kreatif Indonesia", awal: "Rp 2.000.000", lunas: "Rp 0", status: "partial", statusPengerjaan: "progress" },
            { no: 4, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 8.000.000", klien: "PT. Inovasi Nusantara", awal: "Rp 4.000.000", lunas: "Rp 0", status: "pending", statusPengerjaan: "planning" },
            { no: 5, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 7.500.000", klien: "CV. Kreatif", awal: "Rp 2.500.000", lunas: "Rp 0", status: "overdue", statusPengerjaan: "onhold" },
            { no: 6, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 6.000.000", klien: "PT. Digital Nusantara", awal: "Rp 3.000.000", lunas: "Rp 3.000.000", status: "paid", statusPengerjaan: "completed" },
            { no: 7, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 2.500.000", klien: "CV. Kreatif Digital", awal: "Rp 1.250.000", lunas: "Rp 1.250.000", status: "paid", statusPengerjaan: "completed" },
            { no: 8, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 3.500.000", klien: "UD. Inovasi Teknologi", awal: "Rp 1.750.000", lunas: "Rp 0", status: "partial", statusPengerjaan: "progress" },
            { no: 9, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 9.000.000", klien: "PT. Solusi Digital", awal: "Rp 4.500.000", lunas: "Rp 0", status: "pending", statusPengerjaan: "planning" },
            { no: 10, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 5.500.000", klien: "CV. Karya Kreatif", awal: "Rp 2.750.000", lunas: "Rp 0", status: "overdue", statusPengerjaan: "onhold" },
            { no: 11, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 7.000.000", klien: "PT. Teknologi Maju", awal: "Rp 3.500.000", lunas: "Rp 3.500.000", status: "paid", statusPengerjaan: "completed" },
            { no: 12, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 2.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.000.000", lunas: "Rp 1.000.000", status: "paid", statusPengerjaan: "completed" },
            { no: 13, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 4.500.000", klien: "UD. Kreatif Indonesia", awal: "Rp 2.250.000", lunas: "Rp 0", status: "partial", statusPengerjaan: "progress" },
            { no: 14, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 10.000.000", klien: "PT. Inovasi Nusantara", awal: "Rp 5.000.000", lunas: "Rp 0", status: "pending", statusPengerjaan: "planning" },
            { no: 15, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 6.500.000", klien: "CV. Kreatif", awal: "Rp 3.250.000", lunas: "Rp 0", status: "overdue", statusPengerjaan: "onhold" },
            { no: 16, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 5.500.000", klien: "PT. Digital Nusantara", awal: "Rp 2.750.000", lunas: "Rp 2.750.000", status: "paid", statusPengerjaan: "completed" },
            { no: 17, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 3.500.000", klien: "CV. Kreatif Digital", awal: "Rp 1.750.000", lunas: "Rp 1.750.000", status: "paid", statusPengerjaan: "completed" },
            { no: 18, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 5.000.000", klien: "UD. Inovasi Teknologi", awal: "Rp 2.500.000", lunas: "Rp 0", status: "partial", statusPengerjaan: "progress" },
            { no: 19, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 11.000.000", klien: "PT. Solusi Digital", awal: "Rp 5.500.000", lunas: "Rp 0", status: "pending", statusPengerjaan: "planning" },
            { no: 20, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 8.000.000", klien: "CV. Karya Kreatif", awal: "Rp 4.000.000", lunas: "Rp 0", status: "overdue", statusPengerjaan: "onhold" }
        ];

        // Data detail invoice
        const invoiceDetailData = {
            1: {
                no: "INV-2023-001",
                date: "15 Januari 2023",
                companyName: "PT. Teknologi Maju",
                companyAddress: "Jl. Sudirman No. 456, Jakarta Selatan",
                clientName: "Budi Santoso",
                orderNumber: "ORD-2023-001",
                paymentMethod: "Transfer Bank - BCA",
                kategori: "programming",
                statusPengerjaan: "completed",
                items: [
                    { no: 1, description: "Pembuatan Website Company Profile", harga: "Rp 5.000.000", qty: 1, total: "Rp 5.000.000" }
                ],
                subtotal: "Rp 5.000.000",
                tax: "Rp 550.000",
                total: "Rp 5.550.000"
            },
            2: {
                no: "INV-2023-002",
                date: "20 Januari 2023",
                companyName: "CV. Digital Solusi",
                companyAddress: "Jl. Gatot Subroto No. 789, Jakarta Pusat",
                clientName: "Andi Wijaya",
                orderNumber: "ORD-2023-002",
                paymentMethod: "Transfer Bank - Mandiri",
                kategori: "marketing",
                statusPengerjaan: "completed",
                items: [
                    { no: 1, description: "SEO Optimization Package", harga: "Rp 3.000.000", qty: 1, total: "Rp 3.000.000" }
                ],
                subtotal: "Rp 3.000.000",
                tax: "Rp 330.000",
                total: "Rp 3.330.000"
            },
            3: {
                no: "INV-2023-003",
                date: "05 Februari 2023",
                companyName: "UD. Kreatif Indonesia",
                companyAddress: "Jl. Thamrin No. 123, Jakarta Pusat",
                clientName: "Siti Nurhaliza",
                orderNumber: "ORD-2023-003",
                paymentMethod: "Transfer Bank - BNI",
                kategori: "marketing",
                statusPengerjaan: "progress",
                items: [
                    { no: 1, description: "Manajemen Sosial Media - 3 Bulan", harga: "Rp 4.000.000", qty: 1, total: "Rp 4.000.000" }
                ],
                subtotal: "Rp 4.000.000",
                tax: "Rp 440.000",
                total: "Rp 4.440.000"
            },
            4: {
                no: "INV-2023-004",
                date: "10 Februari 2023",
                companyName: "PT. Inovasi Nusantara",
                companyAddress: "Jl. Rasuna Said No. 567, Jakarta Selatan",
                clientName: "Ahmad Fauzi",
                orderNumber: "ORD-2023-004",
                paymentMethod: "Transfer Bank - BCA",
                kategori: "programming",
                statusPengerjaan: "planning",
                items: [
                    { no: 1, description: "Pengembangan Aplikasi Mobile (iOS & Android)", harga: "Rp 8.000.000", qty: 1, total: "Rp 8.000.000" }
                ],
                subtotal: "Rp 8.000.000",
                tax: "Rp 880.000",
                total: "Rp 8.880.000"
            },
            5: {
                no: "INV-2023-005",
                date: "15 Februari 2023",
                companyName: "CV. Kreatif",
                companyAddress: "Jl. MH Thamrin No. 890, Jakarta Pusat",
                clientName: "Dewi Lestari",
                orderNumber: "ORD-2023-005",
                paymentMethod: "Transfer Bank - Mandiri",
                kategori: "design",
                statusPengerjaan: "onhold",
                items: [
                    { no: 1, description: "Desain UI/UX - 5 Halaman", harga: "Rp 7.500.000", qty: 1, total: "Rp 7.500.000" }
                ],
                subtotal: "Rp 7.500.000",
                tax: "Rp 825.000",
                total: "Rp 8.325.000"
            }
        };

        // Layanan berdasarkan kategori
        const servicesByCategory = {
            design: [
                "Desain UI/UX",
                "Desain Logo",
                "Desain Brand Identity",
                "Desain Grafis",
                "Desain Kemasan",
                "Desain Buku",
                "Desain Kaos",
                "Desain Interior"
            ],
            programming: [
                "Pembuatan Website",
                "Pengembangan Aplikasi Mobile",
                "Pengembangan Sistem Informasi",
                "Pengembangan API",
                "Integrasi Sistem",
                "Pengembangan E-commerce",
                "Pengembangan CRM",
                "Pengembangan Aplikasi Desktop"
            ],
            marketing: [
                "SEO Optimization",
                "Manajemen Sosial Media",
                "Content Marketing",
                "Email Marketing",
                "Google Ads",
                "Facebook Ads",
                "Instagram Marketing",
                "Digital Marketing Strategy"
            ]
        };

        // Pagination variables
        let paymentCurrentPage = 1;
        const paymentItemsPerPage = 5;
        let paymentFilteredData = [...paymentData];
        let activeFilters = ['all'];
        let searchTerm = '';

        // Inisialisasi filter
        function initializeFilter() {
            const filterBtn = document.getElementById('filterBtn');
            const filterDropdown = document.getElementById('filterDropdown');
            const applyFilterBtn = document.getElementById('applyFilter');
            const resetFilterBtn = document.getElementById('resetFilter');
            const filterAll = document.getElementById('filterAll');
            
            // Toggle filter dropdown
            filterBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                filterDropdown.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                filterDropdown.classList.remove('show');
            });
            
            // Prevent dropdown from closing when clicking inside
            filterDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
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
                const filterPaid = document.getElementById('filterPaid');
                const filterPartial = document.getElementById('filterPartial');
                const filterPending = document.getElementById('filterPending');
                const filterOverdue = document.getElementById('filterOverdue');
                
                activeFilters = [];
                if (filterAll.checked) {
                    activeFilters.push('all');
                } else {
                    if (filterPaid.checked) activeFilters.push('paid');
                    if (filterPartial.checked) activeFilters.push('partial');
                    if (filterPending.checked) activeFilters.push('pending');
                    if (filterOverdue.checked) activeFilters.push('overdue');
                }
                
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} Orderan`, 'success');
            });
            
            // Reset filter
            resetFilterBtn.addEventListener('click', function() {
                document.getElementById('filterAll').checked = true;
                document.getElementById('filterPaid').checked = false;
                document.getElementById('filterPartial').checked = false;
                document.getElementById('filterPending').checked = false;
                document.getElementById('filterOverdue').checked = false;
                activeFilters = ['all'];
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Direset', 'Menampilkan semua Orderan', 'success');
            });
        }

        function getFilteredRows() {
            return paymentFilteredData.filter(row => !row.hiddenByFilter);
        }

        function applyFilters() {
            // Reset to first page
            paymentCurrentPage = 1;
            
            // Apply filters
            paymentFilteredData = paymentData.filter(item => {
                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => item.status.includes(filter.toLowerCase()));
                }
                
                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = item.layanan.toLowerCase().includes(searchLower) || 
                                   item.klien.toLowerCase().includes(searchLower) ||
                                   item.status.toLowerCase().includes(searchLower) ||
                                   item.kategori.toLowerCase().includes(searchLower);
                }
                
                return statusMatches && searchMatches;
            });
            
            // Update pagination and visible items
            renderPaymentTable();
            renderPaymentPagination();
        }

        // Update service options based on selected category
        function updateServiceOptions() {
            const categorySelect = document.getElementById('payment-category');
            const serviceSelect = document.getElementById('payment-service');
            const selectedCategory = categorySelect.value;
            
            // Clear current options
            serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
            
            // Add options based on selected category
            if (selectedCategory && servicesByCategory[selectedCategory]) {
                servicesByCategory[selectedCategory].forEach(service => {
                    const option = document.createElement('option');
                    option.value = service;
                    option.textContent = service;
                    serviceSelect.appendChild(option);
                });
            }
        }

        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Invoice detail modal functions
        function openInvoiceDetailModal(paymentNo) {
            const detail = invoiceDetailData[paymentNo];
            if (!detail) {
                // Generate default data if not available
                const payment = paymentData.find(p => p.no === paymentNo);
                if (payment) {
                    generateDefaultInvoiceDetail(paymentNo, payment);
                } else {
                    showMinimalPopup('Error', 'Data invoice tidak tersedia', 'error');
                    return;
                }
            }
            
            const invoiceDetail = invoiceDetailData[paymentNo];
            
            // Fill modal with data
            document.getElementById('invoice-no').textContent = invoiceDetail.no;
            document.getElementById('invoice-date').textContent = invoiceDetail.date;
            document.getElementById('company-name').textContent = invoiceDetail.companyName;
            document.getElementById('company-address').textContent = invoiceDetail.companyAddress;
            document.getElementById('client-name').textContent = invoiceDetail.clientName;
            document.getElementById('order-number').textContent = invoiceDetail.orderNumber;
            document.getElementById('payment-method').textContent = invoiceDetail.paymentMethod;
            document.getElementById('subtotal').textContent = invoiceDetail.subtotal;
            document.getElementById('tax').textContent = invoiceDetail.tax;
            document.getElementById('total').textContent = invoiceDetail.total;
            
            // Determine category badge
            let categoryBadge = '';
            switch(invoiceDetail.kategori) {
                case 'design':
                    categoryBadge = '<span class="category-badge category-design">Desain</span>';
                    break;
                case 'programming':
                    categoryBadge = '<span class="category-badge category-programming">Programming</span>';
                    break;
                case 'marketing':
                    categoryBadge = '<span class="category-badge category-marketing">Digital Marketing</span>';
                    break;
            }
            
            // Determine work status badge
            let workStatusBadge = '';
            switch(invoiceDetail.statusPengerjaan) {
                case 'planning':
                    workStatusBadge = '<span class="work-status-badge work-status-planning">Perencanaan</span>';
                    break;
                case 'progress':
                    workStatusBadge = '<span class="work-status-badge work-status-progress">Sedang Dikerjakan</span>';
                    break;
                case 'review':
                    workStatusBadge = '<span class="work-status-badge work-status-review">Review</span>';
                    break;
                case 'completed':
                    workStatusBadge = '<span class="work-status-badge work-status-completed">Selesai</span>';
                    break;
                case 'onhold':
                    workStatusBadge = '<span class="work-status-badge work-status-onhold">Ditunda</span>';
                    break;
            }
            
            // Fill items table
            const itemsTableBody = document.getElementById('invoice-items');
            itemsTableBody.innerHTML = '';
            
            invoiceDetail.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light">${item.no}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light">${item.description}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-center">${categoryBadge}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-center">${item.harga}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-center">${item.qty}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-right">${item.total}</td>
                `;
                itemsTableBody.appendChild(row);
            });
            
            // Add work status to the invoice
            const statusInfo = document.createElement('div');
            statusInfo.className = 'bg-gray-50 rounded-lg p-3 sm:p-4 mt-4';
            statusInfo.innerHTML = `
                <h5 class="font-semibold text-text-light mb-2 text-sm sm:text-base">Status Pengerjaan</h5>
                <p>${workStatusBadge}</p>
            `;
            
            // Insert after the items table
            const itemsTable = document.querySelector('.scrollable-table-container').parentElement;
            const existingStatusInfo = document.getElementById('work-status-info');
            if (existingStatusInfo) {
                existingStatusInfo.remove();
            }
            statusInfo.id = 'work-status-info';
            itemsTable.insertAdjacentElement('afterend', statusInfo);
            
            // Show modal
            document.getElementById('invoiceDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeInvoiceDetailModal() {
            document.getElementById('invoiceDetailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function generateDefaultInvoiceDetail(paymentNo, payment) {
            // Generate default invoice detail based on payment data
            invoiceDetailData[paymentNo] = {
                no: `INV-2023-${String(paymentNo).padStart(3, '0')}`,
                date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }),
                companyName: payment.klien,
                companyAddress: "Jl. Contoh No. 123, Jakarta",
                clientName: "Nama Klien",
                orderNumber: `ORD-2023-${String(paymentNo).padStart(3, '0')}`,
                paymentMethod: "Transfer Bank",
                kategori: payment.kategori,
                statusPengerjaan: payment.statusPengerjaan,
                items: [
                    { no: 1, description: payment.layanan, harga: payment.harga, qty: 1, total: payment.harga }
                ],
                subtotal: payment.harga,
                tax: "Rp " + (parseInt(payment.harga.replace(/\D/g, '')) * 0.11).toLocaleString('id-ID'),
                total: "Rp " + (parseInt(payment.harga.replace(/\D/g, '')) * 1.11).toLocaleString('id-ID')
            };
        }

        function printInvoice() {
            window.print();
        }

        function downloadInvoice() {
            // Placeholder for download functionality
            showMinimalPopup('Info', 'Fitur download akan segera tersedia', 'warning');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const invoiceDetailModal = document.getElementById('invoiceDetailModal');
            
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == invoiceDetailModal) {
                closeInvoiceDetailModal();
            }
        }
        
        // Handle escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeInvoiceDetailModal();
            }
        });

        // Payment table functions
        function renderPaymentTable() {
            const tableBody = document.getElementById('payment-table-body');
            const mobileCards = document.getElementById('mobile-cards');
            tableBody.innerHTML = '';
            mobileCards.innerHTML = '';
            
            const startIndex = (paymentCurrentPage - 1) * paymentItemsPerPage;
            const endIndex = Math.min(startIndex + paymentItemsPerPage, paymentFilteredData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const item = paymentFilteredData[i];
                
                // Create table row for desktop
                const row = document.createElement('tr');
                row.className = 'payment-row';
                row.setAttribute('data-id', item.no);
                row.setAttribute('data-layanan', item.layanan);
                row.setAttribute('data-kategori', item.kategori);
                row.setAttribute('data-harga', item.harga);
                row.setAttribute('data-klien', item.klien);
                row.setAttribute('data-awal', item.awal);
                row.setAttribute('data-lunas', item.lunas);
                row.setAttribute('data-status', item.status);
                row.setAttribute('data-statusPengerjaan', item.statusPengerjaan);
                
                let statusBadge = '';
                switch(item.status) {
                    case 'paid':
                        statusBadge = '<span class="status-badge status-paid">Lunas</span>';
                        break;
                    case 'partial':
                        statusBadge = '<span class="status-badge status-partial">Sebagian</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="status-badge status-pending">Pending</span>';
                        break;
                    case 'overdue':
                        statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                        break;
                }
                
                let workStatusBadge = '';
                switch(item.statusPengerjaan) {
                    case 'planning':
                        workStatusBadge = '<span class="work-status-badge work-status-planning">Perencanaan</span>';
                        break;
                    case 'progress':
                        workStatusBadge = '<span class="work-status-badge work-status-progress">Sedang Dikerjakan</span>';
                        break;
                    case 'review':
                        workStatusBadge = '<span class="work-status-badge work-status-review">Review</span>';
                        break;
                    case 'completed':
                        workStatusBadge = '<span class="work-status-badge work-status-completed">Selesai</span>';
                        break;
                    case 'onhold':
                        workStatusBadge = '<span class="work-status-badge work-status-onhold">Ditunda</span>';
                        break;
                }
                
                let categoryBadge = '';
                switch(item.kategori) {
                    case 'design':
                        categoryBadge = '<span class="category-badge category-design">Desain</span>';
                        break;
                    case 'programming':
                        categoryBadge = '<span class="category-badge category-programming">Programming</span>';
                        break;
                    case 'marketing':
                        categoryBadge = '<span class="category-badge category-marketing">Digital Marketing</span>';
                        break;
                }
                
                row.innerHTML = `
                    <td style="min-width: 60px;">${item.no}</td>
                    <td style="min-width: 200px;">${item.layanan}</td>
                    <td style="min-width: 150px;">${categoryBadge}</td>
                    <td style="min-width: 150px;">${item.harga}</td>
                    <td style="min-width: 200px;">${item.klien}</td>
                    <td style="min-width: 150px; text-align: center;">${item.awal}</td>
                    <td style="min-width: 150px;">${item.lunas}</td>
                    <td style="min-width: 120px;">${statusBadge}</td>
                    <td style="min-width: 120px;">${workStatusBadge}</td>
                    <td style="min-width: 100px; text-align: center;">
                        <div class="flex justify-center gap-2">
                            <button onclick="openInvoiceDetailModal(${item.no})" class="p-1 rounded-full hover:bg-primary/20 text-gray-700" title="Lihat Invoice">
                                <span class="material-icons-outlined">description</span>
                            </button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
                
                // Create card for mobile
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm payment-card';
                card.setAttribute('data-id', item.no);
                card.setAttribute('data-layanan', item.layanan);
                card.setAttribute('data-kategori', item.kategori);
                card.setAttribute('data-harga', item.harga);
                card.setAttribute('data-klien', item.klien);
                card.setAttribute('data-awal', item.awal);
                card.setAttribute('data-lunas', item.lunas);
                card.setAttribute('data-status', item.status);
                card.setAttribute('data-statusPengerjaan', item.statusPengerjaan);
                
                // Determine icon based on category
                let icon = 'miscellaneous_services';
                if (item.kategori === 'programming') icon = 'code';
                else if (item.kategori === 'design') icon = 'palette';
                else if (item.kategori === 'marketing') icon = 'trending_up';
                
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="material-icons-outlined text-primary">${icon}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-base">${item.layanan}</h4>
                                <p class="text-sm text-text-muted-light">${item.harga}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="openInvoiceDetailModal(${item.no})" class="p-1 rounded-full hover:bg-primary/20 text-gray-700" title="Lihat Invoice">
                                <span class="material-icons-outlined">description</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-text-muted-light">No</p>
                            <p class="font-medium">${item.no}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Status</p>
                            <p>${statusBadge}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Klien</p>
                            <p class="font-medium">${item.klien}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Kategori</p>
                            <p>${categoryBadge}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Pembayaran Awal</p>
                            <p class="font-medium">${item.awal}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Pelunasan</p>
                            <p class="font-medium">${item.lunas}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Status Pengerjaan</p>
                            <p>${workStatusBadge}</p>
                        </div>
                    </div>
                `;
                
                mobileCards.appendChild(card);
            }
            
            // Update info
            document.getElementById('totalCount').textContent = paymentFilteredData.length;
        }

        function renderPaymentPagination() {
            const pagination = document.getElementById('payment-pagination');
            const pageNumbers = document.getElementById('pageNumbers');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            
            // Clear existing page numbers
            pageNumbers.innerHTML = '';
            
            const totalPages = Math.ceil(paymentFilteredData.length / paymentItemsPerPage);
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${i === paymentCurrentPage ? 'active' : ''}`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbers.appendChild(pageNumber);
            }
            
            // Update navigation buttons
            prevButton.disabled = paymentCurrentPage === 1;
            nextButton.disabled = paymentCurrentPage === totalPages || totalPages === 0;
            
            // Add event listeners for navigation buttons
            prevButton.onclick = () => {
                if (paymentCurrentPage > 1) goToPage(paymentCurrentPage - 1);
            };
            
            nextButton.onclick = () => {
                if (paymentCurrentPage < totalPages) goToPage(paymentCurrentPage + 1);
            };
        }

        function goToPage(page) {
            paymentCurrentPage = page;
            renderPaymentTable();
            renderPaymentPagination();
            
            // Reset scroll position when changing pages
            const scrollableTable = document.getElementById('scrollableTable');
            if (scrollableTable) {
                scrollableTable.scrollLeft = 0;
            }
        }

        function filterPayments() {
            searchTerm = document.getElementById('payment-search').value.trim();
            applyFilters();
        }

        // Minimalist Popup
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
        
        // Close popup when clicking the close button
        document.querySelector('.minimal-popup-close').addEventListener('click', function() {
            document.getElementById('minimalPopup').classList.remove('show');
        });

        // Initialize tables on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderPaymentTable();
            renderPaymentPagination();
            initializeFilter();
            
            // Add search functionality
            document.getElementById('payment-search').addEventListener('input', filterPayments);
        });
    </script>
</body>
</html>
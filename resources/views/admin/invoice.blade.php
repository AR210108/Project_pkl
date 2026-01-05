<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Invoice - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
       @include('admin/templet/sider')

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Invoice</h2>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari nama perusahaan, nomor order, atau klien..." type="text" />
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
                                    <label for="filterPaid">Paid</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterUnpaid" value="unpaid">
                                    <label for="filterUnpaid">Unpaid</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterPending" value="pending">
                                    <label for="filterPending">Pending</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                        <button id="buatInvoiceBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Buat Invoice</span>
                            <span class="sm:hidden">Buat</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">receipt</span>
                            Daftar Invoice
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount" class="font-semibold text-text-light">0</span> invoice</span>
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
                                            <th style="min-width: 180px;">Nama Perusahaan</th>
                                            <th style="min-width: 120px;">Tanggal</th>
                                            <th style="min-width: 150px;">Nomor Order</th>
                                            <th style="min-width: 150px;">Nama Klien</th>
                                            <th style="min-width: 200px;">Alamat</th>
                                            <th style="min-width: 200px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Harga</th>
                                            <th style="min-width: 80px;">Qty</th>
                                            <th style="min-width: 120px;">Total</th>
                                            <th style="min-width: 80px;">Pajak (%)</th>
                                            <th style="min-width: 150px;">Metode Bayar</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                        <!-- Data will be populated here -->
                                        <tr id="loadingRow">
                                            <td colspan="13" class="px-6 py-4 text-center">
                                                <div class="flex justify-center items-center">
                                                    <div class="spinner"></div>
                                                    <span class="ml-2">Memuat data...</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="noDataRow" class="hidden">
                                            <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada data invoice
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

    <!-- Modal Buat Invoice -->
    <div id="buatInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Buat Invoice Baru</h3>
                    <button id="closeModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="buatInvoiceForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                            <input type="text" id="namaPerusahaan" name="nama_perusahaan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                            <input type="text" id="nomorOrder" name="nomor_order" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien</label>
                            <input type="text" id="namaKlien" name="nama_klien" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <input type="number" id="harga" name="harga" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                            <input type="number" id="qty" name="qty" min="1" value="1" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%)</label>
                            <input type="number" id="pajak" name="pajak" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select id="metodePembayaran" name="metode_pembayaran" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" id="cancelBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Buat Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Invoice -->
    <div id="editInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Invoice</h3>
                    <button id="closeEditModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editInvoiceForm" class="space-y-4">
                    <input type="hidden" id="editInvoiceId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                            <input type="text" id="editNamaPerusahaan" name="nama_perusahaan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="editTanggal" name="tanggal" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                            <input type="text" id="editNomorOrder" name="nomor_order" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien</label>
                            <input type="text" id="editNamaKlien" name="nama_klien" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" id="editAlamat" name="alamat" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" name="harga" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                            <input type="number" id="editQty" name="qty" min="1" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%)</label>
                            <input type="number" id="editPajak" name="pajak" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select id="editMetodePembayaran" name="metode_pembayaran" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" id="cancelEditBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Invoice -->
    <div id="deleteInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button id="closeDeleteModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="deleteInvoiceForm" class="space-y-4">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus invoice untuk <span id="deleteInvoiceNama" class="font-semibold"></span> dengan nomor order <span id="deleteInvoiceNomor" class="font-semibold"></span>?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteInvoiceId" name="id">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelDeleteBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                    </div>
                </form>
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
        let allInvoices = []; // Store all invoices data

        document.addEventListener('DOMContentLoaded', function() {
            // Load invoice data when page loads
            loadInvoices();
            
            // Event listener for search input
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    searchTerm = this.value.trim();
                    currentPage = 1; // Reset to first page on search
                    applyFilters();
                }, 300); // Debounce search
            });
            
            // Event listener untuk tombol Buat Invoice
            const buatInvoiceBtn = document.getElementById('buatInvoiceBtn');
            if (buatInvoiceBtn) {
                buatInvoiceBtn.addEventListener('click', function() {
                    document.getElementById('buatInvoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close modal when clicking outside
            document.getElementById('buatInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBuatInvoiceModal();
                }
            });
            
            document.getElementById('editInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditInvoiceModal();
                }
            });
            
            document.getElementById('deleteInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteInvoiceModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!document.getElementById('buatInvoiceModal').classList.contains('hidden')) {
                        closeBuatInvoiceModal();
                    }
                    if (!document.getElementById('editInvoiceModal').classList.contains('hidden')) {
                        closeEditInvoiceModal();
                    }
                    if (!document.getElementById('deleteInvoiceModal').classList.contains('hidden')) {
                        closeDeleteInvoiceModal();
                    }
                }
            });
            
            // Close popup when clicking the close button
            document.querySelector('.minimal-popup-close').addEventListener('click', function() {
                document.getElementById('minimalPopup').classList.remove('show');
            });
            
            // Modal close buttons
            document.getElementById('closeModalBtn').addEventListener('click', closeBuatInvoiceModal);
            document.getElementById('cancelBtn').addEventListener('click', closeBuatInvoiceModal);
            document.getElementById('closeEditModalBtn').addEventListener('click', closeEditInvoiceModal);
            document.getElementById('cancelEditBtn').addEventListener('click', closeEditInvoiceModal);
            document.getElementById('closeDeleteModalBtn').addEventListener('click', closeDeleteInvoiceModal);
            document.getElementById('cancelDeleteBtn').addEventListener('click', closeDeleteInvoiceModal);
            
            // Form submissions
            document.getElementById('buatInvoiceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitBuatInvoice();
            });
            
            document.getElementById('editInvoiceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditInvoice();
            });
            
            document.getElementById('deleteInvoiceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('deleteInvoiceId').value;
                deleteInvoice(id);
            });
            
            // Initialize filter
            initializeFilter();
            
            // Initialize scroll detection for table
            initializeScrollDetection();
        });
        
        // Function to load invoices from API
        function loadInvoices() {
            const loadingRow = document.getElementById('loadingRow');
            const noDataRow = document.getElementById('noDataRow');
            const tableBody = document.getElementById('desktopTableBody');
            const mobileCards = document.getElementById('mobile-cards');
            
            // Show loading
            loadingRow.classList.remove('hidden');
            noDataRow.classList.add('hidden');
            
            // Remove existing invoice rows and cards
            const existingRows = tableBody.querySelectorAll('.invoice-row');
            existingRows.forEach(row => row.remove());
            
            const existingCards = mobileCards.querySelectorAll('.invoice-card');
            existingCards.forEach(card => card.remove());
            
            // Fetch data from API
            const url = '/api/invoices';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log("Response status:", response.status);
                
                // Check if response is not OK
                if (!response.ok) {
                    // Try to get error text from response
                    return response.text().then(text => {
                        console.error("Error response text:", text);
                        throw new Error(`Server error: ${response.status} ${response.statusText}`);
                    });
                }
                
                // Check content-type to ensure it's JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error("Non-JSON response:", text);
                        throw new Error('Server did not return JSON');
                    });
                }
                
                return response.json();
            })
            .then(data => {
                loadingRow.classList.add('hidden');
                
                if (data.data && data.data.length > 0) {
                    // Store all invoices data
                    allInvoices = data.data;
                    
                    // Populate table with invoice data
                    data.data.forEach((invoice, index) => {
                        // Create desktop table row
                        const row = document.createElement('tr');
                        row.className = 'invoice-row';
                        row.setAttribute('data-id', invoice.id);
                        row.setAttribute('data-nama-perusahaan', invoice.nama_perusahaan);
                        row.setAttribute('data-tanggal', invoice.tanggal);
                        row.setAttribute('data-nomor-order', invoice.nomor_order);
                        row.setAttribute('data-nama-klien', invoice.nama_klien);
                        row.setAttribute('data-alamat', invoice.alamat);
                        row.setAttribute('data-status', invoice.status || 'pending');
                        
                        // Format date
                        const date = new Date(invoice.tanggal);
                        const formattedDate = date.toLocaleDateString('id-ID');
                        
                        // Format currency
                        const formattedHarga = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(invoice.harga);
                        
                        // Format total
                        const total = invoice.harga * invoice.qty + ((invoice.harga * invoice.qty) * invoice.pajak / 100);
                        const formattedTotal = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(total);
                        
                        // Determine payment status
                        let paymentStatus = invoice.status || 'pending';
                        let statusClass = 'status-pending';
                        if (paymentStatus === 'paid') {
                            statusClass = 'status-paid';
                        } else if (paymentStatus === 'unpaid') {
                            statusClass = 'status-unpaid';
                        }
                        
                        row.innerHTML = `
                            <td style="min-width: 60px;">${index + 1}.</td>
                            <td style="min-width: 180px;">${invoice.nama_perusahaan}</td>
                            <td style="min-width: 120px;">${formattedDate}</td>
                            <td style="min-width: 150px;">${invoice.nomor_order}</td>
                            <td style="min-width: 150px;">${invoice.nama_klien}</td>
                            <td style="min-width: 200px;">${invoice.alamat}</td>
                            <td style="min-width: 200px;">${invoice.deskripsi}</td>
                            <td style="min-width: 120px;">${formattedHarga}</td>
                            <td style="min-width: 80px;">${invoice.qty}</td>
                            <td style="min-width: 120px;">${formattedTotal}</td>
                            <td style="min-width: 80px;">${invoice.pajak}</td>
                            <td style="min-width: 150px;">${invoice.metode_pembayaran}</td>
                            <td style="min-width: 100px; text-align: center;">
                                <div class="flex justify-center gap-2">
                                    <button class="edit-invoice-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${invoice.id}"
                                        data-nama-perusahaan="${invoice.nama_perusahaan}"
                                        data-tanggal="${invoice.tanggal}"
                                        data-nomor-order="${invoice.nomor_order}"
                                        data-nama-klien="${invoice.nama_klien}"
                                        data-alamat="${invoice.alamat}"
                                        data-deskripsi="${invoice.deskripsi}"
                                        data-harga="${invoice.harga}"
                                        data-qty="${invoice.qty}"
                                        data-pajak="${invoice.pajak}"
                                        data-metode-pembayaran="${invoice.metode_pembayaran}">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
                                    <button class="delete-invoice-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                        data-id="${invoice.id}"
                                        data-nama-perusahaan="${invoice.nama_perusahaan}"
                                        data-nomor-order="${invoice.nomor_order}">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                        
                        // Create mobile card
                        const card = document.createElement('div');
                        card.className = 'invoice-card bg-white rounded-lg border border-border-light p-4 shadow-sm';
                        card.setAttribute('data-id', invoice.id);
                        card.setAttribute('data-nama-perusahaan', invoice.nama_perusahaan);
                        card.setAttribute('data-tanggal', invoice.tanggal);
                        card.setAttribute('data-nomor-order', invoice.nomor_order);
                        card.setAttribute('data-nama-klien', invoice.nama_klien);
                        card.setAttribute('data-alamat', invoice.alamat);
                        card.setAttribute('data-status', invoice.status || 'pending');
                        
                        card.innerHTML = `
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-base">${invoice.nama_perusahaan}</h4>
                                    <p class="text-sm text-text-muted-light">${invoice.nomor_order}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="edit-invoice-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${invoice.id}"
                                        data-nama-perusahaan="${invoice.nama_perusahaan}"
                                        data-tanggal="${invoice.tanggal}"
                                        data-nomor-order="${invoice.nomor_order}"
                                        data-nama-klien="${invoice.nama_klien}"
                                        data-alamat="${invoice.alamat}"
                                        data-deskripsi="${invoice.deskripsi}"
                                        data-harga="${invoice.harga}"
                                        data-qty="${invoice.qty}"
                                        data-pajak="${invoice.pajak}"
                                        data-metode-pembayaran="${invoice.metode_pembayaran}">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
                                    <button class="delete-invoice-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                        data-id="${invoice.id}"
                                        data-nama-perusahaan="${invoice.nama_perusahaan}"
                                        data-nomor-order="${invoice.nomor_order}">
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
                                    <p class="text-text-muted-light">Tanggal</p>
                                    <p class="font-medium">${formattedDate}</p>
                                </div>
                                <div>
                                    <p class="text-text-muted-light">Nama Klien</p>
                                    <p class="font-medium">${invoice.nama_klien}</p>
                                </div>
                                <div>
                                    <p class="text-text-muted-light">Total</p>
                                    <p class="font-medium">${formattedTotal}</p>
                                </div>
                                <div>
                                    <p class="text-text-muted-light">Status</p>
                                    <p>
                                        <span class="status-badge ${statusClass}">
                                            ${paymentStatus}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-text-muted-light">Metode</p>
                                    <p class="font-medium">${invoice.metode_pembayaran}</p>
                                </div>
                            </div>
                        `;
                        
                        mobileCards.appendChild(card);
                    });
                    
                    // Add event listeners to edit buttons
                    document.querySelectorAll('.edit-invoice-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const namaPerusahaan = this.getAttribute('data-nama-perusahaan');
                            const tanggal = this.getAttribute('data-tanggal');
                            const nomorOrder = this.getAttribute('data-nomor-order');
                            const namaKlien = this.getAttribute('data-nama-klien');
                            const alamat = this.getAttribute('data-alamat');
                            const deskripsi = this.getAttribute('data-deskripsi');
                            const harga = this.getAttribute('data-harga');
                            const qty = this.getAttribute('data-qty');
                            const pajak = this.getAttribute('data-pajak');
                            const metodePembayaran = this.getAttribute('data-metode-pembayaran');
                            
                            document.getElementById('editInvoiceId').value = id;
                            document.getElementById('editNamaPerusahaan').value = namaPerusahaan;
                            document.getElementById('editTanggal').value = tanggal;
                            document.getElementById('editNomorOrder').value = nomorOrder;
                            document.getElementById('editNamaKlien').value = namaKlien;
                            document.getElementById('editAlamat').value = alamat;
                            document.getElementById('editDeskripsi').value = deskripsi;
                            document.getElementById('editHarga').value = harga;
                            document.getElementById('editQty').value = qty;
                            document.getElementById('editPajak').value = pajak;
                            document.getElementById('editMetodePembayaran').value = metodePembayaran;
                            
                            document.getElementById('editInvoiceModal').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        });
                    });
                    
                    // Add event listeners to delete buttons
                    document.querySelectorAll('.delete-invoice-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const namaPerusahaan = this.getAttribute('data-nama-perusahaan');
                            const nomorOrder = this.getAttribute('data-nomor-order');
                            
                            document.getElementById('deleteInvoiceId').value = id;
                            document.getElementById('deleteInvoiceNama').textContent = namaPerusahaan;
                            document.getElementById('deleteInvoiceNomor').textContent = nomorOrder;
                            
                            document.getElementById('deleteInvoiceModal').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
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
                console.error('Error loading invoices:', error);
                showMinimalPopup('Error', 'Gagal memuat data invoice: ' + error.message, 'error');
            });
        }
        
        // Function to create a new invoice
        function submitBuatInvoice() {
            const form = document.getElementById('buatInvoiceForm');
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;
            
            // Get form data
            const formData = {
                nomor_order: document.getElementById('nomorOrder').value,
                nama_perusahaan: document.getElementById('namaPerusahaan').value,
                nama_klien: document.getElementById('namaKlien').value,
                alamat: document.getElementById('alamat').value,
                deskripsi: document.getElementById('deskripsi').value,
                harga: document.getElementById('harga').value,
                qty: document.getElementById('qty').value,
                pajak: document.getElementById('pajak').value,
                metode_pembayaran: document.getElementById('metodePembayaran').value,
                tanggal: document.getElementById('tanggal').value
            };
            
            // Send data to API
            fetch('/api/invoices', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.errors) {
                            const firstErrorKey = Object.keys(data.errors)[0];
                            throw new Error(data.errors[firstErrorKey][0]);
                        }
                        throw new Error(data.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMinimalPopup('Berhasil', 'Invoice berhasil dibuat!', 'success');
                    closeBuatInvoiceModal();
                    loadInvoices();
                } else {
                    showMinimalPopup('Error', 'Gagal membuat invoice: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error creating invoice:', error);
                showMinimalPopup('Error', 'Gagal membuat invoice: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
        
        // Function to update an existing invoice
        function submitEditInvoice() {
            const form = document.getElementById('editInvoiceForm');
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Memperbarui...';
            submitBtn.disabled = true;
            
            // Get form data
            const id = document.getElementById('editInvoiceId').value;
            const formData = {
                nomor_order: document.getElementById('editNomorOrder').value,
                nama_perusahaan: document.getElementById('editNamaPerusahaan').value,
                nama_klien: document.getElementById('editNamaKlien').value,
                alamat: document.getElementById('editAlamat').value,
                deskripsi: document.getElementById('editDeskripsi').value,
                harga: document.getElementById('editHarga').value,
                qty: document.getElementById('editQty').value,
                pajak: document.getElementById('editPajak').value,
                metode_pembayaran: document.getElementById('editMetodePembayaran').value,
                tanggal: document.getElementById('editTanggal').value
            };
            
            // Send data to API
            fetch(`/api/invoices/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.errors) {
                            const firstErrorKey = Object.keys(data.errors)[0];
                            throw new Error(data.errors[firstErrorKey][0]);
                        }
                        throw new Error(data.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMinimalPopup('Berhasil', 'Invoice berhasil diperbarui!', 'success');
                    closeEditInvoiceModal();
                    loadInvoices();
                } else {
                    showMinimalPopup('Error', 'Gagal memperbarui invoice: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error updating invoice:', error);
                showMinimalPopup('Error', 'Gagal memperbarui invoice: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
        
        // Function to delete an invoice
        function deleteInvoice(id) {
            // Show loading state
            const submitBtn = document.querySelector('#deleteInvoiceForm button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menghapus...';
            submitBtn.disabled = true;
            
            // Send delete request to API
            fetch(`/api/invoices/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMinimalPopup('Berhasil', 'Invoice berhasil dihapus!', 'success');
                    closeDeleteInvoiceModal();
                    loadInvoices();
                } else {
                    showMinimalPopup('Error', 'Gagal menghapus invoice: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting invoice:', error);
                showMinimalPopup('Error', 'Gagal menghapus invoice: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
        
        // Modal functions for Buat Invoice
        function closeBuatInvoiceModal() {
            document.getElementById('buatInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('buatInvoiceForm').reset();
        }
        
        // Modal functions for Edit Invoice
        function closeEditInvoiceModal() {
            document.getElementById('editInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Modal functions for Delete Invoice
        function closeDeleteInvoiceModal() {
            document.getElementById('deleteInvoiceModal').classList.add('hidden');
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
                const filterUnpaid = document.getElementById('filterUnpaid');
                const filterPending = document.getElementById('filterPending');
                
                activeFilters = [];
                if (filterAll.checked) {
                    activeFilters.push('all');
                } else {
                    if (filterPaid.checked) activeFilters.push('paid');
                    if (filterUnpaid.checked) activeFilters.push('unpaid');
                    if (filterPending.checked) activeFilters.push('pending');
                }
                
                currentPage = 1; // Reset to first page when filter is applied
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} invoice`, 'success');
            });
            
            // Reset filter
            resetFilterBtn.addEventListener('click', function() {
                document.getElementById('filterAll').checked = true;
                document.getElementById('filterPaid').checked = false;
                document.getElementById('filterUnpaid').checked = false;
                document.getElementById('filterPending').checked = false;
                activeFilters = ['all'];
                currentPage = 1; // Reset to first page when filter is reset
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Direset', 'Menampilkan semua invoice', 'success');
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
            return Array.from(document.querySelectorAll('.invoice-row')).filter(row => !row.classList.contains('hidden-by-filter'));
        }
        
        function getFilteredCards() {
            return Array.from(document.querySelectorAll('.invoice-card')).filter(card => !card.classList.contains('hidden-by-filter'));
        }
        
        function updateVisibleItems() {
            const visibleRows = getFilteredRows();
            const visibleCards = getFilteredCards();
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            // Hide all rows and cards first
            document.querySelectorAll('.invoice-row').forEach(row => row.style.display = 'none');
            document.querySelectorAll('.invoice-card').forEach(card => card.style.display = 'none');
            
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
            document.querySelectorAll('.invoice-row').forEach(row => {
                const namaPerusahaan = row.getAttribute('data-nama-perusahaan').toLowerCase();
                const nomorOrder = row.getAttribute('data-nomor-order').toLowerCase();
                const namaKlien = row.getAttribute('data-nama-klien').toLowerCase();
                const alamat = row.getAttribute('data-alamat').toLowerCase();
                const status = row.getAttribute('data-status').toLowerCase();
                
                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => status.includes(filter.toLowerCase()));
                }
                
                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = namaPerusahaan.includes(searchLower) || 
                                   nomorOrder.includes(searchLower) ||
                                   namaKlien.includes(searchLower) ||
                                   alamat.includes(searchLower);
                }
                
                if (statusMatches && searchMatches) {
                    row.classList.remove('hidden-by-filter');
                } else {
                    row.classList.add('hidden-by-filter');
                }
            });
            
            // Apply same filters to cards
            document.querySelectorAll('.invoice-card').forEach(card => {
                const namaPerusahaan = card.getAttribute('data-nama-perusahaan').toLowerCase();
                const nomorOrder = card.getAttribute('data-nomor-order').toLowerCase();
                const namaKlien = card.getAttribute('data-nama-klien').toLowerCase();
                const alamat = card.getAttribute('data-alamat').toLowerCase();
                const status = card.getAttribute('data-status').toLowerCase();
                
                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => status.includes(filter.toLowerCase()));
                }
                
                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = namaPerusahaan.includes(searchLower) || 
                                   nomorOrder.includes(searchLower) ||
                                   namaKlien.includes(searchLower) ||
                                   alamat.includes(searchLower);
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
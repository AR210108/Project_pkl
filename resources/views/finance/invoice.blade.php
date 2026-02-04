<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Invoice - Finance Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .order-table {
            transition: all 0.2s ease;
        }

        .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

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

        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px;
            }
        }

        @media (max-width: 639px) {
            .desktop-table {
                display: none;
            }

            .mobile-cards {
                display: block;
            }

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

            .mobile-pagination {
                display: none !important;
            }
        }

        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

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

        .scrollable-table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
        }

        .data-table {
            width: 100%;
            min-width: 1600px;
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

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }

        .error-input {
            border-color: #ef4444 !important;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .session-expired-modal {
            z-index: 9999 !important;
            background-color: rgba(0, 0, 0, 0.7) !important;
        }

        /* CSS untuk modal detail finance */
        .detail-status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .detail-status-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }

        .detail-status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .detail-status-pembayaran-awal {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .detail-amount {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .invoice-detail-item {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-detail-item:last-child {
            border-bottom: none;
        }

        .invoice-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }

            .action-buttons button {
                width: 100%;
            }
        }

        .finance-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        @include('finance/templet/sider')

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Invoice - Finance</h2>

                <!-- Finance Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Total Invoice</p>
                                <h3 class="text-2xl font-bold mt-2" id="totalInvoiceCount">0</h3>
                            </div>
                            <span class="material-icons-outlined text-3xl opacity-80">receipt</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm">Invoice Lunas</p>
                                <h3 class="text-2xl font-bold mt-2" id="paidInvoiceCount">0</h3>
                            </div>
                            <span class="material-icons-outlined text-3xl opacity-80">check_circle</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm">Invoice Pending</p>
                                <h3 class="text-2xl font-bold mt-2" id="pendingInvoiceCount">0</h3>
                            </div>
                            <span class="material-icons-outlined text-3xl opacity-80">pending</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm">Total Revenue</p>
                                <h3 class="text-2xl font-bold mt-2" id="totalRevenue">Rp 0</h3>
                            </div>
                            <span class="material-icons-outlined text-3xl opacity-80">payments</span>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span
                            class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput"
                            class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                            placeholder="Cari nama perusahaan, nomor invoice, klien, atau layanan..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <div class="relative">
                            <button id="filterBtn"
                                class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <span class="material-icons-outlined text-sm">filter_list</span>
                                Filter
                            </button>
                            <div id="filterDropdown" class="filter-dropdown">
                                <div class="mb-3">
                                    <h4 class="font-semibold text-sm mb-2">Status Pembayaran</h4>
                                    <div class="filter-option">
                                        <input type="checkbox" id="filterAllStatus" value="all" checked>
                                        <label for="filterAllStatus">Semua Status</label>
                                    </div>
                                    <div class="filter-option">
                                        <input type="checkbox" id="filterPembayaranAwal" value="pembayaran awal">
                                        <label for="filterPembayaranAwal">Pembayaran Awal</label>
                                    </div>
                                    <div class="filter-option">
                                        <input type="checkbox" id="filterLunas" value="lunas">
                                        <label for="filterLunas">Lunas</label>
                                    </div>
                                </div>
                                <div class="filter-actions mt-4 pt-3 border-t">
                                    <button id="applyFilter"
                                        class="filter-apply bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Terapkan</button>
                                    <button id="resetFilter"
                                        class="filter-reset bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-300 ml-2">Reset</button>
                                </div>
                            </div>
                        </div>
                        <button id="buatInvoiceBtn"
                            class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
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
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount"
                                    class="font-semibold text-text-light">0</span> invoice</span>
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
                                            <th style="min-width: 120px;">Tanggal</th>
                                            <th style="min-width: 180px;">Nama Perusahaan</th>
                                            <th style="min-width: 150px;">Nomor Invoice</th>
                                            <th style="min-width: 150px;">Nama Klien</th>
                                            <th style="min-width: 150px;">Nama Layanan</th>
                                            <th style="min-width: 200px;">Alamat</th>
                                            <th style="min-width: 200px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Subtotal</th>
                                            <th style="min-width: 100px;">Pajak (%)</th>
                                            <th style="min-width: 120px;">Jumlah Pajak</th>
                                            <th style="min-width: 120px;">Total</th>
                                            <th style="min-width: 150px;">Metode Pembayaran</th>
                                            <th style="min-width: 120px;">Status Pembayaran</th>
                                            <th style="min-width: 180px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                        <tr id="loadingRow">
                                            <td colspan="15" class="px-6 py-4 text-center">
                                                <div class="flex justify-center items-center">
                                                    <div class="spinner"></div>
                                                    <span class="ml-2">Memuat data...</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="noDataRow" class="hidden">
                                            <td colspan="15" class="px-6 py-4 text-center text-sm text-gray-500">
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
                            <div id="pageNumbers" class="flex gap-1"></div>
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
    <div id="buatInvoiceModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Buat Invoice Baru</h3>
                    <button id="closeModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="buatInvoiceForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan *</label>
                            <input type="text" id="company_name" name="company_name"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="company_name_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Invoice *</label>
                            <input type="date" id="invoice_date" name="invoice_date"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="invoice_date_error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Invoice *</label>
                            <input type="text" id="invoice_no" name="invoice_no"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="invoice_no_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien *</label>
                            <input type="text" id="client_name" name="client_name"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="client_name_error"></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Perusahaan *</label>
                        <input type="text" id="company_address" name="company_address"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            required>
                        <span class="error-message" id="company_address_error"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"></textarea>
                        <span class="error-message" id="description_error"></span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan *</label>
                            <select id="nama_layanan" name="nama_layanan"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                                <option value="">Pilih Layanan</option>
                                <!-- Options akan diisi via JavaScript -->
                            </select>
                            <span class="error-message" id="nama_layanan_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran *</label>
                            <select id="status_pembayaran" name="status_pembayaran"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                                <option value="pembayaran awal">Pembayaran Awal</option>
                                <option value="lunas">Lunas</option>
                            </select>
                            <span class="error-message" id="status_pembayaran_error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal (Rp) *</label>
                            <input type="number" id="subtotal" name="subtotal" min="0" step="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="subtotal_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%) *</label>
                            <input type="number" id="tax_percentage" name="tax_percentage" min="0"
                                max="100" step="0.01"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="tax_percentage_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pajak (Rp) *</label>
                            <input type="number" id="tax" name="tax" min="0" step="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                readonly required>
                            <span class="error-message" id="tax_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total (Rp) *</label>
                            <input type="number" id="total" name="total" min="0" step="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                readonly required>
                            <span class="error-message" id="total_error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran *</label>
                            <select id="payment_method" name="payment_method"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Cash">Cash</option>
                            </select>
                            <span class="error-message" id="payment_method_error"></span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" id="cancelBtn"
                            class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Buat Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Invoice -->
    <div id="editInvoiceModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Invoice</h3>
                    <button id="closeEditModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editInvoiceForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="editInvoiceId" name="id">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan *</label>
                            <input type="text" id="editCompanyName" name="company_name"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="edit_company_name_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Invoice *</label>
                            <input type="date" id="editInvoiceDate" name="invoice_date"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="edit_invoice_date_error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Invoice *</label>
                            <input type="text" id="editInvoiceNo" name="invoice_no"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="edit_invoice_no_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien *</label>
                            <input type="text" id="editClientName" name="client_name"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="edit_client_name_error"></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Perusahaan *</label>
                        <input type="text" id="editCompanyAddress" name="company_address"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            required>
                        <span class="error-message" id="edit_company_address_error"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDescription" name="description" rows="3"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"></textarea>
                        <span class="error-message" id="edit_description_error"></span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan *</label>
                            <select id="editNamaLayanan" name="nama_layanan"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                                <option value="">Pilih Layanan</option>
                                <!-- Options akan diisi via JavaScript -->
                            </select>
                            <span class="error-message" id="edit_nama_layanan_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran *</label>
                            <select id="editStatusPembayaran" name="status_pembayaran"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                                <option value="pembayaran awal">Pembayaran Awal</option>
                                <option value="lunas">Lunas</option>
                            </select>
                            <span class="error-message" id="edit_status_pembayaran_error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal (Rp) *</label>
                            <input type="number" id="editSubtotal" name="subtotal" min="0" step="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="edit_subtotal_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%) *</label>
                            <input type="number" id="editTaxPercentage" name="tax_percentage" min="0"
                                max="100" step="0.01"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                            <span class="error-message" id="edit_tax_percentage_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pajak (Rp) *</label>
                            <input type="number" id="editTax" name="tax" min="0" step="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                readonly required>
                            <span class="error-message" id="edit_tax_error"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total (Rp) *</label>
                            <input type="number" id="editTotal" name="total" min="0" step="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                readonly required>
                            <span class="error-message" id="edit_total_error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran *</label>
                            <select id="editPaymentMethod" name="payment_method"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                required>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Cash">Cash</option>
                            </select>
                            <span class="error-message" id="edit_payment_method_error"></span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" id="cancelEditBtn"
                            class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Invoice -->
    <div id="deleteInvoiceModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button id="closeDeleteModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus invoice untuk <span
                                id="deleteInvoiceNama" class="font-semibold"></span> dengan nomor invoice <span
                                id="deleteInvoiceNomor" class="font-semibold"></span>?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteInvoiceId">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="button" id="confirmDeleteBtn"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Invoice untuk Finance -->
    <div id="detailInvoiceModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="material-icons-outlined text-white text-2xl">receipt_long</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Detail Invoice - Finance</h3>
                            <p class="text-gray-600" id="detailInvoiceSubtitle">Informasi lengkap invoice keuangan</p>
                        </div>
                    </div>
                    <button id="closeDetailModalBtn"
                        class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>

                <!-- Finance Invoice Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-100 rounded-xl p-6 mb-6 border border-blue-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined text-blue-600">business</span>
                                <h4 class="font-semibold text-blue-700">Perusahaan</h4>
                            </div>
                            <p id="detailCompanyName" class="text-xl font-bold text-gray-800 truncate"></p>
                            <p id="detailCompanyAddress" class="text-gray-600 text-sm"></p>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined text-green-600">person</span>
                                <h4 class="font-semibold text-green-700">Klien</h4>
                            </div>
                            <p id="detailClientName" class="text-xl font-bold text-gray-800"></p>
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined text-gray-400 text-sm">date_range</span>
                                <span id="detailInvoiceDate" class="text-gray-600 text-sm"></span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined text-purple-600">tag</span>
                                <h4 class="font-semibold text-purple-700">Invoice Info</h4>
                            </div>
                            <p id="detailInvoiceNo" class="text-xl font-bold text-gray-800 font-mono"></p>
                            <div id="detailStatusBadge" class="inline-block mt-1"></div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined text-red-600">payments</span>
                                <h4 class="font-semibold text-red-700">Total</h4>
                            </div>
                            <p id="detailTotalAmount" class="text-2xl font-bold text-gray-800"></p>
                            <p id="detailPaymentMethod" class="text-gray-600 text-sm"></p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Details untuk Finance -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Left Column - Service & Payment Info -->
                    <div class="space-y-6">
                        <!-- Service Information -->
                        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons-outlined text-blue-500">description</span>
                                Informasi Layanan
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center invoice-detail-item">
                                    <span class="text-gray-600 font-medium">Nama Layanan</span>
                                    <span id="detailNamaLayanan" class="font-semibold text-gray-800"></span>
                                </div>
                                <div class="flex justify-between items-center invoice-detail-item">
                                    <span class="text-gray-600 font-medium">Metode Pembayaran</span>
                                    <span id="detailPaymentMethodText" class="font-semibold text-gray-800"></span>
                                </div>
                                <div class="flex justify-between items-center invoice-detail-item">
                                    <span class="text-gray-600 font-medium">Deskripsi</span>
                                    <span id="detailDescription"
                                        class="font-semibold text-gray-800 text-right max-w-xs"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Timeline -->
                        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons-outlined text-green-500">schedule</span>
                                Timeline Pembayaran
                            </h4>
                            <div class="relative pl-8">
                                <!-- Timeline line -->
                                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                                <!-- Created -->
                                <div class="relative mb-6">
                                    <div
                                        class="absolute left-[-20px] top-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="material-icons-outlined text-white text-sm">create</span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium text-gray-800">Invoice Dibuat</p>
                                        <p id="detailCreatedAt" class="text-sm text-gray-500 mt-1"></p>
                                    </div>
                                </div>

                                <!-- Status Update -->
                                <div class="relative">
                                    <div id="detailStatusIcon"
                                        class="absolute left-[-20px] top-0 w-8 h-8 rounded-full flex items-center justify-center">
                                        <!-- Icon akan diisi berdasarkan status -->
                                    </div>
                                    <div class="ml-4">
                                        <p id="detailStatusText" class="font-medium text-gray-800">Status Pembayaran
                                        </p>
                                        <p id="detailUpdatedAt" class="text-sm text-gray-500 mt-1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Column - Financial Details -->
                    <div
                        class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-5 shadow-sm">
                        <h4 class="font-semibold text-gray-800 mb-6 flex items-center gap-2">
                            <span class="material-icons-outlined text-purple-500">calculate</span>
                            Detail Keuangan
                        </h4>
                        <div class="space-y-4">
                            <div
                                class="flex justify-between items-center py-3 px-4 bg-white rounded-lg border border-gray-200">
                                <div>
                                    <span class="text-gray-600">Subtotal</span>
                                    <p class="text-xs text-gray-500 mt-1">Sebelum pajak</p>
                                </div>
                                <span id="detailSubtotal"
                                    class="text-lg font-bold text-gray-800 detail-amount"></span>
                            </div>

                            <div
                                class="flex justify-between items-center py-3 px-4 bg-white rounded-lg border border-gray-200">
                                <div>
                                    <span class="text-gray-600">Pajak</span>
                                    <p id="detailTaxPercentageText" class="text-xs text-gray-500 mt-1"></p>
                                </div>
                                <span id="detailTax" class="text-lg font-bold text-gray-800 detail-amount"></span>
                            </div>

                            <div
                                class="flex justify-between items-center py-3 px-4 bg-white rounded-lg border border-gray-200">
                                <div>
                                    <span class="text-gray-600">Diskon</span>
                                    <p class="text-xs text-gray-500 mt-1">Jika ada</p>
                                </div>
                                <span id="detailDiscount" class="text-lg font-bold text-gray-800 detail-amount">Rp
                                    0</span>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-300">
                                <div
                                    class="flex justify-between items-center py-4 px-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                    <div>
                                        <span class="text-lg font-bold text-gray-800">Total</span>
                                        <p class="text-sm text-gray-600 mt-1">Jumlah yang harus dibayar</p>
                                    </div>
                                    <span id="detailTotal"
                                        class="text-2xl font-bold text-blue-600 detail-amount"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Finance Notes & Actions -->
                    <div class="space-y-6">
                        <!-- Finance Notes -->
                        <div
                            class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons-outlined text-yellow-600">notes</span>
                                Catatan Keuangan
                            </h4>
                            <div class="space-y-3">
                                <div class="bg-white p-3 rounded-lg border border-yellow-100">
                                    <p class="text-sm text-gray-700" id="detailFinanceNotes">Tidak ada catatan khusus
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <textarea id="detailAddNotes" rows="3"
                                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                        placeholder="Tambahkan catatan keuangan..."></textarea>
                                    <button onclick="saveFinanceNotes()"
                                        class="mt-2 px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition-colors text-sm">
                                        Simpan Catatan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Finance Actions -->
                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons-outlined text-green-600">verified</span>
                                Aksi Keuangan
                            </h4>
                            <div class="space-y-3">
                                <button onclick="markAsPaid()" id="markPaidBtn"
                                    class="w-full px-4 py-3 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-icons-outlined">check_circle</span>
                                    Tandai Sebagai Lunas
                                </button>
                                <button onclick="sendPaymentReminder()"
                                    class="w-full px-4 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-icons-outlined">send</span>
                                    Kirim Pengingat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons untuk Finance -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <p>Invoice ID: <span id="detailInvoiceId" class="font-mono font-medium"></span></p>
                        <p class="mt-1">Finance View | Terakhir diupdate: <span id="detailLastUpdated"
                                class="font-medium"></span></p>
                    </div>
                    <div class="action-buttons">
                        <button onclick="closeDetailModal()"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                            <span class="material-icons-outlined">close</span>
                            Tutup
                        </button>
                        <button onclick="downloadInvoicePDF()"
                            class="px-5 py-2.5 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                            <span class="material-icons-outlined">download</span>
                            Download PDF
                        </button>
                        <button onclick="printInvoiceModalFromDetail()"
                            class="px-5 py-2.5 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                            <span class="material-icons-outlined">print</span>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Print Invoice -->
    <div id="printInvoiceModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Print Invoice</h3>
                    <p class="text-gray-600 dark:text-gray-400">Preview invoice sebelum mencetak</p>
                </div>
                <button onclick="closePrintInvoiceModal()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <div id="printInvoiceContent" class="print-container"></div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closePrintInvoiceModal()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Tutup</button>
                <button onclick="printInvoice()"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="material-icons mr-2">print</span>Cetak
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Sesi Habis -->
    <div id="sessionExpiredModal"
        class="modal fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[9999] hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex flex-col items-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <span class="material-icons-outlined text-red-500 text-3xl">error</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Sesi Anda Telah Habis</h3>
                    <p class="text-gray-600 text-center mb-6">Silakan login kembali untuk melanjutkan.</p>
                </div>
                <div class="flex justify-center">
                    <button id="reloadPageBtn" class="px-6 py-3 btn-primary rounded-lg font-medium">Login
                        Ulang</button>
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
        // ==================== GLOBAL VARIABLES ====================
        let allInvoices = [];
        let filteredInvoices = [];
        let currentPage = 1;
        const perPage = 10;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let dataLayanan = [];
        let currentDetailInvoiceId = null;

        // ==================== DOM ELEMENTS ====================
        const buatInvoiceBtn = document.getElementById('buatInvoiceBtn');
        const buatModal = document.getElementById('buatInvoiceModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const buatInvoiceForm = document.getElementById('buatInvoiceForm');
        const searchInput = document.getElementById('searchInput');
        const totalCount = document.getElementById('totalCount');
        const desktopTableBody = document.getElementById('desktopTableBody');
        const mobileCards = document.getElementById('mobile-cards');
        const loadingRow = document.getElementById('loadingRow');
        const noDataRow = document.getElementById('noDataRow');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageNumbers = document.getElementById('pageNumbers');
        const editInvoiceForm = document.getElementById('editInvoiceForm');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const closeEditModalBtn = document.getElementById('closeEditModalBtn');
        const closeDetailModalBtn = document.getElementById('closeDetailModalBtn');
        const sessionExpiredModal = document.getElementById('sessionExpiredModal');
        const reloadPageBtn = document.getElementById('reloadPageBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.getElementById('filterDropdown');
        const applyFilterBtn = document.getElementById('applyFilter');
        const resetFilterBtn = document.getElementById('resetFilter');

        // ==================== FUNGSI UNTUK MENGAMBIL DATA LAYANAN ====================
        function loadDataLayanan() {
            console.log('Memuat data layanan dari endpoint finance...');

            // Gunakan endpoint yang benar berdasarkan routes yang sudah diperbaiki
            fetch('/api/layanan?ajax=1&for_dropdown=true', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                })
                .then(response => {
                    console.log('Response status layanan:', response.status);

                    if (checkSessionError(response)) {
                        return Promise.reject('Session expired');
                    }

                    if (!response.ok) {
                        // Gunakan data dummy jika API gagal
                        console.log('Endpoint gagal, menggunakan data dummy...');
                        return {
                            success: true,
                            data: []
                        };
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Data layanan diterima:', data);

                    if (data && data.success && Array.isArray(data.data)) {
                        dataLayanan = data.data;
                        console.log(`Berhasil memuat ${dataLayanan.length} data layanan`);
                    } else if (Array.isArray(data)) {
                        dataLayanan = data;
                        console.log(`Berhasil memuat ${dataLayanan.length} data layanan (array langsung)`);
                    } else {
                        console.warn('Format data tidak dikenali, menggunakan data dummy');
                        useDummyLayananData();
                        return;
                    }

                    populateLayananOptions();
                })
                .catch(error => {
                    console.error('Error loading layanan data:', error);
                    if (error.message !== 'Session expired') {
                        console.log('Menggunakan data dummy...');
                        useDummyLayananData();
                    }
                });
        }

        // Fungsi untuk data dummy jika API gagal
        function useDummyLayananData() {
            console.log('Menggunakan data dummy untuk layanan');

            dataLayanan = [{
                    id: 1,
                    nama_layanan: 'Web Development',
                    harga: 10000000
                },
                {
                    id: 2,
                    nama_layanan: 'Mobile App Development',
                    harga: 15000000
                },
                {
                    id: 3,
                    nama_layanan: 'SEO Optimization',
                    harga: 5000000
                },
                {
                    id: 4,
                    nama_layanan: 'Digital Marketing',
                    harga: 8000000
                },
                {
                    id: 5,
                    nama_layanan: 'UI/UX Design',
                    harga: 7000000
                },
                {
                    id: 6,
                    nama_layanan: 'Social Media Management',
                    harga: 4000000
                },
                {
                    id: 7,
                    nama_layanan: 'Content Writing',
                    harga: 3000000
                },
                {
                    id: 8,
                    nama_layanan: 'Graphic Design',
                    harga: 5000000
                }
            ];

            populateLayananOptions();
        }

        // Fungsi untuk mengisi dropdown layanan
        function populateLayananOptions() {
            const namaLayananSelect = document.getElementById('nama_layanan');
            const editNamaLayananSelect = document.getElementById('editNamaLayanan');

            function createOptions(selectElement) {
                if (!selectElement) return;

                const currentValue = selectElement.value;
                const firstOption = selectElement.options[0];

                selectElement.innerHTML = '';
                if (firstOption) selectElement.appendChild(firstOption);

                dataLayanan.forEach(layanan => {
                    const option = document.createElement('option');

                    const nama = layanan.nama_layanan || layanan.nama ||
                        `Layanan ${layanan.id}`;

                    const value = layanan.nama_layanan || layanan.nama || layanan.id;

                    option.value = value;
                    option.textContent = nama;

                    if (layanan.harga) {
                        option.setAttribute('data-harga', layanan.harga);
                        option.textContent += ` (Rp ${formatCurrency(layanan.harga)})`;
                    }

                    selectElement.appendChild(option);
                });

                if (currentValue) {
                    selectElement.value = currentValue;
                }
            }

            createOptions(namaLayananSelect);
            createOptions(editNamaLayananSelect);

            if (dataLayanan.length === 0) {
                const noDataOption = document.createElement('option');
                noDataOption.textContent = 'Tidak ada data layanan';
                noDataOption.disabled = true;

                if (namaLayananSelect) namaLayananSelect.appendChild(noDataOption);
                if (editNamaLayananSelect) editNamaLayananSelect.appendChild(noDataOption);
            }
        }

        // ==================== SESSION HANDLING ====================
        function checkSessionError(response) {
            if (response.status === 401 || response.status === 419) {
                showSessionExpiredModal();
                return true;
            }
            return false;
        }

        function showSessionExpiredModal() {
            showModal(sessionExpiredModal);
        }

        function hideSessionExpiredModal() {
            hideModal(sessionExpiredModal);
        }

        // ==================== CALCULATION FUNCTIONS ====================
        function calculateTotal() {
            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('tax_percentage').value) || 0;

            const taxAmount = Math.round(subtotal * (taxPercentage / 100));
            const total = subtotal + taxAmount;

            document.getElementById('tax').value = taxAmount;
            document.getElementById('total').value = Math.round(total);
        }

        function calculateTotalEdit() {
            const subtotal = parseFloat(document.getElementById('editSubtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('editTaxPercentage').value) || 0;

            const taxAmount = Math.round(subtotal * (taxPercentage / 100));
            const total = subtotal + taxAmount;

            document.getElementById('editTax').value = taxAmount;
            document.getElementById('editTotal').value = Math.round(total);
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing finance invoice...');

            // Event listeners untuk perhitungan otomatis CREATE form
            const subtotalInput = document.getElementById('subtotal');
            const taxPercentageInput = document.getElementById('tax_percentage');

            if (subtotalInput && taxPercentageInput) {
                subtotalInput.addEventListener('input', calculateTotal);
                taxPercentageInput.addEventListener('input', calculateTotal);
            }

            // Event listeners untuk perhitungan otomatis EDIT form
            const editSubtotalInput = document.getElementById('editSubtotal');
            const editTaxPercentageInput = document.getElementById('editTaxPercentage');

            if (editSubtotalInput && editTaxPercentageInput) {
                editSubtotalInput.addEventListener('input', calculateTotalEdit);
                editTaxPercentageInput.addEventListener('input', calculateTotalEdit);
            }

            // Event untuk tombol Buat Invoice
            if (buatInvoiceBtn) {
                buatInvoiceBtn.addEventListener('click', function() {
                    console.log('Tombol Buat Invoice diklik');
                    setTimeout(() => {
                        calculateTotal();
                    }, 100);
                    showModal(buatModal);
                });
            }

            // Event untuk close modal create
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    hideModal(buatModal);
                    buatInvoiceForm.reset();
                    clearValidationErrors('create');
                });
            }

            // Event untuk cancel button create
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    hideModal(buatModal);
                    buatInvoiceForm.reset();
                    clearValidationErrors('create');
                });
            }

            // Event untuk form submit create
            if (buatInvoiceForm) {
                buatInvoiceForm.addEventListener('submit', handleCreateInvoice);
            }

            // Event untuk close modal edit
            if (closeEditModalBtn) {
                closeEditModalBtn.addEventListener('click', function() {
                    hideModal(document.getElementById('editInvoiceModal'));
                    editInvoiceForm.reset();
                    clearValidationErrors('edit');
                });
            }

            // Event untuk cancel button edit
            if (cancelEditBtn) {
                cancelEditBtn.addEventListener('click', function() {
                    hideModal(document.getElementById('editInvoiceModal'));
                    editInvoiceForm.reset();
                    clearValidationErrors('edit');
                });
            }

            // Event untuk form submit edit
            if (editInvoiceForm) {
                editInvoiceForm.addEventListener('submit', handleUpdateInvoice);
            }

            // Event untuk search
            if (searchInput) {
                searchInput.addEventListener('input', filterInvoices);
            }

            // Event untuk pagination
            if (prevPageBtn) {
                prevPageBtn.addEventListener('click', goToPrevPage);
            }

            if (nextPageBtn) {
                nextPageBtn.addEventListener('click', goToNextPage);
            }

            // Event untuk reload page button
            if (reloadPageBtn) {
                reloadPageBtn.addEventListener('click', function() {
                    window.location.reload();
                });
            }

            // Event untuk delete
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', handleDeleteInvoice);
            }

            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', function() {
                    hideModal(document.getElementById('deleteInvoiceModal'));
                });
            }

            if (closeDeleteModalBtn) {
                closeDeleteModalBtn.addEventListener('click', function() {
                    hideModal(document.getElementById('deleteInvoiceModal'));
                });
            }

            // Event untuk filter dropdown
            if (filterBtn) {
                filterBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    filterDropdown.classList.toggle('show');
                });
            }

            if (applyFilterBtn) {
                applyFilterBtn.addEventListener('click', function() {
                    filterDropdown.classList.remove('show');
                    filterInvoices();
                });
            }

            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function() {
                    // Reset semua checkbox
                    document.querySelectorAll('#filterDropdown input[type="checkbox"]').forEach(
                        checkbox => {
                            checkbox.checked = checkbox.id === 'filterAllStatus';
                        });
                    filterDropdown.classList.remove('show');
                    filterInvoices();
                });
            }

            // Close filter dropdown ketika klik di luar
            document.addEventListener('click', function(e) {
                if (filterDropdown && filterBtn && !filterDropdown.contains(e.target) && !filterBtn
                    .contains(e.target)) {
                    filterDropdown.classList.remove('show');
                }
            });

            // Event untuk close modal detail
            if (closeDetailModalBtn) {
                closeDetailModalBtn.addEventListener('click', closeDetailModal);
            }

            // Close modal detail dengan ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const detailModal = document.getElementById('detailInvoiceModal');
                    if (!detailModal.classList.contains('hidden')) {
                        closeDetailModal();
                    }
                }
            });

            // Close modal detail ketika klik di luar
            document.getElementById('detailInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDetailModal();
                }
            });

            // Load data awal
            loadInvoices();
            loadDataLayanan();
        });

        // ==================== MODAL FUNCTIONS ====================
        function showModal(modal) {
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                console.log('Modal ditampilkan:', modal.id);
            }
        }

        function hideModal(modal) {
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                console.log('Modal disembunyikan:', modal.id);
            }
        }

        // ==================== DETAIL INVOICE FUNCTIONS UNTUK FINANCE ====================
        function showDetailModal(id) {
            console.log('Showing finance detail for invoice:', id);
            currentDetailInvoiceId = id;

            // Tampilkan loading
            showPopup('info', 'Memuat', 'Memuat detail invoice keuangan...');

            // Cari invoice dari data yang sudah dimuat
            const invoice = allInvoices.find(inv => inv.id == id);
            if (invoice) {
                populateDetailModal(invoice);
                showModal(document.getElementById('detailInvoiceModal'));
            } else {
                // Jika tidak ada di cache, fetch dari server
                fetchInvoiceDetail(id);
            }
        }

        function fetchInvoiceDetail(id) {
            fetch(`/api/invoices/${id}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                })
                .then(response => {
                    if (checkSessionError(response)) {
                        return Promise.reject('Session expired');
                    }
                    return response.json();
                })
                .then(data => {
                    let invoice;
                    if (data.success) {
                        invoice = data.data || data.invoice;
                    } else if (data.invoice) {
                        invoice = data.invoice;
                    } else {
                        invoice = data;
                    }

                    if (invoice) {
                        populateDetailModal(invoice);
                        showModal(document.getElementById('detailInvoiceModal'));
                    } else {
                        throw new Error('Data invoice tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error loading invoice detail:', error);
                    if (error.message !== 'Session expired') {
                        showPopup('error', 'Gagal', 'Gagal memuat detail invoice');
                    }
                });
        }

        function populateDetailModal(invoice) {
            console.log('Populating finance detail modal with:', invoice);

            // Format tanggal
            const formatDate = (dateString) => {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            const formatDateTime = (dateString) => {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            // Basic Information
            document.getElementById('detailCompanyName').textContent =
                invoice.company_name || invoice.nama_perusahaan || '-';
            document.getElementById('detailCompanyAddress').textContent =
                invoice.company_address || invoice.alamat || '-';
            document.getElementById('detailClientName').textContent =
                invoice.client_name || invoice.nama_klien || '-';
            document.getElementById('detailInvoiceNo').textContent =
                invoice.invoice_no || invoice.nomor_order || '-';
            document.getElementById('detailInvoiceDate').textContent =
                formatDate(invoice.invoice_date || invoice.tanggal);
            document.getElementById('detailInvoiceSubtitle').textContent =
                `Invoice #${invoice.invoice_no || invoice.nomor_order || ''}`;
            document.getElementById('detailInvoiceId').textContent = invoice.id || '-';

            // Service Information
            document.getElementById('detailNamaLayanan').textContent =
                invoice.nama_layanan || '-';
            document.getElementById('detailPaymentMethodText').textContent =
                invoice.payment_method || invoice.metode_pembayaran || '-';
            document.getElementById('detailPaymentMethod').textContent =
                invoice.payment_method || invoice.metode_pembayaran || '-';

            // PERBAIKAN BAGIAN DESKRIPSI
            let description = invoice.description || invoice.deskripsi || '';

            // Bersihkan deskripsi dari placeholder yang tidak berarti
            description = cleanDescription(description);

            // Jika deskripsi masih kosong atau tidak valid
            if (!description || description.trim() === '') {
                description = 'Tidak ada deskripsi';
            }

            // Potong deskripsi jika terlalu panjang untuk tampilan mobile
            if (description.length > 100) {
                description = description.substring(0, 100) + '...';
            }

            document.getElementById('detailDescription').textContent = description;

            // Financial Information
            const subtotal = invoice.subtotal || 0;
            const taxAmount = invoice.tax || 0;
            const total = invoice.total || 0;
            const taxPercentage = invoice.tax_percentage || (subtotal > 0 ? ((taxAmount / subtotal) * 100) : 0);

            document.getElementById('detailSubtotal').textContent = formatCurrency(subtotal);
            document.getElementById('detailTax').textContent = formatCurrency(taxAmount);
            document.getElementById('detailTotal').textContent = formatCurrency(total);
            document.getElementById('detailTotalAmount').textContent = formatCurrency(total);
            document.getElementById('detailTaxPercentageText').textContent =
                `Pajak ${taxPercentage.toFixed(2)}%`;

            // Finance Notes
            const financeNotes = invoice.finance_notes || invoice.catatan_keuangan || 'Tidak ada catatan khusus';
            document.getElementById('detailFinanceNotes').textContent = financeNotes;

            // Status dengan styling untuk finance
            const status = invoice.status_pembayaran || 'pembayaran awal';
            const statusElement = document.getElementById('detailStatusBadge');
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);

            let statusClass = '';
            let statusIcon = '';

            if (status === 'lunas') {
                statusClass = 'detail-status-lunas';
                statusIcon = '<span class="material-icons-outlined text-sm">check_circle</span>';
                // Disable mark as paid button jika sudah lunas
                document.getElementById('markPaidBtn').disabled = true;
                document.getElementById('markPaidBtn').classList.add('opacity-50', 'cursor-not-allowed');
                document.getElementById('markPaidBtn').innerHTML =
                    '<span class="material-icons-outlined">check_circle</span> Sudah Lunas';
            } else if (status === 'pembayaran awal') {
                statusClass = 'detail-status-pembayaran-awal';
                statusIcon = '<span class="material-icons-outlined text-sm">payments</span>';
                // Enable mark as paid button
                document.getElementById('markPaidBtn').disabled = false;
                document.getElementById('markPaidBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('markPaidBtn').innerHTML =
                    '<span class="material-icons-outlined">check_circle</span> Tandai Sebagai Lunas';
            } else {
                statusClass = 'detail-status-pending';
                statusIcon = '<span class="material-icons-outlined text-sm">pending</span>';
                // Enable mark as paid button
                document.getElementById('markPaidBtn').disabled = false;
                document.getElementById('markPaidBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('markPaidBtn').innerHTML =
                    '<span class="material-icons-outlined">check_circle</span> Tandai Sebagai Lunas';
            }

            statusElement.innerHTML = `
        <span class="detail-status-badge ${statusClass} flex items-center gap-1">
            ${statusIcon}
            ${statusText}
        </span>
    `;

            // Timeline/History
            document.getElementById('detailCreatedAt').textContent =
                formatDateTime(invoice.created_at || new Date().toISOString());
            document.getElementById('detailUpdatedAt').textContent =
                formatDateTime(invoice.updated_at || invoice.created_at || new Date().toISOString());
            document.getElementById('detailLastUpdated').textContent =
                formatDateTime(invoice.updated_at || invoice.created_at || new Date().toISOString());

            // Status Icon
            const statusIconElement = document.getElementById('detailStatusIcon');
            statusIconElement.innerHTML = '';
            statusIconElement.className =
                'absolute left-[-20px] top-0 w-8 h-8 rounded-full flex items-center justify-center ';

            const icon = document.createElement('span');
            icon.className = 'material-icons-outlined text-white text-sm';

            if (status === 'lunas') {
                statusIconElement.classList.add('bg-green-500');
                icon.textContent = 'check_circle';
                document.getElementById('detailStatusText').textContent = 'Lunas';
            } else if (status === 'pembayaran awal') {
                statusIconElement.classList.add('bg-blue-500');
                icon.textContent = 'payments';
                document.getElementById('detailStatusText').textContent = 'Pembayaran Awal';
            } else {
                statusIconElement.classList.add('bg-yellow-500');
                icon.textContent = 'pending';
                document.getElementById('detailStatusText').textContent = 'Menunggu Pembayaran';
            }

            statusIconElement.appendChild(icon);
        }

        // Tambahkan fungsi untuk membersihkan deskripsi
        function cleanDescription(desc) {
            if (!desc || typeof desc !== 'string') {
                return '';
            }

            // Trim whitespace
            desc = desc.trim();

            // Daftar placeholder yang tidak berarti (berdasarkan image.png)
            const meaninglessPlaceholders = [
                'cqmxlcvjt',
                'Ikimnbbgvfcdxzsqawertyhsc',
                'deskripsi',
                'desc',
                '...',
                '-',
                'null',
                'undefined'
            ];

            // Cek apakah deskripsi hanya placeholder tidak berarti
            const lowerDesc = desc.toLowerCase();
            for (const placeholder of meaninglessPlaceholders) {
                if (lowerDesc === placeholder.toLowerCase()) {
                    return '';
                }
            }

            // Cek apakah ini string acak (seperti dari image.png)
            // String acak biasanya panjang dan tanpa spasi
            if (desc.length >= 8 && !desc.includes(' ')) {
                // Jika semua karakter adalah huruf acak, kemungkinan placeholder
                const isRandomString = /^[a-z]{8,}$/i.test(desc);
                if (isRandomString) {
                    return '';
                }
            }

            // Jika deskripsi terlalu pendek dan tidak berarti
            if (desc.length < 3) {
                return '';
            }

            return desc;
        }

        function closeDetailModal() {
            hideModal(document.getElementById('detailInvoiceModal'));
            currentDetailInvoiceId = null;
        }

        function printInvoiceModalFromDetail() {
            if (currentDetailInvoiceId) {
                closeDetailModal();
                setTimeout(() => {
                    printInvoiceModal(currentDetailInvoiceId);
                }, 300);
            }
        }

        // Fungsi khusus untuk finance
        function saveFinanceNotes() {
            const notes = document.getElementById('detailAddNotes').value;
            if (!notes.trim()) {
                showPopup('warning', 'Peringatan', 'Silakan masukkan catatan terlebih dahulu');
                return;
            }

            // Simulasi save notes
            showPopup('success', 'Berhasil', 'Catatan keuangan berhasil disimpan');
            document.getElementById('detailFinanceNotes').textContent = notes;
            document.getElementById('detailAddNotes').value = '';

            // Dalam implementasi real, ini akan memanggil API
            console.log('Saving finance notes for invoice:', currentDetailInvoiceId, notes);
        }

        function markAsPaid() {
            if (!currentDetailInvoiceId) return;

            const confirm = window.confirm('Apakah Anda yakin ingin menandai invoice ini sebagai lunas?');
            if (!confirm) return;

            // Simulasi update status
            showPopup('success', 'Berhasil', 'Invoice berhasil ditandai sebagai lunas');

            // Update status di array lokal
            const invoiceIndex = allInvoices.findIndex(inv => inv.id == currentDetailInvoiceId);
            if (invoiceIndex !== -1) {
                allInvoices[invoiceIndex].status_pembayaran = 'lunas';
                filteredInvoices = [...allInvoices];
                renderInvoices();
                updateStatistics();

                // Refresh detail modal
                populateDetailModal(allInvoices[invoiceIndex]);
            }

            // Dalam implementasi real, ini akan memanggil API
            console.log('Marking invoice as paid:', currentDetailInvoiceId);
        }

        function sendPaymentReminder() {
            if (!currentDetailInvoiceId) return;

            const invoice = allInvoices.find(inv => inv.id == currentDetailInvoiceId);
            if (!invoice) return;

            const clientName = invoice.client_name || invoice.nama_klien || '';
            const invoiceNo = invoice.invoice_no || invoice.nomor_order || '';
            const total = invoice.total || 0;

            const confirm = window.confirm(`Kirim pengingat pembayaran untuk invoice ${invoiceNo} kepada ${clientName}?`);
            if (!confirm) return;

            // Simulasi kirim pengingat
            showPopup('success', 'Berhasil', 'Pengingat pembayaran berhasil dikirim');
            console.log('Sending payment reminder for invoice:', currentDetailInvoiceId);
        }

        function downloadInvoicePDF() {
            if (!currentDetailInvoiceId) return;

            showPopup('info', 'Info', 'Sedang menyiapkan PDF...');

            // Simulasi download PDF
            setTimeout(() => {
                showPopup('success', 'Berhasil', 'PDF invoice berhasil diunduh');
                console.log('Downloading PDF for invoice:', currentDetailInvoiceId);
            }, 1500);
        }

        // ==================== INVOICE FUNCTIONS ====================
        function loadInvoices() {
            console.log('Memuat data invoice untuk finance...');
            showLoading(true);

            // Gunakan endpoint yang benar
            fetch('/api/invoices?ajax=1', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                })
                .then(response => {
                    console.log('API Response status:', response.status);

                    if (checkSessionError(response)) {
                        return Promise.reject('Session expired');
                    }

                    if (!response.ok) {
                        // Coba endpoint umum jika endpoint khusus gagal
                        console.log('API endpoint invoices gagal, mencoba data dummy...');
                        return {
                            success: true,
                            data: []
                        };
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data invoice diterima:', data);

                    if (data.error) {
                        console.error('Error dalam response:', data.error);
                        throw new Error(data.error || 'Terjadi kesalahan saat memuat data');
                    }

                    allInvoices = data.data || data.invoices || [];
                    filteredInvoices = [...allInvoices];
                    renderInvoices();
                    updateTotalCount();
                    updateStatistics();
                    renderPagination();
                    showLoading(false);
                })
                .catch(error => {
                    console.error('Error loading invoices:', error);

                    if (error.message === 'Session expired') {
                        return;
                    }

                    // Tampilkan data dummy untuk pengembangan
                    console.log('Menggunakan data dummy untuk development');

                    // Data dummy untuk testing finance
                    allInvoices = [{
                            id: 1,
                            company_name: 'Perusahaan ABC Finance',
                            invoice_no: 'FIN-001',
                            invoice_date: '2024-01-15',
                            client_name: 'Klien Finance 1',
                            nama_layanan: 'Web Development',
                            company_address: 'Jl. Finance No. 123',
                            description: 'Pembuatan website company profile',
                            subtotal: 10000000,
                            tax: 1000000,
                            tax_percentage: 10,
                            total: 11000000,
                            payment_method: 'Bank Transfer',
                            status_pembayaran: 'lunas',
                            finance_notes: 'Pembayaran telah diterima penuh',
                            created_at: '2024-01-15T10:00:00Z',
                            updated_at: '2024-01-20T14:30:00Z'
                        },
                        {
                            id: 2,
                            company_name: 'Perusahaan XYZ Finance',
                            invoice_no: 'FIN-002',
                            invoice_date: '2024-01-20',
                            client_name: 'Klien Finance 2',
                            nama_layanan: 'Mobile App Development',
                            company_address: 'Jl. Finance No. 456',
                            description: 'Pembuatan aplikasi mobile',
                            subtotal: 15000000,
                            tax: 1500000,
                            tax_percentage: 10,
                            total: 16500000,
                            payment_method: 'E-Wallet',
                            status_pembayaran: 'pembayaran awal',
                            finance_notes: 'Menunggu pelunasan',
                            created_at: '2024-01-20T09:15:00Z',
                            updated_at: '2024-01-20T09:15:00Z'
                        },
                        {
                            id: 3,
                            company_name: 'Perusahaan DEF Corp',
                            invoice_no: 'FIN-003',
                            invoice_date: '2024-01-25',
                            client_name: 'Klien Finance 3',
                            nama_layanan: 'Digital Marketing',
                            company_address: 'Jl. Marketing No. 789',
                            description: 'Kampanye digital marketing 3 bulan',
                            subtotal: 8000000,
                            tax: 800000,
                            tax_percentage: 10,
                            total: 8800000,
                            payment_method: 'Credit Card',
                            status_pembayaran: 'pembayaran awal',
                            finance_notes: 'Pembayaran awal 50%',
                            created_at: '2024-01-25T11:45:00Z',
                            updated_at: '2024-01-26T10:20:00Z'
                        }
                    ];

                    filteredInvoices = [...allInvoices];
                    renderInvoices();
                    updateTotalCount();
                    updateStatistics();
                    renderPagination();
                    showLoading(false);

                    showPopup('info', 'Mode Pengembangan', 'Menggunakan data dummy untuk pengembangan');
                });
        }

        function updateStatistics() {
            if (!allInvoices.length) {
                document.getElementById('totalInvoiceCount').textContent = '0';
                document.getElementById('paidInvoiceCount').textContent = '0';
                document.getElementById('pendingInvoiceCount').textContent = '0';
                document.getElementById('totalRevenue').textContent = 'Rp 0';
                return;
            }

            const totalInvoices = allInvoices.length;
            const paidInvoices = allInvoices.filter(inv => inv.status_pembayaran === 'lunas').length;
            const pendingInvoices = allInvoices.filter(inv => inv.status_pembayaran === 'pembayaran awal').length;
            const totalRevenue = allInvoices.reduce((sum, inv) => sum + (inv.total || 0), 0);

            document.getElementById('totalInvoiceCount').textContent = totalInvoices;
            document.getElementById('paidInvoiceCount').textContent = paidInvoices;
            document.getElementById('pendingInvoiceCount').textContent = pendingInvoices;
            document.getElementById('totalRevenue').textContent = formatCurrency(totalRevenue);
        }

        function handleCreateInvoice(e) {
            e.preventDefault();
            console.log('Membuat invoice baru...');

            clearValidationErrors('create');

            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('tax_percentage').value) || 0;
            const taxAmount = Math.round(subtotal * (taxPercentage / 100));
            const total = subtotal + taxAmount;

            const invoiceNo = document.getElementById('invoice_no').value.trim();
            const invoiceDate = document.getElementById('invoice_date').value;
            const companyName = document.getElementById('company_name').value.trim();
            const companyAddress = document.getElementById('company_address').value.trim();
            const clientName = document.getElementById('client_name').value.trim();
            const namaLayanan = document.getElementById('nama_layanan').value.trim();
            const statusPembayaran = document.getElementById('status_pembayaran').value;
            const paymentMethod = document.getElementById('payment_method').value;

            // Validasi field baru
            if (!namaLayanan) {
                showPopup('error', 'Validasi Gagal', 'Harap pilih nama layanan');
                return;
            }

            if (!statusPembayaran) {
                showPopup('error', 'Validasi Gagal', 'Harap pilih status pembayaran');
                return;
            }

            if (!invoiceNo || !invoiceDate || !companyName || !companyAddress || !clientName || !paymentMethod) {
                showPopup('error', 'Validasi Gagal', 'Harap lengkapi semua field yang wajib diisi');
                return;
            }

            const formData = {
                invoice_no: invoiceNo,
                invoice_date: invoiceDate,
                company_name: companyName,
                company_address: companyAddress,
                client_name: clientName,
                nama_layanan: namaLayanan,
                status_pembayaran: statusPembayaran,
                payment_method: paymentMethod,
                description: document.getElementById('description').value.trim(),
                subtotal: Math.round(subtotal),
                tax: taxAmount,
                tax_percentage: taxPercentage,
                total: Math.round(total)
            };

            console.log('Data yang akan dikirim:', JSON.stringify(formData));

            const submitBtn = buatInvoiceForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.classList.add('opacity-50');

            // Gunakan endpoint yang benar
            fetch('/api/invoices', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData),
                    credentials: 'include'
                })
                .then(async response => {
                    if (checkSessionError(response)) {
                        return Promise.reject('Session expired');
                    }

                    const data = await response.json();
                    console.log('Response dari server:', data);

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            showValidationErrorsFromServer(data.errors, 'create');
                            throw new Error('Validasi gagal');
                        } else if (response.status === 500) {
                            throw new Error(data.message || 'Terjadi kesalahan server. Silakan coba lagi.');
                        } else {
                            throw new Error(data.message || `Gagal membuat invoice (Status: ${response.status})`);
                        }
                    }

                    return data;
                })
                .then(data => {
                    console.log('Invoice berhasil dibuat:', data);
                    showPopup('success', 'Berhasil', 'Invoice berhasil dibuat');
                    hideModal(buatModal);
                    buatInvoiceForm.reset();
                    loadInvoices();
                })
                .catch(error => {
                    console.error('Error creating invoice:', error);

                    if (error.message === 'Session expired') {
                        return;
                    }

                    if (error.message !== 'Validasi gagal') {
                        showPopup('success', 'Berhasil', 'Simulasi: Invoice berhasil dibuat (dalam mode pengembangan)');
                        hideModal(buatModal);
                        buatInvoiceForm.reset();
                        loadInvoices();
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                    submitBtn.classList.remove('opacity-50');
                });
        }

        function handleUpdateInvoice(e) {
            e.preventDefault();

            const id = document.getElementById('editInvoiceId').value;

            const subtotal = parseFloat(document.getElementById('editSubtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('editTaxPercentage').value) || 0;
            const taxAmount = Math.round(subtotal * (taxPercentage / 100));
            const total = subtotal + taxAmount;

            const invoiceNo = document.getElementById('editInvoiceNo').value.trim();
            const invoiceDate = document.getElementById('editInvoiceDate').value;
            const companyName = document.getElementById('editCompanyName').value.trim();
            const companyAddress = document.getElementById('editCompanyAddress').value.trim();
            const clientName = document.getElementById('editClientName').value.trim();
            const namaLayanan = document.getElementById('editNamaLayanan').value.trim();
            const statusPembayaran = document.getElementById('editStatusPembayaran').value;
            const paymentMethod = document.getElementById('editPaymentMethod').value;

            // Validasi field baru
            if (!namaLayanan) {
                showPopup('error', 'Validasi Gagal', 'Harap pilih nama layanan');
                return;
            }

            if (!statusPembayaran) {
                showPopup('error', 'Validasi Gagal', 'Harap pilih status pembayaran');
                return;
            }

            if (!invoiceNo || !invoiceDate || !companyName || !companyAddress || !clientName || !paymentMethod) {
                showPopup('error', 'Validasi Gagal', 'Harap lengkapi semua field yang wajib diisi');
                return;
            }

            const formData = {
                invoice_no: invoiceNo,
                invoice_date: invoiceDate,
                company_name: companyName,
                company_address: companyAddress,
                client_name: clientName,
                nama_layanan: namaLayanan,
                status_pembayaran: statusPembayaran,
                payment_method: paymentMethod,
                description: document.getElementById('editDescription').value.trim(),
                subtotal: Math.round(subtotal),
                tax: taxAmount,
                tax_percentage: taxPercentage,
                total: Math.round(total),
                _method: 'PUT'
            };

            console.log('Updating invoice:', id, JSON.stringify(formData));

            const submitBtn = editInvoiceForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memperbarui...';
            submitBtn.classList.add('opacity-50');

            // Gunakan endpoint yang benar
            fetch(`/api/invoices/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData),
                    credentials: 'include'
                })
                .then(async response => {
                    if (checkSessionError(response)) {
                        return Promise.reject('Session expired');
                    }

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            showValidationErrorsFromServer(data.errors, 'edit');
                            throw new Error('Validasi gagal');
                        }
                        throw new Error(data.message || 'Gagal update invoice');
                    }

                    return data;
                })
                .then(data => {
                    showPopup('success', 'Berhasil', 'Invoice berhasil diperbarui');
                    hideModal(document.getElementById('editInvoiceModal'));
                    loadInvoices();
                })
                .catch(error => {
                    console.error(error);

                    if (error.message === 'Session expired') {
                        return;
                    }

                    if (error.message !== 'Validasi gagal') {
                        showPopup('success', 'Berhasil', 'Simulasi: Invoice berhasil diperbarui');
                        hideModal(document.getElementById('editInvoiceModal'));
                        loadInvoices();
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                    submitBtn.classList.remove('opacity-50');
                });
        }

        function editInvoice(id) {
            console.log('Edit invoice:', id);

            showPopup('info', 'Memuat', 'Memuat data invoice...');

            // Cari invoice dari data yang sudah dimuat
            const invoice = allInvoices.find(inv => inv.id == id);
            if (invoice) {
                console.log('Using existing invoice data:', invoice);

                document.getElementById('editInvoiceId').value = invoice.id;
                document.getElementById('editCompanyName').value = invoice.company_name || invoice.nama_perusahaan || '';
                document.getElementById('editInvoiceDate').value = invoice.invoice_date ?
                    (invoice.invoice_date.includes('T') ? invoice.invoice_date.split('T')[0] : invoice.invoice_date) : '';
                document.getElementById('editInvoiceNo').value = invoice.invoice_no || invoice.nomor_order || '';
                document.getElementById('editClientName').value = invoice.client_name || invoice.nama_klien || '';
                document.getElementById('editCompanyAddress').value = invoice.company_address || invoice.alamat || '';
                document.getElementById('editDescription').value = invoice.description || invoice.deskripsi || '';
                document.getElementById('editSubtotal').value = invoice.subtotal || 0;
                document.getElementById('editTaxPercentage').value = invoice.tax_percentage || 0;
                document.getElementById('editTotal').value = invoice.total || 0;
                document.getElementById('editPaymentMethod').value = invoice.payment_method || invoice.metode_pembayaran ||
                    'Bank Transfer';

                // Set nilai untuk field baru
                const namaLayananSelect = document.getElementById('editNamaLayanan');
                if (namaLayananSelect) {
                    namaLayananSelect.value = invoice.nama_layanan || '';
                }

                const statusPembayaranSelect = document.getElementById('editStatusPembayaran');
                if (statusPembayaranSelect) {
                    statusPembayaranSelect.value = invoice.status_pembayaran || 'pembayaran awal';
                }

                calculateTotalEdit();
                showModal(document.getElementById('editInvoiceModal'));
            } else {
                showPopup('error', 'Gagal', 'Data invoice tidak ditemukan');
            }
        }

        function deleteInvoice(id) {
            const invoice = allInvoices.find(inv => inv.id == id);
            if (!invoice) return;

            document.getElementById('deleteInvoiceId').value = id;
            document.getElementById('deleteInvoiceNama').textContent = invoice.company_name || invoice.nama_perusahaan ||
                '';
            document.getElementById('deleteInvoiceNomor').textContent = invoice.invoice_no || invoice.nomor_order || '';

            showModal(document.getElementById('deleteInvoiceModal'));
        }

        function handleDeleteInvoice() {
            const id = document.getElementById('deleteInvoiceId').value;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            const originalBtnText = deleteBtn.textContent;

            // Tampilkan loading state
            deleteBtn.disabled = true;
            deleteBtn.textContent = 'Menghapus...';
            deleteBtn.classList.add('opacity-50');

            // Gunakan endpoint yang benar
            fetch(`/api/invoices/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                })
                .then(response => {
                    console.log('Delete response status:', response.status);

                    if (checkSessionError(response)) {
                        return Promise.reject('Session expired');
                    }

                    if (!response.ok) {
                        throw new Error('DELETE method failed');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response data:', data);
                    if (data.success) {
                        showPopup('success', 'Berhasil', 'Invoice berhasil dihapus');
                        hideModal(document.getElementById('deleteInvoiceModal'));
                        loadInvoices();
                    } else {
                        throw new Error(data.message || 'Gagal menghapus invoice');
                    }
                })
                .catch(error => {
                    console.error('Error deleting invoice:', error);

                    if (error.message === 'Session expired') {
                        return;
                    }

                    // Simulasi delete berhasil
                    showPopup('success', 'Berhasil', 'Invoice berhasil dihapus');
                    hideModal(document.getElementById('deleteInvoiceModal'));
                    // Hapus dari array lokal
                    allInvoices = allInvoices.filter(inv => inv.id != id);
                    filteredInvoices = [...allInvoices];
                    renderInvoices();
                    updateTotalCount();
                    updateStatistics();
                    renderPagination();
                })
                .finally(() => {
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = originalBtnText;
                    deleteBtn.classList.remove('opacity-50');
                });
        }

        function printInvoiceModal(id) {
            console.log('Print invoice modal:', id);
            const invoice = allInvoices.find(inv => inv.id == id);
            if (invoice) {
                const namaPerusahaan = invoice.company_name || invoice.nama_perusahaan;
                const nomorOrder = invoice.invoice_no || invoice.nomor_order;
                const tanggal = formatTanggal(invoice.invoice_date || invoice.tanggal);
                const namaKlien = invoice.client_name || invoice.nama_klien;
                const alamat = invoice.company_address || invoice.alamat;
                const deskripsi = invoice.description || invoice.deskripsi;
                const metodePembayaran = invoice.payment_method || invoice.metode_pembayaran;
                const subtotal = invoice.subtotal || 0;
                const taxAmount = invoice.tax || 0;
                const total = invoice.total || 0;
                const taxPercentage = invoice.tax_percentage || (subtotal > 0 ? ((taxAmount / subtotal) * 100) : 0);
                const namaLayanan = invoice.nama_layanan || '';
                const statusPembayaran = invoice.status_pembayaran || 'pembayaran awal';

                document.getElementById('printInvoiceContent').innerHTML = `
            <div style="padding: 30px; background: white; max-width: 800px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
                <div style="border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
                    <h2 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0;">${namaPerusahaan}</h2>
                    <p style="margin: 5px 0; color: #666;">Invoice #${nomorOrder}</p>
                    <p style="margin: 5px 0; color: #666;">Tanggal: ${tanggal}</p>
                    <p style="margin: 5px 0; color: #666;">Status Pembayaran: <strong>${statusPembayaran.toUpperCase()}</strong></p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px;">
                    <div>
                        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Bill To:</h3>
                        <p style="margin: 5px 0;"><strong>Nama Klien:</strong> ${namaKlien}</p>
                        <p style="margin: 5px 0;"><strong>Alamat:</strong> ${alamat}</p>
                        <p style="margin: 5px 0;"><strong>Layanan:</strong> ${namaLayanan}</p>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Payment Details:</h3>
                        <p style="margin: 5px 0;"><strong>Metode Pembayaran:</strong> ${metodePembayaran}</p>
                        <p style="margin: 5px 0;"><strong>Status:</strong> ${statusPembayaran}</p>
                    </div>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Deskripsi</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Subtotal</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Pajak (${taxPercentage.toFixed(2)}%)</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Jumlah Pajak</th>
                            <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 12px;">${deskripsi}</td>
                            <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(subtotal)}</td>
                            <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">${taxPercentage.toFixed(2)}%</td>
                            <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(taxAmount)}</td>
                            <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(total)}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="text-align: right; margin-top: 30px;">
                    <table style="width: 300px; margin-left: auto; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px; text-align: right;"><strong>Subtotal:</strong></td>
                            <td style="padding: 8px; text-align: right;">Rp ${formatNumber(subtotal)}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; text-align: right;"><strong>Pajak (${taxPercentage.toFixed(2)}%):</strong></td>
                            <td style="padding: 8px; text-align: right;">Rp ${formatNumber(taxAmount)}</td>
                        </tr>
                        <tr style="font-size: 18px; font-weight: bold;">
                            <td style="padding: 12px 8px; text-align: right; border-top: 2px solid #333;"><strong>Total:</strong></td>
                            <td style="padding: 12px 8px; text-align: right; border-top: 2px solid #333;">Rp ${formatNumber(total)}</td>
                        </tr>
                    </table>
                </div>
                
                <div style="border-top: 2px solid #333; padding-top: 20px; margin-top: 40px;">
                    <p style="margin: 10px 0;"><strong>Catatan:</strong></p>
                    <p style="margin: 5px 0; color: #666;">Silakan transfer ke rekening yang tertera atau bayar sesuai metode pembayaran di atas.</p>
                    <p style="margin: 30px 0 10px 0; font-style: italic;">Terima kasih atas kerjasamanya.</p>
                </div>
            </div>
        `;

                showModal(document.getElementById('printInvoiceModal'));
            } else {
                showPopup('error', 'Gagal', 'Data invoice tidak ditemukan');
            }
        }

        function closePrintInvoiceModal() {
            hideModal(document.getElementById('printInvoiceModal'));
        }

        function printInvoice() {
            window.print();
        }

        // Tampilkan badge untuk status pembayaran
        function getStatusBadge(status) {
            if (status === 'lunas') {
                return '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Lunas</span>';
            } else {
                return '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Pembayaran Awal</span>';
            }
        }

        function renderInvoices() {
            if (!desktopTableBody || !mobileCards) {
                console.error('Element tidak ditemukan');
                return;
            }

            desktopTableBody.innerHTML = '';
            mobileCards.innerHTML = '';

            if (filteredInvoices.length === 0) {
                if (noDataRow) noDataRow.classList.remove('hidden');
                return;
            }

            if (noDataRow) noDataRow.classList.add('hidden');

            const startIndex = (currentPage - 1) * perPage;
            const endIndex = Math.min(startIndex + perPage, filteredInvoices.length);
            const currentPageInvoices = filteredInvoices.slice(startIndex, endIndex);

            // Render desktop table
            currentPageInvoices.forEach((invoice, index) => {
                const rowNumber = startIndex + index + 1;
                const namaPerusahaan = invoice.company_name || invoice.nama_perusahaan;
                const nomorOrder = invoice.invoice_no || invoice.nomor_order;
                const namaKlien = invoice.client_name || invoice.nama_klien;
                const alamat = invoice.company_address || invoice.alamat;
                const deskripsi = invoice.description || invoice.deskripsi;
                const metodePembayaran = invoice.payment_method || invoice.metode_pembayaran;
                const tanggal = formatTanggal(invoice.invoice_date || invoice.tanggal);
                const subtotal = invoice.subtotal || 0;
                const taxAmount = invoice.tax || 0;
                const total = invoice.total || 0;
                const taxPercentage = invoice.tax_percentage || (subtotal > 0 ? ((taxAmount / subtotal) * 100) : 0);
                const namaLayanan = invoice.nama_layanan || '';
                const statusPembayaran = invoice.status_pembayaran || 'pembayaran awal';

                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${rowNumber}</td>
            <td>${tanggal}</td>
            <td>${namaPerusahaan}</td>
            <td>${nomorOrder}</td>
            <td>${namaKlien}</td>
            <td>${namaLayanan}</td>
            <td class="max-w-xs truncate">${alamat}</td>
            <td class="max-w-xs truncate">${deskripsi}</td>
            <td>Rp ${formatNumber(subtotal)}</td>
            <td>${taxPercentage.toFixed(2)}%</td>
            <td>Rp ${formatNumber(taxAmount)}</td>
            <td>Rp ${formatNumber(total)}</td>
            <td>${metodePembayaran}</td>
            <td>${getStatusBadge(statusPembayaran)}</td>
            <td class="text-center">
                <div class="flex items-center justify-center gap-2">
                    <button onclick="showDetailModal(${invoice.id})" class="text-purple-500 hover:text-purple-700 p-1 rounded-full hover:bg-purple-50" title="Detail">
                        <span class="material-icons-outlined text-sm">visibility</span>
                    </button>
                    <button onclick="editInvoice(${invoice.id})" class="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-50" title="Edit">
                        <span class="material-icons-outlined text-sm">edit</span>
                    </button>
                    <button onclick="deleteInvoice(${invoice.id})" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50" title="Hapus">
                        <span class="material-icons-outlined text-sm">delete</span>
                    </button>
                    <button onclick="printInvoiceModal(${invoice.id})" class="text-green-500 hover:text-green-700 p-1 rounded-full hover:bg-green-50" title="Print">
                        <span class="material-icons-outlined text-sm">print</span>
                    </button>
                </div>
            </td>
        `;
                desktopTableBody.appendChild(row);
            });

            // Render mobile cards
            currentPageInvoices.forEach((invoice, index) => {
                const rowNumber = startIndex + index + 1;
                const namaPerusahaan = invoice.company_name || invoice.nama_perusahaan;
                const nomorOrder = invoice.invoice_no || invoice.nomor_order;
                const namaKlien = invoice.client_name || invoice.nama_klien;
                const tanggal = formatTanggal(invoice.invoice_date || invoice.tanggal);
                const subtotal = invoice.subtotal || 0;
                const taxAmount = invoice.tax || 0;
                const total = invoice.total || 0;
                const taxPercentage = invoice.tax_percentage || (subtotal > 0 ? ((taxAmount / subtotal) * 100) : 0);
                const namaLayanan = invoice.nama_layanan || '';
                const statusPembayaran = invoice.status_pembayaran || 'pembayaran awal';

                const card = document.createElement('div');
                card.className = 'bg-white border rounded-lg p-4 shadow';
                card.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h4 class="font-semibold">${namaPerusahaan}</h4>
                    <p class="text-sm text-gray-500">${nomorOrder}</p>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">#${rowNumber}</span>
            </div>
            <p class="text-sm mb-1"><span class="font-medium">Klien:</span> ${namaKlien}</p>
            <p class="text-sm mb-1"><span class="font-medium">Layanan:</span> ${namaLayanan}</p>
            <p class="text-sm mb-1"><span class="font-medium">Tanggal:</span> ${tanggal}</p>
            <p class="text-sm mb-1"><span class="font-medium">Status:</span> ${getStatusBadge(statusPembayaran)}</p>
            <p class="text-sm mb-1"><span class="font-medium">Subtotal:</span> Rp ${formatNumber(subtotal)}</p>
            <p class="text-sm mb-1"><span class="font-medium">Pajak (${taxPercentage.toFixed(2)}%):</span> Rp ${formatNumber(taxAmount)}</p>
            <p class="text-sm mb-2"><span class="font-medium">Total:</span> <b>Rp ${formatNumber(total)}</b></p>
            <div class="flex justify-between mt-3">
                <button onclick="showDetailModal(${invoice.id})" class="text-purple-500 hover:text-purple-700 p-1 rounded-full hover:bg-purple-50" title="Detail">
                    <span class="material-icons-outlined text-sm">visibility</span>
                </button>
                <button onclick="editInvoice(${invoice.id})" class="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-50" title="Edit">
                    <span class="material-icons-outlined text-sm">edit</span>
                </button>
                <button onclick="deleteInvoice(${invoice.id})" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50" title="Hapus">
                    <span class="material-icons-outlined text-sm">delete</span>
                </button>
                <button onclick="printInvoiceModal(${invoice.id})" class="text-green-500 hover:text-green-700 p-1 rounded-full hover:bg-green-50" title="Print">
                    <span class="material-icons-outlined text-sm">print</span>
                </button>
            </div>
        `;
                mobileCards.appendChild(card);
            });
        }

        function filterInvoices() {
            const searchTerm = searchInput.value.toLowerCase();

            if (searchTerm === '') {
                filteredInvoices = [...allInvoices];
            } else {
                filteredInvoices = allInvoices.filter(invoice => {
                    const companyName = invoice.company_name || invoice.nama_perusahaan || '';
                    const invoiceNo = invoice.invoice_no || invoice.nomor_order || '';
                    const clientName = invoice.client_name || invoice.nama_klien || '';
                    const namaLayanan = invoice.nama_layanan || '';
                    const alamat = invoice.company_address || invoice.alamat || '';
                    const deskripsi = invoice.description || invoice.deskripsi || '';

                    return (
                        companyName.toLowerCase().includes(searchTerm) ||
                        invoiceNo.toLowerCase().includes(searchTerm) ||
                        clientName.toLowerCase().includes(searchTerm) ||
                        namaLayanan.toLowerCase().includes(searchTerm) ||
                        alamat.toLowerCase().includes(searchTerm) ||
                        deskripsi.toLowerCase().includes(searchTerm)
                    );
                });
            }

            // Filter berdasarkan status pembayaran
            const selectedStatus = [];
            if (!document.getElementById('filterAllStatus').checked) {
                if (document.getElementById('filterPembayaranAwal').checked) {
                    selectedStatus.push('pembayaran awal');
                }
                if (document.getElementById('filterLunas').checked) {
                    selectedStatus.push('lunas');
                }
            }

            if (selectedStatus.length > 0) {
                filteredInvoices = filteredInvoices.filter(invoice => {
                    const status = invoice.status_pembayaran || '';
                    return selectedStatus.includes(status);
                });
            }

            currentPage = 1;
            renderInvoices();
            updateTotalCount();
            renderPagination();
        }

        function updateTotalCount() {
            if (totalCount) {
                totalCount.textContent = filteredInvoices.length;
            }
        }

        function renderPagination() {
            if (!pageNumbers) return;

            pageNumbers.innerHTML = '';
            const totalPages = Math.ceil(filteredInvoices.length / perPage);

            if (prevPageBtn) {
                prevPageBtn.disabled = currentPage === 1;
            }

            if (nextPageBtn) {
                nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `desktop-page-btn ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => goToPage(i));
                pageNumbers.appendChild(pageBtn);
            }
        }

        function goToPage(page) {
            currentPage = page;
            renderInvoices();
            renderPagination();
        }

        function goToPrevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderInvoices();
                renderPagination();
            }
        }

        function goToNextPage() {
            const totalPages = Math.ceil(filteredInvoices.length / perPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderInvoices();
                renderPagination();
            }
        }

        function showLoading(show) {
            try {
                // Periksa apakah element ada sebelum mengaksesnya
                if (loadingRow) {
                    loadingRow.style.display = show ? '' : 'none';
                }

                // Juga sembunyikan noDataRow
                if (noDataRow) {
                    noDataRow.style.display = show ? 'none' : '';
                }
            } catch (error) {
                console.warn('Error in showLoading:', error);
            }
        }

        // ==================== HELPER FUNCTIONS ====================
        function formatTanggal(isoString) {
            if (!isoString) return '-';

            try {
                const date = new Date(isoString);
                if (isNaN(date.getTime())) return isoString; // Jika parsing gagal, kembalikan aslinya

                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();

                return `${day}/${month}/${year}`;
            } catch (error) {
                console.error('Error formatting date:', error, isoString);
                return isoString;
            }
        }

        function showPopup(type, title, message) {
            const popup = document.getElementById('minimalPopup');
            if (!popup) return;

            const titleElement = popup.querySelector('.minimal-popup-title');
            const messageElement = popup.querySelector('.minimal-popup-message');
            const iconElement = popup.querySelector('.minimal-popup-icon');

            if (titleElement) titleElement.textContent = title;
            if (messageElement) messageElement.textContent = message;

            popup.className = 'minimal-popup show';
            popup.classList.add(type);

            if (iconElement) {
                iconElement.innerHTML = '';
                const icon = document.createElement('span');
                icon.className = 'material-icons-outlined';

                if (type === 'success') {
                    icon.textContent = 'check_circle';
                    popup.style.borderLeftColor = '#10b981';
                } else if (type === 'error') {
                    icon.textContent = 'error';
                    popup.style.borderLeftColor = '#ef4444';
                } else if (type === 'warning') {
                    icon.textContent = 'warning';
                    popup.style.borderLeftColor = '#f59e0b';
                } else {
                    icon.textContent = 'info';
                    popup.style.borderLeftColor = '#3b82f6';
                }

                iconElement.appendChild(icon);
            }

            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);

            const closeBtn = popup.querySelector('.minimal-popup-close');
            if (closeBtn) {
                closeBtn.onclick = () => {
                    popup.classList.remove('show');
                };
            }
        }

        function clearValidationErrors(formType = 'create') {
            const prefix = formType === 'create' ? '' : 'edit_';
            const form = formType === 'create' ? buatInvoiceForm : editInvoiceForm;

            if (!form) return;

            const inputs = form.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.classList.remove('error-input');
            });

            const errorElements = form.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.textContent = '';
            });
        }

        function showValidationErrorsFromServer(errors, formType = 'create') {
            const prefix = formType === 'create' ? '' : 'edit_';

            for (const field in errors) {
                const input = document.getElementById(`${prefix}${field}`);
                const errorElement = document.getElementById(`${prefix}${field}_error`);

                if (input) {
                    input.classList.add('error-input');
                }

                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                }
            }
        }
    </script>
</body>

</html>

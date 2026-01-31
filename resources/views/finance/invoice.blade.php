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
            min-width: 1400px;
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
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        @include('finance/templet/sider')

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Invoice - Finance</h2>

                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span
                            class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput"
                            class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                            placeholder="Cari nama perusahaan, nomor invoice, atau klien..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <div class="relative">
                            <button id="filterBtn"
                                class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
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
                                            <th style="min-width: 200px;">Alamat</th>
                                            <th style="min-width: 200px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Subtotal</th>
                                            <th style="min-width: 100px;">Pajak (%)</th>
                                            <th style="min-width: 120px;">Jumlah Pajak</th>
                                            <th style="min-width: 120px;">Total</th>
                                            <th style="min-width: 150px;">Metode Pembayaran</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
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
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    @method('PUT')
                    <input type="hidden" id="editInvoiceId" name="id">
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
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <form id="deleteInvoiceForm" class="space-y-4">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus invoice untuk <span
                                id="deleteInvoiceNama" class="font-semibold"></span> dengan nomor invoice <span
                                id="deleteInvoiceNomor" class="font-semibold"></span>?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteInvoiceId" name="id">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                    </div>
                </form>
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
        // ==================== CALCULATION FUNCTIONS ====================
        function calculateTotal() {
            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('tax_percentage').value) || 0;

            const taxAmount = subtotal * (taxPercentage / 100);
            const total = subtotal + taxAmount;

            // Pastikan elemen ada sebelum mengatur value
            const taxInput = document.getElementById('tax');
            const totalInput = document.getElementById('total');

            if (taxInput) taxInput.value = Math.round(taxAmount);
            if (totalInput) totalInput.value = Math.round(total);
        }

        function calculateTotalEdit() {
            const subtotal = parseFloat(document.getElementById('editSubtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('editTaxPercentage').value) || 0;

            const taxAmount = subtotal * (taxPercentage / 100);
            const total = subtotal + taxAmount;

            const taxInput = document.getElementById('editTax');
            const totalInput = document.getElementById('editTotal');

            if (taxInput) taxInput.value = Math.round(taxAmount);
            if (totalInput) totalInput.value = Math.round(total);
        }

        // ==================== KONFIGURASI API ====================
        const API_BASE_URL = '/api/invoices';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ==================== GLOBAL VARIABLES ====================
        let allInvoices = [];
        let filteredInvoices = [];
        let currentPage = 1;
        const perPage = 10;

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

        // ==================== HELPER FUNCTIONS ====================
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function showModal(modal) {
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function hideModal(modal) {
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
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

        // ==================== API FUNCTIONS ====================
        async function loadInvoices() {
            console.log('Memuat data dari API...');
            showLoading(true);

            try {
                const searchTerm = document.getElementById('searchInput')?.value || '';
                const params = new URLSearchParams({
                    per_page: perPage,
                    page: currentPage
                });

                if (searchTerm) {
                    params.append('search', searchTerm);
                }

                const url = `/api/invoices?${params.toString()}`;
                console.log('Fetch URL:', url);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'include'
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Data dari API:', data);
                processInvoiceData(data);

            } catch (error) {
                console.error('Error loading invoices:', error);
                showPopup('error', 'Gagal', 'Gagal memuat data: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        async function handleCreateInvoice(e) {
            e.preventDefault();
            console.log('Membuat invoice baru...');

            clearValidationErrors('create');

            // Validasi elemen ada sebelum mengakses value
            const invoiceNoInput = document.getElementById('invoice_no');
            const invoiceDateInput = document.getElementById('invoice_date');
            const companyNameInput = document.getElementById('company_name');
            const companyAddressInput = document.getElementById('company_address');
            const clientNameInput = document.getElementById('client_name');
            const paymentMethodInput = document.getElementById('payment_method');
            const descriptionInput = document.getElementById('description');
            const subtotalInput = document.getElementById('subtotal');
            const taxInput = document.getElementById('tax');
            const totalInput = document.getElementById('total');

            // Cek apakah semua elemen ada
            const requiredElements = [invoiceNoInput, invoiceDateInput, companyNameInput,
                companyAddressInput, clientNameInput, paymentMethodInput,
                subtotalInput, taxInput, totalInput
            ];

            for (const element of requiredElements) {
                if (!element) {
                    console.error('Element not found:', element?.id);
                    showPopup('error', 'Gagal', 'Form tidak lengkap. Silakan refresh halaman.');
                    return;
                }
            }

            const formData = {
                invoice_no: invoiceNoInput.value.trim(),
                invoice_date: invoiceDateInput.value,
                company_name: companyNameInput.value.trim(),
                company_address: companyAddressInput.value.trim(),
                client_name: clientNameInput.value.trim(),
                payment_method: paymentMethodInput.value,
                description: descriptionInput ? descriptionInput.value.trim() : '',
                subtotal: Math.round(parseFloat(subtotalInput.value) || 0),
                tax: Math.round(parseFloat(taxInput.value) || 0),
                total: Math.round(parseFloat(totalInput.value) || 0)
            };

            console.log('Data yang akan dikirim:', formData);

            try {
                const response = await fetch('/api/invoices', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Response dari server:', data);

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        showValidationErrorsFromServer(data.errors, 'create');
                        throw new Error('Validasi gagal');
                    } else {
                        throw new Error(data.message || 'Gagal membuat invoice');
                    }
                }

                console.log('Invoice berhasil dibuat:', data);
                showPopup('success', 'Berhasil', 'Invoice berhasil dibuat');
                hideModal(buatModal);
                buatInvoiceForm.reset();
                loadInvoices();
            } catch (error) {
                console.error('Error creating invoice:', error);
                if (error.message !== 'Validasi gagal') {
                    showPopup('error', 'Gagal', error.message || 'Gagal membuat invoice');
                }
            }
        }

        // ==================== UPDATE INVOICE FUNCTION ====================
        async function handleUpdateInvoice(e) {
            e.preventDefault();
            console.log('Starting update invoice...');

            clearValidationErrors('edit');

            const id = document.getElementById('editInvoiceId').value;
            const subtotal = parseFloat(document.getElementById('editSubtotal').value) || 0;
            const taxPercentage = parseFloat(document.getElementById('editTaxPercentage').value) || 0;
            const taxAmount = Math.round(subtotal * (taxPercentage / 100));
            const total = subtotal + taxAmount;

            const formData = {
                invoice_no: document.getElementById('editInvoiceNo').value.trim(),
                invoice_date: document.getElementById('editInvoiceDate').value,
                company_name: document.getElementById('editCompanyName').value.trim(),
                company_address: document.getElementById('editCompanyAddress').value.trim(),
                client_name: document.getElementById('editClientName').value.trim(),
                payment_method: document.getElementById('editPaymentMethod').value,
                description: document.getElementById('editDescription').value.trim(),
                subtotal: Math.round(subtotal),
                tax: taxAmount,
                tax_percentage: taxPercentage,
                total: Math.round(total)
            };

            console.log('Updating invoice:', id, formData);

            // Validasi client side
            if (!formData.invoice_no || !formData.invoice_date || !formData.company_name ||
                !formData.company_address || !formData.client_name || !formData.payment_method) {
                showPopup('error', 'Validasi Gagal', 'Semua field wajib diisi');
                return;
            }

            try {
                const response = await fetch(`/api/invoices/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Update response:', data);

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        showValidationErrorsFromServer(data.errors, 'edit');
                        showPopup('error', 'Validasi Gagal', 'Periksa kembali data yang dimasukkan');
                        throw new Error('Validasi gagal');
                    } else {
                        throw new Error(data.message || `Gagal mengupdate invoice (${response.status})`);
                    }
                }

                showPopup('success', 'Berhasil', 'Invoice berhasil diperbarui');
                hideModal(document.getElementById('editInvoiceModal'));
                loadInvoices();
            } catch (error) {
                console.error('Error updating invoice:', error);
                if (error.message !== 'Validasi gagal') {
                    showPopup('error', 'Gagal', error.message || 'Gagal mengupdate invoice');
                }
            }
        }

        function editInvoice(id) {
            console.log('Edit invoice:', id);

            // Fetch invoice data
            fetch(`/admin/invoice/${id}?edit=true`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        // Coba endpoint alternatif
                        return fetch(`/admin/invoice/${id}/edit`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                    }
                    return response;
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Invoice data received:', data);

                    let invoice;
                    if (data.success) {
                        invoice = data.data || data.invoice;
                    } else if (data.invoice) {
                        invoice = data.invoice;
                    } else {
                        invoice = data;
                    }

                    if (!invoice) {
                        throw new Error('Data invoice tidak ditemukan');
                    }

                    console.log('Invoice data to edit:', invoice);

                    // Calculate tax percentage from tax amount
                    const subtotal = invoice.subtotal || 0;
                    const taxAmount = invoice.tax || 0;
                    const taxPercentage = subtotal > 0 ? (taxAmount / subtotal * 100) : 0;

                    // Map backend fields to frontend form fields
                    document.getElementById('editInvoiceId').value = invoice.id;
                    document.getElementById('editCompanyName').value = invoice.company_name || invoice
                        .nama_perusahaan || '';
                    document.getElementById('editInvoiceDate').value = invoice.invoice_date ?
                        (invoice.invoice_date.includes('T') ? invoice.invoice_date.split('T')[0] : invoice
                            .invoice_date) : '';
                    document.getElementById('editInvoiceNo').value = invoice.invoice_no || invoice.nomor_order || '';
                    document.getElementById('editClientName').value = invoice.client_name || invoice.nama_klien || '';
                    document.getElementById('editCompanyAddress').value = invoice.company_address || invoice.alamat ||
                        '';
                    document.getElementById('editDescription').value = invoice.description || invoice.deskripsi || '';
                    document.getElementById('editSubtotal').value = subtotal;
                    document.getElementById('editTaxPercentage').value = taxPercentage.toFixed(2);
                    document.getElementById('editTotal').value = invoice.total || 0;
                    document.getElementById('editPaymentMethod').value = invoice.payment_method || invoice
                        .metode_pembayaran || 'Bank Transfer';

                    // Show edit modal
                    showModal(document.getElementById('editInvoiceModal'));
                })
                .catch(error => {
                    console.error('Error loading invoice:', error);

                    // Fallback: Coba ambil dari data yang sudah dimuat
                    const invoice = allInvoices.find(inv => inv.id == id);
                    if (invoice) {
                        console.log('Using cached invoice data:', invoice);

                        // Calculate tax percentage from tax amount
                        const subtotal = invoice.subtotal || 0;
                        const taxAmount = invoice.tax || 0;
                        const taxPercentage = subtotal > 0 ? (taxAmount / subtotal * 100) : 0;

                        // Map backend fields to frontend form fields
                        document.getElementById('editInvoiceId').value = invoice.id;
                        document.getElementById('editCompanyName').value = invoice.company_name || invoice
                            .nama_perusahaan || '';
                        document.getElementById('editInvoiceDate').value = invoice.invoice_date ?
                            (invoice.invoice_date.includes('T') ? invoice.invoice_date.split('T')[0] : invoice
                                .invoice_date) : '';
                        document.getElementById('editInvoiceNo').value = invoice.invoice_no || invoice.nomor_order ||
                            '';
                        document.getElementById('editClientName').value = invoice.client_name || invoice.nama_klien ||
                            '';
                        document.getElementById('editCompanyAddress').value = invoice.company_address || invoice
                            .alamat || '';
                        document.getElementById('editDescription').value = invoice.description || invoice.deskripsi ||
                            '';
                        document.getElementById('editSubtotal').value = subtotal;
                        document.getElementById('editTaxPercentage').value = taxPercentage.toFixed(2);
                        document.getElementById('editTotal').value = invoice.total || 0;
                        document.getElementById('editPaymentMethod').value = invoice.payment_method || invoice
                            .metode_pembayaran || 'Bank Transfer';

                        // Show edit modal
                        showModal(document.getElementById('editInvoiceModal'));
                    } else {
                        showPopup('error', 'Gagal', 'Gagal memuat data invoice. Silakan refresh halaman.');
                    }
                });
        }

        // ==================== DELETE FUNCTIONS ====================
        function confirmDeleteInvoice(id) {
            const invoice = allInvoices.find(inv => inv.id == id);
            if (!invoice) return;

            document.getElementById('deleteInvoiceId').value = id;
            document.getElementById('deleteInvoiceNama').textContent = invoice.company_name || '';
            document.getElementById('deleteInvoiceNomor').textContent = invoice.invoice_no || '';

            showModal(document.getElementById('deleteInvoiceModal'));
        }

        // ==================== PRINT FUNCTIONS ====================
        function viewPrintInvoice(id) {
            const invoice = allInvoices.find(inv => inv.id == id);
            if (invoice) {
                document.getElementById('printInvoiceContent').innerHTML = `
                    <div style="padding: 30px; background: white; max-width: 800px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
                        <div style="border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
                            <h2 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0;">${invoice.company_name || ''}</h2>
                            <p style="margin: 5px 0; color: #666;">Invoice #${invoice.invoice_no || ''}</p>
                            <p style="margin: 5px 0; color: #666;">Tanggal: ${invoice.invoice_date || ''}</p>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Bill To:</h3>
                                <p style="margin: 5px 0;"><strong>Nama Klien:</strong> ${invoice.client_name || ''}</p>
                                <p style="margin: 5px 0;"><strong>Alamat:</strong> ${invoice.company_address || ''}</p>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Payment Details:</h3>
                                <p style="margin: 5px 0;"><strong>Metode Pembayaran:</strong> ${invoice.payment_method || ''}</p>
                            </div>
                        </div>
                        
                        <table style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Deskripsi</th>
                                    <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Subtotal</th>
                                    <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Pajak</th>
                                    <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 12px;">${invoice.description || ''}</td>
                                    <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(invoice.subtotal || 0)}</td>
                                    <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(invoice.tax || 0)}</td>
                                    <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(invoice.total || 0)}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div style="text-align: right; margin-top: 30px;">
                            <table style="width: 300px; margin-left: auto; border-collapse: collapse;">
                                <tr style="font-size: 18px; font-weight: bold;">
                                    <td style="padding: 12px 8px; text-align: right; border-top: 2px solid #333;"><strong>Total:</strong></td>
                                    <td style="padding: 12px 8px; text-align: right; border-top: 2px solid #333;">Rp ${formatNumber(invoice.total || 0)}</td>
                                </tr>
                            </table>
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

        // ==================== UI FUNCTIONS ====================
        function processInvoiceData(data) {
            if (data.success) {
                allInvoices = data.data || [];
                filteredInvoices = [...allInvoices];
                renderInvoices();
                updateTotalCount();

                if (data.pagination) {
                    renderPaginationFromAPI(data.pagination);
                } else {
                    renderPagination();
                }

            } else {
                showPopup('error', 'Gagal', data.message || 'Gagal memuat data');
            }
        }

        function renderInvoices() {
            // Clear existing content
            desktopTableBody.innerHTML = '';
            mobileCards.innerHTML = '';

            if (filteredInvoices.length === 0) {
                noDataRow.classList.remove('hidden');
                return;
            }

            noDataRow.classList.add('hidden');

            // Calculate start and end index for pagination
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
                const tanggal = invoice.invoice_date || invoice.tanggal;
                const subtotal = invoice.subtotal || 0;
                const taxAmount = invoice.tax || 0;
                const total = invoice.total || 0;

                // Hitung persentase pajak
                const taxPercentage = subtotal > 0 ? ((taxAmount / subtotal) * 100) : 0;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${rowNumber}</td>
                    <td>${tanggal}</td>
                    <td>${namaPerusahaan}</td>
                    <td>${nomorOrder}</td>
                    <td>${namaKlien}</td>
                    <td>${alamat}</td>
                    <td>${deskripsi}</td>
                    <td>Rp ${formatNumber(subtotal)}</td>
                    <td>${taxPercentage.toFixed(2)}%</td>
                    <td>Rp ${formatNumber(taxAmount)}</td>
                    <td>Rp ${formatNumber(total)}</td>
                    <td>${metodePembayaran}</td>
                    <td class="text-center">
                        <button onclick="editInvoice(${invoice.id})" class="text-blue-500 hover:text-blue-700 mx-1">Edit</button>
                        <button onclick="confirmDeleteInvoice(${invoice.id})" class="text-red-500 hover:text-red-700 mx-1">Hapus</button>
                        <button onclick="viewPrintInvoice(${invoice.id})" class="text-green-500 hover:text-green-700 mx-1">Print</button>
                    </td>
                `;
                desktopTableBody.appendChild(row);
            });

            // Render mobile cards
            currentPageInvoices.forEach((invoice, index) => {
                const rowNumber = startIndex + index + 1;
                const card = document.createElement('div');
                card.className = 'bg-white border rounded-lg p-4 shadow';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-semibold">${invoice.company_name || ''}</h4>
                            <p class="text-sm text-gray-500">${invoice.invoice_no || ''}</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">#${rowNumber}</span>
                    </div>
                    <p class="text-sm mb-1"><span class="font-medium">Klien:</span> ${invoice.client_name || ''}</p>
                    <p class="text-sm mb-1"><span class="font-medium">Tanggal:</span> ${invoice.invoice_date || ''}</p>
                    <p class="text-sm mb-2"><span class="font-medium">Total:</span> <b>Rp ${formatNumber(invoice.total || 0)}</b></p>
                    <div class="flex justify-between mt-3">
                        <button onclick="editInvoice(${invoice.id})" class="text-blue-500 hover:text-blue-700">Edit</button>
                        <button onclick="confirmDeleteInvoice(${invoice.id})" class="text-red-500 hover:text-red-700">Hapus</button>
                        <button onclick="viewPrintInvoice(${invoice.id})" class="text-green-500 hover:text-green-700">Print</button>
                    </div>
                `;
                mobileCards.appendChild(card);
            });
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

        function renderPaginationFromAPI(pagination) {
            if (!pageNumbers || !pagination) return;

            pageNumbers.innerHTML = '';
            const totalPages = pagination.last_page || Math.ceil(pagination.total / perPage);
            currentPage = pagination.current_page || currentPage;

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
                pageBtn.addEventListener('click', () => {
                    currentPage = i;
                    loadInvoices();
                });
                pageNumbers.appendChild(pageBtn);
            }
        }

        function goToPage(page) {
            currentPage = page;
            loadInvoices();
        }

        function goToPrevPage() {
            if (currentPage > 1) {
                currentPage--;
                loadInvoices();
            }
        }

        function goToNextPage() {
            const totalPages = Math.ceil(filteredInvoices.length / perPage);
            if (currentPage < totalPages) {
                currentPage++;
                loadInvoices();
            }
        }

        function filterInvoices() {
            const searchTerm = searchInput.value.toLowerCase();

            if (searchTerm === '') {
                filteredInvoices = [...allInvoices];
            } else {
                filteredInvoices = allInvoices.filter(invoice => {
                    const companyName = invoice.company_name || '';
                    const invoiceNo = invoice.invoice_no || '';
                    const clientName = invoice.client_name || '';

                    return (
                        companyName.toLowerCase().includes(searchTerm) ||
                        invoiceNo.toLowerCase().includes(searchTerm) ||
                        clientName.toLowerCase().includes(searchTerm)
                    );
                });
            }

            currentPage = 1;
            renderInvoices();
            updateTotalCount();
            renderPagination();
        }

        // ==================== SETUP DELETE MODAL ====================
        function setupDeleteModalEvents() {
            const deleteInvoiceForm = document.getElementById('deleteInvoiceForm');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
            const deleteModal = document.getElementById('deleteInvoiceModal');

            if (deleteInvoiceForm) {
                deleteInvoiceForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const id = document.getElementById('deleteInvoiceId').value;

                    try {
                        const response = await fetch(`/api/invoices/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            showPopup('success', 'Berhasil', 'Invoice berhasil dihapus');
                            hideModal(deleteModal);
                            loadInvoices();
                        } else {
                            showPopup('error', 'Gagal', data.message || 'Gagal menghapus invoice');
                        }
                    } catch (error) {
                        console.error('Error deleting invoice:', error);
                        showPopup('error', 'Gagal', 'Gagal menghapus invoice');
                    }
                });
            }

            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', function() {
                    hideModal(deleteModal);
                });
            }

            if (closeDeleteModalBtn) {
                closeDeleteModalBtn.addEventListener('click', function() {
                    hideModal(deleteModal);
                });
            }
        }

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Finance Invoice Page Loaded');
            console.log('API Base URL:', API_BASE_URL);
            console.log('CSRF Token:', csrfToken);

            // Event untuk tombol Buat Invoice
            if (buatInvoiceBtn) {
                buatInvoiceBtn.addEventListener('click', function() {
                    console.log('Tombol Buat Invoice diklik');
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

            // Event untuk perhitungan otomatis (create form)
            const subtotalInput = document.getElementById('subtotal');
            const taxPercentageInput = document.getElementById('tax_percentage');

            if (subtotalInput && taxPercentageInput) {
                subtotalInput.addEventListener('input', calculateTotal);
                taxPercentageInput.addEventListener('input', calculateTotal);
            }

            // Event untuk perhitungan otomatis (edit form)
            const editSubtotalInput = document.getElementById('editSubtotal');
            const editTaxPercentageInput = document.getElementById('editTaxPercentage');

            if (editSubtotalInput && editTaxPercentageInput) {
                editSubtotalInput.addEventListener('input', calculateTotalEdit);
                editTaxPercentageInput.addEventListener('input', calculateTotalEdit);
            }

            // Hitung total awal jika ada nilai
            calculateTotal();

            // Setup delete modal events
            setupDeleteModalEvents();

            // Load data awal
            loadInvoices();
        });
    </script>
</body>

</html>
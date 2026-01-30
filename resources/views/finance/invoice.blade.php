<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Invoice - Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
            fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log("Response status:", response.status);

                // If response not OK, try to read body for more context
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Server error response body:', text);
                        // If server returned an HTML login page (user not authenticated), redirect to login
                        if (text && text.toLowerCase().includes('<form') && text.toLowerCase().includes('login')) {
                            showMinimalPopup('Sesi Habis', 'Sesi Anda mungkin telah berakhir. Mengalihkan ke halaman login...', 'warning');
                            setTimeout(() => { window.location.href = '/login'; }, 800);
                            throw new Error('Not authenticated - redirecting to login');
                        }
                        throw new Error(`Server error: ${response.status} ${response.statusText} - ${text.slice(0,300)}`);
                    });
                }

                // Ensure JSON content-type; if not, read text and try to give helpful feedback
                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    return response.text().then(text => {
                        // If HTML page likely indicates redirect to dashboard/login, redirect
                        if (text && text.toLowerCase().includes('<form') && text.toLowerCase().includes('login')) {
                            showMinimalPopup('Sesi Habis', 'Sesi Anda mungkin telah berakhir. Mengalihkan ke halaman login...', 'warning');
                            setTimeout(() => { window.location.href = '/login'; }, 800);
                            throw new Error('Not authenticated - redirecting to login');
                        }
                        console.error('Non-JSON response:', text);
                        throw new Error('Server did not return JSON: ' + text.slice(0,500));
                    });
                }

                return response.json();
            })
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
                margin-left: 256px;
                /* Lebar sidebar */
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
            min-width: 1400px;
            /* Fixed minimum width */
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Print styles */
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

        /* Invoice print styles */
        .invoice-header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .invoice-footer {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .invoice-summary {
            text-align: right;
            margin-top: 20px;
        }

        .invoice-summary table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .invoice-summary td {
            padding: 5px;
        }

        /* Scribble effect for prices */
        .scribble {
            position: relative;
            display: inline-block;
        }

        .scribble::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #ef4444;
            transform: rotate(-2deg);
        }
    </style>
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
                        <span
                            class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput"
                            class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                            placeholder="Cari nama perusahaan, nomor order, atau klien..." type="text" />
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
                                            <th style="min-width: 150px;">Nomor Order</th>
                                            <th style="min-width: 150px;">Nama Klien</th>
                                            <th style="min-width: 200px;">Alamat</th>
                                            <th style="min-width: 200px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Harga</th>
                                            <th style="min-width: 120px;">Qty</th>
                                            <th style="min-width: 120px;">Total</th>
                                            <th style="min-width: 120px;">Pajak (%)</th>
                                            <th style="min-width: 150px;">Metode Pembayaran</th>
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
                <!-- Input hidden untuk work_status -->
                <input type="hidden" id="workStatus" name="work_status" value="Pending">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                        <input type="text" id="namaPerusahaan" name="nama_perusahaan"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                        <input type="text" id="nomorOrder" name="nomor_order"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien</label>
                        <input type="text" id="namaKlien" name="nama_klien"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <input type="text" id="alamat" name="alamat"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        required></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                        <input type="number" id="qty" name="qty" min="1" value="1"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%)</label>
                        <input type="number" id="pajak" name="pajak"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode
                        Pembayaran</label>
                    <select id="metodePembayaran" name="metode_pembayaran"
                        class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
                <!-- Tambahkan field kategori di form buat invoice -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="category" name="category"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="">Pilih Kategori</option>
                        <option value="Service">Service</option>
                        <option value="Product">Product</option>
                        <option value="Consultation">Consultation</option>
                        <option value="Other">Other</option>
                    </select>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                            <input type="text" id="editNamaPerusahaan" name="nama_perusahaan"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="editTanggal" name="tanggal"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                            <input type="text" id="editNomorOrder" name="nomor_order"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien</label>
                            <input type="text" id="editNamaKlien" name="nama_klien"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <input type="text" id="editAlamat" name="alamat"
                            class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" name="deskripsi" rows="3"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" name="harga"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                            <input type="number" id="editQty" name="qty" min="1"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%)</label>
                            <input type="number" id="editPajak" name="pajak"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode
                            Pembayaran</label>
                        <select id="editMetodePembayaran" name="metode_pembayaran"
                            class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <!-- Tambahkan field kategori dan work_status di form edit invoice -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="editCategory" name="category"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="">Pilih Kategori</option>
                            <option value="Service">Service</option>
                            <option value="Product">Product</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pekerjaan</label>
                        <select id="editWorkStatus" name="work_status"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                            <option value="">Pilih Status</option>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
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
                                id="deleteInvoiceNama" class="font-semibold"></span> dengan nomor order <span
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
                <div id="printInvoiceContent" class="print-container">
                    <!-- Invoice content will be populated here -->
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closePrintInvoiceModal()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Tutup
                </button>
                <button onclick="printInvoice()"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="material-icons mr-2">print</span>
                    Cetak
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
        // ==================== GLOBAL VARIABLES ====================
        let allInvoices = [];
        let filteredInvoices = [];
        let currentPage = 1;
        const perPage = 10;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');

            // Event untuk tombol Buat Invoice
            if (buatInvoiceBtn) {
                buatInvoiceBtn.addEventListener('click', function() {
                    console.log('Tombol Buat Invoice diklik');
                    showModal(buatModal);
                });
            }

            // Event untuk close modal
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    hideModal(buatModal);
                    buatInvoiceForm.reset();
                    clearValidationErrors();
                });
            }

            // Event untuk cancel button
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    hideModal(buatModal);
                    buatInvoiceForm.reset();
                    clearValidationErrors();
                });
            }

            // Event untuk form submit buat invoice
            if (buatInvoiceForm) {
                buatInvoiceForm.addEventListener('submit', handleCreateInvoice);
            }

            // Event untuk form submit edit invoice
            const editInvoiceForm = document.getElementById('editInvoiceForm');
            if (editInvoiceForm) {
                editInvoiceForm.addEventListener('submit', handleEditInvoice);
            }

            // Event untuk close edit modal
            const closeEditModalBtn = document.getElementById('closeEditModalBtn');
            if (closeEditModalBtn) {
                closeEditModalBtn.addEventListener('click', function() {
                    hideModal(document.getElementById('editInvoiceModal'));
                });
            }

            // Event untuk cancel edit button
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            if (cancelEditBtn) {
                cancelEditBtn.addEventListener('click', function() {
                    hideModal(document.getElementById('editInvoiceModal'));
                });
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

            // Load data awal
            loadInvoices();
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

        // ==================== INVOICE FUNCTIONS ====================
        function loadInvoices() {
            console.log('Memuat data invoice...');
            showLoading(true);

            fetch('/admin/invoice', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data diterima:', data);
                    allInvoices = data.data || [];
                    filteredInvoices = [...allInvoices];
                    renderInvoices();
                    updateTotalCount();
                    renderPagination();
                    showLoading(false);
                })
                .catch(error => {
                    console.error('Error loading invoices:', error);
                    showPopup('error', 'Gagal', 'Gagal memuat data invoice');
                    showLoading(false);
                });
        }

      function handleCreateInvoice(e) {
    e.preventDefault();
    console.log('Membuat invoice baru...');

    // Clear previous validation errors
    clearValidationErrors();

    // Collect form data
    const namaPerusahaan = document.getElementById('namaPerusahaan').value.trim();
    const tanggal = document.getElementById('tanggal').value;
    const nomorOrder = document.getElementById('nomorOrder').value.trim();
    const namaKlien = document.getElementById('namaKlien').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const deskripsi = document.getElementById('deskripsi').value.trim();
    const harga = parseFloat(document.getElementById('harga').value) || 0;
    const qty = parseInt(document.getElementById('qty').value) || 0;
    const pajakPersen = parseFloat(document.getElementById('pajak').value) || 0;
    const metodePembayaran = document.getElementById('metodePembayaran').value;
    const category = document.getElementById('category').value;
    const workStatus = document.getElementById('workStatus').value; // Ambil dari input hidden

    // Calculate additional fields needed by backend
    const subtotal = harga * qty;
    const taxAmount = subtotal * (pajakPersen / 100);
    const total = subtotal + taxAmount;

    // Prepare data in format expected by backend
    const formData = {
        invoice_no: nomorOrder,
        invoice_date: tanggal,
        company_name: namaPerusahaan,
        company_address: alamat,
        client_name: namaKlien,
        payment_method: metodePembayaran,
        category: category,
        work_status: workStatus, // Pastikan ini dikirim
        description: deskripsi,
        subtotal: subtotal,
        tax: taxAmount,
        total: total
    };

    console.log('Data yang akan dikirim:', formData);



            // Client-side validation
            const errors = validateInvoiceData(formData);
            if (errors.length > 0) {
                showValidationErrors(errors);
                return;
            }

            // Send data to server
            fetch('/admin/invoice', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        // Handle validation errors from server
                        if (response.status === 422 && data.errors) {
                            throw new Error(formatServerErrors(data.errors));
                        } else {
                            throw new Error(data.message || 'Gagal membuat invoice');
                        }
                    }

                    return data;
                })
                .then(data => {
                    console.log('Invoice berhasil dibuat:', data);
                    showPopup('success', 'Berhasil', 'Invoice berhasil dibuat');
                    hideModal(buatModal);
                    buatInvoiceForm.reset();
                    clearValidationErrors();
                    loadInvoices(); // Reload data
                })
                .catch(error => {
                    console.error('Error creating invoice:', error);
                    showPopup('error', 'Gagal', error.message || 'Gagal membuat invoice');
                });
        }

        function handleEditInvoice(e) {
            e.preventDefault();
            console.log('Mengedit invoice...');

            const id = document.getElementById('editInvoiceId').value;
            
            // Collect form data
            const namaPerusahaan = document.getElementById('editNamaPerusahaan').value.trim();
            const tanggal = document.getElementById('editTanggal').value;
            const nomorOrder = document.getElementById('editNomorOrder').value.trim();
            const namaKlien = document.getElementById('editNamaKlien').value.trim();
            const alamat = document.getElementById('editAlamat').value.trim();
            const deskripsi = document.getElementById('editDeskripsi').value.trim();
            const harga = parseFloat(document.getElementById('editHarga').value) || 0;
            const qty = parseInt(document.getElementById('editQty').value) || 0;
            const pajakPersen = parseFloat(document.getElementById('editPajak').value) || 0;
            const metodePembayaran = document.getElementById('editMetodePembayaran').value;
            const category = document.getElementById('editCategory').value;
            const workStatus = document.getElementById('editWorkStatus').value;

            // Calculate additional fields needed by backend
            const subtotal = harga * qty;
            const taxAmount = subtotal * (pajakPersen / 100);
            const total = subtotal + taxAmount;

            // Prepare data in format expected by backend
            const formData = {
                invoice_no: nomorOrder,
                invoice_date: tanggal,
                company_name: namaPerusahaan,
                company_address: alamat,
                client_name: namaKlien,
                payment_method: metodePembayaran,
                category: category,
                work_status: workStatus,
                description: deskripsi,
                subtotal: subtotal,
                tax: taxAmount,
                total: total,
                _method: 'PUT' // Laravel method spoofing
            };

            console.log('Data yang akan dikirim untuk edit:', formData);

            // Send data to server
            fetch(`/admin/invoice/${id}`, {
                    method: 'POST', // Using POST with _method=PUT
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        // Handle validation errors from server
                        if (response.status === 422 && data.errors) {
                            throw new Error(formatServerErrors(data.errors));
                        } else {
                            throw new Error(data.message || 'Gagal mengupdate invoice');
                        }
                    }

                    return data;
                })
                .then(data => {
                    console.log('Invoice berhasil diupdate:', data);
                    showPopup('success', 'Berhasil', 'Invoice berhasil diupdate');
                    hideModal(document.getElementById('editInvoiceModal'));
                    loadInvoices(); // Reload data
                })
                .catch(error => {
                    console.error('Error updating invoice:', error);
                    showPopup('error', 'Gagal', error.message || 'Gagal mengupdate invoice');
                });
        }

        function validateInvoiceData(data) {
            const errors = [];

            // Check required fields based on backend expectations
            const requiredFields = [{
                    field: 'invoice_no',
                    label: 'Nomor Invoice'
                },
                {
                    field: 'invoice_date',
                    label: 'Tanggal Invoice'
                },
                {
                    field: 'company_name',
                    label: 'Nama Perusahaan'
                },
                {
                    field: 'company_address',
                    label: 'Alamat Perusahaan'
                },
                {
                    field: 'client_name',
                    label: 'Nama Klien'
                },
                {
                    field: 'payment_method',
                    label: 'Metode Pembayaran'
                },
                {
                    field: 'category',
                    label: 'Kategori'
                },
                {
                    field: 'description',
                    label: 'Deskripsi'
                }
            ];

            requiredFields.forEach(({
                field,
                label
            }) => {
                if (!data[field] || data[field].toString().trim() === '') {
                    errors.push(`${label} harus diisi`);
                }
            });

            // Check numeric fields
            if (!data.subtotal || isNaN(data.subtotal) || parseFloat(data.subtotal) <= 0) {
                errors.push('Subtotal harus berupa angka yang valid dan lebih dari 0');
            }

            if (data.tax === undefined || data.tax === null || isNaN(data.tax) || parseFloat(data.tax) < 0) {
                errors.push('Tax harus berupa angka yang valid');
            }

            if (!data.total || isNaN(data.total) || parseFloat(data.total) <= 0) {
                errors.push('Total harus berupa angka yang valid dan lebih dari 0');
            }

            return errors;
        }

        function formatServerErrors(errors) {
            if (typeof errors === 'object') {
                return Object.values(errors).flat().join(', ');
            }
            return String(errors);
        }

        function showValidationErrors(errors) {
            errors.forEach(error => {
                showPopup('error', 'Validasi Error', error);
            });
        }

        function clearValidationErrors() {
            // Remove any existing error styles
            const formInputs = buatInvoiceForm.querySelectorAll('input, select, textarea');
            formInputs.forEach(input => {
                input.classList.remove('border-red-500');
                const errorElement = input.nextElementSibling;
                if (errorElement && errorElement.classList.contains('text-red-500')) {
                    errorElement.remove();
                }
            });
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

                // Map backend field names to frontend display names
                const namaPerusahaan = invoice.company_name || invoice.nama_perusahaan;
                const nomorOrder = invoice.invoice_no || invoice.nomor_order;
                const namaKlien = invoice.client_name || invoice.nama_klien;
                const alamat = invoice.company_address || invoice.alamat;
                const deskripsi = invoice.description || invoice.deskripsi;
                const metodePembayaran = invoice.payment_method || invoice.metode_pembayaran;
                const tanggal = invoice.invoice_date || invoice.tanggal;
                const pajakPersen = invoice.tax_percentage || invoice.pajak;

                // Calculate total if not provided by backend
                const harga = invoice.harga || invoice.subtotal / (invoice.qty || 1);
                const qty = invoice.qty || 1;
                const pajak = invoice.tax || (harga * qty * (pajakPersen || 0) / 100);
                const total = invoice.total || ((harga * qty) + pajak);

                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${rowNumber}</td>
            <td>${tanggal}</td>
            <td>${namaPerusahaan}</td>
            <td>${nomorOrder}</td>
            <td>${namaKlien}</td>
            <td>${alamat}</td>
            <td>${deskripsi}</td>
            <td>Rp ${formatNumber(harga)}</td>
            <td>${qty}</td>
            <td>Rp ${formatNumber(total)}</td>
            <td>${pajakPersen || (pajak/(harga * qty) * 100).toFixed(2)}%</td>
            <td>${metodePembayaran}</td>
            <td class="text-center">
                <button onclick="editInvoice(${invoice.id})" class="text-blue-500 hover:text-blue-700 mx-1">Edit</button>
                <button onclick="deleteInvoice(${invoice.id})" class="text-red-500 hover:text-red-700 mx-1">Hapus</button>
                <button onclick="printInvoiceModal(${invoice.id})" class="text-green-500 hover:text-green-700 mx-1">Print</button>
            </td>
        `;
                desktopTableBody.appendChild(row);
            });

            // Render mobile cards
            currentPageInvoices.forEach((invoice, index) => {
                const rowNumber = startIndex + index + 1;

                // Map backend field names to frontend display names
                const namaPerusahaan = invoice.company_name || invoice.nama_perusahaan;
                const nomorOrder = invoice.invoice_no || invoice.nomor_order;
                const namaKlien = invoice.client_name || invoice.nama_klien;
                const tanggal = invoice.invoice_date || invoice.tanggal;
                const total = invoice.total || 0;

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
            <p class="text-sm mb-1"><span class="font-medium">Tanggal:</span> ${tanggal}</p>
            <p class="text-sm mb-2"><span class="font-medium">Total:</span> <b>Rp ${formatNumber(total)}</b></p>
            <div class="flex justify-between mt-3">
                <button onclick="editInvoice(${invoice.id})" class="text-blue-500 hover:text-blue-700">Edit</button>
                <button onclick="deleteInvoice(${invoice.id})" class="text-red-500 hover:text-red-700">Hapus</button>
                <button onclick="printInvoiceModal(${invoice.id})" class="text-green-500 hover:text-green-700">Print</button>
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
                    // Check both old and new field names
                    const companyName = invoice.company_name || invoice.nama_perusahaan || '';
                    const invoiceNo = invoice.invoice_no || invoice.nomor_order || '';
                    const clientName = invoice.client_name || invoice.nama_klien || '';

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

        function updateTotalCount() {
            if (totalCount) {
                totalCount.textContent = filteredInvoices.length;
            }
        }

        function renderPagination() {
            if (!pageNumbers) return;

            pageNumbers.innerHTML = '';
            const totalPages = Math.ceil(filteredInvoices.length / perPage);

            // Update button states
            if (prevPageBtn) {
                prevPageBtn.disabled = currentPage === 1;
            }

            if (nextPageBtn) {
                nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
            }

            // Create page number buttons
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
            if (loadingRow) {
                loadingRow.style.display = show ? '' : 'none';
            }
        }

        // ==================== CRUD OPERATIONS ====================
        function editInvoice(id) {
            console.log('Edit invoice:', id);

            // Fetch invoice data
            fetch(`/admin/invoice/${id}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const invoice = data.invoice;

                        // Map backend fields to frontend form fields
                        document.getElementById('editInvoiceId').value = invoice.id;
                        document.getElementById('editNamaPerusahaan').value = invoice.company_name || invoice
                            .nama_perusahaan || '';
                        document.getElementById('editTanggal').value = invoice.invoice_date || invoice.tanggal || '';
                        document.getElementById('editNomorOrder').value = invoice.invoice_no || invoice.nomor_order ||
                            '';
                        document.getElementById('editNamaKlien').value = invoice.client_name || invoice.nama_klien ||
                        '';
                        document.getElementById('editAlamat').value = invoice.company_address || invoice.alamat || '';
                        document.getElementById('editDeskripsi').value = invoice.description || invoice.deskripsi || '';

                        // Calculate harga and qty from subtotal if needed
                        const harga = invoice.harga || invoice.subtotal / (invoice.qty || 1);
                        const qty = invoice.qty || 1;

                        document.getElementById('editHarga').value = harga;
                        document.getElementById('editQty').value = qty;

                        // Calculate tax percentage from tax amount
                        const taxPercentage = invoice.tax_percentage || invoice.pajak ||
                            (invoice.tax ? (invoice.tax / invoice.subtotal * 100) : 0);
                        document.getElementById('editPajak').value = taxPercentage;

                        document.getElementById('editMetodePembayaran').value = invoice.payment_method || invoice
                            .metode_pembayaran || '';
                        
                        document.getElementById('editCategory').value = invoice.category || '';
                        document.getElementById('editWorkStatus').value = invoice.work_status || 'Pending';

                        // Show edit modal
                        showModal(document.getElementById('editInvoiceModal'));
                    } else {
                        showPopup('error', 'Gagal', 'Gagal memuat data invoice');
                    }
                })
                .catch(error => {
                    console.error('Error loading invoice:', error);
                    showPopup('error', 'Gagal', 'Gagal memuat data invoice');
                });
        }

        function deleteInvoice(id) {
            if (confirm('Apakah Anda yakin ingin menghapus invoice ini?')) {
                console.log('Delete invoice:', id);

                fetch(`/admin/invoice/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showPopup('success', 'Berhasil', 'Invoice berhasil dihapus');
                            loadInvoices(); // Reload data
                        } else {
                            showPopup('error', 'Gagal', data.message || 'Gagal menghapus invoice');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting invoice:', error);
                        showPopup('error', 'Gagal', 'Gagal menghapus invoice');
                    });
            }
        }

        function printInvoiceModal(id) {
            console.log('Print invoice modal:', id);

            fetch(`/admin/invoice/${id}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const invoice = data.invoice;

                        // Map backend fields for display
                        const namaPerusahaan = invoice.company_name || invoice.nama_perusahaan;
                        const nomorOrder = invoice.invoice_no || invoice.nomor_order;
                        const tanggal = invoice.invoice_date || invoice.tanggal;
                        const namaKlien = invoice.client_name || invoice.nama_klien;
                        const alamat = invoice.company_address || invoice.alamat;
                        const deskripsi = invoice.description || invoice.deskripsi;
                        const metodePembayaran = invoice.payment_method || invoice.metode_pembayaran;
                        const subtotal = invoice.subtotal || 0;
                        const taxAmount = invoice.tax || 0;
                        const total = invoice.total || 0;
                        const taxPercentage = invoice.tax_percentage || invoice.pajak ||
                            (taxAmount ? (taxAmount / subtotal * 100) : 0);

                        document.getElementById('printInvoiceContent').innerHTML = `
                <div style="padding: 30px; background: white; max-width: 800px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
                    <div style="border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
                        <h2 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0;">${namaPerusahaan}</h2>
                        <p style="margin: 5px 0; color: #666;">Invoice #${nomorOrder}</p>
                        <p style="margin: 5px 0; color: #666;">Tanggal: ${tanggal}</p>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px;">
                        <div>
                            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Bill To:</h3>
                            <p style="margin: 5px 0;"><strong>Nama Klien:</strong> ${namaKlien}</p>
                            <p style="margin: 5px 0;"><strong>Alamat:</strong> ${alamat}</p>
                        </div>
                        <div>
                            <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">Payment Details:</h3>
                            <p style="margin: 5px 0;"><strong>Metode Pembayaran:</strong> ${metodePembayaran}</p>
                        </div>
                    </div>
                    
                    <table style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Deskripsi</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Subtotal</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Pajak (${taxPercentage.toFixed(2)}%)</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 12px;">${deskripsi}</td>
                                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(subtotal)}</td>
                                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(taxAmount)}</td>
                                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;">Rp ${formatNumber(total)}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div style="text-align: right; margin-top: 30px;">
                        <table style="width: 300px; margin-left: auto; border-collapse: collapse;">
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
                        showPopup('error', 'Gagal', 'Gagal memuat data invoice');
                    }
                })
                .catch(error => {
                    console.error('Error loading invoice:', error);
                    showPopup('error', 'Gagal', 'Gagal memuat data invoice');
                });
        }

        // ==================== HELPER FUNCTIONS ====================
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function showPopup(type, title, message) {
            const popup = document.getElementById('minimalPopup');
            if (!popup) return;

            // Update popup content
            const titleElement = popup.querySelector('.minimal-popup-title');
            const messageElement = popup.querySelector('.minimal-popup-message');
            const iconElement = popup.querySelector('.minimal-popup-icon');

            if (titleElement) titleElement.textContent = title;
            if (messageElement) messageElement.textContent = message;

            // Update styles based on type
            popup.className = 'minimal-popup show';
            popup.classList.add(type);

            // Update icon
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

            // Auto hide after 3 seconds
            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);

            // Close button functionality
            const closeBtn = popup.querySelector('.minimal-popup-close');
            if (closeBtn) {
                closeBtn.onclick = () => {
                    popup.classList.remove('show');
                };
            }
        }

        // ==================== FILTER FUNCTIONALITY ====================
        // Filter dropdown toggle
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.getElementById('filterDropdown');

        if (filterBtn && filterDropdown) {
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

            // Apply filter button
            const applyFilterBtn = document.getElementById('applyFilter');
            if (applyFilterBtn) {
                applyFilterBtn.addEventListener('click', function() {
                    // Get selected statuses
                    const selectedStatuses = [];
                    const checkboxes = filterDropdown.querySelectorAll('input[type="checkbox"]:checked');

                    checkboxes.forEach(checkbox => {
                        if (checkbox.value !== 'all') {
                            selectedStatuses.push(checkbox.value);
                        }
                    });

                    console.log('Selected statuses:', selectedStatuses);
                    // Implement filter logic here
                    filterDropdown.classList.remove('show');
                });
            }

            // Reset filter button
            const resetFilterBtn = document.getElementById('resetFilter');
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function() {
                    const checkboxes = filterDropdown.querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = checkbox.value === 'all';
                    });
                    filterDropdown.classList.remove('show');
                });
            }
        }

        // ==================== PRINT FUNCTIONALITY ====================
        function closePrintInvoiceModal() {
            document.getElementById('printInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function printInvoice() {
            window.print();
        }
    </script>
</body>

</html>
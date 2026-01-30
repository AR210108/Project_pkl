gi<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Keuangan</title>
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
                        "danger": "#ef4444",
                        "income": "#10b981",
                        "expense": "#ef4444"
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
        /* ... (Style CSS Tetap Sama) ... */
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

        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .finance-table {
            transition: all 0.2s ease;
        }

        .finance-table tr:hover {
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

        .toggle-btn {
            background-color: #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .toggle-btn:hover {
            background-color: #e2e8f0;
        }

        .toggle-btn.active {
            background-color: #3b82f6;
            color: white;
        }

        .toggle-btn.income.active {
            background-color: #10b981;
        }

        .toggle-btn.expense.active {
            background-color: #ef4444;
        }

        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .type-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .type-income {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .type-expense {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .category-salary {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }

        .category-project {
            background-color: rgba(139, 92, 246, 0.15);
            color: #5b21b6;
        }

        .category-investment {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .category-office {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }

        .category-marketing {
            background-color: rgba(236, 72, 153, 0.15);
            color: #9f1239;
        }

        .category-utilities {
            background-color: rgba(14, 165, 233, 0.15);
            color: #0c4a6e;
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

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

        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
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

        .hidden-by-filter {
            display: none !important;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-card-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
        }

        .stat-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .stat-card-value {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card-change {
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-card-change.positive {
            color: #10b981;
        }

        .stat-card-change.negative {
            color: #ef4444;
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
                <header class="mb-4 sm:mb-8">
                    <h1 class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">Data Keuangan</h1>
                </header>

                <!-- Stat Cards (Bisa dibuat dinamis via controller jika ingin, saat ini statis) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3 class="stat-card-title">Total Pemasukan</h3>
                            <div class="stat-card-icon bg-green-100">
                                <span class="material-icons-outlined text-green-600">trending_up</span>
                            </div>
                        </div>
                        <div class="stat-card-value text-green-600">Rp 45.750.000</div>
                        <div class="stat-card-change positive">
                            <span class="material-icons-outlined text-sm">arrow_upward</span>
                            <span>12% dari bulan lalu</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3 class="stat-card-title">Total Pengeluaran</h3>
                            <div class="stat-card-icon bg-red-100">
                                <span class="material-icons-outlined text-red-600">trending_down</span>
                            </div>
                        </div>
                        <div class="stat-card-value text-red-600">Rp 28.350.000</div>
                        <div class="stat-card-change negative">
                            <span class="material-icons-outlined text-sm">arrow_upward</span>
                            <span>8% dari bulan lalu</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3 class="stat-card-title">Saldo Bersih</h3>
                            <div class="stat-card-icon bg-blue-100">
                                <span class="material-icons-outlined text-blue-600">account_balance</span>
                            </div>
                        </div>
                        <div class="stat-card-value text-blue-600">Rp 17.400.000</div>
                        <div class="stat-card-change positive">
                            <span class="material-icons-outlined text-sm">arrow_upward</span>
                            <span>18% dari bulan lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Buttons and Search Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="flex flex-wrap gap-2">
                        <button id="toggleAll" class="toggle-btn active px-4 py-2 rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined">all_inclusive</span>
                            <span>Semua</span>
                        </button>
                        <button id="toggleIncome"
                            class="toggle-btn income px-4 py-2 rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined">arrow_downward</span>
                            <span>Pemasukan</span>
                        </button>
                        <button id="toggleExpense"
                            class="toggle-btn expense px-4 py-2 rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined">arrow_upward</span>
                            <span>Pengeluaran</span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <div class="relative w-full md:w-1/3">
                            <span
                                class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input id="finance-search"
                                class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                                placeholder="Cari nama, kategori, atau deskripsi..." type="text" />
                        </div>
                        <div class="relative">
                            <button id="filterBtn"
                                class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <span class="material-icons-outlined text-sm">filter_list</span>
                                Filter
                            </button>
                            <div id="filterDropdown" class="filter-dropdown">
                                <div class="filter-option">
                                    <input type="checkbox" id="filterAll" value="all" checked>
                                    <label for="filterAll">Semua Kategori</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterSalary" value="salary">
                                    <label for="filterSalary">Gaji</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterProject" value="project">
                                    <label for="filterProject">Proyek</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterInvestment" value="investment">
                                    <label for="filterInvestment">Investasi</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterOffice" value="office">
                                    <label for="filterOffice">Kantor</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterMarketing" value="marketing">
                                    <label for="filterMarketing">Pemasaran</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterUtilities" value="utilities">
                                    <label for="filterUtilities">Utilitas</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                        <button onclick="openAddModal()"
                            class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Tambah Transaksi</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>

                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">account_balance_wallet</span>
                            Data Keuangan
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount"
                                    class="font-semibold text-text-light">0</span> transaksi</span>
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
                                            <th style="min-width: 150px;">Tanggal</th>
                                            <th style="min-width: 200px;">Nama</th>
                                            <th style="min-width: 150px;">Kategori</th>
                                            <th style="min-width: 300px;">Deskripsi</th>
                                            <th style="min-width: 150px;">Jumlah</th>
                                            <th style="min-width: 120px;">Tipe</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="finance-table-body">
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
                        <div id="finance-pagination" class="desktop-pagination">
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

    <!-- Modal Tambah Data Keuangan -->
    <!-- FORM DIPERBAIKI: Menambahkan action, method, csrf, dan name attributes -->
    <div id="addModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Data Keuangan</h3>
                    <button onclick="closeAddModal()" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <!-- Form Action ke /pemasukan dengan method POST -->
                <form action="/pemasukan" method="POST" class="space-y-4">
                    @csrf <!-- Token Keamanan Laravel -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Input No (Otomatis di DB, bisa dihidden atau opsional) -->
                        <div style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1">No</label>
                            <input type="text" name="no" value="Auto">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Transaksi</label>
                            <select name="tipe" id="transaction-type"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                onchange="updateCategoryOptions()" required>
                                <option value="">Pilih Tipe</option>
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" id="transaction-category"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="nama"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Nama Transaksi" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <!-- Type number untuk validasi input angka -->
                            <input type="number" name="jumlah"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Contoh: 1500000" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                rows="3" placeholder="Deskripsi Transaksi"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeAddModal()"
                            class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div id="transactionDetailModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Transaksi</h3>
                    <button onclick="closeTransactionDetailModal()" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>

                <!-- Transaction Detail Content -->
                <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Informasi
                                Transaksi</h5>
                            <div class="space-y-2">
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nomor:</span>
                                    <span id="detail-no" class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Tanggal:</span>
                                    <span id="detail-date"
                                        class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Tipe:</span>
                                    <span id="detail-type"
                                        class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Kategori:</span>
                                    <span id="detail-category"
                                        class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Detail
                                Pembayaran</h5>
                            <div class="space-y-2">
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nama:</span>
                                    <span id="detail-name"
                                        class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Jumlah:</span>
                                    <span id="detail-amount"
                                        class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row">
                                    <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Metode:</span>
                                    <span id="detail-method"
                                        class="text-xs sm:text-sm text-text-light font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6">
                        <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Deskripsi</h5>
                        <div class="bg-white rounded-lg p-3 sm:p-4">
                            <p id="detail-description" class="text-xs sm:text-sm text-text-light"></p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row justify-center sm:justify-end gap-2 sm:gap-3 mt-4 sm:mt-6">
                    <button onclick="printTransaction()"
                        class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                        <span class="material-icons-outlined">print</span>
                        <span>Cetak</span>
                    </button>
                    <button onclick="downloadTransaction()"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                        <span class="material-icons-outlined">download</span>
                        <span>Download</span>
                    </button>
                    <button onclick="closeTransactionDetailModal()"
                        class="px-4 py-2 btn-secondary rounded-lg flex items-center gap-2">
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
        // 1. GANTI DATA STATIS DENGAN DATA DARI CONTROLLER
        // Data keuangan diambil dari variable $financeData yang dikirim Controller
        const financeData = @json($financeData ?? []);

        // Kategori berdasarkan tipe
        const categoriesByType = {
            income: [
                { value: "salary", label: "Gaji" },
                { value: "project", label: "Proyek" },
                { value: "investment", label: "Investasi" }
            ],
            expense: [
                { value: "office", label: "Kantor" },
                { value: "marketing", label: "Pemasaran" },
                { value: "utilities", label: "Utilitas" }
            ]
        };

        // Pagination variables
        let financeCurrentPage = 1;
        const financeItemsPerPage = 5;
        let financeFilteredData = [...financeData];
        let activeFilters = ['all'];
        let activeType = 'all'; // 'all', 'income', or 'expense'
        let searchTerm = '';

        // Inisialisasi toggle buttons
        function initializeToggleButtons() {
            const toggleAll = document.getElementById('toggleAll');
            const toggleIncome = document.getElementById('toggleIncome');
            const toggleExpense = document.getElementById('toggleExpense');

            toggleAll.addEventListener('click', function () {
                activeType = 'all';
                updateToggleButtons();
                applyFilters();
            });

            toggleIncome.addEventListener('click', function () {
                activeType = 'income';
                updateToggleButtons();
                applyFilters();
            });

            toggleExpense.addEventListener('click', function () {
                activeType = 'expense';
                updateToggleButtons();
                applyFilters();
            });
        }

        function updateToggleButtons() {
            const toggleAll = document.getElementById('toggleAll');
            const toggleIncome = document.getElementById('toggleIncome');
            const toggleExpense = document.getElementById('toggleExpense');

            // Reset all buttons
            toggleAll.classList.remove('active');
            toggleIncome.classList.remove('active');
            toggleExpense.classList.remove('active');

            // Set active button
            if (activeType === 'all') {
                toggleAll.classList.add('active');
            } else if (activeType === 'income') {
                toggleIncome.classList.add('active');
            } else if (activeType === 'expense') {
                toggleExpense.classList.add('active');
            }
        }

        // Inisialisasi filter
        function initializeFilter() {
            const filterBtn = document.getElementById('filterBtn');
            const filterDropdown = document.getElementById('filterDropdown');
            const applyFilterBtn = document.getElementById('applyFilter');
            const resetFilterBtn = document.getElementById('resetFilter');
            const filterAll = document.getElementById('filterAll');

            // Toggle filter dropdown
            filterBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                filterDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function () {
                filterDropdown.classList.remove('show');
            });

            // Prevent dropdown from closing when clicking inside
            filterDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // Handle "All" checkbox
            filterAll.addEventListener('change', function () {
                if (this.checked) {
                    // Uncheck all other checkboxes
                    document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)').forEach(cb => {
                        cb.checked = false;
                    });
                }
            });

            // Handle other checkboxes
            document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)').forEach(cb => {
                cb.addEventListener('change', function () {
                    if (this.checked) {
                        // Uncheck "All" checkbox
                        filterAll.checked = false;
                    }
                });
            });

            // Apply filter
            applyFilterBtn.addEventListener('click', function () {
                const filterAll = document.getElementById('filterAll');
                const filterSalary = document.getElementById('filterSalary');
                const filterProject = document.getElementById('filterProject');
                const filterInvestment = document.getElementById('filterInvestment');
                const filterOffice = document.getElementById('filterOffice');
                const filterMarketing = document.getElementById('filterMarketing');
                const filterUtilities = document.getElementById('filterUtilities');

                activeFilters = [];
                if (filterAll.checked) {
                    activeFilters.push('all');
                } else {
                    if (filterSalary.checked) activeFilters.push('salary');
                    if (filterProject.checked) activeFilters.push('project');
                    if (filterInvestment.checked) activeFilters.push('investment');
                    if (filterOffice.checked) activeFilters.push('office');
                    if (filterMarketing.checked) activeFilters.push('marketing');
                    if (filterUtilities.checked) activeFilters.push('utilities');
                }

                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} transaksi`, 'success');
            });

            // Reset filter
            resetFilterBtn.addEventListener('click', function () {
                document.getElementById('filterAll').checked = true;
                document.getElementById('filterSalary').checked = false;
                document.getElementById('filterProject').checked = false;
                document.getElementById('filterInvestment').checked = false;
                document.getElementById('filterOffice').checked = false;
                document.getElementById('filterMarketing').checked = false;
                document.getElementById('filterUtilities').checked = false;
                activeFilters = ['all'];
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Direset', 'Menampilkan semua transaksi', 'success');
            });
        }

        function getFilteredRows() {
            return financeFilteredData.filter(row => !row.hiddenByFilter);
        }

        function applyFilters() {
            // Reset to first page
            financeCurrentPage = 1;

            // Apply filters
            financeFilteredData = financeData.filter(item => {
                // Check if type matches filter
                let typeMatches = false;
                if (activeType === 'all') {
                    typeMatches = true;
                } else {
                    typeMatches = item.tipe === activeType;
                }

                // Check if category matches filter
                let categoryMatches = false;
                if (activeFilters.includes('all')) {
                    categoryMatches = true;
                } else {
                    categoryMatches = activeFilters.some(filter => item.kategori.includes(filter.toLowerCase()));
                }

                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = item.nama.toLowerCase().includes(searchLower) ||
                        item.deskripsi.toLowerCase().includes(searchLower) ||
                        item.kategori.toLowerCase().includes(searchLower) ||
                        item.tipe.toLowerCase().includes(searchLower);
                }

                return typeMatches && categoryMatches && searchMatches;
            });

            // Update pagination and visible items
            renderFinanceTable();
            renderFinancePagination();
        }

        // Update category options based on selected type
        function updateCategoryOptions() {
            const typeSelect = document.getElementById('transaction-type');
            const categorySelect = document.getElementById('transaction-category');
            const selectedType = typeSelect.value;

            // Clear current options
            categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';

            // Add options based on selected type
            if (selectedType && categoriesByType[selectedType]) {
                categoriesByType[selectedType].forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.value;
                    option.textContent = category.label;
                    categorySelect.appendChild(option);
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

        // Transaction detail modal functions
        function openTransactionDetailModal(transactionNo) {
            const transaction = financeData.find(t => t.no === transactionNo);
            if (!transaction) {
                showMinimalPopup('Error', 'Data transaksi tidak tersedia', 'error');
                return;
            }

            // Fill modal with data
            document.getElementById('detail-no').textContent = `#${transaction.no}`;
            document.getElementById('detail-date').textContent = transaction.tanggal;
            document.getElementById('detail-name').textContent = transaction.nama;
            document.getElementById('detail-amount').textContent = transaction.jumlah;
            document.getElementById('detail-description').textContent = transaction.deskripsi;
            document.getElementById('detail-method').textContent = "Transfer Bank";

            // Determine type badge
            let typeBadge = '';
            if (transaction.tipe === 'income') {
                typeBadge = '<span class="type-badge type-income">Pemasukan</span>';
            } else {
                typeBadge = '<span class="type-badge type-expense">Pengeluaran</span>';
            }
            document.getElementById('detail-type').innerHTML = typeBadge;

            // Determine category badge
            let categoryBadge = '';
            switch (transaction.kategori) {
                case 'salary':
                    categoryBadge = '<span class="category-badge category-salary">Gaji</span>';
                    break;
                case 'project':
                    categoryBadge = '<span class="category-badge category-project">Proyek</span>';
                    break;
                case 'investment':
                    categoryBadge = '<span class="category-badge category-investment">Investasi</span>';
                    break;
                case 'office':
                    categoryBadge = '<span class="category-badge category-office">Kantor</span>';
                    break;
                case 'marketing':
                    categoryBadge = '<span class="category-badge category-marketing">Pemasaran</span>';
                    break;
                case 'utilities':
                    categoryBadge = '<span class="category-badge category-utilities">Utilitas</span>';
                    break;
            }
            document.getElementById('detail-category').innerHTML = categoryBadge;

            // Show modal
            document.getElementById('transactionDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTransactionDetailModal() {
            document.getElementById('transactionDetailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function printTransaction() {
            window.print();
        }

        function downloadTransaction() {
            // Placeholder for download functionality
            showMinimalPopup('Info', 'Fitur download akan segera tersedia', 'warning');
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const addModal = document.getElementById('addModal');
            const transactionDetailModal = document.getElementById('transactionDetailModal');

            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == transactionDetailModal) {
                closeTransactionDetailModal();
            }
        }

        // Handle escape key to close modals
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeTransactionDetailModal();
            }
        });

        // Finance table functions
        function renderFinanceTable() {
            const tableBody = document.getElementById('finance-table-body');
            const mobileCards = document.getElementById('mobile-cards');
            tableBody.innerHTML = '';
            mobileCards.innerHTML = '';

            const startIndex = (financeCurrentPage - 1) * financeItemsPerPage;
            const endIndex = Math.min(startIndex + financeItemsPerPage, financeFilteredData.length);

            for (let i = startIndex; i < endIndex; i++) {
                const item = financeFilteredData[i];

                // Create table row for desktop
                const row = document.createElement('tr');
                row.className = 'finance-row';
                row.setAttribute('data-id', item.no);
                row.setAttribute('data-tanggal', item.tanggal);
                row.setAttribute('data-nama', item.nama);
                row.setAttribute('data-kategori', item.kategori);
                row.setAttribute('data-deskripsi', item.deskripsi);
                row.setAttribute('data-jumlah', item.jumlah);
                row.setAttribute('data-tipe', item.tipe);

                let typeBadge = '';
                let amountClass = '';
                if (item.tipe === 'income') {
                    typeBadge = '<span class="type-badge type-income">Pemasukan</span>';
                    amountClass = 'text-green-600';
                } else {
                    typeBadge = '<span class="type-badge type-expense">Pengeluaran</span>';
                    amountClass = 'text-red-600';
                }

                let categoryBadge = '';
                switch (item.kategori) {
                    case 'salary':
                        categoryBadge = '<span class="category-badge category-salary">Gaji</span>';
                        break;
                    case 'project':
                        categoryBadge = '<span class="category-badge category-project">Proyek</span>';
                        break;
                    case 'investment':
                        categoryBadge = '<span class="category-badge category-investment">Investasi</span>';
                        break;
                    case 'office':
                        categoryBadge = '<span class="category-badge category-office">Kantor</span>';
                        break;
                    case 'marketing':
                        categoryBadge = '<span class="category-badge category-marketing">Pemasaran</span>';
                        break;
                    case 'utilities':
                        categoryBadge = '<span class="category-badge category-utilities">Utilitas</span>';
                        break;
                }

                row.innerHTML = `
                    <td style="min-width: 60px;">${item.no}</td>
                    <td style="min-width: 150px;">${item.tanggal}</td>
                    <td style="min-width: 200px;">${item.nama}</td>
                    <td style="min-width: 150px;">${categoryBadge}</td>
                    <td style="min-width: 300px;">${item.deskripsi}</td>
                    <td style="min-width: 150px;" class="${amountClass} font-semibold">${item.jumlah}</td>
                    <td style="min-width: 120px;">${typeBadge}</td>
                    <td style="min-width: 100px; text-align: center;">
                        <div class="flex justify-center gap-2">
                            <button onclick="openTransactionDetailModal(${item.no})" class="p-1 rounded-full hover:bg-primary/20 text-gray-700" title="Lihat Detail">
                                <span class="material-icons-outlined">description</span>
                            </button>
                        </div>
                    </td>
                `;

                tableBody.appendChild(row);

                // Create card for mobile
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm finance-card';
                card.setAttribute('data-id', item.no);
                card.setAttribute('data-tanggal', item.tanggal);
                card.setAttribute('data-nama', item.nama);
                card.setAttribute('data-kategori', item.kategori);
                card.setAttribute('data-deskripsi', item.deskripsi);
                card.setAttribute('data-jumlah', item.jumlah);
                card.setAttribute('data-tipe', item.tipe);

                // Determine icon based on type
                let icon = 'account_balance_wallet';
                if (item.tipe === 'income') {
                    icon = 'arrow_downward';
                    amountClass = 'text-green-600';
                } else {
                    icon = 'arrow_upward';
                    amountClass = 'text-red-600';
                }

                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="material-icons-outlined text-primary">${icon}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-base">${item.nama}</h4>
                                <p class="text-sm text-text-muted-light">${item.tanggal}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="openTransactionDetailModal(${item.no})" class="p-1 rounded-full hover:bg-primary/20 text-gray-700" title="Lihat Detail">
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
                            <p class="text-text-muted-light">Tipe</p>
                            <p>${typeBadge}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Kategori</p>
                            <p>${categoryBadge}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Jumlah</p>
                            <p class="font-medium ${amountClass}">${item.jumlah}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-text-muted-light">Deskripsi</p>
                            <p class="font-medium text-xs">${item.deskripsi}</p>
                        </div>
                    </div>
                `;

                mobileCards.appendChild(card);
            }

            // Update info
            document.getElementById('totalCount').textContent = financeFilteredData.length;
        }

        function renderFinancePagination() {
            const pagination = document.getElementById('finance-pagination');
            const pageNumbers = document.getElementById('pageNumbers');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');

            // Clear existing page numbers
            pageNumbers.innerHTML = '';

            const totalPages = Math.ceil(financeFilteredData.length / financeItemsPerPage);

            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${i === financeCurrentPage ? 'active' : ''}`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbers.appendChild(pageNumber);
            }

            // Update navigation buttons
            prevButton.disabled = financeCurrentPage === 1;
            nextButton.disabled = financeCurrentPage === totalPages || totalPages === 0;

            // Add event listeners for navigation buttons
            prevButton.onclick = () => {
                if (financeCurrentPage > 1) goToPage(financeCurrentPage - 1);
            };

            nextButton.onclick = () => {
                if (financeCurrentPage < totalPages) goToPage(financeCurrentPage + 1);
            };
        }

        function goToPage(page) {
            financeCurrentPage = page;
            renderFinanceTable();
            renderFinancePagination();

            // Reset scroll position when changing pages
            const scrollableTable = document.getElementById('scrollableTable');
            if (scrollableTable) {
                scrollableTable.scrollLeft = 0;
            }
        }

        /**
         * Helper: Extract numeric value from formatted currency string
         * Input: "Rp 45.750.000" or similar
         * Output: 45750000
         */
        function extractNumericFromCurrency(currencyStr) {
            if (typeof currencyStr === 'number') return currencyStr;
            // Remove 'Rp', spaces, and dots used as thousand separators
            const cleaned = String(currencyStr).replace(/[^\d]/g, '');
            return parseInt(cleaned, 10) || 0;
        }

        /**
         * Format number as Indonesian Rupiah
         * Input: 45750000
         * Output: "Rp 45.750.000"
         */
        function formatCurrency(num) {
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        /**
         * Calculate percentage change and direction
         * Returns: { percentage: number, direction: 'positive'|'negative', text: string }
         */
        function calculatePercentageChange(current, previous) {
            if (previous === 0) {
                return {
                    percentage: current > 0 ? 100 : 0,
                    direction: current > 0 ? 'positive' : 'negative',
                    text: current > 0 ? '+100% dari sebelumnya' : 'Tidak ada perubahan'
                };
            }
            const change = ((current - previous) / previous) * 100;
            return {
                percentage: Math.abs(change),
                direction: change >= 0 ? 'positive' : 'negative',
                text: `${change >= 0 ? '+' : ''}${change.toFixed(1)}% dari sebelumnya`
            };
        }

        /**
         * Calculate average amount per transaction for a type
         */
        function calculateAveragePerType(type) {
            const items = financeData.filter(item => item.tipe === type);
            if (items.length === 0) return 0;
            
            let total = 0;
            items.forEach(item => {
                total += extractNumericFromCurrency(item.jumlah);
            });
            return total / items.length;
        }

        /**
         * Update stat cards with dynamic totals from financeData
         */
        function updateStatCards() {
            let totalIncome = 0;
            let totalExpense = 0;
            let incomeCount = 0;
            let expenseCount = 0;

            financeData.forEach(item => {
                const amount = extractNumericFromCurrency(item.jumlah);
                if (item.tipe === 'income') {
                    totalIncome += amount;
                    incomeCount++;
                } else if (item.tipe === 'expense') {
                    totalExpense += amount;
                    expenseCount++;
                }
            });

            const netBalance = totalIncome - totalExpense;

            // Update stat cards values
            const statCards = document.querySelectorAll('.stat-card-value');
            if (statCards.length >= 3) {
                statCards[0].textContent = formatCurrency(totalIncome);    // Total Pemasukan
                statCards[1].textContent = formatCurrency(totalExpense);   // Total Pengeluaran
                statCards[2].textContent = formatCurrency(netBalance);     // Saldo Bersih
            }

            // Calculate and update percentage changes
            const avgIncome = calculateAveragePerType('income');
            const avgExpense = calculateAveragePerType('expense');

            // Income percentage change (compare current total vs average * count)
            const incomePercentageChange = calculatePercentageChange(totalIncome, avgIncome * incomeCount);
            
            // Expense percentage change (compare current total vs average * count)
            const expensePercentageChange = calculatePercentageChange(totalExpense, avgExpense * expenseCount);
            
            // Net balance percentage (profit margin)
            const balancePercentage = totalIncome > 0 ? (netBalance / totalIncome) * 100 : 0;
            const balanceChange = {
                percentage: Math.abs(balancePercentage),
                direction: balancePercentage >= 0 ? 'positive' : 'negative',
                text: `${balancePercentage >= 0 ? '+' : ''}${balancePercentage.toFixed(1)}% margin keuntungan`
            };

            // Update percentage change displays
            const statChanges = document.querySelectorAll('.stat-card-change');
            if (statChanges.length >= 3) {
                // Income change
                statChanges[0].className = `stat-card-change ${incomePercentageChange.direction}`;
                statChanges[0].innerHTML = `
                    <span class="material-icons-outlined text-sm">${incomePercentageChange.direction === 'positive' ? 'arrow_upward' : 'arrow_downward'}</span>
                    <span>${incomePercentageChange.text}</span>
                `;

                // Expense change
                statChanges[1].className = `stat-card-change ${expensePercentageChange.direction === 'positive' ? 'negative' : 'positive'}`;
                statChanges[1].innerHTML = `
                    <span class="material-icons-outlined text-sm">${expensePercentageChange.direction === 'positive' ? 'arrow_upward' : 'arrow_downward'}</span>
                    <span>${expensePercentageChange.direction === 'positive' ? 'Pengeluaran naik ' : 'Pengeluaran turun '}${expensePercentageChange.percentage.toFixed(1)}%</span>
                `;

                // Net balance change
                statChanges[2].className = `stat-card-change ${balanceChange.direction}`;
                statChanges[2].innerHTML = `
                    <span class="material-icons-outlined text-sm">${balanceChange.direction === 'positive' ? 'arrow_upward' : 'arrow_downward'}</span>
                    <span>${balanceChange.text}</span>
                `;
            }
        }

        function filterFinance() {
            searchTerm = document.getElementById('finance-search').value.trim();
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
        document.querySelector('.minimal-popup-close').addEventListener('click', function () {
            document.getElementById('minimalPopup').classList.remove('show');
        });

        // Initialize tables on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Render data tanpa perlu fetch karena sudah dari PHP
            renderFinanceTable();
            renderFinancePagination();
            updateStatCards(); // Update stat cards dengan data dari controller
            initializeFilter();
            initializeToggleButtons();

            // Add search functionality
            document.getElementById('finance-search').addEventListener('input', filterFinance);
        });
    </script>

    <!-- Script PHP untuk Menangani Pesan Sukses (Flash Session) -->
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showMinimalPopup('Berhasil', '{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showMinimalPopup('Gagal', '{{ session('error') }}', 'error');
            });
        </script>
    @endif
</body>

</html>
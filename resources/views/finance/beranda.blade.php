<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // Biru yang lebih terang dan standar
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
                        DEFAULT: "0.75rem", // 12px
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
        
        .status-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-overdue {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }
        
        /* Sidebar link styles */
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            font-weight: 600;
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
        
        /* Date Filter Styles */
        .date-filter {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .date-filter select {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        
        .date-filter select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Chart Container Styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Pagination styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            gap: 0.25rem;
        }
        
        .pagination-btn {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background-color: white;
            color: #1e293b;
        }
        
        .pagination-btn:hover {
            background-color: #f1f5f9;
        }
        
        .pagination-btn.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Main content positioning */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px; /* Lebar sidebar */
            }
        }
        
        /* Sidebar positioning */
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }
        
        /* Dark mode adjustments */
        .dark .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .dark .status-paid {
            background-color: rgba(16, 185, 129, 0.25);
            color: #6ee7b7;
        }
        
        .dark .status-pending {
            background-color: rgba(245, 158, 11, 0.25);
            color: #fcd34d;
        }
        
        .dark .status-overdue {
            background-color: rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }
        
        /* Mobile card adjustments */
        @media (max-width: 639px) {
            .stat-card {
                padding: 0.75rem !important;
            }
            
            .stat-card .icon-container {
                width: 2rem !important;
                height: 2rem !important;
            }
            
            .stat-card .material-icons-outlined {
                font-size: 1.25rem !important;
            }
            
            .stat-card .value-text {
                font-size: 0.875rem !important;
                line-height: 1.2 !important;
            }
            
            .stat-card .label-text {
                font-size: 0.625rem !important;
                line-height: 1 !important;
            }
            
            .stat-card .mr-3 {
                margin-right: 0.5rem !important;
            }
            
            /* Hide table on mobile */
            .order-table-container {
                display: none;
            }
            
            /* Show mobile cards */
            .mobile-order-cards {
                display: block;
            }
            
            .pagination-container {
                flex-wrap: wrap;
            }
            
            .chart-container {
                height: 250px;
            }
        }
        
        /* Show table on desktop */
        @media (min-width: 640px) {
            .order-table-container {
                display: block;
            }
            
            .mobile-order-cards {
                display: none;
            }
            
            .pagination-container {
                justify-content: flex-end;
            }
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('finance.templet.sider')
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col bg-background-light main-content">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Beranda</h2>
                
                <!-- Stat Cards Grid -->
                <div class="grid grid-cols-2 gap-3 mb-6 sm:mb-8">
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-blue-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-primary">trending_up</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Pemasukan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate" id="income-value">Rp 20.000.000</p>
                        </div>
                    </div>
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-red-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-red-500">trending_down</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Pengeluaran</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate" id="expense-value">Rp 10.000.000</p>
                        </div>
                    </div>
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-green-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-green-500">business_center</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Jumlah Layanan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate" id="service-count">100</p>
                        </div>
                    </div>
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-purple-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-500">account_balance_wallet</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Total Keuangan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate" id="total-finance">Rp 10.000.000</p>
                        </div>
                    </div>
                </div>
                
                <!-- Financial Chart Section -->
                <div class="bg-card-light rounded-DEFAULT p-3 sm:p-6 border border-border-light shadow-card mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-lg font-semibold">Grafik Keuangan</h3>
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <div class="date-filter">
                                <select id="chart-filter">
                                    <option value="week">Minggu Ini</option>
                                    <option value="month" selected>Bulan Ini</option>
                                    <option value="year">Tahun Ini</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="finance-chart"></canvas>
                    </div>
                </div>
                
                <!-- Order Table Section -->
                <div class="bg-card-light rounded-DEFAULT p-3 sm:p-6 border border-border-light shadow-card">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-lg font-semibold">Order List</h3>
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <div class="relative">
                                <button id="filterBtn" class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                    <span class="material-icons-outlined text-sm">filter_list</span>
                                    Filter
                                </button>
                                <div id="filterDropdown" class="filter-dropdown">
                                    <div class="date-filter">
                                        <select id="day-filter">
                                            <option value="">Semua Hari</option>
                                            <option value="1">Senin</option>
                                            <option value="2">Selasa</option>
                                            <option value="3">Rabu</option>
                                            <option value="4">Kamis</option>
                                            <option value="5">Jumat</option>
                                            <option value="6">Sabtu</option>
                                            <option value="7">Minggu</option>
                                        </select>
                                    </div>
                                    <div class="date-filter">
                                        <select id="month-filter">
                                            <option value="">Semua Bulan</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="date-filter">
                                        <select id="year-filter">
                                            <option value="">Semua Tahun</option>
                                            <option value="2023">2023</option>
                                            <option value="2024" selected>2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                    </div>
                                    <div class="filter-actions">
                                        <button id="applyFilter" class="filter-apply">Terapkan</button>
                                        <button id="resetFilter" class="filter-reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Table View -->
                    <div class="order-table-container overflow-x-auto">
                        <table class="w-full text-left text-sm order-table">
                            <thead>
                                <tr class="border-b border-border-light">
                                    <th class="p-3 font-semibold">NO</th>
                                    <th class="p-3 font-semibold">LAYANAN</th>
                                    <th class="p-3 font-semibold">HARGA</th>
                                    <th class="p-3 font-semibold">KLIEN</th>
                                    <th class="p-3 font-semibold text-center">PEMBAYARAN AWAL</th>
                                    <th class="p-3 font-semibold">PELUNASAN</th>
                                    <th class="p-3 font-semibold">STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="order-table-body">
                                <!-- Data akan diisi dengan JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card View -->
                    <div class="mobile-order-cards space-y-4" id="mobile-order-cards">
                        <!-- Data akan diisi dengan JavaScript -->
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-container" id="pagination-container">
                        <!-- Pagination buttons akan diisi dengan JavaScript -->
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data order dengan tambahan tanggal
        const orderData = [
            { no: 1, layanan: "Pembuatan Website", harga: "Rp 5.000.000", klien: "PT. Teknologi Maju", awal: "Rp 2.500.000", lunas: "Rp 2.500.000", status: "paid", date: new Date(2024, 0, 15) },
            { no: 2, layanan: "SEO Optimization", harga: "Rp 3.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.500.000", lunas: "Rp 1.500.000", status: "paid", date: new Date(2024, 0, 20) },
            { no: 3, layanan: "Manajemen Sosial Media", harga: "Rp 4.000.000", klien: "UD. Kreatif Indonesia", awal: "Rp 2.000.000", lunas: "Rp 0", status: "pending", date: new Date(2024, 1, 5) },
            { no: 4, layanan: "Pengembangan Aplikasi Mobile", harga: "Rp 8.000.000", klien: "PT. Inovasi Nusantara", awal: "Rp 4.000.000", lunas: "Rp 0", status: "overdue", date: new Date(2024, 1, 10) },
            { no: 5, layanan: "Desain UI/UX", harga: "Rp 7.500.000", klien: "CV. Kreatif", awal: "Rp 2.500.000", lunas: "Rp 0", status: "pending", date: new Date(2024, 2, 15) },
            { no: 6, layanan: "Pembuatan Website", harga: "Rp 6.000.000", klien: "PT. Digital Nusantara", awal: "Rp 3.000.000", lunas: "Rp 3.000.000", status: "paid", date: new Date(2024, 2, 20) },
            { no: 7, layanan: "SEO Optimization", harga: "Rp 2.500.000", klien: "CV. Kreatif Digital", awal: "Rp 1.250.000", lunas: "Rp 1.250.000", status: "paid", date: new Date(2024, 3, 5) },
            { no: 8, layanan: "Manajemen Sosial Media", harga: "Rp 3.500.000", klien: "UD. Inovasi Teknologi", awal: "Rp 1.750.000", lunas: "Rp 0", status: "pending", date: new Date(2024, 3, 10) },
            { no: 9, layanan: "Pengembangan Aplikasi Mobile", harga: "Rp 9.000.000", klien: "PT. Solusi Digital", awal: "Rp 4.500.000", lunas: "Rp 0", status: "overdue", date: new Date(2024, 4, 15) },
            { no: 10, layanan: "Desain UI/UX", harga: "Rp 5.500.000", klien: "CV. Karya Kreatif", awal: "Rp 2.750.000", lunas: "Rp 0", status: "pending", date: new Date(2024, 4, 20) },
            { no: 11, layanan: "Pembuatan Website", harga: "Rp 7.000.000", klien: "PT. Teknologi Maju", awal: "Rp 3.500.000", lunas: "Rp 3.500.000", status: "paid", date: new Date(2024, 5, 5) },
            { no: 12, layanan: "SEO Optimization", harga: "Rp 2.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.000.000", lunas: "Rp 1.000.000", status: "paid", date: new Date(2024, 5, 10) }
        ];

        // Data keuangan untuk grafik
        const financeData = {
            week: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                income: [5000000, 7000000, 6000000, 8000000, 9000000, 4000000, 3000000],
                expense: [2000000, 3000000, 2500000, 4000000, 3500000, 1500000, 1000000]
            },
            month: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                income: [25000000, 30000000, 28000000, 35000000],
                expense: [12000000, 15000000, 13000000, 18000000]
            },
            year: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                income: [80000000, 85000000, 90000000, 95000000, 100000000, 105000000, 110000000, 115000000, 120000000, 125000000, 130000000, 135000000],
                expense: [40000000, 42000000, 45000000, 48000000, 50000000, 53000000, 55000000, 58000000, 60000000, 63000000, 65000000, 68000000]
            }
        };

        // Pagination variables
        let currentPage = 1;
        const itemsPerPage = 5;
        let filteredData = [...orderData];
        let chartInstance = null;

        // Function to render table
        function renderTable() {
            const tableBody = document.getElementById('order-table-body');
            tableBody.innerHTML = '';
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const item = filteredData[i];
                const row = document.createElement('tr');
                row.className = 'border-b border-border-light';
                
                let statusBadge = '';
                switch(item.status) {
                    case 'paid':
                        statusBadge = '<span class="status-badge status-paid">Lunas</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="status-badge status-pending">Pending</span>';
                        break;
                    case 'overdue':
                        statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                        break;
                }
                
                row.innerHTML = `
                    <td class="p-3">${item.no}</td>
                    <td class="p-3">${item.layanan}</td>
                    <td class="p-3">${item.harga}</td>
                    <td class="p-3">${item.klien}</td>
                    <td class="p-3 text-center">${item.awal}</td>
                    <td class="p-3">${item.lunas}</td>
                    <td class="p-3">${statusBadge}</td>
                `;
                
                tableBody.appendChild(row);
            }
            
            renderPagination();
            renderMobileCards();
            updateStatCards();
        }

        // Function to render mobile cards
        function renderMobileCards() {
            const mobileCards = document.getElementById('mobile-order-cards');
            mobileCards.innerHTML = '';
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const item = filteredData[i];
                
                let statusBadge = '';
                switch(item.status) {
                    case 'paid':
                        statusBadge = '<span class="status-badge status-paid">Lunas</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="status-badge status-pending">Pending</span>';
                        break;
                    case 'overdue':
                        statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                        break;
                }
                
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-base">${item.layanan}</h4>
                            <p class="text-sm text-text-muted-light">${item.klien}</p>
                        </div>
                        ${statusBadge}
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-text-muted-light">Total Harga</p>
                            <p class="font-medium">${item.harga}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Pembayaran Awal</p>
                            <p class="font-medium">${item.awal}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Pelunasan</p>
                            <p class="font-medium">${item.lunas}</p>
                        </div>
                    </div>
                `;
                
                mobileCards.appendChild(card);
            }
        }

        // Function to render pagination
        function renderPagination() {
            const paginationContainer = document.getElementById('pagination-container');
            paginationContainer.innerHTML = '';
            
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '<span class="material-icons-outlined">chevron_left</span>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            };
            paginationContainer.appendChild(prevBtn);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
                paginationContainer.appendChild(pageBtn);
            }
            
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = '<span class="material-icons-outlined">chevron_right</span>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            };
            paginationContainer.appendChild(nextBtn);
        }

        // Function to update stat cards
        function updateStatCards() {
            let totalIncome = 0;
            let totalExpense = 0;
            
            filteredData.forEach(item => {
                // Hitung pemasukan dari status paid
                if (item.status === 'paid') {
                    const harga = parseInt(item.harga.replace(/\D/g, ''));
                    totalIncome += harga;
                }
                
                // Hitung pengeluaran (misalnya dari awal yang belum lunas)
                if (item.status === 'pending' || item.status === 'overdue') {
                    const awal = parseInt(item.awal.replace(/\D/g, ''));
                    totalExpense += awal;
                }
            });
            
            // Update nilai di stat cards
            document.getElementById('income-value').textContent = `Rp ${totalIncome.toLocaleString('id-ID')}`;
            document.getElementById('expense-value').textContent = `Rp ${totalExpense.toLocaleString('id-ID')}`;
            document.getElementById('total-finance').textContent = `Rp ${(totalIncome - totalExpense).toLocaleString('id-ID')}`;
            document.getElementById('service-count').textContent = filteredData.length;
        }

        // Function to initialize chart
        function initChart(filterType = 'month') {
            const ctx = document.getElementById('finance-chart').getContext('2d');
            
            // Hancurkan chart yang sudah ada jika ada
            if (chartInstance) {
                chartInstance.destroy();
            }
            
            // Ambil data berdasarkan filter
            const data = financeData[filterType];
            
            // Buat chart baru
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: data.income,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Pengeluaran',
                            data: data.expense,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Function to apply filters
        function applyFilters() {
            const dayFilter = document.getElementById('day-filter').value;
            const monthFilter = document.getElementById('month-filter').value;
            const yearFilter = document.getElementById('year-filter').value;
            
            // Filter data berdasarkan pilihan
            filteredData = orderData.filter(item => {
                const date = item.date;
                const day = date.getDay() === 0 ? 7 : date.getDay(); // Konversi: Minggu = 7
                const month = date.getMonth() + 1; // Bulan dimulai dari 0
                const year = date.getFullYear();
                
                // Jika tidak ada filter, tampilkan semua
                if (!dayFilter && !monthFilter && !yearFilter) {
                    return true;
                }
                
                // Filter berdasarkan hari
                if (dayFilter && day != dayFilter) {
                    return false;
                }
                
                // Filter berdasarkan bulan
                if (monthFilter && month != monthFilter) {
                    return false;
                }
                
                // Filter berdasarkan tahun
                if (yearFilter && year != yearFilter) {
                    return false;
                }
                
                return true;
            });
            
            // Reset ke halaman pertama
            currentPage = 1;
            
            // Render ulang tabel
            renderTable();
            
            // Tutup dropdown filter
            document.getElementById('filterDropdown').classList.remove('show');
        }

        // Function to reset filters
        function resetFilters() {
            document.getElementById('day-filter').value = '';
            document.getElementById('month-filter').value = '';
            document.getElementById('year-filter').value = '';
            
            // Kembalikan ke data asli
            filteredData = [...orderData];
            
            // Reset ke halaman pertama
            currentPage = 1;
            
            // Render ulang tabel
            renderTable();
            
            // Tutup dropdown filter
            document.getElementById('filterDropdown').classList.remove('show');
        }

        // Initialize tables on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderTable();
            initChart('month'); // Inisialisasi grafik dengan filter bulan
            
            // Event listener untuk filter dropdown
            document.getElementById('filterBtn').addEventListener('click', function(e) {
                e.stopPropagation();
                document.getElementById('filterDropdown').classList.toggle('show');
            });
            
            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function() {
                document.getElementById('filterDropdown').classList.remove('show');
            });
            
            // Mencegah dropdown tertutup saat klik di dalamnya
            document.getElementById('filterDropdown').addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Event listener untuk tombol filter
            document.getElementById('applyFilter').addEventListener('click', applyFilters);
            document.getElementById('resetFilter').addEventListener('click', resetFilters);
            
            // Event listener untuk filter chart
            document.getElementById('chart-filter').addEventListener('change', function() {
                initChart(this.value);
            });
        });
    </script>
</body>

</html>
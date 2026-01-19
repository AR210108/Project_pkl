<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Project</title>
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons+Round"
        rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

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
                        body: ["Inter", "sans-serif"],
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
            font-family: "Inter", sans-serif;
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
        
        .status-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-proses {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-selesai {
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
        <!-- Menggunakan template header -->
        @include('manager_divisi/templet/sider')
        <div class="flex-1 flex flex-col main-content">
            <div class="flex-1 p-3 sm:p-8">
                <header class="mb-4 sm:mb-8">
                    <h1 class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">Data Project</h1>
                </header>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari nama project atau deskripsi..." type="text" />
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
                                    <input type="checkbox" id="filterPending" value="pending">
                                    <label for="filterPending">Pending</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterProses" value="proses">
                                    <label for="filterProses">Proses</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterSelesai" value="selesai">
                                    <label for="filterSelesai">Selesai</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">shopping_cart</span>
                            Data Project
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount" class="font-semibold text-text-light">2</span> project</span>
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
                                            <th style="min-width: 200px;">Nama Project</th>
                                            <th style="min-width: 250px;">Deskripsi</th>
                                            <th style="min-width: 150px;">Harga</th>
                                            <th style="min-width: 150px;">Deadline</th>
                                            <th style="min-width: 150px;">Progres</th>
                                            <th style="min-width: 150px;">Status</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards">
                            <div class="bg-white rounded-lg border border-border-light p-4 shadow-sm orderan-card" 
                                 data-id="1" 
                                 data-nama="Website Company Profile" 
                                 data-deskripsi="Pembuatan web profil perusahaan..." 
                                 data-harga="5000000" 
                                 data-deadline="12 Okt 2025"
                                 data-progres="75"
                                 data-status="selesai">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                            <span class="material-icons-outlined text-primary">shopping_cart</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-base">Website Company Profile</h4>
                                            <p class="text-sm text-text-muted-light">Rp 5.000.000</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                                data-id="1"
                                                data-nama="Website Company Profile"
                                                data-deskripsi="Pembuatan web profil perusahaan..."
                                                data-harga="5000000"
                                                data-deadline="12 Okt 2025"
                                                data-progres="75"
                                                data-status="selesai">
                                            <span class="material-icons-outlined">edit</span>
                                        </button>
                                        <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                                data-id="1">
                                            <span class="material-icons-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <p class="text-text-muted-light">No</p>
                                        <p class="font-medium">1</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Status</p>
                                        <p>
                                            <span class="status-badge status-selesai">
                                                Selesai
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Deadline</p>
                                        <p class="font-medium">12 Okt 2025</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Progres</p>
                                        <div class="w-full bg-gray-400 rounded-full h-2 dark:bg-gray-600 mt-2">
                                            <div class="bg-primary h-2 rounded-full" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-text-muted-light">Deskripsi</p>
                                        <p class="font-medium">Pembuatan web profil perusahaan...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg border border-border-light p-4 shadow-sm orderan-card" 
                                 data-id="2" 
                                 data-nama="Aplikasi Kasir Android" 
                                 data-deskripsi="Aplikasi POS untuk retail..." 
                                 data-harga="12000000" 
                                 data-deadline="20 Nov 2025"
                                 data-progres="30"
                                 data-status="proses">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                            <span class="material-icons-outlined text-primary">shopping_cart</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-base">Aplikasi Kasir Android</h4>
                                            <p class="text-sm text-text-muted-light">Rp 12.000.000</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                                data-id="2"
                                                data-nama="Aplikasi Kasir Android"
                                                data-deskripsi="Aplikasi POS untuk retail..."
                                                data-harga="12000000"
                                                data-deadline="20 Nov 2025"
                                                data-progres="30"
                                                data-status="proses">
                                            <span class="material-icons-outlined">edit</span>
                                        </button>
                                        <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                                data-id="2">
                                            <span class="material-icons-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <p class="text-text-muted-light">No</p>
                                        <p class="font-medium">2</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Status</p>
                                        <p>
                                            <span class="status-badge status-proses">
                                                Proses
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Deadline</p>
                                        <p class="font-medium">20 Nov 2025</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Progres</p>
                                        <div class="w-full bg-gray-400 rounded-full h-2 dark:bg-gray-600 mt-2">
                                            <div class="bg-primary h-2 rounded-full" style="width: 30%"></div>
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-text-muted-light">Deskripsi</p>
                                        <p class="font-medium">Aplikasi POS untuk retail...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div id="paginationContainer" class="desktop-pagination">
                            <button id="prevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="pageNumbers" class="flex gap-1">
                                <button class="desktop-page-btn active">1</button>
                            </div>
                            <button id="nextPage" class="desktop-nav-btn" disabled>
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

    <!-- Modal Edit Orderan -->
    <div id="editOrderanModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Project</h3>
                    <button id="closeEditModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="#" id="editOrderanForm" class="space-y-4">
                    <input type="hidden" id="editId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Project</label>
                            <input type="text" id="editNamaOrderan" name="nama_orderan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama Project" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="text" id="editHarga" name="harga" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan harga" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                            <input type="text" id="editDeadline" name="deadline" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan deadline" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="editStatus" name="status" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih status</option>
                                <option value="pending">Pending</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan deskripsi Project" required></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Progres (%)</label>
                        <input type="number" id="editProgres" name="progres" min="0" max="100" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan progres (0-100)" required>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" id="batalEditBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Konfirmasi -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button id="closeDeleteModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="deleteForm" method="POST" action="#">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus project ini?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteId" name="id">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="batalDeleteBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
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
        // Inisialisasi variabel untuk pagination, filter, dan search
        let currentPage = 1;
        const itemsPerPage = 5;
        let activeFilters = ['all'];
        let searchTerm = '';
        
        // Dapatkan semua elemen orderan
        const orderanRows = document.querySelectorAll('.orderan-row');
        const orderanCards = document.querySelectorAll('.orderan-card');
        
        // Inisialisasi pagination, filter, dan search
        initializePagination();
        initializeFilter();
        initializeSearch();
        initializeScrollDetection();

        // === PAGINATION ===
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
            return Array.from(orderanRows).filter(row => !row.classList.contains('hidden-by-filter'));
        }
        
        function getFilteredCards() {
            return Array.from(orderanCards).filter(card => !card.classList.contains('hidden-by-filter'));
        }
        
        function updateVisibleItems() {
            const visibleRows = getFilteredRows();
            const visibleCards = getFilteredCards();
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            // Hide all rows and cards first
            orderanRows.forEach(row => row.style.display = 'none');
            orderanCards.forEach(card => card.style.display = 'none');
            
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
            
            // Update total count
            document.getElementById('totalCount').textContent = visibleRows.length;
        }
        
        // === FILTER ===
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
                const filterPending = document.getElementById('filterPending');
                const filterProses = document.getElementById('filterProses');
                const filterSelesai = document.getElementById('filterSelesai');
                
                activeFilters = [];
                if (filterAll.checked) {
                    activeFilters.push('all');
                } else {
                    if (filterPending.checked) activeFilters.push('pending');
                    if (filterProses.checked) activeFilters.push('proses');
                    if (filterSelesai.checked) activeFilters.push('selesai');
                }
                
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} orderan`, 'success');
            });
            
            // Reset filter
            resetFilterBtn.addEventListener('click', function() {
                document.getElementById('filterAll').checked = true;
                document.getElementById('filterPending').checked = false;
                document.getElementById('filterProses').checked = false;
                document.getElementById('filterSelesai').checked = false;
                activeFilters = ['all'];
                applyFilters();
                filterDropdown.classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Direset', 'Menampilkan semua orderan', 'success');
            });
        }
        
        function applyFilters() {
            // Reset to first page
            currentPage = 1;
            
            // Apply filters to rows
            orderanRows.forEach(row => {
                const status = row.getAttribute('data-status').toLowerCase();
                const nama = row.getAttribute('data-nama').toLowerCase();
                const deskripsi = row.getAttribute('data-deskripsi').toLowerCase();
                
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
                    searchMatches = nama.includes(searchLower) || 
                                   deskripsi.includes(searchLower) ||
                                   status.includes(searchLower);
                }
                
                if (statusMatches && searchMatches) {
                    row.classList.remove('hidden-by-filter');
                } else {
                    row.classList.add('hidden-by-filter');
                }
            });
            
            // Apply same filters to cards
            orderanCards.forEach(card => {
                const status = card.getAttribute('data-status').toLowerCase();
                const nama = card.getAttribute('data-nama').toLowerCase();
                const deskripsi = card.getAttribute('data-deskripsi').toLowerCase();
                
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
                    searchMatches = nama.includes(searchLower) || 
                                   deskripsi.includes(searchLower) ||
                                   status.includes(searchLower);
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
        }
        
        // === SEARCH ===
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchTerm = searchInput.value.trim();
                    applyFilters();
                }, 300); // Debounce search
            });
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

        // Modal elements
        const tambahOrderanModal = document.getElementById('tambahOrderanModal');
        const editOrderanModal = document.getElementById('editOrderanModal');
        const deleteModal = document.getElementById('deleteModal');

        // Buttons
        const tambahOrderanBtn = document.getElementById('tambahOrderanBtn');
        const batalTambahBtn = document.getElementById('batalTambahBtn');
        const batalEditBtn = document.getElementById('batalEditBtn');
        const batalDeleteBtn = document.getElementById('batalDeleteBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closeEditModalBtn = document.getElementById('closeEditModalBtn');
        const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');

        // Forms
        const tambahOrderanForm = document.getElementById('tambahOrderanForm');
        const editOrderanForm = document.getElementById('editOrderanForm');

        // Open tambah modal
        tambahOrderanBtn.addEventListener('click', () => {
            tambahOrderanModal.classList.remove('hidden');
        });

        // Close tambah modal
        batalTambahBtn.addEventListener('click', () => {
            tambahOrderanModal.classList.add('hidden');
            tambahOrderanForm.reset();
        });

        closeModalBtn.addEventListener('click', () => {
            tambahOrderanModal.classList.add('hidden');
            tambahOrderanForm.reset();
        });

        // Close edit modal
        batalEditBtn.addEventListener('click', () => {
            editOrderanModal.classList.add('hidden');
            editOrderanForm.reset();
        });

        closeEditModalBtn.addEventListener('click', () => {
            editOrderanModal.classList.add('hidden');
            editOrderanForm.reset();
        });

        // Close delete modal
        batalDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        closeDeleteModalBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        // ============================
        // HANDLE EDIT BUTTON
        // ============================
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                // SET VALUE
                document.getElementById('editId').value = button.dataset.id;
                document.getElementById('editNamaOrderan').value = button.dataset.nama;
                document.getElementById('editDeskripsi').value = button.dataset.deskripsi;
                document.getElementById('editHarga').value = button.dataset.harga;
                document.getElementById('editDeadline').value = button.dataset.deadline;
                document.getElementById('editProgres').value = button.dataset.progres;
                document.getElementById('editStatus').value = button.dataset.status;

                // SET ACTION URL DINAMIS
                editOrderanForm.action = `/orderan/${button.dataset.id}`;

                editOrderanModal.classList.remove('hidden');
            });
        });

        // ============================
        // HANDLE DELETE BUTTON
        // ============================
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;

                // Set action form delete
                document.getElementById('deleteForm').action = `/orderan/${id}`;
                document.getElementById('deleteId').value = id;

                // Tampilkan modal
                deleteModal.classList.remove('hidden');
            });
        });

        // ============================
        // SUBMIT FORM EDIT
        // ============================
        editOrderanForm.addEventListener('submit', (e) => {
            editOrderanModal.classList.add('hidden');
        });

        // ============================
        // SUBMIT FORM DELETE
        // ============================
        document.getElementById('deleteForm').addEventListener('submit', (e) => {
            deleteModal.classList.add('hidden');
        });

        // ============================
        // SUBMIT FORM TAMBAH
        // ============================
        tambahOrderanForm.addEventListener('submit', (e) => {
            tambahOrderanModal.classList.add('hidden');
        });
    </script>
</body>
</html>
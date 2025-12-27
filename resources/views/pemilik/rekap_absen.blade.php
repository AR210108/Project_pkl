<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi - Dashboard</title>
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
        
        /* Card hover effects - updated to match reference */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
        
        /* Status Badge Styles */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-hadir {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .status-terlambat {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-izin {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-cuti {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
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
                margin-left: 0; /* Diubah karena menggunakan header bukan sidebar */
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
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }
        
        /* Show desktop pagination on medium screens and up */
        @media (min-width: 768px) {
            .desktop-pagination {
                display: flex;
            }
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
        
        /* SCROLLABLE TABLE - TANPA INDICATOR */
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
            min-width: 1200px; /* Fixed minimum width */
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

        /* Tab Navigation Styles */
        .tab-nav {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #64748b;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-button:hover {
            color: #3b82f6;
        }

        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }
        
        /* Icon styling - added to match reference */
        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
        }
        
        /* Mobile pagination styles */
        .mobile-pagination {
            display: flex; /* Visible by default */
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
        }
        
        /* Hide mobile pagination on medium screens and up */
        @media (min-width: 768px) {
            .mobile-pagination {
                display: none;
            }
        }
        
        .mobile-page-btn {
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
        
        .mobile-page-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .mobile-page-btn:not(.active) {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        .mobile-page-btn:not(.active):hover {
            background-color: #e2e8f0;
        }
        
        .mobile-nav-btn {
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
        
        .mobile-nav-btn:hover:not(:disabled) {
            background-color: #e2e8f0;
        }
        
        .mobile-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    @include('pemilik/template/header')
    
    <!-- Main Content Container -->
    <div class="main-content">
        <main class="flex-1 flex flex-col bg-background-light">
            <div class="flex-1 p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Absensi</h2>
                
                <!-- Stats Cards - Modified to match reference style -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                    <!-- Total Kehadiran Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-green-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-green-600 text-lg md:text-xl">check_circle</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Total Kehadiran</p>
                                <p class="text-xl md:text-2xl font-bold text-green-600">1</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tidak Hadir Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-red-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-red-600 text-lg md:text-xl">cancel</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Tidak Hadir</p>
                                <p class="text-xl md:text-2xl font-bold text-red-600">11</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Izin Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-blue-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-blue-600 text-lg md:text-xl">error</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Izin</p>
                                <p class="text-xl md:text-2xl font-bold text-blue-600">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cuti Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-yellow-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-yellow-600 text-lg md:text-xl">event_busy</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Cuti</p>
                                <p class="text-xl md:text-2xl font-bold text-yellow-600">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dinas Luar Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-purple-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-purple-600 text-lg md:text-xl">directions_car</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Dinas Luar</p>
                                <p class="text-xl md:text-2xl font-bold text-purple-600">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sakit Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-orange-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-orange-600 text-lg md:text-xl">healing</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Sakit</p>
                                <p class="text-xl md:text-2xl font-bold text-orange-600">0</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tab-nav">
                    <button id="absensiTab" class="tab-button active" onclick="switchTab('absensi')">
                        <span class="material-icons-outlined align-middle mr-2">fact_check</span>
                        Data Absensi
                    </button>
                    <button id="ketidakhadiranTab" class="tab-button" onclick="switchTab('ketidakhadiran')">
                        <span class="material-icons-outlined align-middle mr-2">assignment_late</span>
                        Daftar Ketidakhadiran
                    </button>
                </div>
                
                <!-- Data Absensi Panel -->
                <div id="absensiPanel" class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">fact_check</span>
                            Data Absensi
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="absensiCount">3</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE -->
                        <div class="scrollable-table-container table-shadow" id="scrollableTable">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 60px;">No</th>
                                        <th style="min-width: 200px;">Nama</th>
                                        <th style="min-width: 120px;">Tanggal</th>
                                        <th style="min-width: 120px;">Jam Masuk</th>
                                        <th style="min-width: 120px;">Jam Keluar</th>
                                        <th style="min-width: 120px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="absensiTableBody">
                                    <!-- Data rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Desktop Pagination -->
                        <div id="absensiPaginationContainer" class="desktop-pagination">
                            <button id="absensiPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="absensiPageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="absensiNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                        
                        <!-- Mobile Pagination -->
                        <div class="mobile-pagination">
                            <button id="absensiPrevPageMobile" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="absensiPageNumbersMobile" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="absensiNextPageMobile" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Ketidakhadiran Panel (Initially Hidden) -->
                <div id="ketidakhadiranPanel" class="panel hidden">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">assignment_late</span>
                            Daftar Ketidakhadiran
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="ketidakhadiranCount">2</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE -->
                        <div class="scrollable-table-container table-shadow" id="scrollableTableKetidakhadiran">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 60px;">No</th>
                                        <th style="min-width: 200px;">Nama</th>
                                        <th style="min-width: 120px;">Tanggal Mulai</th>
                                        <th style="min-width: 120px;">Tanggal Akhir</th>
                                        <th style="min-width: 200px;">Alasan</th>
                                        <th style="min-width: 120px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="ketidakhadiranTableBody">
                                    <!-- Data rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Desktop Pagination -->
                        <div id="ketidakhadiranPaginationContainer" class="desktop-pagination">
                            <button id="ketidakhadiranPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="ketidakhadiranPageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="ketidakhadiranNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                        
                        <!-- Mobile Pagination -->
                        <div class="mobile-pagination">
                            <button id="ketidakhadiranPrevPageMobile" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="ketidakhadiranPageNumbersMobile" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="ketidakhadiranNextPageMobile" class="mobile-nav-btn">
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

    <script>
        // Sample data for absensi
        const absensiData = [
            { id: 1, nama: "Budi Santoso", tanggal: "2024-10-15", jamMasuk: "08:00", jamKeluar: "17:00", status: "Hadir" },
            { id: 2, nama: "Citra Lestari", tanggal: "2024-10-15", jamMasuk: "08:30", jamKeluar: "17:30", status: "Hadir" },
            { id: 3, nama: "Eko Prabowo", tanggal: "2024-10-15", jamMasuk: "09:15", jamKeluar: "17:00", status: "Terlambat" }
        ];

        // Sample data for ketidakhadiran
        const ketidakhadiranData = [
            { id: 1, nama: "Dewi Anggraini", tanggalMulai: "2024-10-10", tanggalAkhir: "2024-10-12", alasan: "Sakit", status: "Izin" },
            { id: 2, nama: "Ani Yudhoyono", tanggalMulai: "2024-10-05", tanggalAkhir: "2024-10-15", alasan: "Liburan keluarga", status: "Cuti" }
        ];

        // Pagination variables for absensi
        const absensiItemsPerPage = 2; // Dikurangi agar pagination terlihat
        let absensiCurrentPage = 1;
        const absensiTotalPages = Math.ceil(absensiData.length / absensiItemsPerPage);

        // Pagination variables for ketidakhadiran
        const ketidakhadiranItemsPerPage = 2; // Dikurangi agar pagination terlihat
        let ketidakhadiranCurrentPage = 1;
        const ketidakhadiranTotalPages = Math.ceil(ketidakhadiranData.length / ketidakhadiranItemsPerPage);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize pagination for absensi
            initializeAbsensiPagination();
            
            // Initialize pagination for ketidakhadiran
            initializeKetidakhadiranPagination();
        });

        // Function to switch between tabs
        function switchTab(tabName) {
            // Get tab buttons and panels
            const absensiTab = document.getElementById('absensiTab');
            const ketidakhadiranTab = document.getElementById('ketidakhadiranTab');
            const absensiPanel = document.getElementById('absensiPanel');
            const ketidakhadiranPanel = document.getElementById('ketidakhadiranPanel');

            // Hide all panels and remove active class from all tabs
            absensiPanel.classList.add('hidden');
            ketidakhadiranPanel.classList.add('hidden');
            absensiTab.classList.remove('active');
            ketidakhadiranTab.classList.remove('active');

            // Show selected panel and add active class to clicked tab
            if (tabName === 'absensi') {
                absensiPanel.classList.remove('hidden');
                absensiTab.classList.add('active');
            } else if (tabName === 'ketidakhadiran') {
                ketidakhadiranPanel.classList.remove('hidden');
                ketidakhadiranTab.classList.add('active');
            }
        }

        // Initialize pagination for absensi
        function initializeAbsensiPagination() {
            // Update total count
            document.getElementById('absensiCount').textContent = absensiData.length;
            
            // SELALU tampilkan pagination
            initAbsensiDesktopPagination();
            initAbsensiMobilePagination();
            
            // Render first page
            renderAbsensiTable(1);
        }

        // Initialize pagination for ketidakhadiran
        function initializeKetidakhadiranPagination() {
            // Update total count
            document.getElementById('ketidakhadiranCount').textContent = ketidakhadiranData.length;
            
            // SELALU tampilkan pagination
            initKetidakhadiranDesktopPagination();
            initKetidakhadiranMobilePagination();
            
            // Render first page
            renderKetidakhadiranTable(1);
        }

        // Desktop pagination functionality for absensi
        function initAbsensiDesktopPagination() {
            const pageNumbersContainer = document.getElementById('absensiPageNumbers');
            const prevButton = document.getElementById('absensiPrevPage');
            const nextButton = document.getElementById('absensiNextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= absensiTotalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${
                    i === absensiCurrentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToAbsensiDesktopPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (absensiCurrentPage > 1) goToAbsensiDesktopPage(absensiCurrentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (absensiCurrentPage < absensiTotalPages) goToAbsensiDesktopPage(absensiCurrentPage + 1);
            });
        }

        // Mobile pagination functionality for absensi
        function initAbsensiMobilePagination() {
            const pageNumbersContainer = document.getElementById('absensiPageNumbersMobile');
            const prevButton = document.getElementById('absensiPrevPageMobile');
            const nextButton = document.getElementById('absensiNextPageMobile');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= absensiTotalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `mobile-page-btn ${
                    i === absensiCurrentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToAbsensiMobilePage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (absensiCurrentPage > 1) goToAbsensiMobilePage(absensiCurrentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (absensiCurrentPage < absensiTotalPages) goToAbsensiMobilePage(absensiCurrentPage + 1);
            });
        }

        // Desktop pagination functionality for ketidakhadiran
        function initKetidakhadiranDesktopPagination() {
            const pageNumbersContainer = document.getElementById('ketidakhadiranPageNumbers');
            const prevButton = document.getElementById('ketidakhadiranPrevPage');
            const nextButton = document.getElementById('ketidakhadiranNextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= ketidakhadiranTotalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${
                    i === ketidakhadiranCurrentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToKetidakhadiranDesktopPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (ketidakhadiranCurrentPage > 1) goToKetidakhadiranDesktopPage(ketidakhadiranCurrentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (ketidakhadiranCurrentPage < ketidakhadiranTotalPages) goToKetidakhadiranDesktopPage(ketidakhadiranCurrentPage + 1);
            });
        }

        // Mobile pagination functionality for ketidakhadiran
        function initKetidakhadiranMobilePagination() {
            const pageNumbersContainer = document.getElementById('ketidakhadiranPageNumbersMobile');
            const prevButton = document.getElementById('ketidakhadiranPrevPageMobile');
            const nextButton = document.getElementById('ketidakhadiranNextPageMobile');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= ketidakhadiranTotalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `mobile-page-btn ${
                    i === ketidakhadiranCurrentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToKetidakhadiranMobilePage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (ketidakhadiranCurrentPage > 1) goToKetidakhadiranMobilePage(ketidakhadiranCurrentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (ketidakhadiranCurrentPage < ketidakhadiranTotalPages) goToKetidakhadiranMobilePage(ketidakhadiranCurrentPage + 1);
            });
        }

        // Go to specific desktop page for absensi
        function goToAbsensiDesktopPage(page) {
            absensiCurrentPage = page;
            renderAbsensiTable(page);
            updateAbsensiDesktopPaginationButtons();
            updateAbsensiMobilePaginationButtons();
        }

        // Go to specific mobile page for absensi
        function goToAbsensiMobilePage(page) {
            absensiCurrentPage = page;
            renderAbsensiTable(page);
            updateAbsensiDesktopPaginationButtons();
            updateAbsensiMobilePaginationButtons();
        }

        // Go to specific desktop page for ketidakhadiran
        function goToKetidakhadiranDesktopPage(page) {
            ketidakhadiranCurrentPage = page;
            renderKetidakhadiranTable(page);
            updateKetidakhadiranDesktopPaginationButtons();
            updateKetidakhadiranMobilePaginationButtons();
        }

        // Go to specific mobile page for ketidakhadiran
        function goToKetidakhadiranMobilePage(page) {
            ketidakhadiranCurrentPage = page;
            renderKetidakhadiranTable(page);
            updateKetidakhadiranDesktopPaginationButtons();
            updateKetidakhadiranMobilePaginationButtons();
        }

        // Render table for absensi (used for both desktop and mobile)
        function renderAbsensiTable(page) {
            const tbody = document.getElementById('absensiTableBody');
            tbody.innerHTML = '';
            
            const startIndex = (page - 1) * absensiItemsPerPage;
            const endIndex = Math.min(startIndex + absensiItemsPerPage, absensiData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const absensi = absensiData[i];
                const row = document.createElement('tr');
                
                // Determine status class
                let statusClass = '';
                if (absensi.status === 'Hadir') {
                    statusClass = 'status-hadir';
                } else if (absensi.status === 'Terlambat') {
                    statusClass = 'status-terlambat';
                }
                
                row.innerHTML = `
                    <td style="min-width: 60px;">${i + 1}</td>
                    <td style="min-width: 200px;">${absensi.nama}</td>
                    <td style="min-width: 120px;">${absensi.tanggal}</td>
                    <td style="min-width: 120px;">${absensi.jamMasuk}</td>
                    <td style="min-width: 120px;">${absensi.jamKeluar}</td>
                    <td style="min-width: 120px;"><span class="status-badge ${statusClass}">${absensi.status}</span></td>
                `;
                tbody.appendChild(row);
            }
        }

        // Render table for ketidakhadiran (used for both desktop and mobile)
        function renderKetidakhadiranTable(page) {
            const tbody = document.getElementById('ketidakhadiranTableBody');
            tbody.innerHTML = '';
            
            const startIndex = (page - 1) * ketidakhadiranItemsPerPage;
            const endIndex = Math.min(startIndex + ketidakhadiranItemsPerPage, ketidakhadiranData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const ketidakhadiran = ketidakhadiranData[i];
                const row = document.createElement('tr');
                
                // Determine status class
                let statusClass = '';
                if (ketidakhadiran.status === 'Izin') {
                    statusClass = 'status-izin';
                } else if (ketidakhadiran.status === 'Cuti') {
                    statusClass = 'status-cuti';
                }
                
                row.innerHTML = `
                    <td style="min-width: 60px;">${i + 1}</td>
                    <td style="min-width: 200px;">${ketidakhadiran.nama}</td>
                    <td style="min-width: 120px;">${ketidakhadiran.tanggalMulai}</td>
                    <td style="min-width: 120px;">${ketidakhadiran.tanggalAkhir}</td>
                    <td style="min-width: 200px;">${ketidakhadiran.alasan}</td>
                    <td style="min-width: 120px;"><span class="status-badge ${statusClass}">${ketidakhadiran.status}</span></td>
                `;
                tbody.appendChild(row);
            }
        }

        // Update desktop pagination buttons for absensi
        function updateAbsensiDesktopPaginationButtons() {
            const prevButton = document.getElementById('absensiPrevPage');
            const nextButton = document.getElementById('absensiNextPage');
            const pageButtons = document.querySelectorAll('#absensiPageNumbers button');
            
            prevButton.disabled = absensiCurrentPage === 1;
            nextButton.disabled = absensiCurrentPage === absensiTotalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === absensiCurrentPage) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Update mobile pagination buttons for absensi
        function updateAbsensiMobilePaginationButtons() {
            const prevButton = document.getElementById('absensiPrevPageMobile');
            const nextButton = document.getElementById('absensiNextPageMobile');
            const pageButtons = document.querySelectorAll('#absensiPageNumbersMobile button');
            
            prevButton.disabled = absensiCurrentPage === 1;
            nextButton.disabled = absensiCurrentPage === absensiTotalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === absensiCurrentPage) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Update desktop pagination buttons for ketidakhadiran
        function updateKetidakhadiranDesktopPaginationButtons() {
            const prevButton = document.getElementById('ketidakhadiranPrevPage');
            const nextButton = document.getElementById('ketidakhadiranNextPage');
            const pageButtons = document.querySelectorAll('#ketidakhadiranPageNumbers button');
            
            prevButton.disabled = ketidakhadiranCurrentPage === 1;
            nextButton.disabled = ketidakhadiranCurrentPage === ketidakhadiranTotalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === ketidakhadiranCurrentPage) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Update mobile pagination buttons for ketidakhadiran
        function updateKetidakhadiranMobilePaginationButtons() {
            const prevButton = document.getElementById('ketidakhadiranPrevPageMobile');
            const nextButton = document.getElementById('ketidakhadiranNextPageMobile');
            const pageButtons = document.querySelectorAll('#ketidakhadiranPageNumbersMobile button');
            
            prevButton.disabled = ketidakhadiranCurrentPage === 1;
            nextButton.disabled = ketidakhadiranCurrentPage === ketidakhadiranTotalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === ketidakhadiranCurrentPage) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>
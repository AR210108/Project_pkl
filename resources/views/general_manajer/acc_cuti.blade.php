<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manajemen Cuti - Dashboard</title>
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
            background-color: #f8fafc;
        }

        .material-icons-outlined {
            font-size: 24px;
            vertical-align: middle;
        }

        /* Card hover effects - removed transition */
        .stat-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
        }

        /* Button styles - removed transition */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #64748b;
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

        .status-disetujui {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .status-menunggu {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }

        .status-ditolak {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        /* Table mobile adjustments */
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

        /* Form input styles - removed transition */
        .form-input {
            border: 1px solid #e2e8f0;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
            min-width: 800px;
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

        /* Minimalist Popup Styles - removed transition */
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
        }

        .minimal-popup-close:hover {
            background-color: #f1f5f9;
            color: #64748b;
        }

        /* Filter Dropdown Styles - removed transition */
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

        /* Pagination - removed transition */
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
            cursor: pointer;
        }

        .desktop-nav-btn:hover:not(:disabled) {
            background-color: #e2e8f0;
        }

        .desktop-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Modal - removed transition */
        .modal {
            opacity: 1;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Main Content Layout - removed transition */
        .main-content {
            margin-left: 0;
            width: 100%;
        }

        /* Header Styles */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Stats Card Styles - COMPACT VERSION */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
        }

        .stat-card.blue::before {
            background: linear-gradient(180deg, #3b82f6, #2563eb);
        }

        .stat-card.red::before {
            background: linear-gradient(180deg, #ef4444, #dc2626);
        }

        .stat-card.green::before {
            background: linear-gradient(180deg, #10b981, #059669);
        }

        .stat-card.yellow::before {
            background: linear-gradient(180deg, #f59e0b, #d97706);
        }

        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .stat-icon.red {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .stat-icon.green {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .stat-icon.yellow {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        /* Alert Styles */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-blue {
            background-color: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
        }

        .alert-icon {
            margin-right: 0.75rem;
            color: #3b82f6;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.125rem;
            font-size: 0.875rem;
        }

        .alert-message {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Action Button Styles - removed transition */
        .action-btn {
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .action-btn.approve {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .action-btn.approve:hover {
            background-color: rgba(16, 185, 129, 0.2);
        }

        .action-btn.reject {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-btn.reject:hover {
            background-color: rgba(239, 68, 68, 0.2);
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    <!-- =========== MAIN CONTENT =========== -->
    <div class="main-content">
        @include('general_manajer.templet.header')
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">Manajemen Cuti</h1>
                    <p class="page-subtitle">Kelola pengajuan cuti karyawan dengan mudah</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <span class="material-icons-outlined text-xl">description</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Total Pengajuan</div>
                            <div class="stat-value" id="stat-total-pengajuan">4</div>
                        </div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <span class="material-icons-outlined text-xl">check_circle</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Disetujui</div>
                            <div class="stat-value" id="stat-disetujui">2</div>
                        </div>
                    </div>

                    <div class="stat-card red">
                        <div class="stat-icon red">
                            <span class="material-icons-outlined text-xl">cancel</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Ditolak</div>
                            <div class="stat-value" id="stat-ditolak">1</div>
                        </div>
                    </div>

                    <div class="stat-card yellow">
                        <div class="stat-icon yellow">
                            <span class="material-icons-outlined text-xl">hourglass_top</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Belum Dikonfirmasi</div>
                            <div class="stat-value" id="stat-menunggu">1</div>
                        </div>
                    </div>
                </div>

                <!-- Data Cuti Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">event_note</span>
                            Data Pengajuan Cuti
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span
                                    class="font-semibold text-text-light" id="cutiCount">4</span> pengajuan</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Search and Filter Section -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <div class="relative w-full md:w-1/3">
                                <span
                                    class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                                <input id="searchCutiInput"
                                    class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                                    placeholder="Cari keterangan atau tanggal..." type="text" />
                            </div>
                            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                                <div class="relative">
                                    <button id="filterCutiBtn"
                                        class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 flex items-center gap-2">
                                        <span class="material-icons-outlined text-sm">filter_list</span>
                                        Filter
                                    </button>
                                    <div id="filterCutiDropdown" class="filter-dropdown">
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterCutiAll" value="all" checked>
                                            <label for="filterCutiAll">Semua Status</label>
                                        </div>
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterCutiDisetujui" value="disetujui">
                                            <label for="filterCutiDisetujui">Disetujui</label>
                                        </div>
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterCutiMenunggu" value="menunggu">
                                            <label for="filterCutiMenunggu">Belum Dikonfirmasi</label>
                                        </div>
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterCutiDitolak" value="ditolak">
                                            <label for="filterCutiDitolak">Ditolak</label>
                                        </div>
                                        <div class="filter-actions">
                                            <button id="applyCutiFilter" class="filter-apply">Terapkan</button>
                                            <button id="resetCutiFilter" class="filter-reset">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Table -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container table-shadow" id="scrollableCutiTable">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 150px;">Tanggal Cuti</th>
                                            <th style="min-width: 120px;">Durasi</th>
                                            <th style="min-width: 300px;">Keterangan</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cutiTableBody">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="cuti-mobile-cards">
                            <!-- Cards will be populated by JavaScript -->
                        </div>

                        <!-- Pagination -->
                        <div id="cutiPaginationContainer" class="desktop-pagination">
                            <button id="cutiPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="cutiPageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="cutiNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

                <footer
                    class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light mt-8">
                    Copyright ©2025 by digicity.id
                </footer>
            </div>
        </main>
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
        document.addEventListener('DOMContentLoaded', function () {
            // ==================== STATIC DATA ====================
            let cutiData = [
                { id: 1, tanggal_mulai: '2024-01-15', tanggal_selesai: '2024-01-16', durasi: 2, keterangan: 'Cuti tahunan untuk liburan keluarga', jenis_cuti: 'tahunan', status: 'disetujui' },
                { id: 2, tanggal_mulai: '2024-02-28', tanggal_selesai: '2024-02-28', durasi: 1, keterangan: 'Cuti sakit', jenis_cuti: 'sakit', status: 'disetujui' },
                { id: 3, tanggal_mulai: '2024-03-10', tanggal_selesai: '2024-03-12', durasi: 3, keterangan: 'Urusan keluarga penting', jenis_cuti: 'penting', status: 'menunggu' },
                { id: 4, tanggal_mulai: '2024-04-05', tanggal_selesai: '2024-04-06', durasi: 2, keterangan: 'Cuti melahirkan', jenis_cuti: 'melahirkan', status: 'ditolak' },
            ];

            let nextCutiId = 5;

            // ==================== STATE MANAGEMENT ====================
            let cutiCurrentPage = 1;
            const itemsPerPage = 5;
            let cutiActiveFilters = ['all'];
            let cutiSearchTerm = '';

            // ==================== INITIALIZATION ====================
            initializeCuti();
            attachEventListeners();

            // ==================== CUTI FUNCTIONS ====================
            function initializeCuti() {
                renderCutiTable();
                renderCutiPagination();
                updateCutiStats();
            }

            function renderCutiTable() {
                const filteredData = getFilteredCutiData();
                const paginatedData = paginateData(filteredData, cutiCurrentPage);

                const tableBody = document.getElementById('cutiTableBody');
                const mobileCards = document.getElementById('cuti-mobile-cards');

                tableBody.innerHTML = '';
                mobileCards.innerHTML = '';

                paginatedData.forEach((item, index) => {
                    const globalIndex = (cutiCurrentPage - 1) * itemsPerPage + index + 1;

                    // Format tanggal
                    const startDate = new Date(item.tanggal_mulai);
                    const endDate = new Date(item.tanggal_selesai);
                    const formattedDate = startDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                    // Status badge
                    let statusBadge = '';
                    if (item.status === 'disetujui') {
                        statusBadge = '<span class="status-badge status-disetujui">Disetujui</span>';
                    } else if (item.status === 'menunggu') {
                        statusBadge = '<span class="status-badge status-menunggu">Belum Dikonfirmasi</span>';
                    } else if (item.status === 'ditolak') {
                        statusBadge = '<span class="status-badge status-ditolak">Ditolak</span>';
                    }

                    // Check if action buttons should be disabled
                    const isDisabled = item.status !== 'menunggu';

                    // Desktop Table Row
                    const row = document.createElement('tr');
                    row.className = 'cuti-row';
                    row.innerHTML = `
                        <td style="min-width: 60px;">${globalIndex}</td>
                        <td style="min-width: 150px;">${formattedDate}</td>
                        <td style="min-width: 120px;">${item.durasi} hari</td>
                        <td style="min-width: 300px;">${item.keterangan}</td>
                        <td style="min-width: 120px;">${statusBadge}</td>
                        <td style="min-width: 100px; text-align: center;">
                            <div class="flex justify-center gap-2">
                                <button class="action-btn approve ${isDisabled ? 'disabled' : ''}" data-id='${item.id}' data-action='approve' ${isDisabled ? 'disabled' : ''}>
                                    <span class="material-icons-outlined">check</span>
                                </button>
                                <button class="action-btn reject ${isDisabled ? 'disabled' : ''}" data-id='${item.id}' data-action='reject' ${isDisabled ? 'disabled' : ''}>
                                    <span class="material-icons-outlined">close</span>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);

                    // Mobile Card
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-base">${formattedDate}</h4>
                                <p class="text-sm text-text-muted-light">${item.durasi} hari</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="action-btn approve ${isDisabled ? 'disabled' : ''}" data-id='${item.id}' data-action='approve' ${isDisabled ? 'disabled' : ''}>
                                    <span class="material-icons-outlined">check</span>
                                </button>
                                <button class="action-btn reject ${isDisabled ? 'disabled' : ''}" data-id='${item.id}' data-action='reject' ${isDisabled ? 'disabled' : ''}>
                                    <span class="material-icons-outlined">close</span>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="text-sm text-gray-700">${item.keterangan}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-text-muted-light">No</p>
                                <p class="font-medium text-sm">${globalIndex}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-muted-light">Status</p>
                                <div>${statusBadge}</div>
                            </div>
                        </div>
                    `;
                    mobileCards.appendChild(card);
                });

                document.getElementById('cutiCount').textContent = filteredData.length;
            }

            function renderCutiPagination() {
                const filteredData = getFilteredCutiData();
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                renderPagination('cuti', totalPages, cutiCurrentPage);
            }

            function getFilteredCutiData() {
                return cutiData.filter(item => {
                    const matchesSearch = !cutiSearchTerm ||
                        item.keterangan.toLowerCase().includes(cutiSearchTerm.toLowerCase()) ||
                        item.tanggal_mulai.includes(cutiSearchTerm);

                    const matchesFilter = cutiActiveFilters.includes('all') ||
                        cutiActiveFilters.includes(item.status);

                    return matchesSearch && matchesFilter;
                });
            }

            // ==================== GENERAL HELPER FUNCTIONS ====================
            function paginateData(data, currentPage) {
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                return data.slice(startIndex, endIndex);
            }

            function renderPagination(type, totalPages, currentPage) {
                const pageNumbersContainer = document.getElementById(`${type}PageNumbers`);
                const prevButton = document.getElementById(`${type}PrevPage`);
                const nextButton = document.getElementById(`${type}NextPage`);

                pageNumbersContainer.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const pageNumber = document.createElement('button');
                    pageNumber.textContent = i;
                    pageNumber.className = `desktop-page-btn ${i === currentPage ? 'active' : ''}`;
                    pageNumber.addEventListener('click', () => goToPage(type, i));
                    pageNumbersContainer.appendChild(pageNumber);
                }

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === totalPages || totalPages === 0;

                prevButton.onclick = () => goToPage(type, currentPage - 1);
                nextButton.onclick = () => goToPage(type, currentPage + 1);
            }

            function goToPage(type, page) {
                if (type === 'cuti') {
                    cutiCurrentPage = page;
                    initializeCuti();
                }
            }

            function updateCutiStats() {
                const totalPengajuan = cutiData.length;
                const disetujui = cutiData.filter(c => c.status === 'disetujui').length;
                const ditolak = cutiData.filter(c => c.status === 'ditolak').length;
                const menunggu = cutiData.filter(c => c.status === 'menunggu').length;

                document.getElementById('stat-total-pengajuan').textContent = totalPengajuan;
                document.getElementById('stat-disetujui').textContent = disetujui;
                document.getElementById('stat-ditolak').textContent = ditolak;
                document.getElementById('stat-menunggu').textContent = menunggu;
            }

            // ==================== EVENT LISTENERS ====================
            function attachEventListeners() {
                // Search and Filter for Cuti
                document.getElementById('searchCutiInput').addEventListener('input', debounce(function (e) {
                    cutiSearchTerm = e.target.value.trim();
                    cutiCurrentPage = 1;
                    initializeCuti();
                }, 300));

                document.getElementById('filterCutiBtn').addEventListener('click', () => toggleDropdown('filterCutiDropdown'));
                document.getElementById('applyCutiFilter').addEventListener('click', applyCutiFilter);
                document.getElementById('resetCutiFilter').addEventListener('click', resetCutiFilter);

                // Popup Close
                document.querySelector('.minimal-popup-close').addEventListener('click', () => {
                    document.getElementById('minimalPopup').classList.remove('show');
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('.relative')) {
                        document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('show'));
                    }
                });

                // Attach listeners to dynamically created buttons
                document.body.addEventListener('click', function (e) {
                    if (e.target.closest('.action-btn')) {
                        const button = e.target.closest('.action-btn');
                        const id = parseInt(button.dataset.id);
                        const action = button.dataset.action;
                        
                        if (action === 'approve') {
                            approveCuti(id);
                        } else if (action === 'reject') {
                            rejectCuti(id);
                        }
                    }
                });
            }

            // ==================== ACTION HANDLERS ====================
            function approveCuti(id) {
                const cutiIndex = cutiData.findIndex(c => c.id === id);
                
                if (cutiIndex !== -1) {
                    simulateApiCall(() => {
                        cutiData[cutiIndex].status = 'disetujui';
                        initializeCuti();
                        showMinimalPopup('Berhasil', 'Pengajuan cuti telah disetujui', 'success');
                    });
                }
            }

            function rejectCuti(id) {
                const cutiIndex = cutiData.findIndex(c => c.id === id);
                
                if (cutiIndex !== -1) {
                    simulateApiCall(() => {
                        cutiData[cutiIndex].status = 'ditolak';
                        initializeCuti();
                        showMinimalPopup('Berhasil', 'Pengajuan cuti telah ditolak', 'warning');
                    });
                }
            }

            // ==================== UI HELPER FUNCTIONS ====================
            function toggleDropdown(dropdownId) {
                document.getElementById(dropdownId).classList.toggle('show');
            }

            function applyCutiFilter() {
                const filterAll = document.getElementById('filterCutiAll').checked;
                const filterDisetujui = document.getElementById('filterCutiDisetujui').checked;
                const filterMenunggu = document.getElementById('filterCutiMenunggu').checked;
                const filterDitolak = document.getElementById('filterCutiDitolak').checked;

                cutiActiveFilters = [];
                if (filterAll) {
                    cutiActiveFilters.push('all');
                } else {
                    if (filterDisetujui) cutiActiveFilters.push('disetujui');
                    if (filterMenunggu) cutiActiveFilters.push('menunggu');
                    if (filterDitolak) cutiActiveFilters.push('ditolak');
                }
                cutiCurrentPage = 1;
                initializeCuti();
                toggleDropdown('filterCutiDropdown');
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${getFilteredCutiData().length} pengajuan`, 'success');
            }

            function resetCutiFilter() {
                document.getElementById('filterCutiAll').checked = true;
                document.getElementById('filterCutiDisetujui').checked = false;
                document.getElementById('filterCutiMenunggu').checked = false;
                document.getElementById('filterCutiDitolak').checked = false;
                cutiActiveFilters = ['all'];
                cutiCurrentPage = 1;
                initializeCuti();
                toggleDropdown('filterCutiDropdown');
                showMinimalPopup('Filter Direset', 'Menampilkan semua pengajuan', 'success');
            }

            function showMinimalPopup(title, message, type = 'success') {
                const popup = document.getElementById('minimalPopup');
                const popupTitle = popup.querySelector('.minimal-popup-title');
                const popupMessage = popup.querySelector('.minimal-popup-message');
                const popupIcon = popup.querySelector('.minimal-popup-icon span');

                popupTitle.textContent = title;
                popupMessage.textContent = message;
                popup.className = `minimal-popup show ${type}`;

                if (type === 'success') popupIcon.textContent = 'check';
                else if (type === 'error') popupIcon.textContent = 'error';
                else if (type === 'warning') popupIcon.textContent = 'warning';

                setTimeout(() => {
                    popup.classList.remove('show');
                }, 3000);
            }

            function simulateApiCall(callback, shouldFail = false) {
                // Show loading state
                const buttons = document.querySelectorAll('.action-btn:not(.disabled)');
                buttons.forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                });

                setTimeout(() => {
                    // Randomly fail for demonstration (10% chance)
                    if (Math.random() < 0.1 || shouldFail) {
                        showMinimalPopup('Error', 'Terjadi kesalahan pada server. Silakan coba lagi.', 'error');
                    } else {
                        callback();
                    }
                    // Reset button state
                    buttons.forEach(btn => {
                        btn.disabled = false;
                        btn.style.opacity = '';
                    });
                }, 800); // Simulate network delay
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
</body>

</html>
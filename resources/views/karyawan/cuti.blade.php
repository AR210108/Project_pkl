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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
<!-- Global Routes Object untuk JavaScript -->
<script>
    // Pastikan route parameter menggunakan nama yang benar
    window.appRoutes = {
        cuti: {
            index: '{{ route("karyawan.cuti.index") }}',
            data: '{{ route("karyawan.cuti.data") }}',
            store: '{{ route("karyawan.cuti.store") }}',
            stats: '{{ route("karyawan.cuti.stats") }}',
            checkAvailableDays: '{{ route("karyawan.cuti.check-available-days") }}',
            calculateDuration: '{{ route("karyawan.cuti.calculate-duration") }}',
            // Gunakan route dengan parameter yang benar
            update: (id) => {
                const url = '{{ route("karyawan.cuti.update", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            destroy: (id) => {
                const url = '{{ route("karyawan.cuti.destroy", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            edit: (id) => {
                const url = '{{ route("karyawan.cuti.edit", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            availableDays: '{{ route("karyawan.cuti.available-days") }}',
            dashboardStats: '{{ route("karyawan.cuti.dashboard-stats") }}',
        }
    };
</script>
    
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

        /* Card hover effects */
        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.05);
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

        /* Form input styles */
        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
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

        .hidden-by-filter {
            display: none !important;
        }

        /* Pagination */
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

        /* Modal */
        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Main Content Layout */
        .main-content {
            transition: margin-left 0.3s ease;
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

        /* Edit Popup Styles */
        .edit-popup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .edit-popup.show {
            opacity: 1;
            visibility: visible;
        }

        .edit-popup-content {
            background: white;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .edit-popup.show .edit-popup-content {
            transform: scale(1);
        }

        .edit-popup-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-popup-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .edit-popup-body {
            padding: 1.5rem;
        }

        .edit-popup-footer {
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    <!-- =========== MAIN CONTENT =========== -->
    <div class="main-content">
        @include('karyawan.templet.header')
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">Manajemen Cuti</h1>
                    <p class="page-subtitle">Kelola pengajuan cuti Anda dengan mudah</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <span class="material-icons-outlined text-xl">calendar_today</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Total Cuti Diberikan</div>
                            <div class="stat-value" id="stat-total-cuti">0</div>
                        </div>
                    </div>

                    <div class="stat-card red">
                        <div class="stat-icon red">
                            <span class="material-icons-outlined text-xl">event_busy</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Cuti Terpakai</div>
                            <div class="stat-value" id="stat-cuti-terpakai">0</div>
                        </div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <span class="material-icons-outlined text-xl">event_available</span>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Cuti Tersisa</div>
                            <div class="stat-value" id="stat-cuti-tersisa">0</div>
                        </div>
                    </div>
                </div>

                <!-- Sisa Cuti Alert -->
                <div class="alert alert-blue" id="sisaCutiAlert" style="display: none;">
                    <span class="material-icons-outlined alert-icon">info</span>
                    <div class="alert-content">
                        <div class="alert-title">Sisa Cuti Tahunan Anda</div>
                        <div class="alert-message">Anda masih memiliki <span class="font-bold text-blue-600" id="sisaCutiText">0 hari</span> cuti tersisa untuk tahun ini</div>
                    </div>
                </div>

                <!-- Error Message Display -->
                @if(isset($error))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Data Tidak Lengkap</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ $error }}</p>
                                
                                @if(isset($showProfileLink) && $showProfileLink)
                                <div class="mt-4">
                                    <a href="{{ route('karyawan.profile') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Lengkapi Profil Sekarang
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Data Cuti Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">event_note</span>
                            Data Pengajuan Cuti
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span
                                    class="font-semibold text-text-light" id="cutiCount">0</span> pengajuan</span>
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
                                        class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
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
                                            <label for="filterCutiMenunggu">Menunggu</label>
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
                                <button id="tambahCutiBtn"
                                    class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                                    <span class="material-icons-outlined">add</span>
                                    <span class="hidden sm:inline">Ajukan Cuti</span>
                                    <span class="sm:hidden">Ajukan</span>
                                </button>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div id="cutiLoading" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                            <p class="mt-2 text-gray-600">Memuat data cuti...</p>
                        </div>

                        <!-- Desktop Table -->
                        <div class="desktop-table" id="cutiDesktopTable" style="display: none;">
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
                        <div class="mobile-cards space-y-4" id="cuti-mobile-cards" style="display: none;">
                            <!-- Cards will be populated by JavaScript -->
                        </div>

                        <!-- No Data Message -->
                        <div id="noCutiData" class="text-center py-8" style="display: none;">
                            <span class="material-icons-outlined text-gray-400 text-6xl mb-4">event_busy</span>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengajuan cuti</h3>
                            <p class="text-gray-600">Mulai dengan mengajukan cuti baru</p>
                        </div>

                        <!-- Pagination -->
                        <div id="cutiPaginationContainer" class="desktop-pagination" style="display: none;">
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

                <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light mt-8">
                    Copyright ©{{ date('Y') }} by digicity.id
                </footer>
            </div>
        </main>
    </div>

    <!-- Tambah Cuti Modal -->
    <div id="tambahCutiModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Ajukan Cuti Baru</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="tambahCutiModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahCutiForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_mulai" required min="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_selesai" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (hari) <span class="text-red-500">*</span></label>
                            <input type="number" name="durasi" required min="1" max="30"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                placeholder="Masukkan durasi cuti">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti <span class="text-red-500">*</span></label>
                            <select name="jenis_cuti" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                                <option value="">Pilih Jenis Cuti</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="sakit">Cuti Sakit</option>
                                <option value="penting">Cuti Penting</option>
                                <option value="melahirkan">Cuti Melahirkan</option>
                                <option value="lainnya">Cuti Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan <span class="text-red-500">*</span></label>
                        <textarea name="keterangan" rows="3" required maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            placeholder="Jelaskan alasan pengajuan cuti"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter, maksimal 500 karakter</p>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg"
                            data-target="tambahCutiModal">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined text-sm">send</span>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Cuti Popup -->
    <div id="editCutiPopup" class="edit-popup">
        <div class="edit-popup-content">
            <div class="edit-popup-header">
                <h3 class="edit-popup-title">Edit Pengajuan Cuti</h3>
                <button id="closeEditPopup" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="edit-popup-body">
                <form id="editCutiForm" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editCutiId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" id="editTanggalMulai" name="tanggal_mulai" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" id="editTanggalSelesai" name="tanggal_selesai" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (hari) <span class="text-red-500">*</span></label>
                            <input type="number" id="editDurasi" name="durasi" required min="1" max="30"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti <span class="text-red-500">*</span></label>
                            <select id="editJenisCuti" name="jenis_cuti" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                                <option value="">Pilih Jenis Cuti</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="sakit">Cuti Sakit</option>
                                <option value="penting">Cuti Penting</option>
                                <option value="melahirkan">Cuti Melahirkan</option>
                                <option value="lainnya">Cuti Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan <span class="text-red-500">*</span></label>
                        <textarea id="editKeterangan" name="keterangan" rows="3" required maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter, maksimal 500 karakter</p>
                    </div>
                </form>
            </div>
            <div class="edit-popup-footer">
                <button type="button" id="cancelEditPopup" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                <button type="button" id="saveEditPopup" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Cuti Modal -->
    <div id="deleteCutiModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="deleteCutiModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="deleteCutiForm">
                    @csrf
                    @method('DELETE')
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus data pengajuan cuti ini?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteCutiId" name="id">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg"
                            data-target="deleteCutiModal">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2">
                            <span class="material-icons-outlined text-sm">delete</span>
                            Hapus
                        </button>
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
        // ==================== GLOBAL CONFIG ====================
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        // Debug: Cetak semua routes untuk memastikan mereka benar
        console.log('🔧 Debug - All Routes:');
        console.log('Data route:', '{{ route("karyawan.cuti.data") }}');
        console.log('Store route:', '{{ route("karyawan.cuti.store") }}');
        console.log('Stats route:', '{{ route("karyawan.cuti.stats") }}');
        console.log('Update route template:', '{{ route("karyawan.cuti.update", ["cuti" => ":id"]) }}');
        console.log('Destroy route template:', '{{ route("karyawan.cuti.destroy", ["cuti" => ":id"]) }}');
        console.log('Edit route template:', '{{ route("karyawan.cuti.edit", ["cuti" => ":id"]) }}');

        // Gunakan appRoutes dari window object
        const API_ROUTES = window.appRoutes?.cuti || {
            data: '{{ route("karyawan.cuti.data") }}',
            store: '{{ route("karyawan.cuti.store") }}',
            stats: '{{ route("karyawan.cuti.stats") }}',
            checkAvailableDays: '{{ route("karyawan.cuti.check-available-days") }}',
            calculateDuration: '{{ route("karyawan.cuti.calculate-duration") }}',
            update: (id) => {
                const url = '{{ route("karyawan.cuti.update", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            destroy: (id) => {
                const url = '{{ route("karyawan.cuti.destroy", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            edit: (id) => {
                const url = '{{ route("karyawan.cuti.edit", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
        };

        console.log('🚀 API Routes initialized:', API_ROUTES);

        // ==================== STATE MANAGEMENT ====================
        let cutiCurrentPage = 1;
        const itemsPerPage = 10;
        let cutiActiveFilters = ['all'];
        let cutiSearchTerm = '';
        let totalCutiPages = 1;

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function () {
            console.log('🎯 Cuti page loaded');
            
            initializeCuti();
            attachEventListeners();
        });

        // ==================== CUTI FUNCTIONS ====================
        async function initializeCuti() {
            console.log('🚀 Initializing cuti page...');
            try {
                await Promise.all([
                    loadCutiData(),
                    loadCutiStats()
                ]);
            } catch (error) {
                console.error('❌ Error initializing cuti:', error);
            }
        }

        async function loadCutiData() {
            showLoading();
            
            try {
                const params = new URLSearchParams({
                    page: cutiCurrentPage,
                    per_page: itemsPerPage,
                    search: cutiSearchTerm,
                    status: cutiActiveFilters.includes('all') ? 'all' : cutiActiveFilters.join(','),
                    _token: CSRF_TOKEN
                });

                const url = `${API_ROUTES.data}?${params}`;
                console.log('📡 Loading cuti data from:', url);
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                console.log('📊 Response status:', response.status, response.statusText);
                
                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error(`Endpoint tidak ditemukan (404). URL: ${url}. Pastikan route 'karyawan.cuti.data' ada di web.php`);
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('✅ Cuti data loaded:', data);
                
                if (data.success) {
                    renderCutiTable(data.data);
                    renderCutiPagination(data.pagination);
                    updateCutiCount(data.pagination.total);
                    hideLoading();
                    
                    // Show/hide no data message
                    if (data.pagination.total === 0) {
                        showNoDataMessage();
                    } else {
                        hideNoDataMessage();
                    }
                } else {
                    throw new Error(data.message || 'Failed to load data');
                }
            } catch (error) {
                console.error('❌ Error loading cuti data:', error);
                showMinimalPopup('Error', error.message || 'Gagal memuat data cuti', 'error');
                hideLoading();
                showNoDataMessage();
                
                // Tampilkan error detail di console
                console.error('Error details:', {
                    searchTerm: cutiSearchTerm,
                    filters: cutiActiveFilters,
                    page: cutiCurrentPage,
                    routes: API_ROUTES
                });
            }
        }

        async function loadCutiStats() {
            try {
                console.log('📡 Loading cuti stats from:', API_ROUTES.stats);
                
                const response = await fetch(API_ROUTES.stats, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    console.warn('⚠️ Stats endpoint not available:', response.status);
                    // Tetap update dengan default values
                    updateCutiStats({
                        total_cuti_tahunan: 12,
                        cuti_terpakai: 0,
                        sisa_cuti: 12,
                    });
                    return;
                }
                
                const data = await response.json();
                console.log('✅ Cuti stats loaded:', data);
                
                if (data.success) {
                    updateCutiStats(data.data);
                    updateSisaCutiAlert(data.data);
                } else {
                    // Fallback to default values
                    updateCutiStats({
                        total_cuti_tahunan: 12,
                        cuti_terpakai: 0,
                        sisa_cuti: 12,
                    });
                }
            } catch (error) {
                console.error('❌ Error loading stats:', error);
                // Tetap update dengan default values
                updateCutiStats({
                    total_cuti_tahunan: 12,
                    cuti_terpakai: 0,
                    sisa_cuti: 12,
                });
            }
        }

        function renderCutiTable(cutiData) {
            const tableBody = document.getElementById('cutiTableBody');
            const mobileCards = document.getElementById('cuti-mobile-cards');

            if (!tableBody || !mobileCards) {
                console.error('❌ Table elements not found!');
                return;
            }

            tableBody.innerHTML = '';
            mobileCards.innerHTML = '';

            if (!cutiData || cutiData.length === 0) {
                console.log('📭 No cuti data to render');
                return;
            }

            cutiData.forEach((item, index) => {
                const globalIndex = (cutiCurrentPage - 1) * itemsPerPage + index + 1;

                // Format tanggal
                let formattedDate = '';
                try {
                    const startDate = new Date(item.tanggal_mulai);
                    const endDate = new Date(item.tanggal_selesai);
                    formattedDate = startDate.toLocaleDateString('id-ID', { 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric' 
                    });
                } catch (e) {
                    formattedDate = item.tanggal_mulai;
                }

                // Status badge
                let statusBadge = '';
                if (item.status === 'disetujui') {
                    statusBadge = '<span class="status-badge status-disetujui">Disetujui</span>';
                } else if (item.status === 'menunggu') {
                    statusBadge = '<span class="status-badge status-menunggu">Menunggu</span>';
                } else if (item.status === 'ditolak') {
                    statusBadge = '<span class="status-badge status-ditolak">Ditolak</span>';
                }

                // Action buttons (only show edit/delete for pending requests)
                let actionButtons = '';
                if (item.status === 'menunggu') {
                    actionButtons = `
                        <div class="flex justify-center gap-2">
                            <button class="edit-cuti-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" data-id='${item.id}' title="Edit">
                                <span class="material-icons-outlined text-sm">edit</span>
                            </button>
                            <button class="delete-cuti-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" data-id='${item.id}' title="Hapus">
                                <span class="material-icons-outlined text-sm">delete</span>
                            </button>
                        </div>
                    `;
                } else {
                    actionButtons = '<span class="text-gray-400 text-sm">Tidak dapat diubah</span>';
                }

                // Desktop Table Row
                const row = document.createElement('tr');
                row.className = 'cuti-row';
                row.innerHTML = `
                    <td style="min-width: 60px;">${globalIndex}</td>
                    <td style="min-width: 150px;">${formattedDate}</td>
                    <td style="min-width: 120px;">${item.durasi} hari</td>
                    <td style="min-width: 300px; white-space: normal;">${item.keterangan || '-'}</td>
                    <td style="min-width: 120px;">${statusBadge}</td>
                    <td style="min-width: 100px; text-align: center;">${actionButtons}</td>
                `;
                tableBody.appendChild(row);

                // Mobile Card
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-base">${formattedDate}</h4>
                            <p class="text-sm text-text-muted-light">${item.durasi} hari • ${item.jenis_cuti || 'Cuti'}</p>
                        </div>
                        <div class="flex gap-2">
                            ${item.status === 'menunggu' ? `
                                <button class="edit-cuti-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" data-id='${item.id}' title="Edit">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button class="delete-cuti-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" data-id='${item.id}' title="Hapus">
                                    <span class="material-icons-outlined text-sm">delete</span>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-sm text-gray-700">${item.keterangan || 'Tidak ada keterangan'}</p>
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
        }

        function renderCutiPagination(pagination) {
            totalCutiPages = pagination.last_page;
            const pageNumbersContainer = document.getElementById('cutiPageNumbers');
            const prevButton = document.getElementById('cutiPrevPage');
            const nextButton = document.getElementById('cutiNextPage');

            if (!pageNumbersContainer || !prevButton || !nextButton) {
                console.error('❌ Pagination elements not found!');
                return;
            }

            pageNumbersContainer.innerHTML = '';

            // Show pagination only if there's more than one page
            const paginationContainer = document.getElementById('cutiPaginationContainer');
            if (paginationContainer) {
                if (totalCutiPages > 1) {
                    paginationContainer.style.display = 'flex';
                } else {
                    paginationContainer.style.display = 'none';
                }
            }

            // Calculate visible page range
            let startPage = Math.max(1, cutiCurrentPage - 2);
            let endPage = Math.min(totalCutiPages, cutiCurrentPage + 2);

            // Adjust if at the beginning
            if (cutiCurrentPage <= 3) {
                endPage = Math.min(5, totalCutiPages);
            }

            // Adjust if at the end
            if (cutiCurrentPage >= totalCutiPages - 2) {
                startPage = Math.max(1, totalCutiPages - 4);
            }

            // Create page buttons
            for (let i = startPage; i <= endPage; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${i === cutiCurrentPage ? 'active' : ''}`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }

            prevButton.disabled = cutiCurrentPage === 1;
            prevButton.onclick = () => goToPage(cutiCurrentPage - 1);

            nextButton.disabled = cutiCurrentPage === totalCutiPages || totalCutiPages === 0;
            nextButton.onclick = () => goToPage(cutiCurrentPage + 1);
        }

        function goToPage(page) {
            cutiCurrentPage = page;
            loadCutiData();
            // Scroll to top of table
            const tableContainer = document.getElementById('scrollableCutiTable');
            if (tableContainer) {
                tableContainer.scrollTop = 0;
            }
        }

        function updateCutiStats(stats) {
            const totalCutiEl = document.getElementById('stat-total-cuti');
            const cutiTerpakaiEl = document.getElementById('stat-cuti-terpakai');
            const cutiTersisaEl = document.getElementById('stat-cuti-tersisa');
            
            if (totalCutiEl) totalCutiEl.textContent = stats.total_cuti_tahunan || 12;
            if (cutiTerpakaiEl) cutiTerpakaiEl.textContent = stats.cuti_terpakai || 0;
            if (cutiTersisaEl) cutiTersisaEl.textContent = stats.sisa_cuti || 0;
        }

        function updateCutiCount(count) {
            const cutiCountEl = document.getElementById('cutiCount');
            if (cutiCountEl) {
                cutiCountEl.textContent = count;
            }
        }

        function updateSisaCutiAlert(stats) {
            const sisaCuti = stats.sisa_cuti || 0;
            const alertElement = document.getElementById('sisaCutiAlert');
            const sisaCutiText = document.getElementById('sisaCutiText');
            
            if (alertElement && sisaCutiText) {
                if (sisaCuti > 0) {
                    sisaCutiText.textContent = `${sisaCuti} hari`;
                    alertElement.style.display = 'flex';
                } else {
                    alertElement.style.display = 'none';
                }
            }
        }

        function showLoading() {
            const loadingEl = document.getElementById('cutiLoading');
            const desktopTable = document.getElementById('cutiDesktopTable');
            const mobileCards = document.getElementById('cuti-mobile-cards');
            const paginationContainer = document.getElementById('cutiPaginationContainer');
            const noData = document.getElementById('noCutiData');
            
            if (loadingEl) loadingEl.style.display = 'block';
            if (desktopTable) desktopTable.style.display = 'none';
            if (mobileCards) mobileCards.style.display = 'none';
            if (paginationContainer) paginationContainer.style.display = 'none';
            if (noData) noData.style.display = 'none';
        }

        function hideLoading() {
            const loadingEl = document.getElementById('cutiLoading');
            const desktopTable = document.getElementById('cutiDesktopTable');
            const mobileCards = document.getElementById('cuti-mobile-cards');
            
            if (loadingEl) loadingEl.style.display = 'none';
            if (desktopTable) desktopTable.style.display = 'block';
            if (mobileCards) mobileCards.style.display = 'block';
        }

        function showNoDataMessage() {
            const noData = document.getElementById('noCutiData');
            const desktopTable = document.getElementById('cutiDesktopTable');
            const mobileCards = document.getElementById('cuti-mobile-cards');
            const paginationContainer = document.getElementById('cutiPaginationContainer');
            
            if (noData) noData.style.display = 'block';
            if (desktopTable) desktopTable.style.display = 'none';
            if (mobileCards) mobileCards.style.display = 'none';
            if (paginationContainer) paginationContainer.style.display = 'none';
        }

        function hideNoDataMessage() {
            const noData = document.getElementById('noCutiData');
            if (noData) noData.style.display = 'none';
        }

        // ==================== EVENT LISTENERS ====================
        function attachEventListeners() {
            console.log('🔗 Attaching event listeners...');
            
            // Modal Controls
            document.querySelectorAll('.close-modal, .cancel-modal').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    closeModal(targetId);
                });
            });

            // Edit Popup Controls
            const closeEditBtn = document.getElementById('closeEditPopup');
            const cancelEditBtn = document.getElementById('cancelEditPopup');
            const saveEditBtn = document.getElementById('saveEditPopup');
            
            if (closeEditBtn) closeEditBtn.addEventListener('click', closeEditPopup);
            if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditPopup);
            if (saveEditBtn) saveEditBtn.addEventListener('click', saveEditChanges);

            // Form Submissions
            const tambahCutiForm = document.getElementById('tambahCutiForm');
            const deleteCutiForm = document.getElementById('deleteCutiForm');
            
            if (tambahCutiForm) {
                tambahCutiForm.addEventListener('submit', handleAddCuti);
            }
            
            if (deleteCutiForm) {
                deleteCutiForm.addEventListener('submit', handleDeleteCuti);
            }

            // Tombol Tambah
            const tambahCutiBtn = document.getElementById('tambahCutiBtn');
            if (tambahCutiBtn) {
                tambahCutiBtn.addEventListener('click', () => {
                    openModal('tambahCutiModal');
                    // Reset form
                    const form = document.getElementById('tambahCutiForm');
                    if (form) form.reset();
                    
                    // Set min date for end date
                    const startDateInput = document.querySelector('#tambahCutiForm input[name="tanggal_mulai"]');
                    const endDateInput = document.querySelector('#tambahCutiForm input[name="tanggal_selesai"]');
                    
                    if (startDateInput && endDateInput) {
                        // Set min untuk tanggal mulai (hari ini)
                        const today = new Date().toISOString().split('T')[0];
                        startDateInput.min = today;
                        
                        startDateInput.addEventListener('change', function() {
                            endDateInput.min = this.value;
                            if (endDateInput.value && endDateInput.value >= this.value) {
                                calculateDuration(this.value, endDateInput.value, document.querySelector('#tambahCutiForm input[name="durasi"]'));
                            }
                        });
                        
                        endDateInput.addEventListener('change', function() {
                            if (startDateInput.value && this.value >= startDateInput.value) {
                                calculateDuration(startDateInput.value, this.value, document.querySelector('#tambahCutiForm input[name="durasi"]'));
                            }
                        });
                    }
                });
            }

            // Search and Filter for Cuti
            const searchInput = document.getElementById('searchCutiInput');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(function (e) {
                    cutiSearchTerm = e.target.value.trim();
                    cutiCurrentPage = 1;
                    loadCutiData();
                }, 500));
            }

            const filterBtn = document.getElementById('filterCutiBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', () => toggleDropdown('filterCutiDropdown'));
            }

            const applyFilterBtn = document.getElementById('applyCutiFilter');
            if (applyFilterBtn) {
                applyFilterBtn.addEventListener('click', applyCutiFilter);
            }

            const resetFilterBtn = document.getElementById('resetCutiFilter');
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', resetCutiFilter);
            }

            // Popup Close
            const popupClose = document.querySelector('.minimal-popup-close');
            if (popupClose) {
                popupClose.addEventListener('click', () => {
                    const popup = document.getElementById('minimalPopup');
                    if (popup) popup.classList.remove('show');
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.relative')) {
                    document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('show'));
                }
            });

            // Attach listeners to dynamically created buttons
            document.body.addEventListener('click', function (e) {
                if (e.target.closest('.edit-cuti-btn')) {
                    const btn = e.target.closest('.edit-cuti-btn');
                    const id = parseInt(btn.dataset.id);
                    openEditCutiPopup(id);
                }
                if (e.target.closest('.delete-cuti-btn')) {
                    const btn = e.target.closest('.delete-cuti-btn');
                    const id = parseInt(btn.dataset.id);
                    openDeleteModal(id);
                }
            });

            // Close edit popup when clicking outside
            const editPopup = document.getElementById('editCutiPopup');
            if (editPopup) {
                editPopup.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditPopup();
                    }
                });
            }
        }

        // ==================== CRUD HANDLERS ====================
        async function handleAddCuti(e) {
            e.preventDefault();
            console.log('📝 Submitting cuti form...');
            
            const formData = new FormData(e.target);
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Mengirim...';
            submitBtn.disabled = true;
            
            try {
                // Validate form
                const tanggalMulai = formData.get('tanggal_mulai');
                const tanggalSelesai = formData.get('tanggal_selesai');
                
                if (!tanggalMulai || !tanggalSelesai) {
                    throw new Error('Tanggal mulai dan selesai harus diisi');
                }
                
                if (new Date(tanggalMulai) > new Date(tanggalSelesai)) {
                    throw new Error('Tanggal selesai harus setelah tanggal mulai');
                }
                
                console.log('📤 Sending to:', API_ROUTES.store);
                
                const response = await fetch(API_ROUTES.store, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                console.log('📨 Add cuti response:', data);
                
                if (data.success) {
                    closeModal('tambahCutiModal');
                    e.target.reset();
                    cutiCurrentPage = 1;
                    await Promise.all([
                        loadCutiData(),
                        loadCutiStats()
                    ]);
                    showMinimalPopup('Berhasil', 'Pengajuan cuti berhasil dikirim', 'success');
                } else {
                    let errorMessage = data.message || 'Gagal mengirim pengajuan';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    }
                    showMinimalPopup('Error', errorMessage, 'error');
                }
            } catch (error) {
                console.error('❌ Error adding cuti:', error);
                showMinimalPopup('Error', error.message || 'Terjadi kesalahan saat mengirim pengajuan', 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }

        async function saveEditChanges() {
            const id = document.getElementById('editCutiId').value;
            if (!id) {
                showMinimalPopup('Error', 'ID cuti tidak valid', 'error');
                return;
            }
            
            const formData = new FormData(document.getElementById('editCutiForm'));
            const submitBtn = document.getElementById('saveEditPopup');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;
            
            try {
                const updateUrl = API_ROUTES.update(id);
                console.log('✏️ Updating cuti at:', updateUrl);
                console.log('✏️ With data:', Object.fromEntries(formData));
                
                const response = await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });
                
                const data = await response.json();
                console.log('✏️ Update cuti response:', data);
                
                if (data.success) {
                    closeEditPopup();
                    await Promise.all([
                        loadCutiData(),
                        loadCutiStats()
                    ]);
                    showMinimalPopup('Berhasil', 'Data cuti berhasil diperbarui', 'success');
                } else {
                    let errorMessage = data.message || 'Gagal memperbarui data';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    }
                    showMinimalPopup('Error', errorMessage, 'error');
                }
            } catch (error) {
                console.error('❌ Error updating cuti:', error);
                showMinimalPopup('Error', error.message || 'Terjadi kesalahan saat memperbarui data', 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }

        async function handleDeleteCuti(e) {
            e.preventDefault();
            const id = document.getElementById('deleteCutiId').value;
            if (!id) {
                showMinimalPopup('Error', 'ID cuti tidak valid', 'error');
                return;
            }
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Menghapus...';
            submitBtn.disabled = true;
            
            try {
                const deleteUrl = API_ROUTES.destroy(id);
                console.log('🗑️ Deleting cuti at:', deleteUrl);
                
                const response = await fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                });
                
                const data = await response.json();
                console.log('🗑️ Delete cuti response:', data);
                
                if (data.success) {
                    closeModal('deleteCutiModal');
                    cutiCurrentPage = 1;
                    await Promise.all([
                        loadCutiData(),
                        loadCutiStats()
                    ]);
                    showMinimalPopup('Berhasil', 'Data cuti berhasil dihapus', 'success');
                } else {
                    showMinimalPopup('Error', data.message || 'Gagal menghapus data', 'error');
                }
            } catch (error) {
                console.error('❌ Error deleting cuti:', error);
                showMinimalPopup('Error', error.message || 'Terjadi kesalahan saat menghapus data', 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }

        // ==================== UI HELPER FUNCTIONS ====================
        async function openEditCutiPopup(id) {
            console.log('📂 Opening edit popup for cuti ID:', id);
            
            try {
                const editUrl = API_ROUTES.edit(id);
                console.log('📄 Fetching edit data from:', editUrl);
                
                const response = await fetch(editUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                
                console.log('📄 Edit response status:', response.status);
                
                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error(`Data cuti dengan ID ${id} tidak ditemukan`);
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('✅ Edit data loaded:', data);
                
                if (data.success) {
                    const cuti = data.data;
                    document.getElementById('editCutiId').value = cuti.id;
                    document.getElementById('editTanggalMulai').value = cuti.tanggal_mulai.split(' ')[0];
                    document.getElementById('editTanggalSelesai').value = cuti.tanggal_selesai.split(' ')[0];
                    document.getElementById('editDurasi').value = cuti.durasi;
                    document.getElementById('editJenisCuti').value = cuti.jenis_cuti;
                    document.getElementById('editKeterangan').value = cuti.keterangan;
                    
                    // Show popup
                    const popup = document.getElementById('editCutiPopup');
                    popup.classList.add('show');
                    
                    // Prevent body scroll
                    document.body.style.overflow = 'hidden';
                } else {
                    throw new Error(data.message || 'Failed to load cuti data');
                }
            } catch (error) {
                console.error('❌ Error loading cuti for edit:', error);
                showMinimalPopup('Error', error.message || 'Gagal memuat data cuti untuk diedit', 'error');
            }
        }

        function closeEditPopup() {
            const popup = document.getElementById('editCutiPopup');
            popup.classList.remove('show');
            
            // Restore body scroll
            document.body.style.overflow = '';
        }

        function openDeleteModal(id) {
            document.getElementById('deleteCutiId').value = id;
            openModal('deleteCutiModal');
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
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
            loadCutiData();
            toggleDropdown('filterCutiDropdown');
        }

        function resetCutiFilter() {
            document.getElementById('filterCutiAll').checked = true;
            document.getElementById('filterCutiDisetujui').checked = false;
            document.getElementById('filterCutiMenunggu').checked = false;
            document.getElementById('filterCutiDitolak').checked = false;
            cutiActiveFilters = ['all'];
            cutiCurrentPage = 1;
            loadCutiData();
            toggleDropdown('filterCutiDropdown');
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

        async function calculateDuration(startDate, endDate, durasiInput) {
            try {
                if (!API_ROUTES.calculateDuration) {
                    // Fallback calculation jika endpoint tidak ada
                    fallbackDurationCalculation(startDate, endDate, durasiInput);
                    return;
                }
                
                const response = await fetch(API_ROUTES.calculateDuration, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        start_date: startDate,
                        end_date: endDate
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success && durasiInput) {
                        durasiInput.value = data.duration;
                    }
                } else {
                    fallbackDurationCalculation(startDate, endDate, durasiInput);
                }
            } catch (error) {
                console.error('Error calculating duration:', error);
                fallbackDurationCalculation(startDate, endDate, durasiInput);
            }
        }

        function fallbackDurationCalculation(startDate, endDate, durasiInput) {
            if (startDate && endDate && durasiInput) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const timeDiff = Math.abs(end.getTime() - start.getTime());
                const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                durasiInput.value = diffDays;
            }
        }
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar User - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
     <link rel="icon" type="image/png" href="{{ asset('logo1.jpeg') }}">
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
        
        .status-admin {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-karyawan {
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
        
        /* SIMPLIFIED SCROLLABLE TABLE */
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
            min-width: 800px; /* Fixed minimum width */
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
        
        /* Scroll indicator */
        .scroll-indicator {
            position: relative;
        }
        
        .scroll-hint {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 6px;
            animation: pulse 2s infinite;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .scroll-hint.hidden {
            display: none;
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
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Hidden class for filtering */
        .hidden-by-filter {
            display: none !important;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
         @include('admin/templet/sider')
    

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Daftar User</h2>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari nama atau email..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <button id="tambahUserBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Tambah User</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">people</span>
                            Daftar User
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount" class="font-semibold text-text-light">{{ count($users) }}</span> user</span>
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
                                            <th style="min-width: 200px;">Username</th>
                                            <th style="min-width: 200px;">Divisi</th>
                                            <th style="min-width: 250px;">Email</th>
                                            <th style="min-width: 120px;">Role</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                        @foreach ($users as $i => $u)
<tr class="user-row"
 data-id="{{ $u->id }}"
 data-name="{{ $u->name }}"
 data-divisi="{{ $u->divisi }}"
 data-email="{{ $u->email }}"
 data-role="{{ $u->role }}"
 data-index="{{ $i }}">
                                                <td style="min-width: 60px;">{{ $i+1 }}</td>
<td style="min-width: 200px;">{{ $u->name }}</td>
<td style="min-width: 200px;">{{ $u->divisi ?? '-' }}</td>
<td style="min-width: 250px;">{{ $u->email }}</td>
<td style="min-width: 120px;">
                                                    <span class="status-badge {{ $u->role == 'admin' ? 'status-admin' : 'status-karyawan' }}">
                                                        {{ $u->role }}
                                                    </span>
                                                </td>
                                                <td style="min-width: 100px; text-align: center;">
                                                    <div class="flex justify-center gap-2">
                                                        <button onclick="openModalEdit({{ $u->id }}, '{{ $u->name }}', '{{ $u->divisi }}', '{{ $u->email }}', '{{ $u->role }}')"
                                                            class="p-1 rounded-full hover:bg-primary/20 text-gray-700">
                                                            <span class="material-icons-outlined">edit</span>
                                                        </button>

                                                        <form action="{{ route('admin.user.delete', $u->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user?')" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1 rounded-full hover:bg-red-500/20 text-gray-700">
                                                                <span class="material-icons-outlined">delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards">
                            @foreach ($users as $i => $u)
                                <div class="user-card bg-white rounded-lg border border-border-light p-4 shadow-sm" data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}" data-role="{{ $u->role }}" data-index="{{ $i }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-base">{{ $u->name }}</h4>
                                            <p class="text-sm text-text-muted-light">{{ $u->email }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="openModalEdit({{ $u->id }}, '{{ $u->name }}', '{{ $u->email }}', '{{ $u->role }}')"
                                                class="p-1 rounded-full hover:bg-primary/20 text-gray-700">
                                                <span class="material-icons-outlined">edit</span>
                                            </button>

                                            <form action="{{ route('admin.user.delete', $u->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded-full hover:bg-red-500/20 text-gray-700">
                                                    <span class="material-icons-outlined">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <p class="text-text-muted-light">No</p>
                                            <p class="font-medium">{{ $i+1 }}</p>
                                        </div>
                                            <div>
        <p class="text-text-muted-light">Divisi</p>
        <p class="font-medium">{{ $u->divisi ?? '-' }}</p>
    </div>
                                        <div>
                                            <p class="text-text-muted-light">Role</p>
                                            <p>
                                                <span class="status-badge {{ $u->role == 'admin' ? 'status-admin' : 'status-karyawan' }}">
                                                    {{ $u->role }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div id="paginationContainer" class="desktop-pagination">
                            <button id="prevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="pageNumbers" class="flex gap-1">
                                <!-- Nomor halaman akan dibuat dengan JavaScript -->
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

    <!-- MODAL TAMBAH -->
    <div id="modalTambah" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah User Baru</h3>
                    <button onclick="closeModalTambah()" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input name="name" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama user">
                    </div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
    <select name="divisi" required
        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
        
        <option value="">-- Pilih Divisi --</option>
        <option value="IT">IT</option>
        <option value="Desain">Desain</option>
        <option value="Marketing">Marketing</option>
        <option value="Admin">Admin</option>
    </select>
</div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input name="email" required type="email" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan email">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input name="password" required type="password" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan password">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModalTambah()" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modalEdit" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit User</h3>
                    <button onclick="closeModalEdit()" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="formEdit" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="edit_name" name="name" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
    <select id="edit_divisi" name="divisi"
        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
        
        <option value="">-- Pilih Divisi --</option>
        <option value="IT">IT</option>
        <option value="Desain">Desain</option>
        <option value="Marketing">Marketing</option>
        <option value="Admin">Admin</option>
    </select>
</div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="edit_email" name="email" type="email" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="edit_role" name="role" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="admin">Admin</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password (kosongkan jika tidak diubah)</label>
                        <input name="password" type="password" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModalEdit()" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update Data</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi variabel
            let currentPage = 1;
            const itemsPerPage = 5;
            let activeFilters = ['admin', 'karyawan'];
            let searchTerm = '';
            
            // Dapatkan semua elemen user
            const userRows = document.querySelectorAll('.user-row');
            const userCards = document.querySelectorAll('.user-card');
            
            // Debug: Log untuk memeriksa data
            console.log('Total user rows found:', userRows.length);
            console.log('Total user cards found:', userCards.length);
            
            // Debug: Log semua role yang ada
            const allRoles = [];
            userRows.forEach(row => {
                const role = row.getAttribute('data-role');
                if (role && !allRoles.includes(role)) {
                    allRoles.push(role);
                }
            });
            console.log('Available roles in data:', allRoles);
            
            // Pastikan semua elemen terlihat pada awalnya
            userRows.forEach(row => {
                row.classList.remove('hidden-by-filter');
                row.style.display = '';
            });
            
            userCards.forEach(card => {
                card.classList.remove('hidden-by-filter');
                card.style.display = '';
            });
            
            // Inisialisasi pagination, filter, dan search
            initializePagination();
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
                
                // Debug: Log jumlah visible rows
                console.log('Visible rows for pagination:', visibleRows.length);
                
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
                const filtered = Array.from(userRows).filter(row => !row.classList.contains('hidden-by-filter'));
                console.log('Filtered rows count:', filtered.length);
                return filtered;
            }
            
            function getFilteredCards() {
                return Array.from(userCards).filter(card => !card.classList.contains('hidden-by-filter'));
            }
            
            function updateVisibleItems() {
                const visibleRows = getFilteredRows();
                const visibleCards = getFilteredCards();
                
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                
                // Debug: Log informasi pagination
                console.log('Current page:', currentPage, 'Start index:', startIndex, 'End index:', endIndex);
                
                // Hide all rows and cards first
                userRows.forEach(row => row.style.display = 'none');
                userCards.forEach(card => card.style.display = 'none');
                
                // Show only the rows for current page
                visibleRows.forEach((row, index) => {
                    if (index >= startIndex && index < endIndex) {
                        row.style.display = '';
                        console.log('Showing row:', row.getAttribute('data-name'));
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
                console.log('Updated total count to:', visibleRows.length);
            }
            
            // === FILTER ===
            function initializeFilter() {
                const filterBtn = document.getElementById('filterBtn');
                const filterDropdown = document.getElementById('filterDropdown');
                const applyFilterBtn = document.getElementById('applyFilter');
                const resetFilterBtn = document.getElementById('resetFilter');
                
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
                
                // Apply filter
                applyFilterBtn.addEventListener('click', function() {
                    const adminCheckbox = document.getElementById('filterAdmin');
                    const karyawanCheckbox = document.getElementById('filterKaryawan');
                    
                    activeFilters = [];
                    if (adminCheckbox.checked) activeFilters.push('admin');
                    if (karyawanCheckbox.checked) activeFilters.push('karyawan');
                    
                    console.log('Active filters:', activeFilters);
                    applyFilters();
                    filterDropdown.classList.remove('show');
                    const visibleCount = getFilteredRows().length;
                    showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} user`, 'success');
                });
                
                // Reset filter
                resetFilterBtn.addEventListener('click', function() {
                    document.getElementById('filterAdmin').checked = true;
                    document.getElementById('filterKaryawan').checked = true;
                    activeFilters = ['admin', 'karyawan'];
                    console.log('Filters reset to:', activeFilters);
                    applyFilters();
                    filterDropdown.classList.remove('show');
                    const visibleCount = getFilteredRows().length;
                    showMinimalPopup('Filter Direset', 'Menampilkan semua user', 'success');
                });
            }
            
            function applyFilters() {
                // Reset to first page
                currentPage = 1;
                
                console.log('Applying filters with search term:', searchTerm);
                
                // Apply filters to rows
                userRows.forEach(row => {
                    const role = row.getAttribute('data-role');
                    const name = row.getAttribute('data-name');
                    const email = row.getAttribute('data-email');
                    
                    console.log('Checking row:', name, 'Role:', role);
                    
                    // Check if role matches filter (case insensitive)
                    const roleMatches = activeFilters.some(filter => filter.toLowerCase() === role.toLowerCase());
                    console.log('Role matches for', name, ':', roleMatches, '(role:', role, 'filters:', activeFilters + ')');
                    
                    // Check if search term matches
                    let searchMatches = true;
                    if (searchTerm) {
                        const searchLower = searchTerm.toLowerCase();
                        searchMatches = name.toLowerCase().includes(searchLower) || 
                                       email.toLowerCase().includes(searchLower);
                    }
                    console.log('Search matches for', name, ':', searchMatches);
                    
                    if (roleMatches && searchMatches) {
                        row.classList.remove('hidden-by-filter');
                        console.log('Row', name, 'is visible');
                    } else {
                        row.classList.add('hidden-by-filter');
                        console.log('Row', name, 'is hidden');
                    }
                });
                
                // Apply same filters to cards
                userCards.forEach(card => {
                    const role = card.getAttribute('data-role');
                    const name = card.getAttribute('data-name');
                    const email = card.getAttribute('data-email');
                    
                    // Check if role matches filter (case insensitive)
                    const roleMatches = activeFilters.some(filter => filter.toLowerCase() === role.toLowerCase());
                    
                    // Check if search term matches
                    let searchMatches = true;
                    if (searchTerm) {
                        const searchLower = searchTerm.toLowerCase();
                        searchMatches = name.toLowerCase().includes(searchLower) || 
                                       email.toLowerCase().includes(searchLower);
                    }
                    
                    if (roleMatches && searchMatches) {
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
                        console.log('Search term changed to:', searchTerm);
                        applyFilters();
                    }, 300); // Debounce search
                });
            }
            
            // === MODAL TAMBAH ===
            const modalTambah = document.getElementById('modalTambah');
            const tambahUserBtn = document.getElementById('tambahUserBtn');
            
            tambahUserBtn.addEventListener('click', function() {
                openModalTambah();
            });
            
            function openModalTambah() {
                modalTambah.classList.remove('hidden');
            }
            
            function closeModalTambah() {
                modalTambah.classList.add('hidden');
            }

            // === MODAL EDIT ===
            const modalEdit = document.getElementById('modalEdit');
window.openModalEdit = function(id, name, divisi, email, role) {
    modalEdit.classList.remove('hidden');

    document.getElementById("edit_name").value = name;
    document.getElementById("edit_divisi").value = divisi;
    document.getElementById("edit_email").value = email;
    document.getElementById("edit_role").value = role;

    document.getElementById("formEdit").action = "/admin/user/update/" + id;
}
            
            function closeModalEdit() {
                modalEdit.classList.add('hidden');
            }
            
            // === MINIMALIST POPUP ===
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
            
            // Initialize scroll detection for table
            function initializeScrollDetection() {
                const scrollableTable = document.getElementById('scrollableTable');
                
                if (scrollableTable) {
                    // Check if scrolling is needed
                    if (scrollableTable.scrollWidth > scrollableTable.clientWidth) {
                        // Add scroll hint if needed
                        if (!document.getElementById('scrollHint')) {
                            const scrollHint = document.createElement('div');
                            scrollHint.id = 'scrollHint';
                            scrollHint.className = 'scroll-hint';
                            scrollHint.innerHTML = '<span class="material-icons-outlined text-sm">east</span> Geser untuk melihat lebih banyak';
                            scrollableTable.appendChild(scrollHint);
                        }
                    }
                    
                    // Add scroll event listener
                    scrollableTable.addEventListener('scroll', function() {
                        const scrollLeft = scrollableTable.scrollLeft;
                        const maxScroll = scrollableTable.scrollWidth - scrollableTable.clientWidth;
                        const scrollHint = document.getElementById('scrollHint');
                        
                        // Hide hint when scrolled to the end
                        if (scrollHint) {
                            if (scrollLeft >= maxScroll - 10) {
                                scrollHint.classList.add('hidden');
                            } else {
                                scrollHint.classList.remove('hidden');
                            }
                        }
                    });
                }
            }
            
            // Handle form submissions
            document.querySelector('#modalTambah form').addEventListener('submit', function(e) {
                // Let the form submit normally
                closeModalTambah();
                showMinimalPopup('Berhasil', 'User berhasil ditambahkan', 'success');
            });
            
            document.querySelector('#modalEdit form').addEventListener('submit', function(e) {
                // Let the form submit normally
                closeModalEdit();
                showMinimalPopup('Berhasil', 'Data user berhasil diperbarui', 'success');
            });
        });
    </script>
</body>
</html>
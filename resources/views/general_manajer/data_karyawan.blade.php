<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Karyawan - Dashboard</title>
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
        
        .status-intern {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-permanent {
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
            min-width: 1600px; /* Fixed minimum width */
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
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    @include('general_manajer/templet/header')
    
    <!-- Main Content Container -->
    <div class="main-content">
        <main class="flex-1 flex flex-col bg-background-light">
            <div class="flex-1 p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Data Karyawan</h2>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Search..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <button class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                            Filter
                        </button>
                        <button id="tambahKaryawanBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Tambah Karyawan</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">people</span>
                            Daftar Karyawan
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light">15</span> karyawan</span>
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
                                            <th style="min-width: 200px;">Nama Lengkap</th>
                                            <th style="min-width: 350px;">Alamat Lengkap</th>
                                            <th style="min-width: 120px;">Jenis Kelamin</th>
                                            <th style="min-width: 150px;">Nomor Telepon</th>
                                            <th style="min-width: 180px;">Jabatan</th>
                                            <th style="min-width: 150px;">Divisi</th>
                                            <th style="min-width: 140px;">Status Karyawan</th>
                                            <th style="min-width: 250px;">Email Address</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="desktopTableBody">
                                        <!-- Data rows will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="mobile-cards">
                            <!-- Mobile cards will be populated by JavaScript -->
                        </div>
                        
                        <!-- Desktop Pagination -->
                        <div id="desktopPaginationContainer" class="desktop-pagination hidden">
                            <button id="desktopPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="desktopPageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="desktopNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                        
                        <!-- Mobile Pagination -->
                        <div class="mobile-pagination md:hidden flex justify-center items-center gap-2 mt-4">
                            <button id="prevPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="pageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="nextPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
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

    <!-- Modal Tambah Karyawan -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Karyawan</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" id="tambahNama" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="tambahAlamat" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select id="tambahJenisKelamin" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                        <input type="tel" id="tambahTelp" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <input type="text" id="tambahJabatan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                        <input type="text" id="tambahDivisi" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="tambahStatus" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Status</option>
                            <option value="Magang">Magang</option>
                            <option value="Karyawan Tetap">Karyawan Tetap</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="tambahEmail" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Karyawan -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Karyawan</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="editId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" id="editNama" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="editAlamat" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select id="editJenisKelamin" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                        <input type="tel" id="editTelp" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <input type="text" id="editJabatan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                        <input type="text" id="editDivisi" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Magang">Magang</option>
                            <option value="Karyawan Tetap">Karyawan Tetap</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="editEmail" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Karyawan -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus karyawan <span id="deleteNama" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons-outlined">close</span>
        </button>
    </div>

    <script>
        // Sample data for karyawan
        const karyawanData = [
            { id: 1, nama: "Budi Santoso", alamat: "Jl. Merdeka No. 10, Jakarta Pusat, DKI Jakarta 10110, Indonesia", jenisKelamin: "Laki-laki", telp: "081234567890", jabatan: "Frontend Developer", divisi: "IT Department", status: "Karyawan Tetap", email: "budi.santoso@company.com" },
            { id: 2, nama: "Citra Lestari", alamat: "Jl. Pahlawan No. 5, Surabaya, Jawa Timur 60245, Indonesia", jenisKelamin: "Perempuan", telp: "082345678901", jabatan: "Backend Developer", divisi: "IT Department", status: "Karyawan Tetap", email: "citra.lestari@company.com" },
            { id: 3, nama: "Dewi Anggraini", alamat: "Jl. Sudirman No. 22, Bandung, Jawa Barat 40115, Indonesia", jenisKelamin: "Perempuan", telp: "083456789012", jabatan: "UI/UX Designer", divisi: "Marketing", status: "Magang", email: "dewi.anggraini@company.com" },
            { id: 4, nama: "Eko Wijoyo", alamat: "Jl. Gajah Mada No. 8, Medan, Sumatera Utara 20112, Indonesia", jenisKelamin: "Laki-laki", telp: "084567890123", jabatan: "Project Manager", divisi: "Human Resources", status: "Karyawan Tetap", email: "eko.wijoyo@company.com" },
            { id: 5, nama: "Ahmad Fauzi", alamat: "Jl. Thamrin No. 15, Jakarta Pusat, DKI Jakarta 10250, Indonesia", jenisKelamin: "Laki-laki", telp: "085678901234", jabatan: "Full Stack Developer", divisi: "IT Department", status: "Karyawan Tetap", email: "ahmad.fauzi@company.com" },
            { id: 6, nama: "Siti Nurhaliza", alamat: "Jl. Gatot Subroto No. 30, Jakarta Selatan, DKI Jakarta 12190, Indonesia", jenisKelamin: "Perempuan", telp: "086789012345", jabatan: "HR Manager", divisi: "Human Resources", status: "Karyawan Tetap", email: "siti.nurhaliza@company.com" },
            { id: 7, nama: "Rudi Hermawan", alamat: "Jl. Diponegoro No. 7, Semarang, Jawa Tengah 50241, Indonesia", jenisKelamin: "Laki-laki", telp: "087890123456", jabatan: "DevOps Engineer", divisi: "IT Department", status: "Karyawan Tetap", email: "rudi.hermawan@company.com" },
            { id: 8, nama: "Linda Wijaya", alamat: "Jl. Ahmad Yani No. 20, Surabaya, Jawa Timur 60234, Indonesia", jenisKelamin: "Perempuan", telp: "088901234567", jabatan: "QA Engineer", divisi: "IT Department", status: "Karyawan Tetap", email: "linda.wijaya@company.com" },
            { id: 9, nama: "Joko Prasetyo", alamat: "Jl. Sudirman No. 45, Bandung, Jawa Barat 40181, Indonesia", jenisKelamin: "Laki-laki", telp: "089012345678", jabatan: "Data Analyst", divisi: "IT Department", status: "Magang", email: "joko.prasetyo@company.com" },
            { id: 10, nama: "Maya Sari", alamat: "Jl. Merdeka No. 12, Yogyakarta, DI Yogyakarta 55122, Indonesia", jenisKelamin: "Perempuan", telp: "081234567891", jabatan: "Product Manager", divisi: "Product", status: "Karyawan Tetap", email: "maya.sari@company.com" },
            { id: 11, nama: "Doni Kusumo", alamat: "Jl. Pemuda No. 8, Malang, Jawa Timur 65112, Indonesia", jenisKelamin: "Laki-laki", telp: "082345678912", jabatan: "Mobile Developer", divisi: "IT Department", status: "Karyawan Tetap", email: "doni.kusumo@company.com" },
            { id: 12, nama: "Ratna Dewi", alamat: "Jl. Hayam Wuruk No. 25, Jakarta Barat, DKI Jakarta 11110, Indonesia", jenisKelamin: "Perempuan", telp: "083456789123", jabatan: "Business Analyst", divisi: "Business Development", status: "Karyawan Tetap", email: "ratna.dewi@company.com" },
            { id: 13, nama: "Hendra Wijaya", alamat: "Jl. Gatot Subroto No. 17, Jakarta Pusat, DKI Jakarta 10270, Indonesia", jenisKelamin: "Laki-laki", telp: "084567891234", jabatan: "System Administrator", divisi: "IT Department", status: "Karyawan Tetap", email: "hendra.wijaya@company.com" },
            { id: 14, nama: "Fitri Handayani", alamat: "Jl. Sudirman No. 33, Jakarta Selatan, DKI Jakarta 12180, Indonesia", jenisKelamin: "Perempuan", telp: "085678912345", jabatan: "Marketing Manager", divisi: "Marketing", status: "Karyawan Tetap", email: "fitri.handayani@company.com" },
            { id: 15, nama: "Andi Pratama", alamat: "Jl. Rasuna Said No. 10, Jakarta Selatan, DKI Jakarta 12940, Indonesia", jenisKelamin: "Laki-laki", telp: "086789123456", jabatan: "Software Architect", divisi: "IT Department", status: "Karyawan Tetap", email: "andi.pratama@company.com" }
        ];

        // Pagination variables
        const itemsPerPage = 10;
        let currentPage = 1;
        const totalPages = Math.ceil(karyawanData.length / itemsPerPage);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize pagination
            initializePagination();
            
            // Initialize scroll detection
            initializeScrollDetection();
            
            // Modal elements
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Buttons
            const tambahKaryawanBtn = document.getElementById('tambahKaryawanBtn');
            const closeModals = document.querySelectorAll('.close-modal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const closeToastBtn = document.getElementById('closeToast');
            
            // Forms
            const tambahForm = document.getElementById('tambahForm');
            const editForm = document.getElementById('editForm');
            
            // Show tambah modal
            tambahKaryawanBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
                tambahForm.reset();
            });
            
            // Close modals
            closeModals.forEach(btn => {
                btn.addEventListener('click', function() {
                    tambahModal.classList.add('hidden');
                    editModal.classList.add('hidden');
                    deleteModal.classList.add('hidden');
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === tambahModal) {
                    tambahModal.classList.add('hidden');
                }
                if (event.target === editModal) {
                    editModal.classList.add('hidden');
                }
                if (event.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
            });
            
            // Handle tambah form submission
            tambahForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showToast('Karyawan berhasil ditambahkan!');
                tambahModal.classList.add('hidden');
                tambahForm.reset();
            });
            
            // Handle edit form submission
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showToast('Data karyawan berhasil diperbarui!');
                editModal.classList.add('hidden');
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteId').value;
                showToast('Karyawan berhasil dihapus!');
                deleteModal.classList.add('hidden');
            });
            
            // Close toast notification
            closeToastBtn.addEventListener('click', function() {
                toast.classList.add('translate-y-20', 'opacity-0');
            });
            
            // Function to show toast notification
            function showToast(message) {
                toastMessage.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                
                // Auto hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }
        });

        // Initialize scroll detection for table
        function initializeScrollDetection() {
            const scrollableTable = document.getElementById('scrollableTable');
            const scrollHint = document.getElementById('scrollHint');
            
            if (scrollableTable && scrollHint) {
                // Check if scrolling is needed
                if (scrollableTable.scrollWidth > scrollableTable.clientWidth) {
                    scrollHint.classList.remove('hidden');
                } else {
                    scrollHint.classList.add('hidden');
                }
                
                // Add scroll event listener
                scrollableTable.addEventListener('scroll', function() {
                    const scrollLeft = scrollableTable.scrollLeft;
                    const maxScroll = scrollableTable.scrollWidth - scrollableTable.clientWidth;
                    
                    // Hide hint when scrolled to the end
                    if (scrollLeft >= maxScroll - 10) {
                        scrollHint.classList.add('hidden');
                    } else {
                        scrollHint.classList.remove('hidden');
                    }
                });
            }
        }

        // Initialize pagination
        function initializePagination() {
            // Show/hide pagination based on data count
            if (karyawanData.length > itemsPerPage) {
                document.getElementById('desktopPaginationContainer').classList.remove('hidden');
                initDesktopPagination();
                initMobilePagination();
            } else {
                document.getElementById('desktopPaginationContainer').classList.add('hidden');
                // Show all data if less than or equal to itemsPerPage
                renderDesktopTable(1);
                renderMobileCards(1);
            }
        }

        // Desktop pagination functionality
        function initDesktopPagination() {
            const pageNumbersContainer = document.getElementById('desktopPageNumbers');
            const prevButton = document.getElementById('desktopPrevPage');
            const nextButton = document.getElementById('desktopNextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${
                    i === currentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToDesktopPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) goToDesktopPage(currentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) goToDesktopPage(currentPage + 1);
            });
            
            // Initialize first page
            renderDesktopTable(1);
        }

        // Mobile pagination functionality
        function initMobilePagination() {
            const pageNumbersContainer = document.getElementById('pageNumbers');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm ${
                    i === currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'
                }`;
                pageNumber.addEventListener('click', () => goToMobilePage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) goToMobilePage(currentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) goToMobilePage(currentPage + 1);
            });
            
            // Initialize first page
            renderMobileCards(1);
        }

        // Go to specific desktop page
        function goToDesktopPage(page) {
            currentPage = page;
            renderDesktopTable(page);
            updateDesktopPaginationButtons();
            
            // Reset scroll position when changing pages
            const scrollableTable = document.getElementById('scrollableTable');
            if (scrollableTable) {
                scrollableTable.scrollLeft = 0;
                // Reinitialize scroll detection
                initializeScrollDetection();
            }
        }

        // Go to specific mobile page
        function goToMobilePage(page) {
            currentPage = page;
            renderMobileCards(page);
            updateMobilePaginationButtons();
        }

        // Render desktop table for specific page
        function renderDesktopTable(page) {
            const tbody = document.getElementById('desktopTableBody');
            tbody.innerHTML = '';
            
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, karyawanData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const karyawan = karyawanData[i];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="min-width: 60px;">${i + 1}</td>
                    <td style="min-width: 200px;">${karyawan.nama}</td>
                    <td style="min-width: 350px;">${karyawan.alamat}</td>
                    <td style="min-width: 120px;">${karyawan.jenisKelamin}</td>
                    <td style="min-width: 150px;">${karyawan.telp}</td>
                    <td style="min-width: 180px;">${karyawan.jabatan}</td>
                    <td style="min-width: 150px;">${karyawan.divisi}</td>
                    <td style="min-width: 140px;"><span class="status-badge ${karyawan.status === 'Karyawan Tetap' ? 'status-permanent' : 'status-intern'}">${karyawan.status}</span></td>
                    <td style="min-width: 250px;">${karyawan.email}</td>
                    <td style="min-width: 100px; text-align: center;">
                        <div class="flex justify-center gap-2">
                            <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                onclick="openEditModal(${karyawan.id}, '${karyawan.nama}', '${karyawan.alamat}', '${karyawan.jenisKelamin}', '${karyawan.telp}', '${karyawan.jabatan}', '${karyawan.divisi}', '${karyawan.status}', '${karyawan.email}')">
                                <span class="material-icons-outlined">edit</span>
                            </button>
                            <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                onclick="openDeleteModal(${karyawan.id}, '${karyawan.nama}')">
                                <span class="material-icons-outlined">delete</span>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            }
        }

        // Render mobile cards for specific page
        function renderMobileCards(page) {
            const container = document.getElementById('mobile-cards');
            container.innerHTML = '';
            
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, karyawanData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const karyawan = karyawanData[i];
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-base">${karyawan.nama}</h4>
                            <p class="text-sm text-text-muted-light">${karyawan.jabatan}</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                onclick="openEditModal(${karyawan.id}, '${karyawan.nama}', '${karyawan.alamat}', '${karyawan.jenisKelamin}', '${karyawan.telp}', '${karyawan.jabatan}', '${karyawan.divisi}', '${karyawan.status}', '${karyawan.email}')">
                                <span class="material-icons-outlined">edit</span>
                            </button>
                            <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                onclick="openDeleteModal(${karyawan.id}, '${karyawan.nama}')">
                                <span class="material-icons-outlined">delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-text-muted-light">Jenis Kelamin</p>
                            <p class="font-medium">${karyawan.jenisKelamin}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">No. Telp</p>
                            <p class="font-medium">${karyawan.telp}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Divisi</p>
                            <p class="font-medium">${karyawan.divisi}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Status</p>
                            <p><span class="status-badge ${karyawan.status === 'Karyawan Tetap' ? 'status-permanent' : 'status-intern'}">${karyawan.status}</span></p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Email</p>
                            <p class="font-medium">${karyawan.email}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-text-muted-light">Alamat</p>
                        <p class="font-medium">${karyawan.alamat}</p>
                    </div>
                `;
                container.appendChild(card);
            }
        }

        // Update desktop pagination buttons
        function updateDesktopPaginationButtons() {
            const prevButton = document.getElementById('desktopPrevPage');
            const nextButton = document.getElementById('desktopNextPage');
            const pageButtons = document.querySelectorAll('#desktopPageNumbers button');
            
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === currentPage) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Update mobile pagination buttons
        function updateMobilePaginationButtons() {
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            const pageButtons = document.querySelectorAll('#pageNumbers button');
            
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
            
            pageButtons.forEach((btn, index) => {
                if (index + 1 === currentPage) {
                    btn.className = 'page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm bg-primary text-white';
                } else {
                    btn.className = 'page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm bg-gray-200 text-gray-600';
                }
            });
        }

        // Open edit modal with data
        function openEditModal(id, nama, alamat, jenisKelamin, telp, jabatan, divisi, status, email) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editAlamat').value = alamat;
            document.getElementById('editJenisKelamin').value = jenisKelamin;
            document.getElementById('editTelp').value = telp;
            document.getElementById('editJabatan').value = jabatan;
            document.getElementById('editDivisi').value = divisi;
            document.getElementById('editStatus').value = status;
            document.getElementById('editEmail').value = email;
            
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Open delete modal with data
        function openDeleteModal(id, nama) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteNama').textContent = nama;
            
            document.getElementById('deleteModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
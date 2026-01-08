<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas - Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        .status-todo {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-progress {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-done {
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
        
        /* SCROLLABLE TABLE - TANPA TEKS SCROLL */
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
            min-width: 1400px; /* Fixed minimum width */
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
        
        /* Truncate text style */
        .truncate-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    @include('general_manajer/templet/header')
    
    <!-- Main Content Container -->
    <div class="main-content">
        <main class="flex-1 flex flex-col bg-background-light">
            <div class="flex-1 p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Kelola Tugas</h2>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Search..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <button class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                            Filter
                        </button>
                        <button id="buatTugasBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Buat Tugas Baru</span>
                            <span class="sm:hidden">Buat</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">task_alt</span>
                            Daftar Tugas
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="totalCount">0</span> tugas</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE - TANPA INDICATOR -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container table-shadow" id="scrollableTable">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 200px;">Judul</th>
                                            <th style="min-width: 300px;">Deskripsi</th>
                                            <th style="min-width: 120px;">Deadline</th>
                                            <th style="min-width: 150px;">Karyawan</th>
                                            <th style="min-width: 150px;">Projek Manajer</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 180px; text-align: center;">Aksi</th>
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
                        
                        <!-- Desktop Pagination - SELALU DITAMPILKAN -->
                        <div id="desktopPaginationContainer" class="desktop-pagination">
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

    <!-- Modal Tambah Tugas -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Buat Tugas Baru</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                        <input type="text" id="tambahJudul" name="judul" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="tambahDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="tambahDeadline" name="deadline" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                        <select id="tambahKaryawan" name="karyawan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Karyawan</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Projek Manajer</label>
                        <select id="tambahManajer" name="manajer" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Projek Manajer</option>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="tambahStatus" name="status" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="To Do">To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Tugas</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                        <input type="text" id="editJudul" name="judul" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" id="editDeadline" name="deadline" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                        <select id="editKaryawan" name="karyawan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Projek Manajer</label>
                        <select id="editManajer" name="manajer" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" name="status" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="To Do">To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Tugas -->
    <div id="detailModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Tugas</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">ID Tugas</h4>
                            <p class="text-base font-medium" id="detailId"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Judul Tugas</h4>
                            <p class="text-base font-medium" id="detailJudul"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Deadline</h4>
                            <p class="text-base font-medium" id="detailDeadline"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                            <p id="detailStatus"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Karyawan</h4>
                            <p class="text-base font-medium" id="detailKaryawan"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Projek Manajer</h4>
                            <p class="text-base font-medium" id="detailManajer"></p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h4>
                        <p class="text-base" id="detailDeskripsi"></p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Tugas -->
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
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus tugas <span id="deleteJudul" class="font-semibold"></span>?</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            // --- VARIABEL GLOBAL ---
            let currentPage = 1;
            let totalPages = 1;
            let searchQuery = '';

            // --- ELEMEN DOM ---
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            const detailModal = document.getElementById('detailModal');
            const deleteModal = document.getElementById('deleteModal');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const totalCount = document.getElementById('totalCount');
            const searchInput = document.getElementById('searchInput');
            
            // --- INISIALISASI ---
            fetchTugas(); // Ambil data saat halaman dimuat
            setupEventListeners();

            // --- FUNGSI UTAMA ---
            function fetchTugas(page = 1) {
                currentPage = page;
                let url = `/kelola_tugas/data?page=${page}`;
                
                if (searchQuery) {
                    url += `&search=${encodeURIComponent(searchQuery)}`;
                }
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        renderDesktopTable(data.data);
                        renderMobileCards(data.data);
                        renderPagination(data.links);
                        totalCount.textContent = data.total;
                        totalPages = data.last_page;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function renderDesktopTable(tugasList) {
                const tbody = document.getElementById('desktopTableBody');
                tbody.innerHTML = '';
                
                if (tugasList.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data tugas</td>`;
                    tbody.appendChild(row);
                    return;
                }
                
                tugasList.forEach((tugas, index) => {
                    const row = document.createElement('tr');
                    let statusClass = getStatusClass(tugas.status);
                    const startIndex = (currentPage - 1) * 3; // 3 adalah items per page
                    const truncatedDesc = tugas.deskripsi.length > 50 
                        ? tugas.deskripsi.substring(0, 50) + '...' 
                        : tugas.deskripsi;
                    
                    row.innerHTML = `
                        <td style="min-width: 60px;">${startIndex + index + 1}</td>
                        <td style="min-width: 200px;">${tugas.judul}</td>
                        <td style="min-width: 300px;" class="truncate-text" title="${tugas.deskripsi}">${truncatedDesc}</td>
                        <td style="min-width: 120px;">${tugas.deadline}</td>
                        <td style="min-width: 150px;">${tugas.karyawan}</td>
                        <td style="min-width: 150px;">${tugas.manajer}</td>
                        <td style="min-width: 120px;"><span class="status-badge ${statusClass}">${tugas.status}</span></td>
                        <td style="min-width: 180px; text-align: center;">
                            <div class="flex justify-center gap-2">
                                <button class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" onclick="openDetailModal(${tugas.id})" title="Lihat Detail"><span class="material-icons-outlined">visibility</span></button>
                                <button class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" onclick="openEditModal(${tugas.id})" title="Edit"><span class="material-icons-outlined">edit</span></button>
                                <button class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" onclick="openDeleteModal(${tugas.id}, '${tugas.judul}')" title="Hapus"><span class="material-icons-outlined">delete</span></button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }

            function renderMobileCards(tugasList) {
                const container = document.getElementById('mobile-cards');
                container.innerHTML = '';
                
                if (tugasList.length === 0) {
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm text-center text-gray-500';
                    card.textContent = 'Tidak ada data tugas';
                    container.appendChild(card);
                    return;
                }
                
                tugasList.forEach(tugas => {
                    let statusClass = getStatusClass(tugas.status);
                    const truncatedDesc = tugas.deskripsi.length > 80 
                        ? tugas.deskripsi.substring(0, 80) + '...' 
                        : tugas.deskripsi;
                    
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-base">${tugas.judul}</h4>
                                <p class="text-sm text-text-muted-light">Deadline: ${tugas.deadline}</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-1 rounded-full hover:bg-primary/20 text-gray-700" onclick="openDetailModal(${tugas.id})" title="Lihat Detail"><span class="material-icons-outlined">visibility</span></button>
                                <button class="p-1 rounded-full hover:bg-primary/20 text-gray-700" onclick="openEditModal(${tugas.id})" title="Edit"><span class="material-icons-outlined">edit</span></button>
                                <button class="p-1 rounded-full hover:bg-red-500/20 text-gray-700" onclick="openDeleteModal(${tugas.id}, '${tugas.judul}')" title="Hapus"><span class="material-icons-outlined">delete</span></button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><p class="text-text-muted-light">Karyawan</p><p class="font-medium">${tugas.karyawan}</p></div>
                            <div><p class="text-text-muted-light">Projek Manajer</p><p class="font-medium">${tugas.manajer}</p></div>
                            <div><p class="text-text-muted-light">Status</p><p><span class="status-badge ${statusClass}">${tugas.status}</span></p></div>
                        </div>
                        <div class="mt-3">
                            <p class="text-text-muted-light">Deskripsi</p>
                            <p class="font-medium">${truncatedDesc}</p>
                            ${tugas.deskripsi.length > 80 ? `<button class="text-primary text-sm mt-1" onclick="openDetailModal(${tugas.id})">Lihat selengkapnya</button>` : ''}
                        </div>
                    `;
                    container.appendChild(card);
                });
            }
            
            function renderPagination(links) {
                // Desktop Pagination
                const desktopPageNumbers = document.getElementById('desktopPageNumbers');
                desktopPageNumbers.innerHTML = '';
                links.slice(1, -1).forEach(link => {
                    const btn = document.createElement('button');
                    btn.innerHTML = link.label;
                    btn.className = `desktop-page-btn ${link.active ? 'active' : ''}`;
                    btn.disabled = !link.url;
                    btn.onclick = () => fetchTugas(new URL(link.url).searchParams.get('page'));
                    desktopPageNumbers.appendChild(btn);
                });
                document.getElementById('desktopPrevPage').onclick = () => links[0].url ? fetchTugas(new URL(links[0].url).searchParams.get('page')) : null;
                document.getElementById('desktopNextPage').onclick = () => links[links.length-1].url ? fetchTugas(new URL(links[links.length-1].url).searchParams.get('page')) : null;
                
                // Mobile Pagination (sama dengan desktop)
                const mobilePageNumbers = document.getElementById('pageNumbers');
                mobilePageNumbers.innerHTML = '';
                links.slice(1, -1).forEach(link => {
                    const btn = document.createElement('button');
                    btn.innerHTML = link.label;
                    btn.className = `page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm ${link.active ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'}`;
                    btn.disabled = !link.url;
                    btn.onclick = () => fetchTugas(new URL(link.url).searchParams.get('page'));
                    mobilePageNumbers.appendChild(btn);
                });
                document.getElementById('prevPage').onclick = () => links[0].url ? fetchTugas(new URL(links[0].url).searchParams.get('page')) : null;
                document.getElementById('nextPage').onclick = () => links[links.length-1].url ? fetchTugas(new URL(links[links.length-1].url).searchParams.get('page')) : null;
            }

            // --- EVENT LISTENERS ---
            function setupEventListeners() {
                // Modal
                document.querySelectorAll('.close-modal').forEach(btn => {
                    btn.addEventListener('click', () => {
                        tambahModal.classList.add('hidden');
                        editModal.classList.add('hidden');
                        detailModal.classList.add('hidden');
                        deleteModal.classList.add('hidden');
                    });
                });

                // Form Tambah
                document.getElementById('tambahForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch('/kelola_tugas', {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        showToast(data.message);
                        tambahModal.classList.add('hidden');
                        this.reset();
                        fetchTugas(currentPage);
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        if (err.response && err.response.status === 422) {
                            err.response.json().then(errors => {
                                let errorMessage = 'Error: ';
                                for (const field in errors.errors) {
                                    errorMessage += errors.errors[field].join(', ') + ' ';
                                }
                                showToast(errorMessage);
                            });
                        } else {
                            showToast('Terjadi kesalahan saat menambah tugas');
                        }
                    });
                });

                // Form Edit
                document.getElementById('editForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const id = document.getElementById('editId').value;
                    const formData = new FormData(this);
                    formData.append('_method', 'PUT'); // Method spoofing untuk PUT

                    fetch(`/kelola_tugas/${id}`, {
                        method: 'POST', // Kirim sebagai POST karena FormData tidak mendukung PUT
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        showToast(data.message);
                        editModal.classList.add('hidden');
                        fetchTugas(currentPage);
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        if (err.response && err.response.status === 422) {
                            err.response.json().then(errors => {
                                let errorMessage = 'Error: ';
                                for (const field in errors.errors) {
                                    errorMessage += errors.errors[field].join(', ') + ' ';
                                }
                                showToast(errorMessage);
                            });
                        } else {
                            showToast('Terjadi kesalahan saat memperbarui tugas');
                        }
                    });
                });

                // Tombol Hapus
                document.getElementById('confirmDelete').addEventListener('click', function() {
                    const id = document.getElementById('deleteId').value;
                    fetch(`/kelola_tugas/${id}`, {
                        method: 'DELETE',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        showToast(data.message);
                        deleteModal.classList.add('hidden');
                        fetchTugas(currentPage);
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        showToast('Terjadi kesalahan saat menghapus tugas');
                    });
                });

                // Toast
                document.getElementById('closeToast').addEventListener('click', () => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                });

                // Search
                searchInput.addEventListener('input', function() {
                    searchQuery = this.value;
                    fetchTugas(1); // Reset ke halaman pertama saat searching
                });
            }

            // --- FUNGSI MODAL ---
            window.openDetailModal = function(id) {
                fetch(`/kelola_tugas/${id}`)
                    .then(res => res.json())
                    .then(tugas => {
                        document.getElementById('detailId').textContent = '#' + tugas.id;
                        document.getElementById('detailJudul').textContent = tugas.judul;
                        document.getElementById('detailDeskripsi').textContent = tugas.deskripsi;
                        document.getElementById('detailDeadline').textContent = tugas.deadline;
                        document.getElementById('detailKaryawan').textContent = tugas.karyawan;
                        document.getElementById('detailManajer').textContent = tugas.manajer;
                        document.getElementById('detailStatus').innerHTML = `<span class="status-badge ${getStatusClass(tugas.status)}">${tugas.status}</span>`;
                        detailModal.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        showToast('Terjadi kesalahan saat mengambil detail tugas');
                    });
            };

            window.openEditModal = function(id) {
                fetch(`/kelola_tugas/${id}`)
                    .then(res => res.json())
                    .then(tugas => {
                        document.getElementById('editId').value = tugas.id;
                        document.getElementById('editJudul').value = tugas.judul;
                        document.getElementById('editDeskripsi').value = tugas.deskripsi;
                        document.getElementById('editDeadline').value = tugas.deadline;
                        document.getElementById('editKaryawan').value = tugas.karyawan;
                        document.getElementById('editManajer').value = tugas.manajer;
                        document.getElementById('editStatus').value = tugas.status;
                        editModal.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        showToast('Terjadi kesalahan saat mengambil data tugas untuk diedit');
                    });
            };

            window.openDeleteModal = function(id, judul) {
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteJudul').textContent = judul;
                deleteModal.classList.remove('hidden');
            };

            // --- UTILITAS ---
            function getStatusClass(status) {
                if (status === 'To Do') return 'status-todo';
                if (status === 'In Progress') return 'status-progress';
                if (status === 'Done') return 'status-done';
                return '';
            }

            function showToast(message) {
                toastMessage.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }

            // Tombol buat tugas
            document.getElementById('buatTugasBtn').addEventListener('click', () => {
                tambahModal.classList.remove('hidden');
                document.getElementById('tambahForm').reset();
            });
        });
    </script>
</body>
</html>
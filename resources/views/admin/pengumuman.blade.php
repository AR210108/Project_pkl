<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pengumuman Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
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
            min-width: 1000px;
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
    </style>
    <!-- Add CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        @include('admin.templet.sider')

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Pengumuman</h2>

                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span
                            class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput"
                            class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                            placeholder="Cari judul atau isi pengumuman..." type="text" />
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
                                    <label for="filterAll">Semua Kategori</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterUmum" value="umum">
                                    <label for="filterUmum">Umum</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterPenting" value="penting">
                                    <label for="filterPenting">Penting</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterInternal" value="internal">
                                    <label for="filterInternal">Internal</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                        <button id="createBtn"
                            class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Buat Pengumuman</span>
                            <span class="sm:hidden">Buat</span>
                        </button>
                    </div>
                </div>

                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">campaign</span>
                            Daftar Pengumuman
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span id="totalCount"
                                    class="font-semibold text-text-light">0</span> pengumuman</span>
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
                                            <th style="min-width: 200px;">Judul Informasi</th>
                                            <th style="min-width: 300px;">Isi Pesan</th>
                                            <th style="min-width: 150px;">Kepada</th>
                                            <th style="min-width: 150px;">Lampiran</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    @php use Illuminate\Support\Str; @endphp

                                    <tbody>
                                        @forelse ($pengumuman as $index => $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                <td style="min-width: 60px;">
                                                    {{ $index + 1 }}
                                                </td>

                                                <td style="min-width: 200px;">
                                                    {{ $item->judul_informasi }}
                                                </td>

                                                <td style="min-width: 300px;">
                                                    {{ Str::limit($item->isi_pesan, 80) }}
                                                </td>

                                                <td style="min-width: 150px;">
                                                    {{ $item->judul }}
                                                </td>

                                                <td style="min-width: 150px;">
                                                    @if ($item->lampiran)
                                                        <a href="{{ asset('storage/pengumuman/' . $item->lampiran) }}"
                                                            target="_blank" class="text-primary hover:underline">
                                                            Lihat Lampiran
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td style="min-width: 100px; text-align: center;">
                                                    <button class="text-blue-600 hover:underline">Edit</button>
                                                    <button class="text-red-600 hover:underline">Hapus</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Tidak ada data pengumuman
                                                </td>
                                            </tr>
                                        @endforelse
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

    <!-- Modal Popup -->
    <div id="modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div
            class="bg-card-light dark:bg-card-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold text-text-light dark:text-text-dark"></h3>
                    <button id="closeModal"
                        class="text-muted-light dark:text-muted-dark hover:text-text-light dark:hover:text-text-dark">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>

                <div id="modalContent" class="mb-6">
                    <!-- Content will be dynamically inserted here -->
                </div>

                <div class="flex justify-end gap-3">
                    <button id="cancelBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                    <button id="confirmBtn" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
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
        // Declare all global variables at the top level
        let currentPage = 1;
        const itemsPerPage = 5;
        let activeFilters = ['all'];
        let searchTerm = '';
        let allPengumuman = []; // Store all pengumuman data

        document.addEventListener('DOMContentLoaded', function() {
            // Load pengumuman data when page loads
            loadPengumumanData();

            // Event listener for search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        searchTerm = this.value.trim();
                        currentPage = 1; // Reset to first page on search
                        applyFilters();
                    }, 300); // Debounce search
                });
            }

            // Event listener for filter button
            const filterBtn = document.getElementById('filterBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    document.getElementById('filterDropdown').classList.toggle('show');
                });
            }

            // Close filter dropdown when clicking outside
            document.addEventListener('click', function() {
                document.getElementById('filterDropdown').classList.remove('show');
            });

            // Prevent dropdown from closing when clicking inside
            document.getElementById('filterDropdown').addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Get modal elements
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');

            // Get buttons
            const createBtn = document.getElementById('createBtn');

            // Current action (create, edit, delete)
            let currentAction = '';
            let currentId = null;

            // Show modal function
            function showModal(title, content, confirmText = 'Simpan', confirmClass = 'bg-primary') {
                modalTitle.textContent = title;
                modalContent.innerHTML = content;
                confirmBtn.textContent = confirmText;
                confirmBtn.className =
                    `px-4 py-2 ${confirmClass} text-white rounded-lg font-medium hover:bg-opacity-90 transition-colors`;
                modal.classList.remove('hidden');
            }

            // Hide modal function
            function hideModal() {
                modal.classList.add('hidden');
                currentAction = '';
                currentId = null;
            }

            // Create button event
            createBtn.addEventListener('click', function() {
                currentAction = 'create';
                const content = `
                    <form id="pengumumanForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul</label>
                            <input type="text" name="judul" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul Informasi</label>
                            <input type="text" name="judul_informasi" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Isi Pesan</label>
                            <textarea name="isi_pesan" rows="4" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Kepada</label>
                            <select name="kepada" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Umum">Umum</option>
                                <option value="Penting">Penting</option>
                                <option value="Internal">Internal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Lampiran</label>
                            <div class="flex items-center gap-3">
                                <input type="file" name="lampiran" id="lampiranInput" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <button type="button" id="selectFileBtn" class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    <span class="material-icons-outlined">upload_file</span>
                                    Pilih File
                                </button>
                                <span id="fileName" class="text-sm text-muted-light dark:text-muted-dark">Tidak ada file yang dipilih</span>
                            </div>
                        </div>
                    </form>
                `;
                showModal('Buat Pengumuman Baru', content, 'Simpan', 'bg-primary');

                // File input handling
                document.getElementById('selectFileBtn').addEventListener('click', function() {
                    document.getElementById('lampiranInput').click();
                });

                document.getElementById('lampiranInput').addEventListener('change', function() {
                    const fileName = this.files[0] ? this.files[0].name :
                        'Tidak ada file yang dipilih';
                    document.getElementById('fileName').textContent = fileName;
                });
            });

            // Close modal events
            closeModal.addEventListener('click', hideModal);
            cancelBtn.addEventListener('click', hideModal);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!modal.classList.contains('hidden')) {
                        hideModal();
                    }
                }
            });

            // Close popup when clicking the close button
            document.querySelector('.minimal-popup-close').addEventListener('click', function() {
                document.getElementById('minimalPopup').classList.remove('show');
            });

            // Confirm button event
            confirmBtn.addEventListener('click', function() {
                if (currentAction === 'create') {
                    const form = document.getElementById('pengumumanForm');
                    const formData = new FormData(form);

                    fetch('/pengumuman', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showMinimalPopup('Berhasil', data.message, 'success');
                                // Reload data
                                loadPengumumanData();
                                hideModal();
                            } else {
                                // Handle validation errors
                                if (data.errors) {
                                    let errorMessage = '';
                                    for (const [key, value] of Object.entries(data.errors)) {
                                        errorMessage += `${value.join(', ')}\n`;
                                    }
                                    showMinimalPopup('Error', errorMessage, 'error');
                                } else {
                                    showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.',
                                        'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                } else if (currentAction === 'edit') {
                    const form = document.getElementById('pengumumanForm');
                    const formData = new FormData(form);

                    fetch(`/pengumuman/${currentId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-HTTP-Method-Override': 'PUT'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showMinimalPopup('Berhasil', data.message, 'success');
                                // Reload data
                                loadPengumumanData();
                                hideModal();
                            } else {
                                // Handle validation errors
                                if (data.errors) {
                                    let errorMessage = '';
                                    for (const [key, value] of Object.entries(data.errors)) {
                                        errorMessage += `${value.join(', ')}\n`;
                                    }
                                    showMinimalPopup('Error', errorMessage, 'error');
                                } else {
                                    showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.',
                                        'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                } else if (currentAction === 'delete') {
                    fetch(`/pengumuman/${currentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showMinimalPopup('Berhasil', data.message, 'success');
                                // Reload data
                                loadPengumumanData();
                                hideModal();
                            } else {
                                showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.',
                                    'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showMinimalPopup('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                }
            });

            // Initialize filter
            initializeFilter();

            // Initialize scroll detection for table
            initializeScrollDetection();
        });

        // Load pengumuman data from database
        function loadPengumumanData() {
            const loadingRow = document.getElementById('loadingRow');
            const noDataRow = document.getElementById('noDataRow');
            const tableBody = document.getElementById('desktopTableBody');
            const mobileCards = document.getElementById('mobile-cards');

            // Show loading
            loadingRow.classList.remove('hidden');
            noDataRow.classList.add('hidden');

            // Remove existing pengumuman rows and cards
            const existingRows = tableBody.querySelectorAll('.pengumuman-row');
            existingRows.forEach(row => row.remove());

            const existingCards = mobileCards.querySelectorAll('.pengumuman-card');
            existingCards.forEach(card => card.remove());

            .then(response => {
                    if (!response.ok) {
                        throw new Error(`Server error: ${response.status} ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    loadingRow.classList.add('hidden');

                    if (data.data && data.data.length > 0) {
                        // Store all pengumuman data
                        allPengumuman = data.data;

                        // Populate table with pengumuman data
                        data.data.forEach((pengumuman, index) => {
                            // Create desktop table row
                            const row = document.createElement('tr');
                            row.className = 'pengumuman-row';
                            row.setAttribute('data-id', pengumuman.id);
                            row.setAttribute('data-judul', pengumuman.judul);
                            row.setAttribute('data-judul-informasi', pengumuman.judul_informasi);
                            row.setAttribute('data-isi-pesan', pengumuman.isi_pesan);
                            row.setAttribute('data-kepada', pengumuman.kepada);
                            row.setAttribute('data-lampiran', pengumuman.lampiran || '');

                            // Truncate description if too long
                            let isiPesanDisplay = pengumuman.isi_pesan || '';
                            if (isiPesanDisplay.length > 50) {
                                isiPesanDisplay = isiPesanDisplay.substring(0, 50) + '...';
                            }

                            // Format lampiran display
                            let lampiranDisplay = '-';
                            if (pengumuman.lampiran) {
                                lampiranDisplay =
                                    `<a class="text-primary hover:underline" href="${pengumuman.lampiran_url}" target="_blank">${pengumuman.lampiran}</a>`;
                            }

                            row.innerHTML = `
                            <td style="min-width: 60px;">${index + 1}.</td>
                            <td style="min-width: 200px;">${pengumuman.judul_informasi}</td>
                            <td style="min-width: 300px;">${isiPesanDisplay}</td>
                            <td style="min-width: 150px;">${pengumuman.kepada}</td>
                            <td style="min-width: 150px;">${lampiranDisplay}</td>
                            <td style="min-width: 100px; text-align: center;">
                                <div class="flex justify-center gap-2">
                                    <button class="edit-pengumuman-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${pengumuman.id}"
                                        data-judul="${pengumuman.judul}"
                                        data-judul-informasi="${pengumuman.judul_informasi}"
                                        data-isi-pesan="${pengumuman.isi_pesan}"
                                        data-kepada="${pengumuman.kepada}"
                                        data-lampiran="${pengumuman.lampiran || ''}">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
                                    <button class="delete-pengumuman-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                        data-id="${pengumuman.id}"
                                        data-judul="${pengumuman.judul}">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;

                            tableBody.appendChild(row);

                            // Create mobile card
                            const card = document.createElement('div');
                            card.className =
                                'pengumuman-card bg-white rounded-lg border border-border-light p-4 shadow-sm';
                            card.setAttribute('data-id', pengumuman.id);
                            card.setAttribute('data-judul', pengumuman.judul);
                            card.setAttribute('data-judul-informasi', pengumuman.judul_informasi);
                            card.setAttribute('data-isi-pesan', pengumuman.isi_pesan);
                            card.setAttribute('data-kepada', pengumuman.kepada);

                            card.innerHTML = `
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-base">${pengumuman.judul_informasi}</h4>
                                    <p class="text-sm text-text-muted-light">${pengumuman.kepada}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="edit-pengumuman-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" 
                                        data-id="${pengumuman.id}"
                                        data-judul="${pengumuman.judul}"
                                        data-judul-informasi="${pengumuman.judul_informasi}"
                                        data-isi-pesan="${pengumuman.isi_pesan}"
                                        data-kepada="${pengumuman.kepada}"
                                        data-lampiran="${pengumuman.lampiran || ''}">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
                                    <button class="delete-pengumuman-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" 
                                        data-id="${pengumuman.id}"
                                        data-judul="${pengumuman.judul}">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <p class="text-text-muted-light">No</p>
                                    <p class="font-medium">${index + 1}</p>
                                </div>
                                <div>
                                    <p class="text-text-muted-light">Lampiran</p>
                                    <p class="font-medium">${pengumuman.lampiran ? 'Ada' : 'Tidak ada'}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-text-muted-light">Isi Pesan</p>
                                    <p class="font-medium">${isiPesanDisplay}</p>
                                </div>
                            </div>
                        `;

                            mobileCards.appendChild(card);
                        });

                        // Add event listeners to edit buttons
                        document.querySelectorAll('.edit-pengumuman-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                currentAction = 'edit';
                                currentId = this.getAttribute('data-id');
                                const judul = this.getAttribute('data-judul');
                                const judulInformasi = this.getAttribute('data-judul-informasi');
                                const isiPesan = this.getAttribute('data-isi-pesan');
                                const kepada = this.getAttribute('data-kepada');
                                const lampiran = this.getAttribute('data-lampiran');

                                const content = `
                                <form id="pengumumanForm" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul</label>
                                        <input type="text" name="judul" value="${judul}" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul Informasi</label>
                                        <input type="text" name="judul_informasi" value="${judulInformasi}" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Isi Pesan</label>
                                        <textarea name="isi_pesan" rows="4" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>${isiPesan}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Kepada</label>
                                        <select name="kepada" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Umum" ${kepada === 'Umum' ? 'selected' : ''}>Umum</option>
                                            <option value="Penting" ${kepada === 'Penting' ? 'selected' : ''}>Penting</option>
                                            <option value="Internal" ${kepada === 'Internal' ? 'selected' : ''}>Internal</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Lampiran</label>
                                        <div class="flex items-center gap-3">
                                            <input type="file" name="lampiran" id="lampiranInput" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            <button type="button" id="selectFileBtn" class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                                <span class="material-icons-outlined">upload_file</span>
                                                Ganti File
                                            </button>
                                            <span id="fileName" class="text-sm text-muted-light dark:text-muted-dark">${lampiran || 'Tidak ada file'}</span>
                                        </div>
                                    </div>
                                </form>
                            `;
                                showModal('Edit Pengumuman', content, 'Update', 'bg-blue-500');

                                // File input handling
                                document.getElementById('selectFileBtn').addEventListener('click',
                                    function() {
                                        document.getElementById('lampiranInput').click();
                                    });

                                document.getElementById('lampiranInput').addEventListener('change',
                                    function() {
                                        const fileName = this.files[0] ? this.files[0].name :
                                            'Tidak ada file';
                                        document.getElementById('fileName').textContent = fileName;
                                    });
                            });
                        });

                        // Add event listeners to delete buttons
                        document.querySelectorAll('.delete-pengumuman-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                currentAction = 'delete';
                                currentId = this.getAttribute('data-id');
                                const judul = this.getAttribute('data-judul');

                                const content = `
                                <div class="text-center py-4">
                                    <span class="material-icons-outlined text-red-500 text-5xl mb-4">warning</span>
                                    <p class="text-lg font-medium text-text-light dark:text-text-dark mb-2">Konfirmasi Hapus</p>
                                    <p class="text-muted-light dark:text-muted-dark">Apakah Anda yakin ingin menghapus pengumuman "${judul}"? Tindakan ini tidak dapat dibatalkan.</p>
                                </div>
                            `;
                                showModal('Hapus Pengumuman', content, 'Hapus', 'bg-red-500');
                            });
                        });

                        // Apply filters and initialize pagination
                        applyFilters();
                    } else {
                        // Show no data message
                        noDataRow.classList.remove('hidden');

                        // Update total count
                        document.getElementById('totalCount').textContent = '0';
                    }
                })
                .catch(error => {
                    loadingRow.classList.add('hidden');
                    console.error('Error loading pengumuman data:', error);
                    showMinimalPopup('Error', 'Gagal memuat data pengumuman: ' + error.message, 'error');
                });
        }

        // Minimalist Popup function
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

        // Initialize filter
        function initializeFilter() {
            const filterAll = document.getElementById('filterAll');
            const applyFilterBtn = document.getElementById('applyFilter');
            const resetFilterBtn = document.getElementById('resetFilter');

            // Handle "All" checkbox
            filterAll.addEventListener('change', function() {
                if (this.checked) {
                    // Uncheck all other checkboxes
                    document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)').forEach(
                    cb => {
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
                const filterUmum = document.getElementById('filterUmum');
                const filterPenting = document.getElementById('filterPenting');
                const filterInternal = document.getElementById('filterInternal');

                activeFilters = [];
                if (filterAll.checked) {
                    activeFilters.push('all');
                } else {
                    if (filterUmum.checked) activeFilters.push('umum');
                    if (filterPenting.checked) activeFilters.push('penting');
                    if (filterInternal.checked) activeFilters.push('internal');
                }

                currentPage = 1; // Reset to first page when filter is applied
                applyFilters();
                document.getElementById('filterDropdown').classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} pengumuman`, 'success');
            });

            // Reset filter
            resetFilterBtn.addEventListener('click', function() {
                document.getElementById('filterAll').checked = true;
                document.getElementById('filterUmum').checked = false;
                document.getElementById('filterPenting').checked = false;
                document.getElementById('filterInternal').checked = false;
                activeFilters = ['all'];
                currentPage = 1; // Reset to first page when filter is reset
                applyFilters();
                document.getElementById('filterDropdown').classList.remove('show');
                const visibleCount = getFilteredRows().length;
                showMinimalPopup('Filter Direset', 'Menampilkan semua pengumuman', 'success');
            });
        }

        // Initialize pagination
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
            return Array.from(document.querySelectorAll('.pengumuman-row')).filter(row => !row.classList.contains(
                'hidden-by-filter'));
        }

        function getFilteredCards() {
            return Array.from(document.querySelectorAll('.pengumuman-card')).filter(card => !card.classList.contains(
                'hidden-by-filter'));
        }

        function updateVisibleItems() {
            const visibleRows = getFilteredRows();
            const visibleCards = getFilteredCards();

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Hide all rows and cards first
            document.querySelectorAll('.pengumuman-row').forEach(row => row.style.display = 'none');
            document.querySelectorAll('.pengumuman-card').forEach(card => card.style.display = 'none');

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
        }

        function applyFilters() {
            // Apply filters to rows
            document.querySelectorAll('.pengumuman-row').forEach(row => {
                const judul = row.getAttribute('data-judul').toLowerCase();
                const judulInformasi = row.getAttribute('data-judul-informasi').toLowerCase();
                const isiPesan = row.getAttribute('data-isi-pesan').toLowerCase();
                const kepada = row.getAttribute('data-kepada').toLowerCase();

                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => kepada.includes(filter.toLowerCase()));
                }

                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = judul.includes(searchLower) ||
                        judulInformasi.includes(searchLower) ||
                        isiPesan.includes(searchLower);
                }

                if (statusMatches && searchMatches) {
                    row.classList.remove('hidden-by-filter');
                } else {
                    row.classList.add('hidden-by-filter');
                }
            });

            // Apply same filters to cards
            document.querySelectorAll('.pengumuman-card').forEach(card => {
                const judul = card.getAttribute('data-judul').toLowerCase();
                const judulInformasi = card.getAttribute('data-judul-informasi').toLowerCase();
                const isiPesan = card.getAttribute('data-isi-pesan').toLowerCase();
                const kepada = card.getAttribute('data-kepada').toLowerCase();

                // Check if status matches filter
                let statusMatches = false;
                if (activeFilters.includes('all')) {
                    statusMatches = true;
                } else {
                    statusMatches = activeFilters.some(filter => kepada.includes(filter.toLowerCase()));
                }

                // Check if search term matches
                let searchMatches = true;
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    searchMatches = judul.includes(searchLower) ||
                        judulInformasi.includes(searchLower) ||
                        isiPesan.includes(searchLower);
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

            // Update total count
            document.getElementById('totalCount').textContent = getFilteredRows().length;
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
    </script>
</body>

</html>

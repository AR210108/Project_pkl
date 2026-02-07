<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi - Dashboard Manajer</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .material-icons-outlined { font-size: 24px; vertical-align: middle; }
        
        /* Custom Scrollbar for Tables */
        .scrollable-table-container::-webkit-scrollbar { height: 8px; width: 8px; }
        .scrollable-table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .scrollable-table-container::-webkit-scrollbar-track { background: #f1f5f9; }

        /* Card & Table Styling */
        .card { transition: all 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); }
        
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        
        /* Status Colors */
        .bg-hadir { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .bg-terlambat { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .bg-izin { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .bg-cuti { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }
        .bg-sakit { background-color: rgba(251, 146, 60, 0.15); color: #9a3412; }
        .bg-dinas-luar { background-color: rgba(139, 92, 246, 0.15); color: #5b21b6; }
        .bg-tidak-masuk { background-color: rgba(107, 114, 128, 0.15); color: #374151; }
        
        /* Approval Status Colors */
        .bg-pending { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .bg-approved { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .bg-rejected { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }

        .icon-container { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; }
        
        /* Table */
        .data-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .data-table th, .data-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f8fafc; font-weight: 600; color: #374151; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .data-table tbody tr:hover { background: #f3f4f6; }
        .form-input { border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: all 0.2s ease; width: 100%; box-sizing: border-box;}
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); outline: none; }
        
        /* Tabs */
        .tab-nav { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; }
        .tab-button { padding: 0.75rem 1.5rem; background: none; border: none; font-size: 0.875rem; font-weight: 500; color: #6b7280; cursor: pointer; position: relative; transition: color 0.2s; }
        .tab-button:hover { color: #3b82f6; }
        .tab-button.active { color: #3b82f6; font-weight: 600; }
        .tab-button.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background-color: #3b82f6; }
        
        /* Panel */
        .panel { background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; margin-bottom: 1.5rem; }
        .panel-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .panel-title { font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        .panel-body { padding: 1.5rem; }
        
        /* Filter Dropdown */
        .filter-dropdown { display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 20; min-width: 220px; padding: 1rem; margin-top: 0.5rem; }
        .filter-dropdown.show { display: block; }
        .filter-option { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; cursor: pointer; }
        .filter-option:hover { background-color: #f8fafc; }
        .filter-actions { display: flex; gap: 0.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
        
        /* Mobile Responsive */
        .desktop-table { display: block; }
        .mobile-cards { display: none; }
        @media (max-width: 768px) {
            .desktop-table { display: none; }
            .mobile-cards { display: block; }
        }
        .mobile-card { background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem; }
        .mobile-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .mobile-card-title { font-weight: 600; color: #111827; }
        .mobile-card-body { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .mobile-card-item { display: flex; flex-direction: column; }
        .mobile-card-label { font-size: 0.75rem; color: #6b7280; }
        .mobile-card-value { font-weight: 500; font-size: 0.875rem; color: #374151; }
        
        /* Pagination */
        .pagination-container { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1.5rem; }
        .nav-btn { padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280; }
        .nav-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .page-btn { padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; background: white; cursor: pointer; font-size: 0.875rem; color: #374151; transition: all 0.2s;}
        .page-btn:hover { background-color: #f8fafc; }
        .page-btn.active { background-color: #3b82f6; color: white; border-color: #3b82f6; }
        
        /* Modal */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 50; backdrop-filter: blur(2px); }
        .modal.show { display: flex; }
        .modal-content { background: white; border-radius: 0.75rem; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); animation: slideDown 0.3s ease-out; }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Utility */
        .hidden { display: none !important; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <!-- Main Container -->
    <main class="p-4 sm:p-6 lg:p-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Absensi</h1>
                    <p class="text-sm text-gray-500 mt-1">Dashboard Manajemen Kehadiran Karyawan</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 bg-white px-3 py-1.5 rounded-full shadow-sm border border-gray-200">
                    <span class="material-icons-outlined text-base text-blue-500">calendar_today</span>
                    <span id="currentDateDisplay"></span>
                </div>
            </div>

            <!-- Statistics Cards (Auto-updated) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <!-- Cards generated by JS -->
                <div id="statsContainer" class="contents"></div>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-nav">
                <button id="tabAbsensi" class="tab-button active" onclick="switchTab('absensi')">
                    <span class="material-icons-outlined align-middle mr-2 text-sm">fact_check</span>
                    Data Absensi
                </button>
                <button id="tabKetidakhadiran" class="tab-button" onclick="switchTab('ketidakhadiran')">
                    <span class="material-icons-outlined align-middle mr-2 text-sm">assignment_late</span>
                    Daftar Ketidakhadiran
                </button>
            </div>

            <!-- Search and Filter Controls -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="relative w-full md:w-1/3">
                    <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                    <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input text-sm" placeholder="Cari nama karyawan..." type="text" />
                </div>
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <!-- Date Filter -->
                    <div class="relative">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">date_range</span>
                        <input id="dateFilter" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 form-input text-sm cursor-pointer" placeholder="Filter Tanggal" type="date" />
                    </div>
                    
                    <!-- Status Filter Dropdown -->
                    <div class="relative">
                        <button id="filterBtn" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2 text-sm font-medium shadow-sm">
                            <span class="material-icons-outlined text-sm">filter_list</span>
                            Filter Status
                        </button>
                        <div id="filterDropdown" class="filter-dropdown">
                            <div class="filter-option" onclick="toggleFilter('all')">
                                <input type="radio" name="filter" id="filterAll" value="all" checked>
                                <label for="filterAll" class="cursor-pointer w-full">Semua Status</label>
                            </div>
                            <div class="h-px bg-gray-100 my-1"></div>
                            <div class="filter-option" onclick="toggleFilter('Hadir')"><input type="radio" name="filter" value="Hadir"> <label class="cursor-pointer w-full">Hadir</label></div>
                            <div class="filter-option" onclick="toggleFilter('Terlambat')"><input type="radio" name="filter" value="Terlambat"> <label class="cursor-pointer w-full">Terlambat</label></div>
                            <div class="filter-option" onclick="toggleFilter('Izin')"><input type="radio" name="filter" value="Izin"> <label class="cursor-pointer w-full">Izin</label></div>
                            <div class="filter-option" onclick="toggleFilter('Sakit')"><input type="radio" name="filter" value="Sakit"> <label class="cursor-pointer w-full">Sakit</label></div>
                            <div class="filter-option" onclick="toggleFilter('Cuti')"><input type="radio" name="filter" value="Cuti"> <label class="cursor-pointer w-full">Cuti</label></div>
                            <div class="filter-option" onclick="toggleFilter('Dinas Luar')"><input type="radio" name="filter" value="Dinas Luar"> <label class="cursor-pointer w-full">Dinas Luar</label></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL 1: DATA ABSENSI -->
            <div id="panelAbsensi" class="panel">
                <div class="panel-header">
                    <h3 class="panel-title text-gray-800">
                        <span class="material-icons-outlined text-blue-500">fact_check</span>
                        Riwayat Absensi Harian
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Total: <span id="totalCountAbsensi" class="font-bold text-gray-800">0</span> data</span>
                    </div>
                </div>
                <div class="panel-body p-0">
                    <!-- Desktop Table -->
                    <div class="desktop-table overflow-x-auto">
                        <table class="data-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="min-w-[50px]">No</th>
                                    <th class="min-w-[180px]">Nama Karyawan</th>
                                    <th class="min-w-[120px]">Tanggal</th>
                                    <th class="min-w-[120px]">Jam Masuk</th>
                                    <th class="min-w-[120px]">Jam Pulang</th>
                                    <th class="min-w-[120px]">Status Kehadiran</th>
                                    <th class="min-w-[100px] text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="absensiTableBody" class="text-sm text-gray-600">
                                <!-- Rows injected by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="mobile-cards p-4" id="absensiMobileCards">
                        <!-- Cards injected by JS -->
                    </div>

                    <!-- Pagination -->
                    <div id="paginationAbsensi" class="pagination-container p-4 border-t border-gray-100">
                        <!-- Pagination injected by JS -->
                    </div>
                </div>
            </div>

            <!-- PANEL 2: DAFTAR KETIDAKHADIRAN (IZIN/SAKIT/CUTI) -->
            <div id="panelKetidakhadiran" class="panel hidden">
                <div class="panel-header">
                    <h3 class="panel-title text-gray-800">
                        <span class="material-icons-outlined text-orange-500">assignment_late</span>
                        Pengajuan Izin & Cuti
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Total: <span id="totalCountKetidakhadiran" class="font-bold text-gray-800">0</span> data</span>
                    </div>
                </div>
                <div class="panel-body p-0">
                    <!-- Desktop Table -->
                    <div class="desktop-table overflow-x-auto">
                        <table class="data-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="min-w-[50px]">No</th>
                                    <th class="min-w-[200px]">Nama Karyawan</th>
                                    <th class="min-w-[120px]">Jenis</th>
                                    <th class="min-w-[150px]">Tanggal</th>
                                    <th class="min-w-[200px]">Keterangan</th>
                                    <th class="min-w-[120px]">Status Approval</th>
                                    <th class="min-w-[150px] text-center">Aksi Manajer</th>
                                </tr>
                            </thead>
                            <tbody id="ketidakhadiranTableBody" class="text-sm text-gray-600">
                                <!-- Rows injected by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="mobile-cards p-4" id="ketidakhadiranMobileCards">
                        <!-- Cards injected by JS -->
                    </div>

                    <!-- Pagination -->
                    <div id="paginationKetidakhadiran" class="pagination-container p-4 border-t border-gray-100">
                        <!-- Pagination injected by JS -->
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-5 right-5 z-[100] flex flex-col gap-2"></div>

    <!-- MODAL: Verifikasi (Approve/Reject) -->
    <div id="verifyModal" class="modal">
        <div class="modal-content">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-icons-outlined text-blue-500">verified_user</span>
                    Verifikasi Pengajuan
                </h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal('verifyModal')">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form id="verifyForm" class="p-6">
                <input type="hidden" id="verifyId">
                
                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Karyawan</p>
                    <p id="verifyEmployeeName" class="text-sm font-bold text-gray-800 mb-1">-</p>
                    <div class="flex gap-2">
                        <span id="verifyLeaveType" class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">-</span>
                        <span id="verifyDateRange" class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-700">-</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan Manajer</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="approved" class="peer sr-only" checked onchange="toggleRejectionReason(false)">
                            <div class="p-3 text-center rounded-lg border border-gray-200 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-50 transition-all">
                                <span class="material-icons-outlined text-sm align-middle mr-1">check_circle</span> Setujui
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="rejected" class="peer sr-only" onchange="toggleRejectionReason(true)">
                            <div class="p-3 text-center rounded-lg border border-gray-200 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-gray-50 transition-all">
                                <span class="material-icons-outlined text-sm align-middle mr-1">cancel</span> Tolak
                            </div>
                        </label>
                    </div>
                </div>

                <div id="rejectionReasonContainer" class="mb-5 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea id="rejectionReason" rows="3" class="w-full bg-white border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all" placeholder="Tuliskan alasan mengapa pengajuan ini ditolak..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors" onclick="closeModal('verifyModal')">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium shadow-md shadow-blue-200 transition-all">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // === 1. DATA SIMULASI (Mock Data) ===
        // Digunakan agar tampilan tidak kosong saat pertama kali dibuka
        const users = [
            { id: 1, name: "Budi Santoso", avatar: "BS" },
            { id: 2, name: "Siti Aminah", avatar: "SA" },
            { id: 3, name: "Rizky Pratama", avatar: "RP" },
            { id: 4, name: "Dewi Lestari", avatar: "DL" },
            { id: 5, name: "Andi Wijaya", avatar: "AW" },
            { id: 6, name: "Fitri Handayani", avatar: "FH" }
        ];

        let dataAbsensi = [
            { id: 101, userId: 1, date: "2023-10-25", checkIn: "08:00", checkOut: "17:00", status: "Hadir" },
            { id: 102, userId: 2, date: "2023-10-25", checkIn: "08:45", checkOut: "17:10", status: "Hadir" },
            { id: 103, userId: 3, date: "2023-10-25", checkIn: "09:15", checkOut: "17:30", status: "Terlambat" }, // Terlambat
            { id: 104, userId: 4, date: "2023-10-25", checkIn: "08:05", checkOut: "-", status: "Hadir" }, // Belum pulang
            { id: 105, userId: 1, date: "2023-10-24", checkIn: "07:55", checkOut: "17:05", status: "Hadir" },
            { id: 106, userId: 5, date: "2023-10-24", checkIn: "09:00", checkOut: "-", status: "Terlambat" },
        ];

        let dataKetidakhadiran = [
            { id: 201, userId: 5, type: "Sakit", dateStart: "2023-10-25", dateEnd: "2023-10-26", reason: "Demam tinggi dan flu.", status: "pending" },
            { id: 202, userId: 6, type: "Izin", dateStart: "2023-10-24", dateEnd: "2023-10-24", reason: "Acara keluarga.", status: "approved" },
            { id: 203, userId: 3, type: "Cuti", dateStart: "2023-10-20", dateEnd: "2023-10-23", reason: "Cuti tahunan.", status: "approved" },
            { id: 204, userId: 2, type: "Dinas Luar", dateStart: "2023-10-26", dateEnd: "2023-10-27", reason: "Meeting klien di Surabaya.", status: "pending" },
            { id: 205, userId: 4, type: "Izin", dateStart: "2023-10-23", dateEnd: "2023-10-23", reason: "Keperluan bank.", status: "rejected", rejectionNote: "Izin ditolak karena deadline project." },
        ];

        // === 2. STATE MANAGEMENT ===
        let state = {
            activeTab: 'absensi', // 'absensi' or 'ketidakhadiran'
            currentPage: 1,
            itemsPerPage: 5,
            search: '',
            filterStatus: 'all', // 'all', 'Hadir', 'Terlambat', 'Sakit', etc.
            filterDate: ''
        };

        // === 3. INITIALIZATION ===
        document.addEventListener('DOMContentLoaded', () => {
            // Set current date in header
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDateDisplay').textContent = new Date().toLocaleDateString('id-ID', options);

            // Initial Render
            updateStats();
            renderAbsensiTable();
            renderKetidakhadiranTable();
            
            // Event Listeners
            document.getElementById('searchInput').addEventListener('input', (e) => {
                state.search = e.target.value.toLowerCase();
                state.currentPage = 1;
                refreshCurrentView();
            });

            document.getElementById('dateFilter').addEventListener('change', (e) => {
                state.filterDate = e.target.value;
                state.currentPage = 1;
                refreshCurrentView();
            });

            // Toggle Filter Dropdown
            document.getElementById('filterBtn').addEventListener('click', (e) => {
                e.stopPropagation();
                document.getElementById('filterDropdown').classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.relative')) {
                    document.getElementById('filterDropdown').classList.remove('show');
                }
            });

            // Verification Form Submit
            document.getElementById('verifyForm').addEventListener('submit', handleVerificationSubmit);
        });

        // === 4. CORE FUNCTIONS ===

        function switchTab(tabName) {
            state.activeTab = tabName;
            state.currentPage = 1; // Reset page on switch
            
            // UI Toggle
            const btnAbsensi = document.getElementById('tabAbsensi');
            const btnKetid = document.getElementById('tabKetidakhadiran');
            const panelAbsensi = document.getElementById('panelAbsensi');
            const panelKetid = document.getElementById('panelKetidakhadiran');

            if (tabName === 'absensi') {
                btnAbsensi.classList.add('active');
                btnKetid.classList.remove('active');
                panelAbsensi.classList.remove('hidden');
                panelKetid.classList.add('hidden');
                renderAbsensiTable();
            } else {
                btnKetid.classList.add('active');
                btnAbsensi.classList.remove('active');
                panelKetid.classList.remove('hidden');
                panelAbsensi.classList.add('hidden');
                renderKetidakhadiranTable();
            }
        }

        function toggleFilter(status) {
            state.filterStatus = status;
            // Update Radio UI
            const radios = document.querySelectorAll('input[name="filter"]');
            radios.forEach(r => {
                if(r.value === status) r.checked = true;
            });
            state.currentPage = 1;
            refreshCurrentView();
            document.getElementById('filterDropdown').classList.remove('show');
        }

        function refreshCurrentView() {
            if (state.activeTab === 'absensi') renderAbsensiTable();
            else renderKetidakhadiranTable();
        }

        function getUserName(userId) {
            const user = users.find(u => u.id === userId);
            return user ? user.name : 'Unknown';
        }

        // === 5. RENDER LOGIC: DATA ABSENSI ===

        function renderAbsensiTable() {
            const tbody = document.getElementById('absensiTableBody');
            const mobileCards = document.getElementById('absensiMobileCards');
            const pagination = document.getElementById('paginationAbsensi');
            
            tbody.innerHTML = '';
            mobileCards.innerHTML = '';
            pagination.innerHTML = '';

            // Filter Logic
            let filtered = dataAbsensi.filter(item => {
                const name = getUserName(item.userId).toLowerCase();
                const matchesSearch = name.includes(state.search);
                const matchesDate = state.filterDate ? item.date === state.filterDate : true;
                const matchesStatus = state.filterStatus === 'all' ? true : item.status === state.filterStatus;
                return matchesSearch && matchesDate && matchesStatus;
            });

            // Update Count
            document.getElementById('totalCountAbsensi').textContent = filtered.length;

            // Pagination Logic
            const totalPages = Math.ceil(filtered.length / state.itemsPerPage) || 1;
            if (state.currentPage > totalPages) state.currentPage = totalPages;
            
            const start = (state.currentPage - 1) * state.itemsPerPage;
            const end = start + state.itemsPerPage;
            const pageData = filtered.slice(start, end);

            // Generate Desktop Rows
            pageData.forEach((item, index) => {
                const globalIndex = start + index + 1;
                const statusClass = item.status === 'Hadir' ? 'bg-hadir' : 'bg-terlambat';
                const name = getUserName(item.userId);
                
                const row = `
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="text-center text-gray-400">${globalIndex}</td>
                        <td class="font-medium text-gray-800">${name}</td>
                        <td>${formatDateIndo(item.date)}</td>
                        <td><span class="text-gray-700 font-medium">${item.checkIn}</span></td>
                        <td><span class="${item.checkOut === '-' ? 'text-gray-400 italic' : 'text-gray-700 font-medium'}">${item.checkOut}</span></td>
                        <td><span class="status-badge ${statusClass}">${item.status}</span></td>
                        <td class="text-center">
                            <button class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus Data" onclick="deleteAbsensi(${item.id})">
                                <span class="material-icons-outlined text-sm">delete</span>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);

                // Generate Mobile Card
                const card = `
                    <div class="mobile-card border-l-4 ${item.status === 'Hadir' ? 'border-l-green-500' : 'border-l-yellow-500'}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-800">${name}</h4>
                                <p class="text-xs text-gray-500">${formatDateIndo(item.date)}</p>
                            </div>
                            <span class="status-badge ${statusClass} text-xs">${item.status}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div class="bg-gray-50 p-2 rounded">
                                <p class="text-xs text-gray-400">Masuk</p>
                                <p class="font-semibold text-gray-700">${item.checkIn}</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded">
                                <p class="text-xs text-gray-400">Pulang</p>
                                <p class="font-semibold text-gray-700">${item.checkOut}</p>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button class="text-xs text-red-500 flex items-center gap-1" onclick="deleteAbsensi(${item.id})">
                                <span class="material-icons-outlined text-sm">delete</span> Hapus
                            </button>
                        </div>
                    </div>
                `;
                mobileCards.insertAdjacentHTML('beforeend', card);
            });

            // Render Pagination
            renderPaginationControls(pagination, totalPages, 'absensi');
        }

        // === 6. RENDER LOGIC: KETIDAKHADIRAN (Approvals) ===

        function renderKetidakhadiranTable() {
            const tbody = document.getElementById('ketidakhadiranTableBody');
            const mobileCards = document.getElementById('ketidakhadiranMobileCards');
            const pagination = document.getElementById('paginationKetidakhadiran');

            tbody.innerHTML = '';
            mobileCards.innerHTML = '';
            pagination.innerHTML = '';

            // Filter Logic
            let filtered = dataKetidakhadiran.filter(item => {
                const name = getUserName(item.userId).toLowerCase();
                const matchesSearch = name.includes(state.search);
                const matchesDate = state.filterDate ? item.dateStart === state.filterDate : true;
                
                let matchesStatus = true;
                if (state.filterStatus !== 'all') {
                    // Filter by Type (Sakit, Cuti, etc) OR Status (Pending, Approved, Rejected)
                    // For this demo, we assume Filter Dropdown selects the TYPE of leave
                    matchesStatus = item.type === state.filterStatus;
                }

                return matchesSearch && matchesDate && matchesStatus;
            });

            document.getElementById('totalCountKetidakhadiran').textContent = filtered.length;

            const totalPages = Math.ceil(filtered.length / state.itemsPerPage) || 1;
            if (state.currentPage > totalPages) state.currentPage = totalPages;
            
            const start = (state.currentPage - 1) * state.itemsPerPage;
            const end = start + state.itemsPerPage;
            const pageData = filtered.slice(start, end);

            pageData.forEach((item, index) => {
                const globalIndex = start + index + 1;
                const name = getUserName(item.userId);
                
                // Styling based on approval status
                let statusBadge = '';
                let actionButton = '';
                
                if (item.status === 'pending') {
                    statusBadge = '<span class="status-badge bg-pending">Menunggu</span>';
                    actionButton = `<button onclick="openVerifyModal(${item.id})" class="flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-semibold hover:bg-blue-100 transition-colors">
                                        <span class="material-icons-outlined text-sm">check_circle</span> Verifikasi
                                    </button>`;
                } else if (item.status === 'approved') {
                    statusBadge = '<span class="status-badge bg-approved">Disetujui</span>';
                    actionButton = `<span class="text-gray-400 text-xs italic">Selesai</span>`;
                } else if (item.status === 'rejected') {
                    statusBadge = '<span class="status-badge bg-rejected">Ditolak</span>';
                    actionButton = `<button onclick="openVerifyModal(${item.id})" class="text-blue-500 hover:text-blue-700 text-xs font-medium">Review</button>`;
                }

                // Color type for left border badge
                const typeColorMap = {
                    'Sakit': 'border-orange-400',
                    'Izin': 'border-blue-400',
                    'Cuti': 'border-red-400',
                    'Dinas Luar': 'border-purple-400'
                };
                const typeClass = typeColorMap[item.type] || 'border-gray-400';

                // Desktop Row
                const row = `
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="text-center text-gray-400">${globalIndex}</td>
                        <td class="font-medium text-gray-800">${name}</td>
                        <td><span class="font-semibold text-gray-700">${item.type}</span></td>
                        <td>${formatDateIndo(item.dateStart)} ${item.dateEnd !== item.dateStart ? '- ' + formatDateIndo(item.dateEnd) : ''}</td>
                        <td class="max-w-xs truncate text-gray-500" title="${item.reason}">${item.reason}</td>
                        <td>${statusBadge}</td>
                        <td class="text-center">${actionButton}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);

                // Mobile Card
                const card = `
                    <div class="mobile-card border-l-4 ${typeClass}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-bold text-gray-800">${name}</h4>
                                <p class="text-xs text-gray-500 font-medium">${item.type}</p>
                            </div>
                            ${statusBadge}
                        </div>
                        <div class="text-sm mb-3">
                            <p class="text-gray-400 text-xs mb-1">Tanggal</p>
                            <p class="font-medium text-gray-700">${formatDateIndo(item.dateStart)} ${item.dateEnd !== item.dateStart ? '- ' + formatDateIndo(item.dateEnd) : ''}</p>
                        </div>
                        <div class="text-sm mb-4">
                            <p class="text-gray-400 text-xs mb-1">Keterangan</p>
                            <p class="text-gray-600">${item.reason}</p>
                            ${item.status === 'rejected' ? `<p class="text-red-500 text-xs mt-1 italic">Alasan Ditolak: ${item.rejectionNote || '-'}</p>` : ''}
                        </div>
                        <div class="flex justify-end pt-2 border-t border-gray-100">
                            ${actionButton}
                        </div>
                    </div>
                `;
                mobileCards.insertAdjacentHTML('beforeend', card);
            });

            renderPaginationControls(pagination, totalPages, 'ketidakhadiran');
        }

        // === 7. PAGINATION HELPER ===

        function renderPaginationControls(container, totalPages, context) {
            if (totalPages <= 1) return;

            const prevBtn = `<button class="nav-btn" onclick="changePage(-1, '${context}')" ${state.currentPage === 1 ? 'disabled' : ''}><span class="material-icons-outlined text-sm">chevron_left</span></button>`;
            const nextBtn = `<button class="nav-btn" onclick="changePage(1, '${context}')" ${state.currentPage === totalPages ? 'disabled' : ''}><span class="material-icons-outlined text-sm">chevron_right</span></button>`;

            let pagesHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === state.currentPage ? 'active' : '';
                pagesHtml += `<button class="page-btn ${activeClass}" onclick="goToPage(${i}, '${context}')">${i}</button>`;
            }

            container.innerHTML = prevBtn + pagesHtml + nextBtn;
        }

        function changePage(delta, context) {
            state.currentPage += delta;
            refreshCurrentView();
        }

        function goToPage(page, context) {
            state.currentPage = page;
            refreshCurrentView();
        }

        // === 8. STATS UPDATE HELPER ===

        function updateStats() {
            // Calculate stats dynamically from data
            const stats = {
                hadir: dataAbsensi.filter(i => i.status === 'Hadir').length,
                terlambat: dataAbsensi.filter(i => i.status === 'Terlambat').length,
                izin: dataKetidakhadiran.filter(i => i.type === 'Izin').length,
                cuti: dataKetidakhadiran.filter(i => i.type === 'Cuti').length,
                dinas: dataKetidakhadiran.filter(i => i.type === 'Dinas Luar').length,
                sakit: dataKetidakhadiran.filter(i => i.type === 'Sakit').length,
            };

            const statsData = [
                { label: 'Total Hadir', val: stats.hadir, color: 'bg-green-100 text-green-600', icon: 'check_circle' },
                { label: 'Terlambat', val: stats.terlambat, color: 'bg-yellow-100 text-yellow-600', icon: 'schedule' },
                { label: 'Izin', val: stats.izin, color: 'bg-blue-100 text-blue-600', icon: 'info' },
                { label: 'Cuti', val: stats.cuti, color: 'bg-red-100 text-red-600', icon: 'flight_takeoff' },
                { label: 'Dinas Luar', val: stats.dinas, color: 'bg-purple-100 text-purple-600', icon: 'directions_car' },
                { label: 'Sakit', val: stats.sakit, color: 'bg-orange-100 text-orange-600', icon: 'healing' },
            ];

            const container = document.getElementById('statsContainer');
            container.innerHTML = statsData.map(s => `
                <div class="card bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">${s.label}</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">${s.val}</p>
                        </div>
                        <div class="icon-container ${s.color} bg-opacity-20">
                            <span class="material-icons-outlined text-xl">${s.icon}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // === 9. MODAL & ACTIONS ===

        function openVerifyModal(id) {
            const item = dataKetidakhadiran.find(i => i.id === id);
            if (!item) return;

            document.getElementById('verifyId').value = id;
            document.getElementById('verifyEmployeeName').textContent = getUserName(item.userId);
            document.getElementById('verifyLeaveType').textContent = item.type;
            document.getElementById('verifyDateRange').textContent = `${formatDateIndo(item.dateStart)} - ${formatDateIndo(item.dateEnd)}`;

            // Reset Form State
            const radios = document.getElementsByName('decision');
            radios[0].checked = true; // Default Approved
            toggleRejectionReason(false);
            document.getElementById('rejectionReason').value = '';

            const modal = document.getElementById('verifyModal');
            modal.classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        function toggleRejectionReason(show) {
            const container = document.getElementById('rejectionReasonContainer');
            const textarea = document.getElementById('rejectionReason');
            if (show) {
                container.classList.remove('hidden');
                textarea.setAttribute('required', 'true');
            } else {
                container.classList.add('hidden');
                textarea.removeAttribute('required');
            }
        }

        function handleVerificationSubmit(e) {
            e.preventDefault();
            const id = parseInt(document.getElementById('verifyId').value);
            const decision = document.querySelector('input[name="decision"]:checked').value;
            const reason = document.getElementById('rejectionReason').value;

            // Update Data
            const index = dataKetidakhadiran.findIndex(i => i.id === id);
            if (index !== -1) {
                dataKetidakhadiran[index].status = decision;
                dataKetidakhadiran[index].rejectionNote = decision === 'rejected' ? reason : null;
                
                // Show Notification
                const msg = decision === 'approved' ? 'Pengajuan berhasil disetujui' : 'Pengajuan ditolak';
                const type = decision === 'approved' ? 'success' : 'error';
                showNotification(msg, type);

                closeModal('verifyModal');
                renderKetidakhadiranTable();
                updateStats(); // Update numbers at top
            }
        }

        function deleteAbsensi(id) {
            if(confirm('Apakah Anda yakin ingin menghapus data absensi ini?')) {
                dataAbsensi = dataAbsensi.filter(i => i.id !== id);
                showNotification('Data absensi dihapus', 'success');
                renderAbsensiTable();
                updateStats();
            }
        }

        // === 10. UTILITIES ===

        function formatDateIndo(dateString) {
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        function showNotification(message, type = 'success') {
            const container = document.getElementById('notificationContainer');
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            const icons = {
                success: 'check_circle',
                error: 'error',
                warning: 'warning',
                info: 'info'
            };

            const notification = document.createElement('div');
            notification.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 transform transition-all duration-300 translate-x-full opacity-0`;
            notification.innerHTML = `
                <span class="material-icons-outlined text-xl">${icons[type]}</span>
                <span class="font-medium text-sm">${message}</span>
            `;

            container.appendChild(notification);

            // Animate in
            requestAnimationFrame(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            });

            // Remove after 3s
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>
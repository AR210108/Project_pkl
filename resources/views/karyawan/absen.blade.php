<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attendance Screen</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#3b82f6", "primary-dark": "#2563eb", "primary-light": "#60a5fa", secondary: "#8b5cf6", success: "#10b981", warning: "#f59e0b", danger: "#ef4444", background: "#f8fafc", "background-alt": "#f1f5f9", surface: "#ffffff", "text-primary": "#1e293b", "text-secondary": "#64748b", "border-color": "#e2e8f0", "shadow-color": "rgba(0, 0, 0, 0.08)", },
                    fontFamily: { display: ["Roboto", "sans-serif"] },
                    borderRadius: { DEFAULT: "1rem", lg: "1.25rem", full: "9999px" },
                    boxShadow: { card: "0 10px 25px rgba(0,0,0,0.08)", "card-hover": "0 20px 40px rgba(0,0,0,0.12)" },
                },
            },
            darkMode: 'class',
        };
    </script>
    <style>
        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-alt: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: rgba(226, 232, 240, 0.5);
            --shadow-color: rgba(0, 0, 0, 0.08);
            --card-bg: linear-gradient(145deg, #ffffff, #f8fafc);
            --table-hover: #f8fafc;
        }

        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-alt: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: rgba(51, 65, 85, 0.5);
            --shadow-color: rgba(0, 0, 0, 0.3);
            --card-bg: linear-gradient(145deg, #1e293b, #334155);
            --table-hover: #334155;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-alt) 100%);
            min-height: 100vh;
            color: var(--text-secondary);
        }

        * {
            box-sizing: border-box;
        }

        .card {
            background: var(--card-bg);
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 10px 25px var(--shadow-color);
            border: 1px solid var(--border-color);
        }

        .action-card {
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .action-card:hover:not(:disabled) {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px var(--shadow-color);
        }

        .action-card:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .action-card.checkin .material-icons {
            color: #10b981;
        }

        .action-card.checkout .material-icons {
            color: #ef4444;
        }

        .action-card.sakit .material-icons {
            color: #ef4444;
        }

        .action-card.izin .material-icons {
            color: #f59e0b;
        }

        .action-card.cuti .material-icons {
            color: #8b5cf6;
        }

        .action-card .material-icons {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .clock-container {
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .clock-time {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .table-row {
            transition: all 0.2s ease;
            cursor: default;
        }

        .table-row:hover {
            background-color: var(--table-hover);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-on-time {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-late {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-absent {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .status-no-show {
            background-color: rgba(156, 163, 175, 0.1);
            color: #9ca3af;
        }

        .status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .status-cuti {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .late-time-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            margin-left: 0.5rem;
        }

        .cuti-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
            margin-left: 0.5rem;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .pagination-btn {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            min-width: 36px;
            text-align: center;
        }

        .pagination-btn.active {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            color: white;
            border-color: transparent;
        }

        .pagination-btn:hover:not(:disabled):not(.active) {
            background: var(--bg-alt);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .swal2-popup {
            font-family: 'Roboto', sans-serif;
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px var(--shadow-color);
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .swal2-header {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            padding: 1.25rem 1.5rem 1rem;
            margin: 0 !important;
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .swal2-title {
            color: white !important;
            font-weight: 600 !important;
            font-size: 1.3rem !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .swal2-html-container {
            padding: 1.25rem 1.5rem !important;
            margin: 0 !important;
            color: var(--text-primary);
        }

        .swal2-actions {
            padding: 0 1.5rem 1.25rem !important;
            margin: 0 !important;
            gap: 0.75rem !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            border-radius: 0.75rem !important;
            font-weight: 500 !important;
            padding: 0.75rem 1.25rem !important;
            transition: all 0.3s ease !important;
            font-size: 0.9rem !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }

        .swal2-cancel {
            background: var(--bg-alt) !important;
            color: var(--text-secondary) !important;
            border: 1px solid var(--border-color) !important;
        }

        .form-group {
            margin-bottom: 1rem;
            width: 100%;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label .material-icons {
            font-size: 1.1rem;
            color: #3b82f6;
        }

        .form-group .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .uniform-input,
        .uniform-textarea,
        .uniform-select,
        .uniform-file {
            border-radius: 0.75rem !important;
            border: 2px solid var(--border-color) !important;
            padding: 0.75rem 1rem !important;
            width: 100% !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
            background: var(--bg-alt) !important;
            font-family: 'Roboto', sans-serif !important;
            color: var(--text-primary);
        }

        .uniform-input,
        .uniform-select,
        .uniform-file {
            height: 48px !important;
        }

        .uniform-input:focus,
        .uniform-textarea:focus,
        .uniform-select:focus,
        .uniform-file:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            background: var(--bg-secondary) !important;
            outline: none !important;
        }

        .uniform-textarea {
            min-height: 100px !important;
            resize: vertical !important;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }

        .empty-state .material-icons {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .filter-select {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 0.9rem;
            outline: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .filter-label {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.85rem;
        }

        .file-helper {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .leave-message {
            background: linear-gradient(145deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05));
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 1.25rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(139, 92, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(139, 92, 246, 0);
            }
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 0, 0, .1);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 640px) {
            .swal2-popup {
                width: 95% !important;
                max-width: 95% !important;
            }

            .clock-time {
                font-size: 2.5rem;
            }

            .action-card {
                padding: 1.5rem;
            }

            .action-card .material-icons {
                font-size: 2.5rem;
            }

            .card {
                padding: 1.5rem;
            }

            .filter-group {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }

            .leave-message {
                padding: 1.5rem;
            }
        }

        /* Timezone info */
        .timezone-info {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-align: center;
            margin-top: 0.5rem;
        }

        /* Operational info styling */
        .operational-info {
            background: var(--bg-alt);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-top: 1rem;
            font-size: 0.8rem;
        }

        .operational-info .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .operational-info .info-item:last-child {
            margin-bottom: 0;
        }

        .operational-info .label {
            color: var(--text-secondary);
        }

        .operational-info .value {
            font-weight: 500;
            color: var(--text-primary);
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col p-4 lg:p-8">
        <!-- Assuming header blade partial exists or use placeholder -->
        @include('karyawan.templet.header')

        <main class="flex-grow w-full max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold" style="color: var(--text-primary);">ABSENSI KARYAWAN</h2>
            </div>

            <!-- Clock -->
            <div class="card clock-container mb-8">
                <p class="clock-time" id="clock-time">12:00:00</p>
                <p class="text-lg mt-2" id="clock-date">Senin, 01 Januari 2025</p>
                <p class="timezone-info" id="clock-timezone">WIB (UTC+7)</p>
            </div>

            <!-- Pesan Cuti (akan ditampilkan jika sedang cuti) -->
            <div id="leave-message-container" class="leave-message hidden">
                <div class="flex flex-col items-center">
                    <span class="material-icons text-secondary mb-4" style="font-size: 4rem;">beach_access</span>
                    <h3 class="text-2xl font-bold mb-2 text-secondary">ANDA SEDANG CUTI</h3>
                    <div id="leave-details" class="text-lg mb-4"></div>
                    <p class="text-sm mb-2">Selama periode cuti, Anda tidak dapat melakukan:</p>
                    <ul class="text-sm list-disc list-inside text-left mb-4">
                        <li>Absen Masuk</li>
                        <li>Absen Pulang</li>
                        <li>Pengajuan Sakit/Izin</li>
                    </ul>
                    <a href="{{ route('karyawan.cuti.index') }}"
                        class="btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-secondary text-white hover:bg-purple-700 transition">
                        <span class="material-icons">calendar_today</span>
                        Lihat Detail Cuti
                    </a>
                </div>
            </div>

            <!-- Action Cards -->
            <div id="action-cards-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

                <!-- Dynamic Check-in / Check-out Button -->
                <div id="main-action-btn" class="card action-card checkin">
                    <span id="main-action-icon" class="material-icons">login</span>
                    <p id="main-action-text" class="font-semibold" style="color: var(--text-primary);">ABSEN MASUK</p>
                </div>

                <!-- Sakit Button -->
                <div class="card action-card sakit btn-sakit">
                    <span class="material-icons">local_hospital</span>
                    <p class="font-semibold" style="color: var(--text-primary);">SAKIT</p>
                </div>

                <!-- Izin Button -->
                <div class="card action-card izin btn-izin">
                    <span class="material-icons">event_busy</span>
                    <p class="font-semibold" style="color: var(--text-primary);">IZIN</p>
                </div>
            </div>

            <!-- Status & History -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Riwayat Absensi -->
                <div class="card lg:col-span-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
                        <h3 class="font-bold text-xl flex items-center" style="color: var(--text-primary);">
                            <span class="material-icons text-primary mr-2">history</span>Riwayat Absensi
                            <span id="history-loading" class="loading-spinner ml-2 hidden"></span>
                        </h3>

                        <!-- Filter Group -->
                        <div class="filter-group">
                            <label class="filter-label">Filter:</label>
                            <div class="relative">
                                <select id="history-filter" class="filter-select">
                                    <option value="week">Minggu Ini</option>
                                    <option value="month" selected>Bulan Ini</option>
                                    <option value="year">Tahun Ini</option>
                                    <option value="custom">Pilih Periode</option>
                                </select>
                                <span
                                    class="material-icons absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-400"
                                    style="font-size: 20px;">
                                    filter_list
                                </span>
                            </div>

                            <!-- Custom Filter (Tampil hanya ketika pilih periode) -->
                            <div id="custom-filter-container" class="hidden flex items-center gap-2">
                                <div class="relative">
                                    <select id="filter-month" class="filter-select">
                                        <option value="">Pilih Bulan</option>
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
                                <div class="relative">
                                    <select id="filter-year" class="filter-select">
                                        <option value="">Pilih Tahun</option>
                                    </select>
                                </div>
                                <button id="apply-custom-filter"
                                    class="filter-select bg-primary text-white hover:bg-primary-dark">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr style="color: var(--text-primary); border-bottom: 1px solid var(--border-color);">
                                    <th class="pb-3 font-medium">No</th>
                                    <th class="pb-3 font-medium">Tanggal</th>
                                    <th class="pb-3 font-medium">Jam Masuk</th>
                                    <th class="pb-3 font-medium">Jam Pulang</th>
                                    <th class="pb-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody id="history-tbody" style="color: var(--text-secondary);"></tbody>
                        </table>
                    </div>
                    <div id="empty-state" class="empty-state hidden">
                        <span class="material-icons">assignment_late</span>
                        <h4>Belum Ada Riwayat Absensi</h4>
                        <p>Anda belum memiliki riwayat absensi periode ini.</p>
                    </div>
                    <div class="pagination-container" id="pagination"></div>
                </div>

                <!-- Status Absensi -->
                <div class="card">
                    <h3 class="font-bold text-xl mb-4 flex items-center" style="color: var(--text-primary);">
                        <span class="material-icons text-primary mr-2">assignment</span>Status Absensi
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-primary">login</span>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Absen Masuk</p>
                                <p class="text-sm" id="today-checkin" style="color: var(--text-secondary);">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-primary">logout</span>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Absen Pulang</p>
                                <p class="text-sm" id="today-checkout" style="color: var(--text-secondary);">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-success">check_circle</span>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Status Hari Ini</p>
                                <div id="today-status" class="font-medium text-success">Belum Absen</div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Jam Operasional -->
                    <div class="operational-info mt-4">
                        <div class="info-item">
                            <span class="label">Jam Masuk:</span>
                            <span class="value" id="info-start-time">08:00</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Jam Pulang:</span>
                            <span class="value" id="info-end-time">17:00</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Batas Terlambat:</span>
                            <span class="value" id="info-late-limit">09:05</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="w-full max-w-7xl mx-auto mt-8 text-center text-xs sm:text-sm py-4"
            style="color: var(--text-secondary);">
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <script>
        // --- KONFIGURASI GLOBAL ---
        window.currentUserId = {{ auth()->id() }};
        window.apiBasePath = '/karyawan/api';
        window.todayData = null;
        window.leaveData = null; // Menyimpan data cuti

        // --- VARIABEL PAGINATION ---
        let currentPage = 1;
        const recordsPerPage = 5;
        let attendanceHistoryData = [];
        let currentFilterType = 'month';
        let customMonth = '';
        let customYear = '';

        // --- KONSTAN TIMEZONE & JAM BATAS ---
        const TIMEZONE = 'Asia/Jakarta'; // WIB (UTC+7)
        // LIMIT_HOUR dan LIMIT_MINUTE akan diambil dari server
        let LIMIT_HOUR = 9; // Default
        let LIMIT_MINUTE = 5; // Default
        let LIMIT_TOTAL_MINUTES = LIMIT_HOUR * 60 + LIMIT_MINUTE; // Default
        let START_TIME = '08:00'; // Default
        let END_TIME = '17:00'; // Default

        // Fungsi untuk memuat pengaturan jam operasional
        async function loadOperationalHours() {
            try {
                const response = await fetch('/api/operational-hours');
                const res = await response.json();

                if (res.success && res.data) {
                    LIMIT_HOUR = parseInt(res.data.late_limit_hour);
                    LIMIT_MINUTE = parseInt(res.data.late_limit_minute);
                    LIMIT_TOTAL_MINUTES = LIMIT_HOUR * 60 + LIMIT_MINUTE;
                    START_TIME = res.data.start_time;
                    END_TIME = res.data.end_time;

                    // Update info di UI
                    document.getElementById('info-start-time').textContent = START_TIME;
                    document.getElementById('info-end-time').textContent = END_TIME;
                    document.getElementById('info-late-limit').textContent =
                        `${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')}`;

                    console.log("🕐 Operational hours loaded:", {
                        start_time: START_TIME,
                        end_time: END_TIME,
                        late_limit_hour: LIMIT_HOUR,
                        late_limit_minute: LIMIT_MINUTE,
                        limit_time: `${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')}`
                    });
                }
            } catch (error) {
                console.error("Error loading operational hours:", error);
            }
        }

        // --- FUNGSI HELPER TIMEZONE ---

        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        // Fungsi untuk mendapatkan jam dan menit dari string waktu apapun
        function extractHoursMinutes(timeString) {
            console.log("🕐 extractHoursMinutes - Input:", timeString);

            if (!timeString || timeString === '-') {
                return { hours: 0, minutes: 0, valid: false };
            }

            try {
                // Jika format ISO
                if (timeString.includes('T') && timeString.includes('Z')) {
                    const date = new Date(timeString);
                    if (isNaN(date.getTime())) {
                        console.error("❌ Invalid ISO date:", timeString);
                        return { hours: 0, minutes: 0, valid: false };
                    }

                    // Konversi ke WIB (UTC+7)
                    const wibOffset = 7 * 60 * 60 * 1000;
                    const wibTime = new Date(date.getTime() + wibOffset);

                    const hours = wibTime.getUTCHours();
                    const minutes = wibTime.getUTCMinutes();

                    console.log("🕐 extractHoursMinutes - ISO parsed:", { hours, minutes });
                    return { hours, minutes, valid: true };
                }
                // Jika format HH:mm atau HH:mm:ss
                else if (timeString.includes(':')) {
                    const parts = timeString.split(':');
                    const hours = parseInt(parts[0], 10);
                    const minutes = parseInt(parts[1] || '0', 10);

                    console.log("🕐 extractHoursMinutes - Time parsed:", { hours, minutes });
                    return { hours, minutes, valid: !isNaN(hours) && !isNaN(minutes) };
                }
                // Jika format lain, coba parse sebagai angka
                else {
                    const timeValue = parseInt(timeString, 10);
                    if (!isNaN(timeValue)) {
                        const hours = Math.floor(timeValue / 100);
                        const minutes = timeValue % 100;
                        return { hours, minutes, valid: true };
                    }
                }
            } catch (error) {
                console.error("❌ Error extracting hours/minutes:", error);
            }

            return { hours: 0, minutes: 0, valid: false };
        }

        // Format waktu dengan debug
        function formatTime(timeString) {
            console.log("🔧 formatTime - Input:", timeString, "Type:", typeof timeString);

            if (!timeString || timeString === '00:00:00' || timeString === '00:00' ||
                timeString === '00:00:00.000000' || timeString === 'null' || timeString === null) {
                console.log("🔧 formatTime - Returning '-' for empty time");
                return '-';
            }

            try {
                // Jika format ISO dengan Z (UTC)
                if (typeof timeString === 'string' && timeString.includes('T') && timeString.includes('Z')) {
                    console.log("🔧 formatTime - Detected ISO format with Z");

                    // Parse tanggal ISO
                    const date = new Date(timeString);

                    if (isNaN(date.getTime())) {
                        console.error("❌ formatTime - Invalid date:", timeString);
                        return '-';
                    }

                    // Konversi ke WIB (UTC+7)
                    const wibOffset = 7 * 60 * 60 * 1000; // 7 jam dalam milidetik
                    const wibTime = new Date(date.getTime() + wibOffset);

                    const hours = wibTime.getUTCHours();
                    const minutes = wibTime.getUTCMinutes();

                    console.log("🔧 formatTime - ISO result:",
                        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`);

                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

                }
                // Jika format waktu saja (HH:mm:ss)
                else if (typeof timeString === 'string' && timeString.includes(':')) {
                    console.log("🔧 formatTime - Detected time-only format");
                    const parts = timeString.split(':');
                    const hours = parseInt(parts[0], 10);
                    const minutes = parseInt(parts[1], 10);

                    if (isNaN(hours) || isNaN(minutes)) {
                        console.error("❌ formatTime - Invalid time format:", timeString);
                        return timeString;
                    }

                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                }
                // Fallback
                else {
                    console.log("🔧 formatTime - Fallback to string conversion");
                    return String(timeString);
                }

            } catch (e) {
                console.error('❌ Error formatting time:', e, timeString);
                return '-';
            }
        }

        // Format waktu lengkap untuk display
        function formatTimeDetailed(timeString) {
            if (!timeString) return '-';

            const formattedTime = formatTime(timeString);
            return formattedTime === '-' ? '-' : `${formattedTime} WIB`;
        }

        // Format waktu keterlambatan yang lebih baik
        function formatLateTime(totalMinutes) {
            if (totalMinutes <= 0) return '0 menit';

            const hours = Math.floor(totalMinutes / 60);
            const minutes = Math.floor(totalMinutes % 60);

            // Format yang lebih baik
            const parts = [];
            if (hours > 0) parts.push(`${hours} jam`);
            if (minutes > 0) parts.push(`${minutes} menit`);

            return parts.join(' ') || '0 menit';
        }

        // --- FUNGSI PERHITUNGAN KETERLAMBATAN YANG DIPERBAIKI ---

        // Fungsi utama untuk menghitung keterlambatan - VERSI DIPERBAIKI
        function calculateLateMinutesFromTime(timeString) {
            console.log("⏰ calculateLateMinutesFromTime START - Input:", timeString);

            if (!timeString || timeString === '-' || timeString === '00:00:00' ||
                timeString === '00:00' || timeString === '00:00:00.000000') {
                console.log("⏰ Returning 0 - invalid time string");
                return 0;
            }

            try {
                // Extract jam dan menit dari waktu masuk
                const { hours, minutes, valid } = extractHoursMinutes(timeString);

                if (!valid) {
                    console.warn("⏰ Invalid time values - Hours:", hours, "Minutes:", minutes);
                    return 0;
                }

                console.log("⏰ Parsing result:", {
                    input: timeString,
                    hours: hours,
                    minutes: minutes,
                    limitHour: LIMIT_HOUR,
                    limitMinute: LIMIT_MINUTE
                });

                // Hitung total menit dari waktu masuk
                const totalMinutesMasuk = (hours * 60) + minutes;

                // Batas waktu dari pengaturan admin
                const limitMinutes = LIMIT_TOTAL_MINUTES;

                console.log("⏰ CRITICAL CALCULATION:", {
                    waktu_masuk: `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`,
                    total_menit_masuk: totalMinutesMasuk,
                    batas_waktu: `${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')}`,
                    batas_menit: limitMinutes,
                    selisih: totalMinutesMasuk - limitMinutes
                });

                // Keterlambatan = total menit waktu masuk - batas menit
                const lateMinutes = Math.max(0, totalMinutesMasuk - limitMinutes);

                console.log("⏰ FINAL RESULT - Terlambat:", lateMinutes, "menit",
                    lateMinutes > 0 ? `(${Math.floor(lateMinutes / 60)} jam ${lateMinutes % 60} menit)` : "Tepat waktu");

                return lateMinutes;

            } catch (e) {
                console.error('❌ Error calculating late minutes:', e);
                return 0;
            }
        }

        // Fungsi untuk mendapatkan waktu WIB saat ini - DIPERBAIKI
        function getCurrentWIBTime() {
            try {
                const now = new Date();

                // Cara 1: Gunakan toLocaleString dengan timezone
                const options = {
                    timeZone: 'Asia/Jakarta',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };

                // Format ke string terlebih dahulu
                const timeStr = now.toLocaleTimeString('en-US', options);
                console.log("🌐 getCurrentWIBTime - Raw time string:", timeStr);

                // Parse string
                const parts = timeStr.split(':');
                if (parts.length >= 2) {
                    const hours = parseInt(parts[0], 10);
                    const minutes = parseInt(parts[1], 10);
                    const seconds = parseInt(parts[2] || '0', 10);

                    console.log("🌐 getCurrentWIBTime - Parsed:", { hours, minutes, seconds });

                    // Validasi
                    if (!isNaN(hours) && !isNaN(minutes)) {
                        return {
                            hours: hours,
                            minutes: minutes,
                            seconds: seconds
                        };
                    }
                }

                // Fallback: UTC+7
                console.warn("🌐 Using UTC+7 fallback");
                const utcHours = now.getUTCHours();
                const utcMinutes = now.getUTCMinutes();
                const utcSeconds = now.getUTCSeconds();

                // WIB = UTC+7
                const wibHours = (utcHours + 7) % 24;

                return {
                    hours: wibHours,
                    minutes: utcMinutes,
                    seconds: utcSeconds
                };

            } catch (error) {
                console.error("❌ Error in getCurrentWIBTime:", error);
                // Fallback extreme
                const now = new Date();
                return {
                    hours: now.getHours(),
                    minutes: now.getMinutes(),
                    seconds: now.getSeconds()
                };
            }
        }

        // Fungsi untuk menghitung waktu yang telah berlalu sejak checkin - DIPERBAIKI
        function calculateTimeElapsedSinceCheckin(checkinTime) {
            console.log("⏱️ calculateTimeElapsedSinceCheckin START");
            console.log("⏱️ Checkin time:", checkinTime);

            if (!checkinTime || checkinTime === '-') {
                return { elapsedMinutes: 0, elapsedTime: '0 menit' };
            }

            try {
                // Format checkin time jika perlu
                const formattedCheckinTime = formatTime(checkinTime);
                console.log("⏱️ Formatted checkin time:", formattedCheckinTime);

                // Parse jam dan menit dari checkin time
                const checkinMatch = formattedCheckinTime.match(/(\d{1,2}):(\d{1,2})/);

                if (!checkinMatch) {
                    console.error("⏱️ Could not parse checkin time:", formattedCheckinTime);
                    return { elapsedMinutes: 0, elapsedTime: '0 menit' };
                }

                const checkinHour = parseInt(checkinMatch[1], 10);
                const checkinMinute = parseInt(checkinMatch[2], 10);

                // Dapatkan waktu WIB sekarang
                const wibTimeNow = getCurrentWIBTime();
                console.log("⏱️ Current WIB time:", wibTimeNow);

                if (isNaN(wibTimeNow.hours) || isNaN(wibTimeNow.minutes)) {
                    console.error("⏱️ Invalid current time");
                    return { elapsedMinutes: 0, elapsedTime: '0 menit' };
                }

                // Hitung total menit
                const currentTotalMinutes = (wibTimeNow.hours * 60) + wibTimeNow.minutes;
                const checkinTotalMinutes = (checkinHour * 60) + checkinMinute;

                console.log("⏱️ Total minutes:", {
                    current: currentTotalMinutes,
                    checkin: checkinTotalMinutes
                });

                let elapsedMinutes = currentTotalMinutes - checkinTotalMinutes;

                // Handle kasus melewati tengah malam
                if (elapsedMinutes < 0) {
                    elapsedMinutes += (24 * 60);
                }

                // Format output
                let elapsedTime;
                if (elapsedMinutes >= 60) {
                    const hours = Math.floor(elapsedMinutes / 60);
                    const minutes = elapsedMinutes % 60;
                    elapsedTime = `${hours} jam ${minutes} menit`;
                } else {
                    elapsedTime = `${elapsedMinutes} menit`;
                }

                console.log("⏱️ Elapsed minutes:", elapsedMinutes);
                console.log("⏱️ Elapsed time:", elapsedTime);
                console.log("⏱️ calculateTimeElapsedSinceCheckin END");

                return {
                    elapsedMinutes: elapsedMinutes,
                    elapsedTime: elapsedTime
                };

            } catch (error) {
                console.error("❌ Error in calculateTimeElapsedSinceCheckin:", error);
                return { elapsedMinutes: 0, elapsedTime: '0 menit' };
            }
        }

        function getCurrentWIBTimeString() {
            try {
                const wibTime = getCurrentWIBTime();
                const hours = wibTime.hours.toString().padStart(2, '0');
                const minutes = wibTime.minutes.toString().padStart(2, '0');
                const seconds = wibTime.seconds.toString().padStart(2, '0');
                return `${hours}:${minutes}:${seconds}`;
            } catch (error) {
                // Fallback
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                return `${hours}:${minutes}:${seconds}`;
            }
        }

        // Dapatkan jam dan menit WIB saat ini (format HH:mm)
        function getCurrentWIBHoursMinutes() {
            try {
                const wibTime = getCurrentWIBTime();
                const hours = wibTime.hours.toString().padStart(2, '0');
                const minutes = wibTime.minutes.toString().padStart(2, '0');
                return `${hours}:${minutes}`;
            } catch (error) {
                // Fallback
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                return `${hours}:${minutes}`;
            }
        }

        // Dapatkan jam WIB saat ini (angka)
        function getCurrentWIBHour() {
            const wibTime = getCurrentWIBTime();
            return wibTime.hours;
        }

        // Dapatkan menit WIB saat ini (angka)
        function getCurrentWIBMinutes() {
            const wibTime = getCurrentWIBTime();
            return wibTime.minutes;
        }

        // --- FUNGSI CHECK STATUS CUTI ---
        async function checkIfOnLeave() {
            try {
                console.log("🔄 Checking leave status...");
                const response = await apiFetch('/today-status');

                if (response && response.data) {
                    console.log("🔄 Leave status check result:", response.data);

                    // Check if user is on leave today
                    // The controller returns cuti details in the response if user is on leave
                    if (response.data.is_on_leave || response.data.cuti_details) {
                        const cutiDetails = response.data.cuti_details || response.data;
                        window.leaveData = {
                            is_on_leave: true,
                            tanggal_mulai: cutiDetails.tanggal_mulai || cutiDetails.start_date,
                            tanggal_selesai: cutiDetails.tanggal_selesai || cutiDetails.end_date,
                            tipe_cuti: cutiDetails.tipe_cuti || cutiDetails.type || 'Umum',
                            alasan: cutiDetails.alasan || cutiDetails.reason
                        };
                        return window.leaveData;
                    }
                }

                window.leaveData = null;
                return null;
            } catch (error) {
                console.error('❌ Error checking leave status:', error);
                window.leaveData = null;
                return null;
            }
        }

        // --- API FUNCTIONS ---

        async function apiFetch(endpoint, options = {}) {
            const url = window.apiBasePath + endpoint;
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            };
            const token = getCSRFToken();
            if (token) headers['X-CSRF-TOKEN'] = token;

            const config = {
                ...options,
                headers: { ...headers, ...options.headers }
            };

            try {
                const response = await fetch(url, config);
                const data = await response.json();

                if (!response.ok) {
                    // Check if error is due to being on leave
                    if (response.status === 403 && data.cuti_details) {
                        // User is on leave, handle gracefully
                        console.log("User is on leave:", data);
                        return data; // Return data to handle leave status
                    }

                    const message = data.message || data.error || `Terjadi kesalahan (${response.status})`;
                    throw new Error(message);
                }
                return data;
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        }

        // --- FUNGSI FILTER TAHUN ---
        function populateYearFilter() {
            const yearSelect = document.getElementById('filter-year');
            const currentYear = new Date().getFullYear();

            // Kosongkan dulu
            yearSelect.innerHTML = '<option value="">Pilih Tahun</option>';

            // Tambahkan 5 tahun ke belakang dan 2 tahun ke depan
            for (let year = currentYear + 2; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }

            // Set default ke tahun sekarang
            yearSelect.value = currentYear;
        }

        // --- FUNGSI TOGGLE FILTER CUSTOM ---
        function toggleCustomFilter() {
            const filterType = document.getElementById('history-filter').value;
            const customContainer = document.getElementById('custom-filter-container');

            if (filterType === 'custom') {
                customContainer.classList.remove('hidden');
                customContainer.classList.add('flex');
            } else {
                customContainer.classList.remove('flex');
                customContainer.classList.add('hidden');
                // Reset custom filter
                customMonth = '';
                customYear = '';
            }
        }

        // --- FUNGSI UNTUK MENGAMBIL DATA DENGAN FILTER ---
        async function fetchAttendanceHistory() {
            const loadingSpinner = document.getElementById('history-loading');
            const tbody = document.getElementById('history-tbody');
            const emptyState = document.getElementById('empty-state');
            const pagination = document.getElementById('pagination');

            try {
                // Show loading
                loadingSpinner.classList.remove('hidden');
                tbody.style.display = 'none';
                emptyState.classList.add('hidden');
                pagination.style.display = 'none';

                const filterType = currentFilterType;
                let endpoint = '/history';
                let queryParams = [];

                if (filterType === 'custom' && customMonth && customYear) {
                    queryParams.push(`month=${customMonth}`);
                    queryParams.push(`year=${customYear}`);
                } else {
                    queryParams.push(`filter=${filterType}`);
                }

                if (queryParams.length > 0) {
                    endpoint += '?' + queryParams.join('&');
                }

                console.log("📡 Fetching history with endpoint:", endpoint);

                const res = await apiFetch(endpoint);

                if (res && res.success && res.data && Array.isArray(res.data)) {
                    attendanceHistoryData = res.data;

                    // Debug: lihat data yang diterima
                    console.log('History data received:', attendanceHistoryData.length, 'records');
                    if (attendanceHistoryData.length > 0) {
                        console.log('Sample record:', {
                            tanggal: attendanceHistoryData[0].tanggal,
                            jam_masuk: attendanceHistoryData[0].jam_masuk
                        });
                    }
                } else {
                    attendanceHistoryData = [];
                }

                renderHistoryTable();

            } catch (error) {
                console.error('Gagal memuat riwayat:', error);
                attendanceHistoryData = [];

                // Show error message
                tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-8" style="color: var(--text-secondary);">
                    <span class="material-icons text-red-500" style="font-size: 2rem;">error</span>
                    <p class="mt-2">Gagal memuat riwayat absensi</p>
                    <button onclick="fetchAttendanceHistory()" class="mt-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Coba Lagi
                    </button>
                </td>
            </tr>
        `;
                tbody.style.display = 'table-row-group';
                emptyState.classList.add('hidden');
                pagination.style.display = 'none';
            } finally {
                // Hide loading
                loadingSpinner.classList.add('hidden');
            }
        }

        // --- UI UPDATE FUNCTIONS ---

        // Fungsi untuk menampilkan/merender pesan cuti
        function renderLeaveMessage() {
            const leaveMessageContainer = document.getElementById('leave-message-container');
            const actionCardsContainer = document.getElementById('action-cards-container');
            const leaveDetails = document.getElementById('leave-details');

            if (window.leaveData && window.leaveData.is_on_leave) {
                // Tampilkan pesan cuti
                leaveMessageContainer.classList.remove('hidden');

                // Format tanggal
                const startDate = new Date(window.leaveData.tanggal_mulai);
                const endDate = new Date(window.leaveData.tanggal_selesai);
                const startStr = startDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                const endStr = endDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                leaveDetails.innerHTML = `
            <div class="mb-2">
                <span class="font-semibold">Periode:</span> ${startStr} - ${endStr}
            </div>
            <div class="mb-2">
                <span class="font-semibold">Tipe Cuti:</span> ${window.leaveData.tipe_cuti}
            </div>
            <div>
                <span class="font-semibold">Alasan:</span> ${window.leaveData.alasan}
            </div>
        `;

                // Nonaktifkan action cards
                actionCardsContainer.classList.add('opacity-50');
                actionCardsContainer.style.pointerEvents = 'none';

            } else {
                // Sembunyikan pesan cuti
                leaveMessageContainer.classList.add('hidden');

                // Aktifkan action cards
                actionCardsContainer.classList.remove('opacity-50');
                actionCardsContainer.style.pointerEvents = 'auto';
            }
        }

        function updateMainActionButton(hasCheckedIn) {
            const btn = document.getElementById('main-action-btn');
            const icon = document.getElementById('main-action-icon');
            const text = document.getElementById('main-action-text');

            // Cek apakah sedang cuti
            if (window.leaveData && window.leaveData.is_on_leave) {
                btn.classList.remove('checkin', 'checkout');
                btn.classList.add('cuti');
                icon.textContent = 'beach_access';
                text.textContent = 'SEDANG CUTI';
                btn.disabled = true;
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.6';
                return;
            }

            if (hasCheckedIn) {
                btn.classList.remove('checkin', 'cuti');
                btn.classList.add('checkout');
                icon.textContent = 'logout';
                text.textContent = 'ABSEN PULANG';
                btn.dataset.action = 'Absen Pulang';
                btn.disabled = false;
                btn.style.pointerEvents = 'auto';
                btn.style.opacity = '1';
            } else {
                btn.classList.remove('checkout', 'cuti');
                btn.classList.add('checkin');
                icon.textContent = 'login';
                text.textContent = 'ABSEN MASUK';
                btn.dataset.action = 'Absen Masuk';
                btn.disabled = false;
                btn.style.pointerEvents = 'auto';
                btn.style.opacity = '1';
            }
        }

        function enableButtons(enable) {
            const mainBtn = document.getElementById('main-action-btn');

            // Cek apakah sedang cuti
            if (window.leaveData && window.leaveData.is_on_leave) {
                renderLeaveMessage();
                return;
            }

            if (enable) {
                mainBtn.style.pointerEvents = 'auto';
                mainBtn.style.opacity = '1';
                mainBtn.disabled = false;
            } else {
                mainBtn.style.pointerEvents = 'none';
                mainBtn.style.opacity = '0.5';
                mainBtn.disabled = true;
            }

            document.querySelectorAll('.btn-sakit, .btn-izin').forEach(el => {
                if (enable) {
                    el.style.pointerEvents = 'auto';
                    el.style.opacity = '1';
                    el.disabled = false;
                } else {
                    el.style.pointerEvents = 'none';
                    el.style.opacity = '0.5';
                    el.disabled = true;
                }
            });
        }

        async function fetchAndDisplayTodayStatus() {
            try {
                console.log("📡 Fetching today status...");
                const res = await apiFetch('/today-status');
                console.log("📡 Today status response:", res);

                // Cek status cuti terlebih dahulu
                const leaveStatus = await checkIfOnLeave();

                if (res && res.success && res.data && typeof res.data === 'object') {
                    window.todayData = res.data;

                    // Tambahkan data cuti jika ada
                    if (leaveStatus && leaveStatus.is_on_leave) {
                        window.todayData.is_on_leave = true;
                        window.todayData.leave_type = leaveStatus.tipe_cuti;
                        window.todayData.leave_dates = {
                            start: leaveStatus.tanggal_mulai,
                            end: leaveStatus.tanggal_selesai
                        };
                        window.todayData.leave_reason = leaveStatus.alasan;
                    }

                    // DEBUG: Test calculation langsung
                    if (res.data.jam_masuk) {
                        console.log("📡 DEBUG MANUAL CALCULATION:");
                        console.log("Jam masuk dari API:", res.data.jam_masuk);

                        // Test dengan fungsi utama
                        const lateMain = calculateLateMinutesFromTime(res.data.jam_masuk);

                        // Extract jam dan menit untuk verifikasi
                        const { hours, minutes } = extractHoursMinutes(res.data.jam_masuk);
                        const total = (hours * 60) + minutes;
                        const lateManual = Math.max(0, total - LIMIT_TOTAL_MINUTES);

                        console.log("📡 MANUAL DEBUG:", {
                            hours: hours,
                            minutes: minutes,
                            totalMinutes: total,
                            limit: LIMIT_TOTAL_MINUTES,
                            lateManual: lateManual,
                            lateFromMainFunc: lateMain,
                            api_late_minutes: res.data.late_minutes,
                            api_is_terlambat: res.data.is_terlambat
                        });
                    }
                } else if (res && typeof res === 'object') {
                    window.todayData = res;
                } else {
                    window.todayData = null;
                }

                renderTodayStatusUI();

            } catch (error) {
                console.error('❌ Gagal memuat status hari ini:', error);
                window.todayData = null;
                renderTodayStatusUI();
            }
        }

        // --- FUNGSI RENDER STATUS HARI INI YANG DIPERBAIKI ---
        function renderTodayStatusUI() {
            const data = window.todayData;

            console.log("🎨 RENDER UI - Today Data:", data);

            // CEK JIKA SEDANG CUTI
            if (data && data.is_on_leave) {
                // Jika sedang cuti, tampilkan status cuti
                document.getElementById('today-checkin').textContent = '-';
                document.getElementById('today-checkout').textContent = '-';

                const statusEl = document.getElementById('today-status');
                statusEl.innerHTML = '';

                // Teks utama status cuti
                const mainText = document.createElement('span');
                mainText.textContent = `CUTI (${data.leave_type || 'Umum'})`;
                statusEl.appendChild(mainText);

                // Tambahkan badge informasi cuti
                const leaveBadge = document.createElement('span');
                leaveBadge.className = 'cuti-badge';
                leaveBadge.style.marginLeft = '0.5rem';
                leaveBadge.textContent = 'Sedang Cuti';
                statusEl.appendChild(leaveBadge);

                // Tambahkan alasan jika ada
                if (data.leave_reason) {
                    statusEl.appendChild(document.createElement('br'));
                    const reasonText = document.createElement('small');
                    reasonText.style.color = 'var(--text-secondary)';
                    reasonText.style.fontSize = '0.75rem';
                    reasonText.style.display = 'block';
                    reasonText.style.marginTop = '0.25rem';
                    reasonText.textContent = `Alasan: ${data.leave_reason}`;
                    statusEl.appendChild(reasonText);
                }

                statusEl.className = 'font-medium text-secondary';

                // Render pesan cuti
                renderLeaveMessage();

                return;
            }

            if (!data) {
                // Jika tidak ada data hari ini
                document.getElementById('today-checkin').textContent = '-';
                document.getElementById('today-checkout').textContent = '-';

                const statusEl = document.getElementById('today-status');
                statusEl.textContent = 'Belum Absen';
                statusEl.className = 'font-medium text-success';

                // Set tombol ke mode absen masuk
                const mainBtn = document.getElementById('main-action-btn');
                mainBtn.classList.remove('checkout', 'cuti');
                mainBtn.classList.add('checkin');
                mainBtn.querySelector('.material-icons').textContent = 'login';
                mainBtn.querySelector('p').textContent = 'ABSEN MASUK';
                mainBtn.dataset.action = 'Absen Masuk';

                enableButtons(true);
                return;
            }

            // Update waktu masuk dan pulang
            const jamMasukWIB = data.jam_masuk ? formatTime(data.jam_masuk) : '-';
            const jamPulangWIB = data.jam_pulang ? formatTime(data.jam_pulang) : '-';

            console.log("🎨 UI Times:", {
                jam_masuk_raw: data.jam_masuk,
                jam_masuk_formatted: jamMasukWIB,
                jam_pulang_raw: data.jam_pulang,
                jam_pulang_formatted: jamPulangWIB
            });

            document.getElementById('today-checkin').textContent = jamMasukWIB === '-' ? '-' : `${jamMasukWIB} WIB`;
            document.getElementById('today-checkout').textContent = jamPulangWIB === '-' ? '-' : `${jamPulangWIB} WIB`;

            // Update status
            const statusEl = document.getElementById('today-status');

            // Reset class terlebih dahulu
            statusEl.className = 'font-medium';

            if (data.jenis_ketidakhadiran) {
                // Jika ada pengajuan sakit/izin
                statusEl.textContent = data.jenis_ketidakhadiran_label || 'Pengajuan';
                if (data.approval_status === 'pending') {
                    statusEl.textContent += ' (Menunggu)';
                    statusEl.classList.add('text-warning');
                } else {
                    statusEl.classList.add('text-secondary');
                }

                // Nonaktifkan tombol jika ada pengajuan
                enableButtons(false);

            } else if (data.jam_masuk && data.jam_pulang) {
                // Sudah absen masuk dan pulang
                statusEl.textContent = 'Selesai';
                statusEl.classList.add('text-success');

                // Nonaktifkan tombol utama
                const mainBtn = document.getElementById('main-action-btn');
                mainBtn.querySelector('.material-icons').textContent = 'check_circle';
                mainBtn.querySelector('p').textContent = 'SELESAI';
                mainBtn.classList.remove('checkin', 'checkout', 'cuti');
                mainBtn.style.pointerEvents = 'none';
                mainBtn.style.opacity = '0.5';
                mainBtn.disabled = true;

                // Nonaktifkan tombol sakit/izin
                enableButtons(false);

            } else if (data.jam_masuk && !data.jam_pulang) {
                // Sudah absen masuk, belum pulang
                console.log("🎨 Status: Sudah absen masuk, belum pulang");

                // Hitung keterlambatan
                const lateMinutes = calculateLateMinutesFromTime(data.jam_masuk);

                // Hitung waktu yang telah berlalu sejak check-in
                const elapsedResult = calculateTimeElapsedSinceCheckin(data.jam_masuk);

                console.log("🎨 LATE CALCULATION VERIFICATION:", {
                    jam_masuk: data.jam_masuk,
                    jam_masuk_formatted: jamMasukWIB,
                    lateMinutes: lateMinutes,
                    isLate: lateMinutes > 0 ? "TERLAMBAT" : "TEPAT WAKTU",
                    expected_for_11_19: "134 menit (11:19 - 09:05 = 2 jam 14 menit)",
                    batas_waktu: `${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')}`,
                    elapsedMinutes: elapsedResult.elapsedMinutes,
                    elapsedTime: elapsedResult.elapsedTime
                });

                // Bersihkan dan buat ulang konten
                statusEl.innerHTML = '';

                if (lateMinutes > 0) {
                    // TERLAMBAT
                    const formattedLateTime = formatLateTime(lateMinutes);
                    const formattedElapsedTime = elapsedResult.elapsedTime;

                    // Teks utama "Terlambat"
                    const mainText = document.createElement('span');
                    mainText.textContent = 'Terlambat ';
                    statusEl.appendChild(mainText);

                    // Badge keterlambatan
                    const lateBadge = document.createElement('span');
                    lateBadge.className = 'late-time-badge';
                    lateBadge.textContent = `+${formattedLateTime}`;
                    statusEl.appendChild(lateBadge);

                    // Line break
                    statusEl.appendChild(document.createElement('br'));

                    // Waktu yang telah berlalu
                    const elapsedText = document.createElement('small');
                    elapsedText.style.color = 'var(--text-secondary)';
                    elapsedText.style.fontSize = '0.8rem';
                    elapsedText.textContent = `Waktu berlalu: ${formattedElapsedTime}`;
                    statusEl.appendChild(elapsedText);

                    statusEl.className = 'font-medium text-warning';

                    console.log("🎨 Setting status: TERLAMBAT", {
                        lateMinutes: lateMinutes,
                        lateTime: formattedLateTime,
                        elapsedTime: formattedElapsedTime
                    });
                } else {
                    // TEPAT WAKTU
                    const formattedElapsedTime = elapsedResult.elapsedTime;

                    // Teks utama "Tepat Waktu"
                    const mainText = document.createElement('span');
                    mainText.textContent = 'Tepat Waktu ';
                    statusEl.appendChild(mainText);

                    // Badge waktu yang telah berlalu
                    const elapsedBadge = document.createElement('span');
                    elapsedBadge.className = 'late-time-badge';
                    elapsedBadge.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                    elapsedBadge.style.color = '#10b981';
                    elapsedBadge.textContent = `${formattedElapsedTime} sejak check-in`;
                    statusEl.appendChild(elapsedBadge);

                    statusEl.className = 'font-medium text-success';

                    console.log("🎨 Setting status: TEPAT WAKTU", {
                        elapsedMinutes: elapsedResult.elapsedMinutes,
                        elapsedTime: formattedElapsedTime
                    });
                }

                // Simpan di data untuk referensi
                data.elapsed_minutes = elapsedResult.elapsedMinutes;
                data.is_terlambat = lateMinutes > 0;

                // Set tombol ke mode absen pulang
                updateMainActionButton(true);
                enableButtons(true);

            } else {
                // Belum absen sama sekali
                statusEl.textContent = 'Belum Absen';
                statusEl.className = 'font-medium text-success';

                updateMainActionButton(false);
                enableButtons(true);
            }
        }

        // Fungsi untuk update UI langsung setelah action
        function updateUIAfterAction(responseData) {
            if (!responseData) return;

            // Update data hari ini
            window.todayData = responseData;

            // Update UI status
            renderTodayStatusUI();

            // Tambahkan ke riwayat jika belum ada
            if (responseData.tanggal) {
                const existingIndex = attendanceHistoryData.findIndex(
                    item => item.tanggal === responseData.tanggal
                );

                if (existingIndex >= 0) {
                    // Update data yang sudah ada
                    attendanceHistoryData[existingIndex] = responseData;
                } else {
                    // Tambahkan data baru di awal array
                    attendanceHistoryData.unshift(responseData);
                }

                // Reset ke halaman pertama untuk menampilkan data terbaru
                currentPage = 1;

                // Render ulang tabel riwayat
                renderHistoryTable();
            }
        }

        // --- HISTORY FUNCTIONS ---

        async function fetchAndRenderHistory() {
            await fetchAttendanceHistory();
        }

        function renderHistoryTable() {
            const tbody = document.getElementById('history-tbody');
            const emptyState = document.getElementById('empty-state');
            const pagination = document.getElementById('pagination');

            // Tampilkan empty state jika tidak ada data
            if (attendanceHistoryData.length === 0) {
                tbody.style.display = 'none';
                emptyState.classList.remove('hidden');
                pagination.style.display = 'none';
                return;
            }

            tbody.style.display = 'table-row-group';
            emptyState.classList.add('hidden');
            tbody.innerHTML = '';

            // Pagination logic
            const totalPages = Math.ceil(attendanceHistoryData.length / recordsPerPage);
            if (currentPage > totalPages) currentPage = 1;

            const startIndex = (currentPage - 1) * recordsPerPage;
            const endIndex = startIndex + recordsPerPage;
            const pageData = attendanceHistoryData.slice(startIndex, endIndex);

            // Render rows
            pageData.forEach((record, index) => {
                const displayNo = startIndex + index + 1;
                const row = tbody.insertRow();
                row.className = 'table-row';
                row.style.borderBottom = '1px solid var(--border-color)';

                // Format tanggal
                const dateObj = new Date(record.tanggal);
                const dateStr = dateObj.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                // Tentukan status
                let statusHTML = '';

                // Cek apakah ada data cuti di record
                if (record.is_on_leave) {
                    // Status cuti
                    const leaveType = record.leave_type === 'annual' ? 'Tahunan' :
                        record.leave_type === 'sick' ? 'Sakit' :
                            record.leave_type === 'maternity' ? 'Melahirkan' :
                                record.leave_type === 'paternity' ? 'Menjaga Istri' :
                                    record.leave_type === 'unpaid' ? 'Tanpa Gaji' : 'Umum';

                    statusHTML = `<span class="status-badge status-cuti">CUTI (${leaveType})</span>`;

                } else if (record.jenis_ketidakhadiran) {
                    let badgeClass = 'status-absent';
                    if (record.jenis_ketidakhadiran === 'sakit') badgeClass = 'status-late';
                    if (record.jenis_ketidakhadiran === 'izin') badgeClass = 'status-pending';

                    let statusText = record.jenis_ketidakhadiran_label || 'Pengajuan';
                    if (record.approval_status === 'pending') {
                        statusText += ' (Menunggu)';
                    }

                    statusHTML = `<span class="status-badge ${badgeClass}">${statusText}</span>`;

                } else if (record.jam_masuk) {
                    // Cek keterlambatan dengan fungsi yang diperbaiki
                    const lateMinutes = calculateLateMinutesFromTime(record.jam_masuk);
                    const isLate = lateMinutes > 0;

                    const statusClass = isLate ? 'status-late' : 'status-on-time';
                    const statusLabel = isLate ? 'Terlambat' : 'Tepat Waktu';

                    statusHTML = `<span class="status-badge ${statusClass}">${statusLabel}</span>`;

                    if (isLate && lateMinutes > 0) {
                        const formattedLateTime = formatLateTime(lateMinutes);
                        statusHTML += `<span class="late-time-badge">+${formattedLateTime}</span>`;
                    }

                } else {
                    statusHTML = '<span class="status-badge status-no-show">Tidak Hadir</span>';
                }

                row.innerHTML = `
            <td class="py-3" style="color: var(--text-primary);">${displayNo}</td>
            <td class="py-3">${dateStr}</td>
            <td class="py-3">${formatTime(record.jam_masuk)}</td>
            <td class="py-3">${formatTime(record.jam_pulang)}</td>
            <td class="py-3">${statusHTML}</td>
        `;
            });

            // Render pagination
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const container = document.getElementById('pagination');
            container.innerHTML = '';

            if (totalPages <= 1) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'flex';

            // Tombol Previous
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '<span class="material-icons" style="font-size: 16px;">chevron_left</span>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderHistoryTable();
                }
            };
            container.appendChild(prevBtn);

            // Tombol nomor halaman
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, startPage + 4);

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    currentPage = i;
                    renderHistoryTable();
                };
                container.appendChild(pageBtn);
            }

            // Tombol Next
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = '<span class="material-icons" style="font-size: 16px;">chevron_right</span>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderHistoryTable();
                }
            };
            container.appendChild(nextBtn);
        }

        // --- CLOCK FUNCTION (WIB) ---
        function updateClock() {
            const now = new Date();

            // Format waktu sesuai timezone 'Asia/Jakarta' (WIB)
            const timeOptions = {
                timeZone: 'Asia/Jakarta',
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };

            // Format tanggal sesuai timezone 'Asia/Jakarta'
            const dateOptions = {
                timeZone: 'Asia/Jakarta',
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };

            try {
                // Waktu WIB
                const timeStr = now.toLocaleTimeString('id-ID', timeOptions);
                document.getElementById('clock-time').textContent = timeStr;

                // Tanggal WIB
                let dateStr = now.toLocaleDateString('id-ID', dateOptions);
                // Kapitalisasi hari
                dateStr = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
                document.getElementById('clock-date').textContent = dateStr;

            } catch (error) {
                // Fallback jika toLocaleString error
                console.error('Timezone error:', error);

                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                document.getElementById('clock-time').textContent = `${hours}:${minutes}:${seconds}`;

                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const dateStr = `${days[now.getDay()]}, ${String(now.getDate()).padStart(2, '0')} ${months[now.getMonth()]} ${now.getFullYear()}`;
                document.getElementById('clock-date').textContent = dateStr;
            }
        }

        // --- EVENT LISTENERS & INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', async () => {
            // Set theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }

            // Start clock (WIB)
            updateClock();
            setInterval(updateClock, 1000);

            // Setup tahun filter
            populateYearFilter();

            // Muat pengaturan jam operasional
            await loadOperationalHours();

            // Load initial data
            fetchAndDisplayTodayStatus();
            fetchAndRenderHistory();

            // Setup filter event listener
            document.getElementById('history-filter').addEventListener('change', () => {
                const filterType = document.getElementById('history-filter').value;
                currentFilterType = filterType;
                currentPage = 1;

                // Toggle custom filter visibility
                toggleCustomFilter();

                // Jika bukan custom, langsung fetch data
                if (filterType !== 'custom') {
                    fetchAttendanceHistory();
                }
            });

            // Setup custom filter apply button
            document.getElementById('apply-custom-filter').addEventListener('click', () => {
                const month = document.getElementById('filter-month').value;
                const year = document.getElementById('filter-year').value;

                if (!month || !year) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Filter Tidak Lengkap',
                        text: 'Harap pilih bulan dan tahun terlebih dahulu',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                customMonth = month;
                customYear = year;
                currentPage = 1;
                fetchAttendanceHistory();
            });

            // --- MAIN ACTION BUTTON (Check-in/Check-out) ---
            document.getElementById('main-action-btn').addEventListener('click', async function () {
                const action = this.dataset.action;

                // Refresh data dulu untuk memastikan state terkini
                await fetchAndDisplayTodayStatus();

                // VALIDASI CUTI: Cek apakah sedang cuti
                if (window.todayData && window.todayData.is_on_leave) {
                    const leaveType = window.todayData.leave_type || 'Umum';
                    const leaveReason = window.todayData.leave_reason ?
                        `<br><small>Alasan: ${window.todayData.leave_reason}</small>` : '';

                    const leaveDates = window.todayData.leave_dates ?
                        `<br><small>Periode: ${new Date(window.todayData.leave_dates.start).toLocaleDateString('id-ID')} - ${new Date(window.todayData.leave_dates.end).toLocaleDateString('id-ID')}</small>` : '';

                    await Swal.fire({
                        icon: 'info',
                        title: 'Sedang Cuti',
                        html: `Anda sedang dalam status cuti (${leaveType})${leaveReason}${leaveDates}<br>
                       <small class="text-sm">Tidak dapat melakukan absensi selama periode cuti.</small>`,
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                if (action === 'Absen Pulang') {
                    // Validasi: harus sudah absen masuk dulu
                    if (!window.todayData || !window.todayData.jam_masuk) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Belum Absen Masuk',
                            text: 'Anda harus melakukan absen masuk terlebih dahulu sebelum absen pulang.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Validasi: sudah absen pulang
                    if (window.todayData.jam_pulang) {
                        await Swal.fire({
                            icon: 'info',
                            title: 'Sudah Absen Pulang',
                            text: 'Anda sudah melakukan absen pulang hari ini.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Tampilkan waktu WIB saat ini
                    const currentWIBTime = getCurrentWIBHoursMinutes();

                    // Konfirmasi absen pulang dengan waktu WIB
                    const result = await Swal.fire({
                        title: 'Konfirmasi Absen Pulang',
                        html: `<p>Apakah Anda yakin ingin melakukan <strong>Absen Pulang</strong>?</p>
                       <p class="text-sm mt-2">Waktu saat ini: <strong>${currentWIBTime} WIB</strong></p>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Absen Pulang',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280'
                    });

                    if (result.isConfirmed) {
                        // Cek apakah pulang lebih awal (sebelum jam operasional)
                        const currentWIBHour = getCurrentWIBHour();
                        const endHour = parseInt(END_TIME.split(':')[0]);

                        // Siapkan payload dengan default reason kosong
                        let payload = {
                            reason: '' // Default reason untuk checkout normal
                        };

                        if (currentWIBHour < endHour) {
                            const earlyResult = await Swal.fire({
                                title: 'Pulang Lebih Awal',
                                html: `
                            <div class="form-group">
                                <label><span class="material-icons">info</span> Alasan Pulang Lebih Awal <span class="required">*</span></label>
                                <textarea id="early-checkout-reason" class="uniform-textarea" placeholder="Masukkan alasan pulang lebih awal..." rows="3" required></textarea>
                                <p class="file-helper">Harap isi alasan jika pulang sebelum jam ${END_TIME} WIB</p>
                            </div>
                        `,
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: 'Kirim',
                                cancelButtonText: 'Batal',
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#6b7280',
                                preConfirm: () => {
                                    const reason = document.getElementById('early-checkout-reason').value.trim();
                                    if (!reason) {
                                        Swal.showValidationMessage('Harap masukkan alasan pulang lebih awal');
                                        return false;
                                    }
                                    return reason;
                                }
                            });

                            if (earlyResult.isConfirmed) {
                                payload.reason = earlyResult.value;
                            } else {
                                return; // User membatalkan
                            }
                        }

                        // Kirim request absen pulang
                        try {
                            Swal.showLoading();

                            const response = await apiFetch('/absen-pulang', {
                                method: 'POST',
                                body: JSON.stringify(payload)
                            });

                            Swal.hideLoading();

                            if (response.success) {
                                // Update UI dengan data baru
                                updateUIAfterAction(response.data);

                                // Tampilkan waktu WIB yang berhasil dicatat
                                const jamPulangWIB = formatTimeDetailed(response.data.jam_pulang);

                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    html: `Absen pulang berhasil dicatat<br>
                                   <p class="text-sm mt-2">Jam Pulang: <strong>${jamPulangWIB}</strong></p>`,
                                    confirmButtonColor: '#3b82f6'
                                });

                            } else {
                                throw new Error(response.message || 'Gagal melakukan absen pulang');
                            }

                        } catch (error) {
                            Swal.hideLoading();
                            await Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: error.message || 'Terjadi kesalahan saat melakukan absen pulang',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    }

                } else if (action === 'Absen Masuk') {
                    // Validasi: sudah absen masuk hari ini
                    if (window.todayData && window.todayData.jam_masuk) {
                        const jamMasukWIB = formatTimeDetailed(window.todayData.jam_masuk);
                        await Swal.fire({
                            icon: 'info',
                            title: 'Sudah Absen Masuk',
                            text: `Anda sudah melakukan absen masuk hari ini pukul ${jamMasukWIB}`,
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Validasi: ada pengajuan sakit/izin
                    if (window.todayData && window.todayData.jenis_ketidakhadiran) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Absen',
                            text: `Anda sudah mengajukan ${window.todayData.jenis_ketidakhadiran_label} hari ini.`,
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Tampilkan waktu WIB saat ini
                    const currentWIBTime = getCurrentWIBHoursMinutes();

                    // Konfirmasi absen masuk dengan info waktu
                    const result = await Swal.fire({
                        title: 'Konfirmasi Absen Masuk',
                        html: `<p>Apakah Anda yakin ingin melakukan <strong>Absen Masuk</strong>?</p>
                       <p class="text-sm mt-2">Waktu saat ini: <strong>${currentWIBTime} WIB</strong></p>
                       <p class="text-xs mt-1 text-warning">Batas waktu absen masuk: <strong>${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')} WIB</strong></p>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Absen Masuk',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280'
                    });

                    if (result.isConfirmed) {
                        try {
                            Swal.showLoading();

                            const response = await apiFetch('/absen-masuk', {
                                method: 'POST',
                                body: JSON.stringify({})
                            });

                            Swal.hideLoading();

                            if (response.success) {
                                // Update UI dengan data baru
                                updateUIAfterAction(response.data);

                                // Tampilkan pesan sesuai status terlambat
                                let message = response.message || 'Absen masuk berhasil dicatat';
                                let icon = 'success';
                                let title = 'Berhasil!';
                                let jamMasukWIB = formatTimeDetailed(response.data.jam_masuk);

                                console.log("Absen masuk response:", {
                                    raw: response.data.jam_masuk,
                                    formatted: jamMasukWIB
                                });

                                // Hitung keterlambatan untuk notifikasi
                                const lateMinutes = calculateLateMinutesFromTime(response.data.jam_masuk);

                                if (lateMinutes > 0) {
                                    const lateTime = formatLateTime(lateMinutes);
                                    message = `Absen masuk berhasil dicatat<br><small>(Terlambat ${lateTime} dari batas ${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')})</small>`;
                                    icon = 'warning';
                                    title = 'Absen Masuk (Terlambat)';
                                } else {
                                    message = `Absen masuk berhasil dicatat<br><small>(Tepat waktu, batas ${LIMIT_HOUR.toString().padStart(2, '0')}:${LIMIT_MINUTE.toString().padStart(2, '0')})</small>`;
                                }

                                await Swal.fire({
                                    icon: icon,
                                    title: title,
                                    html: `${message}<br>
                                   <p class="text-sm mt-2">Jam Masuk: <strong>${jamMasukWIB}</strong></p>`,
                                    confirmButtonColor: '#3b82f6'
                                });

                            } else {
                                throw new Error(response.message || 'Gagal melakukan absen masuk');
                            }

                        } catch (error) {
                            Swal.hideLoading();
                            await Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: error.message || 'Terjadi kesalahan saat melakukan absen masuk',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    }
                }
            });

            // --- SAKIT BUTTON ---
            document.querySelector('.btn-sakit').addEventListener('click', async () => {
                // Refresh data dulu
                await fetchAndDisplayTodayStatus();

                // VALIDASI CUTI
                if (window.todayData && window.todayData.is_on_leave) {
                    const leaveType = window.todayData.leave_type || 'Umum';
                    await Swal.fire({
                        icon: 'info',
                        title: 'Sedang Cuti',
                        html: `Anda sedang dalam status cuti (${leaveType}).<br>
                       <small>Tidak dapat mengajukan sakit selama periode cuti.</small>`,
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                // Validasi: sudah absen atau ada pengajuan
                if (window.todayData) {
                    if (window.todayData.jam_masuk) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Mengajukan',
                            text: 'Anda sudah melakukan absen masuk hari ini.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    if (window.todayData.jenis_ketidakhadiran) {
                        await Swal.fire({
                            icon: 'info',
                            title: 'Sudah Ada Pengajuan',
                            text: `Anda sudah mengajukan ${window.todayData.jenis_ketidakhadiran_label} hari ini.`,
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }
                }

                // Tampilkan form sakit
                const today = new Date().toISOString().split('T')[0];

                const result = await Swal.fire({
                    title: '<span class="material-icons">local_hospital</span> Form Sakit',
                    html: `
                <div class="form-group">
                    <label><span class="material-icons">calendar_today</span> Tanggal Mulai <span class="required">*</span></label>
                    <input type="date" id="sakit-start" class="uniform-input" value="${today}" min="${today}">
                </div>
                <div class="form-group">
                    <label><span class="material-icons">calendar_today</span> Tanggal Selesai <span class="required">*</span></label>
                    <input type="date" id="sakit-end" class="uniform-input" value="${today}" min="${today}">
                </div>
                <div class="form-group">
                    <label><span class="material-icons">description</span> Keterangan <span class="required">*</span></label>
                    <textarea id="sakit-reason" class="uniform-textarea" placeholder="Masukkan keterangan sakit..." rows="3" required></textarea>
                    <p class="file-helper">Harap isi keterangan dengan jelas</p>
                </div>
            `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Kirim Pengajuan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    preConfirm: () => {
                        const start = document.getElementById('sakit-start').value;
                        const end = document.getElementById('sakit-end').value;
                        const reason = document.getElementById('sakit-reason').value.trim();

                        if (!start || !end || !reason) {
                            Swal.showValidationMessage('Semua field harus diisi');
                            return false;
                        }

                        if (new Date(end) < new Date(start)) {
                            Swal.showValidationMessage('Tanggal selesai tidak boleh sebelum tanggal mulai');
                            return false;
                        }

                        return { start_date: start, end_date: end, reason: reason };
                    }
                });

                if (result.isConfirmed) {
                    try {
                        Swal.showLoading();

                        // Ganti kode fetch lama dengan ini:
                        const response = await apiFetch('/submit-izin', {
                            method: 'POST',
                            body: JSON.stringify({
                                type: 'sakit',                // <--- PERHATIKAN: TYPE (bukan jenis)
                                start_date: result.value.start_date,
                                end_date: result.value.end_date,
                                reason: result.value.reason
                            })
                        });

                        Swal.hideLoading();

                        if (response.success) {
                            // Update UI
                            updateUIAfterAction(response.data);

                            await Swal.fire({
                                icon: 'success',
                                title: 'Pengajuan Terkirim!',
                                text: 'Pengajuan sakit Anda telah dikirim dan sedang menunggu persetujuan.',
                                confirmButtonColor: '#3b82f6'
                            });

                        } else {
                            throw new Error(response.message || 'Gagal mengirim pengajuan');
                        }

                    } catch (error) {
                        Swal.hideLoading();
                        await Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: error.message || 'Terjadi kesalahan saat mengirim pengajuan',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });

            // --- IZIN BUTTON ---
            document.querySelector('.btn-izin').addEventListener('click', async () => {
                // Refresh data dulu
                await fetchAndDisplayTodayStatus();

                // VALIDASI CUTI
                if (window.todayData && window.todayData.is_on_leave) {
                    const leaveType = window.todayData.leave_type || 'Umum';
                    await Swal.fire({
                        icon: 'info',
                        title: 'Sedang Cuti',
                        html: `Anda sedang dalam status cuti (${leaveType}).<br>
                       <small>Tidak dapat mengajukan izin selama periode cuti.</small>`,
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                // Validasi: sudah absen atau ada pengajuan
                if (window.todayData) {
                    if (window.todayData.jam_masuk) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Mengajukan',
                            text: 'Anda sudah melakukan absen masuk hari ini.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    if (window.todayData.jenis_ketidakhadiran) {
                        await Swal.fire({
                            icon: 'info',
                            title: 'Sudah Ada Pengajuan',
                            text: `Anda sudah mengajukan ${window.todayData.jenis_ketidakhadiran_label} hari ini.`,
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }
                }

                // Tampilkan form izin
                const today = new Date().toISOString().split('T')[0];

                const result = await Swal.fire({
                    title: '<span class="material-icons">event_busy</span> Form Izin',
                    html: `
                <div class="form-group">
                    <label><span class="material-icons">calendar_today</span> Tanggal Mulai <span class="required">*</span></label>
                    <input type="date" id="izin-start" class="uniform-input" value="${today}" min="${today}">
                </div>
                <div class="form-group">
                    <label><span class="material-icons">calendar_today</span> Tanggal Selesai <span class="required">*</span></label>
                    <input type="date" id="izin-end" class="uniform-input" value="${today}" min="${today}">
                </div>
                <div class="form-group">
                    <label><span class="material-icons">description</span> Keterangan <span class="required">*</span></label>
                    <textarea id="izin-reason" class="uniform-textarea" placeholder="Masukkan keterangan izin..." rows="3" required></textarea>
                    <p class="file-helper">Harap isi keterangan dengan jelas</p>
                </div>
            `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Kirim Pengajuan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    preConfirm: () => {
                        const start = document.getElementById('izin-start').value;
                        const end = document.getElementById('izin-end').value;
                        const reason = document.getElementById('izin-reason').value.trim();

                        if (!start || !end || !reason) {
                            Swal.showValidationMessage('Semua field harus diisi');
                            return false;
                        }

                        if (new Date(end) < new Date(start)) {
                            Swal.showValidationMessage('Tanggal selesai tidak boleh sebelum tanggal mulai');
                            return false;
                        }

                        return { start_date: start, end_date: end, reason: reason };
                    }
                });

                if (result.isConfirmed) {
                    try {
                        Swal.showLoading();

                        // Ganti kode fetch lama dengan ini:
                        const response = await apiFetch('/submit-izin', {
                            method: 'POST',
                            body: JSON.stringify({
                                type: 'izin',                  // <--- PERHATIKAN: TYPE (bukan jenis)
                                start_date: result.value.start_date,
                                end_date: result.value.end_date,
                                reason: result.value.reason
                            })
                        });

                        Swal.hideLoading();

                        if (response.success) {
                            // Update UI
                            updateUIAfterAction(response.data);

                            await Swal.fire({
                                icon: 'success',
                                title: 'Pengajuan Terkirim!',
                                text: 'Pengajuan izin Anda telah dikirim dan sedang menunggu persetujuan.',
                                confirmButtonColor: '#3b82f6'
                            });

                        } else {
                            throw new Error(response.message || 'Gagal mengirim pengajuan');
                        }

                    } catch (error) {
                        Swal.hideLoading();
                        await Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: error.message || 'Terjadi kesalahan saat mengirim pengajuan',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });

            // Auto refresh data setiap 30 detik
            setInterval(async () => {
                await fetchAndDisplayTodayStatus();
            }, 30000);
        });
    </script>
</body>

</html>
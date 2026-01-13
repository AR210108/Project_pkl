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
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px var(--shadow-color);
        }

        .action-card .material-icons {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            color: #3b82f6;
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
            cursor: pointer;
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

        /* --- REVISI: Tambahkan CSS untuk status pending --- */
        .status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
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
        .uniform-textarea {
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

        .uniform-input {
            height: 48px !important;
        }

        .uniform-input:focus,
        .uniform-textarea:focus {
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
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col p-4 lg:p-8">
        @include('karyawan.templet.header')

        <main class="flex-grow w-full max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold" style="color: var(--text-primary);">ABSENSI KARYAWAN</h2>
            </div>

            <!-- Clock -->
            <div class="card clock-container mb-8">
                <p class="clock-time" id="clock-time">12:00:00</p>
                <p class="text-lg mt-2" id="clock-date">Senin, 01 Januari 2025</p>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="card action-card absensi-btn" data-action="Absen Masuk"><span
                        class="material-icons">login</span>
                    <p class="font-semibold" style="color: var(--text-primary);">ABSEN MASUK</p>
                </div>
                <div class="card action-card absensi-btn" data-action="Absen Pulang"><span
                        class="material-icons">logout</span>
                    <p class="font-semibold" style="color: var(--text-primary);">ABSEN PULANG</p>
                </div>
                <div class="card action-card absensi-izin-btn" data-action="Izin"><span
                        class="material-icons">event_busy</span>
                    <p class="font-semibold" style="color: var(--text-primary);">IZIN</p>
                </div>
                <div class="card action-card absensi-dinas-btn" data-action="Dinas Luar"><span
                        class="material-icons">work_outline</span>
                    <p class="font-semibold" style="color: var(--text-primary);">DINAS LUAR</p>
                </div>
            </div>

            <!-- Status & History -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Riwayat Absensi -->
                <div class="card lg:col-span-2">
                    <h3 class="font-bold text-xl mb-4 flex items-center justify-between"
                        style="color: var(--text-primary);">
                        <span class="flex items-center"><span
                                class="material-icons text-primary mr-2">history</span>Riwayat Absensi</span>
                        <button class="text-sm text-primary hover:underline" id="view-all-btn">Lihat Semua</button>
                    </h3>
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
                    <div id="empty-state" class="empty-state" style="display: none;"><span
                            class="material-icons">assignment_late</span>
                        <h4>Belum Ada Riwayat Absensi</h4>
                        <p>Anda belum memiliki riwayat absensi. Silakan lakukan absen terlebih dahulu.</p>
                    </div>
                    <!-- Pagination Container sudah ada dan berfungsi -->
                    <div class="pagination-container" id="pagination"></div>
                </div>

                <!-- Status Absensi -->
                <div class="card">
                    <h3 class="font-bold text-xl mb-4 flex items-center" style="color: var(--text-primary);"><span
                            class="material-icons text-primary mr-2">assignment</span>Status Absensi</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3"><span class="material-icons text-primary">login</span>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Absen Masuk</p>
                                <p class="text-sm" id="today-checkin" style="color: var(--text-secondary);">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3"><span class="material-icons text-primary">logout</span>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Absen Pulang</p>
                                <p class="text-sm" id="today-checkout" style="color: var(--text-secondary);">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3"><span
                                class="material-icons text-success">check_circle</span>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Status Hari Ini</p>
                                <p class="font-medium text-success" id="today-status">Belum Absen</p>
                            </div>
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
        window.csrfToken = '{{ csrf_token() }}';

        /**
         * MEMFORMAT TOTAL MENIT KE FORMAT YANG LEBIH BISA DIBACA
         * @param {number} totalMinutes - Total menit keterlambatan (contoh: 439.8)
         * @returns {string} - String yang sudah diformat (contoh: "7 jam 20 menit")
         */
        function formatLateTime(totalMinutes) {
            if (totalMinutes <= 0) {
                return '0 menit';
            }
            const hours = Math.floor(totalMinutes / 60);
            const minutes = Math.round(totalMinutes % 60);
            if (hours > 0 && minutes > 0) {
                return `${hours} jam ${minutes} menit`;
            } else if (hours > 0) {
                return `${hours} jam`;
            } else {
                // Jika kurang dari 1 jam, tampilkan menit yang dibulatkan
                return `${Math.round(totalMinutes)} menit`;
            }
        }

        /**
         * Memformat string waktu dari server menjadi format HH:MM waktu lokal.
         * Fungsi ini tangguh menangani konversi timezone, terutama dari UTC ke waktu lokal browser.
         * @param {string|null} timeString - String waktu dari server (contoh: "2025-06-17T12:00:00.000000Z" atau "2025-06-17 12:00:00")
         * @returns {string} - Waktu lokal yang sudah diformat (contoh: "12:00") atau "-" jika null
         */
        function formatTime(timeString) {
            if (!timeString) return '-';
            try {
                const date = new Date(timeString);
                if (isNaN(date.getTime())) return timeString;
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
            } catch (e) {
                console.error("Error formatting time:", e);
                return timeString;
            }
        }

        /**
         * Memeriksa apakah waktu saat ini sebelum waktu pulang kerja
         * @returns {boolean} - True jika waktu saat ini sebelum waktu pulang kerja
         */
        function isBeforeCheckoutTime() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();

            // Waktu pulang kerja adalah 17:00 (5 PM)
            // Ubah nilai ini sesuai dengan kebijakan perusahaan Anda
            const checkoutHour = 17;
            const checkoutMinute = 0;

            // Jika waktu saat ini sebelum waktu pulang kerja
            if (currentHour < checkoutHour || (currentHour === checkoutHour && currentMinute < checkoutMinute)) {
                return true;
            }

            return false;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark');
            fetchAndDisplayTodayStatus();
            fetchAndRenderHistory();
        });

        const API_URL = '/api/karyawan';
        async function apiFetch(endpoint, options = {}) {
            // --- TAMBAHKAN CACHE BUSTING DI SINI ---
            const cacheBuster = `_t=${Date.now()}`;
            const url = `${API_URL}${endpoint}${endpoint.includes('?') ? '&' : '?'}${cacheBuster}`;

            const defaultOptions = { headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.csrfToken } };
            const finalOptions = { ...defaultOptions, ...options };
            const response = await fetch(url, finalOptions);
            if (response.status === 419) throw new Error('CSRF token mismatch. Silakan muat ulang halaman.');
            if (!response.ok) { const errorData = await response.json().catch(() => ({})); throw new Error(errorData.message || 'Something went wrong'); }
            return response.json();
        }

        async function fetchAndDisplayTodayStatus() {
            try {
                const data = await apiFetch('/today-status');
                document.getElementById('today-checkin').textContent = formatTime(data.jam_masuk);
                document.getElementById('today-checkout').textContent = formatTime(data.jam_pulang);

                const statusEl = document.getElementById('today-status');

                // --- REVISI: Tambahkan logika untuk status pending ---
                if (data.status_type === 'absent' && data.approval_status === 'pending') {
                    statusEl.textContent = 'Menunggu Persetujuan';
                    statusEl.className = 'font-medium text-warning';
                } else if (data.status_type === 'late' && data.late_minutes > 0) {
                    const formattedLateTime = formatLateTime(data.late_minutes);
                    statusEl.innerHTML = `Terlambat <span class="late-time-badge">+${formattedLateTime}</span>`;
                    statusEl.className = 'font-medium text-warning';
                } else if (data.status_type === 'on-time') {
                    statusEl.textContent = 'Tepat Waktu';
                    statusEl.className = 'font-medium text-success';
                } else {
                    statusEl.textContent = data.status || 'Belum Absen';
                    statusEl.className = 'font-medium text-success';
                }

                // --- PERUBAHAN: NONAKTIFKAN TOMBOL ABSEN MASUK JIKA IZIN/DINAS ---
                const checkinBtn = document.querySelector('.absensi-btn[data-action="Absen Masuk"]');
                if (checkinBtn) {
                    // Cek apakah statusnya adalah 'Sakit' atau 'Dinas Luar'
                    if (data.status === 'Sakit' || data.status === 'Dinas Luar') {
                        checkinBtn.disabled = true; // Nonaktifkan tombol
                        checkinBtn.style.opacity = '0.5'; // Buat terlihat redup
                        checkinBtn.style.cursor = 'not-allowed'; // Ubah kursor
                        checkinBtn.title = `Tidak dapat absen karena status: ${data.status}`; // Tambahkan tooltip
                    } else {
                        // Pastikan tombol aktif jika statusnya memungkinkan
                        checkinBtn.disabled = false;
                        checkinBtn.style.opacity = '1';
                        checkinBtn.style.cursor = 'pointer';
                        checkinBtn.removeAttribute('title');
                    }
                }
                // --- AKHIR PERUBAHAN ---

            } catch (error) {
                console.error('Gagal memuat status hari ini:', error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        let currentPage = 1;
        const recordsPerPage = 5; // Pagination: 5 data per halaman
        let attendanceData = [];

        async function fetchAndRenderHistory() {
            try {
                attendanceData = await apiFetch('/history');
                renderHistoryTable();
            } catch (error) {
                console.error('Gagal memuat riwayat:', error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        function renderPagination() {
            const totalPages = Math.ceil(attendanceData.length / recordsPerPage);
            const container = document.getElementById('pagination');
            container.innerHTML = '';
            if (totalPages <= 1) { container.style.display = 'none'; return; }
            container.style.display = 'flex';
            const createBtn = (content, onClick, disabled = false, active = false) => {
                const btn = document.createElement('button');
                btn.className = `pagination-btn ${active ? 'active' : ''}`;
                btn.innerHTML = content;
                btn.disabled = disabled;
                btn.onclick = onClick;
                container.appendChild(btn);
            };
            createBtn('<span class="material-icons" style="font-size: 16px;">chevron_left</span>', () => { currentPage--; renderHistoryTable(); }, currentPage === 1);
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    createBtn(i, () => { currentPage = i; renderHistoryTable(); }, false, i === currentPage);
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    const ellipsis = document.createElement('span'); ellipsis.className = 'pagination-info'; ellipsis.textContent = '...'; container.appendChild(ellipsis);
                }
            }
            createBtn('<span class="material-icons" style="font-size: 16px;">chevron_right</span>', () => { currentPage++; renderHistoryTable(); }, currentPage === totalPages);
        }

        function renderHistoryTable() {
            const tbody = document.getElementById('history-tbody');
            const emptyState = document.getElementById('empty-state');
            if (attendanceData.length === 0) {
                tbody.style.display = 'none'; emptyState.style.display = 'block';
                renderPagination(); return;
            }
            tbody.style.display = 'table-row-group'; emptyState.style.display = 'none'; tbody.innerHTML = '';
            const pageData = attendanceData.slice((currentPage - 1) * recordsPerPage, currentPage * recordsPerPage);

            pageData.forEach((record, index) => {
                const displayNo = (currentPage - 1) * recordsPerPage + index + 1;

                let statusHTML = '';

                // --- REVISI: Tambahkan logika untuk status pending ---
                if (record.statusType === 'absent' && record.approvalStatus === 'pending') {
                    statusHTML = `<span class="status-badge status-pending">Menunggu Persetujuan</span>`;
                } else {
                    const statusClass = `status-${record.statusType}`;
                    statusHTML = `<span class="status-badge ${statusClass}">${record.status}</span>`;

                    if (record.statusType === 'late' && record.lateMinutes > 0) {
                        const formattedLateTime = formatLateTime(record.lateMinutes);
                        statusHTML += `<span class="late-time-badge">+${formattedLateTime}</span>`;
                    }
                }

                const row = tbody.insertRow();
                row.className = 'table-row'; row.style.borderBottom = `1px solid var(--border-color)`;
                row.innerHTML = `<td class="py-3" style="color: var(--text-primary);">${displayNo}</td><td class="py-3">${record.date}</td><td class="py-3">${formatTime(record.checkIn)}</td><td class="py-3">${formatTime(record.checkOut)}</td><td class="py-3">${statusHTML}</td>`;
            });
            renderPagination();
        }

        // Fungsi Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock-time').textContent = now.toLocaleTimeString('id-ID');
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            document.getElementById('clock-date').textContent = `${days[now.getDay()]}, ${String(now.getDate()).padStart(2, '0')} ${months[now.getMonth()]} ${now.getFullYear()}`;
        }
        setInterval(updateClock, 1000); updateClock();

        // Event Listener untuk tombol Absen Masuk & Pulang
        document.querySelectorAll('.absensi-btn').forEach(btn => {
            btn.addEventListener('click', async function () {
                const action = this.dataset.action;

                // Untuk Absen Pulang, periksa apakah sudah waktunya pulang
                if (action === 'Absen Pulang') {
                    // Periksa apakah sudah absen masuk hari ini
                    try {
                        const todayStatus = await apiFetch('/today-status');
                        if (todayStatus.jam_masuk === null) {
                            Swal.fire('Perhatian', 'Anda belum melakukan absen masuk hari ini.', 'warning');
                            return;
                        }

                        // Periksa apakah sudah waktunya pulang
                        if (isBeforeCheckoutTime()) {
                            // Tampilkan form alasan pulang cepat
                            const result = await Swal.fire({
                                title: '<span class="material-icons">warning</span> Pulang Lebih Awal',
                                html: `<p>Anda mencoba untuk pulang lebih awal dari waktu yang ditentukan. Silakan berikan alasan Anda.</p>
                               <div class="form-group">
                                   <label><span class="material-icons">description</span> Alasan Pulang Cepat <span class="required">*</span></label>
                                   <textarea id="early-checkout-reason" class="uniform-textarea" placeholder="Jelaskan alasan Anda pulang lebih awal..."></textarea>
                               </div>`,
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: 'Kirim',
                                cancelButtonText: 'Batal',
                                confirmButtonColor: '#3b82f6',
                                preConfirm: () => {
                                    const reason = document.getElementById('early-checkout-reason').value;
                                    if (!reason) {
                                        Swal.showValidationMessage('Alasan harus diisi');
                                        return false;
                                    }
                                    return { reason };
                                }
                            });

                            if (result.isConfirmed) {
                                try {
                                    const response = await apiFetch('/absen-pulang', {
                                        method: 'POST',
                                        body: JSON.stringify({
                                            reason: result.value.reason
                                        })
                                    });

                                    Swal.fire('Berhasil!', response.message, 'success');

                                    // Perbarui UI
                                    const time = formatTime(response.data.time);
                                    document.getElementById('today-checkout').textContent = time;

                                    await fetchAndRenderHistory();
                                } catch (error) {
                                    Swal.fire('Gagal', error.message, 'error');
                                }
                            }

                            return; // Keluar dari fungsi jika sudah menampilkan form alasan
                        }
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                        return;
                    }
                }

                // Proses normal untuk Absen Masuk atau Absen Pulang (jika sudah waktunya)
                const endpoint = action === 'Absen Masuk' ? '/absen-masuk' : '/absen-pulang';
                const result = await Swal.fire({
                    title: 'Konfirmasi',
                    html: `<p>Apakah kamu yakin ingin melakukan <strong>${action}</strong>?</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#64748b'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await apiFetch(endpoint, { method: 'POST' });
                        Swal.fire('Berhasil!', response.message, 'success');

                        // Perbarui UI berdasarkan response dari backend
                        if (action === 'Absen Masuk') {
                            const time = formatTime(response.data.time);
                            const status = response.data.status;
                            const lateMinutes = response.data.late_minutes;
                            document.getElementById('today-checkin').textContent = time;
                            const statusEl = document.getElementById('today-status');

                            // Gunakan fungsi formatLateTime di sini
                            if (status === 'Terlambat' && lateMinutes > 0) {
                                const formattedLateTime = formatLateTime(lateMinutes);
                                statusEl.innerHTML = `Terlambat <span class="late-time-badge">+${formattedLateTime}</span>`;
                                statusEl.className = 'font-medium text-warning';
                            } else {
                                statusEl.textContent = 'Tepat Waktu';
                                statusEl.className = 'font-medium text-success';
                            }
                        } else if (action === 'Absen Pulang') {
                            const time = formatTime(response.data.time);
                            document.getElementById('today-checkout').textContent = time;
                        }

                        await fetchAndRenderHistory();
                    } catch (error) {
                        Swal.fire('Gagal', error.message, 'error');
                    }
                }
            });
        });

        // Event Listener untuk tombol Izin
        document.querySelector('.absensi-izin-btn').addEventListener('click', async () => {
            const result = await Swal.fire({
                title: '<span class="material-icons">event_busy</span> Form Pengajuan Izin',
                html: `<div class="form-group"><label><span class="material-icons">calendar_today</span> Tanggal Mulai <span class="required">*</span></label><input type="date" id="start-date" class="uniform-input"></div>
               <div class="form-group"><label><span class="material-icons">event</span> Tanggal Selesai <span class="required">*</span></label><input type="date" id="end-date" class="uniform-input"></div>
               <div class="form-group"><label><span class="material-icons">category</span> Tipe Izin <span class="required">*</span></label><select id="type" class="uniform-input"><option value="">Pilih Tipe Izin</option><option value="Cuti">Cuti</option><option value="Sakit">Sakit</option><option value="Izin">Izin Pribadi</option></select></div>
               <div class="form-group"><label><span class="material-icons">description</span> Alasan <span class="required">*</span></label><textarea id="reason" class="uniform-textarea" placeholder="Jelaskan alasan izin Anda..."></textarea></div>`,
                focusConfirm: false, showCancelButton: true, confirmButtonText: 'Kirim', cancelButtonText: 'Batal', confirmButtonColor: '#3b82f6',
                preConfirm: () => {
                    const s = document.getElementById('start-date').value, e = document.getElementById('end-date').value, t = document.getElementById('type').value, r = document.getElementById('reason').value;
                    if (!s || !e || !t || !r) { Swal.showValidationMessage('Semua kolom wajib diisi'); return false; }
                    if (e < s) { Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai'); return false; }
                    return { start_date: s, end_date: e, type: t, reason: r };
                }
            });
            if (result.isConfirmed) {
                try { const response = await apiFetch('/submit-izin', { method: 'POST', body: JSON.stringify(result.value) }); Swal.fire('Pengajuan Terkirim!', response.message, 'success'); await fetchAndRenderHistory(); }
                catch (error) { Swal.fire('Gagal', error.message, 'error'); }
            }
        });

        // Event Listener untuk tombol Dinas Luar
        document.querySelector('.absensi-dinas-btn').addEventListener('click', async () => {
            const result = await Swal.fire({
                title: '<span class="material-icons">work_outline</span> Form Pengajuan Dinas Luar',
                html: `<div class="form-group"><label><span class="material-icons">calendar_today</span> Tanggal Mulai <span class="required">*</span></label><input type="date" id="dinas-start-date" class="uniform-input"></div>
               <div class="form-group"><label><span class="material-icons">event</span> Tanggal Selesai <span class="required">*</span></label><input type="date" id="dinas-end-date" class="uniform-input"></div>
               <div class="form-group"><label><span class="material-icons">location_on</span> Lokasi <span class="required">*</span></label><input type="text" id="location" class="uniform-input" placeholder="Masukkan lokasi dinas"></div>
               <div class="form-group"><label><span class="material-icons">flag</span> Tujuan Kegiatan <span class="required">*</span></label><input type="text" id="purpose" class="uniform-input" placeholder="Masukkan tujuan kegiatan"></div>
               <div class="form-group"><label><span class="material-icons">description</span> Deskripsi <span class="required">*</span></label><textarea id="dinas-description" class="uniform-textarea" placeholder="Jelaskan kegiatan dinas Anda..."></textarea></div>`,
                focusConfirm: false, showCancelButton: true, confirmButtonText: 'Kirim', cancelButtonText: 'Batal', confirmButtonColor: '#3b82f6',
                preConfirm: () => {
                    const s = document.getElementById('dinas-start-date').value, e = document.getElementById('dinas-end-date').value, l = document.getElementById('location').value, p = document.getElementById('purpose').value, d = document.getElementById('dinas-description').value;
                    if (!s || !e || !l || !p || !d) { Swal.showValidationMessage('Semua kolom wajib diisi'); return false; }
                    if (e < s) { Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai'); return false; }
                    return { start_date: s, end_date: e, location: l, purpose: p, description: d };
                }
            });
            if (result.isConfirmed) {
                try { const response = await apiFetch('/submit-dinas', { method: 'POST', body: JSON.stringify(result.value) }); Swal.fire('Pengajuan Terkirim!', response.message, 'success'); await fetchAndRenderHistory(); }
                catch (error) { Swal.fire('Gagal', error.message, 'error'); }
            }
        });

        // Event Listener untuk "Lihat Semua"
        document.getElementById('view-all-btn').addEventListener('click', () => {
            if (attendanceData.length === 0) {
                Swal.fire({ title: 'Riwayat Absensi', html: '<div class="empty-state"><span class="material-icons">assignment_late</span><h4>Belum Ada Riwayat Absensi</h4><p>Anda belum memiliki riwayat absensi.</p></div>', confirmButtonText: 'Tutup', confirmButtonColor: '#3b82f6' });
                return;
            }
            let tableHTML = `<table class="w-full text-left"><thead><tr><th>No</th><th>Tanggal</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Status</th></tr></thead><tbody>`;
            attendanceData.forEach((r, i) => {
                let statusHTML = '';

                // --- REVISI: Tambahkan logika untuk status pending di modal ---
                if (r.statusType === 'absent' && r.approvalStatus === 'pending') {
                    statusHTML = `<span class="status-badge status-pending">Menunggu Persetujuan</span>`;
                } else {
                    const statusClass = `status-${r.statusType}`;
                    statusHTML = `<span class="status-badge ${statusClass}">${r.status}</span>`;
                    // Gunakan fungsi formatLateTime di sini juga
                    if (r.statusType === 'late' && r.lateMinutes > 0) {
                        const formattedLateTime = formatLateTime(r.lateMinutes);
                        statusHTML += `<span class="late-time-badge">+${formattedLateTime}</span>`;
                    }
                }
                tableHTML += `<tr><td>${i + 1}</td><td>${r.date}</td><td>${formatTime(r.checkIn)}</td><td>${formatTime(r.checkOut)}</td><td>${statusHTML}</td></tr>`;
            });
            tableHTML += `</tbody></table>`;
            Swal.fire({ title: 'Riwayat Absensi Lengkap', html: `<div style="max-height: 400px; overflow-y: auto;">${tableHTML}</div>`, confirmButtonText: 'Tutup', confirmButtonColor: '#3b82f6', width: '95%' });
        });
    </script>
</body>

</html>
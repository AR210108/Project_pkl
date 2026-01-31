<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Digital Agency Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0f172a",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                        "card-light": "#111827",
                        "card-dark": "#1f2937",
                        "text-light": "#111827",
                        "text-dark": "#f9fafb",
                        "subtext-light": "#6b7280",
                        "subtext-dark": "#d1d5db",
                        "border-light": "#e5e7eb",
                        "border-dark": "#4b5563",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "1rem",
                    },
                },
            },
        };
    </script>
    <style>
        /* Custom styles for improved appearance */
        .gradient-primary {
            background: linear-gradient(135deg, #000000, #111827);
        }

        .gradient-dark {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }

        .gradient-subtle {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }

        /* Button hover effects - DIUBAH */
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        /* Menghapus efek hover pada tombol OWNERS */
        .btn-no-hover {
            transition: none !important;
            background-color: white !important;
            color: black !important;
        }

        .btn-no-hover:hover {
            background-color: white !important;
            color: black !important;
            transform: none !important;
        }

        .btn-no-hover:before {
            display: none !important;
        }

        /* Card hover effects */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }

        /* Chart bar animation */
        .chart-bar {
            transition: height 0.5s ease-in-out;
        }

        /* Modal Styles - Simplified */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 0;
            border: none;
            width: 90%;
            max-width: 500px;
            /* Diperkecil karena konten lebih sederhana */
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: modalopen 0.3s;
        }

        @keyframes modalopen {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            padding: 8px 16px;
        }

        .close-modal:hover,
        .close-modal:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Loading animation */
        .loading-dots {
            display: inline-block;
        }

        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {

            0%,
            20% {
                content: '';
            }

            40% {
                content: '.';
            }

            60% {
                content: '..';
            }

            80%,
            100% {
                content: '...';
            }
        }

        /* Fade in animation for loaded content */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Calendar styles - DIPERBAIKI */
        .calendar-day {
            transition: all 0.2s ease;
            position: relative;
            color: #000000; /* Teks hitam untuk hari-hari */
        }

        .calendar-day.has-event {
            cursor: pointer;
        }

        .calendar-day.has-event:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .calendar-day.highlighted {
            background-color: rgba(59, 130, 246, 0.2);
            font-weight: 600;
            color: #000000; /* Teks hitam untuk tanggal yang dihighlight */
        }

        .calendar-day.selected {
            background-color: rgba(59, 130, 246, 0.3);
            font-weight: 700;
            color: #000000; /* Teks hitam untuk tanggal yang dipilih */
        }

        .event-indicator {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background-color: #3b82f6;
        }

        .announcement-indicator {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background-color: #f59e0b;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="container mx-auto p-4 md:p-8">
        <!-- Include header template -->
        @include('pemilik/template/header')

        <main class="space-y-6 md:space-y-8">
            <section class="gradient-primary rounded-2xl shadow-lg relative overflow-hidden p-6 md:p-8 lg:p-12">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>

                <div class="max-w-4xl mx-auto relative z-10">
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4">HALLO, <span
                            id="ownerName" class="loading-dots">Memuat</span>
                    </h2>
                    <p class="text-sm md:text-base text-white/90 mb-6 md:mb-8">
                        Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau
                        jasanya
                        secara online melalui berbagai layanan digital.
                    </p>
                    <!-- TOMBOL OWNERS TANPA EFEK HOVER -->
                    <a href="/karyawan/absensi"
                        class="btn-no-hover bg-white text-black px-6 py-2 md:px-8 md:py-3 rounded-lg font-semibold shadow-lg inline-block text-sm md:text-base cursor-pointer">
                        OWNERS
                    </a>
                </div>
            </section>

            <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                <!-- Kehadiran Karyawan Card -->
                <div id="attendance-card-trigger"
                    class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4 cursor-pointer">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">groups</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Kehadiran Karyawan</p>
                        <p id="attendancePercentage" class="text-xl md:text-2xl font-bold text-white loading-dots">
                            Memuat</p>
                    </div>
                </div>

                <!-- JUMLAH LAYANAN CARD - DIPERBAIKI -->
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">design_services</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Jumlah Layanan</p>
                        <p id="serviceCount" class="text-xl md:text-2xl font-bold text-white loading-dots">Memuat</p>
                    </div>
                </div>

                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">arrow_downward</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pemasukan</p>
                        <p id="totalIncome" class="text-lg md:text-xl font-bold text-white loading-dots">Memuat</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">arrow_upward</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pengeluaran</p>
                        <p id="totalExpense" class="text-lg md:text-xl font-bold text-white loading-dots">Memuat</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Keuntungan</p>
                        <p id="totalProfit" class="text-lg md:text-xl font-bold text-white loading-dots">Memuat</p>
                    </div>
                </div>
            </section>

            <section class="gradient-subtle p-4 md:p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-black">Grafik Keuangan</h3>
                    <button class="bg-gray-200 p-2 rounded-full text-black hover:bg-gray-300 transition-colors">
                        <span class="material-icons">open_in_new</span>
                    </button>
                </div>

                <!-- Grafik untuk Desktop (Vertikal) -->
                <div class="hidden md:block">
                    <div class="flex items-end h-64 space-x-4">
                        <div
                            class="flex flex-col justify-between h-full text-xs text-gray-600 pr-2 border-r border-gray-300">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div id="chartContainer" class="w-full h-full flex items-end justify-around">
                            <!-- Chart bars will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Grafik untuk Mobile (Sama seperti Desktop, hanya lebih kecil) -->
                <div class="md:hidden overflow-x-auto pb-4">
                    <div class="flex items-end h-48 min-w-max">
                        <div
                            class="flex flex-col justify-between h-full text-xs text-gray-600 pr-2 border-r border-gray-300">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div id="chartContainerMobile" class="w-full h-full flex items-end justify-around px-2">
                            <!-- Chart bars will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Indikator scroll untuk mobile -->
                <div class="md:hidden text-center text-xs text-gray-600 mt-2">
                    <span class="material-icons text-sm">swipe</span> Geser untuk melihat grafik lengkap
                </div>
            </section>


            <!-- Calendar and Meeting Notes Section -->
            <section class="gradient-subtle p-4 md:p-6 rounded-2xl shadow-sm mt-6 md:mt-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Calendar Section - DIPERBAIKI -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg md:text-xl font-bold text-black">Kalender</h3>
                            <div class="flex items-center space-x-2">
                                <button id="prev-month"
                                    class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                                    <span class="material-icons text-sm">chevron_left</span>
                                </button>
                                <span id="current-month" class="text-lg font-medium text-black"></span>
                                <button id="next-month"
                                    class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                                    <span class="material-icons text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-600 mb-2">
                            <div>Min</div>
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                        </div>
                        <div id="calendar-days" class="grid grid-cols-7 gap-1">
                            <!-- Calendar days will be generated by JavaScript -->
                        </div>
                        <div class="flex justify-center mt-4 space-x-4 text-xs">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-1"></div>
                                <span class="text-gray-600">Meeting</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-amber-500 rounded-full mr-1"></div>
                                <span class="text-gray-600">Pengumuman</span>
                            </div>
                        </div>
                    </div>

                    <!-- Meeting Notes Section -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg md:text-xl font-bold text-black">Catatan Meeting</h3>
                            <button id="refresh-notes"
                                class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                                <span class="material-icons text-sm">refresh</span>
                            </button>
                        </div>
                        <div id="meeting-notes-container" class="space-y-3 max-h-96 overflow-y-auto">
                            <div class="text-center py-8 text-gray-500">
                                <span class="material-icons text-4xl">event_note</span>
                                <p class="mt-2">Tidak ada catatan pada tanggal ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Announcements Section -->
            <section class="gradient-subtle p-4 md:p-6 rounded-2xl shadow-sm mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg md:text-xl font-bold text-black">Pengumuman</h3>
                    <button id="refresh-announcements"
                        class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                        <span class="material-icons text-sm">refresh</span>
                    </button>
                </div>
                <div id="announcements-container" class="space-y-3 max-h-96 overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-icons text-4xl">campaign</span>
                        <p class="mt-2">Tidak ada pengumuman</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="mt-8 md:mt-12 gradient-dark text-center py-3 md:py-4 rounded-lg shadow-sm">
            <p class="text-xs md:text-sm text-gray-700">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <!-- Modal Kehadiran Per Divisi -->
    <div id="attendanceModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Kehadiran Per Divisi</h3>
                <div id="modal-body-content">
                    <!-- Konten akan diisi oleh JavaScript -->
                    <p class="text-center text-gray-500">Klik untuk melihat detail</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- VARIABEL GLOBAL ---
            let currentDate = new Date();
            let selectedDate = null;
            let highlightedDates = [];
            let announcementDates = [];

            // --- FUNGSI API UNTUK OWNER ---
            async function ownerApiFetch(endpoint, options = {}) {
                const cacheBuster = `_t=${Date.now()}`;
                const url = `/owner/api${endpoint}${endpoint.includes('?') ? '&' : '?'}${cacheBuster}`;
                const defaultOptions = { headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } };
                const finalOptions = { ...defaultOptions, ...options };

                const response = await fetch(url, finalOptions);
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Server Error');
                }
                return await response.json();
            }

            // --- FUNGSI-FUNGSI LAMA (TIDAK PERLU DIUBANG) ---
            async function fetchOwnerData() { /* ... biarkan seperti semula ... */ }
            async function fetchServiceCount() { /* ... biarkan seperti semula ... */ }
            async function fetchDashboardData() { /* ... biarkan seperti semula ... */ }
            function updateDashboardStats() { /* ... biarkan seperti semula ... */ }
            function formatCurrency(amount) { /* ... biarkan seperti semula ... */ }
            function initializeChart() { /* ... biarkan seperti semula ... */ }

            // --- MODAL LOGIC (TIDAK PERLU DIUBANG) ---
            const attendanceModal = document.getElementById('attendanceModal');
            // ... (biarkan semua kode modal seperti semula) ...

            // --- FUNGSI KALENDER & PEMANGGIL DATA BARU - DIPERBAIKI ---
            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                
                const monthElement = document.getElementById('current-month');
                const calendarDays = document.getElementById('calendar-days');
                if (!monthElement || !calendarDays) {
                    console.error("Elemen kalender tidak ditemukan!");
                    return;
                }

                monthElement.textContent = `${monthNames[month]} ${year}`;
                calendarDays.innerHTML = '';
                
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                // Tambahkan hari kosong di awal bulan
                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    calendarDays.appendChild(emptyDay);
                }
                
                // Tambahkan hari-hari dalam bulan
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day p-2 text-center rounded hover:bg-gray-100 cursor-pointer'; // Tambahkan style hover dan cursor
                    dayElement.textContent = day;
                    
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    
                    // PERUBAHAN PENTING: Buat semua tanggal bisa diklik
                    dayElement.addEventListener('click', () => selectDate(dateStr));
                    
                    // Cek apakah tanggal ini memiliki event
                    const hasMeeting = highlightedDates.includes(dateStr);
                    const hasAnnouncement = announcementDates.includes(dateStr);

                    if (hasMeeting || hasAnnouncement) {
                        dayElement.classList.add('has-event', 'font-bold', 'bg-blue-50'); // Tambahkan visual cue
                        
                        if (hasMeeting) {
                            const indicator = document.createElement('div');
                            indicator.className = 'event-indicator';
                            dayElement.appendChild(indicator);
                        }
                        
                        if (hasAnnouncement) {
                            const indicator = document.createElement('div');
                            indicator.className = 'announcement-indicator';
                            if (hasMeeting) indicator.style.left = '60%';
                            dayElement.appendChild(indicator);
                        }
                    }
                    
                    // Tandai tanggal yang dipilih
                    if (selectedDate === dateStr) {
                        dayElement.classList.add('selected', 'bg-blue-200', 'font-bold');
                    }
                    
                    calendarDays.appendChild(dayElement);
                }
            }

            function selectDate(dateStr) {
                console.log('Tanggal dipilih:', dateStr); // Tambahkan log untuk debugging
                selectedDate = dateStr;
                renderCalendar(); // Render ulang untuk menandai tanggal yang dipilih
                loadOwnerMeetingNotes(dateStr);
            }

            async function loadOwnerHighlightedDates() {
                try {
                    const dates = await ownerApiFetch('/meeting-notes-dates');
                    highlightedDates = dates.map(date => new Date(date).toISOString().split('T')[0]);
                    console.log('Tanggal meeting berhasil dimuat:', highlightedDates);
                    renderCalendar();
                } catch (error) { console.error('Gagal load tanggal meeting:', error); }
            }

            async function loadOwnerAnnouncementDates() {
                try {
                    const dates = await ownerApiFetch('/announcements-dates');
                    announcementDates = dates.map(date => new Date(date).toISOString().split('T')[0]);
                    console.log('Tanggal pengumuman berhasil dimuat:', announcementDates);
                    renderCalendar();
                } catch (error) { console.error('Gagal load tanggal pengumuman:', error); }
            }

            async function loadOwnerMeetingNotes(date) {
                const container = document.getElementById('meeting-notes-container');
                container.innerHTML = '<p class="text-center text-gray-500">Memuat...</p>';
                try {
                    const response = await ownerApiFetch(`/meeting-notes?date=${encodeURIComponent(date)}`);
                    const notes = response.data || [];

                    if (!Array.isArray(notes) || notes.length === 0) {
                        container.innerHTML = `<div class="text-center py-8 text-gray-500"><span class="material-icons text-4xl">event_note</span><p class="mt-2">Tidak ada catatan pada tanggal ini</p></div>`;
                        return;
                    }

                    container.innerHTML = notes.map(note => `
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h4 class="font-semibold text-black mb-2">${note.topik || 'Tanpa Topik'}</h4>
                        <div class="text-sm text-gray-600 space-y-2">
                            <div><span class="font-medium">Hasil Diskusi:</span><p class="mt-1">${note.hasil_diskusi || 'Tidak ada hasil diskusi'}</p></div>
                            <div><span class="font-medium">Keputusan:</span><p class="mt-1">${note.keputusan || 'Tidak ada keputusan'}</p></div>
                        </div>
                    </div>
                `).join('');

                } catch (error) {
                    console.error('Error loading meeting notes:', error);
                    container.innerHTML = `<div class="text-center py-8 text-red-500"><span class="material-icons text-4xl">error</span><p class="mt-2">Gagal memuat catatan meeting</p></div>`;
                }
            }

            async function loadOwnerAnnouncements() {
                const container = document.getElementById('announcements-container');
                container.innerHTML = '<p class="text-center text-gray-500">Memuat...</p>';
                try {
                    const response = await ownerApiFetch('/announcements');
                    const announcements = response.data || [];

                    if (!Array.isArray(announcements) || announcements.length === 0) {
                        container.innerHTML = `<div class="text-center py-8 text-gray-500"><span class="material-icons text-4xl">campaign</span><p class="mt-2">Tidak ada pengumuman</p></div>`;
                        return;
                    }

                    container.innerHTML = announcements.map(announcement => `
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-black">${announcement.judul || 'Tanpa Judul'}</h4>
                            <span class="text-xs text-gray-600">${announcement.tanggal_indo || new Date(announcement.created_at).toLocaleDateString('id-ID')}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${announcement.ringkasan || announcement.isi_pesan || 'Tidak ada pesan'}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Oleh: ${announcement.creator || 'System'}</span>
                            ${announcement.lampiran_url ? `<a href="${announcement.lampiran_url}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Lampiran</a>` : ''}
                        </div>
                    </div>
                `).join('');

                } catch (error) {
                    console.error('Error loading announcements:', error);
                    container.innerHTML = `<div class="text-center py-8 text-red-500"><span class="material-icons text-4xl">error</span><p class="mt-2">Gagal memuat pengumuman</p></div>`;
                }
            }

            // --- EVENT LISTENERS BARU ---
            document.getElementById('prev-month')?.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); });
            document.getElementById('next-month')?.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); });
            document.getElementById('refresh-notes')?.addEventListener('click', () => { if (selectedDate) loadOwnerMeetingNotes(selectedDate); });
            document.getElementById('refresh-announcements')?.addEventListener('click', loadOwnerAnnouncements);

            // --- INISIALISASI UTAMA (DIPERBAIKI) ---
            fetchOwnerData();
            fetchServiceCount();
            fetchDashboardData();

            // Inisialisasi fitur baru
            renderCalendar();
            loadOwnerHighlightedDates();
            loadOwnerAnnouncementDates();
            loadOwnerAnnouncements();

            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            selectDate(todayStr);
        });
    </script>
</body>

</html>
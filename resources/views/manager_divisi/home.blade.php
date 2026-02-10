<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda (Home) - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // Biru yang lebih terang dan standar
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
                        DEFAULT: "0.75rem", // 12px
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

        /* Deadline card hover effects */
        .deadline-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .deadline-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Sidebar link styles - Dihapus karena sudah ada di template header */

        /* Button styles */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        /* Custom styles untuk transisi - Dihapus karena sudah ada di template header */

        /* Animasi hamburger - Dihapus karena sudah ada di template header */

        /* Style untuk efek hover yang lebih menonjol - Dihapus karena sudah ada di template header */

        /* Gaya untuk indikator aktif/hover - Dihapus karena sudah ada di template header */

        /* Memastikan sidebar tetap di posisinya saat scroll - Dihapus karena sudah ada di template header */

        /* Menyesuaikan konten utama agar tidak tertutup sidebar - Dihapus karena sudah ada di template header */

        /* Scrollbar kustom untuk sidebar - Dihapus karena sudah ada di template header */

        /* Mobile card adjustments */
        @media (max-width: 639px) {
            .stat-card {
                padding: 0.75rem !important;
            }

            .stat-card .icon-container {
                width: 2rem !important;
                height: 2rem !important;
            }

            .stat-card .material-icons-outlined {
                font-size: 1.25rem !important;
            }

            .stat-card .value-text {
                font-size: 0.875rem !important;
                line-height: 1.2 !important;
            }

            .stat-card .label-text {
                font-size: 0.625rem !important;
                line-height: 1 !important;
            }

            .stat-card .mr-3 {
                margin-right: 0.5rem !important;
            }

            /* Adjust deadline cards for mobile */
            .deadline-card {
                padding: 1rem !important;
            }

            .deadline-card .image-placeholder {
                height: 6rem !important;
            }
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

        /* Calendar styles - DITAMBAHKAN */
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
    <div class="flex min-h-screen">
        <!-- Tombol Hamburger untuk Mobile - DIHAPUS -->
        <!-- Overlay untuk Mobile - DIHAPUS -->

        <!-- Sidebar - Dipanggil dari template header -->
        @include('manager_divisi/templet/sider')

        <main class="flex-1 flex flex-col bg-background-light main-content">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Beranda</h2>

                <!-- Stat Cards Grid -->
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-6 sm:mb-8">
                    <!-- Grad Tugas Card -->
                    <div
                        class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div
                            class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg mb-2 flex items-center justify-center">
                            <span class="material-icons-outlined text-primary text-sm sm:text-base">task_alt</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Belum Dikerjakan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">3</p>
                        </div>
                    </div>

                    <!-- Tugas Diteruskan Card -->
                    <div
                        class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div
                            class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg mb-2 flex items-center justify-center">
                            <span
                                class="material-icons-outlined text-yellow-500 text-sm sm:text-base">assignment_turned_in</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Tugas Dikerjakan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">2</p>
                        </div>
                    </div>

                    <!-- Tugas Selesai Card -->
                    <div
                        class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div
                            class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg mb-2 flex items-center justify-center">
                            <span
                                class="material-icons-outlined text-green-500 text-sm sm:text-base">check_circle</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Tugas Selesai</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">6</p>
                        </div>
                    </div>

                    <!-- Total Tugas Card -->
                    <div
                        class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div
                            class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg mb-2 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-500 text-sm sm:text-base">summarize</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Total Tugas</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">10</p>
                        </div>
                    </div>
                </div>

                <!-- Deadline Terdekat Section - Card Layout -->
                <div class="bg-card-light rounded-xl p-3 sm:p-8 border border-border-light shadow-card">
                    <h2 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6">Deadline Terdekat</h2>
                    <div id="deadlineGrid" class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <!-- Deadline cards will be populated by JavaScript -->
                    </div>

                    <!-- Pagination for Deadline -->
                    <div id="deadlinePaginationContainer" class="desktop-pagination">
                        <button id="deadlinePrevPage" class="desktop-nav-btn">
                            <span class="material-icons-outlined text-sm">chevron_left</span>
                        </button>
                        <div id="deadlinePageNumbers" class="flex gap-1">
                            <!-- Page numbers will be generated by JavaScript -->
                        </div>
                        <button id="deadlineNextPage" class="desktop-nav-btn">
                            <span class="material-icons-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar and Meeting Notes Section -->
            <section class="bg-card-light rounded-xl p-3 sm:p-8 border border-border-light shadow-card mt-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Calendar Section - DIPERBAIKI -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-black">Kalender</h3>
                            <div class="flex items-center space-x-2">
                                <button id="prev-month"
                                    class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <span id="current-month" class="text-lg font-medium text-black"></span>
                                <button id="next-month"
                                    class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                                    <span class="material-icons-outlined text-sm">chevron_right</span>
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

                    <!-- Meeting Notes Section - DIPERBAIKI -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-black">Catatan Meeting</h3>
                            <button id="refresh-notes"
                                class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                                <span class="material-icons-outlined text-sm">refresh</span>
                            </button>
                        </div>
                        <div id="meeting-notes-container" class="space-y-3 max-h-96 overflow-y-auto">
                            <div class="text-center py-8 text-gray-500">
                                <span class="material-icons-outlined text-4xl">event_note</span>
                                <p class="mt-2">Tidak ada catatan pada tanggal ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Announcements Section -->
            <section class="bg-card-light rounded-xl p-3 sm:p-8 border border-border-light shadow-card mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-black">Pengumuman</h3>
                    <button id="refresh-announcements"
                        class="p-2 rounded-full bg-gray-100 text-black hover:bg-gray-200 transition-colors">
                        <span class="material-icons-outlined text-sm">refresh</span>
                    </button>
                </div>
                <div id="announcements-container" class="space-y-3 max-h-96 overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-icons-outlined text-4xl">campaign</span>
                        <p class="mt-2">Tidak ada pengumuman</p>
                    </div>
                </div>
            </section>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <script>
        // Sample data for deadline cards
        const deadlineData = [
            { title: "Malaria Pendidikan", daysLeft: 2, progress: 80, status: "TERLAMBAT", statusColor: "red" },
            { title: "Vaksin Pendidikan", daysLeft: 3, progress: 60, status: "DIKERJAKAN", statusColor: "yellow" },
            { title: "Laporan Tahunan", daysLeft: 5, progress: 40, status: "DIKERJAKAN", statusColor: "blue" },
            { title: "Malaria Pendidikan", daysLeft: 7, progress: 25, status: "DIKERJAKAN", statusColor: "green" },
            { title: "Evaluasi Program", daysLeft: 10, progress: 15, status: "DIKERJAKAN", statusColor: "blue" },
            { title: "Penelitian Baru", daysLeft: 12, progress: 5, status: "RENCANA", statusColor: "gray" },
            { title: "Audit Internal", daysLeft: 14, progress: 0, status: "RENCANA", statusColor: "gray" },
            { title: "Pengembangan Kurikulum", daysLeft: 20, progress: 0, status: "RENCANA", statusColor: "gray" }
        ];

        // Pagination variables for deadline
        const itemsPerPage = 4;
        let currentPage = 1;
        const totalPages = Math.ceil(deadlineData.length / itemsPerPage);

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize pagination for deadline
            initializeDeadlinePagination();
        });

        // Function to initialize pagination for deadline
        function initializeDeadlinePagination() {
            renderDeadlineCards(currentPage);
            renderPaginationButtons();
        }

        // Function to render deadline cards for a specific page
        function renderDeadlineCards(page) {
            const grid = document.getElementById('deadlineGrid');
            grid.innerHTML = '';

            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, deadlineData.length);

            for (let i = startIndex; i < endIndex; i++) {
                const deadline = deadlineData[i];
                const card = document.createElement('div');
                card.className = 'deadline-card border border-border-light p-3 sm:p-4 rounded-lg flex flex-col';

                // Determine button color based on status
                let buttonClass = '';
                if (deadline.statusColor === 'red') {
                    buttonClass = 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/70';
                } else if (deadline.statusColor === 'yellow') {
                    buttonClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 hover:bg-yellow-200 dark:hover:bg-yellow-900/70';
                } else if (deadline.statusColor === 'blue') {
                    buttonClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/70';
                } else if (deadline.statusColor === 'green') {
                    buttonClass = 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/70';
                } else {
                    buttonClass = 'bg-gray-100 text-gray-700 dark:bg-gray-900/50 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-900/70';
                }

                card.innerHTML = `
                    <div class="image-placeholder bg-gray-200 dark:bg-gray-600 h-24 sm:h-32 rounded-md mb-3 sm:mb-4 flex items-center justify-center">
                        <span class="material-icons-outlined text-gray-400 dark:text-gray-500 text-sm sm:text-base">image</span>
                    </div>
                    <h3 class="font-semibold text-sm mb-1">${deadline.title}</h3>
                    <p class="text-xs text-text-muted-light mb-3">SISA WAKTU ${deadline.daysLeft} HARI</p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2">
                        <div class="bg-${deadline.statusColor}-500 h-1.5 rounded-full" style="width: ${deadline.progress}%"></div>
                    </div>
                    <button class="mt-auto w-full text-center py-2 text-xs font-semibold ${buttonClass} rounded-md transition-colors">${deadline.status}</button>
                `;
                grid.appendChild(card);
            }
        }

        // Function to render pagination buttons
        function renderPaginationButtons() {
            const pageNumbersContainer = document.getElementById('deadlinePageNumbers');
            const prevButton = document.getElementById('deadlinePrevPage');
            const nextButton = document.getElementById('deadlineNextPage');

            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';

            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${i === currentPage ? 'active' : ''
                    }`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }

            // Update navigation buttons
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;

            // Add event listeners for navigation buttons
            prevButton.onclick = () => {
                if (currentPage > 1) goToPage(currentPage - 1);
            };

            nextButton.onclick = () => {
                if (currentPage < totalPages) goToPage(currentPage + 1);
            };
        }

        // Function to go to a specific page
        function goToPage(page) {
            currentPage = page;
            renderDeadlineCards(page);
            renderPaginationButtons();
        }
        // --- VARIABEL GLOBAL UNTUK KALENDER ---
        let managerCurrentDate = new Date();
        let managerSelectedDate = null;
        let managerHighlightedDates = [];
        let managerAnnouncementDates = [];

        // --- FUNGSI API UNTUK MANAGER DIVISI ---
        async function managerApiFetch(endpoint, options = {}) {
            const cacheBuster = `_t=${Date.now()}`;
            const url = `/manager-divisi/api${endpoint}${endpoint.includes('?') ? '&' : '?'}${cacheBuster}`;

            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                console.error("CSRF token meta tag not found!");
                throw new Error("CSRF token not found");
            }

            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': tokenElement.getAttribute('content')
                }
            };
            const finalOptions = { ...defaultOptions, ...options };

            const response = await fetch(url, finalOptions);
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Server Error');
            }
            return await response.json();
        }

        // --- FUNGSI-FUNGSI KALENDER - DIPERBAIKI ---
        function renderManagerCalendar() {
            const year = managerCurrentDate.getFullYear();
            const month = managerCurrentDate.getMonth();
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            const monthElement = document.getElementById('current-month');
            const calendarDays = document.getElementById('calendar-days');
            if (!monthElement || !calendarDays) return;

            monthElement.textContent = `${monthNames[month]} ${year}`;
            calendarDays.innerHTML = '';

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) calendarDays.appendChild(document.createElement('div'));

            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day p-2 text-center rounded hover:bg-gray-100 cursor-pointer'; // DIPERBAIKI
                dayElement.textContent = day;
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                dayElement.addEventListener('click', () => selectManagerDate(dateStr));

                const hasMeeting = managerHighlightedDates.includes(dateStr);
                const hasAnnouncement = managerAnnouncementDates.includes(dateStr);

                if (hasMeeting || hasAnnouncement) {
                    dayElement.classList.add('has-event', 'font-bold', 'bg-blue-50'); // DIPERBAIKI
                    if (hasMeeting) {
                        const indicator = document.createElement('div'); indicator.className = 'event-indicator'; dayElement.appendChild(indicator);
                    }
                    if (hasAnnouncement) {
                        const indicator = document.createElement('div'); indicator.className = 'announcement-indicator';
                        if (hasMeeting) indicator.style.left = '60%';
                        dayElement.appendChild(indicator);
                    }
                }

                if (managerSelectedDate === dateStr) {
                    dayElement.classList.add('selected', 'bg-blue-200', 'font-bold'); // DIPERBAIKI
                }

                calendarDays.appendChild(dayElement);
            }
        }

        function selectManagerDate(dateStr) {
            managerSelectedDate = dateStr;
            renderManagerCalendar();
            loadManagerMeetingNotes(dateStr);
        }

        // --- FUNGSI PEMANGGIL DATA ---
        async function loadManagerHighlightedDates() {
            try {
                const dates = await managerApiFetch('/meeting-notes-dates');
                managerHighlightedDates = dates.map(date => new Date(date).toISOString().split('T')[0]);
                renderManagerCalendar();
            } catch (error) { console.error('Gagal load tanggal meeting:', error); }
        }

        async function loadManagerAnnouncementDates() {
            try {
                const dates = await managerApiFetch('/announcements-dates');
                managerAnnouncementDates = dates.map(date => new Date(date).toISOString().split('T')[0]);
                renderManagerCalendar();
            } catch (error) { console.error('Gagal load tanggal pengumuman:', error); }
        }

        async function loadManagerMeetingNotes(date) {
            const container = document.getElementById('meeting-notes-container');
            container.innerHTML = '<p class="text-center text-gray-500">Memuat...</p>';
            try {
                const response = await managerApiFetch(`/meeting-notes?date=${encodeURIComponent(date)}`);
                const notes = response.data || [];

                if (!Array.isArray(notes) || notes.length === 0) {
                    container.innerHTML = `<div class="text-center py-8 text-gray-500"><span class="material-icons-outlined text-4xl">event_note</span><p class="mt-2">Tidak ada catatan pada tanggal ini</p></div>`; // DIPERBAIKI
                    return;
                }

                container.innerHTML = notes.map(note => `
            <div class="bg-gray-100 p-4 rounded-lg"> <!-- DIPERBAIKI -->
                <h4 class="font-semibold text-black mb-2">${note.topik || 'Tanpa Topik'}</h4> <!-- DIPERBAIKI -->
                <div class="text-sm text-gray-600 space-y-2"> <!-- DIPERBAIKI -->
                    <div><span class="font-medium">Hasil Diskusi:</span><p class="mt-1">${note.hasil_diskusi || 'Tidak ada hasil diskusi'}</p></div>
                    <div><span class="font-medium">Keputusan:</span><p class="mt-1">${note.keputusan || 'Tidak ada keputusan'}</p></div>
                </div>
            </div>
        `).join('');

            } catch (error) {
                console.error('Error loading meeting notes:', error);
                container.innerHTML = `<div class="text-center py-8 text-red-500"><span class="material-icons-outlined text-4xl">error</span><p class="mt-2">Gagal memuat catatan meeting</p></div>`; // DIPERBAIKI
            }
        }

        async function loadManagerAnnouncements() {
            const container = document.getElementById('announcements-container');
            container.innerHTML = '<p class="text-center text-gray-500">Memuat...</p>';
            try {
                const response = await managerApiFetch('/announcements');
                const announcements = response.data || [];

                if (!Array.isArray(announcements) || announcements.length === 0) {
                    container.innerHTML = `<div class="text-center py-8 text-gray-500"><span class="material-icons-outlined text-4xl">campaign</span><p class="mt-2">Tidak ada pengumuman</p></div>`; // DIPERBAIKI
                    return;
                }

                container.innerHTML = announcements.map(announcement => `
            <div class="bg-gray-100 p-4 rounded-lg"> <!-- DIPERBAIKI -->
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-black">${announcement.judul || 'Tanpa Judul'}</h4> <!-- DIPERBAIKI -->
                    <span class="text-xs text-gray-600">${announcement.tanggal_indo || new Date(announcement.created_at).toLocaleDateString('id-ID')}</span> <!-- DIPERBAIKI -->
                </div>
                <p class="text-sm text-gray-600 mb-2">${announcement.ringkasan || announcement.isi_pesan || 'Tidak ada pesan'}</p> <!-- DIPERBAIKI -->
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-600">Oleh: ${announcement.creator || 'System'}</span> <!-- DIPERBAIKI -->
                    ${announcement.lampiran_url ? `<a href="${announcement.lampiran_url}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Lampiran</a>` : ''} <!-- DIPERBAIKI -->
                </div>
            </div>
        `).join('');

            } catch (error) {
                console.error('Error loading announcements:', error);
                container.innerHTML = `<div class="text-center py-8 text-red-500"><span class="material-icons-outlined text-4xl">error</span><p class="mt-2">Gagal memuat pengumuman</p></div>`; // DIPERBAIKI
            }
        }

        // --- EVENT LISTENERS ---
        document.getElementById('prev-month')?.addEventListener('click', () => { managerCurrentDate.setMonth(managerCurrentDate.getMonth() - 1); renderManagerCalendar(); });
        document.getElementById('next-month')?.addEventListener('click', () => { managerCurrentDate.setMonth(managerCurrentDate.getMonth() + 1); renderManagerCalendar(); });
        document.getElementById('refresh-notes')?.addEventListener('click', () => { if (managerSelectedDate) loadManagerMeetingNotes(managerSelectedDate); });
        document.getElementById('refresh-announcements')?.addEventListener('click', loadManagerAnnouncements);

        // --- INISIALISASI FITUR BARU (DIPANGGIL SAAT DOM LOADED) ---
        document.addEventListener('DOMContentLoaded', function () {
            renderManagerCalendar();
            loadManagerHighlightedDates();
            loadManagerAnnouncementDates();
            loadManagerAnnouncements();

            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            selectManagerDate(todayStr);
        });
    </script>
</body>

</html>

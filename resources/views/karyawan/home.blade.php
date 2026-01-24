<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Employee Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#f3f4f6",
                        "background-dark": "#111827",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
        .calendar-day {
            transition: all 0.2s ease;
            position: relative;
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
        }
        .calendar-day.selected {
            background-color: rgba(59, 130, 246, 0.3);
            font-weight: 700;
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

<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="flex flex-col min-h-screen p-4 sm:p-6 lg:p-8">
        @include('karyawan.templet.header')

        <main class="flex-grow my-8">
            <section class="bg-white dark:bg-gray-800 rounded-lg p-8 sm:p-12 lg:p-16 shadow-sm">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-2">HALLO, 
                        <span id="employee-name" class="text-primary">{{ Auth::user()->name ?? 'Karyawan' }}</span>
                    </h2>
                    
                    <!-- Division display right below the name -->
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                            <span class="material-symbols-outlined text-base mr-1">business</span>
                            Divisi {{ $user_divisi ?? 'Tidak Diketahui' }}
                        </span>
                    </div>
                    
                    <!-- Role-based welcome message -->
                    @if($user_role === 'general_manager')
                        <p class="text-gray-600 dark:text-gray-400 mb-8">
                            Selamat datang di Dashboard General Manager. Kelola tim dan pantau kinerja perusahaan dari sini.
                        </p>
                    @elseif($user_role === 'manager')
                        <p class="text-gray-600 dark:text-gray-400 mb-8">
                            Selamat datang di Dashboard Manajer. Pantau tim divisi {{ $user_divisi }} dan kelola tugas mereka.
                        </p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 mb-8">
                            Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau jasanya
                            secara online melalui berbagai layanan digital.
                        </p>
                    @endif
                    
                    <!-- Role-based action buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="/karyawan/absensi"
                            class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-transform transform hover:scale-105 shadow-lg inline-block">
                            Absen Karyawan
                        </a>
                        
                        @if($user_role === 'general_manager')
                            <a href="/pegawai"
                                class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-transform transform hover:scale-105 shadow-lg inline-block">
                                Kelola Karyawan
                            </a>
                        @endif
                        
                        @if($user_role === 'manager' || $user_role === 'general_manager')
                            <a href="/tugas"
                                class="bg-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-transform transform hover:scale-105 shadow-lg inline-block">
                                Kelola Tugas
                            </a>
                        @endif
                    </div>
                </div>
            </section>
            
            <!-- Updated cards section with new metrics -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-blue-100 dark:bg-blue-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Status Absensi</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="attendance-status">Memuat...</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-purple-100 dark:bg-purple-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-purple-500">assignment</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Tugas</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="tugas-count">{{ $tugas_count ?? 0 }}</p>
                    </div>
                </div>
                
                <!-- New Card: Gaji Tahun Ini -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-green-100 dark:bg-green-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-green-500">payments</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gaji Tahun Ini</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp 60.000.000</p>
                    </div>
                </div>
                
                <!-- New Card: Total Hadir -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-blue-100 dark:bg-blue-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-blue-500">check_circle</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Hadir</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">210 hari</p>
                    </div>
                </div>
                
                <!-- New Card: Total Terlambat -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-red-100 dark:bg-red-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-red-500">schedule</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Terlambat</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">8 kali</p>
                    </div>
                </div>
                
                <!-- New Card: Total Izin -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-yellow-100 dark:bg-yellow-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-yellow-500">event_available</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Izin</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">5 hari</p>
                    </div>
                </div>
                
                <!-- New Card: Total Sakit -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-orange-100 dark:bg-orange-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-orange-500">sick</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Sakit</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">3 hari</p>
                    </div>
                </div>
                
                <!-- New Card: Total Absen -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gray-500">event_busy</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Absen</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">2 hari</p>
                    </div>
                </div>
            </section>
            
            <!-- Calendar and Meeting Notes Section -->
            <section class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm mt-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Calendar Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Kalender</h3>
                            <div class="flex items-center space-x-2">
                                <button id="prev-month" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="material-symbols-outlined">chevron_left</span>
                                </button>
                                <span id="current-month" class="text-lg font-medium text-gray-900 dark:text-white"></span>
                                <button id="next-month" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span class="material-symbols-outlined">chevron_right</span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                                <span class="text-gray-600 dark:text-gray-400">Meeting</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-amber-500 rounded-full mr-1"></div>
                                <span class="text-gray-600 dark:text-gray-400">Pengumuman</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Meeting Notes Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Catatan Meeting</h3>
                            <button id="refresh-notes" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="material-symbols-outlined">refresh</span>
                            </button>
                        </div>
                        <div id="meeting-notes-container" class="space-y-3 max-h-96 overflow-y-auto">
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <span class="material-symbols-outlined text-4xl">event_note</span>
                                <p class="mt-2">Tidak ada catatan pada tanggal ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Announcements Section -->
            <section class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Pengumuman</h3>
                    <button id="refresh-announcements" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined">refresh</span>
                    </button>
                </div>
                <div id="announcements-container" class="space-y-3 max-h-96 overflow-y-auto">
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-4xl">campaign</span>
                        <p class="mt-2">Tidak ada pengumuman</p>
                    </div>
                </div>
            </section>
            
            <!-- Role-specific additional cards -->
            @if($user_role === 'general_manager')
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div
                            class="bg-indigo-100 dark:bg-indigo-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-indigo-500">groups</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Karyawan</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role_based_data['totalKaryawan'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div
                            class="bg-teal-100 dark:bg-teal-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-teal-500">business</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Divisi</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role_based_data['totalDivisi'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div
                            class="bg-orange-100 dark:bg-orange-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-orange-500">pending_actions</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu Persetujuan</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role_based_data['pendingApprovals'] ?? 0 }}</p>
                        </div>
                    </div>
                </section>
            @elseif($user_role === 'manager')
                <section class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div
                            class="bg-indigo-100 dark:bg-indigo-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-indigo-500">groups</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Anggota Tim</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role_based_data['teamMembers'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div
                            class="bg-orange-100 dark:bg-orange-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-orange-500">pending_actions</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tim Menunggu Persetujuan</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role_based_data['teamPendingApprovals'] ?? 0 }}</p>
                        </div>
                    </div>
                </section>
            @endif
        </main>
        <footer
            class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center text-gray-600 dark:text-gray-400 text-sm shadow-sm">
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu) {
                    mobileMenu.classList.toggle('hidden');
                }
            });
        }

        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');

            if (mobileMenu && mobileMenuButton && !mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        function formatAttendanceStatus(status) {
            if (status === 'Belum Absen') {
                return 'Belum Absen';
            } else if (status === 'Tepat Waktu') {
                return '<span class="text-green-500">Tepat Waktu</span>';
            } else if (status === 'Terlambat') {
                return '<span class="text-red-500">Terlambat</span>';
            } else if (status === 'Sakit' || status === 'Izin' || status === 'Dinas Luar') {
                return '<span class="text-yellow-500">' + status + '</span>';
            } else {
                return status;
            }
        }

        async function apiFetch(endpoint, options = {}) {
            const cacheBuster = `_t=${Date.now()}`;
            const url = `/api/karyawan${endpoint}${endpoint.includes('?') ? '&' : '?'}${cacheBuster}`;
            
            const defaultOptions = { 
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': window.csrfToken 
                } 
            };
            const finalOptions = { ...defaultOptions, ...options };
            
            console.log('=== API FETCH ===');
            console.log('URL:', url);
            console.log('Options:', finalOptions);
            
            const response = await fetch(url, finalOptions);
            
            console.log('Response status:', response.status);
            console.log('Response headers:', [...response.headers.entries()]);
            
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Invalid JSON response');
            }
            
            console.log('Parsed data:', data);
            
            if (response.status === 419) throw new Error('CSRF token mismatch. Silakan muat ulang halaman.');
            if (!response.ok) { 
                console.error('API Error:', data);
                throw new Error(data.message || data.error || 'Something went wrong'); 
            }
            
            return data;
        }

        async function fetchDashboardData() {
            try {
                const data = await apiFetch('/dashboard-data');
                
                if (data.attendance_status) {
                    const statusElement = document.getElementById('attendance-status');
                    if(statusElement) statusElement.innerHTML = formatAttendanceStatus(data.attendance_status);
                }
                
                if (data.tugas_count !== undefined) {
                    const tugasElement = document.getElementById('tugas-count');
                    if(tugasElement) tugasElement.textContent = data.tugas_count;
                }
                
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
            }
        }

        // Calendar functionality
        let currentDate = new Date();
        let selectedDate = null;
        let highlightedDates = [];
        let announcementDates = [];

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Update month display
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;
            
            // Clear calendar days
            const calendarDays = document.getElementById('calendar-days');
            calendarDays.innerHTML = '';
            
            // Get first day of month and number of days in month
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Add empty cells for days before month starts
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                calendarDays.appendChild(emptyDay);
            }
            
            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day p-2 text-center rounded';
                dayElement.textContent = day;
                
                // Format date as YYYY-MM-DD for comparison
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Check if this date has events
                const hasMeeting = highlightedDates.includes(dateStr);
                const hasAnnouncement = announcementDates.includes(dateStr);
                
                if (hasMeeting || hasAnnouncement) {
                    dayElement.classList.add('has-event');
                    
                    // Add event indicators
                    if (hasMeeting) {
                        const indicator = document.createElement('div');
                        indicator.className = 'event-indicator';
                        dayElement.appendChild(indicator);
                    }
                    
                    if (hasAnnouncement) {
                        const indicator = document.createElement('div');
                        indicator.className = 'announcement-indicator';
                        // Adjust position if there's already a meeting indicator
                        if (hasMeeting) {
                            indicator.style.left = '60%';
                        }
                        dayElement.appendChild(indicator);
                    }
                    
                    // Add click event
                    dayElement.addEventListener('click', function() {
                        selectDate(dateStr);
                    });
                }
                
                // Check if this is the selected date
                if (selectedDate === dateStr) {
                    dayElement.classList.add('selected');
                }
                
                calendarDays.appendChild(dayElement);
            }
        }

        function selectDate(dateStr) {
            console.log('=== SELECT DATE ===');
            console.log('Selected date:', dateStr);
            
            selectedDate = dateStr;
            renderCalendar();
            loadMeetingNotes(dateStr);
        }

        async function loadHighlightedDates() {
            try {
                console.log('=== LOADING HIGHLIGHTED DATES ===');
                const dates = await apiFetch('/meeting-notes-dates');
                console.log('Received dates:', dates);
                
                // Ensure dates are in YYYY-MM-DD format
                highlightedDates = dates.map(date => {
                    const d = new Date(date);
                    const formatted = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                    console.log(`Date ${date} formatted to ${formatted}`);
                    return formatted;
                });
                
                console.log('Final highlighted dates:', highlightedDates);
                renderCalendar();
            } catch (error) {
                console.error('Error loading highlighted dates:', error);
                highlightedDates = [];
                renderCalendar();
            }
        }

        async function loadAnnouncementDates() {
            try {
                console.log('=== LOADING ANNOUNCEMENT DATES ===');
                const announcements = await apiFetch('/announcements-dates');
                console.log('Received announcement dates:', announcements);
                
                // Ensure dates are in YYYY-MM-DD format
                announcementDates = announcements.map(date => {
                    const d = new Date(date);
                    const formatted = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                    console.log(`Announcement date ${date} formatted to ${formatted}`);
                    return formatted;
                });
                
                console.log('Final announcement dates:', announcementDates);
                renderCalendar();
            } catch (error) {
                console.error('Error loading announcement dates:', error);
                announcementDates = [];
                renderCalendar();
            }
        }

        async function loadMeetingNotes(date) {
            try {
                console.log('=== LOADING MEETING NOTES ===');
                console.log('Date parameter:', date);
                
                const notes = await apiFetch(`/meeting-notes?date=${encodeURIComponent(date)}`);
                console.log('Received notes:', notes);
                
                const container = document.getElementById('meeting-notes-container');
                
                if (!notes || notes.length === 0) {
                    console.log('No notes found, displaying empty message');
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-4xl">event_note</span>
                            <p class="mt-2">Tidak ada catatan pada tanggal ini</p>
                            <p class="text-xs mt-1">Tanggal: ${date}</p>
                        </div>
                    `;
                    return;
                }
                
                console.log(`Found ${notes.length} notes, rendering...`);
                container.innerHTML = notes.map(note => `
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">${note.topik || 'Tanpa Topik'}</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <div>
                                <span class="font-medium">Hasil Diskusi:</span>
                                <p class="mt-1">${note.hasil_diskusi || 'Tidak ada hasil diskusi'}</p>
                            </div>
                            <div>
                                <span class="font-medium">Keputusan:</span>
                                <p class="mt-1">${note.keputusan || 'Tidak ada keputusan'}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Error loading meeting notes:', error);
                const container = document.getElementById('meeting-notes-container');
                container.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <span class="material-symbols-outlined text-4xl">error</span>
                        <p class="mt-2">Gagal memuat catatan meeting</p>
                        <p class="text-xs mt-1">${error.message}</p>
                    </div>
                `;
            }
        }

        async function loadAnnouncements() {
            try {
                console.log('=== LOADING ANNOUNCEMENTS ===');
                const announcements = await apiFetch('/announcements');
                console.log('Received announcements:', announcements);
                
                const container = document.getElementById('announcements-container');
                
                if (!announcements || announcements.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-4xl">campaign</span>
                            <p class="mt-2">Tidak ada pengumuman</p>
                        </div>
                    `;
                    return;
                }
                
                container.innerHTML = announcements.map(announcement => `
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white">${announcement.judul || 'Tanpa Judul'}</h4>
                            <span class="text-xs text-gray-500 dark:text-gray-500">
                                ${announcement.tanggal_indo || new Date(announcement.created_at).toLocaleDateString('id-ID')}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            ${announcement.ringkasan || announcement.isi_pesan || 'Tidak ada pesan'}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-500">Oleh: ${announcement.creator || 'System'}</span>
                            ${announcement.lampiran_url ? 
                                `<a href="${announcement.lampiran_url}" target="_blank" class="text-xs text-primary hover:underline">Lihat Lampiran</a>` : 
                                ''}
                        </div>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Error loading announcements:', error);
                const container = document.getElementById('announcements-container');
                container.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <span class="material-symbols-outlined text-4xl">error</span>
                        <p class="mt-2">Gagal memuat pengumuman</p>
                        <p class="text-xs mt-1">${error.message}</p>
                    </div>
                `;
            }
        }
        
        // Calendar navigation
        document.getElementById('prev-month').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
        
        document.getElementById('next-month').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        // Refresh buttons
        document.getElementById('refresh-notes')?.addEventListener('click', function() {
            if (selectedDate) {
                loadMeetingNotes(selectedDate);
            }
        });

        document.getElementById('refresh-announcements')?.addEventListener('click', function() {
            loadAnnouncements();
        });

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('=== INITIALIZATION START ===');
            
            // Load dashboard data
            fetchDashboardData();
            
            // Initialize calendar
            renderCalendar();
            
            // Load data from database
            await loadHighlightedDates();
            await loadAnnouncementDates();
            await loadAnnouncements();
            
            // Select today by default
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
            selectDate(todayStr);
            
            console.log('=== INITIALIZATION COMPLETE ===');
        });
    </script>
</body>
</html>
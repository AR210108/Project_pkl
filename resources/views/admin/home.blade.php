<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet" />
     <link rel="icon" type="image/png" href="{{ asset('logo1.jpeg') }}">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // Blue-500
                        success: "#10b981",
                        warning: "#f59e0b",
                        danger: "#ef4444",
                        "background-light": "#F9FAFB", // Gray-50
                        "background-dark": "#0f172a", // Slate-900
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1e293b", // Slate-800
                        "text-main-light": "#1f2937", // Gray-800
                        "text-main-dark": "#f8fafc", // Slate-50
                        "text-sub-light": "#6b7280", // Gray-500
                        "text-sub-dark": "#94a3b8", // Slate-400
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        'xl': '1rem',
                        '2xl': '1.5rem',
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background-color: #475569;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
            /* Ensure table has minimum width for horizontal scroll */
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

        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
        }

        .tab-nav {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            /* Allow horizontal scroll on mobile */
        }

        .tab-button {
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: #64748b;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tab-button:hover {
            color: #3b82f6;
        }

        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        /* Custom styles for layout */
        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 256px;
            flex-shrink: 0;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
        }

        .calendar-animate {
    transition: transform 0.35s ease, opacity 0.35s ease;
}

.calendar-slide-left {
    transform: translateX(-20px);
    opacity: 0;
}

.calendar-slide-right {
    transform: translateX(20px);
    opacity: 0;
}

.event-animate {
    transition: all 0.3s ease;
}

.event-hidden {
    opacity: 0;
    transform: translateY(8px);
}


        /* Mobile responsive */
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .main-content {
                width: 100%;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .panel-header {
                padding: 0.75rem 1rem;
            }

            .panel-body {
                padding: 1rem;
            }

            .panel-title {
                font-size: 1rem;
            }

            /* Adjust graph for mobile */
            .graph-container {
                height: 200px !important;
            }

            .graph-bar {
                width: 2rem !important;
            }

            .graph-label {
                font-size: 0.65rem !important;
            }

            /* Adjust calendar for mobile */
            .calendar-date {
                padding: 0.25rem !important;
            }

            .calendar-date-text {
                font-size: 0.7rem !important;
            }

            .calendar-date-number {
                font-size: 0.8rem !important;
            }

            /* Adjust table for mobile */
            .data-table th,
            .data-table td {
                padding: 8px 12px;
                font-size: 0.875rem;
            }

            .status-badge {
                padding: 0.2rem 0.5rem;
                font-size: 0.7rem;
            }

            /* Fix for mobile cards - ensure proper 2 column layout */
            .stat-card {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                min-height: 100px !important;
            }

            .stat-card .icon-container {
                width: 2rem !important;
                height: 2rem !important;
                margin-bottom: 0.5rem !important;
                margin-right: 0 !important;
            }

            .stat-card .material-symbols-rounded {
                font-size: 1.25rem !important;
            }

            .stat-card .value-text {
                font-size: 1.125rem !important;
                font-weight: 700 !important;
                line-height: 1.2 !important;
            }

            .stat-card .label-text {
                font-size: 0.7rem !important;
                line-height: 1 !important;
                margin-top: 0.25rem !important;
            }
        }

        @media (max-width: 480px) {

            /* Extra small mobile adjustments */
            .content-wrapper {
                padding: 0.75rem;
            }

            .stat-card {
                min-height: 90px !important;
                padding: 0.75rem !important;
            }

            .stat-card .icon-container {
                width: 1.75rem !important;
                height: 1.75rem !important;
            }

            .stat-card .material-symbols-rounded {
                font-size: 1.125rem !important;
            }

            .stat-card .value-text {
                font-size: 1rem !important;
            }

            .stat-card .label-text {
                font-size: 0.65rem !important;
            }

            .panel-title {
                font-size: 0.9rem;
            }

            .tab-button {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .graph-container {
                height: 180px !important;
            }

            .graph-bar {
                width: 1.5rem !important;
            }

            /* Adjust grid spacing for very small screens */
            .gap-3 {
                gap: 0.5rem !important;
            }

            .gap-6 {
                gap: 0.75rem !important;
            }

            /* Adjust footer for mobile */
            footer {
                padding: 0.75rem 1rem !important;
            }
        }

        /* Fix for desktop cards to maintain horizontal layout */
        @media (min-width: 769px) {
            .stat-card {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                text-align: left !important;
            }

            .stat-card .icon-container {
                margin-right: 1rem !important;
                margin-bottom: 0 !important;
            }

            .stat-card .card-content {
                flex: 1;
            }

        }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    <div class="app-container">
        <!-- Sidebar menggunakan Blade directive -->
        @include('admin/templet/sider')

        <main class="main-content bg-gray-50">
            <div class="content-wrapper max-w-7xl mx-auto space-y-8">
                <header class="mb-8">
                    <h2 class="font-display text-3xl font-bold text-gray-800">Dashboard</h2>
                </header>
                <!-- Modified grid: 2 columns on mobile, 4 columns on desktop -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card">
                        <div class="icon-container bg-blue-100">
                            <span class="material-symbols-rounded text-blue-600">groups</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah Karyawan</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahKaryawan }}</p>
                        </div>
                    </div>
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card">
                        <div class="icon-container bg-green-100">
                            <span class="material-symbols-rounded text-green-600">person_check</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah User</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahUser }}</p>
                        </div>
                    </div>
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card">
                        <div class="icon-container bg-purple-100">
                            <span class="material-symbols-rounded text-purple-600">design_services</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah Layanan</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahLayanan }}</p>
                        </div>
                    </div>
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card">
                        <div class="icon-container bg-yellow-100">
                            <span class="material-symbols-rounded text-yellow-600">handshake</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah surat kerjasama</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">$10.000.000</p>
                        </div>
                    </div>
                </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="panel">
        <div class="panel-header">
            <div class="flex gap-2">
    <button onclick="prevDate()">‹</button>
    <button onclick="nextDate()">›</button>
</div>

<h3 class="panel-title" id="calendarTitle">
    <span class="material-symbols-rounded text-primary">calendar_month</span>
    <!-- diisi JS -->
</h3>
        </div>

        <div class="panel-body">
            <div class="w-full mb-6">
                <div id="calendarDates"
                     class="flex justify-between mb-4 calendar-animate">
                </div>
            </div>

            <div id="calendarEvent"
                 class="event-animate w-full h-12 md:h-16 border-2 border-gray-200 border-dashed
                        rounded-xl flex items-center justify-center text-gray-500 text-xs md:text-sm">
                Pilih tanggal
            </div>
        </div>
    </div>
</div>


                <!-- Tab Navigation for Meeting Notes and Announcements -->
                <div class="tab-nav">
                    <button id="meetingTab" class="tab-button active" onclick="switchTab('meeting')">
                        <span class="material-symbols-rounded align-middle mr-1 md:mr-2">description</span>
                        <span class="hidden sm:inline">Catatan Meeting</span>
                        <span class="sm:hidden">Meeting</span>
                    </button>
                    <button id="announcementTab" class="tab-button" onclick="switchTab('announcement')">
                        <span class="material-symbols-rounded align-middle mr-1 md:mr-2">campaign</span>
                        <span class="hidden sm:inline">Pengumuman Terbaru</span>
                        <span class="sm:hidden">Pengumuman</span>
                    </button>
                </div>

                <!-- Catatan Meeting Panel -->
                <div id="meetingPanel" class="panel mb-8">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-symbols-rounded text-primary">description</span>
                            Catatan Meeting
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Peserta</th>
                                        <th class="text-center">Topik Rapat</th>
                                        <th class="text-center">Hasil Diskusi</th>
                                        <th class="text-center">Keputusan</th>
                                        <th class="text-center">Penugasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($catatanRapat->count() > 0)
                                        @foreach ($catatanRapat as $rapat)
                                            <tr>
                                                <td class="font-medium">{{ $loop->iteration }}</td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($rapat->tanggal)->translatedFormat('d F Y') }}
                                                </td>

                                                <td class="text-center">
                                                    @foreach ($rapat->peserta as $user)
                                                        <span class="block">{{ $user->name }}</span>
                                                    @endforeach
                                                </td>

                                                <td class="text-center">{{ $rapat->topik }}</td>

                                                <td class="text-center">
                                                    {{ Str::limit($rapat->hasil_diskusi, 30) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ Str::limit($rapat->keputusan, 30) }}
                                                </td>

                                                <td class="text-center">
                                                    @foreach ($rapat->penugasan as $user)
                                                        <span class="block">{{ $user->name }}</span>
                                                    @endforeach
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-6 text-gray-500">
                                                Belum ada catatan meeting
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pengumuman Terbaru Panel (Initially Hidden) -->
                <div id="announcementPanel" class="panel mb-8 hidden">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-symbols-rounded text-primary">campaign</span>
                            Pengumuman Terbaru
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="overflow-x-auto min-h-[100px]">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th class="text-center">Isi</th>
                                        <th class="text-center">Kepada</th>
                                        <th class="text-center">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($pengumumanTerbaru->count() > 0)
                                        @foreach ($pengumumanTerbaru as $item)
                                            <tr>
                                                <td class="font-medium">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="font-medium">
                                                    {{ $item->judul }}
                                                </td>


                                                <td>
                                                    {{ \Illuminate\Support\Str::limit($item->isi_pesan, 50) }}
                                                </td>
                                                <td>
                                                    @if ($item->kepada === 'specific')
                                                        @foreach ($item->users as $user)
                                                            <span
                                                                class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                                                {{ $user->name }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        {{ ucfirst($item->kepada) }}
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if ($item->lampiran)
                                                        <a href="{{ asset('storage/' . $item->lampiran) }}"
                                                            class="text-blue-600 hover:underline" target="_blank">
                                                            Lihat
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-8 text-gray-500">
                                                Tidak ada pengumuman terbaru
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white border-t border-gray-200 text-center py-4 px-8 mt-auto sticky bottom-0 z-20">
                <p class="text-sm font-medium text-gray-500">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>
    <script>
        let offset = 0;

        // Simple dark mode toggle logic for demonstration (optional)
        // Check system preference on load
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        // Function to switch between tabs
        window.switchTab = function(tabName) {
            // Get tab buttons and panels
            const meetingTab = document.getElementById('meetingTab');
            const announcementTab = document.getElementById('announcementTab');
            const meetingPanel = document.getElementById('meetingPanel');
            const announcementPanel = document.getElementById('announcementPanel');

            // Hide all panels and remove active class from all tabs
            meetingPanel.classList.add('hidden');
            announcementPanel.classList.add('hidden');
            meetingTab.classList.remove('active');
            announcementTab.classList.remove('active');

            // Show selected panel and add active class to clicked tab
            if (tabName === 'meeting') {
                meetingPanel.classList.remove('hidden');
                meetingTab.classList.add('active');
            } else if (tabName === 'announcement') {
                announcementPanel.classList.remove('hidden');
                announcementTab.classList.add('active');
            }
        }

        const events = @json($events);
        const today = "{{ $today->format('Y-m-d') }}";
        const calendarContainer = document.getElementById('calendarDates');
        const eventBox = document.getElementById('calendarEvent');

        const baseDate = new Date(); // hari ini
        const daysToShow = 5;

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

function renderCalendar(direction = 'right') {
    updateCalendarTitle(); // ⬅️ tambahkan ini
    // trigger animasi keluar
    calendarContainer.classList.add(
        direction === 'right' ? 'calendar-slide-right' : 'calendar-slide-left'
    );

    setTimeout(() => {
        calendarContainer.innerHTML = '';

        for (let i = -2; i <= 2; i++) {
            const d = new Date(baseDate);
            d.setDate(baseDate.getDate() + offset + i);

            const dateStr = formatDate(d);
            const isToday = dateStr === today;

            const div = document.createElement('div');
            div.className = `
                flex flex-col items-center space-y-2 calendar-date cursor-pointer
                ${isToday ? 'bg-primary text-white p-2 rounded-xl scale-110 shadow-lg' : ''}
            `;

            div.innerHTML = `
                <span class="text-xs ${isToday ? 'text-white/80' : 'text-gray-500'}">
                    ${d.toLocaleString('id-ID', { month: 'short' })}
                </span>
                <span class="text-sm font-bold">${d.getDate()}</span>
            `;

            if (events[dateStr]) {
                div.innerHTML += `<span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>`;
            }

            div.onclick = () => showEvents(dateStr);
            calendarContainer.appendChild(div);
        }

        // slide masuk
        calendarContainer.classList.remove(
            'calendar-slide-left',
            'calendar-slide-right'
        );
    }, 200);
}


function showEvents(date) {
    eventBox.classList.add('event-hidden');

    setTimeout(() => {
        if (events[date]) {
            eventBox.innerHTML = events[date]
                .map(e => `• ${e.keputusan}`)
                .join('<br>');
            eventBox.classList.remove('text-gray-500');
        } else {
            eventBox.innerHTML = 'Tidak ada keputusan pada tanggal ini';
            eventBox.classList.add('text-gray-500');
        }

        eventBox.classList.remove('event-hidden');
    }, 200);
}

function nextDate() {
    offset++;
    renderCalendar('right');
}

function prevDate() {
    offset--;
    renderCalendar('left');
}

const calendarTitle = document.getElementById('calendarTitle');

function updateCalendarTitle() {
    const d = new Date(baseDate);
    d.setDate(baseDate.getDate() + offset);

    const monthYear = d.toLocaleDateString('id-ID', {
        month: 'long',
        year: 'numeric'
    });

    calendarTitle.innerHTML = `
        <span class="material-symbols-rounded text-primary">calendar_month</span>
        ${monthYear}
    `;
}

        renderCalendar();
        showEvents(today);
    </script>

</body>

</html>

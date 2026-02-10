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

        ::webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background-color: #475569;
        }

        /* ANIMASI FADE UNTUK CARD */
        .fade-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Animasi untuk setiap card dengan delay berbeda */
        .fade-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .fade-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .fade-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .fade-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        /* Keyframes untuk animasi fade-in */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animasi fade-in untuk panel */
        .fade-panel {
            opacity: 0;
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Animasi untuk calendar dan notes */
        .fade-in-slide {
            opacity: 0;
            transform: translateX(-20px);
            animation: slideFadeIn 0.6s ease forwards;
        }

        .notes-container {
            opacity: 0;
            transform: translateX(20px);
            animation: slideFadeIn 0.6s ease forwards 0.3s;
        }

        @keyframes slideFadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
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

        /* Calendar Styles */
        .calendar-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
            height: 100%;
        }

        .calendar-header {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: white;
            padding: 0.5rem 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .calendar-nav-button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .calendar-nav-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .calendar-nav-button .material-symbols-rounded {
            font-size: 0.875rem;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.125rem;
            padding: 0.25rem 0.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .calendar-weekday {
            text-align: center;
            font-size: 0.625rem;
            font-weight: 600;
            color: #64748b;
            padding: 0.25rem 0;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.125rem;
            padding: 0.25rem 0.5rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            font-size: 0.75rem;
        }

        .calendar-day:hover {
            background: #f1f5f9;
            transform: scale(1.05);
        }

        .calendar-day.today {
            background: #3b82f6;
            color: white;
            font-weight: 600;
        }

        .calendar-day.today:hover {
            background: #2563eb;
        }

        .calendar-day.selected {
            background: #dbeafe;
            color: #1e40af;
            font-weight: 600;
        }

        .calendar-day.selected:hover {
            background: #bfdbfe;
        }

        .calendar-day.has-event::after {
            content: '';
            position: absolute;
            bottom: 0.125rem;
            width: 0.125rem;
            height: 0.125rem;
            background: #ef4444;
            border-radius: 50%;
        }

        .calendar-day.today.has-event::after {
            background: white;
        }

        .calendar-day.selected.has-event::after {
            background: #1e40af;
        }

        /* Notes Container */
        .notes-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
            height: 100%;
            width: 230%;
            display: flex;
            flex-direction: column;
        }

        .notes-header {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: white;
            padding: 0.5rem 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notes-header h3 {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .notes-body {
            flex: 1;
            padding: 0.5rem;
            overflow-y: auto;
        }

        .note-item {
            padding: 0.375rem;
            border-left: 2px solid #3b82f6;
            margin-bottom: 0.375rem;
            background: #f8fafc;
            border-radius: 0 0.25rem 0.25rem 0;
            transition: all 0.2s ease;
        }

        .note-item:hover {
            background: #f1f5f9;
            transform: translateX(2px);
        }

        .note-date {
            font-size: 0.625rem;
            color: #64748b;
            margin-bottom: 0.125rem;
        }

        .note-title {
            font-weight: 600;
            font-size: 0.75rem;
            margin-bottom: 0.125rem;
            color: #1f2937;
        }

        .note-content {
            font-size: 0.6875rem;
            color: #4b5563;
            line-height: 1.2;
        }

        .calendar-notes-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            max-width: 600px;
            margin-left: 0;
            margin-right: auto;
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

            .graph-container {
                height: 200px !important;
            }

            .graph-bar {
                width: 2rem !important;
            }

            .graph-label {
                font-size: 0.65rem !important;
            }

            .calendar-date {
                padding: 0.25rem !important;
            }

            .calendar-date-text {
                font-size: 0.7rem !important;
            }

            .calendar-date-number {
                font-size: 0.8rem !important;
            }

            .data-table th,
            .data-table td {
                padding: 8px 12px;
                font-size: 0.875rem;
            }

            .status-badge {
                padding: 0.2rem 0.5rem;
                font-size: 0.7rem;
            }

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

            .calendar-notes-container {
                grid-template-columns: 1fr !important;
                max-width: 100% !important;
            }

            .calendar-container,
            .notes-container {
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }
        }

        @media (max-width: 480px) {
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

            .gap-3 {
                gap: 0.5rem !important;
            }

            .gap-6 {
                gap: 0.75rem !important;
            }

            footer {
                padding: 0.75rem 1rem !important;
            }
        }

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
        
        .mobile-table-cards {
            display: none;
        }
        
        .mobile-table-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            opacity: 0;
            animation: fadeInUp 0.4s ease forwards;
        }
        
        .mobile-table-card:nth-child(1) { animation-delay: 0.1s; }
        .mobile-table-card:nth-child(2) { animation-delay: 0.15s; }
        .mobile-table-card:nth-child(3) { animation-delay: 0.2s; }
        .mobile-table-card:nth-child(4) { animation-delay: 0.25s; }
        .mobile-table-card:nth-child(5) { animation-delay: 0.3s; }
        
        .mobile-table-card-header {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }
        
        .mobile-table-card-row {
            display: flex;
            margin-bottom: 0.5rem;
        }
        
        .mobile-table-card-label {
            font-weight: 600;
            width: 40%;
            color: #64748b;
        }
        
        .mobile-table-card-value {
            width: 60%;
            color: #1f2937;
        }
        
        @media (max-width: 768px) {
            .data-table-container {
                display: none;
            }
            
            .mobile-table-cards {
                display: block;
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
                <header class="mb-8 fade-panel">
                    <h2 class="font-display text-3xl font-bold text-gray-800">Dashboard</h2>
                </header>
                
                <!-- Stat Cards dengan animasi fade -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card fade-card">
                        <div class="icon-container bg-blue-100">
                            <span class="material-symbols-rounded text-blue-600">groups</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah Karyawan</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahKaryawan }}
                            </p>
                        </div>
                    </div>
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card fade-card">
                        <div class="icon-container bg-green-100">
                            <span class="material-symbols-rounded text-green-600">person_check</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah Perusahaan</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahPerusahaan }}
                            </p>
                        </div>
                    </div>
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card fade-card">
                        <div class="icon-container bg-purple-100">
                            <span class="material-symbols-rounded text-purple-600">design_services</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah Layanan</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahLayanan }}
                            </p>
                        </div>
                    </div>
                    <div class="card bg-white p-3 sm:p-4 md:p-6 rounded-xl shadow-md stat-card fade-card">
                        <div class="icon-container bg-yellow-100">
                            <span class="material-symbols-rounded text-yellow-600">handshake</span>
                        </div>
                        <div class="card-content">
                            <p class="text-xs sm:text-sm text-gray-500 label-text">Jumlah Data Project</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 value-text">
                                {{ $jumlahProject }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Calendar and Notes dengan animasi slide -->
                <div class="calendar-notes-container">
                    <div class="calendar-container fade-in-slide">
                        <div class="calendar-header">
                            <button class="calendar-nav-button" onclick="prevMonth()">
                                <span class="material-symbols-rounded">chevron_left</span>
                            </button>
                            <h3 id="calendarTitle" class="text-sm font-semibold">
                                <!-- diisi JS -->
                            </h3>
                            <button class="calendar-nav-button" onclick="nextMonth()">
                                <span class="material-symbols-rounded">chevron_right</span>
                            </button>
                        </div>
                        <div class="calendar-weekdays">
                            <div class="calendar-weekday">Min</div>
                            <div class="calendar-weekday">Sen</div>
                            <div class="calendar-weekday">Sel</div>
                            <div class="calendar-weekday">Rab</div>
                            <div class="calendar-weekday">Kam</div>
                            <div class="calendar-weekday">Jum</div>
                            <div class="calendar-weekday">Sab</div>
                        </div>
                        <div id="calendarDays" class="calendar-days">
                            <!-- diisi JS -->
                        </div>
                    </div>

                    <div class="notes-container">
                        <div class="notes-header">
                            <h3>Catatan Meeting</h3>
                        </div>
                        <div class="notes-body" id="notesContainer">
                            <!-- diisi JS -->
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="tab-nav fade-panel">
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

                <!-- Catatan Meeting Panel dengan animasi fade -->
                <div id="meetingPanel" class="panel mb-8 fade-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-symbols-rounded text-primary">description</span>
                            Catatan Meeting
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!-- Desktop Table View -->
                        <div class="data-table-container">
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
                                                <tr class="fade-table-row" style="animation-delay: {{ $loop->index * 0.05 }}s;">
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
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-table-cards">
                            @if ($catatanRapat->count() > 0)
                                @foreach ($catatanRapat as $rapat)
                                    <div class="mobile-table-card">
                                        <div class="mobile-table-card-header">
                                            #{{ $loop->iteration }} - {{ \Carbon\Carbon::parse($rapat->tanggal)->translatedFormat('d F Y') }}
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Topik:</div>
                                            <div class="mobile-table-card-value">{{ $rapat->topik }}</div>
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Peserta:</div>
                                            <div class="mobile-table-card-value">
                                                @foreach ($rapat->peserta as $user)
                                                    <span class="block">{{ $user->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Hasil:</div>
                                            <div class="mobile-table-card-value">{{ Str::limit($rapat->hasil_diskusi, 50) }}</div>
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Keputusan:</div>
                                            <div class="mobile-table-card-value">{{ Str::limit($rapat->keputusan, 50) }}</div>
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Penugasan:</div>
                                            <div class="mobile-table-card-value">
                                                @foreach ($rapat->penugasan as $user)
                                                    <span class="block">{{ $user->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="mobile-table-card">
                                    <div class="text-center py-6 text-gray-500">
                                        Belum ada catatan meeting
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pengumuman Terbaru Panel dengan animasi fade -->
                <div id="announcementPanel" class="panel mb-8 hidden fade-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-symbols-rounded text-primary">campaign</span>
                            Pengumuman Terbaru
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!-- Desktop Table View -->
                        <div class="data-table-container">
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
                                                <tr class="fade-table-row" style="animation-delay: {{ $loop->index * 0.05 }}s;">
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
                                                            {{ $item->users->take(2)->pluck('name')->join(', ') }}
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
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-table-cards">
                            @if ($pengumumanTerbaru->count() > 0)
                                @foreach ($pengumumanTerbaru as $item)
                                    <div class="mobile-table-card">
                                        <div class="mobile-table-card-header">
                                            #{{ $loop->iteration }} - {{ $item->judul }}
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Isi:</div>
                                            <div class="mobile-table-card-value">{{ \Illuminate\Support\Str::limit($item->isi_pesan, 100) }}</div>
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Kepada:</div>
                                            <div class="mobile-table-card-value">
                                                @if ($item->kepada === 'specific')
                                                    @foreach ($item->users as $user)
                                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs mr-1 mb-1">
                                                            {{ $user->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    {{ $item->users->take(2)->pluck('name')->join(', ') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mobile-table-card-row">
                                            <div class="mobile-table-card-label">Lampiran:</div>
                                            <div class="mobile-table-card-value">
                                                @if ($item->lampiran)
                                                    <a href="{{ asset('storage/' . $item->lampiran) }}"
                                                        class="text-blue-600 hover:underline" target="_blank">
                                                        Lihat
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="mobile-table-card">
                                    <div class="text-center py-8 text-gray-500">
                                        Tidak ada pengumuman terbaru
                                    </div>
                                </div>
                            @endif
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
        // Tambahkan animasi untuk row table
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk rows table
            const tableRows = document.querySelectorAll('.fade-table-row');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('fade-card');
            });

            // Fungsi untuk menampilkan animasi saat tab diganti
            window.animateTabSwitch = function(tabName) {
                const panel = document.getElementById(tabName + 'Panel');
                panel.classList.remove('fade-panel');
                void panel.offsetWidth; // Trigger reflow
                panel.classList.add('fade-panel');
                
                // Animate mobile cards if visible
                setTimeout(() => {
                    const mobileCards = panel.querySelectorAll('.mobile-table-card');
                    mobileCards.forEach((card, index) => {
                        card.style.animationDelay = `${index * 0.05}s`;
                        card.classList.add('fade-card');
                    });
                }, 100);
            };
        });

        // Simple dark mode toggle logic for demonstration (optional)
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        // Function to switch between tabs
        window.switchTab = function (tabName) {
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
                // Trigger animation
                animateTabSwitch('meeting');
            } else if (tabName === 'announcement') {
                announcementPanel.classList.remove('hidden');
                announcementTab.classList.add('active');
                // Trigger animation
                animateTabSwitch('announcement');
            }
        }

        // Calendar functionality
        const events = @json($events);
        const today = new Date();
        const calendarTitle = document.getElementById('calendarTitle');
        const calendarDays = document.getElementById('calendarDays');
        const notesContainer = document.getElementById('notesContainer');

        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();
        let selectedDate = null;

        function updateCalendarTitle() {
            const monthNames = [
                "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
            ];

            calendarTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        }

        function renderCalendar() {
            updateCalendarTitle();

            // Clear previous calendar days
            calendarDays.innerHTML = '';

            // Get first day of month and number of days in month
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Add empty cells for days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day';
                calendarDays.appendChild(emptyDay);
            }

            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;

                // Add fade animation to calendar days
                setTimeout(() => {
                    dayElement.style.opacity = '0';
                    dayElement.style.transform = 'translateY(5px)';
                    dayElement.style.transition = 'all 0.3s ease';
                    
                    setTimeout(() => {
                        dayElement.style.opacity = '1';
                        dayElement.style.transform = 'translateY(0)';
                    }, 10);
                }, day * 10);

                // Check if this day is today
                const currentDate = new Date();
                if (currentYear === currentDate.getFullYear() &&
                    currentMonth === currentDate.getMonth() &&
                    day === currentDate.getDate()) {
                    dayElement.classList.add('today');
                }

                // Check if this day has events
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                if (events[dateStr]) {
                    dayElement.classList.add('has-event');
                }

                // Add click event to select date
                dayElement.addEventListener('click', function () {
                    // Remove selected class from all days
                    document.querySelectorAll('.calendar-day').forEach(el => {
                        el.classList.remove('selected');
                    });

                    // Add selected class to clicked day
                    this.classList.add('selected');

                    // Update selected date
                    selectedDate = new Date(currentYear, currentMonth, day);

                    // Show events for selected date
                    showEvents(dateStr);
                });

                calendarDays.appendChild(dayElement);
            }
        }

        function showEvents(dateStr) {
            notesContainer.innerHTML = '';

            if (events[dateStr]) {
                events[dateStr].forEach((event, index) => {
                    const noteItem = document.createElement('div');
                    noteItem.className = 'note-item';
                    
                    // Add fade animation
                    noteItem.style.animationDelay = `${index * 0.1}s`;
                    noteItem.classList.add('fade-card');

                    const noteDate = document.createElement('div');
                    noteDate.className = 'note-date';
                    noteDate.textContent = dateStr;

                    const noteTitle = document.createElement('div');
                    noteTitle.className = 'note-title';
                    noteTitle.textContent = event.topik || 'Meeting';

                    const noteContent = document.createElement('div');
                    noteContent.className = 'note-content';
                    noteContent.textContent = event.keputusan || 'Tidak ada keputusan';

                    noteItem.appendChild(noteDate);
                    noteItem.appendChild(noteTitle);
                    noteItem.appendChild(noteContent);

                    notesContainer.appendChild(noteItem);
                });
            } else {
                const noEvents = document.createElement('div');
                noEvents.className = 'text-center text-gray-500 py-4 text-xs fade-card';
                noEvents.textContent = 'Tidak ada catatan pada tanggal ini';
                notesContainer.appendChild(noEvents);
            }
        }

        function prevMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        }

        // Initialize calendar
        renderCalendar();

        // Show today's events by default
        const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        showEvents(todayStr);
    </script>
</body>

</html>
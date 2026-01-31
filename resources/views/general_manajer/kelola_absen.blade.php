<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Rekap Absensi - General Manager</title>
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
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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

        /* Status Badge Styles */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-hadir {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        .status-terlambat {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        .status-izin {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }

        .status-sakit {
            background-color: rgba(251, 146, 60, 0.15);
            color: #9a3412;
        }

        .status-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        .status-approved {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        .status-rejected {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }
        .status-tidak-hadir {
            background-color: rgba(107, 114, 128, 0.15);
            color: #374151;
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
            min-width: 800px;
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

        /* Tab Navigation Styles */
        .tab-nav {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #64748b;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-button:hover {
            color: #3b82f6;
        }

        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        /* Icon styling */
        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
        }

        /* Filter styles */
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        /* Form input styles */
        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            background-color: white;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #1e293b;
            color: white;
            transition: all 0.3s ease;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #334155;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar-item:hover {
            background-color: #334155;
            color: white;
        }

        .sidebar-item.active {
            background-color: #3b82f6;
            color: white;
        }

        .sidebar-item .material-icons-outlined {
            margin-right: 0.75rem;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 101;
            background-color: #1e293b;
            color: white;
            border: none;
            border-radius: 0.375rem;
            padding: 0.5rem;
            cursor: pointer;
        }

        /* Main content with sidebar */
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body class="font-display bg-background-light text-text-light">
    <!-- Sidebar -->
    @include('general_manajer/templet/header')
    
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <span class="material-icons-outlined">menu</span>
    </button>

    <!-- Main Content Container -->
    <div class="main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-border-light">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-text-light">General Manager Dashboard</h1>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col bg-background-light">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Rekap Absensi</h2>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                    <!-- Total Kehadiran Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-green-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-green-600 text-lg md:text-xl">check_circle</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Total Kehadiran</p>
                                <p class="text-xl md:text-2xl font-bold text-green-600">
                                    {{ ($stats['total_tepat_waktu'] ?? 0) + ($stats['total_terlambat'] ?? 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tidak Hadir Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-red-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-red-600 text-lg md:text-xl">cancel</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Tidak Hadir</p>
                                <p class="text-xl md:text-2xl font-bold text-red-600">
                                    {{ $stats['total_tidak_masuk'] ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Izin Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-blue-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-blue-600 text-lg md:text-xl">error</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Izin</p>
                                <p class="text-xl md:text-2xl font-bold text-blue-600">
                                    {{ $stats['total_izin'] ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sakit Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-orange-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-orange-600 text-lg md:text-xl">healing</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Sakit</p>
                                <p class="text-xl md:text-2xl font-bold text-orange-600">
                                    {{ $stats['total_sakit'] ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="panel mb-6 md:mb-8">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">filter_list</span>
                            Filter Data
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('general_manajer.kelola_absen') }}" class="filter-container">
                            <!-- Tanggal Mulai -->
                            <div class="filter-item">
                                <label class="filter-label" for="start_date">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" 
                                       value="{{ $startDate ?? date('Y-m-01') }}" 
                                       class="form-input">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div class="filter-item">
                                <label class="filter-label" for="end_date">Tanggal Akhir</label>
                                <input type="date" id="end_date" name="end_date" 
                                       value="{{ $endDate ?? date('Y-m-d') }}" 
                                       class="form-input">
                            </div>

                            <!-- Divisi -->
                            <div class="filter-item">
                                <label class="filter-label" for="division">Divisi</label>
                                <select id="division" name="division" class="form-input">
                                    <option value="semua">Semua Divisi</option>
                                    @foreach($divisions as $division)
                                        @if($division)
                                            <option value="{{ $division }}" {{ $selectedDivision == $division ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $division)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="filter-item">
                                <label class="filter-label" for="status">Status</label>
                                <select id="status" name="status" class="form-input">
                                    <option value="semua" {{ $statusFilter == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="hadir" {{ $statusFilter == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="terlambat" {{ $statusFilter == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                    <option value="izin" {{ $statusFilter == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ $statusFilter == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $statusFilter == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $statusFilter == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="filter-item flex-end">
                                <label class="filter-label">&nbsp;</label>
                                <div class="flex gap-2">
                                    <button type="submit" class="btn-primary px-4 py-2 rounded-md text-white">
                                        <span class="material-icons-outlined text-sm align-middle mr-1">filter_list</span>
                                        Filter
                                    </button>
                                    <a href="{{ route('general_manajer.kelola_absen') }}" 
                                       class="btn-secondary px-4 py-2 rounded-md text-gray-700 inline-block text-center">
                                        <span class="material-icons-outlined text-sm align-middle mr-1">refresh</span>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="tab-nav">
                    <button id="attendanceTab" class="tab-button active" onclick="switchTab('attendance')">
                        <span class="material-icons-outlined align-middle mr-2">fact_check</span>
                        Data Absensi
                    </button>
                    <button id="absenceTab" class="tab-button" onclick="switchTab('absence')">
                        <span class="material-icons-outlined align-middle mr-2">assignment_late</span>
                        Daftar Ketidakhadiran
                    </button>
                </div>

                <!-- Data Absensi Panel -->
                <div id="attendancePanel" class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">fact_check</span>
                            Data Absensi
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light">{{ $formattedAbsensi->count() }}</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE -->
                        <div class="scrollable-table-container table-shadow">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 60px;">No</th>
                                        <th style="min-width: 200px;">Nama Karyawan</th>
                                        <th style="min-width: 120px;">Divisi</th>
                                        <th style="min-width: 120px;">Tanggal</th>
                                        <th style="min-width: 120px;">Jam Masuk</th>
                                        <th style="min-width: 120px;">Jam Pulang</th>
                                        <th style="min-width: 120px;">Status</th>
                                        <th style="min-width: 120px;">Approval</th>
                                    </tr>
                                </thead>
                                <tbody>
<!-- Di view, pastikan menggunakan $formattedAbsensi bukan $attendances -->
@forelse($formattedAbsensi as $index => $absen)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $absen['user_name'] }}</td>
        <td>{{ $absen['divisi'] }}</td>
        <td>{{ \Carbon\Carbon::parse($absen['tanggal'])->format('d/m/Y') }}</td>
        <td>{{ $absen['jam_masuk'] }}</td>
        <td>{{ $absen['jam_pulang'] }}</td>
        <td>
            <span class="status-badge {{ $absen['status_class'] }}">
                {{ $absen['status_kehadiran'] }}
            </span>
        </td>
        <td>
            @if($absen['type'] == 'ketidakhadiran' && $absen['approval_status'] == 'pending')
                <!-- Tombol approve/reject -->
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-8">Tidak ada data ditemukan</td>
    </tr>
@endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Daftar Ketidakhadiran Panel (Initially Hidden) -->
                <div id="absencePanel" class="panel hidden">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">assignment_late</span>
                            Daftar Ketidakhadiran
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="absenceCount">0</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE -->
                        <div class="scrollable-table-container table-shadow">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 60px;">No</th>
                                        <th style="min-width: 200px;">Nama Karyawan</th>
                                        <th style="min-width: 120px;">Divisi</th>
                                        <th style="min-width: 120px;">Tanggal Mulai</th>
                                        <th style="min-width: 120px;">Tanggal Akhir</th>
                                        <th style="min-width: 200px;">Alasan</th>
                                        <th style="min-width: 120px;">Status</th>
                                        <th style="min-width: 120px;">Approval</th>
                                    </tr>
                                </thead>
                                <tbody id="absenceTableBody">
                                    <!-- Data rows will be populated by JavaScript -->
@forelse($formattedAbsensi->where('type', 'ketidakhadiran') as $index => $absen)
<tr class="hover:bg-gray-50">
    <td style="min-width: 60px;">{{ $index + 1 }}</td>
    <td style="min-width: 200px;">{{ $absen['user_name'] }}</td>
    <td style="min-width: 120px;">{{ $absen['divisi'] }}</td>
    <td style="min-width: 120px;">{{ \Carbon\Carbon::parse($absen['tanggal'])->format('d/m/Y') }}</td>
    <td style="min-width: 120px;">{{ $absen['tanggal_akhir'] ? \Carbon\Carbon::parse($absen['tanggal_akhir'])->format('d/m/Y') : '-' }}</td>
    <td style="min-width: 200px;">{{ $absen['alasan'] ?? '-' }}</td>
    <td style="min-width: 120px;">
        @if($absen['jenis_ketidakhadiran'] == 'izin')
            <span class="status-badge status-izin">Izin</span>
        @elseif($absen['jenis_ketidakhadiran'] == 'sakit')
            <span class="status-badge status-sakit">Sakit</span>
        @endif
    </td>
    <td style="min-width: 120px;">
        @if($absen['approval_status'] == 'pending')
            <span class="status-badge status-pending">
                Pending
            </span>
            <div class="mt-1 flex space-x-2">
                <form action="{{ route('general_manajer.absensi.approve', $absen['id']) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-green-600 hover:text-green-900 text-sm">
                        Approve
                    </button>
                </form>
                <form action="{{ route('general_manajer.absensi.reject', $absen['id']) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" onclick="showRejectModal('{{ $absen['id'] }}', '{{ $absen['user_name'] }}')" 
                            class="text-red-600 hover:text-red-900 text-sm">
                        Reject
                    </button>
                </form>
            </div>
        @elseif($absen['approval_status'] == 'approved')
            <span class="status-badge status-approved">
                Approved
            </span>
        @elseif($absen['approval_status'] == 'rejected')
            <span class="status-badge status-rejected">
                Rejected
            </span>
        @else
            <span class="text-sm text-gray-500">-</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
        <div class="flex flex-col items-center">
            <span class="material-icons-outlined text-4xl text-gray-300 mb-2">search_off</span>
            <p class="text-lg">Tidak ada data ditemukan</p>
            <p class="text-sm mt-1">Coba ubah filter pencarian Anda</p>
        </div>
    </td>
</tr>
@endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©{{ date('Y') }} by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal untuk Reject -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tolak Permohonan</h3>
                <p class="text-sm text-gray-500 mb-4" id="employeeName"></p>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                        <textarea name="rejection_reason" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                  placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="hideRejectModal()" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to switch between tabs
        function switchTab(tabName) {
            // Get tab buttons and panels
            const attendanceTab = document.getElementById('attendanceTab');
            const absenceTab = document.getElementById('absenceTab');
            const attendancePanel = document.getElementById('attendancePanel');
            const absencePanel = document.getElementById('absencePanel');

            // Hide all panels and remove active class from all tabs
            attendancePanel.classList.add('hidden');
            absencePanel.classList.add('hidden');
            attendanceTab.classList.remove('active');
            absenceTab.classList.remove('active');

            // Show selected panel and add active class to clicked tab
            if (tabName === 'attendance') {
                attendancePanel.classList.remove('hidden');
                attendanceTab.classList.add('active');
            } else if (tabName === 'absence') {
                absencePanel.classList.remove('hidden');
                absenceTab.classList.add('active');
            }
        }

        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        });

        function showRejectModal(absensiId, employeeName) {
            document.getElementById('employeeName').textContent = 'Karyawan: ' + employeeName;
            document.getElementById('rejectForm').action = '/general-manajer/absensi/' + absensiId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        
        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectForm').reset();
        }
        
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target.id === 'rejectModal') {
                hideRejectModal();
            }
        });
        
        // Auto-set end date jika start date lebih baru
        document.querySelector('input[name="start_date"]')?.addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.querySelector('input[name="end_date"]');
            
            if (startDate && endDateInput && startDate > endDateInput.value) {
                endDateInput.value = startDate;
            }
        });
        
        // Validasi form filter
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const startDate = this.querySelector('input[name="start_date"]')?.value;
            const endDate = this.querySelector('input[name="end_date"]')?.value;
            
            if (startDate && endDate && startDate > endDate) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
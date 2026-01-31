<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi - Dashboard Manajer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .material-icons-outlined { font-size: 24px; vertical-align: middle; }
        .card { transition: all 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); }
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-hadir { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .status-terlambat { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .status-izin { background-color: rgba(59, 130, 246, 0.15); color: #1e40af; }
        .status-cuti { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }
        .status-sakit { background-color: rgba(251, 146, 60, 0.15); color: #9a3412; }
        .status-dinas-luar { background-color: rgba(139, 92, 246, 0.15); color: #5b21b6; }
        .status-tidak-masuk { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }
        .icon-container { display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f8fafc; font-weight: 600; color: #374151; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .data-table tbody tr:nth-child(even) { background: #f9fafb; }
        .data-table tbody tr:hover { background: #f3f4f6; }
        .form-input { border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 0.375rem; transition: all 0.2s ease; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); outline: none; }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    @include('general_manajer/templet/header')
    
    <main class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Absensi</h1>
                    @if($selectedDivision)
                        <p class="text-sm text-blue-600 mt-1">
                            <span class="material-icons-outlined text-sm align-middle">filter_list</span>
                            Menampilkan untuk divisi: <span class="font-semibold">{{ $selectedDivision }}</span>
                        </p>
                    @endif
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                    <!-- Total Kehadiran Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-green-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-green-600 text-lg md:text-xl">check_circle</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Total Kehadiran</p>
                                <p class="text-xl font-bold text-green-600">{{ $stats['total_tepat_waktu'] }}</p>
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
                                <p class="text-xl md:text-2xl font-bold text-red-600">{{ $stats['total_tidak_masuk'] }}</p>
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
                                <p class="text-xl md:text-2xl font-bold text-blue-600">{{ $stats['total_izin'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cuti Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-yellow-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-yellow-600 text-lg md:text-xl">event_busy</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Cuti</p>
                                <p class="text-xl md:text-2xl font-bold text-yellow-600">{{ $stats['total_cuti'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dinas Luar Card -->
                    <div class="card bg-white p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="icon-container bg-purple-100 mr-3 md:mr-4">
                                <span class="material-icons-outlined text-purple-600 text-lg md:text-xl">directions_car</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Dinas Luar</p>
                                <p class="text-xl md:text-2xl font-bold text-purple-600">{{ $stats['total_dinas_luar'] }}</p>
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
                                <p class="text-xl md:text-2xl font-bold text-orange-600">{{ $stats['total_sakit'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Tab Navigation -->
                <div class="tab-nav">
                    <button id="absensiTab" class="tab-button active" onclick="switchTab('absensi')">
                        <span class="material-icons-outlined align-middle mr-2">fact_check</span>
                        Data Absensi
                    </button>
                    <button id="ketidakhadiranTab" class="tab-button" onclick="switchTab('ketidakhadiran')">
                        <span class="material-icons-outlined align-middle mr-2">assignment_late</span>
                        Daftar Ketidakhadiran
                    </button>
                </div>

                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Cari nama karyawan..." type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <div class="relative">
                            <button id="filterBtn" class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <span class="material-icons-outlined text-sm">filter_list</span>
                                Filter
                            </button>
                            <div id="filterDropdown" class="filter-dropdown">
                                <div class="filter-option">
                                    <input type="checkbox" id="filterAll" value="all" checked>
                                    <label for="filterAll">Semua Status</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterHadir" value="hadir">
                                    <label for="filterHadir">Hadir</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterTerlambat" value="terlambat">
                                    <label for="filterTerlambat">Terlambat</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterTidakHadir" value="tidak hadir">
                                    <label for="filterTidakHadir">Tidak Hadir</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterIzin" value="izin">
                                    <label for="filterIzin">Izin</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterCuti" value="cuti">
                                    <label for="filterCuti">Cuti</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterDinasLuar" value="dinas luar">
                                    <label for="filterDinasLuar">Dinas Luar</label>
                                </div>
                                <div class="filter-option">
                                    <input type="checkbox" id="filterSakit" value="sakit">
                                    <label for="filterSakit">Sakit</label>
                                </div>
                                <div class="filter-actions">
                                    <button id="applyFilter" class="filter-apply">Terapkan</button>
                                    <button id="resetFilter" class="filter-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="date-picker-container">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">date_range</span>
                            <input id="dateFilter" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Pilih tanggal" type="date" />
                        </div>
                    </div>
                </div>

                <!-- Data Absensi Panel -->
                <div id="absensiPanel" class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">fact_check</span>
                            Data Absensi
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Total: <span id="totalCount" class="font-semibold text-gray-800">{{ $formattedAbsensi->count() }}</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Desktop Table View -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 100px;">Nama</th>
                                            <th style="min-width: 120px;">Tanggal</th>
                                            <th style="min-width: 120px;">Jam Masuk</th>
                                            <th style="min-width: 120px;">Jam Keluar</th>
                                            <th style="min-width: 100px;">Status</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="absensiTableBody">
                                        @foreach ($formattedAbsensi as $i => $absen)
                                            <tr class="absensi-row" data-id="{{ $absen->id }}">
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ optional($absen->user)->name ?? 'User tidak ditemukan' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                                                <td>{{ $absen->jam_masuk ?? '-' }}</td>
                                                <td>{{ $absen->jam_pulang ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $status = 'Tepat Waktu';
                                                        if ($absen->jam_masuk) {
                                                            $jamMasuk = \Carbon\Carbon::parse($absen->jam_masuk);
                                                            $jamBatas = \Carbon\Carbon::parse('09:05');
                                                            if ($jamMasuk->gt($jamBatas)) {
                                                                $status = 'Terlambat';
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="status-badge status-{{ $status == 'Tepat Waktu' ? 'hadir' : 'terlambat' }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="edit-absensi-btn text-blue-600 hover:text-blue-800 mr-2" data-id="{{ $absen->id }}">
                                                        <span class="material-icons-outlined text-sm">edit</span>
                                                    </button>
                                                    <button class="delete-absensi-btn text-red-600 hover:text-red-800" data-id="{{ $absen->id }}">
                                                        <span class="material-icons-outlined text-sm">delete</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div id="absensiPagination" class="desktop-pagination">
                                <button id="absensiPrevPage" class="desktop-nav-btn">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <div id="absensiPageNumbers" class="flex gap-1">
                                    <!-- Page numbers will be generated by JavaScript -->
                                </div>
                                <button id="absensiNextPage" class="desktop-nav-btn">
                                    <span class="material-icons-outlined text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="mobile-cards" id="absensiMobileCards">
                            @foreach ($formattedAbsensi as $i => $absen)
                                <div class="mobile-card absensi-card" data-id="{{ $absen->id }}">
                                    <div class="mobile-card-header">
                                        <div class="mobile-card-title">{{ optional($absen->user)->name ?? 'User tidak ditemukan' }}</div>
                                        <div>
                                            @php
                                                $status = 'Tepat Waktu';
                                                if ($absen->jam_masuk) {
                                                    $jamMasuk = \Carbon\Carbon::parse($absen->jam_masuk);
                                                    $jamBatas = \Carbon\Carbon::parse('09:05');
                                                    if ($jamMasuk->gt($jamBatas)) {
                                                        $status = 'Terlambat';
                                                    }
                                                }
                                            @endphp
                                            <span class="status-badge status-{{ $status == 'Tepat Waktu' ? 'hadir' : 'terlambat' }}">
                                                {{ $status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mobile-card-body">
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">No</span>
                                            <span class="mobile-card-value">{{ $i + 1 }}</span>
                                        </div>
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">Tanggal</span>
                                            <span class="mobile-card-value">{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">Jam Masuk</span>
                                            <span class="mobile-card-value">{{ $absen->jam_masuk ?? '-' }}</span>
                                        </div>
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">Jam Keluar</span>
                                            <span class="mobile-card-value">{{ $absen->jam_pulang ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-end space-x-2 mt-3">
                                        <button class="edit-absensi-btn text-gray-600 hover:text-gray-800" data-id="{{ $absen->id }}" title="Edit">
                                            <span class="material-icons-outlined text-sm">edit</span>
                                        </button>
                                        <button class="delete-absensi-btn text-gray-600 hover:text-gray-800" data-id="{{ $absen->id }}" title="Hapus">
                                            <span class="material-icons-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Mobile Pagination -->
                        <div id="absensiMobilePagination" class="mobile-pagination">
                            <button id="absensiMobilePrevPage" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="absensiMobilePageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="absensiMobileNextPage" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ketidakhadiran Panel (Initially Hidden) -->
                <div id="ketidakhadiranPanel" class="panel hidden">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">assignment_late</span>
                            Daftar Ketidakhadiran
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Total: <span id="totalCount2" class="font-semibold text-gray-800">{{ $ketidakhadiran->count() }}</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Desktop Table View -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 200px;">Nama</th>
                                            <th style="min-width: 120px;">Tanggal Mulai</th>
                                            <th style="min-width: 120px;">Tanggal Akhir</th>
                                            <th style="min-width: 200px;">Alasan</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ketidakhadiranTableBody">
                                        @foreach ($ketidakhadiran as $index => $absen)
                                            <tr class="ketidakhadiran-row" data-id="{{ $absen->id }}"
                                                data-nama="{{ $absen->user->name ?? '-' }}"
                                                data-tanggal="{{ $absen->tanggal }}"
                                                data-tanggal-akhir="{{ $absen->tanggal_akhir }}"
                                                data-alasan="{{ $absen->keterangan ?? '-' }}"
                                                data-status="{{ $absen->approval_status }}">

                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $absen->user->name ?? '-' }}</td>
                                                <td>{{ $absen->tanggal?->format('d/m/Y') }}</td>
                                                <td>
                                                    {{ $absen->tanggal_akhir ? $absen->tanggal_akhir->format('d/m/Y') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $absen->keterangan ?? '-' }}
                                                </td>
                                                <td>
                                                    <span class="status-badge status-{{ Str::slug($absen->approval_status) }}">
                                                        {{ strtoupper($absen->approval_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="flex justify-center space-x-2">
                                                        <button class="edit-cuti-btn text-blue-600 hover:text-blue-800" data-id="{{ $absen->id }}" title="Edit">
                                                            <span class="material-icons-outlined text-sm">edit</span>
                                                        </button>
                                                        @if ($absen->approval_status === 'pending')
                                                            <button class="verify-btn text-green-600 hover:text-green-800" data-id="{{ $absen->id }}" title="Verifikasi">
                                                                <span class="material-icons-outlined text-sm">check_circle</span>
                                                            </button>
                                                        @endif
                                                        <button class="delete-cuti-btn text-red-600 hover:text-red-800" data-id="{{ $absen->id }}" title="Hapus">
                                                            <span class="material-icons-outlined text-sm">delete</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div id="ketidakhadiranPagination" class="desktop-pagination">
                                <button id="ketidakhadiranPrevPage" class="desktop-nav-btn">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <div id="ketidakhadiranPageNumbers" class="flex gap-1">
                                    <!-- Page numbers will be generated by JavaScript -->
                                </div>
                                <button id="ketidakhadiranNextPage" class="desktop-nav-btn">
                                    <span class="material-icons-outlined text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="mobile-cards" id="ketidakhadiranMobileCards">
                            @foreach ($ketidakhadiran as $index => $absen)
                                <div class="mobile-card ketidakhadiran-card" data-id="{{ $absen->id }}">
                                    <div class="mobile-card-header">
                                        <div class="mobile-card-title">{{ $absen->user->name ?? '-' }}</div>
                                        <div>
                                            <span class="status-badge status-{{ Str::slug($absen->approval_status) }}">
                                                {{ strtoupper($absen->approval_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mobile-card-body">
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">No</span>
                                            <span class="mobile-card-value">{{ $index + 1 }}</span>
                                        </div>
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">Tanggal</span>
                                            <span class="mobile-card-value">
                                                {{ $absen->tanggal?->format('d/m/Y') }}
                                                @if ($absen->tanggal_akhir)
                                                    - {{ $absen->tanggal_akhir->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">Alasan</span>
                                            <span class="mobile-card-value">{{ $absen->keterangan ?? '-' }}</span>
                                        </div>
                                        <div class="mobile-card-item">
                                            <span class="mobile-card-label">Status</span>
                                            <span class="mobile-card-value">{{ strtoupper($absen->approval_status) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-end space-x-2 mt-3">
                                        <button class="edit-cuti-btn text-gray-600 hover:text-gray-800" data-id="{{ $absen->id }}" title="Edit">
                                            <span class="material-icons-outlined text-sm">edit</span>
                                        </button>
                                        @if ($absen->approval_status === 'pending')
                                            <button class="verify-btn text-gray-600 hover:text-gray-800" data-id="{{ $absen->id }}" title="Verifikasi">
                                                <span class="material-icons-outlined text-sm">check_circle</span>
                                            </button>
                                        @endif
                                        <button class="delete-cuti-btn text-gray-600 hover:text-gray-800" data-id="{{ $absen->id }}" title="Hapus">
                                            <span class="material-icons-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            <span class="material-icons-outlined text-sm align-middle mr-1">filter_list</span>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="card bg-white p-4 rounded-xl shadow-md">
                    <div class="flex items-center">
                        <div class="icon-container bg-green-100 mr-3">
                            <span class="material-icons-outlined text-green-600 text-xl">check_circle</span>
                        </div>

                        <!-- Mobile Pagination -->
                        <div id="ketidakhadiranMobilePagination" class="mobile-pagination">
                            <button id="ketidakhadiranMobilePrevPage" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="ketidakhadiranMobilePageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="ketidakhadiranMobileNextPage" class="mobile-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Edit Cuti Modal -->
    <div id="editCutiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Edit Cuti/Izin</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <form id="editCutiForm" class="p-6">
                <input type="hidden" id="editCutiId" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Karyawan</label>
                        <select id="editCutiNamaKaryawan" name="user_id" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                            <option value="">Pilih karyawan</option>
                            @foreach ($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jenis Cuti/Izin</label>
                        <select id="editCutiJenisCuti" name="jenis_cuti" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                            <option value="">Pilih jenis cuti</option>
                            <option value="cuti">Cuti</option>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                            <option value="dinas-luar">Dinas Luar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                        <input type="date" id="editCutiTanggalMulai" name="tanggal" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
                        <input type="date" id="editCutiTanggalAkhir" name="tanggal_akhir" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Alasan</label>
                        <textarea id="editCutiAlasan" name="keterangan" rows="3" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary" placeholder="Masukkan alasan cuti"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Status Persetujuan</label>
                        <select id="editCutiStatus" name="approval_status" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div id="editRejectionReasonWrapper" class="hidden">
                        <label class="block text-sm font-medium mb-2">Alasan Penolakan</label>
                        <textarea id="editRejectionReason" name="rejection_reason" rows="3" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="cancel-btn px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg">
                        <span class="material-icons-outlined text-sm align-middle mr-2">save</span>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Verify Modal -->
    <div id="verifyModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Verifikasi Pengajuan</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <form id="verifyForm" class="p-6">
                <input type="hidden" id="verifyId">
                <input type="hidden" id="verifyType">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Status Persetujuan</label>
                    <select id="verifyStatus" name="approval_status" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>

                <div class="mb-6" id="rejectionReasonContainer" style="display: none;">
                    <label class="block text-sm font-medium mb-2">Alasan Penolakan</label>
                    <textarea id="rejectionReason" name="rejection_reason" rows="3" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary" placeholder="Masukkan alasan penolakan"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="cancel-btn px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg">
                        <span class="material-icons-outlined text-sm align-middle mr-2">check_circle</span>Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="icon-container bg-gray-100 mr-4" style="width: 3rem; height: 3rem;">
                        <span class="material-icons-outlined text-gray-600 text-2xl">warning</span>
                    </div>
                    <div>
                        <p class="font-semibold">Apakah Anda yakin?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>

                <input type="hidden" id="deleteId">
                <input type="hidden" id="deleteType">

                <div class="flex justify-end space-x-3">
                    <button class="cancel-btn px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-danger text-white rounded-lg">
                        <span class="material-icons-outlined text-sm align-middle mr-2">delete</span>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi variabel untuk pagination, filter, dan search
            let currentPageAbsensi = 1;
            let currentPageKetidakhadiran = 1;
            const itemsPerPage = 5;
            let activeFilters = ['all'];
            let searchTerm = '';

            // Dapatkan semua elemen absensi
            const absensiRows = document.querySelectorAll('.absensi-row');
            const absensiCards = document.querySelectorAll('.absensi-card');
            const ketidakhadiranRows = document.querySelectorAll('.ketidakhadiran-row');
            const ketidakhadiranCards = document.querySelectorAll('.ketidakhadiran-card');

            // Inisialisasi pagination, filter, dan search
            initializePagination();
            initializeFilter();
            initializeSearch();

            // Helper untuk menampilkan notifikasi
            function showNotification(message, type = 'success') {
                const container = document.getElementById('notificationContainer');
                const notification = document.createElement('div');

                // Set icon dan warna berdasarkan tipe
                let icon, bgColor;
                switch (type) {
                    case 'success':
                        icon = 'check_circle';
                        bgColor = 'bg-green-500';
                        break;
                    case 'error':
                        icon = 'error';
                        bgColor = 'bg-red-500';
                        break;
                    case 'warning':
                        icon = 'warning';
                        bgColor = 'bg-yellow-500';
                        break;
                    default:
                        icon = 'info';
                        bgColor = 'bg-blue-500';
                }

                notification.className = `notification ${bgColor} text-white p-4 rounded-lg shadow-lg mb-3 flex items-center`;
                notification.innerHTML = `
                    <span class="material-icons-outlined mr-3">${icon}</span>
                    <span>${message}</span>
                `;

                container.appendChild(notification);

                // Hapus notifikasi setelah 3 detik
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Fungsi untuk membuka modal
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            // Fungsi untuk menutup modal
            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    // Reset form saat modal ditutup
                    const form = modal.querySelector('form');
                    if (form) {
                        form.reset();
                    }
                    // Sembunyikan container alasan penolakan jika ada
                    const rejectionContainer = document.getElementById('rejectionReasonContainer');
                    if (rejectionContainer) {
                        rejectionContainer.style.display = 'none';
                    }
                    // Sembunyikan wrapper alasan penolakan edit jika ada
                    const editRejectionWrapper = document.getElementById('editRejectionReasonWrapper');
                    if (editRejectionWrapper) {
                        editRejectionWrapper.classList.add('hidden');
                    }
                }
            }

            // Event listeners untuk tombol tutup modal
            document.querySelectorAll('.close-modal, .cancel-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const modal = e.target.closest('[id$="Modal"]');
                    if (modal) {
                        closeModal(modal.id);
                    }
                });
            });

            // Menutup modal saat klik di luar area modal
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });

            // Event listener untuk status persetujuan di modal edit cuti
            document.getElementById('editCutiStatus')?.addEventListener('change', function() {
                const wrap = document.getElementById('editRejectionReasonWrapper');
                if (this.value === 'rejected') {
                    wrap.classList.remove('hidden');
                } else {
                    wrap.classList.add('hidden');
                    document.getElementById('editRejectionReason').value = '';
                }
            });

            // Event listener untuk status persetujuan di modal verifikasi
            document.getElementById('verifyStatus')?.addEventListener('change', function() {
                const rejectionReasonContainer = document.getElementById('rejectionReasonContainer');
                if (this.value === 'rejected') {
                    rejectionReasonContainer.style.display = 'block';
                } else {
                    rejectionReasonContainer.style.display = 'none';
                    document.getElementById('rejectionReason').value = '';
                }
            });

            // === PAGINATION ===
            function initializePagination() {
                renderPaginationAbsensi();
                renderPaginationKetidakhadiran();
                updateVisibleItemsAbsensi();
                updateVisibleItemsKetidakhadiran();

                // Event listeners untuk pagination absensi
                document.getElementById('absensiPrevPage').addEventListener('click', () => {
                    if (currentPageAbsensi > 1) {
                        currentPageAbsensi--;
                        renderPaginationAbsensi();
                        updateVisibleItemsAbsensi();
                    }
                });

                document.getElementById('absensiNextPage').addEventListener('click', () => {
                    const totalPages = Math.ceil(getFilteredRowsAbsensi().length / itemsPerPage);
                    if (currentPageAbsensi < totalPages) {
                        currentPageAbsensi++;
                        renderPaginationAbsensi();
                        updateVisibleItemsAbsensi();
                    }
                });

                // Event listeners untuk pagination ketidakhadiran
                document.getElementById('ketidakhadiranPrevPage').addEventListener('click', () => {
                    if (currentPageKetidakhadiran > 1) {
                        currentPageKetidakhadiran--;
                        renderPaginationKetidakhadiran();
                        updateVisibleItemsKetidakhadiran();
                    }
                });

                document.getElementById('ketidakhadiranNextPage').addEventListener('click', () => {
                    const totalPages = Math.ceil(getFilteredRowsKetidakhadiran().length / itemsPerPage);
                    if (currentPageKetidakhadiran < totalPages) {
                        currentPageKetidakhadiran++;
                        renderPaginationKetidakhadiran();
                        updateVisibleItemsKetidakhadiran();
                    }
                });

                // Event listeners untuk mobile pagination
                document.getElementById('absensiMobilePrevPage').addEventListener('click', () => {
                    if (currentPageAbsensi > 1) {
                        currentPageAbsensi--;
                        renderPaginationAbsensi();
                        updateVisibleItemsAbsensi();
                    }
                });

                document.getElementById('absensiMobileNextPage').addEventListener('click', () => {
                    const totalPages = Math.ceil(getFilteredRowsAbsensi().length / itemsPerPage);
                    if (currentPageAbsensi < totalPages) {
                        currentPageAbsensi++;
                        renderPaginationAbsensi();
                        updateVisibleItemsAbsensi();
                    }
                });

                document.getElementById('ketidakhadiranMobilePrevPage').addEventListener('click', () => {
                    if (currentPageKetidakhadiran > 1) {
                        currentPageKetidakhadiran--;
                        renderPaginationKetidakhadiran();
                        updateVisibleItemsKetidakhadiran();
                    }
                });

                document.getElementById('ketidakhadiranMobileNextPage').addEventListener('click', () => {
                    const totalPages = Math.ceil(getFilteredRowsKetidakhadiran().length / itemsPerPage);
                    if (currentPageKetidakhadiran < totalPages) {
                        currentPageKetidakhadiran++;
                        renderPaginationKetidakhadiran();
                        updateVisibleItemsKetidakhadiran();
                    }
                });
            }

            function renderPaginationAbsensi() {
                const visibleRows = getFilteredRowsAbsensi();
                const totalPages = Math.ceil(visibleRows.length / itemsPerPage);
                const pageNumbersContainer = document.getElementById('absensiPageNumbers');
                const mobilePageNumbersContainer = document.getElementById('absensiMobilePageNumbers');
                const prevButton = document.getElementById('absensiPrevPage');
                const nextButton = document.getElementById('absensiNextPage');
                const mobilePrevButton = document.getElementById('absensiMobilePrevPage');
                const mobileNextButton = document.getElementById('absensiMobileNextPage');

                // Clear existing page numbers
                pageNumbersContainer.innerHTML = '';
                mobilePageNumbersContainer.innerHTML = '';

                // Generate page numbers
                for (let i = 1; i <= totalPages; i++) {
                    // Desktop pagination
                    const pageNumber = document.createElement('button');
                    pageNumber.textContent = i;
                    pageNumber.className = `desktop-page-btn ${i === currentPageAbsensi ? 'active' : ''}`;
                    pageNumber.addEventListener('click', () => {
                        currentPageAbsensi = i;
                        renderPaginationAbsensi();
                        updateVisibleItemsAbsensi();
                    });
                    pageNumbersContainer.appendChild(pageNumber);

                    // Mobile pagination
                    const mobilePageNumber = document.createElement('button');
                    mobilePageNumber.textContent = i;
                    mobilePageNumber.className = `mobile-page-btn ${i === currentPageAbsensi ? 'active' : ''}`;
                    mobilePageNumber.addEventListener('click', () => {
                        currentPageAbsensi = i;
                        renderPaginationAbsensi();
                        updateVisibleItemsAbsensi();
                    });
                    mobilePageNumbersContainer.appendChild(mobilePageNumber);
                }

                // Update navigation buttons
                prevButton.disabled = currentPageAbsensi === 1;
                nextButton.disabled = currentPageAbsensi === totalPages || totalPages === 0;
                mobilePrevButton.disabled = currentPageAbsensi === 1;
                mobileNextButton.disabled = currentPageAbsensi === totalPages || totalPages === 0;
            }

            function renderPaginationKetidakhadiran() {
                const visibleRows = getFilteredRowsKetidakhadiran();
                const totalPages = Math.ceil(visibleRows.length / itemsPerPage);
                const pageNumbersContainer = document.getElementById('ketidakhadiranPageNumbers');
                const mobilePageNumbersContainer = document.getElementById('ketidakhadiranMobilePageNumbers');
                const prevButton = document.getElementById('ketidakhadiranPrevPage');
                const nextButton = document.getElementById('ketidakhadiranNextPage');
                const mobilePrevButton = document.getElementById('ketidakhadiranMobilePrevPage');
                const mobileNextButton = document.getElementById('ketidakhadiranMobileNextPage');

                // Clear existing page numbers
                pageNumbersContainer.innerHTML = '';
                mobilePageNumbersContainer.innerHTML = '';

                // Generate page numbers
                for (let i = 1; i <= totalPages; i++) {
                    // Desktop pagination
                    const pageNumber = document.createElement('button');
                    pageNumber.textContent = i;
                    pageNumber.className = `desktop-page-btn ${i === currentPageKetidakhadiran ? 'active' : ''}`;
                    pageNumber.addEventListener('click', () => {
                        currentPageKetidakhadiran = i;
                        renderPaginationKetidakhadiran();
                        updateVisibleItemsKetidakhadiran();
                    });
                    pageNumbersContainer.appendChild(pageNumber);

                    // Mobile pagination
                    const mobilePageNumber = document.createElement('button');
                    mobilePageNumber.textContent = i;
                    mobilePageNumber.className = `mobile-page-btn ${i === currentPageKetidakhadiran ? 'active' : ''}`;
                    mobilePageNumber.addEventListener('click', () => {
                        currentPageKetidakhadiran = i;
                        renderPaginationKetidakhadiran();
                        updateVisibleItemsKetidakhadiran();
                    });
                    mobilePageNumbersContainer.appendChild(mobilePageNumber);
                }

                // Update navigation buttons
                prevButton.disabled = currentPageKetidakhadiran === 1;
                nextButton.disabled = currentPageKetidakhadiran === totalPages || totalPages === 0;
                mobilePrevButton.disabled = currentPageKetidakhadiran === 1;
                mobileNextButton.disabled = currentPageKetidakhadiran === totalPages || totalPages === 0;
            }

            function getFilteredRowsAbsensi() {
                return Array.from(absensiRows).filter(row => !row.classList.contains('hidden-by-filter'));
            }

            function getFilteredRowsKetidakhadiran() {
                return Array.from(ketidakhadiranRows).filter(row => !row.classList.contains('hidden-by-filter'));
            }

            function getFilteredCardsAbsensi() {
                return Array.from(absensiCards).filter(card => !card.classList.contains('hidden-by-filter'));
            }

            function getFilteredCardsKetidakhadiran() {
                return Array.from(ketidakhadiranCards).filter(card => !card.classList.contains('hidden-by-filter'));
            }

            function updateVisibleItemsAbsensi() {
                const visibleRows = getFilteredRowsAbsensi();
                const visibleCards = getFilteredCardsAbsensi();

                const startIndex = (currentPageAbsensi - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                // Hide all rows and cards first
                absensiRows.forEach(row => row.style.display = 'none');
                absensiCards.forEach(card => card.style.display = 'none');

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

                // Update total count
                document.getElementById('totalCount').textContent = visibleRows.length;
            }

            function updateVisibleItemsKetidakhadiran() {
                const visibleRows = getFilteredRowsKetidakhadiran();
                const visibleCards = getFilteredCardsKetidakhadiran();

                const startIndex = (currentPageKetidakhadiran - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                // Hide all rows and cards first
                ketidakhadiranRows.forEach(row => row.style.display = 'none');
                ketidakhadiranCards.forEach(card => card.style.display = 'none');

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

                // Update total count
                document.getElementById('totalCount2').textContent = visibleRows.length;
            }

            // === FILTER ===
            function initializeFilter() {
                const filterBtn = document.getElementById('filterBtn');
                const filterDropdown = document.getElementById('filterDropdown');
                const applyFilterBtn = document.getElementById('applyFilter');
                const resetFilterBtn = document.getElementById('resetFilter');
                const filterAll = document.getElementById('filterAll');
                const dateFilter = document.getElementById('dateFilter');

                // Toggle filter dropdown
                filterBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    filterDropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    filterDropdown.classList.remove('show');
                });

                // Prevent dropdown from closing when clicking inside
                filterDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Handle "All" checkbox
                filterAll.addEventListener('change', function() {
                    if (this.checked) {
                        // Uncheck all other checkboxes
                        document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)')
                            .forEach(cb => {
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
                    const filterHadir = document.getElementById('filterHadir');
                    const filterTerlambat = document.getElementById('filterTerlambat');
                    const filterTidakHadir = document.getElementById('filterTidakHadir');
                    const filterIzin = document.getElementById('filterIzin');
                    const filterCuti = document.getElementById('filterCuti');
                    const filterDinasLuar = document.getElementById('filterDinasLuar');
                    const filterSakit = document.getElementById('filterSakit');

                    activeFilters = [];
                    if (filterAll.checked) {
                        activeFilters.push('all');
                    } else {
                        if (filterHadir.checked) activeFilters.push('hadir');
                        if (filterTerlambat.checked) activeFilters.push('terlambat');
                        if (filterTidakHadir.checked) activeFilters.push('tidak hadir');
                        if (filterIzin.checked) activeFilters.push('izin');
                        if (filterCuti.checked) activeFilters.push('cuti');
                        if (filterDinasLuar.checked) activeFilters.push('dinas luar');
                        if (filterSakit.checked) activeFilters.push('sakit');
                    }

                    applyFilters();
                    filterDropdown.classList.remove('show');
                    const visibleCount = getFilteredRowsAbsensi().length + getFilteredRowsKetidakhadiran().length;
                    showNotification('Filter Diterapkan', `Menampilkan ${visibleCount} data`, 'success');
                });

                // Reset filter
                resetFilterBtn.addEventListener('click', function() {
                    document.getElementById('filterAll').checked = true;
                    document.getElementById('filterHadir').checked = false;
                    document.getElementById('filterTerlambat').checked = false;
                    document.getElementById('filterTidakHadir').checked = false;
                    document.getElementById('filterIzin').checked = false;
                    document.getElementById('filterCuti').checked = false;
                    document.getElementById('filterDinasLuar').checked = false;
                    document.getElementById('filterSakit').checked = false;
                    activeFilters = ['all'];
                    applyFilters();
                    filterDropdown.classList.remove('show');
                    const visibleCount = getFilteredRowsAbsensi().length + getFilteredRowsKetidakhadiran().length;
                    showNotification('Filter Direset', 'Menampilkan semua data', 'success');
                });

                // Date filter
                dateFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }

            function applyFilters() {
                // Reset to first page
                currentPageAbsensi = 1;
                currentPageKetidakhadiran = 1;

                // Get date filter value
                const dateFilterValue = document.getElementById('dateFilter').value;

                // Apply filters to absensi rows
                absensiRows.forEach(row => {
                    const status = row.getAttribute('data-status')?.toLowerCase() || '';
                    const nama = row.getAttribute('data-nama')?.toLowerCase() || '';
                    const tanggal = row.getAttribute('data-tanggal') || '';

                    // Check if status matches filter
                    let statusMatches = false;
                    if (activeFilters.includes('all')) {
                        statusMatches = true;
                    } else {
                        statusMatches = activeFilters.some(filter => {
                            if (filter === 'hadir' && status === 'tepat waktu') return true;
                            if (filter === 'terlambat' && status === 'terlambat') return true;
                            return status.includes(filter.toLowerCase());
                        });
                    }

                    // Check if date matches filter
                    let dateMatches = true;
                    if (dateFilterValue) {
                        dateMatches = tanggal === dateFilterValue;
                    }

                    // Check if search term matches
                    let searchMatches = true;
                    if (searchTerm) {
                        const searchLower = searchTerm.toLowerCase();
                        searchMatches = nama.includes(searchLower) || status.includes(searchLower);
                    }

                    if (statusMatches && dateMatches && searchMatches) {
                        row.classList.remove('hidden-by-filter');
                    } else {
                        row.classList.add('hidden-by-filter');
                    }
                });

                // Apply same filters to absensi cards
                absensiCards.forEach(card => {
                    const status = card.getAttribute('data-status')?.toLowerCase() || '';
                    const nama = card.getAttribute('data-nama')?.toLowerCase() || '';
                    const tanggal = card.getAttribute('data-tanggal') || '';

                    // Check if status matches filter
                    let statusMatches = false;
                    if (activeFilters.includes('all')) {
                        statusMatches = true;
                    } else {
                        statusMatches = activeFilters.some(filter => {
                            if (filter === 'hadir' && status === 'tepat waktu') return true;
                            if (filter === 'terlambat' && status === 'terlambat') return true;
                            return status.includes(filter.toLowerCase());
                        });
                    }

                    // Check if date matches filter
                    let dateMatches = true;
                    if (dateFilterValue) {
                        dateMatches = tanggal === dateFilterValue;
                    }

                    // Check if search term matches
                    let searchMatches = true;
                    if (searchTerm) {
                        const searchLower = searchTerm.toLowerCase();
                        searchMatches = nama.includes(searchLower) || status.includes(searchLower);
                    }

                    if (statusMatches && dateMatches && searchMatches) {
                        card.classList.remove('hidden-by-filter');
                    } else {
                        card.classList.add('hidden-by-filter');
                    }
                });

                // Apply filters to ketidakhadiran rows
                ketidakhadiranRows.forEach(row => {
                    const status = row.getAttribute('data-status')?.toLowerCase() || '';
                    const nama = row.getAttribute('data-nama')?.toLowerCase() || '';
                    const alasan = row.getAttribute('data-alasan')?.toLowerCase() || '';
                    const tanggal = row.getAttribute('data-tanggal') || '';

                    // Check if status matches filter
                    let statusMatches = false;
                    if (activeFilters.includes('all')) {
                        statusMatches = true;
                    } else {
                        statusMatches = activeFilters.some(filter => {
                            if (filter === 'izin' && (status === 'pending' || status === 'approved' || status === 'rejected')) return true;
                            if (filter === 'sakit' && alasan.includes('sakit')) return true;
                            if (filter === 'cuti' && alasan.includes('cuti')) return true;
                            if (filter === 'dinas luar' && alasan.includes('dinas')) return true;
                            if (filter === 'tidak hadir' && alasan.includes('tidak masuk')) return true;
                            return status.includes(filter.toLowerCase()) || alasan.includes(filter.toLowerCase());
                        });
                    }

                    // Check if date matches filter
                    let dateMatches = true;
                    if (dateFilterValue) {
                        dateMatches = tanggal === dateFilterValue;
                    }

                    // Check if search term matches
                    let searchMatches = true;
                    if (searchTerm) {
                        const searchLower = searchTerm.toLowerCase();
                        searchMatches = nama.includes(searchLower) || alasan.includes(searchLower) || status.includes(searchLower);
                    }

                    if (statusMatches && dateMatches && searchMatches) {
                        row.classList.remove('hidden-by-filter');
                    } else {
                        row.classList.add('hidden-by-filter');
                    }
                });

                // Apply same filters to ketidakhadiran cards
                ketidakhadiranCards.forEach(card => {
                    const status = card.getAttribute('data-status')?.toLowerCase() || '';
                    const nama = card.getAttribute('data-nama')?.toLowerCase() || '';
                    const alasan = card.getAttribute('data-alasan')?.toLowerCase() || '';
                    const tanggal = card.getAttribute('data-tanggal') || '';

                    // Check if status matches filter
                    let statusMatches = false;
                    if (activeFilters.includes('all')) {
                        statusMatches = true;
                    } else {
                        statusMatches = activeFilters.some(filter => {
                            if (filter === 'izin' && (status === 'pending' || status === 'approved' || status === 'rejected')) return true;
                            if (filter === 'sakit' && alasan.includes('sakit')) return true;
                            if (filter === 'cuti' && alasan.includes('cuti')) return true;
                            if (filter === 'dinas luar' && alasan.includes('dinas')) return true;
                            if (filter === 'tidak hadir' && alasan.includes('tidak masuk')) return true;
                            return status.includes(filter.toLowerCase()) || alasan.includes(filter.toLowerCase());
                        });
                    }

                    // Check if date matches filter
                    let dateMatches = true;
                    if (dateFilterValue) {
                        dateMatches = tanggal === dateFilterValue;
                    }

                    // Check if search term matches
                    let searchMatches = true;
                    if (searchTerm) {
                        const searchLower = searchTerm.toLowerCase();
                        searchMatches = nama.includes(searchLower) || alasan.includes(searchLower) || status.includes(searchLower);
                    }

                    if (statusMatches && dateMatches && searchMatches) {
                        card.classList.remove('hidden-by-filter');
                    } else {
                        card.classList.add('hidden-by-filter');
                    }
                });

                // Update pagination and visible items
                renderPaginationAbsensi();
                updateVisibleItemsAbsensi();
                renderPaginationKetidakhadiran();
                updateVisibleItemsKetidakhadiran();
            }

            // === SEARCH ===
            function initializeSearch() {
                const searchInput = document.getElementById('searchInput');
                let searchTimeout;

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchTerm = searchInput.value.trim();
                        applyFilters();
                    }, 300); // Debounce search
                });
            }

            // Event listeners untuk tombol edit cuti
            document.querySelectorAll('.edit-cuti-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = this.getAttribute('data-id');
                    // Fetch data dari server
                    try {
                        const response = await fetch(`/api/admin/absensi/cuti/${id}`);
                        const result = await response.json();
                        
                        if (result.success) {
                            const data = result.data;
                            document.getElementById('editCutiId').value = data.id;
                            document.getElementById('editCutiNamaKaryawan').value = data.user_id;
                            document.getElementById('editCutiJenisCuti').value = data.jenis_ketidakhadiran;
                            document.getElementById('editCutiTanggalMulai').value = data.tanggal;
                            document.getElementById('editCutiTanggalAkhir').value = data.tanggal_akhir || data.tanggal;
                            document.getElementById('editCutiAlasan').value = data.keterangan || '';
                            
                            const editStatus = document.getElementById('editCutiStatus');
                            editStatus.value = data.approval_status;

                            // Tampilkan atau sembunyikan field alasan penolakan
                            const editRejectionWrapper = document.getElementById('editRejectionReasonWrapper');
                            if (data.approval_status === 'rejected') {
                                editRejectionWrapper.classList.remove('hidden');
                                document.getElementById('editRejectionReason').value = data.rejection_reason || '';
                            } else {
                                editRejectionWrapper.classList.add('hidden');
                            }

                            openModal('editCutiModal');
                        } else {
                            showNotification('Gagal memuat data', 'error');
                        }
                    } catch (error) {
                        showNotification('Terjadi kesalahan', 'error');
                    }
                });
            });

            // Event listener untuk submit form edit cuti
            document.getElementById('editCutiForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const id = document.getElementById('editCutiId').value;
                if (!id) {
                    showNotification('ID cuti tidak ditemukan', 'error');
                    return;
                }

                const formData = new FormData(this);
                
                try {
                    const response = await fetch(`/api/admin/absensi/cuti/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Data berhasil diperbarui', 'success');
                        closeModal('editCutiModal');
                        location.reload();
                    } else {
                        showNotification(result.message || 'Gagal memperbarui data', 'error');
                    }
                } catch (error) {
                    showNotification('Terjadi kesalahan', 'error');
                }
            });

            // Event listeners untuk tombol verifikasi
            document.querySelectorAll('.verify-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.getElementById('verifyId').value = id;

                    // Reset form
                    document.getElementById('verifyStatus').value = 'approved';
                    document.getElementById('rejectionReason').value = '';
                    document.getElementById('rejectionReasonContainer').style.display = 'none';

                    openModal('verifyModal');
                });
            });

            // Event listener untuk submit form verifikasi
            document.getElementById('verifyForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const id = document.getElementById('verifyId').value;
                if (!id) {
                    showNotification('ID verifikasi tidak ditemukan', 'error');
                    return;
                }

                const status = document.getElementById('verifyStatus').value;
                const rejectionReason = document.getElementById('rejectionReason').value;

                // Buat FormData manual untuk mengontrol field yang dikirim
                const formData = new FormData();
                formData.append('approval_status', status);

                // Hanya tambahkan rejection_reason jika status adalah "rejected"
                if (status === 'rejected') {
                    if (!rejectionReason || rejectionReason.trim() === '') {
                        showNotification('Alasan penolakan harus diisi', 'error');
                        return;
                    }
                    formData.append('rejection_reason', rejectionReason.trim());
                }

                try {
                    const response = await fetch(`/api/admin/absensi/${id}/verify`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Data berhasil diverifikasi', 'success');
                        closeModal('verifyModal');
                        location.reload();
                    } else {
                        showNotification(result.message || 'Gagal memverifikasi data', 'error');
                    }
                } catch (error) {
                    showNotification('Terjadi kesalahan', 'error');
                }
            });

            // Event listeners untuk tombol delete
            document.querySelectorAll('.delete-cuti-btn, .delete-absensi-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const type = this.classList.contains('delete-absensi-btn') ? 'absensi' : 'cuti';

                    document.getElementById('deleteId').value = id;
                    document.getElementById('deleteType').value = type;

                    openModal('deleteModal');
                });
            });

            // Event listener untuk tombol konfirmasi hapus
            document.getElementById('confirmDeleteBtn')?.addEventListener('click', async function() {
                const id = document.getElementById('deleteId').value;
                const type = document.getElementById('deleteType').value;
                if (!id) {
                    showNotification('ID hapus tidak ditemukan', 'error');
                    return;
                }

                try {
                    const response = await fetch(`/api/admin/absensi/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Data berhasil dihapus', 'success');
                        closeModal('deleteModal');
                        location.reload();
                    } else {
                        showNotification(result.message || 'Gagal menghapus data', 'error');
                    }
                } catch (error) {
                    showNotification('Terjadi kesalahan', 'error');
                }
            });

            // Function to switch between tabs
            window.switchTab = function(tabName) {
                // Get tab buttons and panels
                const absensiTab = document.getElementById('absensiTab');
                const ketidakhadiranTab = document.getElementById('ketidakhadiranTab');
                const absensiPanel = document.getElementById('absensiPanel');
                const ketidakhadiranPanel = document.getElementById('ketidakhadiranPanel');

                // Hide all panels and remove active class from all tabs
                absensiPanel.classList.add('hidden');
                ketidakhadiranPanel.classList.add('hidden');
                absensiTab.classList.remove('active');
                ketidakhadiranTab.classList.remove('active');

                // Show selected panel and add active class to clicked tab
                if (tabName === 'absensi') {
                    absensiPanel.classList.remove('hidden');
                    absensiTab.classList.add('active');
                } else if (tabName === 'ketidakhadiran') {
                    ketidakhadiranPanel.classList.remove('hidden');
                    ketidakhadiranTab.classList.add('active');
                }
            }
        });
    </script>
</body>

</html>
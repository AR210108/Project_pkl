<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        success: "#10b981",
                        warning: "#f59e0b",
                        danger: "#ef4444"
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    }
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
        
        /* Fix untuk layout sidebar */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 256px;
            flex-shrink: 0;
            position: fixed;
            height: 100vh;
            z-index: 40;
            overflow-y: auto;
        }
        
        .main-content {
            flex: 1;
            margin-left: 256px;
            width: calc(100% - 256px);
            min-height: 100vh;
            overflow-y: auto;
        }
        
        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .main-wrapper {
                flex-direction: column;
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
        
        .status-cuti {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
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
        
        .scrollable-table-container {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
        }
        
        .data-table {
            width: 100%;
            min-width: 1200px;
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
        
        .desktop-pagination {
            display: none;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }
        
        @media (min-width: 768px) {
            .desktop-pagination {
                display: flex;
            }
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
        
        .mobile-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
        }
        
        @media (min-width: 768px) {
            .mobile-pagination {
                display: none;
            }
        }
        
        .mobile-page-btn {
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
        
        .mobile-page-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .mobile-page-btn:not(.active) {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        .mobile-page-btn:not(.active):hover {
            background-color: #e2e8f0;
        }
        
        .mobile-nav-btn {
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
        
        .mobile-nav-btn:hover:not(:disabled) {
            background-color: #e2e8f0;
        }
        
        .mobile-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .modal {
            backdrop-filter: blur(5px);
        }
        
        .notification {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    <div class="main-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            @include('admin/templet/sider')
        </div>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="p-4 md:p-6 lg:p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-0">Kelola Absensi</h2>
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
                                <p class="text-xl md:text-2xl font-bold text-green-600">{{ $stats['total_tepat_waktu'] + $stats['total_terlambat'] }}</p>
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
                                <p class="text-xl md:text-2xl font-bold text-purple-600">{{ $stats['total_dinas_luar'] ?? 0 }}</p>
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
                                <p class="text-xl md:text-2xl font-bold text-orange-600">{{ $stats['total_sakit'] ?? 0 }}</p>
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
                
                <!-- Data Absensi Panel -->
                <div id="absensiPanel" class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">fact_check</span>
                            Data Absensi
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Total: <span class="font-semibold text-gray-800">{{ count($attendances) }}</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="scrollable-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 60px;">No</th>
                                        <th style="min-width: 200px;">Nama</th>
                                        <th style="min-width: 120px;">Tanggal</th>
                                        <th style="min-width: 120px;">Jam Masuk</th>
                                        <th style="min-width: 120px;">Jam Keluar</th>
                                        <th style="min-width: 120px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $index = 0;
                                    @endphp
                                    @forelse ($attendances as $attendance)
                                        @if(!in_array($attendance->status, ['Sakit', 'Cuti', 'Izin', 'Tidak Masuk', 'Dinas Luar']))
                                            @php
                                            $index++;
                                            @endphp
                                            <tr>
                                                <td style="min-width: 60px;">{{ $index }}</td>
                                                <td style="min-width: 200px;">{{ $attendance->user->name }}</td>
                                                <td style="min-width: 120px;">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                                <td style="min-width: 120px;">{{ $attendance->jam_masuk ? \Carbon\Carbon::parse($attendance->jam_masuk)->format('H:i') : '-' }}</td>
                                                <td style="min-width: 120px;">{{ $attendance->jam_pulang ? \Carbon\Carbon::parse($attendance->jam_pulang)->format('H:i') : '-' }}</td>
                                                <td style="min-width: 120px;">
                                                    <span class="status-badge 
                                                        @if($attendance->status == 'Tepat Waktu') status-hadir
                                                        @elseif($attendance->status == 'Terlambat') status-terlambat
                                                        @else status-hadir
                                                        @endif">
                                                        {{ $attendance->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data absensi.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
                            <span class="text-sm text-gray-500">Total: <span class="font-semibold text-gray-800">{{ count($ketidakhadiran) + count($attendances->whereIn('status', ['Sakit', 'Cuti', 'Izin', 'Dinas Luar'])) }}</span> data</span>
                        </div>
                    </div>
                    <div class="panel-body">
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
                                <tbody>
                                    @php
                                    $index = 0;
                                    @endphp
                                    <!-- Data dari tabel ketidakhadiran (tanpa status Tidak Masuk) -->
                                    @forelse ($ketidakhadiran as $item)
                                        @if($item->status !== 'Tidak Masuk')
                                            @php
                                            $index++;
                                            @endphp
                                            <tr>
                                                <td style="min-width: 60px;">{{ $index }}</td>
                                                <td style="min-width: 200px;">{{ $item->user->name }}</td>
                                                <td style="min-width: 120px;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                                <td style="min-width: 120px;">{{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d/m/Y') : \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                                <td style="min-width: 200px;">
                                                    @if($item->status === 'Cuti')
                                                        {{ $item->jenis_cuti }} - {{ $item->alasan_cuti }}
                                                    @else
                                                        {{ $item->status }} - {{ $item->reason }}
                                                    @endif
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <span class="status-badge 
                                                        @if($item->approval_status == 'approved') status-hadir
                                                        @elseif($item->approval_status == 'rejected') status-terlambat
                                                        @else status-izin
                                                        @endif">
                                                        {{ strtoupper($item->approval_status) }}
                                                    </span>
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <div class="flex justify-center space-x-2">
                                                        <button class="edit-cuti-btn text-gray-600 hover:text-gray-800" data-id="{{ $item->id }}" title="Edit">
                                                            <span class="material-icons-outlined text-sm">edit</span>
                                                        </button>
                                                        @if($item->approval_status == 'pending')
                                                        <button class="verify-btn text-gray-600 hover:text-gray-800" data-id="{{ $item->id }}" data-type="{{ $item->status }}" title="Verifikasi">
                                                            <span class="material-icons-outlined text-sm">check_circle</span>
                                                        </button>
                                                        @endif
                                                        <button class="delete-cuti-btn text-gray-600 hover:text-gray-800" data-id="{{ $item->id }}" title="Hapus">
                                                            <span class="material-icons-outlined text-sm">delete</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                    @endphp
                                    @endforelse
                                    
                                    <!-- Data dari tabel absensi dengan status Sakit, Cuti, Izin, atau Dinas Luar -->
                                    @forelse ($attendances as $attendance)
                                        @if(in_array($attendance->status, ['Sakit', 'Cuti', 'Izin', 'Dinas Luar']))
                                            @php
                                            $index++;
                                            @endphp
                                            <tr>
                                                <td style="min-width: 60px;">{{ $index }}</td>
                                                <td style="min-width: 200px;">{{ $attendance->user->name }}</td>
                                                <td style="min-width: 120px;">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                                <td style="min-width: 120px;">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                                <td style="min-width: 200px;">{{ $attendance->status }}</td>
                                                <td style="min-width: 120px;">
                                                    <span class="status-badge 
                                                        @if($attendance->status == 'Sakit') status-izin
                                                        @elseif($attendance->status == 'Cuti') status-cuti
                                                        @elseif($attendance->status == 'Izin') status-izin
                                                        @else status-izin
                                                        @endif">
                                                        {{ $attendance->status }}
                                                    </span>
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <div class="flex justify-center space-x-2">
                                                        <button class="edit-absensi-btn text-gray-600 hover:text-gray-800" data-id="{{ $attendance->id }}" title="Edit">
                                                            <span class="material-icons-outlined text-sm">edit</span>
                                                        </button>
                                                        <button class="delete-absensi-btn text-gray-600 hover:text-gray-800" data-id="{{ $attendance->id }}" title="Hapus">
                                                            <span class="material-icons-outlined text-sm">delete</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                    @endphp
                                    @endforelse
                                    
                                    @if($index == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data ketidakhadiran.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white border-t border-gray-200 px-4 md:px-8 py-4 text-center">
                <p class="text-sm text-gray-500">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
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
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jenis Cuti/Izin</label>
                        <select id="editCutiJenisCuti" name="jenis_cuti" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                            <option value="">Pilih jenis cuti</option>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Dinas Luar">Dinas Luar</option>
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
                        <textarea id="editCutiAlasan" name="alasan_cuti" rows="3" class="w-full bg-gray-100 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary" placeholder="Masukkan alasan cuti"></textarea>
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
            // Helper untuk menampilkan notifikasi
            function showNotification(message, type = 'success') {
                const container = document.getElementById('notificationContainer');
                const notification = document.createElement('div');
                
                // Set icon dan warna berdasarkan tipe
                let icon, bgColor;
                switch(type) {
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

            // Fungsi untuk memuat ulang halaman
            function reloadPage() {
                window.location.reload();
            }

            // Event listeners untuk tombol edit cuti
            document.querySelectorAll('.edit-cuti-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = this.getAttribute('data-id');
                    try {
                        const response = await fetch(`/api/absensi/${id}`);
                        const result = await response.json();
                        if (result.success) {
                            const data = result.data;
                            document.getElementById('editCutiId').value = data.id;
                            document.getElementById('editCutiNamaKaryawan').value = data.user_id;
                            document.getElementById('editCutiJenisCuti').value = data.jenis_cuti;
                            document.getElementById('editCutiTanggalMulai').value = data.tanggal;
                            document.getElementById('editCutiTanggalAkhir').value = data.tanggal_akhir;
                            document.getElementById('editCutiAlasan').value = data.alasan_cuti;
                            
                            const editStatus = document.getElementById('editCutiStatus');
                            editStatus.value = data.approval_status;
                            
                            // Tampilkan atau sembunyikan field alasan penolakan
                            const editRejectionWrapper = document.getElementById('editRejectionReasonWrapper');
                            if(data.approval_status === 'rejected') {
                                editRejectionWrapper.classList.remove('hidden');
                                document.getElementById('editRejectionReason').value = data.rejection_reason || '';
                            } else {
                                editRejectionWrapper.classList.add('hidden');
                            }
                            
                            openModal('editCutiModal');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Gagal mengambil data', 'error');
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
                formData.append('_method', 'PUT');
                try {
                    const response = await fetch(`/api/absensi/${id}/cuti`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        showNotification(result.message);
                        closeModal('editCutiModal');
                        reloadPage();
                    } else {
                        showNotification(result.message || 'Gagal memperbarui data', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan pada server', 'error');
                }
            });

            // Event listeners untuk tombol verifikasi
            document.querySelectorAll('.verify-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const type = this.getAttribute('data-type');
                    
                    document.getElementById('verifyId').value = id;
                    document.getElementById('verifyType').value = type;
                    
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
                    const response = await fetch(`/api/absensi/${id}/verify`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        showNotification(result.message);
                        closeModal('verifyModal');
                        reloadPage();
                    } else {
                        showNotification(result.message || 'Gagal memverifikasi data', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan pada server', 'error');
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
                    const response = await fetch(`/api/absensi/${id}`, {
                        method: 'DELETE',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        showNotification(result.message);
                        closeModal('deleteModal');
                        reloadPage();
                    } else {
                        showNotification(result.message || 'Gagal menghapus data', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan pada server', 'error');
                }
            });

            // Event listeners untuk tombol edit absensi (untuk data yang dipindahkan ke tabel ketidakhadiran)
            document.querySelectorAll('.edit-absensi-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = this.getAttribute('data-id');
                    try {
                        const response = await fetch(`/api/absensi/${id}`);
                        const result = await response.json();
                        if (result.success) {
                            const data = result.data;
                            document.getElementById('editCutiId').value = data.id;
                            document.getElementById('editCutiNamaKaryawan').value = data.user_id;
                            
                            // Set jenis cuti berdasarkan status
                            const jenisCuti = document.getElementById('editCutiJenisCuti');
                            if (data.status === 'Sakit') {
                                jenisCuti.value = 'Sakit';
                            } else if (data.status === 'Cuti') {
                                jenisCuti.value = 'Cuti Tahunan';
                            } else if (data.status === 'Izin') {
                                jenisCuti.value = 'Izin';
                            } else if (data.status === 'Dinas Luar') {
                                jenisCuti.value = 'Dinas Luar';
                            }
                            
                            document.getElementById('editCutiTanggalMulai').value = data.tanggal;
                            document.getElementById('editCutiTanggalAkhir').value = data.tanggal;
                            document.getElementById('editCutiAlasan').value = data.status;
                            
                            // Set status persetujuan
                            const editStatus = document.getElementById('editCutiStatus');
                            editStatus.value = data.approval_status || 'pending';
                            
                            // Tampilkan atau sembunyikan field alasan penolakan
                            const editRejectionWrapper = document.getElementById('editRejectionReasonWrapper');
                            if(data.approval_status === 'rejected') {
                                editRejectionWrapper.classList.remove('hidden');
                                document.getElementById('editRejectionReason').value = data.rejection_reason || '';
                            } else {
                                editRejectionWrapper.classList.add('hidden');
                            }
                            
                            openModal('editCutiModal');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Gagal mengambil data', 'error');
                    }
                });
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
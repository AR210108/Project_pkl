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
                <div class="text-sm text-gray-500">
                    Periode: <span class="font-semibold text-gray-700">{{ $stats['periode'] }}</span>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ route('general_manager.kelola_absen') }}" class="flex flex-col md:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <label for="division_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Divisi</label>
                        <select name="division" id="division_filter" class="form-input w-full">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division }}" {{ request('division') == $division ? 'selected' : '' }}>{{ $division }}</option>
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
                        <div>
                            <p class="text-xs text-gray-500">Total Kehadiran</p>
                            <p class="text-xl font-bold text-green-600">{{ $stats['total_hadir'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-4 rounded-xl shadow-md">
                    <div class="flex items-center">
                        <div class="icon-container bg-red-100 mr-3">
                            <span class="material-icons-outlined text-red-600 text-xl">cancel</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tidak Hadir</p>
                            <p class="text-xl font-bold text-red-600">{{ $stats['total_tidak_hadir'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-4 rounded-xl shadow-md">
                    <div class="flex items-center">
                        <div class="icon-container bg-blue-100 mr-3">
                            <span class="material-icons-outlined text-blue-600 text-xl">event_available</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Izin</p>
                            <p class="text-xl font-bold text-blue-600">{{ $stats['total_izin'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-4 rounded-xl shadow-md">
                    <div class="flex items-center">
                        <div class="icon-container bg-yellow-100 mr-3">
                            <span class="material-icons-outlined text-yellow-600 text-xl">event_busy</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Cuti</p>
                            <p class="text-xl font-bold text-yellow-600">{{ $stats['total_cuti'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-4 rounded-xl shadow-md">
                    <div class="flex items-center">
                        <div class="icon-container bg-purple-100 mr-3">
                            <span class="material-icons-outlined text-purple-600 text-xl">directions_car</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Dinas Luar</p>
                            <p class="text-xl font-bold text-purple-600">{{ $stats['total_dinas_luar'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-4 rounded-xl shadow-md">
                    <div class="flex items-center">
                        <div class="icon-container bg-orange-100 mr-3">
                            <span class="material-icons-outlined text-orange-600 text-xl">healing</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Sakit</p>
                            <p class="text-xl font-bold text-orange-600">{{ $stats['total_sakit'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Data Absensi Bulanan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table min-w-full">
                        <thead>
                            <tr>
                                <th class="w-12">No</th>
                                <th class="min-w-[200px]">Nama</th>
                                <th class="min-w-[120px]">Tanggal</th>
                                <th class="min-w-[120px]">Jam Masuk</th>
                                <th class="min-w-[120px]">Jam Keluar</th>
                                <th class="min-w-[120px]">Status</th>
                                <th class="min-w-[200px]">Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allAbsensi->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-gray-500">
                                        Tidak ada data absensi untuk periode ini.
                                    </td>
                                </tr>
                            @else
                                @php $no = 1; @endphp
                                @foreach ($allAbsensi as $absensi)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $absensi->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td>{{ $absensi->jam_masuk ? substr($absensi->jam_masuk, 0, 5) : '-' }}</td>
                                        <td>{{ $absensi->jam_pulang ? substr($absensi->jam_pulang, 0, 5) : '-' }}</td>
                                        <td>
                                            @php
                                                $statusText = 'Tidak Masuk';
                                                $statusClass = 'status-tidak-masuk';
                                                if ($absensi->jam_masuk) {
                                                    $statusText = ($absensi->late_minutes > 0) ? 'Terlambat' : 'Hadir';
                                                    $statusClass = ($absensi->late_minutes > 0) ? 'status-terlambat' : 'status-hadir';
                                                } elseif ($absensi->jenis_ketidakhadiran) {
                                                    $statusText = ucfirst($absensi->jenis_ketidakhadiran);
                                                    $statusClass = 'status-' . str_replace('-', '_', $absensi->jenis_ketidakhadiran);
                                                }
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                            @if ($absensi->approval_status === 'pending')
                                                <span class="status-badge ml-1" style="background-color: rgba(245, 158, 11, 0.15); color: #92400e;">Menunggu Persetujuan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $alasan = '-';
                                                if ($absensi->jenis_ketidakhadiran === 'cuti') {
                                                    $alasan = $absensi->keterangan ?: 'Cuti';
                                                } elseif ($absensi->jenis_ketidakhadiran === 'sakit') {
                                                    $alasan = 'Sakit';
                                                } elseif ($absensi->jenis_ketidakhadiran === 'izin') {
                                                    $alasan = $absensi->reason ?: 'Izin';
                                                } elseif ($absensi->jenis_ketidakhadiran === 'dinas-luar') {
                                                    $alasan = $absensi->purpose ?: 'Dinas Luar';
                                                } elseif (!$absensi->jam_masuk && !$absensi->jenis_ketidakhadiran) {
                                                    $alasan = 'Tanpa Keterangan';
                                                }
                                            @endphp
                                            {{ $alasan }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
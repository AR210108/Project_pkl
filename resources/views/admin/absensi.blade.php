<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#7C3AED',
                        success: '#10B981',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        info: '#3B82F6',
                    },
                },
            },
        };
    </script>
    <style>
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn {
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: scale(1.05);
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
        /* Custom responsive styles */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="font-plus-jakarta bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="flex flex-col md:flex-row h-screen">
        <!-- Sidebar -->
        @include('admin/templet/sider')
        
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 lg:p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-0">Kelola Absensi</h2>
                </div>
                
                <!-- Statistics Cards -->
                <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                    <!-- Baris 1 -->
                    <div class="card bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="bg-blue-100 dark:bg-blue-900 h-12 w-12 md:h-16 md:w-16 rounded-xl mr-3 md:mr-4 flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 dark:text-blue-300 text-lg md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Total Kehadiran</p>
                                <p class="text-xl md:text-2xl font-bold">{{ $stats['total_tepat_waktu'] + $stats['total_terlambat'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="bg-red-100 dark:bg-red-900 h-12 w-12 md:h-16 md:w-16 rounded-xl mr-3 md:mr-4 flex items-center justify-center">
                                <i class="fas fa-user-times text-red-600 dark:text-red-300 text-lg md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Tidak Hadir</p>
                                <p class="text-xl md:text-2xl font-bold">{{ $stats['total_tidak_masuk'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 dark:bg-yellow-900 h-12 w-12 md:h-16 md:w-16 rounded-xl mr-3 md:mr-4 flex items-center justify-center">
                                <i class="fas fa-calendar-times text-yellow-600 dark:text-yellow-300 text-lg md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Izin</p>
                                <p class="text-xl md:text-2xl font-bold">{{ $stats['total_izin'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2 -->
                    <div class="card bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 h-12 w-12 md:h-16 md:w-16 rounded-xl mr-3 md:mr-4 flex items-center justify-center">
                                <i class="fas fa-umbrella-beach text-green-600 dark:text-green-300 text-lg md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Cuti</p>
                                <p class="text-xl md:text-2xl font-bold">{{ $stats['total_cuti'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="bg-purple-100 dark:bg-purple-900 h-12 w-12 md:h-16 md:w-16 rounded-xl mr-3 md:mr-4 flex items-center justify-center">
                                <i class="fas fa-briefcase text-purple-600 dark:text-purple-300 text-lg md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Dinas Luar</p>
                                <p class="text-xl md:text-2xl font-bold">{{ $stats['total_dinas_luar'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 dark:bg-indigo-900 h-12 w-12 md:h-16 md:w-16 rounded-xl mr-3 md:mr-4 flex items-center justify-center">
                                <i class="fas fa-thermometer text-indigo-600 dark:text-indigo-300 text-lg md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Sakit</p>
                                <p class="text-xl md:text-2xl font-bold">{{ $stats['total_sakit'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Table -->
                <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md mb-6 md:mb-8">
                    <h3 class="text-lg md:text-xl font-semibold mb-4">Absensi</h3>
                    <div class="table-container overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase">
                                <tr>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">No</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Nama</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Tanggal</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Jam Masuk</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Jam Keluar</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Status</th>
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
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $index }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4 font-medium">{{ $attendance->user->name }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $attendance->jam_masuk ? \Carbon\Carbon::parse($attendance->jam_masuk)->format('H:i') : '-' }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $attendance->jam_pulang ? \Carbon\Carbon::parse($attendance->jam_pulang)->format('H:i') : '-' }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">
                                                <span class="px-2 md:px-3 py-1 rounded-full text-xs font-medium
                                                    @if($attendance->status == 'Tepat Waktu') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status == 'Terlambat') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @endif">
                                                    {{ $attendance->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 md:px-6 py-2 md:py-4 text-center">Tidak ada data absensi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Leave/Absence Table -->
                <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-md">
                    <h3 class="text-lg md:text-xl font-semibold mb-4">Daftar Ketidakhadiran</h3>
                    <div class="table-container overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase">
                                <tr>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">No</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Nama</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Tanggal Mulai</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Tanggal Akhir</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Alasan</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-left">Status</th>
                                    <th class="px-4 md:px-6 py-2 md:py-3 text-center">Aksi</th>
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
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $index }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4 font-medium">{{ $item->user->name }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d/m/Y') : \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">
                                                @if($item->status === 'Cuti')
                                                    {{ $item->jenis_cuti }} - {{ $item->alasan_cuti }}
                                                @else
                                                    {{ $item->status }} - {{ $item->reason }}
                                                @endif
                                            </td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">
                                                <span class="px-2 md:px-3 py-1 rounded-full text-xs font-medium
                                                    @if($item->approval_status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($item->approval_status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @endif">
                                                    {{ strtoupper($item->approval_status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">
                                                <div class="flex justify-center space-x-2">
                                                    <button class="edit-cuti-btn text-blue-500 hover:text-blue-700" data-id="{{ $item->id }}" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($item->approval_status == 'pending')
                                                    <button class="verify-btn text-yellow-500 hover:text-yellow-700" data-id="{{ $item->id }}" data-type="{{ $item->status }}" title="Verifikasi">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                    @endif
                                                    <button class="delete-cuti-btn text-red-500 hover:text-red-700" data-id="{{ $item->id }}" title="Hapus">
                                                        <i class="fas fa-trash"></i>
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
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $index }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4 font-medium">{{ $attendance->user->name }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">{{ $attendance->status }}</td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">
                                                <span class="px-2 md:px-3 py-1 rounded-full text-xs font-medium
                                                    @if($attendance->status == 'Sakit') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                                    @elseif($attendance->status == 'Cuti') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status == 'Izin') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($attendance->status == 'Dinas Luar') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                    @endif">
                                                    {{ $attendance->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 md:px-6 py-2 md:py-4">
                                                <div class="flex justify-center space-x-2">
                                                    <button class="edit-absensi-btn text-blue-500 hover:text-blue-700" data-id="{{ $attendance->id }}" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="delete-absensi-btn text-red-500 hover:text-red-700" data-id="{{ $attendance->id }}" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                               
                                @endforelse
                                
                                @if($index == 0)
                                <tr>
                                    <td colspan="7" class="px-4 md:px-6 py-2 md:py-4 text-center">Tidak ada data ketidakhadiran.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-4 md:px-8 py-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- Edit Cuti Modal -->
    <div id="editCutiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Edit Cuti/Izin</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="editCutiForm" class="p-6">
                <input type="hidden" id="editCutiId" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Karyawan</label>
                        <select id="editCutiNamaKaryawan" name="user_id" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                            <option value="">Pilih karyawan</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jenis Cuti/Izin</label>
                        <select id="editCutiJenisCuti" name="jenis_cuti" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
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
                        <input type="date" id="editCutiTanggalMulai" name="tanggal" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
                        <input type="date" id="editCutiTanggalAkhir" name="tanggal_akhir" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Alasan</label>
                        <textarea id="editCutiAlasan" name="alasan_cuti" rows="3" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary" placeholder="Masukkan alasan cuti"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Status Persetujuan</label>
                        <select id="editCutiStatus" name="approval_status" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div id="editRejectionReasonWrapper" class="hidden">
                        <label class="block text-sm font-medium mb-2">Alasan Penolakan</label>
                        <textarea id="editRejectionReason" name="rejection_reason" rows="3" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="cancel-btn px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-lg">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Verify Modal -->
    <div id="verifyModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Verifikasi Pengajuan</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="verifyForm" class="p-6">
                <input type="hidden" id="verifyId">
                <input type="hidden" id="verifyType">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Status Persetujuan</label>
                    <select id="verifyStatus" name="approval_status" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary">
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                
                <div class="mb-6" id="rejectionReasonContainer" style="display: none;">
                    <label class="block text-sm font-medium mb-2">Alasan Penolakan</label>
                    <textarea id="rejectionReason" name="rejection_reason" rows="3" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary" placeholder="Masukkan alasan penolakan"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="cancel-btn px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-info text-white rounded-lg">
                        <i class="fas fa-check mr-2"></i>Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 dark:bg-red-900 h-16 w-16 rounded-full mr-4 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Apakah Anda yakin?</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>

                <input type="hidden" id="deleteId">
                <input type="hidden" id="deleteType">

                <div class="flex justify-end space-x-3">
                    <button class="cancel-btn px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Batal</button>
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-danger text-white rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Hapus
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
                        icon = 'fa-check-circle';
                        bgColor = 'bg-green-500';
                        break;
                    case 'error':
                        icon = 'fa-exclamation-circle';
                        bgColor = 'bg-red-500';
                        break;
                    case 'warning':
                        icon = 'fa-exclamation-triangle';
                        bgColor = 'bg-yellow-500';
                        break;
                    default:
                        icon = 'fa-info-circle';
                        bgColor = 'bg-blue-500';
                }
                
                notification.className = `notification ${bgColor} text-white p-4 rounded-lg shadow-lg mb-3 flex items-center`;
                notification.innerHTML = `
                    <i class="fas ${icon} mr-3"></i>
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
        });
    </script>
</body>
</html>
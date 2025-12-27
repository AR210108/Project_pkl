<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Karyawan - Dashboard</title>
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
        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Table styles */
        .order-table {
            transition: all 0.2s ease;
        }
        
        .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
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
        
        /* Modal styles */
        .modal {
            transition: opacity 0.25s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Status Badge Styles */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-intern {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .status-permanent {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        /* Custom styles untuk transisi */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Animasi hamburger */
        .hamburger-line {
            transition: all 0.3s ease-in-out;
        }
        
        .hamburger-active .line1 {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger-active .line2 {
            opacity: 0;
        }
        
        .hamburger-active .line3 {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Gaya untuk indikator aktif/hover */
        /* Default untuk mobile: di sebelah kanan */
        .nav-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background-color: #3b82f6;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        /* Override untuk desktop: di sebelah kiri */
        @media (min-width: 768px) {
            .nav-item::before {
                right: auto;
                left: 0;
                transform: translateX(-100%);
            }
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: translateX(0);
        }
        
        /* Memastikan sidebar tetap di posisinya saat scroll */
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }
        
        /* Menyesuaikan konten utama agar tidak tertutup sidebar */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px; /* Lebar sidebar */
            }
        }
        
        /* Scrollbar kustom untuk sidebar */
        .sidebar-fixed::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Table mobile adjustments */
        @media (max-width: 639px) {
            .desktop-table {
                display: none;
            }
            
            .mobile-cards {
                display: block;
            }
            
            /* Hide desktop pagination on mobile */
            .desktop-pagination {
                display: none !important;
            }
        }
        
        @media (min-width: 640px) {
            .desktop-table {
                display: block;
            }
            
            .mobile-cards {
                display: none;
            }
            
            /* Hide mobile pagination on desktop */
            .mobile-pagination {
                display: none !important;
            }
        }
        
        /* Form input styles */
        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        
        /* SIMPLIFIED SCROLLABLE TABLE */
        .scrollable-table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
        }
        
        /* Force scrollbar to be visible */
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
            min-width: 1600px; /* Fixed minimum width */
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
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <!-- PERUBAHAN: Asumsikan Anda memiliki file header ini -->
    @include('general_manajer.templet.header')
    
    <!-- Main Content Container -->
    <div class="main-content">
        <main class="flex-1 flex flex-col bg-background-light">
            <div class="flex-1 p-3 sm:p-8">

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Data Karyawan</h2>
                
                <!-- PERUBAHAN: Tambahkan notifikasi sukses -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <form method="GET" action="{{ route('pegawai.index') }}" class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Search..." type="text" />
                    </form>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <form method="GET" action="{{ route('pegawai.index') }}" class="flex items-center gap-2">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="divisi" class="px-3 py-2 bg-white border border-border-light text-text-muted-light rounded-lg form-input">
                                <option value="">Semua Divisi</option>
                                @foreach(
                                    \App\Models\Pegawai::select('divisi')->distinct()->orderBy('divisi')->pluck('divisi')
                                    as $d)
                                <option value="{{ $d }}" {{ request('divisi') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                            <select name="status" class="px-3 py-2 bg-white border border-border-light text-text-muted-light rounded-lg form-input">
                                <option value="">Semua Status</option>
                                <option value="Magang" {{ request('status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                                <option value="Karyawan Tetap" {{ request('status') == 'Karyawan Tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors">
                                Filter
                            </button>
                        </form>
                        <button id="tambahKaryawanBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-icons-outlined">add</span>
                            <span class="hidden sm:inline">Tambah Karyawan</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>
                
                <!-- Data Table Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">people</span>
                            Daftar Karyawan
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light">{{ $pegawai->total() }}</span> karyawan</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- SCROLLABLE TABLE -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container table-shadow">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 200px;">Nama Lengkap</th>
                                            <th style="min-width: 350px;">Alamat Lengkap</th>
                                            <th style="min-width: 120px;">Jenis Kelamin</th>
                                            <th style="min-width: 150px;">Nomor Telepon</th>
                                            <th style="min-width: 180px;">Jabatan</th>
                                            <th style="min-width: 150px;">Divisi</th>
                                            <th style="min-width: 140px;">Status Karyawan</th>
                                            <th style="min-width: 250px;">Email Address</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <!-- PERUBAHAN: Loop data dari database -->
                                    <tbody>
                                        @forelse ($pegawai as $index => $item)
                                        <tr>
                                            <td style="min-width: 60px;">{{ ($pegawai->currentPage()-1) * $pegawai->perPage() + $index + 1 }}</td>
                                            <td style="min-width: 200px;">{{ $item->nama }}</td>
                                            <td style="min-width: 350px;">{{ $item->alamat }}</td>
                                            <td style="min-width: 120px;">{{ $item->jenis_kelamin }}</td>
                                            <td style="min-width: 150px;">{{ $item->telp }}</td>
                                            <td style="min-width: 180px;">{{ $item->jabatan }}</td>
                                            <td style="min-width: 150px;">{{ $item->divisi }}</td>
                                            <td style="min-width: 140px;">
                                                <span class="status-badge {{ $item->status == 'Karyawan Tetap' ? 'status-permanent' : 'status-intern' }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td style="min-width: 250px;">{{ $item->email }}</td>
                                            <td style="min-width: 100px; text-align: center;">
                                                <div class="flex justify-center gap-2">
                                                    <button data-id="{{ $item->id }}" class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" title="Edit">
                                                        <span class="material-icons-outlined">edit</span>
                                                    </button>
                                                    <form method="POST" action="{{ route('pegawai.destroy', $item->id) }}" onsubmit="return confirm('Hapus karyawan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 rounded-full hover:bg-red-500/20 text-gray-700" title="Hapus">
                                                            <span class="material-icons-outlined">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4 text-gray-500">Belum ada data karyawan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4">
                            <!-- PERUBAHAN: Loop data dari database -->
                            @forelse ($pegawai as $item)
                            <div class="bg-white rounded-lg border border-border-light p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-base">{{ $item->nama }}</h4>
                                        <p class="text-sm text-text-muted-light">{{ $item->jabatan }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button data-id="{{ $item->id }}" class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700">
                                            <span class="material-icons-outlined">edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('pegawai.destroy', $item->id) }}" onsubmit="return confirm('Hapus karyawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-full hover:bg-red-500/20 text-gray-700">
                                                <span class="material-icons-outlined">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <p class="text-text-muted-light">Jenis Kelamin</p>
                                        <p class="font-medium">{{ $item->jenis_kelamin }}</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">No. Telp</p>
                                        <p class="font-medium">{{ $item->telp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Divisi</p>
                                        <p class="font-medium">{{ $item->divisi }}</p>
                                    </div>
                                    <div>
                                        <p class="text-text-muted-light">Status</p>
                                        <p><span class="status-badge {{ $item->status == 'Karyawan Tetap' ? 'status-permanent' : 'status-intern' }}">{{ $item->status }}</span></p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-text-muted-light">Email</p>
                                        <p class="font-medium">{{ $item->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="text-text-muted-light">Alamat</p>
                                    <p class="font-medium">{{ $item->alamat }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-gray-500">Belum ada data karyawan.</p>
                            @endforelse
                        </div>
                        <!-- Pagination Links -->
                        <div class="mt-4 desktop-pagination">
                            {{ $pegawai->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Tambah Karyawan -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Karyawan</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <!-- PERUBAHAN: Form action dan atribut name -->
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                        <input type="tel" name="telp" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <input type="text" name="jabatan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                        <input type="text" name="divisi" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Status</option>
                            <option value="Magang">Magang</option>
                            <option value="Karyawan Tetap">Karyawan Tetap</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Karyawan -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Karyawan</h3>
                    <button id="closeEditModal" class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" id="edit_nama" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" id="edit_alamat" rows="3" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit_jenis_kelamin" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                        <input type="tel" name="telp" id="edit_telp" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                        <input type="text" name="divisi" id="edit_divisi" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Status</option>
                            <option value="Magang">Magang</option>
                            <option value="Karyawan Tetap">Karyawan Tetap</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelEdit" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PERUBAHAN: JavaScript disederhanakan, hanya untuk modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const tambahModal = document.getElementById('tambahModal');
            const tambahKaryawanBtn = document.getElementById('tambahKaryawanBtn');
            const closeModals = document.querySelectorAll('.close-modal');
            
            // Show tambah modal
            tambahKaryawanBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
            });
            
            // Close modals
            closeModals.forEach(btn => {
                btn.addEventListener('click', function() {
                    tambahModal.classList.add('hidden');
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === tambahModal) {
                    tambahModal.classList.add('hidden');
                }
            });

            // Edit modal logic: fetch data and fill form
            const editModal = document.getElementById('editModal');
            const editForm = document.getElementById('editForm');
            const closeEditModalBtn = document.getElementById('closeEditModal');
            const cancelEdit = document.getElementById('cancelEdit');

            function openEditModal() {
                editModal.classList.remove('hidden');
            }

            function closeEditModal() {
                editModal.classList.add('hidden');
            }

            closeEditModalBtn.addEventListener('click', closeEditModal);
            cancelEdit.addEventListener('click', closeEditModal);

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    fetch(`{{ url('/data_karyawan') }}/${id}/edit`)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('edit_id').value = data.id;
                            document.getElementById('edit_nama').value = data.nama;
                            document.getElementById('edit_alamat').value = data.alamat;
                            document.getElementById('edit_jenis_kelamin').value = data.jenis_kelamin;
                            document.getElementById('edit_telp').value = data.telp;
                            document.getElementById('edit_jabatan').value = data.jabatan;
                            document.getElementById('edit_divisi').value = data.divisi;
                            document.getElementById('edit_status').value = data.status;
                            document.getElementById('edit_email').value = data.email;
                            editForm.action = `{{ url('/data_karyawan') }}/${id}`;
                            openEditModal();
                        }).catch(err => alert('Gagal mengambil data.'));
                });
            });

            // HAPUS SEMUA KODE YANG BERHUBUNGAN DENGAN:
            // - karyawanData array
            // - Pagination
            // - Fetch API
            // - renderDesktopTable, renderMobileCards
            // - Event listener submit form yang menggunakan fetch
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Task List Dashboard - Monochrome</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        black: "#09090b",
                        white: "#ffffff",
                        gray: {
                            50: "#f9fafb",
                            100: "#f3f4f6",
                            200: "#e5e7eb",
                            300: "#d1d5db",
                            400: "#9ca3af",
                            500: "#6b7280",
                            600: "#4b5563",
                            700: "#374151",
                            800: "#1f2937",
                            900: "#111827",
                        }
                    },
                    fontFamily: { sans: ["Inter", "sans-serif"] },
                    boxShadow: {
                        'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                        'modal': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -5px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        };
    </script>

    <style>
        /* Base Styles */
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f9fafb; 
            color: #111827; 
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Layout Fix */
        .container-wrapper {
            min-height: 100vh;
            display: flex;
            width: 100%;
        }
        
        .main-content {
            flex: 1;
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px;
            }
        }

        /* Monochrome Components */
        .mono-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .mono-card:hover { 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); 
            border-color: #d1d5db; 
        }

        /* Buttons */
        .btn-mono-primary {
            background-color: #18181b; 
            color: white;
            transition: all 0.2s;
        }
        .btn-mono-primary:hover { 
            background-color: #27272a; 
            transform: translateY(-1px); 
        }

        .btn-mono-secondary {
            background-color: white; 
            color: #374151; 
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        .btn-mono-secondary:hover { 
            background-color: #f3f4f6; 
            border-color: #d1d5db; 
        }

        .btn-icon { 
            padding: 0.5rem; 
            border-radius: 0.5rem; 
            color: #6b7280; 
            transition: all 0.2s; 
        }
        .btn-icon:hover { 
            background-color: #f3f4f6; 
            color: #18181b; 
        }

        /* Status Badges (Monochrome) */
        .badge { 
            padding: 0.25rem 0.75rem; 
            border-radius: 9999px; 
            font-size: 0.75rem; 
            font-weight: 600; 
            border: 1px solid transparent; 
        }
        .badge-pending { 
            background-color: #f3f4f6; 
            color: #374151; 
            border-color: #e5e7eb; 
        }
        .badge-process { 
            background-color: #ffffff; 
            color: #18181b; 
            border-color: #18181b; 
        }
        .badge-done { 
            background-color: #18181b; 
            color: white; 
        }

        /* Table */
        .mono-table-container { 
            overflow-x: auto; 
            border: 1px solid #e5e7eb; 
            border-radius: 0.5rem; 
            background: white;
        }
        .mono-table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            min-width: 800px;
        }
        .mono-table th { 
            background-color: #f9fafb; 
            text-align: left; 
            padding: 0.75rem 1rem; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            color: #6b7280; 
            border-bottom: 1px solid #e5e7eb; 
            white-space: nowrap; 
        }
        .mono-table td { 
            padding: 1rem; 
            border-bottom: 1px solid #f3f4f6; 
            font-size: 0.875rem; 
            vertical-align: middle; 
        }
        .mono-table tr:last-child td { 
            border-bottom: none; 
        }
        .mono-table tr:hover td { 
            background-color: #f9fafb; 
        }

        /* Tabs */
        .tab-btn { 
            padding-bottom: 0.75rem; 
            border-bottom: 2px solid transparent; 
            color: #6b7280; 
            font-weight: 500; 
            cursor: pointer; 
            transition: all 0.2s; 
        }
        .tab-btn:hover { 
            color: #18181b; 
        }
        .tab-btn.active { 
            color: #18181b; 
            border-bottom-color: #18181b; 
        }

        /* Inputs */
        .mono-input {
            width: 100%; 
            background-color: #fff; 
            border: 1px solid #e5e7eb; 
            border-radius: 0.5rem;
            padding: 0.625rem 1rem; 
            transition: all 0.2s; 
            outline: none; 
            font-size: 0.875rem;
        }
        .mono-input:focus { 
            border-color: #18181b; 
            box-shadow: 0 0 0 2px rgba(24, 24, 27, 0.1); 
        }

        /* Animations */
        @keyframes slideDown { 
            from { opacity: 0; transform: translateY(-10px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .animate-slide { animation: slideDown 0.2s ease-out; }

        /* Minimal Toast */
        .toast {
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 9999;
            background: white; 
            padding: 1rem 1.5rem; 
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border-left: 4px solid #18181b;
            display: flex; 
            align-items: center; 
            gap: 0.75rem;
            transform: translateX(120%); 
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .toast.show { 
            transform: translateX(0); 
        }

        /* Utilities */
        .text-truncate { 
            max-width: 200px; 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        }
        .avatar-circle { 
            width: 32px; 
            height: 32px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: white; 
            background-color: #374151; 
        }
        
        /* Top Navigation */
        .top-nav {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 30;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Mobile Responsive */
        @media (max-width: 767px) {
            .main-content {
                margin-left: 0 !important;
            }
            
            .top-nav {
                padding: 0.75rem 1rem;
            }
            
            .mono-table-container {
                border-radius: 0.375rem;
            }
            
            .mono-table th,
            .mono-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="container-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            @include('manager_divisi/templet/sider')
        </div>

        <!-- Main Content Area -->
        <div class="main-content flex flex-col flex-1">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                        <span class="material-icons-outlined text-white text-sm">assignment</span>
                    </div>
                    <span class="font-bold text-lg tracking-tight">Task<span class="text-gray-400">Manager</span></span>
                </div>
                <div class="flex items-center gap-4">
                    <button class="btn-icon">
                        <span class="material-icons-outlined text-xl">notifications</span>
                    </button>
                    <div class="avatar-circle bg-gray-800 text-gray-100">MD</div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
                
                <!-- Header -->
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-black mb-2">Daftar Tugas</h1>
                    <p class="text-gray-500 text-sm sm:text-base">Kelola dan pantau tugas tim Anda dengan efisien</p>
                </div>

                <!-- Tabs -->
                <div class="flex gap-6 mb-6 border-b border-gray-200">
                    <button class="tab-btn active" data-tab="tugas-saya">Tugas Saya</button>
                    <button class="tab-btn" data-tab="tugas-gm">Dari GM</button>
                </div>

                <!-- Controls: Search & Filter -->
                <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                    <div class="relative w-full md:w-1/3">
                        <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                        <input type="text" id="searchInput" class="mono-input pl-10" placeholder="Cari tugas atau karyawan...">
                    </div>
                    <div class="flex gap-2">
                        <div class="relative">
                            <button id="filterBtn" class="btn-mono-secondary px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium">
                                <span class="material-icons-outlined text-lg">filter_list</span> Filter
                                <span id="filterCount" class="hidden bg-black text-white text-[10px] px-1.5 py-0.5 rounded-full">0</span>
                            </button>
                            <!-- Filter Dropdown -->
                            <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-card p-4 z-50 animate-slide">
                                <h4 class="text-xs font-bold text-gray-900 uppercase mb-3">Status</h4>
                                <div class="space-y-2 mb-4">
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" id="f_all" checked class="accent-black"> 
                                        Semua
                                    </label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" id="f_pending" class="accent-black"> 
                                        Pending
                                    </label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" id="f_proses" class="accent-black"> 
                                        Proses
                                    </label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" id="f_selesai" class="accent-black"> 
                                        Selesai
                                    </label>
                                </div>
                                <div class="flex gap-2 border-t border-gray-100 pt-3">
                                    <button id="resetFilter" class="flex-1 py-2 text-xs text-gray-600 hover:text-black font-medium">Reset</button>
                                    <button id="applyFilter" class="flex-1 py-2 text-xs bg-black text-white rounded hover:bg-gray-800 font-medium">Terapkan</button>
                                </div>
                            </div>
                        </div>
                        <button id="addTaskBtn" class="btn-mono-primary px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium shadow-sm">
                            <span class="material-icons-outlined text-lg">add</span> Buat Baru
                        </button>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="tab-content-container">
                    
                    <!-- SECTION: TUGAS SAYA -->
                    <div id="tugas-saya" class="tab-panel">
                        <div class="mono-card p-0 overflow-hidden">
                            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h3 class="font-semibold text-sm uppercase tracking-wide text-gray-500">Tugas Saya</h3>
                                <span class="text-xs font-bold bg-white border border-gray-200 px-2 py-1 rounded" id="countSaya">0</span>
                            </div>
                            
                            <div class="mono-table-container">
                                <table class="mono-table">
                                    <thead>
                                        <tr>
                                            <th class="w-12 pl-4">#</th>
                                            <th>Tugas</th>
                                            <th>Layanan</th>
                                            <th>Karyawan</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                            <th class="text-center w-28">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBodySaya">
                                        <!-- Rows akan diisi oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Empty State -->
                            <div id="emptyStateSaya" class="hidden p-8 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="material-icons-outlined text-gray-400">assignment</span>
                                </div>
                                <h3 class="font-bold text-gray-700 mb-2">Belum ada tugas</h3>
                                <p class="text-gray-500 text-sm mb-4">Mulai dengan membuat tugas pertama Anda</p>
                                <button id="addFirstTaskBtn" class="btn-mono-primary px-4 py-2 text-sm">
                                    <span class="material-icons-outlined text-sm mr-2">add</span>Buat Tugas Pertama
                                </button>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/30">
                                <span class="text-xs text-gray-500" id="pageInfoSaya">Hal 1 dari 1</span>
                                <div class="flex gap-1">
                                    <button id="prevSaya" class="btn-mono-secondary w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 disabled:opacity-50">
                                        <span class="material-icons-outlined text-sm">chevron_left</span>
                                    </button>
                                    <button id="nextSaya" class="btn-mono-secondary w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 disabled:opacity-50">
                                        <span class="material-icons-outlined text-sm">chevron_right</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: TUGAS GM -->
                    <div id="tugas-gm" class="tab-panel hidden">
                        <div class="mono-card p-0 overflow-hidden border-l-4 border-l-black">
                            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h3 class="font-semibold text-sm uppercase tracking-wide text-gray-500 flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">verified</span> Instruksi General Manager
                                </h3>
                                <span class="text-xs font-bold bg-black text-white px-2 py-1 rounded" id="countGM">0</span>
                            </div>
                            
                            <div class="mono-table-container">
                                <table class="mono-table">
                                    <thead>
                                        <tr>
                                            <th class="w-12 pl-4">#</th>
                                            <th>Tugas</th>
                                            <th>Layanan</th>
                                            <th>Pengirim</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                            <th class="text-center w-28">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBodyGM">
                                        <!-- Rows akan diisi oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Empty State GM -->
                            <div id="emptyStateGM" class="hidden p-8 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="material-icons-outlined text-gray-400">verified</span>
                                </div>
                                <h3 class="font-bold text-gray-700 mb-2">Tidak ada tugas dari GM</h3>
                                <p class="text-gray-500 text-sm">General Manager belum memberikan tugas baru</p>
                            </div>
                            
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/30">
                                <span class="text-xs text-gray-500" id="pageInfoGM">Hal 1 dari 1</span>
                                <div class="flex gap-1">
                                    <button id="prevGM" class="btn-mono-secondary w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 disabled:opacity-50">
                                        <span class="material-icons-outlined text-sm">chevron_left</span>
                                    </button>
                                    <button id="nextGM" class="btn-mono-secondary w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 disabled:opacity-50">
                                        <span class="material-icons-outlined text-sm">chevron_right</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Modal Form (Add/Edit) -->
    <div id="taskModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-modal w-full max-w-lg max-h-[90vh] overflow-y-auto animate-slide">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg" id="modalTitle">Buat Tugas Baru</h3>
                <button id="closeModalBtn" class="btn-icon hover:bg-gray-200">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form id="taskForm" class="p-6 space-y-4">
                <input type="hidden" id="taskId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" id="inputJudul" class="mono-input" required placeholder="Contoh: Desain UI Dashboard">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Layanan</label>
                        <select id="inputLayanan" class="mono-input">
                            <option value="">Pilih Layanan</option>
                            <option value="Web Development">Web Development</option>
                            <option value="Mobile App">Mobile App</option>
                            <option value="Backend">Backend</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Design">Design</option>
                            <option value="Support">Support</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan <span class="text-red-500">*</span></label>
                        <select id="inputKaryawan" class="mono-input" required>
                            <option value="">Pilih Karyawan</option>
                            <option value="Ahmad Rizki">Ahmad Rizki</option>
                            <option value="Siti Nurhaliza">Siti Nurhaliza</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Dewi Lestari">Dewi Lestari</option>
                            <option value="Rizki Pratama">Rizki Pratama</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span class="text-red-500">*</span></label>
                        <input type="date" id="inputDeadline" class="mono-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="inputStatus" class="mono-input">
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="inputDeskripsi" rows="3" class="mono-input" placeholder="Detail pekerjaan atau instruksi spesifik..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Lampiran</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer">
                        <span class="material-icons-outlined text-gray-400 text-3xl mb-2">cloud_upload</span>
                        <p class="text-sm text-gray-600 mb-1">Klik atau drop file di sini</p>
                        <p class="text-xs text-gray-500">Maksimal 10MB per file</p>
                        <input type="file" class="hidden" id="fileInput">
                    </div>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" id="cancelBtn" class="btn-mono-secondary px-4 py-2 rounded-lg text-sm font-medium">Batal</button>
                    <button type="submit" class="btn-mono-primary px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                        <span class="material-icons-outlined text-sm">save</span> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-modal w-full max-w-sm p-6 text-center animate-slide">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-icons-outlined text-red-600">delete_outline</span>
            </div>
            <h3 class="font-bold text-lg mb-1">Hapus Tugas?</h3>
            <p class="text-sm text-gray-500 mb-6">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-3">
                <button id="cancelDelete" class="btn-mono-secondary px-4 py-2 rounded-lg text-sm font-medium">Batal</button>
                <button id="confirmDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span class="material-icons-outlined text-gray-700" id="toastIcon">check_circle</span>
        <div>
            <div class="font-bold text-sm text-gray-900" id="toastTitle">Sukses</div>
            <div class="text-xs text-gray-500" id="toastMsg">Operasi selesai.</div>
        </div>
    </div>

    <!-- JavaScript Logic -->
    <script>
        // Data Source (Mocking Database)
        let tasksSaya = [
            { 
                id: 1, 
                judul: 'Desain UI/UX Dashboard', 
                layanan: 'Web Development', 
                karyawan: 'Ahmad Rizki', 
                deadline: '2023-12-15', 
                status: 'pending', 
                deskripsi: 'Membuat desain modern dan minimalis untuk dashboard admin baru' 
            },
            { 
                id: 2, 
                judul: 'API Integration Payment', 
                layanan: 'Mobile Development', 
                karyawan: 'Siti Nurhaliza', 
                deadline: '2023-12-20', 
                status: 'proses', 
                deskripsi: 'Integrasi Midtrans gateway untuk pembayaran dalam aplikasi' 
            },
            { 
                id: 3, 
                judul: 'Optimasi Database', 
                layanan: 'Backend Development', 
                karyawan: 'Budi Santoso', 
                deadline: '2023-12-10', 
                status: 'selesai', 
                deskripsi: 'Indexing dan query optimization untuk meningkatkan performa' 
            }
        ];

        let tasksGM = [
            { 
                id: 101, 
                judul: 'Laporan Kuartalan Q4', 
                layanan: 'Management', 
                karyawan: 'General Manager', 
                deadline: '2023-12-31', 
                status: 'pending', 
                deskripsi: 'Laporan lengkap performa divisi untuk kuartal ke-4' 
            },
            { 
                id: 102, 
                judul: 'Rapat Strategi 2024', 
                layanan: 'Corporate', 
                karyawan: 'General Manager', 
                deadline: '2024-01-15', 
                status: 'proses', 
                deskripsi: 'Persiapan materi presentasi untuk rapat strategi perusahaan' 
            },
            { 
                id: 103, 
                judul: 'Audit Internal', 
                layanan: 'Quality Control', 
                karyawan: 'General Manager', 
                deadline: '2023-12-28', 
                status: 'selesai', 
                deskripsi: 'Pengecekan SOP dan proses kerja divisi' 
            }
        ];

        // State
        let state = {
            activeTab: 'tugas-saya',
            sayaPage: 1,
            gmPage: 1,
            filter: ['all'],
            search: '',
            itemsPerPage: 10
        };

        // Elements
        const els = {
            tableSaya: document.getElementById('tableBodySaya'),
            tableGM: document.getElementById('tableBodyGM'),
            emptyStateSaya: document.getElementById('emptyStateSaya'),
            emptyStateGM: document.getElementById('emptyStateGM'),
            searchInput: document.getElementById('searchInput'),
            modal: document.getElementById('taskModal'),
            deleteModal: document.getElementById('deleteModal'),
            form: document.getElementById('taskForm'),
            toast: document.getElementById('toast'),
            filterBtn: document.getElementById('filterBtn'),
            filterDropdown: document.getElementById('filterDropdown')
        };

        // --- Helper Functions ---

        function getBadge(status) {
            const map = {
                'pending': '<span class="badge badge-pending">Pending</span>',
                'proses': '<span class="badge badge-process">Proses</span>',
                'selesai': '<span class="badge badge-done">Selesai</span>'
            };
            return map[status] || map['pending'];
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }

        function showToast(title, message, type = 'success') {
            const toast = els.toast;
            const icon = toast.querySelector('#toastIcon');
            const toastTitle = toast.querySelector('#toastTitle');
            const toastMsg = toast.querySelector('#toastMsg');
            
            toastTitle.textContent = title;
            toastMsg.textContent = message;
            
            // Set icon based on type
            if (type === 'error') {
                icon.textContent = 'error';
                toast.style.borderLeftColor = '#ef4444';
            } else if (type === 'warning') {
                icon.textContent = 'warning';
                toast.style.borderLeftColor = '#f59e0b';
            } else {
                icon.textContent = 'check_circle';
                toast.style.borderLeftColor = '#10b981';
            }
            
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function updateEmptyState(type, isEmpty) {
            const emptyState = type === 'saya' ? els.emptyStateSaya : els.emptyStateGM;
            const tableContainer = type === 'saya' ? els.tableSaya.parentElement.parentElement : els.tableGM.parentElement.parentElement;
            
            if (isEmpty) {
                emptyState.classList.remove('hidden');
                tableContainer.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                tableContainer.classList.remove('hidden');
            }
        }

        function renderTable(type) {
            const data = type === 'saya' ? tasksSaya : tasksGM;
            const tbody = type === 'saya' ? els.tableSaya : els.tableGM;
            const pageKey = type === 'saya' ? 'sayaPage' : 'gmPage';
            
            // Filtering
            let filtered = data.filter(item => {
                // Status filter
                const matchStatus = state.filter.includes('all') || state.filter.includes(item.status);
                
                // Search filter
                const term = state.search.toLowerCase();
                const matchSearch = !term || 
                    item.judul.toLowerCase().includes(term) || 
                    item.karyawan.toLowerCase().includes(term) ||
                    item.layanan.toLowerCase().includes(term) ||
                    item.deskripsi.toLowerCase().includes(term);
                
                return matchStatus && matchSearch;
            });

            // Update empty state
            updateEmptyState(type, filtered.length === 0);
            
            // Update count
            document.getElementById(`count${type === 'saya' ? 'Saya' : 'GM'}`).textContent = filtered.length;

            // Pagination
            const totalPages = Math.max(1, Math.ceil(filtered.length / state.itemsPerPage));
            const currentPage = Math.min(state[pageKey], totalPages);
            state[pageKey] = currentPage;
            
            const start = (currentPage - 1) * state.itemsPerPage;
            const end = start + state.itemsPerPage;
            const paginatedItems = filtered.slice(start, end);

            // Render Rows
            tbody.innerHTML = paginatedItems.map((item, index) => `
                <tr class="group border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-colors">
                    <td class="pl-4 text-gray-500 font-medium text-sm">${start + index + 1}</td>
                    <td>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-500 mt-1 flex-shrink-0">
                                <span class="material-icons-outlined text-sm">description</span>
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-sm text-gray-900 truncate" title="${item.judul}">${item.judul}</div>
                                <div class="text-xs text-gray-400 truncate max-w-[200px]" title="${item.deskripsi}">${item.deskripsi}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">${item.layanan}</span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full ${type === 'gm' ? 'bg-black' : 'bg-gray-700'} text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                ${item.karyawan.substring(0, 2).toUpperCase()}
                            </div>
                            <span class="text-sm text-gray-600 truncate max-w-[100px]" title="${item.karyawan}">${item.karyawan}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm font-medium text-gray-900">${formatDate(item.deadline)}</div>
                        <div class="text-xs text-gray-400">Deadline</div>
                    </td>
                    <td>${getBadge(item.status)}</td>
                    <td class="text-center">
                        <div class="flex justify-center gap-1">
                            ${type === 'saya' ? `
                                <button onclick="editTask(${item.id})" class="btn-icon w-8 h-8 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 transition-colors" title="Edit">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="confirmDelete(${item.id})" class="btn-icon w-8 h-8 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-colors" title="Hapus">
                                    <span class="material-icons-outlined text-sm">delete</span>
                                </button>
                            ` : `
                                <button onclick="viewTaskDetail(${item.id})" class="btn-icon w-8 h-8 flex items-center justify-center hover:bg-gray-200 transition-colors" title="Lihat Detail">
                                    <span class="material-icons-outlined text-sm">visibility</span>
                                </button>
                                <button onclick="updateStatusGM(${item.id})" class="btn-icon w-8 h-8 flex items-center justify-center hover:bg-green-50 hover:text-green-600 transition-colors" title="Update Status">
                                    <span class="material-icons-outlined text-sm">update</span>
                                </button>
                            `}
                        </div>
                    </td>
                </tr>
            `).join('');

            // Update pagination info
            document.getElementById(`pageInfo${type === 'saya' ? 'Saya' : 'GM'}`).textContent = 
                `Hal ${currentPage} dari ${totalPages} • ${filtered.length} tugas`;

            // Update pagination buttons
            const prevBtn = document.getElementById(`prev${type === 'saya' ? 'Saya' : 'GM'}`);
            const nextBtn = document.getElementById(`next${type === 'saya' ? 'Saya' : 'GM'}`);
            
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
        }

        function refreshTables() {
            renderTable('saya');
            renderTable('gm');
        }

        // --- Event Listeners ---

        // Tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
                
                btn.classList.add('active');
                const target = btn.dataset.tab;
                document.getElementById(target).classList.remove('hidden');
                state.activeTab = target;
                
                // Update last updated text
                document.getElementById('lastUpdated').textContent = 
                    `Terakhir update: ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}`;
            });
        });

        // Search
        let searchTimeout;
        els.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                state.search = e.target.value.trim();
                state.sayaPage = 1;
                state.gmPage = 1;
                refreshTables();
            }, 300);
        });

        // Filter Logic
        const filterCheckboxes = {
            all: document.getElementById('f_all'),
            pending: document.getElementById('f_pending'),
            proses: document.getElementById('f_proses'),
            selesai: document.getElementById('f_selesai')
        };

        function updateFilterCount() {
            const activeFilters = Object.keys(filterCheckboxes)
                .filter(key => key !== 'all' && filterCheckboxes[key].checked)
                .length;
            
            const filterCount = document.getElementById('filterCount');
            if (activeFilters > 0) {
                filterCount.textContent = activeFilters;
                filterCount.classList.remove('hidden');
            } else {
                filterCount.classList.add('hidden');
            }
        }

        // Toggle filter dropdown
        els.filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            els.filterDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!els.filterBtn.contains(e.target) && !els.filterDropdown.contains(e.target)) {
                els.filterDropdown.classList.add('hidden');
            }
        });

        // Reset filter
        document.getElementById('resetFilter').addEventListener('click', () => {
            filterCheckboxes.all.checked = true;
            filterCheckboxes.pending.checked = false;
            filterCheckboxes.proses.checked = false;
            filterCheckboxes.selesai.checked = false;
            
            state.filter = ['all'];
            updateFilterCount();
            state.sayaPage = 1;
            state.gmPage = 1;
            refreshTables();
            els.filterDropdown.classList.add('hidden');
            
            showToast('Filter Direset', 'Semua filter telah direset', 'info');
        });

        // Apply filter
        document.getElementById('applyFilter').addEventListener('click', () => {
            const filters = [];
            if (filterCheckboxes.pending.checked) filters.push('pending');
            if (filterCheckboxes.proses.checked) filters.push('proses');
            if (filterCheckboxes.selesai.checked) filters.push('selesai');
            
            if (filters.length === 0 || filterCheckboxes.all.checked) {
                state.filter = ['all'];
            } else {
                state.filter = filters;
            }
            
            updateFilterCount();
            state.sayaPage = 1;
            state.gmPage = 1;
            refreshTables();
            els.filterDropdown.classList.add('hidden');
            
            const filterText = state.filter.includes('all') ? 'Semua' : state.filter.join(', ');
            showToast('Filter Diterapkan', `Menampilkan: ${filterText}`, 'success');
        });

        // Handle "All" checkbox logic
        filterCheckboxes.all.addEventListener('change', function() {
            if (this.checked) {
                filterCheckboxes.pending.checked = false;
                filterCheckboxes.proses.checked = false;
                filterCheckboxes.selesai.checked = false;
            }
        });

        // Handle individual checkbox clicks
        ['pending', 'proses', 'selesai'].forEach(key => {
            filterCheckboxes[key].addEventListener('change', function() {
                if (this.checked) {
                    filterCheckboxes.all.checked = false;
                }
            });
        });

        // Pagination
        document.getElementById('prevSaya').addEventListener('click', () => {
            if (state.sayaPage > 1) {
                state.sayaPage--;
                renderTable('saya');
            }
        });

        document.getElementById('nextSaya').addEventListener('click', () => {
            if (state.sayaPage < Math.ceil(tasksSaya.length / state.itemsPerPage)) {
                state.sayaPage++;
                renderTable('saya');
            }
        });

        document.getElementById('prevGM').addEventListener('click', () => {
            if (state.gmPage > 1) {
                state.gmPage--;
                renderTable('gm');
            }
        });

        document.getElementById('nextGM').addEventListener('click', () => {
            if (state.gmPage < Math.ceil(tasksGM.length / state.itemsPerPage)) {
                state.gmPage++;
                renderTable('gm');
            }
        });

        // Modal Actions
        document.getElementById('addTaskBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').textContent = 'Buat Tugas Baru';
            document.getElementById('taskId').value = '';
            els.form.reset();
            
            // Set default deadline (tomorrow)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('inputDeadline').valueAsDate = tomorrow;
            
            els.modal.classList.remove('hidden');
        });

        document.getElementById('addFirstTaskBtn').addEventListener('click', () => {
            document.getElementById('addTaskBtn').click();
        });

        window.editTask = function(id) {
            const task = tasksSaya.find(t => t.id === id);
            if (!task) {
                showToast('Error', 'Tugas tidak ditemukan', 'error');
                return;
            }
            
            document.getElementById('modalTitle').textContent = 'Edit Tugas';
            document.getElementById('taskId').value = task.id;
            document.getElementById('inputJudul').value = task.judul;
            document.getElementById('inputLayanan').value = task.layanan;
            document.getElementById('inputKaryawan').value = task.karyawan;
            document.getElementById('inputDeadline').value = task.deadline;
            document.getElementById('inputStatus').value = task.status;
            document.getElementById('inputDeskripsi').value = task.deskripsi;
            
            els.modal.classList.remove('hidden');
        };

        window.confirmDelete = function(id) {
            const task = tasksSaya.find(t => t.id === id);
            if (!task) {
                showToast('Error', 'Tugas tidak ditemukan', 'error');
                return;
            }
            
            document.getElementById('confirmDelete').dataset.id = id;
            els.deleteModal.classList.remove('hidden');
        };

        window.viewTaskDetail = function(id) {
            const task = tasksGM.find(t => t.id === id);
            if (task) {
                showToast('Detail Tugas', `"${task.judul}" dari ${task.karyawan}`, 'info');
            }
        };

        window.updateStatusGM = function(id) {
            const task = tasksGM.find(t => t.id === id);
            if (task) {
                const newStatus = task.status === 'pending' ? 'proses' : 
                                task.status === 'proses' ? 'selesai' : 'pending';
                task.status = newStatus;
                refreshTables();
                showToast('Status Diperbarui', `Tugas "${task.judul}" sekarang ${newStatus}`, 'success');
            }
        };

        document.getElementById('confirmDelete').addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            const taskIndex = tasksSaya.findIndex(t => t.id === id);
            
            if (taskIndex !== -1) {
                const deletedTask = tasksSaya[taskIndex];
                tasksSaya.splice(taskIndex, 1);
                
                els.deleteModal.classList.add('hidden');
                refreshTables();
                showToast('Tugas Dihapus', `"${deletedTask.judul}" telah dihapus`, 'success');
            }
        });

        // Close Modals
        [document.getElementById('closeModalBtn'), document.getElementById('cancelBtn')].forEach(btn => {
            btn.addEventListener('click', () => els.modal.classList.add('hidden'));
        });

        document.getElementById('cancelDelete').addEventListener('click', () => {
            els.deleteModal.classList.add('hidden');
        });

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === els.modal) els.modal.classList.add('hidden');
            if (e.target === els.deleteModal) els.deleteModal.classList.add('hidden');
        });

        // Form Submit
        els.form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const id = document.getElementById('taskId').value;
            const judul = document.getElementById('inputJudul').value.trim();
            const karyawan = document.getElementById('inputKaryawan').value;
            
            if (!judul || !karyawan) {
                showToast('Error', 'Judul dan Karyawan wajib diisi', 'error');
                return;
            }
            
            const newTask = {
                id: id ? parseInt(id) : Date.now(),
                judul: judul,
                layanan: document.getElementById('inputLayanan').value,
                karyawan: karyawan,
                deadline: document.getElementById('inputDeadline').value,
                status: document.getElementById('inputStatus').value,
                deskripsi: document.getElementById('inputDeskripsi').value.trim()
            };

            if (id) {
                // Edit existing task
                const index = tasksSaya.findIndex(t => t.id == id);
                if (index !== -1) {
                    tasksSaya[index] = newTask;
                    showToast('Berhasil', 'Tugas berhasil diperbarui', 'success');
                }
            } else {
                // Add new task
                tasksSaya.unshift(newTask);
                showToast('Berhasil', 'Tugas baru berhasil dibuat', 'success');
            }
            
            els.modal.classList.add('hidden');
            refreshTables();
            
            // Switch to "Tugas Saya" tab if not already
            if (state.activeTab !== 'tugas-saya') {
                document.querySelector('[data-tab="tugas-saya"]').click();
            }
        });

        // File upload handling
        const fileInput = document.getElementById('fileInput');
        const fileUploadArea = fileInput.parentElement;
        
        fileUploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                fileUploadArea.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <span class="material-icons-outlined text-green-600">check_circle</span>
                        <span class="text-sm text-gray-900">${fileName}</span>
                    </div>
                `;
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            refreshTables();
            
            // Set current date in footer
            const now = new Date();
            document.getElementById('lastUpdated').textContent = 
                `Terakhir update: ${now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}`;
        });

    </script>
</body>
</html>
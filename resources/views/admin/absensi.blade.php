<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <!-- Tambahkan library Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#333333", // Using a dark gray as primary
                        "background-light": "#FFFFFF",
                        "background-dark": "#121212",
                        "surface-light": "#F3F4F6", // Lighter gray for backgrounds
                        "surface-dark": "#1F1F1F", // Darker gray for backgrounds
                        "text-light-primary": "#111827",
                        "text-dark-primary": "#F9FAFB",
                        "text-light-secondary": "#6B7280",
                        "text-dark-secondary": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151",
                        // Tambahkan warna untuk glass effect
                        "primary": "#6366f1", // indigo-500
                        "secondary": "#8b5cf6", // violet-500
                        "accent": "#ec4899", // pink-500
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-light": "#ffffff", // white
                        "surface-dark": "#1e293b", // slate-800
                        "text-light": "#0f172a", // slate-900
                        "text-dark": "#f1f5f9", // slate-100
                        "subtle-light": "#64748b", // slate-500
                        "subtle-dark": "#94a3b8", // slate-400
                    },
                    fontFamily: {
                        display: ["Plus Jakarta Sans", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                        "lg": "0.75rem", // 12px
                        "xl": "1rem", // 16px
                        "full": "9999px",
                    },
                },
            },
        };
    </script>
    <style>
        /* Tambahkan style untuk glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .dark .glass-effect {
            background: rgba(30, 41, 59, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(30, 41, 59, 0.18);
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light-primary dark:text-dark-primary">
    <div class="flex h-screen">
        <!-- Menggunakan template header -->
        @include('admin/templet/sider')
        
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-8 overflow-y-auto">
                <h2 class="text-3xl font-bold mb-8 text-text-light-primary dark:text-dark-primary">Kelola Absensi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-gray-200 dark:bg-gray-600 h-16 w-16 rounded-md mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-3xl text-gray-500 dark:text-gray-300">people</span>
                        </div>
                        <div>
                            <p class="text-sm text-text-light-secondary dark:text-dark-secondary">Total Kehadiran</p>
                            <p class="text-3xl font-bold text-text-light-primary dark:text-dark-primary">50</p>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-gray-200 dark:bg-gray-600 h-16 w-16 rounded-md mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-3xl text-gray-500 dark:text-gray-300">person_off</span>
                        </div>
                        <div>
                            <p class="text-sm text-text-light-secondary dark:text-dark-secondary">Tidak Hadir</p>
                            <p class="text-3xl font-bold text-text-light-primary dark:text-dark-primary">50</p>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-gray-200 dark:bg-gray-600 h-16 w-16 rounded-md mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-3xl text-gray-500 dark:text-gray-300">event_busy</span>
                        </div>
                        <div>
                            <p class="text-sm text-text-light-secondary dark:text-dark-secondary">Izin</p>
                            <p class="text-3xl font-bold text-text-light-primary dark:text-dark-primary">50</p>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-gray-200 dark:bg-gray-600 h-16 w-16 rounded-md mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-3xl text-gray-500 dark:text-gray-300">beach_access</span>
                        </div>
                        <div>
                            <p class="text-sm text-text-light-secondary dark:text-dark-secondary">Cuti</p>
                            <p class="text-3xl font-bold text-text-light-primary dark:text-dark-primary">50</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-text-light-primary dark:text-dark-primary">Absensi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-text-light-secondary dark:text-dark-secondary uppercase">
                                <tr>
                                    <th class="px-6 py-3" scope="col">NO</th>
                                    <th class="px-6 py-3" scope="col">NAMA</th>
                                    <th class="px-6 py-3" scope="col">TANGGAL</th>
                                    <th class="px-6 py-3" scope="col">JAM MASUK</th>
                                    <th class="px-6 py-3" scope="col">JAM KELUAR</th>
                                    <th class="px-6 py-3" scope="col">STATUS</th>
                                    <th class="px-6 py-3" scope="col">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">1.</td>
                                    <td class="px-6 py-4">John Doe</td>
                                    <td class="px-6 py-4">20/10/2025</td>
                                    <td class="px-6 py-4">09:00</td>
                                    <td class="px-6 py-4">17:00</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">VALID</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button class="edit-absensi-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-500" data-id="1">
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <button class="delete-absensi-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-red-500" data-id="1">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">2.</td>
                                    <td class="px-6 py-4">Jane Smith</td>
                                    <td class="px-6 py-4">20/10/2025</td>
                                    <td class="px-6 py-4">08:45</td>
                                    <td class="px-6 py-4">17:15</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">VALID</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button class="edit-absensi-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-500" data-id="2">
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <button class="delete-absensi-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-red-500" data-id="2">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-text-light-primary dark:text-dark-primary">Daftar Ketidakhadiran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-text-light-secondary dark:text-dark-secondary uppercase">
                                <tr>
                                    <th class="px-6 py-3" scope="col">NO</th>
                                    <th class="px-6 py-3" scope="col">NAMA</th>
                                    <th class="px-6 py-3" scope="col">TANGGAL MULAI</th>
                                    <th class="px-6 py-3" scope="col">TANGGAL AKHIR</th>
                                    <th class="px-6 py-3" scope="col">ALASAN</th>
                                    <th class="px-6 py-3" scope="col">STATUS</th>
                                    <th class="px-6 py-3" scope="col">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">1.</td>
                                    <td class="px-6 py-4">Michael Johnson</td>
                                    <td class="px-6 py-4">20/10/2025</td>
                                    <td class="px-6 py-4">25/10/2025</td>
                                    <td class="px-6 py-4">Cuti Tahunan</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">PENDING</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button class="edit-cuti-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-500" data-id="1">
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <button class="delete-cuti-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-red-500" data-id="1">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">2.</td>
                                    <td class="px-6 py-4">Sarah Williams</td>
                                    <td class="px-6 py-4">22/10/2025</td>
                                    <td class="px-6 py-4">24/10/2025</td>
                                    <td class="px-6 py-4">Cuti Sakit</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">APPROVED</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button class="edit-cuti-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-500" data-id="2">
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <button class="delete-cuti-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-red-500" data-id="2">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <footer class="bg-surface-light dark:bg-surface-dark border-t border-border-light dark:border-border-dark px-8 py-4 text-center">
                <p class="text-sm text-text-light-secondary dark:text-dark-secondary">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- Modal Tambah Absensi -->
    <div id="tambahAbsensiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Tambah Absensi Baru</h3>
                    <button id="closeTambahAbsensiModalBtn" class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="tambahAbsensiForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama Karyawan</label>
                        <select name="namaKaryawan" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih karyawan</option>
                            <option value="John Doe">John Doe</option>
                            <option value="Jane Smith">Jane Smith</option>
                            <option value="Michael Johnson">Michael Johnson</option>
                            <option value="Sarah Williams">Sarah Williams</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jam Masuk</label>
                        <input type="time" name="jamMasuk" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jam Keluar</label>
                        <input type="time" name="jamKeluar" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Status</label>
                        <select name="status" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih status</option>
                            <option value="VALID">VALID</option>
                            <option value="INVALID">INVALID</option>
                            <option value="LATE">LATE</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelTambahAbsensiBtn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Absensi -->
    <div id="editAbsensiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Edit Absensi</h3>
                    <button id="closeEditAbsensiModalBtn" class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="editAbsensiForm" class="p-6 space-y-6">
                <input type="hidden" id="editAbsensiId" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama Karyawan</label>
                        <select id="editNamaKaryawan" name="namaKaryawan" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih karyawan</option>
                            <option value="John Doe">John Doe</option>
                            <option value="Jane Smith">Jane Smith</option>
                            <option value="Michael Johnson">Michael Johnson</option>
                            <option value="Sarah Williams">Sarah Williams</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Tanggal</label>
                        <input type="date" id="editTanggal" name="tanggal" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jam Masuk</label>
                        <input type="time" id="editJamMasuk" name="jamMasuk" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jam Keluar</label>
                        <input type="time" id="editJamKeluar" name="jamKeluar" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Status</label>
                        <select id="editStatus" name="status" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih status</option>
                            <option value="VALID">VALID</option>
                            <option value="INVALID">INVALID</option>
                            <option value="LATE">LATE</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelEditAbsensiBtn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Cuti -->
    <div id="tambahCutiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Tambah Cuti Baru</h3>
                    <button id="closeTambahCutiModalBtn" class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="tambahCutiForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama Karyawan</label>
                        <select name="namaKaryawan" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih karyawan</option>
                            <option value="John Doe">John Doe</option>
                            <option value="Jane Smith">Jane Smith</option>
                            <option value="Michael Johnson">Michael Johnson</option>
                            <option value="Sarah Williams">Sarah Williams</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jenis Cuti</label>
                        <select name="jenisCuti" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih jenis cuti</option>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                            <option value="Cuti Penting">Cuti Penting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggalMulai" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Tanggal Akhir</label>
                        <input type="date" name="tanggalAkhir" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Status</label>
                        <select name="status" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih status</option>
                            <option value="PENDING">PENDING</option>
                            <option value="APPROVED">APPROVED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Alasan</label>
                    <textarea name="alasan" rows="3" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary" placeholder="Masukkan alasan cuti"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelTambahCutiBtn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Cuti -->
    <div id="editCutiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Edit Cuti</h3>
                    <button id="closeEditCutiModalBtn" class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="editCutiForm" class="p-6 space-y-6">
                <input type="hidden" id="editCutiId" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama Karyawan</label>
                        <select id="editCutiNamaKaryawan" name="namaKaryawan" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih karyawan</option>
                            <option value="John Doe">John Doe</option>
                            <option value="Jane Smith">Jane Smith</option>
                            <option value="Michael Johnson">Michael Johnson</option>
                            <option value="Sarah Williams">Sarah Williams</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jenis Cuti</label>
                        <select id="editCutiJenisCuti" name="jenisCuti" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih jenis cuti</option>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                            <option value="Cuti Penting">Cuti Penting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Tanggal Mulai</label>
                        <input type="date" id="editCutiTanggalMulai" name="tanggalMulai" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Tanggal Akhir</label>
                        <input type="date" id="editCutiTanggalAkhir" name="tanggalAkhir" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Status</label>
                        <select id="editCutiStatus" name="status" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
                            <option value="">Pilih status</option>
                            <option value="PENDING">PENDING</option>
                            <option value="APPROVED">APPROVED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Alasan</label>
                    <textarea id="editCutiAlasan" name="alasan" rows="3" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary" placeholder="Masukkan alasan cuti"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelEditCutiBtn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Konfirmasi -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Konfirmasi Hapus</h3>
                    <button id="closeDeleteModalBtn" class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <p class="text-text-light-primary dark:text-dark-primary mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>

                <input type="hidden" id="deleteId">
                <input type="hidden" id="deleteType">

                <div class="flex justify-end space-x-3">
                    <button id="cancelDeleteBtn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mendapatkan elemen-elemen yang diperlukan
        const tambahAbsensiBtn = document.getElementById('tambahAbsensiBtn');
        const tambahAbsensiModal = document.getElementById('tambahAbsensiModal');
        const closeTambahAbsensiModalBtn = document.getElementById('closeTambahAbsensiModalBtn');
        const cancelTambahAbsensiBtn = document.getElementById('cancelTambahAbsensiBtn');
        const tambahAbsensiForm = document.getElementById('tambahAbsensiForm');

        // Elemen untuk modal edit absensi
        const editAbsensiModal = document.getElementById('editAbsensiModal');
        const closeEditAbsensiModalBtn = document.getElementById('closeEditAbsensiModalBtn');
        const cancelEditAbsensiBtn = document.getElementById('cancelEditAbsensiBtn');
        const editAbsensiForm = document.getElementById('editAbsensiForm');

        // Elemen untuk modal tambah cuti
        const tambahCutiBtn = document.getElementById('tambahCutiBtn');
        const tambahCutiModal = document.getElementById('tambahCutiModal');
        const closeTambahCutiModalBtn = document.getElementById('closeTambahCutiModalBtn');
        const cancelTambahCutiBtn = document.getElementById('cancelTambahCutiBtn');
        const tambahCutiForm = document.getElementById('tambahCutiForm');

        // Elemen untuk modal edit cuti
        const editCutiModal = document.getElementById('editCutiModal');
        const closeEditCutiModalBtn = document.getElementById('closeEditCutiModalBtn');
        const cancelEditCutiBtn = document.getElementById('cancelEditCutiBtn');
        const editCutiForm = document.getElementById('editCutiForm');

        // Elemen untuk modal delete
        const deleteModal = document.getElementById('deleteModal');
        const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        // Fungsi untuk membuka modal tambah absensi
        function openTambahAbsensiModal() {
            tambahAbsensiModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal tambah absensi
        function closeTambahAbsensiModal() {
            tambahAbsensiModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            tambahAbsensiForm.reset();
        }

        // Fungsi untuk membuka modal edit absensi
        function openEditAbsensiModal(id) {
            // Di sini biasanya Anda akan mengambil data dari server berdasarkan ID
            // Untuk contoh, kita akan mengisi dengan data dummy
            document.getElementById('editAbsensiId').value = id;
            document.getElementById('editNamaKaryawan').value = 'John Doe';
            document.getElementById('editTanggal').value = '2025-10-20';
            document.getElementById('editJamMasuk').value = '09:00';
            document.getElementById('editJamKeluar').value = '17:00';
            document.getElementById('editStatus').value = 'VALID';

            editAbsensiModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal edit absensi
        function closeEditAbsensiModal() {
            editAbsensiModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            editAbsensiForm.reset();
        }

        // Fungsi untuk membuka modal tambah cuti
        function openTambahCutiModal() {
            tambahCutiModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal tambah cuti
        function closeTambahCutiModal() {
            tambahCutiModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            tambahCutiForm.reset();
        }

        // Fungsi untuk membuka modal edit cuti
        function openEditCutiModal(id) {
            // Di sini biasanya Anda akan mengambil data dari server berdasarkan ID
            // Untuk contoh, kita akan mengisi dengan data dummy
            document.getElementById('editCutiId').value = id;
            document.getElementById('editCutiNamaKaryawan').value = 'Michael Johnson';
            document.getElementById('editCutiJenisCuti').value = 'Cuti Tahunan';
            document.getElementById('editCutiTanggalMulai').value = '2025-10-20';
            document.getElementById('editCutiTanggalAkhir').value = '2025-10-25';
            document.getElementById('editCutiStatus').value = 'PENDING';
            document.getElementById('editCutiAlasan').value = 'Cuti tahunan untuk liburan keluarga';

            editCutiModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal edit cuti
        function closeEditCutiModal() {
            editCutiModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            editCutiForm.reset();
        }

        // Fungsi untuk membuka modal delete
        function openDeleteModal(id, type) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteType').value = type;
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal delete
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Event listener untuk membuka modal tambah absensi
        tambahAbsensiBtn.addEventListener('click', openTambahAbsensiModal);

        // Event listener untuk menutup modal tambah absensi
        closeTambahAbsensiModalBtn.addEventListener('click', closeTambahAbsensiModal);
        cancelTambahAbsensiBtn.addEventListener('click', closeTambahAbsensiModal);

        // Event listener untuk modal edit absensi
        closeEditAbsensiModalBtn.addEventListener('click', closeEditAbsensiModal);
        cancelEditAbsensiBtn.addEventListener('click', closeEditAbsensiModal);

        // Event listener untuk membuka modal tambah cuti
        tambahCutiBtn.addEventListener('click', openTambahCutiModal);

        // Event listener untuk menutup modal tambah cuti
        closeTambahCutiModalBtn.addEventListener('click', closeTambahCutiModal);
        cancelTambahCutiBtn.addEventListener('click', closeTambahCutiModal);

        // Event listener untuk modal edit cuti
        closeEditCutiModalBtn.addEventListener('click', closeEditCutiModal);
        cancelEditCutiBtn.addEventListener('click', closeEditCutiModal);

        // Event listener untuk modal delete
        closeDeleteModalBtn.addEventListener('click', closeDeleteModal);
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);

        // Menutup modal saat klik di luar area modal
        tambahAbsensiModal.addEventListener('click', function(event) {
            if (event.target === tambahAbsensiModal) {
                closeTambahAbsensiModal();
            }
        });

        editAbsensiModal.addEventListener('click', function(event) {
            if (event.target === editAbsensiModal) {
                closeEditAbsensiModal();
            }
        });

        tambahCutiModal.addEventListener('click', function(event) {
            if (event.target === tambahCutiModal) {
                closeTambahCutiModal();
            }
        });

        editCutiModal.addEventListener('click', function(event) {
            if (event.target === editCutiModal) {
                closeEditCutiModal();
            }
        });

        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Menangani submit form tambah absensi
        tambahAbsensiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Di sini Anda bisa menambahkan logika untuk menyimpan data absensi
            alert('Data absensi berhasil ditambahkan!');
            closeTambahAbsensiModal();
        });

        // Menangani submit form edit absensi
        editAbsensiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editAbsensiId').value;
            // Di sini Anda bisa menambahkan logika untuk update data absensi
            alert(`Data absensi dengan ID ${id} berhasil diperbarui!`);
            closeEditAbsensiModal();
        });

        // Menangani submit form tambah cuti
        tambahCutiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Di sini Anda bisa menambahkan logika untuk menyimpan data cuti
            alert('Data cuti berhasil ditambahkan!');
            closeTambahCutiModal();
        });

        // Menangani submit form edit cuti
        editCutiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editCutiId').value;
            // Di sini Anda bisa menambahkan logika untuk update data cuti
            alert(`Data cuti dengan ID ${id} berhasil diperbarui!`);
            closeEditCutiModal();
        });

        // Menangani tombol delete
        confirmDeleteBtn.addEventListener('click', function() {
            const id = document.getElementById('deleteId').value;
            const type = document.getElementById('deleteType').value;
            // Di sini Anda bisa menambahkan logika untuk menghapus data
            alert(`Data ${type} dengan ID ${id} berhasil dihapus!`);
            closeDeleteModal();
        });

        // Event listener untuk tombol edit absensi
        document.querySelectorAll('.edit-absensi-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditAbsensiModal(id);
            });
        });

        // Event listener untuk tombol delete absensi
        document.querySelectorAll('.delete-absensi-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openDeleteModal(id, 'absensi');
            });
        });

        // Event listener untuk tombol edit cuti
        document.querySelectorAll('.edit-cuti-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditCutiModal(id);
            });
        });

        // Event listener untuk tombol delete cuti
        document.querySelectorAll('.delete-cuti-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openDeleteModal(id, 'cuti');
            });
        });
    </script>

</body>

</html>
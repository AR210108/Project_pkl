<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Absensi (Manage Attendance) - List</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1d4ed8", // Using a blue shade as a sample primary
                        "background-light": "#f3f4f6",
                        "background-dark": "#111827",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    <style>
        .material-icons {
            font-size: 20px;
        }
        
        /* CSS untuk modal */
        .modal {
            transition: opacity 0.25s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">
    <div class="flex h-screen">
       @include('general_manajer/templet/header')
        <div class="flex-1 flex flex-col">
            <main class="flex-1 overflow-y-auto bg-white dark:bg-gray-900">
                <div class="p-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Kelola Absensi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg flex items-center gap-4">
                            <div class="bg-gray-300 dark:bg-gray-700 p-3 rounded">
                                <span class="material-icons text-gray-600 dark:text-gray-400">check_circle</span>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Total Kehadiran</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">50</p>
                            </div>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg flex items-center gap-4">
                            <div class="bg-gray-300 dark:bg-gray-700 p-3 rounded">
                                <span class="material-icons text-gray-600 dark:text-gray-400">cancel</span>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Tidak Hadir</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">50</p>
                            </div>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg flex items-center gap-4">
                            <div class="bg-gray-300 dark:bg-gray-700 p-3 rounded">
                                <span class="material-icons text-gray-600 dark:text-gray-400">error</span>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Izin</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">50</p>
                            </div>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg flex items-center gap-4">
                            <div class="bg-gray-300 dark:bg-gray-700 p-3 rounded">
                                <span class="material-icons text-gray-600 dark:text-gray-400">event_busy</span>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Cuti</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">50</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Absensi</h3>
                            <button id="tambahAbsensiBtn"
                                class="bg-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded flex items-center">
                                <span class="material-icons mr-2">add</span>
                                Tambah Absensi
                            </button>
                        </div>
                        <div
                            class="overflow-x-auto bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="w-full text-sm text-left">
                                <thead
                                    class="bg-gray-200 dark:bg-gray-700 text-xs text-gray-600 dark:text-gray-300 uppercase">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">No</th>
                                        <th class="px-6 py-3" scope="col">Nama</th>
                                        <th class="px-6 py-3" scope="col">Tanggal</th>
                                        <th class="px-6 py-3" scope="col">Jam Masuk</th>
                                        <th class="px-6 py-3" scope="col">Jam Keluar</th>
                                        <th class="px-6 py-3" scope="col">Status</th>
                                        <th class="px-6 py-3" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">1.</td>
                                        <td class="px-6 py-4">Budi Santoso</td>
                                        <td class="px-6 py-4">2024-10-15</td>
                                        <td class="px-6 py-4">08:00</td>
                                        <td class="px-6 py-4">17:00</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Hadir</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-absensi-btn"
                                                    data-id="1"
                                                    data-nama="Budi Santoso"
                                                    data-tanggal="2024-10-15"
                                                    data-jam-masuk="08:00"
                                                    data-jam-keluar="17:00"
                                                    data-status="Hadir"
                                                    class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                                                    <span class="material-icons text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-absensi-btn"
                                                    data-id="1"
                                                    data-nama="Budi Santoso"
                                                    data-tanggal="2024-10-15"
                                                    class="p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors">
                                                    <span class="material-icons text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="bg-white dark:bg-gray-900">
                                        <td class="px-6 py-4">2.</td>
                                        <td class="px-6 py-4">Citra Lestari</td>
                                        <td class="px-6 py-4">2024-10-15</td>
                                        <td class="px-6 py-4">08:30</td>
                                        <td class="px-6 py-4">17:30</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Hadir</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-absensi-btn"
                                                    data-id="2"
                                                    data-nama="Citra Lestari"
                                                    data-tanggal="2024-10-15"
                                                    data-jam-masuk="08:30"
                                                    data-jam-keluar="17:30"
                                                    data-status="Hadir"
                                                    class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                                                    <span class="material-icons text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-absensi-btn"
                                                    data-id="2"
                                                    data-nama="Citra Lestari"
                                                    data-tanggal="2024-10-15"
                                                    class="p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors">
                                                    <span class="material-icons text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">3.</td>
                                        <td class="px-6 py-4">Eko Prabowo</td>
                                        <td class="px-6 py-4">2024-10-15</td>
                                        <td class="px-6 py-4">09:15</td>
                                        <td class="px-6 py-4">17:00</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Terlambat</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-absensi-btn"
                                                    data-id="3"
                                                    data-nama="Eko Prabowo"
                                                    data-tanggal="2024-10-15"
                                                    data-jam-masuk="09:15"
                                                    data-jam-keluar="17:00"
                                                    data-status="Terlambat"
                                                    class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                                                    <span class="material-icons text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-absensi-btn"
                                                    data-id="3"
                                                    data-nama="Eko Prabowo"
                                                    data-tanggal="2024-10-15"
                                                    class="p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors">
                                                    <span class="material-icons text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Ketidakhadiran</h3>
                            <button id="tambahKetidakhadiranBtn"
                                class="bg-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded flex items-center">
                                <span class="material-icons mr-2">add</span>
                                Tambah Ketidakhadiran
                            </button>
                        </div>
                        <div
                            class="overflow-x-auto bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="w-full text-sm text-left">
                                <thead
                                    class="bg-gray-200 dark:bg-gray-700 text-xs text-gray-600 dark:text-gray-300 uppercase">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">No</th>
                                        <th class="px-6 py-3" scope="col">Nama</th>
                                        <th class="px-6 py-3" scope="col">Tanggal Mulai</th>
                                        <th class="px-6 py-3" scope="col">Tanggal Akhir</th>
                                        <th class="px-6 py-3" scope="col">Alasan</th>
                                        <th class="px-6 py-3" scope="col">Status</th>
                                        <th class="px-6 py-3" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">1.</td>
                                        <td class="px-6 py-4">Dewi Anggraini</td>
                                        <td class="px-6 py-4">2024-10-10</td>
                                        <td class="px-6 py-4">2024-10-12</td>
                                        <td class="px-6 py-4">Sakit</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Izin</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-ketidakhadiran-btn"
                                                    data-id="1"
                                                    data-nama="Dewi Anggraini"
                                                    data-tanggal-mulai="2024-10-10"
                                                    data-tanggal-akhir="2024-10-12"
                                                    data-alasan="Sakit"
                                                    data-status="Izin"
                                                    class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                                                    <span class="material-icons text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-ketidakhadiran-btn"
                                                    data-id="1"
                                                    data-nama="Dewi Anggraini"
                                                    data-tanggal-mulai="2024-10-10"
                                                    class="p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors">
                                                    <span class="material-icons text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="bg-white dark:bg-gray-900">
                                        <td class="px-6 py-4">2.</td>
                                        <td class="px-6 py-4">Ani Yudhoyono</td>
                                        <td class="px-6 py-4">2024-10-05</td>
                                        <td class="px-6 py-4">2024-10-15</td>
                                        <td class="px-6 py-4">Liburan keluarga</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Cuti</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-ketidakhadiran-btn"
                                                    data-id="2"
                                                    data-nama="Ani Yudhoyono"
                                                    data-tanggal-mulai="2024-10-05"
                                                    data-tanggal-akhir="2024-10-15"
                                                    data-alasan="Liburan keluarga"
                                                    data-status="Cuti"
                                                    class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                                                    <span class="material-icons text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-ketidakhadiran-btn"
                                                    data-id="2"
                                                    data-nama="Ani Yudhoyono"
                                                    data-tanggal-mulai="2024-10-05"
                                                    class="p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors">
                                                    <span class="material-icons text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="bg-gray-200 dark:bg-gray-800 text-center p-4 text-sm text-gray-600 dark:text-gray-400">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

    <!-- Modal Tambah Absensi -->
    <div id="tambahAbsensiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Absensi</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <form id="tambahAbsensiForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Karyawan</label>
                        <select id="tambahAbsensiNama" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Karyawan</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                        <input type="date" id="tambahAbsensiTanggal" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Masuk</label>
                            <input type="time" id="tambahAbsensiJamMasuk" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Keluar</label>
                            <input type="time" id="tambahAbsensiJamKeluar" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="tambahAbsensiStatus" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Terlambat">Terlambat</option>
                            </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Absensi -->
    <div id="editAbsensiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Absensi</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <form id="editAbsensiForm">
                    <input type="hidden" id="editAbsensiId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Karyawan</label>
                        <select id="editAbsensiNama" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                        <input type="date" id="editAbsensiTanggal" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Masuk</label>
                            <input type="time" id="editAbsensiJamMasuk" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Keluar</label>
                            <input type="time" id="editAbsensiJamKeluar" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="editAbsensiStatus" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Terlambat">Terlambat</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Absensi -->
    <div id="deleteAbsensiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus absensi <span id="deleteAbsensiNama" class="font-semibold"></span> pada tanggal <span id="deleteAbsensiTanggal" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteAbsensiId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                    <button id="confirmDeleteAbsensi" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Ketidakhadiran -->
    <div id="tambahKetidakhadiranModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Ketidakhadiran</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <form id="tambahKetidakhadiranForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Karyawan</label>
                        <select id="tambahKetidakhadiranNama" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Karyawan</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" id="tambahKetidakhadiranTanggalMulai" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                            <input type="date" id="tambahKetidakhadiranTanggalAkhir" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan</label>
                        <textarea id="tambahKetidakhadiranAlasan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="tambahKetidakhadiranStatus" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Izin">Izin</option>
                            <option value="Cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Ketidakhadiran -->
    <div id="editKetidakhadiranModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Ketidakhadiran</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <form id="editKetidakhadiranForm">
                    <input type="hidden" id="editKetidakhadiranId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Karyawan</label>
                        <select id="editKetidakhadiranNama" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" id="editKetidakhadiranTanggalMulai" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                            <input type="date" id="editKetidakhadiranTanggalAkhir" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan</label>
                        <textarea id="editKetidakhadiranAlasan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="editKetidakhadiranStatus" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Izin">Izin</option>
                            <option value="Cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Ketidakhadiran -->
    <div id="deleteKetidakhadiranModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus ketidakhadiran <span id="deleteKetidakhadiranNama" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteKetidakhadiranId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                    <button id="confirmDeleteKetidakhadiran" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons text-lg">close</span>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const tambahAbsensiModal = document.getElementById('tambahAbsensiModal');
            const editAbsensiModal = document.getElementById('editAbsensiModal');
            const deleteAbsensiModal = document.getElementById('deleteAbsensiModal');
            const tambahKetidakhadiranModal = document.getElementById('tambahKetidakhadiranModal');
            const editKetidakhadiranModal = document.getElementById('editKetidakhadiranModal');
            const deleteKetidakhadiranModal = document.getElementById('deleteKetidakhadiranModal');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Buttons
            const tambahAbsensiBtn = document.getElementById('tambahAbsensiBtn');
            const tambahKetidakhadiranBtn = document.getElementById('tambahKetidakhadiranBtn');
            const editAbsensiBtns = document.querySelectorAll('.edit-absensi-btn');
            const deleteAbsensiBtns = document.querySelectorAll('.delete-absensi-btn');
            const editKetidakhadiranBtns = document.querySelectorAll('.edit-ketidakhadiran-btn');
            const deleteKetidakhadiranBtns = document.querySelectorAll('.delete-ketidakhadiran-btn');
            const closeModals = document.querySelectorAll('.close-modal');
            const confirmDeleteAbsensiBtn = document.getElementById('confirmDeleteAbsensi');
            const confirmDeleteKetidakhadiranBtn = document.getElementById('confirmDeleteKetidakhadiran');
            const closeToastBtn = document.getElementById('closeToast');
            
            // Forms
            const tambahAbsensiForm = document.getElementById('tambahAbsensiForm');
            const editAbsensiForm = document.getElementById('editAbsensiForm');
            const tambahKetidakhadiranForm = document.getElementById('tambahKetidakhadiranForm');
            const editKetidakhadiranForm = document.getElementById('editKetidakhadiranForm');
            
            // Show tambah absensi modal
            tambahAbsensiBtn.addEventListener('click', function() {
                tambahAbsensiModal.classList.remove('hidden');
                tambahAbsensiForm.reset();
            });
            
            // Show tambah ketidakhadiran modal
            tambahKetidakhadiranBtn.addEventListener('click', function() {
                tambahKetidakhadiranModal.classList.remove('hidden');
                tambahKetidakhadiranForm.reset();
            });
            
            // Show edit absensi modal with data
            editAbsensiBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const tanggal = this.getAttribute('data-tanggal');
                    const jamMasuk = this.getAttribute('data-jam-masuk');
                    const jamKeluar = this.getAttribute('data-jam-keluar');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('editAbsensiId').value = id;
                    document.getElementById('editAbsensiNama').value = nama;
                    document.getElementById('editAbsensiTanggal').value = tanggal;
                    document.getElementById('editAbsensiJamMasuk').value = jamMasuk;
                    document.getElementById('editAbsensiJamKeluar').value = jamKeluar;
                    document.getElementById('editAbsensiStatus').value = status;
                    
                    editAbsensiModal.classList.remove('hidden');
                });
            });
            
            // Show delete absensi modal with data
            deleteAbsensiBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const tanggal = this.getAttribute('data-tanggal');
                    
                    document.getElementById('deleteAbsensiId').value = id;
                    document.getElementById('deleteAbsensiNama').textContent = nama;
                    document.getElementById('deleteAbsensiTanggal').textContent = tanggal;
                    
                    deleteAbsensiModal.classList.remove('hidden');
                });
            });
            
            // Show edit ketidakhadiran modal with data
            editKetidakhadiranBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const tanggalMulai = this.getAttribute('data-tanggal-mulai');
                    const tanggalAkhir = this.getAttribute('data-tanggal-akhir');
                    const alasan = this.getAttribute('data-alasan');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('editKetidakhadiranId').value = id;
                    document.getElementById('editKetidakhadiranNama').value = nama;
                    document.getElementById('editKetidakhadiranTanggalMulai').value = tanggalMulai;
                    document.getElementById('editKetidakhadiranTanggalAkhir').value = tanggalAkhir;
                    document.getElementById('editKetidakhadiranAlasan').value = alasan;
                    document.getElementById('editKetidakhadiranStatus').value = status;
                    
                    editKetidakhadiranModal.classList.remove('hidden');
                });
            });
            
            // Show delete ketidakhadiran modal with data
            deleteKetidakhadiranBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    
                    document.getElementById('deleteKetidakhadiranId').value = id;
                    document.getElementById('deleteKetidakhadiranNama').textContent = nama;
                    
                    deleteKetidakhadiranModal.classList.remove('hidden');
                });
            });
            
            // Close modals
            closeModals.forEach(btn => {
                btn.addEventListener('click', function() {
                    tambahAbsensiModal.classList.add('hidden');
                    editAbsensiModal.classList.add('hidden');
                    deleteAbsensiModal.classList.add('hidden');
                    tambahKetidakhadiranModal.classList.add('hidden');
                    editKetidakhadiranModal.classList.add('hidden');
                    deleteKetidakhadiranModal.classList.add('hidden');
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === tambahAbsensiModal) {
                    tambahAbsensiModal.classList.add('hidden');
                }
                if (event.target === editAbsensiModal) {
                    editAbsensiModal.classList.add('hidden');
                }
                if (event.target === deleteAbsensiModal) {
                    deleteAbsensiModal.classList.add('hidden');
                }
                if (event.target === tambahKetidakhadiranModal) {
                    tambahKetidakhadiranModal.classList.add('hidden');
                }
                if (event.target === editKetidakhadiranModal) {
                    editKetidakhadiranModal.classList.add('hidden');
                }
                if (event.target === deleteKetidakhadiranModal) {
                    deleteKetidakhadiranModal.classList.add('hidden');
                }
            });
            
            // Handle tambah absensi form submission
            tambahAbsensiForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Data absensi berhasil ditambahkan!');
                tambahAbsensiModal.classList.add('hidden');
                tambahAbsensiForm.reset();
            });
            
            // Handle edit absensi form submission
            editAbsensiForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Data absensi berhasil diperbarui!');
                editAbsensiModal.classList.add('hidden');
            });
            
            // Handle delete absensi confirmation
            confirmDeleteAbsensiBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteAbsensiId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Data absensi berhasil dihapus!');
                deleteAbsensiModal.classList.add('hidden');
            });
            
            // Handle tambah ketidakhadiran form submission
            tambahKetidakhadiranForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Data ketidakhadiran berhasil ditambahkan!');
                tambahKetidakhadiranModal.classList.add('hidden');
                tambahKetidakhadiranForm.reset();
            });
            
            // Handle edit ketidakhadiran form submission
            editKetidakhadiranForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Data ketidakhadiran berhasil diperbarui!');
                editKetidakhadiranModal.classList.add('hidden');
            });
            
            // Handle delete ketidakhadiran confirmation
            confirmDeleteKetidakhadiranBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteKetidakhadiranId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Data ketidakhadiran berhasil dihapus!');
                deleteKetidakhadiranModal.classList.add('hidden');
            });
            
            // Close toast notification
            closeToastBtn.addEventListener('click', function() {
                toast.classList.add('translate-y-20', 'opacity-0');
            });
            
            // Function to show toast notification
            function showToast(message) {
                toastMessage.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                
                // Auto hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }
        });
    </script>
</body>

</html>
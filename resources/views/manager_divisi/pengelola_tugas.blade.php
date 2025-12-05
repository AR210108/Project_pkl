<!DOCTYPE html>
<html class="h-full" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Tugas</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#A43A3A",
                        "background-light": "#F8FAFC",
                        "background-dark": "#18181B",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Tooltip styles */
        .tooltip {
            position: relative;
        }
        
        .tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        
        .tooltip:hover::after {
            opacity: 1;
        }
        
        /* Modal styles */
        .modal {
            transition: opacity 0.3s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="h-full font-display bg-gray-50 dark:bg-background-dark text-slate-800 dark:text-slate-200">
    <div class="flex h-screen flex-col">
        <div class="flex flex-1 overflow-hidden">
            @include('manager_divisi/templet/header')
            <main class="flex-1 overflow-y-auto bg-white">
                <div class="p-8">
                    <!-- Judul -->
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-black dark:text-white">Daftar Tugas</h2>
                    </div>
                    
                    <!-- Tombol Buat Tugas Baru dan Pencarian sejajar -->
                    <div class="flex justify-between items-center mb-6">
                        <button id="buatTugasBtn"
                            class="bg-primary text-white font-semibold py-2 px-4 rounded-md hover:bg-opacity-90 transition-colors">
                            <span class="material-symbols-outlined text-base mr-2">add</span>
                            Buat Tugas Baru
                        </button>
                        <div class="relative w-64">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-slate-500">search</span>
                            <input
                                class="w-full bg-gray-200 dark:bg-zinc-700 border-none rounded-md pl-10 pr-4 py-2 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-background-dark placeholder-gray-500 dark:placeholder-slate-400"
                                placeholder="Search..." type="text" />
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-zinc-900 rounded-lg overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-200 dark:bg-zinc-800">
                                    <tr>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 w-12 tracking-wider">
                                            NO</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            JUDUL</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            DESKRIPSI</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            DEADLINE</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            KARYAWAN</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            PROJEK MANAJER</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            STATUS</th>
                                        <th
                                            class="p-4 text-sm font-semibold text-gray-600 dark:text-slate-400 tracking-wider">
                                            AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white dark:divide-zinc-700 border-b border-gray-200">
                                        <td class="p-4 text-gray-800 dark:text-slate-400">1.</td>
                                        <td class="p-4 font-medium">Pengembangan Fitur Login</td>
                                        <td class="p-4 max-w-xs">Membuat sistem autentikasi user dengan JWT</td>
                                        <td class="p-4">15 Juni 2023</td>
                                        <td class="p-4">Ahmad Rizki</td>
                                        <td class="p-4">Budi Santoso</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">On Progress</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex justify-center gap-2">
                                                <button class="edit-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="1"
                                                    data-judul="Pengembangan Fitur Login"
                                                    data-deskripsi="Membuat sistem autentikasi user dengan JWT"
                                                    data-deadline="2023-06-15"
                                                    data-karyawan="Ahmad Rizki"
                                                    data-manajer="Budi Santoso"
                                                    data-status="On Progress"
                                                    data-tooltip="Edit">
                                                    <span class="material-symbols-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="1"
                                                    data-judul="Pengembangan Fitur Login"
                                                    data-tooltip="Hapus">
                                                    <span class="material-symbols-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50 dark:divide-zinc-700 border-b border-gray-200">
                                        <td class="p-4 text-gray-800 dark:text-slate-400">2.</td>
                                        <td class="p-4 font-medium">Desain UI Dashboard</td>
                                        <td class="p-4 max-w-xs">Membuat desain antarmuka dashboard admin</td>
                                        <td class="p-4">20 Juni 2023</td>
                                        <td class="p-4">Siti Nurhaliza</td>
                                        <td class="p-4">Budi Santoso</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Selesai</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex justify-center gap-2">
                                                <button class="edit-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="2"
                                                    data-judul="Desain UI Dashboard"
                                                    data-deskripsi="Membuat desain antarmuka dashboard admin"
                                                    data-deadline="2023-06-20"
                                                    data-karyawan="Siti Nurhaliza"
                                                    data-manajer="Budi Santoso"
                                                    data-status="Selesai"
                                                    data-tooltip="Edit">
                                                    <span class="material-symbols-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="2"
                                                    data-judul="Desain UI Dashboard"
                                                    data-tooltip="Hapus">
                                                    <span class="material-symbols-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="bg-white dark:divide-zinc-700 border-b border-gray-200">
                                        <td class="p-4 text-gray-800 dark:text-slate-400">3.</td>
                                        <td class="p-4 font-medium">Optimasi Database</td>
                                        <td class="p-4 max-w-xs">Meningkatkan performa query database</td>
                                        <td class="p-4">25 Juni 2023</td>
                                        <td class="p-4">Eko Prasetyo</td>
                                        <td class="p-4">Budi Santoso</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Terlambat</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex justify-center gap-2">
                                                <button class="edit-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="3"
                                                    data-judul="Optimasi Database"
                                                    data-deskripsi="Meningkatkan performa query database"
                                                    data-deadline="2023-06-25"
                                                    data-karyawan="Eko Prasetyo"
                                                    data-manajer="Budi Santoso"
                                                    data-status="Terlambat"
                                                    data-tooltip="Edit">
                                                    <span class="material-symbols-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="3"
                                                    data-judul="Optimasi Database"
                                                    data-tooltip="Hapus">
                                                    <span class="material-symbols-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50 dark:divide-zinc-700">
                                        <td class="p-4 text-gray-800 dark:text-slate-400">4.</td>
                                        <td class="p-4 font-medium">Testing Aplikasi</td>
                                        <td class="p-4 max-w-xs">Melakukan uji coba fungsi aplikasi</td>
                                        <td class="p-4">30 Juni 2023</td>
                                        <td class="p-4">Dewi Lestari</td>
                                        <td class="p-4">Budi Santoso</td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">To Do</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex justify-center gap-2">
                                                <button class="edit-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="4"
                                                    data-judul="Testing Aplikasi"
                                                    data-deskripsi="Melakukan uji coba fungsi aplikasi"
                                                    data-deadline="2023-06-30"
                                                    data-karyawan="Dewi Lestari"
                                                    data-manajer="Budi Santoso"
                                                    data-status="To Do"
                                                    data-tooltip="Edit">
                                                    <span class="material-symbols-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-tugas-btn tooltip p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors"
                                                    data-id="4"
                                                    data-judul="Testing Aplikasi"
                                                    data-tooltip="Hapus">
                                                    <span class="material-symbols-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex justify-end items-center mt-6">
                        <div class="flex items-center gap-4">
                            <button
                                class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-slate-400 hover:text-black dark:hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                <span>Previous</span>
                            </button>
                            <button
                                class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-slate-400 hover:text-black dark:hover:text-white transition-colors">
                                <span>Next</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <footer class="bg-gray-200 dark:bg-zinc-800 text-center p-4">
            <p class="text-sm text-gray-600 dark:text-slate-400">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <!-- Modal Buat Tugas -->
    <div id="buatTugasModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-zinc-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buat Tugas Baru</h3>
                    <p class="text-gray-600 dark:text-slate-400">Isi form di bawah untuk membuat tugas baru</p>
                </div>
                <button onclick="closeBuatTugasModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="buatTugasForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Judul Tugas</label>
                        <input type="text" id="judulTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Deskripsi</label>
                        <textarea id="deskripsiTugas" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Deadline</label>
                            <input type="date" id="deadlineTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Karyawan</label>
                            <select id="karyawanTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                                <option value="">Pilih Karyawan</option>
                                <option value="Ahmad Rizki">Ahmad Rizki</option>
                                <option value="Siti Nurhaliza">Siti Nurhaliza</option>
                                <option value="Eko Prasetyo">Eko Prasetyo</option>
                                <option value="Dewi Lestari">Dewi Lestari</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Projek Manajer</label>
                        <select id="manajerTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Projek Manajer</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Agus Wijaya">Agus Wijaya</option>
                            <option value="Rina Susanti">Rina Susanti</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Status</label>
                        <select id="statusTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="To Do">To Do</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Terlambat">Terlambat</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-zinc-700 flex justify-end space-x-3">
                <button onclick="closeBuatTugasModal()" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-slate-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitBuatTugas()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-opacity-90 transition-colors">
                    Buat Tugas
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div id="editTugasModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-zinc-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Tugas</h3>
                    <p class="text-gray-600 dark:text-slate-400">Ubah informasi tugas di bawah</p>
                </div>
                <button onclick="closeEditTugasModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="editTugasForm" class="space-y-4">
                    <input type="hidden" id="editTugasId">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Judul Tugas</label>
                        <input type="text" id="editJudulTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsiTugas" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Deadline</label>
                            <input type="date" id="editDeadlineTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Karyawan</label>
                            <select id="editKaryawanTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                                <option value="Ahmad Rizki">Ahmad Rizki</option>
                                <option value="Siti Nurhaliza">Siti Nurhaliza</option>
                                <option value="Eko Prasetyo">Eko Prasetyo</option>
                                <option value="Dewi Lestari">Dewi Lestari</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Projek Manajer</label>
                        <select id="editManajerTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Agus Wijaya">Agus Wijaya</option>
                            <option value="Rina Susanti">Rina Susanti</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Status</label>
                        <select id="editStatusTugas" class="w-full px-3 py-2 bg-gray-100 dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="To Do">To Do</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Terlambat">Terlambat</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-zinc-700 flex justify-end space-x-3">
                <button onclick="closeEditTugasModal()" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-slate-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitEditTugas()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-opacity-90 transition-colors">
                    Update Tugas
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Tugas -->
    <div id="deleteTugasModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-zinc-800 rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <button onclick="closeDeleteTugasModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus tugas <span id="deleteTugasJudul" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteTugasId">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeDeleteTugasModal()" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-slate-200 rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600">Batal</button>
                    <button id="confirmDeleteTugas" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol Buat Tugas
            const buatTugasBtn = document.getElementById('buatTugasBtn');
            if (buatTugasBtn) {
                buatTugasBtn.addEventListener('click', function() {
                    document.getElementById('buatTugasModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Event listeners untuk tombol Edit
            const editBtns = document.querySelectorAll('.edit-tugas-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const judul = this.getAttribute('data-judul');
                    const deskripsi = this.getAttribute('data-deskripsi');
                    const deadline = this.getAttribute('data-deadline');
                    const karyawan = this.getAttribute('data-karyawan');
                    const manajer = this.getAttribute('data-manajer');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('editTugasId').value = id;
                    document.getElementById('editJudulTugas').value = judul;
                    document.getElementById('editDeskripsiTugas').value = deskripsi;
                    document.getElementById('editDeadlineTugas').value = deadline;
                    document.getElementById('editKaryawanTugas').value = karyawan;
                    document.getElementById('editManajerTugas').value = manajer;
                    document.getElementById('editStatusTugas').value = status;
                    
                    document.getElementById('editTugasModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Event listeners untuk tombol Hapus
            const deleteBtns = document.querySelectorAll('.delete-tugas-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const judul = this.getAttribute('data-judul');
                    
                    document.getElementById('deleteTugasId').value = id;
                    document.getElementById('deleteTugasJudul').textContent = judul;
                    
                    document.getElementById('deleteTugasModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Close modal when clicking outside
            document.getElementById('buatTugasModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBuatTugasModal();
                }
            });
            
            document.getElementById('editTugasModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditTugasModal();
                }
            });
            
            document.getElementById('deleteTugasModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteTugasModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!document.getElementById('buatTugasModal').classList.contains('hidden')) {
                        closeBuatTugasModal();
                    }
                    if (!document.getElementById('editTugasModal').classList.contains('hidden')) {
                        closeEditTugasModal();
                    }
                    if (!document.getElementById('deleteTugasModal').classList.contains('hidden')) {
                        closeDeleteTugasModal();
                    }
                }
            });
            
            // Close toast notification
            document.getElementById('closeToast').addEventListener('click', function() {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Confirm delete tugas
            document.getElementById('confirmDeleteTugas').addEventListener('click', function() {
                const id = document.getElementById('deleteTugasId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Tugas berhasil dihapus!');
                closeDeleteTugasModal();
            });
        });
        
        // Modal functions for Buat Tugas
        function closeBuatTugasModal() {
            document.getElementById('buatTugasModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('buatTugasForm').reset();
        }
        
        function submitBuatTugas() {
            const form = document.getElementById('buatTugasForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = {
                judulTugas: document.getElementById('judulTugas').value,
                deskripsiTugas: document.getElementById('deskripsiTugas').value,
                deadlineTugas: document.getElementById('deadlineTugas').value,
                karyawanTugas: document.getElementById('karyawanTugas').value,
                manajerTugas: document.getElementById('manajerTugas').value,
                statusTugas: document.getElementById('statusTugas').value
            };
            
            // In a real application, you would send this data to the server
            console.log('Form data:', formData);
            
            // Show success message
            showToast('Tugas berhasil dibuat!');
            
            // Close modal
            closeBuatTugasModal();
        }
        
        // Modal functions for Edit Tugas
        function closeEditTugasModal() {
            document.getElementById('editTugasModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function submitEditTugas() {
            const form = document.getElementById('editTugasForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = {
                id: document.getElementById('editTugasId').value,
                judulTugas: document.getElementById('editJudulTugas').value,
                deskripsiTugas: document.getElementById('editDeskripsiTugas').value,
                deadlineTugas: document.getElementById('editDeadlineTugas').value,
                karyawanTugas: document.getElementById('editKaryawanTugas').value,
                manajerTugas: document.getElementById('editManajerTugas').value,
                statusTugas: document.getElementById('editStatusTugas').value
            };
            
            // In a real application, you would send this data to the server
            console.log('Form data:', formData);
            
            // Show success message
            showToast('Tugas berhasil diperbarui!');
            
            // Close modal
            closeEditTugasModal();
        }
        
        // Modal functions for Delete Tugas
        function closeDeleteTugasModal() {
            document.getElementById('deleteTugasModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Toast notification function
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }
    </script>
</body>

</html>
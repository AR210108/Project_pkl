<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Tugas (Manage Tasks) - List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        .material-icons {
            font-size: inherit;
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
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6B7280", // A neutral gray, works for buttons
                        "background-light": "#F3F4F6", // Light gray background
                        "background-dark": "#111827", // Dark gray background
                    },
                    fontFamily: {
                        display: ["Roboto", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                    },
                },
            },
        };
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">
    <div class="flex h-screen">
        @include('general_manajer/templet/header')
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-8 overflow-y-auto">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Daftar Tugas</h1>
                    <div class="flex justify-between items-center mb-6">
                        <button id="buatTugasBtn"
                            class="bg-primary hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded flex items-center">
                            <span class="material-icons mr-2 text-xl">add</span>
                            Buat Tugas Baru
                        </button>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <span
                                    class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                                <input
                                    class="w-64 pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-gray-500"
                                    placeholder="Search..." type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3" scope="col">NO</th>
                                    <th class="px-6 py-3" scope="col">Judul</th>
                                    <th class="px-6 py-3" scope="col">Deskripsi</th>
                                    <th class="px-6 py-3" scope="col">Deadline</th>
                                    <th class="px-6 py-3" scope="col">Karyawan</th>
                                    <th class="px-6 py-3" scope="col">Projek Manajer</th>
                                    <th class="px-6 py-3" scope="col">Status</th>
                                    <th class="px-6 py-3" scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-6 py-4">1.</td>
                                    <td class="px-6 py-4">Mendesain Ulang Halaman Utama</td>
                                    <td class="px-6 py-4">Revisi UI/UX untuk meningkatkan...</td>
                                    <td class="px-6 py-4">2024-10-25</td>
                                    <td class="px-6 py-4">Budi Santoso</td>
                                    <td class="px-6 py-4">Citra Lestari</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">In
                                            Progress</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button class="edit-btn"
                                                data-id="1"
                                                data-judul="Mendesain Ulang Halaman Utama"
                                                data-deskripsi="Revisi UI/UX untuk meningkatkan pengalaman pengguna"
                                                data-deadline="2024-10-25"
                                                data-karyawan="Budi Santoso"
                                                data-manajer="Citra Lestari"
                                                data-status="In Progress"
                                                class="p-1 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="1"
                                                data-judul="Mendesain Ulang Halaman Utama"
                                                class="p-1 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
                                    <td class="px-6 py-4">2.</td>
                                    <td class="px-6 py-4">Implementasi Fitur Pembayaran</td>
                                    <td class="px-6 py-4">Mengintegrasikan gateway pemba...</td>
                                    <td class="px-6 py-4">2024-11-05</td>
                                    <td class="px-6 py-4">Ani Yudhoyono</td>
                                    <td class="px-6 py-4">Citra Lestari</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">To
                                            Do</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button class="edit-btn"
                                                data-id="2"
                                                data-judul="Implementasi Fitur Pembayaran"
                                                data-deskripsi="Mengintegrasikan gateway pembayaran ke sistem"
                                                data-deadline="2024-11-05"
                                                data-karyawan="Ani Yudhoyono"
                                                data-manajer="Citra Lestari"
                                                data-status="To Do"
                                                class="p-1 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="2"
                                                data-judul="Implementasi Fitur Pembayaran"
                                                class="p-1 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-6 py-4">3.</td>
                                    <td class="px-6 py-4">Testing Aplikasi Mobile</td>
                                    <td class="px-6 py-4">Melakukan pengujian menyeluruh...</td>
                                    <td class="px-6 py-4">2024-10-30</td>
                                    <td class="px-6 py-4">Eko Prabowo</td>
                                    <td class="px-6 py-4">Citra Lestari</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">To
                                            Do</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button class="edit-btn"
                                                data-id="3"
                                                data-judul="Testing Aplikasi Mobile"
                                                data-deskripsi="Melakukan pengujian menyeluruh pada aplikasi mobile"
                                                data-deadline="2024-10-30"
                                                data-karyawan="Eko Prabowo"
                                                data-manajer="Citra Lestari"
                                                data-status="To Do"
                                                class="p-1 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="3"
                                                data-judul="Testing Aplikasi Mobile"
                                                class="p-1 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-gray-50 dark:bg-gray-900">
                                    <td class="px-6 py-4">4.</td>
                                    <td class="px-6 py-4">Deployment ke Server Produksi</td>
                                    <td class="px-6 py-4">Menyiapkan dan melakukan deplo...</td>
                                    <td class="px-6 py-4">2024-11-15</td>
                                    <td class="px-6 py-4">Dewi Anggraini</td>
                                    <td class="px-6 py-4">Citra Lestari</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Done</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button class="edit-btn"
                                                data-id="4"
                                                data-judul="Deployment ke Server Produksi"
                                                data-deskripsi="Menyiapkan dan melakukan deployment ke server produksi"
                                                data-deadline="2024-11-15"
                                                data-karyawan="Dewi Anggraini"
                                                data-manajer="Citra Lestari"
                                                data-status="Done"
                                                class="p-1 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="4"
                                                data-judul="Deployment ke Server Produksi"
                                                class="p-1 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-700 rounded-full">
                                                <span class="material-icons text-base">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-between items-center mt-6 text-sm">
                        <button
                            class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white disabled:opacity-50"
                            disabled="">
                            <span class="material-icons">chevron_left</span>
                            Previous
                        </button>
                        <div class="flex items-center space-x-2">
                            <button class="px-3 py-1 rounded bg-primary text-white">1</button>
                            <button class="px-3 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">2</button>
                            <button class="px-3 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">3</button>
                        </div>
                        <button
                            class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            Next
                            <span class="material-icons">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
            <footer class="bg-gray-200 dark:bg-gray-900 text-center p-4 text-sm text-gray-600 dark:text-gray-400">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Tambah Tugas -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buat Tugas Baru</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <form id="tambahForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Tugas</label>
                        <input type="text" id="tambahJudul" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea id="tambahDeskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                        <input type="date" id="tambahDeadline" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Karyawan</label>
                        <select id="tambahKaryawan" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Karyawan</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Projek Manajer</label>
                        <select id="tambahManajer" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Projek Manajer</option>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="tambahStatus" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="To Do">To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-gray-600">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Tugas</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="editId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Tugas</label>
                        <input type="text" id="editJudul" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                        <input type="date" id="editDeadline" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Karyawan</label>
                        <select id="editKaryawan" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Ani Yudhoyono">Ani Yudhoyono</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                            <option value="Dewi Anggraini">Dewi Anggraini</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Projek Manajer</label>
                        <select id="editManajer" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Citra Lestari">Citra Lestari</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Eko Prabowo">Eko Prabowo</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="To Do">To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-gray-600">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Tugas -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus tugas <span id="deleteJudul" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
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
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Buttons
            const buatTugasBtn = document.getElementById('buatTugasBtn');
            const editBtns = document.querySelectorAll('.edit-btn');
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const closeModals = document.querySelectorAll('.close-modal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const closeToastBtn = document.getElementById('closeToast');
            
            // Forms
            const tambahForm = document.getElementById('tambahForm');
            const editForm = document.getElementById('editForm');
            
            // Show tambah modal
            buatTugasBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
                tambahForm.reset();
            });
            
            // Show edit modal with data
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const judul = this.getAttribute('data-judul');
                    const deskripsi = this.getAttribute('data-deskripsi');
                    const deadline = this.getAttribute('data-deadline');
                    const karyawan = this.getAttribute('data-karyawan');
                    const manajer = this.getAttribute('data-manajer');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('editId').value = id;
                    document.getElementById('editJudul').value = judul;
                    document.getElementById('editDeskripsi').value = deskripsi;
                    document.getElementById('editDeadline').value = deadline;
                    document.getElementById('editKaryawan').value = karyawan;
                    document.getElementById('editManajer').value = manajer;
                    document.getElementById('editStatus').value = status;
                    
                    editModal.classList.remove('hidden');
                });
            });
            
            // Show delete modal with data
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const judul = this.getAttribute('data-judul');
                    
                    document.getElementById('deleteId').value = id;
                    document.getElementById('deleteJudul').textContent = judul;
                    
                    deleteModal.classList.remove('hidden');
                });
            });
            
            // Close modals
            closeModals.forEach(btn => {
                btn.addEventListener('click', function() {
                    tambahModal.classList.add('hidden');
                    editModal.classList.add('hidden');
                    deleteModal.classList.add('hidden');
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === tambahModal) {
                    tambahModal.classList.add('hidden');
                }
                if (event.target === editModal) {
                    editModal.classList.add('hidden');
                }
                if (event.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
            });
            
            // Handle tambah form submission
            tambahForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Tugas berhasil ditambahkan!');
                tambahModal.classList.add('hidden');
                tambahForm.reset();
            });
            
            // Handle edit form submission
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Tugas berhasil diperbarui!');
                editModal.classList.add('hidden');
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Tugas berhasil dihapus!');
                deleteModal.classList.add('hidden');
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
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Karyawan</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap"
        rel="stylesheet" />
    <!-- Tambahkan semua library ikon yang mungkin digunakan -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <style>
        /* CSS untuk Material Icons */
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* CSS untuk Material Icons Outlined */
        .material-icons-outlined {
            font-family: 'Material Icons Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* CSS untuk Material Symbols Outlined */
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
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
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b8cee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "content-light": "#ffffff",
                        "content-dark": "#182431",
                        "text-light": "#101922",
                        "text-dark": "#f6f7f8",
                        "subtle-light": "#e7edf3",
                        "subtle-dark": "#212e3c",
                        "border-light": "#dde3ea",
                        "border-dark": "#2a394a",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <div class="flex min-h-screen">
        <!-- Pastikan path include benar -->
        @include('general_manajer/templet/header')
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col p-6 lg:p-8">
            <div class="flex-grow bg-content-light dark:bg-content-dark p-6 rounded-xl shadow-sm">
                <!-- PageHeading -->
                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <h1 class="text-3xl font-black tracking-tight text-text-light dark:text-text-dark">Daftar Karyawan
                    </h1>
                </div>
                <!-- Toolbar & Actions -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <button id="tambahKaryawanBtn"
                        class="w-full md:w-auto flex items-center justify-center rounded-lg h-10 px-4 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                        <span class="material-symbols-outlined text-xl">add</span>
                        <span class="truncate">Tambah Karyawan</span>
                    </button>
                    <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                        <label class="flex-1 sm:max-w-xs h-10 w-full">
                            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                                <div
                                    class="flex bg-subtle-light dark:bg-subtle-dark items-center justify-center pl-3 pr-2 rounded-l-lg">
                                    <span
                                        class="material-symbols-outlined text-text-light/70 dark:text-text-dark/70">search</span>
                                </div>
                                <input
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-subtle-light dark:bg-subtle-dark h-full placeholder:text-text-light/70 dark:placeholder:text-text-dark/70 text-sm font-normal"
                                    placeholder="Search..." value="" />
                            </div>
                        </label>
                        <button
                            class="w-full sm:w-auto flex min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-subtle-light dark:bg-subtle-dark text-text-light dark:text-text-dark gap-2 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-subtle-light/80 dark:hover:bg-subtle-dark/80">
                            <span class="material-symbols-outlined text-lg">filter_list</span>
                            <span class="truncate">Filter</span>
                        </button>
                    </div>
                </div>
                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-subtle-light dark:bg-subtle-dark/60 text-xs uppercase font-semibold text-text-light/80 dark:text-text-dark/80 tracking-wider">
                            <tr>
                                <th class="p-4">No</th>
                                <th class="p-4">Nama</th>
                                <th class="p-4">Alamat</th>
                                <th class="p-4">Jenis Kelamin</th>
                                <th class="p-4">No. Telp</th>
                                <th class="p-4">Posisi</th>
                                <th class="p-4 text-right">Gaji</th>
                                <th class="p-4">Email</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light dark:divide-border-dark">
                            <tr class="hover:bg-subtle-light dark:hover:bg-subtle-dark/40">
                                <td class="p-4 font-medium">1</td>
                                <td class="p-4">Budi Santoso</td>
                                <td class="p-4">Jl. Merdeka No. 10, Jakarta</td>
                                <td class="p-4">Laki-laki</td>
                                <td class="p-4">081234567890</td>
                                <td class="p-4">Frontend Developer</td>
                                <td class="p-4 text-right">Rp 10.000.000</td>
                                <td class="p-4">budi.s@example.com</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-btn"
                                            data-id="1"
                                            data-nama="Budi Santoso"
                                            data-alamat="Jl. Merdeka No. 10, Jakarta"
                                            data-jenis-kelamin="Laki-laki"
                                            data-telp="081234567890"
                                            data-posisi="Frontend Developer"
                                            data-gaji="10000000"
                                            data-email="budi.s@example.com"
                                            class="p-1 rounded-full hover:bg-primary/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">edit</span></button>
                                        <button class="delete-btn"
                                            data-id="1"
                                            data-nama="Budi Santoso"
                                            class="p-1 rounded-full hover:bg-red-500/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                class="bg-background-light dark:bg-background-dark hover:bg-subtle-light dark:hover:bg-subtle-dark/40">
                                <td class="p-4 font-medium">2</td>
                                <td class="p-4">Citra Lestari</td>
                                <td class="p-4">Jl. Pahlawan No. 5, Surabaya</td>
                                <td class="p-4">Perempuan</td>
                                <td class="p-4">082345678901</td>
                                <td class="p-4">Backend Developer</td>
                                <td class="p-4 text-right">Rp 12.000.000</td>
                                <td class="p-4">citra.l@example.com</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-btn"
                                            data-id="2"
                                            data-nama="Citra Lestari"
                                            data-alamat="Jl. Pahlawan No. 5, Surabaya"
                                            data-jenis-kelamin="Perempuan"
                                            data-telp="082345678901"
                                            data-posisi="Backend Developer"
                                            data-gaji="12000000"
                                            data-email="citra.l@example.com"
                                            class="p-1 rounded-full hover:bg-primary/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">edit</span></button>
                                        <button class="delete-btn"
                                            data-id="2"
                                            data-nama="Citra Lestari"
                                            class="p-1 rounded-full hover:bg-red-500/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-subtle-light dark:hover:bg-subtle-dark/40">
                                <td class="p-4 font-medium">3</td>
                                <td class="p-4">Dewi Anggraini</td>
                                <td class="p-4">Jl. Sudirman No. 22, Bandung</td>
                                <td class="p-4">Perempuan</td>
                                <td class="p-4">083456789012</td>
                                <td class="p-4">UI/UX Designer</td>
                                <td class="p-4 text-right">Rp 11.000.000</td>
                                <td class="p-4">dewi.a@example.com</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-btn"
                                            data-id="3"
                                            data-nama="Dewi Anggraini"
                                            data-alamat="Jl. Sudirman No. 22, Bandung"
                                            data-jenis-kelamin="Perempuan"
                                            data-telp="083456789012"
                                            data-posisi="UI/UX Designer"
                                            data-gaji="11000000"
                                            data-email="dewi.a@example.com"
                                            class="p-1 rounded-full hover:bg-primary/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">edit</span></button>
                                        <button class="delete-btn"
                                            data-id="3"
                                            data-nama="Dewi Anggraini"
                                            class="p-1 rounded-full hover:bg-red-500/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                class="bg-background-light dark:bg-background-dark hover:bg-subtle-light dark:hover:bg-subtle-dark/40">
                                <td class="p-4 font-medium">4</td>
                                <td class="p-4">Eko Wijoyo</td>
                                <td class="p-4">Jl. Gajah Mada No. 8, Medan</td>
                                <td class="p-4">Laki-laki</td>
                                <td class="p-4">084567890123</td>
                                <td class="p-4">Project Manager</td>
                                <td class="p-4 text-right">Rp 15.000.000</td>
                                <td class="p-4">eko.w@example.com</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-btn"
                                            data-id="4"
                                            data-nama="Eko Wijoyo"
                                            data-alamat="Jl. Gajah Mada No. 8, Medan"
                                            data-jenis-kelamin="Laki-laki"
                                            data-telp="084567890123"
                                            data-posisi="Project Manager"
                                            data-gaji="15000000"
                                            data-email="eko.w@example.com"
                                            class="p-1 rounded-full hover:bg-primary/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">edit</span></button>
                                        <button class="delete-btn"
                                            data-id="4"
                                            data-nama="Eko Wijoyo"
                                            class="p-1 rounded-full hover:bg-red-500/20 text-text-light dark:text-text-dark"><span
                                                class="material-symbols-outlined text-lg">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Footer -->
            <footer class="text-center mt-6 text-sm text-text-light/60 dark:text-text-dark/60">
                <p>Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- Modal Tambah Karyawan -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-content-light dark:bg-content-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Tambah Karyawan</h3>
                    <button class="close-modal text-text-light dark:text-text-dark hover:text-gray-500">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>
                <form id="tambahForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Nama</label>
                        <input type="text" id="tambahNama" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Alamat</label>
                        <textarea id="tambahAlamat" rows="3" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Jenis Kelamin</label>
                        <select id="tambahJenisKelamin" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">No. Telp</label>
                        <input type="tel" id="tambahTelp" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Posisi</label>
                        <input type="text" id="tambahPosisi" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Gaji</label>
                        <input type="number" id="tambahGaji" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Email</label>
                        <input type="email" id="tambahEmail" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-subtle-light dark:bg-subtle-dark text-text-light dark:text-text-dark rounded-lg hover:bg-subtle-light/80 dark:hover:bg-subtle-dark/80">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Karyawan -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-content-light dark:bg-content-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Edit Karyawan</h3>
                    <button class="close-modal text-text-light dark:text-text-dark hover:text-gray-500">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="editId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Nama</label>
                        <input type="text" id="editNama" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Alamat</label>
                        <textarea id="editAlamat" rows="3" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Jenis Kelamin</label>
                        <select id="editJenisKelamin" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">No. Telp</label>
                        <input type="tel" id="editTelp" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Posisi</label>
                        <input type="text" id="editPosisi" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Gaji</label>
                        <input type="number" id="editGaji" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Email</label>
                        <input type="email" id="editEmail" class="w-full px-3 py-2 bg-subtle-light dark:bg-subtle-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-subtle-light dark:bg-subtle-dark text-text-light dark:text-text-dark rounded-lg hover:bg-subtle-light/80 dark:hover:bg-subtle-dark/80">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Karyawan -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-content-light dark:bg-content-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Konfirmasi Hapus</h3>
                    <button class="close-modal text-text-light dark:text-text-dark hover:text-gray-500">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-text-light dark:text-text-dark">Apakah Anda yakin ingin menghapus karyawan <span id="deleteNama" class="font-semibold"></span>?</p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 bg-subtle-light dark:bg-subtle-dark text-text-light dark:text-text-dark rounded-lg hover:bg-subtle-light/80 dark:hover:bg-subtle-dark/80">Batal</button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
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
            // Modal elements
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Buttons
            const tambahKaryawanBtn = document.getElementById('tambahKaryawanBtn');
            const editBtns = document.querySelectorAll('.edit-btn');
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const closeModals = document.querySelectorAll('.close-modal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const closeToastBtn = document.getElementById('closeToast');
            
            // Forms
            const tambahForm = document.getElementById('tambahForm');
            const editForm = document.getElementById('editForm');
            
            // Show tambah modal
            tambahKaryawanBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
                tambahForm.reset();
            });
            
            // Show edit modal with data
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const alamat = this.getAttribute('data-alamat');
                    const jenisKelamin = this.getAttribute('data-jenis-kelamin');
                    const telp = this.getAttribute('data-telp');
                    const posisi = this.getAttribute('data-posisi');
                    const gaji = this.getAttribute('data-gaji');
                    const email = this.getAttribute('data-email');
                    
                    document.getElementById('editId').value = id;
                    document.getElementById('editNama').value = nama;
                    document.getElementById('editAlamat').value = alamat;
                    document.getElementById('editJenisKelamin').value = jenisKelamin;
                    document.getElementById('editTelp').value = telp;
                    document.getElementById('editPosisi').value = posisi;
                    document.getElementById('editGaji').value = gaji;
                    document.getElementById('editEmail').value = email;
                    
                    editModal.classList.remove('hidden');
                });
            });
            
            // Show delete modal with data
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    
                    document.getElementById('deleteId').value = id;
                    document.getElementById('deleteNama').textContent = nama;
                    
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
                showToast('Karyawan berhasil ditambahkan!');
                tambahModal.classList.add('hidden');
                tambahForm.reset();
            });
            
            // Handle edit form submission
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Data karyawan berhasil diperbarui!');
                editModal.classList.add('hidden');
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Karyawan berhasil dihapus!');
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
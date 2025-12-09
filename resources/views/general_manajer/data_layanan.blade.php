<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Layanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <!-- Tambahkan kedua jenis Material Icons untuk kompatibilitas -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#8B5CF6", // A tasteful purple as a primary color
                        "background-light": "#F3F4F6", // Light gray
                        "background-dark": "#1F2937", // Dark gray
                        "surface-light": "#FFFFFF", // White
                        "surface-dark": "#374151", // Slightly lighter dark gray
                        "text-light": "#111827", // Dark text for light mode
                        "text-dark": "#F9FAFB", // Light text for dark mode
                        "subtle-light": "#6B7280", // Gray for secondary text on light
                        "subtle-dark": "#D1D5DB", // Lighter gray for secondary text on dark
                        "border-light": "#E5E7EB", // Light border
                        "border-dark": "#4B5567", // Dark border
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                        "lg": "0.75rem", // 12px
                        "xl": "1rem", // 16px
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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

<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <div class="flex h-screen">
        <!-- Include Sidebar dari file terpisah -->
        @include('general_manajer/templet/header')
        
        <main class="flex-1 flex flex-col overflow-hidden">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div class="w-full sm:w-auto">
                            <h2 class="text-2xl font-bold text-text-light dark:text-text-dark mb-4">Data Layanan</h2>
                            <button id="tambahLayananBtn"
                                class="flex items-center gap-2 bg-primary text-white font-semibold py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors">
                                <span class="material-icons-outlined">add</span>
                                <span>Tambah Layanan</span>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <div class="relative flex-grow sm:flex-grow-0">
                                <span
                                    class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-subtle-light dark:text-subtle-dark">search</span>
                                <input
                                    class="w-full sm:w-48 pl-10 pr-4 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Search..." type="text" />
                            </div>
                            <button
                                class="bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark font-semibold py-2 px-4 rounded-lg hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="bg-background-light dark:bg-background-dark text-xs uppercase text-subtle-light dark:text-subtle-dark">
                                <tr>
                                    <th class="px-6 py-3 rounded-l-lg" scope="col">No</th>
                                    <th class="px-6 py-3" scope="col">Nama Layanan</th>
                                    <th class="px-6 py-3" scope="col">Harga</th>
                                    <th class="px-6 py-3" scope="col">Durasi</th>
                                    <th class="px-6 py-3" scope="col">Deskripsi</th>
                                    <th class="px-6 py-3" scope="col">Kategori</th>
                                    <th class="px-6 py-3 rounded-r-lg" scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">1.</td>
                                    <td class="px-6 py-4 font-medium">Jasa Pembuatan Website</td>
                                    <td class="px-6 py-4">Rp 5.000.000</td>
                                    <td class="px-6 py-4">30 Hari</td>
                                    <td class="px-6 py-4">Website company profile...</td>
                                    <td class="px-6 py-4">Teknologi</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button class="edit-btn"
                                                data-id="1"
                                                data-nama="Jasa Pembuatan Website"
                                                data-harga="5000000"
                                                data-durasi="30 Hari"
                                                data-deskripsi="Website company profile"
                                                data-kategori="Teknologi"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="1"
                                                data-nama="Jasa Pembuatan Website"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-500 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    class="bg-background-light/50 dark:bg-background-dark/50 border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">2.</td>
                                    <td class="px-6 py-4 font-medium">Desain Logo</td>
                                    <td class="px-6 py-4">Rp 1.500.000</td>
                                    <td class="px-6 py-4">7 Hari</td>
                                    <td class="px-6 py-4">Desain logo profesional...</td>
                                    <td class="px-6 py-4">Desain</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button class="edit-btn"
                                                data-id="2"
                                                data-nama="Desain Logo"
                                                data-harga="1500000"
                                                data-durasi="7 Hari"
                                                data-deskripsi="Desain logo profesional"
                                                data-kategori="Desain"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="2"
                                                data-nama="Desain Logo"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-500 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="px-6 py-4">3.</td>
                                    <td class="px-6 py-4 font-medium">Manajemen Sosial Media</td>
                                    <td class="px-6 py-4">Rp 3.000.000</td>
                                    <td class="px-6 py-4">30 Hari</td>
                                    <td class="px-6 py-4">Kelola akun sosmed...</td>
                                    <td class="px-6 py-4">Marketing</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button class="edit-btn"
                                                data-id="3"
                                                data-nama="Manajemen Sosial Media"
                                                data-harga="3000000"
                                                data-durasi="30 Hari"
                                                data-deskripsi="Kelola akun sosmed"
                                                data-kategori="Marketing"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="3"
                                                data-nama="Manajemen Sosial Media"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-500 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-background-light/50 dark:bg-background-dark/50">
                                    <td class="px-6 py-4">4.</td>
                                    <td class="px-6 py-4 font-medium">Konsultasi SEO</td>
                                    <td class="px-6 py-4">Rp 2.000.000</td>
                                    <td class="px-6 py-4">14 Hari</td>
                                    <td class="px-6 py-4">Analisis dan strategi SEO...</td>
                                    <td class="px-6 py-4">Marketing</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button class="edit-btn"
                                                data-id="4"
                                                data-nama="Konsultasi SEO"
                                                data-harga="2000000"
                                                data-durasi="14 Hari"
                                                data-deskripsi="Analisis dan strategi SEO"
                                                data-kategori="Marketing"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">edit</span>
                                            </button>
                                            <button class="delete-btn"
                                                data-id="4"
                                                data-nama="Konsultasi SEO"
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 text-red-500 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 transition-colors">
                                                <span class="material-icons-outlined text-xl">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Table navigation" class="flex justify-center items-center pt-6 text-sm">
                        <ul class="inline-flex items-center -space-x-px">
                            <li>
                                <a class="px-3 py-2 ml-0 leading-tight text-subtle-light dark:text-subtle-dark bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-l-lg hover:bg-background-light dark:hover:bg-background-dark"
                                    href="#">
                                    <span class="material-icons-outlined text-base">chevron_left</span>
                                </a>
                            </li>
                            <li>
                                <a aria-current="page"
                                    class="px-3 py-2 leading-tight text-white bg-primary border border-primary hover:bg-primary/90"
                                    href="#">1</a>
                            </li>
                            <li>
                                <a class="px-3 py-2 leading-tight text-subtle-light dark:text-subtle-dark bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark"
                                    href="#">2</a>
                            </li>
                            <li>
                                <a class="px-3 py-2 leading-tight text-subtle-light dark:text-subtle-dark bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark"
                                    href="#">3</a>
                            </li>
                            <li>
                                <a class="px-3 py-2 leading-tight text-subtle-light dark:text-subtle-dark bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-r-lg hover:bg-background-light dark:hover:bg-background-dark"
                                    href="#">
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <footer
                class="text-center p-4 text-sm text-subtle-light dark:text-subtle-dark bg-surface-light dark:bg-surface-dark border-t border-border-light dark:border-border-dark">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Tambah Layanan -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Tambah Layanan</h3>
                    <button class="close-modal text-text-light dark:text-text-dark hover:text-gray-500">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
                <form id="tambahForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Nama Layanan</label>
                        <input type="text" id="tambahNama" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Harga</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">Rp</span>
                            <input type="number" id="tambahHarga" class="w-full pl-10 pr-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Durasi</label>
                        <input type="text" id="tambahDurasi" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Contoh: 30 Hari" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Deskripsi</label>
                        <textarea id="tambahDeskripsi" rows="3" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Kategori</label>
                        <select id="tambahKategori" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Desain">Desain</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Konsultasi">Konsultasi</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark rounded-lg hover:bg-opacity-80">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-opacity-90">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Layanan -->
    <div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Edit Layanan</h3>
                    <button class="close-modal text-text-light dark:text-text-dark hover:text-gray-500">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="editId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Nama Layanan</label>
                        <input type="text" id="editNama" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Harga</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-light dark:text-text-dark">Rp</span>
                            <input type="number" id="editHarga" class="w-full pl-10 pr-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Durasi</label>
                        <input type="text" id="editDurasi" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" rows="3" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Kategori</label>
                        <select id="editKategori" class="w-full px-3 py-2 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Desain">Desain</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Konsultasi">Konsultasi</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark rounded-lg hover:bg-opacity-80">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-opacity-90">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Layanan -->
    <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-light dark:text-text-dark">Konfirmasi Hapus</h3>
                    <button class="close-modal text-text-light dark:text-text-dark hover:text-gray-500">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-text-light dark:text-text-dark">Apakah Anda yakin ingin menghapus layanan <span id="deleteNama" class="font-semibold"></span>?</p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteId">
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark rounded-lg hover:bg-opacity-80">Batal</button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons-outlined text-lg">close</span>
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
            const tambahLayananBtn = document.getElementById('tambahLayananBtn');
            const editBtns = document.querySelectorAll('.edit-btn');
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const closeModals = document.querySelectorAll('.close-modal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const closeToastBtn = document.getElementById('closeToast');
            
            // Forms
            const tambahForm = document.getElementById('tambahForm');
            const editForm = document.getElementById('editForm');
            
            // Show tambah modal
            tambahLayananBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
                tambahForm.reset();
            });
            
            // Show edit modal with data
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const harga = this.getAttribute('data-harga');
                    const durasi = this.getAttribute('data-durasi');
                    const deskripsi = this.getAttribute('data-deskripsi');
                    const kategori = this.getAttribute('data-kategori');
                    
                    document.getElementById('editId').value = id;
                    document.getElementById('editNama').value = nama;
                    document.getElementById('editHarga').value = harga;
                    document.getElementById('editDurasi').value = durasi;
                    document.getElementById('editDeskripsi').value = deskripsi;
                    document.getElementById('editKategori').value = kategori;
                    
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
                showToast('Layanan berhasil ditambahkan!');
                tambahModal.classList.add('hidden');
                tambahForm.reset();
            });
            
            // Handle edit form submission
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would send the form data to the server
                // For demo purposes, we'll just show a success message
                showToast('Data layanan berhasil diperbarui!');
                editModal.classList.add('hidden');
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const id = document.getElementById('deleteId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Layanan berhasil dihapus!');
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
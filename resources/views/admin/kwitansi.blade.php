<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kwitansi Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // Using a blue color as a neutral primary
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        .material-icons-outlined {
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

        .active .material-symbols-outlined {
            font-variation-settings:
                'FILL' 1
        }
        
        .active .material-icons-outlined {
            font-weight: bold;
        }
        
        /* Modal styles */
        .modal {
            transition: opacity 0.3s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
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
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
    <div class="flex h-screen">
        @include('admin/templet/sider')
        <main class="flex-1 flex flex-col">
            <div class="flex-grow p-8">
                <!-- Baris pertama: Judul -->
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50">Kwitansi</h1>
                </div>
                
                <!-- Baris kedua: Tombol Buat Kwitansi di kiri, Search dan Filter di kanan -->
                <div class="flex justify-between items-center mb-6">
                    <button id="buatKwitansiBtn"
                        class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg shadow-sm hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined">add</span>
                        <span>Buat Kwitansi</span>
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                            <input
                                class="w-72 bg-slate-100 dark:bg-slate-800 border-none rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary"
                                placeholder="Search..." type="text" />
                        </div>
                        <button
                            class="bg-slate-200 dark:bg-slate-700 px-4 py-2 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">Filter</button>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <tr>
                                <th class="p-4">No</th>
                                <th class="p-4">Nama</th>
                                <th class="p-4">Nomor Order</th>
                                <th class="p-4">Detail Layanan</th>
                                <th class="p-4">Harga dan Pajak</th>
                                <th class="p-4">Metode Pembayaran</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <tr>
                                <td class="p-4">1.</td>
                                <td class="p-4">John Doe</td>
                                <td class="p-4">ORD-202405-001</td>
                                <td class="p-4">Website Development</td>
                                <td class="p-4">Rp 5.500.000</td>
                                <td class="p-4">Bank Transfer</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="1"
                                            data-nama="John Doe"
                                            data-nomor-order="ORD-202405-001"
                                            data-layanan="Website Development"
                                            data-harga="5500000"
                                            data-pembayaran="Bank Transfer"
                                            data-tooltip="Edit">
                                            <span class="material-symbols-outlined text-blue-500">edit</span>
                                        </button>
                                        <button class="delete-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="1"
                                            data-nama="John Doe"
                                            data-nomor-order="ORD-202405-001"
                                            data-tooltip="Hapus">
                                            <span class="material-symbols-outlined text-red-500">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4">2.</td>
                                <td class="p-4">Jane Smith</td>
                                <td class="p-4">ORD-202405-002</td>
                                <td class="p-4">SEO Optimization</td>
                                <td class="p-4">Rp 2.200.000</td>
                                <td class="p-4">E-Wallet</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="2"
                                            data-nama="Jane Smith"
                                            data-nomor-order="ORD-202405-002"
                                            data-layanan="SEO Optimization"
                                            data-harga="2200000"
                                            data-pembayaran="E-Wallet"
                                            data-tooltip="Edit">
                                            <span class="material-symbols-outlined text-blue-500">edit</span>
                                        </button>
                                        <button class="delete-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="2"
                                            data-nama="Jane Smith"
                                            data-nomor-order="ORD-202405-002"
                                            data-tooltip="Hapus">
                                            <span class="material-symbols-outlined text-red-500">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4">3.</td>
                                <td class="p-4">Robert Brown</td>
                                <td class="p-4">ORD-202405-003</td>
                                <td class="p-4">Cloud Hosting (1 Year)</td>
                                <td class="p-4">Rp 1.650.000</td>
                                <td class="p-4">Credit Card</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="3"
                                            data-nama="Robert Brown"
                                            data-nomor-order="ORD-202405-003"
                                            data-layanan="Cloud Hosting (1 Year)"
                                            data-harga="1650000"
                                            data-pembayaran="Credit Card"
                                            data-tooltip="Edit">
                                            <span class="material-symbols-outlined text-blue-500">edit</span>
                                        </button>
                                        <button class="delete-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="3"
                                            data-nama="Robert Brown"
                                            data-nomor-order="ORD-202405-003"
                                            data-tooltip="Hapus">
                                            <span class="material-symbols-outlined text-red-500">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4">4.</td>
                                <td class="p-4">Emily Johnson</td>
                                <td class="p-4">ORD-202405-004</td>
                                <td class="p-4">Logo Design</td>
                                <td class="p-4">Rp 880.000</td>
                                <td class="p-4">Bank Transfer</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <button class="edit-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="4"
                                            data-nama="Emily Johnson"
                                            data-nomor-order="ORD-202405-004"
                                            data-layanan="Logo Design"
                                            data-harga="880000"
                                            data-pembayaran="Bank Transfer"
                                            data-tooltip="Edit">
                                            <span class="material-symbols-outlined text-blue-500">edit</span>
                                        </button>
                                        <button class="delete-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            data-id="4"
                                            data-nama="Emily Johnson"
                                            data-nomor-order="ORD-202405-004"
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
            <footer class="text-center p-4 bg-slate-200 dark:bg-slate-900 text-sm text-slate-600 dark:text-slate-400">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Buat Kwitansi -->
    <div id="buatKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Buat Kwitansi Baru</h3>
                    <p class="text-slate-600 dark:text-slate-400">Isi form di bawah untuk membuat kwitansi baru</p>
                </div>
                <button onclick="closeBuatKwitansiModal()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="buatKwitansiForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Klien</label>
                            <input type="text" id="namaKlien" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Order</label>
                            <input type="text" id="nomorOrder" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Detail Layanan</label>
                        <textarea id="detailLayanan" rows="3" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="harga" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pajak (%)</label>
                            <input type="number" id="pajak" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Metode Pembayaran</label>
                        <select id="metodePembayaran" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Pembayaran</label>
                        <input type="date" id="tanggalPembayaran" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Keterangan Tambahan</label>
                        <textarea id="keterangan" rows="3" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-3">
                <button onclick="closeBuatKwitansiModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitBuatKwitansi()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-600 transition-colors">
                    Buat Kwitansi
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kwitansi -->
    <div id="editKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Edit Kwitansi</h3>
                    <p class="text-slate-600 dark:text-slate-400">Ubah informasi kwitansi di bawah</p>
                </div>
                <button onclick="closeEditKwitansiModal()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="editKwitansiForm" class="space-y-4">
                    <input type="hidden" id="editKwitansiId">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Klien</label>
                            <input type="text" id="editNamaKlien" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Order</label>
                            <input type="text" id="editNomorOrder" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Detail Layanan</label>
                        <textarea id="editDetailLayanan" rows="3" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pajak (%)</label>
                            <input type="number" id="editPajak" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Metode Pembayaran</label>
                        <select id="editMetodePembayaran" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Pembayaran</label>
                        <input type="date" id="editTanggalPembayaran" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Keterangan Tambahan</label>
                        <textarea id="editKeterangan" rows="3" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-3">
                <button onclick="closeEditKwitansiModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitEditKwitansi()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-600 transition-colors">
                    Update Kwitansi
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Kwitansi -->
    <div id="deleteKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Konfirmasi Hapus</h3>
                    <button onclick="closeDeleteKwitansiModal()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-slate-900 dark:text-slate-50">Apakah Anda yakin ingin menghapus kwitansi untuk <span id="deleteKwitansiNama" class="font-semibold"></span> dengan nomor order <span id="deleteKwitansiNomor" class="font-semibold"></span>?</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteKwitansiId">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeDeleteKwitansiModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600">Batal</button>
                    <button id="confirmDeleteKwitansi" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
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
            // Event listener untuk tombol Buat Kwitansi
            const buatKwitansiBtn = document.getElementById('buatKwitansiBtn');
            if (buatKwitansiBtn) {
                buatKwitansiBtn.addEventListener('click', function() {
                    document.getElementById('buatKwitansiModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Event listeners untuk tombol Edit
            const editBtns = document.querySelectorAll('.edit-kwitansi-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const nomorOrder = this.getAttribute('data-nomor-order');
                    const layanan = this.getAttribute('data-layanan');
                    const harga = this.getAttribute('data-harga');
                    const pembayaran = this.getAttribute('data-pembayaran');
                    
                    document.getElementById('editKwitansiId').value = id;
                    document.getElementById('editNamaKlien').value = nama;
                    document.getElementById('editNomorOrder').value = nomorOrder;
                    document.getElementById('editDetailLayanan').value = layanan;
                    document.getElementById('editHarga').value = harga;
                    document.getElementById('editMetodePembayaran').value = pembayaran;
                    
                    document.getElementById('editKwitansiModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Event listeners untuk tombol Hapus
            const deleteBtns = document.querySelectorAll('.delete-kwitansi-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const nomorOrder = this.getAttribute('data-nomor-order');
                    
                    document.getElementById('deleteKwitansiId').value = id;
                    document.getElementById('deleteKwitansiNama').textContent = nama;
                    document.getElementById('deleteKwitansiNomor').textContent = nomorOrder;
                    
                    document.getElementById('deleteKwitansiModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Close modal when clicking outside
            document.getElementById('buatKwitansiModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBuatKwitansiModal();
                }
            });
            
            document.getElementById('editKwitansiModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditKwitansiModal();
                }
            });
            
            document.getElementById('deleteKwitansiModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteKwitansiModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!document.getElementById('buatKwitansiModal').classList.contains('hidden')) {
                        closeBuatKwitansiModal();
                    }
                    if (!document.getElementById('editKwitansiModal').classList.contains('hidden')) {
                        closeEditKwitansiModal();
                    }
                    if (!document.getElementById('deleteKwitansiModal').classList.contains('hidden')) {
                        closeDeleteKwitansiModal();
                    }
                }
            });
            
            // Close toast notification
            document.getElementById('closeToast').addEventListener('click', function() {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Confirm delete kwitansi
            document.getElementById('confirmDeleteKwitansi').addEventListener('click', function() {
                const id = document.getElementById('deleteKwitansiId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Kwitansi berhasil dihapus!');
                closeDeleteKwitansiModal();
            });
        });
        
        // Modal functions for Buat Kwitansi
        function closeBuatKwitansiModal() {
            document.getElementById('buatKwitansiModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('buatKwitansiForm').reset();
        }
        
        function submitBuatKwitansi() {
            const form = document.getElementById('buatKwitansiForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = {
                namaKlien: document.getElementById('namaKlien').value,
                nomorOrder: document.getElementById('nomorOrder').value,
                detailLayanan: document.getElementById('detailLayanan').value,
                harga: document.getElementById('harga').value,
                pajak: document.getElementById('pajak').value,
                metodePembayaran: document.getElementById('metodePembayaran').value,
                tanggalPembayaran: document.getElementById('tanggalPembayaran').value,
                keterangan: document.getElementById('keterangan').value
            };
            
            // In a real application, you would send this data to the server
            console.log('Form data:', formData);
            
            // Show success message
            showToast('Kwitansi berhasil dibuat!');
            
            // Close modal
            closeBuatKwitansiModal();
        }
        
        // Modal functions for Edit Kwitansi
        function closeEditKwitansiModal() {
            document.getElementById('editKwitansiModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function submitEditKwitansi() {
            const form = document.getElementById('editKwitansiForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = {
                id: document.getElementById('editKwitansiId').value,
                namaKlien: document.getElementById('editNamaKlien').value,
                nomorOrder: document.getElementById('editNomorOrder').value,
                detailLayanan: document.getElementById('editDetailLayanan').value,
                harga: document.getElementById('editHarga').value,
                pajak: document.getElementById('editPajak').value,
                metodePembayaran: document.getElementById('editMetodePembayaran').value,
                tanggalPembayaran: document.getElementById('editTanggalPembayaran').value,
                keterangan: document.getElementById('editKeterangan').value
            };
            
            // In a real application, you would send this data to the server
            console.log('Form data:', formData);
            
            // Show success message
            showToast('Kwitansi berhasil diperbarui!');
            
            // Close modal
            closeEditKwitansiModal();
        }
        
        // Modal functions for Delete Kwitansi
        function closeDeleteKwitansiModal() {
            document.getElementById('deleteKwitansiModal').classList.add('hidden');
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Invoice Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1d4ed8", // Using a sample blue for primary, as none is specified
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
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
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .material-icons {
            font-size: 20px;
        }
        
        .material-icons-outlined {
            font-size: 20px;
        }
        
        /* Tambahkan style untuk glass effect jika diperlukan */
        .glass-effect {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .dark .glass-effect {
            background-color: rgba(31, 41, 55, 0.7);
            border-right: 1px solid rgba(75, 85, 99, 0.3);
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

<body class="font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100">
    <div class="flex h-screen">
       @include('admin/templet/header')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <div class="p-8 flex-1 overflow-y-auto">
                <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Invoice</h2>
                <div class="flex justify-between items-center mb-6">
                    <button id="buatInvoiceBtn"
                        class="flex items-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <span class="material-icons mr-2">add</span>
                        Buat Invoice
                    </button>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <span
                                class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                            <input
                                class="w-72 bg-gray-200 dark:bg-gray-700 border-none rounded-lg pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary"
                                placeholder="Search..." type="text" />
                        </div>
                        <button
                            class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Filter
                        </button>
                    </div>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="p-4 w-16 text-sm font-semibold text-gray-600 dark:text-gray-400">NO</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">NAMA</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">NOMOR ORDER</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">DETAIL LAYANAN
                                </th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">HARGA DAN PAJAK
                                </th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">METODE PEMBAYARAN
                                </th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="p-4">1.</td>
                                <td class="p-4">PT. Maju Bersama</td>
                                <td class="p-4">#ORD-2024-001</td>
                                <td class="p-4">Pengembangan Website Company Profile</td>
                                <td class="p-4">Rp 15.000.000 (termasuk PPN 11%)</td>
                                <td class="p-4">Transfer Bank</td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="edit-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="1"
                                            data-nama="PT. Maju Bersama"
                                            data-nomor-order="#ORD-2024-001"
                                            data-layanan="Pengembangan Website Company Profile"
                                            data-harga="15000000"
                                            data-pajak="11"
                                            data-pembayaran="Transfer Bank"
                                            data-tooltip="Edit">
                                            <span class="material-icons text-blue-600 dark:text-blue-400">edit</span>
                                        </button>
                                        <button class="delete-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="1"
                                            data-nama="PT. Maju Bersama"
                                            data-nomor-order="#ORD-2024-001"
                                            data-tooltip="Hapus">
                                            <span class="material-icons text-red-600 dark:text-red-400">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="p-4">2.</td>
                                <td class="p-4">CV. Sejahtera Abadi</td>
                                <td class="p-4">#ORD-2024-002</td>
                                <td class="p-4">Jasa Digital Marketing</td>
                                <td class="p-4">Rp 8.500.000 (termasuk PPN 11%)</td>
                                <td class="p-4">Kartu Kredit</td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="edit-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="2"
                                            data-nama="CV. Sejahtera Abadi"
                                            data-nomor-order="#ORD-2024-002"
                                            data-layanan="Jasa Digital Marketing"
                                            data-harga="8500000"
                                            data-pajak="11"
                                            data-pembayaran="Kartu Kredit"
                                            data-tooltip="Edit">
                                            <span class="material-icons text-blue-600 dark:text-blue-400">edit</span>
                                        </button>
                                        <button class="delete-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="2"
                                            data-nama="CV. Sejahtera Abadi"
                                            data-nomor-order="#ORD-2024-002"
                                            data-tooltip="Hapus">
                                            <span class="material-icons text-red-600 dark:text-red-400">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="p-4">3.</td>
                                <td class="p-4">UD. Sukses Makmur</td>
                                <td class="p-4">#ORD-2024-003</td>
                                <td class="p-4">Desain Logo dan Brand Identity</td>
                                <td class="p-4">Rp 5.000.000 (termasuk PPN 11%)</td>
                                <td class="p-4">E-Wallet</td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="edit-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="3"
                                            data-nama="UD. Sukses Makmur"
                                            data-nomor-order="#ORD-2024-003"
                                            data-layanan="Desain Logo dan Brand Identity"
                                            data-harga="5000000"
                                            data-pajak="11"
                                            data-pembayaran="E-Wallet"
                                            data-tooltip="Edit">
                                            <span class="material-icons text-blue-600 dark:text-blue-400">edit</span>
                                        </button>
                                        <button class="delete-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="3"
                                            data-nama="UD. Sukses Makmur"
                                            data-nomor-order="#ORD-2024-003"
                                            data-tooltip="Hapus">
                                            <span class="material-icons text-red-600 dark:text-red-400">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4">4.</td>
                                <td class="p-4">PT. Teknologi Nusantara</td>
                                <td class="p-4">#ORD-2024-004</td>
                                <td class="p-4">Maintenance Aplikasi Mobile</td>
                                <td class="p-4">Rp 12.000.000 (termasuk PPN 11%)</td>
                                <td class="p-4">Transfer Bank</td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="edit-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="4"
                                            data-nama="PT. Teknologi Nusantara"
                                            data-nomor-order="#ORD-2024-004"
                                            data-layanan="Maintenance Aplikasi Mobile"
                                            data-harga="12000000"
                                            data-pajak="11"
                                            data-pembayaran="Transfer Bank"
                                            data-tooltip="Edit">
                                            <span class="material-icons text-blue-600 dark:text-blue-400">edit</span>
                                        </button>
                                        <button class="delete-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            data-id="4"
                                            data-nama="PT. Teknologi Nusantara"
                                            data-nomor-order="#ORD-2024-004"
                                            data-tooltip="Hapus">
                                            <span class="material-icons text-red-600 dark:text-red-400">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <footer class="bg-gray-200 dark:bg-gray-800 text-center py-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- Modal Buat Invoice -->
    <div id="buatInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buat Invoice Baru</h3>
                    <p class="text-gray-600 dark:text-gray-400">Isi form di bawah untuk membuat invoice baru</p>
                </div>
                <button onclick="closeBuatInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="buatInvoiceForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Klien</label>
                            <input type="text" id="namaKlien" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Order</label>
                            <input type="text" id="nomorOrder" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detail Layanan</label>
                        <textarea id="detailLayanan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="harga" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pajak (%)</label>
                            <input type="number" id="pajak" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                        <select id="metodePembayaran" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Kartu Kredit">Kartu Kredit</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan Tambahan</label>
                        <textarea id="keterangan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closeBuatInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitBuatInvoice()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Buat Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Invoice -->
    <div id="editInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Invoice</h3>
                    <p class="text-gray-600 dark:text-gray-400">Ubah informasi invoice di bawah</p>
                </div>
                <button onclick="closeEditInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="editInvoiceForm" class="space-y-4">
                    <input type="hidden" id="editInvoiceId">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Klien</label>
                            <input type="text" id="editNamaKlien" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Order</label>
                            <input type="text" id="editNomorOrder" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detail Layanan</label>
                        <textarea id="editDetailLayanan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pajak (%)</label>
                            <input type="number" id="editPajak" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                        <select id="editMetodePembayaran" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Kartu Kredit">Kartu Kredit</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan Tambahan</label>
                        <textarea id="editKeterangan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closeEditInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitEditInvoice()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Update Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Invoice -->
    <div id="deleteInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <button onclick="closeDeleteInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus invoice <span id="deleteInvoiceNama" class="font-semibold"></span> dengan nomor order <span id="deleteInvoiceNomor" class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteInvoiceId">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeDeleteInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Batal</button>
                    <button id="confirmDeleteInvoice" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
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
            // Event listener untuk tombol Buat Invoice
            const buatInvoiceBtn = document.getElementById('buatInvoiceBtn');
            if (buatInvoiceBtn) {
                buatInvoiceBtn.addEventListener('click', function() {
                    document.getElementById('buatInvoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Event listeners untuk tombol Edit
            const editBtns = document.querySelectorAll('.edit-invoice-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const nomorOrder = this.getAttribute('data-nomor-order');
                    const layanan = this.getAttribute('data-layanan');
                    const harga = this.getAttribute('data-harga');
                    const pajak = this.getAttribute('data-pajak');
                    const pembayaran = this.getAttribute('data-pembayaran');
                    
                    document.getElementById('editInvoiceId').value = id;
                    document.getElementById('editNamaKlien').value = nama;
                    document.getElementById('editNomorOrder').value = nomorOrder;
                    document.getElementById('editDetailLayanan').value = layanan;
                    document.getElementById('editHarga').value = harga;
                    document.getElementById('editPajak').value = pajak;
                    document.getElementById('editMetodePembayaran').value = pembayaran;
                    
                    document.getElementById('editInvoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Event listeners untuk tombol Hapus
            const deleteBtns = document.querySelectorAll('.delete-invoice-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const nomorOrder = this.getAttribute('data-nomor-order');
                    
                    document.getElementById('deleteInvoiceId').value = id;
                    document.getElementById('deleteInvoiceNama').textContent = nama;
                    document.getElementById('deleteInvoiceNomor').textContent = nomorOrder;
                    
                    document.getElementById('deleteInvoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Close modal when clicking outside
            document.getElementById('buatInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBuatInvoiceModal();
                }
            });
            
            document.getElementById('editInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditInvoiceModal();
                }
            });
            
            document.getElementById('deleteInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteInvoiceModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!document.getElementById('buatInvoiceModal').classList.contains('hidden')) {
                        closeBuatInvoiceModal();
                    }
                    if (!document.getElementById('editInvoiceModal').classList.contains('hidden')) {
                        closeEditInvoiceModal();
                    }
                    if (!document.getElementById('deleteInvoiceModal').classList.contains('hidden')) {
                        closeDeleteInvoiceModal();
                    }
                }
            });
            
            // Close toast notification
            document.getElementById('closeToast').addEventListener('click', function() {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });
            
            // Confirm delete invoice
            document.getElementById('confirmDeleteInvoice').addEventListener('click', function() {
                const id = document.getElementById('deleteInvoiceId').value;
                
                // In a real application, you would send a delete request to the server
                // For demo purposes, we'll just show a success message
                showToast('Invoice berhasil dihapus!');
                closeDeleteInvoiceModal();
            });
        });
        
        // Modal functions for Buat Invoice
        function closeBuatInvoiceModal() {
            document.getElementById('buatInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('buatInvoiceForm').reset();
        }
        
        function submitBuatInvoice() {
            const form = document.getElementById('buatInvoiceForm');
            
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
                keterangan: document.getElementById('keterangan').value
            };
            
            // In a real application, you would send this data to the server
            console.log('Form data:', formData);
            
            // Show success message
            showToast('Invoice berhasil dibuat!');
            
            // Close modal
            closeBuatInvoiceModal();
        }
        
        // Modal functions for Edit Invoice
        function closeEditInvoiceModal() {
            document.getElementById('editInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function submitEditInvoice() {
            const form = document.getElementById('editInvoiceForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = {
                id: document.getElementById('editInvoiceId').value,
                namaKlien: document.getElementById('editNamaKlien').value,
                nomorOrder: document.getElementById('editNomorOrder').value,
                detailLayanan: document.getElementById('editDetailLayanan').value,
                harga: document.getElementById('editHarga').value,
                pajak: document.getElementById('editPajak').value,
                metodePembayaran: document.getElementById('editMetodePembayaran').value,
                keterangan: document.getElementById('editKeterangan').value
            };
            
            // In a real application, you would send this data to the server
            console.log('Form data:', formData);
            
            // Show success message
            showToast('Invoice berhasil diperbarui!');
            
            // Close modal
            closeEditInvoiceModal();
        }
        
        // Modal functions for Delete Invoice
        function closeDeleteInvoiceModal() {
            document.getElementById('deleteInvoiceModal').classList.add('hidden');
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
        
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const icon = document.getElementById(dropdownId.replace('-dropdown', '-icon'));
            
            dropdown.classList.toggle('hidden');
            
            // Rotate the icon
            if (dropdown.classList.contains('hidden')) {
                icon.textContent = 'expand_more';
            } else {
                icon.textContent = 'expand_less';
            }
        }
    </script>
</body>

</html>
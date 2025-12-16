<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kelola Invoice</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        /* Loading spinner */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100">
    <div class="flex h-screen">
       @include('admin/templet/sider')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <div class="p-8 flex-1 overflow-y-auto">
                <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Kelola Invoice</h2>
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
                            <input id="searchInput"
                                class="w-72 bg-gray-200 dark:bg-gray-700 border-none rounded-lg pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary"
                                placeholder="Search..." type="text" />
                        </div>
                        <button
                            class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Filter
                        </button>
                    </div>
                </div>
                <!-- Changed overflow-hidden to overflow-x-auto to handle wide table -->
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="p-4 w-16 text-sm font-semibold text-gray-600 dark:text-gray-400">NO</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">NAMA PERUSAHAAN</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">TANGGAL</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">NOMOR ORDER</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">NAMA KLIEN</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">ALAMAT</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">DESKRIPSI</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">HARGA</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">QTY</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">TOTAL</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">PAJAK (%)</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">METODE BAYAR</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <!-- Data will be populated here -->
                            <tr id="loadingRow">
                                <td colspan="13" class="p-4 text-center">
                                    <div class="flex justify-center items-center">
                                        <div class="spinner"></div>
                                        <span class="ml-2">Memuat data...</span>
                                    </div>
                                </td>
                            </tr>
                            <tr id="noDataRow" class="hidden">
                                <td colspan="13" class="p-4 text-center">Tidak ada data invoice</td>
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
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                            <input type="text" id="namaPerusahaan" name="nama_perusahaan" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Order</label>
                            <input type="text" id="nomorOrder" name="nomor_order" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Klien</label>
                            <input type="text" id="namaKlien" name="nama_klien" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="harga" name="harga" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Qty</label>
                            <input type="number" id="qty" name="qty" min="1" value="1" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pajak (%)</label>
                            <input type="number" id="pajak" name="pajak" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                        <select id="metodePembayaran" name="metode_pembayaran" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
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
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
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
                    <input type="hidden" id="editInvoiceId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                            <input type="text" id="editNamaPerusahaan" name="nama_perusahaan" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                            <input type="date" id="editTanggal" name="tanggal" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Order</label>
                            <input type="text" id="editNomorOrder" name="nomor_order" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Klien</label>
                            <input type="text" id="editNamaKlien" name="nama_klien" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <input type="text" id="editAlamat" name="alamat" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" name="harga" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Qty</label>
                            <input type="number" id="editQty" name="qty" min="1" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pajak (%)</label>
                            <input type="number" id="editPajak" name="pajak" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                        <select id="editMetodePembayaran" name="metode_pembayaran" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cash">Cash</option>
                        </select>
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
                    <p class="text-gray-900 dark:text-white">Apakah Anda yakin ingin menghapus invoice untuk <span id="deleteInvoiceNama" class="font-semibold"></span> dengan nomor order <span id="deleteInvoiceNomor" class="font-semibold"></span>?</p>
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
            // Load invoice data when page loads
            loadInvoices();
            
            // Event listener for search input
            document.getElementById('searchInput').addEventListener('input', function() {
                loadInvoices(this.value);
            });
            
            // Event listener untuk tombol Buat Invoice
            const buatInvoiceBtn = document.getElementById('buatInvoiceBtn');
            if (buatInvoiceBtn) {
                buatInvoiceBtn.addEventListener('click', function() {
                    document.getElementById('buatInvoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
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
                deleteInvoice(id);
            });
        });
        
        // Function to load invoices from API
        function loadInvoices(search = '') {
            const loadingRow = document.getElementById('loadingRow');
            const noDataRow = document.getElementById('noDataRow');
            const tableBody = document.getElementById('invoiceTableBody');
            
            // Show loading
            loadingRow.classList.remove('hidden');
            noDataRow.classList.add('hidden');
            
            // Remove existing invoice rows
            const existingRows = tableBody.querySelectorAll('.invoice-row');
            existingRows.forEach(row => row.remove());
            
            // Fetch data from API
            const url = search ? `/api/invoices?search=${encodeURIComponent(search)}` : '/api/invoices';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log("Response status:", response.status);
                
                // Check if response is not OK
                if (!response.ok) {
                    // Try to get error text from response
                    return response.text().then(text => {
                        console.error("Error response text:", text);
                        throw new Error(`Server error: ${response.status} ${response.statusText}`);
                    });
                }
                
                // Check content-type to ensure it's JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error("Non-JSON response:", text);
                        throw new Error('Server did not return JSON');
                    });
                }
                
                return response.json();
            })
            .then(data => {
                loadingRow.classList.add('hidden');
                
                if (data.data && data.data.length > 0) {
                    // Populate table with invoice data
                    data.data.forEach((invoice, index) => {
                        const row = document.createElement('tr');
                        row.className = 'border-b border-gray-200 dark:border-gray-700 invoice-row';
                        
                        // Format date
                        const date = new Date(invoice.tanggal);
                        const formattedDate = date.toLocaleDateString('id-ID');
                        
                        // Format currency
                        const formattedHarga = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(invoice.harga);
                        
                        // Format total
                        const total = invoice.harga * invoice.qty + ((invoice.harga * invoice.qty) * invoice.pajak / 100);
                        const formattedTotal = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(total);
                        
                        row.innerHTML = `
                            <td class="p-4">${index + 1}.</td>
                            <td class="p-4">${invoice.nama_perusahaan}</td>
                            <td class="p-4">${formattedDate}</td>
                            <td class="p-4">${invoice.nomor_order}</td>
                            <td class="p-4">${invoice.nama_klien}</td>
                            <td class="p-4">${invoice.alamat}</td>
                            <td class="p-4">${invoice.deskripsi}</td>
                            <td class="p-4">${formattedHarga}</td>
                            <td class="p-4">${invoice.qty}</td>
                            <td class="p-4">${formattedTotal}</td>
                            <td class="p-4">${invoice.pajak}</td>
                            <td class="p-4">${invoice.metode_pembayaran}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="edit-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                        data-id="${invoice.id}"
                                        data-nama-perusahaan="${invoice.nama_perusahaan}"
                                        data-tanggal="${invoice.tanggal}"
                                        data-nomor-order="${invoice.nomor_order}"
                                        data-nama-klien="${invoice.nama_klien}"
                                        data-alamat="${invoice.alamat}"
                                        data-deskripsi="${invoice.deskripsi}"
                                        data-harga="${invoice.harga}"
                                        data-qty="${invoice.qty}"
                                        data-pajak="${invoice.pajak}"
                                        data-metode-pembayaran="${invoice.metode_pembayaran}"
                                        data-tooltip="Edit">
                                        <span class="material-icons text-blue-600 dark:text-blue-400">edit</span>
                                    </button>
                                    <button class="delete-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                        data-id="${invoice.id}"
                                        data-nama-perusahaan="${invoice.nama_perusahaan}"
                                        data-nomor-order="${invoice.nomor_order}"
                                        data-tooltip="Hapus">
                                        <span class="material-icons text-red-600 dark:text-red-400">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to edit buttons
                    document.querySelectorAll('.edit-invoice-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const namaPerusahaan = this.getAttribute('data-nama-perusahaan');
                            const tanggal = this.getAttribute('data-tanggal');
                            const nomorOrder = this.getAttribute('data-nomor-order');
                            const namaKlien = this.getAttribute('data-nama-klien');
                            const alamat = this.getAttribute('data-alamat');
                            const deskripsi = this.getAttribute('data-deskripsi');
                            const harga = this.getAttribute('data-harga');
                            const qty = this.getAttribute('data-qty');
                            const pajak = this.getAttribute('data-pajak');
                            const metodePembayaran = this.getAttribute('data-metode-pembayaran');
                            
                            document.getElementById('editInvoiceId').value = id;
                            document.getElementById('editNamaPerusahaan').value = namaPerusahaan;
                            document.getElementById('editTanggal').value = tanggal;
                            document.getElementById('editNomorOrder').value = nomorOrder;
                            document.getElementById('editNamaKlien').value = namaKlien;
                            document.getElementById('editAlamat').value = alamat;
                            document.getElementById('editDeskripsi').value = deskripsi;
                            document.getElementById('editHarga').value = harga;
                            document.getElementById('editQty').value = qty;
                            document.getElementById('editPajak').value = pajak;
                            document.getElementById('editMetodePembayaran').value = metodePembayaran;
                            
                            document.getElementById('editInvoiceModal').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        });
                    });
                    
                    // Add event listeners to delete buttons
                    document.querySelectorAll('.delete-invoice-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const namaPerusahaan = this.getAttribute('data-nama-perusahaan');
                            const nomorOrder = this.getAttribute('data-nomor-order');
                            
                            document.getElementById('deleteInvoiceId').value = id;
                            document.getElementById('deleteInvoiceNama').textContent = namaPerusahaan;
                            document.getElementById('deleteInvoiceNomor').textContent = nomorOrder;
                            
                            document.getElementById('deleteInvoiceModal').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        });
                    });
                } else {
                    // Show no data message
                    noDataRow.classList.remove('hidden');
                }
            })
            .catch(error => {
                loadingRow.classList.add('hidden');
                console.error('Error loading invoices:', error);
                showToast('Gagal memuat data invoice: ' + error.message);
            });
        }
        
        // Function to create a new invoice
        function submitBuatInvoice() {
            const form = document.getElementById('buatInvoiceForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = {
                nomor_order: document.getElementById('nomorOrder').value,
                nama_perusahaan: document.getElementById('namaPerusahaan').value,
                nama_klien: document.getElementById('namaKlien').value,
                alamat: document.getElementById('alamat').value,
                deskripsi: document.getElementById('deskripsi').value,
                harga: document.getElementById('harga').value,
                qty: document.getElementById('qty').value,
                pajak: document.getElementById('pajak').value,
                metode_pembayaran: document.getElementById('metodePembayaran').value,
                tanggal: document.getElementById('tanggal').value
            };
            
            // Send data to API
            fetch('/api/invoices', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.errors) {
                            const firstErrorKey = Object.keys(data.errors)[0];
                            throw new Error(data.errors[firstErrorKey][0]);
                        }
                        throw new Error(data.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Invoice berhasil dibuat!');
                    closeBuatInvoiceModal();
                    loadInvoices();
                } else {
                    showToast('Gagal membuat invoice: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error creating invoice:', error);
                showToast('Gagal membuat invoice: ' + error.message);
            });
        }
        
        // Function to update an existing invoice
        function submitEditInvoice() {
            const form = document.getElementById('editInvoiceForm');
            
            // Simple form validation
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const id = document.getElementById('editInvoiceId').value;
            const formData = {
                nomor_order: document.getElementById('editNomorOrder').value,
                nama_perusahaan: document.getElementById('editNamaPerusahaan').value,
                nama_klien: document.getElementById('editNamaKlien').value,
                alamat: document.getElementById('editAlamat').value,
                deskripsi: document.getElementById('editDeskripsi').value,
                harga: document.getElementById('editHarga').value,
                qty: document.getElementById('editQty').value,
                pajak: document.getElementById('editPajak').value,
                metode_pembayaran: document.getElementById('editMetodePembayaran').value,
                tanggal: document.getElementById('editTanggal').value
            };
            
            // Send data to API
            fetch(`/api/invoices/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.errors) {
                            const firstErrorKey = Object.keys(data.errors)[0];
                            throw new Error(data.errors[firstErrorKey][0]);
                        }
                        throw new Error(data.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Invoice berhasil diperbarui!');
                    closeEditInvoiceModal();
                    loadInvoices();
                } else {
                    showToast('Gagal memperbarui invoice: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error updating invoice:', error);
                showToast('Gagal memperbarui invoice: ' + error.message);
            });
        }
        
        // Function to delete an invoice
        function deleteInvoice(id) {
            // Send delete request to API
            fetch(`/api/invoices/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Invoice berhasil dihapus!');
                    closeDeleteInvoiceModal();
                    loadInvoices();
                } else {
                    showToast('Gagal menghapus invoice: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error deleting invoice:', error);
                showToast('Gagal menghapus invoice: ' + error.message);
            });
        }
        
        // Modal functions for Buat Invoice
        function closeBuatInvoiceModal() {
            document.getElementById('buatInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('buatInvoiceForm').reset();
        }
        
        // Modal functions for Edit Invoice
        function closeEditInvoiceModal() {
            document.getElementById('editInvoiceModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
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
    </script>
</body>

</html>
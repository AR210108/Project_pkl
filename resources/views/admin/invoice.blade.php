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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000", // Black as primary accent
                        "background-light": "#f3f4f6", // Light gray background for page
                        "background-dark": "#111827", // Dark gray background for page
                        "paper-light": "#ffffff",
                        "paper-dark": "#1f2937", // Zinc 800
                    },
                    fontFamily: {
                        display: ["Montserrat", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
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
        
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .print-container, .print-container * {
                visibility: visible;
            }
            
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        /* Invoice print styles */
        .invoice-header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .invoice-footer {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 20px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .invoice-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .invoice-summary {
            text-align: right;
            margin-top: 20px;
        }
        
        .invoice-summary table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .invoice-summary td {
            padding: 5px;
        }
        
        /* Scribble effect for prices */
        .scribble {
            position: relative;
            display: inline-block;
        }
        
        .scribble::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #ef4444;
            transform: rotate(-2deg);
        }
    </style>
</head>

<body class="font-body bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100">
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
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <!-- Data will be populated here -->
                            <tr id="loadingRow">
                                <td colspan="12" class="p-4 text-center">
                                    <div class="flex justify-center items-center">
                                        <div class="spinner"></div>
                                        <span class="ml-2">Memuat data...</span>
                                    </div>
                                </td>
                            </tr>
                            <tr id="noDataRow" class="hidden">
                                <td colspan="12" class="p-4 text-center">Tidak ada data invoice</td>
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

    <!-- Modal Print Invoice -->
    <div id="printInvoiceModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Print Invoice</h3>
                    <p class="text-gray-600 dark:text-gray-400">Preview invoice sebelum mencetak</p>
                </div>
                <button onclick="closePrintInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <div id="printInvoiceContent" class="print-container">
                    <!-- Invoice content will be populated here -->
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closePrintInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Tutup
                </button>
                <button onclick="printInvoice()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="material-icons mr-2">print</span>
                    Cetak
                </button>
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
            
            document.getElementById('printInvoiceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePrintInvoiceModal();
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
                    if (!document.getElementById('printInvoiceModal').classList.contains('hidden')) {
                        closePrintInvoiceModal();
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
                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="print-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                        data-id="${invoice.id}"
                                        data-tooltip="Cetak">
                                        <span class="material-icons text-green-600 dark:text-green-400">print</span>
                                    </button>
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
                    
                    // Add event listeners to print buttons
                    document.querySelectorAll('.print-invoice-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            showPrintInvoiceModal(id);
                        });
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
        
        // Function to show print invoice modal
        function showPrintInvoiceModal(id) {
            // Fetch invoice data
            fetch(`/api/invoices/${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch invoice data');
                }
                return response.json();
            })
            .then(data => {
                if (data.data) {
                    const invoice = data.data;
                    
                    // Format date
                    const date = new Date(invoice.tanggal);
                    const formattedDate = date.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    // Format currency
                    const formattedHarga = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(invoice.harga);
                    
                    // Calculate subtotal
                    const subtotal = invoice.harga * invoice.qty;
                    const formattedSubtotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(subtotal);
                    
                    // Calculate tax
                    const tax = subtotal * (invoice.pajak / 100);
                    const formattedTax = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(tax);
                    
                    // Calculate total
                    const total = subtotal + tax;
                    const formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(total);
                    
                    // Format total in ribu with thousands separator
                    const totalInRibuan = (total / 1000).toFixed(1);
                    const totalInRibuanFormatted = new Intl.NumberFormat('id-ID').format(totalInRibuan * 1000);
                    
                    // Format subtotal in ribu with thousands separator
                    const subTotalInRibuan = (subtotal / 1000).toFixed(1);
                    const subTotalInRibuanFormatted = new Intl.NumberFormat('id-ID').format(subTotalInRibuan * 1000);
                    
                    // Format harga in ribu with thousands separator
                    const hargaInRibuan = (invoice.harga / 1000).toFixed(1);
                    const hargaInRibuanFormatted = new Intl.NumberFormat('id-ID').format(hargaInRibuan * 1000);
                    
                    // Generate invoice HTML
                    const invoiceHTML = `
                        <div class="w-full max-w-[800px] bg-paper-light dark:bg-paper-dark shadow-2xl overflow-hidden relative print:shadow-none print:w-full print:max-w-none">
                            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none z-0 overflow-hidden flex flex-wrap content-start justify-center gap-8 -rotate-12 scale-150">
                                <span class="text-8xl font-display text-black dark:text-white whitespace-nowrap">DIGICITY</span>
                                <span class="text-8xl font-display text-black dark:text-white whitespace-nowrap">DIGITAL MARKETER</span>
                                <span class="text-8xl font-display text-black dark:text-white whitespace-nowrap">INVOICE</span>
                                <span class="text-8xl font-display text-black dark:text-white whitespace-nowrap">STRATEGIST</span>
                            </div>
                            <div class="relative z-10 px-8 py-10 md:px-12 md:py-14 flex flex-col h-full">
                                <div class="flex flex-col items-center mb-6">
                                    <div class="mb-4">
                                        <svg class="text-primary dark:text-white" fill="none" height="40" viewBox="0 0 100 100" width="40" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M30 20 H60 C80 20 80 50 60 50 H40 V80" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="8"></path>
                                            <path d="M40 50 L70 80" stroke="currentColor" stroke-linecap="round" stroke-width="8"></path>
                                        </svg>
                                    </div>
                                    <h1 class="font-display text-2xl md:text-3xl font-black tracking-widest text-center uppercase mb-1">DIGICITY</h1>
                                    <p class="text-sm md:text-base tracking-widest text-gray-500 dark:text-gray-400 uppercase font-medium">Digital Marketer Strategist</p>
                                </div>
                                <hr class="border-t-2 border-gray-800 dark:border-gray-200 w-full mb-10"/>
                                <div class="flex flex-col md:flex-row justify-between items-start mb-12 gap-8">
                                    <div class="w-full md:w-1/2">
                                        <h2 class="font-display text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">INVOICE</h2>
                                        <div class="space-y-1 text-sm md:text-base font-medium text-gray-700 dark:text-gray-300">
                                            <p><span class="inline-block w-20">Tanggal</span> : ${formattedDate}</p>
                                            <p><span class="inline-block w-20">Nomor</span> : ${invoice.nomor_order}</p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 md:text-right">
                                        <p class="font-display font-bold text-lg mb-1 uppercase tracking-wider">Kepada :</p>
                                        <p class="font-bold text-xl mb-1">${invoice.nama_klien}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                            ${invoice.alamat}<br/>
                                            Indonesia
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full mb-8 overflow-hidden rounded-sm">
                                    <div class="grid grid-cols-12 bg-primary text-white font-bold text-xs md:text-sm uppercase tracking-wider py-3 px-4">
                                        <div class="col-span-6 md:col-span-5">Deskripsi</div>
                                        <div class="col-span-2 md:col-span-3 text-right">Harga</div>
                                        <div class="col-span-2 text-center">Qty</div>
                                        <div class="col-span-2 text-right">Total</div>
                                    </div>
                                    <div class="grid grid-cols-12 py-4 px-4 border-b border-gray-200 dark:border-gray-700 items-start text-sm">
                                        <div class="col-span-6 md:col-span-5 pr-2">
                                            <p class="font-semibold text-gray-900 dark:text-white">${invoice.deskripsi}</p>
                                        </div>
                                        <div class="col-span-2 md:col-span-3 text-right text-gray-700 dark:text-gray-300 pt-0.5">
                                            <div class="relative inline-block">
                                                ${hargaInRibuan}k
                                                <div class="scribble"></div>
                                            </div>
                                        </div>
                                        <div class="col-span-2 text-center text-gray-700 dark:text-gray-300 pt-0.5">1</div>
                                        <div class="col-span-2 text-right font-medium text-gray-900 dark:text-white pt-0.5">${subTotalInRibuan}k</div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end mb-8 space-y-2">
                                    <div class="w-full md:w-1/2 flex justify-between items-center px-4">
                                        <span class="font-display font-bold uppercase tracking-wider text-sm">Total</span>
                                        <span class="font-bold text-lg">Rp. ${new Intl.NumberFormat('id-ID').format(total)}</span>
                                    </div>
                                    <div class="w-full md:w-1/2 flex justify-between items-center px-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span>Pajak</span>
                                        <span>${invoice.pajak}%</span>
                                    </div>
                                </div>
                                <div class="bg-primary text-white w-full py-3 px-4 md:px-6 flex justify-between items-center mb-12 rounded-sm shadow-md dark:shadow-none dark:border dark:border-gray-600">
                                    <span class="font-display font-bold uppercase tracking-widest text-sm md:text-base">Total Keseluruhan</span>
                                    <span class="font-display font-black text-lg md:text-xl tracking-wide">Rp. ${totalInRibuanFormatted}k</span>
                                </div>
                                <div class="flex flex-col-reverse md:flex-row justify-between items-end md:items-start mb-10 gap-8">
                                    <div class="w-full md:w-1/2">
                                        <h3 class="font-display font-bold text-lg uppercase mb-3 text-gray-900 dark:text-white">Metode Pembayaran</h3>
                                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                            <p>Bank BRI : DIGICITY</p>
                                            <p>Nomor Bank : 403001011093536</p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 text-right">
                                        <h2 class="font-display font-black text-4xl md:text-5xl uppercase leading-tight text-gray-900 dark:text-white">
                                            Terima<br/>Kasih.
                                        </h2>
                                    </div>
                                </div>
                                <div class="mt-auto pt-6 border-t-2 border-gray-800 dark:border-gray-200">
                                    <div class="flex flex-col md:flex-row items-center justify-between text-sm font-medium gap-4">
                                        <span class="font-display font-bold uppercase tracking-wider hidden md:block">Contact Us :</span>
                                        <div class="flex flex-wrap justify-center gap-6">
                                            <span class="font-display font-bold uppercase tracking-wider md:hidden w-full text-center mb-2">Contact Us</span>
                                            <div class="flex items-center gap-2">
                                                <span class="bg-primary text-white rounded-full p-1 w-6 h-6 flex items-center justify-center">
                                                    <span class="material-icons" style="font-size: 14px;">call</span>
                                                </span>
                                                <span>+62 82115568304</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="bg-primary text-white rounded-full p-1 w-6 h-6 flex items-center justify-center">
                                                    <span class="material-icons" style="font-size: 14px;">email</span>
                                                </span>
                                                <span>digicity@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-t-2 border-gray-800 dark:border-gray-200 mt-6 md:mt-4"/>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Populate print modal with invoice HTML
                    document.getElementById('printInvoiceContent').innerHTML = invoiceHTML;
                    
                    // Show print modal
                    document.getElementById('printInvoiceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    showToast('Gagal memuat data invoice');
                }
            })
            .catch(error => {
                console.error('Error fetching invoice:', error);
                showToast('Gagal memuat data invoice: ' + error.message);
            });
        }
        
        // Function to print invoice
        function printInvoice() {
            window.print();
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
        
        // Modal functions for Print Invoice
        function closePrintInvoiceModal() {
            document.getElementById('printInvoiceModal').classList.add('hidden');
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
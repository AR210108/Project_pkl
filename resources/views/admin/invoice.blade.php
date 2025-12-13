<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Invoice Management</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    
    <!-- Custom Configuration -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1d4ed8",
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
    
    <!-- Custom Styles (Minimal) -->
    <style>
        /* Scrollbar Styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
        
        /* Tooltip Styles */
        .tooltip { position: relative; }
        .tooltip::after {
            content: attr(data-tooltip);
            position: absolute; bottom: 125%; left: 50%; transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8); color: white; padding: 4px 8px;
            border-radius: 4px; font-size: 12px; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: opacity 0.3s; z-index: 1000;
        }
        .tooltip:hover::after { opacity: 1; }

        /* Menggunakan @apply untuk komponen yang sering dipakai */
        .material-icons, .material-icons-outlined { @apply text-xl; }
        .modal-backdrop { @apply bg-black/50; }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100 min-h-screen">
    <div class="flex h-screen">
        <!-- Include Sidebar -->
        @include('admin/templet/sider')

        <!-- Main content -->
        <main class="flex-1 flex flex-col p-8 overflow-y-auto">
            <div class="flex-1">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Invoice</h2>
                    <button id="buatInvoiceBtn"
                        class="flex items-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <span class="material-icons mr-2">add</span>
                        Buat Invoice
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="flex justify-end items-center mb-6 space-x-4">
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                        <input id="searchInput"
                            class="w-72 bg-gray-200 dark:bg-gray-700 border-none rounded-lg pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary"
                            placeholder="Search..." type="text" />
                    </div>
                    <button
                        class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Filter
                    </button>
                </div>

                <!-- Table -->
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden overflow-x-auto">
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
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">PAJAK</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">METODE BAYAR</th>
                                <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-400">AKSI</th>
                            </tr>
                        </thead>
                        <!-- TUBUH TABEL AKAN DI-GENERASI OLEH JAVASCRIPT -->
                        <tbody id="invoiceTableBody">
                            <!-- Baris tabel akan muncul di sini -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center text-sm text-gray-600 dark:text-gray-400 pt-8">
                <p>Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- ======================================================= -->
    <!-- ===================== MODAL COMPONENTS ================== -->
    <!-- ======================================================= -->

    <!-- Modal Buat Invoice -->
    <div id="buatInvoiceModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50 transition-opacity duration-300">
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                            <input type="text" id="namaPerusahaan" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Order</label>
                            <input type="text" id="nomorOrder" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Klien</label>
                            <input type="text" id="namaKlien" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <input type="text" id="alamat" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="harga" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                            <input type="number" id="qty" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
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
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closeBuatInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Batal</button>
                <button onclick="submitBuatInvoice()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Buat Invoice</button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Invoice -->
    <div id="editInvoiceModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50 transition-opacity duration-300">
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
                    <!-- Form fields akan sama dengan buat invoice -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                            <input type="text" id="editNamaPerusahaan" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                            <input type="date" id="editTanggal" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Order</label>
                            <input type="text" id="editNomorOrder" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Klien</label>
                            <input type="text" id="editNamaKlien" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <input type="text" id="editAlamat" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                            <input type="number" id="editQty" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
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
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closeEditInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Batal</button>
                <button onclick="submitEditInvoice()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Update Invoice</button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Invoice -->
    <div id="deleteInvoiceModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50 transition-opacity duration-300">
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
    <div id="toast" class="fixed bottom-4 right-4 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-300 flex items-center z-50 translate-y-full opacity-0">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons text-lg">close</span>
        </button>
    </div>

    <!-- Modal Print Invoice -->
    <div id="printInvoiceModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[95vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Cetak Invoice</h3>
                <button onclick="closePrintInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div id="printContent" class="flex-grow overflow-auto p-6 bg-white">
                <!-- Content akan di-generate oleh JavaScript -->
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3 bg-gray-50 dark:bg-gray-900">
                <button onclick="closePrintInvoiceModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Tutup</button>
                <button onclick="printInvoice()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <span class="material-icons mr-2 text-lg">print</span>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- ======================= JAVASCRIPT ==================== -->
    <!-- ======================================================= -->
    <script>
        // ============================================
        // DATA & STATE MANAGEMEN
        // ============================================
        let invoices = [
            {
                id: 1,
                namaPerusahaan: 'PT. Maju Bersama',
                tanggal: '2024-01-15',
                nomorOrder: '#ORD-2024-001',
                namaKlien: 'John Doe',
                alamat: 'Jakarta, Indonesia',
                deskripsi: 'Pengembangan Website Company Profile',
                harga: 15000000,
                qty: 1,
                pajak: 11,
                metodeBayar: 'Transfer Bank'
            },
            {
                id: 2,
                namaPerusahaan: 'CV. Sejahtera Abadi',
                tanggal: '2024-01-20',
                nomorOrder: '#ORD-2024-002',
                namaKlien: 'Jane Smith',
                alamat: 'Bandung, Indonesia',
                deskripsi: 'Jasa Digital Marketing',
                harga: 8500000,
                qty: 1,
                pajak: 11,
                metodeBayar: 'Kartu Kredit'
            }
        ];
        
        let nextId = 3; // Untuk ID unik baru

        // ============================================
        // HELPER FUNCTIONS
        // ============================================
        
        /** Format angka ke format mata uang Rupiah */
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        /** Render ulang seluruh tabel invoice */
        function renderTable() {
            const tableBody = document.getElementById('invoiceTableBody');
            tableBody.innerHTML = ''; // Kosongkan tabel

            if (invoices.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="13" class="p-4 text-center text-gray-500">Tidak ada data invoice.</td></tr>`;
                return;
            }

            invoices.forEach((invoice, index) => {
                const total = invoice.harga * invoice.qty;
                const row = `
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="p-4">${index + 1}.</td>
                        <td class="p-4">${invoice.namaPerusahaan}</td>
                        <td class="p-4">${invoice.tanggal}</td>
                        <td class="p-4">${invoice.nomorOrder}</td>
                        <td class="p-4">${invoice.namaKlien}</td>
                        <td class="p-4">${invoice.alamat}</td>
                        <td class="p-4">${invoice.deskripsi}</td>
                        <td class="p-4">${formatRupiah(invoice.harga)}</td>
                        <td class="p-4">${invoice.qty}</td>
                        <td class="p-4">${formatRupiah(total)}</td>
                        <td class="p-4">${invoice.pajak}%</td>
                        <td class="p-4">${invoice.metodeBayar}</td>
                        <td class="p-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="print-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                    data-id="${invoice.id}"
                                    data-tooltip="Cetak">
                                    <span class="material-icons text-green-600 dark:text-green-400">print</span>
                                </button>
                                <button class="edit-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                    data-id="${invoice.id}"
                                    data-tooltip="Edit">
                                    <span class="material-icons text-blue-600 dark:text-blue-400">edit</span>
                                </button>
                                <button class="delete-invoice-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                    data-id="${invoice.id}"
                                    data-tooltip="Hapus">
                                    <span class="material-icons text-red-600 dark:text-red-400">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });

            // Set ulang event listener karena elemen baru ditambahkan
            attachRowEventListeners();
        }
        
        /** Lampirkan event listener ke tombol edit/hapus/cetak di baris tabel */
        function attachRowEventListeners() {
            // Event listeners untuk tombol Cetak
            const printBtns = document.querySelectorAll('.print-invoice-btn');
            printBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    const invoiceData = invoices.find(inv => inv.id === id);
                    if (invoiceData) {
                        openPrintInvoiceModal(invoiceData);
                    }
                });
            });
            
            // Event listeners untuk tombol Edit
            const editBtns = document.querySelectorAll('.edit-invoice-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    const invoiceData = invoices.find(inv => inv.id === id);
                    if (invoiceData) {
                        openEditInvoiceModal(invoiceData);
                    }
                });
            });
            
            // Event listeners untuk tombol Hapus
            const deleteBtns = document.querySelectorAll('.delete-invoice-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    const invoiceData = invoices.find(inv => inv.id === id);
                    if (invoiceData) {
                        openDeleteInvoiceModal(invoiceData);
                    }
                });
            });
        }

        // ============================================
        // MODAL FUNCTIONS
        // ============================================
        function openBuatInvoiceModal() {
            document.getElementById('buatInvoiceModal').classList.remove('hidden');
            document.getElementById('buatInvoiceModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeBuatInvoiceModal() {
            document.getElementById('buatInvoiceModal').classList.add('hidden');
            document.getElementById('buatInvoiceModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
            document.getElementById('buatInvoiceForm').reset();
        }

        function openEditInvoiceModal(invoiceData) {
            // Populate form dengan data invoice
            document.getElementById('editInvoiceId').value = invoiceData.id;
            document.getElementById('editNamaPerusahaan').value = invoiceData.namaPerusahaan;
            document.getElementById('editTanggal').value = invoiceData.tanggal;
            document.getElementById('editNomorOrder').value = invoiceData.nomorOrder;
            document.getElementById('editNamaKlien').value = invoiceData.namaKlien;
            document.getElementById('editAlamat').value = invoiceData.alamat;
            document.getElementById('editDeskripsi').value = invoiceData.deskripsi;
            document.getElementById('editHarga').value = invoiceData.harga;
            document.getElementById('editQty').value = invoiceData.qty;
            document.getElementById('editPajak').value = invoiceData.pajak;
            document.getElementById('editMetodePembayaran').value = invoiceData.metodeBayar;
            
            document.getElementById('editInvoiceModal').classList.remove('hidden');
            document.getElementById('editInvoiceModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEditInvoiceModal() {
            document.getElementById('editInvoiceModal').classList.add('hidden');
            document.getElementById('editInvoiceModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openDeleteInvoiceModal(invoiceData) {
            document.getElementById('deleteInvoiceId').value = invoiceData.id;
            document.getElementById('deleteInvoiceNama').textContent = invoiceData.namaPerusahaan;
            document.getElementById('deleteInvoiceNomor').textContent = invoiceData.nomorOrder;
            
            document.getElementById('deleteInvoiceModal').classList.remove('hidden');
            document.getElementById('deleteInvoiceModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteInvoiceModal() {
            document.getElementById('deleteInvoiceModal').classList.add('hidden');
            document.getElementById('deleteInvoiceModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openPrintInvoiceModal(invoiceData) {
            // Generate print content dengan desain profesional
            const total = invoiceData.harga * invoiceData.qty;
            const pajak = total * (invoiceData.pajak / 100);
            const totalFinal = total + pajak;
            const tanggalFormat = new Date(invoiceData.tanggal).toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric' 
            });

            const printContent = `
                <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; max-width: 900px; margin: 0 auto;">
                    <!-- HEADER -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #1f2937;">
                        <div style="flex: 1;">
                            <h1 style="font-size: 28px; color: #1f2937; margin-bottom: 8px; font-weight: 700;">DIGICITY</h1>
                            <div style="font-size: 11px; line-height: 1.6; color: #666;">
                                <p style="margin: 2px 0;"><strong>PT. Digicity Indonesia</strong></p>
                                <p style="margin: 2px 0;">Jln. Raya Gatot Subroto No. 123</p>
                                <p style="margin: 2px 0;">Jakarta Selatan 12930</p>
                                <p style="margin: 2px 0;">Telp: +62 21 1234 5678</p>
                                <p style="margin: 2px 0;">Email: invoice@digicity.id</p>
                            </div>
                        </div>
                        <div style="flex: 1; text-align: right;">
                            <h2 style="font-size: 32px; color: #1f2937; margin-bottom: 10px; font-weight: 700;">INVOICE</h2>
                            <div style="font-size: 12px; line-height: 1.8; color: #555;">
                                <p style="margin: 5px 0;"><span style="font-weight: 600; color: #1f2937;">No. Invoice:</span> ${invoiceData.nomorOrder}</p>
                                <p style="margin: 5px 0;">Tanggal: ${tanggalFormat}</p>
                                <p style="margin: 5px 0;">Jatuh Tempo: 30 hari</p>
                            </div>
                        </div>
                    </div>

                    <!-- BILL TO / CLIENT INFO -->
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 12px; font-weight: 700; color: #1f2937; margin-top: 20px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Tagihan Kepada:</p>
                        <div style="padding: 12px; background: #f8f9fa; border-left: 4px solid #3b82f6; border-radius: 2px;">
                            <p style="font-weight: 600; color: #1f2937; font-size: 13px; margin-bottom: 5px;">${invoiceData.namaKlien}</p>
                            <p style="font-size: 12px; margin: 4px 0; line-height: 1.5;"><strong>Perusahaan:</strong> ${invoiceData.namaPerusahaan}</p>
                            <p style="font-size: 12px; margin: 4px 0; line-height: 1.5;"><strong>Alamat:</strong> ${invoiceData.alamat}</p>
                        </div>
                    </div>

                    <!-- ITEMS TABLE -->
                    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                        <thead>
                            <tr style="background: #1f2937; color: white;">
                                <th style="padding: 12px 8px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #1f2937; width: 5%;">No</th>
                                <th style="padding: 12px 8px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #1f2937; width: 50%;">Deskripsi Layanan</th>
                                <th style="padding: 12px 8px; text-align: right; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #1f2937; width: 15%;">Harga Unit</th>
                                <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #1f2937; width: 10%;">Qty</th>
                                <th style="padding: 12px 8px; text-align: right; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #1f2937; width: 20%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: #f8f9fa;">
                                <td style="padding: 12px 8px; font-size: 12px; border: 1px solid #ddd; text-align: center;">1</td>
                                <td style="padding: 12px 8px; font-size: 12px; border: 1px solid #ddd; line-height: 1.5;">${invoiceData.deskripsi}</td>
                                <td style="padding: 12px 8px; font-size: 12px; border: 1px solid #ddd; text-align: right;">${formatRupiah(invoiceData.harga)}</td>
                                <td style="padding: 12px 8px; font-size: 12px; border: 1px solid #ddd; text-align: center;">${invoiceData.qty}</td>
                                <td style="padding: 12px 8px; font-size: 12px; border: 1px solid #ddd; text-align: right;">${formatRupiah(total)}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- SUMMARY -->
                    <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                        <div style="width: 350px;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 500; width: 60%; color: #555;">Subtotal</td>
                                    <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 500; width: 40%; color: #1f2937;">${formatRupiah(total)}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 500; color: #555;">Pajak (${invoiceData.pajak}%)</td>
                                    <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 500; color: #1f2937;">${formatRupiah(pajak)}</td>
                                </tr>
                                <tr style="border-bottom: 2px solid #ddd;">
                                    <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 500; color: #555;">Diskon</td>
                                    <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 500; color: #1f2937;">Rp 0</td>
                                </tr>
                                <tr style="border-top: 2px solid #1f2937; border-bottom: 2px solid #1f2937; background: #f8f9fa; font-size: 14px; font-weight: 700;">
                                    <td style="padding: 10px 12px; text-align: right; color: #555;">TOTAL</td>
                                    <td style="padding: 10px 12px; text-align: right; color: #1f2937;">${formatRupiah(totalFinal)}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div style="margin-top: 25px; padding: 12px; background: #e3f2fd; border-left: 4px solid #3b82f6; border-radius: 2px;">
                        <p style="font-weight: 600; color: #1f2937; font-size: 13px;">Metode Pembayaran</p>
                        <p style="font-size: 12px; margin: 4px 0; line-height: 1.5;">${invoiceData.metodeBayar}</p>
                        <p style="margin-top: 8px; font-size: 10px; color: #666; line-height: 1.5;">Mohon lakukan pembayaran dalam 30 hari sejak tanggal invoice.</p>
                    </div>

                    <!-- NOTES -->
                    <div style="margin-top: 25px; padding: 12px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 2px;">
                        <p style="font-weight: 600; margin: 0 0 5px 0;"><strong>Catatan:</strong></p>
                        <p style="font-size: 11px; margin: 3px 0; line-height: 1.4; color: #666;">Terima kasih atas kepercayaan Anda. Apabila ada pertanyaan mengenai invoice ini, silakan hubungi kami.</p>
                    </div>

                    <!-- FOOTER -->
                    <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #999; line-height: 1.6;">
                        <p>Dokumen ini adalah bukti transaksi yang sah. Terima kasih telah berbisnis dengan kami.</p>
                        <p>© 2025 PT. Digicity Indonesia. All rights reserved.</p>
                    </div>
                </div>
            `;

            document.getElementById('printContent').innerHTML = printContent;
            document.getElementById('printInvoiceModal').classList.remove('hidden');
            document.getElementById('printInvoiceModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePrintInvoiceModal() {
            document.getElementById('printInvoiceModal').classList.add('hidden');
            document.getElementById('printInvoiceModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function printInvoice() {
            const printContent = document.getElementById('printContent').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>Invoice</title>
                    <style>
                        body {
                            font-family: Arial, Helvetica, sans-serif;
                            color: #000;
                            margin: 0;
                            padding: 0;
                            background: #fff;
                        }
                        .max-w-4xl {
                            max-width: 56rem;
                            margin: auto;
                        }
                        .mx-auto { margin-left: auto; margin-right: auto; }
                        .p-8 { padding: 2rem; }
                        .p-3 { padding: 0.75rem; }
                        .mb-8 { margin-bottom: 2rem; }
                        .mb-2 { margin-bottom: 0.5rem; }
                        .mt-1 { margin-top: 0.25rem; }
                        .mt-2 { margin-top: 0.5rem; }
                        .mt-12 { margin-top: 3rem; }
                        .pb-6 { padding-bottom: 1.5rem; }
                        .pt-8 { padding-top: 2rem; }
                        .flex { display: flex; }
                        .justify-between { justify-content: space-between; }
                        .items-start { align-items: flex-start; }
                        .text-right { text-align: right; }
                        .text-left { text-align: left; }
                        .text-2xl { font-size: 1.5rem; }
                        .text-4xl { font-size: 2.25rem; }
                        .text-sm { font-size: 0.875rem; }
                        .text-lg { font-size: 1.125rem; }
                        .font-bold { font-weight: bold; }
                        .font-semibold { font-weight: 600; }
                        .text-gray-900 { color: #111827; }
                        .text-gray-600 { color: #4b5563; }
                        .border-b-2 { border-bottom: 2px solid; }
                        .border-b { border-bottom: 1px solid; }
                        .border-t { border-top: 1px solid; }
                        .border-t-2 { border-top: 2px solid; }
                        .border-gray-800 { border-color: #1f2937; }
                        .border-gray-300 { border-color: #d1d5db; }
                        .bg-gray-100 { background-color: #f3f4f6; }
                        .w-full { width: 100%; }
                        .w-3-4 { width: 75%; }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        td {
                            padding: 0.75rem;
                        }
                        tr {
                            border: 1px solid #1f2937;
                        }
                        thead tr {
                            border: 1px solid #1f2937;
                            background-color: #f3f4f6;
                        }
                        @media print {
                            body {
                                margin: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
                </html>
            `);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
            }, 250);
        }

        // ============================================
        // CRUD FUNCTIONS
        // ============================================
        function submitBuatInvoice() {
            const form = document.getElementById('buatInvoiceForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const newInvoice = {
                id: nextId++,
                namaPerusahaan: document.getElementById('namaPerusahaan').value,
                tanggal: document.getElementById('tanggal').value,
                nomorOrder: document.getElementById('nomorOrder').value,
                namaKlien: document.getElementById('namaKlien').value,
                alamat: document.getElementById('alamat').value,
                deskripsi: document.getElementById('deskripsi').value,
                harga: parseFloat(document.getElementById('harga').value),
                qty: parseInt(document.getElementById('qty').value),
                pajak: parseFloat(document.getElementById('pajak').value),
                metodeBayar: document.getElementById('metodePembayaran').value
            };
            
            invoices.push(newInvoice);
            renderTable();
            showToast('Invoice berhasil dibuat!', 'success');
            closeBuatInvoiceModal();
        }

        function submitEditInvoice() {
            const form = document.getElementById('editInvoiceForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const id = parseInt(document.getElementById('editInvoiceId').value);
            const index = invoices.findIndex(inv => inv.id === id);

            if (index !== -1) {
                invoices[index] = {
                    id: id,
                    namaPerusahaan: document.getElementById('editNamaPerusahaan').value,
                    tanggal: document.getElementById('editTanggal').value,
                    nomorOrder: document.getElementById('editNomorOrder').value,
                    namaKlien: document.getElementById('editNamaKlien').value,
                    alamat: document.getElementById('editAlamat').value,
                    deskripsi: document.getElementById('editDeskripsi').value,
                    harga: parseFloat(document.getElementById('editHarga').value),
                    qty: parseInt(document.getElementById('editQty').value),
                    pajak: parseFloat(document.getElementById('editPajak').value),
                    metodeBayar: document.getElementById('editMetodePembayaran').value
                };
                renderTable();
                showToast('Invoice berhasil diperbarui!', 'success');
                closeEditInvoiceModal();
            }
        }

        function confirmDelete() {
            const id = parseInt(document.getElementById('deleteInvoiceId').value);
            const index = invoices.findIndex(inv => inv.id === id);

            if (index !== -1) {
                invoices.splice(index, 1);
                renderTable();
                showToast('Invoice berhasil dihapus!', 'success');
                closeDeleteInvoiceModal();
            }
        }

        // ============================================
        // INITIALIZATION & EVENT LISTENERS
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            renderTable(); // Render tabel pertama kali

            // Tombol buat invoice
            document.getElementById('buatInvoiceBtn').addEventListener('click', openBuatInvoiceModal);
            
            // Tombol konfirmasi hapus
            document.getElementById('confirmDeleteInvoice').addEventListener('click', confirmDelete);

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const filteredInvoices = invoices.filter(invoice => 
                    invoice.namaPerusahaan.toLowerCase().includes(searchTerm) ||
                    invoice.nomorOrder.toLowerCase().includes(searchTerm) ||
                    invoice.namaKlien.toLowerCase().includes(searchTerm)
                );
                // Render ulang dengan data yang sudah difilter
                const originalInvoices = invoices;
                invoices = filteredInvoices;
                renderTable();
                invoices = originalInvoices; // Kembalikan ke data asli
            });

            // Modal close on outside click
            ['buatInvoiceModal', 'editInvoiceModal', 'deleteInvoiceModal', 'printInvoiceModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        if (modalId === 'buatInvoiceModal') closeBuatInvoiceModal();
                        else if (modalId === 'editInvoiceModal') closeEditInvoiceModal();
                        else if (modalId === 'deleteInvoiceModal') closeDeleteInvoiceModal();
                        else if (modalId === 'printInvoiceModal') closePrintInvoiceModal();
                    }
                });
            });

            // Modal close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeBuatInvoiceModal();
                    closeEditInvoiceModal();
                    closeDeleteInvoiceModal();
                }
            });

            // Toast close
            document.getElementById('closeToast').addEventListener('click', hideToast);
        });

        // ============================================
        // TOAST NOTIFICATION
        // ============================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.className = 'fixed bottom-4 right-4 text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-300 flex items-center z-50';
            
            if (type === 'success') toast.classList.add('bg-green-500');
            else if (type === 'error') toast.classList.add('bg-red-500');
            else if (type === 'warning') toast.classList.add('bg-yellow-500');
            else toast.classList.add('bg-blue-500');

            setTimeout(() => toast.classList.remove('translate-y-full', 'opacity-0'), 100);
            setTimeout(() => hideToast(), 3000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.add('translate-y-full', 'opacity-0');
        }
    </script>
</body>
</html>
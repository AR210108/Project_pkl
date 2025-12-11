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
        
        /** Lampirkan event listener ke tombol edit/hapus di baris tabel */
        function attachRowEventListeners() {
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
            ['buatInvoiceModal', 'editInvoiceModal', 'deleteInvoiceModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        if (modalId === 'buatInvoiceModal') closeBuatInvoiceModal();
                        else if (modalId === 'editInvoiceModal') closeEditInvoiceModal();
                        else if (modalId === 'deleteInvoiceModal') closeDeleteInvoiceModal();
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
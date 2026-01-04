<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kwitansi Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000",
                        "background-light": "#ffffff",
                        "background-dark": "#000000",
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
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
            font-variation-settings: 'FILL' 1
        }
        .active .material-icons-outlined {
            font-weight: bold;
        }
        .modal {
            transition: opacity 0.3s ease;
        }
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
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
        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        .pagination a {
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            border-radius: 0.375rem;
            background-color: #f1f5f9;
            color: #000000;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }
        .pagination a:hover {
            background-color: #e2e8f0;
        }
        .pagination .active {
            background-color: #000000;
            color: white;
        }
        .pagination .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        /* Loading spinner */
        .spinner {
            border: 2px solid rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            border-top: 2px solid black;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Black and white theme overrides */
        body {
            color: #000000;
        }
        .dark body {
            color: #ffffff;
        }
        .dark .bg-white {
            background-color: #1a1a1a !important;
        }
        .dark .text-slate-900 {
            color: #ffffff !important;
        }
        .dark .text-slate-800 {
            color: #e0e0e0 !important;
        }
        .dark .text-slate-700 {
            color: #cccccc !important;
        }
        .dark .text-slate-600 {
            color: #b3b3b3 !important;
        }
        .dark .text-slate-500 {
            color: #999999 !important;
        }
        .dark .text-slate-400 {
            color: #808080 !important;
        }
        .dark .border-slate-200 {
            border-color: #333333 !important;
        }
        .dark .border-slate-300 {
            border-color: #404040 !important;
        }
        .dark .bg-slate-100 {
            background-color: #1a1a1a !important;
        }
        .dark .bg-slate-200 {
            background-color: #262626 !important;
        }
        .dark .bg-slate-700 {
            background-color: #404040 !important;
        }
        .dark .bg-slate-800 {
            background-color: #1a1a1a !important;
        }
        .dark .hover\:bg-slate-100:hover {
            background-color: #333333 !important;
        }
        .dark .hover\:bg-slate-300:hover {
            background-color: #333333 !important;
        }
        .dark .hover\:bg-slate-600:hover {
            background-color: #333333 !important;
        }
        .dark .hover\:bg-slate-700:hover {
            background-color: #333333 !important;
        }
        .dark input, .dark select, .dark textarea {
            background-color: #262626 !important;
            color: #ffffff !important;
            border-color: #404040 !important;
        }
        .dark .focus\:ring-primary:focus {
            --tw-ring-color: #666666 !important;
        }
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            .print-modal {
                position: static !important;
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .print-content {
                padding: 20px !important;
            }
            @page {
                size: A4;
                margin: 0;
            }
        }
        /* Kwitansi Styles */
        .kwitansi-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 15mm 20mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            min-height: 297mm;
        }

        .kwitansi-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10mm;
        }

        .kwitansi-left-section {
            text-align: left;
        }

        .kwitansi-company-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }

        .kwitansi-company-tagline {
            font-size: 8pt;
            color: #666;
            letter-spacing: 4px;
            margin-bottom: 5mm;
        }

        .kwitansi-title {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 0;
            line-height: 1;
        }

        .kwitansi-right-section {
            text-align: right;
        }

        .kwitansi-invoice-number {
            font-size: 10pt;
            margin-bottom: 2mm;
        }

        .kwitansi-date-row {
            font-size: 10pt;
        }

        .kwitansi-main-content {
            margin: 8mm 0;
        }

        .kwitansi-info-row {
            display: flex;
            margin-bottom: 4mm;
            font-size: 11pt;
            line-height: 1.4;
        }

        .kwitansi-info-label {
            font-weight: normal;
            min-width: 45mm;
            flex-shrink: 0;
        }

        .kwitansi-info-separator {
            margin: 0 3mm;
        }

        .kwitansi-info-value {
            flex: 1;
            font-weight: normal;
        }

        .kwitansi-keterangan-section {
            margin: 10mm 0;
            border: 1.5pt solid #000;
            padding: 0;
        }

        .kwitansi-keterangan-header {
            background: #000;
            color: white;
            padding: 2mm 4mm;
            font-weight: bold;
            font-size: 10pt;
        }

        .kwitansi-keterangan-content {
            padding: 5mm 4mm;
        }

        .kwitansi-keterangan-row {
            display: flex;
            margin-bottom: 3mm;
            font-size: 11pt;
            line-height: 1.4;
        }

        .kwitansi-keterangan-row:last-child {
            margin-bottom: 0;
        }

        .kwitansi-keterangan-label {
            min-width: 40mm;
            flex-shrink: 0;
        }

        .kwitansi-keterangan-separator {
            margin: 0 3mm;
        }

        .kwitansi-keterangan-value {
            flex: 1;
        }

        .kwitansi-totals-section {
            margin: 8mm 0;
            text-align: right;
        }

        .kwitansi-total-row {
            display: flex;
            justify-content: flex-end;
            font-size: 12pt;
            font-weight: bold;
            line-height: 1.4;
        }

        .kwitansi-total-label {
            margin-right: 3mm;
        }

        .kwitansi-total-separator {
            margin: 0 3mm;
        }

        .kwitansi-total-value {
            min-width: 35mm;
            text-align: left;
        }

        .kwitansi-payment-info {
            margin-top: 12mm;
            border: 1pt solid #ccc;
            padding: 5mm;
        }

        .kwitansi-payment-info-title {
            font-weight: bold;
            margin-bottom: 3mm;
            font-size: 10pt;
        }

        .kwitansi-bank-info {
            font-size: 10pt;
        }

        .kwitansi-bank-row {
            margin-bottom: 2mm;
            display: flex;
        }

        .kwitansi-bank-row:last-child {
            margin-bottom: 0;
        }

        .kwitansi-bank-label {
            display: inline-block;
            min-width: 30mm;
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
    <div class="flex h-screen">
        <!-- Include sider from template -->
        @include('admin/templet/sider')
        
        <main class="flex-1 flex flex-col">
            <div class="flex-grow p-8">
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50">Kwitansi</h1>
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    <button id="buatKwitansiBtn" onclick="toggleModal('buatKwitansiModal')" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined">add</span>
                        <span>Buat Kwitansi</span>
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                            <input id="searchInput" class="w-72 bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary" placeholder="Search..." type="text" />
                        </div>
                        <button id="filterBtn" class="bg-slate-200 dark:bg-slate-700 px-4 py-2 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">Filter</button>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                <tr>
                                    <th class="p-4">No</th>
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4">Nama Perusahaan</th>
                                    <th class="p-4">Nomor Order</th>
                                    <th class="p-4">Nama Klien</th>
                                    <th class="p-4">Deskripsi</th>
                                    <th class="p-4">Harga</th>
                                    <th class="p-4">Sub Total</th>
                                    <th class="p-4">Fee Maintenance</th>
                                    <th class="p-4">Total</th>
                                    <th class="p-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700" id="kwitansiTableBody">
                                <!-- Data will be loaded from database via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                        <div class="pagination" id="pagination">
                            <!-- Pagination links will be generated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-slate-200 dark:bg-slate-900 text-sm text-slate-600 dark:text-slate-400">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Buat Kwitansi -->
    <div id="buatKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col border border-slate-200 dark:border-slate-700">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Buat Kwitansi Baru</h3>
                    <p class="text-slate-600 dark:text-slate-400">Pilih invoice dan isi form untuk membuat kwitansi</p>
                </div>
                <button onclick="toggleModal('buatKwitansiModal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="buatKwitansiForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pilih Invoice</label>
                        <select id="pilihInvoice" name="invoice_id" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">-- Pilih Invoice --</option>
                            <!-- Invoice options will be loaded via JavaScript -->
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Perusahaan</label>
                            <input type="text" id="namaPerusahaan" name="nama_perusahaan" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Order</label>
                        <input type="text" id="nomorOrder" name="nomor_order" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Klien</label>
                        <input type="text" id="namaKlien" name="nama_klien" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <!-- Added Deskripsi Field -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="harga" name="harga" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sub Total (Rp)</label>
                            <input type="number" id="subTotal" name="sub_total" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fee Maintenance (Rp)</label>
                            <input type="number" id="feeMaintenance" name="fee_maintenance" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Total (Rp)</label>
                        <input type="text" id="total" name="total_display" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50" readonly>
                        <!-- Hidden input for the actual numeric total value -->
                        <input type="hidden" id="totalValue" name="total" value="0">
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-3">
                <button onclick="toggleModal('buatKwitansiModal')" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button id="submitBuatKwitansiBtn" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-gray-800 transition-colors flex items-center">
                    <span id="submitBuatKwitansiText">Buat Kwitansi</span>
                    <span id="submitBuatKwitansiSpinner" class="spinner ml-2 hidden"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kwitansi -->
    <div id="editKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col border border-slate-200 dark:border-slate-700">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Edit Kwitansi</h3>
                    <p class="text-slate-600 dark:text-slate-400">Ubah informasi kwitansi di bawah</p>
                </div>
                <button onclick="toggleModal('editKwitansiModal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="editKwitansiForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="editKwitansiId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal</label>
                            <input type="date" id="editTanggal" name="tanggal" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Perusahaan</label>
                            <input type="text" id="editNamaPerusahaan" name="nama_perusahaan" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Order</label>
                        <input type="text" id="editNomorOrder" name="nomor_order" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Klien</label>
                        <input type="text" id="editNamaKlien" name="nama_klien" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <!-- Added Deskripsi Field -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga (Rp)</label>
                            <input type="number" id="editHarga" name="harga" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sub Total (Rp)</label>
                            <input type="number" id="editSubTotal" name="sub_total" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fee Maintenance (Rp)</label>
                            <input type="number" id="editFeeMaintenance" name="fee_maintenance" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50 focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Total (Rp)</label>
                        <input type="text" id="editTotal" name="total_display" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-50" readonly>
                        <!-- Hidden input for the actual numeric total value -->
                        <input type="hidden" id="editTotalValue" name="total" value="0">
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-3">
                <button onclick="toggleModal('editKwitansiModal')" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button id="submitEditKwitansiBtn" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-gray-800 transition-colors flex items-center">
                    <span id="submitEditKwitansiText">Update Kwitansi</span>
                    <span id="submitEditKwitansiSpinner" class="spinner ml-2 hidden"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Kwitansi -->
    <div id="deleteKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-md w-full mx-4 border border-slate-200 dark:border-slate-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Konfirmasi Hapus</h3>
                    <button onclick="toggleModal('deleteKwitansiModal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-slate-900 dark:text-slate-50">Apakah Anda yakin ingin menghapus kwitansi untuk <span id="deleteKwitansiNama" class="font-semibold"></span> dengan nomor order <span id="deleteKwitansiNomor" class="font-semibold"></span>?</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <input type="hidden" id="deleteKwitansiId">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('deleteKwitansiModal')" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600">Batal</button>
                    <button id="confirmDeleteKwitansiBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center">
                        <span id="confirmDeleteText">Hapus</span>
                        <span id="confirmDeleteSpinner" class="spinner ml-2 hidden"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cetak Kwitansi -->
    <div id="cetakKwitansiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col border border-slate-200 dark:border-slate-700 print-modal">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center no-print">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-50">Cetak Kwitansi</h3>
                    <p class="text-slate-600 dark:text-slate-400">Pratinjau kwitansi sebelum mencetak</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-gray-800 transition-colors flex items-center">
                        <span class="material-symbols-outlined mr-2">print</span>
                        Cetak
                    </button>
                    <button onclick="toggleModal('cetakKwitansiModal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="flex-grow overflow-auto p-4 print-content" id="kwitansiPrintContent">
                <!-- Kwitansi content will be loaded here -->
                <div class="text-center py-8">
                    <div class="spinner mx-auto mb-4"></div>
                    <p class="text-slate-600 dark:text-slate-400">Memuat data kwitansi...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM Content Loaded. Memulai inisialisasi...");

            // Set today's date as default for the date input
            const today = new Date().toISOString().split('T')[0];
            const tanggalInput = document.getElementById('tanggal');
            if (tanggalInput) {
                tanggalInput.value = today;
            }

            // Load invoice options when page loads
            console.log("Memuat opsi invoice...");
            loadInvoiceOptions();
            
            // Load kwitansi data on page load
            console.log("Memuat data kwitansi...");
            loadKwitansiData();
            
            // Event listener for search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    loadKwitansiData(1, this.value);
                });
            } else {
                console.error("Elemen #searchInput tidak ditemukan.");
            }
            
            // Event listener for filter button
            const filterBtn = document.getElementById('filterBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    showToast('Filter functionality will be implemented soon');
                });
            }

            // Event listener for submit Buat Kwitansi form - FIXED NAMING CONFLICT
            const submitBuatKwitansiBtn = document.getElementById('submitBuatKwitansiBtn');
            if (submitBuatKwitansiBtn) {
                submitBuatKwitansiBtn.addEventListener('click', function() {
                    submitBuatKwitansiForm();
                });
            }
            
            // Event listener for submit Edit Kwitansi form - FIXED NAMING CONFLICT
            const submitEditKwitansiBtn = document.getElementById('submitEditKwitansiBtn');
            if (submitEditKwitansiBtn) {
                submitEditKwitansiBtn.addEventListener('click', function() {
                    submitEditKwitansiForm();
                });
            }
            
            // Event listener for confirm delete button - FIXED NAMING CONFLICT
            const confirmDeleteKwitansiBtn = document.getElementById('confirmDeleteKwitansiBtn');
            if (confirmDeleteKwitansiBtn) {
                confirmDeleteKwitansiBtn.addEventListener('click', function() {
                    confirmDeleteKwitansi();
                });
            }
            
            // Event listener for invoice selection
            const pilihInvoice = document.getElementById('pilihInvoice');
            if (pilihInvoice) {
                pilihInvoice.addEventListener('change', function() {
                    const invoiceId = this.value;
                    if (invoiceId) {
                        // Fetch invoice details and populate form
                        fetch(`/api/invoices/${invoiceId}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Server error: ${response.status} ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                document.getElementById('namaPerusahaan').value = data.data.nama_perusahaan;
                                document.getElementById('nomorOrder').value = data.data.nomor_order;
                                document.getElementById('namaKlien').value = data.data.nama_klien;
                                document.getElementById('harga').value = data.data.harga;
                                // Calculate sub_total and fee_maintenance based on harga
                                const harga = parseFloat(data.data.harga);
                                document.getElementById('subTotal').value = harga;
                                document.getElementById('feeMaintenance').value = 0;
                                
                                // Calculate total
                                calculateTotal();
                            } else {
                                showToast(data.message || 'Gagal mengambil data invoice', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching invoice details:', error);
                            showToast('Terjadi kesalahan saat mengambil data invoice', 'error');
                        });
                    }
                });
            }
            
            // Event listeners for automatic total calculation in create form
            const subTotal = document.getElementById('subTotal');
            const feeMaintenance = document.getElementById('feeMaintenance');
            if (subTotal) subTotal.addEventListener('input', calculateTotal);
            if (feeMaintenance) feeMaintenance.addEventListener('input', calculateTotal);
            
            // Event listeners for automatic total calculation in edit form
            const editSubTotal = document.getElementById('editSubTotal');
            const editFeeMaintenance = document.getElementById('editFeeMaintenance');
            if (editSubTotal) editSubTotal.addEventListener('input', calculateEditTotal);
            if (editFeeMaintenance) editFeeMaintenance.addEventListener('input', calculateEditTotal);
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    ['buatKwitansiModal', 'editKwitansiModal', 'deleteKwitansiModal', 'cetakKwitansiModal'].forEach(id => {
                        if (!document.getElementById(id).classList.contains('hidden')) {
                            toggleModal(id);
                        }
                    });
                }
            });
            
            // Close toast notification
            const closeToast = document.getElementById('closeToast');
            if (closeToast) {
                closeToast.addEventListener('click', hideToast);
            }
            
            console.log("Inisialisasi selesai.");
        });
        
        // Function to format number to Rupiah
        function formatToRupiah(number) {
            if (isNaN(number)) return 'Rp 0';
            return 'Rp ' + parseFloat(number).toLocaleString('id-ID');
        }
        
        // Function to parse Rupiah string back to number
        function parseRupiah(rupiahString) {
            if (typeof rupiahString !== 'string') return 0;
            // Remove "Rp " and all non-digit characters except decimal point
            const cleanString = rupiahString.replace(/Rp\s/g, '').replace(/[^\d.-]/g, '');
            return parseFloat(cleanString) || 0;
        }
        
        // Load invoice options from database
        function loadInvoiceOptions() {
            const select = document.getElementById('pilihInvoice');
            
            if (!select) {
                console.error("Elemen #pilihInvoice tidak ditemukan.");
                return;
            }
            
            // Fetch invoices from API
            fetch('/api/invoices', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Clear existing options except first one
                select.innerHTML = '<option value="">-- Pilih Invoice --</option>';
                
                // Add invoice options
                if (data.data && data.data.length > 0) {
                    data.data.forEach(invoice => {
                        const option = document.createElement('option');
                        option.value = invoice.id;
                        option.textContent = `${invoice.nomor_order} - ${invoice.nama_klien}`;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading invoice options:', error);
                showToast('Terjadi kesalahan saat memuat data invoice', 'error');
            });
        }
        
        // Load kwitansi data from database
        function loadKwitansiData(page = 1, search = '') {
            const tableBody = document.getElementById('kwitansiTableBody');
            const pagination = document.getElementById('pagination');
            
            if (!tableBody) {
                console.error("Elemen #kwitansiTableBody tidak ditemukan.");
                return;
            }
            
            // Show loading state
            tableBody.innerHTML = '<tr><td colspan="11" class="p-4 text-center">Loading data...</td></tr>';
            
            // Fetch data from API
            fetch(`/api/kwitansi?page=${page}&search=${encodeURIComponent(search)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Clear table
                tableBody.innerHTML = '';
                
                // Check if there's data
                if (data.data && data.data.length > 0) {
                    // Populate table with data
                    data.data.forEach((kwitansi, index) => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-slate-50 dark:hover:bg-slate-700';
                        
                        // Calculate row number based on current page
                        const rowNumber = (data.current_page - 1) * data.per_page + index + 1;
                        
                        // Format price with thousand separator
                        const formattedHarga = formatToRupiah(kwitansi.harga);
                        const formattedSubTotal = formatToRupiah(kwitansi.sub_total);
                        const formattedFeeMaintenance = formatToRupiah(kwitansi.fee_maintenance);
                        const formattedTotal = formatToRupiah(kwitansi.total);
                        
                        // Format date
                        let formattedDate = '';
                        if (kwitansi.tanggal) {
                            const date = new Date(kwitansi.tanggal);
                            formattedDate = date.toLocaleDateString('id-ID', { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            });
                        }
                        
                        // Truncate description if too long
                        let deskripsiDisplay = kwitansi.deskripsi || '';
                        if (deskripsiDisplay.length > 50) {
                            deskripsiDisplay = deskripsiDisplay.substring(0, 50) + '...';
                        }
                        
                        row.innerHTML = `
                            <td class="p-4">${rowNumber}.</td>
                            <td class="p-4">${formattedDate}</td>
                            <td class="p-4">${kwitansi.nama_perusahaan}</td>
                            <td class="p-4">${kwitansi.nomor_order}</td>
                            <td class="p-4">${kwitansi.nama_klien}</td>
                            <td class="p-4" title="${kwitansi.deskripsi || ''}">${deskripsiDisplay}</td>
                            <td class="p-4">${formattedHarga}</td>
                            <td class="p-4">${formattedSubTotal}</td>
                            <td class="p-4">${formattedFeeMaintenance}</td>
                            <td class="p-4">${formattedTotal}</td>
                            <td class="p-4">
                                <div class="flex justify-center gap-2">
                                    <button class="edit-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" 
                                        data-id="${kwitansi.id}" 
                                        data-tanggal="${kwitansi.tanggal}"
                                        data-nama-perusahaan="${kwitansi.nama_perusahaan}"
                                        data-nomor-order="${kwitansi.nomor_order}" 
                                        data-nama-klien="${kwitansi.nama_klien}"
                                        data-deskripsi="${kwitansi.deskripsi || ''}"
                                        data-harga="${kwitansi.harga}"
                                        data-sub-total="${kwitansi.sub_total}"
                                        data-fee-maintenance="${kwitansi.fee_maintenance}"
                                        data-total="${kwitansi.total}"
                                        data-tooltip="Edit">
                                        <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">edit</span>
                                    </button>
                                    <button class="cetak-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" 
                                        data-id="${kwitansi.id}" 
                                        data-tooltip="Cetak">
                                        <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">print</span>
                                    </button>
                                    <button class="delete-kwitansi-btn tooltip p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" 
                                        data-id="${kwitansi.id}" 
                                        data-nama-perusahaan="${kwitansi.nama_perusahaan}"
                                        data-nomor-order="${kwitansi.nomor_order}" 
                                        data-tooltip="Hapus">
                                        <span class="material-symbols-outlined text-red-500">delete</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Generate pagination
                    generatePagination(data);
                    
                    // Add event listeners to edit buttons
                    document.querySelectorAll('.edit-kwitansi-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.getElementById('editKwitansiId').value = this.getAttribute('data-id');
                            
                            // Format date for input field (YYYY-MM-DD)
                            let tanggalValue = this.getAttribute('data-tanggal');
                            if (tanggalValue) {
                                const date = new Date(tanggalValue);
                                tanggalValue = date.toISOString().split('T')[0];
                            }
                            document.getElementById('editTanggal').value = tanggalValue;
                            
                            document.getElementById('editNamaPerusahaan').value = this.getAttribute('data-nama-perusahaan');
                            document.getElementById('editNomorOrder').value = this.getAttribute('data-nomor-order');
                            document.getElementById('editNamaKlien').value = this.getAttribute('data-nama-klien');
                            document.getElementById('editDeskripsi').value = this.getAttribute('data-deskripsi');
                            document.getElementById('editHarga').value = this.getAttribute('data-harga');
                            document.getElementById('editSubTotal').value = this.getAttribute('data-sub-total');
                            document.getElementById('editFeeMaintenance').value = this.getAttribute('data-fee-maintenance');
                            
                            // Set both the display and the actual value for total
                            const totalValue = this.getAttribute('data-total');
                            document.getElementById('editTotal').value = formatToRupiah(totalValue);
                            document.getElementById('editTotalValue').value = totalValue;
                            
                            toggleModal('editKwitansiModal');
                        });
                    });
                    
                    // Add event listeners to cetak buttons
                    document.querySelectorAll('.cetak-kwitansi-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            cetakKwitansi(id);
                        });
                    });
                    
                    // Add event listeners to delete buttons
                    document.querySelectorAll('.delete-kwitansi-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.getElementById('deleteKwitansiId').value = this.getAttribute('data-id');
                            document.getElementById('deleteKwitansiNama').textContent = this.getAttribute('data-nama-perusahaan');
                            document.getElementById('deleteKwitansiNomor').textContent = this.getAttribute('data-nomor-order');
                            toggleModal('deleteKwitansiModal');
                        });
                    });
                } else {
                    // No data message
                    tableBody.innerHTML = '<tr><td colspan="11" class="p-4 text-center">Tidak ada data kwitansi</td></tr>';
                    if (pagination) pagination.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Error loading kwitansi data:', error);
                tableBody.innerHTML = '<tr><td colspan="11" class="p-4 text-center text-red-500">Error loading data. Please try again.</td></tr>';
            });
        }
        
        // Generate pagination links
        function generatePagination(data) {
            const pagination = document.getElementById('pagination');
            if (!pagination) return;
            
            pagination.innerHTML = '';
            
            if (data.last_page > 1) {
                // Previous button
                const prevBtn = document.createElement('a');
                prevBtn.href = '#';
                prevBtn.innerHTML = '&laquo; Previous';
                prevBtn.className = data.prev_page_url ? '' : 'disabled';
                if (data.prev_page_url) {
                    prevBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        loadKwitansiData(data.current_page - 1, document.getElementById('searchInput').value);
                    });
                }
                pagination.appendChild(prevBtn);
                
                // Page numbers
                for (let i = 1; i <= data.last_page; i++) {
                    const pageBtn = document.createElement('a');
                    pageBtn.href = '#';
                    pageBtn.textContent = i;
                    pageBtn.className = i === data.current_page ? 'active' : '';
                    pageBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        loadKwitansiData(i, document.getElementById('searchInput').value);
                    });
                    pagination.appendChild(pageBtn);
                }
                
                // Next button
                const nextBtn = document.createElement('a');
                nextBtn.href = '#';
                nextBtn.innerHTML = 'Next &raquo;';
                nextBtn.className = data.next_page_url ? '' : 'disabled';
                if (data.next_page_url) {
                    nextBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        loadKwitansiData(data.current_page + 1, document.getElementById('searchInput').value);
                    });
                }
                pagination.appendChild(nextBtn);
            }
        }
        
        // Modal toggle function
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            const isHidden = modal.classList.contains('hidden');
            
            if (isHidden) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                
                // Reset form if it's a create modal
                if (modalId === 'buatKwitansiModal') {
                    const form = document.getElementById('buatKwitansiForm');
                    if (form) form.reset();
                    const totalInput = document.getElementById('total');
                    if (totalInput) totalInput.value = '';
                    const totalValueInput = document.getElementById('totalValue');
                    if (totalValueInput) totalValueInput.value = '0';
                    
                    // Set today's date again
                    const today = new Date().toISOString().split('T')[0];
                    const tanggalInput = document.getElementById('tanggal');
                    if (tanggalInput) {
                        tanggalInput.value = today;
                    }
                }
            }
        }
        
        // Function to calculate total in create form
        function calculateTotal() {
            const subTotal = parseFloat(document.getElementById('subTotal').value) || 0;
            const feeMaintenance = parseFloat(document.getElementById('feeMaintenance').value) || 0;
            const total = subTotal + feeMaintenance;
            
            // Set both the display and the actual value
            document.getElementById('total').value = formatToRupiah(total);
            document.getElementById('totalValue').value = total;
        }
        
        // Function to calculate total in edit form
        function calculateEditTotal() {
            const subTotal = parseFloat(document.getElementById('editSubTotal').value) || 0;
            const feeMaintenance = parseFloat(document.getElementById('editFeeMaintenance').value) || 0;
            const total = subTotal + feeMaintenance;
            
            // Set both the display and the actual value
            document.getElementById('editTotal').value = formatToRupiah(total);
            document.getElementById('editTotalValue').value = total;
        }
        
        // Function to print kwitansi
        function cetakKwitansi(id) {
            // Show modal first
            toggleModal('cetakKwitansiModal');
            
            // Fetch kwitansi data
            fetch(`/api/kwitansi/${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const kwitansi = data.data;
                    
                    // Format date
                    let formattedDate = '';
                    if (kwitansi.tanggal) {
                        const date = new Date(kwitansi.tanggal);
                        formattedDate = date.toLocaleDateString('id-ID', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                    }
                    
                    // Format price with thousand separator
                    const formattedHarga = formatToRupiah(kwitansi.harga);
                    const formattedSubTotal = formatToRupiah(kwitansi.sub_total);
                    const formattedFeeMaintenance = formatToRupiah(kwitansi.fee_maintenance);
                    const formattedTotal = formatToRupiah(kwitansi.total);
                    
                    // Format total in ribuan (K) instead of juta (jt)
                    const totalInRibuan = (kwitansi.total / 1000).toFixed(0);
                    
                    // Format sub total in ribuan (K) instead of juta (jt)
                    const subTotalInRibuan = (kwitansi.sub_total / 1000).toFixed(0);
                    
                    // Calculate fee maintenance percentage
                    const feeMaintenancePercentage = kwitansi.sub_total > 0 ? 
                        Math.round((kwitansi.fee_maintenance / kwitansi.sub_total) * 100) : 0;
                    
                    // Create kwitansi HTML
                    const kwitansiHTML = `
                        <div class="kwitansi-container">
                            <div class="kwitansi-header">
                                <div class="kwitansi-left-section">
                                    <div class="kwitansi-company-name">Udizital</div>
                                    <div class="kwitansi-company-tagline">C R E A T I V E&nbsp;&nbsp;&nbsp;A G E N C Y</div>
                                    <h1 class="kwitansi-title">Kwitansi</h1>
                                </div>
                                <div class="kwitansi-right-section">
                                    <div class="kwitansi-invoice-number">#${kwitansi.nomor_order}</div>
                                    <div class="kwitansi-date-row">Tgl: ${formattedDate}</div>
                                </div>
                            </div>

                            <div class="kwitansi-main-content">
                                <div class="kwitansi-info-row">
                                    <div class="kwitansi-info-label">Telah Diterima dari</div>
                                    <div class="kwitansi-info-separator">:</div>
                                    <div class="kwitansi-info-value">${kwitansi.nama_klien}</div>
                                </div>

                                <div class="kwitansi-info-row">
                                    <div class="kwitansi-info-label">Uang Sejumlah</div>
                                    <div class="kwitansi-info-separator">:</div>
                                    <div class="kwitansi-info-value">${totalInRibuan}K</div>
                                </div>

                                <div class="kwitansi-info-row">
                                    <div class="kwitansi-info-label">Untuk</div>
                                    <div class="kwitansi-info-separator">:</div>
                                    <div class="kwitansi-info-value">${kwitansi.deskripsi}</div>
                                </div>
                            </div>

                            <div class="kwitansi-keterangan-section">
                                <div class="kwitansi-keterangan-header">KETERANGAN</div>
                                <div class="kwitansi-keterangan-content">
                                    <div class="kwitansi-keterangan-row">
                                        <div class="kwitansi-keterangan-label">SUB-TOTAL</div>
                                        <div class="kwitansi-keterangan-separator">:</div>
                                        <div class="kwitansi-keterangan-value">${subTotalInRibuan}K</div>
                                    </div>
                                    <div class="kwitansi-keterangan-row">
                                        <div class="kwitansi-keterangan-label">FEE MAINTENANCE</div>
                                        <div class="kwitansi-keterangan-separator">:</div>
                                        <div class="kwitansi-keterangan-value">${feeMaintenancePercentage}%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="kwitansi-totals-section">
                                <div class="kwitansi-total-row">
                                    <div class="kwitansi-total-label">TOTAL</div>
                                    <div class="kwitansi-total-separator">:</div>
                                    <div class="kwitansi-total-value">${formattedTotal}</div>
                                </div>
                            </div>

                            <div class="kwitansi-payment-info">
                                <div class="kwitansi-payment-info-title">INFO PEMBAYARAN :</div>
                                <div class="kwitansi-bank-info">
                                    <div class="kwitansi-bank-row">
                                        <span class="kwitansi-bank-label">BANK</span>
                                        <span>: BRI</span>
                                    </div>
                                    <div class="kwitansi-bank-row">
                                        <span class="kwitansi-bank-label">No. Rekening</span>
                                        <span>: 4030-01-011093-53-6</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Update modal content
                    document.getElementById('kwitansiPrintContent').innerHTML = kwitansiHTML;
                } else {
                    showToast(data.message || 'Gagal memuat data kwitansi', 'error');
                    toggleModal('cetakKwitansiModal');
                }
            })
            .catch(error => {
                console.error('Error fetching kwitansi details:', error);
                showToast('Terjadi kesalahan saat memuat data kwitansi', 'error');
                toggleModal('cetakKwitansiModal');
            });
        }
        
        // Function to convert number to words (Indonesian)
        function numberToWords(num) {
            const a = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
            const bilangan = ['', 'ribu', 'juta', 'miliar', 'triliun'];
            
            if (num < 12) {
                return a[num];
            } else if (num < 20) {
                return a[num - 10] + ' belas';
            } else if (num < 100) {
                return (Math.floor(num / 10) === 1 ? 'sepuluh' : a[Math.floor(num / 10)] + ' puluh') + ' ' + a[num % 10];
            } else if (num < 200) {
                return 'seratus ' + numberToWords(num - 100);
            } else if (num < 1000) {
                return a[Math.floor(num / 100)] + ' ratus ' + numberToWords(num % 100);
            } else {
                let temp = '';
                let i = 0;
                
                while (num > 0) {
                    if (num % 1000 !== 0) {
                        if (num % 1000 < 12 && num >= 1000 && num % 1000 === 1) {
                            temp = 'se' + bilangan[i] + ' ' + temp;
                        } else if (num % 1000 < 100 && num >= 1000 && num % 1000 === 1) {
                            temp = 'se' + bilangan[i] + ' ' + temp;
                        } else {
                            temp = numberToWords(num % 1000) + ' ' + bilangan[i] + ' ' + temp;
                        }
                    }
                    num = Math.floor(num / 1000);
                    i++;
                }
                
                return temp.trim();
            }
        }
        
        // Submit Buat Kwitansi form - RENAMED FUNCTION
        function submitBuatKwitansiForm() {
            const form = document.getElementById('buatKwitansiForm');
            
            if (!form) {
                console.error("Form buat kwitansi tidak ditemukan.");
                return;
            }
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show loading state
            const submitText = document.getElementById('submitBuatKwitansiText');
            const submitSpinner = document.getElementById('submitBuatKwitansiSpinner');
            const submitBtn = document.getElementById('submitBuatKwitansiBtn');
            
            if (submitText) submitText.textContent = 'Menyimpan...';
            if (submitSpinner) submitSpinner.classList.remove('hidden');
            if (submitBtn) submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            fetch('/api/kwitansi', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
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
                    showToast('Kwitansi berhasil dibuat!');
                    toggleModal('buatKwitansiModal');
                    loadKwitansiData();
                } else {
                    showToast(data.message || 'Gagal membuat kwitansi', 'error');
                }
            })
            .catch(error => {
                console.error('Error creating kwitansi:', error);
                showToast('Terjadi kesalahan pada server: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset loading state
                if (submitText) submitText.textContent = 'Buat Kwitansi';
                if (submitSpinner) submitSpinner.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
            });
        }
        
        // Submit Edit Kwitansi form - RENAMED FUNCTION
        function submitEditKwitansiForm() {
            const form = document.getElementById('editKwitansiForm');
            
            if (!form) {
                console.error("Form edit kwitansi tidak ditemukan.");
                return;
            }
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show loading state
            const submitText = document.getElementById('submitEditKwitansiText');
            const submitSpinner = document.getElementById('submitEditKwitansiSpinner');
            const submitBtn = document.getElementById('submitEditKwitansiBtn');
            
            if (submitText) submitText.textContent = 'Menyimpan...';
            if (submitSpinner) submitSpinner.classList.remove('hidden');
            if (submitBtn) submitBtn.disabled = true;
            
            const formData = new FormData(form);
            const id = document.getElementById('editKwitansiId').value;
            
            fetch(`/api/kwitansi/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
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
                    showToast('Kwitansi berhasil diperbarui!');
                    toggleModal('editKwitansiModal');
                    loadKwitansiData();
                } else {
                    showToast(data.message || 'Gagal memperbarui kwitansi', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating kwitansi:', error);
                showToast('Terjadi kesalahan pada server: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset loading state
                if (submitText) submitText.textContent = 'Update Kwitansi';
                if (submitSpinner) submitSpinner.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
            });
        }
        
        // Confirm delete kwitansi
        function confirmDeleteKwitansi() {
            const id = document.getElementById('deleteKwitansiId').value;
            
            if (!id) {
                console.error("ID kwitansi tidak ditemukan.");
                return;
            }
            
            // Show loading state
            const confirmText = document.getElementById('confirmDeleteText');
            const confirmSpinner = document.getElementById('confirmDeleteSpinner');
            const confirmBtn = document.getElementById('confirmDeleteKwitansiBtn');
            
            if (confirmText) confirmText.textContent = 'Menghapus...';
            if (confirmSpinner) confirmSpinner.classList.remove('hidden');
            if (confirmBtn) confirmBtn.disabled = true;
            
            fetch(`/api/kwitansi/${id}`, {
                method: 'DELETE',
                headers: {
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
                    showToast('Kwitansi berhasil dihapus!');
                    toggleModal('deleteKwitansiModal');
                    loadKwitansiData();
                } else {
                    showToast(data.message || 'Gagal menghapus kwitansi', 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting kwitansi:', error);
                showToast('Terjadi kesalahan pada server: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset loading state
                if (confirmText) confirmText.textContent = 'Hapus';
                if (confirmSpinner) confirmSpinner.classList.add('hidden');
                if (confirmBtn) confirmBtn.disabled = false;
            });
        }
        
        // Toast notification functions
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            if (!toast || !toastMessage) return;
            
            // Set background color based on type
            if (type === 'error') {
                toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center';
            } else {
                toast.className = 'fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center';
            }
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                hideToast();
            }, 3000);
        }
        
        function hideToast() {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.add('translate-y-20', 'opacity-0');
            }
        }
    </script>
</body>
</html>
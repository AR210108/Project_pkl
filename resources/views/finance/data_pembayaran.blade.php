<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                        "sidebar-light": "#f3f4f6",
                        "sidebar-dark": "#1e293b",
                        "card-light": "#ffffff",
                        "card-dark": "#1e293b",
                        "text-light": "#1e293b",
                        "text-dark": "#f8fafc",
                        "text-muted-light": "#64748b",
                        "text-muted-dark": "#94a3b8",
                        "border-light": "#e2e8f0",
                        "border-dark": "#334155",
                        "success": "#10b981",
                        "warning": "#f59e0b",
                        "danger": "#ef4444"
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                    boxShadow: {
                        card: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                        "card-hover": "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .bx {
            font-size: 24px;
            vertical-align: middle;
        }
        
        /* Responsive table styles */
        .payment-table {
            transition: all 0.2s ease;
            min-width: 800px; /* Ensure minimum width for proper layout */
        }
        
        .payment-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        /* Sticky header for horizontal scrolling */
        .payment-table thead th {
            position: sticky;
            top: 0;
            background-color: #ffffff;
            z-index: 10;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .status-paid {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .status-partial {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-pending {
            background-color: rgba(107, 114, 128, 0.15);
            color: #4b5563;
        }
        
        .status-overdue {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }
        
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .category-design {
            background-color: rgba(139, 92, 246, 0.15);
            color: #5b21b6;
        }
        
        .category-programming {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }
        
        .category-marketing {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        /* Button styles */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem; /* Smaller font for mobile */
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            white-space: nowrap;
        }
        
        /* Larger screens */
        @media (min-width: 768px) {
            .btn-action {
                font-size: 0.875rem;
            }
        }
        
        .btn-invoice {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-invoice:hover {
            background-color: #2563eb;
        }
        
        .btn-edit {
            background-color: #10b981;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #059669;
        }
        
        .btn-delete {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #dc2626;
        }
        
        /* Form input styles */
        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Modal styles */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        /* Action buttons container */
        .action-buttons {
            display: flex;
            gap: 0.25rem; /* Smaller gap for mobile */
            flex-wrap: wrap;
            justify-content: center; /* Center on mobile */
        }
        
        /* Larger screens */
        @media (min-width: 768px) {
            .action-buttons {
                gap: 0.5rem;
                justify-content: flex-start;
            }
        }
        
        /* Responsive table container */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        }
        
        /* Custom scrollbar for webkit browsers */
        .table-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Responsive modal content */
        @media (max-width: 640px) {
            .modal-content {
                padding: 1rem;
                margin: 0.5rem;
            }
        }
        
        /* Responsive text sizes */
        .responsive-text-sm {
            font-size: 0.75rem;
            line-height: 1rem;
        }
        
        @media (min-width: 768px) {
            .responsive-text-sm {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
        }
        
        /* Responsive pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        @media (min-width: 768px) {
            .pagination-container {
                justify-content: flex-end;
                gap: 0.5rem;
            }
        }
        
        /* Hide some columns on very small screens if needed */
        @media (max-width: 480px) {
            .hide-mobile {
                display: none;
            }
        }
    </style>
</head>
<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')
        
        <!-- Main Content -->
        <main class="flex-1 min-w-0">
            <div class="flex flex-col h-full">
                <div class="flex-grow p-3 sm:p-4 md:p-6 lg:p-8">
                    <header class="mb-4 sm:mb-6 md:mb-8">
                        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold">Data Pembayaran</h2>
                    </header>
                    
                    <!-- Controls Section -->
                    <div class="flex flex-col gap-4 mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <button onclick="openAddModal()" class="btn-primary font-medium py-2 px-4 sm:py-3 sm:px-6 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center">
                                <i class='bx bx-plus'></i>
                                <span>Tambah Pembayaran</span>
                            </button>
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <div class="relative w-full sm:w-48 md:w-64">
                                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                    <input id="payment-search" class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Search" type="text" onkeyup="filterPayments()" />
                                </div>
                                <button class="btn-secondary font-medium py-2 px-4 rounded-lg w-full sm:w-auto">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="bg-card-light rounded-DEFAULT p-3 sm:p-4 md:p-6 border border-border-light shadow-card">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                            <h3 class="text-base sm:text-lg font-semibold">Daftar Pembayaran</h3>
                            <div class="responsive-text-sm text-text-muted-light">
                                Menampilkan <span id="payment-start">1</span>-<span id="payment-end">5</span> dari <span id="payment-total">20</span> data
                            </div>
                        </div>
                        
                        <!-- Responsive Table Container -->
                        <div class="table-container">
                            <table class="w-full text-left responsive-text-sm payment-table">
                                <thead>
                                    <tr class="border-b border-border-light">
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">No</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Layanan</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Kategori</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Harga</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Klien</th>
                                        <th class="p-2 sm:p-3 font-semibold text-center whitespace-nowrap">Pembayaran Awal</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Pelunasan</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Status</th>
                                        <th class="p-2 sm:p-3 font-semibold whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-table-body">
                                    <!-- Data akan diisi dengan JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Responsive Pagination -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                            <div class="responsive-text-sm text-text-muted-light text-center sm:text-left">
                                Menampilkan <span id="payment-page-start">1</span>-<span id="payment-page-end">5</span> dari <span id="payment-page-total">20</span> data
                            </div>
                            <div class="pagination-container" id="payment-pagination">
                                <!-- Pagination buttons akan diisi dengan JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="bg-gray-100 border-t border-border-light text-center py-3 sm:py-4">
                    <p class="text-xs sm:text-sm text-text-muted-light">Copyright ©2025 by digicity.id</p>
                </footer>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Data Pembayaran -->
    <div id="addModal" class="fixed inset-0 modal-backdrop flex items-center justify-center hidden z-50 p-2 sm:p-4">
        <div class="modal-content bg-white rounded-DEFAULT p-4 sm:p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-text-light">Tambah Data Pembayaran</h3>
                <button onclick="closeAddModal()" class="text-text-muted-light hover:text-text-light">
                    <i class='bx bx-x text-xl sm:text-2xl'></i>
                </button>
            </div>
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-text-light mb-2 text-sm">No</label>
                        <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light" placeholder="Nomor">
                    </div>
                    <div>
                        <label class="block text-text-light mb-2 text-sm">Kategori Layanan</label>
                        <select id="payment-category" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light" onchange="updateServiceOptions()">
                            <option value="">Pilih Kategori</option>
                            <option value="design">Desain</option>
                            <option value="programming">Programming</option>
                            <option value="marketing">Digital Marketing</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-text-light mb-2 text-sm">Layanan</label>
                        <select id="payment-service" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                            <option value="">Pilih Layanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-text-light mb-2 text-sm">Harga</label>
                        <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light" placeholder="Harga">
                    </div>
                    <div>
                        <label class="block text-text-light mb-2 text-sm">Klien</label>
                        <select class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                            <option value="">Pilih Klien</option>
                            <option value="pt1">PT. Teknologi Maju</option>
                            <option value="cv1">CV. Digital Solusi</option>
                            <option value="ud1">UD. Kreatif Indonesia</option>
                            <option value="pt2">PT. Inovasi Nusantara</option>
                            <option value="cv2">CV. Kreatif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-text-light mb-2 text-sm">Pembayaran Awal</label>
                        <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light" placeholder="Jumlah Pembayaran Awal">
                    </div>
                    <div>
                        <label class="block text-text-light mb-2 text-sm">Pelunasan</label>
                        <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light" placeholder="Jumlah Pelunasan">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-text-light mb-2 text-sm">Status</label>
                        <select class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                            <option value="">Pilih Status</option>
                            <option value="paid">Lunas</option>
                            <option value="partial">Sebagian</option>
                            <option value="pending">Pending</option>
                            <option value="overdue">Terlambat</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 btn-secondary rounded-lg order-2 sm:order-1 w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg order-1 sm:order-2 w-full sm:w-auto">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Invoice -->
    <div id="invoiceDetailModal" class="fixed inset-0 modal-backdrop flex items-center justify-center hidden z-50 p-2 sm:p-4">
        <div class="modal-content bg-white rounded-DEFAULT p-4 sm:p-6 w-full max-w-6xl max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-text-light">Detail Invoice</h3>
                <button onclick="closeInvoiceDetailModal()" class="text-text-muted-light hover:text-text-light">
                    <i class='bx bx-x text-xl sm:text-2xl'></i>
                </button>
            </div>
            
            <!-- Header Invoice -->
            <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                    <div>
                        <h4 class="text-base sm:text-lg font-semibold text-text-light mb-2">INVOICE</h4>
                        <p class="text-xs sm:text-sm text-text-muted-light">Nomor: <span id="invoice-no" class="font-medium text-text-light"></span></p>
                        <p class="text-xs sm:text-sm text-text-muted-light">Tanggal: <span id="invoice-date" class="font-medium text-text-light"></span></p>
                    </div>
                    <div class="text-left sm:text-right">
                        <h4 class="text-base sm:text-lg font-semibold text-text-light mb-2">DigiCity</h4>
                        <p class="text-xs sm:text-sm text-text-muted-light">Jl. Teknologi No. 123</p>
                        <p class="text-xs sm:text-sm text-text-muted-light">Jakarta, Indonesia</p>
                        <p class="text-xs sm:text-sm text-text-muted-light">Email: info@digicity.id</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Klien -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                    <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Informasi Perusahaan</h5>
                    <div class="space-y-2">
                        <div class="flex flex-col sm:flex-row">
                            <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nama:</span>
                            <span id="company-name" class="text-xs sm:text-sm text-text-light font-medium"></span>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Alamat:</span>
                            <span id="company-address" class="text-xs sm:text-sm text-text-light font-medium"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                    <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Informasi Kontak</h5>
                    <div class="space-y-2">
                        <div class="flex flex-col sm:flex-row">
                            <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nama Klien:</span>
                            <span id="client-name" class="text-xs sm:text-sm text-text-light font-medium"></span>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="text-xs sm:text-sm text-text-muted-light sm:w-32">Nomor Order:</span>
                            <span id="order-number" class="text-xs sm:text-sm text-text-light font-medium"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Detail Items -->
            <div class="mb-4 sm:mb-6">
                <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Detail Layanan</h5>
                <div class="table-container">
                    <table class="w-full border border-border-light text-xs sm:text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-border-light px-2 sm:px-4 py-2 text-left font-semibold text-text-light whitespace-nowrap">No</th>
                                <th class="border border-border-light px-2 sm:px-4 py-2 text-left font-semibold text-text-light">Deskripsi</th>
                                <th class="border border-border-light px-2 sm:px-4 py-2 text-center font-semibold text-text-light whitespace-nowrap">Kategori</th>
                                <th class="border border-border-light px-2 sm:px-4 py-2 text-center font-semibold text-text-light whitespace-nowrap">Harga</th>
                                <th class="border border-border-light px-2 sm:px-4 py-2 text-center font-semibold text-text-light whitespace-nowrap">Qty</th>
                                <th class="border border-border-light px-2 sm:px-4 py-2 text-right font-semibold text-text-light whitespace-nowrap">Total</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-items">
                            <!-- Items akan diisi dengan JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Metode Pembayaran</h5>
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                        <p id="payment-method" class="text-xs sm:text-sm text-text-light"></p>
                    </div>
                </div>
                <div>
                    <h5 class="font-semibold text-text-light mb-2 sm:mb-3 text-sm sm:text-base">Ringkasan Pembayaran</h5>
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-xs sm:text-sm text-text-muted-light">Subtotal:</span>
                            <span id="subtotal" class="text-xs sm:text-sm text-text-light font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs sm:text-sm text-text-muted-light">Pajak (11%):</span>
                            <span id="tax" class="text-xs sm:text-sm text-text-light font-medium"></span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-border-light">
                            <span class="text-xs sm:text-sm font-semibold text-text-light">Total:</span>
                            <span id="total" class="text-xs sm:text-sm font-semibold text-text-light"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-center sm:justify-end gap-2 sm:gap-3 mt-4 sm:mt-6">
                <button onclick="printInvoice()" class="btn-action btn-invoice w-full sm:w-auto justify-center">
                    <i class='bx bx-printer'></i>
                    <span>Cetak</span>
                </button>
                <button onclick="downloadInvoice()" class="btn-action btn-edit w-full sm:w-auto justify-center">
                    <i class='bx bx-download'></i>
                    <span>Download</span>
                </button>
                <button onclick="closeInvoiceDetailModal()" class="btn-action btn-secondary w-full sm:w-auto justify-center">
                    <i class='bx bx-x'></i>
                    <span>Tutup</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data pembayaran
        const paymentData = [
            { no: 1, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 5.000.000", klien: "PT. Teknologi Maju", awal: "Rp 2.500.000", lunas: "Rp 2.500.000", status: "paid" },
            { no: 2, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 3.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.500.000", lunas: "Rp 1.500.000", status: "paid" },
            { no: 3, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 4.000.000", klien: "UD. Kreatif Indonesia", awal: "Rp 2.000.000", lunas: "Rp 0", status: "partial" },
            { no: 4, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 8.000.000", klien: "PT. Inovasi Nusantara", awal: "Rp 4.000.000", lunas: "Rp 0", status: "pending" },
            { no: 5, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 7.500.000", klien: "CV. Kreatif", awal: "Rp 2.500.000", lunas: "Rp 0", status: "overdue" },
            { no: 6, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 6.000.000", klien: "PT. Digital Nusantara", awal: "Rp 3.000.000", lunas: "Rp 3.000.000", status: "paid" },
            { no: 7, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 2.500.000", klien: "CV. Kreatif Digital", awal: "Rp 1.250.000", lunas: "Rp 1.250.000", status: "paid" },
            { no: 8, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 3.500.000", klien: "UD. Inovasi Teknologi", awal: "Rp 1.750.000", lunas: "Rp 0", status: "partial" },
            { no: 9, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 9.000.000", klien: "PT. Solusi Digital", awal: "Rp 4.500.000", lunas: "Rp 0", status: "pending" },
            { no: 10, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 5.500.000", klien: "CV. Karya Kreatif", awal: "Rp 2.750.000", lunas: "Rp 0", status: "overdue" },
            { no: 11, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 7.000.000", klien: "PT. Teknologi Maju", awal: "Rp 3.500.000", lunas: "Rp 3.500.000", status: "paid" },
            { no: 12, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 2.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.000.000", lunas: "Rp 1.000.000", status: "paid" },
            { no: 13, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 4.500.000", klien: "UD. Kreatif Indonesia", awal: "Rp 2.250.000", lunas: "Rp 0", status: "partial" },
            { no: 14, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 10.000.000", klien: "PT. Inovasi Nusantara", awal: "Rp 5.000.000", lunas: "Rp 0", status: "pending" },
            { no: 15, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 6.500.000", klien: "CV. Kreatif", awal: "Rp 3.250.000", lunas: "Rp 0", status: "overdue" },
            { no: 16, layanan: "Pembuatan Website", kategori: "programming", harga: "Rp 5.500.000", klien: "PT. Digital Nusantara", awal: "Rp 2.750.000", lunas: "Rp 2.750.000", status: "paid" },
            { no: 17, layanan: "SEO Optimization", kategori: "marketing", harga: "Rp 3.500.000", klien: "CV. Kreatif Digital", awal: "Rp 1.750.000", lunas: "Rp 1.750.000", status: "paid" },
            { no: 18, layanan: "Manajemen Sosial Media", kategori: "marketing", harga: "Rp 5.000.000", klien: "UD. Inovasi Teknologi", awal: "Rp 2.500.000", lunas: "Rp 0", status: "partial" },
            { no: 19, layanan: "Pengembangan Aplikasi Mobile", kategori: "programming", harga: "Rp 11.000.000", klien: "PT. Solusi Digital", awal: "Rp 5.500.000", lunas: "Rp 0", status: "pending" },
            { no: 20, layanan: "Desain UI/UX", kategori: "design", harga: "Rp 8.000.000", klien: "CV. Karya Kreatif", awal: "Rp 4.000.000", lunas: "Rp 0", status: "overdue" }
        ];

        // Data detail invoice
        const invoiceDetailData = {
            1: {
                no: "INV-2023-001",
                date: "15 Januari 2023",
                companyName: "PT. Teknologi Maju",
                companyAddress: "Jl. Sudirman No. 456, Jakarta Selatan",
                clientName: "Budi Santoso",
                orderNumber: "ORD-2023-001",
                paymentMethod: "Transfer Bank - BCA",
                kategori: "programming",
                items: [
                    { no: 1, description: "Pembuatan Website Company Profile", harga: "Rp 5.000.000", qty: 1, total: "Rp 5.000.000" }
                ],
                subtotal: "Rp 5.000.000",
                tax: "Rp 550.000",
                total: "Rp 5.550.000"
            },
            2: {
                no: "INV-2023-002",
                date: "20 Januari 2023",
                companyName: "CV. Digital Solusi",
                companyAddress: "Jl. Gatot Subroto No. 789, Jakarta Pusat",
                clientName: "Andi Wijaya",
                orderNumber: "ORD-2023-002",
                paymentMethod: "Transfer Bank - Mandiri",
                kategori: "marketing",
                items: [
                    { no: 1, description: "SEO Optimization Package", harga: "Rp 3.000.000", qty: 1, total: "Rp 3.000.000" }
                ],
                subtotal: "Rp 3.000.000",
                tax: "Rp 330.000",
                total: "Rp 3.330.000"
            },
            3: {
                no: "INV-2023-003",
                date: "05 Februari 2023",
                companyName: "UD. Kreatif Indonesia",
                companyAddress: "Jl. Thamrin No. 123, Jakarta Pusat",
                clientName: "Siti Nurhaliza",
                orderNumber: "ORD-2023-003",
                paymentMethod: "Transfer Bank - BNI",
                kategori: "marketing",
                items: [
                    { no: 1, description: "Manajemen Sosial Media - 3 Bulan", harga: "Rp 4.000.000", qty: 1, total: "Rp 4.000.000" }
                ],
                subtotal: "Rp 4.000.000",
                tax: "Rp 440.000",
                total: "Rp 4.440.000"
            },
            4: {
                no: "INV-2023-004",
                date: "10 Februari 2023",
                companyName: "PT. Inovasi Nusantara",
                companyAddress: "Jl. Rasuna Said No. 567, Jakarta Selatan",
                clientName: "Ahmad Fauzi",
                orderNumber: "ORD-2023-004",
                paymentMethod: "Transfer Bank - BCA",
                kategori: "programming",
                items: [
                    { no: 1, description: "Pengembangan Aplikasi Mobile (iOS & Android)", harga: "Rp 8.000.000", qty: 1, total: "Rp 8.000.000" }
                ],
                subtotal: "Rp 8.000.000",
                tax: "Rp 880.000",
                total: "Rp 8.880.000"
            },
            5: {
                no: "INV-2023-005",
                date: "15 Februari 2023",
                companyName: "CV. Kreatif",
                companyAddress: "Jl. MH Thamrin No. 890, Jakarta Pusat",
                clientName: "Dewi Lestari",
                orderNumber: "ORD-2023-005",
                paymentMethod: "Transfer Bank - Mandiri",
                kategori: "design",
                items: [
                    { no: 1, description: "Desain UI/UX - 5 Halaman", harga: "Rp 7.500.000", qty: 1, total: "Rp 7.500.000" }
                ],
                subtotal: "Rp 7.500.000",
                tax: "Rp 825.000",
                total: "Rp 8.325.000"
            }
        };

        // Layanan berdasarkan kategori
        const servicesByCategory = {
            design: [
                "Desain UI/UX",
                "Desain Logo",
                "Desain Brand Identity",
                "Desain Grafis",
                "Desain Kemasan",
                "Desain Buku",
                "Desain Kaos",
                "Desain Interior"
            ],
            programming: [
                "Pembuatan Website",
                "Pengembangan Aplikasi Mobile",
                "Pengembangan Sistem Informasi",
                "Pengembangan API",
                "Integrasi Sistem",
                "Pengembangan E-commerce",
                "Pengembangan CRM",
                "Pengembangan Aplikasi Desktop"
            ],
            marketing: [
                "SEO Optimization",
                "Manajemen Sosial Media",
                "Content Marketing",
                "Email Marketing",
                "Google Ads",
                "Facebook Ads",
                "Instagram Marketing",
                "Digital Marketing Strategy"
            ]
        };

        // Pagination variables
        let paymentCurrentPage = 1;
        const paymentItemsPerPage = 5;
        let paymentFilteredData = [...paymentData];

        // Load sidebar dari file terpisah
        function loadSidebar() {
            fetch('sidebar.html')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('sidebar-container').innerHTML = html;
                    
                    // Inisialisasi fungsi sidebar setelah dimuat
                    const script = document.createElement('script');
                    script.textContent = `
                        // Ambil elemen yang diperlukan
                        const hamburger = document.getElementById('hamburger');
                        const sidebar = document.getElementById('sidebar');
                        const overlay = document.getElementById('overlay');

                        // Fungsi untuk membuka sidebar
                        function openSidebar() {
                            sidebar.classList.remove('-translate-x-full');
                            overlay.classList.remove('hidden');
                            hamburger.classList.add('hamburger-active');
                            document.body.style.overflow = 'hidden'; // Mencegah scroll saat sidebar terbuka
                        }

                        // Fungsi untuk menutup sidebar
                        function closeSidebar() {
                            sidebar.classList.add('-translate-x-full');
                            overlay.classList.add('hidden');
                            hamburger.classList.remove('hamburger-active');
                            document.body.style.overflow = ''; // Kembalikan scroll
                        }

                        // Event listener untuk hamburger
                        hamburger.addEventListener('click', () => {
                            if (sidebar.classList.contains('-translate-x-full')) {
                                openSidebar();
                            } else {
                                closeSidebar();
                            }
                        });

                        // Event listener untuk overlay
                        overlay.addEventListener('click', closeSidebar);

                        // Event listener untuk escape key
                        document.addEventListener('keydown', (e) => {
                            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                                closeSidebar();
                            }
                        });

                        // Event listener untuk resize window
                        window.addEventListener('resize', () => {
                            if (window.innerWidth >= 768) {
                                closeSidebar(); // Tutup sidebar jika layar menjadi besar
                            }
                        });

                        // Event listener untuk menutup sidebar saat link diklik di mobile
                        document.querySelectorAll('nav a').forEach(link => {
                            link.addEventListener('click', () => {
                                if (window.innerWidth < 768) {
                                    closeSidebar();
                                }
                            });
                        });
                    `;
                    document.body.appendChild(script);
                })
                .catch(error => {
                    console.error('Error loading sidebar:', error);
                    // Fallback: tampilkan sidebar sederhana jika gagal memuat
                    document.getElementById('sidebar-container').innerHTML = `
                        <aside class="w-64 bg-white dark:bg-gray-800 flex flex-col p-6 fixed md:static inset-y-0 left-0 z-50 sidebar-transition transform -translate-x-full md:translate-x-0 shadow-lg md:shadow-none">
                            <div class="flex-grow">
                                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-6 md:mb-12 text-gray-800 dark:text-white">Brand</h1>
                                <nav class="space-y-2">
                                    <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/finance">
                                        <i class='bx bx-home'></i>
                                        <span class="text-sm md:text-base">Beranda</span>
                                    </a>
                                    <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/data">
                                        <i class='bx bx-list-ul'></i>
                                        <span class="text-sm md:text-base">Data Layanan</span>
                                    </a>
                                    <a class="flex items-center space-x-3 text-primary bg-blue-50 dark:bg-blue-900/20 font-medium p-3 rounded-lg transition-colors" href="/pembayaran">
                                        <i class='bx bx-credit-card'></i>
                                        <span class="text-sm md:text-base">Data Pembayaran</span>
                                    </a>
                                    <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/data_in_out">
                                        <i class='bx bx-cart'></i>
                                        <span class="text-sm md:text-base">Data Order</span>
                                    </a>
                                </nav>
                            </div>
                            <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="#">
                                    <i class='bx bx-log-out'></i>
                                    <span class="text-sm md:text-base">Log Out</span>
                                </a>
                            </div>
                        </aside>
                    `;
                });
        }

        // Update service options based on selected category
        function updateServiceOptions() {
            const categorySelect = document.getElementById('payment-category');
            const serviceSelect = document.getElementById('payment-service');
            const selectedCategory = categorySelect.value;
            
            // Clear current options
            serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
            
            // Add options based on selected category
            if (selectedCategory && servicesByCategory[selectedCategory]) {
                servicesByCategory[selectedCategory].forEach(service => {
                    const option = document.createElement('option');
                    option.value = service;
                    option.textContent = service;
                    serviceSelect.appendChild(option);
                });
            }
        }

        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Invoice detail modal functions
        function openInvoiceDetailModal(paymentNo) {
            const detail = invoiceDetailData[paymentNo];
            if (!detail) {
                // Generate default data if not available
                const payment = paymentData.find(p => p.no === paymentNo);
                if (payment) {
                    generateDefaultInvoiceDetail(paymentNo, payment);
                } else {
                    alert('Data invoice tidak tersedia');
                    return;
                }
            }
            
            const invoiceDetail = invoiceDetailData[paymentNo];
            
            // Fill modal with data
            document.getElementById('invoice-no').textContent = invoiceDetail.no;
            document.getElementById('invoice-date').textContent = invoiceDetail.date;
            document.getElementById('company-name').textContent = invoiceDetail.companyName;
            document.getElementById('company-address').textContent = invoiceDetail.companyAddress;
            document.getElementById('client-name').textContent = invoiceDetail.clientName;
            document.getElementById('order-number').textContent = invoiceDetail.orderNumber;
            document.getElementById('payment-method').textContent = invoiceDetail.paymentMethod;
            document.getElementById('subtotal').textContent = invoiceDetail.subtotal;
            document.getElementById('tax').textContent = invoiceDetail.tax;
            document.getElementById('total').textContent = invoiceDetail.total;
            
            // Determine category badge
            let categoryBadge = '';
            switch(invoiceDetail.kategori) {
                case 'design':
                    categoryBadge = '<span class="category-badge category-design">Desain</span>';
                    break;
                case 'programming':
                    categoryBadge = '<span class="category-badge category-programming">Programming</span>';
                    break;
                case 'marketing':
                    categoryBadge = '<span class="category-badge category-marketing">Digital Marketing</span>';
                    break;
            }
            
            // Fill items table
            const itemsTableBody = document.getElementById('invoice-items');
            itemsTableBody.innerHTML = '';
            
            invoiceDetail.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light">${item.no}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light">${item.description}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-center">${categoryBadge}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-center">${item.harga}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-center">${item.qty}</td>
                    <td class="border border-border-light px-2 sm:px-4 py-2 text-text-light text-right">${item.total}</td>
                `;
                itemsTableBody.appendChild(row);
            });
            
            // Show modal
            document.getElementById('invoiceDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeInvoiceDetailModal() {
            document.getElementById('invoiceDetailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function generateDefaultInvoiceDetail(paymentNo, payment) {
            // Generate default invoice detail based on payment data
            invoiceDetailData[paymentNo] = {
                no: `INV-2023-${String(paymentNo).padStart(3, '0')}`,
                date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }),
                companyName: payment.klien,
                companyAddress: "Jl. Contoh No. 123, Jakarta",
                clientName: "Nama Klien",
                orderNumber: `ORD-2023-${String(paymentNo).padStart(3, '0')}`,
                paymentMethod: "Transfer Bank",
                kategori: payment.kategori,
                items: [
                    { no: 1, description: payment.layanan, harga: payment.harga, qty: 1, total: payment.harga }
                ],
                subtotal: payment.harga,
                tax: "Rp " + (parseInt(payment.harga.replace(/\D/g, '')) * 0.11).toLocaleString('id-ID'),
                total: "Rp " + (parseInt(payment.harga.replace(/\D/g, '')) * 1.11).toLocaleString('id-ID')
            };
        }

        function printInvoice() {
            window.print();
        }

        function downloadInvoice() {
            // Placeholder for download functionality
            alert('Fitur download akan segera tersedia');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const invoiceDetailModal = document.getElementById('invoiceDetailModal');
            
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == invoiceDetailModal) {
                closeInvoiceDetailModal();
            }
        }
        
        // Handle escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeInvoiceDetailModal();
            }
        });

        // Payment table functions
        function renderPaymentTable() {
            const tableBody = document.getElementById('payment-table-body');
            tableBody.innerHTML = '';
            
            const startIndex = (paymentCurrentPage - 1) * paymentItemsPerPage;
            const endIndex = Math.min(startIndex + paymentItemsPerPage, paymentFilteredData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const item = paymentFilteredData[i];
                const row = document.createElement('tr');
                row.className = 'border-b border-border-light';
                
                let statusBadge = '';
                switch(item.status) {
                    case 'paid':
                        statusBadge = '<span class="status-badge status-paid">Lunas</span>';
                        break;
                    case 'partial':
                        statusBadge = '<span class="status-badge status-partial">Sebagian</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="status-badge status-pending">Pending</span>';
                        break;
                    case 'overdue':
                        statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                        break;
                }
                
                let categoryBadge = '';
                switch(item.kategori) {
                    case 'design':
                        categoryBadge = '<span class="category-badge category-design">Desain</span>';
                        break;
                    case 'programming':
                        categoryBadge = '<span class="category-badge category-programming">Programming</span>';
                        break;
                    case 'marketing':
                        categoryBadge = '<span class="category-badge category-marketing">Digital Marketing</span>';
                        break;
                }
                
                row.innerHTML = `
                    <td class="p-2 sm:p-3">${item.no}</td>
                    <td class="p-2 sm:p-3">${item.layanan}</td>
                    <td class="p-2 sm:p-3">${categoryBadge}</td>
                    <td class="p-2 sm:p-3">${item.harga}</td>
                    <td class="p-2 sm:p-3">${item.klien}</td>
                    <td class="p-2 sm:p-3 text-center">${item.awal}</td>
                    <td class="p-2 sm:p-3">${item.lunas}</td>
                    <td class="p-2 sm:p-3">${statusBadge}</td>
                    <td class="p-2 sm:p-3">
                        <div class="action-buttons">
                            <button onclick="openInvoiceDetailModal(${item.no})" class="btn-action btn-invoice" title="Lihat Invoice">
                                <i class='bx bx-file'></i>
                                <span class="hidden sm:inline">Invoice</span>
                            </button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            }
            
            // Update info
            document.getElementById('payment-start').textContent = startIndex + 1;
            document.getElementById('payment-end').textContent = endIndex;
            document.getElementById('payment-total').textContent = paymentFilteredData.length;
            document.getElementById('payment-page-start').textContent = startIndex + 1;
            document.getElementById('payment-page-end').textContent = endIndex;
            document.getElementById('payment-page-total').textContent = paymentFilteredData.length;
            
            // Render pagination
            renderPaymentPagination();
        }

        function renderPaymentPagination() {
            const pagination = document.getElementById('payment-pagination');
            pagination.innerHTML = '';
            
            const totalPages = Math.ceil(paymentFilteredData.length / paymentItemsPerPage);
            
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'p-2 rounded-lg border border-border-light hover:bg-gray-50';
            prevBtn.innerHTML = '<i class="bx bx-chevron-left text-sm"></i>';
            prevBtn.disabled = paymentCurrentPage === 1;
            prevBtn.onclick = () => {
                if (paymentCurrentPage > 1) {
                    paymentCurrentPage--;
                    renderPaymentTable();
                }
            };
            pagination.appendChild(prevBtn);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `p-2 rounded-lg border border-border-light ${i === paymentCurrentPage ? 'bg-primary text-white' : 'hover:bg-gray-50'}`;
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    paymentCurrentPage = i;
                    renderPaymentTable();
                };
                pagination.appendChild(pageBtn);
            }
            
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'p-2 rounded-lg border border-border-light hover:bg-gray-50';
            nextBtn.innerHTML = '<i class="bx bx-chevron-right text-sm"></i>';
            nextBtn.disabled = paymentCurrentPage === totalPages;
            nextBtn.onclick = () => {
                if (paymentCurrentPage < totalPages) {
                    paymentCurrentPage++;
                    renderPaymentTable();
                }
            };
            pagination.appendChild(nextBtn);
        }

        function filterPayments() {
            const searchTerm = document.getElementById('payment-search').value.toLowerCase();
            
            if (searchTerm === '') {
                paymentFilteredData = [...paymentData];
            } else {
                paymentFilteredData = paymentData.filter(item => 
                    item.layanan.toLowerCase().includes(searchTerm) ||
                    item.klien.toLowerCase().includes(searchTerm) ||
                    item.status.toLowerCase().includes(searchTerm) ||
                    item.kategori.toLowerCase().includes(searchTerm)
                );
            }
            
            paymentCurrentPage = 1;
            renderPaymentTable();
        }

        // Initialize tables on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSidebar(); // Load sidebar terlebih dahulu
            renderPaymentTable();
        });
    </script>
</body>
</html>
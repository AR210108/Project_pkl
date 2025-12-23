<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // Biru yang lebih terang dan standar
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
                        DEFAULT: "0.75rem", // 12px
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
        
        .material-icons-outlined {
            font-size: 24px;
            vertical-align: middle;
        }
        
        /* Card hover effects */
        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Table styles */
        .order-table {
            transition: all 0.2s ease;
        }
        
        .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-paid {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }
        
        .status-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }
        
        .status-overdue {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }
        
        /* Sidebar link styles */
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            font-weight: 600;
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
        
        /* Pagination styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            gap: 0.25rem;
        }
        
        .pagination-btn {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background-color: white;
            color: #1e293b;
        }
        
        .pagination-btn:hover {
            background-color: #f1f5f9;
        }
        
        .pagination-btn.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Dark mode adjustments */
        .dark .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .dark .status-paid {
            background-color: rgba(16, 185, 129, 0.25);
            color: #6ee7b7;
        }
        
        .dark .status-pending {
            background-color: rgba(245, 158, 11, 0.25);
            color: #fcd34d;
        }
        
        .dark .status-overdue {
            background-color: rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }
        
        /* Mobile card adjustments */
        @media (max-width: 639px) {
            .stat-card {
                padding: 0.75rem !important;
            }
            
            .stat-card .icon-container {
                width: 2rem !important;
                height: 2rem !important;
            }
            
            .stat-card .material-icons-outlined {
                font-size: 1.25rem !important;
            }
            
            .stat-card .value-text {
                font-size: 0.875rem !important;
                line-height: 1.2 !important;
            }
            
            .stat-card .label-text {
                font-size: 0.625rem !important;
                line-height: 1 !important;
            }
            
            .stat-card .mr-3 {
                margin-right: 0.5rem !important;
            }
            
            /* Hide table on mobile */
            .order-table-container {
                display: none;
            }
            
            /* Show mobile cards */
            .mobile-order-cards {
                display: block;
            }
            
            .pagination-container {
                flex-wrap: wrap;
            }
        }
        
        /* Show table on desktop */
        @media (min-width: 640px) {
            .order-table-container {
                display: block;
            }
            
            .mobile-order-cards {
                display: none;
            }
            
            .pagination-container {
                justify-content: flex-end;
            }
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('finance.templet.sider')
        
        <main class="flex-1 flex flex-col bg-background-light">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Beranda</h2>
                
                <!-- Stat Cards Grid -->
                <div class="grid grid-cols-2 gap-3 mb-6 sm:mb-8">
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-blue-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-primary">trending_up</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Pemasukan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">Rp 20.000.000</p>
                        </div>
                    </div>
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-red-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-red-500">trending_down</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Pengeluaran</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">Rp 10.000.000</p>
                        </div>
                    </div>
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-green-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-green-500">business_center</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Jumlah Layanan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">100</p>
                        </div>
                    </div>
                    <div class="stat-card bg-card-light rounded-DEFAULT p-2 sm:p-5 flex items-center border border-border-light">
                        <div class="icon-container w-8 h-8 sm:w-12 sm:h-12 bg-purple-100 rounded-lg mr-3 sm:mr-4 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-500">account_balance_wallet</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Total Keuangan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">Rp 10.000.000</p>
                        </div>
                    </div>
                </div>
                
                <!-- Order Table Section -->
                <div class="bg-card-light rounded-DEFAULT p-3 sm:p-6 border border-border-light shadow-card">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-lg font-semibold">Order List</h3>
                        <button class="btn-primary text-sm py-2 px-4 rounded-lg w-full sm:w-auto">
                            Tambah Order
                        </button>
                    </div>
                    
                    <!-- Desktop Table View -->
                    <div class="order-table-container overflow-x-auto">
                        <table class="w-full text-left text-sm order-table">
                            <thead>
                                <tr class="border-b border-border-light">
                                    <th class="p-3 font-semibold">NO</th>
                                    <th class="p-3 font-semibold">LAYANAN</th>
                                    <th class="p-3 font-semibold">HARGA</th>
                                    <th class="p-3 font-semibold">KLIEN</th>
                                    <th class="p-3 font-semibold text-center">PEMBAYARAN AWAL</th>
                                    <th class="p-3 font-semibold">PELUNASAN</th>
                                    <th class="p-3 font-semibold">STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="order-table-body">
                                <!-- Data akan diisi dengan JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card View -->
                    <div class="mobile-order-cards space-y-4" id="mobile-order-cards">
                        <!-- Data akan diisi dengan JavaScript -->
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-container" id="pagination-container">
                        <!-- Pagination buttons akan diisi dengan JavaScript -->
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <script>
        // Data order
        const orderData = [
            { no: 1, layanan: "Pembuatan Website", harga: "Rp 5.000.000", klien: "PT. Teknologi Maju", awal: "Rp 2.500.000", lunas: "Rp 2.500.000", status: "paid" },
            { no: 2, layanan: "SEO Optimization", harga: "Rp 3.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.500.000", lunas: "Rp 1.500.000", status: "paid" },
            { no: 3, layanan: "Manajemen Sosial Media", harga: "Rp 4.000.000", klien: "UD. Kreatif Indonesia", awal: "Rp 2.000.000", lunas: "Rp 0", status: "pending" },
            { no: 4, layanan: "Pengembangan Aplikasi Mobile", harga: "Rp 8.000.000", klien: "PT. Inovasi Nusantara", awal: "Rp 4.000.000", lunas: "Rp 0", status: "overdue" },
            { no: 5, layanan: "Desain UI/UX", harga: "Rp 7.500.000", klien: "CV. Kreatif", awal: "Rp 2.500.000", lunas: "Rp 0", status: "pending" },
            { no: 6, layanan: "Pembuatan Website", harga: "Rp 6.000.000", klien: "PT. Digital Nusantara", awal: "Rp 3.000.000", lunas: "Rp 3.000.000", status: "paid" },
            { no: 7, layanan: "SEO Optimization", harga: "Rp 2.500.000", klien: "CV. Kreatif Digital", awal: "Rp 1.250.000", lunas: "Rp 1.250.000", status: "paid" },
            { no: 8, layanan: "Manajemen Sosial Media", harga: "Rp 3.500.000", klien: "UD. Inovasi Teknologi", awal: "Rp 1.750.000", lunas: "Rp 0", status: "pending" },
            { no: 9, layanan: "Pengembangan Aplikasi Mobile", harga: "Rp 9.000.000", klien: "PT. Solusi Digital", awal: "Rp 4.500.000", lunas: "Rp 0", status: "overdue" },
            { no: 10, layanan: "Desain UI/UX", harga: "Rp 5.500.000", klien: "CV. Karya Kreatif", awal: "Rp 2.750.000", lunas: "Rp 0", status: "pending" },
            { no: 11, layanan: "Pembuatan Website", harga: "Rp 7.000.000", klien: "PT. Teknologi Maju", awal: "Rp 3.500.000", lunas: "Rp 3.500.000", status: "paid" },
            { no: 12, layanan: "SEO Optimization", harga: "Rp 2.000.000", klien: "CV. Digital Solusi", awal: "Rp 1.000.000", lunas: "Rp 1.000.000", status: "paid" }
        ];

        // Pagination variables
        let currentPage = 1;
        const itemsPerPage = 5;
        let filteredData = [...orderData];

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
                                    <a class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" href="/pembayaran">
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

        // Function to render table
        function renderTable() {
            const tableBody = document.getElementById('order-table-body');
            tableBody.innerHTML = '';
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const item = filteredData[i];
                const row = document.createElement('tr');
                row.className = 'border-b border-border-light';
                
                let statusBadge = '';
                switch(item.status) {
                    case 'paid':
                        statusBadge = '<span class="status-badge status-paid">Lunas</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="status-badge status-pending">Pending</span>';
                        break;
                    case 'overdue':
                        statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                        break;
                }
                
                row.innerHTML = `
                    <td class="p-3">${item.no}</td>
                    <td class="p-3">${item.layanan}</td>
                    <td class="p-3">${item.harga}</td>
                    <td class="p-3">${item.klien}</td>
                    <td class="p-3 text-center">${item.awal}</td>
                    <td class="p-3">${item.lunas}</td>
                    <td class="p-3">${statusBadge}</td>
                `;
                
                tableBody.appendChild(row);
            }
            
            renderPagination();
            renderMobileCards();
        }

        // Function to render mobile cards
        function renderMobileCards() {
            const mobileCards = document.getElementById('mobile-order-cards');
            mobileCards.innerHTML = '';
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const item = filteredData[i];
                
                let statusBadge = '';
                switch(item.status) {
                    case 'paid':
                        statusBadge = '<span class="status-badge status-paid">Lunas</span>';
                        break;
                    case 'pending':
                        statusBadge = '<span class="status-badge status-pending">Pending</span>';
                        break;
                    case 'overdue':
                        statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                        break;
                }
                
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-base">${item.layanan}</h4>
                            <p class="text-sm text-text-muted-light">${item.klien}</p>
                        </div>
                        ${statusBadge}
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-text-muted-light">Total Harga</p>
                            <p class="font-medium">${item.harga}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Pembayaran Awal</p>
                            <p class="font-medium">${item.awal}</p>
                        </div>
                        <div>
                            <p class="text-text-muted-light">Pelunasan</p>
                            <p class="font-medium">${item.lunas}</p>
                        </div>
                    </div>
                `;
                
                mobileCards.appendChild(card);
            }
        }

        // Function to render pagination
        function renderPagination() {
            const paginationContainer = document.getElementById('pagination-container');
            paginationContainer.innerHTML = '';
            
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '<span class="material-icons-outlined">chevron_left</span>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            };
            paginationContainer.appendChild(prevBtn);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
                paginationContainer.appendChild(pageBtn);
            }
            
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = '<span class="material-icons-outlined">chevron_right</span>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            };
            paginationContainer.appendChild(nextBtn);
        }

        // Initialize tables on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSidebar(); // Load sidebar terlebih dahulu
            renderTable();
        });
    </script>
</body>

</html>
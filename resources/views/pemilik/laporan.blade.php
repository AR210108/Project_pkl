<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Financial &amp; Order List</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "hsl(210, 100%, 50%)",
                        "background-light": "#ffffff",
                        "background-dark": "#121212",
                        "card-light": "#f8fafc",
                        "card-dark": "#1e293b",
                        "text-light": "#1e293b",
                        "text-dark": "#f1f5f9",
                        "border-light": "#e2e8f0",
                        "border-dark": "#334155",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: "Poppins", sans-serif
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem
        }
        
        /* Custom styles for pagination */
        .pagination-btn {
            transition: all 0.2s ease;
        }
        .pagination-btn:hover:not(.active):not(:disabled) {
            background-color: #f1f5f9;
            transform: translateY(-1px);
        }
        .pagination-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        @include('pemilik/template/header')
        <main class="mt-6 sm:mt-8">
            <!-- Financial Cards Section -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mb-6 sm:mb-8">
                <div class="bg-white dark:bg-card-dark p-4 sm:p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Total Pemasukan</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-green-600 dark:text-green-500 mt-1">1.000.000</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                            <i class='bx bx-trending-up text-2xl text-green-600 dark:text-green-500'></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-card-dark p-4 sm:p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Total Pengeluaran</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-red-600 dark:text-red-500 mt-1">500.000</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                            <i class='bx bx-trending-down text-2xl text-red-600 dark:text-red-500'></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-card-dark p-4 sm:p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Total Keuangan</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-blue-600 dark:text-blue-500 mt-1">500.000</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                            <i class='bx bx-wallet text-2xl text-blue-600 dark:text-blue-500'></i>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Filters Section -->
            <section class="bg-white dark:bg-card-dark p-4 sm:p-6 rounded-xl shadow-md mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <button
                        class="bg-primary text-white px-4 sm:px-6 py-2 rounded-lg font-medium text-sm sm:text-base hover:bg-blue-600 transition-colors w-full sm:w-auto flex items-center justify-center">
                        <i class='bx bx-download mr-2'></i>
                        Export PDF
                    </button>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <select
                                class="w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 pl-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark focus:ring-primary text-sm sm:text-base">
                                <option>Semua Kategori</option>
                                <option>Web Design</option>
                                <option>SEO</option>
                                <option>Marketing</option>
                                <option>Mobile App</option>
                            </select>
                        </div>
                        <div class="relative w-full sm:w-auto">
                            <select
                                class="w-full bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 pl-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark focus:ring-primary text-sm sm:text-base">
                                <option>Semua Bulan</option>
                                <option>Januari</option>
                                <option>Februari</option>
                                <option>Maret</option>
                                <option>April</option>
                                <option>Mei</option>
                                <option>Juni</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Order List Section -->
            <section class="bg-white dark:bg-card-dark p-4 sm:p-6 md:p-8 rounded-xl shadow-md">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Daftar Pesanan</h2>
                    <div class="relative w-full sm:w-auto">
                        <input
                            class="w-full py-2 pl-10 pr-4 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-primary focus:border-primary"
                            placeholder="Cari pesanan..." type="text" id="orderSearch" />
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <div class="min-w-[900px] sm:min-w-[1000px]">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div
                                class="grid grid-cols-7 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider p-4 gap-4">
                                <div class="col-span-1 px-3">No</div>
                                <div class="col-span-1 px-3">Layanan</div>
                                <div class="col-span-1 px-3">Harga</div>
                                <div class="col-span-1 px-3">Klien</div>
                                <div class="col-span-1 px-3">Pembayaran Awal</div>
                                <div class="col-span-1 px-3">Pelunasan</div>
                                <div class="col-span-1 px-3">Status</div>
                            </div>
                        </div>
                        <div id="orderTableBody" class="mt-4 space-y-4">
                            <!-- Table content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-between items-center mt-6">
                    <p id="orderInfo" class="text-sm text-gray-600 dark:text-gray-400">Showing 1-5 of 15 entries</p>
                    <div id="orderPagination" class="flex space-x-1">
                        <!-- Pagination buttons will be populated by JavaScript -->
                    </div>
                </div>
            </section>
        </main>
        <footer class="mt-8 sm:mt-12 bg-gray-100 dark:bg-gray-800 py-4 sm:py-6 rounded-xl">
            <p class="text-center text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <script>
        // Sample data for demonstration
        const orderData = [
            { id: 1, service: "Web Design", price: 500000, client: "Client A", dp: 250000, remaining: 250000, status: "Lunas" },
            { id: 2, service: "SEO", price: 300000, client: "Client B", dp: 150000, remaining: 0, status: "DP" },
            { id: 3, service: "Marketing", price: 200000, client: "Client C", dp: 0, remaining: 0, status: "Belum Bayar" },
            { id: 4, service: "Mobile App", price: 750000, client: "Client D", dp: 375000, remaining: 375000, status: "Lunas" },
            { id: 5, service: "Web Development", price: 600000, client: "Client E", dp: 300000, remaining: 0, status: "DP" },
            { id: 6, service: "Content Creation", price: 250000, client: "Client F", dp: 0, remaining: 0, status: "Belum Bayar" },
            { id: 7, service: "Social Media Management", price: 350000, client: "Client G", dp: 175000, remaining: 175000, status: "Lunas" },
            { id: 8, service: "UI/UX Design", price: 400000, client: "Client H", dp: 200000, remaining: 0, status: "DP" },
            { id: 9, service: "E-commerce Development", price: 800000, client: "Client I", dp: 400000, remaining: 400000, status: "Lunas" },
            { id: 10, service: "Digital Marketing", price: 300000, client: "Client J", dp: 0, remaining: 0, status: "Belum Bayar" },
            { id: 11, service: "Branding", price: 450000, client: "Client K", dp: 225000, remaining: 225000, status: "Lunas" },
            { id: 12, service: "Video Production", price: 550000, client: "Client L", dp: 275000, remaining: 0, status: "DP" },
            { id: 13, service: "Photography", price: 200000, client: "Client M", dp: 0, remaining: 0, status: "Belum Bayar" },
            { id: 14, service: "Copywriting", price: 150000, client: "Client N", dp: 75000, remaining: 75000, status: "Lunas" },
            { id: 15, service: "Graphic Design", price: 350000, client: "Client O", dp: 175000, remaining: 0, status: "DP" }
        ];
        
        // Pagination configuration
        const itemsPerPage = 5;
        let currentPage = 1;
        let filteredData = [...orderData];
        
        // Function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
        
        // Function to get status styling
        function getStatusClass(status) {
            switch(status) {
                case "Lunas": return "text-green-500";
                case "DP": return "text-yellow-500";
                case "Belum Bayar": return "text-red-500";
                default: return "text-gray-500";
            }
        }
        
        // Function to render table rows
        function renderTableRows(data, page) {
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = data.slice(startIndex, endIndex);
            
            const tableBody = document.getElementById('orderTableBody');
            tableBody.innerHTML = '';
            
            pageData.forEach((item, index) => {
                const isLastRow = index === pageData.length - 1;
                const row = document.createElement('div');
                row.className = `grid grid-cols-7 items-center py-4 ${isLastRow ? '' : 'border-b border-gray-200 dark:border-gray-700'} text-sm gap-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors`;
                
                row.innerHTML = `
                    <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">${startIndex + index + 1}</div>
                    <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="${item.service}">${item.service}</div>
                    <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">Rp. ${formatCurrency(item.price)}</div>
                    <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="${item.client}">${item.client}</div>
                    <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">${item.dp > 0 ? `Rp. ${formatCurrency(item.dp)}` : '-'}</div>
                    <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">${item.remaining > 0 ? `Rp. ${formatCurrency(item.remaining)}` : '-'}</div>
                    <div class="col-span-1 ${getStatusClass(item.status)} px-3 font-medium">${item.status}</div>
                `;
                
                tableBody.appendChild(row);
            });
        }
        
        // Function to render pagination buttons
        function renderPagination(totalItems, page) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById('orderPagination');
            const info = document.getElementById('orderInfo');
            
            // Update info text
            const startItem = (page - 1) * itemsPerPage + 1;
            const endItem = Math.min(page * itemsPerPage, totalItems);
            info.textContent = `Showing ${startItem}-${endItem} of ${totalItems} entries`;
            
            // Clear pagination
            pagination.innerHTML = '';
            
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn px-3 py-1 rounded-md border border-gray-300 text-sm';
            prevBtn.innerHTML = '<i class=\'bx bx-chevron-left\'></i>';
            prevBtn.disabled = page === 1;
            prevBtn.addEventListener('click', () => {
                if (page > 1) {
                    changePage(page - 1);
                }
            });
            pagination.appendChild(prevBtn);
            
            // Dynamic pagination logic with only 2 pages in the middle
            if (totalPages <= 5) {
                // If total pages is 5 or less, show all pages
                for (let i = 1; i <= totalPages; i++) {
                    addPageButton(i, page);
                }
            } else {
                // Always show page 1
                addPageButton(1, page);
                
                if (page === 1) {
                    // Show pages 2 and 3
                    addPageButton(2, page);
                    addPageButton(3, page);
                    addEllipsis();
                    addPageButton(totalPages, page);
                } else if (page === 2) {
                    // Show pages 2 and 3
                    addPageButton(2, page);
                    addPageButton(3, page);
                    addEllipsis();
                    addPageButton(totalPages, page);
                } else if (page === totalPages - 1) {
                    // Show pages totalPages-2 and totalPages-1
                    addEllipsis();
                    addPageButton(totalPages - 2, page);
                    addPageButton(totalPages - 1, page);
                    addPageButton(totalPages, page);
                } else if (page === totalPages) {
                    // Show pages totalPages-2 and totalPages-1
                    addEllipsis();
                    addPageButton(totalPages - 2, page);
                    addPageButton(totalPages - 1, page);
                    addPageButton(totalPages, page);
                } else {
                    // Show pages page and page+1
                    addEllipsis();
                    addPageButton(page, page);
                    addPageButton(page + 1, page);
                    addEllipsis();
                    addPageButton(totalPages, page);
                }
            }
            
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn px-3 py-1 rounded-md border border-gray-300 text-sm';
            nextBtn.innerHTML = '<i class=\'bx bx-chevron-right\'></i>';
            nextBtn.disabled = page === totalPages;
            nextBtn.addEventListener('click', () => {
                if (page < totalPages) {
                    changePage(page + 1);
                }
            });
            pagination.appendChild(nextBtn);
        }
        
        // Helper function to add page button
        function addPageButton(pageNum, currentPage) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `pagination-btn px-3 py-1 rounded-md border border-gray-300 text-sm ${pageNum === currentPage ? 'active' : ''}`;
            pageBtn.textContent = pageNum;
            pageBtn.addEventListener('click', () => {
                changePage(pageNum);
            });
            document.getElementById('orderPagination').appendChild(pageBtn);
        }
        
        // Helper function to add ellipsis
        function addEllipsis() {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'px-2 py-1 text-gray-500';
            ellipsis.textContent = '...';
            document.getElementById('orderPagination').appendChild(ellipsis);
        }
        
        // Function to change page
        function changePage(page) {
            currentPage = page;
            renderTableRows(filteredData, currentPage);
            renderPagination(filteredData.length, currentPage);
        }
        
        // Search functionality
        document.getElementById('orderSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            filteredData = orderData.filter(item => 
                item.service.toLowerCase().includes(searchTerm) || 
                item.client.toLowerCase().includes(searchTerm) ||
                item.status.toLowerCase().includes(searchTerm)
            );
            
            currentPage = 1;
            renderTableRows(filteredData, currentPage);
            renderPagination(filteredData.length, currentPage);
        });
        
        // Initialize table on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderTableRows(filteredData, currentPage);
            renderPagination(filteredData.length, currentPage);
        });
    </script>
</body>

</html>
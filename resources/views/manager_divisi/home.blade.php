<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda (Home) - Dashboard</title>
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
        
        /* Deadline card hover effects */
        .deadline-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .deadline-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Sidebar link styles - Dihapus karena sudah ada di template header */
        
        /* Button styles */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        /* Custom styles untuk transisi - Dihapus karena sudah ada di template header */
        
        /* Animasi hamburger - Dihapus karena sudah ada di template header */
        
        /* Style untuk efek hover yang lebih menonjol - Dihapus karena sudah ada di template header */
        
        /* Gaya untuk indikator aktif/hover - Dihapus karena sudah ada di template header */
        
        /* Memastikan sidebar tetap di posisinya saat scroll - Dihapus karena sudah ada di template header */
        
        /* Menyesuaikan konten utama agar tidak tertutup sidebar - Dihapus karena sudah ada di template header */
        
        /* Scrollbar kustom untuk sidebar - Dihapus karena sudah ada di template header */
        
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
            
            /* Adjust deadline cards for mobile */
            .deadline-card {
                padding: 1rem !important;
            }
            
            .deadline-card .image-placeholder {
                height: 6rem !important;
            }
        }
        
        /* Pagination styles */
        .page-btn {
            transition: all 0.2s ease;
        }
        
        .page-btn:hover:not(:disabled) {
            transform: scale(1.1);
        }
        
        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Desktop pagination styles */
        .desktop-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }
        
        .desktop-page-btn {
            min-width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .desktop-page-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .desktop-page-btn:not(.active) {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        .desktop-page-btn:not(.active):hover {
            background-color: #e2e8f0;
        }
        
        .desktop-nav-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .desktop-nav-btn:hover:not(:disabled) {
            background-color: #e2e8f0;
        }
        
        .desktop-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Tombol Hamburger untuk Mobile - DIHAPUS -->
        <!-- Overlay untuk Mobile - DIHAPUS -->

        <!-- Sidebar - Dipanggil dari template header -->
        @include('manager_divisi/templet/sider')
        
        <main class="flex-1 flex flex-col bg-background-light main-content">
            <div class="flex-1 p-3 sm:p-8"> 
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Beranda</h2>
                
                <!-- Stat Cards Grid -->
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-6 sm:mb-8">
                    <!-- Grad Tugas Card -->
                    <div class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg mb-2 flex items-center justify-center">
                            <span class="material-icons-outlined text-primary text-sm sm:text-base">task_alt</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Belum Dikerjakan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">3</p>
                        </div>
                    </div>
                    
                    <!-- Tugas Diteruskan Card -->
                    <div class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg mb-2 flex items-center justify-center">
                            <span class="material-icons-outlined text-yellow-500 text-sm sm:text-base">assignment_turned_in</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Tugas Dikerjakan</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">2</p>
                        </div>
                    </div>
                    
                    <!-- Tugas Selesai Card -->
                    <div class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg mb-2 flex items-center justify-center">
                            <span class="material-icons-outlined text-green-500 text-sm sm:text-base">check_circle</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Tugas Selesai</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">6</p>
                        </div>
                    </div>
                    
                    <!-- Total Tugas Card -->
                    <div class="stat-card bg-card-light rounded-DEFAULT p-3 sm:p-5 flex flex-col items-center justify-center text-center border border-border-light">
                        <div class="icon-container w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg mb-2 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-500 text-sm sm:text-base">summarize</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="label-text text-xs sm:text-sm text-text-muted-light truncate">Total Tugas</p>
                            <p class="value-text text-base sm:text-xl font-bold truncate">10</p>
                        </div>
                    </div>
                </div>
                
                <!-- Deadline Terdekat Section - Card Layout -->
                <div class="bg-card-light rounded-xl p-3 sm:p-8 border border-border-light shadow-card">
                    <h2 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6">Deadline Terdekat</h2>
                    <div id="deadlineGrid" class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <!-- Deadline cards will be populated by JavaScript -->
                    </div>
                    
                    <!-- Pagination for Deadline -->
                    <div id="deadlinePaginationContainer" class="desktop-pagination">
                        <button id="deadlinePrevPage" class="desktop-nav-btn">
                            <span class="material-icons-outlined text-sm">chevron_left</span>
                        </button>
                        <div id="deadlinePageNumbers" class="flex gap-1">
                            <!-- Page numbers will be generated by JavaScript -->
                        </div>
                        <button id="deadlineNextPage" class="desktop-nav-btn">
                            <span class="material-icons-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <script>
        // Sample data for deadline cards
        const deadlineData = [
            { title: "Malaria Pendidikan", daysLeft: 2, progress: 80, status: "TERLAMBAT", statusColor: "red" },
            { title: "Vaksin Pendidikan", daysLeft: 3, progress: 60, status: "DIKERJAKAN", statusColor: "yellow" },
            { title: "Laporan Tahunan", daysLeft: 5, progress: 40, status: "DIKERJAKAN", statusColor: "blue" },
            { title: "Malaria Pendidikan", daysLeft: 7, progress: 25, status: "DIKERJAKAN", statusColor: "green" },
            { title: "Evaluasi Program", daysLeft: 10, progress: 15, status: "DIKERJAKAN", statusColor: "blue" },
            { title: "Penelitian Baru", daysLeft: 12, progress: 5, status: "RENCANA", statusColor: "gray" },
            { title: "Audit Internal", daysLeft: 14, progress: 0, status: "RENCANA", statusColor: "gray" },
            { title: "Pengembangan Kurikulum", daysLeft: 20, progress: 0, status: "RENCANA", statusColor: "gray" }
        ];

        // Pagination variables for deadline
        const itemsPerPage = 4;
        let currentPage = 1;
        const totalPages = Math.ceil(deadlineData.length / itemsPerPage);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize pagination for deadline
            initializeDeadlinePagination();
        });

        // Function to initialize pagination for deadline
        function initializeDeadlinePagination() {
            renderDeadlineCards(currentPage);
            renderPaginationButtons();
        }

        // Function to render deadline cards for a specific page
        function renderDeadlineCards(page) {
            const grid = document.getElementById('deadlineGrid');
            grid.innerHTML = '';
            
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, deadlineData.length);
            
            for (let i = startIndex; i < endIndex; i++) {
                const deadline = deadlineData[i];
                const card = document.createElement('div');
                card.className = 'deadline-card border border-border-light p-3 sm:p-4 rounded-lg flex flex-col';
                
                // Determine button color based on status
                let buttonClass = '';
                if (deadline.statusColor === 'red') {
                    buttonClass = 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/70';
                } else if (deadline.statusColor === 'yellow') {
                    buttonClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 hover:bg-yellow-200 dark:hover:bg-yellow-900/70';
                } else if (deadline.statusColor === 'blue') {
                    buttonClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/70';
                } else if (deadline.statusColor === 'green') {
                    buttonClass = 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/70';
                } else {
                    buttonClass = 'bg-gray-100 text-gray-700 dark:bg-gray-900/50 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-900/70';
                }
                
                card.innerHTML = `
                    <div class="image-placeholder bg-gray-200 dark:bg-gray-600 h-24 sm:h-32 rounded-md mb-3 sm:mb-4 flex items-center justify-center">
                        <span class="material-icons-outlined text-gray-400 dark:text-gray-500 text-sm sm:text-base">image</span>
                    </div>
                    <h3 class="font-semibold text-sm mb-1">${deadline.title}</h3>
                    <p class="text-xs text-text-muted-light mb-3">SISA WAKTU ${deadline.daysLeft} HARI</p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2">
                        <div class="bg-${deadline.statusColor}-500 h-1.5 rounded-full" style="width: ${deadline.progress}%"></div>
                    </div>
                    <button class="mt-auto w-full text-center py-2 text-xs font-semibold ${buttonClass} rounded-md transition-colors">${deadline.status}</button>
                `;
                grid.appendChild(card);
            }
        }

        // Function to render pagination buttons
        function renderPaginationButtons() {
            const pageNumbersContainer = document.getElementById('deadlinePageNumbers');
            const prevButton = document.getElementById('deadlinePrevPage');
            const nextButton = document.getElementById('deadlineNextPage');
            
            // Clear existing page numbers
            pageNumbersContainer.innerHTML = '';
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `desktop-page-btn ${
                    i === currentPage ? 'active' : ''
                }`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Update navigation buttons
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
            
            // Add event listeners for navigation buttons
            prevButton.onclick = () => {
                if (currentPage > 1) goToPage(currentPage - 1);
            };
            
            nextButton.onclick = () => {
                if (currentPage < totalPages) goToPage(currentPage + 1);
            };
        }

        // Function to go to a specific page
        function goToPage(page) {
            currentPage = page;
            renderDeadlineCards(page);
            renderPaginationButtons();
        }

        // Fungsi untuk inisialisasi sidebar - DIHAPUS
        // Fungsi untuk menandai item navigasi yang aktif - DIHAPUS
    </script>
</body>
</html>
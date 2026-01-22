<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manajemen Tim & Divisi - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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
        
        /* Status Badge Styles */
        .status-badge { 
            display: inline-block; 
            padding: 0.25rem 0.75rem; 
            border-radius: 9999px; 
            font-size: 0.75rem; 
            font-weight: 600; 
        }
        
        .status-aktif { 
            background-color: rgba(16, 185, 129, 0.15); 
            color: #065f46; 
        }
        
        .status-tidak-aktif { 
            background-color: rgba(239, 68, 68, 0.15); 
            color: #991b1b; 
        }

        /* Tab Navigation Styles */
        .tab-nav { 
            display: flex; 
            border-bottom: 2px solid #e2e8f0; 
            margin-bottom: 1.5rem; 
        }

        .tab-button { 
            padding: 0.75rem 1.5rem; 
            font-weight: 500; 
            color: #64748b; 
            background: none; 
            border: none; 
            border-bottom: 2px solid transparent; 
            cursor: pointer; 
            transition: all 0.2s ease; 
        }

        .tab-button:hover { 
            color: #3b82f6; 
        }

        .tab-button.active { 
            color: #3b82f6; 
            border-bottom-color: #3b82f6; 
        }
        
        /* Table mobile adjustments */
        @media (max-width: 639px) { 
            .desktop-table { 
                display: none; 
            } 
            
            .mobile-cards { 
                display: block; 
            } 
            
            .desktop-pagination { 
                display: none !important; 
            } 
        }
        
        @media (min-width: 640px) { 
            .desktop-table { 
                display: block; 
            } 
            
            .mobile-cards { 
                display: none; 
            } 
            
            .mobile-pagination { 
                display: none !important; 
            } 
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
        
        /* Panel Styles */
        .panel { 
            background: white; 
            border-radius: 0.75rem; 
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); 
            overflow: hidden; 
            border: 1px solid #e2e8f0; 
        }
        
        .panel-header { 
            background: #f8fafc; 
            padding: 1rem 1.5rem; 
            border-bottom: 1px solid #e2e8f0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        .panel-title { 
            font-size: 1.125rem; 
            font-weight: 600; 
            color: #1e293b; 
            margin: 0; 
            display: flex; 
            align-items: center; 
            gap: 0.5rem; 
        }
        
        .panel-body { 
            padding: 1.5rem; 
        }
        
        /* SCROLLABLE TABLE */
        .scrollable-table-container { 
            width: 100%; 
            overflow-x: auto; 
            overflow-y: hidden; 
            border: 1px solid #e2e8f0; 
            border-radius: 0.5rem; 
            background: white; 
        }
        
        .scrollable-table-container { 
            scrollbar-width: auto; 
            -webkit-overflow-scrolling: touch; 
        }
        
        .scrollable-table-container::-webkit-scrollbar { 
            height: 12px; 
            width: 12px; 
        }
        
        .scrollable-table-container::-webkit-scrollbar-track { 
            background: #f1f5f9; 
            border-radius: 6px; 
        }
        
        .scrollable-table-container::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 6px; 
            border: 2px solid #f1f5f9; 
        }
        
        .scrollable-table-container::-webkit-scrollbar-thumb:hover { 
            background: #94a3b8; 
        }
        
        .data-table { 
            width: 100%; 
            min-width: 800px; 
            border-collapse: collapse; 
        }
        
        .data-table th, 
        .data-table td { 
            padding: 12px 16px; 
            text-align: left; 
            border-bottom: 1px solid #e2e8f0; 
            white-space: nowrap; 
        }
        
        .data-table th { 
            background: #f8fafc; 
            font-weight: 600; 
            color: #374151; 
            font-size: 0.875rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
        }
        
        .data-table tbody tr:nth-child(even) { 
            background: #f9fafb; 
        }
        
        .data-table tbody tr:hover { 
            background: #f3f4f6; 
        }
        
        .table-shadow { 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
        }
        
        /* Minimalist Popup Styles */
        .minimal-popup { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); 
            padding: 16px 20px; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            z-index: 1000; 
            transform: translateX(400px); 
            transition: transform 0.3s ease; 
            max-width: 350px; 
            border-left: 4px solid #10b981; 
        }
        
        .minimal-popup.show { 
            transform: translateX(0); 
        }
        
        .minimal-popup.error { 
            border-left-color: #ef4444; 
        }
        
        .minimal-popup.warning { 
            border-left-color: #f59e0b; 
        }
        
        .minimal-popup-icon { 
            flex-shrink: 0; 
            width: 24px; 
            height: 24px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 50%; 
        }
        
        .minimal-popup.success .minimal-popup-icon { 
            background-color: rgba(16, 185, 129, 0.1); 
            color: #10b981; 
        }
        
        .minimal-popup.error .minimal-popup-icon { 
            background-color: rgba(239, 68, 68, 0.1); 
            color: #ef4444; 
        }
        
        .minimal-popup.warning .minimal-popup-icon { 
            background-color: rgba(245, 158, 11, 0.1); 
            color: #f59e0b; 
        }
        
        .minimal-popup-content { 
            flex-grow: 1; 
        }
        
        .minimal-popup-title { 
            font-weight: 600; 
            color: #1e293b; 
            margin-bottom: 2px; 
        }
        
        .minimal-popup-message { 
            font-size: 14px; 
            color: #64748b; 
        }
        
        .minimal-popup-close { 
            flex-shrink: 0; 
            background: none; 
            border: none; 
            color: #94a3b8; 
            cursor: pointer; 
            padding: 4px; 
            border-radius: 4px; 
            transition: all 0.2s ease; 
        }
        
        .minimal-popup-close:hover { 
            background-color: #f1f5f9; 
            color: #64748b; 
        }
        
        /* Filter Dropdown Styles */
        .filter-dropdown { 
            position: absolute; 
            top: 100%; 
            right: 0; 
            margin-top: 8px; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); 
            padding: 16px; 
            min-width: 200px; 
            z-index: 100; 
            display: none; 
        }
        
        .filter-dropdown.show { 
            display: block; 
        }
        
        .filter-option { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            padding: 8px 0; 
            cursor: pointer; 
            transition: all 0.2s ease; 
        }
        
        .filter-option:hover { 
            color: #3b82f6; 
        }
        
        .filter-option input[type="checkbox"] { 
            width: 18px; 
            height: 18px; 
            cursor: pointer; 
        }
        
        .filter-option label { 
            cursor: pointer; 
            user-select: none; 
        }
        
        .filter-actions { 
            display: flex; 
            gap: 8px; 
            margin-top: 12px; 
            padding-top: 12px; 
            border-top: 1px solid #e2e8f0; 
        }
        
        .filter-actions button { 
            flex: 1; 
            padding: 6px 12px; 
            border-radius: 6px; 
            font-size: 14px; 
            font-weight: 500; 
            cursor: pointer; 
            transition: all 0.2s ease; 
            border: none; 
        }
        
        .filter-apply { 
            background-color: #3b82f6; 
            color: white; 
        }
        
        .filter-apply:hover { 
            background-color: #2563eb; 
        }
        
        .filter-reset { 
            background-color: #f1f5f9; 
            color: #64748b; 
        }
        
        .filter-reset:hover { 
            background-color: #e2e8f0; 
        }
        
        .hidden-by-filter { 
            display: none !important; 
        }

        /* Pagination */
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

        /* Modal */
        .modal { 
            transition: opacity 0.25s ease; 
        }
        .modal-backdrop { 
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(4px); 
        }

        /* Main Content Layout */
        .main-content { 
            transition: margin-left 0.3s ease; 
        }

        @media (min-width: 768px) { 
            .main-content { 
                margin-left: 256px; /* Lebar sidebar */ 
            } 
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <!-- =========== SIDEBAR/HEADER DI ATAS =========== -->
    @include('general_manajer.templet.header')

    <!-- =========== MAIN CONTENT DI SAMPING SIDEBAR =========== -->
    <div class="main-content">
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Manajemen Tim & Divisi</h2>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                    <div class="stat-card bg-white p-4 md:p-6 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 md:mr-4">
                                <span class="material-icons-outlined text-blue-600 text-lg md:text-xl">groups</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Total Tim</p>
                                <p class="text-xl md:text-2xl font-bold text-blue-600" id="stat-total-tim">6</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 md:p-6 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3 md:mr-4">
                                <span class="material-icons-outlined text-green-600 text-lg md:text-xl">business</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Total Divisi</p>
                                <p class="text-xl md:text-2xl font-bold text-green-600" id="stat-total-divisi">3</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 md:p-6 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3 md:mr-4">
                                <span class="material-icons-outlined text-purple-600 text-lg md:text-xl">group_work</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Tim Aktif</p>
                                <p class="text-xl md:text-2xl font-bold text-purple-600" id="stat-tim-aktif">5</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 md:p-6 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-3 md:mr-4">
                                <span class="material-icons-outlined text-orange-600 text-lg md:text-xl">people</span>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm text-gray-500">Total Anggota</p>
                                <p class="text-xl md:text-2xl font-bold text-orange-600" id="stat-total-anggota">28</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="tab-nav">
                    <button id="timTab" class="tab-button active" onclick="switchTab('tim')">
                        <span class="material-icons-outlined align-middle mr-2">groups</span>
                        Data Tim
                    </button>
                    <button id="divisiTab" class="tab-button" onclick="switchTab('divisi')">
                        <span class="material-icons-outlined align-middle mr-2">business</span>
                        Data Divisi
                    </button>
                </div>

                <!-- Data Tim Panel -->
                <div id="timPanel" class="panel tab-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">groups</span>
                            Data Tim
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="timCount">6</span> tim</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Search and Filter Section -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <div class="relative w-full md:w-1/3">
                                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                                <input id="searchTimInput"
                                    class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                                    placeholder="Cari nama tim atau divisi..." type="text" />
                            </div>
                            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                                <div class="relative">
                                    <button id="filterTimBtn"
                                        class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                        <span class="material-icons-outlined text-sm">filter_list</span>
                                        Filter
                                    </button>
                                    <div id="filterTimDropdown" class="filter-dropdown">
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterTimAll" value="all" checked>
                                            <label for="filterTimAll">Semua Divisi</label>
                                        </div>
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterTimTI" value="1">
                                            <label for="filterTimTI">Teknologi Informasi</label>
                                        </div>
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterTimSDM" value="2">
                                            <label for="filterTimSDM">Sumber Daya Manusia</label>
                                        </div>
                                        <div class="filter-option">
                                            <input type="checkbox" id="filterTimPemasaran" value="3">
                                            <label for="filterTimPemasaran">Pemasaran</label>
                                        </div>
                                        <div class="filter-actions">
                                            <button id="applyTimFilter" class="filter-apply">Terapkan</button>
                                            <button id="resetTimFilter" class="filter-reset">Reset</button>
                                        </div>
                                    </div>
                                </div>
                                <button id="tambahTimBtn"
                                    class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                                    <span class="material-icons-outlined">add</span>
                                    <span class="hidden sm:inline">Tambah Tim</span>
                                    <span class="sm:hidden">Tambah</span>
                                </button>
                            </div>
                        </div>

                        <!-- Desktop Table -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container table-shadow" id="scrollableTimTable">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 200px;">Nama Tim</th>
                                            <th style="min-width: 200px;">Divisi</th>
                                            <th style="min-width: 120px;">Jumlah Anggota</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timTableBody">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="tim-mobile-cards">
                            <!-- Cards will be populated by JavaScript -->
                        </div>

                        <!-- Pagination -->
                        <div id="timPaginationContainer" class="desktop-pagination">
                            <button id="timPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="timPageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="timNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Divisi Panel (Initially Hidden) -->
                <div id="divisiPanel" class="panel tab-panel hidden">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">business</span>
                            Data Divisi
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-text-muted-light">Total: <span class="font-semibold text-text-light" id="divisiCount">3</span> divisi</span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Search Section -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <div class="relative w-full md:w-1/3">
                                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                                <input id="searchDivisiInput"
                                    class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                                    placeholder="Cari nama divisi..." type="text" />
                            </div>
                            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                                <button id="tambahDivisiBtn"
                                    class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                                    <span class="material-icons-outlined">add</span>
                                    <span class="hidden sm:inline">Tambah Divisi</span>
                                    <span class="sm:hidden">Tambah</span>
                                </button>
                            </div>
                        </div>

                        <!-- Desktop Table -->
                        <div class="desktop-table">
                            <div class="scrollable-table-container table-shadow" id="scrollableDivisiTable">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 200px;">Nama Divisi</th>
                                            <th style="min-width: 150px;">Jumlah Tim</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="divisiTableBody">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="mobile-cards space-y-4" id="divisi-mobile-cards">
                            <!-- Cards will be populated by JavaScript -->
                        </div>

                        <!-- Pagination -->
                        <div id="divisiPaginationContainer" class="desktop-pagination">
                            <button id="divisiPrevPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="divisiPageNumbers" class="flex gap-1">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="divisiNextPage" class="desktop-nav-btn">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

                <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light mt-8">
                    Copyright ©2025 by digicity.id
                </footer>
            </div>
        </main>
    </div>

    <!-- Tambah Tim Modal -->
    <div id="tambahTimModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Tim Baru</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="tambahTimModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahTimForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tim</label>
                            <input type="text" name="nama_tim" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama tim">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota</label>
                            <input type="number" name="jumlah_anggota" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan Jumlah Anggota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                            <select name="divisi_id" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Divisi</option>
                                <option value="1">Teknologi Informasi</option>
                                <option value="2">Sumber Daya Manusia</option>
                                <option value="3">Pemasaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg" data-target="tambahTimModal">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Tim Modal -->
    <div id="editTimModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Tim</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="editTimModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editTimForm" class="space-y-4">
                    <input type="hidden" id="editTimId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tim</label>
                            <input type="text" id="editNamaTim" name="nama_tim" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama tim">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota</label>
                            <input type="number" id="editJumlahAnggota" name="jumlah_anggota" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan Jumlah Anggota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                            <select id="editDivisiSelect" name="divisi_id" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Divisi</option>
                                <option value="1">Teknologi Informasi</option>
                                <option value="2">Sumber Daya Manusia</option>
                                <option value="3">Pemasaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="editStatusTimSelect" name="status" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg" data-target="editTimModal">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambah Divisi Modal -->
    <div id="tambahDivisiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Divisi Baru</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="tambahDivisiModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahDivisiForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Divisi</label>
                            <input type="text" name="nama_divisi" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama divisi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Divisi</label>
                            <input type="text" name="kepala_divisi" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama kepala divisi">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg" data-target="tambahDivisiModal">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Divisi Modal -->
    <div id="editDivisiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Divisi</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="editDivisiModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editDivisiForm" class="space-y-4">
                    <input type="hidden" id="editDivisiId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Divisi</label>
                            <input type="text" id="editNamaDivisi" name="nama_divisi" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan nama divisi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Divisi</label>
                            <select id="editKepalaDivisiSelect" name="kepala_divisi" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Pilih Kepala Divisi</option>
                                <option value="Eko Rahardjo">Eko Rahardjo</option>
                                <option value="Fajar Kusumo">Fajar Kusumo</option>
                                <option value="Gina Permata">Gina Permata</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="editStatusDivisiSelect" name="status" required class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg" data-target="editDivisiModal">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Tim Modal -->
    <div id="deleteTimModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="deleteTimModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="deleteTimForm">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus data tim ini?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteTimId">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg" data-target="deleteTimModal">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Divisi Modal -->
    <div id="deleteDivisiModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500" data-target="deleteDivisiModal">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="deleteDivisiForm">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus data divisi ini?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteDivisiId">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="cancel-modal px-4 py-2 btn-secondary rounded-lg" data-target="deleteDivisiModal">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Minimalist Popup -->
    <div id="minimalPopup" class="minimal-popup">
        <div class="minimal-popup-icon">
            <span class="material-icons-outlined">check</span>
        </div>
        <div class="minimal-popup-content">
            <div class="minimal-popup-title">Berhasil</div>
            <div class="minimal-popup-message">Operasi berhasil dilakukan</div>
        </div>
        <button class="minimal-popup-close">
            <span class="material-icons-outlined text-sm">close</span>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== STATIC DATA ====================
            let timData = [
                { id: 1, nama_tim: 'Tim Pengembangan Web', divisi_id: 1, divisi_nama: 'Teknologi Informasi', ketua_tim: 'Andi Pratama', jumlah_anggota: 5, status: 'aktif' },
                { id: 2, nama_tim: 'Tim Mobile Apps', divisi_id: 1, divisi_nama: 'Teknologi Informasi', ketua_tim: 'Budi Santoso', jumlah_anggota: 4, status: 'aktif' },
                { id: 3, nama_tim: 'Tim Rekrutmen', divisi_id: 2, divisi_nama: 'Sumber Daya Manusia', ketua_tim: 'Citra Dewi', jumlah_anggota: 3, status: 'aktif' },
                { id: 4, nama_tim: 'Tim Pelatihan', divisi_id: 2, divisi_nama: 'Sumber Daya Manusia', ketua_tim: 'Doni Wibowo', jumlah_anggota: 2, status: 'tidak_aktif' },
                { id: 5, nama_tim: 'Tim Digital Marketing', divisi_id: 3, divisi_nama: 'Pemasaran', ketua_tim: 'Eko Rahardjo', jumlah_anggota: 6, status: 'aktif' },
                { id: 6, nama_tim: 'Tim Content Creation', divisi_id: 3, divisi_nama: 'Pemasaran', ketua_tim: 'Fajar Kusumo', jumlah_anggota: 8, status: 'aktif' },
            ];

            let divisiData = [
                { id: 1, nama_divisi: 'Teknologi Informasi', kepala_divisi: 'Eko Rahardjo', jumlah_tim: 2, jumlah_anggota: 9, status: 'aktif' },
                { id: 2, nama_divisi: 'Sumber Daya Manusia', kepala_divisi: 'Fajar Kusumo', jumlah_tim: 2, jumlah_anggota: 5, status: 'aktif' },
                { id: 3, nama_divisi: 'Pemasaran', kepala_divisi: 'Gina Permata', jumlah_tim: 2, jumlah_anggota: 14, status: 'aktif' },
            ];

            let nextTimId = 7;
            let nextDivisiId = 4;

            // ==================== STATE MANAGEMENT ====================
            let timCurrentPage = 1;
            let divisiCurrentPage = 1;
            const itemsPerPage = 5;
            let timActiveFilters = ['all'];
            let divisiActiveFilters = ['all'];
            let timSearchTerm = '';
            let divisiSearchTerm = '';

            // ==================== INITIALIZATION ====================
            initializeTim();
            initializeDivisi();
            attachEventListeners();

            // ==================== TIM FUNCTIONS ====================
            function initializeTim() {
                renderTimTable();
                renderTimPagination();
                updateTimStats();
            }

            function renderTimTable() {
                const filteredData = getFilteredTimData();
                const paginatedData = paginateData(filteredData, timCurrentPage);
                
                const tableBody = document.getElementById('timTableBody');
                const mobileCards = document.getElementById('tim-mobile-cards');
                
                tableBody.innerHTML = '';
                mobileCards.innerHTML = '';

                paginatedData.forEach((item, index) => {
                    const globalIndex = (timCurrentPage - 1) * itemsPerPage + index + 1;
                    
                    // Desktop Table Row
                    const row = document.createElement('tr');
                    row.className = 'tim-row';
                    row.innerHTML = `
                        <td style="min-width: 60px;">${globalIndex}</td>
                        <td style="min-width: 200px;">${item.nama_tim}</td>
                        <td style="min-width: 200px;">${item.divisi_nama}</td>
                        <td style="min-width: 120px;">${item.jumlah_anggota}</td>
                        <td style="min-width: 100px; text-align: center;">
                            <div class="flex justify-center gap-2">
                                <button class="edit-tim-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <button class="delete-tim-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);

                    // Mobile Card
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-base">${item.nama_tim}</h4>
                                <p class="text-sm text-text-muted-light">${item.divisi_nama}</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="edit-tim-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <button class="delete-tim-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-text-muted-light">No</p>
                                <p class="font-medium">${globalIndex}</p>
                            </div>
                            <div>
                                <p class="text-text-muted-light">Jumlah Anggota</p>
                                <p class="font-medium">${item.jumlah_anggota}</p>
                            </div>
                        </div>
                    `;
                    mobileCards.appendChild(card);
                });
                
                document.getElementById('timCount').textContent = filteredData.length;
            }

            function renderTimPagination() {
                const filteredData = getFilteredTimData();
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                renderPagination('tim', totalPages, timCurrentPage);
            }

            function getFilteredTimData() {
                return timData.filter(item => {
                    const matchesSearch = !timSearchTerm || 
                        item.nama_tim.toLowerCase().includes(timSearchTerm.toLowerCase()) ||
                        item.divisi_nama.toLowerCase().includes(timSearchTerm.toLowerCase());
                    
                    const matchesFilter = timActiveFilters.includes('all') || 
                        timActiveFilters.includes(item.divisi_id.toString());

                    return matchesSearch && matchesFilter;
                });
            }

            // ==================== DIVISI FUNCTIONS ====================
            function initializeDivisi() {
                renderDivisiTable();
                renderDivisiPagination();
                updateDivisiStats();
            }

            function renderDivisiTable() {
                const filteredData = getFilteredDivisiData();
                const paginatedData = paginateData(filteredData, divisiCurrentPage);
                
                const tableBody = document.getElementById('divisiTableBody');
                const mobileCards = document.getElementById('divisi-mobile-cards');
                
                tableBody.innerHTML = '';
                mobileCards.innerHTML = '';

                paginatedData.forEach((item, index) => {
                    const globalIndex = (divisiCurrentPage - 1) * itemsPerPage + index + 1;

                    // Desktop Table Row
                    const row = document.createElement('tr');
                    row.className = 'divisi-row';
                    row.innerHTML = `
                        <td style="min-width: 60px;">${globalIndex}</td>
                        <td style="min-width: 200px;">${item.nama_divisi}</td>
                        <td style="min-width: 150px;">${item.jumlah_anggota}</td>
                        <td style="min-width: 100px; text-align: center;">
                            <div class="flex justify-center gap-2">
                                <button class="edit-divisi-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <button class="delete-divisi-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);

                    // Mobile Card
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg border border-border-light p-4 shadow-sm';
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-base">${item.nama_divisi}</h4>
                            </div>
                            <div class="flex gap-2">
                                <button class="edit-divisi-btn p-1 rounded-full hover:bg-primary/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <button class="delete-divisi-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700" data-id='${item.id}'>
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-text-muted-light">No</p>
                                <p class="font-medium">${globalIndex}</p>
                            </div>
                            <div>
                                <p class="text-text-muted-light">Jumlah Tim</p>
                                <p class="font-medium">${item.jumlah_anggota}</p>
                            </div>
                        </div>
                    `;
                    mobileCards.appendChild(card);
                });

                document.getElementById('divisiCount').textContent = filteredData.length;
            }

            function renderDivisiPagination() {
                const filteredData = getFilteredDivisiData();
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                renderPagination('divisi', totalPages, divisiCurrentPage);
            }

            function getFilteredDivisiData() {
                return divisiData.filter(item => {
                    const matchesSearch = !divisiSearchTerm || 
                        item.nama_divisi.toLowerCase().includes(divisiSearchTerm.toLowerCase());
                    
                    return matchesSearch;
                });
            }

            // ==================== GENERAL HELPER FUNCTIONS ====================
            function paginateData(data, currentPage) {
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                return data.slice(startIndex, endIndex);
            }

            function renderPagination(type, totalPages, currentPage) {
                const pageNumbersContainer = document.getElementById(`${type}PageNumbers`);
                const prevButton = document.getElementById(`${type}PrevPage`);
                const nextButton = document.getElementById(`${type}NextPage`);

                pageNumbersContainer.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const pageNumber = document.createElement('button');
                    pageNumber.textContent = i;
                    pageNumber.className = `desktop-page-btn ${i === currentPage ? 'active' : ''}`;
                    pageNumber.addEventListener('click', () => goToPage(type, i));
                    pageNumbersContainer.appendChild(pageNumber);
                }

                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === totalPages || totalPages === 0;
                
                prevButton.onclick = () => goToPage(type, currentPage - 1);
                nextButton.onclick = () => goToPage(type, currentPage + 1);
            }

            function goToPage(type, page) {
                if (type === 'tim') {
                    timCurrentPage = page;
                    initializeTim();
                } else {
                    divisiCurrentPage = page;
                    initializeDivisi();
                }
            }

            function updateTimStats() {
                const totalTim = timData.length;
                const timAktif = timData.filter(t => t.status === 'aktif').length;
                const totalAnggota = timData.reduce((sum, t) => sum + t.jumlah_anggota, 0);

                document.getElementById('stat-total-tim').textContent = totalTim;
                document.getElementById('stat-tim-aktif').textContent = timAktif;
                document.getElementById('stat-total-anggota').textContent = totalAnggota;
            }

            function updateDivisiStats() {
                const totalDivisi = divisiData.length;
                document.getElementById('stat-total-divisi').textContent = totalDivisi;
            }

            // ==================== EVENT LISTENERS ====================
            function attachEventListeners() {
                // Tab Switching
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.addEventListener('click', function() {
                        switchTab(this.id.replace('Tab', ''));
                    });
                });

                // Modal Controls
                document.querySelectorAll('.close-modal, .cancel-modal').forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        closeModal(targetId);
                    });
                });

                // Form Submissions
                document.getElementById('tambahTimForm').addEventListener('submit', handleAddTim);
                document.getElementById('editTimForm').addEventListener('submit', handleEditTim);
                document.getElementById('deleteTimForm').addEventListener('submit', handleDeleteTim);
                document.getElementById('tambahDivisiForm').addEventListener('submit', handleAddDivisi);
                document.getElementById('editDivisiForm').addEventListener('submit', handleEditDivisi);
                document.getElementById('deleteDivisiForm').addEventListener('submit', handleDeleteDivisi);

                // Tombol Tambah & Edit
                document.getElementById('tambahTimBtn').addEventListener('click', () => openModal('tambahTimModal'));
                document.getElementById('tambahDivisiBtn').addEventListener('click', () => openModal('tambahDivisiModal'));
                
                // Search and Filter for Tim
                document.getElementById('searchTimInput').addEventListener('input', debounce(function(e) {
                    timSearchTerm = e.target.value.trim();
                    timCurrentPage = 1;
                    initializeTim();
                }, 300));

                document.getElementById('filterTimBtn').addEventListener('click', () => toggleDropdown('filterTimDropdown'));
                document.getElementById('applyTimFilter').addEventListener('click', applyTimFilter);
                document.getElementById('resetTimFilter').addEventListener('click', resetTimFilter);
                
                // Search for Divisi
                document.getElementById('searchDivisiInput').addEventListener('input', debounce(function(e) {
                    divisiSearchTerm = e.target.value.trim();
                    divisiCurrentPage = 1;
                    initializeDivisi();
                }, 300));

                // Popup Close
                document.querySelector('.minimal-popup-close').addEventListener('click', () => {
                    document.getElementById('minimalPopup').classList.remove('show');
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.relative')) {
                        document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('show'));
                    }
                });

                // Attach listeners to dynamically created buttons
                document.body.addEventListener('click', function(e) {
                    if (e.target.closest('.edit-tim-btn')) {
                        const id = parseInt(e.target.closest('.edit-tim-btn').dataset.id);
                        openEditTimModal(id);
                    }
                    if (e.target.closest('.delete-tim-btn')) {
                        const id = parseInt(e.target.closest('.delete-tim-btn').dataset.id);
                        openDeleteModal('tim', id);
                    }
                    if (e.target.closest('.edit-divisi-btn')) {
                        const id = parseInt(e.target.closest('.edit-divisi-btn').dataset.id);
                        openEditDivisiModal(id);
                    }
                    if (e.target.closest('.delete-divisi-btn')) {
                        const id = parseInt(e.target.closest('.delete-divisi-btn').dataset.id);
                        openDeleteModal('divisi', id);
                    }
                });
            }

            // ==================== CRUD HANDLERS ====================
            function handleAddTim(e) {
                e.preventDefault();
                const formData = new FormData(e.target);
                const newTim = {
                    id: nextTimId++,
                    nama_tim: formData.get('nama_tim'),
                    divisi_id: parseInt(formData.get('divisi_id')),
                    divisi_nama: e.target.elements['divisi_id'].options[e.target.elements['divisi_id'].selectedIndex].text,
                    ketua_tim: formData.get('ketua_tim'),
                    jumlah_anggota: parseInt(formData.get('jumlah_anggota')),
                    status: formData.get('status')
                };
                
                simulateApiCall(() => {
                    timData.push(newTim);
                    closeModal('tambahTimModal');
                    e.target.reset();
                    timCurrentPage = 1;
                    initializeTim();
                    showMinimalPopup('Berhasil', 'Tim berhasil ditambahkan', 'success');
                });
            }

            function handleEditTim(e) {
                e.preventDefault();
                const id = parseInt(document.getElementById('editTimId').value);
                const formData = new FormData(e.target);
                const timIndex = timData.findIndex(t => t.id === id);
                
                if (timIndex !== -1) {
                    simulateApiCall(() => {
                        timData[timIndex] = {
                            ...timData[timIndex],
                            nama_tim: formData.get('nama_tim'),
                            divisi_id: parseInt(formData.get('divisi_id')),
                            divisi_nama: e.target.elements['divisi_id'].options[e.target.elements['divisi_id'].selectedIndex].text,
                            ketua_tim: formData.get('ketua_tim'),
                            jumlah_anggota: parseInt(formData.get('jumlah_anggota')),
                            status: formData.get('status')
                        };
                        closeModal('editTimModal');
                        initializeTim();
                        showMinimalPopup('Berhasil', 'Data tim berhasil diperbarui', 'success');
                    });
                }
            }

            function handleDeleteTim(e) {
                e.preventDefault();
                const id = parseInt(document.getElementById('deleteTimId').value);
                
                simulateApiCall(() => {
                    timData = timData.filter(t => t.id !== id);
                    closeModal('deleteTimModal');
                    timCurrentPage = 1;
                    initializeTim();
                    showMinimalPopup('Berhasil', 'Data tim berhasil dihapus', 'success');
                });
            }

            function handleAddDivisi(e) {
                e.preventDefault();
                const formData = new FormData(e.target);
                const newDivisi = {
                    id: nextDivisiId++,
                    nama_divisi: formData.get('nama_divisi'),
                    kepala_divisi: formData.get('kepala_divisi'),
                    jumlah_tim: 0,
                    jumlah_anggota: 0,
                    status: 'aktif'
                };
                
                simulateApiCall(() => {
                    divisiData.push(newDivisi);
                    closeModal('tambahDivisiModal');
                    e.target.reset();
                    divisiCurrentPage = 1;
                    initializeDivisi();
                    showMinimalPopup('Berhasil', 'Divisi berhasil ditambahkan', 'success');
                });
            }

            function handleEditDivisi(e) {
                e.preventDefault();
                const id = parseInt(document.getElementById('editDivisiId').value);
                const formData = new FormData(e.target);
                const divisiIndex = divisiData.findIndex(d => d.id === id);
                
                if (divisiIndex !== -1) {
                    simulateApiCall(() => {
                        divisiData[divisiIndex] = {
                            ...divisiData[divisiIndex],
                            nama_divisi: formData.get('nama_divisi'),
                            kepala_divisi: formData.get('kepala_divisi'),
                            status: formData.get('status')
                        };
                        closeModal('editDivisiModal');
                        initializeDivisi();
                        showMinimalPopup('Berhasil', 'Data divisi berhasil diperbarui', 'success');
                    });
                }
            }

            function handleDeleteDivisi(e) {
                e.preventDefault();
                const id = parseInt(document.getElementById('deleteDivisiId').value);
                
                simulateApiCall(() => {
                    divisiData = divisiData.filter(d => d.id !== id);
                    closeModal('deleteDivisiModal');
                    divisiCurrentPage = 1;
                    initializeDivisi();
                    showMinimalPopup('Berhasil', 'Data divisi berhasil dihapus', 'success');
                });
            }

            // ==================== UI HELPER FUNCTIONS ====================
            function switchTab(tabName) {
                // Hide all panels and remove active class from all tabs
                document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
                document.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));
                
                // Show selected panel and add active class to clicked tab
                document.getElementById(`${tabName}Panel`).classList.remove('hidden');
                document.getElementById(`${tabName}Tab`).classList.add('active');
            }

            function openEditTimModal(id) {
                const tim = timData.find(t => t.id === id);
                if (tim) {
                    document.getElementById('editTimId').value = tim.id;
                    document.getElementById('editNamaTim').value = tim.nama_tim;
                    document.getElementById('editJumlahAnggota').value = tim.jumlah_anggota;
                    document.getElementById('editDivisiSelect').value = tim.divisi_id;
                    document.getElementById('editStatusTimSelect').value = tim.status;
                    openModal('editTimModal');
                }
            }

            function openEditDivisiModal(id) {
                const divisi = divisiData.find(d => d.id === id);
                if (divisi) {
                    document.getElementById('editDivisiId').value = divisi.id;
                    document.getElementById('editNamaDivisi').value = divisi.nama_divisi;
                    document.getElementById('editKepalaDivisiSelect').value = divisi.kepala_divisi;
                    document.getElementById('editStatusDivisiSelect').value = divisi.status;
                    openModal('editDivisiModal');
                }
            }

            function openDeleteModal(type, id) {
                document.getElementById(`delete${type.charAt(0).toUpperCase() + type.slice(1)}Id`).value = id;
                openModal(`delete${type.charAt(0).toUpperCase() + type.slice(1)}Modal`);
            }

            function openModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }

            function toggleDropdown(dropdownId) {
                document.getElementById(dropdownId).classList.toggle('show');
            }

            function applyTimFilter() {
                const filterAll = document.getElementById('filterTimAll').checked;
                const filterTI = document.getElementById('filterTimTI').checked;
                const filterSDM = document.getElementById('filterTimSDM').checked;
                const filterPemasaran = document.getElementById('filterTimPemasaran').checked;

                timActiveFilters = [];
                if (filterAll) {
                    timActiveFilters.push('all');
                } else {
                    if (filterTI) timActiveFilters.push('1');
                    if (filterSDM) timActiveFilters.push('2');
                    if (filterPemasaran) timActiveFilters.push('3');
                }
                timCurrentPage = 1;
                initializeTim();
                toggleDropdown('filterTimDropdown');
                showMinimalPopup('Filter Diterapkan', `Menampilkan ${getFilteredTimData().length} tim`, 'success');
            }

            function resetTimFilter() {
                document.getElementById('filterTimAll').checked = true;
                document.getElementById('filterTimTI').checked = false;
                document.getElementById('filterTimSDM').checked = false;
                document.getElementById('filterTimPemasaran').checked = false;
                timActiveFilters = ['all'];
                timCurrentPage = 1;
                initializeTim();
                toggleDropdown('filterTimDropdown');
                showMinimalPopup('Filter Direset', 'Menampilkan semua tim', 'success');
            }

            function showMinimalPopup(title, message, type = 'success') {
                const popup = document.getElementById('minimalPopup');
                const popupTitle = popup.querySelector('.minimal-popup-title');
                const popupMessage = popup.querySelector('.minimal-popup-message');
                const popupIcon = popup.querySelector('.minimal-popup-icon span');

                popupTitle.textContent = title;
                popupMessage.textContent = message;
                popup.className = `minimal-popup show ${type}`;

                if (type === 'success') popupIcon.textContent = 'check';
                else if (type === 'error') popupIcon.textContent = 'error';
                else if (type === 'warning') popupIcon.textContent = 'warning';

                setTimeout(() => {
                    popup.classList.remove('show');
                }, 3000);
            }

            function simulateApiCall(callback, shouldFail = false) {
                // Show loading state on button
                const submitBtn = document.activeElement;
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Memproses...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    // Randomly fail for demonstration (10% chance)
                    if (Math.random() < 0.1 || shouldFail) {
                        showMinimalPopup('Error', 'Terjadi kesalahan pada server. Silakan coba lagi.', 'error');
                    } else {
                        callback();
                    }
                    // Reset button state
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 800); // Simulate network delay
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
</body>
</html>
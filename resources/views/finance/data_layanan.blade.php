<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Layanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
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
        
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }
        
        /* Card hover effects */
        .service-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Progress bar styling */
        .progress-bar {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #3b82f6;
            border-radius: 9999px;
            transition: width 0.3s ease;
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
        
        /* Modal styles */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
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
        
        /* Custom scrollbar for dark mode */
        .dark ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        
        /* Mobile card adjustments */
        @media (max-width: 639px) {
            .service-card {
                padding: 0.75rem !important;
            }
            
            .service-card .bg-gray-100 {
                height: 120px !important;
            }
            
            .service-card h3 {
                font-size: 1rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            .service-card .space-y-2 {
                gap: 0.25rem !important;
            }
            
            .service-card .space-y-2 > div {
                font-size: 0.75rem !important;
            }
            
            .service-card .material-icons-outlined {
                font-size: 1rem !important;
            }
            
            .service-card .progress-bar {
                height: 6px !important;
            }
            
            .service-card .text-xs {
                font-size: 0.625rem !important;
            }
            
            .service-card .flex.justify-between {
                margin-bottom: 0.5rem !important;
            }
            
            .service-card button {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.75rem !important;
            }
        }
        
        /* Pagination styles */
        .pagination-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #cbd5e1;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .pagination-dot.active {
            background-color: #3b82f6;
            width: 24px;
            border-radius: 4px;
        }
        
        /* Mobile pagination styles */
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
        
        /* Custom styles untuk transisi */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Animasi hamburger */
        .hamburger-line {
            transition: all 0.3s ease-in-out;
        }
        
        .hamburger-active .line1 {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger-active .line2 {
            opacity: 0;
        }
        
        .hamburger-active .line3 {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Style untuk efek hover yang lebih menonjol */
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
        /* Gaya untuk indikator aktif/hover */
        /* Default untuk mobile: di sebelah kanan */
        .nav-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background-color: #000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        /* Override untuk desktop: di sebelah kiri */
        @media (min-width: 768px) {
            .nav-item::before {
                right: auto;
                left: 0;
                transform: translateX(-100%);
            }
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: translateX(0);
        }
        
        /* Memastikan sidebar tetap di posisinya saat scroll */
        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }
        
        /* Scrollbar kustom untuk sidebar */
        .sidebar-fixed::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <!-- Tombol Hamburger untuk Mobile (sekarang di kanan) -->
    <button id="hamburger" class="md:hidden fixed top-4 right-4 z-50 p-2 rounded-md bg-white shadow-md">
        <div class="w-6 h-6 flex flex-col justify-center space-y-1">
            <div class="hamburger-line line1 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line2 w-6 h-0.5 bg-gray-800"></div>
            <div class="hamburger-line line3 w-6 h-0.5 bg-gray-800"></div>
        </div>
    </button>

    <!-- Overlay untuk Mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
  <body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')

    <!-- Konten Utama -->
    <div id="main-content" class="main-content min-h-screen md:ml-64">
        <main class="flex-1 p-4 md:p-8">
            <h2 class="text-3xl font-bold mb-6">Data Layanan</h2>
            
            <!-- Search and Filter Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="relative w-full md:w-1/3">
                    <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input" placeholder="Search" type="text" />
                </div>
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <button class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                        Filter
                    </button>
                    <button onclick="openAddModal()" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                        <span class="material-icons-outlined">add</span>
                        <span class="hidden sm:inline">Tambah Data Layanan</span>
                        <span class="sm:hidden">Tambah</span>
                    </button>
                </div>
            </div>
            
            <!-- Cards Container -->
            <div class="relative">
                <!-- Desktop Cards Grid -->
                <div class="hidden md:block">
                    <div id="desktopPagesContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-4">
                        <!-- Desktop Card 1 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">Website Sekolah</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">school</span>
                                    <span>Educational</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>Sekolah ABC</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 10.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 75%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">75%</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Card 2 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">Aplikasi Mobile</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">phone_android</span>
                                    <span>Mobile App</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>PT. Teknologi</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 15.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 45%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">45%</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Card 3 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">Desain UI/UX</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">palette</span>
                                    <span>Design</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>CV. Kreatif</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 7.500.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 90%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">90%</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Card 4 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">SEO Optimization</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">search</span>
                                    <span>Digital Marketing</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>Toko Online</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 5.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 30%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">30%</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Card 5 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">Sistem ERP</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">business</span>
                                    <span>Enterprise</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>PT. Manufaktur</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 25.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 60%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">60%</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Card 6 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">E-Commerce</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Card 7 -->
                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">Aplikasi Kasir</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">point_of_sale</span>
                                    <span>POS System</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>Resto Nusantara</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 8.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 55%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">55%</span>
                            </div>
                        </div>

                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">E-Commerce</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>

                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">E-Commerce</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>

                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">E-Commerce</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>

                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">E-Commerce</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>

                        <div class="service-card desktop-card bg-card-light rounded-DEFAULT p-5 flex flex-col gap-4 border border-border-light">
                            <div class="bg-gray-100 h-40 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-4xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-base">edit</span>
                                    <span class="hidden sm:inline">Edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-sm bg-white border border-border-light px-3 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-lg">E-Commerce</h3>
                            <div class="space-y-2 text-sm text-text-muted-light">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-base">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Desktop Pagination Container -->
                    <div id="desktopPaginationContainer" class="desktop-pagination hidden">
                        <button id="desktopPrevPage" class="desktop-nav-btn">
                            <span class="material-icons-outlined text-sm">chevron_left</span>
                        </button>
                        <div id="desktopPageNumbers" class="flex gap-1">
                            <!-- Page numbers will be generated by JavaScript -->
                        </div>
                        <button id="desktopNextPage" class="desktop-nav-btn">
                            <span class="material-icons-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Cards Grid -->
                <div class="md:hidden">
                    <div id="mobilePagesContainer" class="grid grid-cols-2 gap-3 mb-4">
                        <!-- Mobile Card 1 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light" data-page="1">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">Website Sekolah</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">school</span>
                                    <span>Educational</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>Sekolah ABC</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 10.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 75%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">75%</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Card 2 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light" data-page="1">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">Aplikasi Mobile</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">phone_android</span>
                                    <span>Mobile App</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>PT. Teknologi</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 15.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 45%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">45%</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Card 3 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light" data-page="1">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">Desain UI/UX</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">palette</span>
                                    <span>Design</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>CV. Kreatif</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 7.500.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 90%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">90%</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Card 4 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light" data-page="1">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">SEO Optimization</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">search</span>
                                    <span>Digital Marketing</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>Toko Online</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 5.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 30%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">30%</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Card 5 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light hidden" data-page="2">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">Sistem ERP</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">business</span>
                                    <span>Enterprise</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>PT. Manufaktur</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 25.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 60%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">60%</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Card 6 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light hidden" data-page="2">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">E-Commerce</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">shopping_cart</span>
                                    <span>Online Store</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>UD. Fashion</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 12.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">15%</span>
                            </div>
                        </div>
                        
                        <!-- Mobile Card 7 -->
                        <div class="service-card mobile-card bg-card-light rounded-DEFAULT p-2 flex flex-col gap-2 border border-border-light hidden" data-page="2">
                            <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center text-text-muted-light">
                                <span class="material-icons-outlined text-2xl">image</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <button onclick="openEditModal()" class="flex items-center gap-1 text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="openStatusModal()" class="text-xs bg-white border border-border-light px-2 py-1 rounded-md text-text-light hover:bg-gray-50 transition-colors">Status</button>
                            </div>
                            <h3 class="font-semibold text-sm">Aplikasi Kasir</h3>
                            <div class="space-y-1 text-xs text-text-muted-light">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">point_of_sale</span>
                                    <span>POS System</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">groups</span>
                                    <span>Resto Nusantara</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">payments</span>
                                    <span>Rp 8.000.000</span>
                                </div>
                            </div>
                            <div>
                                <div class="progress-bar mb-1">
                                    <div class="progress-fill" style="width: 55%"></div>
                                </div>
                                <span class="text-xs text-text-muted-light">55%</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Pagination -->
                    <div class="flex justify-center items-center gap-2 mt-4">
                        <button id="prevPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-icons-outlined text-sm">chevron_left</span>
                        </button>
                        <div id="pageNumbers" class="flex gap-1">
                            <!-- Page numbers will be generated by JavaScript -->
                        </div>
                        <button id="nextPage" class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-icons-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <footer class="bg-gray-100 text-center p-4 text-sm text-text-muted-light border-t border-border-light">
            Copyright ©2025 by digicity.id
        </footer>
    </div>

    <!-- Modal Tambah Data Layanan -->
    <div id="addModal" class="fixed inset-0 modal-backdrop flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-text-light">Tambah Data Layanan</h3>
                <button onclick="closeAddModal()" class="text-text-muted-light hover:text-text-light">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Foto Layanan</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="addPhoto" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-border-light rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="addPhotoPreview">
                                <span class="material-icons-outlined text-4xl text-text-muted-light mb-2">cloud_upload</span>
                                <p class="mb-2 text-sm text-text-muted-light"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-xs text-text-muted-light">PNG, JPG, GIF (MAX. 5MB)</p>
                            </div>
                            <input id="addPhoto" type="file" class="hidden" onchange="previewAddPhoto(event)" />
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Nama Layanan</label>
                    <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Kategori</label>
                    <select class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                        <option value="">Pilih Kategori</option>
                        <option value="website">Website</option>
                        <option value="aplikasi">Aplikasi</option>
                        <option value="design">Design</option>
                        <option value="marketing">Digital Marketing</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Klien</label>
                    <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Harga</label>
                    <input type="text" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Progress</label>
                    <input type="range" min="0" max="100" value="0" class="w-full">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 btn-secondary rounded-lg order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg order-1 sm:order-2">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Data Layanan -->
    <div id="editModal" class="fixed inset-0 modal-backdrop flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-text-light">Edit Data Layanan</h3>
                <button onclick="closeEditModal()" class="text-text-muted-light hover:text-text-light">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Foto Layanan</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="editPhoto" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-border-light rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="editPhotoPreview">
                                <span class="material-icons-outlined text-4xl text-text-muted-light mb-2">cloud_upload</span>
                                <p class="mb-2 text-sm text-text-muted-light"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-xs text-text-muted-light">PNG, JPG, GIF (MAX. 5MB)</p>
                            </div>
                            <input id="editPhoto" type="file" class="hidden" onchange="previewEditPhoto(event)" />
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Nama Layanan</label>
                    <input type="text" value="Website Sekolah" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Kategori</label>
                    <select class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                        <option value="">Pilih Kategori</option>
                        <option value="website" selected>Website</option>
                        <option value="aplikasi">Aplikasi</option>
                        <option value="design">Design</option>
                        <option value="marketing">Digital Marketing</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Klien</label>
                    <input type="text" value="Sekolah ABC" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Harga</label>
                    <input type="text" value="Rp 10.000.000" class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Progress</label>
                    <input type="range" min="0" max="100" value="75" class="w-full">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 btn-secondary rounded-lg order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg order-1 sm:order-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Status -->
    <div id="statusModal" class="fixed inset-0 modal-backdrop flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-text-light">Ubah Status</h3>
                <button onclick="closeStatusModal()" class="text-text-muted-light hover:text-text-light">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Status Layanan</label>
                    <select class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light">
                        <option value="">Pilih Status</option>
                        <option value="pending">Pending</option>
                        <option value="progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-text-light mb-2">Keterangan</label>
                    <textarea class="w-full px-3 py-2 border border-border-light rounded-lg focus:outline-none focus:ring-2 focus:ring-primary form-input bg-white text-text-light" rows="3"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 btn-secondary rounded-lg order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg order-1 sm:order-2">
                        Simpan Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk inisialisasi sidebar
        function initSidebar() {
            // Ambil elemen yang diperlukan
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainContent = document.getElementById('main-content');

            // Fungsi untuk membuka sidebar
            function openSidebar() {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                hamburger.classList.add('hamburger-active');
                document.body.style.overflow = 'hidden'; // Mencegah scroll saat sidebar terbuka
            }

            // Fungsi untuk menutup sidebar
            function closeSidebar() {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                hamburger.classList.remove('hamburger-active');
                document.body.style.overflow = ''; // Kembalikan scroll
            }

            // Event listener untuk hamburger
            hamburger.addEventListener('click', () => {
                // Periksa apakah sidebar sedang tersembunyi (untuk mobile)
                if (sidebar.classList.contains('translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            // Event listener untuk overlay
            overlay.addEventListener('click', closeSidebar);

            // Event listener untuk escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !sidebar.classList.contains('translate-x-full')) {
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
        }

        // Modal functions
        function openAddModal() {
            const modal = document.getElementById('addModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeAddModal() {
            const modal = document.getElementById('addModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                // Reset form
                const photoInput = document.getElementById('addPhoto');
                const photoPreview = document.getElementById('addPhotoPreview');
                if (photoInput && photoPreview) {
                    photoInput.value = '';
                    photoPreview.innerHTML = `
                        <span class="material-icons-outlined text-4xl text-text-muted-light mb-2">cloud_upload</span>
                        <p class="mb-2 text-sm text-text-muted-light"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                        <p class="text-xs text-text-muted-light">PNG, JPG, GIF (MAX. 5MB)</p>
                    `;
                }
            }
        }

        function openEditModal() {
            const modal = document.getElementById('editModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                // Reset form khusus untuk bagian foto
                const photoInput = document.getElementById('editPhoto');
                const photoPreview = document.getElementById('editPhotoPreview');
                if (photoInput && photoPreview) {
                    photoInput.value = '';
                    photoPreview.innerHTML = `
                        <span class="material-icons-outlined text-4xl text-text-muted-light mb-2">cloud_upload</span>
                        <p class="mb-2 text-sm text-text-muted-light"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                        <p class="text-xs text-text-muted-light">PNG, JPG, GIF (MAX. 5MB)</p>
                    `;
                }
            }
        }

        function openStatusModal() {
            const modal = document.getElementById('statusModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Preview photo functions
        function previewAddPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('addPhotoPreview');
                    if (preview) {
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="preview-image rounded-lg mb-2">
                            <p class="text-xs text-text-muted-light">Klik untuk mengganti foto</p>
                        `;
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        function previewEditPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('editPhotoPreview');
                    if (preview) {
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="preview-image rounded-lg mb-2">
                            <p class="text-xs text-text-muted-light">Klik untuk mengganti foto</p>
                        `;
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        // Desktop pagination functionality
        function initDesktopPagination() {
            const cardsPerPage = 9; // 9 cards per page for desktop
            const desktopCards = document.querySelectorAll('.desktop-card');
            const totalCards = desktopCards.length;
            const paginationContainer = document.getElementById('desktopPaginationContainer');
            
            // Only show pagination if there are more than 3 cards
            if (totalCards <= cardsPerPage) {
                // Show all cards and hide pagination
                desktopCards.forEach(card => {
                    card.classList.remove('hidden');
                });
                if (paginationContainer) {
                    paginationContainer.classList.add('hidden');
                }
                return;
            }
            
            // Show pagination container
            if (paginationContainer) {
                paginationContainer.classList.remove('hidden');
            }
            
            const totalPages = Math.ceil(totalCards / cardsPerPage);
            let currentPage = 1;
            
            const pageNumbersContainer = document.getElementById('desktopPageNumbers');
            const prevButton = document.getElementById('desktopPrevPage');
            const nextButton = document.getElementById('desktopNextPage');
            
            // Clear existing page numbers
            if (pageNumbersContainer) {
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
            }
            
            // Update button states
            function updateButtonStates() {
                if (prevButton) prevButton.disabled = currentPage === 1;
                if (nextButton) nextButton.disabled = currentPage === totalPages;
                
                // Update page number buttons
                if (pageNumbersContainer) {
                    const pageButtons = pageNumbersContainer.querySelectorAll('button');
                    pageButtons.forEach((btn, index) => {
                        if (index + 1 === currentPage) {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    });
                }
            }
            
            // Go to specific page
            function goToPage(page) {
                if (page < 1 || page > totalPages) return;
                
                currentPage = page;
                
                // Hide all cards
                desktopCards.forEach(card => {
                    card.classList.add('hidden');
                });
                
                // Show cards for current page
                const startIndex = (page - 1) * cardsPerPage;
                const endIndex = Math.min(startIndex + cardsPerPage, totalCards);
                
                for (let i = startIndex; i < endIndex; i++) {
                    desktopCards[i].classList.remove('hidden');
                }
                
                updateButtonStates();
            }
            
            // Event listeners for navigation buttons
            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    if (currentPage > 1) goToPage(currentPage - 1);
                });
            }
            
            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    if (currentPage < totalPages) goToPage(currentPage + 1);
                });
            }
            
            // Initialize first page
            goToPage(1);
        }

        // Mobile pagination functionality
        function initMobilePagination() {
            const cardsPerPage = 4; // 2x2 grid
            const mobileCards = document.querySelectorAll('.mobile-card');
            const totalPages = Math.ceil(mobileCards.length / cardsPerPage);
            let currentPage = 1;
            
            const pageNumbersContainer = document.getElementById('pageNumbers');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            
            // Generate page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageNumber = document.createElement('button');
                pageNumber.textContent = i;
                pageNumber.className = `page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm ${
                    i === currentPage ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600'
                }`;
                pageNumber.addEventListener('click', () => goToPage(i));
                pageNumbersContainer.appendChild(pageNumber);
            }
            
            // Update button states
            function updateButtonStates() {
                prevButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === totalPages;
                
                // Update page number buttons
                const pageButtons = pageNumbersContainer.querySelectorAll('button');
                pageButtons.forEach((btn, index) => {
                    if (index + 1 === currentPage) {
                        btn.className = 'page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm bg-primary text-white';
                    } else {
                        btn.className = 'page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm bg-gray-200 text-gray-600';
                    }
                });
            }
            
            // Go to specific page
            function goToPage(page) {
                if (page < 1 || page > totalPages) return;
                
                currentPage = page;
                
                // Hide all cards
                mobileCards.forEach(card => {
                    card.classList.add('hidden');
                });
                
                // Show cards for current page
                const startIndex = (page - 1) * cardsPerPage;
                const endIndex = Math.min(startIndex + cardsPerPage, mobileCards.length);
                
                for (let i = startIndex; i < endIndex; i++) {
                    mobileCards[i].classList.remove('hidden');
                }
                
                updateButtonStates();
            }
            
            // Event listeners for navigation buttons
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) goToPage(currentPage - 1);
            });
            
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) goToPage(currentPage + 1);
            });
            
            // Initialize first page
            goToPage(1);
        }

        // Initialize all event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar
            initSidebar();
            
            // Initialize desktop pagination
            initDesktopPagination();
            
            // Initialize mobile pagination
            initMobilePagination();
            
            // Close modal when clicking outside
            window.onclick = function(event) {
                const addModal = document.getElementById('addModal');
                const editModal = document.getElementById('editModal');
                const statusModal = document.getElementById('statusModal');
                
                if (event.target == addModal) {
                    closeAddModal();
                }
                if (event.target == editModal) {
                    closeEditModal();
                }
                if (event.target == statusModal) {
                    closeStatusModal();
                }
            }
            
            // Handle escape key to close modals
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                    closeStatusModal();
                }
            });
        });
    </script>
</body>

</html>
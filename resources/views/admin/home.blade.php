<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard Overview</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6366f1", // indigo-500
                        secondary: "#8b5cf6", // violet-500
                        accent: "#ec4899", // pink-500
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-light": "#ffffff", // white
                        "surface-dark": "#1e293b", // slate-800
                        "text-light": "#0f172a", // slate-900
                        "text-dark": "#f1f5f9", // slate-100
                        "subtle-light": "#64748b", // slate-500
                        "subtle-dark": "#94a3b8", // slate-400
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "1rem", // 16px
                        lg: "1.5rem", // 24px
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                    },
                },
            },
        };
    </script>
    <style>
        /* Custom styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-image: 
                radial-gradient(at 47% 33%, hsl(262.00, 53.00%, 53.00%) 0px, transparent 59%),
                radial-gradient(at 82% 65%, hsl(218.00, 39.00%, 11.00%) 0px, transparent 55%);
        }
        
        .dark body {
            background-image: 
                radial-gradient(at 47% 33%, hsl(262.00, 53.00%, 23.00%) 0px, transparent 59%),
                radial-gradient(at 82% 65%, hsl(218.00, 39.00%, 5.00%) 0px, transparent 55%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .dark .glass-effect {
            background: rgba(30, 41, 59, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(30, 41, 59, 0.18);
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Style untuk link agar tidak terlihat seperti link default */
        .card-link {
            display: block; /* Membuat <a> memenuhi seluruh area div */
            color: inherit; /* Mewarisi warna link default */
            text-decoration: none; /* Menghilangkan underline */
        }
        
        /* Icon gradient styles */
        .icon-gradient-1 {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }
        
        .icon-gradient-2 {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
        }
        
        .icon-gradient-3 {
            background: linear-gradient(135deg, #ec4899, #f97316);
        }
        
        .icon-gradient-4 {
            background: linear-gradient(135deg, #f97316, #ef4444);
        }
        
        /* Table row hover effect */
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .dark .table-row:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark min-h-screen">
    <div class="flex h-screen">
        <!-- Include Sidebar -->
        @include('admin/templet/sider')
        
        <!-- Main content with artistic design -->
        <main class="flex-1 flex-col p-8 overflow-y-auto pattern-bg">
            <div class="flex-1">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-4xl font-bold mb-2">Dashboard</h2>
                        <p class="text-subtle-light dark:text-subtle-dark">Selamat datang kembali! Ini ringkasan aktivitas Anda.</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full bg-surface-light dark:bg-surface-dark shadow-sm">
                            <span class="material-icons-outlined">notifications</span>
                        </button>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary to-secondary flex items-center justify-center text-white font-bold">
                            A
                        </div>
                    </div>
                </div>
                
                <!-- Stats cards with artistic design -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Karyawan Card (Clickable) -->
                    <a href="{{ route('admin.data_karyawan') }}" class="card-link card-hover bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg cursor-pointer overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-primary bg-opacity-10 rounded-full -mr-10 -mt-10"></div>
                        <div class="relative z-10">
                            <div class="icon-gradient-1 w-12 h-12 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <span class="material-icons-outlined text-white text-2xl">people</span>
                            </div>
                            <p class="text-sm text-subtle-light dark:text-subtle-dark mb-1">Jumlah Karyawan</p>
                            <p class="text-lg font-medium text-primary">Klik untuk detail</p>
                            <div class="mt-4 flex items-center text-sm text-subtle-light dark:text-subtle-dark">
                                <span class="material-icons-outlined text-xs">trending_up</span>
                                <span class="ml-1">12% dari bulan lalu</span>
                            </div>
                        </div>
                    </a>
                    
                    <!-- PM Card (Not Clickable) -->
                    <div id="pm-card" class="card-hover bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg cursor-pointer overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-secondary bg-opacity-10 rounded-full -mr-10 -mt-10"></div>
                        <div class="relative z-10">
                            <div class="icon-gradient-2 w-12 h-12 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <span class="material-icons-outlined text-white text-2xl">assignment_ind</span>
                            </div>
                            <p class="text-sm text-subtle-light dark:text-subtle-dark mb-1">Jumlah PM</p>
                            <p class="text-lg font-medium text-secondary">Klik untuk detail</p>
                            <div class="mt-4 flex items-center text-sm text-subtle-light dark:text-subtle-dark">
                                <span class="material-icons-outlined text-xs">trending_up</span>
                                <span class="ml-1">8% dari bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Layanan Card (Not Clickable) -->
                    <div id="layanan-card" class="card-hover bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg cursor-pointer overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-accent bg-opacity-10 rounded-full -mr-10 -mt-10"></div>
                        <div class="relative z-10">
                            <div class="icon-gradient-3 w-12 h-12 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <span class="material-icons-outlined text-white text-2xl">design_services</span>
                            </div>
                            <p class="text-sm text-subtle-light dark:text-subtle-dark mb-1">Jumlah Layanan</p>
                            <p class="text-lg font-medium text-accent">Klik untuk detail</p>
                            <div class="mt-4 flex items-center text-sm text-subtle-light dark:text-subtle-dark">
                                <span class="material-icons-outlined text-xs">trending_up</span>
                                <span class="ml-1">15% dari bulan lalu</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Keuangan Card (Clickable) -->
                    <a href="{{ route('admin.keuangan.index') }}" class="card-link card-hover bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg cursor-pointer overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-orange-500 bg-opacity-10 rounded-full -mr-10 -mt-10"></div>
                        <div class="relative z-10">
                            <div class="icon-gradient-4 w-12 h-12 rounded-xl flex items-center justify-center mb-4 shadow-md">
                                <span class="material-icons-outlined text-white text-2xl">account_balance_wallet</span>
                            </div>
                            <p class="text-sm text-subtle-light dark:text-subtle-dark mb-1">Total Keuangan</p>
                            <p class="text-lg font-medium text-orange-500">Klik untuk detail</p>
                            <div class="mt-4 flex items-center text-sm text-subtle-light dark:text-subtle-dark">
                                <span class="material-icons-outlined text-xs">trending_up</span>
                                <span class="ml-1">5% dari bulan lalu</span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Absensi Card (Clickable) -->
                <div class="grid grid-cols-1 mb-8">
                    <a href="{{ route('admin.absensi.index') }}" class="card-link card-hover bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg cursor-pointer overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-green-500 bg-opacity-10 rounded-full -mr-10 -mt-10"></div>
                        <div class="relative z-10 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 shadow-md bg-green-500">
                                    <span class="material-icons-outlined text-white text-2xl">fact_check</span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-subtle-light dark:text-subtle-dark mb-1">Data Absensi</p>
                                    <p class="text-lg font-medium text-green-500">Klik untuk detail</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-subtle-light dark:text-subtle-dark">
                                <span class="material-icons-outlined text-xs">trending_up</span>
                                <span class="ml-1">10% dari bulan lalu</span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
                    <!-- Chart Section -->
                    <div class="xl:col-span-2 bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg">Grafik Absensi</h3>
                            <div class="flex space-x-2">
                                <button class="p-2 rounded-lg bg-surface-light dark:bg-surface-dark shadow-sm">
                                    <span class="material-icons-outlined text-sm">filter_list</span>
                                </button>
                                <button class="p-2 rounded-lg bg-surface-light dark:bg-surface-dark shadow-sm">
                                    <span class="material-icons-outlined text-sm">more_vert</span>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-end gap-4 sm:gap-6 h-64">
                            <div class="flex-1 flex-col items-center">
                                <div class="w-full chart-bar bg-gradient-to-t from-primary to-primary bg-opacity-70 rounded-t-lg" style="height: 65%;"></div>
                                <p class="text-sm mt-3 text-subtle-light dark:text-subtle-dark">Jan</p>
                            </div>
                            <div class="flex-1 flex-col items-center">
                                <div class="w-full chart-bar bg-gradient-to-t from-primary to-primary bg-opacity-70 rounded-t-lg" style="height: 40%;"></div>
                                <p class="text-sm mt-3 text-subtle-light dark:text-subtle-dark">Feb</p>
                            </div>
                            <div class="flex-1 flex-col items-center">
                                <div class="w-full chart-bar bg-gradient-to-t from-primary to-primary bg-opacity-70 rounded-t-lg" style="height: 85%;"></div>
                                <p class="text-sm mt-3 text-subtle-light dark:text-subtle-dark">Mar</p>
                            </div>
                            <div class="flex-1 flex-col items-center">
                                <div class="w-full chart-bar bg-gradient-to-t from-primary to-primary bg-opacity-70 rounded-t-lg" style="height: 68%;"></div>
                                <p class="text-sm mt-3 text-subtle-light dark:text-subtle-dark">Apr</p>
                            </div>
                            <div class="flex-1 flex-col items-center">
                                <div class="w-full chart-bar bg-gradient-to-t from-primary to-primary bg-opacity-70 rounded-t-lg" style="height: 70%;"></div>
                                <p class="text-sm mt-3 text-subtle-light dark:text-subtle-dark">May</p>
                            </div>
                            <div class="flex-1 flex-col items-center">
                                <div class="w-full chart-bar bg-gradient-to-t from-primary to-primary bg-opacity-70 rounded-t-lg" style="height: 20%;"></div>
                                <p class="text-sm mt-3 text-subtle-light dark:text-subtle-dark">Jun</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calendar Section -->
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg">
                        <h3 class="font-bold text-lg mb-6 text-center">September 2025</h3>
                        <div class="grid grid-cols-5 gap-2 text-center mb-6">
                            <div class="p-2 rounded-lg hover:bg-surface-light dark:hover:bg-surface-dark transition-colors cursor-pointer">
                                <p class="text-xs text-subtle-light dark:text-subtle-dark">Mon</p>
                                <p class="font-semibold mt-1">12</p>
                            </div>
                            <div class="p-2 rounded-lg hover:bg-surface-light dark:hover:bg-surface-dark transition-colors cursor-pointer">
                                <p class="text-xs text-subtle-light dark:text-subtle-dark">Tue</p>
                                <p class="font-semibold mt-1">13</p>
                            </div>
                            <div class="p-2 rounded-lg bg-gradient-to-r from-primary to-secondary text-white shadow-md">
                                <p class="text-xs opacity-80">Wed</p>
                                <p class="font-semibold">14</p>
                            </div>
                            <div class="p-2 rounded-lg hover:bg-surface-light dark:hover:bg-surface-dark transition-colors cursor-pointer">
                                <p class="text-xs text-subtle-light dark:text-subtle-dark">Thu</p>
                                <p class="font-semibold mt-1">15</p>
                            </div>
                            <div class="p-2 rounded-lg hover:bg-surface-light dark:hover:bg-surface-dark transition-colors cursor-pointer">
                                <p class="text-xs text-subtle-light dark:text-subtle-dark">Fri</p>
                                <p class="font-semibold mt-1">16</p>
                            </div>
                        </div>
                        <div class="bg-surface-light dark:bg-surface-dark h-24 rounded-lg border border-surface-light dark:border-surface-dark p-4 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-sm text-subtle-light dark:text-subtle-dark">Hari Ini</p>
                                <p class="font-bold text-lg">Rapat Team</p>
                                <p class="text-xs text-subtle-light dark:text-subtle-dark">14:00 - 15:30</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Table Section -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 shadow-lg">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg">Riwayat Absensi</h3>
                        <div class="flex gap-2">
                            <button class="p-2 rounded-lg bg-surface-light dark:bg-surface-dark shadow-sm">
                                <span class="material-icons-outlined text-sm">refresh</span>
                            </button>
                            <button class="p-2 rounded-lg bg-surface-light dark:bg-surface-dark shadow-sm">
                                <span class="material-icons-outlined text-sm">more_vert</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b border-surface-light dark:border-surface-dark">
                                <tr>
                                    <th class="p-3 text-xs font-medium text-subtle-light dark:text-subtle-dark tracking-wider">NAMA</th>
                                    <th class="p-3 text-xs font-medium text-subtle-light dark:text-subtle-dark tracking-wider">TANGGAL</th>
                                    <th class="p-3 text-xs font-medium text-subtle-light dark:text-subtle-dark tracking-wider">JAM MASUK</th>
                                    <th class="p-3 text-xs font-medium text-subtle-light dark:text-subtle-dark tracking-wider">JAM KELUAR</th>
                                    <th class="p-3 text-xs font-medium text-subtle-light dark:text-subtle-dark tracking-wider">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Baris data contoh, Anda bisa generate ini dari database -->
                                <tr class="table-row cursor-pointer">
                                    <td class="p-3 font-medium">Alya Chan</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">25 November</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">09:00</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">17:00</td>
                                    <td class="p-3">
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Valid</span>
                                    </td>
                                </tr>
                                <tr class="table-row cursor-pointer">
                                    <td class="p-3 font-medium">Budi Santoso</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">24 November</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">08:45</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">17:15</td>
                                    <td class="p-3">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Terlambat</span>
                                    </td>
                                </tr>
                                <tr class="table-row cursor-pointer">
                                    <td class="p-3 font-medium">Citra Dewi</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">23 November</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">09:30</td>
                                    <td class="p-3 text-subtle-light dark:text-subtle-dark">17:00</td>
                                    <td class="p-3">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Sakit</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <footer class="text-center text-sm text-subtle-light dark:text-subtle-dark pt-8">
                <p>Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- Karyawan Modal -->
    <div id="karyawan-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-secondary p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Detail Karyawan</h3>
                    <button class="close-modal bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-colors">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Total Karyawan Aktif</span>
                        <span class="font-bold text-lg text-primary">25</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Karyawan Tetap</span>
                        <span class="font-medium text-text-light dark:text-text-dark">20</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Karyawan Kontrak</span>
                        <span class="font-medium text-text-light dark:text-text-dark">5</span>
                    </div>
                    <div class="flex justify-between items-center pt-3">
                        <span class="font-medium text-text-light dark:text-text-dark">Total Karyawan</span>
                        <span class="font-bold text-xl gradient-text">25</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PM Modal -->
    <div id="pm-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-secondary to-accent p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Detail Project Manager</h3>
                    <button class="close-modal bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-colors">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">PM Senior</span>
                        <span class="font-medium text-text-light dark:text-text-dark">2</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">PM Junior</span>
                        <span class="font-medium text-text-light dark:text-text-dark">3</span>
                    </div>
                    <div class="flex justify-between items-center pt-3">
                        <span class="font-medium text-text-light dark:text-text-dark">Total PM</span>
                        <span class="font-bold text-xl gradient-text">5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layanan Modal -->
    <div id="layanan-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-accent to-orange-500 p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Detail Layanan</h3>
                    <button class="close-modal bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-colors">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Layanan Web Development</span>
                        <span class="font-medium text-text-light dark:text-text-dark">10</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Layanan Mobile App</span>
                        <span class="font-medium text-text-light dark:text-text-dark">5</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Layanan UI/UX Design</span>
                        <span class="font-medium text-text-light dark:text-text-dark">5</span>
                    </div>
                    <div class="flex justify-between items-center pt-3">
                        <span class="font-medium text-text-light dark:text-text-dark">Total Layanan</span>
                        <span class="font-bold text-xl gradient-text">20</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keuangan Modal -->
    <div id="keuangan-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Detail Keuangan</h3>
                    <button class="close-modal bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-colors">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Total Pendapatan</span>
                        <span class="font-medium text-text-light dark:text-text-dark">Rp 1.500.000</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Total Pengeluaran</span>
                        <span class="font-medium text-text-light dark:text-text-dark">Rp 500.000</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-light dark:border-surface-dark">
                        <span class="text-subtle-light dark:text-subtle-dark">Pajak</span>
                        <span class="font-medium text-text-light dark:text-text-dark">Rp 100.000</span>
                    </div>
                    <div class="flex justify-between items-center pt-3">
                        <span class="font-medium text-text-light dark:text-text-dark">Total Keuangan</span>
                        <span class="font-bold text-xl gradient-text">Rp 1.000.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Get modal elements
            const pmModal = document.getElementById('pm-modal');
            const layananModal = document.getElementById('layanan-modal');
            
            // Get card elements
            const pmCard = document.getElementById('pm-card');
            const layananCard = document.getElementById('layanan-card');
            
            // Add click event for PM card to show modal
            if (pmCard) {
                pmCard.addEventListener('click', function(e) {
                    e.preventDefault();
                    pmModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Add click event for Layanan card to show modal
            if (layananCard) {
                layananCard.addEventListener('click', function(e) {
                    e.preventDefault();
                    layananModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close modal buttons
            const closeModals = document.querySelectorAll('.close-modal');
            
            // Close modal when close button is clicked
            closeModals.forEach(button => {
                button.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            });
            
            // Close modal when clicking outside of modal content
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            });
            
            // Add click event to table rows
            const tableRows = document.querySelectorAll('.table-row');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    // You can add functionality here to view details of the selected row
                    console.log('Row clicked:', this);
                });
            });
        });
    </script>
</body>
</html>
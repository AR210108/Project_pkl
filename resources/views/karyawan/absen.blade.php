<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Attendance Screen</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#0ea5e9",
                        background: "#ffffff",
                        surface: "#f8fafc",
                        "text-primary": "#1e293b",
                        "text-secondary": "#64748b",
                        "border-color": "#e2e8f0",
                    },
                    fontFamily: {
                        display: ["Roboto", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                        lg: "1rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        /* Custom scrollbar for tables */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-background text-text-secondary">
    <div class="min-h-screen flex flex-col p-3 sm:p-4 md:p-6 lg:p-8">
        @include('karyawan.templet.header')
        
        <main class="flex-grow w-full max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6 text-text-primary">ABSENSI KARYAWAN</h2>
            
            <!-- Time Display - Moved to top -->
            <div class="bg-surface p-4 sm:p-6 rounded-lg text-center border border-border-color mb-6 sm:mb-8">
                <p class="text-3xl sm:text-5xl font-bold tracking-wider text-text-primary">12 : 00 : 00</p>
                <p class="text-sm sm:text-base text-text-secondary mt-2">Senin, 01 Januari 2025</p>
            </div>
            
            <!-- Action Cards -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-surface p-4 sm:p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-4xl sm:text-5xl mb-2 sm:mb-4 text-primary">login</span>
                    <p class="font-semibold text-sm sm:text-base text-text-primary">ABSEN MASUK</p>
                </div>
                <div class="bg-surface p-4 sm:p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-4xl sm:text-5xl mb-2 sm:mb-4 text-primary">logout</span>
                    <p class="font-semibold text-sm sm:text-base text-text-primary">ABSEN PULANG</p>
                </div>
                <div class="bg-surface p-4 sm:p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-4xl sm:text-5xl mb-2 sm:mb-4 text-primary">event_busy</span>
                    <p class="font-semibold text-sm sm:text-base text-text-primary">IZIN</p>
                </div>
                <div class="bg-surface p-4 sm:p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-4xl sm:text-5xl mb-2 sm:mb-4 text-primary">work_outline</span>
                    <p class="font-semibold text-sm sm:text-base text-text-primary">DINAS LUAR</p>
                </div>
            </div>
            
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Left Column - Now only contains Attendance Status -->
                <div class="lg:col-span-1">
                    <!-- Attendance Status -->
                    <div class="bg-surface p-4 sm:p-6 rounded-lg border border-border-color">
                        <h3 class="font-bold text-base sm:text-lg mb-3 sm:mb-4 text-text-primary">Status Absensi</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-primary">schedule</span>
                                <div>
                                    <p class="font-medium text-text-primary">Absen Masuk</p>
                                    <p class="text-text-secondary text-sm">09 : 00</p>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-text-primary">Status: <span class="text-green-600">Tepat Waktu</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Attendance History -->
                <div class="lg:col-span-2 bg-surface p-4 sm:p-6 rounded-lg border border-border-color">
                    <h3 class="font-bold text-base sm:text-lg mb-3 sm:mb-4 text-text-primary">Riwayat Absensi</h3>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-text-primary">
                                    <th class="p-2 font-medium whitespace-nowrap">No</th>
                                    <th class="p-2 font-medium whitespace-nowrap">Tanggal</th>
                                    <th class="p-2 font-medium whitespace-nowrap">Jam Masuk</th>
                                    <th class="p-2 font-medium whitespace-nowrap">Jam Pulang</th>
                                    <th class="p-2 font-medium whitespace-nowrap">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-text-secondary">
                                <tr class="border-t border-border-color">
                                    <td class="p-2 text-text-primary">1</td>
                                    <td class="p-2 whitespace-nowrap">31 Des 2024</td>
                                    <td class="p-2 whitespace-nowrap">08:55</td>
                                    <td class="p-2 whitespace-nowrap">17:05</td>
                                    <td class="p-2">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs whitespace-nowrap">Tepat Waktu</span>
                                    </td>
                                </tr>
                                <tr class="border-t border-border-color">
                                    <td class="p-2 text-text-primary">2</td>
                                    <td class="p-2 whitespace-nowrap">30 Des 2024</td>
                                    <td class="p-2 whitespace-nowrap">09:10</td>
                                    <td class="p-2 whitespace-nowrap">17:00</td>
                                    <td class="p-2">
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs whitespace-nowrap">Terlambat</span>
                                    </td>
                                </tr>
                                <tr class="border-t border-border-color">
                                    <td class="p-2 text-text-primary">3</td>
                                    <td class="p-2 whitespace-nowrap">29 Des 2024</td>
                                    <td class="p-2 whitespace-nowrap">-</td>
                                    <td class="p-2 whitespace-nowrap">-</td>
                                    <td class="p-2">
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs whitespace-nowrap">Izin</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="w-full max-w-7xl mx-auto mt-8 sm:mt-12 text-center text-xs sm:text-sm text-text-secondary">
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Digital Agency Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3730a3",
                        "background-light": "#f3f4f6",
                        "background-dark": "#1f2937",
                        "card-light": "#ffffff",
                        "card-dark": "#374151",
                        "text-light": "#111827",
                        "text-dark": "#f9fafb",
                        "subtext-light": "#6b7280",
                        "subtext-dark": "#d1d5db",
                        "border-light": "#e5e7eb",
                        "border-dark": "#4b5563",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "1rem",
                    },
                },
            },
        };
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="container mx-auto p-4 md:p-8">
        <!-- Include header template -->
        @include('pemilik/template/header')

        <main class="space-y-6 md:space-y-8">
           <section class="bg-white dark:bg-gray-800 rounded-lg p-6 md:p-8 lg:p-12 shadow-sm">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-3 md:mb-4">HALLO, NAMA OWNERS
                    </h2>
                    <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 mb-6 md:mb-8">
                        Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau
                                jasanya
                                secara online melalui berbagai layanan digital.
                    </p>
                    <a href="/karyawan/absensi"
                        class="bg-primary text-white px-6 py-2 md:px-8 md:py-3 rounded-lg font-semibold hover:bg-blue-600 transition-transform transform hover:scale-105 shadow-lg inline-block text-sm md:text-base">
                        OWNERS
                    </a>
                </div>
            </section>
            
            <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-200 dark:bg-gray-500 p-3 rounded-md">
                        <span class="material-icons text-subtext-light dark:text-subtext-dark">groups</span>
                    </div>
                    <div>
                        <p class="text-xs text-subtext-light dark:text-subtext-dark">Kehadiran Karyawan</p>
                        <p class="text-xl md:text-2xl font-bold">50</p>
                    </div>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-200 dark:bg-gray-500 p-3 rounded-md">
                        <span class="material-icons text-subtext-light dark:text-subtext-dark">design_services</span>
                    </div>
                    <div>
                        <p class="text-xs text-subtext-light dark:text-subtext-dark">Jumlah Layanan</p>
                        <p class="text-xl md:text-2xl font-bold">10</p>
                    </div>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-200 dark:bg-gray-500 p-3 rounded-md">
                        <span class="material-icons text-subtext-light dark:text-subtext-dark">arrow_downward</span>
                    </div>
                    <div>
                        <p class="text-xs text-subtext-light dark:text-subtext-dark">Total Pemasukan</p>
                        <p class="text-lg md:text-xl font-bold">1.000.000</p>
                    </div>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-200 dark:bg-gray-500 p-3 rounded-md">
                        <span class="material-icons text-subtext-light dark:text-subtext-dark">arrow_upward</span>
                    </div>
                    <div>
                        <p class="text-xs text-subtext-light dark:text-subtext-dark">Total Pengeluaran</p>
                        <p class="text-lg md:text-xl font-bold">500.000</p>
                    </div>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-200 dark:bg-gray-500 p-3 rounded-md">
                        <span
                            class="material-icons text-subtext-light dark:text-subtext-dark">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-xs text-subtext-light dark:text-subtext-dark">Total Keuntungan</p>
                        <p class="text-lg md:text-xl font-bold">500.000</p>
                    </div>
                </div>
            </section>
            
            <section class="bg-card-light dark:bg-card-dark p-4 md:p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold">Grafik Keuangan</h3>
                    <button
                        class="bg-gray-200 dark:bg-gray-600 p-2 rounded-full text-subtext-light dark:text-subtext-dark hover:opacity-80 transition-opacity">
                        <span class="material-icons">open_in_new</span>
                    </button>
                </div>
                
                <!-- Grafik untuk Desktop (Vertikal) -->
                <div class="hidden md:block">
                    <div class="flex items-end h-64 space-x-4">
                        <div class="flex flex-col justify-between h-full text-xs text-subtext-light dark:text-subtext-dark pr-2 border-r border-border-light dark:border-border-dark">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div class="w-full h-full flex items-end justify-around">
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Jan</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 50%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Feb</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 95%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Mar</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 82%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Apr</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">May</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 25%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Jun</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 20%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Jul</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 40%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Aug</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 28%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Sep</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 83%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Oct</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 88%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Nov</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 45%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Dec</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik untuk Mobile (Sama seperti Desktop, hanya lebih kecil) -->
                <div class="md:hidden overflow-x-auto pb-4">
                    <div class="flex items-end h-48 min-w-max">
                        <div class="flex flex-col justify-between h-full text-xs text-subtext-light dark:text-subtext-dark pr-2 border-r border-border-light dark:border-border-dark">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div class="w-full h-full flex items-end justify-around px-2">
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Jan</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 50%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Feb</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 95%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Mar</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 82%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Apr</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">May</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 25%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Jun</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 20%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Jul</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 40%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Aug</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 28%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Sep</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 83%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Oct</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 88%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Nov</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="w-full bg-gray-300 dark:bg-gray-500 rounded-t-md" style="height: 45%;"></div>
                                <span class="text-xs mt-2 text-subtext-light dark:text-subtext-dark">Dec</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Indikator scroll untuk mobile -->
                <div class="md:hidden text-center text-xs text-subtext-light dark:text-subtext-dark mt-2">
                    <span class="material-icons text-sm">swipe</span> Geser untuk melihat grafik lengkap
                </div>
            </section>
        </main>
        
        <footer class="mt-8 md:mt-12 bg-card-light dark:bg-card-dark text-center py-3 md:py-4 rounded-lg shadow-sm">
            <p class="text-xs md:text-sm text-subtext-light dark:text-subtext-dark">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

</body>

</html>
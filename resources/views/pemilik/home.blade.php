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
                        primary: "#0f172a", // Biru sangat tua mendekati hitam
                        "background-light": "#ffffff", // Putih untuk background utama
                        "background-dark": "#f8fafc", // Putih sangat terang untuk mode gelap
                        "card-light": "#111827", // Hitam untuk kartu
                        "card-dark": "#1f2937", // Abu-abu untuk kartu mode gelap
                        "text-light": "#111827", // Hitam untuk teks
                        "text-dark": "#f9fafb", // Putih terang untuk teks mode gelap
                        "subtext-light": "#6b7280", // Abu-abu untuk subteks mode terang
                        "subtext-dark": "#d1d5db", // Abu-abu terang untuk subteks mode gelap
                        "border-light": "#e5e7eb", // Abu-abu terang untuk border
                        "border-dark": "#4b5563", // Abu-abu untuk border mode gelap
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
    <style>
        /* Custom styles for improved appearance */
        .gradient-primary {
            background: linear-gradient(135deg, #000000, #111827);
        }
        
        .gradient-dark {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }
        
        .gradient-subtle {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }
        
        /* Button hover effects */
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-primary:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #000000, #111827);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease;
        }
        
        .btn-primary:hover:before {
            opacity: 1;
        }
        
        /* Card hover effects */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Chart bar animation */
        .chart-bar {
            transition: height 0.5s ease-in-out;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="container mx-auto p-4 md:p-8">
        <!-- Include header template -->
        @include('pemilik/template/header')

        <main class="space-y-6 md:space-y-8">
           <section class="gradient-primary rounded-2xl shadow-lg relative overflow-hidden p-6 md:p-8 lg:p-12">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
                
                <div class="max-w-4xl mx-auto relative z-10">
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4">HALLO, NAMA OWNERS
                    </h2>
                    <p class="text-sm md:text-base text-white/90 mb-6 md:mb-8">
                        Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau
                                jasanya
                                secara online melalui berbagai layanan digital.
                    </p>
                    <a href="/karyawan/absensi"
                        class="btn-primary bg-white text-black px-6 py-2 md:px-8 md:py-3 rounded-lg font-semibold hover:bg-gray-100 transition-transform transform hover:scale-105 shadow-lg inline-block text-sm md:text-base">
                        OWNERS
                    </a>
                </div>
            </section>
            
            <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">groups</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Kehadiran Karyawan</p>
                        <p class="text-xl md:text-2xl font-bold text-white">50</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">design_services</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Jumlah Layanan</p>
                        <p class="text-xl md:text-2xl font-bold text-white">10</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">arrow_downward</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pemasukan</p>
                        <p class="text-lg md:text-xl font-bold text-white">1.000.000</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">arrow_upward</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pengeluaran</p>
                        <p class="text-lg md:text-xl font-bold text-white">500.000</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Keuntungan</p>
                        <p class="text-lg md:text-xl font-bold text-white">500.000</p>
                    </div>
                </div>
            </section>
            
            <section class="gradient-subtle p-4 md:p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-black">Grafik Keuangan</h3>
                    <button
                        class="bg-gray-200 p-2 rounded-full text-black hover:bg-gray-300 transition-colors">
                        <span class="material-icons">open_in_new</span>
                    </button>
                </div>
                
                <!-- Grafik untuk Desktop (Vertikal) -->
                <div class="hidden md:block">
                    <div class="flex items-end h-64 space-x-4">
                        <div class="flex flex-col justify-between h-full text-xs text-gray-600 pr-2 border-r border-gray-300">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div class="w-full h-full flex items-end justify-around">
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Jan</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 50%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Feb</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 95%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Mar</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 82%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Apr</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-gray-600">May</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 25%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Jun</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 20%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Jul</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 40%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Aug</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 28%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Sep</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 83%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Oct</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 88%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Nov</span>
                            </div>
                            <div class="flex flex-col items-center w-1/12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 45%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Dec</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik untuk Mobile (Sama seperti Desktop, hanya lebih kecil) -->
                <div class="md:hidden overflow-x-auto pb-4">
                    <div class="flex items-end h-48 min-w-max">
                        <div class="flex flex-col justify-between h-full text-xs text-gray-600 pr-2 border-r border-gray-300">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div class="w-full h-full flex items-end justify-around px-2">
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Jan</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 50%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Feb</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 95%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Mar</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 82%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Apr</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 80%;"></div>
                                <span class="text-xs mt-2 text-gray-600">May</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 25%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Jun</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 20%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Jul</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 40%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Aug</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 28%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Sep</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 83%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Oct</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 88%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Nov</span>
                            </div>
                            <div class="flex flex-col items-center w-12">
                                <div class="chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md" style="height: 45%;"></div>
                                <span class="text-xs mt-2 text-gray-600">Dec</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Indikator scroll untuk mobile -->
                <div class="md:hidden text-center text-xs text-gray-600 mt-2">
                    <span class="material-icons text-sm">swipe</span> Geser untuk melihat grafik lengkap
                </div>
            </section>
        </main>
        
        <footer class="mt-8 md:mt-12 gradient-dark text-center py-3 md:py-4 rounded-lg shadow-sm">
            <p class="text-xs md:text-sm text-gray-700">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <script>
        // Animate chart bars on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chartBars = document.querySelectorAll('.chart-bar');
            
            // Set initial height to 0
            chartBars.forEach(bar => {
                const targetHeight = bar.style.height;
                bar.style.height = '0';
                
                // Animate to target height after a short delay
                setTimeout(() => {
                    bar.style.height = targetHeight;
                }, 100);
            });
        });
    </script>
</body>

</html>
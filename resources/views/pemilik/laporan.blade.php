<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Financial &amp; Order List</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "hsl(210, 100%, 50%)",
                        "background-light": "#ffffff",
                        "background-dark": "#121212",
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
            background-image: url(https://lh3.googleusercontent.com/aida-public/AB6AXuDZ2q9LDS0-IBkN-a0BaIehx2IwAClw39O1UaRMC7EdeKeutz_KItlCqiW1Xi_NCFfYOvMTHt8yOGDnPTMWscDJV6N2AB0WgrleBuxYwwLX7ptqAeFAd1SyqwH5wK396IDP06SWApqkDZEy-9-b_df56wtD_APtdwsusbdGj2VqBSqm6n3SZZj6W02HJ2N0jf6d4HP0H4EumjyczQ5XjDjV3ORKHIZBT67LQb6RT11X-yrbldNQCmypJ2S1RQo4GzexcNHQMVCBrluJ);
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        @include('pemilik/template/header')
        <main class="mt-6 sm:mt-8">
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <div class="bg-gray-200 dark:bg-gray-800 p-4 sm:p-6 rounded-lg flex items-center space-x-3 sm:space-x-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-400 dark:bg-gray-600 rounded-lg flex-shrink-0"></div>
                    <div>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Total Pemasukan</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">1.000.000</p>
                    </div>
                </div>
                <div class="bg-gray-200 dark:bg-gray-800 p-4 sm:p-6 rounded-lg flex items-center space-x-3 sm:space-x-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-400 dark:bg-gray-600 rounded-lg flex-shrink-0"></div>
                    <div>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Total Pengeluaran</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">500.000</p>
                    </div>
                </div>
                <div class="bg-gray-200 dark:bg-gray-800 p-4 sm:p-6 rounded-lg flex items-center space-x-3 sm:space-x-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-400 dark:bg-gray-600 rounded-lg flex-shrink-0"></div>
                    <div>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Total Keuangan</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">500.000</p>
                    </div>
                </div>
            </section>
            <section class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <button
                    class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 sm:px-6 py-2 rounded-lg font-medium text-sm sm:text-base hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors w-full sm:w-auto">
                    Export PDF
                </button>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <select
                            class="w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 pl-4 py-2 rounded-lg border-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark focus:ring-primary text-sm sm:text-base">
                            <option>Filter Kategori</option>
                        </select>
                    </div>
                    <div class="relative w-full sm:w-auto">
                        <select
                            class="w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 pl-4 py-2 rounded-lg border-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark focus:ring-primary text-sm sm:text-base">
                            <option>Filter Bulan</option>
                        </select>
                    </div>
                </div>
            </section>
            <section class="mt-6 sm:mt-8 bg-gray-200 dark:bg-gray-800 p-4 sm:p-6 md:p-8 rounded-2xl">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4 sm:mb-6">Order List</h2>
                <div class="overflow-x-auto">
                    <div class="min-w-[900px] sm:min-w-[1000px]">
                        <div class="bg-gray-300 dark:bg-gray-700 rounded-lg">
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
                        <div class="mt-4 space-y-4">
                            <div
                                class="grid grid-cols-7 items-center py-4 border-b border-gray-300 dark:border-gray-700 text-sm gap-4">
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">1</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="Web Design">Web Design</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">500.000</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="Client A">Client A</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">250.000</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">250.000</div>
                                <div class="col-span-1 text-green-500 px-3">Lunas</div>
                            </div>
                            <div
                                class="grid grid-cols-7 items-center py-4 border-b border-gray-300 dark:border-gray-700 text-sm gap-4">
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">2</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="SEO">SEO</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">300.000</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="Client B">Client B</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">150.000</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">-</div>
                                <div class="col-span-1 text-yellow-500 px-3">DP</div>
                            </div>
                            <div class="grid grid-cols-7 items-center py-4 text-sm gap-4">
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">3</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="Marketing">Marketing</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">200.000</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3 truncate" title="Client C">Client C</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">-</div>
                                <div class="col-span-1 text-gray-800 dark:text-gray-200 px-3">-</div>
                                <div class="col-span-1 text-red-500 px-3">Belum Bayar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer class="mt-8 sm:mt-12 bg-gray-200 dark:bg-gray-800 py-4 sm:py-6 rounded-2xl">
            <p class="text-center text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

</body>

</html>
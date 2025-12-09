<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Attendance Dashboard</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#333333", // Using a dark grey as primary for the brand text color
                        "background-light": "#ffffff",
                        "background-dark": "#121212",
                        "card-light": "#e5e7eb", // light gray
                        "card-dark": "#2d3748", // darker gray
                        "text-light": "#1f2937", // almost black
                        "text-dark": "#e2e8f0", // light gray/white
                        "border-light": "#d1d5db",
                        "border-dark": "#4a5568",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem", // 12px
                    },
                },
            },
        };
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
       @include('pemilik/template/header')
        <main class="mt-6 sm:mt-8">
            <div class="grid grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg text-center">
                    <p class="text-sm sm:text-md">Kehadiran</p>
                    <p class="text-2xl sm:text-3xl font-bold mt-1">500</p>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg text-center">
                    <p class="text-sm sm:text-md">Tidak Hadir</p>
                    <p class="text-2xl sm:text-3xl font-bold mt-1">50</p>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg text-center">
                    <p class="text-sm sm:text-md">Izin</p>
                    <p class="text-2xl sm:text-3xl font-bold mt-1">50</p>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg text-center">
                    <p class="text-sm sm:text-md">Cuti</p>
                    <p class="text-2xl sm:text-3xl font-bold mt-1">10</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                <div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h2 class="text-lg sm:text-xl font-semibold">Belum Absen</h2>
                        <div class="w-full sm:w-1/2">
                            <input
                                class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:ring-primary focus:border-primary"
                                placeholder="Search" type="text" />
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <div class="border border-border-light dark:border-border-dark rounded-lg min-w-[500px]">
                            <div
                                class="grid grid-cols-5 text-xs font-semibold text-center uppercase p-3 bg-gray-300 dark:bg-gray-700">
                                <div>No</div>
                                <div>Nama</div>
                                <div>Jam Masuk</div>
                                <div>Jam Masuk</div>
                                <div>Status</div>
                            </div>
                            <div class="space-y-2 p-3 text-sm">
                                <div
                                    class="grid grid-cols-5 items-center border-b border-border-light dark:border-border-dark py-3">
                                    <div class="text-center">1</div>
                                    <div class="text-center truncate px-1" title="Nama">Nama</div>
                                    <div class="text-center">-</div>
                                    <div class="text-center">-</div>
                                    <div class="text-center">-</div>
                                </div>
                                <div
                                    class="grid grid-cols-5 items-center border-b border-border-light dark:border-border-dark py-3">
                                    <div class="text-center">2</div>
                                    <div class="text-center truncate px-1" title="Nama">Nama</div>
                                    <div class="text-center">-</div>
                                    <div class="text-center">-</div>
                                    <div class="text-center">-</div>
                                </div>
                                <div class="grid grid-cols-5 items-center pt-3">
                                    <div class="text-center">3</div>
                                    <div class="text-center truncate px-1" title="Nama">Nama</div>
                                    <div class="text-center">-</div>
                                    <div class="text-center">-</div>
                                    <div class="text-center">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-card-light dark:bg-card-dark p-4 sm:p-6 rounded-lg">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h2 class="text-lg sm:text-xl font-semibold">Sudah Absen</h2>
                        <div class="w-full sm:w-1/2">
                            <input
                                class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark focus:ring-primary focus:border-primary"
                                placeholder="Search" type="text" />
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <div class="border border-border-light dark:border-border-dark rounded-lg min-w-[500px]">
                            <div
                                class="grid grid-cols-5 text-xs font-semibold text-center uppercase p-3 bg-gray-300 dark:bg-gray-700">
                                <div>No</div>
                                <div>Nama</div>
                                <div>Jam Masuk</div>
                                <div>Jam Keluar</div>
                                <div>Status</div>
                            </div>
                            <div class="space-y-2 p-3 text-sm">
                                <div
                                    class="grid grid-cols-5 items-center border-b border-border-light dark:border-border-dark py-3">
                                    <div class="text-center">1</div>
                                    <div class="text-center truncate px-1" title="Nama">Nama</div>
                                    <div class="text-center">08:00</div>
                                    <div class="text-center">17:00</div>
                                    <div class="text-center">Hadir</div>
                                </div>
                                <div
                                    class="grid grid-cols-5 items-center border-b border-border-light dark:border-border-dark py-3">
                                    <div class="text-center">2</div>
                                    <div class="text-center truncate px-1" title="Nama">Nama</div>
                                    <div class="text-center">08:05</div>
                                    <div class="text-center">17:01</div>
                                    <div class="text-center">Hadir</div>
                                </div>
                                <div class="grid grid-cols-5 items-center pt-3">
                                    <div class="text-center">3</div>
                                    <div class="text-center truncate px-1" title="Nama">Nama</div>
                                    <div class="text-center">07:55</div>
                                    <div class="text-center">17:05</div>
                                    <div class="text-center">Hadir</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="mt-8 sm:mt-12 bg-card-light dark:bg-card-dark text-center py-3 sm:py-4 rounded-lg">
            <p class="text-xs sm:text-sm text-text-light dark:text-text-dark">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

</body>

</html>
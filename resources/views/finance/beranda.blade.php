<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // A sample primary, not used in this grayscale design
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
                        "sidebar-light": "#e5e7eb",
                        "sidebar-dark": "#1f2937",
                        "card-light": "#f3f4f6",
                        "card-dark": "#374151",
                        "text-light": "#111827",
                        "text-dark": "#f9fafb",
                        "text-muted-light": "#6b7280",
                        "text-muted-dark": "#9ca3af",
                        "border-light": "#e5e7eb",
                        "border-dark": "#4b5563"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "1rem", // 16px
                    },
                },
            },
        };
    </script>
    <style>
        .material-icons {
            font-size: 24px;
            vertical-align: middle;
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="flex min-h-screen">
        @include('finance.templet.sider')
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-8">
                <h2 class="text-4xl font-bold mb-8">Beranda</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-card-light dark:bg-card-dark rounded-DEFAULT p-5 flex items-center">
                        <div class="w-12 h-12 bg-gray-300 dark:bg-gray-500 rounded-lg mr-4"></div>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Pemasukan</p>
                            <p class="text-xl font-bold">$20.000.000</p>
                        </div>
                    </div>
                    <div class="bg-card-light dark:bg-card-dark rounded-DEFAULT p-5 flex items-center">
                        <div class="w-12 h-12 bg-gray-300 dark:bg-gray-500 rounded-lg mr-4"></div>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Pengeluaran</p>
                            <p class="text-xl font-bold">$10.000.000</p>
                        </div>
                    </div>
                    <div class="bg-card-light dark:bg-card-dark rounded-DEFAULT p-5 flex items-center">
                        <div class="w-12 h-12 bg-gray-300 dark:bg-gray-500 rounded-lg mr-4"></div>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Jumlah Layanan</p>
                            <p class="text-xl font-bold">100</p>
                        </div>
                    </div>
                    <div class="bg-card-light dark:bg-card-dark rounded-DEFAULT p-5 flex items-center">
                        <div class="w-12 h-12 bg-gray-300 dark:bg-gray-500 rounded-lg mr-4"></div>
                        <div>
                            <p class="text-sm text-text-muted-light dark:text-text-muted-dark">Total Keuangan</p>
                            <p class="text-xl font-bold">$10.000.000</p>
                        </div>
                    </div>
                </div>
                <div class="bg-card-light dark:bg-card-dark rounded-DEFAULT p-6">
                    <h3 class="text-lg font-semibold mb-4">Order List</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <th class="p-3 font-semibold">NO</th>
                                    <th class="p-3 font-semibold">LAYANAN</th>
                                    <th class="p-3 font-semibold">HARGA</th>
                                    <th class="p-3 font-semibold">KLIEN</th>
                                    <th class="p-3 font-semibold text-center">PEMBAYARAN AWAL</th>
                                    <th class="p-3 font-semibold">PELUNASAN</th>
                                    <th class="p-3 font-semibold">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="p-3 h-12"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark">
                                    <td class="p-3 h-12"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                </tr>
                                <tr>
                                    <td class="p-3 h-12"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                    <td class="p-3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <footer
                class="text-center p-4 bg-gray-300 dark:bg-gray-700 text-text-muted-light dark:text-text-muted-dark text-sm">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

</body>

</html>

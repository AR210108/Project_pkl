<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Order Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // A generic primary color, as none is specified
                        "background-light": "#ffffff",
                        "background-dark": "#18181b",
                        "surface-light": "#f3f4f6",
                        "surface-dark": "#27272a",
                        "border-light": "#e5e7eb",
                        "border-dark": "#3f3f46",
                        "text-primary-light": "#111827",
                        "text-primary-dark": "#f4f4f5",
                        "text-secondary-light": "#6b7280",
                        "text-secondary-dark": "#a1a1aa",
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-icons {
            font-size: 20px;
            vertical-align: middle;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark font-display">
    <div class="flex h-screen">
        @include('finance/templet/sider')
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-8 overflow-y-auto">
                <h2 class="text-3xl font-bold mb-8">Data Order</h2>
                <div class="flex justify-between items-center mb-6">
                    <button
                        class="bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark px-6 py-2.5 rounded-full shadow-sm hover:bg-border-light dark:hover:bg-border-dark transition-colors font-medium">
                        + Tambah
                    </button>
                    <div class="flex items-center space-x-4">
                        <button
                            class="flex items-center space-x-2 bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark px-6 py-2.5 rounded-full shadow-sm hover:bg-border-light dark:hover:bg-border-dark transition-colors font-medium">
                            <span class="material-icons">search</span>
                            <span>Search</span>
                        </button>
                        <button
                            class="bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark px-6 py-2.5 rounded-full shadow-sm hover:bg-border-light dark:hover:bg-border-dark transition-colors font-medium">
                            Filter
                        </button>
                    </div>
                </div>
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-2xl shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-text-primary-light dark:text-text-primary-dark">Order
                        List</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead
                                class="text-text-secondary-light dark:text-text-secondary-dark uppercase font-medium">
                                <tr>
                                    <th class="p-4">NO</th>
                                    <th class="p-4">LAYANAN</th>
                                    <th class="p-4">HARGA</th>
                                    <th class="p-4">KLIEN</th>
                                    <th class="p-4">PEMBAYARAN AWAL</th>
                                    <th class="p-4">PELUNASAN</th>
                                    <th class="p-4">STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="text-text-primary-light dark:text-text-primary-dark">
                                <tr class="border-t border-border-light dark:border-border-dark">
                                    <td class="p-4" colspan="7">
                                        <div class="h-8"></div>
                                    </td>
                                </tr>
                                <tr class="border-t border-border-light dark:border-border-dark">
                                    <td class="p-4" colspan="7">
                                        <div class="h-8"></div>
                                    </td>
                                </tr>
                                <tr class="border-t border-border-light dark:border-border-dark">
                                    <td class="p-4" colspan="7">
                                        <div class="h-8"></div>
                                    </td>
                                </tr>
                                <tr class="border-t border-border-light dark:border-border-dark">
                                    <td class="p-4" colspan="7">
                                        <div class="h-8"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
            <footer class="bg-gray-500 dark:bg-gray-800 text-white text-center py-3 text-sm flex-shrink-0">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

</body>

</html>

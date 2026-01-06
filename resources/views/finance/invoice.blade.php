<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Invoice Dashboard</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#111827",
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
                        "surface-light": "#e5e7eb",
                        "surface-dark": "#1f2937",
                        "accent-light": "#d1d5db",
                        "accent-dark": "#374151",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        sans: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
</head>

<body class="font-sans text-gray-900 bg-background-light dark:bg-background-dark dark:text-gray-100 flex flex-col h-screen overflow-hidden selection:bg-gray-300 dark:selection:bg-gray-600">

<div class="flex flex-1 overflow-hidden">

    <!-- SIDEBAR -->
    <body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')
        

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto bg-white dark:bg-gray-900 p-8 pb-4 relative flex flex-col">
        <h2 class="text-4xl font-bold mb-8 text-black dark:text-white">Invoice</h2>

        <div class="flex flex-col md:flex-row gap-4 mb-8 justify-between items-start md:items-center">
            <button class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-black dark:text-white px-6 py-3 rounded-full font-bold flex items-center gap-2 transition-colors">
                <span class="material-icons-outlined">add</span>
                Buat Invoice
            </button>

            <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-black dark:text-white">
                        <span class="material-icons-outlined text-2xl font-bold">search</span>
                    </span>
                    <input type="text" placeholder="Search..."
                           class="w-full bg-gray-300 dark:bg-gray-700 text-black dark:text-white placeholder-black dark:placeholder-gray-300 rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-500 font-bold border-none" />
                </div>

                <button class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-black dark:text-white px-8 py-3 rounded-full font-bold transition-colors">
                    Filter
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="flex-1 overflow-x-auto rounded-lg">
            <table class="w-full min-w-[1200px] border-collapse">
                <thead>
                    <tr class="bg-[#a3a3a3] dark:bg-gray-600 text-black dark:text-white text-xs font-bold uppercase tracking-wider">
                        <th class="p-3 text-left rounded-tl-lg">No</th>
                        <th class="p-3 text-left">Nama Perusahaan</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">No Order</th>
                        <th class="p-3 text-left">Klien</th>
                        <th class="p-3 text-left">Alamat</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-left">Harga</th>
                        <th class="p-3 text-left">Qty</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Pajak</th>
                        <th class="p-3 text-left">Metode Bayar</th>
                        <th class="p-3 text-left rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm font-medium">
                    <tr class="bg-gray-200 dark:bg-gray-800 border-b border-white dark:border-gray-900 h-16">
                        <td class="p-3">1.</td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                    </tr>

                    <tr class="bg-gray-200 dark:bg-gray-800 border-b border-white dark:border-gray-900 h-16">
                        <td class="p-3">2.</td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                    </tr>

                    <tr class="bg-gray-200 dark:bg-gray-800 border-b border-white dark:border-gray-900 h-16">
                        <td class="p-3">3.</td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                    </tr>

                    <tr class="bg-gray-200 dark:bg-gray-800 rounded-b-lg h-16">
                        <td class="p-3 rounded-bl-lg">4.</td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3 rounded-br-lg"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="h-8"></div>
    </main>
</div>

<footer class="bg-[#a3a3a3] dark:bg-gray-700 py-4 px-6 text-center text-black dark:text-white font-medium z-20">
    Copyright ©2025 by digicity.id
</footer>

</body>
</html>

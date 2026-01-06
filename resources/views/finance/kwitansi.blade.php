<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Kwitansi Dashboard</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1a1a1a",
                        "background-light": "#ffffff",
                        "background-dark": "#121212",
                        "gray-light-1": "#E5E5E5",
                        "gray-light-2": "#D4D4D4",
                        "gray-dark-1": "#A3A3A3",
                        "text-primary": "#000000",
                        "text-secondary": "#4B5563",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>

    <!-- Custom Scrollbar -->
    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-text-primary dark:text-gray-100 min-h-screen flex flex-col font-body">

    <div class="flex flex-1 overflow-hidden h-screen">

        <!-- Sidebar -->
         <body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-white dark:bg-background-dark p-8 md:p-12">

            <header class="mb-10">
                <h2 class="text-4xl font-semibold text-black dark:text-white">
                    Kwitansi
                </h2>
            </header>

            <!-- Actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">

                <button class="bg-gray-light-2 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-black dark:text-white font-bold py-3 px-6 rounded-2xl flex items-center min-w-[200px] justify-center shadow-sm transition-colors">
                    <span class="material-icons mr-2 text-2xl">add</span>
                    Buat Kwitansi
                </button>

                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <div class="relative flex-grow md:flex-grow-0">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="material-icons text-black dark:text-gray-300 text-3xl">search</span>
                        </span>
                        <input
                            type="text"
                            placeholder="Search..."
                            class="bg-gray-light-2 dark:bg-zinc-700 text-black dark:text-white placeholder-black dark:placeholder-gray-300 rounded-2xl py-3 pl-14 pr-4 w-full md:w-80 border-none focus:ring-2 focus:ring-gray-400 font-bold text-lg"
                        />
                    </div>

                    <button class="bg-gray-light-2 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-black dark:text-white font-bold py-3 px-10 rounded-2xl shadow-sm transition-colors text-lg">
                        Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl">
                <table class="w-full min-w-max text-left border-collapse">

                    <thead>
                        <tr class="bg-gray-dark-1 dark:bg-zinc-600 text-xs uppercase tracking-wider text-black dark:text-white font-bold h-12">
                            <th class="px-4 py-3 rounded-tl-xl">No</th>
                            <th class="px-4 py-3">Nama<br />Perusahaan</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">No Order</th>
                            <th class="px-4 py-3">Klien</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Sub Total</th>
                            <th class="px-4 py-3">Fee Maintenence</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3 rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-300 dark:divide-zinc-700 border-b border-gray-300 dark:border-zinc-700">
                        <tr class="bg-gray-light-1 dark:bg-zinc-800 h-24">
                            <td class="px-4 py-4 font-bold">1.</td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                            <td class="px-4 py-4"></td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-dark-1 dark:bg-zinc-950 py-4 px-8 z-10 relative">
        <p class="text-black dark:text-gray-300 font-medium text-lg ml-12 pl-4">
            Copyright ©2025 by digicity.id
        </p>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengeluaran Dashboard</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons+Round"
        rel="stylesheet"
    />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000",
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
                        "sidebar-light": "#e5e5e5",
                        "sidebar-dark": "#1f2937",
                        "element-grey": "#d4d4d4",
                        "element-grey-dark": "#374151",
                        "table-header": "#a3a3a3",
                        "table-header-dark": "#4b5563",
                        "table-row": "#e5e5e5",
                        "table-row-dark": "#1f2937",
                        "footer-bg": "#a8a8a8",
                        "footer-bg-dark": "#0f172a",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">

    <div class="flex flex-1 overflow-hidden relative">

        <!-- Sidebar -->
           <body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')



        <!-- Main Content -->
        <main class="flex-1 p-8 md:p-12 overflow-y-auto bg-white dark:bg-gray-900">
            <h2 class="text-3xl font-bold mb-10 text-black dark:text-white">
                Pengeluaran
            </h2>

            <!-- Action Bar -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                <button class="bg-element-grey dark:bg-element-grey-dark hover:bg-gray-300 dark:hover:bg-gray-600 text-black dark:text-white font-bold py-3 px-6 rounded-2xl flex items-center gap-2 transition-colors shadow-sm">
                    <span class="material-icons-round text-xl">add</span>
                    Pengeluaran
                </button>

                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <div class="relative w-full sm:w-auto">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="material-icons-round text-black dark:text-white text-xl">search</span>
                        </span>
                        <input
                            type="text"
                            placeholder="Search..."
                            class="bg-element-grey dark:bg-element-grey-dark text-black dark:text-white placeholder-black dark:placeholder-gray-300 font-bold text-sm rounded-2xl border-none py-3 pl-12 pr-6 w-full sm:w-80 focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 shadow-sm"
                        />
                    </div>

                    <button class="bg-element-grey dark:bg-element-grey-dark hover:bg-gray-300 dark:hover:bg-gray-600 text-black dark:text-white font-bold py-3 px-10 rounded-2xl transition-colors shadow-sm text-sm">
                        Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-lg">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-table-header dark:bg-table-header-dark text-xs uppercase tracking-wide text-black dark:text-white font-bold">
                            <th class="p-4 rounded-tl-lg w-16">NO</th>
                            <th class="p-4">NAMA</th>
                            <th class="p-4">TANGGAL</th>
                            <th class="p-4">KATEGORI</th>
                            <th class="p-4">DESKRIPSI</th>
                            <th class="p-4">PEMASUKAN</th>
                            <th class="p-4 rounded-tr-lg text-center w-24">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="text-sm font-medium">
                        <tr class="bg-table-row dark:bg-table-row-dark border-b border-gray-400 dark:border-gray-700">
                            <td class="p-5 h-20 align-middle text-black dark:text-white font-bold">1.</td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle text-center"></td>
                        </tr>

                        <tr class="bg-table-row dark:bg-table-row-dark border-b border-gray-400 dark:border-gray-700">
                            <td class="p-5 h-20 align-middle text-black dark:text-white font-bold">2.</td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle text-center"></td>
                        </tr>

                        <tr class="bg-table-row dark:bg-table-row-dark border-b border-gray-400 dark:border-gray-700">
                            <td class="p-5 h-20 align-middle text-black dark:text-white font-bold">3.</td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle text-center"></td>
                        </tr>

                        <tr class="bg-table-row dark:bg-table-row-dark rounded-b-lg">
                            <td class="p-5 h-20 align-middle text-black dark:text-white font-bold rounded-bl-lg">4.</td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle"></td>
                            <td class="p-5 h-20 align-middle text-center rounded-br-lg"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-footer-bg dark:bg-footer-bg-dark py-4 px-8 w-full shrink-0 z-20">
        <p class="text-sm font-bold text-black dark:text-gray-400">
            Copyright ©2025 by digicity.id
        </p>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pemasukan</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined"
        rel="stylesheet"
    />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1f2937",
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
                        "sidebar-light": "#e5e7eb",
                        "sidebar-dark": "#1f2937",
                        "surface-light": "#f3f4f6",
                        "surface-dark": "#374151",
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

<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200 h-screen overflow-hidden flex">

    <!-- Sidebar -->
       <body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')


    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <div class="flex-1 overflow-y-auto p-8">
            <header class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Pemasukan
                </h2>
            </header>

            <!-- Action Bar -->
            <div class="flex flex-col md:flex-row gap-4 mb-8 justify-between items-start md:items-center">
                <button class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium py-2 px-6 rounded-full flex items-center gap-2 transition-colors shadow-sm">
                    <span class="material-icons-outlined text-sm font-bold">add</span>
                    Pemasukan
                </button>

                <div class="flex gap-4 w-full md:w-auto">
                    <div class="relative flex-1 md:w-80">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-gray-500 text-xl">search</span>
                        </span>
                        <input
                            type="text"
                            placeholder="Search..."
                            class="w-full pl-10 pr-4 py-2 bg-gray-200 dark:bg-gray-800 border-none rounded-full text-gray-800 dark:text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-primary dark:focus:ring-gray-500"
                        />
                    </div>

                    <button class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium py-2 px-8 rounded-full transition-colors shadow-sm">
                        Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-400/50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider font-bold">
                                <th class="p-4 w-16 text-center">No</th>
                                <th class="p-4">Nama</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4">Deskripsi</th>
                                <th class="p-4">Pemasukan</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <!-- rows tetap sama, hanya dirapikan -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="bg-gray-400/50 dark:bg-gray-900 p-4 text-center border-t border-gray-300 dark:border-gray-800">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-400">
                Copyright ©2025 by digicity.id
            </p>
        </footer>
    </main>

    <!-- Dark Mode Button -->
    <div class="fixed bottom-6 right-6">
        <button
            class="bg-primary text-white p-3 rounded-full shadow-lg hover:bg-opacity-90 transition-all z-50"
            onclick="document.documentElement.classList.toggle('dark')"
        >
            <span class="material-icons-outlined">dark_mode</span>
        </button>
    </div>

</body>
</html>

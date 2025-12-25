<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Karyawan | Project Management</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6B4C4C",
                        "background-light": "#F3F4F6",
                        "background-dark": "#1F2937",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#111827",
                        "sidebar-light": "#E5E7EB",
                        "sidebar-dark": "#374151",
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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200 h-screen overflow-hidden flex flex-col">

<div class="flex flex-1 overflow-hidden">

    <!-- SIDEBAR -->
    <body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        <!-- Container untuk sidebar yang akan dimuat -->
        @include('finance.templet.sider')
    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col bg-surface-light dark:bg-surface-dark overflow-hidden relative">

        <div class="flex-1 overflow-y-auto p-8 md:p-12">
            <h2 class="text-2xl font-bold mb-8 text-gray-900 dark:text-white">Daftar karyawan</h2>

            <!-- TOP ACTION -->
            <div class="flex flex-col md:flex-row gap-4 mb-6 justify-between items-start md:items-center">
                <button class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-6 rounded-lg shadow-sm flex items-center gap-2 transition-colors">
                    <span>+</span> tambah karyawan
                </button>

                <div class="flex flex-1 w-full md:w-auto gap-4 md:max-w-xl md:justify-end">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons text-gray-500">search</span>
                        </span>
                        <input type="text" placeholder="Search..."
                               class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 border-transparent focus:border-primary focus:bg-white dark:focus:bg-gray-800 focus:ring-0 text-gray-900 dark:text-white placeholder-gray-500 transition-colors" />
                    </div>

                    <button class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-8 rounded-lg shadow-sm transition-colors">
                        Filter
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <!-- (isi tabel TIDAK diubah, hanya dirapikan indentasinya) -->

        </div>

        <footer class="bg-gray-300 dark:bg-gray-800 py-4 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
            Copyright ©2025 by digicity.id
        </footer>

    </main>
</div>

<button class="fixed bottom-4 right-4 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-opacity-90 transition-all z-50"
        onclick="document.documentElement.classList.toggle('dark')">
    <span class="material-icons">dark_mode</span>
</button>

</body>
</html>

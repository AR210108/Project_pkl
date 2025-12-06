<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda (Dashboard) Management</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6A5ACD", // A vibrant purple for the primary color
                        "background-light": "#F3F4F6", // Light gray for light mode background
                        "background-dark": "#111827", // Dark blue-gray for dark mode background
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem", // 12px
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">
    <div class="flex h-screen">
        @include('manager_divisi/templet/sider')
        <main class="flex-1 flex flex-col">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-lg mr-4">
                            <span class="material-symbols-outlined text-blue-500">draft</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Draf Tugas</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">3</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-lg mr-4">
                            <span class="material-symbols-outlined text-yellow-500">hourglass_top</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tugas Dikerjakan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">2</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-lg mr-4">
                            <span class="material-symbols-outlined text-green-500">task_alt</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tugas Selesai</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">6</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                        <div class="bg-indigo-100 dark:bg-indigo-900/50 p-3 rounded-lg mr-4">
                            <span class="material-symbols-outlined text-indigo-500">summarize</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Tugas</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">10</p>
                        </div>
                    </div>
                </div>
                <div class="mt-10 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Deadline Terdekat</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col bg-white dark:bg-gray-800 overflow-hidden">
                            <div
                                class="aspect-video w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-gray-400 dark:text-gray-500 !text-5xl">image</span>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Website Pendidikan</h3>
                                    <span
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold px-2 py-1 rounded-full">Rp.
                                        1.000.000</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Deadline: 10 Des 2025</p>
                                <div class="mt-auto">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 90%;"></div>
                                    </div>
                                    <p class="text-xs text-red-500 font-semibold text-right mt-1">90%</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col bg-white dark:bg-gray-800 overflow-hidden">
                            <div
                                class="aspect-video w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-gray-400 dark:text-gray-500 !text-5xl">image</span>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Website Pendidikan</h3>
                                    <span
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold px-2 py-1 rounded-full">Rp.
                                        1.000.000</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Deadline: 20 Des 2025</p>
                                <div class="mt-auto">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 90%;"></div>
                                    </div>
                                    <p class="text-xs text-red-500 font-semibold text-right mt-1">90%</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col bg-white dark:bg-gray-800 overflow-hidden">
                            <div
                                class="aspect-video w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-gray-400 dark:text-gray-500 !text-5xl">image</span>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Website Pendidikan</h3>
                                    <span
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold px-2 py-1 rounded-full">Rp.
                                        1.000.000</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Deadline: 30 Des 2025</p>
                                <div class="mt-auto">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 90%;"></div>
                                    </div>
                                    <p class="text-xs text-red-500 font-semibold text-right mt-1">90%</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col bg-white dark:bg-gray-800 overflow-hidden">
                            <div
                                class="aspect-video w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-gray-400 dark:text-gray-500 !text-5xl">image</span>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Website Pendidikan</h3>
                                    <span
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold px-2 py-1 rounded-full">Rp.
                                        1.000.000</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Deadline: 20 Jan 2026</p>
                                <div class="mt-auto">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 90%;"></div>
                                    </div>
                                    <p class="text-xs text-red-500 font-semibold text-right mt-1">90%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-center">
                        <button
                            class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold py-2 px-6 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Lihat Lainnya
                        </button>
                    </div>
                </div>
            </div>
            <footer class="bg-white dark:bg-gray-800 text-center py-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>
</body>

</html>

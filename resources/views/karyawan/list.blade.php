<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manage Tugas</title>
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
                        primary: "#7C3AED", // A vibrant purple for primary actions
                        "background-light": "#FFFFFF",
                        "background-dark": "#121212",
                        "surface-light": "#F3F4F6", // Light grey for cards/sections
                        "surface-dark": "#1F2937", // Dark grey for cards/sections
                        "text-primary-light": "#111827", // Dark grey/black for text on light BG
                        "text-primary-dark": "#F9FAFB", // Light grey/white for text on dark BG
                        "text-secondary-light": "#6B7280", // Lighter grey for subtitles on light BG
                        "text-secondary-dark": "#9CA3AF", // Lighter grey for subtitles on dark BG
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151",
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
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <header class="flex justify-between items-center py-4 border-b border-border-light dark:border-border-dark">
            <h1 class="text-3xl font-bold text-text-primary-light dark:text-text-primary-dark">Brand</h1>
            <nav class="hidden md:flex items-center space-x-8">
                <a class="text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark transition"
                    href="#">Beranda</a>
                <a class="text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark transition"
                    href="#">Absensi</a>
                <a class="font-semibold text-text-primary-light dark:text-text-primary-dark" href="#">Manage
                    Tugas</a>
            </nav>
            <button
                class="bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark px-6 py-2 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">Logout</button>
        </header>
        <main class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg">
                    <h2 class="font-semibold mb-4 text-text-primary-light dark:text-text-primary-dark">Filter</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative">
                            <select
                                class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg py-3 px-4 text-text-secondary-light dark:text-text-secondary-dark focus:ring-primary focus:border-primary">
                                <option>Status</option>
                                <option>Pending</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">search</span>
                            <input
                                class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg py-3 pl-10 pr-4 text-text-secondary-light dark:text-text-secondary-dark placeholder-text-secondary-light dark:placeholder-text-secondary-dark focus:ring-primary focus:border-primary"
                                placeholder="Search..." type="text" />
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div
                        class="bg-surface-light dark:bg-surface-dark p-5 rounded-lg flex justify-between items-center cursor-pointer border-2 border-primary dark:border-primary">
                        <div>
                            <h3 class="font-bold text-lg text-text-primary-light dark:text-text-primary-dark">Website
                                Pendidikan</h3>
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Dari project
                                managemen</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">25 Nov</p>
                            <span
                                class="mt-1 inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">PENDING</span>
                        </div>
                    </div>
                    <div
                        class="bg-surface-light dark:bg-surface-dark p-5 rounded-lg flex justify-between items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <div>
                            <h3 class="font-bold text-lg text-text-primary-light dark:text-text-primary-dark">Website
                                Makanan</h3>
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Dari project
                                managemen</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">25 Nov</p>
                            <span
                                class="mt-1 inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">PENDING</span>
                        </div>
                    </div>
                    <div
                        class="bg-surface-light dark:bg-surface-dark p-5 rounded-lg flex justify-between items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <div>
                            <h3 class="font-bold text-lg text-text-primary-light dark:text-text-primary-dark">Website
                                Industri</h3>
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Dari project
                                managemen</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">25 Nov</p>
                            <span
                                class="mt-1 inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">PENDING</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg sticky top-8">
                    <h2 class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark">Tugas</h2>
                    <h3 class="text-2xl font-bold mt-4 text-text-primary-light dark:text-text-primary-dark">Website
                        Pendidikan</h3>
                    <p class="mt-1 text-sm text-text-secondary-light dark:text-text-secondary-dark">Dari project
                        managemen</p>
                    <div class="mt-6 bg-gray-200 dark:bg-gray-700 h-64 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl text-gray-400 dark:text-gray-500"
                            style="font-variation-settings: 'FILL' 1;">move_item</span>
                    </div>
                    <button
                        class="w-full mt-6 bg-primary text-white font-bold py-3 rounded-lg hover:bg-purple-700 transition">
                        UPLOAD HASIL
                    </button>
                </div>
            </div>
        </main>
    </div>
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-8">
        <div class="bg-surface-light dark:bg-surface-dark text-center py-4 rounded-lg">
            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Copyright ©2025 by digicity.id
            </p>
        </div>
    </footer>

</body>

</html>

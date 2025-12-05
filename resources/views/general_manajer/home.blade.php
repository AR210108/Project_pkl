<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Beranda (Home) - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#8B5CF6", // A nice purple as a placeholder primary color
                        "background-light": "#F3F4F6", // Light Gray
                        "background-dark": "#1F2937", // Dark Gray-Blue
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#374151",
                        "text-light-primary": "#111827",
                        "text-dark-primary": "#F9FAFB",
                        "text-light-secondary": "#6B7280",
                        "text-dark-secondary": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#4B5563"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "0.75rem",
                        xl: "1rem",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-text-light-primary dark:text-dark-primary font-display">
    <div class="flex h-screen">
        @include('general_manajer/templet/header')
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-8 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm">
                        <p class="text-sm font-medium text-text-light-secondary dark:text-dark-secondary">Grad Tugas</p>
                        <p class="text-3xl font-bold mt-2">3</p>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm">
                        <p class="text-sm font-medium text-text-light-secondary dark:text-dark-secondary">Tugas
                            Diteruskan</p>
                        <p class="text-3xl font-bold mt-2">2</p>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm">
                        <p class="text-sm font-medium text-text-light-secondary dark:text-dark-secondary">Tugas Selesai
                        </p>
                        <p class="text-3xl font-bold mt-2">6</p>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-lg shadow-sm">
                        <p class="text-sm font-medium text-text-light-secondary dark:text-dark-secondary">Total Tugas
                        </p>
                        <p class="text-3xl font-bold mt-2">10</p>
                    </div>
                </div>
                <div class="bg-surface-light dark:bg-surface-dark p-8 rounded-xl shadow-sm">
                    <h2 class="text-xl font-bold mb-6">Deadline Terdekat</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="border border-border-light dark:border-border-dark p-4 rounded-lg flex flex-col">
                            <div
                                class="bg-gray-200 dark:bg-gray-600 h-32 rounded-md mb-4 flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Image</span>
                            </div>
                            <h3 class="font-semibold text-sm">Malaria Pendidikan</h3>
                            <p class="text-xs text-text-light-secondary dark:text-dark-secondary mb-3">SISA WAKTU 2 HARI
                            </p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2">
                                <div class="bg-red-500 h-1.5 rounded-full" style="width: 80%"></div>
                            </div>
                            <button
                                class="mt-auto w-full text-center py-2 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-md hover:bg-red-200 dark:hover:bg-red-900/70 transition-colors">TERLAMBAT</button>
                        </div>
                        <div class="border border-border-light dark:border-border-dark p-4 rounded-lg flex flex-col">
                            <div
                                class="bg-gray-200 dark:bg-gray-600 h-32 rounded-md mb-4 flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Image</span>
                            </div>
                            <h3 class="font-semibold text-sm">Vaksin Pendidikan</h3>
                            <p class="text-xs text-text-light-secondary dark:text-dark-secondary mb-3">SISA WAKTU 3 HARI
                            </p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2">
                                <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 60%"></div>
                            </div>
                            <button
                                class="mt-auto w-full text-center py-2 text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 rounded-md hover:bg-yellow-200 dark:hover:bg-yellow-900/70 transition-colors">DIKERJAKAN</button>
                        </div>
                        <div class="border border-border-light dark:border-border-dark p-4 rounded-lg flex flex-col">
                            <div
                                class="bg-gray-200 dark:bg-gray-600 h-32 rounded-md mb-4 flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Image</span>
                            </div>
                            <h3 class="font-semibold text-sm">Vaksin Pendidikan</h3>
                            <p class="text-xs text-text-light-secondary dark:text-dark-secondary mb-3">SISA WAKTU 5 HARI
                            </p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 40%"></div>
                            </div>
                            <button
                                class="mt-auto w-full text-center py-2 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded-md hover:bg-blue-200 dark:hover:bg-blue-900/70 transition-colors">DIKERJAKAN</button>
                        </div>
                        <div class="border border-border-light dark:border-border-dark p-4 rounded-lg flex flex-col">
                            <div
                                class="bg-gray-200 dark:bg-gray-600 h-32 rounded-md mb-4 flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Image</span>
                            </div>
                            <h3 class="font-semibold text-sm">Malaria Pendidikan</h3>
                            <p class="text-xs text-text-light-secondary dark:text-dark-secondary mb-3">SISA WAKTU 7 HARI
                            </p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 25%"></div>
                            </div>
                            <button
                                class="mt-auto w-full text-center py-2 text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 rounded-md hover:bg-green-200 dark:hover:bg-green-900/70 transition-colors">DIKERJAKAN</button>
                        </div>
                    </div>
                </div>
            </main>
            <footer
                class="text-center p-4 text-sm text-text-light-secondary dark:text-dark-secondary border-t border-border-light dark:border-border-dark">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

</body>

</html>

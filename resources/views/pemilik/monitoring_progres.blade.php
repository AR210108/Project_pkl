<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Project Monitoring</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#C33F3F",
                        "background-light": "#FFFFFF",
                        "background-dark": "#1F2937",
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
        .material-icons {
            font-size: inherit;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('pemilik/template/header')
        <main>
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold tracking-widest text-black dark:text-white">PROJECT MONITORING</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <span
                            class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input
                            class="bg-gray-200 dark:bg-gray-700 rounded-full py-2 pl-10 pr-4 w-64 border-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-sm text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400"
                            placeholder="Search..." type="search" />
                    </div>
                    <button
                        class="flex items-center bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium py-2 px-5 rounded-full text-sm">
                        <span class="material-icons mr-2 text-base">add</span>
                        Add project
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg mb-4 w-full h-40"></div>
                    <div class="px-1 pb-1">
                        <h2 class="text-xl font-bold mb-3 text-black dark:text-white">Web Sekolah</h2>
                        <div class="flex flex-col space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">person</span>
                                    <span>anang</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">groups</span>
                                    <span>6 orang</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-lg">monetization_on</span>
                                <span>Rp.20.000.000</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm font-semibold text-primary">45%</span>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg mb-4 w-full h-40"></div>
                    <div class="px-1 pb-1">
                        <h2 class="text-xl font-bold mb-3 text-black dark:text-white">Web Sekolah</h2>
                        <div class="flex flex-col space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">person</span>
                                    <span>Opet</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">groups</span>
                                    <span>6 orang</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-lg">monetization_on</span>
                                <span>Rp.20.000.000</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm font-semibold text-primary">45%</span>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg mb-4 w-full h-40"></div>
                    <div class="px-1 pb-1">
                        <h2 class="text-xl font-bold mb-3 text-black dark:text-white">Web Sekolah</h2>
                        <div class="flex flex-col space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">person</span>
                                    <span>Opet</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">groups</span>
                                    <span>6 orang</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-lg">monetization_on</span>
                                <span>Rp.20.000.000</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 63%"></div>
                            </div>
                            <span class="text-sm font-semibold text-primary">63%</span>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg mb-4 w-full h-40"></div>
                    <div class="px-1 pb-1">
                        <h2 class="text-xl font-bold mb-3 text-black dark:text-white">Web Sekolah</h2>
                        <div class="flex flex-col space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">person</span>
                                    <span>Opet</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">groups</span>
                                    <span>6 orang</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-lg">monetization_on</span>
                                <span>Rp.20.000.000</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm font-semibold text-primary">45%</span>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg mb-4 w-full h-40"></div>
                    <div class="px-1 pb-1">
                        <h2 class="text-xl font-bold mb-3 text-black dark:text-white">Web Sekolah</h2>
                        <div class="flex flex-col space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">person</span>
                                    <span>Opet</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">groups</span>
                                    <span>6 orang</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-lg">monetization_on</span>
                                <span>Rp.20.000.000</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm font-semibold text-primary">45%</span>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-800 rounded-2xl p-4 shadow-md border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg mb-4 w-full h-40"></div>
                    <div class="px-1 pb-1">
                        <h2 class="text-xl font-bold mb-3 text-black dark:text-white">Web Sekolah</h2>
                        <div class="flex flex-col space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">person</span>
                                    <span>Opet</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-2 text-lg">groups</span>
                                    <span>6 orang</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-lg">monetization_on</span>
                                <span>Rp.20.000.000</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 63%"></div>
                            </div>
                            <span class="text-sm font-semibold text-primary">63%</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="mt-16">
            <div
                class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-center py-4 rounded-2xl text-sm">
                Copyright ©2025 by digicity.id
            </div>
        </footer>
    </div>

</body>

</html>

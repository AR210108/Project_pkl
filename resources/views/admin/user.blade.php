<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#f1f5f9",
                        "background-dark": "#1e293b",
                        "surface-light": "#F3F4F6",
                        "surface-dark": "#1E1E1E",
                        "text-light": "#111827",
                        "text-dark": "#E5E7EB",
                        "subtle-light": "#6B7280",
                        "subtle-dark": "#9CA3AF",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    <style>
        .material-icons {
            font-size: 20px;
        }
        
        .material-icons-outlined {
            font-family: 'Material Icons Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        .material-symbols-outlined.filled {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
    <div class="flex min-h-screen">
        <aside class="w-64 flex-shrink-0 bg-surface-light dark:bg-surface-dark flex flex-col p-6">
            @include('admin/templet/header')
        </aside>
        <main class="flex-1 flex flex-col">
            <div class="flex-grow p-8">
                <h2 class="text-4xl font-bold mb-8 text-slate-900 dark:text-white">Daftar User</h2>
                <div class="flex justify-between items-center mb-6">
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-slate-300 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-400 dark:hover:bg-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-2xl">add</span>
                        tambah User
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                            <input
                                class="w-80 pl-10 pr-4 py-2 bg-slate-200 dark:bg-slate-700 border-transparent rounded-lg focus:ring-primary focus:border-primary text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500"
                                placeholder="Search..." type="text" />
                        </div>
                        <button
                            class="px-6 py-2 bg-slate-300 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-400 dark:hover:bg-slate-600 transition-colors">
                            Filter
                        </button>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-200 dark:bg-slate-700 text-xs text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="p-4">No</th>
                                <th class="p-4">Username</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Password</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4">1.</td>
                                <td class="p-4">john.doe</td>
                                <td class="p-4">john.doe@example.com</td>
                                <td class="p-4">Admin</td>
                                <td class="p-4">********</td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button
                                            class="p-1 text-slate-500 hover:text-primary dark:hover:text-primary transition-colors"><span
                                                class="material-symbols-outlined">edit</span></button>
                                        <button class="p-1 text-slate-500 hover:text-red-500 transition-colors"><span
                                                class="material-symbols-outlined">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4">2.</td>
                                <td class="p-4">jane.smith</td>
                                <td class="p-4">jane.smith@example.com</td>
                                <td class="p-4">User</td>
                                <td class="p-4">********</td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button
                                            class="p-1 text-slate-500 hover:text-primary dark:hover:text-primary transition-colors"><span
                                                class="material-symbols-outlined">edit</span></button>
                                        <button class="p-1 text-slate-500 hover:text-red-500 transition-colors"><span
                                                class="material-symbols-outlined">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4">3.</td>
                                <td class="p-4">alex.jones</td>
                                <td class="p-4">alex.jones@example.com</td>
                                <td class="p-4">User</td>
                                <td class="p-4">********</td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button
                                            class="p-1 text-slate-500 hover:text-primary dark:hover:text-primary transition-colors"><span
                                                class="material-symbols-outlined">edit</span></button>
                                        <button class="p-1 text-slate-500 hover:text-red-500 transition-colors"><span
                                                class="material-symbols-outlined">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4">4.</td>
                                <td class="p-4">sam.wilson</td>
                                <td class="p-4">sam.wilson@example.com</td>
                                <td class="p-4">Manager</td>
                                <td class="p-4">********</td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button
                                            class="p-1 text-slate-500 hover:text-primary dark:hover:text-primary transition-colors"><span
                                                class="material-symbols-outlined">edit</span></button>
                                        <button class="p-1 text-slate-500 hover:text-red-500 transition-colors"><span
                                                class="material-symbols-outlined">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between items-center mt-6 text-sm">
                    <a class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors"
                        href="#">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Previous
                    </a>
                    <a class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors"
                        href="#">
                        Next
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
            </div>
            <footer class="bg-slate-200 dark:bg-slate-800 text-center p-4 text-sm text-slate-500 dark:text-slate-400">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Kwitansi Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1a1a1a", // Deep black/charcoal used for key elements
                        "background-light": "#ffffff",
                        "background-dark": "#121212",
                        "gray-light-1": "#E5E5E5", // Sidebar background, row background
                        "gray-light-2": "#D4D4D4", // Slightly darker gray for search bar
                        "gray-dark-1": "#A3A3A3", // Header background
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
<style>
        body {
            font-family: 'Inter', sans-serif;
        }::-webkit-scrollbar {
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
<aside class="w-64 bg-gray-light-1 dark:bg-zinc-900 flex flex-col flex-shrink-0 h-full overflow-y-auto">
<div class="p-8">
<h1 class="text-4xl font-extrabold text-black dark:text-white tracking-tight">Brand</h1>
</div>
<nav class="flex-1 px-4 space-y-2 mt-4">
<a class="flex items-center px-4 py-3 text-sm font-medium text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-icons mr-3">home</span>
                    Beranda
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-icons mr-3">savings</span>
                    Pemasukan
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-icons mr-3">payments</span>
                    Pengeluaran
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-icons mr-3">receipt</span>
                    Invoice
                </a>
<a class="flex items-center px-4 py-3 text-sm font-bold text-black dark:text-white rounded-lg bg-gray-200 dark:bg-zinc-800 group transition-colors shadow-sm" href="#">
<span class="material-icons mr-3">receipt_long</span>
                    Kwitansi
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-icons mr-3">view_list</span>
                    Pembayaran<br/>Layanan
                </a>
<a class="flex items-center px-4 py-3 text-sm font-medium text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-icons mr-3">people</span>
                    Data Karyawan
                </a>
</nav>
<div class="p-4 mt-auto mb-8">
<a class="flex items-center px-4 py-3 text-lg font-bold text-black dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-800 group transition-colors" href="#">
<span class="material-symbols-outlined mr-3 text-2xl" style="transform: rotate(180deg);">logout</span>
                    Log Out
                </a>
</div>
</aside>
<main class="flex-1 overflow-y-auto bg-white dark:bg-background-dark p-8 md:p-12">
<header class="mb-10">
<h2 class="text-4xl font-semibold text-black dark:text-white">Kwitansi</h2>
</header>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
<button class="bg-gray-light-2 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-black dark:text-white font-bold py-3 px-6 rounded-2xl flex items-center transition-colors min-w-[200px] justify-center shadow-sm">
<span class="material-icons mr-2 text-2xl">add</span>
                    Buat Kwitansi
                </button>
<div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
<div class="relative flex-grow md:flex-grow-0">
<span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
<span class="material-icons text-black dark:text-gray-300 text-3xl">search</span>
</span>
<input class="bg-gray-light-2 dark:bg-zinc-700 text-black dark:text-white placeholder-black dark:placeholder-gray-300 rounded-2xl py-3 pl-14 pr-4 w-full md:w-80 border-none focus:ring-2 focus:ring-gray-400 font-bold text-lg" placeholder="Search..." type="text"/>
</div>
<button class="bg-gray-light-2 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-black dark:text-white font-bold py-3 px-10 rounded-2xl transition-colors shadow-sm text-lg">
                        Filter
                    </button>
</div>
</div>
<div class="overflow-x-auto rounded-xl shadow-none">
<table class="w-full min-w-max text-left border-collapse">
<thead>
<tr class="bg-gray-dark-1 dark:bg-zinc-600 text-xs uppercase tracking-wider text-black dark:text-white font-bold h-12">
<th class="px-4 py-3 rounded-tl-xl">No</th>
<th class="px-4 py-3">Nama<br/>Perusahaan</th>
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
<td class="px-4 py-4 font-bold text-black dark:text-gray-200">1.</td>
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
<tr class="bg-gray-light-1 dark:bg-zinc-800 h-24">
<td class="px-4 py-4 font-bold text-black dark:text-gray-200">2.</td>
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
<tr class="bg-gray-light-1 dark:bg-zinc-800 h-24">
<td class="px-4 py-4 font-bold text-black dark:text-gray-200">3.</td>
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
<tr class="bg-gray-light-1 dark:bg-zinc-800 h-24 rounded-b-xl">
<td class="px-4 py-4 font-bold text-black dark:text-gray-200 rounded-bl-xl">4.</td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4"></td>
<td class="px-4 py-4 rounded-br-xl"></td>
</tr>
</tbody>
</table>
</div>
</main>
</div>
<footer class="bg-gray-dark-1 dark:bg-zinc-950 py-4 px-8 text-center md:text-left z-10 relative">
<p class="text-black dark:text-gray-300 font-medium text-lg ml-12 pl-4">Copyright ©2025 by digicity.id</p>
</footer>

</body></html>
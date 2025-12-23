<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Daftar Pemasukan</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1f2937", // A deep charcoal for high contrast
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
                        "sidebar-light": "#e5e7eb", // Matching the gray in image
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
<aside class="w-64 bg-sidebar-light dark:bg-sidebar-dark flex-shrink-0 flex flex-col transition-colors duration-300 border-r border-gray-300 dark:border-gray-700 h-full">
<div class="p-6">
<h1 class="text-3xl font-bold text-black dark:text-white tracking-tight">Brand</h1>
</div>
<nav class="flex-1 px-4 space-y-2 overflow-y-auto">
<a class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:text-black dark:hover:text-white rounded-lg transition-colors group" href="#">
<span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">home</span>
<span class="font-medium">Beranda</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-700 text-black dark:text-white rounded-lg shadow-sm transition-colors" href="#">
<span class="material-icons-outlined text-xl">account_balance_wallet</span>
<span class="font-medium">Pemasukan</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:text-black dark:hover:text-white rounded-lg transition-colors group" href="#">
<span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">payments</span>
<span class="font-medium">Pengeluaran</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:text-black dark:hover:text-white rounded-lg transition-colors group" href="#">
<span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">receipt</span>
<span class="font-medium">Invoice</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:text-black dark:hover:text-white rounded-lg transition-colors group" href="#">
<span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">description</span>
<span class="font-medium">Kwitansi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:text-black dark:hover:text-white rounded-lg transition-colors group" href="#">
<span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">dns</span>
<span class="font-medium text-sm">Pembayaran Layanan</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:text-black dark:hover:text-white rounded-lg transition-colors group" href="#">
<span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">group</span>
<span class="font-medium">Data Karyawan</span>
</a>
</nav>
<div class="p-6 border-t border-gray-300 dark:border-gray-700">
<a class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium" href="#">
<span class="material-icons-outlined transform rotate-180">logout</span>
<span>Log Out</span>
</a>
</div>
</aside>
<main class="flex-1 flex flex-col h-screen overflow-hidden relative">
<div class="flex-1 overflow-y-auto p-8">
<header class="mb-8">
<h2 class="text-3xl font-bold text-gray-900 dark:text-white">Pemasukan</h2>
</header>
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
<input class="w-full pl-10 pr-4 py-2 bg-gray-200 dark:bg-gray-800 border-none rounded-full text-gray-800 dark:text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-primary dark:focus:ring-gray-500" placeholder="Search..." type="text"/>
</div>
<button class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium py-2 px-8 rounded-full transition-colors shadow-sm">
                        Filter
                    </button>
</div>
</div>
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
<tr class="bg-gray-100 dark:bg-gray-800/50 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
<td class="p-4 text-center font-medium">1.</td>
<td class="p-4 font-semibold text-gray-900 dark:text-white">PT Sinergi Tech</td>
<td class="p-4 text-gray-600 dark:text-gray-400">20 Jan 2025</td>
<td class="p-4">
<span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-200">Proyek</span>
</td>
<td class="p-4 text-gray-600 dark:text-gray-400">Pembayaran Termin 1 App</td>
<td class="p-4 font-bold text-green-600 dark:text-green-400">Rp 15.000.000</td>
<td class="p-4 text-center">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-icons-outlined text-lg">more_horiz</span>
</button>
</td>
</tr>
<tr class="bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
<td class="p-4 text-center font-medium">2.</td>
<td class="p-4 font-semibold text-gray-900 dark:text-white">Budi Santoso</td>
<td class="p-4 text-gray-600 dark:text-gray-400">22 Jan 2025</td>
<td class="p-4">
<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-200">Konsultasi</span>
</td>
<td class="p-4 text-gray-600 dark:text-gray-400">Sesi Konsultasi SEO</td>
<td class="p-4 font-bold text-green-600 dark:text-green-400">Rp 2.500.000</td>
<td class="p-4 text-center">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-icons-outlined text-lg">more_horiz</span>
</button>
</td>
</tr>
<tr class="bg-gray-100 dark:bg-gray-800/50 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
<td class="p-4 text-center font-medium">3.</td>
<td class="p-4 font-semibold text-gray-900 dark:text-white">CV Maju Jaya</td>
<td class="p-4 text-gray-600 dark:text-gray-400">23 Jan 2025</td>
<td class="p-4">
<span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full dark:bg-purple-900 dark:text-purple-200">Produk</span>
</td>
<td class="p-4 text-gray-600 dark:text-gray-400">Penjualan Lisensi SW</td>
<td class="p-4 font-bold text-green-600 dark:text-green-400">Rp 8.000.000</td>
<td class="p-4 text-center">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-icons-outlined text-lg">more_horiz</span>
</button>
</td>
</tr>
<tr class="bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
<td class="p-4 text-center font-medium">4.</td>
<td class="p-4 font-semibold text-gray-900 dark:text-white">Indah Permata</td>
<td class="p-4 text-gray-600 dark:text-gray-400">25 Jan 2025</td>
<td class="p-4">
<span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-200">Service</span>
</td>
<td class="p-4 text-gray-600 dark:text-gray-400">Maintenance Bulanan</td>
<td class="p-4 font-bold text-green-600 dark:text-green-400">Rp 1.000.000</td>
<td class="p-4 text-center">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-icons-outlined text-lg">more_horiz</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="h-12"></div>
</div>
<footer class="bg-gray-400/50 dark:bg-gray-900 p-4 text-center border-t border-gray-300 dark:border-gray-800">
<p class="text-sm font-semibold text-gray-700 dark:text-gray-400">Copyright ©2025 by digicity.id</p>
</footer>
</main>
<div class="fixed bottom-6 right-6">
<button class="bg-primary text-white p-3 rounded-full shadow-lg hover:bg-opacity-90 transition-all z-50" onclick="document.documentElement.classList.toggle('dark')">
<span class="material-icons-outlined">dark_mode</span>
</button>
</div>

</body></html>
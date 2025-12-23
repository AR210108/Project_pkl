<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Pengeluaran Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000", // Using black as the primary brand color based on the "Brand" text
                        "background-light": "#ffffff",
                        "background-dark": "#111827", // gray-900
                        "sidebar-light": "#e5e5e5", // Match the light grey sidebar
                        "sidebar-dark": "#1f2937", // gray-800
                        "element-grey": "#d4d4d4", // For buttons/inputs
                        "element-grey-dark": "#374151", // Dark mode equivalent
                        "table-header": "#a3a3a3", // The darker grey header
                        "table-header-dark": "#4b5563",
                        "table-row": "#e5e5e5", // Light grey rows
                        "table-row-dark": "#1f2937",
                        "footer-bg": "#a8a8a8", // Darker grey footer strip
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
<aside class="w-64 bg-sidebar-light dark:bg-sidebar-dark flex flex-col p-6 overflow-y-auto shrink-0 z-10">
<div class="mb-12">
<h1 class="text-3xl font-black tracking-tight text-primary dark:text-white">Brand</h1>
</div>
<nav class="space-y-2 flex-1">
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5" href="#">
<span class="material-icons-round text-lg">home</span>
                    Beranda
                </a>
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5" href="#">
<span class="material-icons-round text-lg">store</span>
                    Pemasukan
                </a>
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-black dark:text-white bg-black/5 dark:bg-white/10 rounded-lg shadow-sm" href="#">
<span class="material-icons-round text-lg">payments</span>
                    Pengeluaran
                </a>
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5" href="#">
<span class="material-icons-round text-lg">receipt</span>
                    Invoice
                </a>
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5" href="#">
<span class="material-icons-round text-lg">receipt_long</span>
                    Kwitansi
                </a>
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5 leading-tight" href="#">
<span class="material-icons-round text-lg">payment</span>
                    Pembayaran <br/>Layanan
                </a>
<a class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5" href="#">
<span class="material-icons-round text-lg">group</span>
                    Data Karyawan
                </a>
</nav>
<div class="mt-8 pt-4">
<a class="flex items-center gap-3 px-3 py-2 text-sm font-bold text-black dark:text-white hover:opacity-70 transition-opacity" href="#">
<span class="material-icons-round text-lg">logout</span>
                    Log Out
                </a>
</div>
</aside>
<main class="flex-1 p-8 md:p-12 overflow-y-auto bg-white dark:bg-gray-900">
<h2 class="text-3xl font-bold mb-10 text-black dark:text-white">Pengeluaran</h2>
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
<input class="bg-element-grey dark:bg-element-grey-dark text-black dark:text-white placeholder-black dark:placeholder-gray-300 font-bold text-sm rounded-2xl border-none py-3 pl-12 pr-6 w-full sm:w-80 focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 shadow-sm" placeholder="Search..." type="text"/>
</div>
<button class="bg-element-grey dark:bg-element-grey-dark hover:bg-gray-300 dark:hover:bg-gray-600 text-black dark:text-white font-bold py-3 px-10 rounded-2xl transition-colors shadow-sm text-sm">
                        Filter
                    </button>
</div>
</div>
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
<footer class="bg-footer-bg dark:bg-footer-bg-dark py-4 px-8 w-full shrink-0 z-20">
<p class="text-sm font-bold text-black dark:text-gray-400">Copyright ©2025 by digicity.id</p>
</footer>

</body></html>
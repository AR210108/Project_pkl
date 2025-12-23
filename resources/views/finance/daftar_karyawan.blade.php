<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Daftar Karyawan | Project Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6B4C4C", // Muted brownish/reddish tone from the border of the original image
                        "background-light": "#F3F4F6", // Light gray background
                        "background-dark": "#1F2937", // Dark gray background
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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200 h-screen overflow-hidden flex flex-col">
<div class="flex flex-1 overflow-hidden">
<aside class="w-64 bg-sidebar-light dark:bg-sidebar-dark flex flex-col justify-between border-r border-gray-300 dark:border-gray-700 transition-colors duration-300">
<div>
<div class="p-6">
<h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Brand</h1>
</div>
<nav class="mt-4 px-4 space-y-2">
<a class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors" href="#">
<span class="material-icons text-xl">home</span>
                        Beranda
                    </a>
<a class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors" href="#">
<span class="material-icons text-xl">account_balance_wallet</span>
                        Pemasukan
                    </a>
<a class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors" href="#">
<span class="material-icons text-xl">payments</span>
                        Pengeluaran
                    </a>
<a class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors" href="#">
<span class="material-icons text-xl">receipt_long</span>
                        Invoice
                    </a>
<a class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors" href="#">
<span class="material-icons text-xl">receipt</span>
                        Kwitansi
                    </a>
<a class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors" href="#">
<span class="material-icons text-xl">dns</span>
                        Pembayaran Layanan
                    </a>
<a class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white bg-gray-200 dark:bg-gray-600 rounded-lg transition-colors shadow-sm" href="#">
<span class="material-icons text-xl">people</span>
                        Data Karyawan
                    </a>
</nav>
</div>
<div class="p-4 mb-4">
<a class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-800 dark:text-gray-200 hover:text-primary dark:hover:text-primary transition-colors" href="#">
<span class="material-icons text-xl transform rotate-180">logout</span>
                    Log Out
                </a>
</div>
</aside>
<main class="flex-1 flex flex-col bg-surface-light dark:bg-surface-dark overflow-hidden relative">
<div class="flex-1 overflow-y-auto p-8 md:p-12">
<h2 class="text-2xl font-bold mb-8 text-gray-900 dark:text-white">Daftar karyawan</h2>
<div class="flex flex-col md:flex-row gap-4 mb-6 justify-between items-start md:items-center">
<button class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-6 rounded-lg shadow-sm flex items-center gap-2 transition-colors">
<span>+</span> tambah karyawan
                    </button>
<div class="flex flex-1 w-full md:w-auto gap-4 md:max-w-xl md:justify-end">
<div class="relative w-full md:w-64">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-icons text-gray-500">search</span>
</span>
<input class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 border-transparent focus:border-primary focus:bg-white dark:focus:bg-gray-800 focus:ring-0 text-gray-900 dark:text-white placeholder-gray-500 transition-colors" placeholder="Search..." type="text"/>
</div>
<button class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-8 rounded-lg shadow-sm transition-colors">
                            Filter
                        </button>
</div>
</div>
<div class="bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm border border-gray-300 dark:border-gray-700">
<div class="overflow-x-auto">
<table class="w-full text-left text-sm whitespace-nowrap">
<thead class="bg-gray-400 dark:bg-gray-900 text-gray-900 dark:text-gray-100 uppercase text-xs font-bold tracking-wider">
<tr>
<th class="px-6 py-4">No</th>
<th class="px-6 py-4">Nama</th>
<th class="px-6 py-4">Jabatan</th>
<th class="px-6 py-4">Gaji</th>
<th class="px-6 py-4">Alamat</th>
<th class="px-6 py-4">Kontak</th>
<th class="px-6 py-4">Status</th>
<th class="px-6 py-4">Foto</th>
<th class="px-6 py-4">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-300 dark:divide-gray-700">
<tr class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
<td class="px-6 py-6 font-medium text-gray-900 dark:text-white">1.</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Budi Santoso</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Manager</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Rp 12.000.000</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Jl. Sudirman No. 1</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">08123456789</td>
<td class="px-6 py-6">
<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full dark:bg-green-900 dark:text-green-300">Aktif</span>
</td>
<td class="px-6 py-6">
<div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
</td>
<td class="px-6 py-6 flex gap-2">
<button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"><span class="material-icons text-base">edit</span></button>
<button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"><span class="material-icons text-base">delete</span></button>
</td>
</tr>
<tr class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
<td class="px-6 py-6 font-medium text-gray-900 dark:text-white">2.</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Siti Aminah</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Staff Admin</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Rp 5.500.000</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Jl. Mawar No. 45</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">08567890123</td>
<td class="px-6 py-6">
<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full dark:bg-green-900 dark:text-green-300">Aktif</span>
</td>
<td class="px-6 py-6">
<div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
</td>
<td class="px-6 py-6 flex gap-2">
<button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"><span class="material-icons text-base">edit</span></button>
<button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"><span class="material-icons text-base">delete</span></button>
</td>
</tr>
<tr class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
<td class="px-6 py-6 font-medium text-gray-900 dark:text-white">3.</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Andi Pratama</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Developer</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Rp 10.000.000</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Jl. Melati Indah</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">08901234567</td>
<td class="px-6 py-6">
<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Cuti</span>
</td>
<td class="px-6 py-6">
<div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
</td>
<td class="px-6 py-6 flex gap-2">
<button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"><span class="material-icons text-base">edit</span></button>
<button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"><span class="material-icons text-base">delete</span></button>
</td>
</tr>
<tr class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
<td class="px-6 py-6 font-medium text-gray-900 dark:text-white">4.</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Ratna Dewi</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Marketing</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Rp 6.000.000</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">Jl. Anggrek No. 12</td>
<td class="px-6 py-6 text-gray-600 dark:text-gray-300">08112233445</td>
<td class="px-6 py-6">
<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full dark:bg-green-900 dark:text-green-300">Aktif</span>
</td>
<td class="px-6 py-6">
<div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
</td>
<td class="px-6 py-6 flex gap-2">
<button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"><span class="material-icons text-base">edit</span></button>
<button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"><span class="material-icons text-base">delete</span></button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="flex justify-center mt-6 items-center gap-2">
<button class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
<span class="material-icons text-gray-500">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center rounded bg-gray-300 dark:bg-gray-600 font-bold text-gray-800 dark:text-white">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">2</button>
<button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">3</button>
<button class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
<span class="material-icons text-gray-500">chevron_right</span>
</button>
</div>
</div>
<footer class="bg-gray-300 dark:bg-gray-800 py-4 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                Copyright ©2025 by digicity.id
            </footer>
</main>
</div>
<button class="fixed bottom-4 right-4 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-opacity-90 transition-all z-50" onclick="document.documentElement.classList.toggle('dark')">
<span class="material-icons">dark_mode</span>
</button>

</body></html>
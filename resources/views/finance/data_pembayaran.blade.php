<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Pembayaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6", // A generic primary color, as none is specified in the grayscale design.
                        "background-light": "#f3f4f6",
                        "background-dark": "#111827",
                        "dark-blue": "#0c4a6e", // Biru tua untuk background
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem", // 12px, based on the image's rounded corners.
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

<body class="bg-dark-blue text-white font-display">
    <div class="flex min-h-screen">
        @include('finance/templet/sider')
        <main class="flex-1">
            <div class="flex flex-col h-full">
                <div class="flex-grow p-10">
                    <header class="mb-8">
                        <h2 class="text-4xl font-bold">Data Pembayaran</h2>
                    </header>
                    <div class="flex justify-between items-center mb-6">
                        <button onclick="openAddModal()"
                            class="bg-blue-500 text-white font-medium py-3 px-6 rounded-full flex items-center gap-2 hover:bg-blue-600 transition-colors">
                            <span class="text-xl font-light">+</span>
                            Tambah
                        </button>
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <span
                                    class="material-icons-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">search</span>
                                <input
                                    class="bg-blue-800 bg-opacity-50 border border-blue-700 rounded-full w-48 pl-12 pr-4 py-3 focus:ring-2 focus:ring-blue-400 text-white placeholder-gray-300"
                                    placeholder="Search" type="text" />
                            </div>
                            <button
                                class="bg-blue-800 bg-opacity-50 border border-blue-700 text-white font-medium py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="bg-blue-800 bg-opacity-30 backdrop-blur-sm rounded-2xl p-6 border border-blue-700">
                        <h3 class="text-lg font-semibold mb-4">Order List</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-blue-200 font-semibold uppercase tracking-wider">
                                    <tr class="border-b border-blue-700">
                                        <th class="py-3 px-4">No</th>
                                        <th class="py-3 px-4">Layanan</th>
                                        <th class="py-3 px-4">Harga</th>
                                        <th class="py-3 px-4">Klien</th>
                                        <th class="py-3 px-4">Pembayaran Awal</th>
                                        <th class="py-3 px-4">Pelunasan</th>
                                        <th class="py-3 px-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-blue-700">
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                    </tr>
                                    <tr class="border-b border-blue-700">
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                    </tr>
                                    <tr>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                        <td class="py-5 px-4"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <footer class="bg-blue-900 bg-opacity-50 border-t border-blue-700 text-center py-4">
                    <p class="text-sm text-blue-200">Copyright ©2025 by digicity.id</p>
                </footer>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Data Pembayaran -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-blue-500 rounded-2xl p-8 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Tambah Data Pembayaran</h3>
                <button onclick="closeAddModal()" class="text-white hover:text-gray-200">
                    <span class="material-icons-outlined text-3xl">close</span>
                </button>
            </div>
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white mb-2">No</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Nomor">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Layanan</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Nama Layanan">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Harga</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Harga">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Klien</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Nama Klien">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Pembayaran Awal</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Jumlah Pembayaran Awal">
                    </div>
                    <div>
                        <label class="block text-white mb-2">Pelunasan</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Jumlah Pelunasan">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-white mb-2">Status</label>
                        <select class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-90 text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                            <option value="">Pilih Status</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial Payment</option>
                            <option value="completed">Completed</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-white text-blue-500 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            
            if (event.target == addModal) {
                closeAddModal();
            }
        }
    </script>
</body>

</html>
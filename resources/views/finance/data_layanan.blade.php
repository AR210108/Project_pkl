<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Layanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1d4ed8",
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
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
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }
        
        /* Custom scrollbar for dark mode */
        .dark ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100">
    <div class="flex min-h-screen flex-col md:flex-row">
        @include('finance/templet/header')
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-4 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-4 md:mb-6">Data Layanan</h2>
                
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 gap-4">
                    <div class="relative w-full md:w-1/3">
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input
                            class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-700 border-none rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Search" type="text" />
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <button
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 flex-1 md:flex-none">
                            Filter
                        </button>
                        <button onclick="openAddModal()"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center gap-2 flex-1 md:flex-none">
                            <span class="material-symbols-outlined">add</span>
                            <span class="hidden sm:inline">Tambah Data Layanan</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>
                
                <!-- Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <!-- Card 1 -->
                    <div
                        class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 flex flex-col gap-4 border-2 border-blue-500">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 h-32 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Gambar
                        </div>
                        <div class="flex justify-between items-center">
                            <button onclick="openEditModal()"
                                class="flex items-center gap-1 text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            <button onclick="openStatusModal()"
                                class="text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">Status</button>
                        </div>
                        <h3 class="font-semibold text-lg">Website Sekolah</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">school</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">groups</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">credit_card</span>
                                <span>-</span>
                            </div>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 flex flex-col gap-4">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 h-32 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Gambar
                        </div>
                        <div class="flex justify-between items-center">
                            <button onclick="openEditModal()"
                                class="flex items-center gap-1 text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            <button onclick="openStatusModal()"
                                class="text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">Status</button>
                        </div>
                        <h3 class="font-semibold text-lg">Website Sekolah</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">school</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">groups</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">credit_card</span>
                                <span>-</span>
                            </div>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                    
                    <!-- Card 3 -->
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 flex flex-col gap-4">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 h-32 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Gambar
                        </div>
                        <div class="flex justify-between items-center">
                            <button onclick="openEditModal()"
                                class="flex items-center gap-1 text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            <button onclick="openStatusModal()"
                                class="text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">Status</button>
                        </div>
                        <h3 class="font-semibold text-lg">Website Sekolah</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">school</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">groups</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">credit_card</span>
                                <span>-</span>
                            </div>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                    
                    <!-- Card 4 -->
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 flex flex-col gap-4">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 h-32 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Gambar
                        </div>
                        <div class="flex justify-between items-center">
                            <button onclick="openEditModal()"
                                class="flex items-center gap-1 text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            <button onclick="openStatusModal()"
                                class="text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">Status</button>
                        </div>
                        <h3 class="font-semibold text-lg">Website Sekolah</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">school</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">groups</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">credit_card</span>
                                <span>-</span>
                            </div>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                    
                    <!-- Card 5 -->
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 flex flex-col gap-4">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 h-32 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Gambar
                        </div>
                        <div class="flex justify-between items-center">
                            <button onclick="openEditModal()"
                                class="flex items-center gap-1 text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            <button onclick="openStatusModal()"
                                class="text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">Status</button>
                        </div>
                        <h3 class="font-semibold text-lg">Website Sekolah</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">school</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">groups</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">credit_card</span>
                                <span>-</span>
                            </div>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                    
                    <!-- Card 6 -->
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 flex flex-col gap-4">
                        <div
                            class="bg-gray-200 dark:bg-gray-700 h-32 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Gambar
                        </div>
                        <div class="flex justify-between items-center">
                            <button onclick="openEditModal()"
                                class="flex items-center gap-1 text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">
                                <span class="material-symbols-outlined text-base">edit</span>
                                <span class="hidden sm:inline">Edit</span>
                            </button>
                            <button onclick="openStatusModal()"
                                class="text-sm bg-white dark:bg-gray-600 px-3 py-1 rounded-md text-gray-700 dark:text-gray-200">Status</button>
                        </div>
                        <h3 class="font-semibold text-lg">Website Sekolah</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">school</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">groups</span>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined">credit_card</span>
                                <span>-</span>
                            </div>
                        </div>
                        <div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="bg-gray-200 dark:bg-gray-900 text-center p-4 text-sm text-gray-600 dark:text-gray-400">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

    <!-- Modal Tambah Data Layanan -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Tambah Data Layanan</h3>
                <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Foto Layanan</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="addPhoto" class="flex flex-col items-center justify-center w-full h-40 md:h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="addPhotoPreview">
                                <span class="material-symbols-outlined text-4xl text-gray-500 mb-2">cloud_upload</span>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF (MAX. 5MB)</p>
                            </div>
                            <input id="addPhoto" type="file" class="hidden" onchange="previewAddPhoto(event)" />
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Layanan</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Pilih Kategori</option>
                        <option value="website">Website</option>
                        <option value="aplikasi">Aplikasi</option>
                        <option value="design">Design</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Klien</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Harga</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Progress</label>
                    <input type="range" min="0" max="100" value="0" class="w-full">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 order-1 sm:order-2">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Data Layanan -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Edit Data Layanan</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Foto Layanan</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="editPhoto" class="flex flex-col items-center justify-center w-full h-40 md:h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="editPhotoPreview">
                                <img src="https://picsum.photos/seed/website-sekolah/300/200.jpg" alt="Preview" class="preview-image rounded-lg mb-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Klik untuk mengganti foto</p>
                            </div>
                            <input id="editPhoto" type="file" class="hidden" onchange="previewEditPhoto(event)" />
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Layanan</label>
                    <input type="text" value="Website Sekolah" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Pilih Kategori</option>
                        <option value="website" selected>Website</option>
                        <option value="aplikasi">Aplikasi</option>
                        <option value="design">Design</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Klien</label>
                    <input type="text" value="Sekolah ABC" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Harga</label>
                    <input type="text" value="Rp 10.000.000" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Progress</label>
                    <input type="range" min="0" max="100" value="0" class="w-full">
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 order-1 sm:order-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Status -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 md:p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Ubah Status</h3>
                <button onclick="closeStatusModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Status Layanan</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Pilih Status</option>
                        <option value="pending">Pending</option>
                        <option value="progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="3"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 order-1 sm:order-2">
                        Simpan Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
            // Reset form
            document.getElementById('addPhoto').value = '';
            document.getElementById('addPhotoPreview').innerHTML = `
                <span class="material-symbols-outlined text-4xl text-gray-500 mb-2">cloud_upload</span>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF (MAX. 5MB)</p>
            `;
        }

        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        function openStatusModal() {
            document.getElementById('statusModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Preview photo functions
        function previewAddPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('addPhotoPreview').innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="preview-image rounded-lg mb-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Klik untuk mengganti foto</p>
                    `;
                }
                reader.readAsDataURL(file);
            }
        }

        function previewEditPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editPhotoPreview').innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="preview-image rounded-lg mb-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Klik untuk mengganti foto</p>
                    `;
                }
                reader.readAsDataURL(file);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            const statusModal = document.getElementById('statusModal');
            
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == statusModal) {
                closeStatusModal();
            }
        }
        
        // Handle escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeEditModal();
                closeStatusModal();
            }
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Rapat Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Custom styles for better UI */
        .modal-overlay {
            backdrop-filter: blur(4px);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animation for modal */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-animate {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Table row hover effect */
        .table-row-hover:hover {
            background-color: #f8fafc;
            transition: background-color 0.2s ease;
        }
    </style>
</head>

<body class="font-sans bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        @include('admin/templet/sider')

        <main class="flex-1 p-4 md:p-8 ml-0 md:ml-64 transition-all">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Catatan Rapat</h2>
                    <p class="text-gray-600 mt-1">Kelola catatan hasil rapat perusahaan</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Role: {{ Auth::user()->role }}</span>
                </div>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <span class="material-icons-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" type="text" placeholder="Cari topik, peserta, hasil diskusi..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                    </div>
                    <div class="flex gap-3">
                        <button id="createBtn"
                            class="px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 flex items-center gap-2 shadow-sm hover:shadow transition-all duration-200">
                            <span class="material-icons-outlined text-lg">add</span>
                            <span class="font-medium">Buat Catatan Baru</span>
                        </button>
                        <button onclick="loadData()" class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                            <span class="material-icons-outlined text-gray-600">refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Table Header -->
                <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="flex items-center gap-3 mb-3 sm:mb-0">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <span class="material-icons-outlined text-blue-600">description</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">Daftar Catatan Rapat</h3>
                            <p class="text-sm text-gray-500">Catatan hasil rapat dalam perusahaan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-600">
                            <span id="filteredCount" class="font-semibold">0</span> dari <span id="totalCount" class="font-semibold">0</span> catatan
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Per halaman:</span>
                            <select id="itemsPerPage" class="text-sm border rounded px-2 py-1">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="p-4">
                    <!-- Loading State -->
                    <div id="loadingContainer" class="flex flex-col items-center justify-center py-12">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-blue-100 rounded-full"></div>
                            <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                        </div>
                        <p class="mt-4 text-gray-600 font-medium">Memuat data catatan rapat...</p>
                        <p class="text-sm text-gray-500 mt-1">Harap tunggu sebentar</p>
                    </div>

                    <!-- Table State -->
                    <div id="tableContainer" class="hidden">
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Topik
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Hasil Diskusi
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Keputusan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Peserta
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Penugasan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="paginationContainer" class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span id="startItem" class="font-semibold">0</span>-<span id="endItem" class="font-semibold">0</span> dari <span id="totalItems" class="font-semibold">0</span> catatan
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="firstPage" class="p-2 rounded-lg border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-icons-outlined text-sm">first_page</span>
                                </button>
                                <button id="prevPage" class="p-2 rounded-lg border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-icons-outlined">chevron_left</span>
                                </button>
                                <div id="pageNumbers" class="flex gap-1"></div>
                                <button id="nextPage" class="p-2 rounded-lg border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-icons-outlined">chevron_right</span>
                                </button>
                                <button id="lastPage" class="p-2 rounded-lg border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="material-icons-outlined text-sm">last_page</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <span class="material-icons-outlined text-4xl text-gray-400">description</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada catatan rapat</h3>
                        <p class="text-gray-500 mb-6">Mulai dengan membuat catatan rapat pertama Anda</p>
                        <button id="createFirstBtn"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm hover:shadow transition">
                            Buat Catatan Pertama
                        </button>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" class="hidden text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-4">
                            <span class="material-icons-outlined text-4xl text-red-500">error</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Terjadi Kesalahan</h3>
                        <p id="errorMessage" class="text-gray-500 mb-6">Gagal memuat data catatan rapat</p>
                        <div class="flex justify-center gap-3">
                            <button onclick="loadData()"
                                class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                                Coba Lagi
                            </button>
                            <button onclick="window.location.reload()"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                Refresh Halaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center p-6 mt-8 text-gray-500 text-sm border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <span class="font-medium text-gray-700">Catatan Rapat Management</span> 
                        <span class="mx-2">•</span>
                        Sistem Manajemen Rapat
                    </div>
                    <div>
                        Copyright © 2025 digicity.id 
                        <span class="mx-2">•</span>
                        v1.0.0
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- Create/Edit Modal -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 modal-overlay">
        <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-auto modal-animate">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 z-10">
                <div class="flex justify-between items-center">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Buat Catatan Rapat Baru</h3>
                    <button id="closeFormModal" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form id="crudForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="itemId" name="id">

                    <!-- Row 1: Tanggal & Topik -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Tanggal Rapat
                            </label>
                            <input type="date" id="tanggal" name="tanggal" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                            <p class="mt-1 text-sm text-gray-500">Pilih tanggal rapat dilaksanakan</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Topik Rapat
                            </label>
                            <input type="text" id="topik" name="topik" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                placeholder="Contoh: Rapat Evaluasi Kinerja Triwulan 1">
                            <p class="mt-1 text-sm text-gray-500">Masukkan topik pembahasan utama</p>
                        </div>
                    </div>

                    <!-- Row 2: Peserta & Penugasan -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Peserta Rapat
                            </label>
                            <div class="relative">
                                <select id="peserta" name="peserta[]" multiple required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition min-h-[120px]">
                                    <!-- Options will be loaded by JavaScript -->
                                </select>
                                <div class="absolute right-3 top-3">
                                    <span class="text-gray-400 text-sm">Ctrl+klik untuk pilih banyak</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Pilih peserta yang hadir dalam rapat</p>
                            <div id="selectedPeserta" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Penugasan
                            </label>
                            <div class="relative">
                                <select id="penugasan" name="penugasan[]" multiple required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition min-h-[120px]">
                                    <!-- Options will be loaded by JavaScript -->
                                </select>
                                <div class="absolute right-3 top-3">
                                    <span class="text-gray-400 text-sm">Ctrl+klik untuk pilih banyak</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Pilih anggota yang mendapatkan tugas</p>
                            <div id="selectedPenugasan" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- Row 3: Hasil Diskusi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Hasil Diskusi
                        </label>
                        <textarea id="hasil_diskusi" name="hasil_diskusi" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition resize-none"
                            placeholder="Tuliskan hasil diskusi dalam rapat..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">Ringkasan pembahasan dan poin-poin penting</p>
                    </div>

                    <!-- Row 4: Keputusan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Keputusan
                        </label>
                        <textarea id="keputusan" name="keputusan" rows="3" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition resize-none"
                            placeholder="Tuliskan keputusan yang diambil..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">Kesimpulan dan tindak lanjut dari rapat</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" id="cancelFormBtn"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium shadow-sm hover:shadow transition">
                            <span id="submitBtnText">Simpan Catatan</span>
                            <span id="submitLoading" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 modal-overlay">
        <div class="bg-white rounded-xl w-full max-w-md modal-animate">
            <div class="p-6">
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <span class="material-icons-outlined text-red-600 text-2xl">warning</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus catatan rapat ini?</p>
                            <p class="text-sm text-gray-500 mt-1">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button id="cancelDelete"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                        Batal
                    </button>
                    <button id="confirmDelete"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm hover:shadow transition">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Notification -->
    <div id="successToast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden z-50 max-w-sm">
        <div class="flex items-center gap-3">
            <span class="material-icons-outlined">check_circle</span>
            <span id="successMessage" class="font-medium">Berhasil!</span>
        </div>
    </div>

    <!-- Error Notification -->
    <div id="errorToast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg hidden z-50 max-w-sm">
        <div class="flex items-center gap-3">
            <span class="material-icons-outlined">error</span>
            <span id="errorToastMessage" class="font-medium">Terjadi kesalahan!</span>
        </div>
    </div>

    <script>
        // Core variables
        let currentPage = 1;
        let itemsPerPage = 10;
        let searchTerm = '';
        let allData = [];
        let currentDeleteId = null;
        let users = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Halaman Catatan Rapat dimuat');
            loadData();
            setupEventListeners();
            
            // Set today's date as default in form
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').value = today;
        });

        // Load data from server
        function loadData() {
            console.log('Memuat data catatan rapat...');
            showState('loading');
            showLoading(true);

            fetch('/catatan_rapat/data', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data diterima:', data);
                showLoading(false);

                if (data.success && data.data && Array.isArray(data.data)) {
                    allData = data.data;
                    console.log(`Memuat ${allData.length} catatan rapat`);
                    showState('table');
                    updateCounters();
                    renderTable();
                    renderPagination();
                } else {
                    console.warn('Data tidak valid:', data);
                    showState('empty');
                }
            })
            .catch(error => {
                console.error('Error loading data:', error);
                showLoading(false);
                document.getElementById('errorMessage').textContent = 'Error: ' + error.message;
                showState('error');
                showToast('error', 'Gagal memuat data: ' + error.message);
            });
        }

        // Load users for dropdown
        function loadUsers(selectedIds = []) {
            return fetch('/users/data', {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to load users');
                return response.json();
            })
            .then(response => {
                if (response.success && response.data) {
                    const pesertaSelect = document.getElementById('peserta');
                    const penugasanSelect = document.getElementById('penugasan');
                    
                    // Clear existing options
                    pesertaSelect.innerHTML = '';
                    penugasanSelect.innerHTML = '';
                    
                    // Add users to both selects
                    response.data.forEach(user => {
                        // For peserta
                        const pesertaOption = document.createElement('option');
                        pesertaOption.value = user.id;
                        pesertaOption.textContent = user.name;
                        if (selectedIds.peserta && selectedIds.peserta.includes(user.id)) {
                            pesertaOption.selected = true;
                        }
                        pesertaSelect.appendChild(pesertaOption);
                        
                        // For penugasan
                        const penugasanOption = document.createElement('option');
                        penugasanOption.value = user.id;
                        penugasanOption.textContent = user.name;
                        if (selectedIds.penugasan && selectedIds.penugasan.includes(user.id)) {
                            penugasanOption.selected = true;
                        }
                        penugasanSelect.appendChild(penugasanOption);
                    });
                    
                    updateSelectedBadges();
                }
            })
            .catch(error => {
                console.error('Error loading users:', error);
                document.getElementById('peserta').innerHTML = '<option disabled>Gagal memuat peserta</option>';
                document.getElementById('penugasan').innerHTML = '<option disabled>Gagal memuat penugasan</option>';
            });
        }

        // Update selected badges
        function updateSelectedBadges() {
            const pesertaSelect = document.getElementById('peserta');
            const penugasanSelect = document.getElementById('penugasan');
            const selectedPesertaDiv = document.getElementById('selectedPeserta');
            const selectedPenugasanDiv = document.getElementById('selectedPenugasan');
            
            // Clear existing badges
            selectedPesertaDiv.innerHTML = '';
            selectedPenugasanDiv.innerHTML = '';
            
            // Add badges for selected peserta
            Array.from(pesertaSelect.selectedOptions).forEach(option => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800';
                badge.innerHTML = `${option.text} <span class="material-icons-outlined text-xs cursor-pointer" onclick="deselectOption('peserta', ${option.value})">close</span>`;
                selectedPesertaDiv.appendChild(badge);
            });
            
            // Add badges for selected penugasan
            Array.from(penugasanSelect.selectedOptions).forEach(option => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm bg-green-100 text-green-800';
                badge.innerHTML = `${option.text} <span class="material-icons-outlined text-xs cursor-pointer" onclick="deselectOption('penugasan', ${option.value})">close</span>`;
                selectedPenugasanDiv.appendChild(badge);
            });
        }

        // Helper function to deselect option
        function deselectOption(selectId, value) {
            const select = document.getElementById(selectId);
            const option = select.querySelector(`option[value="${value}"]`);
            if (option) option.selected = false;
            updateSelectedBadges();
        }

        // Show/hide states
        function showState(state) {
            // Hide all states first
            document.getElementById('loadingContainer').classList.add('hidden');
            document.getElementById('tableContainer').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');

            // Show selected state
            switch (state) {
                case 'loading':
                    document.getElementById('loadingContainer').classList.remove('hidden');
                    break;
                case 'table':
                    document.getElementById('tableContainer').classList.remove('hidden');
                    break;
                case 'empty':
                    document.getElementById('emptyState').classList.remove('hidden');
                    break;
                case 'error':
                    document.getElementById('errorState').classList.remove('hidden');
                    break;
            }
        }

        // Show loading animation
        function showLoading(show) {
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitLoading = document.getElementById('submitLoading');
            
            if (show) {
                submitBtn.disabled = true;
                submitBtnText.classList.add('hidden');
                submitLoading.classList.remove('hidden');
            } else {
                submitBtn.disabled = false;
                submitBtnText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        // Update counters
        function updateCounters() {
            const filtered = filterData();
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = Math.min(start + itemsPerPage - 1, filtered.length);
            
            document.getElementById('filteredCount').textContent = filtered.length;
            document.getElementById('totalCount').textContent = allData.length;
            document.getElementById('startItem').textContent = start;
            document.getElementById('endItem').textContent = end;
            document.getElementById('totalItems').textContent = filtered.length;
        }

        // Render table
        function renderTable() {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const filtered = filterData();
            const pageData = filtered.slice(start, end);

            let html = '';
            
            if (pageData.length === 0) {
                html = `
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <span class="material-icons-outlined text-4xl mb-2 text-gray-300">search_off</span>
                                <p>Tidak ada data yang sesuai dengan pencarian</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                pageData.forEach((item, index) => {
                    const date = item.tanggal ? new Date(item.tanggal).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    }) : '-';
                    
                    html += `
                        <tr class="table-row-hover">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${start + index + 1}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-gray-400 text-sm">calendar_today</span>
                                    ${date}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <span class="font-medium text-gray-900">${escapeHtml(item.topik || '-')}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <span class="text-gray-600">${truncateText(item.hasil_diskusi || '-', 80)}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <span class="text-gray-600">${truncateText(item.keputusan || '-', 80)}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        ${formatUsers(item.peserta)}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        ${formatUsers(item.penugasan)}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <button onclick="editItem(${item.id})" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit">
                                        <span class="material-icons-outlined text-lg">edit</span>
                                    </button>
                                    <button onclick="deleteItem(${item.id})" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                        <span class="material-icons-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('tableBody').innerHTML = html;
            updateCounters();
        }

        // Helper functions
        function truncateText(text, maxLength) {
            if (!text || text.length <= maxLength) return escapeHtml(text || '-');
            return escapeHtml(text.substring(0, maxLength)) + '...';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatUsers(usersArray) {
            if (!Array.isArray(usersArray) || usersArray.length === 0) {
                return '<span class="text-gray-400">-</span>';
            }
            
            const names = usersArray.slice(0, 2).map(user => 
                `<span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">${escapeHtml(user.name)}</span>`
            );
            
            if (usersArray.length > 2) {
                names.push(`<span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">+${usersArray.length - 2}</span>`);
            }
            
            return names.join('');
        }

        // Filter data
        function filterData() {
            const term = searchTerm.toLowerCase();
            if (!term) return allData;

            return allData.filter(item => {
                return (
                    (item.topik || '').toLowerCase().includes(term) ||
                    (item.hasil_diskusi || '').toLowerCase().includes(term) ||
                    (item.keputusan || '').toLowerCase().includes(term) ||
                    (item.peserta || []).some(p => (p.name || '').toLowerCase().includes(term)) ||
                    (item.penugasan || []).some(p => (p.name || '').toLowerCase().includes(term))
                );
            });
        }

        // Pagination
        function renderPagination() {
            const filtered = filterData();
            const totalPages = Math.ceil(filtered.length / itemsPerPage);
            const container = document.getElementById('pageNumbers');
            const paginationContainer = document.getElementById('paginationContainer');

            container.innerHTML = '';

            if (totalPages <= 1) {
                paginationContainer.classList.add('hidden');
                return;
            }

            paginationContainer.classList.remove('hidden');

            // Calculate page range
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            // First page button
            if (startPage > 1) {
                const btn = document.createElement('button');
                btn.textContent = '1';
                btn.className = 'px-3 py-2 rounded-lg hover:bg-gray-100 text-sm';
                btn.onclick = () => goToPage(1);
                container.appendChild(btn);
                
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-2 text-gray-400';
                    container.appendChild(ellipsis);
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `px-3 py-2 rounded-lg text-sm font-medium ${i === currentPage ? 'bg-blue-600 text-white' : 'hover:bg-gray-100 text-gray-700'}`;
                btn.onclick = () => goToPage(i);
                container.appendChild(btn);
            }

            // Last page button
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-2 text-gray-400';
                    container.appendChild(ellipsis);
                }
                
                const btn = document.createElement('button');
                btn.textContent = totalPages;
                btn.className = 'px-3 py-2 rounded-lg hover:bg-gray-100 text-sm';
                btn.onclick = () => goToPage(totalPages);
                container.appendChild(btn);
            }

            // Enable/disable navigation buttons
            document.getElementById('firstPage').disabled = currentPage === 1;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
            document.getElementById('lastPage').disabled = currentPage === totalPages;
        }

        function goToPage(page) {
            currentPage = page;
            renderTable();
            renderPagination();
        }

        // Setup event listeners
        function setupEventListeners() {
            console.log('Setup event listeners');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Search
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    searchTerm = this.value.trim();
                    currentPage = 1;
                    renderTable();
                    renderPagination();
                }, 300);
            });

            // Items per page
            document.getElementById('itemsPerPage').addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
                renderPagination();
            });

            // Create buttons
            document.getElementById('createBtn').addEventListener('click', openFormModal);
            document.getElementById('createFirstBtn').addEventListener('click', openFormModal);

            // Modal controls
            document.getElementById('closeFormModal').addEventListener('click', () => hideModal('formModal'));
            document.getElementById('cancelFormBtn').addEventListener('click', () => hideModal('formModal'));
            document.getElementById('cancelDelete').addEventListener('click', () => hideModal('deleteModal'));

            // Form submit
            document.getElementById('crudForm').addEventListener('submit', function (e) {
                e.preventDefault();
                console.log('Form disubmit');
                
                showLoading(true);
                
                // Get form data
                const formData = {
                    tanggal: document.getElementById('tanggal').value,
                    topik: document.getElementById('topik').value,
                    hasil_diskusi: document.getElementById('hasil_diskusi').value,
                    keputusan: document.getElementById('keputusan').value,
                    peserta: Array.from(document.getElementById('peserta').selectedOptions).map(opt => parseInt(opt.value)),
                    penugasan: Array.from(document.getElementById('penugasan').selectedOptions).map(opt => parseInt(opt.value))
                };

                const id = document.getElementById('itemId').value;
                const method = id ? 'PUT' : 'POST';
                const url = id ? `/catatan-rapat/${id}` : '/catatan-rapat';

                console.log('Sending data:', formData);
                console.log('Method:', method);
                console.log('URL:', url);

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP ${response.status}`);
                    }
                    return data;
                })
                .then(data => {
                    showLoading(false);
                    if (data.success) {
                        showToast('success', data.message || 'Berhasil disimpan!');
                        hideModal('formModal');
                        loadData();
                    } else {
                        showToast('error', data.message || 'Gagal menyimpan data');
                    }
                })
                .catch(error => {
                    showLoading(false);
                    console.error('Error:', error);
                    showToast('error', 'Error: ' + error.message);
                });
            });

            // Delete confirmation
            document.getElementById('confirmDelete').addEventListener('click', function() {
                if (!currentDeleteId) {
                    showToast('error', 'Tidak ada ID untuk dihapus!');
                    return;
                }

                console.log(`Deleting catatan with ID: ${currentDeleteId}`);

                fetch(`/catatan-rapat/${currentDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP ${response.status}`);
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message || 'Berhasil dihapus!');
                        hideModal('deleteModal');
                        loadData();
                    } else {
                        showToast('error', data.message || 'Gagal menghapus data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error: ' + error.message);
                });
            });

            // Pagination buttons
            document.getElementById('firstPage').addEventListener('click', () => goToPage(1));
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) goToPage(currentPage - 1);
            });
            document.getElementById('nextPage').addEventListener('click', () => {
                const filtered = filterData();
                const totalPages = Math.ceil(filtered.length / itemsPerPage);
                if (currentPage < totalPages) goToPage(currentPage + 1);
            });
            document.getElementById('lastPage').addEventListener('click', () => {
                const filtered = filterData();
                const totalPages = Math.ceil(filtered.length / itemsPerPage);
                goToPage(totalPages);
            });

            // Update badges when selection changes
            document.getElementById('peserta').addEventListener('change', updateSelectedBadges);
            document.getElementById('penugasan').addEventListener('change', updateSelectedBadges);
        }

        // Modal functions
        function showModal(modalId) {
            console.log(`Showing modal: ${modalId}`);
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideModal(modalId) {
            console.log(`Hiding modal: ${modalId}`);
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentDeleteId = null;
        }

        // Open form modal
        function openFormModal(isEdit = false, data = null) {
            const modalTitle = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtnText');
            
            if (isEdit && data) {
                modalTitle.textContent = 'Edit Catatan Rapat';
                submitBtn.textContent = 'Update';
                document.getElementById('itemId').value = data.id;
                document.getElementById('tanggal').value = data.tanggal?.split('T')[0] || '';
                document.getElementById('topik').value = data.topik || '';
                document.getElementById('hasil_diskusi').value = data.hasil_diskusi || '';
                document.getElementById('keputusan').value = data.keputusan || '';
                
                // Load users with selected values
                loadUsers({
                    peserta: data.peserta ? data.peserta.map(p => p.id) : [],
                    penugasan: data.penugasan ? data.penugasan.map(p => p.id) : []
                });
            } else {
                modalTitle.textContent = 'Buat Catatan Rapat Baru';
                submitBtn.textContent = 'Simpan';
                document.getElementById('itemId').value = '';
                document.getElementById('crudForm').reset();
                
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('tanggal').value = today;
                
                loadUsers();
            }
            
            showModal('formModal');
        }

        // Edit item
        function editItem(id) {
            console.log(`Edit item with ID: ${id}`);
            const item = allData.find(d => d.id == id);

            if (!item) {
                showToast('error', 'Data tidak ditemukan!');
                return;
            }

            openFormModal(true, item);
        }

        // Delete item
        function deleteItem(id) {
            console.log('Delete item ID:', id);
            currentDeleteId = id;
            showModal('deleteModal');
        }

        // Toast notification
        function showToast(type, message) {
            let toast, messageElement;
            
            if (type === 'success') {
                toast = document.getElementById('successToast');
                messageElement = document.getElementById('successMessage');
            } else {
                toast = document.getElementById('errorToast');
                messageElement = document.getElementById('errorToastMessage');
            }
            
            messageElement.textContent = message;
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>

</html>
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
</head>

<body class="font-sans bg-gray-50 text-gray-800">
    <div class="flex min-h-screen">
        @include('admin/templet/sider')

        <main class="flex-1 p-4 md:p-8 ml-0 md:ml-64 transition-all">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">Catatan Rapat</h2>

            <!-- Search & Filter -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="relative flex-1">
                    <span
                        class="material-icons-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                    <input id="searchInput" type="text" placeholder="Cari topik, peserta, atau hasil diskusi..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-3">
                    <button id="createBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <span class="material-icons-outlined">add</span>
                        <span class="hidden sm:inline">Buat Catatan</span>
                        <span class="sm:hidden">Buat</span>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-lg flex items-center gap-2">
                        <span class="material-icons-outlined text-blue-600">description</span>
                        Daftar Catatan Rapat
                    </h3>
                    <span class="text-sm text-gray-500">Total: <span id="totalCount"
                            class="font-semibold">0</span></span>
                </div>

                <div class="p-4">
                    <!-- Loading Indicator -->
                    <div id="loadingContainer" class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-3">Memuat data...</span>
                    </div>

                    <!-- Table Container (akan diisi dengan JS) -->
                    <div id="tableContainer" class="hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            No</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Peserta</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Topik</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Hasil Diskusi</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Keputusan</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Penugasan</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="paginationContainer" class="flex justify-center items-center gap-2 mt-6">
                            <button id="prevPage" class="p-2 rounded-full hover:bg-gray-100 disabled:opacity-50">
                                <span class="material-icons-outlined text-sm">chevron_left</span>
                            </button>
                            <div id="pageNumbers" class="flex gap-1"></div>
                            <button id="nextPage" class="p-2 rounded-full hover:bg-gray-100 disabled:opacity-50">
                                <span class="material-icons-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden text-center py-8">
                        <div class="text-gray-400 mb-2">
                            <span class="material-icons-outlined text-4xl">description</span>
                        </div>
                        <p class="text-gray-500">Belum ada catatan rapat</p>
                        <button id="createFirstBtn"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Buat Catatan Pertama
                        </button>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" class="hidden text-center py-8">
                        <div class="text-red-400 mb-2">
                            <span class="material-icons-outlined text-4xl">error</span>
                        </div>
                        <p class="text-red-500" id="errorMessage">Terjadi kesalahan saat memuat data</p>
                        <button onclick="loadData()"
                            class="mt-4 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </div>

            <footer class="text-center p-4 mt-8 text-gray-500 text-sm border-t">
                Copyright ©2025 digicity.id
            </footer>
        </main>
    </div>

    <!-- Modals -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-xl font-bold">Buat Catatan Baru</h3>
                    <button id="closeFormModal" class="text-gray-500 hover:text-gray-700">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>

                <form id="crudForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="itemId" name="id">

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal *</label>
                            <input type="date" id="tanggal" name="tanggal" required
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Peserta *</label>
                            <select id="peserta" name="peserta[]" multiple required
                                class="w-full p-2 border rounded-lg h-40">
                            </select>
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Topik Rapat *</label>
                        <input type="text" id="topik" name="topik" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Evaluasi Kinerja">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Hasil Diskusi *</label>
                        <textarea id="hasil_diskusi" name="hasil_diskusi" rows="3" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Keputusan *</label>
                        <textarea id="keputusan" name="keputusan" rows="2" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Penugasan *</label>
                        <select id="penugasan" name="penugasan[]" multiple required
                            class="w-full p-2 border rounded-lg h-40">
                        </select>


                    </div>


                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelFormBtn"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <span class="material-icons-outlined text-red-500 text-3xl mr-3">warning</span>
                    <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
                </div>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus catatan ini?</p>
                <div class="flex justify-end gap-3">
                    <button id="cancelDelete"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button id="confirmDelete"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');

        // Core variables
        let currentPage = 1;
        const itemsPerPage = 10;
        let searchTerm = '';
        let allData = [];
        let currentDeleteId = null;
        let users = [];


        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Halaman dimuat');
            loadData();
            setupEventListeners();
        });

        // Load data from server
        function loadData() {
            console.log('Memuat data...');

            // Show loading state
            showState('loading');

            fetch('/catatan_rapat/data', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    console.log('Response status:', res.status);
                    return res.json();
                })
                .then(data => {
                    console.log('Data diterima:', data);

                    if (data.data && data.data.length) {
                        allData = data.data;
                        console.log(`Memuat ${data.data.length} catatan`);
                        showState('table');
                        renderTable();
                        renderPagination();
                    } else {
                        showState('empty');
                    }
                })
                .catch(err => {
                    console.error('Error loading data:', err);
                    document.getElementById('errorMessage').textContent = 'Error: ' + err.message;
                    showState('error');
                });
        }

        function loadUsers(selectedIds = []) {
            fetch('/users/data', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    const select = document.getElementById('peserta');
                    select.innerHTML = '';

                    res.data.forEach(user => {
                        const opt = document.createElement('option');
                        opt.value = user.id;
                        opt.textContent = user.name;

                        if (selectedIds.includes(user.id)) {
                            opt.selected = true;
                        }

                        select.appendChild(opt);
                    });
                })
                .catch(() => {
                    document.getElementById('peserta').innerHTML =
                        '<option>Gagal memuat peserta</option>';
                });
        }

        function loadPenugasan(selectedIds = []) {
            fetch('/users/data', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    const select = document.getElementById('penugasan');
                    select.innerHTML = '';

                    res.data.forEach(user => {
                        const opt = document.createElement('option');
                        opt.value = user.id;
                        opt.textContent = user.name;

                        if (selectedIds.includes(user.id)) {
                            opt.selected = true;
                        }

                        select.appendChild(opt);
                    });
                })
                .catch(() => {
                    document.getElementById('penugasan').innerHTML =
                        '<option>Gagal memuat penugasan</option>';
                });
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

        // Render table
        function renderTable() {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const filtered = filterData();
            const pageData = filtered.slice(start, end);

            let html = '';
            pageData.forEach((item, index) => {
                const date = item.tanggal ? item.tanggal.split('T')[0].split('-').reverse().join('/') : '-';
                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">${start + index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${date}</td>
                        <td class="px-6 py-4">${formatUsers(item.peserta)}</td>

                        <td class="px-6 py-4">${item.topik || '-'}</td>
                        <td class="px-6 py-4 max-w-xs">${truncateText(item.hasil_diskusi || '-', 50)}</td>
                        <td class="px-6 py-4 max-w-xs">${truncateText(item.keputusan || '-', 50)}</td>
                        <td class="px-6 py-4">${formatUsers(item.penugasan)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2">
                                <button onclick="editItem(${item.id})" class="p-1 text-green-600 hover:text-green-800" title="Edit">
                                    <span class="material-icons-outlined text-sm">edit</span>
                                </button>
                                <button onclick="deleteItem(${item.id})" class="p-1 text-red-600 hover:text-red-800" title="Hapus">
                                    <span class="material-icons-outlined text-sm">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('tableBody').innerHTML = html;
            document.getElementById('totalCount').textContent = filtered.length;
        }

        // Helper function
        function truncateText(text, maxLength) {
            if (!text || text.length <= maxLength) return text || '-';
            return text.substring(0, maxLength) + '...';
        }

        // Filter data
        function filterData() {
            const term = searchTerm.toLowerCase();
            if (!term) return allData;

            return allData.filter(item =>
                (item.topik || '').toLowerCase().includes(term) ||
                (item.peserta || [])
    .map(u => u.name.toLowerCase())
    .join(' ')
    .includes(term)
 ||
                (item.hasil_diskusi || '').toLowerCase().includes(term) ||
                (item.keputusan || '').toLowerCase().includes(term) ||
                (item.penugasan || '').toLowerCase().includes(term)
            );
        }

        // Pagination
        function renderPagination() {
            const filtered = filterData();
            const totalPages = Math.ceil(filtered.length / itemsPerPage);
            const container = document.getElementById('pageNumbers');

            container.innerHTML = '';

            // Show pagination only if we have more than 1 page
            if (totalPages <= 1) {
                document.getElementById('paginationContainer').classList.add('hidden');
                return;
            }

            document.getElementById('paginationContainer').classList.remove('hidden');

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className =
                    `w-8 h-8 rounded-full text-sm ${i === currentPage ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'}`;
                btn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
                container.appendChild(btn);
            }

            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
        }

        // Setup event listeners
        function setupEventListeners() {
            console.log('Setup event listeners');

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

            // Create button
            document.getElementById('createBtn').addEventListener('click', function() {
                console.log('Tombol buat diklik');
                openFormModal();
            });

            // Create first button
            document.getElementById('createFirstBtn').addEventListener('click', openFormModal);

            // Modal controls
            document.getElementById('closeFormModal').addEventListener('click', () => hideModal('formModal'));
            document.getElementById('cancelFormBtn').addEventListener('click', () => hideModal('formModal'));
            document.getElementById('cancelDelete').addEventListener('click', () => hideModal('deleteModal'));

            // Form submit
            document.getElementById('crudForm').addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Form disubmit');

    const formData = new FormData(this);

    const id = document.getElementById('itemId').value;
    const url = id ? `/catatan_rapat/${id}` : '/catatan_rapat';

    if (id) {
        formData.append('_method', 'PUT'); // Laravel way
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        const text = await res.text();
        console.log(`Response (${res.status}):`, text);

        try {
            return JSON.parse(text);
        } catch {
            throw new Error(text);
        }
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Berhasil disimpan');
            hideModal('formModal');
            loadData();
        } else {
            alert(data.message || 'Gagal menyimpan');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error: ' + err.message);
    });
});


            // Delete confirmation
            document.getElementById('confirmDelete').addEventListener('click', function() {
                if (!currentDeleteId) {
                    alert('Tidak ada ID untuk dihapus!');
                    return;
                }

                console.log(`Menghapus catatan dengan ID: ${currentDeleteId}`);

                fetch(`/catatan_rapat/${currentDeleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(async (response) => {
                        const text = await response.text();
                        console.log(`Delete response (${response.status}):`, text);

                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('Invalid JSON response: ' + text);
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Data berhasil dihapus!');
                            hideModal('deleteModal');
                            loadData(); // Reload data
                        } else {
                            alert(data.message || 'Gagal menghapus data!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    });
            });

            // Pagination buttons
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    renderPagination();
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                const filtered = filterData();
                const totalPages = Math.ceil(filtered.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                    renderPagination();
                }
            });
        }

        // Modal functions
        function showModal(modalId) {
            console.log(`Menampilkan modal: ${modalId}`);
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideModal(modalId) {
            console.log(`Menyembunyikan modal: ${modalId}`);
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentDeleteId = null;
        }

        function openFormModal() {
            document.getElementById('modalTitle').textContent = 'Buat Catatan Baru';
            document.getElementById('submitBtn').textContent = 'Simpan';
            document.getElementById('itemId').value = '';

            document.getElementById('crudForm').reset();

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').value = today;

            loadUsers(); // peserta
            loadPenugasan(); // penugasan

            showModal('formModal');
        }


        // CRUD actions
        function editItem(id) {
            console.log(`Edit item dengan ID: ${id}`);
            const item = allData.find(d => d.id == id);

            if (!item) {
                alert('Data tidak ditemukan!');
                return;
            }

            document.getElementById('modalTitle').textContent = 'Edit Catatan';
            document.getElementById('submitBtn').textContent = 'Update';
            document.getElementById('itemId').value = item.id;
            document.getElementById('tanggal').value = item.tanggal?.split('T')[0] || '';
            document.getElementById('topik').value = item.topik || '';
            document.getElementById('hasil_diskusi').value = item.hasil_diskusi || '';
            document.getElementById('keputusan').value = item.keputusan || '';
            document.getElementById('penugasan').value = item.penugasan || '';

            loadUsers(item.peserta); // ⬅️ SET PESERTA DARI USER ID
            loadPenugasan(item.penugasan); // penugasan dari user id
            showModal('formModal');
        }

        function deleteItem(id) {
    console.log('Delete item ID:', id);
    currentDeleteId = id;
    showModal('deleteModal');
}


        function getUserName(userId) {
            const user = users.find(u => u.id == userId);
            return user ? user.name : '-';
        }

function formatUsers(usersArray) {
    if (!Array.isArray(usersArray)) return '-';

    return usersArray
        .map(user => user.name)
        .join(', ');
}

    </script>
</body>

</html>

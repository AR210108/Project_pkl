<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengumuman Management</title>
    
    <!-- External Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Minimal Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        background: "#ffffff",
                        sidebar: "#f3f4f6",
                        text: "#1e293b",
                        "text-muted": "#64748b",
                        border: "#e2e8f0"
                    },
                    fontFamily: {
                        poppins: ["Poppins", "sans-serif"]
                    }
                }
            }
        };
    </script>
    
    <!-- Minimal CSS -->
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .material-icons-outlined { font-size: 24px; }
        
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        
        .modal { transition: opacity 0.2s; }
        .popup-slide { transition: transform 0.3s ease; }
        
        .sidebar-transition { transition: width 0.3s; }
        .scrollable-table { overflow-x: auto; }
        
        @media (max-width: 640px) {
            .desktop-only { display: none; }
            .mobile-only { display: block; }
        }
        @media (min-width: 641px) {
            .mobile-only { display: none; }
        }
        
        .scrollable-table::-webkit-scrollbar { height: 8px; }
        .scrollable-table::-webkit-scrollbar-track { background: #f1f5f9; }
        .scrollable-table::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        
        .selected-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin: 2px;
        }
        
        .selected-user-badge button {
            background: none;
            border: none;
            color: #0369a1;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
        }
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-poppins bg-background text-text">
    <div class="min-h-screen flex">
        @include('admin.templet.sider')
        
        <main class="flex-1 ml-0 md:ml-64 transition-all">
            <div class="p-4 md:p-8">
                <h1 class="text-2xl md:text-3xl font-bold mb-6">Pengumuman</h1>
                
                <!-- Search & Actions -->
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="relative flex-1">
                        <span class="material-icons-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input id="searchInput" type="text" placeholder="Cari pengumuman..." 
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div class="flex gap-3">
                        <button id="filterBtn" class="px-4 py-2 border rounded-lg flex items-center gap-2 hover:bg-gray-50">
                            <span class="material-icons-outlined">filter_list</span>
                            Filter
                        </button>
                        <button id="createBtn" class="px-4 py-2 bg-primary text-white rounded-lg flex items-center gap-2 hover:bg-blue-700">
                            <span class="material-icons-outlined">add</span>
                            Buat Pengumuman
                        </button>
                    </div>
                </div>
                
                <!-- Data Table -->
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h2 class="font-semibold flex items-center gap-2">
                            <span class="material-icons-outlined text-primary">campaign</span>
                            Daftar Pengumuman
                        </h2>
                        <span class="text-sm text-text-muted">
                            Total: <span id="totalCount" class="font-semibold">{{ count($pengumuman ?? []) }}</span>
                        </span>
                    </div>
                    
                    <div class="p-4">
                        <!-- Empty State -->
                        <div id="emptyState" class="{{ count($pengumuman ?? []) > 0 ? 'hidden' : '' }} text-center py-12">
                            <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <span class="material-icons-outlined text-3xl text-gray-400">campaign</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Pengumuman</h3>
                            <p class="text-gray-500 mb-6">Mulai dengan membuat pengumuman pertama Anda</p>
                            <button id="createFirstBtn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                                Buat Pengumuman Pertama
                            </button>
                        </div>
                        
                        <!-- Loading State -->
                        <div id="loadingState" class="hidden text-center py-12">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary mx-auto mb-4"></div>
                            <p class="text-gray-600">Memuat data pengumuman...</p>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div id="tableContainer" class="{{ count($pengumuman ?? []) > 0 ? '' : 'hidden' }} desktop-only scrollable-table">
                            <table class="w-full min-w-[1000px]">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="p-3 text-left text-sm font-semibold">No</th>
                                        <th class="p-3 text-left text-sm font-semibold">Judul</th>
                                        <th class="p-3 text-left text-sm font-semibold">Isi</th>
                                        <th class="p-3 text-left text-sm font-semibold">Kepada</th>
                                        <th class="p-3 text-left text-sm font-semibold">Lampiran</th>
                                        <th class="p-3 text-left text-sm font-semibold">Tanggal</th>
                                        <th class="p-3 text-left text-sm font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach($pengumuman ?? [] as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $loop->iteration }}</td>
                                        <td class="p-3 font-medium">{{ $item->judul }}</td>
                                        <td class="p-3 max-w-xs truncate" title="{{ $item->isi_pesan }}">
                                            {{ Str::limit($item->isi_pesan, 50) }}
                                        </td>
                                        <td class="p-3">
                                            @if($item->users && count($item->users) > 0)
                                                <span class="text-sm">
                                                    {{ $item->users->take(2)->pluck('name')->join(', ') }}
                                                    @if(count($item->users) > 2)
                                                        +{{ count($item->users) - 2 }} lainnya
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @if($item->lampiran)
                                            <a href="{{ Storage::url('pengumuman/' . $item->lampiran) }}" 
                                               target="_blank" class="text-primary hover:underline flex items-center gap-1">
                                                <span class="material-icons-outlined text-sm">attach_file</span>
                                                File
                                            </a>
                                            @else 
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-sm text-gray-500">
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="p-3">
                                            <div class="flex gap-2">
                                                <button onclick="editPengumuman({{ $item->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 p-1 hover:bg-blue-50 rounded">
                                                    <span class="material-icons-outlined text-sm">edit</span>
                                                </button>
                                                <button onclick="deletePengumuman({{ $item->id }})" 
                                                        class="text-red-600 hover:text-red-800 p-1 hover:bg-red-50 rounded">
                                                    <span class="material-icons-outlined text-sm">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Cards -->
                        <div id="mobileCards" class="{{ count($pengumuman ?? []) > 0 ? '' : 'hidden' }} mobile-only space-y-4">
                            @foreach($pengumuman ?? [] as $item)
                            <div class="border rounded-lg p-4 card-hover">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold">{{ $item->judul }}</h3>
                                        <p class="text-sm text-gray-500">
                                            @if($item->users && count($item->users) > 0)
                                                Kepada: {{ $item->users->take(2)->pluck('name')->join(', ') }}
                                                @if(count($item->users) > 2)
                                                    +{{ count($item->users) - 2 }} lainnya
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex gap-1">
                                        <button onclick="editPengumuman({{ $item->id }})" 
                                                class="text-blue-600 p-1 hover:bg-blue-50 rounded">
                                            <span class="material-icons-outlined text-sm">edit</span>
                                        </button>
                                        <button onclick="deletePengumuman({{ $item->id }})" 
                                                class="text-red-600 p-1 hover:bg-red-50 rounded">
                                            <span class="material-icons-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($item->isi_pesan, 100) }}</p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    @if($item->lampiran)
                                    <a href="{{ Storage::url('pengumuman/' . $item->lampiran) }}" 
                                       target="_blank" class="text-primary">
                                        📎 Lampiran
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="mt-auto p-4 text-center text-sm text-gray-500 border-t">
                Copyright ©2025 digicity.id
            </footer>
        </main>
    </div>
    
    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-xl font-semibold"></h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div id="modalContent" class="mb-6"></div>
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button id="confirmBtn" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <span id="confirmBtnText">Simpan</span>
                        <span id="loadingSpinner" class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 rounded-lg shadow-lg p-4 hidden z-50 max-w-sm">
        <div class="flex items-start gap-3">
            <span id="notifIcon" class="material-icons-outlined"></span>
            <div class="flex-1">
                <p id="notifTitle" class="font-semibold"></p>
                <p id="notifMessage" class="text-sm mt-1"></p>
            </div>
            <button onclick="hideNotif()" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined text-sm">close</span>
            </button>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Global variables
        let currentAction = '';
        let currentId = null;
        let allUsers = [];
        let cachedUsers = [];
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Pengumuman page loaded');
            
            // Load users data
            loadUsers();
            
            // Event listeners
            document.getElementById('createBtn').addEventListener('click', openCreateModal);
            document.getElementById('createFirstBtn').addEventListener('click', openCreateModal);
            document.getElementById('confirmBtn').addEventListener('click', handleConfirm);
            document.getElementById('searchInput').addEventListener('input', filterData);
            
            // Escape key to close modal
            document.addEventListener('keydown', function(e) {
                if(e.key === 'Escape') closeModal();
            });
        });
        
        // Load users from server
        async function loadUsers() {
            try {
                showLoading(true, 'users');
                const response = await fetch('/users/data', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load users');
                }
                
                const result = await response.json();
                
                if (result.success && result.data) {
                    cachedUsers = result.data;
                    console.log(`Loaded ${cachedUsers.length} users`);
                } else {
                    console.warn('No users data found');
                    cachedUsers = [];
                }
            } catch (error) {
                console.error('Error loading users:', error);
                cachedUsers = [];
            } finally {
                showLoading(false, 'users');
            }
        }
        
        // Modal functions
        function openCreateModal() {
            currentAction = 'create';
            currentId = null;
            
            showModal(
                'Buat Pengumuman Baru',
                getFormTemplate({}),
                'Simpan'
            );
            
            // Load users into select after modal is shown
            setTimeout(() => populateUserSelect(), 100);
        }
        
        function showModal(title, content, confirmText = 'Simpan') {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('confirmBtnText').textContent = confirmText;
            document.getElementById('modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentAction = '';
            currentId = null;
        }
        
        // Form template
        function getFormTemplate(data = {}) {
            return `
                <form id="pengumumanForm" class="space-y-4" onsubmit="return false;">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">
                            <span class="text-red-500">*</span> Judul
                        </label>
                        <input type="text" id="judulInput" name="judul"
                            value="${data.judul || ''}"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none"
                            placeholder="Masukkan judul pengumuman"
                            required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">
                            <span class="text-red-500">*</span> Isi Pesan
                        </label>
                        <textarea id="isiInput" name="isi_pesan" rows="4"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none resize-none"
                            placeholder="Tulis isi pengumuman..."
                            required>${data.isi_pesan || ''}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">
                            <span class="text-red-500">*</span> Penerima
                        </label>
                        <div class="relative">
                            <select id="usersSelect" name="users[]" multiple
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none min-h-[120px]">
                                <option value="" disabled>Memuat daftar user...</option>
                            </select>
                            <div class="absolute right-3 top-3">
                                <span class="text-xs text-gray-400">Ctrl+klik untuk pilih banyak</span>
                            </div>
                        </div>
                        <div id="selectedUsers" class="mt-2 flex flex-wrap gap-1"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Lampiran</label>
                        <input type="file" id="fileInput" name="lampiran"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <p class="text-xs text-gray-500 mt-1">
                            Ukuran maksimal 10MB. Format: PDF, DOC, JPG, PNG
                        </p>
                        <div id="filePreview" class="mt-2"></div>
                    </div>
                </form>
            `;
        }
        
        // Populate user select dropdown
        function populateUserSelect(selectedIds = []) {
            const select = document.getElementById('usersSelect');
            const selectedUsersDiv = document.getElementById('selectedUsers');
            
            if (!select) return;
            
            // Clear existing options
            select.innerHTML = '';
            if (selectedUsersDiv) selectedUsersDiv.innerHTML = '';
            
            if (cachedUsers.length === 0) {
                select.innerHTML = '<option value="" disabled>Tidak ada user tersedia</option>';
                return;
            }
            
            // Add users to select
            cachedUsers.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} (${user.role || 'No role'})`;
                
                if (Array.isArray(selectedIds) && selectedIds.includes(user.id.toString())) {
                    option.selected = true;
                }
                
                select.appendChild(option);
            });
            
            // Update selected badges
            updateSelectedBadges();
            
            // Add change listener for select
            select.addEventListener('change', updateSelectedBadges);
        }
        
        // Update selected user badges
        function updateSelectedBadges() {
            const select = document.getElementById('usersSelect');
            const selectedUsersDiv = document.getElementById('selectedUsers');
            
            if (!select || !selectedUsersDiv) return;
            
            selectedUsersDiv.innerHTML = '';
            
            const selectedOptions = Array.from(select.selectedOptions);
            
            if (selectedOptions.length === 0) {
                selectedUsersDiv.innerHTML = '<span class="text-sm text-gray-400">Belum ada penerima dipilih</span>';
                return;
            }
            
            selectedOptions.forEach(option => {
                const badge = document.createElement('span');
                badge.className = 'selected-user-badge';
                badge.innerHTML = `
                    ${option.textContent}
                    <button type="button" onclick="deselectUser('${option.value}')">
                        <span class="material-icons-outlined text-xs">close</span>
                    </button>
                `;
                selectedUsersDiv.appendChild(badge);
            });
        }
        
        // Deselect user
        function deselectUser(userId) {
            const select = document.getElementById('usersSelect');
            const option = select.querySelector(`option[value="${userId}"]`);
            
            if (option) {
                option.selected = false;
                updateSelectedBadges();
            }
        }
        
        // Edit pengumuman
        async function editPengumuman(id) {
            try {
                showLoading(true);
                currentAction = 'edit';
                currentId = id;
                
                const response = await fetch(`/pengumuman/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    showModal(
                        'Edit Pengumuman',
                        getFormTemplate(result.data),
                        'Update'
                    );
                    
                    // Wait for modal to render, then populate users
                    setTimeout(() => {
                        const selectedUserIds = result.data.users?.map(u => u.id.toString()) || [];
                        populateUserSelect(selectedUserIds);
                        
                        // Show file preview if exists
                        if (result.data.lampiran) {
                            showFilePreview(result.data.lampiran);
                        }
                    }, 100);
                } else {
                    showNotification('Error', result.message || 'Gagal memuat data', 'error');
                }
            } catch (error) {
                console.error('Error editing pengumuman:', error);
                showNotification('Error', 'Gagal memuat data pengumuman', 'error');
            } finally {
                showLoading(false);
            }
        }
        
        // Show file preview
        function showFilePreview(filename) {
            const filePreview = document.getElementById('filePreview');
            if (filePreview) {
                filePreview.innerHTML = `
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="material-icons-outlined text-sm">attach_file</span>
                        <span>File saat ini: ${filename}</span>
                    </div>
                `;
            }
        }
        
        // Handle form submission
        async function handleConfirm() {
            try {
                // Get form elements
                const judulInput = document.getElementById('judulInput');
                const isiInput = document.getElementById('isiInput');
                const usersSelect = document.getElementById('usersSelect');
                const fileInput = document.getElementById('fileInput');
                
                // Validation
                if (!judulInput || !isiInput || !usersSelect) {
                    showNotification('Error', 'Form tidak lengkap', 'error');
                    return;
                }
                
                const judul = judulInput.value.trim();
                const isi = isiInput.value.trim();
                const selectedUsers = Array.from(usersSelect.selectedOptions).map(opt => opt.value);
                
                if (!judul || !isi) {
                    showNotification('Error', 'Judul dan Isi Pesan wajib diisi', 'error');
                    return;
                }
                
                if (selectedUsers.length === 0) {
                    showNotification('Error', 'Pilih minimal satu penerima', 'error');
                    return;
                }
                
                // Prepare form data
                const formData = new FormData();
                formData.append('judul', judul);
                formData.append('isi_pesan', isi);
                
                // Add users
                selectedUsers.forEach(userId => {
                    formData.append('users[]', userId);
                });
                
                // Add file if exists
                if (fileInput && fileInput.files[0]) {
                    formData.append('lampiran', fileInput.files[0]);
                }
                
                // Determine URL and method
                let url = '/pengumuman';
                let method = 'POST';
                
                if (currentAction === 'edit') {
                    url = `/pengumuman/${currentId}`;
                    method = 'PUT';
                    formData.append('_method', 'PUT');
                }
                
                // Show loading
                showSubmitLoading(true);
                
                // Send request
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                // Hide loading
                showSubmitLoading(false);
                
                if (result.success) {
                    showNotification('Berhasil', result.message || 'Pengumuman berhasil disimpan', 'success');
                    setTimeout(() => {
                        closeModal();
                        window.location.reload();
                    }, 1500);
                } else {
                    let errorMsg = result.message || 'Terjadi kesalahan';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    showNotification('Error', errorMsg, 'error');
                }
                
            } catch (error) {
                console.error('Error saving pengumuman:', error);
                showSubmitLoading(false);
                showNotification('Error', 'Gagal menyimpan: ' + error.message, 'error');
            }
        }
        
        // Delete pengumuman
        async function deletePengumuman(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/pengumuman/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Berhasil', result.message || 'Pengumuman berhasil dihapus', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification('Error', result.message || 'Gagal menghapus pengumuman', 'error');
                }
            } catch (error) {
                console.error('Error deleting pengumuman:', error);
                showNotification('Error', 'Gagal menghapus pengumuman', 'error');
            }
        }
        
        // Filter data
        function filterData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');
            const cards = document.querySelectorAll('#mobileCards > div');
            let visibleCount = 0;
            
            // Filter desktop rows
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchTerm);
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            // Filter mobile cards
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const isVisible = text.includes(searchTerm);
                card.style.display = isVisible ? '' : 'none';
            });
            
            // Update counter
            document.getElementById('totalCount').textContent = visibleCount;
        }
        
        // Show loading state
        function showLoading(show, type = 'general') {
            if (type === 'users') {
                // Handle users loading if needed
                return;
            }
            
            const loadingState = document.getElementById('loadingState');
            const tableContainer = document.getElementById('tableContainer');
            const emptyState = document.getElementById('emptyState');
            const mobileCards = document.getElementById('mobileCards');
            
            if (show) {
                loadingState.classList.remove('hidden');
                if (tableContainer) tableContainer.classList.add('hidden');
                if (mobileCards) mobileCards.classList.add('hidden');
                if (emptyState) emptyState.classList.add('hidden');
            } else {
                loadingState.classList.add('hidden');
            }
        }
        
        function showSubmitLoading(show) {
            const confirmBtnText = document.getElementById('confirmBtnText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const confirmBtn = document.getElementById('confirmBtn');
            
            if (show) {
                confirmBtn.disabled = true;
                confirmBtnText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
            } else {
                confirmBtn.disabled = false;
                confirmBtnText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
            }
        }
        
        // Notification functions
        function showNotification(title, message, type = 'success') {
            const notif = document.getElementById('notification');
            const icon = document.getElementById('notifIcon');
            const notifTitle = document.getElementById('notifTitle');
            const notifMessage = document.getElementById('notifMessage');
            
            // Set styles based on type
            notif.className = 'fixed top-4 right-4 rounded-lg shadow-lg p-4 z-50 max-w-sm';
            
            if (type === 'success') {
                notif.classList.add('bg-green-50', 'border-l-4', 'border-green-500', 'text-green-700');
                icon.textContent = 'check_circle';
                icon.className = 'material-icons-outlined text-green-500';
            } else if (type === 'error') {
                notif.classList.add('bg-red-50', 'border-l-4', 'border-red-500', 'text-red-700');
                icon.textContent = 'error';
                icon.className = 'material-icons-outlined text-red-500';
            } else if (type === 'info') {
                notif.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500', 'text-blue-700');
                icon.textContent = 'info';
                icon.className = 'material-icons-outlined text-blue-500';
            }
            
            // Set content
            notifTitle.textContent = title;
            notifMessage.textContent = message;
            
            // Show notification
            notif.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                notif.classList.add('hidden');
            }, 5000);
        }
        
        function hideNotif() {
            document.getElementById('notification').classList.add('hidden');
        }
    </script>
</body>
</html>
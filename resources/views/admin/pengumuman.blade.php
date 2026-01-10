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
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-poppins bg-background text-text">
    <script>
    // Data users dari PHP ke JavaScript - HARUS DI ATAS
    const usersData = @json($users ?? []);
    console.log('Users data loaded:', usersData.length, 'users');
</script>
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
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex gap-3">
                        <button id="filterBtn" class="px-4 py-2 border rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined">filter_list</span>
                            Filter
                        </button>
                        <button id="createBtn" class="px-4 py-2 bg-primary text-white rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined">add</span>
                            Buat
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
                            Total: <span id="totalCount" class="font-semibold">{{ count($pengumuman) }}</span>
                        </span>
                    </div>
                    
                    <div class="p-4">
                        <!-- Desktop Table -->
                        <div class="desktop-only scrollable-table">
                            <table class="w-full min-w-[1000px]">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="p-3 text-left text-sm font-semibold">No</th>
                                        <th class="p-3 text-left text-sm font-semibold">Judul</th>
                                        <th class="p-3 text-left text-sm font-semibold">Isi</th>
                                        <th class="p-3 text-left text-sm font-semibold">Kepada</th>
                                        <th class="p-3 text-left text-sm font-semibold">Lampiran</th>
                                        <th class="p-3 text-left text-sm font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach($pengumuman as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $loop->iteration }}</td>
                                        <td class="p-3">{{ $item->judul }}</td>
                                        <td class="p-3 max-w-xs truncate">{{ Str::limit($item->isi_pesan, 50) }}</td>
                                        <td class="p-3">
    {{ $item->users->pluck('name')->join(', ') ?: '-' }}
</td>
                                        <td class="p-3">
                                            @if($item->lampiran)
                                            <a href="{{ asset('storage/pengumuman/' . $item->lampiran) }}" 
                                               target="_blank" class="text-primary hover:underline">
                                                Lihat
                                            </a>
                                            @else - @endif
                                        </td>
                                        <td class="p-3">
                                            <div class="flex gap-2">
                                                <button onclick="editPengumuman({{ $item->id }})" 
                                                        class="text-blue-600 hover:text-blue-800">
                                                    Edit
                                                </button>
                                                <button onclick="deletePengumuman({{ $item->id }})" 
                                                        class="text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Cards -->
                        <div class="mobile-only space-y-4" id="mobileCards">
                            @foreach($pengumuman as $item)
                            <div class="border rounded-lg p-4 card-hover">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold">{{ $item->judul }}</h3>
                                        <p class="text-sm text-gray-500">{{ $item->kepada }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="editPengumuman({{ $item->id }})" 
                                                class="text-blue-600">Edit</button>
                                        <button onclick="deletePengumuman({{ $item->id }})" 
                                                class="text-red-600">Hapus</button>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($item->isi_pesan, 100) }}</p>
                                @if($item->lampiran)
                                <a href="{{ asset('storage/pengumuman/' . $item->lampiran) }}" 
                                   target="_blank" class="text-sm text-primary">
                                    📎 Lampiran
                                </a>
                                @endif
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
        <div class="bg-white rounded-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-lg font-semibold"></h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div id="modalContent" class="mb-6"></div>
                <div class="flex justify-end gap-3">
                    <button onclick="closeModal()" class="px-4 py-2 border rounded-lg">Batal</button>
                    <button id="confirmBtn" class="px-4 py-2 bg-primary text-white rounded-lg">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 bg-white rounded-lg shadow-lg p-4 hidden z-50">
        <div class="flex items-center gap-3">
            <span id="notifIcon" class="material-icons-outlined"></span>
            <div>
                <p id="notifTitle" class="font-semibold"></p>
                <p id="notifMessage" class="text-sm text-gray-600"></p>
            </div>
            <button onclick="hideNotif()" class="ml-4 text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Simple state management
        let currentPage = 1;
        let currentAction = '';
        let currentId = null;
        
        // Modal functions
        function showModal(title, content, confirmText = 'Simpan', confirmColor = 'primary') {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('confirmBtn').textContent = confirmText;
            document.getElementById('modal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            currentAction = '';
            currentId = null;
        }
        
        // Notification
        function showNotification(title, message, type = 'success') {
            const notif = document.getElementById('notification');
            const icon = document.getElementById('notifIcon');
            
            notif.className = `fixed top-4 right-4 bg-white rounded-lg shadow-lg p-4 flex items-center gap-3 z-50`;
            
            if(type === 'success') {
                icon.textContent = 'check_circle';
                notif.classList.add('border-l-4', 'border-green-500');
            } else if(type === 'error') {
                icon.textContent = 'error';
                notif.classList.add('border-l-4', 'border-red-500');
            }
            
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifMessage').textContent = message;
            notif.classList.remove('hidden');
            
            setTimeout(hideNotif, 3000);
        }
        
        function hideNotif() {
            document.getElementById('notification').classList.add('hidden');
        }
        
        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Create button
            document.getElementById('createBtn').addEventListener('click', function() {
                currentAction = 'create';
                showModal(
    'Buat Pengumuman',
    getFormTemplate({}, usersData),
    'Simpan',
    'primary'
);

            });
            
            // Search
            document.getElementById('searchInput').addEventListener('input', function(e) {
                filterData(e.target.value);
            });
            
            // Confirm button
            document.getElementById('confirmBtn').addEventListener('click', handleConfirm);
            
            // Escape key
            document.addEventListener('keydown', function(e) {
                if(e.key === 'Escape') closeModal();
            });
        });
        
function getFormTemplate(data = {}, users = []) {
    console.log('Users received in template:', users.length);

    return `
        <form id="pengumumanForm" onsubmit="return false;">
            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Judul *</label>
                    <input type="text" id="judulInput" name="judul"
                        value="${data.judul || ''}"
                        class="w-full border rounded-lg p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Isi Pesan *</label>
                    <textarea id="isiInput" name="isi_pesan" rows="3"
                        class="w-full border rounded-lg p-2" required>${data.isi_pesan || ''}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Kepada *</label>
                    <select id="usersSelect" name="users[]" multiple
                        class="w-full border rounded-lg p-2" required>
                        ${users.map(u => `
                            <option value="${u.id}"
                                ${data.users?.some(du => du.id === u.id) ? 'selected' : ''}>
                                ${u.name}
                            </option>
                        `).join('')}
                        ${users.length === 0 ? '<option disabled>TIDAK ADA USER</option>' : ''}

                    </select>
                    <small class="text-gray-500">
                        Tahan Ctrl / Shift untuk memilih lebih dari satu
                    </small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Lampiran</label>
                    <input type="file" id="fileInput" name="lampiran"
                        class="w-full border rounded-lg p-2">
                </div>

            </div>
        </form>
    `;
}

let cachedUsers = [];

async function fetchUsers() {
    if (cachedUsers.length > 0) {
        return cachedUsers;
    }

    const res = await fetch('/users/data', {
        headers: { 'Accept': 'application/json' }
    });

    const data = await res.json();
    cachedUsers = data;

    return data;
}

async function handleConfirm() {
    try {
        // CARI ELEMEN DARI DALAM MODAL, BUKAN DARI DOCUMENT GLOBAL
        const modal = document.getElementById('modal');
        
        // Cari elemen input dari dalam modal
        const judulInput = modal.querySelector('#judulInput');
        const isiInput = modal.querySelector('#isiInput');
        const usersSelect = modal.querySelector('#usersSelect');
        const kepadaInput = modal.querySelector('#kepadaInput');
        const fileInput = modal.querySelector('#fileInput');
        
        console.log('Elements found IN MODAL:', {
            judulInput: !!judulInput,
            isiInput: !!isiInput,
            usersSelect: !!usersSelect,
            kepadaInput: !!kepadaInput,
            fileInput: !!fileInput,
            modal: !!modal
        });
        
        if (!judulInput || !isiInput) {
            showNotification('Error', 'Form tidak lengkap', 'error');
            return;
        }
        
        const formData = new FormData();
        const judul = judulInput.value.trim();
        const isi = isiInput.value.trim();
        
        if (!judul || !isi) {
            showNotification('Error', 'Judul dan Isi Pesan wajib diisi!', 'error');
            return;
        }
        
        formData.append('judul', judul);
        formData.append('isi_pesan', isi);
        
        // Untuk multiple select users
        if (usersSelect) {
            const selectedUsers = Array.from(usersSelect.selectedOptions).map(option => option.value);
            if (selectedUsers.length > 0) {
                // Kirim sebagai array
                selectedUsers.forEach(userId => {
                    formData.append('users[]', userId);
                });
            }
        }
        
        // Fallback ke input biasa

        
        if (fileInput && fileInput.files[0]) {
            formData.append('lampiran', fileInput.files[0]);
        }
        
        // Debug: lihat data yang akan dikirim
        console.log('Data to send:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ':', pair[1]);
        }
        
        // Tentukan URL dan method
        let url = '/pengumuman';
        let method = 'POST';
        
        if (currentAction === 'edit') {
            url = `/pengumuman/${currentId}`;
            method = 'PUT';
            formData.append('_method', 'PUT');
        }
        
        console.log(`Sending ${method} request to:`, url);
        
        // Kirim request
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });
        
        console.log('Response status:', response.status);
        
        const result = await response.json();
        console.log('Server response:', result);
        
        if (result.success) {
            showNotification('Berhasil', result.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            // Tampilkan error detail jika ada
            let errorMsg = result.message || 'Terjadi kesalahan';
            if (result.errors) {
                errorMsg = Object.values(result.errors).flat().join(', ');
            }
            showNotification('Error', errorMsg, 'error');
        }
        
        closeModal();
    } catch (error) {
        console.error('Error in handleConfirm:', error);
        showNotification('Error', 'Gagal menyimpan data: ' + error.message, 'error');
    }
}
        // Edit function
        async function editPengumuman(id) {
            try {
                const response = await fetch(`/pengumuman/${id}`);
                const data = await response.json();
                
                currentAction = 'edit';
                currentId = id;
                showModal(
    'Edit Pengumuman',
    getFormTemplate(data, usersData),
    'Update',
    'primary'
);

            } catch(error) {
                showNotification('Error', 'Gagal memuat data', 'error');
            }
        }
        
        // Delete function
        async function deletePengumuman(id) {
            if(!confirm('Hapus pengumuman ini?')) return;
            
            try {
                const response = await fetch(`/pengumuman/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                
                if(result.success) {
                    showNotification('Berhasil', 'Pengumuman dihapus', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            } catch(error) {
                showNotification('Error', 'Gagal menghapus', 'error');
            }
        }
        
        // Simple search filter
        function filterData(searchTerm) {
            const rows = document.querySelectorAll('#tableBody tr');
            const cards = document.querySelectorAll('#mobileCards > div');
            
            const term = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(term) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
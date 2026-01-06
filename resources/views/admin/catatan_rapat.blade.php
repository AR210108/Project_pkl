<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Catatan Rapat Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#dc2626",
                        "background-light": "#f8fafc",
                        "background-dark": "#0f172a",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e293b",
                        "border-light": "#e2e8f0",
                        "border-dark": "#334155",
                        "text-primary-light": "#1e293b",
                        "text-primary-dark": "#f1f5f9",
                        "text-secondary-light": "#64748b",
                        "text-secondary-dark": "#94a3b8",
                        "highlight-light": "#f1f5f9",
                        "highlight-dark": "#334155",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "0.75rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>
    <style>
        .tooltip {
            position: relative;
        }
        .tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        .tooltip:hover::after {
            opacity: 1;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-content {
            animation: slideUp 0.3s;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark">

    <div class="flex h-screen">

                <!-- Sidebar -->
        @include('admin/templet/sider')
        <div class="flex-1 flex flex-col">
            <header class="h-20 flex-shrink-0 border-b border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark flex items-center px-6">
                <h1 class="text-xl font-semibold">Management Catatan Rapat</h1>
            </header>
            
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div id="successNotification" class="notification bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg mb-6">
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined">check_circle</span>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    
                    <h2 class="text-3xl font-bold text-text-primary-light dark:text-text-primary-dark mb-6">Catatan Rapat</h2>
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <button id="createBtn"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary text-white font-semibold px-5 py-3 rounded-lg hover:bg-primary/90 transition-colors shadow-sm">
                            <span class="material-icons-outlined">add</span>
                            Buat Catatan Rapat Baru
                        </button>
                        
                        <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-4">
                            <div class="relative w-full sm:w-72">
                                <span class="material-icons-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">search</span>
                                <input id="searchInput"
                                       class="w-full bg-highlight-light dark:bg-highlight-dark border-0 rounded-lg pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                                       placeholder="Search..." type="text" />
                            </div>
                            <button id="filterBtn"
                                    class="w-full sm:w-auto bg-highlight-light dark:bg-highlight-dark font-semibold px-5 py-3 rounded-lg hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                                Filter
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-sm overflow-hidden border border-border-light dark:border-border-dark">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-highlight-light dark:bg-highlight-dark text-xs uppercase text-text-secondary-light dark:text-text-secondary-dark">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">NO</th>
                                        <th class="px-6 py-3" scope="col">ID</th>
                                        <th class="px-6 py-3" scope="col">ID Users</th>
                                        <th class="px-6 py-3" scope="col">Tanggal</th>
                                        <th class="px-6 py-3" scope="col">Peserta</th>
                                        <th class="px-6 py-3" scope="col">Topik</th>
                                        <th class="px-6 py-3" scope="col">Hasil Diskusi</th>
                                        <th class="px-6 py-3" scope="col">Keputusan</th>
                                        <th class="px-6 py-3" scope="col">Penugasan</th>
                                        <th class="px-6 py-3 text-center" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @forelse($catatanRapat ?? [] as $index => $item)
                                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="{{ $item->id }}">
                                        <td class="px-6 py-4">{{ $catatanRapat->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $item->id }}</td>
                                        <td class="px-6 py-4">{{ $item->user_id }}</td>
                                        <td class="px-6 py-4">{{ $item->formatted_tanggal }}</td>
                                        <td class="px-6 py-4">{{ $item->peserta }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $item->topik }}</td>
                                        <td class="px-6 py-4">{{ \Illuminate\Support\Str::limit($item->hasil_diskusi, 50) }}</td>
                                        <td class="px-6 py-4">{{ \Illuminate\Support\Str::limit($item->keputusan, 50) }}</td>
                                        <td class="px-6 py-4">{{ $item->penugasan }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-2">
                                                <button class="view-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" 
                                                        data-id="{{ $item->id }}"
                                                        data-topik="{{ $item->topik }}"
                                                        data-tanggal="{{ $item->formatted_tanggal }}"
                                                        data-peserta="{{ $item->peserta }}"
                                                        data-hasil="{{ $item->hasil_diskusi }}"
                                                        data-keputusan="{{ $item->keputusan }}"
                                                        data-penugasan="{{ $item->penugasan }}"
                                                        data-tooltip="Detail">
                                                    <span class="material-icons-outlined text-green-500">visibility</span>
                                                </button>
                                                <button class="edit-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" 
                                                        data-id="{{ $item->id }}"
                                                        data-topik="{{ $item->topik }}"
                                                        data-tanggal="{{ $item->tanggal->format('Y-m-d') }}"
                                                        data-peserta="{{ $item->peserta }}"
                                                        data-hasil="{{ $item->hasil_diskusi }}"
                                                        data-keputusan="{{ $item->keputusan }}"
                                                        data-penugasan="{{ $item->penugasan }}"
                                                        data-tooltip="Edit">
                                                    <span class="material-icons-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" 
                                                        data-id="{{ $item->id }}" 
                                                        data-tooltip="Hapus">
                                                    <span class="material-icons-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-8 text-center text-text-secondary-light dark:text-text-secondary-dark">
                                            <span class="material-icons-outlined text-4xl mb-2">inbox</span>
                                            <p>Belum ada catatan rapat</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if(isset($catatanRapat) && method_exists($catatanRapat, 'hasPages') && $catatanRapat->hasPages())

                        <div class="px-6 py-4 border-t border-border-light dark:border-border-dark">
                            {{ $catatanRapat->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </main>
            
            <footer class="h-16 flex-shrink-0 flex items-center justify-center bg-highlight-light dark:bg-highlight-dark border-t border-border-light dark:border-border-dark text-sm text-text-secondary-light dark:text-text-secondary-dark">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="formModal" class="modal">
        <div class="modal-content bg-surface-light dark:bg-surface-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark"></h3>
                    <button id="closeFormModal" class="text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <form id="crudForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="itemId" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" required
                                   class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Peserta</label>
                            <input type="text" id="peserta" name="peserta" required
                                   class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
                                   placeholder="Contoh: Tim Manajemen">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Topik Rapat</label>
                        <input type="text" id="topik" name="topik" required
                               class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
                               placeholder="Contoh: Evaluasi Kinerja Q1">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Hasil Diskusi</label>
                        <textarea id="hasil_diskusi" name="hasil_diskusi" rows="4" required
                                  class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
                                  placeholder="Jelaskan hasil diskusi dari rapat..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Keputusan</label>
                        <textarea id="keputusan" name="keputusan" rows="3" required
                                  class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
                                  placeholder="Tuliskan keputusan yang diambil..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Penugasan</label>
                        <input type="text" id="penugasan" name="penugasan" required
                               class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
                               placeholder="Contoh: Bagian Pemasaran">
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelFormBtn" class="px-4 py-2 bg-highlight-light dark:bg-highlight-dark rounded-lg font-medium hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn" class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content bg-surface-light dark:bg-surface-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark">Detail Catatan Rapat</h3>
                    <button id="closeViewModal" class="text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <div id="viewContent" class="space-y-4">
                    <!-- Content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content bg-surface-light dark:bg-surface-dark rounded-lg shadow-xl w-full max-w-md m-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <span class="material-icons-outlined text-red-500 text-3xl mr-3">warning</span>
                    <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
                </div>
                <p class="text-text-secondary-light dark:text-text-secondary-dark mb-6">
                    Apakah Anda yakin ingin menghapus catatan rapat ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-end gap-3">
                    <button id="cancelDelete" class="px-4 py-2 bg-highlight-light dark:bg-highlight-dark rounded-lg font-medium hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                        Batal
                    </button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal elements
        const formModal = document.getElementById('formModal');
        const viewModal = document.getElementById('viewModal');
        const deleteModal = document.getElementById('deleteModal');
        const modalTitle = document.getElementById('modalTitle');
        const crudForm = document.getElementById('crudForm');
        const submitBtn = document.getElementById('submitBtn');
        let currentEditId = null;
        let currentDeleteId = null;

        // Show modal functions
        function showModal(modal) {
            modal.classList.add('show');
        }

        function hideModal(modal) {
            modal.classList.remove('show');
        }

        // Create button
        document.getElementById('createBtn').addEventListener('click', function() {
            currentEditId = null;
            modalTitle.textContent = 'Buat Catatan Rapat Baru';
            submitBtn.textContent = 'Simpan';
            crudForm.reset();
            document.getElementById('itemId').value = '';
            showModal(formModal);
        });

        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentEditId = this.getAttribute('data-id');
                modalTitle.textContent = 'Edit Catatan Rapat';
                submitBtn.textContent = 'Update';
                
                // Populate form with data
                document.getElementById('itemId').value = currentEditId;
                document.getElementById('tanggal').value = this.getAttribute('data-tanggal');
                document.getElementById('peserta').value = this.getAttribute('data-peserta');
                document.getElementById('topik').value = this.getAttribute('data-topik');
                document.getElementById('hasil_diskusi').value = this.getAttribute('data-hasil');
                document.getElementById('keputusan').value = this.getAttribute('data-keputusan');
                document.getElementById('penugasan').value = this.getAttribute('data-penugasan');
                
                showModal(formModal);
            });
        });

        // View buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">Tanggal</h4>
                            <p class="font-semibold">${this.getAttribute('data-tanggal')}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">Peserta</h4>
                            <p class="font-semibold">${this.getAttribute('data-peserta')}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">Topik Rapat</h4>
                        <p class="text-lg font-semibold">${this.getAttribute('data-topik')}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">Hasil Diskusi</h4>
                        <p class="whitespace-pre-wrap">${this.getAttribute('data-hasil')}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">Keputusan</h4>
                        <p class="whitespace-pre-wrap">${this.getAttribute('data-keputusan')}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark mb-1">Penugasan</h4>
                        <p class="font-semibold">${this.getAttribute('data-penugasan')}</p>
                    </div>
                `;
                document.getElementById('viewContent').innerHTML = content;
                showModal(viewModal);
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentDeleteId = this.getAttribute('data-id');
                showModal(deleteModal);
            });
        });

        // Close modal buttons
        document.getElementById('closeFormModal').addEventListener('click', () => hideModal(formModal));
        document.getElementById('closeViewModal').addEventListener('click', () => hideModal(viewModal));
        document.getElementById('cancelFormBtn').addEventListener('click', () => hideModal(formModal));
        document.getElementById('cancelDelete').addEventListener('click', () => hideModal(deleteModal));

        // Close modal when clicking outside
        [formModal, viewModal, deleteModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal(modal);
                }
            });
        });

        // Form submission
        crudForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(crudForm);
            const url = currentEditId ? `/catatan_rapat/${currentEditId}` : '/catatan_rapat';
            const method = currentEditId ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal(formModal);
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000); // Reload untuk melihat perubahan
                } else {
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            });
        });

        // Delete confirmation
        document.getElementById('confirmDelete').addEventListener('click', function() {
            fetch(`/catatan_rapat/${currentDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal(deleteModal);
                    showNotification(data.message, 'success');
                    // Remove row from table
                    const row = document.querySelector(`tr[data-id="${currentDeleteId}"]`);
                    if (row) row.remove();
                } else {
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            });
        });

        // Notification function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification bg-${type === 'success' ? 'green' : 'red'}-500 text-white px-6 py-3 rounded-lg shadow-lg`;
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined">${type === 'success' ? 'check_circle' : 'error'}</span>
                    ${message}
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Auto-hide existing notifications
        setTimeout(() => {
            document.querySelectorAll('.notification').forEach(notif => {
                notif.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => notif.remove(), 300);
            });
        }, 3000);
    </script>
</body>
</html>
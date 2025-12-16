<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pengumuman Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#4F46E5",
                        "background-light": "#F9FAFB",
                        "background-dark": "#111827",
                        "sidebar-light": "#F3F4F6",
                        "sidebar-dark": "#1F2937",
                        "text-light": "#1F2937",
                        "text-dark": "#F9FAFB",
                        "muted-light": "#6B7280",
                        "muted-dark": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151",
                        "card-light": "#FFFFFF",
                        "card-dark": "#1F2937",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
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
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        .nav-item .material-symbols-outlined {
            font-variation-settings: 'FILL' 0;
        }

        .nav-item.active .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }
        
        .material-icons-outlined {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
        
        .active .material-icons-outlined {
            font-weight: bold;
        }
        
        /* Tooltip styles */
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
    </style>
    <!-- Add CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="flex h-screen">
        @include('admin.templet.sider')
        <main class="flex-1 flex flex-col">
            <div class="p-8 flex-1 overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-text-light dark:text-text-dark">Pengumuman</h2>
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <button id="createBtn"
                        class="w-full md:w-auto flex items-center justify-center gap-2 bg-primary text-white font-medium py-2.5 px-5 rounded-lg hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined text-xl">add</span>
                        Buat Pengumuman
                    </button>
                    <div class="flex w-full md:w-auto gap-4">
                        <div class="relative flex-1">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-muted-light dark:text-muted-dark">search</span>
                            <input id="searchInput"
                                class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg pl-10 pr-4 py-2.5 text-text-light dark:text-text-dark placeholder-muted-light dark:placeholder-muted-dark focus:ring-2 focus:ring-primary"
                                placeholder="Search..." type="text" />
                        </div>
                        <button id="filterBtn"
                            class="flex items-center justify-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2.5 px-5 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Filter
                        </button>
                    </div>
                </div>
                <div
                    class="bg-card-light dark:bg-card-dark rounded-lg overflow-hidden shadow-sm border border-border-light dark:border-border-dark">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead
                                class="bg-gray-200 dark:bg-gray-700 text-xs uppercase text-muted-light dark:text-muted-dark font-semibold">
                                <tr>
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Judul Informasi</th>
                                    <th class="px-6 py-4">Isi Pesan</th>
                                    <th class="px-6 py-4">Kepada</th>
                                    <th class="px-6 py-4">Lampiran</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pengumumanTableBody" class="divide-y divide-border-light dark:divide-border-dark">
                                @foreach($pengumuman as $index => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-6 py-4">{{ $index + 1 }}.</td>
                                    <td class="px-6 py-4">{{ $item->judul_informasi }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($item->isi_pesan, 50) }}</td>
                                    <td class="px-6 py-4">{{ $item->judul }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->lampiran)
                                            <a class="text-primary hover:underline" href="{{ asset('storage/pengumuman/' . $item->lampiran) }}" target="_blank">{{ $item->lampiran }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <button class="edit-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="{{ $item->id }}" data-tooltip="Edit">
                                                <span class="material-icons-outlined text-blue-500">edit</span>
                                            </button>
                                            <button class="delete-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="{{ $item->id }}" data-tooltip="Hapus">
                                                <span class="material-icons-outlined text-red-500">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <footer class="bg-gray-200 dark:bg-gray-900 p-4 text-center text-sm text-muted-light dark:text-muted-dark">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Popup -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-card-light dark:bg-card-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold text-text-light dark:text-text-dark"></h3>
                    <button id="closeModal" class="text-muted-light dark:text-muted-dark hover:text-text-light dark:hover:text-text-dark">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <div id="modalContent" class="mb-6">
                    <!-- Content will be dynamically inserted here -->
                </div>
                
                <div class="flex justify-end gap-3">
                    <button id="cancelBtn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button id="confirmBtn" class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get modal elements
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        
        // Get buttons
        const createBtn = document.getElementById('createBtn');
        const editBtns = document.querySelectorAll('.edit-btn');
        const deleteBtns = document.querySelectorAll('.delete-btn');
        
        // Current action (create, edit, delete)
        let currentAction = '';
        let currentId = null;
        
        // Show modal function
        function showModal(title, content, confirmText = 'Simpan', confirmClass = 'bg-primary') {
            modalTitle.textContent = title;
            modalContent.innerHTML = content;
            confirmBtn.textContent = confirmText;
            confirmBtn.className = `px-4 py-2 ${confirmClass} text-white rounded-lg font-medium hover:bg-opacity-90 transition-colors`;
            modal.classList.remove('hidden');
        }
        
        // Hide modal function
        function hideModal() {
            modal.classList.add('hidden');
            currentAction = '';
            currentId = null;
        }
        
        // Create button event
        createBtn.addEventListener('click', function() {
            currentAction = 'create';
            const content = `
                <form id="pengumumanForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul</label>
                        <input type="text" name="judul" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul Informasi</label>
                        <input type="text" name="judul_informasi" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Isi Pesan</label>
                        <textarea name="isi_pesan" rows="4" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Lampiran</label>
                        <div class="flex items-center gap-3">
                            <input type="file" name="lampiran" id="lampiranInput" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <button type="button" id="selectFileBtn" class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                <span class="material-icons-outlined">upload_file</span>
                                Pilih File
                            </button>
                            <span id="fileName" class="text-sm text-muted-light dark:text-muted-dark">Tidak ada file yang dipilih</span>
                        </div>
                    </div>
                </form>
            `;
            showModal('Buat Pengumuman Baru', content, 'Simpan', 'bg-primary');
            
            // File input handling
            document.getElementById('selectFileBtn').addEventListener('click', function() {
                document.getElementById('lampiranInput').click();
            });
            
            document.getElementById('lampiranInput').addEventListener('change', function() {
                const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file yang dipilih';
                document.getElementById('fileName').textContent = fileName;
            });
        });
        
        // Edit buttons event
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                currentAction = 'edit';
                currentId = this.getAttribute('data-id');
                
                // Fetch pengumuman data
                fetch(`/pengumuman/${currentId}`)
                    .then(response => response.json())
                    .then(data => {
                        const content = `
                            <form id="pengumumanForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul</label>
                                    <input type="text" name="judul" value="${data.judul}" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul Informasi</label>
                                    <input type="text" name="judul_informasi" value="${data.judul_informasi}" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Isi Pesan</label>
                                    <textarea name="isi_pesan" rows="4" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none" required>${data.isi_pesan}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Lampiran</label>
                                    <div class="flex items-center gap-3">
                                        <input type="file" name="lampiran" id="lampiranInput" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <button type="button" id="selectFileBtn" class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                            <span class="material-icons-outlined">upload_file</span>
                                            Ganti File
                                        </button>
                                        <span id="fileName" class="text-sm text-muted-light dark:text-muted-dark">${data.lampiran || 'Tidak ada file'}</span>
                                    </div>
                                </div>
                            </form>
                        `;
                        showModal('Edit Pengumuman', content, 'Update', 'bg-blue-500');
                        
                        // File input handling
                        document.getElementById('selectFileBtn').addEventListener('click', function() {
                            document.getElementById('lampiranInput').click();
                        });
                        
                        document.getElementById('lampiranInput').addEventListener('change', function() {
                            const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file';
                            document.getElementById('fileName').textContent = fileName;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
        
        // Delete buttons event
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                currentAction = 'delete';
                currentId = this.getAttribute('data-id');
                const content = `
                    <div class="text-center py-4">
                        <span class="material-icons-outlined text-red-500 text-5xl mb-4">warning</span>
                        <p class="text-lg font-medium text-text-light dark:text-text-dark mb-2">Konfirmasi Hapus</p>
                        <p class="text-muted-light dark:text-muted-dark">Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                `;
                showModal('Hapus Pengumuman', content, 'Hapus', 'bg-red-500');
            });
        });
        
        // Close modal events
        closeModal.addEventListener('click', hideModal);
        cancelBtn.addEventListener('click', hideModal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal();
            }
        });
        
        // Confirm button event
        confirmBtn.addEventListener('click', function() {
            if (currentAction === 'create') {
                const form = document.getElementById('pengumumanForm');
                const formData = new FormData(form);
                
                fetch('/pengumuman', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification(data.message, 'success');
                        // Reload page to show updated data
                        location.reload();
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorMessage = '';
                            for (const [key, value] of Object.entries(data.errors)) {
                                errorMessage += `${value.join(', ')}\n`;
                            }
                            showNotification(errorMessage, 'error');
                        } else {
                            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
            } else if (currentAction === 'edit') {
                const form = document.getElementById('pengumumanForm');
                const formData = new FormData(form);
                
                fetch(`/pengumuman/${currentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification(data.message, 'success');
                        // Reload page to show updated data
                        location.reload();
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorMessage = '';
                            for (const [key, value] of Object.entries(data.errors)) {
                                errorMessage += `${value.join(', ')}\n`;
                            }
                            showNotification(errorMessage, 'error');
                        } else {
                            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
            } else if (currentAction === 'delete') {
                fetch(`/pengumuman/${currentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification(data.message, 'success');
                        // Reload page to show updated data
                        location.reload();
                    } else {
                        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
            }
        });
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#pengumumanTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        // Notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.textContent = message;
            
            // Add to body
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>

</body>

</html>
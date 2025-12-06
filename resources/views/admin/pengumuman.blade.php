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
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="flex h-screen">
        @include('admin/templet/sider')
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
                            <input
                                class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg pl-10 pr-4 py-2.5 text-text-light dark:text-text-dark placeholder-muted-light dark:placeholder-muted-dark focus:ring-2 focus:ring-primary"
                                placeholder="Search..." type="text" />
                        </div>
                        <button
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
                                    <th class="px-6 py-4">Judul</th>
                                    <th class="px-6 py-4">Judul Informasi</th>
                                    <th class="px-6 py-4">Isi Pesan</th>
                                    <th class="px-6 py-4">Lampiran</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-6 py-4">1.</td>
                                    <td class="px-6 py-4">Rapat Tahunan</td>
                                    <td class="px-6 py-4">Jadwal Rapat Tahunan 2025</td>
                                    <td class="px-6 py-4">Diharapkan kehadiran seluruh...</td>
                                    <td class="px-6 py-4">
                                        <a class="text-primary hover:underline" href="#">document.pdf</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <button class="edit-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="1" data-tooltip="Edit">
                                                <span class="material-icons-outlined text-blue-500">edit</span>
                                            </button>
                                            <button class="delete-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="1" data-tooltip="Hapus">
                                                <span class="material-icons-outlined text-red-500">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-6 py-4">2.</td>
                                    <td class="px-6 py-4">Libur Nasional</td>
                                    <td class="px-6 py-4">Pengumuman Hari Libur</td>
                                    <td class="px-6 py-4">Kantor akan libur pada tanggal...</td>
                                    <td class="px-6 py-4">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <button class="edit-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="2" data-tooltip="Edit">
                                                <span class="material-icons-outlined text-blue-500">edit</span>
                                            </button>
                                            <button class="delete-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="2" data-tooltip="Hapus">
                                                <span class="material-icons-outlined text-red-500">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-6 py-4">3.</td>
                                    <td class="px-6 py-4">Maintenance Sistem</td>
                                    <td class="px-6 py-4">Jadwal Pemeliharaan Sistem</td>
                                    <td class="px-6 py-4">Akan dilakukan pemeliharaan...</td>
                                    <td class="px-6 py-4">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <button class="edit-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="3" data-tooltip="Edit">
                                                <span class="material-icons-outlined text-blue-500">edit</span>
                                            </button>
                                            <button class="delete-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="3" data-tooltip="Hapus">
                                                <span class="material-icons-outlined text-red-500">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-6 py-4">4.</td>
                                    <td class="px-6 py-4">Acara Perusahaan</td>
                                    <td class="px-6 py-4">Family Gathering 2025</td>
                                    <td class="px-6 py-4">Mari bergabung dalam acara...</td>
                                    <td class="px-6 py-4">
                                        <a class="text-primary hover:underline" href="#">brosur.jpg</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <button class="edit-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="4" data-tooltip="Edit">
                                                <span class="material-icons-outlined text-blue-500">edit</span>
                                            </button>
                                            <button class="delete-btn tooltip p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" data-id="4" data-tooltip="Hapus">
                                                <span class="material-icons-outlined text-red-500">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
        }
        
        // Create button event
        createBtn.addEventListener('click', function() {
            const content = `
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul</label>
                        <input type="text" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul Informasi</label>
                        <input type="text" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Isi Pesan</label>
                        <textarea rows="4" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Lampiran</label>
                        <div class="flex items-center gap-3">
                            <button type="button" class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                <span class="material-icons-outlined">upload_file</span>
                                Pilih File
                            </button>
                            <span class="text-sm text-muted-light dark:text-muted-dark">Tidak ada file yang dipilih</span>
                        </div>
                    </div>
                </form>
            `;
            showModal('Buat Pengumuman Baru', content, 'Simpan', 'bg-primary');
        });
        
        // Edit buttons event
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const content = `
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul</label>
                            <input type="text" value="Rapat Tahunan" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Judul Informasi</label>
                            <input type="text" value="Jadwal Rapat Tahunan 2025" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Isi Pesan</label>
                            <textarea rows="4" class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">Diharapkan kehadiran seluruh karyawan dalam rapat tahunan yang akan diselenggarakan pada tanggal 20 Juni 2025 di ruang rapat utama.</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light dark:text-text-dark mb-1">Lampiran</label>
                            <div class="flex items-center gap-3">
                                <button type="button" class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    <span class="material-icons-outlined">upload_file</span>
                                    Ganti File
                                </button>
                                <span class="text-sm text-muted-light dark:text-muted-dark">document.pdf</span>
                            </div>
                        </div>
                    </form>
                `;
                showModal('Edit Pengumuman', content, 'Update', 'bg-blue-500');
            });
        });
        
        // Delete buttons event
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
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
        
        // Confirm button event (placeholder for actual functionality)
        confirmBtn.addEventListener('click', function() {
            // In a real application, this would submit the form or send a request to the server
            alert('Aksi telah dikonfirmasi!');
            hideModal();
        });
    </script>

</body>

</html>
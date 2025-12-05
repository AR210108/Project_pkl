<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Catatan Rapat Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#dc2626", // red-600
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-light": "#ffffff", // white
                        "surface-dark": "#1e293b", // slate-800
                        "border-light": "#e2e8f0", // slate-200
                        "border-dark": "#334155", // slate-700
                        "text-primary-light": "#1e293b", // slate-800
                        "text-primary-dark": "#f1f5f9", // slate-100
                        "text-secondary-light": "#64748b", // slate-500
                        "text-secondary-dark": "#94a3b8", // slate-400
                        "highlight-light": "#f1f5f9", // slate-100
                        "highlight-dark": "#334155", // slate-700
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                        lg: "0.75rem", // 12px
                        full: "9999px",
                    },
                },
            },
        };
    </script>
    <style>
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

<body
    class="font-display bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark">
    <div class="flex h-screen">
        @include('admin/templet/header')
        <div class="flex-1 flex flex-col">
            <header
                class="h-20 flex-shrink-0 border-b border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
            </header>
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-3xl font-bold text-text-primary-light dark:text-text-primary-dark mb-6">Catatan
                        Rapat</h2>
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <button id="createBtn"
                            class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary text-white font-semibold px-5 py-3 rounded-lg hover:bg-primary/90 transition-colors shadow-sm">
                            <span class="material-icons-outlined">add</span>
                            Buat Surat Kerjasama
                        </button>
                        <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-4">
                            <div class="relative w-full sm:w-72">
                                <span
                                    class="material-icons-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">search</span>
                                <input
                                    class="w-full bg-highlight-light dark:bg-highlight-dark border-0 rounded-lg pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                                    placeholder="Search..." type="text" />
                            </div>
                            <button
                                class="w-full sm:w-auto bg-highlight-light dark:bg-highlight-dark font-semibold px-5 py-3 rounded-lg hover:bg-border-light dark:hover:bg-border-dark transition-colors">
                                Filter
                            </button>
                        </div>
                    </div>
                    <div
                        class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-sm overflow-hidden border border-border-light dark:border-border-dark">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead
                                    class="bg-highlight-light dark:bg-highlight-dark text-xs uppercase text-text-secondary-light dark:text-text-secondary-dark">
                                    <tr>
                                        <th class="px-6 py-3" scope="col">NO</th>
                                        <th class="px-6 py-3" scope="col">Judul</th>
                                        <th class="px-6 py-3" scope="col">Tanggal</th>
                                        <th class="px-6 py-3" scope="col">Peserta</th>
                                        <th class="px-6 py-3" scope="col">Pembahasan</th>
                                        <th class="px-6 py-3" scope="col">Hasil Diskusi</th>
                                        <th class="px-6 py-3" scope="col">Keputusan</th>
                                        <th class="px-6 py-3" scope="col">Penugasan</th>
                                        <th class="px-6 py-3 text-center" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-border-light dark:border-border-dark">
                                        <td class="px-6 py-4">1.</td>
                                        <td class="px-6 py-4">Rapat Evaluasi Kinerja</td>
                                        <td class="px-6 py-4">15/05/2023</td>
                                        <td class="px-6 py-4">Tim Manajemen</td>
                                        <td class="px-6 py-4">Evaluasi kinerja Q1</td>
                                        <td class="px-6 py-4">Target tercapai 85%</td>
                                        <td class="px-6 py-4">Perbaiki strategi pemasaran</td>
                                        <td class="px-6 py-4">Bagian Pemasaran</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-4">
                                                <button class="edit-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="1" data-tooltip="Edit">
                                                    <span class="material-icons-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="1" data-tooltip="Hapus">
                                                    <span class="material-icons-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-border-light dark:border-border-dark">
                                        <td class="px-6 py-4">2.</td>
                                        <td class="px-6 py-4">Rapat Perencanaan Produk</td>
                                        <td class="px-6 py-4">22/05/2023</td>
                                        <td class="px-6 py-4">Tim Produk & R&D</td>
                                        <td class="px-6 py-4">Peluncuran produk baru</td>
                                        <td class="px-6 py-4">Jadwal peluncuran Juli</td>
                                        <td class="px-6 py-4">Mulai produksi Juni</td>
                                        <td class="px-6 py-4">Tim Produksi</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-4">
                                                <button class="edit-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="2" data-tooltip="Edit">
                                                    <span class="material-icons-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="2" data-tooltip="Hapus">
                                                    <span class="material-icons-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-border-light dark:border-border-dark">
                                        <td class="px-6 py-4">3.</td>
                                        <td class="px-6 py-4">Rapat Anggaran</td>
                                        <td class="px-6 py-4">30/05/2023</td>
                                        <td class="px-6 py-4">Tim Keuangan</td>
                                        <td class="px-6 py-4">Anggaran semester II</td>
                                        <td class="px-6 py-4">Pengajuan peningkatan 10%</td>
                                        <td class="px-6 py-4">Disetujui dengan revisi</td>
                                        <td class="px-6 py-4">Tim Keuangan</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-4">
                                                <button class="edit-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="3" data-tooltip="Edit">
                                                    <span class="material-icons-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="3" data-tooltip="Hapus">
                                                    <span class="material-icons-outlined text-red-500">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4">4.</td>
                                        <td class="px-6 py-4">Rapat Penutupan Proyek</td>
                                        <td class="px-6 py-4">05/06/2023</td>
                                        <td class="px-6 py-4">Tim Proyek</td>
                                        <td class="px-6 py-4">Evaluasi proyek X</td>
                                        <td class="px-6 py-4">Proyek selesai tepat waktu</td>
                                        <td class="px-6 py-4">Dokumentasi lengkap</td>
                                        <td class="px-6 py-4">Tim Dokumentasi</td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center gap-4">
                                                <button class="edit-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="4" data-tooltip="Edit">
                                                    <span class="material-icons-outlined text-blue-500">edit</span>
                                                </button>
                                                <button class="delete-btn tooltip p-2 rounded-full hover:bg-highlight-light dark:hover:bg-highlight-dark transition-colors" data-id="4" data-tooltip="Hapus">
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
            </main>
            <footer
                class="h-16 flex-shrink-0 flex items-center justify-center bg-highlight-light dark:bg-highlight-dark border-t border-border-light dark:border-border-dark text-sm text-text-secondary-light dark:text-text-secondary-dark">
                Copyright ©2025 by digicity.id
            </footer>
        </div>
    </div>

    <!-- Modal Popup -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark"></h3>
                    <button id="closeModal" class="text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                
                <div id="modalContent" class="mb-6">
                    <!-- Content will be dynamically inserted here -->
                </div>
                
                <div class="flex justify-end gap-3">
                    <button id="cancelBtn" class="px-4 py-2 bg-highlight-light dark:bg-highlight-dark rounded-lg font-medium hover:bg-border-light dark:hover:bg-border-dark transition-colors">
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
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Judul Rapat</label>
                        <input type="text" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Tanggal</label>
                        <input type="date" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Peserta</label>
                        <input type="text" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Pembahasan</label>
                        <textarea rows="3" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Hasil Diskusi</label>
                        <textarea rows="3" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Keputusan</label>
                        <textarea rows="2" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Penugasan</label>
                        <input type="text" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </form>
            `;
            showModal('Buat Catatan Rapat Baru', content, 'Simpan', 'bg-primary');
        });
        
        // Edit buttons event
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const content = `
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Judul Rapat</label>
                            <input type="text" value="Rapat Evaluasi Kinerja" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Tanggal</label>
                            <input type="date" value="2023-05-15" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Peserta</label>
                            <input type="text" value="Tim Manajemen" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Pembahasan</label>
                            <textarea rows="3" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">Evaluasi kinerja Q1</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Hasil Diskusi</label>
                            <textarea rows="3" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">Target tercapai 85%</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Keputusan</label>
                            <textarea rows="2" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">Perbaiki strategi pemasaran</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1">Penugasan</label>
                            <input type="text" value="Bagian Pemasaran" class="w-full bg-highlight-light dark:bg-highlight-dark border border-border-light dark:border-border-dark rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    </form>
                `;
                showModal('Edit Catatan Rapat', content, 'Update', 'bg-blue-500');
            });
        });
        
        // Delete buttons event
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const content = `
                    <div class="text-center py-4">
                        <span class="material-icons-outlined text-red-500 text-5xl mb-4">warning</span>
                        <p class="text-lg font-medium text-text-primary-light dark:text-text-primary-dark mb-2">Konfirmasi Hapus</p>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Apakah Anda yakin ingin menghapus catatan rapat ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                `;
                showModal('Hapus Catatan Rapat', content, 'Hapus', 'bg-red-500');
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
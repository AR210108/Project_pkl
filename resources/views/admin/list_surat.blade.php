<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>List Surat Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#000000",
                        "background-light": "#FFFFFF",
                        "background-dark": "#121212",
                        "surface-light": "#F3F4F6",
                        "surface-dark": "#1E1E1E",
                        "text-light": "#111827",
                        "text-dark": "#E5E7EB",
                        "subtle-light": "#6B7280",
                        "subtle-dark": "#9CA3AF",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                },
            },
        };
    </script>
    <style>
        .material-icons-outlined {
            font-family: 'Material Icons Outlined';
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

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        .material-symbols-outlined.filled {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
        
        /* Modal styles */
        .modal {
            transition: opacity 0.3s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="flex h-screen">
        <aside class="w-64 flex-shrink-0 bg-surface-light dark:bg-surface-dark flex flex-col p-6">
            @include('admin/templet/header')
        </aside>
        <main class="flex-1 flex flex-col overflow-y-auto">
            <div class="flex-grow p-8 md:p-12">
                <h1 class="text-4xl font-bold text-text-light dark:text-text-dark mb-8">List Surat</h1>
                <div class="flex flex-wrap items-center gap-4 mb-8">
                    <button id="buatSuratBtn"
                        class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-semibold px-6 py-3 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <span class="material-symbols-outlined text-2xl">add</span>
                        Buat Surat Kerjasama
                    </button>
                    <div class="relative flex-grow max-w-sm">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-subtle-light dark:text-subtle-dark">search</span>
                        <input
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-full py-3 pl-12 pr-4 placeholder-subtle-light dark:placeholder-subtle-dark focus:ring-2 focus:ring-primary"
                            placeholder="Search..." type="text" />
                    </div>
                    <button
                        class="bg-gray-200 dark:bg-gray-700 text-text-light dark:text-text-dark font-semibold px-8 py-3 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Filter
                    </button>
                </div>
                
                <!-- Template Grid -->
                <div id="templateGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 mb-8">
                    <!-- Templates will be inserted here by JavaScript -->
                </div>
                
                <!-- Pagination -->
                <div id="pagination" class="flex justify-center items-center space-x-1 sm:space-x-2 mb-8">
                    <!-- Pagination buttons will be inserted here by JavaScript -->
                </div>
            </div>
            <footer class="bg-gray-200 dark:bg-gray-800 text-center py-4 mt-auto">
                <p class="text-sm text-subtle-light dark:text-subtle-dark">Copyright ©2025 by digicity.id</p>
            </footer>
        </main>
    </div>

    <!-- Modal untuk Buat Surat Kerjasama -->
    <div id="buatSuratModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buat Surat Kerjasama</h3>
                    <p class="text-gray-600 dark:text-gray-400">Isi form di bawah untuk membuat surat kerjasama baru</p>
                </div>
                <button onclick="closeBuatSuratModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4">
                <form id="buatSuratForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan 1</label>
                            <input type="text" id="namaPerusahaan1" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan 2</label>
                            <input type="text" id="namaPerusahaan2" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Perusahaan 1</label>
                            <textarea id="alamatPerusahaan1" rows="2" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Perusahaan 2</label>
                            <textarea id="alamatPerusahaan2" rows="2" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Penanggung Jawab 1</label>
                            <input type="text" id="penanggungJawab1" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Penanggung Jawab 2</label>
                            <input type="text" id="penanggungJawab2" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan Penanggung Jawab 1</label>
                            <input type="text" id="jabatan1" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan Penanggung Jawab 2</label>
                            <input type="text" id="jabatan2" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Kerjasama</label>
                        <input type="text" id="judulKerjasama" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lingkup Kerjasama</label>
                        <textarea id="lingkupKerjasama" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                            <input type="date" id="tanggalMulai" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                            <input type="date" id="tanggalSelesai" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nilai Kontrak</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" id="nilaiKontrak" class="w-full pl-10 pr-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan Tambahan</label>
                        <textarea id="keterangan" rows="3" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="closeBuatSuratModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button onclick="submitBuatSurat()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-opacity-90 transition-colors">
                    Buat Surat
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Template Preview -->
    <div id="templateModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-white"></h3>
                    <p id="modalSubtitle" class="text-gray-600 dark:text-gray-400"></p>
                </div>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4 bg-gray-100 dark:bg-gray-900 flex justify-center">
                <div class="bg-white dark:bg-gray-800 w-full aspect-[3/4] flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-subtle-light dark:text-subtle-dark">description</span>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="downloadTemplate()" class="flex items-center bg-primary text-white font-medium py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download
                </button>
                <button onclick="editTemplate()" class="flex items-center bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>

    <script>
        // Fungsi untuk dropdown menu Surat Kerjasama
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol Buat Surat Kerjasama
            const buatSuratBtn = document.getElementById('buatSuratBtn');
            if (buatSuratBtn) {
                buatSuratBtn.addEventListener('click', function() {
                    document.getElementById('buatSuratModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Cari elemen dropdown Surat Kerjasama
            const suratKerjasamaMenu = document.querySelector('a[href="/surat_kerjasama"]');
            const templateSuratMenu = document.querySelector('a[href="/template_surat"]');
            const listTugosMenu = document.querySelector('a[href="/list_tugos"]');
            
            // Jika elemen ada, tambahkan event listener untuk dropdown
            if (suratKerjasamaMenu) {
                // Buat elemen dropdown
                const dropdown = document.createElement('div');
                dropdown.className = 'pl-6 mt-1 space-y-1';
                
                // Pindahkan menu Template Surat dan List Tugos ke dalam dropdown
                if (templateSuratMenu && listTugosMenu) {
                    dropdown.appendChild(templateSuratMenu);
                    dropdown.appendChild(listTugosMenu);
                    
                    // Sisipkan dropdown setelah menu Surat Kerjasama
                    suratKerjasamaMenu.parentNode.insertBefore(dropdown, suratKerjasamaMenu.nextSibling);
                    
                    // Tambahkan ikon expand pada menu Surat Kerjasama
                    const expandIcon = document.createElement('span');
                    expandIcon.className = 'material-icons-outlined ml-auto';
                    expandIcon.textContent = 'expand_more';
                    suratKerjasamaMenu.appendChild(expandIcon);
                    
                    // Tambahkan event listener untuk toggle dropdown
                    suratKerjasamaMenu.addEventListener('click', function(e) {
                        e.preventDefault();
                        dropdown.classList.toggle('hidden');
                        
                        // Ubah ikon berdasarkan status dropdown
                        if (dropdown.classList.contains('hidden')) {
                            expandIcon.textContent = 'expand_more';
                        } else {
                            expandIcon.textContent = 'expand_less';
                        }
                    });
                    
                    // Tandai menu aktif
                    if (window.location.pathname.includes('/list_tugos')) {
                        suratKerjasamaMenu.classList.add('bg-primary', 'bg-opacity-10', 'text-primary', 'font-medium');
                        listTugosMenu.classList.add('bg-primary', 'bg-opacity-10', 'text-primary', 'font-medium');
                        dropdown.classList.remove('hidden');
                        expandIcon.textContent = 'expand_less';
                    }
                    
                    // Tambahkan badge angka pada menu List Tugos jika lebih dari 2
                    // Hitung jumlah list surat (dalam contoh ini kita hardcode menjadi 9)
                    const jumlahListSurat = 9;
                    
                    if (jumlahListSurat > 2) {
                        // Buat elemen badge
                        const badge = document.createElement('span');
                        badge.className = 'bg-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center ml-2';
                        badge.textContent = jumlahListSurat;
                        
                        // Tambahkan badge ke menu List Tugos
                        listTugosMenu.appendChild(badge);
                    }
                }
            }
            
            // Template data
            const templates = [
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing' },
                { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development' }
            ];

            // Pagination variables
            let currentPage = 1;
            let itemsPerPage = 4; // Default for mobile (2x2 grid)
            let totalPages = Math.ceil(templates.length / itemsPerPage);
            let maxVisiblePages = 3; // Maximum number of page numbers to show at once

            // Function to check if we're on mobile
            function isMobile() {
                return window.innerWidth < 768;
            }

            // Function to update items per page based on screen size
            function updateItemsPerPage() {
                if (isMobile()) {
                    itemsPerPage = 4; // 2 per row, 2 rows
                } else {
                    itemsPerPage = 8; // 4 per row, 2 rows
                }
                totalPages = Math.ceil(templates.length / itemsPerPage);
                
                // Reset to first page if current page is out of bounds
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
            }

            // Function to render templates
            function renderTemplates() {
                const grid = document.getElementById('templateGrid');
                grid.innerHTML = '';
                
                // Calculate start and end indices
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, templates.length);
                
                // Update grid classes based on screen size
                if (isMobile()) {
                    grid.className = 'grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 mb-8';
                } else {
                    grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 mb-8';
                }
                
                // Render templates for current page
                for (let i = startIndex; i < endIndex; i++) {
                    const template = templates[i];
                    
                    const templateElement = document.createElement('div');
                    templateElement.className = `bg-background-light dark:bg-surface-dark rounded-lg p-3 sm:p-4 shadow-md`;
                    
                    templateElement.innerHTML = `
                        <div class="bg-gray-200 dark:bg-gray-700 w-full aspect-[3/4] mb-3 sm:mb-4 cursor-pointer template-preview"
                             onclick="openModal('${template.title}', '${template.subtitle}')">
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-subtle-light dark:text-subtle-dark">description</span>
                            </div>
                        </div>
                        <div class="text-center py-2">
                            <h2 class="font-bold text-text-light dark:text-text-dark text-sm sm:text-base">${template.title}</h2>
                            <p class="text-text-light dark:text-text-dark text-xs sm:text-sm">${template.subtitle}</p>
                        </div>
                    `;
                    
                    grid.appendChild(templateElement);
                }
            }

            // Function to render pagination
            function renderPagination() {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';
                
                // Only show pagination if there's more than one page
                if (totalPages <= 1) return;
                
                // Previous button
                const prevButton = document.createElement('button');
                prevButton.className = `px-2 sm:px-3 py-1 rounded-lg ${currentPage === 1 ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 cursor-not-allowed' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                prevButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
                prevButton.disabled = currentPage === 1;
                prevButton.onclick = () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTemplates();
                        renderPagination();
                    }
                };
                pagination.appendChild(prevButton);
                
                // Calculate visible page range
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                
                // Adjust if we're at the end
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }
                
                // First page and ellipsis if needed
                if (startPage > 1) {
                    const firstButton = document.createElement('button');
                    firstButton.className = `px-2 sm:px-3 py-1 rounded-lg ${1 === currentPage ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                    firstButton.textContent = '1';
                    firstButton.onclick = () => {
                        currentPage = 1;
                        renderTemplates();
                        renderPagination();
                    };
                    pagination.appendChild(firstButton);
                    
                    if (startPage > 2) {
                        const ellipsis = document.createElement('button');
                        ellipsis.className = 'px-2 sm:px-3 py-1 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600';
                        ellipsis.textContent = '...';
                        ellipsis.onclick = () => {
                            // Go to the page before the current visible range
                            currentPage = Math.max(1, startPage - maxVisiblePages);
                            renderTemplates();
                            renderPagination();
                        };
                        pagination.appendChild(ellipsis);
                    }
                }
                
                // Visible page numbers
                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.className = `px-2 sm:px-3 py-1 rounded-lg ${i === currentPage ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                    pageButton.textContent = i;
                    pageButton.onclick = () => {
                        currentPage = i;
                        renderTemplates();
                        renderPagination();
                    };
                    pagination.appendChild(pageButton);
                }
                
                // Last page and ellipsis if needed
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsis = document.createElement('button');
                        ellipsis.className = 'px-2 sm:px-3 py-1 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600';
                        ellipsis.textContent = '...';
                        ellipsis.onclick = () => {
                            // Go to the page after the current visible range
                            currentPage = Math.min(totalPages, endPage + maxVisiblePages);
                            renderTemplates();
                            renderPagination();
                        };
                        pagination.appendChild(ellipsis);
                    }
                    
                    const lastButton = document.createElement('button');
                    lastButton.className = `px-2 sm:px-3 py-1 rounded-lg ${totalPages === currentPage ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                    lastButton.textContent = totalPages;
                    lastButton.onclick = () => {
                        currentPage = totalPages;
                        renderTemplates();
                        renderPagination();
                    };
                    pagination.appendChild(lastButton);
                }
                
                // Next button
                const nextButton = document.createElement('button');
                nextButton.className = `px-2 sm:px-3 py-1 rounded-lg ${currentPage === totalPages ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 cursor-not-allowed' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                nextButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
                nextButton.disabled = currentPage === totalPages;
                nextButton.onclick = () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTemplates();
                        renderPagination();
                    }
                };
                pagination.appendChild(nextButton);
            }

            // Initialize the page
            function init() {
                updateItemsPerPage();
                renderTemplates();
                renderPagination();
            }

            // Handle window resize
            window.addEventListener('resize', () => {
                const oldItemsPerPage = itemsPerPage;
                updateItemsPerPage();
                
                // If items per page changed, re-render
                if (oldItemsPerPage !== itemsPerPage) {
                    renderTemplates();
                    renderPagination();
                }
            });

            // Modal functions for template preview
            function openModal(title, subtitle) {
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('modalSubtitle').textContent = subtitle;
                document.getElementById('templateModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                document.getElementById('templateModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function downloadTemplate() {
                // Simulate download
                alert('Template akan diunduh');
                closeModal();
            }

            function editTemplate() {
                // Simulate edit
                alert('Edit template');
                closeModal();
            }

            // Modal functions for Buat Surat Kerjasama
            function closeBuatSuratModal() {
                document.getElementById('buatSuratModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('buatSuratForm').reset();
            }

            function submitBuatSurat() {
                const form = document.getElementById('buatSuratForm');
                
                // Simple form validation
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                
                // Get form data
                const formData = {
                    namaPerusahaan1: document.getElementById('namaPerusahaan1').value,
                    namaPerusahaan2: document.getElementById('namaPerusahaan2').value,
                    alamatPerusahaan1: document.getElementById('alamatPerusahaan1').value,
                    alamatPerusahaan2: document.getElementById('alamatPerusahaan2').value,
                    penanggungJawab1: document.getElementById('penanggungJawab1').value,
                    penanggungJawab2: document.getElementById('penanggungJawab2').value,
                    jabatan1: document.getElementById('jabatan1').value,
                    jabatan2: document.getElementById('jabatan2').value,
                    judulKerjasama: document.getElementById('judulKerjasama').value,
                    lingkupKerjasama: document.getElementById('lingkupKerjasama').value,
                    tanggalMulai: document.getElementById('tanggalMulai').value,
                    tanggalSelesai: document.getElementById('tanggalSelesai').value,
                    nilaiKontrak: document.getElementById('nilaiKontrak').value,
                    keterangan: document.getElementById('keterangan').value
                };
                
                // In a real application, you would send this data to the server
                console.log('Form data:', formData);
                
                // Show success message
                showToast('Surat kerjasama berhasil dibuat!');
                
                // Close modal
                closeBuatSuratModal();
            }

            // Toast notification function
            function showToast(message) {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toastMessage');
                
                toastMessage.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                
                // Auto hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }

            // Close toast notification
            document.getElementById('closeToast').addEventListener('click', function() {
                document.getElementById('toast').classList.add('translate-y-20', 'opacity-0');
            });

            // Close modal when clicking outside
            document.getElementById('buatSuratModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBuatSuratModal();
                }
            });

            document.getElementById('templateModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!document.getElementById('buatSuratModal').classList.contains('hidden')) {
                        closeBuatSuratModal();
                    }
                    if (!document.getElementById('templateModal').classList.contains('hidden')) {
                        closeModal();
                    }
                }
            });

            // Initialize the page
            init();
        });
    </script>
</body>

</html>
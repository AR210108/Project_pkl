<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manajemen Cuti - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
<!-- Global Routes Object -->
<script>
    window.appRoutes = {
        cuti: {
            index: '{{ route("karyawan.cuti.index") }}',
            data: '{{ route("karyawan.cuti.data") }}',
            store: '{{ route("karyawan.cuti.store") }}',
            stats: '{{ route("karyawan.cuti.stats") }}',
            update: (id) => {
                const url = '{{ route("karyawan.cuti.update", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            destroy: (id) => {
                const url = '{{ route("karyawan.cuti.destroy", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
            edit: (id) => {
                const url = '{{ route("karyawan.cuti.edit", ["cuti" => ":id"]) }}';
                return url.replace(':id', id);
            },
        }
    };
</script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "text-light": "#1e293b",
                        "text-muted-light": "#64748b",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                    boxShadow: {
                        card: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        .material-icons-outlined { font-size: 24px; vertical-align: middle; }

        /* Status Badge */
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-disetujui { background-color: rgba(16, 185, 129, 0.15); color: #065f46; }
        .status-menunggu { background-color: rgba(245, 158, 11, 0.15); color: #92400e; }
        .status-ditolak { background-color: rgba(239, 68, 68, 0.15); color: #991b1b; }

        /* Stats Card */
        .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: white; padding: 1rem; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); position: relative; overflow: hidden; display: flex; align-items: center; gap: 0.75rem; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; }
        .stat-card.blue::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
        .stat-card.red::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
        .stat-card.green::before { background: linear-gradient(180deg, #10b981, #059669); }
        .stat-icon { width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon.blue { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-icon.red { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .stat-icon.green { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
        .stat-content { flex: 1; }
        .stat-label { font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem; font-weight: 500; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; line-height: 1.2; }

        /* Table */
        .data-table { width: 100%; min-width: 800px; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .data-table th { background: #f8fafc; font-weight: 600; color: #374151; font-size: 0.875rem; text-transform: uppercase; }
        .data-table tbody tr:nth-child(even) { background: #f9fafb; }
        .data-table tbody tr:hover { background: #f3f4f6; }
        .scrollable-table-container { width: 100%; overflow-x: auto; overflow-y: hidden; border: 1px solid #e2e8f0; border-radius: 0.5rem; background: white; }

        /* Mobile Responsive */
        @media (max-width: 639px) {
            .desktop-table { display: none !important; }
            .mobile-cards { display: block !important; }
            .desktop-pagination { display: none !important; }
        }
        @media (min-width: 640px) {
            .desktop-table { display: block !important; }
            .mobile-cards { display: none !important; }
            .desktop-pagination { display: flex !important; }
        }

        /* Popup */
        .minimal-popup {
            position: fixed; top: 20px; right: 20px; background: white; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); padding: 16px 20px; display: flex;
            align-items: center; gap: 12px; z-index: 1000; transform: translateX(400px);
            transition: transform 0.3s ease; max-width: 350px; border-left: 4px solid #10b981;
        }
        .minimal-popup.show { transform: translateX(0); }
        .minimal-popup.error { border-left-color: #ef4444; }
        .minimal-popup-icon { flex-shrink: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .minimal-popup.success .minimal-popup-icon { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
        .minimal-popup.error .minimal-popup-icon { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

        .edit-popup {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
            z-index: 1000; opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .edit-popup.show { opacity: 1; visibility: visible; }
        .edit-popup-content {
            background: white; border-radius: 0.75rem; width: 90%; max-width: 500px;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: scale(0.9); transition: transform 0.3s ease;
        }
        .edit-popup.show .edit-popup-content { transform: scale(1); }

        /* Buttons */
        .btn-primary { background-color: #3b82f6; color: white; transition: all 0.2s ease; }
        .btn-primary:hover { background-color: #2563eb; cursor: pointer; }
        .btn-secondary { background-color: #f1f5f9; color: #64748b; transition: all 0.2s ease; }
        .btn-secondary:hover { background-color: #e2e8f0; cursor: pointer; }

        .form-input { border: 1px solid #e2e8f0; transition: all 0.2s ease; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        /* Pagination */
        .desktop-pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 24px; }
        .desktop-page-btn { min-width: 32px; height: 32px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-size: 14px; font-weight: 500; transition: all 0.2s ease; cursor: pointer; }
        .desktop-page-btn.active { background-color: #3b82f6; color: white; }
        .desktop-page-btn:not(.active) { background-color: #f1f5f9; color: #64748b; }
        .desktop-nav-btn { display: flex; justify-content: center; align-items: center; width: 32px; height: 32px; border-radius: 50%; background-color: #f1f5f9; color: #64748b; transition: all 0.2s ease; cursor: pointer; }
        .desktop-nav-btn:hover:not(:disabled) { background-color: #e2e8f0; }

    </style>
</head>

<body class="font-display bg-gray-50 text-gray-800">
    <div class="main-content">
        @include('karyawan.templet.header')
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-3 sm:p-8">
                <!-- Header -->
                <div class="page-header mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Cuti</h1>
                    <p class="text-gray-600">Kelola pengajuan cuti Anda dengan mudah</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card blue">
                        <div class="stat-icon blue"><span class="material-icons-outlined text-xl">calendar_today</span></div>
                        <div class="stat-content">
                            <div class="stat-label">Total Cuti</div>
                            <div class="stat-value" id="stat-total-cuti">12</div>
                        </div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-icon red"><span class="material-icons-outlined text-xl">event_busy</span></div>
                        <div class="stat-content">
                            <div class="stat-label">Terpakai</div>
                            <div class="stat-value" id="stat-cuti-terpakai">0</div>
                        </div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-icon green"><span class="material-icons-outlined text-xl">event_available</span></div>
                        <div class="stat-content">
                            <div class="stat-label">Tersisa</div>
                            <div class="stat-value" id="stat-cuti-tersisa">12</div>
                        </div>
                    </div>
                </div>

                <!-- Data Cuti Panel -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                            <span class="material-icons-outlined text-primary">event_note</span>
                            Riwayat Pengajuan
                        </h3>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-500">Total: <span class="font-bold text-gray-800" id="cutiCount">0</span></span>
                            <!-- Tombol Tambah -->
                            <button id="tambahCutiBtn" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 cursor-pointer">
                                <span class="material-icons-outlined text-sm">add</span>
                                <span class="hidden sm:inline">Ajukan Cuti</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Loading -->
                        <div id="cutiLoading" class="text-center py-10 hidden">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                            <p class="mt-2 text-gray-500 text-sm">Memuat data...</p>
                        </div>

                        <!-- Desktop Table -->
                        <div id="cutiDesktopTable" class="desktop-table hidden">
                            <div class="scrollable-table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">No</th>
                                            <th style="min-width: 150px;">Tanggal</th>
                                            <th style="min-width: 100px;">Durasi</th>
                                            <th style="min-width: 250px;">Keterangan</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 100px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cutiTableBody">
                                        <!-- Data injected by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Cards -->
                        <div id="cuti-mobile-cards" class="mobile-cards space-y-4">
                            <!-- Cards injected by JS -->
                        </div>

                        <!-- No Data State -->
                        <div id="noCutiData" class="text-center py-12 hidden">
                            <span class="material-icons-outlined text-gray-300 text-6xl mb-4">event_busy</span>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengajuan cuti</h3>
                            <p class="text-gray-500">Mulai dengan mengajukan cuti baru</p>
                        </div>

                        <!-- Pagination -->
                        <div id="cutiPaginationContainer" class="desktop-pagination hidden">
                            <button id="cutiPrevPage" class="desktop-nav-btn"><span class="material-icons-outlined text-sm">chevron_left</span></button>
                            <div id="cutiPageNumbers" class="flex gap-1"></div>
                            <button id="cutiNextPage" class="desktop-nav-btn"><span class="material-icons-outlined text-sm">chevron_right</span></button>
                        </div>
                    </div>
                </div>

                <footer class="text-center p-6 mt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-500">Copyright ©{{ date('Y') }} by digicity.id</p>
                </footer>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Cuti -->
    <div id="tambahCutiModal" class="edit-popup">
        <div class="edit-popup-content">
            <div class="edit-popup-header p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="edit-popup-title text-xl font-bold text-gray-800">Ajukan Cuti Baru</h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('tambahCutiModal')"><span class="material-icons-outlined">close</span></button>
            </div>
            <div class="edit-popup-body p-6">
                <form id="tambahCutiForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" required id="inputTanggalMulai"
                                class="form-input w-full px-3 py-2 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" required id="inputTanggalSelesai"
                                class="form-input w-full px-3 py-2 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (Hari) <span class="text-gray-400 text-xs font-normal">- Otomatis</span></label>
                            <input type="number" name="durasi" id="inputDurasi"
                                class="form-input w-full px-3 py-2 rounded-lg bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti</label>
                            <select name="jenis_cuti" required class="form-input w-full px-3 py-2 rounded-lg">
                                <option value="">Pilih Jenis Cuti</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="sakit">Cuti Sakit</option>
                                <option value="penting">Cuti Penting</option>
                                <option value="melahirkan">Cuti Melahirkan</option>
                                <option value="lainnya">Cuti Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" required class="form-input w-full px-3 py-2 rounded-lg"></textarea>
                    </div>
                </form>
            </div>
            <div class="edit-popup-footer p-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('tambahCutiModal')" class="btn-secondary px-4 py-2 rounded-lg font-medium">Batal</button>
                <button type="button" onclick="handleAddCuti()" class="btn-primary px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">send</span> Kirim Pengajuan
                </button>
            </div>
        </div>
    </div>

    <!-- Minimalist Popup -->
    <div id="minimalPopup" class="minimal-popup">
        <div class="minimal-popup-icon"><span class="material-icons-outlined">check</span></div>
        <div class="minimal-popup-content">
            <div class="minimal-popup-title">Berhasil</div>
            <div class="minimal-popup-message">Operasi berhasil dilakukan</div>
        </div>
        <button class="minimal-popup-close"><span class="material-icons-outlined text-sm">close</span></button>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        const API_ROUTES = window.appRoutes?.cuti || {
            data: '/karyawan/cuti/data',
            store: '/karyawan/cuti',
            stats: '/karyawan/cuti/stats',
            update: (id) => `/karyawan/cuti/${id}`,
            destroy: (id) => `/karyawan/cuti/${id}`,
            edit: (id) => `/karyawan/cuti/${id}/edit`,
        };

        let cutiCurrentPage = 1;
        let cutiActiveFilters = ['all'];
        let cutiSearchTerm = '';
        let totalCutiPages = 1;

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', () => {
            console.log('🎯 Cuti Page Loaded');
            initializeCuti();
            setupAutoDurationCalc();
            
            // Event Listeners (DIBUNGKUS DI SINI UNTUK MENCEGAH ERROR)
            
            // 1. Tombol Tambah Cuti
            const tambahBtn = document.getElementById('tambahCutiBtn');
            if (tambahBtn) {
                tambahBtn.addEventListener('click', () => {
                    openModal('tambahCutiModal');
                });
            }

            // 2. Tombol Tutup Popup
            const closePopupBtn = document.querySelector('.minimal-popup-close');
            if (closePopupBtn) {
                closePopupBtn.addEventListener('click', () => {
                    document.getElementById('minimalPopup').classList.remove('show');
                });
            }
            
            // 3. Tutup modal saat klik luar
            document.querySelectorAll('.edit-popup').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal(modal.id);
                });
            });
        });

        // ==================== CORE LOGIC ====================
        async function initializeCuti() {
            try {
                await Promise.all([loadCutiData(), loadCutiStats()]);
            } catch (e) { console.error('Init Error:', e); }
        }

        // ==================== FETCHING DATA ====================
        async function loadCutiData() {
            showLoading();
            try {
                const params = new URLSearchParams({
                    page: cutiCurrentPage,
                    per_page: 10,
                    search: cutiSearchTerm,
                    status: cutiActiveFilters.includes('all') ? 'all' : cutiActiveFilters.join(','),
                    _token: CSRF_TOKEN
                });
                
                const response = await fetch(`${API_ROUTES.data}?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    renderCutiTable(data.data);
                    renderPagination(data.pagination);
                    document.getElementById('cutiCount').innerText = data.pagination.total;
                    hideLoading();
                    
                    if (data.pagination.total === 0) showNoData();
                    else hideNoData();
                }
            } catch (e) {
                console.error('Load Data Error:', e);
                hideLoading();
                showNoData();
            }
        }

        async function loadCutiStats() {
            try {
                const response = await fetch(API_ROUTES.stats);
                const data = await response.json();
                
                if (data.success) {
                    updateCutiStatsUI(data.data);
                }
            } catch (e) { console.error('Load Stats Error:', e); }
        }

        // ==================== RENDERING ====================
        function renderCutiTable(cutiData) {
            const tbody = document.getElementById('cutiTableBody');
            const mobileContainer = document.getElementById('cuti-mobile-cards');
            
            if (!tbody || !mobileContainer) return;
            
            tbody.innerHTML = '';
            mobileContainer.innerHTML = '';

            if (!cutiData || cutiData.length === 0) return;

            cutiData.forEach((item, index) => {
                const globalIndex = (cutiCurrentPage - 1) * 10 + index + 1;
                const formattedDate = new Date(item.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                
                let badgeClass = 'status-menunggu';
                if (item.status === 'disetujui') badgeClass = 'status-disetujui';
                if (item.status === 'ditolak') badgeClass = 'status-ditolak';

                const statusBadge = `<span class="status-badge ${badgeClass}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span>`;
                
                // Tombol aksi hanya untuk 'menunggu'
                const actions = item.status === 'menunggu' 
                    ? `<div class="flex gap-2 justify-center">
                        <button class="text-blue-500 hover:bg-blue-50 p-1 rounded" title="Edit" onclick="alert('Fitur edit belum aktif di demo ini')"><span class="material-icons-outlined text-sm">edit</span></button>
                        <button class="text-red-500 hover:bg-red-50 p-1 rounded" title="Hapus" onclick="handleDeleteCuti(${item.id})"><span class="material-icons-outlined text-sm">delete</span></button>
                       </div>`
                    : `<span class="text-gray-400 text-xs">-</span>`;

                // Desktop Row
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${globalIndex}</td>
                    <td>${formattedDate}</td>
                    <td>${item.durasi} hari</td>
                    <td>${item.keterangan}</td>
                    <td>${statusBadge}</td>
                    <td class="text-center">${actions}</td>
                `;
                tbody.appendChild(tr);

                // Mobile Card
                const card = document.createElement('div');
                card.className = "bg-white border border-gray-200 rounded-lg p-4 shadow-sm";
                card.innerHTML = `
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold text-gray-800">${formattedDate}</span>
                        ${statusBadge}
                    </div>
                    <p class="text-gray-600 text-sm mb-2">${item.keterangan}</p>
                    <div class="text-xs text-gray-500 flex justify-between">
                        <span>${item.durasi} hari • ${item.jenis_cuti}</span>
                        <div class="flex gap-2">${actions}</div>
                    </div>
                `;
                mobileContainer.appendChild(card);
            });
        }

        function renderPagination(pagination) {
            totalCutiPages = pagination.last_page;
            const container = document.getElementById('cutiPageNumbers');
            const paginationContainer = document.getElementById('cutiPaginationContainer');
            const prevBtn = document.getElementById('cutiPrevPage');
            const nextBtn = document.getElementById('cutiNextPage');

            if (!container || !prevBtn || !nextBtn) return;

            container.innerHTML = '';
            
            if (totalCutiPages > 1) {
                paginationContainer.classList.remove('hidden');
                for (let i = 1; i <= totalCutiPages; i++) {
                    const btn = document.createElement('button');
                    btn.className = `desktop-page-btn ${i === cutiCurrentPage ? 'active' : ''}`;
                    btn.innerText = i;
                    btn.onclick = () => goToPage(i);
                    container.appendChild(btn);
                }
                prevBtn.disabled = cutiCurrentPage === 1;
                nextBtn.disabled = cutiCurrentPage === totalCutiPages;
            } else {
                paginationContainer.classList.add('hidden');
            }
        }

        function goToPage(page) {
            cutiCurrentPage = page;
            loadCutiData();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ==================== UI HELPERS ====================
        function showLoading() {
            document.getElementById('cutiLoading').classList.remove('hidden');
            document.getElementById('cutiDesktopTable').classList.add('hidden');
            document.getElementById('cuti-mobile-cards').classList.add('hidden');
            document.getElementById('noCutiData').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('cutiLoading').classList.add('hidden');
            const isMobile = window.innerWidth < 640;
            document.getElementById('cutiDesktopTable').classList.remove('hidden');
            document.getElementById('cuti-mobile-cards').classList.remove('hidden');
            if(!isMobile) document.getElementById('cuti-mobile-cards').classList.add('hidden');
            else document.getElementById('cutiDesktopTable').classList.add('hidden');
        }

        function showNoData() {
            document.getElementById('noCutiData').classList.remove('hidden');
            document.getElementById('cutiDesktopTable').classList.add('hidden');
            document.getElementById('cuti-mobile-cards').classList.add('hidden');
            document.getElementById('cutiPaginationContainer').classList.add('hidden');
        }

        function hideNoData() {
            document.getElementById('noCutiData').classList.add('hidden');
        }

        function updateCutiStatsUI(stats) {
            const total = stats.total_cuti_tahunan || 12;
            const terpakai = stats.cuti_terpakai || 0;
            const sisa = stats.sisa_cuti || (total - terpakai);

            document.getElementById('stat-total-cuti').innerText = total;
            document.getElementById('stat-cuti-terpakai').innerText = terpakai;
            document.getElementById('stat-cuti-tersisa').innerText = sisa;
        }

        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('show');
            document.body.style.overflow = '';
            if(id === 'tambahCutiModal') {
                document.getElementById('tambahCutiForm').reset();
            }
        }

        function showMinimalPopup(title, msg, type) {
            const p = document.getElementById('minimalPopup');
            p.querySelector('.minimal-popup-title').innerText = title;
            p.querySelector('.minimal-popup-message').innerText = msg;
            p.className = `minimal-popup show ${type}`;
            setTimeout(() => p.classList.remove('show'), 3000);
        }

        // ==================== FEATURE: AUTO DURATION ====================
        function setupAutoDurationCalc() {
            const startInput = document.getElementById('inputTanggalMulai');
            const endInput = document.getElementById('inputTanggalSelesai');
            const durasiInput = document.getElementById('inputDurasi');

            if (startInput && endInput && durasiInput) {
                const calc = () => {
                    if (startInput.value && endInput.value) {
                        const s = new Date(startInput.value);
                        const e = new Date(endInput.value);
                        if (e >= s) {
                            const diffTime = Math.abs(e - s);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                            durasiInput.value = diffDays;
                        } else {
                            durasiInput.value = '';
                        }
                    }
                };
                startInput.addEventListener('change', calc);
                endInput.addEventListener('change', calc);
            }
        }

        // ==================== CRUD HANDLERS ====================
        async function handleAddCuti() {
            const form = document.getElementById('tambahCutiForm');
            const formData = new FormData(form);
            
            const btn = document.querySelector('#tambahCutiModal button[onclick="handleAddCuti()"]');
            if (!btn) return;
            
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Mengirim...';
            btn.disabled = true;

            try {
                const response = await fetch(API_ROUTES.store, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    closeModal('tambahCutiModal');
                    await loadCutiData();
                    
                    // Update Stats Realtime
                    if (result.updated_stats) {
                        updateCutiStatsUI(result.updated_stats);
                    } else {
                        await loadCutiStats();
                    }

                    showMinimalPopup('Berhasil', result.message, 'success');
                } else {
                    let msg = result.message || 'Gagal';
                    if (result.errors) msg = Object.values(result.errors).flat().join(', ');
                    showMinimalPopup('Gagal', msg, 'error');
                }
            } catch (error) {
                console.error(error);
                showMinimalPopup('Error', 'Terjadi kesalahan jaringan', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        async function handleDeleteCuti(id) {
            if(!confirm("Yakin hapus?")) return;
            
            try {
                const response = await fetch(API_ROUTES.destroy(id), {
                    method: 'POST', // Delete via POST method override usually in Laravel
                    headers: { 
                        'X-CSRF-TOKEN': CSRF_TOKEN, 
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                });
                
                const result = await response.json();
                if(result.success) {
                    await loadCutiData();
                    if(result.updated_stats) updateCutiStatsUI(result.updated_stats);
                    showMinimalPopup('Sukses', 'Data dihapus', 'success');
                }
            } catch (e) {
                console.error(e);
            }
        }
    </script>
</body>
</html>
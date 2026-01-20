<!DOCTYPE html>
<html lang="id">
 <head>
     <meta charset="utf-8" />
     <meta content="width=device-width, initial-scale=1.0" name="viewport" />
     <title>Daftar Karyawan - Dashboard</title>
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
     <link rel="icon" type="image/png" href="{{ asset('logo1.jpeg') }}">


     <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
     <script>
         tailwind.config = {
             theme: {
                 extend: {
                     colors: {
                         primary: "#3b82f6",
                         "background-light": "#ffffff",
                         "background-dark": "#f8fafc",
                         "sidebar-light": "#f3f4f6",
                         "sidebar-dark": "#1e293b",
                         "card-light": "#ffffff",
                         "card-dark": "#1e293b",
                         "text-light": "#1e293b",
                         "text-dark": "#f8fafc",
                         "text-muted-light": "#64748b",
                         "text-muted-dark": "#94a3b8",
                         "border-light": "#e2e8f0",
                         "border-dark": "#334155",
                         "success": "#10b981",
                         "warning": "#f59e0b",
                         "danger": "#ef4444"
                     },
                     fontFamily: {
                         display: ["Poppins", "sans-serif"],
                     },
                     borderRadius: {
                         DEFAULT: "0.75rem",
                     },
                     boxShadow: {
                         card: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                         "card-hover": "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                     },
                 },
             },
         };
     </script>
     <style>
         body {
             font-family: 'Poppins', sans-serif;
         }

         .material-icons-outlined {
             font-size: 24px;
             vertical-align: middle;
         }

         /* Card hover effects */
         .stat-card {
             transition: all 0.3s ease;
             box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
         }

         .stat-card:hover {
             transform: translateY(-4px);
             box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
         }

         /* Table styles */
         .order-table {
             transition: all 0.2s ease;
         }

         .order-table tr:hover {
             background-color: rgba(59, 130, 246, 0.05);
         }

         /* Button styles */
         .btn-primary {
             background-color: #3b82f6;
             color: white;
             transition: all 0.2s ease;
         }

         .btn-primary:hover {
             background-color: #2563eb;
         }

         .btn-secondary {
             background-color: #f1f5f9;
             color: #64748b;
             transition: all 0.2s ease;
         }

         .btn-secondary:hover {
             background-color: #e2e8f0;
         }

         /* Modal styles */
         .modal {
             transition: opacity 0.25s ease;
         }

         .modal-backdrop {
             background-color: rgba(0, 0, 0, 0.5);
             backdrop-filter: blur(4px);
         }

         /* Status Badge Styles */
         .status-badge {
             display: inline-block;
             padding: 0.25rem 0.75rem;
             border-radius: 9999px;
             font-size: 0.75rem;
             font-weight: 600;
         }

         .status-manager {
             background-color: rgba(59, 130, 246, 0.15);
             color: #1e40af;
         }

         .status-staff {
             background-color: rgba(16, 185, 129, 0.15);
             color: #065f46;
         }

         .status-intern {
             background-color: rgba(245, 158, 11, 0.15);
             color: #92400e;
         }

         /* Custom styles untuk transisi */
         .sidebar-transition {
             transition: transform 0.3s ease-in-out;
         }

         /* Animasi hamburger */
         .hamburger-line {
             transition: all 0.3s ease-in-out;
         }

         .hamburger-active .line1 {
             transform: rotate(45deg) translate(5px, 5px);
         }

         .hamburger-active .line2 {
             opacity: 0;
         }

         .hamburger-active .line3 {
             transform: rotate(-45deg) translate(7px, -6px);
         }

         /* Style untuk efek hover yang lebih menonjol */
         .nav-item {
             position: relative;
             overflow: hidden;
         }

         /* Gaya untuk indikator aktif/hover */
         /* Default untuk mobile: di sebelah kanan */
         .nav-item::before {
             content: '';
             position: absolute;
             right: 0;
             top: 0;
             height: 100%;
             width: 3px;
             background-color: #3b82f6;
             transform: translateX(100%);
             transition: transform 0.3s ease;
         }

         /* Override untuk desktop: di sebelah kiri */
         @media (min-width: 768px) {
             .nav-item::before {
                 right: auto;
                 left: 0;
                 transform: translateX(-100%);
             }
         }

         .nav-item:hover::before,
         .nav-item.active::before {
             transform: translateX(0);
         }

         /* Memastikan sidebar tetap di posisinya saat scroll */
         .sidebar-fixed {
             position: fixed;
             height: 100vh;
             overflow-y: auto;
             z-index: 40;
         }

         /* Menyesuaikan konten utama agar tidak tertutup sidebar */
         .main-content {
             margin-left: 0;
             transition: margin-left 0.3s ease-in-out;
         }

         @media (min-width: 768px) {
             .main-content {
                 margin-left: 256px;
                 /* Lebar sidebar */
             }
         }

         /* Scrollbar kustom untuk sidebar */
         .sidebar-fixed::-webkit-scrollbar {
             width: 6px;
         }

         .sidebar-fixed::-webkit-scrollbar-track {
             background: #f1f1f1;
         }

         .sidebar-fixed::-webkit-scrollbar-thumb {
             background: #888;
             border-radius: 3px;
         }

         .sidebar-fixed::-webkit-scrollbar-thumb:hover {
             background: #555;
         }

         /* Table mobile adjustments */
         @media (max-width: 639px) {
             .desktop-table {
                 display: none;
             }

             .mobile-cards {
                 display: block;
             }

             /* Hide desktop pagination on mobile */
             .desktop-pagination {
                 display: none !important;
             }
         }

         @media (min-width: 640px) {
             .desktop-table {
                 display: block;
             }

             .mobile-cards {
                 display: none;
             }

             /* Hide mobile pagination on desktop */
             .mobile-pagination {
                 display: none !important;
             }
         }

         /* Form input styles */
         .form-input {
             border: 1px solid #e2e8f0;
             transition: all 0.2s ease;
         }

         .form-input:focus {
             border-color: #3b82f6;
             box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
         }

         /* Pagination styles */
         .page-btn {
             transition: all 0.2s ease;
         }

         .page-btn:hover:not(:disabled) {
             transform: scale(1.1);
         }

         .page-btn:disabled {
             opacity: 0.5;
             cursor: not-allowed;
         }

         /* Desktop pagination styles */
         .desktop-pagination {
             display: flex;
             justify-content: center;
             align-items: center;
             gap: 8px;
             margin-top: 24px;
         }

         .desktop-page-btn {
             min-width: 32px;
             height: 32px;
             display: flex;
             justify-content: center;
             align-items: center;
             border-radius: 50%;
             font-size: 14px;
             font-weight: 500;
             transition: all 0.2s ease;
             cursor: pointer;
         }

         .desktop-page-btn.active {
             background-color: #3b82f6;
             color: white;
         }

         .desktop-page-btn:not(.active) {
             background-color: #f1f5f9;
             color: #64748b;
         }

         .desktop-page-btn:not(.active):hover {
             background-color: #e2e8f0;
         }

         .desktop-nav-btn {
             display: flex;
             justify-content: center;
             align-items: center;
             width: 32px;
             height: 32px;
             border-radius: 50%;
             background-color: #f1f5f9;
             color: #64748b;
             transition: all 0.2s ease;
             cursor: pointer;
         }

         .desktop-nav-btn:hover:not(:disabled) {
             background-color: #e2e8f0;
         }

         .desktop-nav-btn:disabled {
             opacity: 0.5;
             cursor: not-allowed;
         }

         /* Panel Styles */
         .panel {
             background: white;
             border-radius: 0.75rem;
             box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
             overflow: hidden;
             border: 1px solid #e2e8f0;
         }

         .panel-header {
             background: #f8fafc;
             padding: 1rem 1.5rem;
             border-bottom: 1px solid #e2e8f0;
             display: flex;
             justify-content: space-between;
             align-items: center;
         }

         .panel-title {
             font-size: 1.125rem;
             font-weight: 600;
             color: #1e293b;
             margin: 0;
             display: flex;
             align-items: center;
             gap: 0.5rem;
         }

         .panel-body {
             padding: 1.5rem;
         }

         /* SIMPLIFIED SCROLLABLE TABLE */
         .scrollable-table-container {
             width: 100%;
             overflow-x: auto;
             overflow-y: hidden;
             border: 1px solid #e2e8f0;
             border-radius: 0.5rem;
             background: white;
         }

         /* Force scrollbar to be visible */
         .scrollable-table-container {
             scrollbar-width: auto;
             -webkit-overflow-scrolling: touch;
         }

         .scrollable-table-container::-webkit-scrollbar {
             height: 12px;
             width: 12px;
         }

         .scrollable-table-container::-webkit-scrollbar-track {
             background: #f1f5f9;
             border-radius: 6px;
         }

         .scrollable-table-container::-webkit-scrollbar-thumb {
             background: #cbd5e1;
             border-radius: 6px;
             border: 2px solid #f1f5f9;
         }

         .scrollable-table-container::-webkit-scrollbar-thumb:hover {
             background: #94a3b8;
         }

         /* Table with fixed width to ensure scrolling */
         .data-table {
             width: 100%;
             min-width: 1200px;
             /* Fixed minimum width */
             border-collapse: collapse;
         }

         .data-table th,
         .data-table td {
             padding: 12px 16px;
             text-align: left;
             border-bottom: 1px solid #e2e8f0;
             white-space: nowrap;
         }

         .data-table th {
             background: #f8fafc;
             font-weight: 600;
             color: #374151;
             font-size: 0.875rem;
             text-transform: uppercase;
             letter-spacing: 0.05em;
         }

         .data-table tbody tr:nth-child(even) {
             background: #f9fafb;
         }

         .data-table tbody tr:hover {
             background: #f3f4f6;
         }

         /* Shadow effect */
         .table-shadow {
             box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
         }

         /* Minimalist Popup Styles */
         .minimal-popup {
             position: fixed;
             top: 20px;
             right: 20px;
             background: white;
             border-radius: 8px;
             box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
             padding: 16px 20px;
             display: flex;
             align-items: center;
             gap: 12px;
             z-index: 1000;
             transform: translateX(400px);
             transition: transform 0.3s ease;
             max-width: 350px;
             border-left: 4px solid #10b981;
         }

         .minimal-popup.show {
             transform: translateX(0);
         }

         .minimal-popup.error {
             border-left-color: #ef4444;
         }

         .minimal-popup.warning {
             border-left-color: #f59e0b;
         }

         .minimal-popup-icon {
             flex-shrink: 0;
             width: 24px;
             height: 24px;
             display: flex;
             align-items: center;
             justify-content: center;
             border-radius: 50%;
         }

         .minimal-popup.success .minimal-popup-icon {
             background-color: rgba(16, 185, 129, 0.1);
             color: #10b981;
         }

         .minimal-popup.error .minimal-popup-icon {
             background-color: rgba(239, 68, 68, 0.1);
             color: #ef4444;
         }

         .minimal-popup.warning .minimal-popup-icon {
             background-color: rgba(245, 158, 11, 0.1);
             color: #f59e0b;
         }

         .minimal-popup-content {
             flex-grow: 1;
         }

         .minimal-popup-title {
             font-weight: 600;
             color: #1e293b;
             margin-bottom: 2px;
         }

         .minimal-popup-message {
             font-size: 14px;
             color: #64748b;
         }

         .minimal-popup-close {
             flex-shrink: 0;
             background: none;
             border: none;
             color: #94a3b8;
             cursor: pointer;
             padding: 4px;
             border-radius: 4px;
             transition: all 0.2s ease;
         }

         .minimal-popup-close:hover {
             background-color: #f1f5f9;
             color: #64748b;
         }

         /* Filter Dropdown Styles */
         .filter-dropdown {
             position: absolute;
             top: 100%;
             right: 0;
             margin-top: 8px;
             background: white;
             border-radius: 8px;
             box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
             padding: 16px;
             min-width: 200px;
             z-index: 100;
             display: none;
         }

         .filter-dropdown.show {
             display: block;
         }

         .filter-option {
             display: flex;
             align-items: center;
             gap: 8px;
             padding: 8px 0;
             cursor: pointer;
             transition: all 0.2s ease;
         }

         .filter-option:hover {
             color: #3b82f6;
         }

         .filter-option input[type="checkbox"] {
             width: 18px;
             height: 18px;
             cursor: pointer;
         }

         .filter-option label {
             cursor: pointer;
             user-select: none;
         }

         .filter-actions {
             display: flex;
             gap: 8px;
             margin-top: 12px;
             padding-top: 12px;
             border-top: 1px solid #e2e8f0;
         }

         .filter-actions button {
             flex: 1;
             padding: 6px 12px;
             border-radius: 6px;
             font-size: 14px;
             font-weight: 500;
             cursor: pointer;
             transition: all 0.2s ease;
             border: none;
         }

         .filter-apply {
             background-color: #3b82f6;
             color: white;
         }

         .filter-apply:hover {
             background-color: #2563eb;
         }

         .filter-reset {
             background-color: #f1f5f9;
             color: #64748b;
         }

         .filter-reset:hover {
             background-color: #e2e8f0;
         }

         /* Hidden class for filtering */
         .hidden-by-filter {
             display: none !important;
         }
     </style>
     <!-- Add CSRF token meta tag -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
 </head>

 <body class="font-display bg-background-light text-text-light">
     <div class="flex min-h-screen">
         @include('admin/templet/sider')

         <!-- MAIN -->
         <main class="flex-1 flex flex-col main-content">
             <div class="flex-grow p-3 sm:p-8">

                 <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Daftar Karyawan</h2>

                 <!-- Search and Filter Section -->
                 <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                     <div class="relative w-full md:w-1/3">
                         <span
                             class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                         <input id="searchInput"
                             class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                             placeholder="Cari nama, jabatan, atau alamat..." type="text" />
                     </div>
                     <div class="flex flex-wrap gap-3 w-full md:w-auto">
                         <div class="relative">
                             <button id="filterBtn"
                                 class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                 <span class="material-icons-outlined text-sm">filter_list</span>
                                 Filter
                             </button>
                             <div id="filterDropdown" class="filter-dropdown">
                                 <div class="filter-option">
                                     <input type="checkbox" id="filterAll" value="all" checked>
                                     <label for="filterAll">Semua Jabatan</label>
                                 </div>
                                 <div class="filter-option">
                                     <input type="checkbox" id="filterManager" value="manager">
                                     <label for="filterManager">Manager</label>
                                 </div>
                                 <div class="filter-option">
                                     <input type="checkbox" id="filterStaff" value="staff">
                                     <label for="filterStaff">Staff</label>
                                 </div>
                                 <div class="filter-option">
                                     <input type="checkbox" id="filterIntern" value="intern">
                                     <label for="filterIntern">Intern</label>
                                 </div>
                                 <div class="filter-actions">
                                     <button id="applyFilter" class="filter-apply">Terapkan</button>
                                     <button id="resetFilter" class="filter-reset">Reset</button>
                                 </div>
                             </div>
                         </div>
                         <button id="tambahKaryawanBtn"
                             class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                             <span class="material-icons-outlined">add</span>
                             <span class="hidden sm:inline">Tambah Karyawan</span>
                             <span class="sm:hidden">Tambah</span>
                         </button>
                     </div>
                 </div>

                 <!-- Data Table Panel -->
                 <div class="panel">
                     <div class="panel-header">
                         <h3 class="panel-title">
                             <span class="material-icons-outlined text-primary">people</span>
                             Daftar Karyawan
                         </h3>
                         <div class="flex items-center gap-2">
                             <span class="text-sm text-text-muted-light">Total: <span id="totalCount"
                                     class="font-semibold text-text-light">{{ count($karyawan) }}</span> karyawan</span>
                         </div>
                     </div>
                     <div class="panel-body">
                         <!-- SCROLLABLE TABLE -->
                         <div class="desktop-table">
                             <div class="scrollable-table-container scroll-indicator table-shadow" id="scrollableTable">
                                 <table class="data-table">
                                     <thead>
                                         <tr>
                                             <th style="min-width: 60px;">No</th>
                                             <th style="min-width: 200px;">Nama</th>
                                             <th style="min-width: 150px;">Jabatan</th>
                                             <th style="min-width: 150px;">Divisi</th>
                                             <th style="min-width: 250px;">Alamat</th>
                                             <th style="min-width: 150px;">Kontak</th>
                                             <th style="min-width: 100px;">Foto</th>
                                             <th style="min-width: 100px; text-align: center;">Aksi</th>
                                         </tr>
                                     </thead>
                                     <tbody id="desktopTableBody">
                                         @if (isset($karyawan) && count($karyawan) > 0)
                                             @php $no = 1; @endphp
                                             @foreach ($karyawan as $item)
                                                 <tr class="karyawan-row" data-id="{{ $item->id }}"
                                                     data-nama="{{ $item->user->name ?? $item->nama }}"
                                                     data-jabatan="{{ $item->user->role ?? $item->jabatan }}"
                                                     data-divisi="{{ $item->user->divisi ?? $item->divisi }}"
                                                     data-alamat="{{ $item->alamat }}" data-kontak="{{ $item->kontak }}"
                                                     data-foto="{{ $item->foto ? asset('storage/karyawan/' . $item->foto) : '' }}">
                                                     <td style="min-width: 60px;">{{ $no++ }}</td>
                                                     <td style="min-width: 200px;">{{ $item->nama }}</td>
                                                     <td style="min-width: 150px;">
                                                         <span
                                                             class="status-badge 
                                                            @if (strtolower($item->jabatan) == 'manager') status-manager
                                                            @elseif(strtolower($item->jabatan) == 'staff') status-staff
                                                            @else status-intern @endif">
                                                             {{ $item->jabatan }}
                                                         </span>
                                                     </td>
                                                     <td style="min-width: 150px;">{{ $item->divisi }}</td>
                                                     <td style="min-width: 250px;">{{ $item->alamat }}</td>
                                                     <td style="min-width: 150px;">{{ $item->kontak }}</td>
                                                     <td style="min-width: 100px;">
                                                         <!-- Di tabel desktop -->
                                                         @if ($item->foto)
                                                             <img src="{{ asset('storage/karyawan/' . $item->foto) }}"
                                                                 alt="{{ $item->nama }}"
                                                                 class="h-10 w-10 rounded-full object-cover"
                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=random';">
                                                         @else
                                                             <div
                                                                 class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                                 <span
                                                                     class="material-icons-outlined text-gray-500">person</span>
                                                             </div>
                                                         @endif

                                                     </td>
                                                     <td style="min-width: 100px; text-align: center;">
                                                         <div class="flex justify-center gap-2">
                                                             <button
                                                                 class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                                 data-karyawan='{"id": "{{ $item->id }}", "nama": "{{ $item->nama }}", "jabatan": "{{ $item->jabatan }}", "divisi": "{{ $item->divisi }}", "alamat": "{{ $item->alamat }}", "kontak": "{{ $item->kontak }}", "foto": "{{ $item->foto ?? '' }}" }'>
                                                                 <span class="material-icons-outlined">edit</span>
                                                             </button>
                                                             <button
                                                                 class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                                                 data-id="{{ $item->id }}">
                                                                 <span class="material-icons-outlined">delete</span>
                                                             </button>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             @endforeach
                                         @else
                                             <tr>
                                                 <td colspan="8"
                                                     class="px-6 py-4 text-center text-sm text-gray-500">
                                                     Tidak ada data karyawan
                                                 </td>
                                             </tr>
                                         @endif
                                     </tbody>
                                 </table>
                             </div>
                         </div>

                         <!-- Mobile Card View -->
                         <div class="mobile-cards space-y-4" id="mobile-cards">
                             @if (isset($karyawan) && count($karyawan) > 0)
                                 @php $no = 1; @endphp
                                 @foreach ($karyawan as $item)
                                     <div class="bg-white rounded-lg border border-border-light p-4 shadow-sm karyawan-card"
                                         data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                         data-jabatan="{{ $item->jabatan }}" data-divisi="{{ $item->divisi }}"
                                         data-alamat="{{ $item->alamat }}" data-kontak="{{ $item->kontak }}"
                                         data-foto="{{ $item->foto ? asset('storage/karyawan/' . $item->foto) : '' }}">
                                         <div class="flex justify-between items-start mb-3">
                                             <div class="flex items-center gap-3">
                                                 @if ($item->foto)
                                                     <img src="{{ asset('storage/karyawan/' . $item->foto) }}"
                                                         alt="{{ $item->nama }}"
                                                         class="h-12 w-12 rounded-full object-cover"
                                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=random';">
                                                 @else
                                                     <div
                                                         class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                                         <span
                                                             class="material-icons-outlined text-gray-500">person</span>
                                                     </div>
                                                 @endif
                                                 <div>
                                                     <h4 class="font-semibold text-base">{{ $item->nama }}</h4>
                                                     <p class="text-sm text-text-muted-light">{{ $item->kontak }}</p>
                                                 </div>
                                             </div>
                                             <div class="flex gap-2">
                                                 <button
                                                     class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                     data-karyawan='{"id": "{{ $item->id }}", "nama": "{{ $item->nama }}", "jabatan": "{{ $item->jabatan }}", "divisi": "{{ $item->divisi }}", "alamat": "{{ $item->alamat }}", "kontak": "{{ $item->kontak }}", "foto": "{{ $item->foto ?? '' }}" }'>
                                                     <span class="material-icons-outlined">edit</span>
                                                 </button>
                                                 <button
                                                     class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                                     data-id="{{ $item->id }}">
                                                     <span class="material-icons-outlined">delete</span>
                                                 </button>
                                             </div>
                                         </div>
                                         <div class="grid grid-cols-2 gap-2 text-sm">
                                             <div>
                                                 <p class="text-text-muted-light">No</p>
                                                 <p class="font-medium">{{ $no++ }}</p>
                                             </div>
                                             <div>
                                                 <p class="text-text-muted-light">Jabatan</p>
                                                 <p>
                                                     <span
                                                         class="status-badge 
                                                            @if (strtolower($item->jabatan) == 'manager') status-manager
                                                            @elseif(strtolower($item->jabatan) == 'staff') status-staff
                                                            @else status-intern @endif">
                                                         {{ $item->jabatan }}
                                                     </span>
                                                 </p>
                                             </div>
                                             <div>
                                                 <p class="text-text-muted-light">Divisi</p>
                                                 <p class="font-medium">{{ $item->divisi }}</p>
                                             </div>
                                             <div>
                                                 <p class="text-text-muted-light">Alamat</p>
                                                 <p class="font-medium truncate">{{ $item->alamat }}</p>
                                             </div>
                                         </div>
                                     </div>
                                 @endforeach
                             @else
                                 <div class="bg-white rounded-lg border border-border-light p-8 text-center">
                                     <span class="material-icons-outlined text-4xl text-gray-300 mb-2">people</span>
                                     <p class="text-gray-500">Tidak ada data karyawan</p>
                                 </div>
                             @endif
                         </div>

                         <!-- Pagination -->
                         <div id="paginationContainer" class="desktop-pagination">
                             <button id="prevPage" class="desktop-nav-btn">
                                 <span class="material-icons-outlined text-sm">chevron_left</span>
                             </button>
                             <div id="pageNumbers" class="flex gap-1">
                                 <!-- Page numbers will be generated by JavaScript -->
                             </div>
                             <button id="nextPage" class="desktop-nav-btn">
                                 <span class="material-icons-outlined text-sm">chevron_right</span>
                             </button>
                         </div>
                     </div>
                 </div>
             </div>
             <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                 Copyright ©2025 by digicity.id
             </footer>
         </main>
     </div>

     <!-- Popup Modal untuk Tambah Karyawan -->
     <div id="tambahKaryawanModal"
         class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
         <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
             <div class="p-6">
                 <div class="flex justify-between items-center mb-4">
                     <h3 class="text-xl font-bold text-gray-800">Tambah Karyawan Baru</h3>
                     <button id="closeModalBtn" class="text-gray-800 hover:text-gray-500">
                         <span class="material-icons-outlined">close</span>
                     </button>
                 </div>
                 <!-- Ganti form tambah karyawan dengan ini -->
<form id="tambahKaryawanForm" class="space-y-4" enctype="multipart/form-data">
    @csrf
    
    <!-- Field user_id - REQUIRED -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User *</label>
        <select name="user_id" id="userSelect"
            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
            required>
            <option value="">-- Pilih User --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" 
                        data-nama="{{ $user->name }}"
                        data-divisi="{{ $user->divisi ?? '' }}" 
                        data-role="{{ $user->role ?? 'karyawan' }}">
                    {{ $user->name }} 
                    ({{ $user->role ?? 'karyawan' }}) 
                    {{ $user->divisi ? '- ' . $user->divisi : '' }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Hanya menampilkan user yang belum menjadi karyawan</p>
    </div>

    <!-- Field nama - AUTO FILL (hidden) -->
    <input type="hidden" name="nama" id="namaInput">
    
    <!-- PERBAIKAN: Field jabatan sesuai controller validation -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan *</label>
        <input type="text" name="jabatan" id="jabatanInput"
            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Contoh: Karyawan, Manager, Staff" required>
    </div>

    <!-- Field divisi - AUTO FILL -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
        <input type="text" name="divisi" id="divisiInput"
            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Contoh: Programmer, Marketing, HR">
    </div>

    <!-- Field kontak - REQUIRED -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kontak *</label>
        <input type="text" name="kontak" id="kontakInput"
            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Contoh: 081234567890" required>
    </div>

    <!-- Field alamat - REQUIRED -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
        <textarea name="alamat" id="alamatInput"
            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
            rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
    </div>

    <!-- Field foto - Optional -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Foto (Opsional)</label>
        <div class="flex items-center space-x-4">
            <div id="fotoPreview" 
                 class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                <span class="material-icons-outlined text-gray-500 text-2xl">person</span>
            </div>
            <div>
                <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*">
                <button type="button" id="pilihFotoBtn" 
                        class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Pilih Foto
                </button>
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG maks. 2MB</p>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-2 mt-6">
        <button type="button" id="cancelBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Data</button>
    </div>
</form>
             </div>
         </div>
     </div>

     <!-- Popup Modal untuk Edit Karyawan -->
     <div id="editKaryawanModal"
         class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
         <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
             <div class="p-6">
                 <div class="flex justify-between items-center mb-4">
                     <h3 class="text-xl font-bold text-gray-800">Edit Karyawan</h3>
                     <button id="closeEditModalBtn" class="text-gray-800 hover:text-gray-500">
                         <span class="material-icons-outlined">close</span>
                     </button>
                 </div>
                 <form id="editKaryawanForm" class="space-y-4" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" id="editId" name="id">
                     @method('PUT')

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                             <input type="text" id="editNama" name="nama"
                                 class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                 placeholder="Masukkan nama karyawan" required>
                         </div>

                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                             <input type="text" id="editJabatan" name="jabatan"
                                 class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                 placeholder="Masukkan jabatan" required>
                         </div>

                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                             <input type="text" id="editDivisi" name="divisi"
                                 class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                 placeholder="Masukkan divisi" required>
                         </div>

                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                             <input type="text" id="editKontak" name="kontak"
                                 class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                 placeholder="Masukkan nomor telepon" required>
                         </div>

                         <div class="md:col-span-2">
                             <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                             <textarea id="editAlamat" name="alamat"
                                 class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                 rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                         </div>

                         <div class="md:col-span-2">
                             <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                             <div class="flex items-center space-x-4">
                                 <div id="editFotoPreview"
                                     class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                                     <span class="material-icons-outlined text-gray-500 text-2xl">person</span>
                                 </div>
                                 <div>
                                     <input type="file" name="foto" id="editFotoInput" class="hidden"
                                         accept="image/*">
                                     <button type="button" id="pilihEditFotoBtn"
                                         class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                         Pilih Foto
                                     </button>
                                     <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG maks. 2MB</p>
                                 </div>
                             </div>
                         </div>
                     </div>

    <!-- Popup Modal untuk Konfirmasi Hapus -->
    <div id="deleteKaryawanModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button id="closeDeleteModalBtn" class="text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <!-- PERBAIKAN: Hapus atribut action yang statis -->
                <form id="deleteKaryawanForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-6">
                        <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus data karyawan ini?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        <input type="hidden" id="deleteId" name="id">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Popup Modal untuk Konfirmasi Hapus -->
     <div id="deleteKaryawanModal"
         class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
         <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
             <div class="p-6">
                 <div class="flex justify-between items-center mb-4">
                     <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                     <button id="closeDeleteModalBtn" class="text-gray-800 hover:text-gray-500">
                         <span class="material-icons-outlined">close</span>
                     </button>
                 </div>
                 <form id="deleteKaryawanForm" method="POST">
                     @csrf
                     @method('DELETE')
                     <div class="mb-6">
                         <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus data karyawan ini?</p>
                         <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                         <input type="hidden" id="deleteId" name="id">
                     </div>
                     <div class="flex justify-end gap-2">
                         <button type="button" id="cancelDeleteBtn"
                             class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                         <button type="submit"
                             class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Hapus</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>

     <!-- Minimalist Popup -->
     <div id="minimalPopup" class="minimal-popup">
         <div class="minimal-popup-icon">
             <span class="material-icons-outlined">check</span>
         </div>
         <div class="minimal-popup-content">
             <div class="minimal-popup-title">Berhasil</div>
             <div class="minimal-popup-message">Operasi berhasil dilakukan</div>
         </div>
         <button class="minimal-popup-close">
             <span class="material-icons-outlined text-sm">close</span>
         </button>
     </div>

     <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Modal functions
         const tambahKaryawanBtn = document.getElementById('tambahKaryawanBtn');
         const tambahKaryawanModal = document.getElementById('tambahKaryawanModal');
         const closeModalBtn = document.getElementById('closeModalBtn');
         const cancelBtn = document.getElementById('cancelBtn');
         const tambahKaryawanForm = document.getElementById('tambahKaryawanForm');
         const pilihFotoBtn = document.getElementById('pilihFotoBtn');
         const fotoInput = document.getElementById('fotoInput');
         const fotoPreview = document.getElementById('fotoPreview');

         const editKaryawanModal = document.getElementById('editKaryawanModal');
         const closeEditModalBtn = document.getElementById('closeEditModalBtn');
         const cancelEditBtn = document.getElementById('cancelEditBtn');
         const editKaryawanForm = document.getElementById('editKaryawanForm');
         const pilihEditFotoBtn = document.getElementById('pilihEditFotoBtn');
         const editFotoInput = document.getElementById('editFotoInput');
         const editFotoPreview = document.getElementById('editFotoPreview');

         const deleteKaryawanModal = document.getElementById('deleteKaryawanModal');
         const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
         const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
         const deleteKaryawanForm = document.getElementById('deleteKaryawanForm');
         // Inisialisasi variabel untuk pagination, filter, dan search
         let currentPage = 1;
         const itemsPerPage = 5;
         let activeFilters = ['all'];
         let searchTerm = '';

         // Dapatkan semua elemen karyawan
         const karyawanRows = document.querySelectorAll('.karyawan-row');
         const karyawanCards = document.querySelectorAll('.karyawan-card');

         // Inisialisasi pagination, filter, dan search
         initializePagination();
         initializeFilter();
         initializeSearch();
         initializeScrollDetection();

         // === PAGINATION ===
         function initializePagination() {
             renderPagination();
             updateVisibleItems();
         }

         function renderPagination() {
             const visibleRows = getFilteredRows();
             const totalPages = Math.ceil(visibleRows.length / itemsPerPage);
             const pageNumbersContainer = document.getElementById('pageNumbers');
             const prevButton = document.getElementById('prevPage');
             const nextButton = document.getElementById('nextPage');

             // Clear existing page numbers
             pageNumbersContainer.innerHTML = '';

             // Generate page numbers
             for (let i = 1; i <= totalPages; i++) {
                 const pageNumber = document.createElement('button');
                 pageNumber.textContent = i;
                 pageNumber.className = `desktop-page-btn ${i === currentPage ? 'active' : ''}`;
                 pageNumber.addEventListener('click', () => goToPage(i));
                 pageNumbersContainer.appendChild(pageNumber);
             }

             // Update navigation buttons
             prevButton.disabled = currentPage === 1;
             nextButton.disabled = currentPage === totalPages || totalPages === 0;

             // Add event listeners for navigation buttons
             prevButton.onclick = () => {
                 if (currentPage > 1) goToPage(currentPage - 1);
             };

             nextButton.onclick = () => {
                 if (currentPage < totalPages) goToPage(currentPage + 1);
             };
         }

    // Modal Delete
    function openDeleteModal(id) {
        document.getElementById('deleteId').value = id;
        // PERBAIKAN: Set action secara dinamis di JS, bukan di HTML
        deleteKaryawanForm.action = `/admin/karyawan/${id}`;
        deleteKaryawanModal.classList.remove('hidden');
    }
    function closeDeleteModal() {
        deleteKaryawanModal.classList.add('hidden');
    }

             // Reset scroll position when changing pages
             const scrollableTable = document.getElementById('scrollableTable');
             if (scrollableTable) {
                 scrollableTable.scrollLeft = 0;
             }
         }

         function getFilteredRows() {
             return Array.from(karyawanRows).filter(row => !row.classList.contains('hidden-by-filter'));
         }

         function getFilteredCards() {
             return Array.from(karyawanCards).filter(card => !card.classList.contains('hidden-by-filter'));
         }

         function updateVisibleItems() {
             const visibleRows = getFilteredRows();
             const visibleCards = getFilteredCards();

             const startIndex = (currentPage - 1) * itemsPerPage;
             const endIndex = startIndex + itemsPerPage;

             // Hide all rows and cards first
             karyawanRows.forEach(row => row.style.display = 'none');
             karyawanCards.forEach(card => card.style.display = 'none');

             // Show only the rows for current page
             visibleRows.forEach((row, index) => {
                 if (index >= startIndex && index < endIndex) {
                     row.style.display = '';
                 }
             });

             // Show only the cards for current page
             visibleCards.forEach((card, index) => {
                 if (index >= startIndex && index < endIndex) {
                     card.style.display = '';
                 }
             });

             // Update total count
             document.getElementById('totalCount').textContent = visibleRows.length;
         }

    // CREATE (POST)
    // ========= CRUD ========= //


// UPDATE (PUT) - VERSI DEBUGGING
editKaryawanForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = editKaryawanForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Memperbarui...';
    submitBtn.disabled = true;

    let id = document.getElementById("editId").value;
    let formData = new FormData(editKaryawanForm);
    formData.append('_method', 'PUT');

    try {
        let response = await fetch(`/admin/karyawan/${id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            },
            body: formData
        });

        // --- DEBUGGING: Periksa status dan respons ---
        console.log('Update Response Status:', response.status);
        console.log('Update Response Headers:', response.headers.get('Content-Type'));

        const responseText = await response.text();
        console.log('Update Raw Response Text:', responseText);

        let res;
        try {
            res = JSON.parse(responseText);
        } catch (e) {
            console.error('Gagal parse JSON. Respons mungkin bukan JSON.');
            throw new Error('Server returned non-JSON response. Check console for details.');
        }
        
        if (res.success) {
            showMinimalPopup('Berhasil', 'Data karyawan berhasil diperbarui', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showMinimalPopup('Error', res.message || 'Terjadi kesalahan saat memperbarui data', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMinimalPopup('Error', 'Terjadi kesalahan saat memperbarui data. Cek console untuk detail.', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// DELETE (DELETE) - VERSI DEBUGGING
deleteKaryawanForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = deleteKaryawanForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Menghapus...';
    submitBtn.disabled = true;

    let formData = new FormData(deleteKaryawanForm);
    formData.append('_method', 'DELETE');

    const deleteUrl = deleteKaryawanForm.action; 

    try {
        let response = await fetch(deleteUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            },
            body: formData
        });

        // --- DEBUGGING: Periksa status dan respons ---
        console.log('Delete Response Status:', response.status);
        console.log('Delete Response Headers:', response.headers.get('Content-Type'));

        const responseText = await response.text();
        console.log('Delete Raw Response Text:', responseText);

        let res;
        try {
            res = JSON.parse(responseText);
        } catch (e) {
            console.error('Gagal parse JSON. Respons mungkin bukan JSON.');
            throw new Error('Server returned non-JSON response. Check console for details.');
        }
        
        if (res.success) {
            showMinimalPopup('Berhasil', 'Data karyawan berhasil dihapus', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showMinimalPopup('Error', res.message || 'Terjadi kesalahan saat menghapus data', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMinimalPopup('Error', 'Terjadi kesalahan saat menghapus data. Cek console untuk detail.', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        closeDeleteModal();
    }
});

             // Handle other checkboxes
             document.querySelectorAll('.filter-option input[type="checkbox"]:not(#filterAll)').forEach(cb => {
                 cb.addEventListener('change', function() {
                     if (this.checked) {
                         // Uncheck "All" checkbox
                         filterAll.checked = false;
                     }
                 });
             });

        let id = document.getElementById("editId").value;
        let formData = new FormData(editKaryawanForm);
        
        // Tambahkan _method ke FormData untuk Laravel
        formData.append('_method', 'PUT');

        try {
            let response = await fetch(`/admin/karyawan/${id}`, {
                method: "POST", // Gunakan POST dengan _method di FormData
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                },
                body: formData
            });

                 applyFilters();
                 filterDropdown.classList.remove('show');
                 const visibleCount = getFilteredRows().length;
                 showMinimalPopup('Filter Diterapkan', `Menampilkan ${visibleCount} karyawan`, 'success');
             });

             // Reset filter
             resetFilterBtn.addEventListener('click', function() {
                 document.getElementById('filterAll').checked = true;
                 document.getElementById('filterManager').checked = false;
                 document.getElementById('filterStaff').checked = false;
                 document.getElementById('filterIntern').checked = false;
                 activeFilters = ['all'];
                 applyFilters();
                 filterDropdown.classList.remove('show');
                 const visibleCount = getFilteredRows().length;
                 showMinimalPopup('Filter Direset', 'Menampilkan semua karyawan', 'success');
             });
         }

         function applyFilters() {
             // Reset to first page
             currentPage = 1;

        let formData = new FormData(deleteKaryawanForm);
        // Tambahkan _method ke FormData untuk Laravel
        formData.append('_method', 'DELETE');

        // PENTING: Pastikan URL di sini benar-benar cocok dengan route di web.php
        const deleteUrl = deleteKaryawanForm.action; 

        try {
            let response = await fetch(deleteUrl, {
                method: "POST", // Gunakan POST dengan _method di FormData
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                },
                body: formData
            });

            if (!response.ok) {
                // Log error untuk debugging
                console.error('Delete failed:', response.status, response.statusText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

                 // Check if search term matches
                 let searchMatches = true;
                 if (searchTerm) {
                     const searchLower = searchTerm.toLowerCase();
                     searchMatches = nama.includes(searchLower) ||
                         alamat.includes(searchLower) ||
                         jabatan.includes(searchLower);
                 }

                 if (jabatanMatches && searchMatches) {
                     row.classList.remove('hidden-by-filter');
                 } else {
                     row.classList.add('hidden-by-filter');
                 }
             });

             // Apply same filters to cards
             karyawanCards.forEach(card => {
                 const jabatan = card.getAttribute('data-jabatan').toLowerCase();
                 const nama = card.getAttribute('data-nama').toLowerCase();
                 const alamat = card.getAttribute('data-alamat').toLowerCase();

                 // Check if jabatan matches filter
                 let jabatanMatches = false;
                 if (activeFilters.includes('all')) {
                     jabatanMatches = true;
                 } else {
                     jabatanMatches = activeFilters.some(filter => jabatan.includes(filter.toLowerCase()));
                 }

                 // Check if search term matches
                 let searchMatches = true;
                 if (searchTerm) {
                     const searchLower = searchTerm.toLowerCase();
                     searchMatches = nama.includes(searchLower) ||
                         alamat.includes(searchLower) ||
                         jabatan.includes(searchLower);
                 }

                 if (jabatanMatches && searchMatches) {
                     card.classList.remove('hidden-by-filter');
                 } else {
                     card.classList.add('hidden-by-filter');
                 }
             });

             // Update pagination and visible items
             renderPagination();
             updateVisibleItems();
         }

         // === SEARCH ===
         function initializeSearch() {
             const searchInput = document.getElementById('searchInput');
             let searchTimeout;

             searchInput.addEventListener('input', function() {
                 clearTimeout(searchTimeout);
                 searchTimeout = setTimeout(() => {
                     searchTerm = searchInput.value.trim();
                     applyFilters();
                 }, 300); // Debounce search
             });
         }

         // Initialize scroll detection for table
         function initializeScrollDetection() {
             const scrollableTable = document.getElementById('scrollableTable');

             if (scrollableTable) {
                 // Add scroll event listener
                 scrollableTable.addEventListener('scroll', function() {
                     const scrollLeft = scrollableTable.scrollLeft;
                     const maxScroll = scrollableTable.scrollWidth - scrollableTable.clientWidth;
                 });
             }
         }

         // Minimalist Popup
         function showMinimalPopup(title, message, type = 'success') {
             const popup = document.getElementById('minimalPopup');
             const popupTitle = popup.querySelector('.minimal-popup-title');
             const popupMessage = popup.querySelector('.minimal-popup-message');
             const popupIcon = popup.querySelector('.minimal-popup-icon span');

             // Set content
             popupTitle.textContent = title;
             popupMessage.textContent = message;

             // Set type
             popup.className = 'minimal-popup show ' + type;

             // Set icon
             if (type === 'success') {
                 popupIcon.textContent = 'check';
             } else if (type === 'error') {
                 popupIcon.textContent = 'error';
             } else if (type === 'warning') {
                 popupIcon.textContent = 'warning';
             }

             // Auto hide after 3 seconds
             setTimeout(() => {
                 popup.classList.remove('show');
             }, 3000);
         }

         // Close popup when clicking the close button
         document.querySelector('.minimal-popup-close').addEventListener('click', function() {
             document.getElementById('minimalPopup').classList.remove('show');
         });

         

         if (pilihFotoBtn && fotoInput) {
             pilihFotoBtn.addEventListener('click', () => {
                 fotoInput.click();
             });
         }
         if (fotoInput && fotoPreview) {
             fotoInput.addEventListener('change', function(e) {
                 const file = e.target.files[0];
                 if (file) {
                     const reader = new FileReader();
                     reader.onload = function(e) {
                         fotoPreview.innerHTML =
                             `<img src="${e.target.result}" alt="Preview" class="h-16 w-16 rounded-full object-cover">`;
                     };
                     reader.readAsDataURL(file);
                 }
             });
         }

         // Cek elemen EDIT FOTO
         if (pilihEditFotoBtn && editFotoInput) {
             pilihEditFotoBtn.addEventListener('click', () => {
                 editFotoInput.click();
             });
         }

         if (editFotoInput && editFotoPreview) {
             editFotoInput.addEventListener('change', function(e) {
                 const file = e.target.files[0];
                 if (file) {
                     const reader = new FileReader();
                     reader.onload = function(e) {
                         editFotoPreview.innerHTML =
                             `<img src="${e.target.result}" alt="Preview" class="h-16 w-16 rounded-full object-cover">`;
                     };
                     reader.readAsDataURL(file);
                 }
             });
         }

         // Modal Tambah
         function openTambahModal() {
             tambahKaryawanModal.classList.remove('hidden');
         }

         function closeTambahModal() {
             tambahKaryawanModal.classList.add('hidden');
             tambahKaryawanForm.reset();
             fotoPreview.innerHTML = '<span class="material-icons-outlined text-gray-500 text-2xl">person</span>';
         }

         // Modal Edit
         function openEditModal(data) {
             document.getElementById('editId').value = data.id;
             document.getElementById('editNama').value = data.nama;
             document.getElementById('editJabatan').value = data.jabatan;
             document.getElementById('editDivisi').value = data.divisi;
             document.getElementById('editKontak').value = data.kontak;
             document.getElementById('editAlamat').value = data.alamat;

             // PERBAIKAN: Gunakan data.foto bukan variabel foto yang tidak didefinisikan
             if (data.foto && data.foto.trim() !== '') {
                 editFotoPreview.innerHTML =
                     `<img src="${window.location.origin}/storage/karyawan/${data.foto}" 
                  alt="${data.nama}" 
                  class="h-16 w-16 rounded-full object-cover">`;
             } else {
                 editFotoPreview.innerHTML =
                     '<span class="material-icons-outlined text-gray-500 text-2xl">person</span>';
             }

             editKaryawanModal.classList.remove('hidden');
         }

         function closeEditModal() {
             editKaryawanModal.classList.add('hidden');
             editKaryawanForm.reset();
         }

         // Modal Delete
         function openDeleteModal(id) {
             document.getElementById('deleteId').value = id;
             deleteKaryawanForm.action = `/admin/karyawan/delete/${id}`;
             deleteKaryawanModal.classList.remove('hidden');
         }

         function closeDeleteModal() {
             deleteKaryawanModal.classList.add('hidden');
         }

         // Open Modal
         tambahKaryawanBtn.addEventListener('click', openTambahModal);

         // Close Modal
         closeModalBtn.addEventListener('click', closeTambahModal);
         cancelBtn.addEventListener('click', closeTambahModal);

         closeEditModalBtn.addEventListener('click', closeEditModal);
         cancelEditBtn.addEventListener('click', closeEditModal);

         closeDeleteModalBtn.addEventListener('click', closeDeleteModal);
         cancelDeleteBtn.addEventListener('click', closeDeleteModal);

         pilihEditFotoBtn.addEventListener('click', () => {
             editFotoInput.click();
         });

         if (fotoInput) {
             fotoInput.addEventListener('change', function(e) {
                 const file = e.target.files[0];
                 if (file && fotoPreview) {
                     const reader = new FileReader();
                     reader.onload = function(e) {
                         fotoPreview.innerHTML =
                             `<img src="${e.target.result}" alt="Preview" class="h-16 w-16 rounded-full object-cover">`;
                     };
                     reader.readAsDataURL(file);
                 }
             });
         }

         editFotoInput.addEventListener('change', function(e) {
             const file = e.target.files[0];
             if (file) {
                 const reader = new FileReader();
                 reader.onload = function(e) {
                     editFotoPreview.innerHTML =
                         `<img src="${e.target.result}" alt="Preview" class="h-16 w-16 rounded-full object-cover">`;
                 };
                 reader.readAsDataURL(file);
             }
         });

         // ========= AUTO-FILL DIVISI DAN JABATAN BERDASARKAN USER ========= //
         // Auto-fill jabatan dan divisi saat memilih user
// ========= AUTO-FILL BERDASARKAN USER ========= //
document.getElementById('userSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const userId = selectedOption.value;
    const userName = selectedOption.getAttribute('data-nama');
    const divisi = selectedOption.getAttribute('data-divisi') || '';
    const role = selectedOption.getAttribute('data-role') || 'karyawan';
    
    console.log('Selected user:', { userId, userName, divisi, role });
    
    // Isi otomatis field yang diperlukan
    document.getElementById('namaInput').value = userName; // Hidden field
    document.getElementById('jabatanInput').value = role;
    document.getElementById('divisiInput').value = divisi;
});

// CREATE (POST) dengan debugging lebih detail
tambahKaryawanForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    console.log('Form submission started');

    // Validasi manual
    const userSelect = document.getElementById('userSelect');
    const kontakInput = document.getElementById('kontakInput');
    const alamatInput = document.getElementById('alamatInput');
    const jabatanInput = document.getElementById('jabatanInput');
    
    if (!userSelect.value) {
        showMinimalPopup('Error', 'Silakan pilih user terlebih dahulu', 'error');
        return;
    }
    
    // Auto-fill nama dari user yang dipilih
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    const namaInput = document.getElementById('namaInput');
    namaInput.value = selectedOption.getAttribute('data-nama');
    
    if (!jabatanInput.value.trim()) {
        showMinimalPopup('Error', 'Jabatan harus diisi', 'error');
        return;
    }
    
    if (!kontakInput.value.trim()) {
        showMinimalPopup('Error', 'Nomor kontak harus diisi', 'error');
        return;
    }
    
    if (!alamatInput.value.trim()) {
        showMinimalPopup('Error', 'Alamat harus diisi', 'error');
        return;
    }

    // Show loading state
    const submitBtn = tambahKaryawanForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Menyimpan...';
    submitBtn.disabled = true;

    let formData = new FormData(tambahKaryawanForm);

    // Debug: Tampilkan data FormData
    console.log('FormData to be sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        console.log('Sending to:', '/admin/karyawan/store');

        let response = await fetch("/admin/karyawan/store", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
                // JANGAN tambah Content-Type untuk FormData
            },
            body: formData
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));
        
        let data;
        try {
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            data = JSON.parse(responseText);
            console.log('Parsed response:', data);
            
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            showMinimalPopup('Error', 'Terjadi kesalahan pada server (Invalid JSON)', 'error');
            return;
        }

        if (response.ok) {
            showMinimalPopup('Berhasil', 'Karyawan berhasil ditambahkan', 'success');
            
            // Reset form
            tambahKaryawanForm.reset();
            closeTambahModal();
            
            // Refresh page setelah 1.5 detik
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            // Handle errors
            if (data.errors) {
                let errorMessages = [];
                for (let field in data.errors) {
                    errorMessages.push(`${field}: ${data.errors[field].join(', ')}`);
                }
                showMinimalPopup('Validasi Gagal', errorMessages.join('\n'), 'error');
            } else {
                showMinimalPopup('Error', data.message || `Terjadi kesalahan (Status: ${response.status})`, 'error');
            }
        }
        
    } catch (error) {
        console.error('Network error:', error);
        showMinimalPopup('Error', 'Koneksi jaringan bermasalah: ' + error.message, 'error');
    } finally {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});
         // CREATE (POST)
         // Pastikan ini di bagian JavaScript
// ========= AUTO-FILL BERDASARKAN USER ========= //
document.getElementById('userSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const userId = selectedOption.value;
    const userName = selectedOption.getAttribute('data-nama');
    const divisi = selectedOption.getAttribute('data-divisi') || '';
    const role = selectedOption.getAttribute('data-role') || 'karyawan';
    
    console.log('Selected user:', { userId, userName, divisi, role });
    
    // Isi otomatis field yang diperlukan
    document.getElementById('namaInput').value = userName; // Hidden field
    document.getElementById('jabatanInput').value = role;
    document.getElementById('divisiInput').value = divisi;
});


         // UPDATE (PUT)
         editKaryawanForm.addEventListener('submit', async function(e) {
             e.preventDefault();

             // Show loading state
             const submitBtn = editKaryawanForm.querySelector('button[type="submit"]');
             const originalText = submitBtn.textContent;
             submitBtn.textContent = 'Memperbarui...';
             submitBtn.disabled = true;

                 if (!response.ok) {
                     throw new Error(`HTTP error! status: ${response.status}`);
                 }

                 let res = await response.json();

                 if (res.success) {
                     showMinimalPopup('Berhasil', 'Data karyawan berhasil diperbarui', 'success');
                     setTimeout(() => {
                         location.reload();
                     }, 1500);
                 } else {
                     showMinimalPopup('Error', res.message || 'Terjadi kesalahan saat memperbarui data',
                         'error');
                 }
             } catch (error) {
                 console.error('Error:', error);
                 showMinimalPopup('Error', 'Terjadi kesalahan saat memperbarui data', 'error');
             } finally {
                 // Reset button state
                 submitBtn.textContent = originalText;
                 submitBtn.disabled = false;
             }
         });

         // DELETE (DELETE)
         deleteKaryawanForm.addEventListener('submit', async function(e) {
             e.preventDefault();

             // Show loading state
             const submitBtn = deleteKaryawanForm.querySelector('button[type="submit"]');
             const originalText = submitBtn.textContent;
             submitBtn.textContent = 'Menghapus...';
             submitBtn.disabled = true;

             try {
                 let response = await fetch(deleteKaryawanForm.action, {
                     method: "POST",
                     headers: {
                         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                             "content"),
                         "X-HTTP-Method-Override": "DELETE",
                         "Accept": "application/json"
                     },
                     body: new FormData(deleteKaryawanForm)
                 });

                 if (!response.ok) {
                     throw new Error(`HTTP error! status: ${response.status}`);
                 }

                 let res = await response.json();

                 if (res.success) {
                     showMinimalPopup('Berhasil', 'Data karyawan berhasil dihapus', 'success');
                     setTimeout(() => {
                         location.reload();
                     }, 1500);
                 } else {
                     showMinimalPopup('Error', res.message || 'Terjadi kesalahan saat menghapus data', 'error');
                 }
             } catch (error) {
                 console.error('Error:', error);
                 showMinimalPopup('Error', 'Terjadi kesalahan saat menghapus data', 'error');
             } finally {
                 // Reset button state
                 submitBtn.textContent = originalText;
                 submitBtn.disabled = false;
                 closeDeleteModal();
             }
         });

         // ====== Trigger Tombol Edit & Delete ====== //
         document.querySelectorAll('.edit-btn').forEach(button => {
             button.addEventListener('click', function() {
                 const data = JSON.parse(this.getAttribute('data-karyawan'));
                 openEditModal(data);
             });
         });

         document.querySelectorAll('.delete-btn').forEach(button => {
             button.addEventListener('click', function() {
                 const id = this.getAttribute('data-id');
                 openDeleteModal(id);
             });
         });
        });
     </script>

 </body>

 </html>

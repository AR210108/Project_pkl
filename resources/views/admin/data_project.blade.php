<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Project</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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

        .status-todo {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }

        .status-progress {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }

        .status-done {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .status-active {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .status-inprogress {
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

        /* SCROLLABLE TABLE - TANPA TEKS SCROLL */
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
            min-width: 1400px;
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

        /* Progress bar styles */
        .progress-bar {
            width: 100%;
            background-color: #e2e8f0;
            border-radius: 9999px;
            height: 8px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 9999px;
        }

        /* Truncate text style */
        .truncate-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin/templet/sider')

        <!-- Main Content Container -->
        <div class="main-content flex-1 flex flex-col overflow-y-auto bg-background-light">
            <main class="flex-1 flex flex-col bg-background-light">
                <div class="flex-1 p-3 sm:p-8">

                    <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Data Project</h2>

                    <!-- Search and Filter Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div class="relative w-full md:w-1/3">
                            <span
                                class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input
                                class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary form-input"
                                placeholder="Search..." type="text" />
                        </div>
                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <button
                                class="px-4 py-2 bg-white border border-border-light text-text-muted-light rounded-lg hover:bg-gray-50 transition-colors flex-1 md:flex-none">
                                Filter
                            </button>
                            <button id="tambahProjectBtn"
                                class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2 flex-1 md:flex-none">
                                <span class="material-icons-outlined">add</span>
                                <span class="hidden sm:inline">Tambah Project</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>

                    <!-- Data Table Panel -->
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="panel-title">
                                <span class="material-icons-outlined text-primary">view_list</span>
                                Data Project
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-text-muted-light">Total: <span
                                        class="font-semibold text-text-light">{{ $project->total() }}</span>
                                    project</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <!-- SCROLLABLE TABLE - TANPA INDICATOR -->
                            <div class="desktop-table">
                                <div class="scrollable-table-container table-shadow" id="scrollableTable">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 60px;">No</th>
                                                <th style="min-width: 200px;">Nama Project</th>
                                                <th style="min-width: 300px;">Deskripsi</th>
                                                <th style="min-width: 120px;">Harga</th>
                                                <th style="min-width: 120px;">Deadline</th>
                                                <th style="min-width: 150px;">Progres</th>
                                                <th style="min-width: 120px;">Status</th>
                                                <th style="min-width: 180px; text-align: center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="desktopTableBody">
                                            @foreach ($project as $index => $item)
                                                <tr>
                                                    <td style="min-width: 60px;">
                                                        {{ ($project->currentPage() - 1) * $project->perPage() + $index + 1 }}
                                                    </td>
                                                    <td style="min-width: 200px;">{{ $item->nama }}</td>
                                                    <td style="min-width: 300px;" class="truncate-text"
                                                        title="{{ $item->deskripsi }}">
                                                        {{ Str::limit($item->deskripsi, 50) }}
                                                    </td>
                                                    <td style="min-width: 120px;">{{ $item->harga }}</td>
                                                    <td style="min-width: 120px;">{{ $item->deadline->format('Y-m-d') }}
                                                    </td>
                                                    <td style="min-width: 150px;">
                                                        <div class="progress-bar">
                                                            <div class="progress-fill {{ $item->progres < 50 ? 'bg-red-500' : ($item->progres < 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                                style="width: {{ $item->progres }}%"></div>
                                                        </div>
                                                        <span
                                                            class="text-xs text-gray-600 dark:text-gray-400 mt-1 block">{{ $item->progres }}%</span>
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        <span
                                                            class="status-badge 
                                                            @if ($item->status == 'In Progress') status-inprogress 
                                                            @elseif($item->status == 'Active') status-active 
                                                            @elseif($item->status == 'Completed') status-done 
                                                            @else status-todo @endif">
                                                            {{ $item->status }}
                                                        </span>
                                                    </td>
                                                    <td style="min-width: 180px; text-align: center;">
                                                        <div class="flex justify-center gap-2">
                                                            <button
                                                                class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                                onclick="openDetailModal({{ $item->id }}, '{{ $item->nama }}', '{{ $item->deskripsi }}', '{{ $item->harga }}', '{{ $item->deadline->format('Y-m-d') }}', {{ $item->progres }}, '{{ $item->status }}')"
                                                                title="Lihat Detail">
                                                                <span class="material-icons-outlined">visibility</span>
                                                            </button>
                                                            <button
                                                                class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                                onclick="openEditModal({{ $item->id }}, '{{ $item->nama }}', '{{ $item->deskripsi }}', '{{ $item->deadline->format('Y-m-d') }}', {{ $item->progres }}, '{{ $item->status }}')"
                                                                title="Edit">
                                                                <span class="material-icons-outlined">edit</span>
                                                            </button>
                                                            <button
                                                                class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama }}')"
                                                                title="Hapus">
                                                                <span class="material-icons-outlined">delete</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="mobile-cards space-y-4" id="mobile-cards">
                                @foreach ($project as $item)
                                    <div class="bg-white rounded-lg border border-border-light p-4 shadow-sm">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-semibold text-base">{{ $item->nama }}</h4>
                                                <p class="text-sm text-text-muted-light">Deadline:
                                                    {{ $item->deadline->format('Y-m-d') }}</p>
                                            </div>
                                            <div class="flex gap-2">
                                                <button
                                                    class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                    onclick="openDetailModal({{ $item->id }}, '{{ $item->nama }}', '{{ $item->deskripsi }}', '{{ $item->harga }}', '{{ $item->deadline->format('Y-m-d') }}', {{ $item->progres }}, '{{ $item->status }}')"
                                                    title="Lihat Detail">
                                                    <span class="material-icons-outlined">visibility</span>
                                                </button>
                                                <button
                                                    class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                    onclick="openEditModal({{ $item->id }}, '{{ $item->nama }}', '{{ $item->deskripsi }}', '{{ $item->deadline->format('Y-m-d') }}', {{ $item->progres }}, '{{ $item->status }}')"
                                                    title="Edit">
                                                    <span class="material-icons-outlined">edit</span>
                                                </button>
                                                <button
                                                    class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                                    onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama }}')"
                                                    title="Hapus">
                                                    <span class="material-icons-outlined">delete</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <p class="text-text-muted-light">Status</p>
                                                <p>
                                                    <span
                                                        class="status-badge 
                                                        @if ($item->status == 'Pending') status-inprogress 
                                                        @elseif($item->status == 'Active') status-active 
                                                        @elseif($item->status == 'Completed') status-done 
                                                        @else status-todo @endif">
                                                        {{ $item->status }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-span-2">
                                                <p class="text-text-muted-light">Progres</p>
                                                <div class="progress-bar mt-1">
                                                    <div class="progress-fill {{ $item->progres < 50 ? 'bg-red-500' : ($item->progres < 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                        style="width: {{ $item->progres }}%"></div>
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $item->progres }}%</p>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <p class="text-text-muted-light">Deskripsi</p>
                                            <p class="font-medium">{{ Str::limit($item->deskripsi, 80) }}</p>
                                            @if (strlen($item->deskripsi) > 80)
                                                <button class="text-primary text-sm mt-1"
                                                    onclick="openDetailModal({{ $item->id }}, '{{ $item->nama }}', '{{ $item->deskripsi }}', '{{ $item->harga }}', '{{ $item->deadline->format('Y-m-d') }}', {{ $item->progres }}, '{{ $item->status }}')">
                                                    Lihat selengkapnya
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Desktop Pagination -->
                            <div class="desktop-pagination">
                                <button class="desktop-nav-btn" @if ($project->currentPage() == 1) disabled @endif
                                    onclick="window.location.href='{{ $project->previousPageUrl() }}'">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <div class="flex gap-1">
                                    @for ($i = 1; $i <= $project->lastPage(); $i++)
                                        <button
                                            class="desktop-page-btn {{ $i == $project->currentPage() ? 'active' : '' }}"
                                            onclick="window.location.href='{{ $project->url($i) }}'">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <button class="desktop-nav-btn" @if ($project->currentPage() == $project->lastPage()) disabled @endif
                                    onclick="window.location.href='{{ $project->nextPageUrl() }}'">
                                    <span class="material-icons-outlined text-sm">chevron_right</span>
                                </button>
                            </div>

                            <!-- Mobile Pagination -->
                            <div class="mobile-pagination md:hidden flex justify-center items-center gap-2 mt-4">
                                <button
                                    class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if ($project->currentPage() == 1) disabled @endif
                                    onclick="window.location.href='{{ $project->previousPageUrl() }}'">
                                    <span class="material-icons-outlined text-sm">chevron_left</span>
                                </button>
                                <div class="flex gap-1">
                                    @for ($i = 1; $i <= $project->lastPage(); $i++)
                                        <button
                                            class="page-btn w-8 h-8 rounded-full flex items-center justify-center text-sm {{ $i == $project->currentPage() ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600' }}"
                                            onclick="window.location.href='{{ $project->url($i) }}'">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                                <button
                                    class="page-btn w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if ($project->currentPage() == $project->lastPage()) disabled @endif
                                    onclick="window.location.href='{{ $project->nextPageUrl() }}'">
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
    </div>

<!-- Modal Tambah Orderan -->
<div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Tambah Project Baru</h3>
                <button class="close-modal text-gray-800 hover:text-gray-500">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form id="tambahForm" action="{{ route('admin.project.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Layanan</label>
                    <select name="layanan_id" id="tambahLayanan"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach ($layanans as $layanan)
                            <option value="{{ $layanan->id }}" data-nama="{{ $layanan->nama_layanan }}"
                                data-deskripsi="{{ $layanan->deskripsi }}" data-harga="{{ $layanan->harga }}">
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Project</label>
                    <input type="text" name="nama" id="tambahNama"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        readonly required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="tambahDeskripsi"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        rows="3" readonly required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                    <input type="number" name="harga" id="tambahHarga"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        readonly required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                    <input type="date" name="deadline" id="tambahDeadline"
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                        required>
                    @error('deadline')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Hanya input hidden untuk status default -->
                <input type="hidden" name="status" value="Pending">
                
                <div class="flex justify-end gap-2">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- HAPUS atau KOMENTARI bagian ini dari modal tambah -->
    <!--
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
    <select name="status" id="tambahStatus"
        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
        required>
        <option value="Pending">Pending</option>
        <option value="Dalam Pengerjaan">Dalam Pengerjaan</option>
        <option value="Selesai">Selesai</option>
        <option value="Dibatalkan">Dibatalkan</option>
    </select>
    @error('status')
    <span class="text-red-500 text-xs">{{ $message }}</span>
@enderror
</div>
-->


    <!-- GANTI dengan input hidden untuk status default -->
    <input type="hidden" name="status" value="Pending">

    <!-- Modal Detail Orderan -->
    <div id="detailModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Project</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">ID Project</h4>
                            <p class="text-base font-medium" id="detailId"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Nama Project</h4>
                            <p class="text-base font-medium" id="detailNama"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Harga</h4>
                            <p class="text-base font-medium" id="detailHarga"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Deadline</h4>
                            <p class="text-base font-medium" id="detailDeadline"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                            <p id="detailStatus"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Progres</h4>
                            <div class="flex items-center gap-2">
                                <div class="progress-bar flex-1">
                                    <div class="progress-fill" id="detailProgressBar"></div>
                                </div>
                                <span class="text-sm font-medium" id="detailProgres"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h4>
                        <p class="text-base" id="detailDeskripsi"></p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Orderan - PASTIKAN ID SESUAI -->
    <div id="editModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Project</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editForm" method="POST"> <!-- ID: editForm -->
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId"> <!-- ID: editId -->

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Project</label>
                        <input type="text" name="nama" id="editNama"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required> <!-- ID: editNama -->
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="editDeskripsi" rows="3"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required></textarea> <!-- ID: editDeskripsi -->
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" name="deadline" id="editDeadline"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                            required> <!-- ID: editDeadline -->
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Orderan -->
    <div id="deleteModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus project <span id="deleteNama"
                            class="font-semibold"></span>?</p>
                    <p class="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="deleteId">
                    <div class="flex justify-end gap-2">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-20 opacity-0 flex items-center">
        <span id="toastMessage" class="mr-2"></span>
        <button id="closeToast" class="ml-2 text-white hover:text-gray-200">
            <span class="material-icons-outlined">close</span>
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div id="successToast"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 flex items-center">
            <span class="mr-2">{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="ml-2 text-white hover:text-gray-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="errorToast"
            class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 flex items-center">
            <span class="mr-2">{{ session('error') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="ml-2 text-white hover:text-gray-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
    @endif

<script>
    // ============================
    // GLOBAL FUNCTIONS
    // ============================
    
    // Function to show toast notification (moved outside DOMContentLoaded)
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        if (toast && toastMessage) {
            toastMessage.textContent = message;
            
            // Set warna berdasarkan type
            if (type === 'success') {
                toast.style.backgroundColor = '#10b981'; // green
            } else if (type === 'error') {
                toast.style.backgroundColor = '#ef4444'; // red
            } else if (type === 'warning') {
                toast.style.backgroundColor = '#f59e0b'; // yellow
            }
            
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }
    }

    // ============================
    // OPEN DETAIL MODAL
    // ============================
    function openDetailModal(id, nama, deskripsi, harga, deadline, progres, status) {
        const detailId = document.getElementById('detailId');
        const detailNama = document.getElementById('detailNama');
        const detailDeskripsi = document.getElementById('detailDeskripsi');
        const detailHarga = document.getElementById('detailHarga');
        const detailDeadline = document.getElementById('detailDeadline');
        const detailProgres = document.getElementById('detailProgres');
        const detailStatus = document.getElementById('detailStatus');
        const detailProgressBar = document.getElementById('detailProgressBar');
        const detailModal = document.getElementById('detailModal');
        
        if (detailId && detailNama && detailDeskripsi && detailHarga && detailDeadline && 
            detailProgres && detailStatus && detailProgressBar && detailModal) {
            
            detailId.textContent = '#' + id;
            detailNama.textContent = nama;
            detailDeskripsi.textContent = deskripsi;
            detailHarga.textContent = harga;
            detailDeadline.textContent = deadline;
            detailProgres.textContent = progres + '%';

            // Set status badge
            let statusClass = '';
            if (status === 'Pending') {
                statusClass = 'status-todo';
            } else if (status === 'Dalam Pengerjaan') {
                statusClass = 'status-progress';
            } else if (status === 'Selesai') {
                statusClass = 'status-done';
            } else if (status === 'Dibatalkan') {
                statusClass = 'status-todo';
            }
            detailStatus.innerHTML = `<span class="status-badge ${statusClass}">${status}</span>`;

            // Set progress bar
            let progressColor = '';
            if (progres < 50) {
                progressColor = 'bg-red-500';
            } else if (progres < 80) {
                progressColor = 'bg-yellow-500';
            } else {
                progressColor = 'bg-green-500';
            }
            detailProgressBar.className = `progress-fill ${progressColor}`;
            detailProgressBar.style.width = progres + '%';

            detailModal.classList.remove('hidden');
        } else {
            console.error('One or more detail modal elements not found');
            showToast('Error: Detail modal tidak ditemukan', 'error');
        }
    }

    // ============================
    // OPEN EDIT MODAL
    // ============================
    function openEditModal(id, nama, deskripsi, harga, deadline, progres, status) {
        console.log('openEditModal called with:', { id, nama, harga, deadline });
        
        const editId = document.getElementById('editId');
        const editNama = document.getElementById('editNama');
        const editDeskripsi = document.getElementById('editDeskripsi');
        const editDeadline = document.getElementById('editDeadline');
        const editForm = document.getElementById('editForm');
        const editModal = document.getElementById('editModal');
        
        console.log('Elements found:', {
            editId: !!editId,
            editNama: !!editNama,
            editDeskripsi: !!editDeskripsi,
            editDeadline: !!editDeadline,
            editForm: !!editForm,
            editModal: !!editModal
        });
        
        if (editId && editNama && editDeskripsi && editDeadline && editForm && editModal) {
            editId.value = id;
            editNama.value = nama;
            editDeskripsi.value = deskripsi;
            editDeadline.value = deadline;
            
            // Update form action dengan route yang benar
            editForm.action = `/admin/project/${id}`;
            
            editModal.classList.remove('hidden');
            console.log('Edit modal opened successfully');
        } else {
            console.error('One or more edit form elements not found');
            showToast('Error: Form edit tidak ditemukan', 'error');
        }
    }

    // ============================
    // OPEN DELETE MODAL
    // ============================
    function openDeleteModal(id, nama) {
        const deleteId = document.getElementById('deleteId');
        const deleteNama = document.getElementById('deleteNama');
        const deleteForm = document.getElementById('deleteForm');
        const deleteModal = document.getElementById('deleteModal');
        
        if (deleteId && deleteNama && deleteForm && deleteModal) {
            deleteId.value = id;
            deleteNama.textContent = nama;
            
            // Update form action dengan route yang benar
            deleteForm.action = `/admin/project/${id}`;
            
            deleteModal.classList.remove('hidden');
        } else {
            console.error('One or more delete form elements not found');
            showToast('Error: Form delete tidak ditemukan', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - Data Project');
        
        // Debug: Cek elemen
        console.log('editForm element:', document.getElementById('editForm'));
        console.log('editModal element:', document.getElementById('editModal'));
        
        const tambahModal = document.getElementById('tambahModal');
        const editModal = document.getElementById('editModal');
        const detailModal = document.getElementById('detailModal');
        const deleteModal = document.getElementById('deleteModal');

        const tambahForm = document.getElementById('tambahForm');
        const editForm = document.getElementById('editForm');
        const deleteForm = document.getElementById('deleteForm');

        const tambahProjectBtn = document.getElementById('tambahProjectBtn');
        const closeModals = document.querySelectorAll('.close-modal');

        const tambahLayanan = document.getElementById('tambahLayanan');
        const tambahNama = document.getElementById('tambahNama');
        const tambahDeskripsi = document.getElementById('tambahDeskripsi');
        const tambahHarga = document.getElementById('tambahHarga');

        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const closeToastBtn = document.getElementById('closeToast');

        // Show tambah modal
        if (tambahProjectBtn) {
            tambahProjectBtn.addEventListener('click', function() {
                tambahModal.classList.remove('hidden');
                if (tambahForm) tambahForm.reset();
                
                // kosongkan field autofill
                if (tambahLayanan) tambahLayanan.value = '';
                if (tambahNama) tambahNama.value = '';
                if (tambahDeskripsi) tambahDeskripsi.value = '';
                if (tambahHarga) tambahHarga.value = '';
            });
        }

        // Auto-fill tambah form based on layanan selection
        if (tambahLayanan) {
            tambahLayanan.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                
                if (tambahNama) tambahNama.value = selected.getAttribute('data-nama') || '';
                if (tambahDeskripsi) tambahDeskripsi.value = selected.getAttribute('data-deskripsi') || '';
                if (tambahHarga) tambahHarga.value = selected.getAttribute('data-harga') || '';
            });
        }

        // Close modals
        closeModals.forEach(btn => {
            btn.addEventListener('click', function() {
                if (tambahModal) tambahModal.classList.add('hidden');
                if (editModal) editModal.classList.add('hidden');
                if (detailModal) detailModal.classList.add('hidden');
                if (deleteModal) deleteModal.classList.add('hidden');
            });
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === tambahModal) {
                tambahModal.classList.add('hidden');
            }
            if (event.target === editModal) {
                editModal.classList.add('hidden');
            }
            if (event.target === detailModal) {
                detailModal.classList.add('hidden');
            }
            if (event.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
        
        // Handle tambah form submission with AJAX
        if (tambahForm) {
            tambahForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(tambahForm);
                
                fetch(tambahForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const responseText = await response.text();
                    
                    try {
                        const data = JSON.parse(responseText);
                        
                        if (!response.ok) {
                            if (response.status === 422) {
                                if (data.errors) {
                                    const firstError = Object.values(data.errors)[0][0];
                                    showToast(firstError, 'error');
                                } else {
                                    showToast(data.message || 'Validasi gagal', 'error');
                                }
                            } else {
                                showToast(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                            }
                            throw data;
                        }
                        
                        return data;
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan server. Silakan coba lagi.', 'error');
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        tambahModal.classList.add('hidden');
                        tambahForm.reset();
                        
                        // Reload page to show new data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
        
        // Handle edit form submission with AJAX
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(editForm);
                const id = document.getElementById('editId').value;
                
                fetch(`/admin/project/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const responseText = await response.text();
                    
                    try {
                        const data = JSON.parse(responseText);
                        
                        if (!response.ok) {
                            if (response.status === 422) {
                                if (data.errors) {
                                    const firstError = Object.values(data.errors)[0][0];
                                    showToast(firstError, 'error');
                                } else {
                                    showToast(data.message || 'Validasi gagal', 'error');
                                }
                            } else {
                                showToast(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                            }
                            throw data;
                        }
                        
                        return data;
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan server. Silakan coba lagi.', 'error');
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        editModal.classList.add('hidden');
                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
        
        // Handle delete form submission with AJAX
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(deleteForm);
                const id = document.getElementById('deleteId').value;

                fetch(`/admin/project/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async (response) => {
                    const responseText = await response.text();
                    
                    try {
                        const data = JSON.parse(responseText);
                        
                        if (!response.ok) {
                            throw data;
                        }
                        
                        return data;
                    } catch (error) {
                        console.error('Invalid JSON response:', responseText);
                        throw new Error('Invalid server response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        deleteModal.classList.add('hidden');

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    if (error.errors) {
                        const firstError = Object.values(error.errors)[0][0];
                        showToast(firstError, 'error');
                    } else {
                        showToast(error.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                });
            });
        }
        
        // Close toast notification
        if (closeToastBtn) {
            closeToastBtn.addEventListener('click', function() {
                toast.classList.add('translate-y-20', 'opacity-0');
            });
        }
    });
</script>

    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</body>

</html>

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

        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .order-table {
            transition: all 0.2s ease;
        }

        .order-table tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

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

        /* Status Pengerjaan */
        .status-pending {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }

        .status-dalam-pengerjaan {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }

        .status-selesai {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .status-dibatalkan {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        /* Status Kerjasama */
        .status-aktif {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
        }

        .status-selesai-kerjasama {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1e40af;
        }

        .status-ditangguhkan {
            background-color: rgba(245, 158, 11, 0.15);
            color: #92400e;
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

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

        .nav-item {
            position: relative;
            overflow: hidden;
        }

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

        .sidebar-fixed {
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 256px;
            }
        }

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

        @media (max-width: 639px) {
            .desktop-table {
                display: none;
            }

            .mobile-cards {
                display: block;
            }

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

            .mobile-pagination {
                display: none !important;
            }
        }

        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

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

        .scrollable-table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
        }

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

        .data-table {
            width: 100%;
            min-width: 2000px;
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

        .table-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

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

        .truncate-text {
            max-width: 200px;
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

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.data_project') }}"
                          class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <!-- SEARCH -->
                        <div class="relative w-full md:w-1/3">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                search
                            </span>
                            <input
                                name="q"
                                value="{{ request('q') }}"
                                class="w-full pl-10 pr-4 py-2 bg-white border border-border-light rounded-lg
                                       focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="Cari nama / deskripsi project..."
                                type="text">
                        </div>

                        <!-- FILTER -->
                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <!-- Filter Status Pengerjaan -->
                            <select name="status_pengerjaan"
                                class="px-3 py-2 bg-white border border-border-light rounded-lg">
                                <option value="">Semua Status Pengerjaan</option>
                                <option value="pending" {{ request('status_pengerjaan')=='pending'?'selected':'' }}>Pending</option>
                                <option value="dalam_pengerjaan" {{ request('status_pengerjaan')=='dalam_pengerjaan'?'selected':'' }}>Dalam Pengerjaan</option>
                                <option value="selesai" {{ request('status_pengerjaan')=='selesai'?'selected':'' }}>Selesai</option>
                                <option value="dibatalkan" {{ request('status_pengerjaan')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                            </select>

                            <!-- Filter Status Kerjasama -->
                            <select name="status_kerjasama"
                                class="px-3 py-2 bg-white border border-border-light rounded-lg">
                                <option value="">Semua Status Kerjasama</option>
                                <option value="aktif" {{ request('status_kerjasama')=='aktif'?'selected':'' }}>Aktif</option>
                                <option value="selesai" {{ request('status_kerjasama')=='selesai'?'selected':'' }}>Selesai</option>
                                <option value="ditangguhkan" {{ request('status_kerjasama')=='ditangguhkan'?'selected':'' }}>Ditangguhkan</option>
                            </select>

                            <!-- Filter Tanggal Mulai Pengerjaan -->
                            <input type="date" name="tanggal_mulai_pengerjaan"
                                value="{{ request('tanggal_mulai_pengerjaan') }}"
                                class="px-3 py-2 bg-white border border-border-light rounded-lg"
                                placeholder="Mulai Pengerjaan">

                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-lg">
                                Terapkan
                            </button>

                            <a href="{{ route('admin.data_project') }}"
                               class="px-4 py-2 bg-gray-200 rounded-lg">
                                Reset
                            </a>

                            <button type="button" id="tambahProjectBtn"
                                class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                                <span class="material-icons-outlined">add</span>
                                Tambah Project
                            </button>
                        </div>
                    </form>

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
                            <!-- SCROLLABLE TABLE -->
                            <div class="desktop-table">
                                <div class="scrollable-table-container table-shadow" id="scrollableTable">
                                    <table class="data-table">
                                       <!-- Di bagian thead table -->
<thead>
    <tr>
        <th style="min-width: 60px;">No</th>
        <th style="min-width: 150px;">Invoice</th>
        <th style="min-width: 200px;">Nama Project</th>
        <th style="min-width: 200px;">Deskripsi</th>
        <th style="min-width: 120px;">Harga</th>
        <!-- Tambahkan ini di sini -->
        <th style="min-width: 150px;">Penanggung Jawab</th>
        <th style="min-width: 120px;">Mulai Pengerjaan</th>
        <th style="min-width: 120px;">Selesai Pengerjaan</th>
        <th style="min-width: 120px;">Mulai Kerjasama</th>
        <th style="min-width: 120px;">Selesai Kerjasama</th>
        <th style="min-width: 120px;">Status Pengerjaan</th>
        <th style="min-width: 120px;">Status Kerjasama</th>
        <th style="min-width: 150px;">Progres</th>
        <th style="min-width: 180px; text-align: center;">Aksi</th>
    </tr>
</thead>
                                        <tbody id="desktopTableBody">
                                            @foreach ($project as $index => $item)
                                                <tr>
                                                    <td style="min-width: 60px;">
                                                        {{ ($project->currentPage() - 1) * $project->perPage() + $index + 1 }}
                                                    </td>
                                                    <td style="min-width: 150px;">
                                                        @if($item->invoice)
                                                            Invoice #{{ $item->invoice->id }}
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 200px;">{{ $item->nama }}</td>
                                                    <td style="min-width: 200px;" class="truncate-text"
                                                        title="{{ $item->deskripsi }}">
                                                        {{ Str::limit($item->deskripsi, 50) }}
                                                    </td>
                                                    <td style="min-width: 120px;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                    <td style="min-width: 120px;">{{ $item->tanggal_mulai_pengerjaan->format('Y-m-d') }}</td>
                                                    <td style="min-width: 120px;">
                                                        @if($item->tanggal_selesai_pengerjaan)
                                                            {{ $item->tanggal_selesai_pengerjaan->format('Y-m-d') }}
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        @if($item->tanggal_mulai_kerjasama)
                                                            {{ $item->tanggal_mulai_kerjasama->format('Y-m-d') }}
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        @if($item->tanggal_selesai_kerjasama)
                                                            {{ $item->tanggal_selesai_kerjasama->format('Y-m-d') }}
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        <span class="status-badge status-{{ str_replace('_', '-', $item->status_pengerjaan) }}">
                                                            {{ $item->status_pengerjaan_formatted }}
                                                        </span>
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        <span class="status-badge status-{{ $item->status_kerjasama }}">
                                                            {{ $item->status_kerjasama_formatted }}
                                                        </span>
                                                    </td>
                                                    <td style="min-width: 150px;">
                                                        <div class="progress-bar">
                                                            <div class="progress-fill {{ $item->progres < 50 ? 'bg-red-500' : ($item->progres < 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                                style="width: {{ $item->progres }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-600 dark:text-gray-400 mt-1 block">{{ $item->progres }}%</span>
                                                    </td>
                                                    <td style="min-width: 180px; text-align: center;">
                                                        <div class="flex justify-center gap-2">
                                                            <button
                                                                class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                                onclick="openDetailModal({{ $item->id }})"
                                                                title="Lihat Detail">
                                                                <span class="material-icons-outlined">visibility</span>
                                                            </button>
                                                            <button
                                                                class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                                onclick="openEditModal({{ $item->id }})"
                                                                title="Edit">
                                                                <span class="material-icons-outlined">edit</span>
                                                            </button>
                                                            <button
                                                                class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                                                onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
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
                                                <p class="text-sm text-text-muted-light">
                                                    @if($item->invoice)
                                                        <span>Invoice #{{ $item->invoice->id }}</span><br>
                                                    @endif
                                                    Mulai: {{ $item->tanggal_mulai_pengerjaan->format('Y-m-d') }}
                                                    @if($item->tanggal_selesai_pengerjaan)
                                                        <br>Selesai: {{ $item->tanggal_selesai_pengerjaan->format('Y-m-d') }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex gap-2">
                                                <button
                                                    class="detail-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                    onclick="openDetailModal({{ $item->id }})"
                                                    title="Lihat Detail">
                                                    <span class="material-icons-outlined">visibility</span>
                                                </button>
                                                <button
                                                    class="edit-btn p-1 rounded-full hover:bg-primary/20 text-gray-700"
                                                    onclick="openEditModal({{ $item->id }})"
                                                    title="Edit">
                                                    <span class="material-icons-outlined">edit</span>
                                                </button>
                                                <button
                                                    class="delete-btn p-1 rounded-full hover:bg-red-500/20 text-gray-700"
                                                    onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                                    title="Hapus">
                                                    <span class="material-icons-outlined">delete</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                            <div>
                                                <p class="text-text-muted-light">Status Pengerjaan</p>
                                                <p>
                                                    <span class="status-badge status-{{ str_replace('_', '-', $item->status_pengerjaan) }}">
                                                        {{ $item->status_pengerjaan_formatted }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-text-muted-light">Status Kerjasama</p>
                                                <p>
                                                    <span class="status-badge status-{{ $item->status_kerjasama }}">
                                                        {{ $item->status_kerjasama_formatted }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-span-2 mb-3">
                                            <p class="text-text-muted-light">Progres</p>
                                            <div class="progress-bar mt-1">
                                                <div class="progress-fill {{ $item->progres < 50 ? 'bg-red-500' : ($item->progres < 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                    style="width: {{ $item->progres }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $item->progres }}%</p>
                                        </div>
                                        <div class="mt-3">
                                            <p class="text-text-muted-light">Harga</p>
                                            <p class="font-medium">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="mt-3">
                                            <p class="text-text-muted-light">Deskripsi</p>
                                            <p class="font-medium">{{ Str::limit($item->deskripsi, 80) }}</p>
                                            @if (strlen($item->deskripsi) > 80)
                                                <button class="text-primary text-sm mt-1"
                                                    onclick="openDetailModal(
                                                        {{ $item->id }}, 
                                                        '{{ addslashes($item->nama) }}', 
                                                        '{{ addslashes($item->deskripsi) }}', 
                                                        '{{ number_format($item->harga, 0, ',', '.') }}', 
                                                        '{{ $item->tanggal_mulai_pengerjaan->format('Y-m-d') }}', 
                                                        '{{ $item->tanggal_selesai_pengerjaan ? $item->tanggal_selesai_pengerjaan->format('Y-m-d') : '' }}', 
                                                        '{{ $item->tanggal_mulai_kerjasama ? $item->tanggal_mulai_kerjasama->format('Y-m-d') : '' }}', 
                                                        '{{ $item->tanggal_selesai_kerjasama ? $item->tanggal_selesai_kerjasama->format('Y-m-d') : '' }}', 
                                                        '{{ $item->status_pengerjaan }}', 
                                                        '{{ $item->status_kerjasama }}', 
                                                        {{ $item->progres }},
                                                        '{{ $item->invoice ? 'Invoice #' . $item->invoice->id : '' }}'
                                                    )">
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

    <!-- ============================ -->
    <!-- MODAL TAMBAH PROJECT -->
    <!-- ============================ -->
    <div id="tambahModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Project Baru</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="tambahForm" action="{{ route('admin.project.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Invoice</label>
                            <select name="invoice_id" id="tambahInvoice"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="">-- Pilih Invoice --</option>
                                @foreach ($invoices as $invoice)
                                    <option value="{{ $invoice->id }}" 
                                            data-nama="{{ $invoice->judul ?? 'Project dari Invoice #' . $invoice->id }}"
                                            data-deskripsi="{{ $invoice->deskripsi ?? '' }}" 
                                            data-harga="{{ $invoice->total ?? 0 }}"
                                            data-tanggal-mulai="{{ $invoice->tanggal_mulai ? $invoice->tanggal_mulai->format('Y-m-d') : '' }}"
                                            data-tanggal-selesai="{{ $invoice->tanggal_selesai ? $invoice->tanggal_selesai->format('Y-m-d') : '' }}">
                                        Invoice #{{ $invoice->id }} - {{ $invoice->judul ?? 'Tanpa Judul' }} (Rp {{ number_format($invoice->total ?? 0, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Project</label>
                            <input type="text" name="nama" id="tambahNama"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                readonly required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="tambahDeskripsi" rows="3"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                readonly required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="number" name="harga" id="tambahHarga"
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                readonly required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Pengerjaan</label>
                                <input type="date" name="tanggal_mulai_pengerjaan" id="tambahTanggalMulaiPengerjaan"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                    required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Pengerjaan</label>
                                <input type="date" name="tanggal_selesai_pengerjaan" id="tambahTanggalSelesaiPengerjaan"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Kerjasama</label>
                                <input type="date" name="tanggal_mulai_kerjasama" id="tambahTanggalMulaiKerjasama"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Kerjasama</label>
                                <input type="date" name="tanggal_selesai_kerjasama" id="tambahTanggalSelesaiKerjasama"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengerjaan</label>
                                <select name="status_pengerjaan" id="tambahStatusPengerjaan"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="pending" selected>Pending</option>
                                    <option value="dalam_pengerjaan">Dalam Pengerjaan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Kerjasama</label>
                                <select name="status_kerjasama" id="tambahStatusKerjasama"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditangguhkan">Ditangguhkan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Progres (%)</label>
                            <input type="range" name="progres" id="tambahProgres" min="0" max="100" value="0"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                oninput="document.getElementById('tambahProgresValue').textContent = this.value + '%'">
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm text-gray-600">0%</span>
                                <span id="tambahProgresValue" class="text-sm font-medium">0%</span>
                                <span class="text-sm text-gray-600">100%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================ -->
    <!-- MODAL DETAIL PROJECT -->
    <!-- ============================ -->
    <div id="detailModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Detail Project</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">ID Project</h4>
                            <p class="text-base font-medium" id="detailId"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Invoice</h4>
                            <p class="text-base font-medium" id="detailInvoice"></p>
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
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status Pengerjaan</h4>
                            <p class="text-base" id="detailStatusPengerjaan"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status Kerjasama</h4>
                            <p class="text-base" id="detailStatusKerjasama"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Mulai Pengerjaan</h4>
                            <p class="text-base font-medium" id="detailTanggalMulaiPengerjaan"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Selesai Pengerjaan</h4>
                            <p class="text-base font-medium" id="detailTanggalSelesaiPengerjaan"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Mulai Kerjasama</h4>
                            <p class="text-base font-medium" id="detailTanggalMulaiKerjasama"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Selesai Kerjasama</h4>
                            <p class="text-base font-medium" id="detailTanggalSelesaiKerjasama"></p>
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
                        <p class="text-base whitespace-pre-wrap" id="detailDeskripsi"></p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================ -->
    <!-- MODAL EDIT PROJECT -->
    <!-- ============================ -->
    <div id="editModal"
        class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Project</h3>
                    <button class="close-modal text-gray-800 hover:text-gray-500">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Project</label>
                            <input type="text" name="nama" id="editNama"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" rows="3"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                required></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Pengerjaan</label>
                                <input type="date" name="tanggal_mulai_pengerjaan" id="editTanggalMulaiPengerjaan"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                    required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Pengerjaan</label>
                                <input type="date" name="tanggal_selesai_pengerjaan" id="editTanggalSelesaiPengerjaan"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Kerjasama</label>
                                <input type="date" name="tanggal_mulai_kerjasama" id="editTanggalMulaiKerjasama"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Kerjasama</label>
                                <input type="date" name="tanggal_selesai_kerjasama" id="editTanggalSelesaiKerjasama"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengerjaan</label>
                                <select name="status_pengerjaan" id="editStatusPengerjaan"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="pending">Pending</option>
                                    <option value="dalam_pengerjaan">Dalam Pengerjaan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Kerjasama</label>
                                <select name="status_kerjasama" id="editStatusKerjasama"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="aktif">Aktif</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditangguhkan">Ditangguhkan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Progres (%)</label>
                            <input type="range" name="progres" id="editProgres" min="0" max="100"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                oninput="document.getElementById('editProgresValue').textContent = this.value + '%'">
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm text-gray-600">0%</span>
                                <span id="editProgresValue" class="text-sm font-medium">0%</span>
                                <span class="text-sm text-gray-600">100%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="close-modal px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================ -->
    <!-- MODAL HAPUS PROJECT -->
    <!-- ============================ -->
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

    <!-- ============================ -->
    <!-- TOAST NOTIFICATION -->
    <!-- ============================ -->
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
        
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            if (toast && toastMessage) {
                toastMessage.textContent = message;
                
                if (type === 'success') {
                    toast.style.backgroundColor = '#10b981';
                } else if (type === 'error') {
                    toast.style.backgroundColor = '#ef4444';
                } else if (type === 'warning') {
                    toast.style.backgroundColor = '#f59e0b';
                }
                
                toast.classList.remove('translate-y-20', 'opacity-0');
                
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }
        }

        function getStatusPengerjaanLabel(status) {
            const statusMap = {
                'pending': 'Pending',
                'dalam_pengerjaan': 'Dalam Pengerjaan',
                'selesai': 'Selesai',
                'dibatalkan': 'Dibatalkan'
            };
            return statusMap[status] || status;
        }

        function getStatusKerjasamaLabel(status) {
            const statusMap = {
                'aktif': 'Aktif',
                'selesai': 'Selesai',
                'ditangguhkan': 'Ditangguhkan'
            };
            return statusMap[status] || status;
        }

        function getStatusPengerjaanClass(status) {
            return `status-${status.replace('_', '-')}`;
        }

        function getStatusKerjasamaClass(status) {
            return `status-${status}`;
        }

        // ============================
        // OPEN DETAIL MODAL
        // ============================
        function openDetailModal(id) {
            const detailModal = document.getElementById('detailModal');
            if (!detailModal) {
                console.error('Detail modal not found');
                return;
            }

            // Fetch latest project data from server
            fetch(`/admin/project/${id}`)
                .then(res => res.json())
                .then(json => {
                    if (!json.success || !json.data) {
                        showToast(json.message || 'Gagal mengambil data project', 'error');
                        return;
                    }

                    const p = json.data;
                    document.getElementById('detailId').textContent = '#' + p.id;
                    document.getElementById('detailNama').textContent = p.nama || '-';
                    document.getElementById('detailDeskripsi').textContent = p.deskripsi || '-';
                    document.getElementById('detailHarga').textContent = p.harga ? p.harga : '-';
                    document.getElementById('detailInvoice').textContent = p.invoice ? ('Invoice #' + p.invoice.id) : '-';
                    document.getElementById('detailTanggalMulaiPengerjaan').textContent = p.tanggal_mulai_pengerjaan || '-';
                    document.getElementById('detailTanggalSelesaiPengerjaan').textContent = p.tanggal_selesai_pengerjaan || '-';
                    document.getElementById('detailTanggalMulaiKerjasama').textContent = p.tanggal_mulai_kerjasama || '-';
                    document.getElementById('detailTanggalSelesaiKerjasama').textContent = p.tanggal_selesai_kerjasama || '-';
                    document.getElementById('detailProgres').textContent = (p.progres || 0) + '%';

                    const statusPengerjaanElement = document.getElementById('detailStatusPengerjaan');
                    const pengerjaanClass = getStatusPengerjaanClass(p.status_pengerjaan || 'pending');
                    const pengerjaanLabel = getStatusPengerjaanLabel(p.status_pengerjaan || 'pending');
                    statusPengerjaanElement.innerHTML = `<span class="status-badge ${pengerjaanClass}">${pengerjaanLabel}</span>`;

                    const statusKerjasamaElement = document.getElementById('detailStatusKerjasama');
                    const kerjasamaClass = getStatusKerjasamaClass(p.status_kerjasama || 'aktif');
                    const kerjasamaLabel = getStatusKerjasamaLabel(p.status_kerjasama || 'aktif');
                    statusKerjasamaElement.innerHTML = `<span class="status-badge ${kerjasamaClass}">${kerjasamaLabel}</span>`;

                    const progressBar = document.getElementById('detailProgressBar');
                    let progressColor = '';
                    const prog = Number(p.progres || 0);
                    if (prog < 50) progressColor = 'bg-red-500';
                    else if (prog < 80) progressColor = 'bg-yellow-500';
                    else progressColor = 'bg-green-500';
                    if (progressBar) {
                        progressBar.className = `progress-fill ${progressColor}`;
                        progressBar.style.width = prog + '%';
                    }

                    detailModal.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error fetching project:', err);
                    showToast('Gagal mengambil data project', 'error');
                });
        }

        // ============================
        // OPEN EDIT MODAL
        // ============================
        function openEditModal(id) {
            const editModal = document.getElementById('editModal');
            if (!editModal) {
                console.error('Edit modal not found');
                return;
            }

            // Fetch project data and populate form
            fetch(`/admin/project/${id}`)
                .then(res => res.json())
                .then(json => {
                    if (!json.success || !json.data) {
                        showToast(json.message || 'Gagal mengambil data project', 'error');
                        return;
                    }

                    const p = json.data;
                    document.getElementById('editId').value = p.id;
                    document.getElementById('editNama').value = p.nama || '';
                    document.getElementById('editDeskripsi').value = p.deskripsi || '';
                    document.getElementById('editTanggalMulaiPengerjaan').value = p.tanggal_mulai_pengerjaan || '';
                    document.getElementById('editTanggalSelesaiPengerjaan').value = p.tanggal_selesai_pengerjaan || '';
                    document.getElementById('editTanggalMulaiKerjasama').value = p.tanggal_mulai_kerjasama || '';
                    document.getElementById('editTanggalSelesaiKerjasama').value = p.tanggal_selesai_kerjasama || '';
                    document.getElementById('editStatusPengerjaan').value = p.status_pengerjaan || 'pending';
                    document.getElementById('editStatusKerjasama').value = p.status_kerjasama || 'aktif';
                    document.getElementById('editProgres').value = p.progres || 0;
                    document.getElementById('editProgresValue').textContent = (p.progres || 0) + '%';

                    const editForm = document.getElementById('editForm');
                    editForm.action = `/admin/project/${p.id}`;

                    editModal.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error fetching project for edit:', err);
                    showToast('Gagal mengambil data project', 'error');
                });
        }

        // ============================
        // OPEN DELETE MODAL
        // ============================
        function openDeleteModal(id, nama) {
            const deleteModal = document.getElementById('deleteModal');
            if (!deleteModal) {
                console.error('Delete modal not found');
                return;
            }
            
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteNama').textContent = nama;
            
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/admin/project/${id}`;
            
            deleteModal.classList.remove('hidden');
        }

        // ============================
        // DOM READY
        // ============================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - Data Project');
            
            // Elements
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            const detailModal = document.getElementById('detailModal');
            const deleteModal = document.getElementById('deleteModal');
            
            const tambahForm = document.getElementById('tambahForm');
            const editForm = document.getElementById('editForm');
            const deleteForm = document.getElementById('deleteForm');
            
            const tambahProjectBtn = document.getElementById('tambahProjectBtn');
            const closeModals = document.querySelectorAll('.close-modal');
            
            const tambahInvoice = document.getElementById('tambahInvoice');
            const tambahNama = document.getElementById('tambahNama');
            const tambahDeskripsi = document.getElementById('tambahDeskripsi');
            const tambahHarga = document.getElementById('tambahHarga');
            const tambahTanggalMulaiKerjasama = document.getElementById('tambahTanggalMulaiKerjasama');
            const tambahTanggalSelesaiKerjasama = document.getElementById('tambahTanggalSelesaiKerjasama');
            
            const toast = document.getElementById('toast');
            const closeToastBtn = document.getElementById('closeToast');

            // Show tambah modal
            if (tambahProjectBtn) {
                tambahProjectBtn.addEventListener('click', function() {
                    if (tambahModal) {
                        tambahModal.classList.remove('hidden');
                        if (tambahForm) {
                            tambahForm.reset();
                            
                            // Set default values
                            const today = new Date().toISOString().split('T')[0];
                            document.getElementById('tambahTanggalMulaiPengerjaan').value = today;
                            document.getElementById('tambahStatusPengerjaan').value = 'pending';
                            document.getElementById('tambahStatusKerjasama').value = 'aktif';
                            document.getElementById('tambahProgres').value = 0;
                            document.getElementById('tambahProgresValue').textContent = '0%';
                        }
                        
                        // Clear autofill fields
                        if (tambahInvoice) tambahInvoice.value = '';
                        if (tambahNama) tambahNama.value = '';
                        if (tambahDeskripsi) tambahDeskripsi.value = '';
                        if (tambahHarga) tambahHarga.value = '';
                        if (tambahTanggalMulaiKerjasama) tambahTanggalMulaiKerjasama.value = '';
                        if (tambahTanggalSelesaiKerjasama) tambahTanggalSelesaiKerjasama.value = '';
                    }
                });
            }

            // Auto-fill tambah form based on invoice selection
            if (tambahInvoice) {
                tambahInvoice.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    
                    if (selected.value) {
                        // Mengambil data dari atribut data
                        const nama = selected.getAttribute('data-nama') || '';
                        const deskripsi = selected.getAttribute('data-deskripsi') || '';
                        const harga = selected.getAttribute('data-harga') || '';
                        const tanggalMulai = selected.getAttribute('data-tanggal-mulai') || '';
                        const tanggalSelesai = selected.getAttribute('data-tanggal-selesai') || '';
                        
                        if (tambahNama) tambahNama.value = nama;
                        if (tambahDeskripsi) tambahDeskripsi.value = deskripsi;
                        if (tambahHarga) tambahHarga.value = harga;
                        if (tambahTanggalMulaiKerjasama && tanggalMulai) {
                            tambahTanggalMulaiKerjasama.value = tanggalMulai;
                        }
                        if (tambahTanggalSelesaiKerjasama && tanggalSelesai) {
                            tambahTanggalSelesaiKerjasama.value = tanggalSelesai;
                        }
                        
                        // Atau gunakan AJAX untuk mengambil data lengkap
                        fetch(`/admin/project/invoice/${selected.value}/details`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.data) {
                                    if (tambahNama && data.data.nama) tambahNama.value = data.data.nama;
                                    if (tambahDeskripsi && data.data.deskripsi) tambahDeskripsi.value = data.data.deskripsi;
                                    if (tambahHarga && data.data.harga) tambahHarga.value = data.data.harga;
                                    if (tambahTanggalMulaiKerjasama && data.data.tanggal_mulai_kerjasama) {
                                        tambahTanggalMulaiKerjasama.value = data.data.tanggal_mulai_kerjasama;
                                    }
                                    if (tambahTanggalSelesaiKerjasama && data.data.tanggal_selesai_kerjasama) {
                                        tambahTanggalSelesaiKerjasama.value = data.data.tanggal_selesai_kerjasama;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching invoice details:', error);
                            });
                    } else {
                        // Kosongkan jika tidak ada invoice yang dipilih
                        if (tambahNama) tambahNama.value = '';
                        if (tambahDeskripsi) tambahDeskripsi.value = '';
                        if (tambahHarga) tambahHarga.value = '';
                        if (tambahTanggalMulaiKerjasama) tambahTanggalMulaiKerjasama.value = '';
                        if (tambahTanggalSelesaiKerjasama) tambahTanggalSelesaiKerjasama.value = '';
                    }
                });
            }

            // Close modals
            closeModals.forEach(btn => {
                btn.addEventListener('click', function() {
                    [tambahModal, editModal, detailModal, deleteModal].forEach(modal => {
                        if (modal) modal.classList.add('hidden');
                    });
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === tambahModal) tambahModal.classList.add('hidden');
                if (event.target === editModal) editModal.classList.add('hidden');
                if (event.target === detailModal) detailModal.classList.add('hidden');
                if (event.target === deleteModal) deleteModal.classList.add('hidden');
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
                    if (toast) toast.classList.add('translate-y-20', 'opacity-0');
                });
            }
            
            // Auto-hide success/error toasts after 5 seconds
            const autoHideToasts = document.querySelectorAll('#successToast, #errorToast');
            autoHideToasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 5000);
            });
        });
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>
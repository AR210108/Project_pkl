<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tugas Saya - Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#f3f4f6",
                        "background-dark": "#111827",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1F2937",
                        "text-primary-light": "#111827",
                        "text-primary-dark": "#F9FAFB",
                        "text-secondary-light": "#6B7280",
                        "text-secondary-dark": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151",
                    },
                    fontFamily: { display: ["Poppins", "sans-serif"] },
                    borderRadius: { DEFAULT: "0.75rem" },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .task-card {
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }

        /* Animation for modal */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #taskDetailModal>div {
            animation: modalFadeIn 0.3s ease-out;
        }

        /* Smooth transitions */
        .task-card,
        button,
        input,
        textarea,
        select {
            transition: all 0.2s ease;
        }

        /* Scrollbar styling for comments */
        #commentsContainer::-webkit-scrollbar {
            width: 4px;
        }

        #commentsContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #commentsContainer::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #commentsContainer::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Dark mode scrollbar */
        .dark #commentsContainer::-webkit-scrollbar-track {
            background: #2d3748;
        }

        .dark #commentsContainer::-webkit-scrollbar-thumb {
            background: #4a5568;
        }

        .dark #commentsContainer::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        /* Detail button styling */
        .detail-btn {
            background-color: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .detail-btn:hover {
            background-color: #2563eb;
        }

        .detail-btn .material-symbols-outlined {
            font-size: 18px;
        }

        /* Accept button styling */
        .accept-btn {
            background-color: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: none;
        }

        .accept-btn:hover {
            background-color: #059669;
        }

        .accept-btn .material-symbols-outlined {
            font-size: 18px;
        }
    </style>

    <style>
        /* Upload Button Styles */
        .upload-btn {
            background-color: #3b82f6;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background-color 0.2s;
            font-weight: 500;
        }

        .upload-btn:hover {
            background-color: #2563eb;
        }

        .upload-btn .material-symbols-outlined {
            font-size: 18px;
        }

        /* Task Card Hover Effect */
        /* Task Card Hover Effect */
        .task-card {
            position: relative;
            cursor: pointer;
        }

        .task-card:hover {
            border-color: #3b82f6 !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(59, 130, 246, 0.01) 100%);
        }

        .task-card.selected {
            border-color: #3b82f6 !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2) !important;
        }
        /* Disable hover transform and visual changes for selected card to avoid conflicting motion */
        .task-card.selected,
        .task-card.selected:hover {
            transform: none !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%) !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2) !important;
        }
    </style>
    <style>
        /* Simple reveal animation for task cards */
        .fade-in {
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 480ms cubic-bezier(.2,.9,.2,1), transform 480ms cubic-bezier(.2,.9,.2,1);
            will-change: opacity, transform;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Small interactive scale on hover (keeps existing visual feel) */
        .task-card.fade-in:hover {
            transform: translateY(-4px) scale(1.01);
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body
    class="bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark min-h-screen">

    @php
        // Pastikan $tasks selalu terdefinisi
        $tasks = $tasks ?? collect([]);
        $error = $error ?? null;
        $userId = $userId ?? null;
    @endphp

    <!-- DEBUG INFO -->


    <!-- Header -->
    @include('karyawan.templet.header')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->


        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Tugas Saya</h1>
            <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mt-2">
                Total: <span id="totalTasks">{{ $tasks->count() }}</span> tugas
            </p>

            <!-- Tampilkan error jika ada -->
            @if($error)
                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <strong>Error:</strong> {{ $error }}
                </div>
            @endif
        </div>

        <main class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Task List & Filter -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Filter -->
                <div
                    class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark">
                    <h2 class="font-semibold mb-4">Filter Tugas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <select id="statusFilter"
                            class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="all">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">search</span>
                            <input id="searchInput" type="text" placeholder="Cari judul tugas..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Task List -->
                <div id="taskList" class="space-y-4">
                    @if($tasks->count() > 0)
                        @foreach($tasks as $task)
                                <div class="task-card fade-in bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm hover:shadow-md transition border border-border-light dark:border-border-dark group"
                                        data-task-id="{{ $task->id }}" data-task-status="{{ $task->status }}"
                                        data-task-title="{{ $task->judul }}"
                                        data-task-description="{{ Str::limit($task->deskripsi ?? 'Tidak ada deskripsi', 100) }}"
                                        data-task-full-description="{{ $task->deskripsi ?? 'Tidak ada deskripsi' }}"
                                        data-task-deadline="{{ $task->deadline }}"
                                        data-task-assigner="{{ $task->assigned_by_manager ? ($task->assigner->name ?? 'Manager') : ($task->creator->name ?? 'Admin') }}"
                                        data-task-priority="{{ $task->priority ?? 'medium' }}"
                                        data-task-catatan="{{ $task->catatan ?? '' }}"
                                        onclick="selectTaskCard({{ $task->id }})"
                                        ondblclick="openUploadModalQuick({{ $task->id }}, event)"
                                        style="position: relative;">

                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                        <!-- Upload on Hover -->

                                                <h3 class="font-bold text-lg">{{ $task->judul ?? 'Untitled Task' }}</h3>
                                                <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mt-1">
                                                    {{ Str::limit($task->deskripsi ?? 'Tidak ada deskripsi', 80) }}
                                                </p>
                                                <div class="flex items-center gap-2 mt-2">
                                                    @php
                                                        $priority = $task->priority ?? 'medium';
                                                        $priorityConfig = [
                                                            'urgent' => ['bg' => 'bg-red-100 dark:bg-red-900', 'text' => 'text-red-800 dark:text-red-200', 'label' => 'Sangat Mendesak', 'icon' => 'priority_high'],
                                                            'high' => ['bg' => 'bg-orange-100 dark:bg-orange-900', 'text' => 'text-orange-800 dark:text-orange-200', 'label' => 'Tinggi', 'icon' => 'arrow_upward'],
                                                            'medium' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900', 'text' => 'text-yellow-800 dark:text-yellow-200', 'label' => 'Sedang', 'icon' => 'remove'],
                                                            'low' => ['bg' => 'bg-green-100 dark:bg-green-900', 'text' => 'text-green-800 dark:text-green-200', 'label' => 'Rendah', 'icon' => 'arrow_downward']
                                                        ];
                                                        $config = $priorityConfig[$priority] ?? $priorityConfig['medium'];
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                                        <span class="material-symbols-outlined text-xs">{{ $config['icon'] }}</span>
                                                        {{ $config['label'] }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-right ml-6">
                                                <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">
                                                    @if($task->deadline)
                                                        {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y') }}
                                                        @if($task->deadline < now() && $task->status !== 'selesai')
                                                            <span class="block text-xs text-red-600 dark:text-red-400">⏰ Terlambat</span>
                                                        @endif
                                                    @else
                                                        Tidak ada deadline
                                                    @endif
                                                </p>
                                                <span class="mt-2 inline-block px-3 py-1 text-xs font-semibold rounded-full
                                                            {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                            ($task->status === 'proses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                ($task->status === 'menunggu' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
                                    ($task->status === 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                        'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'))) }}">
                                                    {{ strtoupper($task->status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex justify-end gap-2">
                                            @if($task->status === 'pending')
                                            <button class="accept-btn" onclick="acceptTaskQuick({{ $task->id }}, event)" title="Terima tugas ini">
                                                <span class="material-symbols-outlined">check_circle</span>
                                                Terima
                                            </button>
                                            @endif
                                            <button class="detail-btn" onclick="openTaskDetail({{ $task->id }})">
                                                <span class="material-symbols-outlined">visibility</span>
                                                Lihat Detail
                                            </button>
                                            
                                            <!-- Removed from here, now shows on hover -->
                                        </div>
                                    </div>
                        @endforeach
                    @else
                        <div
                            class="bg-surface-light dark:bg-surface-dark p-12 rounded-xl text-center border border-border-light dark:border-border-dark">
                            <span class="material-symbols-outlined text-6xl text-gray-400">assignment_late</span>
                            <p class="mt-6 text-xl font-medium text-text-secondary-light dark:text-text-secondary-dark">
                                Belum ada tugas
                            </p>
                            <p class="mt-2 text-sm">
                                Tugas baru akan muncul di sini ketika ditugaskan kepada Anda.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Task Detail Sidebar -->
            <div class="lg:col-span-1">
                <div id="taskDetail"
                    class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl sticky top-8 shadow-sm border border-border-light dark:border-border-dark">
                    <h2 class="text-xl font-bold mb-4">Detail Tugas</h2>
                    <h3 id="taskTitle" class="text-2xl font-bold text-primary">Pilih tugas dari daftar</h3>
                    <p id="taskDescription"
                        class="mt-3 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                        Klik tombol "Lihat Detail" pada tugas di sebelah kiri untuk melihat detail dan mengunggah hasil.
                    </p>
                    <div class="mt-8 bg-gray-200 dark:bg-gray-700 h-64 rounded-xl flex items-center justify-center">
                        <span id="sidebarStatusIcon" class="material-symbols-outlined text-6xl text-gray-400">task_alt</span>
                    </div>
                    

                    <!-- Quick Actions -->
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">Status:</span>
                            <span id="taskStatusBadge"
                                class="px-2 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">Prioritas:</span>
                            <span id="taskPriorityBadge"
                                class="px-2 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">Deadline:</span>
                            <span id="taskDeadlineText"
                                class="text-text-secondary-light dark:text-text-secondary-dark">-</span>
                        </div>
                    </div>

                    <!-- Catatan dari Manager -->
                    <div id="catatanSection" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800" style="display: none;">
                        <h3 class="font-semibold text-blue-900 dark:text-blue-200 text-sm mb-2">Catatan Manager</h3>
                        <p id="catatanText" class="text-sm text-blue-800 dark:text-blue-300 whitespace-pre-line">-</p>
                    </div>

                    <!-- Lampiran Manager -->
                    <div id="attachmentSection" class="mt-6" style="display: none;">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 text-sm mb-3">Lampiran Manager</h3>
                        <div id="attachmentList" class="space-y-3">
                            <!-- Photos preview or file links will be populated here -->
                        </div>
                    </div>

                    <!-- Upload Button -->
                    <button id="sidebarUploadButton" onclick="openUploadFromSidebar(event)" disabled
                        class="w-full mt-6 bg-green-500 text-white font-semibold py-3 rounded-lg hover:bg-green-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined inline mr-2">upload</span>
                        Upload Hasil
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Task Detail Modal - DESAIN BARU -->
    <div id="taskDetailModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4">
        <div
            class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-border-light dark:border-border-dark">
            <div class="p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-text-primary-light dark:text-text-primary-dark">
                        Detail Tugas</h2>
                    <button id="closeDetailModal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <span class="material-symbols-outlined text-3xl">close</span>
                    </button>
                </div>

                <div
                    class="bg-gray-50 dark:bg-gray-800 p-6 md:p-8 rounded-xl border border-border-light dark:border-border-dark">
                    <!-- Task Title -->
                    <h3 id="modalTaskTitle"
                        class="text-xl sm:text-2xl md:text-3xl font-bold text-text-primary-light dark:text-text-primary-dark mb-4 md:mb-6">
                    </h3>

                    <div class="space-y-4 md:space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">
                                Deskripsi Tugas</h4>
                            <p id="modalTaskDescription"
                                class="text-gray-600 dark:text-gray-400 text-sm md:text-base leading-relaxed whitespace-pre-line">
                            </p>
                        </div>

                        <!-- Deadline -->
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">
                                Deadline</h4>
                            <p id="modalTaskDeadline" class="text-gray-600 dark:text-gray-400 text-sm md:text-base"></p>
                        </div>

                        <!-- Ditugaskan Oleh -->
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">
                                Ditugaskan oleh</h4>
                            <p id="modalTaskAssigner" class="text-gray-600 dark:text-gray-400 text-sm md:text-base"></p>
                        </div>

                        <!-- Status dan Prioritas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">
                                    Status</h4>
                                <p id="modalTaskStatus" class="text-gray-600 dark:text-gray-400 text-sm md:text-base">
                                </p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">
                                    Prioritas</h4>
                                <p id="modalTaskPriority" class="text-gray-600 dark:text-gray-400 text-sm md:text-base">
                                </p>
                            </div>
                        </div>

                        <!-- Catatan Manager -->
                        <div id="modalCatatanSection" style="display: none;">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">
                                Catatan Manager</h4>
                            <p id="modalCatatanText" class="text-gray-600 dark:text-gray-400 text-sm md:text-base whitespace-pre-line">
                            </p>
                        </div>
                    </div>

                    <!-- File Terlampir Section -->
                    <div class="mt-6 md:mt-8" id="fileAttachmentSection" style="display: none;">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 text-base md:text-lg">File
                            Terlampir</h4>
                        <div id="fileAttachmentList" class="space-y-3">
                            <!-- Files will be populated here -->
                        </div>
                    </div>

                    <!-- Acceptance Status Section -->
                    <div class="mt-6 md:mt-8" id="acceptanceStatusSection" style="display: none;">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 text-base md:text-lg">Status Penerimaan Tugas</h4>
                        
                        <!-- Acceptance Progress -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Progress Penerimaan</span>
                                <span id="acceptancePercentage" class="text-sm font-semibold text-primary">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div id="acceptanceProgressBar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Acceptance List -->
                        <div id="acceptanceList" class="space-y-3">
                            <!-- Assignees acceptance status will be populated here -->
                        </div>
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-700" />

                    <!-- Komentar & Diskusi Section -->


                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                        <button id="modalCancelButton"
                            class="px-6 py-3 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition text-sm md:text-base w-full sm:w-auto">
                            Tutup
                        </button>
                        <button id="modalAcceptButton"
                            class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm md:text-base w-full sm:w-auto hidden">
                            <span class="material-symbols-outlined inline mr-2 text-lg">check_circle</span>
                            Terima Tugas
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4">
        <div
            class="bg-surface-light dark:bg-surface-dark rounded-2xl max-w-md w-full shadow-2xl border border-border-light dark:border-border-dark">
            <div class="p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark">Upload Hasil
                        Tugas</h2>
                    <button id="closeUploadModal" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined text-3xl">close</span>
                    </button>
                </div>

                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="uploadTaskId" name="task_id">

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-2">File Hasil Tugas</label>
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 text-center hover:border-primary transition">
                                <input type="file" id="taskFile" name="file" class="hidden"
                                    accept=".pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png,.gif">
                                
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    Klik atau drag file ke sini
                                </p>
                                <p class="text-xs text-gray-500">
                                    PDF, DOC, ZIP, RAR, JPG, PNG (max. 250MB)
                                </p>
                                <button type="button" id="fileUploadTrigger"
                                    class="mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600">
                                    Pilih File
                                </button>
                            </div>
                            <p id="fileName" class="text-sm text-green-600 mt-2 hidden"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Catatan (Opsional)</label>
                            <textarea id="fileNotes" name="notes" rows="3"
                                class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg p-4 focus:ring-2 focus:ring-primary focus:outline-none"
                                placeholder="Tambahkan catatan mengenai file yang diupload..."></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" id="cancelUpload"
                            class="px-6 py-3 bg-gray-300 dark:bg-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                        <button type="submit"
                            class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-600">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-500 text-white p-4 rounded-xl shadow-lg max-w-md">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <div>
                    <p class="font-semibold" id="notificationTitle">Success</p>
                    <p class="text-sm opacity-90" id="notificationMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // FIX: Pastikan tasks selalu berupa array di JavaScript
        const tasks = @json($tasks ?? []);
        const currentUser = @json(auth()->user() ?? null);
        let currentTaskId = null;

        const elements = {
            taskCards: document.querySelectorAll('.task-card'),
            taskTitle: document.getElementById('taskTitle'),
            taskDescription: document.getElementById('taskDescription'),
            sidebarStatusIcon: document.getElementById('sidebarStatusIcon'),
            
            taskStatusBadge: document.getElementById('taskStatusBadge'),
            taskPriorityBadge: document.getElementById('taskPriorityBadge'),
            taskDeadlineText: document.getElementById('taskDeadlineText'),
            catatanSection: document.getElementById('catatanSection'),
            catatanText: document.getElementById('catatanText'),
            attachmentSection: document.getElementById('attachmentSection'),
            attachmentList: document.getElementById('attachmentList'),
            taskDetailModal: document.getElementById('taskDetailModal'),
            modalTaskTitle: document.getElementById('modalTaskTitle'),
            modalTaskDescription: document.getElementById('modalTaskDescription'),
            modalTaskDeadline: document.getElementById('modalTaskDeadline'),
            modalTaskAssigner: document.getElementById('modalTaskAssigner'),
            modalTaskStatus: document.getElementById('modalTaskStatus'),
            modalTaskPriority: document.getElementById('modalTaskPriority'),
            modalCatatanSection: document.getElementById('modalCatatanSection'),
            modalCatatanText: document.getElementById('modalCatatanText'),
            totalTasks: document.getElementById('totalTasks'),
            statusFilter: document.getElementById('statusFilter'),
            searchInput: document.getElementById('searchInput'),
            uploadModal: document.getElementById('uploadModal'),
            uploadForm: document.getElementById('uploadForm'),
            taskFile: document.getElementById('taskFile'),
            fileName: document.getElementById('fileName'),
            fileUploadTrigger: document.getElementById('fileUploadTrigger'),
            uploadTaskId: document.getElementById('uploadTaskId'),
            notification: document.getElementById('notification'),
            notificationTitle: document.getElementById('notificationTitle'),
            notificationMessage: document.getElementById('notificationMessage'),
            commentInput: document.getElementById('commentInput'),
            submitComment: document.getElementById('submitComment'),
            cancelComment: document.getElementById('cancelComment'),
            closeDetailModal: document.getElementById('closeDetailModal'),
            modalCancelButton: document.getElementById('modalCancelButton'),
            modalAcceptButton: document.getElementById('modalAcceptButton'),
            sidebarUploadButton: document.getElementById('sidebarUploadButton'),
            closeUploadModal: document.getElementById('closeUploadModal'),
            cancelUpload: document.getElementById('cancelUpload'),
        };

        // Fungsi untuk membuka detail tugas
        function openTaskDetail(taskId) {
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            if (!card) return;

            // Reset active state and mark selected card so hover is disabled
            document.querySelectorAll('.task-card').forEach(c => c.classList.remove('selected'));
            elements.taskCards.forEach(c => c.classList.remove('ring-4', 'ring-primary/50'));
            card.classList.add('ring-4', 'ring-primary/50');
            card.classList.add('selected');

            currentTaskId = parseInt(taskId);
            elements.taskTitle.textContent = card.dataset.taskTitle;
            elements.taskDescription.textContent = card.dataset.taskFullDescription;
            elements.uploadTaskId.value = currentTaskId;

            // Update sidebar details
            updateSidebarDetails(card);

            // Buka modal detail
            openDetailModal();
        }

        // Fungsi untuk update sidebar
        function updateSidebarDetails(card) {
            // Status badge
            const status = card.dataset.taskStatus;
            let statusClass = 'bg-gray-200 dark:bg-gray-700';
            let statusText = 'Tidak diketahui';

            if (status === 'pending') {
                statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                statusText = 'PENDING';
            } else if (status === 'proses') {
                statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                statusText = 'PROSES';
            } else if (status === 'menunggu') {
                statusClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200';
                statusText = 'MENUNGGU';
            } else if (status === 'selesai') {
                statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                statusText = 'SELESAI';
            }

            elements.taskStatusBadge.className = `px-2 py-1 rounded-full text-xs font-medium ${statusClass}`;
            elements.taskStatusBadge.textContent = statusText;
            
            // Update sidebar icon based on status
            if (elements.sidebarStatusIcon) {
                let icon = 'task_alt';
                let iconColorClass = 'text-gray-400';
                
                if (status === 'pending') {
                    icon = 'schedule';
                    iconColorClass = 'text-yellow-600 dark:text-yellow-300';
                } else if (status === 'proses') {
                    icon = 'autorenew';
                    iconColorClass = 'text-blue-600 dark:text-blue-300';
                } else if (status === 'menunggu') {
                    icon = 'hourglass_top';
                    iconColorClass = 'text-purple-600 dark:text-purple-300';
                } else if (status === 'selesai') {
                    icon = 'task_alt';
                    iconColorClass = 'text-green-600 dark:text-green-300';
                } else if (status === 'dibatalkan') {
                    icon = 'cancel';
                    iconColorClass = 'text-red-600 dark:text-red-300';
                }
                
                elements.sidebarStatusIcon.textContent = icon;
                elements.sidebarStatusIcon.className = `material-symbols-outlined text-6xl ${iconColorClass}`;
            }

            // Disable upload button jika status sudah 'menunggu' atau 'selesai'
            if (elements.sidebarUploadButton) {
                if (status === 'menunggu' || status === 'selesai') {
                    elements.sidebarUploadButton.disabled = true;
                    elements.sidebarUploadButton.textContent = status === 'menunggu' ? 
                        '⏳ Menunggu Review' : '✓ Sudah Selesai';
                } else if (status === 'pending' || status === 'proses') {
                    elements.sidebarUploadButton.disabled = false;
                    elements.sidebarUploadButton.innerHTML = '<span class="material-symbols-outlined inline mr-2">upload</span>Upload Hasil';
                }
            }

            // Priority
            const priority = card.dataset.taskPriority || 'medium';
            let priorityClass = 'bg-gray-200 dark:bg-gray-700';
            let priorityText = 'SEDANG';

            if (priority === 'urgent') {
                priorityClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 font-semibold';
                priorityText = 'SANGAT MENDESAK';
            } else if (priority === 'high') {
                priorityClass = 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
                priorityText = 'TINGGI';
            } else if (priority === 'medium') {
                priorityClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                priorityText = 'SEDANG';
            } else if (priority === 'low') {
                priorityClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                priorityText = 'RENDAH';
            }

            elements.taskPriorityBadge.className = `px-2 py-1 rounded-full text-xs font-medium ${priorityClass}`;
            elements.taskPriorityBadge.textContent = priorityText;

            // Deadline
            if (card.dataset.taskDeadline && card.dataset.taskDeadline !== 'null') {
                const deadline = new Date(card.dataset.taskDeadline);
                elements.taskDeadlineText.textContent = deadline.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            } else {
                elements.taskDeadlineText.textContent = 'Tidak ada';
            }

            // Catatan dari Manager
            const catatan = card.dataset.taskCatatan || '';
            if (elements.catatanSection && elements.catatanText) {
                if (catatan && catatan.trim() !== '') {
                    elements.catatanText.textContent = catatan;
                    elements.catatanSection.style.display = 'block';
                } else {
                    elements.catatanSection.style.display = 'none';
                }
            }
        }

        // Fungsi untuk download file
        function downloadFile(filePath) {
            const url = `/storage/${filePath}`;
            window.open(url, '_blank');
        }

        // Fungsi untuk membuka modal detail dengan desain baru
        function openDetailModal() {
            if (!currentTaskId) return;

            const card = document.querySelector(`[data-task-id="${currentTaskId}"]`);
            if (!card) return;

            // Cari task dari array tasks
            const taskElement = Array.from(tasks).find(t => t.id == currentTaskId);

            // Set title
            elements.modalTaskTitle.textContent = card.dataset.taskTitle;

            // Set description
            const description = card.dataset.taskFullDescription || 'Tidak ada deskripsi';
            elements.modalTaskDescription.textContent = description;

            // Format tanggal deadline
            if (card.dataset.taskDeadline && card.dataset.taskDeadline !== 'null') {
                const deadline = new Date(card.dataset.taskDeadline);
                elements.modalTaskDeadline.textContent = deadline.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) + ' WIB';
            } else {
                elements.modalTaskDeadline.textContent = 'Tidak ada deadline';
            }

            // Assigner
            elements.modalTaskAssigner.textContent = card.dataset.taskAssigner || 'Admin';

            if (taskElement) {
                // Status dengan styling
                const status = taskElement.status || 'pending';
                let statusText = 'Tidak diketahui';
                let statusClass = 'text-gray-600 dark:text-gray-400';

                if (status === 'pending') {
                    statusText = 'PENDING';
                    statusClass = 'text-yellow-600 dark:text-yellow-400';
                } else if (status === 'proses') {
                    statusText = 'PROSES';
                    statusClass = 'text-blue-600 dark:text-blue-400';
                } else if (status === 'menunggu') {
                    statusText = 'MENUNGGU';
                    statusClass = 'text-purple-600 dark:text-purple-400';
                } else if (status === 'selesai') {
                    statusText = 'SELESAI';
                    statusClass = 'text-green-600 dark:text-green-400';
                } else if (status === 'dibatalkan') {
                    statusText = 'DIBATALKAN';
                    statusClass = 'text-red-600 dark:text-red-400';
                }

                elements.modalTaskStatus.textContent = statusText;
                elements.modalTaskStatus.className = `${statusClass} text-sm md:text-base`;

                // Priority dengan styling
                const priority = taskElement.priority || 'medium';
                let priorityText = 'Tidak diketahui';
                let priorityClass = 'text-gray-600 dark:text-gray-400';

                if (priority === 'urgent') {
                    priorityText = 'SANGAT MENDESAK';
                    priorityClass = 'text-red-700 dark:text-red-300 font-semibold';
                } else if (priority === 'high') {
                    priorityText = 'TINGGI';
                    priorityClass = 'text-red-600 dark:text-red-400';
                } else if (priority === 'medium') {
                    priorityText = 'SEDANG';
                    priorityClass = 'text-yellow-600 dark:text-yellow-400';
                } else if (priority === 'low') {
                    priorityText = 'RENDAH';
                    priorityClass = 'text-green-600 dark:text-green-400';
                }

                elements.modalTaskPriority.textContent = priorityText;
                elements.modalTaskPriority.className = `${priorityClass} text-sm md:text-base`;
                
                // Tampilkan/sembunyikan tombol Terima Tugas berdasarkan status
                if (status === 'pending') {
                    elements.modalAcceptButton.classList.remove('hidden');
                } else {
                    elements.modalAcceptButton.classList.add('hidden');
                }

                // Catatan Manager
                const catatan = card.dataset.taskCatatan || '';
                if (elements.modalCatatanSection && elements.modalCatatanText) {
                    if (catatan && catatan.trim() !== '') {
                        elements.modalCatatanText.textContent = catatan;
                        elements.modalCatatanSection.style.display = 'block';
                    } else {
                        elements.modalCatatanSection.style.display = 'none';
                    }
                }
            }

            // Hide file section for now (since we don't have file data)
            document.getElementById('fileAttachmentSection').style.display = 'none';

            // Load files dari API
            loadTaskFiles(currentTaskId);

            // Load acceptance status
            loadAcceptanceStatus(currentTaskId);

            // Load comments (you'll need to implement this API)
            // loadComments(currentTaskId);

            elements.taskDetailModal.classList.remove('hidden');
        }

        // Fungsi untuk load files dari API
        async function loadTaskFiles(taskId) {
            try {
                const response = await fetch(`/karyawan/tugas/${taskId}/files`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    console.error('Failed to load files:', response.status);
                    return;
                }

                const data = await response.json();

                if (data.success && data.files && data.files.length > 0) {
                    const fileSection = document.getElementById('fileAttachmentSection');
                    const fileList = document.getElementById('fileAttachmentList');

                    fileList.innerHTML = '';

                    data.files.forEach(file => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700 rounded-lg';

                        // Determine file icon based on MIME type
                        let icon = 'description'; // default
                        const mimeType = file.mime_type || '';

                        if (mimeType.startsWith('image/')) {
                            icon = 'image';
                        } else if (mimeType.includes('pdf')) {
                            icon = 'picture_as_pdf';
                        } else if (mimeType.includes('word') || mimeType.includes('document')) {
                            icon = 'description';
                        } else if (mimeType.includes('sheet') || mimeType.includes('excel')) {
                            icon = 'table_chart';
                        } else if (mimeType.includes('zip') || mimeType.includes('rar')) {
                            icon = 'folder_zip';
                        }

                        fileItem.innerHTML = `
                            <div class="flex items-center gap-3 flex-1">
                                <span class="material-symbols-outlined text-2xl text-primary">${icon}</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 break-words">${file.original_name || file.filename}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">${file.size ? formatBytes(file.size) : 'Unknown size'}</p>
                                </div>
                            </div>
                            <a href="${file.path}" target="_blank" rel="noopener noreferrer" class="ml-2 p-2 text-primary hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition">
                                <span class="material-symbols-outlined">download</span>
                            </a>
                        `;

                        fileList.appendChild(fileItem);
                    });

                    fileSection.style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading files:', error);
            }
        }

        // Fungsi untuk load acceptance status dari API
        async function loadAcceptanceStatus(taskId) {
            try {
                const response = await fetch(`/karyawan/tugas/${taskId}/acceptance-status`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    console.error('Failed to load acceptance status:', response.status);
                    return;
                }

                const data = await response.json();

                if (data.success && data.acceptance_status) {
                    const status = data.acceptance_status;
                    const details = data.acceptance_details || [];

                    // Hanya tampil jika ada multiple assignees
                    // Dengan design baru: tiap karyawan dapat task sendiri, jadi section ini tidak akan tampil
                    // Tapi keep untuk backward compatibility dengan old multi-assign tasks
                    if (status.total > 1) {
                        const acceptanceSection = document.getElementById('acceptanceStatusSection');
                        const acceptanceList = document.getElementById('acceptanceList');

                        // Update progress bar
                        document.getElementById('acceptancePercentage').textContent = status.percentage + '%';
                        document.getElementById('acceptanceProgressBar').style.width = status.percentage + '%';

                        // Clear and populate acceptance list
                        acceptanceList.innerHTML = '';

                        details.forEach(detail => {
                            const item = document.createElement('div');
                            item.className = 'flex items-center gap-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg';

                            let statusIcon = 'schedule';
                            let statusText = 'PENDING';
                            let statusClass = 'text-yellow-600';
                            let acceptedTime = '';

                            if (detail.status === 'accepted') {
                                statusIcon = 'check_circle';
                                statusText = 'DITERIMA';
                                statusClass = 'text-green-600';
                                if (detail.accepted_at) {
                                    const acceptedDate = new Date(detail.accepted_at);
                                    acceptedTime = acceptedDate.toLocaleDateString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                }
                            } else if (detail.status === 'rejected') {
                                statusIcon = 'cancel';
                                statusText = 'DITOLAK';
                                statusClass = 'text-red-600';
                            }

                            item.innerHTML = `
                                <span class="material-symbols-outlined text-lg ${statusClass}">${statusIcon}</span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-gray-200">${detail.user_name}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">${detail.user_email}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-semibold ${statusClass}">${statusText}</p>
                                    ${acceptedTime ? `<p class="text-xs text-gray-500">${acceptedTime}</p>` : ''}
                                </div>
                            `;

                            acceptanceList.appendChild(item);
                        });

                        acceptanceSection.style.display = 'block';
                    } else {
                        // Update 2026: Tiap karyawan dapat task terpisah, jadi acceptance section selalu disembunyikan
                        document.getElementById('acceptanceStatusSection').style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error loading acceptance status:', error);
                // Silently fail - ini optional feature
            }
        }

        // Utility function untuk format bytes
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Fungsi untuk show notification
        function showNotification(title, message, type = 'success') {
            elements.notificationTitle.textContent = title;
            elements.notificationMessage.textContent = message;
            elements.notification.className = 'fixed top-4 right-4 z-50';

            // Set background color based on type
            const bgColor = type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                    type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';

            elements.notification.querySelector('div').className = `${bgColor} text-white p-4 rounded-xl shadow-lg max-w-md flex items-center gap-3`;

            elements.notification.classList.remove('hidden');

            // Auto hide after 5 seconds
            setTimeout(() => {
                elements.notification.classList.add('hidden');
            }, 5000);
        }

        // Fungsi untuk accept task dari button di task card
        async function acceptTaskQuick(taskId, event) {
            event.preventDefault();
            event.stopPropagation();

            try {
                const response = await fetch(`/karyawan/tugas/${taskId}/accept`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: 'proses' })
                });

                const data = await response.json();

                if (data.success) {
                    // Update task status di UI
                    const card = document.querySelector(`[data-task-id="${taskId}"]`);
                    if (card) {
                        card.dataset.taskStatus = 'proses';
                        // Reload untuk update data
                        location.reload();
                    }

                    // Tampilkan notifikasi
                    showNotification('Tugas Diterima', 'Status tugas telah berubah menjadi Dalam Proses', 'success');
                } else {
                    showNotification('Error', data.message || 'Gagal menerima tugas', 'error');
                }
            } catch (error) {
                console.error('Error accepting task:', error);
                showNotification('Error', 'Gagal menerima tugas: ' + error.message, 'error');
            }
        }

        // Fungsi untuk select task card
        function selectTaskCard(taskId) {
            // Remove selected class dari semua card
            document.querySelectorAll('.task-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class ke current card and update sidebar
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            if (card) {
                card.classList.add('selected');

                // Set current task and update sidebar content
                currentTaskId = parseInt(taskId);
                elements.taskTitle.textContent = card.dataset.taskTitle || 'Untitled Task';
                elements.taskDescription.textContent = card.dataset.taskFullDescription || 'Tidak ada deskripsi';
                elements.uploadTaskId.value = currentTaskId;

                // Enable sidebar upload button
                if (elements.sidebarUploadButton) {
                    elements.sidebarUploadButton.disabled = false;
                }

                // Update sidebar badges and deadline
                updateSidebarDetails(card);
            }
        }

        // Fungsi untuk buka upload modal langsung dari card
        function openUploadModalQuick(taskId, event) {
            event.preventDefault();
            event.stopPropagation();
            // Select the card to update sidebar and state
            selectTaskCard(taskId);

            currentTaskId = parseInt(taskId);
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            if (!card) return;

            // Set upload task ID
            elements.uploadTaskId.value = currentTaskId;

            // Get task name dari card untuk display
            const taskTitle = card.dataset.taskTitle || 'Tugas';
            document.querySelector('#uploadModal h2') && (document.querySelector('#uploadModal h2').textContent = `Upload Hasil: ${taskTitle}`);

            // Reset upload form UI
            if (elements.uploadForm) elements.uploadForm.reset();
            if (elements.fileName) elements.fileName.classList.add('hidden');

            // Buka modal upload
            elements.uploadModal.classList.remove('hidden');
        }

        // Fungsi untuk buka upload modal dari sidebar
        function openUploadFromSidebar(event) {
            event.preventDefault();
            event.stopPropagation();

            if (!currentTaskId) {
                showNotification('Peringatan', 'Pilih tugas terlebih dahulu', 'warning');
                return;
            }

            const card = document.querySelector(`[data-task-id="${currentTaskId}"]`);
            if (!card) return;

            // Set upload task ID
            elements.uploadTaskId.value = currentTaskId;

            // Get task name untuk display
            const taskTitle = card.dataset.taskTitle || 'Tugas';
            document.querySelector('#uploadModal h2') && (document.querySelector('#uploadModal h2').textContent = `Upload Hasil: ${taskTitle}`);

            // Reset upload form UI
            if (elements.uploadForm) elements.uploadForm.reset();
            if (elements.fileName) elements.fileName.classList.add('hidden');

            // Buka modal upload
            elements.uploadModal.classList.remove('hidden');
        }

        // Event Listeners untuk modal detail
        if (elements.closeDetailModal) {
            elements.closeDetailModal.addEventListener('click', () => {
                elements.taskDetailModal.classList.add('hidden');
                // Remove selected state when closing modal so hover returns
                document.querySelectorAll('.task-card').forEach(c => c.classList.remove('selected', 'ring-4', 'ring-primary/50'));
            });
        }

        if (elements.modalCancelButton) {
            elements.modalCancelButton.addEventListener('click', () => {
                elements.taskDetailModal.classList.add('hidden');
                // Remove selected state when closing modal so hover returns
                document.querySelectorAll('.task-card').forEach(c => c.classList.remove('selected', 'ring-4', 'ring-primary/50'));
            });
        }

        if (elements.modalAcceptButton) {
            elements.modalAcceptButton.addEventListener('click', async () => {
                if (!currentTaskId) return;
                
                try {
                    const response = await fetch(`/karyawan/tugas/${currentTaskId}/accept`, {
                        method: 'PUT',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: 'proses' })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Tutup modal
                        elements.taskDetailModal.classList.add('hidden');
                        
                        // Update task status di UI
                        const card = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                        if (card) {
                            card.dataset.taskStatus = 'proses';
                            // Update visual
                            location.reload(); // Reload untuk update data
                        }

                        // Tampilkan notifikasi
                        showNotification('Tugas Diterima', 'Status tugas telah berubah menjadi Dalam Proses');
                    } else {
                        alert('Gagal menerima tugas: ' + (data.message || 'Error tidak diketahui'));
                    }
                } catch (error) {
                    console.error('Error accepting task:', error);
                    alert('Gagal menerima tugas: ' + error.message);
                }
            });
        }

        if (elements.modalUploadButton) {
            elements.modalUploadButton.addEventListener('click', () => {
                elements.taskDetailModal.classList.add('hidden');
                setTimeout(() => {
                    if (elements.uploadModal) {
                        elements.uploadModal.classList.remove('hidden');
                    }
                }, 300);
            });
        }

        // Upload button event
        if (elements.uploadButton) {
            elements.uploadButton.addEventListener('click', () => {
                if (currentTaskId && elements.uploadModal) {
                    elements.uploadModal.classList.remove('hidden');
                } else {
                    showNotification('Peringatan', 'Silakan pilih tugas terlebih dahulu', 'warning');
                }
            });
        }

        // File upload trigger
        if (elements.fileUploadTrigger) {
            elements.fileUploadTrigger.addEventListener('click', () => {
                if (elements.taskFile) {
                    elements.taskFile.click();
                }
            });
        }

        if (elements.taskFile) {
            elements.taskFile.addEventListener('change', (e) => {
                if (e.target.files.length > 0 && elements.fileName) {
                    elements.fileName.textContent = `File terpilih: ${e.target.files[0].name}`;
                    elements.fileName.classList.remove('hidden');
                }
            });
        }

        // Close upload modal
        if (elements.closeUploadModal) {
            elements.closeUploadModal.addEventListener('click', () => {
                if (elements.uploadModal) {
                    elements.uploadModal.classList.add('hidden');
                    if (elements.uploadForm) elements.uploadForm.reset();
                    if (elements.fileName) elements.fileName.classList.add('hidden');
                }
            });
        }

        if (elements.cancelUpload) {
            elements.cancelUpload.addEventListener('click', () => {
                if (elements.uploadModal) {
                    elements.uploadModal.classList.add('hidden');
                    if (elements.uploadForm) elements.uploadForm.reset();
                    if (elements.fileName) elements.fileName.classList.add('hidden');
                }
            });
        }

        // Form submit untuk upload
        if (elements.uploadForm) {
            elements.uploadForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!currentTaskId) {
                    showNotification('Error', 'Tidak ada tugas yang dipilih', 'error');
                    return;
                }

                // Client-side file size validation to avoid server 413 responses.
                const fileInput = document.getElementById('taskFile');
                const file = fileInput && fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
                const MAX_BYTES = 250 * 1024 * 1024; // 250 MB (must match server validation)

                if (!file) {
                    showNotification('Error', 'Pilih file terlebih dahulu', 'error');
                    return;
                }

                if (file.size > MAX_BYTES) {
                    showNotification('Error', 'Ukuran file terlalu besar. Maksimum 250MB.', 'error');
                    return;
                }

                // Create FormData manually to ensure file is properly included
                const formData = new FormData();
                formData.append('file', file);
                
                const notes = document.getElementById('fileNotes')?.value || '';
                if (notes) {
                    formData.append('notes', notes);
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                formData.append('_token', csrfToken);

                // Debug: Log FormData contents
                console.log('FormData contents before upload:');
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(`  ${key}: File(${value.name}, ${value.size} bytes, ${value.type})`);
                    } else {
                        console.log(`  ${key}:`, value);
                    }
                }

                showNotification('Memproses', 'Mengupload file...', 'warning');                try {
                    // Gunakan API route untuk upload
                    const response = await fetch(`/api/tasks/${currentTaskId}/upload`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    // Be defensive: server may return non-JSON (HTML error pages), handle gracefully.
                    let result;
                    const contentType = response.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        result = await response.json();
                    } else {
                        const text = await response.text();
                        // Show trimmed server response when not JSON
                        showNotification('Error', 'Server error: ' + (text.substring(0, 250) || 'Unknown'), 'error');
                        console.error('Non-JSON response:', text);
                        return;
                    }

                    console.log('Upload response status:', response.status);
                    console.log('Upload response data:', result);

                    // Handle non-200 responses
                    if (!response.ok) {
                        console.error('Upload failed with status', response.status, ':', result);
                        
                        if (result.errors && typeof result.errors === 'object') {
                            // If validation errors exist, show them
                            const errorMessages = Object.entries(result.errors)
                                .map(([field, msgs]) => {
                                    if (Array.isArray(msgs)) {
                                        return `${field}: ${msgs.join(', ')}`;
                                    } else {
                                        return `${field}: ${msgs}`;
                                    }
                                })
                                .join('\n');
                            console.error('Validation errors:', errorMessages);
                            showNotification('Validasi Gagal', errorMessages, 'error');
                        } else if (result.message) {
                            console.error('Error message:', result.message);
                            showNotification('Error', result.message, 'error');
                        } else {
                            console.error('Unknown error. Full response:', result);
                            showNotification('Error', `Server error ${response.status}: ${JSON.stringify(result)}`, 'error');
                        }
                        return;
                    }

                    if (result.success) {
                        showNotification('Sukses', 'File berhasil diupload');
                        if (elements.uploadModal) {
                            elements.uploadModal.classList.add('hidden');
                            elements.uploadForm.reset();
                            if (elements.fileName) elements.fileName.classList.add('hidden');
                        }

                        // Reload halaman setelah 2 detik
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showNotification('Error', result.message || 'Gagal mengupload file', 'error');
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    // If fetch failed due to network / server rejecting body size, show clear message
                    if (error.message && error.message.toLowerCase().includes('request entity too large')) {
                        showNotification('Error', 'File terlalu besar untuk dikirim ke server (periksa konfigurasi server).', 'error');
                    } else {
                        showNotification('Error', 'Terjadi kesalahan saat mengupload: ' + (error.message || 'Unknown'), 'error');
                    }
                }
            });
        }

        // Filter fungsi
        function applyFilters() {
            const status = elements.statusFilter ? elements.statusFilter.value : 'all';
            const query = elements.searchInput ? elements.searchInput.value.toLowerCase() : '';
            let visible = 0;

            elements.taskCards.forEach((card, idx) => {
                const matchesStatus = status === 'all' || card.dataset.taskStatus === status;
                const matchesSearch = card.dataset.taskTitle.toLowerCase().includes(query);
                const shouldShow = matchesStatus && matchesSearch;
                card.style.display = shouldShow ? 'block' : 'none';

                if (shouldShow) {
                    // staggered reveal for visible cards
                    setTimeout(() => card.classList.add('show'), idx * 70);
                    visible++;
                } else {
                    card.classList.remove('show');
                }
            });

            if (elements.totalTasks) {
                elements.totalTasks.textContent = visible;
            }
        }

        // Filter event listeners
        if (elements.statusFilter) {
            elements.statusFilter.addEventListener('change', applyFilters);
        }

        if (elements.searchInput) {
            elements.searchInput.addEventListener('input', applyFilters);
        }

        // Initialize
        console.log('Task management system initialized');
        // Initial staggered reveal for task cards
        (function initialReveal() {
            const cards = document.querySelectorAll('.task-card.fade-in');
            cards.forEach((c, i) => {
                if (c.style.display === 'none') return;
                setTimeout(() => c.classList.add('show'), i * 60);
            });
        })();
    </script>
</body>

</html>
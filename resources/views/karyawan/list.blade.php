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
                        primary: "#7C3AED",
                        "background-light": "#FFFFFF",
                        "background-dark": "#121212",
                        "surface-light": "#F3F4F6",
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
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
        .task-card { transition: all 0.3s ease; }
        .task-card:hover { transform: translateY(-2px); }
        
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

        #taskDetailModal > div {
            animation: modalFadeIn 0.3s ease-out;
        }

        /* Smooth transitions */
        .task-card, button, input, textarea, select {
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
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark min-h-screen">

    @php
        // Pastikan $tasks selalu terdefinisi
        $tasks = $tasks ?? collect([]);
        $error = $error ?? null;
    @endphp

    <!-- Header -->
    @include('karyawan.templet.header')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm">
                    <h2 class="font-semibold mb-4">Filter Tugas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <select id="statusFilter" class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="all">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">search</span>
                            <input id="searchInput" type="text" placeholder="Cari judul tugas..." 
                                class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Task List -->
                <div id="taskList" class="space-y-4">
                    @if($tasks->count() > 0)
                        @foreach($tasks as $task)
                            <div class="task-card bg-surface-light dark:bg-surface-dark p-6 rounded-xl shadow-sm hover:shadow-md transition cursor-pointer flex justify-between items-center"
                                data-task-id="{{ $task->id }}"
                                data-task-status="{{ $task->status }}"
                                data-task-title="{{ $task->judul }}"
                                data-task-description="{{ Str::limit($task->deskripsi ?? 'Tidak ada deskripsi', 100) }}"
                                data-task-full-description="{{ $task->deskripsi ?? 'Tidak ada deskripsi' }}"
                                data-task-deadline="{{ $task->deadline }}"
                                data-task-assigner="{{ $task->assigned_by_manager ? ($task->assigner->name ?? 'Manager') : ($task->creator->name ?? 'Admin') }}"
                                data-task-priority="{{ $task->priority ?? 'medium' }}">

                                <div class="flex-1">
                                    <h3 class="font-bold text-lg">{{ $task->judul ?? 'Untitled Task' }}</h3>
                                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mt-1">
                                        {{ Str::limit($task->deskripsi ?? 'Tidak ada deskripsi', 80) }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2">
                                        @php
                                            $priority = $task->priority ?? 'medium';
                                        @endphp
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full 
                                            {{ $priority === 'high' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               ($priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                            <span class="material-symbols-outlined text-xs">
                                                {{ $priority === 'high' ? 'priority_high' : 
                                                   ($priority === 'medium' ? 'low_priority' : 'flag') }}
                                            </span>
                                            {{ $priority === 'high' ? 'Tinggi' : ($priority === 'medium' ? 'Sedang' : 'Rendah') }}
                                        </span>
                                        @if($task->submission_file)
                                            <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                                Telah Diupload
                                            </span>
                                        @endif
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
                                           ($task->status === 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                           'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200')) }}">
                                        {{ strtoupper($task->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-surface-light dark:bg-surface-dark p-12 rounded-xl text-center">
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
                <div id="taskDetail" class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl sticky top-8 shadow-sm">
                    <h2 class="text-xl font-bold mb-4">Detail Tugas</h2>
                    <h3 id="taskTitle" class="text-2xl font-bold text-primary">Pilih tugas dari daftar</h3>
                    <p id="taskDescription" class="mt-3 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                        Klik atau double-click tugas di sebelah kiri untuk melihat detail dan mengunggah hasil.
                    </p>
                    <div class="mt-8 bg-gray-200 dark:bg-gray-700 h-64 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-gray-400">task_alt</span>
                    </div>
                    <button id="uploadButton" disabled
                        class="w-full mt-6 bg-primary text-white font-bold py-4 rounded-xl hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        UPLOAD HASIL TUGAS
                    </button>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">Status:</span>
                            <span id="taskStatusBadge" class="px-2 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">Prioritas:</span>
                            <span id="taskPriorityBadge" class="px-2 py-1 rounded-full text-xs font-medium bg-gray-200 dark:bg-gray-700">-</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary-light dark:text-text-secondary-dark">Deadline:</span>
                            <span id="taskDeadlineText" class="text-text-secondary-light dark:text-text-secondary-dark">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Task Detail Modal -->
    <div id="taskDetailModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Tugas</h2>
                    <button id="closeDetailModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <span class="material-symbols-outlined text-3xl">close</span>
                    </button>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-6 md:p-8 rounded-xl border border-gray-200 dark:border-gray-700">
                    <!-- Task Title -->
                    <h3 id="modalTaskTitle" class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4 md:mb-6"></h3>
                    
                    <div class="space-y-4 md:space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">Deskripsi Tugas</h4>
                            <p id="modalTaskDescription" class="text-gray-600 dark:text-gray-400 text-sm md:text-base leading-relaxed whitespace-pre-line"></p>
                        </div>
                        
                        <!-- Deadline -->
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">Deadline</h4>
                            <p id="modalTaskDeadline" class="text-gray-600 dark:text-gray-400 text-sm md:text-base"></p>
                        </div>
                        
                        <!-- Ditugaskan Oleh -->
                        <div>
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">Ditugaskan oleh</h4>
                            <p id="modalTaskAssigner" class="text-gray-600 dark:text-gray-400 text-sm md:text-base"></p>
                        </div>
                        
                        <!-- Status dan Prioritas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">Status</h4>
                                <p id="modalTaskStatus" class="text-gray-600 dark:text-gray-400 text-sm md:text-base"></p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1 text-base md:text-lg">Prioritas</h4>
                                <p id="modalTaskPriority" class="text-gray-600 dark:text-gray-400 text-sm md:text-base"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Terlampir Section -->
                    <div class="mt-6 md:mt-8" id="fileAttachmentSection" style="display: none;">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2 text-base md:text-lg">File Hasil Tugas</h4>
                        <div id="fileAttachmentList" class="space-y-3">
                            <!-- Files will be populated here -->
                        </div>
                    </div>
                    
                    <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-700" />
                    
                    <!-- Komentar & Diskusi Section -->
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3 md:mb-4">Komentar & Diskusi</h4>
                        
                        <!-- Comments Container -->
                        <div id="commentsContainer" class="space-y-4 md:space-y-6 mb-6 max-h-64 overflow-y-auto p-2">
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <span class="material-symbols-outlined text-4xl mb-2">chat</span>
                                <p>Belum ada komentar</p>
                            </div>
                        </div>
                        
                        <!-- Comment Input Form -->
                        <div class="flex flex-col">
                            <textarea id="commentInput"
                                class="w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-shadow p-3 text-sm md:text-base"
                                placeholder="Tulis Komentar..." rows="3"></textarea>
                            <div class="flex justify-end mt-3 gap-2">
                                <button id="cancelComment" type="button"
                                    class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 md:px-5 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition-colors text-sm md:text-base">
                                    Batal
                                </button>
                                <button id="submitComment" type="button"
                                    class="bg-primary text-white font-medium py-2 px-4 md:px-5 rounded-lg hover:bg-purple-700 transition-colors text-sm md:text-base">
                                    Kirim Komentar
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                        <button id="modalCancelButton" 
                            class="px-6 py-3 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition text-sm md:text-base w-full sm:w-auto">
                            Tutup
                        </button>
                        <button id="modalUploadButton" 
                            class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-purple-700 transition text-sm md:text-base w-full sm:w-auto">
                            Upload Hasil Tugas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full shadow-2xl">
            <div class="p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold">Upload Hasil Tugas</h2>
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
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 text-center hover:border-primary transition">
                                <input type="file" id="taskFile" name="file" class="hidden" accept=".pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png,.gif,.txt,.xlsx,.xls,.ppt,.pptx">
                                <span class="material-symbols-outlined text-4xl text-gray-400 mb-3">cloud_upload</span>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    Klik atau drag file ke sini
                                </p>
                                <p class="text-xs text-gray-500">
                                    PDF, DOC, ZIP, JPG, PNG, TXT, XLSX, PPT (max. 10MB)
                                </p>
                                <button type="button" id="fileUploadTrigger" class="mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-purple-700">
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
                        <button type="button" id="cancelUpload" class="px-6 py-3 bg-gray-300 dark:bg-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-purple-700">Upload</button>
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

        // ✅ PERBAIKAN: API Routes yang benar
        const API_ROUTES = {
            taskDetail: (id) => `/api/tasks/${id}/detail`,
            taskComments: (id) => `/api/tasks/${id}/comments`,
            storeComment: (id) => `/api/tasks/${id}/comments`,
            uploadFile: (id) => `/api/tasks/${id}/upload-file`,  // ✅ PERBAIKI INI!
            downloadFile: (id) => `/api/tasks/${id}/download`,
            downloadSubmission: (id) => `/api/tasks/${id}/download`,
            downloadTaskFile: (taskId, fileId) => `/api/tasks/files/${fileId}/download`,
            getTaskFiles: (id) => `/api/tasks/${id}/files`,
        };

        const elements = {
            taskCards: document.querySelectorAll('.task-card'),
            taskTitle: document.getElementById('taskTitle'),
            taskDescription: document.getElementById('taskDescription'),
            uploadButton: document.getElementById('uploadButton'),
            taskStatusBadge: document.getElementById('taskStatusBadge'),
            taskPriorityBadge: document.getElementById('taskPriorityBadge'),
            taskDeadlineText: document.getElementById('taskDeadlineText'),
            taskDetailModal: document.getElementById('taskDetailModal'),
            modalTaskTitle: document.getElementById('modalTaskTitle'),
            modalTaskDescription: document.getElementById('modalTaskDescription'),
            modalTaskDeadline: document.getElementById('modalTaskDeadline'),
            modalTaskAssigner: document.getElementById('modalTaskAssigner'),
            modalTaskStatus: document.getElementById('modalTaskStatus'),
            modalTaskPriority: document.getElementById('modalTaskPriority'),
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
            modalUploadButton: document.getElementById('modalUploadButton'),
            closeUploadModal: document.getElementById('closeUploadModal'),
            cancelUpload: document.getElementById('cancelUpload'),
            commentsContainer: document.getElementById('commentsContainer'),
            fileAttachmentSection: document.getElementById('fileAttachmentSection'),
            fileAttachmentList: document.getElementById('fileAttachmentList'),
        };

        // Fungsi untuk memilih task
        function selectTask(card) {
            if (!card) return;
            
            // Reset active state
            elements.taskCards.forEach(c => c.classList.remove('ring-4', 'ring-primary/50'));
            card.classList.add('ring-4', 'ring-primary/50');

            currentTaskId = parseInt(card.dataset.taskId);
            elements.taskTitle.textContent = card.dataset.taskTitle;
            elements.taskDescription.textContent = card.dataset.taskFullDescription;
            
            // Cek jika tugas sudah selesai, disable upload button
            const taskStatus = card.dataset.taskStatus;
            if (taskStatus === 'selesai') {
                elements.uploadButton.disabled = true;
                elements.uploadButton.textContent = 'TUGAS SUDAH SELESAI';
                elements.uploadButton.className = 'w-full mt-6 bg-gray-400 text-white font-bold py-4 rounded-xl cursor-not-allowed';
            } else {
                elements.uploadButton.disabled = false;
                elements.uploadButton.textContent = 'UPLOAD HASIL TUGAS';
                elements.uploadButton.className = 'w-full mt-6 bg-primary text-white font-bold py-4 rounded-xl hover:bg-purple-700 transition';
            }
            
            elements.uploadTaskId.value = currentTaskId;
            
            // Update sidebar details
            updateSidebarDetails(card);
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
            } else if (status === 'selesai') {
                statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                statusText = 'SELESAI';
            }
            
            elements.taskStatusBadge.className = `px-2 py-1 rounded-full text-xs font-medium ${statusClass}`;
            elements.taskStatusBadge.textContent = statusText;
            
            // Priority
            const priority = card.dataset.taskPriority || 'medium';
            let priorityClass = 'bg-gray-200 dark:bg-gray-700';
            let priorityText = 'SEDANG';
            
            if (priority === 'high') {
                priorityClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                priorityText = 'TINGGI';
            } else if (priority === 'medium') {
                priorityClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                priorityText = 'SEDANG';
            } else {
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
        }

        // Fungsi untuk download file
        function downloadFile(filePath) {
            const url = `/api/tasks/${currentTaskId}/download`;
            window.open(url, '_blank');
        }

        // Fungsi untuk load task detail via API
        async function loadTaskDetail(taskId) {
            try {
                const response = await fetch(API_ROUTES.taskDetail(taskId), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success && result.task) {
                    return result.task;
                }
            } catch (error) {
                console.error('Error loading task detail:', error);
            }
            return null;
        }

        // Fungsi untuk update modal dengan data detail
        async function updateModalWithDetail(task) {
            // Set title
            elements.modalTaskTitle.textContent = task.judul;
            
            // Set description
            elements.modalTaskDescription.textContent = task.deskripsi || 'Tidak ada deskripsi';
            
            // Format tanggal deadline
            if (task.deadline) {
                const deadline = new Date(task.deadline);
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
            elements.modalTaskAssigner.textContent = task.assigned_by || 'Admin';
            
            // Status dengan styling
            const status = task.status || 'pending';
            let statusText = 'Tidak diketahui';
            let statusClass = 'text-gray-600 dark:text-gray-400';
            
            if (status === 'pending') {
                statusText = 'PENDING';
                statusClass = 'text-yellow-600 dark:text-yellow-400';
            } else if (status === 'proses') {
                statusText = 'PROSES';
                statusClass = 'text-blue-600 dark:text-blue-400';
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
            const priority = task.priority || 'medium';
            let priorityText = 'Tidak diketahui';
            let priorityClass = 'text-gray-600 dark:text-gray-400';
            
            if (priority === 'high') {
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
            
            // Tampilkan file jika ada
            if (task.submission_file) {
                elements.fileAttachmentSection.style.display = 'block';
                const submittedDate = task.submitted_at ? new Date(task.submitted_at).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : '-';
                
                elements.fileAttachmentList.innerHTML = `
                    <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">task_alt</span>
                            <div>
                                <p class="font-medium text-green-800 dark:text-green-300">File Hasil Tugas</p>
                                <p class="text-xs text-green-600 dark:text-green-400">Diunggah pada: ${submittedDate}</p>
                                ${task.submission_notes ? `<p class="text-xs text-green-700 dark:text-green-300 mt-1">📝 Catatan: ${task.submission_notes}</p>` : ''}
                            </div>
                        </div>
                        <button onclick="window.open('${task.submission_url}', '_blank')" 
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">download</span>
                            Download
                        </button>
                    </div>
                `;
                
                // Disable upload button di modal jika sudah upload
                elements.modalUploadButton.disabled = true;
                elements.modalUploadButton.textContent = 'SUDAH DIUPLOAD';
                elements.modalUploadButton.className = 'px-6 py-3 bg-green-600 text-white rounded-lg cursor-not-allowed text-sm md:text-base w-full sm:w-auto';
            } else {
                elements.fileAttachmentSection.style.display = 'none';
                elements.modalUploadButton.disabled = false;
                elements.modalUploadButton.textContent = 'Upload Hasil Tugas';
                elements.modalUploadButton.className = 'px-6 py-3 bg-primary text-white rounded-lg hover:bg-purple-700 transition text-sm md:text-base w-full sm:w-auto';
            }
            
            // Load comments
            loadComments(currentTaskId);
        }

        // Fungsi untuk membuka modal detail
        async function openDetailModal() {
            if (!currentTaskId) return;
            
            const taskDetail = await loadTaskDetail(currentTaskId);
            if (taskDetail) {
                await updateModalWithDetail(taskDetail);
            }
            
            elements.taskDetailModal.classList.remove('hidden');
        }

        // Fungsi untuk load komentar
        async function loadComments(taskId) {
            try {
                const response = await fetch(API_ROUTES.taskComments(taskId), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success && result.comments) {
                    renderComments(result.comments);
                }
            } catch (error) {
                console.error('Error loading comments:', error);
                renderComments([]);
            }
        }

        // Fungsi untuk render komentar
        function renderComments(comments) {
            if (!elements.commentsContainer) return;
            
            if (comments.length === 0) {
                elements.commentsContainer.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-4xl mb-2">chat</span>
                        <p>Belum ada komentar</p>
                    </div>
                `;
                return;
            }
            
            elements.commentsContainer.innerHTML = comments.map(comment => `
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-sm">person</span>
                            </div>
                            <div>
                                <span class="font-medium text-sm">${comment.user.name}</span>
                                <span class="text-xs px-2 py-0.5 ml-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                                    ${comment.user.role}
                                </span>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">${comment.created_at}</span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line mt-2">${comment.content}</p>
                </div>
            `).join('');
            
            // Scroll ke bawah
            elements.commentsContainer.scrollTop = elements.commentsContainer.scrollHeight;
        }

        // Fungsi untuk mengirim komentar
        async function submitComment() {
            if (!currentTaskId) {
                showNotification('Peringatan', 'Pilih tugas terlebih dahulu', 'warning');
                return;
            }
            
            const content = elements.commentInput.value.trim();
            if (!content) {
                showNotification('Peringatan', 'Komentar tidak boleh kosong', 'warning');
                return;
            }
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                const response = await fetch(API_ROUTES.storeComment(currentTaskId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ content: content })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Clear input
                    elements.commentInput.value = '';
                    
                    // Add new comment to list
                    const commentsResponse = await fetch(API_ROUTES.taskComments(currentTaskId));
                    const commentsResult = await commentsResponse.json();
                    
                    if (commentsResult.success) {
                        renderComments(commentsResult.comments);
                    }
                    
                    showNotification('Sukses', 'Komentar berhasil dikirim');
                } else {
                    showNotification('Error', result.message || 'Gagal mengirim komentar', 'error');
                }
            } catch (error) {
                console.error('Error submitting comment:', error);
                showNotification('Error', 'Terjadi kesalahan saat mengirim komentar', 'error');
            }
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

        // Event Listeners untuk task cards
        if (elements.taskCards.length > 0) {
            elements.taskCards.forEach(card => {
                card.addEventListener('click', () => selectTask(card));
                card.addEventListener('dblclick', () => {
                    selectTask(card);
                    openDetailModal();
                });
            });
            
            // Auto-select first task
            setTimeout(() => {
                if (elements.taskCards.length > 0) {
                    selectTask(elements.taskCards[0]);
                }
            }, 100);
        }

        // Event Listeners untuk komentar
        if (elements.submitComment) {
            elements.submitComment.addEventListener('click', submitComment);
        }

        if (elements.cancelComment) {
            elements.cancelComment.addEventListener('click', () => {
                elements.commentInput.value = '';
            });
        }

        if (elements.commentInput) {
            elements.commentInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && e.ctrlKey) {
                    submitComment();
                }
            });
        }

        // Event Listeners untuk modal detail
        if (elements.closeDetailModal) {
            elements.closeDetailModal.addEventListener('click', () => {
                elements.taskDetailModal.classList.add('hidden');
            });
        }

        if (elements.modalCancelButton) {
            elements.modalCancelButton.addEventListener('click', () => {
                elements.taskDetailModal.classList.add('hidden');
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

        // Drag and drop functionality
        const dropZone = elements.uploadModal?.querySelector('.border-dashed');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-primary', 'bg-primary/5');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-primary', 'bg-primary/5');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-primary', 'bg-primary/5');
                
                if (e.dataTransfer.files.length > 0) {
                    elements.taskFile.files = e.dataTransfer.files;
                    if (elements.fileName) {
                        elements.fileName.textContent = `File terpilih: ${e.dataTransfer.files[0].name}`;
                        elements.fileName.classList.remove('hidden');
                    }
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

        // ✅ PERBAIKAN: Form submit untuk upload menggunakan endpoint yang benar
        if (elements.uploadForm) {
            elements.uploadForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                if (!currentTaskId) {
                    showNotification('Error', 'Tidak ada tugas yang dipilih', 'error');
                    return;
                }
                
                const fileInput = elements.taskFile;
                if (!fileInput.files || fileInput.files.length === 0) {
                    showNotification('Error', 'Silakan pilih file terlebih dahulu', 'error');
                    return;
                }
                
                // Buat FormData
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                
                const notes = elements.fileNotes ? elements.fileNotes.value : '';
                if (notes) {
                    formData.append('notes', notes);
                }
                
                // Tambahkan CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    formData.append('_token', csrfToken.content);
                }
                
                showNotification('Memproses', 'Mengupload file dan memperbarui status...', 'warning');
                
                try {
                    // ✅ GUNAKAN ENDPOINT YANG BENAR: /upload-file
                    const response = await fetch(`/api/tasks/${currentTaskId}/upload-file`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                            // JANGAN tambahkan 'Content-Type' untuk FormData
                        },
                        credentials: 'include' // untuk kirim session cookie
                    });
                    
                    const result = await response.json();
                    
                    if (!response.ok) {
                        // Cek jika ada error validasi
                        if (result.errors) {
                            const errorMessages = Object.values(result.errors).flat().join(', ');
                            showNotification('Error', `Validasi gagal: ${errorMessages}`, 'error');
                        } else {
                            showNotification('Error', result.message || 'Gagal mengupload file', 'error');
                        }
                        return;
                    }
                    
                    if (result.success) {
                        showNotification('Sukses', result.message || 'File berhasil diupload dan status tugas diperbarui menjadi SELESAI!');
                        
                        // Update UI
                        if (elements.uploadModal) {
                            elements.uploadModal.classList.add('hidden');
                            elements.uploadForm.reset();
                            if (elements.fileName) elements.fileName.classList.add('hidden');
                        }
                        
                        // Update task card status
                        const card = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                        if (card) {
                            card.dataset.taskStatus = 'selesai';
                            
                            // Update status badge
                            const statusBadge = card.querySelector('.inline-block.px-3');
                            if (statusBadge) {
                                statusBadge.className = 'mt-2 inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                statusBadge.textContent = 'SELESAI';
                            }
                            
                            // Add uploaded badge
                            const badgeContainer = card.querySelector('.flex.items-center.gap-2.mt-2');
                            if (badgeContainer && !badgeContainer.querySelector('.bg-green-100')) {
                                const uploadedBadge = document.createElement('span');
                                uploadedBadge.className = 'inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                uploadedBadge.innerHTML = '<span class="material-symbols-outlined text-xs">check_circle</span>Telah Diupload';
                                badgeContainer.appendChild(uploadedBadge);
                            }
                            
                            // Update sidebar
                            updateSidebarDetails(card);
                            
                            // Update upload button
                            if (elements.uploadButton) {
                                elements.uploadButton.disabled = true;
                                elements.uploadButton.textContent = 'TUGAS SUDAH SELESAI';
                                elements.uploadButton.className = 'w-full mt-6 bg-gray-400 text-white font-bold py-4 rounded-xl cursor-not-allowed';
                            }
                        }
                        
                        // Reload task detail untuk mendapatkan data terbaru
                        try {
                            const taskResponse = await fetch(API_ROUTES.taskDetail(currentTaskId));
                            const taskResult = await taskResponse.json();
                            
                            if (taskResult.success && elements.taskDetailModal && !elements.taskDetailModal.classList.contains('hidden')) {
                                await updateModalWithDetail(taskResult.task);
                            }
                        } catch (error) {
                            console.error('Error reloading task detail:', error);
                        }
                        
                        // Tambahkan delay sebelum reload halaman
                        setTimeout(() => {
                            // Optional: Reload page untuk update data terbaru
                            // window.location.reload();
                        }, 2000);
                        
                    } else {
                        showNotification('Error', result.message || 'Gagal mengupload file', 'error');
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    showNotification('Error', 'Terjadi kesalahan jaringan saat mengupload', 'error');
                }
            });
        }

        // Filter fungsi
        function applyFilters() {
            const status = elements.statusFilter ? elements.statusFilter.value : 'all';
            const query = elements.searchInput ? elements.searchInput.value.toLowerCase() : '';
            let visible = 0;

            elements.taskCards.forEach(card => {
                const matchesStatus = status === 'all' || card.dataset.taskStatus === status;
                const matchesSearch = card.dataset.taskTitle.toLowerCase().includes(query);
                card.style.display = matchesStatus && matchesSearch ? 'flex' : 'none';
                if (matchesStatus && matchesSearch) visible++;
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
        
        // Jika tidak ada tasks, disable button
        if (tasks.length === 0 && elements.uploadButton) {
            elements.uploadButton.disabled = true;
            elements.uploadButton.textContent = 'Tidak ada tugas';
        }
        
        // Debug routes
        async function debugRoutes() {
            try {
                console.log('=== DEBUG ROUTES ===');
                console.log('Current Task ID:', currentTaskId);
                
                // Test route yang benar
                if (currentTaskId) {
                    const correctResponse = await fetch(`/api/tasks/${currentTaskId}/upload-file`, { method: 'HEAD' });
                    console.log(`Route /api/tasks/${currentTaskId}/upload-file status:`, correctResponse.status);
                }
                
            } catch (error) {
                console.error('Debug error:', error);
            }
        }
        
        // Jalankan debug
        setTimeout(debugRoutes, 1000);
    </script>
</body>
</html>
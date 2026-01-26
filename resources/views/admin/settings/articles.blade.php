<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pengaturan Artikel - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('logo1.jpeg') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <!-- GANTI DENGAN TRIX EDITOR -->
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#ffffff",
                        "text-light": "#1e293b",
                        "text-muted-light": "#64748b",
                        "border-light": "#e2e8f0",
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

        .form-input {
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Style khusus untuk Trix agar sesuai dengan tema */
        trix-editor {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem;
            min-height: 400px;
        }

        trix-editor:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
            flex-wrap: wrap;
            gap: 1rem;
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

        .article-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .article-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .article-image {
            height: 200px;
            background-color: #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-content {
            padding: 1rem;
        }

        .article-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding: 0 1rem 1rem;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            background-color: #f1f5f9;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #3b82f6;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .main-content {
                width: 100%;
            }

            /* PERUBAHAN PENTING DI SINI */
            .panel-header {
                padding: 0.75rem 1rem;
                flex-wrap: nowrap; /* CEGAH pembungkusan ke baris baru */
                align-items: center;
                justify-content: space-between;
            }

            /* Buat tombol sedikit lebih kecil agar muat */
            .panel-header button {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .panel-body {
                padding: 1rem;
            }

            .panel-title {
                font-size: 1rem;
                /* Hapus margin-bottom yang ditambahkan sebelumnya */
            }

            /* Modal adjustments */
            .modal-content {
                width: 95%;
                margin: 2% auto;
            }

            .modal-header {
                padding: 0.75rem 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-title {
                font-size: 1.1rem;
            }

            /* Trix Editor adjustment */
            trix-editor {
                min-height: 300px; /* Reduce height on mobile */
            }

            /* Form buttons in modal */
            .modal-body .flex.justify-end {
                flex-direction: column;
                gap: 0.75rem;
            }

            .modal-body .flex.justify-end button {
                width: 100%;
            }

            /* Popup adjustments */
            .minimal-popup {
                left: 20px;
                right: 20px;
                max-width: none;
                transform: translateY(-100px);
            }

            .minimal-popup.show {
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .panel-header {
                padding: 0.5rem 0.75rem;
            }

            /* Buat tombol lebih kecil lagi di layar sangat kecil */
            .panel-header button {
                padding: 0.5rem 0.5rem;
                font-size: 0.8rem;
            }

            .panel-body {
                padding: 0.75rem;
            }

            .panel-title {
                font-size: 0.9rem;
            }

            /* Further modal adjustments for very small screens */
            .modal-content {
                width: 98%;
                margin: 1% auto;
            }

            .modal-header {
                padding: 0.5rem 0.75rem;
            }

            .modal-body {
                padding: 0.75rem;
            }

            .modal-title {
                font-size: 1rem;
            }

            /* Further Trix Editor adjustment */
            trix-editor {
                min-height: 250px; /* Further reduce height */
            }

            /* Further popup adjustments */
            .minimal-popup {
                top: 10px;
                left: 10px;
                right: 10px;
                padding: 12px 16px;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen app-container">
        @include('admin/templet/sider')

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">

                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <a href="{{ route('admin.settings.contact') }}">Dashboard</a>
                    <span class="breadcrumb-separator">/</span>
                    <span>Pengaturan Artikel</span>
                </div>

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Pengaturan Artikel</h2>

                <div class="panel mb-6">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">article</span>
                            Daftar Artikel
                        </h3>
                        <button id="addArticleBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined text-sm">add</span>
                            <span class="hidden sm:inline">Tambah Artikel</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($articles as $article)
                                <div class="article-card" data-id="{{ $article->id }}">
                                    <div class="article-image">
                                        @if($article->image)
                                            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}">
                                        @else
                                            <div class="flex items-center justify-center h-full">
                                                <span class="material-icons-outlined text-4xl text-gray-400">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="article-content">
                                        <h4 class="font-semibold text-lg mb-1">{{ $article->title }}</h4>
                                        <p class="text-gray-600 text-sm mb-2">
                                            {{ Str::limit($article->excerpt ?: strip_tags($article->content), 100) }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                @if($article->is_featured)
                                                    <span
                                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Unggulan</span>
                                                @endif
                                                <span class="text-gray-500 text-xs">Urutan: {{ $article->order }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="article-actions">
                                        <button class="edit-article-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
                                            data-id="{{ $article->id }}">
                                            <span class="material-icons-outlined">edit</span>
                                        </button>
                                        <button class="delete-article-btn p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                            data-id="{{ $article->id }}">
                                            <span class="material-icons-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Artikel -->
    <div id="articleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Artikel</h3>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="articleForm">
                    @csrf
                    <input type="hidden" id="articleId" name="id">
                    <!-- PERBAIKAN PENTING: Tambahkan input tersembunyi untuk _method -->
                    <input type="hidden" id="_method" name="_method" value="POST">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title" id="titleInput"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan (Opsional)</label>
                        <textarea name="excerpt" id="excerptInput"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            rows="2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                        <input type="hidden" name="content" id="contentInput">
                        <trix-editor input="contentInput" class="form-input rounded-lg"></trix-editor>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                        <div class="image-preview" id="imagePreview">
                            <span class="material-icons-outlined text-gray-400">image</span>
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Tampilkan di Halaman Utama</label>
                            <label class="switch">
                                <input type="checkbox" name="is_featured" id="isFeaturedInput">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil</label>
                        <input type="number" name="order" id="orderInput" min="0" value="0"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-2">
                        <button type="button" id="cancelBtn" class="px-4 py-2 btn-secondary rounded-lg w-full sm:w-auto">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg w-full sm:w-auto">Simpan</button>
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
            // --- Deklarasi Semua Elemen yang Dibutuhkan ---
            const articleModal = document.getElementById('articleModal');
            const articleForm = document.getElementById('articleForm');
            const addArticleBtn = document.getElementById('addArticleBtn');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const modalTitle = document.getElementById('modalTitle');
            const articleIdInput = document.getElementById('articleId');
            const methodInput = document.getElementById('_method'); // Ambil elemen _method
            const titleInput = document.getElementById('titleInput');
            const excerptInput = document.getElementById('excerptInput');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const isFeaturedInput = document.getElementById('isFeaturedInput');
            const orderInput = document.getElementById('orderInput');
            const contentInput = document.getElementById('contentInput');

            // --- Cek Elemen Krusial ---
            if (!articleForm || !articleIdInput || !methodInput) {
                alert('Error: Elemen form kritis tidak ditemukan. Pastikan input dengan id "articleId" dan "_method" ada di dalam form.');
                return; // Hentikan eksekusi jika elemen penting hilang
            }

            // --- Fungsi Helper untuk Popup ---
            function showMinimalPopup(title, message, type = 'success') {
                const popup = document.getElementById('minimalPopup');
                if (!popup) return; // Jangan eksekusi jika popup tidak ada

                const popupTitle = popup.querySelector('.minimal-popup-title');
                const popupMessage = popup.querySelector('.minimal-popup-message');
                const popupIcon = popup.querySelector('.minimal-popup-icon span');

                popupTitle.textContent = title;
                popupMessage.textContent = message;
                popup.className = `minimal-popup show ${type}`;

                if (type === 'success') popupIcon.textContent = 'check';
                else if (type === 'error') popupIcon.textContent = 'error';
                else if (type === 'warning') popupIcon.textContent = 'warning';

                setTimeout(() => popup.classList.remove('show'), 3000);
            }

            // --- Event Listeners ---

            // Buka Modal untuk Tambah
            addArticleBtn.addEventListener('click', function () {
                modalTitle.textContent = 'Tambah Artikel';
                articleForm.reset();
                articleIdInput.value = '';
                methodInput.value = 'POST'; // Pastikan method adalah POST
                document.querySelector('trix-editor').innerHTML = '';
                imagePreview.innerHTML = '<span class="material-icons-outlined text-gray-400">image</span>';
                articleModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });

            // Tutup Modal
            const closeModal = () => {
                articleModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };
            closeModalBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            window.addEventListener('click', (event) => { if (event.target === articleModal) closeModal(); });

            // Preview Gambar
            imageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    reader.readAsDataURL(file);
                }
            });

            // Edit Artikel
            document.querySelectorAll('.edit-article-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const idToEdit = this.getAttribute('data-id');
                    fetch(`/admin/settings/articles/${idToEdit}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const article = data.article;
                                modalTitle.textContent = 'Edit Artikel';
                                articleIdInput.value = article.id;
                                titleInput.value = article.title;
                                excerptInput.value = article.excerpt || '';
                                isFeaturedInput.checked = article.is_featured;
                                orderInput.value = article.order;
                                document.querySelector('trix-editor').innerHTML = article.content;
                                
                                if (article.image) {
                                    imagePreview.innerHTML = `<img src="/storage/${article.image}" alt="${article.title}">`;
                                } else {
                                    imagePreview.innerHTML = '<span class="material-icons-outlined text-gray-400">image</span>';
                                }
                                articleModal.style.display = 'block';
                                document.body.style.overflow = 'hidden';
                            } else {
                                showMinimalPopup('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            showMinimalPopup('Error', 'Gagal memuat data artikel', 'error');
                        });
                });
            });

            // Hapus Artikel
            document.querySelectorAll('.delete-article-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
                        const idToDelete = this.getAttribute('data-id');
                        fetch(`/admin/settings/articles/${idToDelete}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showMinimalPopup('Berhasil', data.message, 'success');
                                    document.querySelector(`.article-card[data-id="${idToDelete}"]`).remove();
                                } else {
                                    showMinimalPopup('Error', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Delete Error:', error);
                                showMinimalPopup('Error', 'Terjadi kesalahan server', 'error');
                            });
                    }
                });
            });

            // Submit Form
            articleForm.addEventListener('submit', function (e) {
                e.preventDefault();
                
                // Sinkronisasi konten Trix Editor ke input hidden
                contentInput.value = document.querySelector('trix-editor').innerHTML;

                const isEdit = articleIdInput.value !== '';
                methodInput.value = isEdit ? 'PUT' : 'POST'; // Atur method berdasarkan mode
                
                const formData = new FormData(this);
                const url = isEdit ? `/admin/settings/articles/${articleIdInput.value}` : '/admin/settings/articles';

                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) return response.json().then(err => { throw err; });
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showMinimalPopup('Berhasil', data.message, 'success');
                        closeModal();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showMinimalPopup('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Submit Error:', error);
                    if (error.errors) {
                        const firstError = Object.values(error.errors)[0][0];
                        showMinimalPopup('Validasi Gagal', firstError, 'warning');
                    } else {
                        showMinimalPopup('Error', 'Terjadi kesalahan server', 'error');
                    }
                });
            });

            // Tutup Popup
            document.querySelector('.minimal-popup-close')?.addEventListener('click', () => {
                document.getElementById('minimalPopup')?.classList.remove('show');
            });
        });
    </script>
</body>

</html>
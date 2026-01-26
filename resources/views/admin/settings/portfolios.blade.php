<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Portofolio - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('logo1.jpeg') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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

        .portfolio-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .portfolio-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .portfolio-image {
            height: 200px;
            background-color: #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        .portfolio-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .portfolio-content {
            padding: 1rem;
        }

        .portfolio-actions {
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
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="flex min-h-screen">
        @include('admin/templet/sider')

        <!-- MAIN -->
        <main class="flex-1 flex flex-col main-content">
            <div class="flex-grow p-3 sm:p-8">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <a href="{{ route('admin.settings.contact') }}">Dashboard</a>
                    <span class="breadcrumb-separator">/</span>
                    <span>Daftar Portofolio</span>
                </div>

                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Daftar Portofolio</h2>

                <div class="panel mb-6">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <span class="material-icons-outlined text-primary">work</span>
                            Semua Portofolio
                        </h3>
                        <button id="addPortfolioBtn" class="px-4 py-2 btn-primary rounded-lg flex items-center gap-2">
                            <span class="material-icons-outlined text-sm">add</span>
                            Tambah Portofolio
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse ($portfolios as $portfolio)
                                <div class="portfolio-card" data-id="{{ $portfolio->id }}">
                                    <div class="portfolio-image">
                                        @if($portfolio->image)
                                            <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}">
                                        @else
                                            <div class="flex items-center justify-center h-full">
                                                <span class="material-icons-outlined text-4xl text-gray-400">work</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="portfolio-content">
                                        <h4 class="font-semibold text-lg mb-1">{{ $portfolio->title }}</h4>
                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($portfolio->description, 100) }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-500 text-xs">Urutan: {{ $portfolio->order }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portfolio-actions">
                                        <button class="edit-portfolio-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
                                            data-id="{{ $portfolio->id }}">
                                            <span class="material-icons-outlined">edit</span>
                                        </button>
                                        <button class="delete-portfolio-btn p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                            data-id="{{ $portfolio->id }}">
                                            <span class="material-icons-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <span class="material-icons-outlined text-6xl text-gray-300">work</span>
                                    <h3 class="text-xl font-semibold text-gray-500 mt-4">Belum Ada Portofolio</h3>
                                    <p class="text-gray-400 mt-2">Tambahkan portofolio pertama Anda untuk ditampilkan di halaman landing page.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
    </div>

    <!-- Modal Portofolio -->
    <div id="portfolioModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Portofolio</h3>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="portfolioForm">
                    @csrf
                    <input type="hidden" id="portfolioId" name="id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title" id="titleInput"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="descriptionInput"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            rows="4" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teknologi yang Digunakan</label>
                        <input type="text" name="technologies_used" id="technologiesUsedInput"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                            placeholder="Contoh: React, Node.js, MongoDB">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil</label>
                        <input type="number" name="order" id="orderInput" min="0" value="0"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelBtn" class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan</button>
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
        // --- Minimalist Popup ---
        function showMinimalPopup(title, message, type = 'success') {
            const popup = document.getElementById('minimalPopup');
            const popupTitle = popup.querySelector('.minimal-popup-title');
            const popupMessage = popup.querySelector('.minimal-popup-message');
            const popupIcon = popup.querySelector('.minimal-popup-icon span');

            popupTitle.textContent = title;
            popupMessage.textContent = message;
            popup.className = 'minimal-popup show ' + type;

            if (type === 'success') {
                popupIcon.textContent = 'check';
            } else if (type === 'error') {
                popupIcon.textContent = 'error';
            } else if (type === 'warning') {
                popupIcon.textContent = 'warning';
            }

            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }

        // --- Close popup ---
        document.querySelector('.minimal-popup-close').addEventListener('click', function () {
            document.getElementById('minimalPopup').classList.remove('show');
        });

        // --- Modal handling ---
        const modal = document.getElementById('portfolioModal');
        const addPortfolioBtn = document.getElementById('addPortfolioBtn');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const modalTitle = document.getElementById('modalTitle');
        const portfolioForm = document.getElementById('portfolioForm');
        const portfolioIdInput = document.getElementById('portfolioId');
        const titleInput = document.getElementById('titleInput');
        const descriptionInput = document.getElementById('descriptionInput');
        const technologiesUsedInput = document.getElementById('technologiesUsedInput');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const orderInput = document.getElementById('orderInput');

        // --- Open modal for add ---
        addPortfolioBtn.addEventListener('click', function () {
            modalTitle.textContent = 'Tambah Portofolio';
            portfolioForm.reset();
            portfolioIdInput.value = '';
            imagePreview.innerHTML = '<span class="material-icons-outlined text-gray-400">image</span>';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });

        // --- Close modal ---
        closeModalBtn.addEventListener('click', function () {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        cancelBtn.addEventListener('click', function () {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // --- Close modal when clicking outside ---
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // --- Image preview ---
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // --- Edit portfolio ---
        document.querySelectorAll('.edit-portfolio-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const portfolioId = this.getAttribute('data-id');

                // PERBAIKAN: Gunakan URL yang benar
                fetch(`/admin/settings/portfolios/${portfolioId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const portfolio = data.portfolio;

                            modalTitle.textContent = 'Edit Portofolio';
                            portfolioIdInput.value = portfolio.id;
                            titleInput.value = portfolio.title;
                            descriptionInput.value = portfolio.description;
                            technologiesUsedInput.value = portfolio.technologies_used || '';
                            orderInput.value = portfolio.order;

                            // Show image if exists
                            if (portfolio.image) {
                                imagePreview.innerHTML = `<img src="/storage/${portfolio.image}" alt="${portfolio.title}">`;
                            } else {
                                imagePreview.innerHTML = '<span class="material-icons-outlined text-gray-400">image</span>';
                            }

                            modal.style.display = 'block';
                            document.body.style.overflow = 'hidden';
                        } else {
                            showMinimalPopup('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMinimalPopup('Error', 'Gagal memuat data portofolio', 'error');
                    });
            });
        });

        // --- Delete portfolio ---
        document.querySelectorAll('.delete-portfolio-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (confirm('Apakah Anda yakin ingin menghapus portofolio ini?')) {
                    const portfolioId = this.getAttribute('data-id');

                    // PERBAIKAN: Gunakan URL yang benar
                    fetch(`/admin/settings/portfolios/${portfolioId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showMinimalPopup('Berhasil', data.message, 'success');
                                // Remove portfolio card from DOM
                                document.querySelector(`.portfolio-card[data-id="${portfolioId}"]`).remove();
                            } else {
                                showMinimalPopup('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showMinimalPopup('Error', 'Terjadi kesalahan server', 'error');
                        });
                }
            });
        });

        // --- Submit form ---
        portfolioForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const isEdit = portfolioIdInput.value !== '';
            
            // PERBAIKAN: Gunakan URL yang benar
            const url = isEdit ? `/admin/settings/portfolios/${portfolioIdInput.value}` : '/admin/settings/portfolios';
            const method = isEdit ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showMinimalPopup('Berhasil', data.message, 'success');
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';

                        // Reload page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showMinimalPopup('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Tampilkan pesan error validasi jika ada
                    if (error.errors) {
                        const firstError = Object.values(error.errors)[0][0];
                        showMinimalPopup('Validasi Gagal', firstError, 'warning');
                    } else {
                        showMinimalPopup('Error', 'Terjadi kesalahan server', 'error');
                    }
                });
        });

    }); // <-- AKHIR DARI document.addEventListener
    </script>
</body>

</html>
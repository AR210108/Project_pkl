<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pengaturan Kontak - Dashboard</title>
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
        
        .tab-container {
            display: flex;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }
        
        .tab-button {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #64748b;
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        
        .tab-button:hover {
            color: #3b82f6;
        }
        
        .tab-button.active {
            color: #3b82f6;
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #3b82f6;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .contact-preview {
            background-color: #f8fafc;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        .contact-icon {
            background-color: #0f172a;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: black;
            border-radius: 0.5rem;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
        }
        
        .whatsapp-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            background-color: #f8fafc;
        }
        
        .whatsapp-btn .bx {
            margin-right: 8px;
            font-size: 20px;
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
                <h2 class="text-xl sm:text-3xl font-bold mb-4 sm:mb-8">Pengaturan Kontak</h2>

                <!-- Tab Navigation -->
                <div class="tab-container">
                    <button class="tab-button active" data-tab="contact">Kontak</button>
                    <button class="tab-button" data-tab="preview">Pratinjau</button>
                </div>

                <!-- Tab Content -->
                <div class="tab-content active" id="contact-tab">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="panel-title">
                                <span class="material-icons-outlined text-primary">contact_phone</span>
                                Informasi Kontak
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form id="contactForm" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" id="emailInput"
                                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                            value="{{ $contactData['email'] }}" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp/Telepon</label>
                                        <input type="tel" name="phone" id="phoneInput"
                                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                            value="{{ $contactData['phone'] }}" required>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                        <textarea name="address" id="addressInput"
                                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                            rows="3" required>{{ $contactData['address'] }}</textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Default WhatsApp</label>
                                        <textarea name="whatsapp_message" id="whatsappMessageInput"
                                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary form-input"
                                            rows="2" required>{{ $contactData['whatsapp_message'] }}</textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" id="cancelContactBtn"
                                        class="px-4 py-2 btn-secondary rounded-lg">Batal</button>
                                    <button type="submit" class="px-4 py-2 btn-primary rounded-lg">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="preview-tab">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="panel-title">
                                <span class="material-icons-outlined text-primary">preview</span>
                                Pratinjau Halaman Kontak
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="contact-preview">
                                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                    <span class="material-icons-outlined text-primary">contact_phone</span>
                                    Hubungi Kami
                                </h3>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <span class="material-icons-outlined">location_on</span>
                                    </div>
                                    <div class="contact-text">
                                        <div class="font-semibold text-gray-900">Lokasi</div>
                                        <div class="text-gray-600" id="previewAddress">{{ $contactData['address'] }}</div>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <span class="material-icons-outlined">email</span>
                                    </div>
                                    <div class="contact-text">
                                        <div class="font-semibold text-gray-900">Email</div>
                                        <div class="text-gray-600" id="previewEmail">{{ $contactData['email'] }}</div>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <span class="material-icons-outlined">phone</span>
                                    </div>
                                    <div class="contact-text">
                                        <div class="font-semibold text-gray-900">No WA/Telepon</div>
                                        <div class="text-gray-600" id="previewPhone">{{ $contactData['phone'] }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500 mb-4">Pratinjau tombol WhatsApp yang akan muncul di halaman landing page:</p>
                                <button id="previewWhatsappBtn" class="whatsapp-btn mx-auto">
                                    <i class='bx bxl-whatsapp'></i>
                                    Hubungi Kami
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="text-center p-4 bg-gray-100 text-text-muted-light text-sm border-t border-border-light">
                Copyright ©2025 by digicity.id
            </footer>
        </main>
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
        // Minimalist Popup
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

        // Close popup
        document.querySelector('.minimal-popup-close').addEventListener('click', function() {
            document.getElementById('minimalPopup').classList.remove('show');
        });

        // Tab Navigation
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });

        // Update preview when form values change
        document.getElementById('emailInput').addEventListener('input', function() {
            document.getElementById('previewEmail').textContent = this.value;
        });

        document.getElementById('phoneInput').addEventListener('input', function() {
            document.getElementById('previewPhone').textContent = this.value;
        });

        document.getElementById('addressInput').addEventListener('input', function() {
            document.getElementById('previewAddress').textContent = this.value;
        });

        // Contact Form
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            try {
                const response = await fetch("/admin/settings/contact", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Accept": "application/json"
                    },
                    body: formData
                });

                const res = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && res.errors) {
                        const message = Object.values(res.errors)[0][0];
                        showMinimalPopup('Validasi Gagal', message, 'warning');
                        return;
                    }

                    showMinimalPopup('Error', res.message || 'Terjadi kesalahan', 'error');
                    return;
                }

                showMinimalPopup('Berhasil', res.message, 'success');
            } catch (error) {
                console.error(error);
                showMinimalPopup('Error', 'Terjadi kesalahan server', 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });

        // WhatsApp Button in Preview
        document.getElementById('previewWhatsappBtn').addEventListener('click', function() {
            const phoneNumber = document.getElementById('phoneInput').value.replace(/\s/g, '').replace(/-/g, '').replace('+', '');
            const message = document.getElementById('whatsappMessageInput').value;
            
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
            
            window.open(whatsappUrl, '_blank');
        });

        // Cancel button
        document.getElementById('cancelContactBtn').addEventListener('click', function() {
            location.reload();
        });
    </script>
</body>

</html>
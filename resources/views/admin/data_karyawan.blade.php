<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <!-- Tambahkan library Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6366f1", // indigo-500
                        secondary: "#8b5cf6", // violet-500
                        accent: "#ec4899", // pink-500
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-light": "#ffffff", // white
                        "surface-dark": "#1e293b", // slate-800
                        "text-light": "#0f172a", // slate-900
                        "text-dark": "#f1f5f9", // slate-100
                        "subtle-light": "#64748b", // slate-500
                        "subtle-dark": "#94a3b8", // slate-400
                        "text-light-primary": "#111827",
                        "text-dark-primary": "#F9FAFB",
                        "text-light-secondary": "#6B7280",
                        "text-dark-secondary": "#9CA3AF",
                        "border-light": "#E5E7EB",
                        "border-dark": "#374151",
                    },
                    fontFamily: {
                        display: ["Plus Jakarta Sans", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                        "lg": "0.75rem", // 12px
                        "xl": "1rem", // 16px
                        "full": "9999px",
                    },
                },
            },
        };
    </script>
    <style>
        /* Tambahkan style untuk glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .dark .glass-effect {
            background: rgba(30, 41, 59, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(30, 41, 59, 0.18);
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Tambahan style untuk notifikasi */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background-color: #10b981;
        }
        
        .notification.error {
            background-color: #ef4444;
        }
    </style>
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light-primary dark:text-dark-primary">
    <div class="flex h-screen">
        <!-- Include Sidebar Template -->
        @include('admin/templet/sider')
        
        <main class="flex-1 flex flex-col">
            <div class="flex-1 p-8 overflow-y-auto">
                <h2 class="text-3xl font-bold mb-8 text-text-light-primary dark:text-dark-primary">Daftar karyawan</h2>
                <div class="flex items-center justify-between mb-6">
                    <button id="tambahKaryawanBtn"
                        class="flex items-center bg-gray-200 dark:bg-gray-700 text-text-light-primary dark:text-dark-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <span class="material-icons-outlined text-xl mr-2">add</span>
                        tambah karyawan
                    </button>
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('admin.karyawan.index') }}" method="GET" class="relative w-72">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light-secondary dark:text-dark-secondary">search</span>
                            <input
                                class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-full pl-10 pr-4 py-2.5 text-sm placeholder-text-light-secondary dark:placeholder-text-dark-secondary focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                                placeholder="Search..." type="text" name="search" value="{{ request('search') }}" />
                        </form>
                        <button
                            class="bg-gray-200 dark:bg-gray-700 text-text-light-primary dark:text-dark-primary px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Filter
                        </button>
                    </div>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead
                            class="bg-gray-200 dark:bg-gray-700 text-text-light-secondary dark:text-dark-secondary uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-medium">NO</th>
                                <th class="px-6 py-3 font-medium">NAMA</th>
                                <th class="px-6 py-3 font-medium">JABATAN</th>
                                <th class="px-6 py-3 font-medium">GAJI</th>
                                <th class="px-6 py-3 font-medium">ALAMAT</th>
                                <th class="px-6 py-3 font-medium">KONTAK</th>
                                <th class="px-6 py-3 font-medium">FOTO</th>
                                <th class="px-6 py-3 font-medium">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if(isset($karyawan) && count($karyawan) > 0)
                                @php $no = $karyawan->firstItem(); @endphp
                                @foreach($karyawan as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $no++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->jabatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Rp. {{ number_format($item->gaji, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $item->alamat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->kontak }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if($item->foto)
                                                <img src="{{ asset('karyawan/' . $item->foto) }}" alt="{{ $item->nama }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="material-icons-outlined text-gray-500 dark:text-gray-400">person</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="edit-btn text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3" 
                                                    data-karyawan='{"id": "{{ $item->id }}", "nama": "{{ $item->nama }}", "jabatan": "{{ $item->jabatan }}", "gaji": "{{ $item->gaji }}", "alamat": "{{ $item->alamat }}", "kontak": "{{ $item->kontak }}", "foto": "{{ $item->foto ?? '' }}" }'>
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <button class="delete-btn text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                                    data-id="{{ $item->id }}">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada data karyawan
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if(isset($karyawan) && $karyawan->hasPages())
                <div class="flex justify-center mt-6">
                    <nav class="inline-flex items-center space-x-1">
                        {{ $karyawan->links() }}
                    </nav>
                </div>
                @endif
            </div>
            <footer
                class="bg-surface-light dark:bg-surface-dark border-t border-border-light dark:border-border-dark px-8 py-4 text-center">
                <p class="text-sm text-text-light-secondary dark:text-dark-secondary">Copyright ©2025 by digicity.id
                </p>
            </footer>
        </main>
    </div>

    <!-- Popup Modal untuk Tambah Karyawan -->
    <div id="tambahKaryawanModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div
            class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Tambah Karyawan Baru
                    </h3>
                    <button id="closeModalBtn"
                        class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="tambahKaryawanForm" class="p-6 space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama" id="namaInput"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan nama karyawan" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatanInput"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan jabatan" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Gaji</label>
                        <input type="number" name="gaji" id="gajiInput"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan gaji" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Kontak</label>
                        <input type="text" name="kontak" id="kontakInput"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan nomor telepon" required>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Alamat</label>
                        <textarea name="alamat" id="alamatInput"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Foto</label>
                        <div class="flex items-center space-x-4">
                            <div id="fotoPreview"
                                class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>
                            </div>
                            <div>
                                <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*">
                                <button type="button" id="pilihFotoBtn"
                                    class="bg-gray-200 dark:bg-gray-700 text-text-light-primary dark:text-dark-primary px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    Pilih Foto
                                </button>
                                <p class="text-xs text-text-light-secondary dark:text-dark-secondary mt-1">Format: JPG,
                                    PNG maks. 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelBtn"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup Modal untuk Edit Karyawan -->
    <div id="editKaryawanModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div
            class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Edit Karyawan</h3>
                    <button id="closeEditModalBtn"
                        class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="editKaryawanForm" class="p-6 space-y-6" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editId" name="id">
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama
                            Lengkap</label>
                        <input type="text" id="editNama" name="nama"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan nama karyawan" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jabatan</label>
                        <input type="text" id="editJabatan" name="jabatan"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan jabatan" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Gaji</label>
                        <input type="number" id="editGaji" name="gaji"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan gaji" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Kontak</label>
                        <input type="text" id="editKontak" name="kontak"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan nomor telepon" required>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Alamat</label>
                        <textarea id="editAlamat" name="alamat"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Foto</label>
                        <div class="flex items-center space-x-4">
                            <div id="editFotoPreview"
                                class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>
                            </div>
                            <div>
                                <input type="file" name="foto" id="editFotoInput" class="hidden" accept="image/*">
                                <button type="button" id="pilihEditFotoBtn"
                                    class="bg-gray-200 dark:bg-gray-700 text-text-light-primary dark:text-dark-primary px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    Pilih Foto
                                </button>
                                <p class="text-xs text-text-light-secondary dark:text-dark-secondary mt-1">Format: JPG,
                                    PNG maks. 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelEditBtn"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup Modal untuk Konfirmasi Hapus -->
    <div id="deleteKaryawanModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-border-light dark:border-border-dark">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-light-primary dark:text-dark-primary">Konfirmasi Hapus</h3>
                    <button id="closeDeleteModalBtn"
                        class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                        <span class="material-icons-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>

            <form id="deleteKaryawanForm" method="POST" action="{{ route('admin.karyawan.delete', '') }}">
                @csrf
                @method('DELETE')
                <div class="p-6">
                    <p class="text-text-light-primary dark:text-dark-primary mb-6">Apakah Anda yakin ingin menghapus data
                        karyawan ini? Tindakan ini tidak dapat dibatalkan.</p>

                    <input type="hidden" id="deleteId" name="id">

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors">
                            Hapus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Element -->
    <div id="notification" class="notification"></div>

   <script>
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

    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `notification ${type}`;
        notification.classList.add('show');
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }

    // Modal Tambah
    function openTambahModal() {
        tambahKaryawanModal.classList.remove('hidden');
    }
    function closeTambahModal() {
        tambahKaryawanModal.classList.add('hidden');
        tambahKaryawanForm.reset();
        fotoPreview.innerHTML = '<span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>';
    }

    // Modal Edit
    function openEditModal(data) {
        document.getElementById('editId').value = data.id;
        document.getElementById('editNama').value = data.nama;
        document.getElementById('editJabatan').value = data.jabatan;
        document.getElementById('editGaji').value = data.gaji;
        document.getElementById('editKontak').value = data.kontak;
        document.getElementById('editAlamat').value = data.alamat;

        // Tampilkan foto karyawan jika ada
        if (data.foto) {
            editFotoPreview.innerHTML = `<img src="${window.location.origin}/karyawan/${data.foto}" alt="${data.nama}" class="h-16 w-16 rounded-full object-cover">`;
        } else {
            editFotoPreview.innerHTML = '<span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>';
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

    // Handle foto selection
    pilihFotoBtn.addEventListener('click', () => {
        fotoInput.click();
    });

    pilihEditFotoBtn.addEventListener('click', () => {
        editFotoInput.click();
    });

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoPreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-16 w-16 rounded-full object-cover">`;
            };
            reader.readAsDataURL(file);
        }
    });

    editFotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                editFotoPreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-16 w-16 rounded-full object-cover">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // ========= CRUD ========= //

    // CREATE (POST)
    tambahKaryawanForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = tambahKaryawanForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;

        let formData = new FormData(tambahKaryawanForm);

        try {
            let response = await fetch("/admin/karyawan/store", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                },
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            let res = await response.json();
            
            if (res.success) {
                showNotification(res.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(res.message || 'Terjadi kesalahan saat menyimpan data', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menyimpan data', 'error');
        } finally {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // UPDATE (PUT)
    editKaryawanForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = editKaryawanForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Memperbarui...';
        submitBtn.disabled = true;

        let id = document.getElementById("editId").value;
        let formData = new FormData(editKaryawanForm);

        try {
            let response = await fetch(`/admin/karyawan/update/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "X-HTTP-Method-Override": "PUT",
                    "Accept": "application/json"
                },
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            let res = await response.json();
            
            if (res.success) {
                showNotification(res.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(res.message || 'Terjadi kesalahan saat memperbarui data', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memperbarui data', 'error');
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
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
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
                showNotification(res.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(res.message || 'Terjadi kesalahan saat menghapus data', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus data', 'error');
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
</script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- TAMBAHKAN INI -->
    <title>Daftar Karyawan</title>
    <!-- ... sisa head tetap sama ... -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <!-- Tambahkan library Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
    // Mendapatkan elemen-elemen yang diperlukan
    const tambahKaryawanBtn = document.getElementById('tambahKaryawanBtn');
    const tambahKaryawanModal = document.getElementById('tambahKaryawanModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const tambahKaryawanForm = document.getElementById('tambahKaryawanForm');

    const editKaryawanModal = document.getElementById('editKaryawanModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editKaryawanForm = document.getElementById('editKaryawanForm');

    const deleteKaryawanModal = document.getElementById('deleteKaryawanModal');
    const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Fungsi pembuka/penutup modal (tetap sama)
    function openTambahModal() { /* ... */ }
    function closeTambahModal() { /* ... */ }
    function openEditModal() { /* ... */ }
    function closeEditModal() { /* ... */ }
    function openDeleteModal() { /* ... */ }
    function closeDeleteModal() { /* ... */ }

    // Event listener untuk modal (tetap sama)
    tambahKaryawanBtn.addEventListener('click', openTambahModal);
    closeModalBtn.addEventListener('click', closeTambahModal);
    // ... dan seterusnya untuk semua tombol close/cancel ...

    // --- FUNGSIONALITAS UTAMA ---

    // 1. TAMBAH KARYAWAN
    tambahKaryawanForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/karyawan', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                closeTambahModal();
                window.location.reload(); // Reload untuk melihat perubahan
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // 2. EDIT KARYAWAN
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            // Ambil data dari server
            fetch(`/karyawan/${id}`)
                .then(response => response.json())
                .then(data => {
                    // Isi form dengan data yang ada
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editNama').value = data.nama;
                    document.getElementById('editJabatanId').value = data.jabatan_id;
                    document.getElementById('editGaji').value = data.gaji;
                    document.getElementById('editKontak').value = data.kontak;
                    document.getElementById('editAlamat').value = data.alamat;
                    
                    // Buka modal
                    editKaryawanModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
        });
    });

    editKaryawanForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editId').value;
        const formData = new FormData(this);

        fetch(`/karyawan/${id}`, {
            method: 'POST', // Laravel menggunakan method spoofing
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PUT' // Header untuk mengubah method menjadi PUT
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                closeEditModal();
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    });


    // 3. HAPUS KARYAWAN
    let deleteId = null; // Variabel untuk menyimpan ID yang akan dihapus

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            deleteId = this.getAttribute('data-id');
            openDeleteModal();
        });
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteId) {
            fetch(`/karyawan/${deleteId}`, {
                method: 'POST', // Laravel menggunakan method spoofing
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'DELETE' // Header untuk mengubah method menjadi DELETE
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    closeDeleteModal();
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

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
    </style>
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
                        <div class="relative w-72">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light-secondary dark:text-dark-secondary">search</span>
                            <input
                                class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-full pl-10 pr-4 py-2.5 text-sm placeholder-text-light-secondary dark:placeholder-text-dark-secondary focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                                placeholder="Search..." type="text" />
                        </div>
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
                        <tbody class="text-text-light-primary dark:text-dark-primary">
    @forelse ($karyawan as $item)
    <tr class="border-b border-border-light dark:border-border-dark {{ $loop->odd ? '' : 'bg-surface-light dark:bg-surface-dark' }}">
        <td class="px-6 py-4">{{ $karyawan->firstItem() + $loop->index - 1 }}.</td>
        <td class="px-6 py-4">{{ $item->nama }}</td>
        <td class="px-6 py-4">{{ $item->jabatan->nama_jabatan }}</td>
        <td class="px-6 py-4">{{ $item->gaji }}</td>
        <td class="px-6 py-4">{{ $item->alamat }}</td>
        <td class="px-6 py-4">{{ $item->kontak }}</td>
        <td class="px-6 py-4">
            @if($item->foto)
                <img src="{{ asset('storage/foto_karyawan/' . $item->foto) }}" alt="Foto" class="w-10 h-10 rounded-full object-cover">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-xl">person</span>
                </div>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="flex space-x-2">
                <button class="edit-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-500"
                    data-id="{{ $item->id }}">
                    <span class="material-icons-outlined text-lg">edit</span>
                </button>
                <button class="delete-btn p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-red-500"
                    data-id="{{ $item->id }}">
                    <span class="material-icons-outlined text-lg">delete</span>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-4 text-center text-text-light-secondary dark:text-dark-secondary">
            Tidak ada data karyawan.
        </td>
    </tr>
    @endforelse
</tbody>
                    </table>
                </div>
                <div class="flex justify-center mt-6">
                    <nav class="inline-flex items-center space-x-1">
                        <button
                            class="px-3 py-2 rounded-lg text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-text-light-primary dark:hover:text-dark-primary">
                            <span class="material-icons-outlined text-lg">chevron_left</span>
                        </button>
                        <button class="px-4 py-2 rounded-lg text-sm font-medium bg-primary text-white">1</button>
                        <button
                            class="px-4 py-2 rounded-lg text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-text-light-primary dark:hover:text-dark-primary">2</button>
                        <button
                            class="px-4 py-2 rounded-lg text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-text-light-primary dark:hover:text-dark-primary">3</button>
                        <button
                            class="px-4 py-2 rounded-lg text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-text-light-primary dark:hover:text-dark-primary">4</button>
                        <button
                            class="px-4 py-2 rounded-lg text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-text-light-primary dark:hover:text-dark-primary">5</button>
                        <button
                            class="px-3 py-2 rounded-lg text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-text-light-primary dark:hover:text-dark-primary">
                            <span class="material-icons-outlined text-lg">chevron_right</span>
                        </button>
                    </nav>
                </div>
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

            <form id="tambahKaryawanForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan nama karyawan" required>
                    </div>

                    <!-- Tambahkan input tersembunyi untuk ID -->
<input type="hidden" id="editId" name="id">

                    <!-- Ganti input jabatan -->
<div>
    <label class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Jabatan</label>
    <select name="jabatan_id" required
        class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary">
        <option value="">-- Pilih Jabatan --</option>
        @foreach (App\Models\Jabatan::all() as $jabatan)
            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
        @endforeach
    </select>
</div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Gaji</label>
                        <input type="text" name="gaji"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan gaji" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Kontak</label>
                        <input type="text" name="kontak"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            placeholder="Masukkan nomor telepon" required>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Alamat</label>
                        <textarea name="alamat"
                            class="w-full bg-gray-200 dark:bg-gray-700 border-none rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:ring-opacity-50 text-text-light-primary dark:text-dark-primary"
                            rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-text-light-secondary dark:text-dark-secondary mb-2">Foto</label>
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>
                            </div>
                            <div>
                                <button type="button"
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

            <form id="editKaryawanForm" class="p-6 space-y-6">
                <input type="hidden" id="editId" name="id">

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
                        <input type="text" id="editGaji" name="gaji"
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
                            <div
                                class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="material-icons-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>
                            </div>
                            <div>
                                <button type="button"
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

            <div class="p-6">
                <p class="text-text-light-primary dark:text-dark-primary mb-6">Apakah Anda yakin ingin menghapus data
                    karyawan ini? Tindakan ini tidak dapat dibatalkan.</p>

                <input type="hidden" id="deleteId">

                <div class="flex justify-end space-x-3">
                    <button id="cancelDeleteBtn"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-text-light-primary dark:text-dark-primary bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn"
                        class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mendapatkan elemen-elemen yang diperlukan
        const tambahKaryawanBtn = document.getElementById('tambahKaryawanBtn');
        const tambahKaryawanModal = document.getElementById('tambahKaryawanModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const tambahKaryawanForm = document.getElementById('tambahKaryawanForm');

        // Elemen untuk modal edit
        const editKaryawanModal = document.getElementById('editKaryawanModal');
        const closeEditModalBtn = document.getElementById('closeEditModalBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const editKaryawanForm = document.getElementById('editKaryawanForm');

        // Elemen untuk modal delete
        const deleteKaryawanModal = document.getElementById('deleteKaryawanModal');
        const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        // Fungsi untuk membuka modal tambah
        function openTambahModal() {
            tambahKaryawanModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal tambah
        function closeTambahModal() {
            tambahKaryawanModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            tambahKaryawanForm.reset();
        }

        // Fungsi untuk membuka modal edit
        function openEditModal(id) {
            // Di sini biasanya Anda akan mengambil data dari server berdasarkan ID
            // Untuk contoh, kita akan mengisi dengan data dummy
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = 'PAJAR';
            document.getElementById('editJabatan').value = 'RAJA';
            document.getElementById('editGaji').value = '5M';
            document.getElementById('editKontak').value = '+999';
            document.getElementById('editAlamat').value = 'NGAWI NGAJAJAR';

            editKaryawanModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            editKaryawanModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            editKaryawanForm.reset();
        }

        // Fungsi untuk membuka modal delete
        function openDeleteModal(id) {
            document.getElementById('deleteId').value = id;
            deleteKaryawanModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal delete
        function closeDeleteModal() {
            deleteKaryawanModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Event listener untuk membuka modal tambah
        tambahKaryawanBtn.addEventListener('click', openTambahModal);

        // Event listener untuk menutup modal tambah
        closeModalBtn.addEventListener('click', closeTambahModal);
        cancelBtn.addEventListener('click', closeTambahModal);

        // Event listener untuk modal edit
        closeEditModalBtn.addEventListener('click', closeEditModal);
        cancelEditBtn.addEventListener('click', closeEditModal);

        // Event listener untuk modal delete
        closeDeleteModalBtn.addEventListener('click', closeDeleteModal);
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);

        // Menutup modal saat klik di luar area modal
        tambahKaryawanModal.addEventListener('click', function(event) {
            if (event.target === tambahKaryawanModal) {
                closeTambahModal();
            }
        });

        editKaryawanModal.addEventListener('click', function(event) {
            if (event.target === editKaryawanModal) {
                closeEditModal();
            }
        });

        deleteKaryawanModal.addEventListener('click', function(event) {
            if (event.target === deleteKaryawanModal) {
                closeDeleteModal();
            }
        });

        // Menangani submit form tambah
        tambahKaryawanForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Di sini Anda bisa menambahkan logika untuk menyimpan data karyawan
            alert('Data karyawan berhasil ditambahkan!');
            closeTambahModal();
        });

        // Menangani submit form edit
        editKaryawanForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editId').value;
            // Di sini Anda bisa menambahkan logika untuk update data karyawan
            alert(`Data karyawan dengan ID ${id} berhasil diperbarui!`);
            closeEditModal();
        });

        // Menangani tombol delete
        confirmDeleteBtn.addEventListener('click', function() {
            const id = document.getElementById('deleteId').value;
            // Di sini Anda bisa menambahkan logika untuk menghapus data karyawan
            alert(`Data karyawan dengan ID ${id} berhasil dihapus!`);
            closeDeleteModal();
        });

        // Event listener untuk tombol edit
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditModal(id);
            });
        });

        // Event listener untuk tombol delete
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openDeleteModal(id);
            });
        });
    </script>

</body>

</html>
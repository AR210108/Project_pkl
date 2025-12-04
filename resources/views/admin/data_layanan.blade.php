
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Data Layanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6B7280", // Using a neutral gray as primary for this design
                        "background-light": "#F9FAFB",
                        "background-dark": "#111827",
                        // Tambahkan warna untuk glass effect
                        "primary": "#6366f1", // indigo-500
                        "secondary": "#8b5cf6", // violet-500
                        "accent": "#ec4899", // pink-500
                        "background-light": "#f8fafc", // slate-50
                        "background-dark": "#0f172a", // slate-900
                        "surface-light": "#ffffff", // white
                        "surface-dark": "#1e293b", // slate-800
                        "text-light": "#0f172a", // slate-900
                        "text-dark": "#f1f5f9", // slate-100
                        "subtle-light": "#64748b", // slate-500
                        "subtle-dark": "#94a3b8", // slate-400
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

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

<body class="font-display bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">

    <div class="flex min-h-screen">
        <!-- Menggunakan template header -->
        @include('admin/templet/header')
        <div class="flex-1 flex flex-col">
            <div class="flex-1 p-8">
                <header class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Data Layanan</h1>
                </header>
                <div class="flex justify-between items-center mb-6">
                    <button id="tambahLayananBtn" class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg flex items-center hover:bg-gray-400 dark:hover:bg-gray-600 transition-colors">
                        <span class="material-icons-outlined text-base mr-2">add</span>
                        Tambah Layanan
                    </button>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input class="w-64 pl-10 pr-4 py-2 bg-gray-200 dark:bg-gray-700 border-none rounded-lg focus:ring-2 focus:ring-primary dark:focus:ring-gray-500 text-gray-800 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400" placeholder="Search..." type="text" />
                        </div>
                        <button class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium py-2 px-6 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Filter</button>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 font-medium" scope="col">No</th>
                                <th class="px-6 py-3 font-medium" scope="col">Nama Layanan</th>
                                <th class="px-6 py-3 font-medium" scope="col">Deskripsi</th>
                                <th class="px-6 py-3 font-medium" scope="col">Harga</th>
                                <th class="px-6 py-3 font-medium" scope="col">Deadline</th>
                                <th class="px-6 py-3 font-medium" scope="col">Progres</th>
                                <th class="px-6 py-3 font-medium" scope="col">Status</th>
                                <th class="px-6 py-3 font-medium" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($layanan as $layanan)
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $layanan->nama_layanan }}</td>
                                <td class="px-6 py-4">{{ $layanan->deskripsi }}</td>
                                <td class="px-6 py-4">{{ number_format($layanan->harga) }}</td>
                                <td class="px-6 py-4">{{ $layanan->deadline }}</td>
                                <td class="px-6 py-4">{{ $layanan->progres }}</td>
                                <td class="px-6 py-4">{{ $layanan->status }}</td>
                                <td class="px-6 py-4">
                                    <button class="edit-btn" 
                                        data-id="{{ $layanan->id }}"
                                        data-nama="{{ $layanan->nama_layanan }}"
                                        data-deskripsi="{{ $layanan->deskripsi }}"
                                        data-harga="{{ $layanan->harga }}"
                                        data-deadline="{{ $layanan->deadline }}"
                                        data-progres="{{ $layanan->progres }}"
                                        data-status="{{ $layanan->status }}">
                                        Edit
                                    </button>

                                    <button class="delete-btn" data-id="{{ $layanan->id }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>

                    </table>
                </div>
                <div class="flex justify-center items-center mt-6">
                    <nav class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                        <a class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700" href="#">
                            <span class="material-icons-outlined text-xl">chevron_left</span>
                        </a>
                        <a class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white" href="#">1</a>
                        <a class="px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700" href="#">2</a>
                        <a class="px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700" href="#">3</a>
                        <a class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700" href="#">
                            <span class="material-icons-outlined text-xl">chevron_right</span>
                        </a>
                    </nav>
                </div>
            </div>
            <footer class="bg-gray-300 dark:bg-gray-900 text-center py-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">Copyright ©2025 by digicity.id</p>
            </footer>
        </div>
    </div>

    <!-- Modal Tambah Layanan -->
    <div id="tambahLayananModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-4">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Layanan Baru</h2>
            </div>
            <form action="{{ route('layanan.store') }}" method="POST" id="tambahLayananForm" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan nama layanan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga</label>
                        <input type="text" name="harga" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan harga">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                        <input type="date" name="deadline" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Progres</label>
                        <input type="text" name="progres" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan progres">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih status</option>
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan deskripsi layanan"></textarea>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="batalTambahBtn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Layanan -->
    <div id="editLayananModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-4">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Layanan</h2>
            </div>
            <form method="POST" action="{{ route('layanan.update', '') }}" id="editLayananForm" class="p-6">
                 @csrf
                @method('PUT')
                <input type="hidden" id="editId" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Layanan</label>
                        <input type="text" id="editNamaLayanan" name="nama_layanan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan nama layanan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga</label>
                        <input type="text" id="editHarga" name="harga" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan harga">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                        <input type="date" id="editDeadline" name="deadline" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Progres</label>
                        <input type="text" id="editProgres" name="progres" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan progres">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="editStatus" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih status</option>
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:text-white" placeholder="Masukkan deskripsi layanan"></textarea>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="batalEditBtn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

<!-- Modal Delete Konfirmasi -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4">

        <div class="p-6">

            <!-- Icon Warning -->
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 dark:bg-red-900 rounded-full mb-4">
                <span class="material-icons-outlined text-red-600 dark:text-red-400 text-4xl">
                    warning
                </span>
            </div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-white text-center mb-2">
                Konfirmasi Hapus
            </h3>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
                Apakah Anda yakin ingin menghapus layanan ini?
            </p>

            <!-- FORM DELETE -->
            <form id="deleteForm" method="POST" class="flex justify-center space-x-6">
                @csrf
                @method('DELETE')

                <!-- Tombol Batal (ikon X) -->
                <button type="button" id="batalDeleteBtn"
                    class="flex items-center justify-center w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <span class="material-icons-outlined text-gray-700 dark:text-gray-300 text-3xl">
                        close
                    </span>
                </button>

                <!-- Tombol Hapus (ikon trash) -->
                <button type="submit" id="konfirmasiDeleteBtn"
                    class="flex items-center justify-center w-12 h-12 bg-red-600 rounded-full hover:bg-red-700 transition">
                    <span class="material-icons-outlined text-white text-3xl">
                        delete
                    </span>
                </button>
            </form>

        </div>

    </div>
</div>


    

<script>
    // Modal elements
    const tambahLayananModal = document.getElementById('tambahLayananModal');
    const editLayananModal = document.getElementById('editLayananModal');
    const deleteModal = document.getElementById('deleteModal');

    // Buttons
    const tambahLayananBtn = document.getElementById('tambahLayananBtn');
    const batalTambahBtn = document.getElementById('batalTambahBtn');
    const batalEditBtn = document.getElementById('batalEditBtn');
    const batalDeleteBtn = document.getElementById('batalDeleteBtn');

    // Forms
    const tambahLayananForm = document.getElementById('tambahLayananForm');
    const editLayananForm = document.getElementById('editLayananForm');

    // Open tambah modal
    tambahLayananBtn.addEventListener('click', () => {
        tambahLayananModal.classList.remove('hidden');
    });

    // Close tambah modal
    batalTambahBtn.addEventListener('click', () => {
        tambahLayananModal.classList.add('hidden');
        tambahLayananForm.reset();
    });

    // Close edit modal
    batalEditBtn.addEventListener('click', () => {
        editLayananModal.classList.add('hidden');
        editLayananForm.reset();
    });

    // Close delete modal
    batalDeleteBtn.addEventListener('click', () => {
        deleteModal.classList.add('hidden');
    });

    // ============================
    // HANDLE EDIT BUTTON
    // ============================
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {

        // SET VALUE
        document.getElementById('editId').value = button.dataset.id;
        document.getElementById('editNamaLayanan').value = button.dataset.nama;
        document.getElementById('editDeskripsi').value = button.dataset.deskripsi;
        document.getElementById('editHarga').value = button.dataset.harga;
        document.getElementById('editDeadline').value = button.dataset.deadline;
        document.getElementById('editProgres').value = button.dataset.progres;
        document.getElementById('editStatus').value = button.dataset.status;

        // ⬅️ SET ACTION URL DINAMIS
        editLayananForm.action = `/admin/data_layanan/${button.dataset.id}`;

        editLayananModal.classList.remove('hidden');
    });
});


    // ============================
    // HANDLE DELETE BUTTON
    // ============================
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.dataset.id;

        // Set ID ke hidden input (opsional untuk JS lain)
        document.getElementById('deleteId').value = id;

        // Set action URL form delete
        document.getElementById('deleteForm').action = `/admin/data_layanan/${id}`;

        deleteModal.classList.remove('hidden');
    });
});


    // ============================
    // SUBMIT FORM EDIT
    // (untuk Laravel biarkan form submit saja)
    // ============================
    editLayananForm.addEventListener('submit', (e) => {
        // Jangan prevent kalau mau kirim ke backend
        // e.preventDefault();
        // editLayananForm.submit();  // opsional

        editLayananModal.classList.add('hidden');
    });

</script>

</body>

</html>
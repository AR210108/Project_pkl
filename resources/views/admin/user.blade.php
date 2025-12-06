<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
    <div class="flex min-h-screen">

        <!-- SIDER -->
        <aside class="w-64 flex-shrink-0 bg-surface-light dark:bg-surface-dark flex flex-col p-6">
            @include('admin/templet/sider')
        </aside>

        <!-- MAIN -->
        <main class="flex-1 flex flex-col">
            <div class="flex-grow p-8">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white">Daftar User</h2>

                    <!-- TOMBOL ADD USER -->
                    <button onclick="openModalTambah()"
                        class="flex items-center gap-2 px-4 py-2 bg-slate-300 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg hover:bg-slate-400 dark:hover:bg-slate-600 transition-colors">
                        <span class="material-icons-outlined text-2xl">add</span>
                        tambah User
                    </button>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-200 dark:bg-slate-700 text-xs text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="p-4">No</th>
                                <th class="p-4">Username</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach ($users as $i => $u)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="p-4">{{ $i+1 }}.</td>
                                    <td class="p-4">{{ $u->name }}</td>
                                    <td class="p-4">{{ $u->email }}</td>
                                    <td class="p-4">{{ $u->role }}</td>
                                    <td class="p-4">
                                        <div class="flex gap-2">
                                            <button onclick="openModalEdit({{ $u->id }}, '{{ $u->name }}', '{{ $u->email }}', '{{ $u->role }}')"
                                                class="p-1 text-slate-500 hover:text-primary transition-colors">
                                                <span class="material-icons-outlined">edit</span>
                                            </button>

                                            <form action="{{ route('admin.user.delete', $u->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="p-1 text-slate-500 hover:text-red-500 transition-colors">
                                                    <span class="material-icons-outlined">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            <footer class="bg-slate-200 dark:bg-slate-800 text-center p-4 text-sm text-slate-500 dark:text-slate-400">
                Copyright ©2025 by digicity.id
            </footer>
        </main>

    </div>

    <!-- MODAL TAMBAH -->
    <div id="modalTambah" class="fixed inset-0 bg-black/40 hidden justify-center items-center z-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl w-[600px] max-h-[90vh] overflow-y-auto p-8">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold">Tambah User Baru</h3>
                <button onclick="closeModalTambah()" class="text-slate-500 hover:text-red-500">
                    <span class="material-icons-outlined text-xl">close</span>
                </button>
            </div>

            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label>Nama Lengkap</label>
                        <input name="name" required class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700" placeholder="Masukkan nama user">
                    </div>

                    <div>
                        <label>Email</label>
                        <input name="email" required type="email" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700" placeholder="Masukkan email">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label>Role</label>
                        <select name="role" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div>
                        <label>Password</label>
                        <input name="password" required type="password" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModalTambah()" class="px-6 py-2 rounded-lg bg-slate-300 dark:bg-slate-700">Batal</button>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Simpan Data</button>
                </div>
            </form>

        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modalEdit" class="fixed inset-0 bg-black/40 hidden justify-center items-center z-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-xl w-[600px] max-h-[90vh] overflow-y-auto p-8">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold">Edit User</h3>
                <button onclick="closeModalEdit()" class="text-slate-500 hover:text-red-500">
                    <span class="material-icons-outlined text-xl">close</span>
                </button>
            </div>

            <form id="formEdit" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label>Nama Lengkap</label>
                        <input id="edit_name" name="name" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700">
                    </div>

                    <div>
                        <label>Email</label>
                        <input id="edit_email" name="email" type="email" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label>Role</label>
                        <select id="edit_role" name="role" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div>
                        <label>Password (kosongkan jika tidak diubah)</label>
                        <input name="password" type="password" class="w-full px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModalEdit()" class="px-6 py-2 rounded-lg bg-slate-300 dark:bg-slate-700">Batal</button>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Update Data</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        // === MODAL TAMBAH ===
        const modalTambah = document.getElementById('modalTambah');
        function openModalTambah() {
            modalTambah.classList.remove('hidden');
            modalTambah.classList.add('flex');
        }
        function closeModalTambah() {
            modalTambah.classList.add('hidden');
            modalTambah.classList.remove('flex');
        }

        // === MODAL EDIT ===
        const modalEdit = document.getElementById('modalEdit');
        function openModalEdit(id, name, email, role) {
            modalEdit.classList.remove('hidden');
            modalEdit.classList.add('flex');

            document.getElementById("edit_name").value = name;
            document.getElementById("edit_email").value = email;
            document.getElementById("edit_role").value = role;

            document.getElementById("formEdit").action = "/admin/user/update/" + id;
        }
        function closeModalEdit() {
            modalEdit.classList.add('hidden');
            modalEdit.classList.remove('flex');
        }
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Karyawan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light-primary dark:text-dark-primary">
<div class="flex h-screen">
    @include('admin.templet.sider')

    <main class="flex-1 flex flex-col">
        <div class="flex-1 p-8 overflow-y-auto">

            <h2 class="text-3xl font-bold mb-8">Daftar karyawan</h2>

            <button id="tambahKaryawanBtn" class="bg-gray-200 px-4 py-2 rounded-lg mb-4">
            </button>

            <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Gaji</th>
            <th>Alamat</th>
            <th>Kontak</th>
        </tr>
    </thead>
    <tbody>
        @foreach($karyawan as $k)
            <tr>
                <td>{{ $k->id }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->jabatan }}</td>
                <td>{{ $k->gaji }}</td>
                <td>{{ $k->alamat }}</td>
                <td>{{ $k->kontak }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

        </div>
    </main>
</div>

<!-- ================= MODAL TAMBAH ================= -->
<div id="tambahKaryawanModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
    <form action="{{ route('karyawan.store') }}" method="POST" class="bg-white p-6 rounded w-96">
        @csrf

        <h3 class="font-bold mb-4">Tambah Karyawan</h3>

        <input name="nama" placeholder="Nama" class="border p-2 w-full mb-2">
        <input name="jabatan" placeholder="Jabatan" class="border p-2 w-full mb-2">
        <input name="gaji" placeholder="Gaji" class="border p-2 w-full mb-2">
        <input name="kontak" placeholder="Kontak" class="border p-2 w-full mb-2">
        <textarea name="alamat" placeholder="Alamat" class="border p-2 w-full mb-2"></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>

<!-- ================= MODAL EDIT ================= -->
<div id="editKaryawanModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
    <form id="editKaryawanForm" method="POST" class="bg-white p-6 rounded w-96">
        @csrf
        @method('PUT')

        <h3 class="font-bold mb-4">Edit Karyawan</h3>

        <input id="editNama" name="nama" class="border p-2 w-full mb-2">
        <input id="editJabatan" name="jabatan" class="border p-2 w-full mb-2">
        <input id="editGaji" name="gaji" class="border p-2 w-full mb-2">
        <input id="editKontak" name="kontak" class="border p-2 w-full mb-2">
        <textarea id="editAlamat" name="alamat" class="border p-2 w-full mb-2"></textarea>

        <button class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>

<script>
const tambahBtn = document.getElementById('tambahKaryawanBtn');
const tambahModal = document.getElementById('tambahKaryawanModal');
const editModal = document.getElementById('editKaryawanModal');
const editForm = document.getElementById('editKaryawanForm');

tambahBtn.addEventListener('click', () => {
    tambahModal.classList.remove('hidden');
});

document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        editForm.action = `/karyawan/${this.dataset.id}`;
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editJabatan').value = this.dataset.jabatan;
        document.getElementById('editGaji').value = this.dataset.gaji;
        document.getElementById('editKontak').value = this.dataset.kontak;
        document.getElementById('editAlamat').value = this.dataset.alamat;
        editModal.classList.remove('hidden');
    });
});
</script>

</body>
</html>

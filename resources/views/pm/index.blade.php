<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management Dashboard</title>
    <style>
        /* --- Gaya Umum & Font --- */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        :root {
            --sidebar-bg: #F0F0F0;
            --main-bg: #F4F6F9;
            --card-bg: #FFFFFF;
            --primary-color: #007BFF; /* Warna untuk link aktif, bisa disesuaikan */
            --text-color: #333333;
            --text-muted: #6C757D;
            --border-color: #E0E0E0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--main-bg);
            color: var(--text-color);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- Sidebar Navigasi --- */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .brand {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 40px;
            text-align: center;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 15px;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            padding: 12px 15px;
            border-radius: 8px;
            display: block;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-menu a:hover {
            background-color: #ddd;
        }

        .nav-menu a.active {
            background-color: var(--primary-color);
            color: white;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer; /* Menunjukkan bahwa elemen bisa diklik */
            text-align: center;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        /* --- Konten Utama --- */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto; /* Agar konten bisa di-scroll jika terlalu panjang */
        }

        /* --- Bagian Statistik --- */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: var(--card-bg);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .stat-card h3 {
            font-size: 16px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-color);
        }

        /* --- Bagian Deadline Terdekat --- */
        .deadlines-section h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .cards-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px; /* Ruang untuk scroll bar */
        }

        /* Gaya untuk scrollbar (Chrome, Safari) */
        .cards-container::-webkit-scrollbar {
            height: 8px;
        }

        .cards-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .cards-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .cards-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }


        .task-card {
            flex: 0 0 280px; /* Lebar kartu tetap */
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid var(--border-color);
        }

        .task-card h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .task-card .price {
            font-size: 16px;
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
        }

        .progress-bar-container {
            background-color: #e9ecef;
            border-radius: 5px;
            height: 8px;
            margin-bottom: 5px;
        }

        .progress-bar-fill {
            background-color: #28a745; /* Warna hijau untuk progress */
            height: 100%;
            border-radius: 5px;
            width: 90%; /* Sesuaikan dengan persentase */
        }

        .task-card .progress-text {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 15px;
        }

        .task-card .deadline {
            font-size: 14px;
            color: var(--text-muted);
        }

        .lihat-lainnya-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 8px;
            cursor: pointer; /* Menunjukkan bahwa elemen bisa diklik */
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }

        .lihat-lainnya-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Gaya untuk tombol yang sudah dinonaktifkan */
        .lihat-lainnya-btn:disabled {
            background-color: #ccc;
            color: #666;
            border-color: #ccc;
            cursor: not-allowed;
        }


        /* --- Footer --- */
        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            font-size: 14px;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
        }

    </style>
</head>
<body>

    <!-- Sidebar Navigasi -->
    <aside class="sidebar">
        <div>
            <div class="brand">Brand</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#" class="active">Beranda</a></li>
                    <li><a href="#">Kelola Tugas</a></li>
                </ul>
            </nav>
        </div>
        <button class="logout-btn" id="logoutButton">Log Out</button>
    </aside>

    <!-- Konten Utama Halaman -->
    <main class="main-content">
        <!-- Bagian Statistik Tugas -->
        <section class="stats-container">
            <div class="stat-card">
                <h3>Draf Tugas</h3>
                <p>3</p>
            </div>
            <div class="stat-card">
                <h3>Tugas Dikerjakan</h3>
                <p>2</p>
            </div>
            <div class="stat-card">
                <h3>Tugas Selesai</h3>
                <p>6</p>
            </div>
            <div class="stat-card">
                <h3>Total Tugas</h3>
                <p>10</p>
            </div>
        </section>

        <!-- Bagian Deadline Terdekat -->
        <section class="deadlines-section">
            <h2>Deadline Terdekat</h2>
            <div class="cards-container" id="cardsContainer">
                <!-- Kartu Tugas awal akan tetap ada di sini -->
                <div class="task-card">
                    <h4>Website Pendidikan</h4>
                    <p class="price">Rp. 1,000,000</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: 90%;"></div>
                    </div>
                    <p class="progress-text">Progress: 90%</p>
                    <p class="deadline">Deadline: 10 Desember 2025</p>
                </div>
                <div class="task-card">
                    <h4>Website Pendidikan</h4>
                    <p class="price">Rp. 1,000,000</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: 90%;"></div>
                    </div>
                    <p class="progress-text">Progress: 90%</p>
                    <p class="deadline">Deadline: 15 Desember 2025</p>
                </div>
                
                
            </div>
            <button class="lihat-lainnya-btn" id="lihatLainnyaBtn">Lihat Lainnya</button>
        </section>

        <!-- Footer -->
        <footer>
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </main>

    <!-- Kode JavaScript untuk Interaktivitas -->
    <script>
        // --- Fungsionalitas Tombol "Lihat Lainnya" ---
        // 1. Pilih elemen tombol dan container kartu
        const lihatLainnyaBtn = document.getElementById('lihatLainnyaBtn');
        const cardsContainer = document.getElementById('cardsContainer');

        // 2. Tambahkan event listener untuk aksi klik
        lihatLainnyaBtn.addEventListener('click', function() {
            // Data untuk kartu baru (bisa diganti dengan data lain)
            const newTasks = [
                { deadline: '28 Desember 2025' },
                { deadline: '30 Desember 2025' }
            ];

            // 3. Buat dan tambahkan kartu baru ke dalam container
            newTasks.forEach(task => {
                const cardHTML = `
                    <div class="task-card">
                        <h4>Website Pendidikan</h4>
                        <p class="price">Rp. 1,000,000</p>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: 90%;"></div>
                        </div>
                        <p class="progress-text">Progress: 90%</p>
                        <p class="deadline">Deadline: ${task.deadline}</p>
                    </div>
                `;
                // Tambahkan kartu baru di akhir container
                cardsContainer.insertAdjacentHTML('beforeend', cardHTML);
            });

            // 4. Nonaktifkan tombol setelah diklik dan ubah teksnya
            lihatLainnyaBtn.disabled = true;
            lihatLainnyaBtn.textContent = 'Semua Tugas Ditampilkan';
        });


        // --- Fungsionalitas Tombol "Log Out" ---
        // 1. Pilih elemen tombol log out
        const logoutButton = document.getElementById('logoutButton');

        // 2. Tambahkan event listener untuk aksi klik
        logoutButton.addEventListener('click', function() {
            // Tampilkan jendela konfirmasi
            const isConfirmed = confirm('Apakah Anda yakin ingin keluar?');

            // Jika pengguna menekan "OK"
            if (isConfirmed) {
                alert('Anda telah berhasil keluar.');
                // Di aplikasi asli, Anda akan mengarahkan pengguna ke halaman login
                // contoh: window.location.href = 'login.html';
            }
        });

    </script>

</body>
</html>
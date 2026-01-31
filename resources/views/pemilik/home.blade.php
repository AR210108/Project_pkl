<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Digital Agency Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0f172a",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                        "card-light": "#111827",
                        "card-dark": "#1f2937",
                        "text-light": "#111827",
                        "text-dark": "#f9fafb",
                        "subtext-light": "#6b7280",
                        "subtext-dark": "#d1d5db",
                        "border-light": "#e5e7eb",
                        "border-dark": "#4b5563",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "1rem",
                    },
                },
            },
        };
    </script>
    <style>
        /* Custom styles for improved appearance */
        .gradient-primary {
            background: linear-gradient(135deg, #000000, #111827);
        }

        .gradient-dark {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }

        .gradient-subtle {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }

        /* Button hover effects - DIUBAH */
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        /* Menghapus efek hover pada tombol OWNERS */
        .btn-no-hover {
            transition: none !important;
            background-color: white !important;
            color: black !important;
        }

        .btn-no-hover:hover {
            background-color: white !important;
            color: black !important;
            transform: none !important;
        }

        .btn-no-hover:before {
            display: none !important;
        }

        /* Card hover effects */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }

        /* Modal Styles - Simplified */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 0;
            border: none;
            width: 90%;
            max-width: 500px; /* Diperkecil karena konten lebih sederhana */
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: modalopen 0.3s;
        }

        @keyframes modalopen {
            from {opacity: 0; transform: scale(0.8);}
            to {opacity: 1; transform: scale(1);}
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            padding: 8px 16px;
        }

        .close-modal:hover,
        .close-modal:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Loading animation */
        .loading-dots {
            display: inline-block;
        }

        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        /* Chart Container Styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Card improvements */
        .stat-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .stat-card-content {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>

<body class="font-display bg-background-light text-text-light">
    <div class="container mx-auto p-4 md:p-8">
        <!-- Include header template -->
        @include('pemilik/template/header')

        <main class="space-y-6 md:space-y-8">
           <section class="gradient-primary rounded-2xl shadow-lg relative overflow-hidden p-6 md:p-8 lg:p-12">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>

                <div class="max-w-4xl mx-auto relative z-10">
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4">HALLO, <span id="ownerName" class="loading-dots">Memuat</span>
                    </h2>
                    <p class="text-sm md:text-base text-white/90 mb-6 md:mb-8">
                        Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau
                                jasanya
                                secara online melalui berbagai layanan digital.
                    </p>
                    <!-- TOMBOL OWNERS TANPA EFEK HOVER -->
                    <a href="/karyawan/absensi"
                        class="btn-no-hover bg-white text-black px-6 py-2 md:px-8 md:py-3 rounded-lg font-semibold shadow-lg inline-block text-sm md:text-base cursor-pointer">
                        OWNERS
                    </a>
                </div>
            </section>

            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
                <!-- Kehadiran Karyawan Card -->
                <div id="attendance-card-trigger" class="card-hover stat-card bg-card-light p-4 rounded-lg shadow-sm cursor-pointer">
                    <div class="stat-card-content">
                        <div class="flex items-start space-x-4 mb-3">
                            <div class="bg-gray-700 p-3 rounded-md flex-shrink-0">
                                <span class="material-icons text-white">groups</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-xs text-gray-400 mb-1">Kehadiran Karyawan</p>
                            <p id="attendancePercentage" class="text-xl md:text-2xl font-bold text-white loading-dots">Memuat</p>
                        </div>
                    </div>
                </div>

                <!-- JUMLAH LAYANAN CARD -->
                <div class="card-hover stat-card bg-card-light p-4 rounded-lg shadow-sm">
                    <div class="stat-card-content">
                        <div class="flex items-start space-x-4 mb-3">
                            <div class="bg-gray-700 p-3 rounded-md flex-shrink-0">
                                <span class="material-icons text-white">design_services</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-xs text-gray-400 mb-1">Jumlah Layanan</p>
                            <p id="serviceCount" class="text-xl md:text-2xl font-bold text-white loading-dots">Memuat</p>
                        </div>
                    </div>
                </div>

                <!-- TOTAL PEMASUKAN CARD -->
                <div class="card-hover stat-card bg-card-light p-4 rounded-lg shadow-sm">
                    <div class="stat-card-content">
                        <div class="flex items-start space-x-4 mb-3">
                            <div class="bg-gray-700 p-3 rounded-md flex-shrink-0">
                                <span class="material-icons text-white">arrow_downward</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-xs text-gray-400 mb-1">Total Pemasukan</p>
                            <p id="totalIncome" class="text-lg md:text-xl font-bold text-white loading-dots">Memuat</p>
                        </div>
                    </div>
                </div>

                <!-- TOTAL PENGELUARAN CARD -->
                <div class="card-hover stat-card bg-card-light p-4 rounded-lg shadow-sm">
                    <div class="stat-card-content">
                        <div class="flex items-start space-x-4 mb-3">
                            <div class="bg-gray-700 p-3 rounded-md flex-shrink-0">
                                <span class="material-icons text-white">arrow_upward</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-xs text-gray-400 mb-1">Total Pengeluaran</p>
                            <p id="totalExpense" class="text-lg md:text-xl font-bold text-white loading-dots">Memuat</p>
                        </div>
                    </div>
                </div>

                <!-- TOTAL KEUNTUNGAN CARD -->
                <div class="card-hover stat-card bg-card-light p-4 rounded-lg shadow-sm">
                    <div class="stat-card-content">
                        <div class="flex items-start space-x-4 mb-3">
                            <div class="bg-gray-700 p-3 rounded-md flex-shrink-0">
                                <span class="material-icons text-white">account_balance_wallet</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-xs text-gray-400 mb-1">Total Keuntungan</p>
                            <p id="totalProfit" class="text-lg md:text-xl font-bold text-white loading-dots">Memuat</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="gradient-subtle p-4 md:p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-black">Grafik Keuangan</h3>
                    <div class="flex items-center gap-2">
                        <select id="chart-filter" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm">
                            <option value="week">Minggu Ini</option>
                            <option value="month" selected>Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                        </select>
                        <button class="bg-gray-200 p-2 rounded-full text-black hover:bg-gray-300 transition-colors">
                            <span class="material-icons">open_in_new</span>
                        </button>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="chart-container">
                    <canvas id="financeChart"></canvas>
                </div>
            </section>
        </main>

        <footer class="mt-8 md:mt-12 gradient-dark text-center py-3 md:py-4 rounded-lg shadow-sm">
            <p class="text-xs md:text-sm text-gray-700">Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <!-- Modal Kehadiran Per Divisi -->
    <div id="attendanceModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Kehadiran Per Divisi</h3>
                <div id="modal-body-content">
                    <!-- Konten akan diisi oleh JavaScript -->
                    <p class="text-center text-gray-500">Klik untuk melihat detail</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalPemasukan = $totalPemasukan ?? 0;
        $totalPengeluaran = $totalPengeluaran ?? 0;
        $totalKeuntungan = $totalKeuntungan ?? 0;
        $jumlahLayanan = $jumlahLayanan ?? 0;
    @endphp

  <script>
    // Data dari controller
    const financeData = @json($financeData);
    const totalPemasukan = @json($totalPemasukan);
    const totalPengeluaran = @json($totalPengeluaran);
    const totalKeuntungan = @json($totalKeuntungan);
    const jumlahLayanan = @json($jumlahLayanan);

    document.addEventListener('DOMContentLoaded', function() {
        // Function to fetch owner data
        async function fetchOwnerData() {
            try {
                // Fetch owner data dari API
                const response = await fetch('/api/owner/data');
                const result = await response.json();

                if (result.success) {
                    // Update nama owner di dashboard
                    const ownerNameElement = document.getElementById('ownerName');
                    if (ownerNameElement) {
                        // Hapus class loading-dots dan tambahkan class fade-in
                        ownerNameElement.classList.remove('loading-dots');
                        ownerNameElement.classList.add('fade-in');
                        ownerNameElement.textContent = result.data.name;
                    }
                } else {
                    // Tampilkan pesan error jika API gagal
                    const ownerNameElement = document.getElementById('ownerName');
                    if (ownerNameElement) {
                        ownerNameElement.classList.remove('loading-dots');
                        ownerNameElement.textContent = 'Owner';
                    }
                    console.error('Gagal memuat data owner:', result.message);
                }
            } catch (error) {
                console.error('Terjadi kesalahan saat mengambil data owner:', error);
                // Fallback jika terjadi error
                const ownerNameElement = document.getElementById('ownerName');
                if (ownerNameElement) {
                    ownerNameElement.classList.remove('loading-dots');
                    ownerNameElement.textContent = 'Owner';
                }
            }
        }

        // Function to fetch service count from database
        async function fetchServiceCount() {
            try {
                // Fetch jumlah layanan dari API
                const response = await fetch('/api/services/count');
                const result = await response.json();

                if (result.success) {
                    // Update jumlah layanan di dashboard
                    const serviceCountElement = document.getElementById('serviceCount');
                    if (serviceCountElement) {
                        serviceCountElement.classList.remove('loading-dots');
                        serviceCountElement.classList.add('fade-in');
                        serviceCountElement.textContent = result.data.count;
                    }
                } else {
                    // Tampilkan pesan error jika API gagal
                    const serviceCountElement = document.getElementById('serviceCount');
                    if (serviceCountElement) {
                        serviceCountElement.classList.remove('loading-dots');
                        serviceCountElement.textContent = '0';
                    }
                    console.error('Gagal memuat data layanan:', result.message);
                }
            } catch (error) {
                console.error('Terjadi kesalahan saat mengambil data layanan:', error);
                // Fallback jika terjadi error
                const serviceCountElement = document.getElementById('serviceCount');
                if (serviceCountElement) {
                    serviceCountElement.classList.remove('loading-dots');
                    serviceCountElement.textContent = '0';
                }
            }
        }

        // Function to fetch dashboard data
        async function fetchDashboardData() {
            try {
                // Fetch attendance percentage dari API yang sama dengan popup
                const attendanceResponse = await fetch('/api/kehadiran-per-divisi');
                const attendanceResult = await attendanceResponse.json();

                if (attendanceResult.success) {
                    // Update persentase kehadiran di dashboard
                    const percentage = attendanceResult.data.overall_percentage;
                    const percentageElement = document.getElementById('attendancePercentage');
                    if (percentageElement) {
                        percentageElement.classList.remove('loading-dots');
                        percentageElement.classList.add('fade-in');
                        percentageElement.textContent = percentage + '%';
                    }
                } else {
                    // Tampilkan pesan error jika API gagal
                    const percentageElement = document.getElementById('attendancePercentage');
                    if (percentageElement) {
                        percentageElement.classList.remove('loading-dots');
                        percentageElement.textContent = 'Error';
                    }
                    console.error('Gagal memuat data kehadiran:', attendanceResult.message);
                }

                // Fetch data dashboard lainnya (keuangan, dll)
                updateDashboardStats();
                initializeChart('month');

            } catch (error) {
                console.error('Terjadi kesalahan saat mengambil data dashboard:', error);
                // Fallback jika terjadi error
                const percentageElement = document.getElementById('attendancePercentage');
                if (percentageElement) {
                    percentageElement.classList.remove('loading-dots');
                    percentageElement.textContent = '0%';
                }
                updateDashboardStats();
                initializeChart();
            }
        }

        // Function to format currency - DIPERBAIKI
        function formatCurrency(amount) {
            // Convert to number if it's a string
            const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
            // Format with no decimal places for Indonesian Rupiah
            return 'Rp ' + numAmount.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Function to update dashboard stats
        function updateDashboardStats() {
            // Update financial data dari controller
            const incomeElement = document.getElementById('totalIncome');
            if (incomeElement) {
                incomeElement.classList.remove('loading-dots');
                incomeElement.classList.add('fade-in');
                incomeElement.textContent = formatCurrency(totalPemasukan);
            }

            const expenseElement = document.getElementById('totalExpense');
            if (expenseElement) {
                expenseElement.classList.remove('loading-dots');
                expenseElement.classList.add('fade-in');
                expenseElement.textContent = formatCurrency(totalPengeluaran);
            }

            const profitElement = document.getElementById('totalProfit');
            if (profitElement) {
                profitElement.classList.remove('loading-dots');
                profitElement.classList.add('fade-in');
                profitElement.textContent = formatCurrency(totalKeuntungan);
            }

            const serviceElement = document.getElementById('serviceCount');
            if (serviceElement) {
                serviceElement.classList.remove('loading-dots');
                serviceElement.classList.add('fade-in');
                serviceElement.textContent = jumlahLayanan;
            }
        }

        // Function to get chart data based on filter
        function getChartData(filterType) {
            const now = new Date();
            let labels = [];
            let pemasukanData = [];
            let pengeluaranData = [];

            if (filterType === 'week') {
                // Minggu ini: Senin sampai Minggu
                labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                pemasukanData = new Array(7).fill(0);
                pengeluaranData = new Array(7).fill(0);

                // Hitung start dan end of week
                const startOfWeek = new Date(now);
                startOfWeek.setDate(now.getDate() - now.getDay() + 1); // Senin
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6); // Minggu

                financeData.forEach(item => {
                    const date = new Date(item.tanggal_transaksi);
                    if (date >= startOfWeek && date <= endOfWeek) {
                        const dayIndex = date.getDay(); // 0 = Minggu
                        let index;
                        if (dayIndex === 0) index = 6; // Minggu ke index 6
                        else index = dayIndex - 1; // Senin ke 0
                        if (item.tipe_transaksi === 'pemasukan') pemasukanData[index] += parseFloat(item.jumlah);
                        else if (item.tipe_transaksi === 'pengeluaran') pengeluaranData[index] += parseFloat(item.jumlah);
                    }
                });
            } else if (filterType === 'month') {
                // Bulan ini: Minggu 1 sampai 4
                const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                const totalWeeks = Math.ceil((endOfMonth.getDate() - startOfMonth.getDate() + startOfMonth.getDay() + 1) / 7);
                labels = Array.from({length: totalWeeks}, (_, i) => `Minggu ${i + 1}`);
                pemasukanData = new Array(totalWeeks).fill(0);
                pengeluaranData = new Array(totalWeeks).fill(0);

                financeData.forEach(item => {
                    const date = new Date(item.tanggal_transaksi);
                    if (date.getFullYear() === now.getFullYear() && date.getMonth() === now.getMonth()) {
                        const dayOfMonth = date.getDate();
                        const weekIndex = Math.floor((dayOfMonth - 1 + startOfMonth.getDay()) / 7);
                        if (item.tipe_transaksi === 'pemasukan') pemasukanData[weekIndex] += parseFloat(item.jumlah);
                        else if (item.tipe_transaksi === 'pengeluaran') pengeluaranData[weekIndex] += parseFloat(item.jumlah);
                    }
                });
            } else if (filterType === 'year') {
                // Tahun ini: Jan sampai Des
                labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                pemasukanData = new Array(12).fill(0);
                pengeluaranData = new Array(12).fill(0);

                financeData.forEach(item => {
                    const date = new Date(item.tanggal_transaksi);
                    if (date.getFullYear() === now.getFullYear()) {
                        const monthIndex = date.getMonth();
                        if (item.tipe_transaksi === 'pemasukan') pemasukanData[monthIndex] += parseFloat(item.jumlah);
                        else if (item.tipe_transaksi === 'pengeluaran') pengeluaranData[monthIndex] += parseFloat(item.jumlah);
                    }
                });
            }

            return { labels, pemasukanData, pengeluaranData };
        }

        // Function to initialize chart with Chart.js
        let financeChart = null;
        function initializeChart(filterType = 'month') {
            const ctx = document.getElementById('financeChart').getContext('2d');
            const { labels, pemasukanData, pengeluaranData } = getChartData(filterType);

            if (financeChart) {
                financeChart.destroy();
            }

            const data = {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan',
                    data: pemasukanData,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }, {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    // Format currency in tooltip with proper Indonesian formatting
                                    return context.dataset.label + ': ' + formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Periode'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    // Format currency in y-axis
                                    return formatCurrency(value);
                                }
                            },
                            title: {
                                display: true,
                                text: 'Jumlah (Rp)'
                            }
                        }
                    }
                }
            };

            financeChart = new Chart(ctx, config);
        }

        // --- Logika untuk Modal Kehadiran Per Divisi ---

        const attendanceModal = document.getElementById('attendanceModal');
        const attendanceCardTrigger = document.getElementById('attendance-card-trigger');
        const modalBodyContent = document.getElementById('modal-body-content');
        const closeModalBtn = document.querySelector('.close-modal');

        // Event listener untuk membuka modal saat kartu diklik
        if (attendanceCardTrigger) {
            attendanceCardTrigger.addEventListener('click', function() {
                attendanceModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                fetchAttendancePerDivision();
            });
        }

        // Event listener untuk menutup modal
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        // Menutup modal saat klik di luar konten modal
        window.addEventListener('click', function(event) {
            if (event.target === attendanceModal) {
                closeModal();
            }
        });

        function closeModal() {
            if (attendanceModal) {
                attendanceModal.style.display = 'none';
            }
            document.body.style.overflow = 'auto';
        }

        /**
         * Fungsi untuk mengambil data kehadiran per divisi dari server
         */
        async function fetchAttendancePerDivision() {
            try {
                // Tampilkan teks sederhana saat memuat
                modalBodyContent.innerHTML = `<p class="text-center text-gray-500">Memuat data<span class="loading-dots"></span></p>`;

                const response = await fetch('/api/kehadiran-per-divisi');
                const result = await response.json();

                if (result.success) {
                    renderModalContent(result.data);
                } else {
                    throw new Error(result.message || 'Gagal memuat data.');
                }
            } catch (error) {
                console.error('Error fetching attendance per division:', error);
                modalBodyContent.innerHTML = `<p class="text-center text-red-500">Gagal memuat data. Silakan coba lagi.</p>`;
            }
        }

        /**
         * Fungsi untuk merender konten modal
         */
        function renderModalContent(data) {
            const { overall_percentage, divisions } = data;

            let html = `
                <div class="mb-6 p-4 bg-gray-100 rounded-lg text-center">
                    <p class="text-sm text-gray-600">Total Persentase Kehadiran</p>
                    <p class="text-3xl font-bold text-green-600">${overall_percentage}%</p>
                </div>
                <div class="space-y-3">
            `;

            if (divisions.length > 0) {
                divisions.forEach(div => {
                    html += `
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <p class="font-semibold text-gray-800">${div.division}</p>
                            <p class="font-bold text-lg text-gray-700">${div.percentage}%</p>
                        </div>
                    `;
                });
            } else {
                html += `<p class="text-center text-gray-500">Belum ada data kehadiran.</p>`;
            }

            html += `</div>`;
            modalBodyContent.innerHTML = html;
        }

        // Initialize dashboard
        fetchOwnerData();
        fetchServiceCount(); // Tambahkan pemanggilan fungsi untuk jumlah layanan
        fetchDashboardData();

        // Event listener for chart filter
        document.getElementById('chart-filter').addEventListener('change', function() {
            const filterType = this.value;
            initializeChart(filterType);
        });
    });
</script>
</body>

</html>

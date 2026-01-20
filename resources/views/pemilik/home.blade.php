<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Digital Agency Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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
        
        /* Button hover effects */
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-primary:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #000000, #111827);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease;
        }
        
        .btn-primary:hover:before {
            opacity: 1;
        }
        
        /* Card hover effects */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Chart bar animation */
        .chart-bar {
            transition: height 0.5s ease-in-out;
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
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4">HALLO, <span id="ownerName">NAMA OWNERS</span>
                    </h2>
                    <p class="text-sm md:text-base text-white/90 mb-6 md:mb-8">
                        Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau
                                jasanya
                                secara online melalui berbagai layanan digital.
                    </p>
                    <a href="/karyawan/absensi"
                        class="btn-primary bg-white text-black px-6 py-2 md:px-8 md:py-3 rounded-lg font-semibold hover:bg-gray-100 transition-transform transform hover:scale-105 shadow-lg inline-block text-sm md:text-base">
                        OWNERS
                    </a>
                </div>
            </section>
            
            <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
               <!-- Kehadiran Karyawan Card - VERSI FINAL YANG BENAR -->
<div id="attendance-card-trigger" class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4 cursor-pointer">
    <div class="bg-gray-700 p-3 rounded-md">
        <span class="material-icons text-white">groups</span>
    </div>
    <div>
        <p class="text-xs text-gray-400">Kehadiran Karyawan</p>
        <!-- PENTING: Elemen ini memiliki ID yang akan diisi oleh JavaScript -->
        <p id="attendancePercentage" class="text-xl md:text-2xl font-bold text-white">Memuat...</p>
    </div>
</div>
                
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">design_services</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Jumlah Layanan</p>
                        <p id="serviceCount" class="text-xl md:text-2xl font-bold text-white">10</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">arrow_downward</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pemasukan</p>
                        <p id="totalIncome" class="text-lg md:text-xl font-bold text-white">1.000.000</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">arrow_upward</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pengeluaran</p>
                        <p id="totalExpense" class="text-lg md:text-xl font-bold text-white">500.000</p>
                    </div>
                </div>
                <div class="card-hover bg-card-light p-4 rounded-lg shadow-sm flex items-start space-x-4">
                    <div class="bg-gray-700 p-3 rounded-md">
                        <span class="material-icons text-white">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Keuntungan</p>
                        <p id="totalProfit" class="text-lg md:text-xl font-bold text-white">500.000</p>
                    </div>
                </div>
            </section>
            
            <section class="gradient-subtle p-4 md:p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="text-lg md:text-xl font-bold text-black">Grafik Keuangan</h3>
                    <button
                        class="bg-gray-200 p-2 rounded-full text-black hover:bg-gray-300 transition-colors">
                        <span class="material-icons">open_in_new</span>
                    </button>
                </div>
                
                <!-- Grafik untuk Desktop (Vertikal) -->
                <div class="hidden md:block">
                    <div class="flex items-end h-64 space-x-4">
                        <div class="flex flex-col justify-between h-full text-xs text-gray-600 pr-2 border-r border-gray-300">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div id="chartContainer" class="w-full h-full flex items-end justify-around">
                            <!-- Chart bars will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Grafik untuk Mobile (Sama seperti Desktop, hanya lebih kecil) -->
                <div class="md:hidden overflow-x-auto pb-4">
                    <div class="flex items-end h-48 min-w-max">
                        <div class="flex flex-col justify-between h-full text-xs text-gray-600 pr-2 border-r border-gray-300">
                            <span>10k</span>
                            <span>8k</span>
                            <span>4k</span>
                            <span>2k</span>
                            <span>0</span>
                        </div>
                        <div id="chartContainerMobile" class="w-full h-full flex items-end justify-around px-2">
                            <!-- Chart bars will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Indikator scroll untuk mobile -->
                <div class="md:hidden text-center text-xs text-gray-600 mt-2">
                    <span class="material-icons text-sm">swipe</span> Geser untuk melihat grafik lengkap
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to fetch dashboard data
        async function fetchDashboardData() {
            try {
                // Fetch attendance percentage dari API yang sama dengan popup
                // Ini memastikan data di dashboard dan popup SELALU SINKRON
                const attendanceResponse = await fetch('/api/kehadiran-per-divisi');
                const attendanceResult = await attendanceResponse.json();
                
                if (attendanceResult.success) {
                    // Update persentase kehadiran di dashboard
                    const percentage = attendanceResult.data.overall_percentage;
                    const percentageElement = document.getElementById('attendancePercentage');
                    if (percentageElement) {
                        percentageElement.textContent = percentage + '%';
                    }
                } else {
                    // Tampilkan pesan error jika API gagal
                    const percentageElement = document.getElementById('attendancePercentage');
                    if (percentageElement) {
                        percentageElement.textContent = 'Error';
                    }
                    console.error('Gagal memuat data kehadiran:', attendanceResult.message);
                }
                
                // Fetch data dashboard lainnya (layanan, keuangan, dll)
                updateDashboardStats();
                initializeChart();
                
            } catch (error) {
                console.error('Terjadi kesalahan saat mengambil data dashboard:', error);
                // Fallback jika terjadi error
                const percentageElement = document.getElementById('attendancePercentage');
                if (percentageElement) {
                    percentageElement.textContent = '0%';
                }
                updateDashboardStats();
                initializeChart();
            }
        }
        
        // Function to update dashboard stats (using mock data for now)
        function updateDashboardStats() {
            // Update service count
            const serviceCountElement = document.getElementById('serviceCount');
            if (serviceCountElement) {
                serviceCountElement.textContent = '10';
            }
            
            // Update financial data
            const income = 1000000;
            const expense = 500000;
            const profit = income - expense;
            
            const incomeElement = document.getElementById('totalIncome');
            if (incomeElement) {
                incomeElement.textContent = formatCurrency(income);
            }

            const expenseElement = document.getElementById('totalExpense');
            if (expenseElement) {
                expenseElement.textContent = formatCurrency(expense);
            }

            const profitElement = document.getElementById('totalProfit');
            if (profitElement) {
                profitElement.textContent = formatCurrency(profit);
            }
        }
        
        // Function to format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }
        
        // Function to initialize chart with data
        function initializeChart() {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = [8000, 5000, 9500, 8200, 8000, 2500, 2000, 4000, 2800, 8300, 8800, 4500];
            const maxValue = Math.max(...data);
            
            // Create chart for desktop
            const chartContainer = document.getElementById('chartContainer');
            if (chartContainer) {
                chartContainer.innerHTML = '';
                
                months.forEach((month, index) => {
                    const barHeight = (data[index] / maxValue) * 100;
                    const barContainer = document.createElement('div');
                    barContainer.className = 'flex flex-col items-center w-1/12';
                    
                    const bar = document.createElement('div');
                    bar.className = 'chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md';
                    bar.style.height = '0%';
                    
                    const label = document.createElement('span');
                    label.className = 'text-xs mt-2 text-gray-600';
                    label.textContent = month;
                    
                    barContainer.appendChild(bar);
                    barContainer.appendChild(label);
                    chartContainer.appendChild(barContainer);
                    
                    // Animate bar after a short delay
                    setTimeout(() => {
                        bar.style.height = barHeight + '%';
                    }, 100 + (index * 50));
                });
            }
            
            // Create chart for mobile
            const chartContainerMobile = document.getElementById('chartContainerMobile');
            if (chartContainerMobile) {
                chartContainerMobile.innerHTML = '';
                
                months.forEach((month, index) => {
                    const barHeight = (data[index] / maxValue) * 100;
                    const barContainer = document.createElement('div');
                    barContainer.className = 'flex flex-col items-center w-12';
                    
                    const bar = document.createElement('div');
                    bar.className = 'chart-bar w-full bg-gradient-to-t from-black to-gray-700 rounded-t-md';
                    bar.style.height = '0%';
                    
                    const label = document.createElement('span');
                    label.className = 'text-xs mt-2 text-gray-600';
                    label.textContent = month;
                    
                    barContainer.appendChild(bar);
                    barContainer.appendChild(label);
                    chartContainerMobile.appendChild(barContainer);
                    
                    // Animate bar after a short delay
                    setTimeout(() => {
                        bar.style.height = barHeight + '%';
                    }, 100 + (index * 50));
                });
            }
        }
        
        // Function to get owner name (you can modify this to get from session or API)
        function getOwnerName() {
            // This should be replaced with actual data from your backend
            return 'Owner Name';
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
                modalBodyContent.innerHTML = `<p class="text-center text-gray-500">Memuat data...</p>`;

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
         * Fungsi untuk merender konten modal (DISEDERHANA)
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
        fetchDashboardData();
        
        const ownerNameElement = document.getElementById('ownerName');
        if (ownerNameElement) {
            ownerNameElement.textContent = getOwnerName();
        }
    });
</script>
</body>

</html>
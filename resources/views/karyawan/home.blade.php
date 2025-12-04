<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Employee Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#f3f4f6",
                        "background-dark": "#111827",
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
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="flex flex-col min-h-screen p-4 sm:p-6 lg:p-8">
        @include('karyawan.templet.header')

        <main class="flex-grow my-8">
            <section class="bg-white dark:bg-gray-800 rounded-lg p-8 sm:p-12 lg:p-16 shadow-sm">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">HALLO, 
                        <span id="employee-name" class="text-primary">{{ Auth::user()->name ?? 'Karyawan' }}</span>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        Bisnis digital agency adalah perusahaan yang membantu bisnis lain memasarkan produk atau jasanya
                        secara online melalui berbagai layanan digital.
                    </p>
                    <a href="/karyawan/absensi"
                        class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-transform transform hover:scale-105 shadow-lg inline-block">
                        Absen Karyawan
                    </a>
                </div>
            </section>
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-blue-100 dark:bg-blue-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Status Absensi</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="attendance-status">Memuat...</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-green-100 dark:bg-green-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-green-500">schedule</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jam Kerja</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">09:00 - 17:00</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-yellow-100 dark:bg-yellow-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-yellow-500">event_busy</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Ketidakhadiran</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="ketidakhadiran-count">{{ $ketidakhadiran_count ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div
                        class="bg-purple-100 dark:bg-purple-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <!-- PERUBAHAN: Ikon diganti menjadi 'assignment' -->
                        <span class="material-symbols-outlined text-purple-500">assignment</span>
                    </div>
                    <div>
                        <!-- PERUBAHAN: Label diubah -->
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Tugas</p>
                        <!-- PERUBAHAN: ID dan variabel diubah -->
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="tugas-count">{{ $tugas_count ?? 0 }}</p>
                    </div>
                </div>
            </section>
        </main>
        <footer
            class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center text-gray-600 dark:text-gray-400 text-sm shadow-sm">
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu) {
                    mobileMenu.classList.toggle('hidden');
                }
            });
        }

        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');

            if (mobileMenu && mobileMenuButton && !mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        function formatAttendanceStatus(status) {
            if (status === 'Belum Absen') {
                return 'Belum Absen';
            } else if (status === 'Tepat Waktu') {
                return '<span class="text-green-500">Tepat Waktu</span>';
            } else if (status === 'Terlambat') {
                return '<span class="text-red-500">Terlambat</span>';
            } else if (status === 'Sakit' || status === 'Izin' || status === 'Dinas Luar') {
                return '<span class="text-yellow-500">' + status + '</span>';
            } else {
                return status;
            }
        }

        async function apiFetch(endpoint, options = {}) {
            const cacheBuster = `_t=${Date.now()}`;
            const url = `/api/karyawan${endpoint}${endpoint.includes('?') ? '&' : '?'}${cacheBuster}`;
            
            const defaultOptions = { 
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': window.csrfToken 
                } 
            };
            const finalOptions = { ...defaultOptions, ...options };
            const response = await fetch(url, finalOptions);
            
            if (response.status === 419) throw new Error('CSRF token mismatch. Silakan muat ulang halaman.');
            if (!response.ok) { 
                const errorData = await response.json().catch(() => ({})); 
                throw new Error(errorData.message || 'Something went wrong'); 
            }
            return response.json();
        }

        async function fetchDashboardData() {
            try {
                const data = await apiFetch('/dashboard-data');
                
                if (data.attendance_status) {
                    const statusElement = document.getElementById('attendance-status');
                    if(statusElement) statusElement.innerHTML = formatAttendanceStatus(data.attendance_status);
                }
                
                // PERUBAHAN: Update elemen ketidakhadiran
                if (data.ketidakhadiran_count !== undefined) {
                    const ketidakhadiranElement = document.getElementById('ketidakhadiran-count');
                    if(ketidakhadiranElement) ketidakhadiranElement.textContent = data.ketidakhadiran_count;
                }
                
                // PERUBAHAN: Update elemen tugas
                if (data.tugas_count !== undefined) {
                    const tugasElement = document.getElementById('tugas-count');
                    if(tugasElement) tugasElement.textContent = data.tugas_count;
                }
                
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchDashboardData();
        });
    </script>
</body>
</html>
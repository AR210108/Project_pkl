<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Employee Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="attendance-status">{{ $attendanceStatus ?? 'Belum Absen' }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="bg-green-100 dark:bg-green-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-green-500">schedule</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jam Kerja</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">09:00 - 17:00</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="bg-yellow-100 dark:bg-yellow-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-yellow-500">description</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Cuti</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="leave-count">{{ $leaveCount ?? 3 }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="bg-purple-100 dark:bg-purple-900/50 w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-purple-500">checklist</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Tugas</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="task-count">{{ $taskCount ?? 5 }}</p>
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
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');

            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Jika perlu update data secara dinamis setelah halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data tambahan jika diperlukan
            fetchEmployeeData();
        });

        function fetchEmployeeData() {
            // Ambil data karyawan dari API endpoint
            fetch('/api/karyawan/profile')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update UI dengan data dari database
                    if (data.name) {
                        document.getElementById('employee-name').textContent = data.name;
                    }
                    if (data.attendance_status) {
                        document.getElementById('attendance-status').textContent = data.attendance_status;
                    }
                    if (data.leave_count !== undefined) {
                        document.getElementById('leave-count').textContent = data.leave_count;
                    }
                    if (data.task_count !== undefined) {
                        document.getElementById('task-count').textContent = data.task_count;
                    }
                })
                .catch(error => {
                    console.error('Error fetching employee data:', error);
                });
        }
    </script>
</body>

</html>
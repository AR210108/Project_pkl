<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Attendance Screen</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#0ea5e9", // A shade of blue
                        background: "#ffffff",
                        surface: "#f8fafc",
                        "text-primary": "#1e293b",
                        "text-secondary": "#64748b",
                        "border-color": "#e2e8f0",
                    },
                    fontFamily: {
                        display: ["Roboto", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                        lg: "1rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-background text-text-secondary">
    <div class="min-h-screen flex flex-col p-4 sm:p-6 lg:p-8">
        <header class="w-full max-w-7xl mx-auto mb-8">
            <div class="flex justify-between items-center py-4 border-b border-border-color">
                <h1 class="text-2xl font-bold text-text-primary">Brand</h1>
                <nav class="hidden md:flex items-center gap-8 font-medium">
                    <a class="hover:text-primary transition-colors" href="#">Beranda</a>
                    <a class="text-primary font-semibold" href="#">Absensi</a>
                    <a class="hover:text-primary transition-colors" href="#">Manage Tugas</a>
                </nav>
                <button
                    class="bg-slate-100 text-text-primary px-6 py-2 rounded-md font-semibold hover:bg-slate-200 transition-colors">
                    Logout
                </button>
            </div>
        </header>
        <main class="flex-grow w-full max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-10 text-text-primary">ABSENSI KARYAWAN</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-surface p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-5xl mb-4 text-primary">login</span>
                    <p class="font-semibold text-text-primary">ABSEN MASUK</p>
                </div>
                <div
                    class="bg-surface p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-5xl mb-4 text-primary">logout</span>
                    <p class="font-semibold text-text-primary">ABSEN PULANG</p>
                </div>
                <div
                    class="bg-surface p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-5xl mb-4 text-primary">event_busy</span>
                    <p class="font-semibold text-text-primary">IZIN</p>
                </div>
                <div
                    class="bg-surface p-6 rounded-lg text-center flex flex-col justify-center items-center aspect-square hover:bg-slate-200 transition-colors cursor-pointer border border-border-color">
                    <span class="material-icons text-5xl mb-4 text-primary">work_outline</span>
                    <p class="font-semibold text-text-primary">DINAS LUAR</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 flex flex-col gap-8">
                    <div class="bg-surface p-6 rounded-lg text-center border border-border-color">
                        <p class="text-5xl font-bold tracking-wider text-text-primary">12 : 00 : 00</p>
                        <p class="text-text-secondary mt-2">Senin, 01 Januari 2025</p>
                    </div>
                    <div class="bg-surface p-6 rounded-lg border border-border-color">
                        <h3 class="font-bold text-lg mb-4 text-text-primary">Status Absensi</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-primary">schedule</span>
                                <div>
                                    <p class="font-medium text-text-primary">Absen Masuk</p>
                                    <p class="text-text-secondary text-sm">09 : 00</p>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-text-primary">Status: <span class="text-green-600">Tepat
                                        Waktu</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 bg-surface p-6 rounded-lg border border-border-color">
                    <h3 class="font-bold text-lg mb-4 text-text-primary">Riwayat Absensi</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-text-primary">
                                    <th class="p-2 font-medium">No</th>
                                    <th class="p-2 font-medium">Tanggal</th>
                                    <th class="p-2 font-medium">Jam Masuk</th>
                                    <th class="p-2 font-medium">Jam Pulang</th>
                                    <th class="p-2 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-text-secondary">
                                <tr class="border-t border-border-color">
                                    <td class="p-2 text-text-primary">1</td>
                                    <td class="p-2">31 Des 2024</td>
                                    <td class="p-2">08:55</td>
                                    <td class="p-2">17:05</td>
                                    <td class="p-2"><span
                                            class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Tepat
                                            Waktu</span></td>
                                </tr>
                                <tr class="border-t border-border-color">
                                    <td class="p-2 text-text-primary">2</td>
                                    <td class="p-2">30 Des 2024</td>
                                    <td class="p-2">09:10</td>
                                    <td class="p-2">17:00</td>
                                    <td class="p-2"><span
                                            class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Terlambat</span>
                                    </td>
                                </tr>
                                <tr class="border-t border-border-color">
                                    <td class="p-2 text-text-primary">3</td>
                                    <td class="p-2">29 Des 2024</td>
                                    <td class="p-2">-</td>
                                    <td class="p-2">-</td>
                                    <td class="p-2"><span
                                            class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Izin</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <footer class="w-full max-w-7xl mx-auto mt-12 text-center text-sm text-text-secondary">
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

</body>

</html>

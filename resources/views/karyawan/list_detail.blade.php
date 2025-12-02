<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Detail Tugas Screen</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#111827",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem", // 12px
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Custom scrollbar for better mobile experience */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="bg-white text-gray-800">
    <div class="flex flex-col min-h-screen">
        @include('karyawan.templet.header')
        <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10 lg:py-12 max-w-6xl">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 md:mb-8">Detail Tugas</h2>
            <div class="bg-gray-50 p-4 sm:p-6 md:p-8 rounded-xl border border-gray-200">
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">Membuat Website Pendidikan</h3>
                <div class="space-y-4 md:space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-1 text-base md:text-lg">Deskripsi Tugas</h4>
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed">Buatlah desain mockup untuk halaman landing page baru menggunakan
                            Figma. Pastikan desain mengikuti brand guideline yang sudah ada (lihat file di lampiran).
                            Fokus pada tampilan yang clean dan mobile-friendly. Mockup harus mencakup header, hero
                            section, fitur, testimoni, dan footer.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-1 text-base md:text-lg">Deadline</h4>
                        <p class="text-gray-600 text-sm md:text-base">Jumat, 25 Oktober 2024, Pukul 17:00 WIB</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-1 text-base md:text-lg">Ditugaskan oleh</h4>
                        <p class="text-gray-600 text-sm md:text-base">Budi Santoso (Project Manager)</p>
                    </div>
                </div>
                <div class="mt-6 md:mt-8">
                    <h4 class="font-semibold text-gray-700 mb-2 text-base md:text-lg">File Terlampir</h4>
                    <div class="bg-white p-3 sm:p-4 rounded-lg border border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="font-medium text-gray-800 text-sm md:text-base truncate">Referensi_Landing_Page.jpg</span>
                            <button
                                class="bg-gray-100 text-gray-700 text-sm font-medium py-2 px-4 rounded-md border border-gray-300 hover:bg-gray-200 transition-colors whitespace-nowrap w-full sm:w-auto">Lihat</button>
                        </div>
                    </div>
                </div>
                <hr class="my-6 md:my-8 border-gray-200" />
                <div>
                    <h4 class="text-lg md:text-xl font-semibold text-gray-800 mb-3 md:mb-4">Komentar &amp; Diskusi</h4>
                    <div class="space-y-4 md:space-y-6">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded-r-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-1">
                                <p class="font-semibold text-blue-800 text-sm md:text-base">Budi Santoso</p>
                                <p class="text-xs text-blue-600 sm:ml-2 mt-1 sm:mt-0">2 jam yang lalu</p>
                            </div>
                            <p class="text-blue-700 text-sm md:text-base">Jangan lupa untuk memperhatikan warna primer dan sekunder ya.
                                Terima kasih!</p>
                        </div>
                        <div class="flex flex-col">
                            <textarea
                                class="w-full bg-white text-gray-800 placeholder-gray-500 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition-shadow p-3 text-sm md:text-base"
                                placeholder="Tulis Komentar..." rows="3"></textarea>
                            <div class="flex justify-end mt-3">
                                <button
                                    class="bg-gray-800 text-white font-medium py-2 px-4 md:px-5 rounded-lg hover:bg-gray-900 transition-colors text-sm md:text-base">Kirim
                                    Komentar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="mt-auto py-4 md:py-6">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs md:text-sm text-gray-500">Copyright ©2025 by digicity.id</p>
            </div>
        </footer>
    </div>

</body>

</html>
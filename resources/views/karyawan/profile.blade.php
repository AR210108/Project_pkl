<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        secondary: "#64748b",
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen pb-10">

    @include('karyawan.templet.header')

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Profil saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi pribadi dan data pekerjaan Anda.</p>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Foto Profil Saja -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card Foto -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">FOTO KARYAWAN</h3>
                        
                        <div class="relative w-40 h-40 mx-auto mb-4 group">
                            <div class="w-full h-full rounded-full bg-gray-200 overflow-hidden border-4 border-gray-50 shadow-inner">
                                <!-- Placeholder Image -->
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0D8ABC&color=fff&size=256" 
                                     alt="Foto Profil" 
                                     class="w-full h-full object-cover">
                            </div>
                            <!-- Edit Button Overlay (Optional) -->
                            <button class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md text-gray-600 hover:text-primary transition">
                                <span class="material-symbols-outlined text-2xl">edit</span>
                            </button>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name ?? 'Ihsan' }}</h2>
                        <p class="text-sm text-gray-500 mb-6">Clerks</p>
                        
                        <button class="w-full py-2 px-4 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm text-sm">
                            Ubah Foto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Info Pekerjaan & Informasi Pribadi -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card Info Pekerjaan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">work</span>
                        Informasi Pekerjaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-6">
                        <!-- Departemen -->
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 uppercase font-semibold mb-1">Departemen</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400 text-lg">business</span>
                                <span class="text-sm font-medium text-gray-800">Staff IT support</span>
                            </div>
                        </div>

                        <!-- Team -->
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 uppercase font-semibold mb-1">Team</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400 text-lg">groups</span>
                                <span class="text-sm font-medium text-gray-800">Technology & Development</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100">
                         <button class="text-primary text-sm font-semibold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            Edit Data Pekerjaan
                         </button>
                    </div>
                </div>

                <!-- Card Personal Info (Dipindahkan dari kiri) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Nama Lengkap</p>
                            <p class="text-sm font-medium text-gray-700 mt-1">{{ Auth::user()->name ?? 'Ihsan' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Email</p>
                            <p class="text-sm font-medium text-gray-700 mt-1 truncate">{{ Auth::user()->email ?? 'Gazzamopyanseisengweijingjer@email.com' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Nomor Telepon</p>
                            <p class="text-sm font-medium text-gray-700 mt-1">{{ Auth::user()->phone ?? '08777698945' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Alamat / Posisi</p>
                            <p class="text-sm font-medium text-gray-700 mt-1">Jl. Setiabudi / Clerks</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-100">
                         <button class="text-primary text-sm font-semibold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            Edit Informasi Pribadi
                         </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 text-center text-gray-400 text-sm">
            <p>Copyright ©2025 by digicity.id</p>
        </footer>
    </div>

</body>
</html>
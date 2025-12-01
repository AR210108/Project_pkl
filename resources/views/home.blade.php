<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Digital Agency Landing Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#4f46e5",
                        "background-light": "#f8fafc",
                        "background-dark": "#0f172a",
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
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Smooth Scrolling untuk seluruh halaman */
        html {
            scroll-behavior: smooth;
        }
        
        /* Gaya untuk Container Portofolio */
        .portfolio-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Untuk scrolling yang lebih halus di iOS */
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            scroll-snap-type: x mandatory; /* Snap effect saat scroll */
        }
        
        .portfolio-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .portfolio-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .portfolio-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        
        .dark .portfolio-container::-webkit-scrollbar-track {
            background: #334155;
        }
        
        .dark .portfolio-container::-webkit-scrollbar-thumb {
            background-color: #64748b;
        }

        .portfolio-container > div > div {
            scroll-snap-align: start; /* Snap effect untuk setiap item */
        }
        
        /* Gaya untuk Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
            padding-top: 2rem;
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 1rem;
            width: 90%;
            max-width: 800px;
            position: relative;
        }
        
        .dark .modal-content {
            background-color: #1e293b;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #64748b;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .dark .close-modal {
            color: #94a3b8;
        }
        
        .close-modal:hover {
            color: #1e293b;
        }
        
        .dark .close-modal:hover {
            color: #f1f5f9;
        }
        
        /* Gaya untuk Container Layanan - Desktop */
        .layanan-container {
            max-height: 320px; /* Tinggi untuk 1 baris layanan */
            overflow-y: hidden; /* Awalnya tidak bisa di-scroll */
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            transition: max-height 0.5s ease-in-out;
        }
        
        .layanan-container.expanded {
            max-height: 620px; /* Tinggi untuk 2 baris layanan, sisanya di-scroll */
            overflow-y: auto; /* Bisa di-scroll setelah tombol diklik */
        }
        
        .layanan-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .layanan-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .layanan-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        
        .dark .layanan-container::-webkit-scrollbar-track {
            background: #334155;
        }
        
        .dark .layanan-container::-webkit-scrollbar-thumb {
            background-color: #64748b;
        }

        /* Gaya untuk Container Layanan - Mobile */
        .layanan-container-mobile {
            display: none; /* Sembunyikan di desktop */
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            scroll-snap-type: x mandatory;
            padding-bottom: 1rem; /* Ruang untuk scrollbar */
        }
        
        .layanan-container-mobile::-webkit-scrollbar {
            height: 8px;
        }
        
        .layanan-container-mobile::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .layanan-container-mobile::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        
        .dark .layanan-container-mobile::-webkit-scrollbar-track {
            background: #334155;
        }
        
        .dark .layanan-container-mobile::-webkit-scrollbar-thumb {
            background-color: #64748b;
        }

        .layanan-container-mobile > div > div {
            scroll-snap-align: start;
        }

        /* Media query untuk menampilkan/hide container layanan */
        @media (max-width: 768px) {
            .layanan-container {
                display: none; /* Sembunyikan di mobile */
            }
            .layanan-container-mobile {
                display: block; /* Tampilkan di mobile */
            }
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-700 dark:text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="py-6 flex justify-between items-center">
            <div class="text-2xl font-bold text-slate-900 dark:text-white">Brand</div>
            <nav class="hidden md:flex items-center space-x-8">
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors"
                    href="#">Beranda</a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors"
                    href="#layanan">Layanan</a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors"
                    href="#tentang">Tentang</a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors"
                    href="#portofolio">Portofolio</a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors"
                    href="#artikel">Artikel</a>
                <a class="text-sm font-medium hover:text-primary dark:hover:text-primary transition-colors"
                    href="#kontak">Kontak</a>
                </nav>
            <a class="bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white text-sm font-medium py-2 px-6 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors"
                href="#">
                Login
            </a>
        </header>
        <main>
            <section class="py-20 bg-slate-100 dark:bg-slate-800 rounded-2xl">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-12">
                        <div class="col-start-1 col-span-12 text-center">
                            <h1 class="text-4xl md:text-7xl font-bold text-slate-900 dark:text-white mb-4">DIGITAL
                                AGENCY</h1>
                        </div>
                        <div class="col-start-1 col-span-12 md:col-start-4 md:col-span-6 lg:col-start-4 lg:col-span-6">
                            <p class="mb-8 text-slate-600 dark:text-slate-400">Kami digital agency adalah perusahaan
                                yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai
                                layanan digital.</p>
                            <button
                                class="bg-primary text-white font-medium py-3 px-8 rounded-lg hover:bg-indigo-700 transition-colors">
                                List Layanan
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-24 text-left bg-background-light dark:bg-background-dark" id="layanan">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-slate-100 dark:bg-slate-800 p-8 md:p-12 rounded-2xl">
                        <div class="flex flex-wrap justify-between items-start mb-10">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 md:mb-0">List Layanan</h2>
                            <p class="max-w-sm text-slate-600 dark:text-slate-400 text-sm">Kami digital agency adalah
                                perusahaan yang membantu bisnis lain membawa ke produk atau jasanya secara online.</p>
                        </div>
                        
                        <!-- Desktop Layanan Container -->
                        <div id="layananContainer" class="layanan-container mb-10">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Total 10 UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">UI/UX</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Pembuatan Website</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Desain dan pengembangan website profesional dan responsif.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Marketing</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">SEO Optimization</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Meningkatkan peringkat website Anda di mesin pencari.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Sosmed</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Manajemen Sosial Media</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Mengelola dan mengembangkan kehadiran brand di media sosial.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Ads</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Iklan Digital (Google Ads)</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kampanye iklan yang tertarget untuk meningkatkan traffic dan konversi.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Content</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Pembuatan Konten</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membuat konten menarik dan relevan untuk audiens Anda.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Strategy</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Strategi Brand</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membangun identitas brand yang kuat dan konsisten.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Mobile</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Pengembangan Aplikasi Mobile</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membangun aplikasi mobile native dan cross-platform untuk iOS dan Android.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Email</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Email Marketing</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membuat dan mengelola kampanye email marketing yang efektif.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Analytics</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Analitik Digital</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Menganalisis data digital untuk mengoptimalkan strategi pemasaran.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Layanan Container -->
                        <div id="layananContainerMobile" class="layanan-container-mobile mb-4">
                            <div class="flex gap-4" style="width: max-content;">
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Total 10 UI</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kota Bandung</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">UI/UX</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Pembuatan Website</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Desain dan pengembangan website profesional dan responsif.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Marketing</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">SEO Optimization</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Meningkatkan peringkat website Anda di mesin pencari.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Sosmed</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Manajemen Sosial Media</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Mengelola dan mengembangkan kehadiran brand di media sosial.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Ads</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Iklan Digital (Google Ads)</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Kampanye iklan yang tertarget untuk meningkatkan traffic dan konversi.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Content</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Pembuatan Konten</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membuat konten menarik dan relevan untuk audiens Anda.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Strategy</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Strategi Brand</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membangun identitas brand yang kuat dan konsisten.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Mobile</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Pengembangan Aplikasi Mobile</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membangun aplikasi mobile native dan cross-platform untuk iOS dan Android.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Email</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Email Marketing</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Membuat dan mengelola kampanye email marketing yang efektif.</p>
                                </div>
                                <div class="bg-background-light dark:bg-background-dark p-6 rounded-2xl flex flex-col w-72 flex-shrink-0">
                                    <div class="relative pt-[75%] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-full">Analytics</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Analitik Digital</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Menganalisis data digital untuk mengoptimalkan strategi pemasaran.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Indikator Scroll untuk Layanan (Hanya di Mobile) -->
                        <div class="flex justify-center mb-4 md:hidden">
                            <div class="bg-slate-200 dark:bg-slate-700 rounded-full px-4 py-2 flex items-center space-x-2">
                                <span class="material-icons-outlined text-sm">swipe</span>
                                <span class="text-sm text-slate-600 dark:text-slate-300">Geser untuk melihat lebih banyak</span>
                            </div>
                        </div>
                        
                        <!-- Tombol Toggle untuk Desktop -->
                        <div class="hidden md:flex justify-center">
                            <button id="layananToggleBtn" class="bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white font-medium py-3 px-8 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors flex items-center">
                                <span id="layananToggleText">Lihat Lainnya</span>
                                <span id="layananToggleIcon" class="material-icons-outlined ml-2">expand_more</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <section class="py-12 md:py-24 text-center max-w-3xl mx-auto" id="tentang">
                    <div class="flex items-center mb-8">
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                        <h2 class="mx-4 text-3xl font-bold text-slate-900 dark:text-white">TENTANG</h2>
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Kami digital agency adalah perusahaan
                        yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai layanan
                        digital. Layanan yang ditawarkan meliputi strategi pemasaran digital, pembuatan dan pengelolaan
                        situs web, manajemen media sosial, optimasi mesin pencari (SEO), serta kampanye iklan di Google
                        Ads, iklan display, dan video.</p>
                </section>
                <section class="py-12 md:py-24" id="portofolio">
                    <div class="flex items-center mb-12">
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                        <h2 class="mx-4 text-3xl font-bold text-slate-900 dark:text-white">PORTOFOLIO</h2>
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                    </div>
                    <div class="portfolio-container pb-4">
                        <div class="flex gap-8" style="width: max-content;">
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl flex flex-col w-72">
                                <div class="relative flex-grow aspect-[4/5] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-slate-300/50 dark:bg-slate-900/50 w-8 h-8 rounded-full flex items-center justify-center text-slate-800 dark:text-white hover:bg-slate-400/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">E-Commerce Platform</h3>
                                <button class="w-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors portfolio-btn" data-title="E-Commerce Platform" data-description="Platform e-commerce modern dengan fitur lengkap untuk memudahkan transaksi online. Kami mengembangkan solusi yang responsif dan user-friendly untuk meningkatkan konversi penjualan." data-tech="React, Node.js, MongoDB, Stripe API">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl flex flex-col w-72">
                                <div class="relative flex-grow aspect-[4/5] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-slate-300/50 dark:bg-slate-900/50 w-8 h-8 rounded-full flex items-center justify-center text-slate-800 dark:text-white hover:bg-slate-400/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Mobile Banking App</h3>
                                <button class="w-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors portfolio-btn" data-title="Mobile Banking App" data-description="Aplikasi perbankan mobile yang aman dan intuitif dengan fitur transfer, pembayaran tagihan, dan manajemen keuangan pribadi. Dilengkapi dengan sistem keamanan berlapis untuk melindungi data pengguna." data-tech="React Native, Node.js, PostgreSQL, Biometric Authentication">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl flex flex-col w-72">
                                <div class="relative flex-grow aspect-[4/5] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-slate-300/50 dark:bg-slate-900/50 w-8 h-8 rounded-full flex items-center justify-center text-slate-800 dark:text-white hover:bg-slate-400/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Healthcare Dashboard</h3>
                                <button class="w-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors portfolio-btn" data-title="Healthcare Dashboard" data-description="Dashboard kesehatan terintegrasi untuk memonitor pasien dan mengelola jadwal tenaga medis. Sistem ini membantu rumah sakit meningkatkan efisiensi operasional dan kualitas layanan pasien." data-tech="Vue.js, Python, Django, PostgreSQL, Chart.js">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl flex flex-col w-72">
                                <div class="relative flex-grow aspect-[4/5] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-slate-300/50 dark:bg-slate-900/50 w-8 h-8 rounded-full flex items-center justify-center text-slate-800 dark:text-white hover:bg-slate-400/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Educational Platform</h3>
                                <button class="w-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors portfolio-btn" data-title="Educational Platform" data-description="Platform pembelajaran online dengan fitur video interaktif, kuis, dan sistem penilaian otomatis. Dirancang untuk mendukung pembelajaran jarak jauh dengan pengalaman yang engaging." data-tech="Next.js, TypeScript, Prisma, PostgreSQL, WebRTC">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl flex flex-col w-72">
                                <div class="relative flex-grow aspect-[4/5] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-slate-300/50 dark:bg-slate-900/50 w-8 h-8 rounded-full flex items-center justify-center text-slate-800 dark:text-white hover:bg-slate-400/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Food Delivery App</h3>
                                <button class="w-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors portfolio-btn" data-title="Food Delivery App" data-description="Aplikasi pengantaran makanan dengan sistem pemesanan yang intuitif dan tracking real-time. Terintegrasi dengan berbagai restoran dan sistem pembayaran digital untuk kemudahan pengguna." data-tech="Flutter, Firebase, Google Maps API, Razorpay">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl flex flex-col w-72">
                                <div class="relative flex-grow aspect-[4/5] bg-slate-200 dark:bg-slate-700 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-slate-300/50 dark:bg-slate-900/50 w-8 h-8 rounded-full flex items-center justify-center text-slate-800 dark:text-white hover:bg-slate-400/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4">Travel Booking Platform</h3>
                                <button class="w-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors portfolio-btn" data-title="Travel Booking Platform" data-description="Platform pemesanan perjalanan terintegrasi dengan fitur pencarian tiket pesawat, hotel, dan paket liburan. Dilengkapi dengan sistem rekomendasi berbasis AI untuk pengalaman personalisasi." data-tech="Angular, Node.js, Express, MongoDB, Machine Learning">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <div class="bg-slate-200 dark:bg-slate-700 rounded-full px-4 py-2 flex items-center space-x-2">
                            <span class="material-icons-outlined text-sm">swipe</span>
                            <span class="text-sm text-slate-600 dark:text-slate-300">Geser untuk melihat lebih banyak</span>
                        </div>
                    </div>
                </section>
                <section class="py-12 md:py-24" id="artikel">
                    <div class="flex items-center mb-12">
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                        <h2 class="mx-4 text-3xl font-bold text-slate-900 dark:text-white">ARTIKEL</h2>
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="flex flex-col">
                            <div class="aspect-[4/3] bg-slate-200 dark:bg-slate-700 rounded-2xl mb-4"></div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">bilibibib</p>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Judul artikel</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">isi
                                artikel<br />--------------------<br />--------------------<br />--------------------<br />--------------------<br />--------------------</p>
                        </div>
                        <div class="space-y-6">
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-slate-200 dark:bg-slate-700 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-2">Isi artikel</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        --------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-slate-200 dark:bg-slate-700 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-2">Isi artikel</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        --------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-slate-200 dark:bg-slate-700 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-2">Isi artikel</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        --------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="py-12 md:py-24" id="kontak">
                    <div class="flex items-center mb-12">
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                        <h2 class="mx-4 text-3xl font-bold text-slate-900 dark:text-white">KONTAK</h2>
                        <div class="flex-grow h-px bg-slate-300 dark:bg-slate-700"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
                        <div class="bg-slate-100 dark:bg-slate-800 rounded-2xl p-6 h-96 lg:h-auto flex flex-col">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">Hubungi Kami</h3>
                            <div class="flex-grow bg-slate-200 dark:bg-slate-700 rounded-lg"></div>
                        </div>
                        <form class="space-y-6">
                            <div>
                                <label class="sr-only" for="name">Name</label>
                                <input class="w-full bg-slate-100 dark:bg-slate-800 border-transparent rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary focus:border-primary" id="name" name="name" placeholder="Nama Anda" type="text" />
                            </div>
                            <div>
                                <label class="sr-only" for="email">Email</label>
                                <input class="w-full bg-slate-100 dark:bg-slate-800 border-transparent rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary focus:border-primary" id="email" name="email" placeholder="Email Anda" type="email" />
                            </div>
                            <div>
                                <label class="sr-only" for="message">Message</label>
                                <textarea class="w-full bg-slate-100 dark:bg-slate-800 border-transparent rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary focus:border-primary" id="message" name="message" placeholder="Pesan Anda" rows="6"></textarea>
                            </div>
                            <button class="w-full bg-primary text-white font-medium py-3 px-8 rounded-lg hover:bg-indigo-700 transition-colors" type="submit">
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </section>
            </div>
            <footer class="py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-slate-100 dark:bg-slate-800 text-center py-4 rounded-lg">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Copyright ©2025 by digicity.id</p>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- Modal Portofolio -->
    <div id="portfolioModal" class="modal">
        <div class="modal-content">
            <span class="close-modal close-modal-portfolio">&times;</span>
            <div class="p-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-slate-900 dark:text-white mb-4"></h2>
                <div class="aspect-video bg-slate-200 dark:bg-slate-700 rounded-lg mb-6"></div>
                <p id="modalDescription" class="text-slate-600 dark:text-slate-400 mb-6"></p>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Teknologi yang Digunakan:</h3>
                    <div id="modalTech" class="flex flex-wrap gap-2"></div>
                </div>
                <div class="flex justify-between">
                    <button class="bg-primary text-white font-medium py-2 px-6 rounded-lg hover:bg-indigo-700 transition-colors">
                        Kunjungi Website
                    </button>
                    <button class="bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white font-medium py-2 px-6 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                        Hubungi Kami
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal Portfolio
        const portfolioModal = document.getElementById('portfolioModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const modalTech = document.getElementById('modalTech');
        const closePortfolioModal = document.querySelector('.close-modal-portfolio');
        
        const portfolioBtns = document.querySelectorAll('.portfolio-btn');
        
        portfolioBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const tech = this.getAttribute('data-tech').split(', ');
                
                modalTitle.textContent = title;
                modalDescription.textContent = description;
                
                modalTech.innerHTML = '';
                tech.forEach(techItem => {
                    const techTag = document.createElement('span');
                    techTag.className = 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary text-sm font-medium py-1 px-3 rounded-full';
                    techTag.textContent = techItem;
                    modalTech.appendChild(techTag);
                });
                
                portfolioModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });
        
        closePortfolioModal.addEventListener('click', function() {
            portfolioModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Layanan Toggle
        const layananContainer = document.getElementById('layananContainer');
        const layananToggleBtn = document.getElementById('layananToggleBtn');
        const layananToggleText = document.getElementById('layananToggleText');
        const layananToggleIcon = document.getElementById('layananToggleIcon');
        
        layananToggleBtn.addEventListener('click', function() {
            if (layananContainer.classList.contains('expanded')) {
                layananContainer.classList.remove('expanded');
                layananToggleText.textContent = 'Lihat Lainnya';
                layananToggleIcon.textContent = 'expand_more';
                // Scroll ke atas saat ditutup
                layananContainer.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                layananContainer.classList.add('expanded');
                layananToggleText.textContent = 'Tutup';
                layananToggleIcon.textContent = 'expand_less';
            }
        });

        // Tutup modal saat klik di luar area modal
        window.addEventListener('click', function(event) {
            if (event.target === portfolioModal) {
                portfolioModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>

</html>
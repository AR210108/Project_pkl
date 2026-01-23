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
                        primary: "#0f172a",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                        "card-light": "#111827",
                        "card-dark": "#1f2937",
                        "text-light": "#111827",
                        "text-dark": "#f9fafb",
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
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Header yang menempel di atas saat di-scroll */
        .sticky-header {
            position: fixed; /* Diubah dari relative */
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .sticky-header.scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Tambahkan padding-top pada konten utama untuk menghindari tertutup header */
        .main-content {
            padding-top: 80px; /* Sesuaikan dengan tinggi header */
        }
        
        .hamburger-line {
            transition: all 0.3s ease;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        .mobile-nav {
            max-height: 0;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.95);
            transition: max-height 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 0 0 1rem 1rem;
        }
        
        .mobile-nav.active {
            max-height: 70vh;
        }
        
        .mobile-nav-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .mobile-nav .nav-link {
            color: #0f172a;
            font-size: 1.125rem;
            font-weight: 500;
            text-decoration: none;
            position: relative;
            transition: color 0.3s ease;
            padding: 0.75rem 0;
            display: block;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .mobile-nav .nav-link:hover,
        .mobile-nav .nav-link.active {
            color: #0f172a;
            background-color: rgba(15, 23, 42, 0.1);
        }
        
        .mobile-nav .login-btn {
            background-color: #0f172a;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
        }
        
        .mobile-nav .login-btn:hover {
            background-color: rgba(15, 23, 42, 0.9);
            transform: translateY(-2px);
        }
        
        .portfolio-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db #f3f4f6;
            scroll-snap-type: x mandatory;
        }
        
        .portfolio-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .portfolio-container::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }
        
        .portfolio-container::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 4px;
        }

        .portfolio-container > div > div {
            scroll-snap-align: start;
        }
        
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
            background-color: #111827;
            margin: 5% auto;
            padding: 20px;
            border-radius: 1rem;
            width: 90%;
            max-width: 800px;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #d1d5db;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close-modal:hover {
            color: #f9fafb;
        }
        
        /* Gaya untuk Container Layanan Tambahan (Desktop) */
        .layanan-grid-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.6s ease-in-out;
            position: relative;
        }

        .layanan-grid-wrapper.expanded {
            max-height: 600px; /* Tinggi tetap untuk scroll terbatas */
            overflow-y: auto;
            padding-right: 8px; /* Ruang untuk scrollbar */
        }

        .layanan-grid-wrapper::-webkit-scrollbar {
            width: 8px;
        }
        .layanan-grid-wrapper::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }
        .layanan-grid-wrapper::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 4px;
        }

        /* Gaya untuk Container Layanan - Mobile */
        .layanan-container-mobile {
            display: none;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db #f3f4f6;
            scroll-snap-type: x mandatory;
            padding-bottom: 1rem;
            position: relative;
        }
        
        .layanan-container-mobile::-webkit-scrollbar {
            height: 8px;
        }
        
        .layanan-container-mobile::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }
        
        .layanan-container-mobile::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 4px;
        }

        .layanan-container-mobile > div > div {
            scroll-snap-align: start;
        }

        @media (max-width: 768px) {
            .layanan-grid-wrapper {
                display: none;
            }
            .layanan-container-mobile {
                display: block;
            }
        }
        
        /* Perbaikan untuk navigasi hover */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background-color: #0f172a;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .nav-link:hover {
            color: #0f172a;
            background-color: rgba(15, 23, 42, 0.05);
            transform: translateY(-2px);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .nav-link.active {
            color: #0f172a;
            font-weight: 600;
            background-color: rgba(15, 23, 42, 0.08);
        }
        
        .nav-link.active::after {
            width: 80%;
        }
        
        /* Efek hover untuk tombol login di desktop */
        .login-btn-desktop {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn-desktop:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .login-btn-desktop::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .login-btn-desktop:hover::before {
            left: 100%;
        }
        
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .portfolio-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .portfolio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .article-card {
            transition: transform 0.3s ease;
        }
        
        .article-card:hover {
            transform: translateY(-3px);
        }
        
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
        
        .gradient-primary {
            background: linear-gradient(135deg, #000000, #111827);
        }
        
        .gradient-dark {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }
        
        .gradient-subtle {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }
        
        .decorative-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }
        
        .circle-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #000000, #111827);
            top: -150px;
            right: -100px;
        }
        
        .circle-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            bottom: -100px;
            left: -50px;
        }
        
        .circle-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #000000, #111827);
            top: 50%;
            left: -75px;
        }
        
        .layanan-scroll-indicator {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        .layanan-scroll-indicator span {
            display: inline-flex;
            align-items: center;
            background-color: rgba(15, 23, 42, 0.1);
            color: #0f172a;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .layanan-scroll-indicator .material-icons-outlined {
            margin-right: 0.5rem;
            font-size: 1rem;
        }
        
        @media (max-width: 768px) {
            .service-card {
                width: 240px !important;
                flex-shrink: 0;
            }
            .service-card h3 {
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }
            .service-card p {
                font-size: 0.8rem;
                line-height: 1.4;
            }
        }
        
        .layanan-container-mobile .service-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        
        .contact-card {
            background-color: #f8fafc;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        .contact-icon {
            background-color: #0f172a;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .contact-text {
            flex-grow: 1;
        }
        
        .contact-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        
        .contact-value {
            color: #4b5563;
            line-height: 1.5;
        }
        
        /* Style untuk deskripsi layanan satu baris */
        .service-description {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }
        
        /* Style untuk tombol detail layanan */
        .service-detail-btn {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .service-detail-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .service-detail-btn .material-icons-outlined {
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }
    </style>
</head>

<body class="bg-background-light text-text-light">
    <!-- Sticky Header -->
    <header id="header" class="sticky-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-black">Brand</div>
                <nav class="hidden lg:flex items-center space-x-4 absolute left-1/2 transform -translate-x-1/2">
                    <a class="nav-link text-sm font-medium text-gray-700 active" href="#beranda" data-section="beranda">Beranda</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#layanan" data-section="layanan">Layanan</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#tentang" data-section="tentang">Tentang</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#portofolio" data-section="portofolio">Portofolio</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#artikel" data-section="artikel">Artikel</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#kontak" data-section="kontak">Kontak</a>
                </nav>
                <a class="hidden lg:block login-btn-desktop bg-black text-white text-sm font-medium py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors"
                    href="{{ url('/login') }}">
                    Login
                </a>
                <button id="mobileMenuBtn" class="lg:hidden flex flex flex-col justify-center items-center w-8 h-8 hamburger">
                    <span class="hamburger-line w-6 h-0.5 bg-black mb-1.5"></span>
                    <span class="hamburger-line w-6 h-0.5 bg-black mb-1.5"></span>
                    <span class="hamburger-line w-6 h-0.5 bg-black"></span>
                </button>
            </div>
            <div id="mobileNav" class="mobile-nav">
                <div class="mobile-nav-content">
                    <nav>
                        <a class="nav-link active" href="#beranda" data-section="beranda">Beranda</a>
                        <a class="nav-link" href="#layanan" data-section="layanan">Layanan</a>
                        <a class="nav-link" href="#tentang" data-section="tentang">Tentang</a>
                        <a class="nav-link" href="#portofolio" data-section="portofolio">Portofolio</a>
                        <a class="nav-link" href="#artikel" data-section="artikel">Artikel</a>
                        <a class="nav-link" href="#kontak" data-section="kontak">Kontak</a>
                    </nav>
                    <a class="login-btn" href="{{ url('/login') }}">Login</a>
                </div>
            </div>
    </header>
    
    <main class="main-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="py-20 gradient-primary rounded-2xl shadow-lg relative overflow-hidden" id="beranda">
                <div class="decorative-circle circle-1"></div>
                <div class="decorative-circle circle-2"></div>
                <div class="decorative-circle circle-3"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid grid grid-cols-12">
                        <div class="col-start-1 col-span-12 text-center">
                            <h1 class="text-4xl md:text-7xl font-bold text-white mb-4">DIGITAL AGENCY</h1>
                            <p class="mb-8 text-white/90 mx-auto max-w-2xl">Kami digital agency adalah perusahaan
                                yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai
                                layanan digital.</p>
                            <button class="bg-white text-black font-medium py-3 px-8 rounded-lg hover:bg-gray-100 transition-colors shadow-md mx-auto">
                                List Layanan
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="py-24 text-left bg-background-light" id="layanan">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="gradient-subtle p-8 md:p-12 rounded-2xl shadow-sm">
                        <div class="flex flex-wrap justify-between items-start mb-10">
                            <h2 class="text-2xl font-bold text-black mb-4 md:mb-0">List Layanan</h2>
                            <p class="max-w-sm text-gray-700 text-sm">Kami digital agency adalah
                                perusahaan yang membantu bisnis lain membawa ke produk atau jasanya secara online.</p>
                        </div>
                        
                        <!-- Desktop Layanan Container -->
                        <div id="layananContainer" class="mb-10">
                            @if($layanans->isNotEmpty())
                                <!-- ======================================= -->
                                <!-- BAGIAN 1: GRID LAYANAN STATIS (4 PERTAMA) -->
                                <!-- Grid ini tidak akan pernah tergulung -->
                                <!-- ======================================= -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                    @foreach($layanans->take(4) as $layanan)
                                        <div class="service-card bg-card-light p-4 rounded-xl flex flex-col shadow-sm border border-border-light" 
                                             data-service-id="{{ $layanan->id ?? $loop->iteration }}"
                                             data-service-name="{{ $layanan->nama_layanan ?? 'Layanan ' . $loop->iteration }}"
                                             data-service-price="{{ $layanan->harga ? 'Rp ' . number_format($layanan->harga, 0, ',', '.') : 'Tidak tersedia' }}"
                                             data-service-description="{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . $loop->iteration . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}"
                                             data-service-image="{{ $layanan->foto ? Storage::url($layanan->foto) : '' }}">
                                            <div class="relative pt-[60%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-3 overflow-hidden">
                                                @if($layanan->harga)
                                                    <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-black text-xs font-semibold px-2 py-1 rounded-full z-10">
                                                        Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if($layanan->foto)
                                                    <img src="{{ Storage::url($layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover">
                                                @else
                                                    <div class="absolute inset-0 flex items-center justify-center text-white">
                                                        <span class="text-4xl font-bold">{{ $loop->iteration }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-white mb-1 text-sm">{{ $layanan->nama_layanan ?? 'Layanan ' . $loop->iteration }}</h3>
                                            <p class="service-description text-xs text-gray-300">{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . $loop->iteration . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}</p>
                                            <button class="service-detail-btn mt-2">
                                                Lihat Detail
                                                <span class="material-icons-outlined">arrow_forward</span>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                @php
                                    $remainingLayanans = $layanans->skip(4);
                                @endphp

                                @if($remainingLayanans->isNotEmpty())
                                    <!-- Garis pemisah visual untuk membedakan bagian statis dan yang dapat di-scroll -->
                                    <hr class="my-6 border-gray-300">

                                    <!-- ======================================= -->
                                    <!-- BAGIAN 2: GRID LAYANAN YANG DAPAT DI-SCROLL -->
                                    <!-- Container ini yang akan di-scroll -->
                                    <!-- ======================================= -->
                                    <div id="layananGridWrapper" class="layanan-grid-wrapper">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                            @foreach($remainingLayanans as $layanan)
                                                <div class="service-card bg-card-light p-4 rounded-xl flex flex-col shadow-sm border border-border-light"
                                                     data-service-id="{{ $layanan->id ?? ($loop->iteration + 4) }}"
                                                     data-service-name="{{ $layanan->nama_layanan ?? 'Layanan ' . ($loop->iteration + 4) }}"
                                                     data-service-price="{{ $layanan->harga ? 'Rp ' . number_format($layanan->harga, 0, ',', '.') : 'Tidak tersedia' }}"
                                                     data-service-description="{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . ($loop->iteration + 4) . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}"
                                                     data-service-image="{{ $layanan->foto ? Storage::url($layanan->foto) : '' }}">
                                                    <div class="relative pt-[60%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-3 overflow-hidden">
                                                        @if($layanan->harga)
                                                            <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-black text-xs font-semibold px-2 py-1 rounded-full z-10">
                                                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                        @if($layanan->foto)
                                                            <img src="{{ Storage::url($layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover">
                                                        @else
                                                            <div class="absolute inset-0 flex items-center justify-center text-white">
                                                                <span class="text-4xl font-bold">{{ $loop->iteration + 4 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <h3 class="font-bold text-white mb-1 text-sm">{{ $layanan->nama_layanan ?? 'Layanan ' . ($loop->iteration + 4) }}</h3>
                                                    <p class="service-description text-xs text-gray-300">{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . ($loop->iteration + 4) . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}</p>
                                                    <button class="service-detail-btn mt-2">
                                                        Lihat Detail
                                                        <span class="material-icons-outlined">arrow_forward</span>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Indikator Scroll untuk sisa layanan -->
                                    <div id="scrollIndicator" class="layanan-scroll-indicator hidden md:flex" style="display: none;">
                                        <span>
                                            <span class="material-icons-outlined">keyboard_arrow_down</span>
                                            Scroll untuk melihat lebih banyak
                                        </span>
                                    </div>
                                @endif
                            @else
                                <p class="text-center text-gray-500">Belum ada layanan tersedia.</p>
                            @endif
                        </div>

                        <!-- Mobile Layanan Container (Tidak berubah) -->
                        <div id="layananContainerMobile" class="layanan-container-mobile mb-4">
                            @if($layanans->isNotEmpty())
                                <div class="flex gap-3" style="width: max-content;">
                                    @foreach($layanans as $index => $layanan)
                                        <div class="service-card bg-card-light p-4 rounded-xl flex flex-col w-56 flex-shrink-0 shadow-sm border border-border-light"
                                             data-service-id="{{ $layanan->id ?? ($index + 1) }}"
                                             data-service-name="{{ $layanan->nama_layanan ?? 'Layanan ' . ($index + 1) }}"
                                             data-service-price="{{ $layanan->harga ? 'Rp ' . number_format($layanan->harga, 0, ',', '.') : 'Tidak tersedia' }}"
                                             data-service-description="{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . ($index + 1) . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}"
                                             data-service-image="{{ $layanan->foto ? Storage::url($layanan->foto) : '' }}">
                                            <div class="relative pt-[60%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-3 overflow-hidden">
                                                @if($layanan->harga)
                                                    <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-black text-xs font-semibold px-2 py-1 rounded-full z-10">
                                                        Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if($layanan->foto)
                                                    <img src="{{ Storage::url($layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover">
                                                @else
                                                    <div class="absolute inset-0 flex items-center justify-center text-white">
                                                        <span class="text-4xl font-bold">{{ $index + 1 }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-white mb-1 text-sm">{{ $layanan->nama_layanan ?? 'Layanan ' . ($index + 1) }}</h3>
                                            <p class="service-description text-xs text-gray-300">{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . ($index + 1) . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}</p>
                                            <button class="service-detail-btn mt-2">
                                                Lihat Detail
                                                <span class="material-icons-outlined">arrow_forward</span>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-gray-500 px-4">Belum ada layanan tersedia.</p>
                            @endif
                        </div>
                        
                        <div class="layanan-scroll-indicator md:hidden">
                            <span>
                                <span class="material-icons-outlined">swipe</span>
                                Geser untuk melihat lebih banyak
                            </span>
                        </div>
                        
                        <!-- Tombol "Lihat Lainnya" hanya muncul jika ada lebih dari 4 layanan -->
                        @if($layanans->count() > 4)
                            <div class="hidden md:flex justify-center">
                                <button id="layananToggleBtn" class="btn-primary bg-black text-white font-medium py-3 px-8 rounded-lg flex items-center">
                                    <span id="layananToggleText">Lihat Lainnya</span>
                                    <span id="layananToggleIcon" class="material-icons-outlined ml-2">expand_more</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <section class="py-12 md:py-24 text-center max-w-3xl mx-auto" id="tentang">
                    <div class="flex items-center mb-8">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-3xl font-bold text-black">TENTANG</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">Kami digital agency adalah perusahaan
                        yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai layanan
                        digital. Layanan yang ditawarkan meliputi strategi pemasaran digital, pembuatan dan pengelolaan
                        situs web, manajemen media sosial, optimasi mesin pencari (SEO), serta kampanye iklan di Google
                        Ads, iklan display, dan video.</p>
                </section>
                <section class="py-12 md:py-24" id="portofolio">
                    <div class="flex items-center mb-12">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-3xl font-bold text-black">PORTOFOLIO</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <div class="portfolio-container pb-4">
                        <div class="flex gap-8" style="width: max-content;">
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex flex-col w-72 shadow-sm border border-border-light">
                                <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-white text-lg mb-4">E-Commerce Platform</h3>
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="E-Commerce Platform" data-description="Platform e-commerce modern dengan fitur lengkap untuk memudahkan transaksi online. Kami mengembangkan solusi yang responsif dan user-friendly untuk meningkatkan konversi penjualan." data-tech="React, Node.js, MongoDB, Stripe API">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex flex-col w-72 shadow-sm border border-border-light">
                                <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-white text-lg mb-4">Mobile Banking App</h3>
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="Mobile Banking App" data-description="Aplikasi perbankan mobile yang aman dan intuitif dengan fitur transfer, pembayaran tagihan, dan manajemen keuangan pribadi. Dilengkapi dengan sistem keamanan berlapis untuk melindungi data pengguna." data-tech="React Native, Node.js, PostgreSQL, Biometric Authentication">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex flex-col w-72 shadow-sm border border-border-light">
                                <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-white text-lg mb-4">Healthcare Dashboard</h3>
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="Healthcare Dashboard" data-description="Dashboard kesehatan terintegrasi untuk memonitor pasien dan mengelola jadwal tenaga medis. Sistem ini membantu rumah sakit meningkatkan efisiensi operasional dan kualitas layanan pasien." data-tech="Vue.js, Python, Django, PostgreSQL, Chart.js">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex flex-col w-72 shadow-sm border border-border-light">
                                <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-white text-lg mb-4">Educational Platform</h3>
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="Educational Platform" data-description="Platform pembelajaran online dengan fitur video interaktif, kuis, dan sistem penilaian otomatis. Dirancang untuk mendukung pembelajaran jarak jauh dengan pengalaman yang engaging." data-tech="Next.js, TypeScript, Prisma, PostgreSQL, WebRTC">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex-col w-72 shadow-sm border border-border-light">
                                <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-white text-lg mb-4">Food Delivery App</h3>
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="Food Delivery App" data-description="Aplikasi pengantaran makanan dengan sistem pemesanan yang intuitif dan tracking real-time. Terintegrasi dengan berbagai restoran dan sistem pembayaran digital untuk kemudahan pengguna." data-tech="Flutter, Firebase, Google Maps API, Razorpay">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex flex-col w-72 shadow-sm border border-border-light">
                                <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                        <span class="material-icons-outlined text-base">arrow_forward</span>
                                    </button>
                                </div>
                                <h3 class="font-bold text-white text-lg mb-4">Travel Booking Platform</h3>
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="Travel Booking Platform" data-description="Platform pemesanan perjalanan terintegrasi dengan fitur pencarian tiket pesawat pesawat, hotel, dan paket liburan. Dilengkapi dengan sistem rekomendasi berbasis AI untuk pengalaman personalisasi." data-tech="Angular, Node.js, Express, MongoDB, Machine Learning">
                                    <span>Lihat Detail</span>
                                    <span class="material-icons-outlined text-base">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <div class="bg-gray-100 rounded-full px-4 py-2 flex items-center space-x-2">
                            <span class="material-icons-outlined text-sm text-gray-500">swipe</span>
                            <span class="text-sm text-gray-500">Geser untuk melihat lebih banyak</span>
                        </div>
                    </div>
                </section>
                <section class="py-12 md:py-24" id="artikel">
                    <div class="flex items-center mb-12">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-3xl font-bold text-black">ARTIKEL</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="article-card flex flex-col">
                            <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl mb-4"></div>
                            <p class="text-sm text-black mb-1">bilibibib</p>
                            <h3 class="text-xl font-bold text-black mb-2">Judul artikel</h3>
                            <p class="text-sm text-gray-700">isi
                                artikel<br />--------------------<br />--------------------<br />--------------------<br />--------------------<br />--------------------<br />--------------------<br />--------------------</p>
                        </div>
                        <div class="space-y-6">
                            <div class="article-card flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-black mb-2">Isi artikel</h4>
                                    <p class="text-sm text-gray-700">
                                        --------------------<br />--------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                            <div class="article-card flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-black mb-2">Isi artikel</h4>
                                    <p class="text-sm text-gray-700">
                                        --------------------<br />--------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                            <div class="article-card flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-black mb-2">Isi artikel</h4>
                                    <p class="text-sm text-gray-700">
                                        --------------------<br />--------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="py-12 md:py-24" id="kontak">
                    <div class="flex items-center mb-12">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-3xl font-bold text-black">KONTAK</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
                        <div class="contact-card">
                            <h3 class="font-bold text-black mb-6">Hubungi Kami</h3>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <span class="material-icons-outlined">location_on</span>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Lokasi</div>
                                    <div class="contact-value">Jl. Batusari Komplek Buana Citra Ciwastra No.D-3, Buahbatu, Kec. Bojongsoang, Kabupaten Bandung, Jawa Barat 40287</div>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <span class="material-icons-outlined">email</span>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value">inovindocorp@gmail.com</div>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <span class="material-icons-outlined">phone</span>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">No WA/Telepon</div>
                                    <div class="contact-value">+62 817 - 251 - 196</div>
                                </div>
                            </div>
                        </div>
                        <form class="space-y-6">
                            <div>
                                <label class="sr-only" for="name">Name</label>
                                <input class="w-full bg-white border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:ring-black focus:border-black text-black shadow-sm" id="name" name="name" placeholder="Nama Anda" type="text" />
                            </div>
                            <div>
                                <label class="sr-only" for="email">Email</label>
                                <input class="w-full bg-white border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:ring-black focus:border-black text-black shadow-sm" id="email" name="email" placeholder="Email Anda" type="email" />
                            </div>
                            <div>
                                <label class="sr-only" for="message">Message</label>
                                <textarea class="w-full bg-white border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:ring-black focus:border-black text-black shadow-sm" id="message" name="message" placeholder="Pesan Anda" rows="6"></textarea>
                            </div>
                            <button class="w-full btn-primary bg-black text-white font-medium py-3 px-8 rounded-lg" type="submit">
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </section>
            </div>
            <footer class="py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="gradient-dark text-center py-4 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-700">Copyright ©2025 by digicity.id</p>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- Modal Portofolio -->
    <div id="portfolioModal" class="modal">
        <div class="modal-content">
            <span class="close-modal close-modal-portfolio">&times;</span>
            <div class="p-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-white mb-4"></h2>
                <div class="aspect-video bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-6"></div>
                <p id="modalDescription" class="text-gray-300 mb-6"></p>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-white mb-2">Teknologi yang Digunakan:</h3>
                    <div id="modalTech" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Layanan -->
    <div id="layananModal" class="modal">
        <div class="modal-content">
            <span class="close-modal close-modal-layanan">&times;</span>
            <div class="p-6">
                <h2 id="layananModalTitle" class="text-2xl font-bold text-white mb-4"></h2>
                <div id="layananModalImageContainer" class="aspect-video bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-6 overflow-hidden">
                    <img id="layananModalImage" src="" alt="" class="w-full h-full object-cover hidden">
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-white mb-2">Harga:</h3>
                    <p id="layananModalPrice" class="text-gray-300"></p>
                </div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-white mb-2">Deskripsi:</h3>
                    <p id="layananModalDescription" class="text-gray-300"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Logika untuk Header Sticky ---
            const header = document.getElementById('header');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // --- Logika untuk Tombol "Lihat Lainnya" ---
            const layananToggleBtn = document.getElementById('layananToggleBtn');
            const layananGridWrapper = document.getElementById('layananGridWrapper');
            const layananToggleText = document.getElementById('layananToggleText');
            const layananToggleIcon = document.getElementById('layananToggleIcon');
            const scrollIndicator = document.getElementById('scrollIndicator');
            
            // Event listener untuk tombol toggle
            if (layananToggleBtn) {
                layananToggleBtn.addEventListener('click', function() {
                    const isExpanded = layananGridWrapper.classList.contains('expanded');

                    if (isExpanded) {
                        // Tutup
                        layananGridWrapper.classList.remove('expanded');
                        layananToggleText.textContent = 'Lihat Lainnya';
                        layananToggleIcon.textContent = 'expand_more';
                        scrollIndicator.style.display = 'none';
                    } else {
                        // Buka sisa layanan dalam container scroll
                        layananGridWrapper.classList.add('expanded');
                        layananToggleText.textContent = 'Tutup';
                        layananToggleIcon.textContent = 'expand_less';
                        
                        // Tampilkan indikator scroll
                        scrollIndicator.style.display = 'flex';
                    }
                });
            }

            // --- Logika untuk Modal Layanan ---
            const layananModal = document.getElementById('layananModal');
            const layananModalTitle = document.getElementById('layananModalTitle');
            const layananModalImage = document.getElementById('layananModalImage');
            const layananModalImageContainer = document.getElementById('layananModalImageContainer');
            const layananModalPrice = document.getElementById('layananModalPrice');
            const layananModalDescription = document.getElementById('layananModalDescription');
            const closeModalLayanan = document.querySelector('.close-modal-layanan');
            
            // Event listener untuk tombol detail pada setiap kartu layanan
            const serviceDetailBtns = document.querySelectorAll('.service-detail-btn');
            serviceDetailBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const serviceCard = this.closest('.service-card');
                    
                    // Ambil data dari atribut data
                    const serviceName = serviceCard.getAttribute('data-service-name');
                    const servicePrice = serviceCard.getAttribute('data-service-price');
                    const serviceDescription = serviceCard.getAttribute('data-service-description');
                    const serviceImage = serviceCard.getAttribute('data-service-image');
                    
                    // Isi modal dengan data
                    layananModalTitle.textContent = serviceName;
                    layananModalPrice.textContent = servicePrice;
                    layananModalDescription.textContent = serviceDescription;
                    
                    // Tampilkan gambar jika ada
                    if (serviceImage) {
                        layananModalImage.src = serviceImage;
                        layananModalImage.alt = serviceName;
                        layananModalImage.classList.remove('hidden');
                    } else {
                        layananModalImage.classList.add('hidden');
                    }
                    
                    // Tampilkan modal
                    layananModal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Mencegah scroll di background
                });
            });
            
            // Event listener untuk tombol close modal
            closeModalLayanan.addEventListener('click', function() {
                layananModal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Kembalikan scroll
            });
            
            // Tutup modal saat klik di luar konten modal
            window.addEventListener('click', function(event) {
                if (event.target === layananModal) {
                    layananModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Kembalikan scroll
                }
            });
            
            // --- Logika untuk Modal Portofolio ---
            const portfolioModal = document.getElementById('portfolioModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            const modalTech = document.getElementById('modalTech');
            const closeModalPortfolio = document.querySelector('.close-modal-portfolio');
            
            // Event listener untuk tombol detail pada setiap kartu portofolio
            const portfolioBtns = document.querySelectorAll('.portfolio-btn');
            portfolioBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const title = this.getAttribute('data-title');
                    const description = this.getAttribute('data-description');
                    const tech = this.getAttribute('data-tech').split(', ');
                    
                    modalTitle.textContent = title;
                    modalDescription.textContent = description;
                    
                    // Kosongkan dan isi ulang container teknologi
                    modalTech.innerHTML = '';
                    tech.forEach(techItem => {
                        const techBadge = document.createElement('span');
                        techBadge.className = 'bg-gray-700 text-white text-sm px-3 py-1 rounded-full';
                        techBadge.textContent = techItem;
                        modalTech.appendChild(techBadge);
                    });
                    
                    portfolioModal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Mencegah scroll di background
                });
            });
            
            // Event listener untuk tombol close modal
            closeModalPortfolio.addEventListener('click', function() {
                portfolioModal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Kembalikan scroll
            });
            
            // Tutup modal saat klik di luar konten modal
            window.addEventListener('click', function(event) {
                if (event.target === portfolioModal) {
                    portfolioModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Kembalikan scroll
                }
            });
            
            // --- Logika untuk navigasi aktif berdasarkan scroll ---
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            function updateActiveNav() {
                const scrollPosition = window.scrollY + 100;
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        navLinks.forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('data-section') === sectionId) {
                                link.classList.add('active');
                            }
                        });
                    }
                });
            }
            
            // Update navigasi aktif saat scroll
            window.addEventListener('scroll', updateActiveNav);
            
            // Smooth scroll untuk navigasi
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);
                    
                    if (targetSection) {
                        const offsetTop = targetSection.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Inisialisasi navigasi aktif
            updateActiveNav();
            
            // --- Logika untuk Mobile Menu ---
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileNav = document.getElementById('mobileNav');
            
            mobileMenuBtn.addEventListener('click', function() {
                this.classList.toggle('active');
                mobileNav.classList.toggle('active');
            });
            
            // Tutup mobile menu saat link diklik
            const mobileNavLinks = document.querySelectorAll('.mobile-nav .nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenuBtn.classList.remove('active');
                    mobileNav.classList.remove('active');
                });
            });
        });
    </script>
</body>

</html>
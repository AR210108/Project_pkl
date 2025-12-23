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
                        primary: "#0f172a", // Biru sangat tua mendekati hitam
                        "background-light": "#ffffff", // Putih untuk background utama
                        "background-dark": "#f8fafc", // Putih sangat terang untuk mode gelap
                        "card-light": "#111827", // Hitam untuk kartu
                        "card-dark": "#1f2937", // Abu-abu untuk kartu mode gelap
                        "text-light": "#111827", // Hitam untuk teks
                        "text-dark": "#f9fafb", // Putih terang untuk teks mode gelap
                        "border-light": "#e5e7eb", // Abu-abu terang untuk border
                        "border-dark": "#4b5563", // Abu-abu untuk border mode gelap
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
        
        /* Sticky Navigation */
        .sticky-header {
            position: fixed;
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
        
        /* Padding untuk konten agar tidak tertutup header */
        .main-content {
            padding-top: 80px;
        }
        
        /* Hamburger Menu Animation */
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
        
        /* Mobile Navigation - Menyatu dengan Header */
        .mobile-nav {
            max-height: 0;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.95); /* Warna sama dengan header */
            transition: max-height 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 0 0 1rem 1rem; /* Border radius hanya di bagian bawah */
        }
        
        .mobile-nav.active {
            max-height: 70vh; /* Tinggi maksimum saat aktif */
        }
        
        .mobile-nav-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .mobile-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.1); /* Border dengan warna primary */
            margin-bottom: 1rem;
        }
        
        .mobile-nav .brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a; /* Warna primary */
        }
        
        .mobile-nav nav {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-nav .nav-link {
            color: #0f172a; /* Warna primary */
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
            color: #0f172a; /* Warna primary */
            background-color: rgba(15, 23, 42, 0.1); /* Background dengan warna primary */
        }
        
        .mobile-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            width: 0;
            height: 2px;
            background-color: #0f172a; /* Warna primary */
            transition: width 0.3s ease;
        }
        
        .mobile-nav .nav-link:hover::after,
        .mobile-nav .nav-link.active::after {
            width: 30px;
        }
        
        .mobile-nav .login-btn {
            background-color: #0f172a; /* Warna primary */
            color: white; /* Teks putih */
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
        }
        
        .mobile-nav .login-btn:hover {
            background-color: rgba(15, 23, 42, 0.9); /* Warna primary dengan opacity */
            transform: translateY(-2px);
        }
        
        .mobile-nav .close-btn {
            color: #0f172a; /* Warna primary */
            font-size: 1.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .mobile-nav .close-btn:hover {
            background-color: rgba(15, 23, 42, 0.1); /* Background dengan warna primary */
            transform: rotate(90deg);
        }
        
        /* Gaya untuk Container Portofolio */
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
        
        /* Gaya untuk Container Layanan - Desktop */
        .layanan-container {
            max-height: 320px;
            overflow-y: hidden;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db #f3f4f6;
            transition: max-height 0.5s ease-in-out;
        }
        
        .layanan-container.expanded {
            max-height: 620px;
            overflow-y: auto;
        }
        
        .layanan-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .layanan-container::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }
        
        .layanan-container::-webkit-scrollbar-thumb {
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

        /* Media query untuk menampilkan/hide container layanan */
        @media (max-width: 768px) {
            .layanan-container {
                display: none;
            }
            .layanan-container-mobile {
                display: block;
            }
        }
        
        /* Active navigation link */
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #0f172a; /* Biru sangat tua mendekati hitam */
            transition: width 0.3s ease;
        }
        
        .nav-link:hover {
            color: #0f172a; /* Biru sangat tua mendekati hitam */
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link.active {
            color: #0f172a; /* Biru sangat tua mendekati hitam */
        }
        
        .nav-link.active::after {
            width: 100%;
        }
        
        /* Card hover effects */
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        
        /* Gradient backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #000000, #111827);
        }
        
        .gradient-dark {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }
        
        .gradient-subtle {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }
        
        /* Decorative elements */
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
        
        /* Mobile Layanan Improvements */
        .layanan-scroll-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                opacity: 0.6;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0.6;
            }
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
        
        /* Mobile service card improvements */
        @media (max-width: 768px) {
            .service-card {
                width: 280px !important;
                flex-shrink: 0;
            }
            
            .service-card .relative {
                min-height: 180px;
            }
            
            .service-card h3 {
                font-size: 1.125rem;
                margin-bottom: 0.5rem;
            }
            
            .service-card p {
                font-size: 0.875rem;
                line-height: 1.4;
            }
            
            .service-card .absolute span {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
        }
        
        /* Add shadow effect for mobile cards */
        .layanan-container-mobile .service-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        
        /* Contact Card Styles */
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
    </style>
</head>

<body class="bg-background-light text-text-light">
    <!-- Sticky Header -->
    <header id="header" class="sticky-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <!-- Brand Logo -->
                <div class="text-2xl font-bold text-black">Brand</div>
                
                <!-- Desktop Navigation - Centered -->
                <nav class="hidden lg:flex items-center space-x-8 absolute left-1/2 transform -translate-x-1/2">
                    <a class="nav-link text-sm font-medium text-gray-700 active" href="#beranda" data-section="beranda">Beranda</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#layanan" data-section="layanan">Layanan</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#tentang" data-section="tentang">Tentang</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#portofolio" data-section="portofolio">Portofolio</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#artikel" data-section="artikel">Artikel</a>
                    <a class="nav-link text-sm font-medium text-gray-700" href="#kontak" data-section="kontak">Kontak</a>
                </nav>
                
                <!-- Login Button -->
                <a class="hidden lg:block bg-black text-white text-sm font-medium py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors"
                    href="{{ url('/login') }}">
                    Login
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="lg:hidden flex flex-col justify-center items-center w-8 h-8 hamburger">
                    <span class="hamburger-line w-6 h-0.5 bg-black mb-1.5"></span>
                    <span class="hamburger-line w-6 h-0.5 bg-black mb-1.5"></span>
                    <span class="hamburger-line w-6 h-0.5 bg-black"></span>
                </button>
            </div>
            
            <!-- Mobile Navigation - Menyatu dengan Header -->
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
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="py-20 gradient-primary rounded-2xl shadow-lg relative overflow-hidden" id="beranda">
                <div class="decorative-circle circle-1"></div>
                <div class="decorative-circle circle-2"></div>
                <div class="decorative-circle circle-3"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid grid-cols-12">
                        <div class="col-start-1 col-span-12 text-center">
                            <h1 class="text-4xl md:text-7xl font-bold text-white mb-4">DIGITAL
                                AGENCY</h1>
                            <p class="mb-8 text-white/90 mx-auto max-w-2xl">Kami digital agency adalah perusahaan
                                yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai
                                layanan digital.</p>
                            <button
                                class="bg-white text-black font-medium py-3 px-8 rounded-lg hover:bg-gray-100 transition-colors shadow-md mx-auto">
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
                        <div id="layananContainer" class="layanan-container mb-10">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Total 10 UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">UI/UX</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Pembuatan Website</h3>
                                    <p class="text-sm text-gray-300">Desain dan pengembangan website profesional dan responsif.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Marketing</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">SEO Optimization</h3>
                                    <p class="text-sm text-gray-300">Meningkatkan peringkat website Anda di mesin pencari.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Sosmed</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Manajemen Sosial Media</h3>
                                    <p class="text-sm text-gray-300">Mengelola dan mengembangkan kehadiran brand di media sosial.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Ads</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Iklan Digital (Google Ads)</h3>
                                    <p class="text-sm text-gray-300">Kampanye iklan yang tertarget untuk meningkatkan traffic dan konversi.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Content</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Pembuatan Konten</h3>
                                    <p class="text-sm text-gray-300">Membuat konten menarik dan relevan untuk audiens Anda.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Strategy</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Strategi Brand</h3>
                                    <p class="text-sm text-gray-300">Membangun identitas brand yang kuat dan konsisten.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Mobile</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Pengembangan Aplikasi Mobile</h3>
                                    <p class="text-sm text-gray-300">Membangun aplikasi mobile native dan cross-platform untuk iOS dan Android.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Email</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Email Marketing</h3>
                                    <p class="text-sm text-gray-300">Membuat dan mengelola kampanye email marketing yang efektif.</p>
                                </div>
                                <div class="service-card bg-card-light p-6 rounded-2xl flex flex-col shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Analytics</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Analitik Digital</h3>
                                    <p class="text-sm text-gray-300">Menganalisis data digital untuk mengoptimalkan strategi pemasaran.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Layanan Container -->
                        <div id="layananContainerMobile" class="layanan-container-mobile mb-4">
                            <div class="flex gap-4" style="width: max-content;">
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Future UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Total 10 UI</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Digital Agency</h3>
                                    <p class="text-sm text-gray-300">Kota Bandung</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">UI/UX</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Pembuatan Website</h3>
                                    <p class="text-sm text-gray-300">Desain dan pengembangan website profesional dan responsif.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Marketing</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">SEO Optimization</h3>
                                    <p class="text-sm text-gray-300">Meningkatkan peringkat website Anda di mesin pencari.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Sosmed</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Manajemen Sosial Media</h3>
                                    <p class="text-sm text-gray-300">Mengelola dan mengembangkan kehadiran brand di media sosial.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Ads</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Iklan Digital (Google Ads)</h3>
                                    <p class="text-sm text-gray-300">Kampanye iklan yang tertarget untuk meningkatkan traffic dan konversi.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Content</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Pembuatan Konten</h3>
                                    <p class="text-sm text-gray-300">Membuat konten menarik dan relevan untuk audiens Anda.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Strategy</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Strategi Brand</h3>
                                    <p class="text-sm text-gray-300">Membangun identitas brand yang kuat dan konsisten.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Mobile</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Pengembangan Aplikasi Mobile</h3>
                                    <p class="text-sm text-gray-300">Membangun aplikasi mobile native dan cross-platform untuk iOS dan Android.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Email</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Email Marketing</h3>
                                    <p class="text-sm text-gray-300">Membuat dan mengelola kampanye email marketing yang efektif.</p>
                                </div>
                                <div class="service-card bg-card-light p-5 rounded-2xl flex flex-col w-72 flex-shrink-0 shadow-sm border border-border-light">
                                    <div class="relative pt-[75%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4">
                                        <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full">Analytics</span>
                                    </div>
                                    <h3 class="font-bold text-white mb-1">Analitik Digital</h3>
                                    <p class="text-sm text-gray-300">Menganalisis data digital untuk mengoptimalkan strategi pemasaran.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Indikator Scroll untuk Layanan (Hanya di Mobile) -->
                        <div class="layanan-scroll-indicator md:hidden">
                            <span>
                                <span class="material-icons-outlined">swipe</span>
                                Geser untuk melihat lebih banyak
                            </span>
                        </div>
                        
                        <!-- Tombol Toggle untuk Desktop -->
                        <div class="hidden md:flex justify-center">
                            <button id="layananToggleBtn" class="btn-primary bg-black text-white font-medium py-3 px-8 rounded-lg flex items-center">
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
                            <div class="portfolio-card bg-card-light p-6 rounded-2xl flex flex-col w-72 shadow-sm border border-border-light">
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
                                <button class="w-full btn-primary bg-black text-white text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" data-title="Travel Booking Platform" data-description="Platform pemesanan perjalanan terintegrasi dengan fitur pencarian tiket pesawat, hotel, dan paket liburan. Dilengkapi dengan sistem rekomendasi berbasis AI untuk pengalaman personalisasi." data-tech="Angular, Node.js, Express, MongoDB, Machine Learning">
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
                                artikel<br />--------------------<br />--------------------<br />--------------------<br />--------------------<br />--------------------</p>
                        </div>
                        <div class="space-y-6">
                            <div class="article-card flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-black mb-2">Isi artikel</h4>
                                    <p class="text-sm text-gray-700">
                                        --------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                            <div class="article-card flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-black mb-2">Isi artikel</h4>
                                    <p class="text-sm text-gray-700">
                                        --------------------<br />--------------------<br />--------------------</p>
                                </div>
                            </div>
                            <div class="article-card flex items-center gap-6">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-black mb-2">Isi artikel</h4>
                                    <p class="text-sm text-gray-700">
                                        --------------------<br />--------------------<br />--------------------</p>
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
                                <input class="w-full bg-white border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:border-black text-black shadow-sm" id="name" name="name" placeholder="Nama Anda" type="text" />
                            </div>
                            <div>
                                <label class="sr-only" for="email">Email</label>
                                <input class="w-full bg-white border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:border-black text-black shadow-sm" id="email" name="email" placeholder="Email Anda" type="email" />
                            </div>
                            <div>
                                <label class="sr-only" for="message">Message</label>
                                <textarea class="w-full bg-white border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:border-black text-black shadow-sm" id="message" name="message" placeholder="Pesan Anda" rows="6"></textarea>
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
        </main>
    </div>

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
                <div class="flex justify-between">
                    <button class="btn-primary bg-black text-white font-medium py-2 px-6 rounded-lg">
                        Kunjungi Website
                    </button>
                    <button class="bg-gray-700 text-white font-medium py-2 px-6 rounded-lg hover:bg-gray-600 transition-colors">
                        Hubungi Kami
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sticky Header
        const header = document.getElementById('header');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile Navigation
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const closeMobileNav = document.getElementById('closeMobileNav');
        
        // Pastikan navigasi mobile tersembunyi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            mobileNav.classList.remove('active');
            mobileMenuBtn.classList.remove('active');
        });
        
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenuBtn.classList.toggle('active');
            mobileNav.classList.toggle('active');
        });
        
        closeMobileNav.addEventListener('click', function() {
            mobileMenuBtn.classList.remove('active');
            mobileNav.classList.remove('active');
        });
        
        // Close mobile nav when clicking on a link
        const mobileNavLinks = mobileNav.querySelectorAll('.nav-link');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuBtn.classList.remove('active');
                mobileNav.classList.remove('active');
            });
        });
        
        // Also close when clicking on login button
        const mobileLoginBtn = mobileNav.querySelector('.login-btn');
        mobileLoginBtn.addEventListener('click', function() {
            mobileMenuBtn.classList.remove('active');
            mobileNav.classList.remove('active');
        });
        
        // Navigation active state
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('section[id]');
        
        // Function to update active navigation link
        function updateActiveNav() {
            const scrollY = window.pageYOffset;
            
            // Handle special case for beranda section when at the top of the page
            if (scrollY < 100) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('data-section') === 'beranda') {
                        link.classList.add('active');
                    }
                });
                return;
            }
            
            sections.forEach(section => {
                const sectionHeight = section.offsetHeight;
                const sectionTop = section.offsetTop - 100;
                const sectionId = section.getAttribute('id');
                
                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('data-section') === sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }
        
        // Add click event to navigation links
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Remove active class from all links
                navLinks.forEach(l => l.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // If it's a link to a section, update active state on scroll
                const targetSection = this.getAttribute('href');
                if (targetSection.startsWith('#')) {
                    // Update active state when scrolling to section
                    setTimeout(updateActiveNav, 100);
                }
            });
        });
        
        // Update active navigation on scroll
        window.addEventListener('scroll', updateActiveNav);
        
        // Initial call to set active nav on page load
        updateActiveNav();
        
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
                    techTag.className = 'bg-gray-700 text-white text-sm font-medium py-1 px-3 rounded-full';
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
<!DOCTYPE html>
<html class="scroll-smooth" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Digital Agency Landing Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Tambahkan AOS (Animate On Scroll) library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
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
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-in-left': 'slideInLeft 0.6s ease-out',
                        'slide-in-right': 'slideInRight 0.6s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'gradient-shift': 'gradientShift 8s ease infinite',
                        'typing': 'typing 3.5s steps(40, end)',
                        'blink': 'blink 0.75s step-end infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        slideInLeft: {
                            '0%': { transform: 'translateX(-50px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        slideInRight: {
                            '0%': { transform: 'translateX(50px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        gradientShift: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' }
                        },
                        typing: {
                            'from': { width: '0' },
                            'to': { width: '100%' }
                        },
                        blink: {
                            'from, to': { borderColor: 'transparent' },
                            '50%': { borderColor: 'currentColor' }
                        }
                    }
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
        
        /* Tambahkan padding-top pada konten utama untuk menghindari tertutup header */
        .main-content {
            padding-top: 80px;
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
            animation: modalFadeIn 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #d1d5db;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease, transform 0.2s ease;
        }
        
        .close-modal:hover {
            color: #f9fafb;
            transform: scale(1.1);
        }
        
        /* Gaya untuk Container Layanan Tambahan (Desktop) */
        .layanan-grid-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.6s ease-in-out;
            position: relative;
        }

        .layanan-grid-wrapper.expanded {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 8px;
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
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
            z-index: 1;
        }
        
        .service-card:hover::before {
            left: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .portfolio-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .portfolio-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
            z-index: 1;
        }
        
        .portfolio-card:hover::before {
            left: 100%;
        }
        
        .portfolio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .article-card {
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .article-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
            z-index: 1;
        }
        
        .article-card:hover::before {
            left: 100%;
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
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
        }
        
        .gradient-dark {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
        }
        
        .gradient-subtle {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
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
            animation: float 6s ease-in-out infinite;
        }
        
        .circle-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            bottom: -100px;
            left: -50px;
            animation: float 8s ease-in-out infinite;
        }
        
        .circle-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #000000, #111827);
            top: 50%;
            left: -75px;
            animation: float 7s ease-in-out infinite;
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }
        
        .contact-item:hover {
            transform: translateX(5px);
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
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        
        .contact-item:hover .contact-icon {
            transform: scale(1.1);
            background-color: #1e293b;
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
            transform: translateY(-2px);
        }
        
        .service-detail-btn .material-icons-outlined {
            font-size: 0.75rem;
            margin-left: 0.25rem;
            transition: transform 0.2s ease;
        }
        
        .service-detail-btn:hover .material-icons-outlined {
            transform: translateX(2px);
        }
        
        /* WhatsApp Button Styles - Original Colors */
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: black;
            border-radius: 0.5rem;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
            position: relative;
            overflow: hidden;
        }
        
        .whatsapp-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .whatsapp-btn:hover::before {
            left: 100%;
        }
        
        .whatsapp-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            background-color: #f8fafc;
        }
        
        .whatsapp-btn .bx {
            margin-right: 8px;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        
        .whatsapp-btn:hover .bx {
            transform: scale(1.2);
        }
        
        /* Loading indicator */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Animasi untuk section beranda */
        .hero-title {
            animation: fadeInUp 1s ease-out;
        }
        
        .hero-description {
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        .hero-button {
            animation: fadeInUp 1s ease-out 0.4s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Animasi untuk gambar portofolio dan artikel */
        .image-container {
            overflow: hidden;
            position: relative;
        }
        
        .image-container img {
            transition: transform 0.5s ease;
        }
        
        .image-container:hover img {
            transform: scale(1.05);
        }
        
        /* Animasi untuk form kontak */
        .form-input {
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Animasi untuk footer */
        .footer-container {
            animation: fadeIn 1s ease-out 0.8s both;
        }
        
        /* Animasi untuk notifikasi */
        .notification-container {
            animation: slideInRight 0.5s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Animasi untuk tombol scroll ke atas */
        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #0f172a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 40;
        }
        
        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-to-top:hover {
            background-color: #1e293b;
            transform: translateY(-5px);
        }
        
        /* Animasi untuk preload */
        .preload {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        
        .preload.hide {
            opacity: 0;
            pointer-events: none;
        }
        
        .preload-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f4f6;
            border-top: 5px solid #0f172a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
       .hero-bg {
    background-image: url('images/bg-hero.gif');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    
}

/* ===== HERO ELEGANT ANIMATION ===== */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-title {
    animation: fadeUp 0.9s ease-out forwards;
}

.hero-desc {
    animation: fadeUp 0.9s ease-out forwards;
    animation-delay: 0.4s;
    opacity: 0;
}

.hero-btn {
    animation: fadeUp 0.9s ease-out forwards;
    animation-delay: 0.6s;
    opacity: 0;
}

/* Perbaikan responsivitas tambahan */
@media (max-width: 640px) {
    .main-content {
        padding-top: 70px;
    }
    
    .hero-title {
        font-size: 2.5rem;
        line-height: 1.2;
    }
    
    .hero-desc {
        font-size: 1rem;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
        padding: 15px;
    }
    
    .close-modal {
        top: 10px;
        right: 15px;
        font-size: 24px;
    }
    
    .contact-card {
        padding: 1rem;
    }
    
    .contact-item {
        margin-bottom: 1rem;
    }
    
    .contact-icon {
        width: 36px;
        height: 36px;
    }
    
    .scroll-to-top {
        width: 45px;
        height: 45px;
        bottom: 15px;
        right: 15px;
    }
    
    .sticky-header {
        padding: 10px 0;
    }
    
    .notification-container {
        max-width: calc(100% - 40px);
        right: 20px;
        left: 20px;
    }
}

/* Perbaikan untuk tampilan tablet */
@media (min-width: 641px) and (max-width: 1024px) {
    .hero-title {
        font-size: 3.5rem;
    }
    
    .service-card {
        width: 220px !important;
    }
    
    .portfolio-card {
        width: 280px !important;
    }
}

/* Perbaikan untuk sentuhan pada perangkat mobile */
@media (hover: none) {
    .nav-link:hover::after,
    .nav-link:hover {
        color: inherit;
        background-color: transparent;
        transform: none;
    }
    
    .nav-link.active {
        color: #0f172a;
        background-color: rgba(15, 23, 42, 0.08);
    }
    
    .service-card:hover,
    .portfolio-card:hover,
    .article-card:hover {
        transform: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .service-card:active,
    .portfolio-card:active,
    .article-card:active {
        transform: scale(0.98);
    }
    
    .btn-primary:active {
        transform: scale(0.98);
    }
    
    .login-btn-desktop:active {
        transform: scale(0.98);
    }
    
    .whatsapp-btn:active {
        transform: scale(0.98);
    }
}

/* Perbaikan untuk modal pada mobile */
@media (max-width: 640px) {
    .modal-content {
        border-radius: 1rem 1rem 0 0;
        margin: 0;
        width: 100%;
        max-width: 100%;
        height: 90vh;
        overflow-y: auto;
        position: fixed;
        bottom: 0;
        top: auto;
        animation: slideUpModal 0.3s ease-out;
    }
    
    @keyframes slideUpModal {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }
    
    .close-modal {
        top: 15px;
        right: 15px;
    }
}

/* Perbaikan untuk form pada mobile */
@media (max-width: 640px) {
    .form-input {
        font-size: 16px; /* Mencegah zoom pada iOS */
    }
    
    #contactForm {
        margin-top: 1rem;
    }
}

/* Perbaikan untuk navigasi mobile */
@media (max-width: 768px) {
    .mobile-nav {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .mobile-nav-content {
        max-height: calc(70vh - 60px);
        overflow-y: auto;
    }
}

/* Perbaikan untuk layanan pada mobile */
@media (max-width: 640px) {
    .layanan-container-mobile {
        padding: 0 1rem;
        margin: 0 -1rem;
    }
    
    .service-detail-btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
}

/* Perbaikan untuk portofolio pada mobile */
@media (max-width: 640px) {
    .portfolio-container {
        padding: 0 1rem;
        margin: 0 -1rem;
    }
    
    .portfolio-card {
        width: 280px !important;
    }
}

/* Perbaikan untuk artikel pada mobile */
@media (max-width: 640px) {
    #articlesContainer {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .article-card img {
        height: 200px;
        object-fit: cover;
    }
}

    </style>
</head>

<body class="bg-background-light text-text-light">
    <!-- Preload Screen -->
    <div class="preload" id="preload">
        <div class="preload-spinner"></div>
    </div>
    
    <!-- Scroll to Top Button -->
    <div class="scroll-to-top" id="scrollToTop">
        <span class="material-icons-outlined">keyboard_arrow_up</span>
    </div>
    
    <!-- Sticky Header -->
    <header id="header" class="sticky-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
            <div class="flex justify-between items-center">
                <img src="{{ asset('images/logo_inovindo.jpg') }}" alt="Inovindo Logo" class="h-8 w-auto object-contain">
                <nav class="hidden lg:flex items-center space-x-2 md:space-x-4 absolute left-1/2 transform -translate-x-1/2">
                    <a class="nav-link text-xs md:text-sm font-medium text-gray-700 active" href="#beranda" data-section="beranda">Beranda</a>
                    <a class="nav-link text-xs md:text-sm font-medium text-gray-700" href="#layanan" data-section="layanan">Layanan</a>
                    <a class="nav-link text-xs md:text-sm font-medium text-gray-700" href="#tentang" data-section="tentang">Tentang</a>
                    <a class="nav-link text-xs md:text-sm font-medium text-gray-700" href="#portofolio" data-section="portofolio">Portofolio</a>
                    <a class="nav-link text-xs md:text-sm font-medium text-gray-700" href="#artikel" data-section="artikel">Artikel</a>
                    <a class="nav-link text-xs md:text-sm font-medium text-gray-700" href="#kontak" data-section="kontak">Kontak</a>
                </nav>
                <a class="hidden lg:block login-btn-desktop bg-black text-white text-xs md:text-sm font-medium py-2 px-4 md:px-6 rounded-lg hover:bg-gray-800 transition-colors"
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
          <section
    id="beranda"
    class="hero-bg rounded-2xl shadow-lg relative overflow-hidden
           min-h-[60vh] md:min-h-[70vh] flex items-center justify-center text-center"
>
    <div class="hero-overlay"></div>

    <div class="relative z-10 px-4 sm:px-6 md:px-8">
        <h1 class="hero-title text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-bold text-white mb-4">
            DIGITAL AGENCY
        </h1>

        <p class="hero-desc mb-6 md:mb-8 text-white/90 max-w-2xl mx-auto text-sm md:text-base">
            Kami digital agency adalah perusahaan yang membantu bisnis lain...
        </p>

        <button class="hero-btn whatsapp-btn mx-auto">
            <i class="bx bxl-whatsapp"></i>
            <span class="hidden sm:inline">Hubungi Kami</span>
            <span class="sm:hidden">Hubungi</span>
        </button>
    </div>
</section>

            
            <section class="py-12 md:py-24 text-left bg-background-light" id="layanan">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="gradient-subtle p-6 md:p-8 lg:p-12 rounded-2xl shadow-sm" data-aos="fade-up">
                        <div class="flex flex-wrap justify-between items-start mb-8 md:mb-10">
                            <h2 class="text-xl md:text-2xl font-bold text-black mb-4 md:mb-0">List Layanan</h2>
                            <p class="max-w-sm text-gray-700 text-xs md:text-sm">Kami digital agency adalah
                                perusahaan yang membantu bisnis lain membawa ke produk atau jasanya secara online.</p>
                        </div>
                        
                        <!-- Desktop Layanan Container -->
                        <div id="layananContainer" class="mb-8 md:mb-10">
                            @if($layanans->isNotEmpty())
                                <!-- ======================================= -->
                                <!-- BAGIAN 1: GRID LAYANAN STATIS (4 PERTAMA) -->
                                <!-- Grid ini tidak akan pernah tergulung -->
                                <!-- ======================================= -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-4 md:mb-6">
                                    @foreach($layanans->take(4) as $index => $layanan)
                                        <div class="service-card bg-card-light p-3 md:p-4 rounded-xl flex flex-col shadow-sm border border-border-light" 
                                             data-service-id="{{ $layanan->id ?? $loop->iteration }}"
                                             data-service-name="{{ $layanan->nama_layanan ?? 'Layanan ' . $loop->iteration }}"
                                             data-service-price="{{ $layanan->harga ? 'Rp ' . number_format($layanan->harga, 0, ',', '.') : 'Tidak tersedia' }}"
                                             data-service-description="{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . $loop->iteration . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}"
                                             data-service-image="{{ $layanan->foto ? Storage::url($layanan->foto) : '' }}"
                                             data-aos="fade-up"
                                             data-aos-delay="{{ $index * 100 }}">
                                            <div class="relative pt-[60%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-3 overflow-hidden image-container">
                                                @if($layanan->harga)
                                                    <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-black text-xs font-semibold px-2 py-1 rounded-full z-10">
                                                        Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if($layanan->foto)
                                                    <img src="{{ Storage::url($layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover">
                                                @else
                                                    <div class="absolute inset-0 flex items-center justify-center text-white">
                                                        <span class="text-3xl md:text-4xl font-bold">{{ $loop->iteration }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-white mb-1 text-xs md:text-sm">{{ $layanan->nama_layanan ?? 'Layanan ' . $loop->iteration }}</h3>
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
                                    <hr class="my-4 md:my-6 border-gray-300">

                                    <!-- ======================================= -->
                                    <!-- BAGIAN 2: GRID LAYANAN YANG DAPAT DI-SCROLL -->
                                    <!-- Container ini yang akan di-scroll -->
                                    <!-- ======================================= -->
                                    <div id="layananGridWrapper" class="layanan-grid-wrapper">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                                            @foreach($remainingLayanans as $index => $layanan)
                                                <div class="service-card bg-card-light p-3 md:p-4 rounded-xl flex flex-col shadow-sm border border-border-light"
                                                     data-service-id="{{ $layanan->id ?? ($loop->iteration + 4) }}"
                                                     data-service-name="{{ $layanan->nama_layanan ?? 'Layanan ' . ($loop->iteration + 4) }}"
                                                     data-service-price="{{ $layanan->harga ? 'Rp ' . number_format($layanan->harga, 0, ',', '.') : 'Tidak tersedia' }}"
                                                     data-service-description="{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . ($loop->iteration + 4) . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}"
                                                     data-service-image="{{ $layanan->foto ? Storage::url($layanan->foto) : '' }}"
                                                     data-aos="fade-up"
                                                     data-aos-delay="{{ ($index + 4) * 100 }}">
                                                    <div class="relative pt-[60%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-3 overflow-hidden image-container">
                                                        @if($layanan->harga)
                                                            <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-black text-xs font-semibold px-2 py-1 rounded-full z-10">
                                                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                        @if($layanan->foto)
                                                            <img src="{{ Storage::url($layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover">
                                                        @else
                                                            <div class="absolute inset-0 flex items-center justify-center text-white">
                                                                <span class="text-3xl md:text-4xl font-bold">{{ $loop->iteration + 4 }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <h3 class="font-bold text-white mb-1 text-xs md:text-sm">{{ $layanan->nama_layanan ?? 'Layanan ' . ($loop->iteration + 4) }}</h3>
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
                                        <div class="service-card bg-card-light p-3 md:p-4 rounded-xl flex flex-col w-56 flex-shrink-0 shadow-sm border border-border-light"
                                             data-service-id="{{ $layanan->id ?? ($index + 1) }}"
                                             data-service-name="{{ $layanan->nama_layanan ?? 'Layanan ' . ($index + 1) }}"
                                             data-service-price="{{ $layanan->harga ? 'Rp ' . number_format($layanan->harga, 0, ',', '.') : 'Tidak tersedia' }}"
                                             data-service-description="{{ $layanan->deskripsi ?? 'Deskripsi untuk layanan ' . ($index + 1) . '. Ini adalah contoh deskripsi yang berbeda untuk setiap layanan.' }}"
                                             data-service-image="{{ $layanan->foto ? Storage::url($layanan->foto) : '' }}">
                                            <div class="relative pt-[60%] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-3 overflow-hidden image-container">
                                                @if($layanan->harga)
                                                    <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm text-black text-xs font-semibold px-2 py-1 rounded-full z-10">
                                                        Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if($layanan->foto)
                                                    <img src="{{ Storage::url($layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover">
                                                @else
                                                    <div class="absolute inset-0 flex items-center justify-center text-white">
                                                        <span class="text-3xl md:text-4xl font-bold">{{ $index + 1 }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-white mb-1 text-xs md:text-sm">{{ $layanan->nama_layanan ?? 'Layanan ' . ($index + 1) }}</h3>
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
                                <button id="layananToggleBtn" class="btn-primary bg-black text-white font-medium py-2 md:py-3 px-6 md:px-8 rounded-lg flex items-center">
                                    <span id="layananToggleText">Lihat Lainnya</span>
                                    <span id="layananToggleIcon" class="material-icons-outlined ml-2">expand_more</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <section class="py-8 md:py-12 lg:py-24 text-center max-w-3xl mx-auto" id="tentang">
                    <div class="flex items-center mb-6 md:mb-8" data-aos="fade-up">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-2xl md:text-3xl font-bold text-black about-title">TENTANG</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-sm md:text-base about-description" data-aos="fade-up" data-aos-delay="100">Kami digital agency adalah perusahaan
                        yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai layanan
                        digital. Layanan yang ditawarkan meliputi strategi pemasaran digital, pembuatan dan pengelolaan
                        situs web, manajemen media sosial, optimasi mesin pencari (SEO), serta kampanye iklan di Google
                        Ads, iklan display, dan video.</p>
                </section>
                <!-- PERBAIKAN: BAGIAN PORTOFOLIO SUDAH DINAMIS -->
                <section class="py-8 md:py-12 lg:py-24" id="portofolio">
                    <div class="flex items-center mb-8 md:mb-12" data-aos="fade-up">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-2xl md:text-3xl font-bold text-black">PORTOFOLIO</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <div class="portfolio-container pb-4">
                        <!-- Container ini akan diisi secara dinamis oleh JavaScript -->
                        <div class="flex gap-4 md:gap-8" style="width: max-content;">
                            <!-- Portofolio akan dimuat melalui JavaScript -->
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <div class="bg-gray-100 rounded-full px-3 md:px-4 py-1 md:py-2 flex items-center space-x-2">
                            <span class="material-icons-outlined text-xs md:text-sm text-gray-500">swipe</span>
                            <span class="text-xs md:text-sm text-gray-500">Geser untuk melihat lebih banyak</span>
                        </div>
                    </div>
                </section>
                <section class="py-8 md:py-12 lg:py-24" id="artikel">
                    <div class="flex items-center mb-8 md:mb-12" data-aos="fade-up">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-2xl md:text-3xl font-bold text-black">ARTIKEL</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <div id="articlesContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                        <!-- Artikel akan dimuat melalui JavaScript -->
                    </div>
                </section>
                <section class="py-8 md:py-12 lg:py-24" id="kontak">
                    <div class="flex items-center mb-8 md:mb-12" data-aos="fade-up">
                        <div class="flex-grow h-px bg-gray-300"></div>
                        <h2 class="mx-4 text-2xl md:text-3xl font-bold text-black">KONTAK</h2>
                        <div class="flex-grow h-px bg-gray-300"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 lg:gap-16">
                        <div class="contact-card" data-aos="fade-right">
                            <h3 class="font-bold text-black mb-4 md:mb-6 text-lg md:text-xl">Hubungi Kami</h3>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <span class="material-icons-outlined">location_on</span>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Lokasi</div>
                                    <div class="contact-value contact-address text-sm md:text-base">Jl. Batusari Komplek Buana Citra Ciwastra No.D-3, Buahbatu, Kec. Bojongsoang, Kabupaten Bandung, Jawa Barat 40287</div>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <span class="material-icons-outlined">email</span>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value contact-email text-sm md:text-base">inovindocorp@gmail.com</div>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <span class="material-icons-outlined">phone</span>
                                </div>
                                <div class="contact-text">
                                    <div class="contact-label">No WA/Telepon</div>
                                    <div class="contact-value contact-phone text-sm md:text-base">+62 817 - 251 - 196</div>
                                </div>
                            </div>
                        </div>
                        <form class="space-y-4 md:space-y-6" id="contactForm" data-aos="fade-left">
                            <div>
                                <label class="sr-only" for="name">Name</label>
                                <input class="w-full bg-white border-gray-300 rounded-lg py-2 md:py-3 px-3 md:px-4 focus:ring-2 focus:ring-black focus:ring-black focus:border-black text-black shadow-sm form-input text-sm md:text-base" id="name" name="name" placeholder="Nama Anda" type="text" required />
                            </div>
                            <div>
                                <label class="sr-only" for="email">Email</label>
                                <input class="w-full bg-white border-gray-300 rounded-lg py-2 md:py-3 px-3 md:px-4 focus:ring-2 focus:ring-black focus:ring-black focus:border-black text-black shadow-sm form-input text-sm md:text-base" id="email" name="email" placeholder="Email Anda" type="email" required />
                            </div>
                            <div>
                                <label class="sr-only" for="message">Message</label>
                                <textarea class="w-full bg-white border-gray-300 rounded-lg py-2 md:py-3 px-3 md:px-4 focus:ring-2 focus:ring-black focus:ring-black focus:border-black text-black shadow-sm form-input text-sm md:text-base" id="message" name="message" placeholder="Pesan Anda" rows="6" required></textarea>
                            </div>
                            <button class="w-full btn-primary bg-black text-white font-medium py-2 md:py-3 px-6 md:px-8 rounded-lg text-sm md:text-base" type="submit" id="submitBtn">
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </section>
            </div>
            <footer class="py-6 md:py-8 mt-8 md:mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="gradient-dark text-center py-3 md:py-4 rounded-lg shadow-sm footer-container">
                        <p class="text-xs md:text-sm text-gray-700">Copyright ©2025 by digicity.id</p>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- PERBAIKAN: MODAL PORTOFOLIO SUDAH DITAMBAHKAN ELEMEN GAMBAR -->
    <div id="portfolioModal" class="modal">
        <div class="modal-content">
            <span class="close-modal close-modal-portfolio">&times;</span>
            <div class="p-4 md:p-6">
                <h2 id="modalTitle" class="text-xl md:text-2xl font-bold text-white mb-4"></h2>
                <div id="modalImageContainer" class="aspect-video bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4 md:mb-6 overflow-hidden">
                    <img id="modalImage" src="" alt="" class="w-full h-full object-cover hidden">
                </div>
                <p id="modalDescription" class="text-gray-300 mb-4 md:mb-6 text-sm md:text-base"></p>
                <div class="mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-white mb-2">Teknologi yang Digunakan:</h3>
                    <div id="modalTech" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Layanan -->
    <div id="layananModal" class="modal">
        <div class="modal-content">
            <span class="close-modal close-modal-layanan">&times;</span>
            <div class="p-4 md:p-6">
                <h2 id="layananModalTitle" class="text-xl md:text-2xl font-bold text-white mb-4"></h2>
                <div id="layananModalImageContainer" class="aspect-video bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4 md:mb-6 overflow-hidden">
                    <img id="layananModalImage" src="" alt="" class="w-full h-full object-cover hidden">
                </div>
                <div class="mb-4">
                    <h3 class="text-base md:text-lg font-semibold text-white mb-2">Harga:</h3>
                    <p id="layananModalPrice" class="text-gray-300 text-sm md:text-base"></p>
                </div>
                <div class="mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-white mb-2">Deskripsi:</h3>
                    <p id="layananModalDescription" class="text-gray-300 text-sm md:text-base"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi Popup -->
    <div id="notificationPopup" class="fixed top-4 right-4 bg-white rounded-lg shadow-lg p-3 md:p-4 max-w-sm transform translate-x-full transition-transform duration-300 z-50 notification-container">
        <div class="flex items-start">
            <div id="notificationIcon" class="flex-shrink-0 mr-3">
                <span class="material-icons-outlined text-green-500">check_circle</span>
            </div>
            <div class="flex-1">
                <h4 id="notificationTitle" class="text-sm font-medium text-gray-900">Berhasil</h4>
                <p id="notificationMessage" class="text-xs md:text-sm text-gray-500 mt-1">Pesan Anda telah terkirim</p>
            </div>
            <button id="closeNotification" class="ml-4 text-gray-400 hover:text-gray-500">
                <span class="material-icons-outlined text-sm">close</span>
            </button>
        </div>
    </div>

    <!-- Tambahkan script AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Halaman beranda dimuat. Memulai inisialisasi...");
            
            // Inisialisasi AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 100,
                delay: 0
            });
            
            // Sembunyikan preload screen setelah halaman dimuat
            window.addEventListener('load', function() {
                const preload = document.getElementById('preload');
                if (preload) {
                    setTimeout(() => {
                        preload.classList.add('hide');
                    }, 500);
                }
            });
            
            // Scroll to top button
            const scrollToTopBtn = document.getElementById('scrollToTop');
            if (scrollToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        scrollToTopBtn.classList.add('visible');
                    } else {
                        scrollToTopBtn.classList.remove('visible');
                    }
                });
                
                scrollToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // --- Inisialisasi EmailJS ---
            try {
                (function() {
                    emailjs.init("oife-AzTJDPCJCsXd");
                })();
            } catch (e) {
                console.error("Gagal menginisialisasi EmailJS:", e);
            }
            
            // --- Ambil data kontak dari API ---
            let contactData = {
                email: 'inovindocorp@gmail.com',
                phone: '+62 817 - 251 - 196',
                address: 'Jl. Batusari Komplek Buana Citra Ciwastra No.D-3, Buahbatu, Kec. Bojongsoang, Kabupaten Bandung, Jawa Barat 40287',
                whatsapp_message: 'Halo, saya tertarik dengan layanan yang ditawarkan. Mohon informasi lebih lanjut.'
            };

            fetch('/api/contact')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        contactData = data.data;
                        updateContactElements();
                    }
                })
                .catch(error => {
                    console.error('Error fetching contact data:', error);
                    updateContactElements();
                });

            function updateContactElements() {
                const emailElements = document.querySelectorAll('.contact-email');
                const phoneElements = document.querySelectorAll('.contact-phone');
                const addressElements = document.querySelectorAll('.contact-address');
                
                if(emailElements) emailElements.forEach(el => el.textContent = contactData.email);
                if(phoneElements) phoneElements.forEach(el => el.textContent = contactData.phone);
                if(addressElements) addressElements.forEach(el => el.textContent = contactData.address);
            }

            // --- Ambil data tentang dari API ---
            let aboutData = {
                title: 'TENTANG',
                description: 'Kami digital agency adalah perusahaan yang membantu bisnis lain membawa ke produk atau jasanya secara online melalui berbagai layanan digital.'
            };

            fetch('/api/about')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        aboutData = data.data;
                        updateAboutElements();
                    }
                })
                .catch(error => {
                    console.error('Error fetching about data:', error);
                    updateAboutElements();
                });

            function updateAboutElements() {
                const titleElements = document.querySelectorAll('.about-title');
                const descriptionElements = document.querySelectorAll('.about-description');
                
                if(titleElements) titleElements.forEach(el => el.textContent = aboutData.title);
                if(descriptionElements) descriptionElements.forEach(el => el.textContent = aboutData.description);
            }

            // --- Logika untuk Header Sticky ---
            const header = document.getElementById('header');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                });
            } else {
                console.error("Elemen #header tidak ditemukan! Ini bisa menyebabkan error saat logout.");
            }

            // --- Logika untuk Tombol "Lihat Lainnya" ---
            const layananToggleBtn = document.getElementById('layananToggleBtn');
            const layananGridWrapper = document.getElementById('layananGridWrapper');
            const layananToggleText = document.getElementById('layananToggleText');
            const layananToggleIcon = document.getElementById('layananToggleIcon');
            const scrollIndicator = document.getElementById('scrollIndicator');
            
            if (layananToggleBtn && layananGridWrapper) {
                layananToggleBtn.addEventListener('click', function() {
                    const isExpanded = layananGridWrapper.classList.contains('expanded');
                    if (isExpanded) {
                        layananGridWrapper.classList.remove('expanded');
                        if(layananToggleText) layananToggleText.textContent = 'Lihat Lainnya';
                        if(layananToggleIcon) layananToggleIcon.textContent = 'expand_more';
                        if(scrollIndicator) scrollIndicator.style.display = 'none';
                    } else {
                        layananGridWrapper.classList.add('expanded');
                        if(layananToggleText) layananToggleText.textContent = 'Tutup';
                        if(layananToggleIcon) layananToggleIcon.textContent = 'expand_less';
                        if(scrollIndicator) scrollIndicator.style.display = 'flex';
                    }
                });
            } else {
                console.error("Elemen untuk toggle layanan tidak ditemukan. Pastikan Anda berada di halaman utama, bukan halaman admin.");
            }

            // --- PERBAIKAN: Logika untuk Modal Layanan (TYPO SUDAH DIPERBAIKI) ---
            const layananModal = document.getElementById('layananModal');
            const closeModalLayanan = document.querySelector('.close-modal-layanan');
            
            // Fungsi untuk membuka modal layanan
            function openLayananModal(serviceCard) {
                if (!serviceCard || !layananModal) {
                    console.error("Tidak dapat membuka modal: elemen tidak ditemukan.");
                    return;
                }

                const serviceName = serviceCard.getAttribute('data-service-name');
                const servicePrice = serviceCard.getAttribute('data-service-price');
                const serviceDescription = serviceCard.getAttribute('data-service-description');
                const serviceImage = serviceCard.getAttribute('data-service-image');
                
                // Deklarasi variabel yang BENAR
                const layananModalTitle = document.getElementById('layananModalTitle');
                const layananModalImage = document.getElementById('layananModalImage');
                const layananModalPrice = document.getElementById('layananModalPrice');
                const layananModalDescription = document.getElementById('layananModalDescription');
                const layananModalImageContainer = document.getElementById('layananModalImageContainer');

                // --- PERBAIKAN SEJATI: Gunakan variabel yang BENAR di dalam kondisi if ---
                if(layananModalTitle) layananModalTitle.textContent = serviceName;
                if(layananModalPrice) layananModalPrice.textContent = servicePrice;
                if(layananModalDescription) layananModalDescription.textContent = serviceDescription;
                
                if (serviceImage && layananModalImage) {
                    layananModalImage.src = serviceImage;
                    layananModalImage.alt = serviceName;
                    layananModalImage.classList.remove('hidden');
                } else if (layananModalImage) {
                    layananModalImage.classList.add('hidden');
                }
                
                layananModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            // Gunakan event delegation untuk menangani klik pada tombol detail
            document.addEventListener('click', function(e) {
                if (e.target.closest('.service-detail-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.service-detail-btn');
                    const serviceCard = btn.closest('.service-card');
                    openLayananModal(serviceCard);
                }
            });
            
            if (closeModalLayanan) {
                closeModalLayanan.addEventListener('click', function() {
                    if(layananModal) {
                        layananModal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });
            }
            
            window.addEventListener('click', function(event) {
                if (event.target === layananModal) {
                    if(layananModal) {
                        layananModal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                }
            });
            
            // --- Logika untuk Modal Portofolio (Penutup) ---
            const portfolioModal = document.getElementById('portfolioModal');
            const closeModalPortfolio = document.querySelector('.close-modal-portfolio');
            
            if (closeModalPortfolio) {
                closeModalPortfolio.addEventListener('click', function() {
                    if(portfolioModal) {
                        portfolioModal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });
            }
            
            window.addEventListener('click', function(event) {
                if (event.target === portfolioModal) {
                    if(portfolioModal) {
                        portfolioModal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                }
            });
            
            // --- Logika untuk Tombol WhatsApp ---
            const whatsappBtn = document.getElementById('whatsappBtn');
            if (whatsappBtn) {
                whatsappBtn.addEventListener('click', function() {
                    const whatsappBtnText = document.getElementById('whatsappBtnText');
                    if(whatsappBtnText) {
                        whatsappBtnText.innerHTML = '<span class="loading"></span> Menghubungi...';
                        whatsappBtn.disabled = true;
                        
                        const phoneNumber = contactData.phone.replace(/\s/g, '').replace(/-/g, '').replace('+', '');
                        const message = encodeURIComponent(contactData.whatsapp_message);
                        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;
                        
                        window.open(whatsappUrl, '_blank');
                        
                        setTimeout(() => {
                            whatsappBtnText.textContent = 'Hubungi Kami';
                            whatsappBtn.disabled = false;
                        }, 1000);
                    }
                });
            } else {
                console.error("KESALAH KRITIS: Tombol #whatsappBtn tidak ditemukan.");
            }
            
            // --- Logika untuk Form Kontak dengan EmailJS ---
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(contactForm);
                    const name = formData.get('name');
                    const email = formData.get('email');
                    const message = formData.get('message');
                    
                    if (!name || !email || !message) {
                        showNotification('Error', 'Mohon lengkapi semua field yang diperlukan.', 'error');
                        return;
                    }
                    
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        showNotification('Error', 'Mohon masukkan alamat email yang valid.', 'error');
                        return;
                    }
                    
                    const originalText = submitBtn.textContent;
                    if(submitBtn) {
                        submitBtn.innerHTML = '<span class="loading"></span> Mengirim...';
                        submitBtn.disabled = true;
                    }
                    
                    emailjs.send('service_v697ved', 'template_5hgs37q', {
                        from_name: name,
                        from_email: email,
                        message: message,
                        to_email: 'aseprinda212008@gmail.com'
                    })
                    .then(function(response) {
                        showNotification('Berhasil', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.', 'success');
                        contactForm.reset();
                        if(submitBtn) {
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                        }
                    }, function(error) {
                        console.error('Gagal mengirim email:', error);
                        showNotification('Error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.', 'error');
                        if(submitBtn) {
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                        }
                    });
                });
            } else {
                console.error("KESALAH KRITIS: Form #contactForm tidak ditemukan.");
            }
            
            // --- Fungsi Notifikasi ---
            function showNotification(title, message, type = 'success') {
                const popup = document.getElementById('notificationPopup');
                if (!popup) {
                    console.error("KESALAH KRITIS: Popup notifikasi #notificationPopup tidak ditemukan.");
                    return;
                }

                const icon = document.getElementById('notificationIcon');
                const titleEl = document.getElementById('notificationTitle');
                const messageEl = document.getElementById('notificationMessage');
                
                if(titleEl) titleEl.textContent = title;
                if(messageEl) messageEl.textContent = message;
                
                if (icon) {
                    if (type === 'success') {
                        icon.innerHTML = '<span class="material-icons-outlined text-green-500">check_circle</span>';
                    } else if (type === 'error') {
                        icon.innerHTML = '<span class="material-icons-outlined text-red-500">error</span>';
                    } else if (type === 'warning') {
                        icon.innerHTML = '<span class="material-icons-outlined text-yellow-500">warning</span>';
                    }
                }
                
                popup.classList.remove('translate-x-full');
                
                setTimeout(() => {
                    if(popup) popup.classList.add('translate-x-full');
                }, 5000);
            }
            
            const closeNotificationBtn = document.getElementById('closeNotification');
            if (closeNotificationBtn) {
                closeNotificationBtn.addEventListener('click', function() {
                    const popup = document.getElementById('notificationPopup');
                    if(popup) popup.classList.add('translate-x-full');
                });
            }

            // --- Logika untuk navigasi aktif berdasarkan scroll ---
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            function updateActiveNav() {
                const scrollPosition = window.scrollY + 100;
                
                if(sections && navLinks) {
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
            }
            
            if (sections && navLinks) {
                window.addEventListener('scroll', updateActiveNav);
                navLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (!this.getAttribute('href')) {
                            console.error("KESALAH KRITIS: Link navigasi tidak memiliki atribut href:", this);
                            return;
                        }
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        const targetSection = document.querySelector(targetId);
                        
                        if (targetSection) {
                            const offsetTop = targetSection.offsetTop - 80;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        } else {
                            console.error("KESALAH KRITIS: Target section tidak ditemukan untuk href:", targetId);
                        }
                    });
                });
                updateActiveNav();
            } else {
                console.error("KESALAH KRITIS: Elemen navigasi (sections atau navLinks) tidak ditemukan.");
            }
            
            // --- Logika untuk Mobile Menu ---
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileNav = document.getElementById('mobileNav');
            
            if (mobileMenuBtn && mobileNav) {
                mobileMenuBtn.addEventListener('click', function() {
                    this.classList.toggle('active');
                    mobileNav.classList.toggle('active');
                });
                
                const mobileNavLinks = document.querySelectorAll('.mobile-nav .nav-link');
                if (mobileNavLinks) {
                    mobileNavLinks.forEach(link => {
                        link.addEventListener('click', function() {
                            mobileMenuBtn.classList.remove('active');
                            mobileNav.classList.remove('active');
                        });
                    });
                }
            } else {
                console.error("KESALAH KRITIS: Elemen mobile menu tidak ditemukan.");
            }

            // --- MEMUAT ARTIKEL ---
            function loadArticles() {
                console.log("Memuat data artikel...");
                fetch('/api/articles')
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data artikel yang diterima:', data);

                        const articlesContainer = document.getElementById('articlesContainer');
                        if (!articlesContainer) {
                            console.error("KESALAH KRITIS: Container #articlesContainer tidak ditemukan! Artikel tidak akan dimuat.");
                            return;
                        }
                        
                        articlesContainer.innerHTML = '';

                        if (data.success && data.data && data.data.length > 0) {
                            const firstArticle = data.data[0];
                            const firstArticleHtml = `
                                <div class="article-card flex flex-col" data-aos="fade-up">
                                    <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl mb-4 overflow-hidden image-container">
                                        ${firstArticle.image ? 
                                            `<img src="/storage/${firstArticle.image}" alt="${firstArticle.title}" class="w-full h-full object-cover">` : 
                                            `<div class="flex items-center justify-center h-full text-white"><span class="material-icons-outlined text-4xl">article</span></div>`
                                        }
                                    </div>
                                    <p class="text-xs md:text-sm text-gray-500 mb-1">${new Date(firstArticle.updated_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                    <h3 class="text-lg md:text-xl font-bold text-black mb-2">${firstArticle.title}</h3>
                                    <p class="text-sm md:text-base text-gray-700 leading-relaxed">${firstArticle.excerpt || firstArticle.content.substring(0, 200) + '...'}</p>
                                </div>
                            `;
                            articlesContainer.innerHTML = firstArticleHtml;

                            if (data.data.length > 1) {
                                let otherArticlesHtml = '<div class="space-y-6">';
                                for (let i = 1; i < data.data.length; i++) {
                                    const article = data.data[i];
                                    otherArticlesHtml += `
                                        <div class="article-card flex items-center gap-6" data-aos="fade-up" data-aos-delay="${i * 100}">
                                            <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex-shrink-0 overflow-hidden image-container">
                                                ${article.image ? 
                                                    `<img src="/storage/${article.image}" alt="${article.title}" class="w-full h-full object-cover">` : 
                                                    `<div class="flex items-center justify-center h-full text-white"><span class="material-icons-outlined text-2xl">article</span></div>`
                                                }
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-black mb-2 text-sm md:text-base">${article.title}</h4>
                                                <p class="text-sm text-gray-700 leading-relaxed">${article.excerpt || article.content.substring(0, 100) + '...'}</p>
                                            </div>
                                        </div>
                                    `;
                                }
                                otherArticlesHtml += '</div>';
                                articlesContainer.innerHTML += otherArticlesHtml;
                            }
                        } else {
                            articlesContainer.innerHTML = `
                                <div class="col-span-2 text-center py-12">
                                    <span class="material-icons-outlined text-6xl text-gray-300">article</span>
                                    <h3 class="text-xl font-semibold text-gray-500 mt-4">Belum Ada Artikel</h3>
                                    <p class="text-gray-400 mt-2">Artikel akan segera tersedia. Silakan kunjungi kembali nanti.</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching articles:', error);
                        const articlesContainer = document.getElementById('articlesContainer');
                        if (articlesContainer) {
                            articlesContainer.innerHTML = `
                                <div class="col-span-2 text-center py-12">
                                    <span class="material-icons-outlined text-6xl text-red-300">error_outline</span>
                                    <h3 class="text-xl font-semibold text-red-500 mt-4">Gagal Memuat Artikel</h3>
                                    <p class="text-red-400 mt-2">Terjadi kesalahan, silakan refresh halaman.</p>
                                </div>
                            `;
                        }
                    });
            }

            // --- FUNGSI UNTUK MEMUAT PORTOFOLIO (VERSI DIPERBAIKI) ---
            function loadPortfolios() {
                console.log("Memuat data portofolio...");
                fetch('/api/portfolios')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data portofolio yang diterima:', data);

                        const portfolioContainer = document.querySelector('.portfolio-container > div');
                        if (!portfolioContainer) {
                            console.error("Container portofolio tidak ditemukan!");
                            return;
                        }
                        
                        portfolioContainer.innerHTML = '';

                        if (data.success && data.data && data.data.length > 0) {
                            data.data.forEach((portfolio, index) => {
                                const techArray = portfolio.technologies_used ? portfolio.technologies_used.split(',').map(tech => tech.trim()) : [];
                                const techString = techArray.join(', ');
                                const imageUrl = portfolio.image ? `/storage/${portfolio.image}` : null;
                                
                                const portfolioHtml = `
                                    <div class="portfolio-card bg-card-light p-4 md:p-6 rounded-2xl flex flex-col w-64 md:w-72 shadow-sm border border-border-light" data-aos="fade-up" data-aos-delay="${index * 100}">
                                        <div class="relative flex-grow aspect-[4/5] bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg mb-4 overflow-hidden image-container">
                                            ${portfolio.image ? 
                                                `<img src="/storage/${portfolio.image}" alt="${portfolio.title}" class="w-full h-full object-cover">` : 
                                                `<div class="flex items-center justify-center h-full text-white"><span class="material-icons-outlined text-4xl">work</span></div>`
                                            }
                                            <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm w-8 h-8 rounded-full flex items-center justify-center text-black hover:bg-white transition-colors">
                                                <span class="material-icons-outlined text-base">arrow_forward</span>
                                            </button>
                                        </div>
                                        <h3 class="font-bold text-white text-base md:text-lg mb-4">${portfolio.title}</h3>
                                        <button class="w-full btn-primary bg-black text-white text-xs md:text-sm font-medium py-2 px-4 rounded-lg flex justify-between items-center portfolio-btn" 
                                                data-title="${portfolio.title}" 
                                                data-description="${portfolio.description}" 
                                                data-tech="${techString}"
                                                data-image="${imageUrl}">
                                            <span>Lihat Detail</span>
                                            <span class="material-icons-outlined text-base">chevron_right</span>
                                        </button>
                                    </div>
                                `;
                                portfolioContainer.innerHTML += portfolioHtml;
                            });
                            
                            // Setelah portofolio dimuat, pasang event listener untuk tombol detail
                            attachPortfolioEventListeners();
                        } else {
                            portfolioContainer.innerHTML = `
                                <div class="text-center py-12" style="width: 100%;">
                                    <span class="material-icons-outlined text-6xl text-gray-300">work</span>
                                    <h3 class="text-xl font-semibold text-gray-500 mt-4">Belum Ada Portofolio</h3>
                                    <p class="text-gray-400 mt-2">Portofolio akan segera tersedia.</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching portfolios:', error);
                        const portfolioContainer = document.querySelector('.portfolio-container > div');
                        if (portfolioContainer) {
                            portfolioContainer.innerHTML = `
                                <div class="text-center py-12" style="width: 100%;">
                                    <span class="material-icons-outlined text-6xl text-red-300">error_outline</span>
                                    <h3 class="text-xl font-semibold text-red-500 mt-4">Gagal Memuat Portofolio</h3>
                                    <p class="text-red-400 mt-2">Terjadi kesalahan, silakan refresh halaman.</p>
                                </div>
                            `;
                        }
                    });
            }

            // --- FUNGSI UNTUK MENAMBAHKAN EVENT LISTENER (VERSI DIPERBAIKI) ---
            function attachPortfolioEventListeners() {
                const portfolioBtns = document.querySelectorAll('.portfolio-btn');
                if (portfolioBtns) {
                    portfolioBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const title = this.getAttribute('data-title');
                            const description = this.getAttribute('data-description');
                            const tech = this.getAttribute('data-tech').split(', ');
                            const imageUrl = this.getAttribute('data-image');
                            
                            const portfolioModal = document.getElementById('portfolioModal');
                            if(portfolioModal) {
                                const modalTitle = document.getElementById('modalTitle');
                                const modalDescription = document.getElementById('modalDescription');
                                const modalTech = document.getElementById('modalTech');
                                const modalImage = document.getElementById('modalImage');
                                const modalImageContainer = document.getElementById('modalImageContainer');

                                if(modalTitle) modalTitle.textContent = title;
                                if(modalDescription) modalDescription.textContent = description;
                                
                                if(modalTech) {
                                    modalTech.innerHTML = '';
                                    tech.forEach(techItem => {
                                        const techBadge = document.createElement('span');
                                        techBadge.className = 'bg-gray-700 text-white text-sm px-3 py-1 rounded-full';
                                        techBadge.textContent = techItem;
                                        modalTech.appendChild(techBadge);
                                    });
                                }

                                // --- PERBAIKAN: LOGIKA UNTUK MENAMPILKAN GAMBAR DI MODAL ---
                                if (modalImage && modalImageContainer) {
                                    if (imageUrl) {
                                        modalImage.src = imageUrl;
                                        modalImage.alt = title;
                                        modalImage.classList.remove('hidden');
                                        modalImageContainer.classList.remove('bg-gradient-to-br', 'from-gray-800', 'to-gray-900');
                                    } else {
                                        modalImage.classList.add('hidden');
                                        modalImageContainer.classList.add('bg-gradient-to-br', 'from-gray-800', 'to-gray-900');
                                    }
                                }
                                
                                portfolioModal.style.display = 'block';
                                document.body.style.overflow = 'hidden';
                            }
                        });
                    });
                }
            }

            // --- PANGGIL FUNGSI UNTUK MEMUAT DATA ---
            loadArticles();
            loadPortfolios();
        }); // <-- AKHIR DARI document.addEventListener
    </script>
</body>

</html>
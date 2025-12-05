<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Digital Agency</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6330C1",
                        "background-light": "#F3F4F6",
                        "background-dark": "#121212",
                    },
                    fontFamily: {
                        display: ["Sora", "sans-serif"],
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Sora', sans-serif; }
        /* PERBAIKAN: Tambahkan transisi untuk animasi yang lebih halus */
        .animate-fade {
            transition: opacity 0.5s ease-in-out;
        }
        .opacity-0 {
            opacity: 0;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-gray-900 dark:text-white antialiased">

    <!-- Toggle dark mode -->
    <button onclick="toggleDarkMode()" class="fixed top-4 right-4 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 p-2 rounded-lg z-10">
        <span class="material-icons-outlined">dark_mode</span>
    </button>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white dark:bg-[#1A1A1A] rounded-2xl shadow-lg flex overflow-hidden animate-fade">
            
            <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">

                <h1 class="text-3xl font-bold">Selamat Datang</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Silakan login ke Digital Agency</p>

                <!-- PERBAIKAN: Tampilkan pesan error dengan lebih baik -->
                @if(session('error'))
                    <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-200 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form class="mt-8 space-y-6" method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email"
                            class="@error('email') border-red-500 @enderror mt-1 block w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 rounded-lg focus:ring-primary focus:border-primary">
                        <!-- PERBAIKAN: Tampilkan error spesifik untuk field email -->
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <div class="relative mt-1">
                            <input id="password" type="password" name="password" required placeholder="Masukkan password"
                                class="@error('password') border-red-500 @enderror block w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 rounded-lg focus:ring-primary focus:border-primary">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                                <span id="toggleIcon" class="material-icons-outlined">visibility_off</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary/90 transition-colors duration-200">
                        Login
                    </button>
                </form>
            </div>

            <div class="hidden md:block md:w-1/2">
                <!-- PERBAIKAN: Hapus typo kurung kurawal yang berlebih -->
                <img src="{{ asset('images/login-bg.jpg') }}" alt="Login Background" class="w-full h-full object-cover">
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            let input = document.getElementById("password");
            let icon = document.getElementById("toggleIcon");

            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "visibility";
            } else {
                input.type = "password";
                icon.textContent = "visibility_off";
            }
        }

        function toggleDarkMode() {
            document.documentElement.classList.toggle("dark");
            localStorage.setItem("theme", document.documentElement.classList.contains("dark") ? "dark" : "light");
        }

        // Set theme dari localStorage saat halaman dimuat
        if (localStorage.getItem("theme") === "dark" || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }

        // PERBAIKAN: Animasi fade-in yang lebih halus
        window.addEventListener('DOMContentLoaded', () => {
            const element = document.querySelector(".animate-fade");
            if(element) {
                element.classList.add("opacity-0");
                setTimeout(() => {
                    element.classList.remove("opacity-0");
                }, 50); // Delay singkat agar transisi terlihat
            }
        });
    </script>

</body>
</html>
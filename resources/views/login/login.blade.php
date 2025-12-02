<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>

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
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-gray-900 dark:text-white antialiased">

    <!-- Toggle dark mode -->
    <button onclick="toggleDarkMode()" class="fixed top-4 right-4 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 p-2 rounded-lg">
        <span class="material-icons-outlined">dark_mode</span>
    </button>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white dark:bg-[#1A1A1A] rounded-2xl shadow-lg flex overflow-hidden animate-fade">
            
            <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">

                <h1 class="text-3xl font-bold">Selamat Datang</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Silakan login ke Digital Agency</p>

                <!-- Error message -->
                @if(session('error'))
                    <div class="mt-4 p-3 bg-red-500 text-white rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-4 p-3 bg-red-500 text-white rounded">
                        Email atau password salah
                    </div>
                @endif

                <form class="mt-8 space-y-6" method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div>
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="Masukan email"
                            class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 rounded">
                    </div>

                    <div>
                        <label>Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required placeholder="Masukan password"
                                class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 rounded">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                                <span id="toggleIcon" class="material-icons-outlined">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-primary text-white font-semibold rounded hover:bg-primary/90">
                        Login
                    </button>
                </form>
            </div>

            <div class="hidden md:block md:w-1/2">
                <img src="{{ asset('images/login-bg.jpg') }}" class="w-full h-full object-cover">
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

        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        }

        document.querySelector(".animate-fade")?.classList.add("opacity-0");
        setTimeout(() => {
            document.querySelector(".animate-fade")?.classList.remove("opacity-0");
        }, 50);
    </script>

</body>
</html>

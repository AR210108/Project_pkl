<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        .material-icons-outlined {
            font-feature-settings: 'liga';
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#6330C1",
                        "background-light": "#F3F4F6", // Light gray for light mode
                        "background-dark": "#121212", // Very dark gray for dark mode
                    },
                    fontFamily: {
                        display: ["Sora", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem", // 8px
                    },
                },
            },
        };
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-white antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl mx-auto bg-white dark:bg-[#1A1A1A] rounded-2xl shadow-lg flex overflow-hidden">
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
                <div class="w-full max-w-md mx-auto">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Selamat Datang Di</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Login Digital Agensy</p>
                    <form class="mt-8 space-y-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                for="email">Email</label>
                            <div class="mt-1">
                                <input autocomplete="email"
                                    class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    id="email" name="email" placeholder="Masukan Email Anda" required=""
                                    type="email" />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                for="password">Password</label>
                            <div class="mt-1 relative">
                                <input autocomplete="current-password"
                                    class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    id="password" name="password" placeholder="Masukan Password Anda" required=""
                                    type="password" />
                                <button
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-400"
                                    type="button">
                                    <span class="material-icons-outlined text-xl">visibility_off</span>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input checked=""
                                    class="h-4 w-4 text-primary bg-gray-200 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-primary"
                                    id="remember-me" name="remember-me" type="checkbox" />
                                <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300"
                                    for="remember-me">Lihat Password</label>
                            </div>
                        </div>
                        <div>
                            <button
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                                type="submit">
                                Log in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden md:block md:w-1/2">
                <!-- Ganti URL di bawah ini dengan gambar Anda -->
                <img alt="Gambar Anda" class="h-full w-full object-cover" src="{{ asset('images/login-bg.jpg') }}" />
            </div>
        </div>
    </div>

</body>

</html>

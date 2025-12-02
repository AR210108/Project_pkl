{{-- karyawan/templet/header.blade.php --}}
<header class="w-full max-w-7xl mx-auto mb-8">
    <div class="flex justify-between items-center py-4 border-b border-border-color">
        <h1 class="text-xl sm:text-2xl font-bold text-text-primary">Brand</h1>
        
        <!-- Mobile Menu Button -->
        <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-md text-text-primary hover:bg-slate-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-4 sm:gap-8 font-medium">
            <a class="hover:text-primary transition-colors" href="/karyawan">Beranda</a>
            <a class="text-primary font-semibold" href="/absensi">Absensi</a>
            <a class="hover:text-primary transition-colors" href="/list">Manage Tugas</a>
        </nav>
        
        <!-- Desktop Logout Button -->
        <form action="/logout" method="POST" class="hidden md:block">
            @csrf
            <button type="submit" class="bg-slate-100 text-text-primary px-4 sm:px-6 py-2 rounded-md font-semibold hover:bg-slate-200 transition-colors">
                Logout
            </button>
        </form>
    </div>
    
    <!-- Mobile Navigation -->
    <nav id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-b border-border-color relative z-50">
        <div class="flex flex-col space-y-3">
            <a class="hover:text-primary transition-colors py-2" href="/karyawan">Beranda</a>
            <a class="text-primary font-semibold py-2" href="/absensi">Absensi</a>
            <a class="hover:text-primary transition-colors py-2" href="/list">Manage Tugas</a>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-slate-100 text-text-primary px-4 py-2 rounded-md font-semibold hover:bg-slate-200 transition-colors w-full">
                    Logout
                </button>
            </form>
        </div>
    </nav>
</header>

<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            mobileMenu.classList.toggle('hidden');
            console.log('Menu toggled');
        } else {
            console.error('Mobile menu not found');
        }
    }
</script>
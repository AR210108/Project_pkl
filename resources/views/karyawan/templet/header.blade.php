{{-- karyawan/templet/header.blade.php --}}
<header class="w-full max-w-7xl mx-auto mb-8">
    <div class="flex justify-between items-center py-4 border-b border-border-color">
        <h1 class="text-xl sm:text-2xl font-bold text-text-primary">Brand</h1>
        
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-text-primary hover:bg-slate-100">
            <span class="material-icons">menu</span>
        </button>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-4 sm:gap-8 font-medium">
            <a class="hover:text-primary transition-colors" href="#">Beranda</a>
            <a class="text-primary font-semibold" href="#">Absensi</a>
            <a class="hover:text-primary transition-colors" href="#">Manage Tugas</a>
        </nav>
        
        <!-- Desktop Logout Button -->
        <button class="hidden md:block bg-slate-100 text-text-primary px-4 sm:px-6 py-2 rounded-md font-semibold hover:bg-slate-200 transition-colors">
            Logout
        </button>
    </div>
    
    <!-- Mobile Navigation -->
    <nav id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-b border-border-color">
        <div class="flex flex-col space-y-3">
            <a class="hover:text-primary transition-colors py-2" href="#">Beranda</a>
            <a class="text-primary font-semibold py-2" href="#">Absensi</a>
            <a class="hover:text-primary transition-colors py-2" href="#">Manage Tugas</a>
            <button class="bg-slate-100 text-text-primary px-4 py-2 rounded-md font-semibold hover:bg-slate-200 transition-colors w-full">
                Logout
            </button>
        </div>
    </nav>
</header>
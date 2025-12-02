{{-- sidebar.blade.php --}}
<aside class="w-64 glass-effect p-6 flex flex-col">
    <div class="mb-12">
        <h1 class="text-3xl font-bold gradient-text">Brand</h1>
        <div class="h-1 w-16 bg-gradient-to-r from-primary to-secondary rounded-full mt-2"></div>
    </div>
    
    <nav class="flex-1 space-y-2">
        <a class="flex items-center gap-3 p-3 rounded-lg bg-primary bg-opacity-10 text-primary font-medium" href="#">
            <span class="material-icons-outlined">home</span>
            Beranda
        </a>
        <a class="flex items-center gap-3 p-3 rounded-lg text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" href="#">
            <span class="material-icons-outlined">groups</span>
            Data Karyawan
        </a>
        <a class="flex items-center gap-3 p-3 rounded-lg text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" href="#">
            <span class="material-icons-outlined">list_alt</span>
            Data Layanan
        </a>
        <a class="flex items-center gap-3 p-3 rounded-lg text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" href="#">
            <span class="material-icons-outlined">person</span>
            Absensi
        </a>
    </nav>
    
    <div class="mt-auto pt-6 border-t border-surface-light dark:border-surface-dark">
        <a class="flex items-center gap-3 p-3 rounded-lg text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" href="#">
            <span class="material-icons-outlined">logout</span>
            Log Out
        </a>
    </div>
</aside>
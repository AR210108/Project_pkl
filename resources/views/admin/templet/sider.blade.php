{{-- sidebar.blade.php --}}
<aside class="w-64 glass-effect p-6 flex flex-col">
    <div class="mb-12">
        <h1 class="text-3xl font-bold gradient-text">Brand</h1>
        <div class="h-1 w-16 bg-gradient-to-r from-primary to-secondary rounded-full mt-2"></div>
    </div>
    
    <nav class="flex-1 space-y-2">
        {{-- Menu Beranda --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('/admin') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/admin">
            <span class="material-icons-outlined">home</span>
            Beranda
        </a>
        
        {{-- Menu Data Karyawan --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('data_karyawan*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/admin/data_karyawan">
            <span class="material-icons-outlined">groups</span>
            Data Karyawan
        </a>
        
        {{-- Menu Data Layanan --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('data_layanan*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/admin/data_layanan">
            <span class="material-icons-outlined">list_alt</span>
            Data Layanan
        </a>
        
        {{-- Menu Absensi --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('data_absen*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/data_absen">
            <span class="material-icons-outlined">person</span>
            Absensi
        </a>
    </nav>
    
    <div class="mt-auto pt-6 border-t border-surface-light dark:border-surface-dark">
        {{-- Menu Logout --}}
        <a class="flex items-center gap-3 p-3 rounded-lg text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark transition-colors" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="material-icons-outlined">logout</span>
            Log Out
        </a>
        
        {{-- Form Logout (Hidden) --}}
        <form id="logout-form" action="/logout" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>
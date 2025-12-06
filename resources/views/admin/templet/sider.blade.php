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
        
        {{-- Menu Data User --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('data_user*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/data_user">
            <span class="material-icons-outlined">person</span>
            Data User
        </a>
        
        {{-- Menu Data Karyawan --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('admin/karyawan*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="{{ route('admin.data_karyawan') }}">
            <span class="material-icons-outlined">groups</span>
            Data Karyawan
        </a>
        
<a class="flex items-center gap-3 p-3 rounded-lg 
    {{ request()->is('admin/layanan*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} 
    transition-colors" 
    href="{{ route('admin.layanan.index') }}">

            <span class="material-icons-outlined">list_alt</span>
            Data Layanan
        </a>
        
        {{-- Menu Absensi --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('data_absen*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/data_absen">
            <span class="material-icons-outlined">person</span>
            Absensi
        </a>
        
        {{-- Menu Surat Kerjasama (Dropdown) --}}
        <div class="relative">
            <button class="flex items-center gap-3 p-3 rounded-lg w-full text-left {{ request()->is('surat_kerjasama*') || request()->is('template_surat*') || request()->is('list_tugos*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" onclick="toggleDropdown('surat-kerjasama-dropdown')">
                <span class="material-icons-outlined">description</span>
                Surat Kerjasama
                <span class="material-icons-outlined ml-auto transition-transform duration-200" id="surat-kerjasama-icon">expand_more</span>
            </button>
            
            {{-- Dropdown Submenu --}}
            <div id="surat-kerjasama-dropdown" class="pl-6 mt-1 space-y-1 {{ request()->is('surat_kerjasama*') || request()->is('template_surat*') || request()->is('list_tugos*') ? 'block' : 'hidden' }}">
                <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('template_surat*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/template_surat">
                    <span class="material-icons-outlined">article</span>
                    Template Surat
                </a>
                <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('list_tugos*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/list_surat">
                    <span class="material-icons-outlined">list</span>
                    List Surat
                </a>
            </div>
        </div>
        
        {{-- Menu Invoice & Kwitansi (Dropdown) --}}
        <div class="relative">
            <button class="flex items-center gap-3 p-3 rounded-lg w-full text-left {{ request()->is('invoice*') || request()->is('kwitansi*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" onclick="toggleDropdown('invoice-kwitansi-dropdown')">
                <span class="material-icons-outlined">receipt</span>
                Invoice & Kwitansi
                <span class="material-icons-outlined ml-auto transition-transform duration-200" id="invoice-kwitansi-icon">expand_more</span>
            </button>
            
            {{-- Dropdown Submenu --}}
            <div id="invoice-kwitansi-dropdown" class="pl-6 mt-1 space-y-1 {{ request()->is('invoice*') || request()->is('kwitansi*') ? 'block' : 'hidden' }}">
                <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('invoice*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/invoice">
                    <span class="material-icons-outlined">receipt_long</span>
                    Invoice
                </a>
                <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('kwitansi*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/kwitansi">
                    <span class="material-icons-outlined">request_quote</span>
                    Kwitansi
                </a>
            </div>
        </div>
        
        {{-- Menu Catatan Rapat --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('catatan_rapat*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/catatan_rapat">
            <span class="material-icons-outlined">summarize</span>
            Catatan Rapat
        </a>
        
        {{-- Menu Pengumuman --}}
        <a class="flex items-center gap-3 p-3 rounded-lg {{ request()->is('pengumuman*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }} transition-colors" href="/pengumuman">
            <span class="material-icons-outlined">campaign</span>
            Pengumuman
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

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const icon = document.getElementById(dropdownId.replace('-dropdown', '-icon'));
    
    dropdown.classList.toggle('hidden');
    
    // Rotate the icon
    if (dropdown.classList.contains('hidden')) {
        icon.textContent = 'expand_more';
    } else {
        icon.textContent = 'expand_less';
    }
}
</script>
{{-- sidebar.blade.php --}}
<aside class="w-64 glass-effect p-6 flex flex-col">
    <div class="mb-12">
        <h1 class="text-3xl font-bold gradient-text">Brand</h1>
        <div class="h-1 w-16 bg-gradient-to-r from-primary to-secondary rounded-full mt-2"></div>
    </div>
    
    <nav class="flex-1 space-y-2">

        {{-- Menu Beranda --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('/admin') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="/admin">
            <i class='bx bx-home'></i>
            Beranda
        </a>

        {{-- Menu Data User --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('data_user*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="/data_user">
            <i class='bx bx-user'></i>
            Data User
        </a>

        {{-- Menu Data Karyawan --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('admin/data_karyawan*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="/admin/data_karyawan">
            <i class='bx bx-group'></i>
            Data Karyawan
        </a>

        {{-- Menu Data Layanan --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('admin/layanan*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="{{ route('admin.layanan.index') }}">
            <i class='bx bx-list-ul'></i>
            Data Layanan
        </a>

        {{-- Menu Absensi --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('data_absen*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="/admin/absensi">
            <i class='bx bx-time'></i>
            Absensi
        </a>

        {{-- Menu Surat Kerjasama (Dropdown) --}}
        <div class="relative">
            <button class="flex items-center gap-3 p-3 rounded-lg w-full text-left 
                {{ request()->is('surat_kerjasama*') || request()->is('template_surat*') || request()->is('list_surat*') 
                ? 'bg-primary bg-opacity-10 text-primary font-medium' 
                : 'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
                onclick="toggleDropdown('surat-kerjasama-dropdown')">

                <i class='bx bx-file'></i>
                Surat Kerjasama
                <i class='bx bx-chevron-down ml-auto transition-transform duration-200' id="surat-kerjasama-icon"></i>
            </button>

            {{-- Dropdown --}}
            <div id="surat-kerjasama-dropdown" 
                class="pl-6 mt-1 space-y-1 
                {{ request()->is('surat_kerjasama*') || request()->is('template_surat*') || request()->is('list_surat*') ? 'block' : 'hidden' }}">

                <a class="flex items-center gap-3 p-3 rounded-lg 
                    {{ request()->is('template_surat*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
                    'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
                    href="/template_surat">
                    <i class='bx bx-file-blank'></i>
                    Template Surat
                </a>

                <a class="flex items-center gap-3 p-3 rounded-lg 
                    {{ request()->is('list_surat*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
                    'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
                    href="/list_surat">
                    <i class='bx bx-list-check'></i>
                    List Surat
                </a>
            </div>
        </div>

        {{-- Menu Invoice & Kwitansi --}}
        <div class="relative">
            <button class="flex items-center gap-3 p-3 rounded-lg w-full text-left 
                {{ request()->is('invoice*') || request()->is('kwitansi*') ? 
                'bg-primary bg-opacity-10 text-primary font-medium' : 
                'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
                onclick="toggleDropdown('invoice-kwitansi-dropdown')">

                <i class='bx bx-receipt'></i>
                Invoice & Kwitansi
                <i class='bx bx-chevron-down ml-auto transition-transform duration-200' id="invoice-kwitansi-icon"></i>
            </button>

            <div id="invoice-kwitansi-dropdown" 
                class="pl-6 mt-1 space-y-1 {{ request()->is('invoice*') || request()->is('kwitansi*') ? 'block' : 'hidden' }}">

                <a class="flex items-center gap-3 p-3 rounded-lg 
                    {{ request()->is('invoice*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
                    'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
                    href="/invoice">
                    <i class='bx bx-detail'></i>
                    Invoice
                </a>

                <a class="flex items-center gap-3 p-3 rounded-lg 
                    {{ request()->is('kwitansi*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
                    'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
                    href="/kwitansi">
                    <i class='bx bx-money'></i>
                    Kwitansi
                </a>
            </div>
        </div>

        {{-- Catatan Rapat --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('catatan_rapat*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="/catatan_rapat">
            <i class='bx bx-notepad'></i>
            Catatan Rapat
        </a>

        {{-- Pengumuman --}}
        <a class="flex items-center gap-3 p-3 rounded-lg 
            {{ request()->is('pengumuman*') ? 'bg-primary bg-opacity-10 text-primary font-medium' : 
            'text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark' }}" 
            href="/pengumuman">
            <i class='bx bx-megaphone'></i>
            Pengumuman
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-surface-light dark:border-surface-dark">
        {{-- Logout --}}
        <a class="flex items-center gap-3 p-3 rounded-lg text-subtle-light dark:text-subtle-dark hover:bg-surface-light dark:hover:bg-surface-dark" 
            href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='bx bx-log-out'></i>
            Log Out
        </a>

        <form id="logout-form" action="/logout" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const icon = document.getElementById(id.replace('-dropdown', '-icon'));

    dropdown.classList.toggle('hidden');

    if (dropdown.classList.contains('hidden')) {
        icon.classList.remove('bx-chevron-up');
        icon.classList.add('bx-chevron-down');
    } else {
        icon.classList.remove('bx-chevron-down');
        icon.classList.add('bx-chevron-up');
    }
}
</script>

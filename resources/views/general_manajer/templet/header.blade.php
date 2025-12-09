<aside class="w-64 bg-surface-light dark:bg-surface-dark flex flex-col shadow-lg flex-shrink-0">
    <div class="h-20 flex items-center justify-center border-b border-border-light dark:border-border-dark">
        <h1 class="text-2xl font-bold">Brand</h1>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a class="menu-item flex items-center gap-3 px-4 py-2.5 text-sm font-semibold bg-primary text-white rounded-lg"
            href="{{ url('/general_manajer') }}" data-page="general_manajer">
            <span class="material-icons">home</span>
            <span>Beranda</span>
        </a>
        <a class="menu-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-background-light dark:hover:bg-background-dark rounded-lg transition-colors"
            href="{{ url('/data_karyawan') }}" data-page="data_karyawan">
            <span class="material-icons">group</span>
            <span>Data Karyawan</span>
        </a>
        <a class="menu-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-background-light dark:hover:bg-background-dark rounded-lg transition-colors"
            href="{{ url('/layanan') }}" data-page="layanan">
            <span class="material-icons">miscellaneous_services</span>
            <span>Data Layanan</span>
        </a>
        <a class="menu-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-background-light dark:hover:bg-background-dark rounded-lg transition-colors"
            href="{{ url('/kelola_tugas') }}" data-page="kelola_tugas">
            <span class="material-icons">task_alt</span>
            <span>Kelola Tugas</span>
        </a>
        <a class="menu-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-background-light dark:hover:bg-background-dark rounded-lg transition-colors"
            href="{{ url('/kelola_absen') }}" data-page="kelola_absen">
            <span class="material-icons">manage_accounts</span>
            <span>Kelola Absen</span>
        </a>
    </nav>
    <div class="px-4 py-6 border-t border-border-light dark:border-border-dark">
        <a class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:bg-background-light dark:hover:bg-background-dark rounded-lg transition-colors"
            href="{{ url('/logout') }}">
            <span class="material-icons">logout</span>
            <span>Log Out</span>
        </a>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mengatur menu aktif
        function setActiveMenu(pageId) {
            // Hapus kelas aktif dari semua menu
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('bg-primary', 'text-white', 'font-semibold');
                item.classList.add('text-text-light-secondary', 'dark:text-dark-secondary', 'font-medium');
            });
            
            // Tambahkan kelas aktif ke menu yang sesuai
            const activeItem = document.querySelector(`.menu-item[data-page="${pageId}"]`);
            if (activeItem) {
                activeItem.classList.remove('text-text-light-secondary', 'dark:text-dark-secondary', 'font-medium');
                activeItem.classList.add('bg-primary', 'text-white', 'font-semibold');
            }
        }
        
        // Dapatkan halaman saat ini dari URL
        function getCurrentPage() {
            const path = window.location.pathname;
            const segments = path.split('/');
            // Ambil segmen terakhir yang bukan string kosong
            return segments.filter(segment => segment !== '').pop() || 'general_manajer';
        }
        
        // Atur menu aktif saat halaman dimuat
        const currentPage = getCurrentPage();
        setActiveMenu(currentPage);
        
        // Tambahkan event listener untuk setiap menu
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Simpan ID halaman yang diklik
                const pageId = this.getAttribute('data-page');
                
                // Atur menu aktif sebelum navigasi
                setActiveMenu(pageId);
                
                // Lanjutkan navigasi (tidak perlu e.preventDefault())
            });
        });
    });
</script>
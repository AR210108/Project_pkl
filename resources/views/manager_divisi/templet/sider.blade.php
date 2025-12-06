<aside class="w-64 bg-white dark:bg-gray-800 flex flex-col flex-shrink-0">
    <div class="h-20 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Brand</h1>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-300 {{ request()->is('/manager_divisi') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="/manager_divisi">
            <span class="material-symbols-outlined mr-3">home</span>
            <span class="font-semibold">Beranda</span>
        </a>
        <a class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-300 {{ request()->is('/pengelola_tugas') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="/pengelola_tugas">
            <span class="material-symbols-outlined mr-3">assignment</span>
            <span class="font-medium">Kelola Tugas</span>
        </a>
    </nav>
    <div class="px-4 py-6 border-t border-gray-200 dark:border-gray-700">
        <a class="flex items-center px-4 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300"
            href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="material-symbols-outlined mr-3">logout</span>
            <span class="font-medium">Log Out</span>
        </a>
        
        <!-- Form Logout (Hidden) -->
        <form id="logout-form" action="/logout" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dapatkan semua menu link
    const menuLinks = document.querySelectorAll('nav a');
    
    // Tambahkan event listener untuk setiap menu
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Hapus kelas aktif dari semua menu
            menuLinks.forEach(menu => {
                menu.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                menu.classList.add('text-gray-600', 'dark:text-gray-300');
            });
            
            // Tambahkan kelas aktif ke menu yang diklik
            this.classList.remove('text-gray-600', 'dark:text-gray-300');
            this.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        });
    });
});
</script>
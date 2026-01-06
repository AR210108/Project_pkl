{{-- sidebar.blade.php --}}
<aside class="w-64 bg-white dark:bg-gray-800 p-6 flex flex-col h-screen shadow-lg">
    <!-- Logo/Brand -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Brand</h1>
        <div class="h-1 w-12 bg-blue-600 rounded-full mt-2"></div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 space-y-1">
        <!-- Beranda -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('admin.beranda') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('admin.beranda') }}">
            <i class='bx bx-home text-xl'></i>
            <span>Beranda</span>
        </a>

        <!-- Data User -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('admin.user') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('admin.user') }}">
            <i class='bx bx-user text-xl'></i>
            <span>Data User</span>
        </a>

        <!-- Data Karyawan -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('admin.data_karyawan') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('admin.data_karyawan') }}">
            <i class='bx bx-group text-xl'></i>
            <span>Data Karyawan</span>
        </a>

        <!-- Data Layanan -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('admin.layanan.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('admin.layanan.index') }}">
            <i class='bx bx-list-ul text-xl'></i>
            <span>Data Layanan</span>
        </a>

        <!-- Absensi -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('admin.absensi.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('admin.absensi.index') }}">
            <i class='bx bx-time text-xl'></i>
            <span>Absensi</span>
        </a>

        <!-- Surat Kerjasama -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('admin.surat_kerjasama.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('admin.surat_kerjasama.index') }}">
            <i class='bx bx-file text-xl'></i>
            <span>Surat Kerjasama</span>
        </a>

        <!-- Invoice & Kwitansi Dropdown -->
        <div class="relative">
            <button class="flex items-center gap-3 p-3 rounded-lg w-full text-left text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
                {{ request()->routeIs('admin.invoice.*') || request()->routeIs('admin.kwitansi.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
                onclick="toggleDropdown('invoice-kwitansi-dropdown', 'invoice-kwitansi-icon')">
                <i class='bx bx-receipt text-xl'></i>
                <span>Invoice & Kwitansi</span>
                <i class='bx bx-chevron-down ml-auto text-xl transition-transform duration-200' id="invoice-kwitansi-icon"></i>
            </button>

            <div id="invoice-kwitansi-dropdown" 
                class="pl-10 mt-1 space-y-1 {{ request()->routeIs('admin.invoice.*') || request()->routeIs('admin.kwitansi.*') ? 'block' : 'hidden' }}">
                <!-- Invoice -->
                <a class="flex items-center gap-3 p-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-gray-700 
                    {{ request()->routeIs('admin.invoice.*') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}" 
                    href="{{ route('admin.invoice.index') }}">
                    <i class='bx bx-detail'></i>
                    <span>Invoice</span>
                </a>

                <!-- Kwitansi -->
                <a class="flex items-center gap-3 p-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-gray-700 
                    {{ request()->routeIs('admin.kwitansi.*') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}" 
                    href="{{ route('admin.kwitansi.index') }}">
                    <i class='bx bx-money'></i>
                    <span>Kwitansi</span>
                </a>
            </div>
        </div>

        <!-- Catatan Rapat -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('catatan_rapat.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('catatan_rapat.index') }}">
            <i class='bx bx-notepad text-xl'></i>
            <span>Catatan Rapat</span>
        </a>

        <!-- Pengumuman -->
        <a class="flex items-center gap-3 p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 
            {{ request()->routeIs('pengumuman.*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold' : '' }}" 
            href="{{ route('pengumuman.index') }}">
            <i class='bx bx-megaphone text-xl'></i>
            <span>Pengumuman</span>
        </a>
    </nav>

    <!-- Logout Section -->
    <div class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-700">
        <!-- User Info (Optional) -->
        <div class="flex items-center gap-3 mb-4 p-2">
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <i class='bx bx-user text-blue-600 dark:text-blue-300'></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="w-full">
            @csrf
            <button type="button" onclick="confirmLogout()" 
                    class="flex items-center gap-3 p-3 w-full rounded-lg text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400">
                <i class='bx bx-log-out text-xl'></i>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</aside>

<script>
// Toggle dropdown function
function toggleDropdown(dropdownId, iconId) {
    const dropdown = document.getElementById(dropdownId);
    const icon = document.getElementById(iconId);
    
    dropdown.classList.toggle('hidden');
    
    if (dropdown.classList.contains('hidden')) {
        icon.classList.remove('bx-chevron-up');
        icon.classList.add('bx-chevron-down');
        icon.classList.remove('rotate-180');
    } else {
        icon.classList.remove('bx-chevron-down');
        icon.classList.add('bx-chevron-up');
        icon.classList.add('rotate-180');
    }
}

// Confirm logout
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        document.getElementById('logout-form').submit();
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('[id$="-dropdown"]');
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target) && 
            !event.target.closest('button[onclick*="toggleDropdown"]')) {
            dropdown.classList.add('hidden');
            const iconId = dropdown.id.replace('-dropdown', '-icon');
            const icon = document.getElementById(iconId);
            if (icon) {
                icon.classList.remove('bx-chevron-up');
                icon.classList.add('bx-chevron-down');
                icon.classList.remove('rotate-180');
            }
        }
    });
});

// Auto-toggle dropdown based on current route
document.addEventListener('DOMContentLoaded', function() {
    // Open dropdown if current route is in dropdown items
    if (window.location.pathname.includes('/invoice') || window.location.pathname.includes('/kwitansi')) {
        const dropdown = document.getElementById('invoice-kwitansi-dropdown');
        const icon = document.getElementById('invoice-kwitansi-icon');
        
        if (dropdown) {
            dropdown.classList.remove('hidden');
            icon.classList.remove('bx-chevron-down');
            icon.classList.add('bx-chevron-up');
            icon.classList.add('rotate-180');
        }
    }
});
</script>

<style>
/* Custom styles for better UX */
.rotate-180 {
    transform: rotate(180deg);
}

/* Smooth transitions */
* {
    transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    aside {
        position: fixed;
        z-index: 50;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    aside.mobile-open {
        transform: translateX(0);
    }
    
    /* Overlay for mobile */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 40;
        display: none;
    }
    
    .sidebar-overlay.mobile-open {
        display: block;
    }
}
</style>
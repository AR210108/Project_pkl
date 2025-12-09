<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Cooperation Letters</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#ffffff",
                        "background-dark": "#111827",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "1rem",
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap"
        rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Modal styles */
        .modal {
            transition: opacity 0.3s ease;
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-gray-900 dark:text-gray-100 font-display">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @include('pemilik/template/header')
        <main class="py-8 sm:py-12">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-wider mb-8 sm:mb-12">
                SURAT KERJASAMA
            </h1>
            
            <!-- Document Grid -->
            <div id="documentGrid" class="grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 mb-8">
                <!-- Documents will be inserted here by JavaScript -->
            </div>
            
            <!-- Pagination -->
            <div id="pagination" class="flex justify-center items-center space-x-1 sm:space-x-2 mb-8">
                <!-- Pagination buttons will be inserted here by JavaScript -->
            </div>
        </main>
        <footer class="mt-12 sm:mt-20 mb-6 sm:mb-10">
            <div class="bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-center py-3 sm:py-4 rounded-lg text-xs sm:text-sm">
                Copyright ©2025 by digicity.id
            </div>
        </footer>
    </div>

    <!-- Modal -->
    <div id="documentModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-white"></h3>
                    <p id="modalSubtitle" class="text-gray-600 dark:text-gray-400"></p>
                </div>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-grow overflow-auto p-4 bg-gray-100 dark:bg-gray-900 flex justify-center">
                <img id="modalImage" src="" alt="Document preview" class="max-w-full max-h-[60vh] object-contain" />
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button onclick="downloadDocument()" class="flex items-center bg-primary text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download
                </button>
                <button onclick="printDocument()" class="flex items-center bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    <script>
        // Document data
        const documents = [
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzZc26YLq_SVMOrYYH-n9V4fwlxdi7_mmTuExncxlr3-pWTjnrTgRuTTf12gHT6HXhXfMtanzjLZFgrlw2irkYxm04fCTMaTfBS8eAtbDto-MEMZhl6_i0bLInRzrMU_QEmhu-IBrM_SUbAOQtRkSIN0MZE2pD7qIMCOHMiaWpeGAy3Ow5z_y9a6DTIkAVQs_zv0hzncLKWnqq_aKe25BuxybECPf7_er64NSv67SVuuaQJIx-vDaVU5FuzorbpSIMRcRqNDu6EUg6' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXZJK-4OVIEVdMfxwdYb9nBMhuXX5pAAA2UO6E9Tkw8JLYpBQWZQn3dNZ6eis7oPJWk6iJLbdfSFA92ijXvpWwuT5myFEj9QRk0BUnd0Tn-XWh3lTiSEfq1woxfwp9eRP0qp1LtG5W0ZpAzoW7N-S8hUoEA5OtU39KcVT8prt8Ty3ZW7oELsDd5cOcVo_m7PuN_yJiZfl8ZT_ry6IWzK3ZyUzd01pld6tyBay5jIpItsXm9Jxbagr3tJz7tkZEmVl4jlX_p-QLxlBQ' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB9w9e09hNTVAOjtiBtEQ54rxaGwGEwqaWXHxDGLX5uz0VcLnvBgR1aj4CsPHlV7XlvwjSMeNUC24paiV9-NcHLRflzuahnUsBU2zXBri9IUThQxY1UyCIBwckBntrC9mLvbSMjDqik97KJOvVMbULukp4--YiktVbioWz4WtXTA92nhegLBnrKN9BT5gtHfKEuaaVoytUxeqSHCvQhVqDd_s_iT3DoQu2SLBN1LckYaTuQjHwqr0bCSeBr5VnQeBLNQ47p4LGfega2' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAdg3dyczoGx2rcv5LBxDBtNH13ed4tCEBA2hT02BOCiPvPSYWavwvYhI1yWpGJV_7w5DzHYAavcY1b0DHKJV9MtK3w3AgMNWh66fzY8eQ3qZlHZ0lbfA-E9d3dA6Xns22r-I6-Jc9iM5ZrrBRDOMin-PAAjt1WaZGf31LLoyglexGC9eyJ45f8-YwX56RSm-NriBQ7-kIFp4Qca-JV4kEZiGEgY_96U_HqEM6Tpz5gZ_zcV8QykU-FZsX_ka8iXN4ZvlyC1RkgcAMm' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNged0kgQGlzNdebChc1BAVHwYqHcLFj6aA8AuQQzy6E7kElptakoYxvuhENNjJ8jhFH34EAc9rOZQ7LgvhBQME_tU_5b0yOSVBQeqWZVszKvVzuQ-v3mbQJE9j4unVJuVXWQ8lT1ESwHkPLUzDfQGScloB9YS-RM0DDOMpV_5wLxvTzjKIL-6Ic1yKCadFyUSX4ef-POB3cMhF1PFb3xt-ljG0KG6khhcQPN438HDb5niXeNWJoxwZupn1v2RZcSXQduCb91AlE8' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Digital Marketing', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDTyYoTW1k_8Ap9a9q9niF3CoRXTGiFl1DAfDjnkO83XO1fmhvBHohjASUveNfRFjPKzU7UR4ooTMqNCqPcVZaJQMxKNdjgoiJOunHubRJTv-N4pnkSjdDRn_hLbtoh_tXBiVb4IPhBj-9sguDUOB25dk7I9meP6I-m_W5uEWQiSZV4xWyvxX5TLT5HrVBauGqOFbQ17q3hYdLARsg6l4R26Jm8-AYRbfgGhIzNy39qf96XpWe_C6uYDXegAkpZH96tlkFEOZff6iQ4' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzZc26YLq_SVMOrYYH-n9V4fwlxdi7_mmTuExncxlr3-pWTjnrTgRuTTf12gHT6HXhXfMtanzjLZFgrlw2irkYxm04fCTMaTfBS8eAtbDto-MEMZhl6_i0bLInRzrMU_QEmhu-IBrM_SUbAOQtRkSIN0MZE2pD7qIMCOHMiaWpeGAy3Ow5z_y9a6DTIkAVQs_zv0hzncLKWnqq_aKe25BuxybECPf7_er64NSv67SVuuaQJIx-vDaVU5FuzorbpSIMRcRqNDu6EUg6' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXZJK-4OVIEVdMfxwdYb9nBMhuXX5pAAA2UO6E9Tkw8JLYpBQWZQn3dNZ6eis7oPJWk6iJLbdfSFA92ijXvpWwuT5myFEj9QRk0BUnd0Tn-XWh3lTiSEfq1woxfwp9eRP0qp1LtG5W0ZpAzoW7N-S8hUoEA5OtU39KcVT8prt8Ty3ZW7oELsDd5cOcVo_m7PuN_yJiZfl8ZT_ry6IWzK3ZyUzd01pld6tyBay5jIpItsXm9Jxbagr3tJz7tkZEmVl4jlX_p-QLxlBQ' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB9w9e09hNTVAOjtiBtEQ54rxaGwGEwqaWXHxDGLX5uz0VcLnvBgR1aj4CsPHlV7XlvwjSMeNUC24paiV9-NcHLRflzuahnUsBU2zXBri9IUThQxY1UyCIBwckBntrC9mLvbSMjDqik97KJOvVMbULukp4--YiktVbioWz4WtXTA92nhegLBnrKN9BT5gtHfKEuaaVoytUxeqSHCvQhVqDd_s_iT3DoQu2SLBN1LckYaTuQjHwqr0bCSeBr5VnQeBLNQ47p4LGfega2' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAdg3dyczoGx2rcv5LBxDBtNH13ed4tCEBA2hT02BOCiPvPSYWavwvYhI1yWpGJV_7w5DzHYAavcY1b0DHKJV9MtK3w3AgMNWh66fzY8eQ3qZlHZ0lbfA-E9d3dA6Xns22r-I6-Jc9iM5ZrrBRDOMin-PAAjt1WaZGf31LLoyglexGC9eyJ45f8-YwX56RSm-NriBQ7-kIFp4Qca-JV4kEZiGEgY_96U_HqEM6Tpz5gZ_zcV8QykU-FZsX_ka8iXN4ZvlyC1RkgcAMm' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNged0kgQGlzNdebChc1BAVHwYqHcLFj6aA8AuQQzy6E7kElptakoYxvuhENNjJ8jhFH34EAc9rOZQ7LgvhBQME_tU_5b0yOSVBQeqWZVszKvVzuQ-v3mbQJE9j4unVJuVXWQ8lT1ESwHkPLUzDfQGScloB9YS-RM0DDOMpV_5wLxvTzjKIL-6Ic1yKCadFyUSX4ef-POB3cMhF1PFb3xt-ljG0KG6khhcQPN438HDb5niXeNWJoxwZupn1v2RZcSXQduCb91AlE8' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Web Development', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDTyYoTW1k_8Ap9a9q9niF3CoRXTGiFl1DAfDjnkO83XO1fmhvBHohjASUveNfRFjPKzU7UR4ooTMqNCqPcVZaJQMxKNdjgoiJOunHubRJTv-N4pnkSjdDRn_hLbtoh_tXBiVb4IPhBj-9sguDUOB25dk7I9meP6I-m_W5uEWQiSZV4xWyvxX5TLT5HrVBauGqOFbQ17q3hYdLARsg6l4R26Jm8-AYRbfgGhIzNy39qf96XpWe_C6uYDXegAkpZH96tlkFEOZff6iQ4' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Mobile App', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzZc26YLq_SVMOrYYH-n9V4fwlxdi7_mmTuExncxlr3-pWTjnrTgRuTTf12gHT6HXhXfMtanzjLZFgrlw2irkYxm04fCTMaTfBS8eAtbDto-MEMZhl6_i0bLInRzrMU_QEmhu-IBrM_SUbAOQtRkSIN0MZE2pD7qIMCOHMiaWpeGAy3Ow5z_y9a6DTIkAVQs_zv0hzncLKWnqq_aKe25BuxybECPf7_er64NSv67SVuuaQJIx-vDaVU5FuzorbpSIMRcRqNDu6EUg6' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Mobile App', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXZJK-4OVIEVdMfxwdYb9nBMhuXX5pAAA2UO6E9Tkw8JLYpBQWZQn3dNZ6eis7oPJWk6iJLbdfSFA92ijXvpWwuT5myFEj9QRk0BUnd0Tn-XWh3lTiSEfq1woxfwp9eRP0qp1LtG5W0ZpAzoW7N-S8hUoEA5OtU39KcVT8prt8Ty3ZW7oELsDd5cOcVo_m7PuN_yJiZfl8ZT_ry6IWzK3ZyUzd01pld6tyBay5jIpItsXm9Jxbagr3tJz7tkZEmVl4jlX_p-QLxlBQ' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Mobile App', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB9w9e09hNTVAOjtiBtEQ54rxaGwGEwqaWXHxDGLX5uz0VcLnvBgR1aj4CsPHlV7XlvwjSMeNUC24paiV9-NcHLRflzuahnUsBU2zXBri9IUThQxY1UyCIBwckBntrC9mLvbSMjDqik97KJOvVMbULukp4--YiktVbioWz4WtXTA92nhegLBnrKN9BT5gtHfKEuaaVoytUxeqSHCvQhVqDd_s_iT3DoQu2SLBN1LckYaTuQjHwqr0bCSeBr5VnQeBLNQ47p4LGfega2' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Mobile App', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAdg3dyczoGx2rcv5LBxDBtNH13ed4tCEBA2hT02BOCiPvPSYWavwvYhI1yWpGJV_7w5DzHYAavcY1b0DHKJV9MtK3w3AgMNWh66fzY8eQ3qZlHZ0lbfA-E9d3dA6Xns22r-I6-Jc9iM5ZrrBRDOMin-PAAjt1WaZGf31LLoyglexGC9eyJ45f8-YwX56RSm-NriBQ7-kIFp4Qca-JV4kEZiGEgY_96U_HqEM6Tpz5gZ_zcV8QykU-FZsX_ka8iXN4ZvlyC1RkgcAMm' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Mobile App', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNged0kgQGlzNdebChc1BAVHwYqHcLFj6aA8AuQQzy6E7kElptakoYxvuhENNjJ8jhFH34EAc9rOZQ7LgvhBQME_tU_5b0yOSVBQeqWZVszKvVzuQ-v3mbQJE9j4unVJuVXWQ8lT1ESwHkPLUzDfQGScloB9YS-RM0DDOMpV_5wLxvTzjKIL-6Ic1yKCadFyUSX4ef-POB3cMhF1PFb3xt-ljG0KG6khhcQPN438HDb5niXeNWJoxwZupn1v2RZcSXQduCb91AlE8' },
            { title: 'Surat Perjanjian Kerjasama', subtitle: 'Mobile App', imageUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDTyYoTW1k_8Ap9a9q9niF3CoRXTGiFl1DAfDjnkO83XO1fmhvBHohjASUveNfRFjPKzU7UR4ooTMqNCqPcVZaJQMxKNdjgoiJOunHubRJTv-N4pnkSjdDRn_hLbtoh_tXBiVb4IPhBj-9sguDUOB25dk7I9meP6I-m_W5uEWQiSZV4xWyvxX5TLT5HrVBauGqOFbQ17q3hYdLARsg6l4R26Jm8-AYRbfgGhIzNy39qf96XpWe_C6uYDXegAkpZH96tlkFEOZff6iQ4' }
        ];

        // Pagination variables
        let currentPage = 1;
        let itemsPerPage = 4; // Default for mobile (2x2 grid)
        let totalPages = Math.ceil(documents.length / itemsPerPage);
        let maxVisiblePages = 3; // Maximum number of page numbers to show at once

        // Function to check if we're on mobile
        function isMobile() {
            return window.innerWidth < 768;
        }

        // Function to update items per page based on screen size
        function updateItemsPerPage() {
            if (isMobile()) {
                itemsPerPage = 4; // 2 per row, 2 rows
            } else {
                itemsPerPage = 8; // 4 per row, 2 rows
            }
            totalPages = Math.ceil(documents.length / itemsPerPage);
            
            // Reset to first page if current page is out of bounds
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }
        }

        // Function to render documents
        function renderDocuments() {
            const grid = document.getElementById('documentGrid');
            grid.innerHTML = '';
            
            // Calculate start and end indices
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, documents.length);
            
            // Update grid classes based on screen size
            if (isMobile()) {
                grid.className = 'grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 mb-8';
            } else {
                grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 mb-8';
            }
            
            // Render documents for current page
            for (let i = startIndex; i < endIndex; i++) {
                const doc = documents[i];
                const isPrimary = i === 0; // Make first item have primary border
                
                const documentElement = document.createElement('div');
                documentElement.className = `bg-gray-100 dark:bg-gray-800 rounded-lg p-3 sm:p-4 shadow-md ${isPrimary ? 'border-2 border-primary' : ''}`;
                
                documentElement.innerHTML = `
                    <div class="bg-white dark:bg-gray-900 w-full aspect-[3/4] mb-3 sm:mb-4 cursor-pointer document-preview"
                         onclick="openModal('${doc.title}', '${doc.subtitle}', '${doc.imageUrl}')">
                        <img alt="Blank document preview area"
                             class="w-full h-full object-cover opacity-0"
                             src="${doc.imageUrl}" />
                    </div>
                    <div class="text-center py-2">
                        <h2 class="font-bold text-gray-900 dark:text-gray-100 text-sm sm:text-base">${doc.title}</h2>
                        <p class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm">${doc.subtitle}</p>
                    </div>
                `;
                
                grid.appendChild(documentElement);
            }
        }

        // Function to render pagination
        function renderPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            
            // Only show pagination if there's more than one page
            if (totalPages <= 1) return;
            
            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = `px-2 sm:px-3 py-1 rounded-lg ${currentPage === 1 ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 cursor-not-allowed' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
            prevButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
            prevButton.disabled = currentPage === 1;
            prevButton.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderDocuments();
                    renderPagination();
                }
            };
            pagination.appendChild(prevButton);
            
            // Calculate visible page range
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            // Adjust if we're at the end
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            // First page and ellipsis if needed
            if (startPage > 1) {
                const firstButton = document.createElement('button');
                firstButton.className = `px-2 sm:px-3 py-1 rounded-lg ${1 === currentPage ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                firstButton.textContent = '1';
                firstButton.onclick = () => {
                    currentPage = 1;
                    renderDocuments();
                    renderPagination();
                };
                pagination.appendChild(firstButton);
                
                if (startPage > 2) {
                    const ellipsis = document.createElement('button');
                    ellipsis.className = 'px-2 sm:px-3 py-1 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600';
                    ellipsis.textContent = '...';
                    ellipsis.onclick = () => {
                        // Go to the page before the current visible range
                        currentPage = Math.max(1, startPage - maxVisiblePages);
                        renderDocuments();
                        renderPagination();
                    };
                    pagination.appendChild(ellipsis);
                }
            }
            
            // Visible page numbers
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.className = `px-2 sm:px-3 py-1 rounded-lg ${i === currentPage ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                pageButton.textContent = i;
                pageButton.onclick = () => {
                    currentPage = i;
                    renderDocuments();
                    renderPagination();
                };
                pagination.appendChild(pageButton);
            }
            
            // Last page and ellipsis if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('button');
                    ellipsis.className = 'px-2 sm:px-3 py-1 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600';
                    ellipsis.textContent = '...';
                    ellipsis.onclick = () => {
                        // Go to the page after the current visible range
                        currentPage = Math.min(totalPages, endPage + maxVisiblePages);
                        renderDocuments();
                        renderPagination();
                    };
                    pagination.appendChild(ellipsis);
                }
                
                const lastButton = document.createElement('button');
                lastButton.className = `px-2 sm:px-3 py-1 rounded-lg ${totalPages === currentPage ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
                lastButton.textContent = totalPages;
                lastButton.onclick = () => {
                    currentPage = totalPages;
                    renderDocuments();
                    renderPagination();
                };
                pagination.appendChild(lastButton);
            }
            
            // Next button
            const nextButton = document.createElement('button');
            nextButton.className = `px-2 sm:px-3 py-1 rounded-lg ${currentPage === totalPages ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 cursor-not-allowed' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600'}`;
            nextButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
            nextButton.disabled = currentPage === totalPages;
            nextButton.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderDocuments();
                    renderPagination();
                }
            };
            pagination.appendChild(nextButton);
        }

        // Initialize the page
        function init() {
            updateItemsPerPage();
            renderDocuments();
            renderPagination();
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            const oldItemsPerPage = itemsPerPage;
            updateItemsPerPage();
            
            // If items per page changed, re-render
            if (oldItemsPerPage !== itemsPerPage) {
                renderDocuments();
                renderPagination();
            }
        });

        // Modal functions
        let currentDocumentUrl = '';

        function openModal(title, subtitle, imageUrl) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalSubtitle').textContent = subtitle;
            document.getElementById('modalImage').src = imageUrl;
            currentDocumentUrl = imageUrl;
            document.getElementById('documentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('documentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function downloadDocument() {
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            link.href = currentDocumentUrl;
            link.download = document.getElementById('modalTitle').textContent + '.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printDocument() {
            // Open image in new window for printing
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>${document.getElementById('modalTitle').textContent}</title>
                    <style>
                        body { margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
                        img { max-width: 100%; max-height: 100vh; }
                    </style>
                </head>
                <body>
                    <img src="${currentDocumentUrl}" alt="${document.getElementById('modalTitle').textContent}" />
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Close modal when clicking outside
        document.getElementById('documentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('documentModal').classList.contains('hidden')) {
                closeModal();
            }
        });

        // Initialize the page when DOM is loaded
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>

</html>
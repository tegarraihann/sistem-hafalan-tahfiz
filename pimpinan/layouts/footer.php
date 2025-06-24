<!-- BEGIN: JS ../assets-->
    <script src="../assets/dist/jquery/jquery.min.js"></script>
    <script src="../assets/dist/js/app.js"></script>
    <script src="../assets/dist/js/sweetalert/sweetalert2.all.min.js"></script>
    <script src="../assets/dist/js/myscript.js"></script>
    
    <script src="../assets/dist/dataTables/jquery-3.7.0.js"></script>
    <script src="../assets/dist/dataTables/jquery.dataTables.min.js"></script>

    <!-- Lucide Icons Fix -->
    <script>
        // Daftar icon yang digunakan dalam aplikasi
        const iconList = {
            'home': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>',
            'layout': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><line x1="9" x2="9" y1="21" y2="9"/></svg>',
            'sidebar': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="9" x2="9" y1="3" y2="21"/></svg>',
            'inbox': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/></svg>',
            'activity': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/></svg>',
            'toggle-right': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="6" ry="6"/><circle cx="16" cy="12" r="2"/></svg>',
            'bar-chart-2': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>',
            'x-circle': '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>'
        };

        // Fungsi untuk mengganti data-lucide dengan SVG
        function replaceLucideIcons() {
            const lucideElements = document.querySelectorAll('[data-lucide]');
            
            lucideElements.forEach(element => {
                const iconName = element.getAttribute('data-lucide');
                if (iconList[iconName]) {
                    element.innerHTML = iconList[iconName];
                    element.removeAttribute('data-lucide');
                }
            });
        }

        // Jalankan saat DOM ready
        $(document).ready(function() {
            replaceLucideIcons();
        });
    </script>

    <script>
        new DataTable('#example');
    </script>
    <!-- END: JS ../assets-->
    </body>

    </html>
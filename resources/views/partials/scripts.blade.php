<!-- Alpine.js untuk dropdown (opsional) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // Global JavaScript functions
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        const menuButton = document.querySelector('.md:hidden');
        
        if (menu && !menu.contains(event.target) && !menuButton.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>

@stack('scripts')
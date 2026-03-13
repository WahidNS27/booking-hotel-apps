<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard - Aplikasi Modern')</title>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Chart.js untuk grafik (opsional) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Konfigurasi Tailwind tambahan -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4F46E5',
                    secondary: '#818CF8',
                    success: '#10B981',
                    warning: '#F59E0B',
                    danger: '#EF4444',
                }
            }
        }
    }
</script>

@include('partials.styles')
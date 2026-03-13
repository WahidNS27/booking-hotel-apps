<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Modern</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome untuk ikon (opsional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Konfigurasi Tailwind tambahan -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#818CF8',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Animasi untuk fade in */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        /* Background gradient */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 font-sans antialiased">
    
    <!-- Container utama dengan animasi fade in -->
    <div class="w-full max-w-md animate-fade-in">
        
        <!-- Card container dengan efek blur dan shadow -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 md:p-10 border border-white/20">
            
            <!-- Header dengan logo dan judul -->
            <div class="text-center mb-8">
                <!-- Logo (bisa diganti dengan ikon atau gambar) -->
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-primary to-secondary text-white mb-4 shadow-lg">
                    
                        <img src="{{ asset('/img/ppkdjp.jpg') }}" class=" rounded-full w-30 h-30">
            
                </div>
                
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Login Resepsionis</h1>
                <p class="text-gray-600"> Silakan login untuk mengakses sistem reservasi Hotel PPKD Jakarta Pusat</p>
            </div>
            
            <!-- Notifikasi error dengan desain modern -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif
            
            <!-- Form login -->
            <form action="/login" method="POST" class="space-y-6">
                @csrf
                
                <!-- Field Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-envelope mr-2 text-primary"></i>Alamat Email
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="w-full pl-10 pr-3 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 outline-none bg-white/50"
                            placeholder="nama@email.com"
                            required
                        >
                    </div>
                </div>
                
                <!-- Field Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-lock mr-2 text-primary"></i>Password
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full pl-10 pr-10 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 outline-none bg-white/50"
                            placeholder="••••••••"
                            required
                        >
                        <!-- Tombol show/hide password (JavaScript opsional) -->
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary transition-colors"
                        >
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
             
                
                <!-- Tombol Login -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold py-3 px-4 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary/50"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center mt-6 text-white/80 text-sm">
            &copy; 2024 Aplikasi Modern. All rights reserved.
        </p>
    </div>
    
    <!-- JavaScript untuk toggle password (opsional) -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    
</body>
</html>
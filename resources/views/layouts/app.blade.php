<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body class="bg-gray-50 font-sans antialiased">
    
    <!-- Container utama dengan flex layout -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- ========== SIDEBAR ========== -->
        @include('partials.sidebar')
        
        <!-- ========== MAIN CONTENT ========== -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- ========== TOP NAVBAR ========== -->
            @include('partials.topbar')
            
            <!-- ========== DASHBOARD CONTENT ========== -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- JavaScript -->
    @include('partials.scripts')
    
</body>
</html>
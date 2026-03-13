@extends('layouts.app')

@section('title', 'Dashboard - Aplikasi Modern')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang kembali, <span class="font-semibold text-primary">{{ session('user') }}</span>! 
            Berikut adalah ringkasan aktivitas Anda.
        </p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stats-card 
            icon="fas fa-users"
            iconBg="bg-blue-100"
            iconColor="text-blue-600"
            title="Total Pengguna"
            value="1,234"
            trend="+12%"
            trendIcon="arrow-up"
            trendColor="text-green-600"
            subtext="↑ 123 dari bulan lalu"
        />
        
        <x-stats-card 
            icon="fas fa-dollar-sign"
            iconBg="bg-green-100"
            iconColor="text-green-600"
            title="Pendapatan"
            value="Rp 45,6 Jt"
            trend="+8%"
            trendIcon="arrow-up"
            trendColor="text-green-600"
            subtext="↑ Rp 3,2 Jt dari bulan lalu"
        />
        
        <x-stats-card 
            icon="fas fa-shopping-cart"
            iconBg="bg-orange-100"
            iconColor="text-orange-600"
            title="Total Pesanan"
            value="456"
            trend="-3%"
            trendIcon="arrow-down"
            trendColor="text-orange-600"
            subtext="↓ 12 dari bulan lalu"
        />
        
        <x-stats-card 
            icon="fas fa-star"
            iconBg="bg-purple-100"
            iconColor="text-purple-600"
            title="Kepuasan"
            value="98%"
            trend="+5%"
            trendIcon="arrow-up"
            trendColor="text-green-600"
            subtext="↑ 2% dari bulan lalu"
        />
    </div>
    
    <!-- Charts & Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Aktivitas -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Aktivitas Pengguna</h2>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option>Minggu Ini</option>
                    <option>Bulan Ini</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center text-gray-400">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Aktivitas Terkini</h2>
            <div class="space-y-4">
                <x-activity-item 
                    icon="fas fa-user-plus"
                    iconBg="bg-blue-100"
                    iconColor="text-blue-600"
                    title="Pengguna baru mendaftar"
                    time="5 menit yang lalu"
                />
                
                <x-activity-item 
                    icon="fas fa-shopping-bag"
                    iconBg="bg-green-100"
                    iconColor="text-green-600"
                    title="Pesanan baru #INV-001"
                    time="15 menit yang lalu"
                />
                
                <x-activity-item 
                    icon="fas fa-credit-card"
                    iconBg="bg-yellow-100"
                    iconColor="text-yellow-600"
                    title="Pembayaran dikonfirmasi"
                    time="30 menit yang lalu"
                />
                
                <x-activity-item 
                    icon="fas fa-message"
                    iconBg="bg-purple-100"
                    iconColor="text-purple-600"
                    title="Komentar baru dari User"
                    time="1 jam yang lalu"
                />
            </div>
            
            <button class="w-full mt-6 text-sm text-primary hover:text-secondary font-medium text-center">
                Lihat Semua Aktivitas <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>
    
    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h2>
            <a href="#" class="text-sm text-primary hover:text-secondary font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-600 border-b border-gray-200">
                        <th class="pb-3 font-semibold">ID Pesanan</th>
                        <th class="pb-3 font-semibold">Pelanggan</th>
                        <th class="pb-3 font-semibold">Produk</th>
                        <th class="pb-3 font-semibold">Total</th>
                        <th class="pb-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="border-b border-gray-100">
                        <td class="py-3">#INV-001</td>
                        <td class="py-3">Budi Santoso</td>
                        <td class="py-3">Paket Premium</td>
                        <td class="py-3">Rp 1.200.000</td>
                        <td class="py-3"><x-status-badge status="success" text="Selesai" /></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3">#INV-002</td>
                        <td class="py-3">Siti Rahayu</td>
                        <td class="py-3">Paket Basic</td>
                        <td class="py-3">Rp 500.000</td>
                        <td class="py-3"><x-status-badge status="warning" text="Diproses" /></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3">#INV-003</td>
                        <td class="py-3">Ahmad Hidayat</td>
                        <td class="py-3">Paket Enterprise</td>
                        <td class="py-3">Rp 2.500.000</td>
                        <td class="py-3"><x-status-badge status="info" text="Dikirim" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Inisialisasi chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Pengunjung',
                        data: [650, 590, 800, 810, 960, 1100, 1250],
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
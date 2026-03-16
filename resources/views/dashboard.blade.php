@extends('layouts.app')

@section('title', 'Dashboard Resepsionis')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Resepsionis</h1>
    <p class="text-gray-600 mt-1">
        Selamat datang kembali, <span class="font-semibold text-primary">{{ session('user') }}</span>! 
        Berikut adalah ringkasan aktivitas reservasi hari ini.
    </p>
    <p class="text-sm text-gray-500 mt-2">
        <i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::now()->format('l, d F Y') }}
    </p>
</div>

<!-- Stats Cards dengan ID untuk realtime update -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-sign-in-alt text-green-600 text-xl"></i>
            </div>
            <span class="text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">Hari Ini</span>
        </div>
        <h3 class="text-gray-500 text-sm">Check-In Hari Ini</h3>
        <div class="flex items-end justify-between">
            <span class="text-3xl font-bold text-gray-800" id="today-checkins">{{ $todayCheckIns }}</span>
            <span class="text-sm text-gray-500">tamu</span>
        </div>
        <div class="mt-2 text-xs text-gray-400" id="checkin-info">
            @if($todayCheckInsList->count() > 0)
                <i class="fas fa-clock mr-1"></i>Terdekat: {{ $todayCheckInsList->first()->arrival_time ?? '14:00' }}
            @endif
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-orange-600 text-xl"></i>
            </div>
            <span class="text-sm text-orange-600 bg-orange-50 px-3 py-1 rounded-full">Hari Ini</span>
        </div>
        <h3 class="text-gray-500 text-sm">Check-Out Hari Ini</h3>
        <div class="flex items-end justify-between">
            <span class="text-3xl font-bold text-gray-800" id="today-checkouts">{{ $todayCheckOuts }}</span>
            <span class="text-sm text-gray-500">tamu</span>
        </div>
        <div class="mt-2 text-xs text-gray-400" id="checkout-info">
            @if($todayCheckOutsList->count() > 0)
                <i class="fas fa-clock mr-1"></i>Belum check-out: {{ $todayCheckOutsList->count() }}
            @endif
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-bed text-blue-600 text-xl"></i>
            </div>
            <span class="text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Sekarang</span>
        </div>
        <h3 class="text-gray-500 text-sm">Kamar Terisi</h3>
        <div class="flex items-end justify-between">
            <span class="text-3xl font-bold text-gray-800" id="occupied-rooms">{{ $occupiedRooms }}</span>
            <span class="text-sm text-gray-500">kamar</span>
        </div>
        <div class="mt-2 text-xs text-gray-400" id="guests-info">
            <i class="fas fa-users mr-1"></i><span id="today-guests">{{ $todayGuests }}</span> tamu menginap
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
            </div>
            <span class="text-sm text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Total</span>
        </div>
        <h3 class="text-gray-500 text-sm">Total Reservasi</h3>
        <div class="flex items-end justify-between">
            <span class="text-3xl font-bold text-gray-800" id="total-reservations">{{ $totalReservations }}</span>
            <span class="text-sm text-gray-500">reservasi</span>
        </div>
        <div class="mt-2 text-xs text-gray-400">
            <i class="fas fa-user mr-1"></i><span id="total-guests">{{ $totalGuests }}</span> tamu terdaftar
        </div>
    </div>
</div>

<!-- Charts & Status Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Grafik Aktivitas 7 Hari -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-line text-primary mr-2"></i>Aktivitas Reservasi (7 Hari Terakhir)
            </h2>
        </div>
        <div class="h-64">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
    
    <!-- Status Reservasi -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-chart-pie text-primary mr-2"></i>Status Reservasi
        </h2>
        
        <div class="space-y-4" id="status-stats">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                    <span class="text-gray-600">Non Guaranteed</span>
                </div>
                <span class="font-semibold" id="stat-non-guaranteed">{{ $statusStats['non-guaranteed'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span class="text-gray-600">Guaranteed</span>
                </div>
                <span class="font-semibold" id="stat-guaranteed">{{ $statusStats['guaranteed'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-gray-600">Checked In</span>
                </div>
                <span class="font-semibold" id="stat-checked-in">{{ $statusStats['checked-in'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                    <span class="text-gray-600">Checked Out</span>
                </div>
                <span class="font-semibold" id="stat-checked-out">{{ $statusStats['checked-out'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    <span class="text-gray-600">Cancelled</span>
                </div>
                <span class="font-semibold" id="stat-cancelled">{{ $statusStats['cancelled'] ?? 0 }}</span>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600">Pendapatan Bulan Ini</span>
                <span class="font-semibold text-primary">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</span>
            </div>
            @if($revenueGrowth != 0)
                <div class="text-xs {{ $revenueGrowth > 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $revenueGrowth > 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                    {{ abs($revenueGrowth) }}% dari bulan lalu
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Check-in & Check-out Today -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Check-in Hari Ini -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-sign-in-alt text-green-600 mr-2"></i>Check-In Hari Ini
            </h2>
            <span class="text-sm bg-green-100 text-green-600 px-3 py-1 rounded-full" id="checkin-count">
                {{ $todayCheckInsList->count() }} tamu
            </span>
        </div>
        
        <div class="space-y-3 max-h-80 overflow-y-auto pr-2" id="checkin-list">
            @forelse($todayCheckInsList as $reservation)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ $reservation->guest->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-hashtag mr-1"></i>{{ $reservation->booking_no }} • {{ $reservation->room_type }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-green-600">{{ $reservation->arrival_time ?? '14:00' }}</p>
                    <a href="{{ route('reservations.show', $reservation->id) }}" class="text-xs text-primary hover:underline">
                        Proses
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                <p>Tidak ada check-in untuk hari ini</p>
            </div>
            @endforelse
        </div>
        
        @if($todayCheckInsList->count() > 0)
        <a href="{{ route('reservations.index') }}?date_from={{ \Carbon\Carbon::today()->format('Y-m-d') }}&status=checked-in" 
           class="block w-full mt-4 text-sm text-primary hover:text-secondary font-medium text-center">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
        @endif
    </div>
    
    <!-- Check-out Hari Ini -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-sign-out-alt text-orange-600 mr-2"></i>Check-Out Hari Ini
            </h2>
            <span class="text-sm bg-orange-100 text-orange-600 px-3 py-1 rounded-full" id="checkout-count">
                {{ $todayCheckOutsList->count() }} tamu
            </span>
        </div>
        
        <div class="space-y-3 max-h-80 overflow-y-auto pr-2" id="checkout-list">
            @forelse($todayCheckOutsList as $reservation)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-orange-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ $reservation->guest->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-hashtag mr-1"></i>{{ $reservation->booking_no }} • {{ $reservation->room_type }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-orange-600">Sebelum 12:00</p>
                    <a href="{{ route('reservations.edit', $reservation->id) }}" class="text-xs text-primary hover:underline">
                        Proses
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                <p>Tidak ada check-out untuk hari ini</p>
            </div>
            @endforelse
        </div>
        
        @if($todayCheckOutsList->count() > 0)
        <a href="{{ route('reservations.index') }}?date_from={{ \Carbon\Carbon::today()->format('Y-m-d') }}&status=checked-out" 
           class="block w-full mt-4 text-sm text-primary hover:text-secondary font-medium text-center">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
        @endif
    </div>
</div>

<!-- Upcoming Reservations -->
<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-calendar-alt text-primary mr-2"></i>Reservasi Mendatang (7 Hari)
        </h2>
        <a href="{{ route('reservations.index') }}" class="text-sm text-primary hover:text-secondary font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-sm text-gray-600 border-b border-gray-200">
                    <th class="pb-3 font-semibold">Booking No</th>
                    <th class="pb-3 font-semibold">Nama Tamu</th>
                    <th class="pb-3 font-semibold">Kamar</th>
                    <th class="pb-3 font-semibold">Check In</th>
                    <th class="pb-3 font-semibold">Check Out</th>
                    <th class="pb-3 font-semibold">Status</th>
                    <th class="pb-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm" id="upcoming-table-body">
                @forelse($upcomingReservations as $reservation)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 font-medium">{{ $reservation->booking_no }}</td>
                    <td class="py-3">
                        <div>
                            <div>{{ $reservation->guest->name ?? '-' }}</div>
                            <small class="text-gray-500">{{ $reservation->guest->phone ?? '-' }}</small>
                        </div>
                    </td>
                    <td class="py-3">
                        @php
                            $roomNumbers = is_array($reservation->room_numbers) 
                                ? $reservation->room_numbers 
                                : (json_decode($reservation->room_numbers, true) ?? []);
                            $firstRoom = !empty($roomNumbers) ? $roomNumbers[0] : '-';
                        @endphp
                        {{ $reservation->room_type }} ({{ $firstRoom }})
                    </td>
                    <td class="py-3">{{ \Carbon\Carbon::parse($reservation->arrival_date)->format('d/m/Y') }}</td>
                    <td class="py-3">{{ \Carbon\Carbon::parse($reservation->departure_date)->format('d/m/Y') }}</td>
                    <td class="py-3">
                        @php
                            $statusClass = '';
                            $statusText = $reservation->status;
                            
                            switch($reservation->status) {
                                case 'guaranteed':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'Guaranteed';
                                    break;
                                case 'non-guaranteed':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'Non Guaranteed';
                                    break;
                                case 'checked-in':
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    $statusText = 'Check In';
                                    break;
                                case 'checked-out':
                                    $statusClass = 'bg-purple-100 text-purple-800';
                                    $statusText = 'Check Out';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusText = 'Dibatalkan';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = $reservation->status;
                            }
                        @endphp
                        <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="py-3">
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                            <p>Tidak ada reservasi mendatang</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
    <h2 class="text-lg font-semibold text-gray-800 mb-6">
        <i class="fas fa-history text-primary mr-2"></i>Aktivitas Terkini
    </h2>
    
    <div class="space-y-4" id="recent-activities">
        @forelse($recentActivities as $activity)
        <div class="flex items-center">
            <div class="w-10 h-10 {{ $activity['iconBg'] }} rounded-full flex items-center justify-center mr-3">
                <i class="{{ $activity['icon'] }} {{ $activity['iconColor'] }}"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">{{ $activity['title'] }}</p>
                <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-gray-500">
            Belum ada aktivitas
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Chart
    const ctx = document.getElementById('activityChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Reservasi',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Fungsi untuk update semua data dashboard
    function updateDashboardData() {
        fetch('/dashboard/realtime-stats')
            .then(response => response.json())
            .then(data => {
                // Update stats cards
                document.getElementById('today-checkins').textContent = data.todayCheckIns;
                document.getElementById('today-checkouts').textContent = data.todayCheckOuts;
                document.getElementById('today-guests').textContent = data.todayGuests;
                document.getElementById('occupied-rooms').textContent = data.occupiedRooms;
                document.getElementById('total-reservations').textContent = data.totalReservations;
                document.getElementById('total-guests').textContent = data.totalGuests;
                
                // Update status stats
                document.getElementById('stat-non-guaranteed').textContent = data.statusStats['non-guaranteed'] || 0;
                document.getElementById('stat-guaranteed').textContent = data.statusStats['guaranteed'] || 0;
                document.getElementById('stat-checked-in').textContent = data.statusStats['checked-in'] || 0;
                document.getElementById('stat-checked-out').textContent = data.statusStats['checked-out'] || 0;
                document.getElementById('stat-cancelled').textContent = data.statusStats['cancelled'] || 0;
                
                // Update info tambahan
                updateCheckInInfo(data.todayCheckInsList);
                updateCheckOutInfo(data.todayCheckOutsList);
            })
            .catch(error => console.error('Error updating stats:', error));
    }
    
    // Fungsi untuk update list check-in
    function updateCheckInInfo(checkIns) {
        const checkinList = document.getElementById('checkin-list');
        const checkinCount = document.getElementById('checkin-count');
        
        if (checkinList && checkinCount) {
            checkinCount.textContent = checkIns.length + ' tamu';
            
            if (checkIns.length > 0) {
                let html = '';
                checkIns.forEach(item => {
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">${item.guest_name}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-hashtag mr-1"></i>${item.booking_no} • ${item.room_type}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">${item.arrival_time || '14:00'}</p>
                                <a href="/reservations/${item.id}" class="text-xs text-primary hover:underline">
                                    Proses
                                </a>
                            </div>
                        </div>
                    `;
                });
                checkinList.innerHTML = html;
            } else {
                checkinList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                        <p>Tidak ada check-in untuk hari ini</p>
                    </div>
                `;
            }
        }
    }
    
    // Fungsi untuk update list check-out
    function updateCheckOutInfo(checkOuts) {
        const checkoutList = document.getElementById('checkout-list');
        const checkoutCount = document.getElementById('checkout-count');
        
        if (checkoutList && checkoutCount) {
            checkoutCount.textContent = checkOuts.length + ' tamu';
            
            if (checkOuts.length > 0) {
                let html = '';
                checkOuts.forEach(item => {
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">${item.guest_name}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-hashtag mr-1"></i>${item.booking_no} • ${item.room_type}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-orange-600">Sebelum 12:00</p>
                                <a href="/reservations/${item.id}/edit" class="text-xs text-primary hover:underline">
                                    Proses
                                </a>
                            </div>
                        </div>
                    `;
                });
                checkoutList.innerHTML = html;
            } else {
                checkoutList.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                        <p>Tidak ada check-out untuk hari ini</p>
                    </div>
                `;
            }
        }
    }
    
    // Fungsi untuk update reservasi mendatang
    function updateUpcomingReservations() {
        fetch('/dashboard/upcoming')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('upcoming-table-body');
                
                if (tableBody && data.upcoming) {
                    if (data.upcoming.length > 0) {
                        let html = '';
                        data.upcoming.forEach(item => {
                            html += `
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 font-medium">${item.booking_no}</td>
                                    <td class="py-3">
                                        <div>
                                            <div>${item.guest_name}</div>
                                            <small class="text-gray-500">${item.guest_phone}</small>
                                        </div>
                                    </td>
                                    <td class="py-3">${item.room_type} (${item.room_number})</td>
                                    <td class="py-3">${item.arrival_date}</td>
                                    <td class="py-3">${item.departure_date}</td>
                                    <td class="py-3">
                                        <span class="${item.status_class} px-3 py-1 rounded-full text-xs">
                                            ${item.status_text}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <a href="/reservations/${item.id}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                        tableBody.innerHTML = html;
                    } else {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                        <p>Tidak ada reservasi mendatang</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                }
            })
            .catch(error => console.error('Error updating upcoming:', error));
    }
    
    // Fungsi untuk update aktivitas terkini
    function updateRecentActivities() {
        fetch('/dashboard/recent-activities')
            .then(response => response.json())
            .then(data => {
                const activitiesContainer = document.getElementById('recent-activities');
                
                if (activitiesContainer && data.activities) {
                    if (data.activities.length > 0) {
                        let html = '';
                        data.activities.forEach(activity => {
                            html += `
                                <div class="flex items-center">
                                    <div class="w-10 h-10 ${activity.iconBg} rounded-full flex items-center justify-center mr-3">
                                        <i class="${activity.icon} ${activity.iconColor}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">${activity.title}</p>
                                        <p class="text-xs text-gray-500">${activity.time}</p>
                                    </div>
                                </div>
                            `;
                        });
                        activitiesContainer.innerHTML = html;
                    } else {
                        activitiesContainer.innerHTML = `
                            <div class="text-center py-4 text-gray-500">
                                Belum ada aktivitas
                            </div>
                        `;
                    }
                }
            })
            .catch(error => console.error('Error updating activities:', error));
    }
    
    // Panggil update pertama kali setelah halaman load
    setTimeout(() => {
        updateDashboardData();
        updateUpcomingReservations();
        updateRecentActivities();
    }, 1000);
    
    // Set interval untuk update berkala setiap 10 detik
    setInterval(() => {
        updateDashboardData();
        updateUpcomingReservations();
        updateRecentActivities();
    }, 10000); // Update setiap 10 detik
    
    // Update ketika tab menjadi aktif
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateDashboardData();
            updateUpcomingReservations();
            updateRecentActivities();
        }
    });
});
</script>
@endpush
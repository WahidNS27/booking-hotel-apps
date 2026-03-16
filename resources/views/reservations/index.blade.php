@extends('layouts.app')

@section('title', 'Daftar Reservasi')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <h1 class="text-3xl font-bold text-gray-800">Daftar Reservasi</h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <!-- Form Pencarian -->
        <form action="{{ route('reservations.index') }}" method="GET" class="flex gap-2">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari booking no / nama tamu..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Reset
                </a>
            @endif
        </form>
        
        <a href="{{ route('reservations.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition text-center">
            <i class="fas fa-plus mr-2"></i>Buat Reservasi Baru
        </a>
    </div>
</div>

<!-- Info hasil pencarian -->
@if(request('search'))
    <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
        <i class="fas fa-info-circle mr-2"></i>
        Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
        ({{ $reservations->total() }} data ditemukan)
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
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
            <tbody class="text-sm">
                @forelse($reservations as $reservation)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 font-medium">{{ $reservation->booking_no }}</td>
                    <td class="py-3">{{ $reservation->guest->name }}</td>
                    <td class="py-3">{{ $reservation->room_type }} ({{ json_decode($reservation->room_numbers)[0] ?? '-' }})</td>
                    <td class="py-3">{{ \Carbon\Carbon::parse($reservation->arrival_date)->format('d/m/Y') }}</td>
                    <td class="py-3">{{ \Carbon\Carbon::parse($reservation->departure_date)->format('d/m/Y') }}</td>
                    <td class="py-3">
                        <span class="{{ $reservation->status_badge_class }} px-3 py-1 rounded-full text-xs">
                            {{ $reservation->status_indonesian }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('reservations.edit', $reservation->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="" class="text-red-600 hover:text-red-800" title="PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">
                        @if(request('search'))
                            Tidak ada data reservasi yang sesuai dengan pencarian "{{ request('search') }}"
                        @else
                            Belum ada data reservasi
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $reservations->appends(request()->query())->links() }}
    </div>
</div>
@endsection
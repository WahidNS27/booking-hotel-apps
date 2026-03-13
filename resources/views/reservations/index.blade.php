@extends('layouts.app')

@section('title', 'Daftar Reservasi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Daftar Reservasi</h1>
    <a href="{{ route('reservations.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
        <i class="fas fa-plus mr-2"></i>Buat Reservasi Baru
    </a>
</div>

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
                        Belum ada data reservasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
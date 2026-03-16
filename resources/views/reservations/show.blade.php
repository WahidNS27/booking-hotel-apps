@extends('layouts.app')

@section('title', 'Detail Reservasi - ' . $reservation->booking_no)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Detail Reservasi</h1>
        <p class="text-gray-600 mt-1">Nomor Booking: <span class="font-semibold">{{ $reservation->booking_no }}</span></p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('reservations.edit', $reservation->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('reservations.pdf', $reservation->id) }}" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
            <i class="fas fa-file-pdf mr-2"></i>PDF
        </a>
        <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-grey-600 transition">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

{{-- Header Hotel --}}
<div class="bg-gradient-to-r from-primary to-secondary text-white rounded-t-2xl p-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold">PPKD HOTEL</h2>
            <p class="text-white/80">Reservation Confirmation</p>
        </div>
        <div class="text-right">
            <p class="text-sm">Booking No: <span class="font-bold">{{ $reservation->booking_no }}</span></p>
            <p class="text-sm">Date: {{ $reservation->booking_date->format('d M Y') }}</p>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="bg-white border-x border-b rounded-b-2xl shadow-lg p-6 mb-6">
    
    {{-- Status Badge --}}
    <div class="flex justify-end mb-4">
        <span class="{{ $reservation->status_badge_class }} px-4 py-2 rounded-full text-sm font-semibold">
            {{ $reservation->status_indonesian }}
        </span>
    </div>
    
    {{-- To Section --}}
    <div class="mb-6 p-4 bg-gray-50 rounded-xl">
        <h3 class="text-sm font-semibold text-gray-500 mb-2">TO:</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="font-bold text-lg">{{ $reservation->guest->name }}</p>
                <p class="text-gray-600">{{ $reservation->guest->phone }}</p>
                <p class="text-gray-600">{{ $reservation->guest->email }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600">Company/Agent: <span class="font-semibold">{{ $reservation->company_agent ?? '-' }}</span></p>
                <p class="text-gray-600">Telp: {{ $reservation->agent_telp ?? '-' }}</p>
                <p class="text-gray-600">Email: {{ $reservation->agent_email ?? '-' }}</p>
            </div>
        </div>
    </div>
    
    {{-- Reservation Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="space-y-3">
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">First Name:</span>
                <span class="w-2/3 font-semibold">{{ $reservation->guest->name }}</span>
            </div>
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Arrival Date:</span>
                <span class="w-2/3 font-semibold">{{ \Carbon\Carbon::parse($reservation->arrival_date)->format('d M Y') }}</span>
            </div>
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Departure Date:</span>
                <span class="w-2/3 font-semibold">{{ \Carbon\Carbon::parse($reservation->departure_date)->format('d M Y') }}</span>
            </div>
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Total Night:</span>
                <span class="w-2/3 font-semibold">{{ $reservation->total_nights }} malam</span>
            </div>
        </div>
        
        <div class="space-y-3">
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Room/Unit Type:</span>
                <span class="w-2/3 font-semibold">{{ $reservation->room_type }}</span>
            </div>
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Room Number:</span>
                <span class="w-2/3 font-semibold">{{ $reservation->formatted_room_numbers }}</span>
            </div>
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Person Pax:</span>
                <span class="w-2/3 font-semibold">{{ $reservation->number_of_persons }} orang</span>
            </div>
            <div class="flex border-b pb-2">
                <span class="w-1/3 text-gray-600">Room Rate Net:</span>
                <span class="w-2/3 font-semibold text-green-600">{{ $reservation->formatted_room_rate }}/malam</span>
            </div>
        </div>
    </div>
    
    {{-- Payment Information --}}
{{-- Payment Information --}}
<div class="mt-6 pt-6 border-t">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h3>
    
    @if($reservation->payment_method)
        <div class="bg-blue-50 p-4 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <p class="text-sm text-gray-600">Payment Method:</p>
                    <p class="font-semibold text-primary">{{ $reservation->payment_method_indonesian ?? $reservation->payment_method }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Status:</p>
                    @php
                        $statusClass = '';
                        $statusText = '';
                        
                        if(isset($reservation->payment_status)) {
                            switch($reservation->payment_status) {
                                case 'paid':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'Lunas';
                                    break;
                                case 'partial':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'Dibayar Sebagian';
                                    break;
                                case 'refunded':
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusText = 'Dikembalikan';
                                    break;
                                default:
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    $statusText = 'Belum Dibayar';
                            }
                        } else {
                            $statusClass = 'bg-blue-100 text-blue-800';
                            $statusText = 'Belum Dibayar';
                        }
                    @endphp
                    <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold inline-block">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
            
            {{-- Total Harga --}}
            <div class="mb-3 p-3 bg-white rounded-lg">
                <p class="text-sm text-gray-600">Total Harga:</p>
                <p class="text-xl font-bold text-primary">
                    Rp {{ number_format($reservation->room_rate_net * $reservation->total_nights * $reservation->number_of_rooms, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500">
                    ({{ $reservation->room_rate_net }} x {{ $reservation->number_of_rooms }} kamar x {{ $reservation->total_nights }} malam)
                </p>
            </div>
            
            {{-- Detail berdasarkan metode pembayaran --}}
            @if($reservation->payment_method == 'Bank Transfer')
                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Bank Account:</p>
                        <p class="font-semibold">{{ $reservation->bank_account ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Account Name:</p>
                        <p class="font-semibold">{{ $reservation->bank_account_name ?? '-' }}</p>
                    </div>
                </div>
            @elseif($reservation->payment_method == 'Credit Card')
                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Card Number:</p>
                        <p class="font-semibold">{{ $reservation->masked_cc_number ?? $reservation->cc_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Card Holder:</p>
                        <p class="font-semibold">{{ $reservation->cc_holder_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Card Type:</p>
                        <p class="font-semibold">{{ $reservation->cc_type ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Expired:</p>
                        <p class="font-semibold">{{ $reservation->cc_expired ?? '-' }}</p>
                    </div>
                </div>
            @elseif($reservation->payment_method == 'Cash')
                <div class="mt-3">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-sm text-green-700 font-semibold flex items-center">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Pembayaran Tunai akan dilakukan saat check-in
                        </p>
                        @if($reservation->payment_status == 'paid')
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-check-circle mr-1"></i>Pembayaran sudah lunas
                            </p>
                        @elseif($reservation->payment_status == 'partial')
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>Pembayaran sebagian telah diterima
                            </p>
                        @endif
                    </div>
                </div>
            @endif
            
            {{-- Catatan Pembayaran --}}
            @if(isset($reservation->payment_notes) && $reservation->payment_notes)
                <div class="mt-3 p-3 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-600 font-semibold">Catatan Pembayaran:</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $reservation->payment_notes }}</p>
                </div>
            @endif
        </div>
    @else
        <div class="bg-yellow-50 p-4 rounded-xl">
            <p class="text-yellow-800">Belum ada informasi pembayaran. Silakan lakukan pembayaran untuk menjamin reservasi.</p>
            <button onclick="showPaymentModal()" class="mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                <i class="fas fa-credit-card mr-2"></i>Proses Pembayaran
            </button>
        </div>
    @endif
</div>
    
    {{-- Additional Info --}}
    <div class="mt-6 pt-6 border-t grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
        <div>
            <span class="font-semibold">Safety Deposit Box:</span>
            <p>{{ $reservation->safety_deposit_box ?? '-' }}</p>
        </div>
        <div>
            <span class="font-semibold">Issued By:</span>
            <p>{{ $reservation->issued_by ?? $reservation->user->name }}</p>
        </div>
        <div>
            <span class="font-semibold">Issued Date:</span>
            <p>{{ $reservation->issued_date ? \Carbon\Carbon::parse($reservation->issued_date)->format('d M Y') : '-' }}</p>
        </div>
    </div>
    
    {{-- Cancellation Policy --}}
    <div class="mt-6 pt-6 border-t">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Cancellation Policy:</h3>
        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
            <li>Please note that check in time is 02.00 pm and check out time 12.00 pm.</li>
            <li>All non-guaranteed reservations will automatically be released on 6 pm.</li>
            <li>The Hotel will charge 1 night for guaranteed reservations that have not been canceling before the day of arrival. Please carefully note your cancellation number.</li>
        </ul>
    </div>
</div>

{{-- Payment Modal --}}
<div id="paymentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <h3 class="text-2xl font-bold">Proses Pembayaran</h3>
        </div>
        
        <form action="" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="Bank Transfer" class="mr-2" onchange="togglePaymentMethod('bank')">
                            Transfer Bank
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="Credit Card" class="mr-2" onchange="togglePaymentMethod('cc')">
                            Kartu Kredit
                        </label>
                    </div>
                </div>
                
                <div id="bankTransferFields" class="space-y-4 hidden">
                    <h4 class="font-semibold">Bank Transfer</h4>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Bank Account</label>
                        <input type="text" name="bank_account" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Account Name</label>
                        <input type="text" name="bank_account_name" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
                
                <div id="ccFields" class="space-y-4 hidden">
                    <h4 class="font-semibold">Credit Card</h4>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Card Number</label>
                        <input type="text" name="cc_number" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Card Holder Name</label>
                        <input type="text" name="cc_holder_name" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Card Type</label>
                            <select name="cc_type" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Pilih</option>
                                <option value="Visa">Visa</option>
                                <option value="Mastercard">Mastercard</option>
                                <option value="Amex">American Express</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Expired</label>
                            <input type="text" name="cc_expired" placeholder="MM/YY" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="hidePaymentModal()" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary">
                    Proses Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
    }
    
    function hidePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
    }
    
    function togglePaymentMethod(method) {
        document.getElementById('bankTransferFields').classList.add('hidden');
        document.getElementById('ccFields').classList.add('hidden');
        
        if (method === 'bank') {
            document.getElementById('bankTransferFields').classList.remove('hidden');
        } else if (method === 'cc') {
            document.getElementById('ccFields').classList.remove('hidden');
        }
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('paymentModal');
        if (event.target == modal) {
            hidePaymentModal();
        }
    }
</script>
@endpush
@endsection
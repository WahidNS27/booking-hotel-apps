@extends('layouts.app')

@section('title', 'Edit Reservasi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Reservasi</h1>
    <p class="text-gray-600 mt-1">Edit data reservasi dengan nomor: <span class="font-semibold">{{ $reservation->booking_no }}</span></p>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
    {{-- Tampilkan Error --}}
    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
        <h4 class="font-bold">Terjadi kesalahan:</h4>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Tampilkan Success --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" id="reservationForm">
        @csrf
        @method('PUT')
        
        {{-- Status Badge --}}
        <div class="mb-6 flex justify-end">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    <option value="non-guaranteed" {{ $reservation->status == 'non-guaranteed' ? 'selected' : '' }}>Belum Terjamin</option>
                    <option value="guaranteed" {{ $reservation->status == 'guaranteed' ? 'selected' : '' }}>Terjamin</option>
                    <option value="checked-in" {{ $reservation->status == 'checked-in' ? 'selected' : '' }}>Check In</option>
                    <option value="checked-out" {{ $reservation->status == 'checked-out' ? 'selected' : '' }}>Check Out</option>
                    <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
        </div>
        
        {{-- Informasi Booking --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs text-gray-500">Booking No</label>
                    <p class="font-semibold">{{ $reservation->booking_no }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Booking Date</label>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($reservation->booking_date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Book By</label>
                    <p class="font-semibold">{{ $reservation->book_by }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Receptionist</label>
                    <p class="font-semibold">{{ $reservation->user->name ?? session('user') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Data Tamu --}}
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 flex items-center">
                    <i class="fas fa-user text-primary mr-2"></i>Data Tamu
                </h3>
                
                <input type="hidden" name="guest_id" value="{{ $reservation->guest_id }}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="guest_name" id="guest_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 @error('guest_name') border-red-500 @enderror"
                           value="{{ old('guest_name', $reservation->guest->name) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        No. Telepon/HP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="guest_phone" id="guest_phone" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 @error('guest_phone') border-red-500 @enderror"
                           value="{{ old('guest_phone', $reservation->guest->phone) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="guest_email" id="guest_email"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                           value="{{ old('guest_email', $reservation->guest->email) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                    <input type="text" name="guest_company" id="guest_company"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                           value="{{ old('guest_company', $reservation->guest->company) }}">
                </div>
            </div>
            
            {{-- Data Kamar --}}
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 flex items-center">
                    <i class="fas fa-bed text-primary mr-2"></i>Data Kamar
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Kamar <span class="text-red-500">*</span>
                    </label>
                    <select name="room_type" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 @error('room_type') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kamar</option>
                        <option value="Standard" {{ old('room_type', $reservation->room_type) == 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="Deluxe" {{ old('room_type', $reservation->room_type) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="Suite" {{ old('room_type', $reservation->room_type) == 'Suite' ? 'selected' : '' }}>Suite</option>
                        <option value="Family" {{ old('room_type', $reservation->room_type) == 'Family' ? 'selected' : '' }}>Family</option>
                        <option value="Executive" {{ old('room_type', $reservation->room_type) == 'Executive' ? 'selected' : '' }}>Executive</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Kamar <span class="text-red-500">*</span>
                    </label>
                    <div id="roomNumbersContainer" class="space-y-2">
                        @php
                            $roomNumbers = is_array($reservation->room_numbers) 
                                ? $reservation->room_numbers 
                                : json_decode($reservation->room_numbers, true) ?? [];
                        @endphp
                        
                        @foreach($roomNumbers as $index => $roomNumber)
                        <div class="flex gap-2 room-input">
                            <input type="text" name="room_numbers[]" placeholder="Contoh: 0601" required
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                                   value="{{ $roomNumber }}">
                            @if($loop->first)
                            <button type="button" class="add-room-btn px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                            @else
                            <button type="button" class="remove-room-btn px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Klik + untuk menambah nomor kamar</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Kamar <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="number_of_rooms" id="number_of_rooms" min="1" 
                               value="{{ old('number_of_rooms', $reservation->number_of_rooms) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Tamu <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="number_of_persons" id="number_of_persons" min="1" 
                               value="{{ old('number_of_persons', $reservation->number_of_persons) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="arrival_date" id="arrival_date" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                               value="{{ old('arrival_date', $reservation->arrival_date) }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Check-in</label>
                        <input type="time" name="arrival_time" 
                               value="{{ old('arrival_time', $reservation->arrival_time ?? '14:00') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="departure_date" id="departure_date" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                               value="{{ old('departure_date', $reservation->departure_date) }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Malam</label>
                        <input type="text" id="total_nights_display" readonly
                               class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700"
                               value="{{ $reservation->total_nights }} malam">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Data Agent/Perusahaan --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-building text-primary mr-2"></i>Data Agent / Perusahaan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Agent/Perusahaan</label>
                    <input type="text" name="company_agent" value="{{ old('company_agent', $reservation->company_agent) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon Agent</label>
                    <input type="text" name="agent_telp" value="{{ old('agent_telp', $reservation->agent_telp) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fax Agent</label>
                    <input type="text" name="agent_fax" value="{{ old('agent_fax', $reservation->agent_fax) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Agent</label>
                    <input type="email" name="agent_email" value="{{ old('agent_email', $reservation->agent_email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>
        </div>
        
        {{-- Data Harga dan Pembayaran --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-credit-card text-primary mr-2"></i>Harga dan Pembayaran
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Malam (Rp)</label>
                    <input type="number" name="room_rate_net" id="room_rate" 
                           value="{{ old('room_rate_net', $reservation->room_rate_net) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                    <input type="text" id="total_price_display" readonly 
                           value="Rp {{ number_format(($reservation->room_rate_net ?? 0) * $reservation->number_of_rooms * $reservation->total_nights, 0, ',', '.') }}"
                           class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="Bank Transfer" {{ old('payment_method', $reservation->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Credit Card" {{ old('payment_method', $reservation->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                    </select>
                </div>
            </div>
        </div>
        
        {{-- Data Transfer Bank --}}
        <div class="mt-6 pt-4 border-t border-gray-200" id="bankTransferFields" style="display: {{ old('payment_method', $reservation->payment_method) == 'Bank Transfer' ? 'block' : 'none' }};">
            <h4 class="text-lg font-semibold text-gray-700 mb-3">Detail Transfer Bank</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $reservation->bank_account) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $reservation->bank_account_name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>
        </div>

        {{-- Data Credit Card --}}
        <div class="mt-6 pt-4 border-t border-gray-200" id="ccFields" style="display: {{ old('payment_method', $reservation->payment_method) == 'Credit Card' ? 'block' : 'none' }};">
            <h4 class="text-lg font-semibold text-gray-700 mb-3">Detail Kartu Kredit</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                    <input type="text" name="cc_number" value="{{ old('cc_number', $reservation->cc_number) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Holder Name</label>
                    <input type="text" name="cc_holder_name" value="{{ old('cc_holder_name', $reservation->cc_holder_name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
                    <select name="cc_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                        <option value="">Pilih Tipe Kartu</option>
                        <option value="Visa" {{ old('cc_type', $reservation->cc_type) == 'Visa' ? 'selected' : '' }}>Visa</option>
                        <option value="Mastercard" {{ old('cc_type', $reservation->cc_type) == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                        <option value="Amex" {{ old('cc_type', $reservation->cc_type) == 'Amex' ? 'selected' : '' }}>American Express</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expired Date</label>
                    <input type="text" name="cc_expired" placeholder="MM/YY" value="{{ old('cc_expired', $reservation->cc_expired) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Holder Signature</label>
                    <textarea name="cc_signature" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">{{ old('cc_signature', $reservation->cc_signature) }}</textarea>
                </div>
            </div>
        </div>
        
        {{-- Data Tambahan --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list text-primary mr-2"></i>Data Tambahan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Safety Deposit Box</label>
                    <input type="text" name="safety_deposit_box" value="{{ old('safety_deposit_box', $reservation->safety_deposit_box) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dikeluarkan Oleh</label>
                    <input type="text" name="issued_by" value="{{ old('issued_by', $reservation->issued_by ?? session('user')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dikeluarkan</label>
                    <input type="date" name="issued_date" value="{{ old('issued_date', $reservation->issued_date ?? date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cancellation Number</label>
                    <input type="text" name="cancellation_number" value="{{ old('cancellation_number', $reservation->cancellation_number) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>
        </div>
        
        {{-- Tombol Submit --}}
        <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('reservations.show', $reservation->id) }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-secondary transition flex items-center justify-center">
                <i class="fas fa-save mr-2"></i>Update Reservasi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hitung total malam
    function calculateNights() {
        const arrival = document.getElementById('arrival_date').value;
        const departure = document.getElementById('departure_date').value;
        
        if (arrival && departure) {
            const start = new Date(arrival);
            const end = new Date(departure);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            document.getElementById('total_nights_display').value = diffDays + ' malam';
            calculateTotalPrice();
        }
    }
    
    // Hitung total harga
    function calculateTotalPrice() {
        const rate = parseInt(document.getElementById('room_rate').value) || 0;
        const rooms = parseInt(document.getElementById('number_of_rooms').value) || 1;
        const nightsText = document.getElementById('total_nights_display').value;
        const nights = parseInt(nightsText) || 0;
        
        const total = rate * rooms * nights;
        
        document.getElementById('total_price_display').value = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    // Set minimum date untuk arrival
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('arrival_date').min = today;
    
    // Event listeners
    document.getElementById('arrival_date').addEventListener('change', function() {
        const departure = document.getElementById('departure_date');
        departure.min = this.value;
        calculateNights();
    });
    
    document.getElementById('departure_date').addEventListener('change', calculateNights);
    document.getElementById('room_rate').addEventListener('input', calculateTotalPrice);
    document.getElementById('number_of_rooms').addEventListener('input', calculateTotalPrice);
    
    // Toggle payment fields
    document.getElementById('payment_method').addEventListener('change', function() {
        const bankFields = document.getElementById('bankTransferFields');
        const ccFields = document.getElementById('ccFields');
        
        bankFields.style.display = 'none';
        ccFields.style.display = 'none';
        
        if (this.value === 'Bank Transfer') {
            bankFields.style.display = 'block';
        } else if (this.value === 'Credit Card') {
            ccFields.style.display = 'block';
        }
    });
    
    // Tambah input room number
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-room-btn') || e.target.closest('.add-room-btn')) {
            e.preventDefault();
            const container = document.getElementById('roomNumbersContainer');
            const newInput = document.createElement('div');
            newInput.className = 'flex gap-2 room-input';
            newInput.innerHTML = `
                <input type="text" name="room_numbers[]" placeholder="Contoh: 0601" required
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                <button type="button" class="remove-room-btn px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newInput);
        }
        
        if (e.target.classList.contains('remove-room-btn') || e.target.closest('.remove-room-btn')) {
            e.preventDefault();
            const roomInput = e.target.closest('.room-input');
            if (document.querySelectorAll('.room-input').length > 1) {
                roomInput.remove();
            } else {
                alert('Minimal harus ada 1 nomor kamar');
            }
        }
    });
});
</script>
@endpush
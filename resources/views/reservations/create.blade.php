@extends('layouts.app')

@section('title', 'Buat Reservasi Baru')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Buat Reservasi Baru</h1>
    <p class="text-gray-600 mt-1">Isi form berikut untuk membuat reservasi baru</p>
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

    <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm">
        @csrf
        
       
        
        {{-- Data Reservasi Utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Data Tamu --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                    <i class="fas fa-user text-primary mr-2"></i>Data Tamu
                </h3>
                
                <input type="hidden" name="guest_id" id="guest_id" value="{{ old('guest_id') }}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="guest_name" id="guest_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 @error('guest_name') border-red-500 @enderror"
                           value="{{ old('guest_name') }}">
                    @error('guest_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        No. Telepon/HP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="guest_phone" id="guest_phone" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 @error('guest_phone') border-red-500 @enderror"
                           value="{{ old('guest_phone') }}">
                    @error('guest_phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="guest_email" id="guest_email"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                           value="{{ old('guest_email') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                    <input type="text" name="guest_company" id="guest_company"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                           value="{{ old('guest_company') }}">
                </div>
            </div>
            
            {{-- Data Kamar --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                    <i class="fas fa-bed text-primary mr-2"></i>Data Kamar
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Kamar <span class="text-red-500">*</span>
                    </label>
                    <select name="room_type" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 @error('room_type') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kamar</option>
                        <option value="Standard" {{ old('room_type') == 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="Deluxe" {{ old('room_type') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="Suite" {{ old('room_type') == 'Suite' ? 'selected' : '' }}>Suite</option>
                        <option value="Family" {{ old('room_type') == 'Family' ? 'selected' : '' }}>Family</option>
                        <option value="Executive" {{ old('room_type') == 'Executive' ? 'selected' : '' }}>Executive</option>
                    </select>
                    @error('room_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Kamar <span class="text-red-500">*</span>
                    </label>
                    <div id="roomNumbersContainer" class="space-y-2">
                        <div class="flex gap-2 room-input">
                            <input type="text" name="room_numbers[]" placeholder="Contoh: 0601" required
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                                   value="{{ old('room_numbers.0') }}">
                            <button type="button" class="add-room-btn px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Klik + untuk menambah nomor kamar</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Kamar <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="number_of_rooms" id="number_of_rooms" min="1" value="{{ old('number_of_rooms', 1) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Tamu <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="number_of_persons" id="number_of_persons" min="1" value="{{ old('number_of_persons', 1) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="arrival_date" id="arrival_date" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                               value="{{ old('arrival_date') }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Check-in</label>
                        <input type="time" name="arrival_time" value="{{ old('arrival_time', '14:00') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="departure_date" id="departure_date" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20"
                               value="{{ old('departure_date') }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Malam</label>
                        <input type="text" id="total_nights_display" readonly
                               class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700"
                               value="0 malam">
                        <input type="hidden" name="total_nights" id="total_nights" value="0">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Data Agent/Perusahaan --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-building text-primary mr-2"></i>Data Agent / Perusahaan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Agent/Perusahaan</label>
                    <input type="text" name="company_agent" value="{{ old('company_agent') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon Agent</label>
                    <input type="text" name="agent_telp" value="{{ old('agent_telp') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fax Agent</label>
                    <input type="text" name="agent_fax" value="{{ old('agent_fax') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Agent</label>
                    <input type="email" name="agent_email" value="{{ old('agent_email') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>
        </div>
        
        {{-- Data Harga dan Pembayaran --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-credit-card text-primary mr-2"></i>Harga dan Pembayaran
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Malam (Rp)</label>
                    <input type="number" name="room_rate_net" id="room_rate" value="{{ old('room_rate_net', 0) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                    <input type="text" id="total_price_display" readonly value="Rp 0"
                           class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                    <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>DP</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Reservasi</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                        <option value="non-guaranteed" {{ old('status') == 'non-guaranteed' ? 'selected' : '' }}>Non Guaranteed</option>
                        <option value="guaranteed" {{ old('status') == 'guaranteed' ? 'selected' : '' }}>Guaranteed</option>
                        <option value="checked-in" {{ old('status') == 'checked-in' ? 'selected' : '' }}>Check In</option>
                        <option value="checked-out" {{ old('status') == 'checked-out' ? 'selected' : '' }}>Check Out</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Number</label>
                    <input type="text" name="booking_no" value="{{ old('booking_no', 'BK-' . date('Ymd') . '-' . rand(100, 999)) }}" readonly
                           class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>
            </div>
        </div>
        
        {{-- Data Transfer Bank --}}
        <div class="mt-4 pt-3 border-t border-gray-200" id="bankTransferFields" style="display: none;">
            <h4 class="text-md font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-university text-primary mr-2"></i>Detail Transfer Bank
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>
        </div>

        {{-- Data Credit Card --}}
        <div class="mt-4 pt-3 border-t border-gray-200" id="ccFields" style="display: none;">
            <h4 class="text-md font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-credit-card text-primary mr-2"></i>Detail Kartu Kredit
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                    <input type="text" name="cc_number" value="{{ old('cc_number') }}" maxlength="16"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Holder Name</label>
                    <input type="text" name="cc_holder_name" value="{{ old('cc_holder_name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
                    <select name="cc_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                        <option value="">Pilih Tipe Kartu</option>
                        <option value="Visa" {{ old('cc_type') == 'Visa' ? 'selected' : '' }}>Visa</option>
                        <option value="Mastercard" {{ old('cc_type') == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                        <option value="Amex" {{ old('cc_type') == 'Amex' ? 'selected' : '' }}>American Express</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expired Date</label>
                    <input type="text" name="cc_expired" placeholder="MM/YY" value="{{ old('cc_expired') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Holder Signature</label>
                    <textarea name="cc_signature" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">{{ old('cc_signature') }}</textarea>
                </div>
            </div>
        </div>
        
        {{-- Data Tambahan --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-clipboard-list text-primary mr-2"></i>Data Tambahan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Safety Deposit Box</label>
                    <input type="text" name="safety_deposit_box" value="{{ old('safety_deposit_box') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dikeluarkan Oleh</label>
                    <input type="text" name="issued_by" value="{{ old('issued_by', auth()->user()->name ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dikeluarkan</label>
                    <input type="date" name="issued_date" value="{{ old('issued_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Book By</label>
                    <input type="text" name="book_by" value="{{ old('book_by', auth()->user()->name ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cancellation Number</label>
                    <input type="text" name="cancellation_number" value="{{ old('cancellation_number') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="remarks" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:ring focus:ring-primary/20">{{ old('remarks') }}</textarea>
            </div>
        </div>
        
        {{-- Tombol Submit --}}
        <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('reservations.index') }}" 
               class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition flex items-center justify-center">
                <i class="fas fa-save mr-2"></i>Simpan Reservasi
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
            document.getElementById('total_nights').value = diffDays;
            calculateTotalPrice();
        }
    }
    
    // Hitung total harga
    function calculateTotalPrice() {
        const rate = parseInt(document.getElementById('room_rate').value) || 0;
        const rooms = parseInt(document.getElementById('number_of_rooms').value) || 1;
        const nights = parseInt(document.getElementById('total_nights').value) || 0;
        
        const total = rate * rooms * nights;
        
        document.getElementById('total_price_display').value = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    // Set today's date as minimum for arrival
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('arrival_date').min = today;
    
    // Set departure date min based on arrival
    document.getElementById('arrival_date').addEventListener('change', function() {
        const departure = document.getElementById('departure_date');
        departure.min = this.value;
        
        // If departure date is less than arrival, update it
        if (departure.value && departure.value < this.value) {
            departure.value = this.value;
        }
        
        calculateNights();
    });
    
    document.getElementById('departure_date').addEventListener('change', calculateNights);
    document.getElementById('room_rate').addEventListener('input', calculateTotalPrice);
    document.getElementById('number_of_rooms').addEventListener('input', function() {
        calculateTotalPrice();
        
        // Update room numbers count jika perlu
        const rooms = parseInt(this.value) || 1;
        const roomInputs = document.querySelectorAll('.room-input');
        
        if (roomInputs.length < rooms) {
            // Tambah input sampai sesuai jumlah kamar
            for (let i = roomInputs.length; i < rooms; i++) {
                addRoomInput();
            }
        } else if (roomInputs.length > rooms) {
            // Kurangi input sampai sesuai jumlah kamar
            for (let i = roomInputs.length; i > rooms; i--) {
                roomInputs[i-1].remove();
            }
        }
    });
    
    // Fungsi untuk menambah input room
    function addRoomInput() {
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
    
    // Toggle payment fields based on payment method
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

// Trigger on page load if there's old value
window.addEventListener('load', function() {
    const paymentMethod = document.getElementById('payment_method');
    if (paymentMethod.value) {
        const event = new Event('change');
        paymentMethod.dispatchEvent(event);
    }
});
    
    // Tambah room input dengan tombol +
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-room-btn') || e.target.closest('.add-room-btn')) {
            e.preventDefault();
            addRoomInput();
            
            // Update jumlah kamar
            const roomCount = document.querySelectorAll('.room-input').length;
            document.getElementById('number_of_rooms').value = roomCount;
        }
        
        if (e.target.classList.contains('remove-room-btn') || e.target.closest('.remove-room-btn')) {
            e.preventDefault();
            const roomInput = e.target.closest('.room-input');
            
            if (document.querySelectorAll('.room-input').length > 1) {
                roomInput.remove();
                
                // Update jumlah kamar
                const roomCount = document.querySelectorAll('.room-input').length;
                document.getElementById('number_of_rooms').value = roomCount;
            } else {
                alert('Minimal harus ada 1 nomor kamar');
            }
        }
    });
    
    // Search guest functionality (simulasi)
    document.getElementById('searchGuestBtn').addEventListener('click', function() {
        const searchTerm = document.getElementById('guestSearch').value.trim();
        
        if (searchTerm.length < 2) {
            alert('Masukkan minimal 2 karakter untuk mencari');
            return;
        }
        
        // Simulasi pencarian - dalam implementasi nyata, ini akan AJAX ke server
        const searchResults = document.getElementById('searchResults');
        const select = document.getElementById('guestSearchResults');
        
        // Kosongkan options
        select.innerHTML = '';
        
        // Contoh hasil pencarian (harusnya dari AJAX)
        const dummyResults = [
            { id: 1, name: 'John Doe', phone: '08123456789' },
            { id: 2, name: 'Jane Smith', phone: '08129876543' }
        ];
        
        dummyResults.forEach(guest => {
            const option = document.createElement('option');
            option.value = guest.id;
            option.textContent = `${guest.name} - ${guest.phone}`;
            option.dataset.name = guest.name;
            option.dataset.phone = guest.phone;
            select.appendChild(option);
        });
        
        searchResults.classList.remove('hidden');
    });
    
    // Pilih hasil pencarian
    document.getElementById('guestSearchResults').addEventListener('click', function(e) {
        if (e.target.tagName === 'OPTION') {
            document.getElementById('guest_id').value = e.target.value;
            document.getElementById('guest_name').value = e.target.dataset.name;
            document.getElementById('guest_phone').value = e.target.dataset.phone;
            
            // Sembunyikan hasil pencarian
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('guestSearch').value = '';
        }
    });
    
    // Format input kartu kredit
    document.querySelector('input[name="cc_number"]')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
    
    // Format expired date
    document.querySelector('input[name="cc_expired"]')?.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length >= 2) {
            this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            this.value = value;
        }
    });
    
    // Trigger initial calculation jika ada data
    if (document.getElementById('arrival_date').value && document.getElementById('departure_date').value) {
        calculateNights();
    }
});
</script>
@endpush
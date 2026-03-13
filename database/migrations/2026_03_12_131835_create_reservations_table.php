<?php
// database/migrations/2024_01_01_000002_create_reservations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Receptionist
            
            // Data Reservasi dari Gambar 1 & 2
            $table->string('booking_no')->unique(); // Booking No. (Output)
            $table->string('book_by')->nullable(); // Book By (Output)
            $table->date('booking_date'); // Date (Output)
            
            // Data Kamar (dari Gambar 1)
            $table->json('room_numbers'); // Menyimpan array room numbers [0601, 0602]
            $table->integer('number_of_rooms'); // Jumlah Kamar (No. of Room)
            $table->integer('number_of_persons'); // Jumlah Tamu (No. of Person) / Person Pax
            $table->string('room_type'); // Jenis Kamar (Room Type)
            
            // Data Waktu (dari Gambar 1 & 2)
            $table->date('arrival_date'); // Tanggal Kedatangan (Arrival Date)
            $table->time('arrival_time')->nullable(); // Waktu Kedatangan (Arrival Time)
            $table->date('departure_date'); // Tanggal Keberangkatan (Departure Date)
            $table->integer('total_nights'); // Total Night (bisa dihitung otomatis)
            
            // Data Perusahaan/Agent (dari Gambar 2)
            $table->string('company_agent')->nullable(); // Company / Agent
            $table->string('agent_telp')->nullable(); // Telp
            $table->string('agent_fax')->nullable(); // Fax
            $table->string('agent_email')->nullable(); // Email (Agent)
            
            // Data Pembayaran (dari Gambar 2)
            $table->integer('room_rate_net')->nullable(); // Room Rate Net
            $table->enum('payment_method', ['Bank Transfer', 'Credit Card'])->nullable();
            
            // Bank Transfer
            $table->string('bank_account')->nullable(); // Mandiri Account
            $table->string('bank_account_name')->nullable(); // Mandiri Name Account
            
            // Credit Card
            $table->string('cc_number')->nullable(); // Card Number
            $table->string('cc_holder_name')->nullable(); // Card holder name
            $table->string('cc_type')->nullable(); // Card Type
            $table->string('cc_expired')->nullable(); // Expired date/month/year
            $table->text('cc_signature')->nullable(); // Card holder signature
            
            // Data Tambahan Gambar 1
            $table->string('safety_deposit_box')->nullable(); // Nomor Kotak Deposit
            $table->string('issued_by')->nullable(); // Dikeluarkan oleh (Issued By)
            $table->date('issued_date')->nullable(); // Tanggal (Date) - dari Gambar 1 bagian bawah
            
            // Status Reservasi
            $table->enum('status', ['guaranteed', 'non-guaranteed', 'cancelled', 'checked-in', 'checked-out'])->default('non-guaranteed');
            $table->string('cancellation_number')->nullable(); // Cancellation number untuk referensi
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
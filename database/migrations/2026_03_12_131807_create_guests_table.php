<?php
// database/migrations/2024_01_01_000001_create_guests_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama tamu
            $table->string('profession')->nullable(); // Pekerjaan
            $table->string('company')->nullable(); // Perusahaan/Company
            $table->string('nationality')->nullable(); // Kebangsaan
            $table->date('birth_date')->nullable(); // Tanggal Lahir
            $table->text('address')->nullable(); // Alamat
            $table->string('phone'); // Telephone/Phone/Handphone
            $table->string('email')->nullable(); // Email
            $table->string('member_no')->nullable(); // No. Member
            $table->string('identity_number')->nullable(); // No. Identitas (KTP/Passport)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
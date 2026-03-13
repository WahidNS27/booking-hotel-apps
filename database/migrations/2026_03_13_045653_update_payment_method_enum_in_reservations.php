<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePaymentMethodEnumInReservations extends Migration
{
    public function up()
    {
        // Ubah kolom payment_method menjadi string dulu
        DB::statement("ALTER TABLE reservations MODIFY payment_method VARCHAR(20)");
        
        // Lalu ubah kembali menjadi ENUM dengan nilai baru
        DB::statement("ALTER TABLE reservations MODIFY payment_method ENUM('Bank Transfer', 'Credit Card', 'Cash')");
    }

    public function down()
    {
        // Kembalikan ke ENUM awal
        DB::statement("ALTER TABLE reservations MODIFY payment_method ENUM('Bank Transfer', 'Credit Card')");
    }
}
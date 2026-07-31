<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('applicants', function (Blueprint $table) {
        // Mengubah ENUM menjadi string biasa agar tidak error saat ganti-ganti status
        $table->string('status')->default('pending')->change();
    });
}

public function down()
{
    Schema::table('applicants', function (Blueprint $table) {
        $table->enum('status', ['pending', 'technical', 'hired', 'rejected'])->default('pending')->change();
    });
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();

            // TAMBAHKAN KOLOM DI SINI:
            $table->string('nama_pelapor');
            $table->text('isi_pengaduan');
            $table->string('foto_bukti')->nullable();
            $table->string('status')->default('Pending');
            $table->string('kategori_ai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};

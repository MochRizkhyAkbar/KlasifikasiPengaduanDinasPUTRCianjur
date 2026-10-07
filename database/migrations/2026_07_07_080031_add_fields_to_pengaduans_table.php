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
        Schema::table('pengaduans', function (Blueprint $table) {
            // Menambahkan kolom untuk skor klasifikasi AI
            $table->float('confidence_score')->nullable()->after('kategori_ai');

            // Menambahkan kolom untuk relasi admin/petugas yang menangani
            $table->unsignedBigInteger('user_id')->nullable()->after('status');

            // Jika kolom 'status' belum ada di tabel sebelumnya, hapus komentar di bawah:
            // $table->string('status')->default('Pending')->after('kategori_ai');

            // Jika ingin menambahkan relasi foreign key ke tabel users:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['confidence_score', 'user_id']);
        });
    }
};

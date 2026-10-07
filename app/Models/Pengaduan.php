<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'nama_pelapor',
        'no_wa',
        'email',
        'isi_pengaduan',
        'lokasi',
        'foto_bukti',
        'kode_pengaduan',
        'kategori_ai',
        'confidence_score', // Tambahan: Simpan nilai akurasi klasifikasi
        'status',
        'alasan_penolakan',
        'user_id',           // Tambahan: Relasi jika pengaduan ditangani oleh admin tertentu
        'catatan_bidang'
    ];

    /**
     * Memastikan kolom status memiliki nilai default saat pertama kali dibuat
     */
    protected $attributes = [
        'status' => 'Pending',
    ];

    // Tambahan: Relasi ke User (Admin yang menindaklanjuti)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusMasyarakatAttribute() {
        return match($this->status) {
            'Pending' => 'Sedang diverifikasi oleh Admin',
            'Diterima', 'Didisposisikan', 'Diproses' => 'Sedang ditindaklanjuti oleh bidang terkait',
            'Selesai' => 'Laporan telah selesai ditangani',
            'Dikembalikan', 'Ditolak' => 'Laporan ditolak atau perlu informasi tambahan',
            default => 'Sedang diproses',
        };
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id'); // Sesuaikan dengan foreign key Anda
    }
}

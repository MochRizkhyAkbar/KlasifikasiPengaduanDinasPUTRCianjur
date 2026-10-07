<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBidangController extends Controller
{
    // Menampilkan daftar pengaduan yang masuk ke bidang terkait
    public function index()
    {
        $user = User::find(Auth::id());
        $roleBidang = $user->getRoleNames()->first();

        // Data hanya masuk ke admin bidang jika Admin Dinas sudah mengubah statusnya menjadi 'Diterima' (atau 'Diproses'/'Selesai')
        $pengaduans = Pengaduan::with('user')
            ->where('kategori_ai', $roleBidang)
            ->whereIn('status', ['Diterima', 'Diproses', 'Didisposisikan', 'Selesai', 'Dikembalikan', 'Ditolak'])
            ->orderByRaw("
                CASE
                    WHEN status = 'Diterima' THEN 1
                    WHEN status = 'Diproses' THEN 2
                    WHEN status = 'Didisposisikan' THEN 3
                    WHEN status = 'Dikembalikan' THEN 4
                    WHEN status = 'Selesai' THEN 5
                    ELSE 6
                END ASC
            ")
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin_bidang.tindaklanjuti', compact('pengaduans'));
    }

    public function dashboard()
    {
        $user = User::find(Auth::id());
        $roleBidang = $user->getRoleNames()->first();
        $query = Pengaduan::where('kategori_ai', $roleBidang);

        // Statistik untuk Kartu Dashboard (disesuaikan dengan key 'dikembalikan')
        $statistik = [
            'diterima'     => (clone $query)->where('status', 'Diterima')->count(),
            'diproses'     => (clone $query)->where('status', 'Diproses')->count(),
            'selesai'      => (clone $query)->where('status', 'Selesai')->count(),
            'dikembalikan' => (clone $query)->where('status', 'Dikembalikan')->count(),
            'ditolak'      => (clone $query)->where('status', 'Ditolak')->count(),
        ];

        // 1. Data Grafik Per Tahun
        $grafikTahun = (clone $query)
            ->selectRaw("strftime('%Y', created_at) as tahun, COUNT(*) as total")
            ->groupBy('tahun')
            ->pluck('total', 'tahun')->toArray();

        // 2. Data Grafik Per Bulan
        $dataBulananRaw = (clone $query)
            ->selectRaw("strftime('%m', created_at) as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        $grafikBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $grafikBulan[] = $dataBulananRaw[str_pad($i, 2, '0', STR_PAD_LEFT)] ?? 0;
        }

        // 3. Data Grafik 7 Hari Terakhir
        $grafikHari = (clone $query)
            ->selectRaw("date(created_at) as tanggal, COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal')->toArray();

        return view('admin_bidang.dashboard', compact('statistik', 'grafikTahun', 'grafikBulan', 'grafikHari'));
    }

    // Memproses update status/kategori oleh Admin Bidang
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:Diterima,Diproses,Selesai,Dikembalikan,Tolak',
            'catatan' => 'required_if:status,Tolak|required_if:status,Dikembalikan|nullable|string|max:500',
            'kategori_baru' => 'nullable|string',
        ], [
            'catatan.required_if' => 'Catatan wajib diisi jika pengaduan dikembalikan atau ditolak untuk menjelaskan alasannya.'
        ]);

        $pengaduan = Pengaduan::findOrFail($id);

        if ($request->filled('kategori_baru')) {
            $pengaduan->kategori_ai = $request->kategori_baru;
            $pengaduan->status = 'Didisposisikan';
        } else {
            if ($request->status === 'Tolak') {
                $pengaduan->status = 'Ditolak';
            } else {
                $pengaduan->status = $request->status;
            }
        }

        $pengaduan->catatan_bidang = $request->catatan;
        $pengaduan->save();

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }
}

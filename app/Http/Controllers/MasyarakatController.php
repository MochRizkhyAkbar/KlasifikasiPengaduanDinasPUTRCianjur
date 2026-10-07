<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class MasyarakatController extends Controller
{
    public function create()
    {
        return view('masyarakat.input_pengaduan');
    }

    public function search(Request $request)
    {
        $pengaduan = null;

        if ($request->has('kode') && $request->kode != '') {
            $pengaduan = Pengaduan::where('kode_pengaduan', $request->kode)->first();

            if (!$pengaduan) {
                return back()->with('error', 'Kode pengaduan tidak ditemukan.');
            }
        }

        return view('masyarakat.cari_pengaduan', compact('pengaduan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'no_wa'        => 'required|string|max:15',
            'email'        => 'required|email|max:255',
            'isi_pengaduan' => 'required|string',
            'lokasi'       => 'required|string|max:255',
            'foto_bukti'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Path ke Python dan script predict.py
            $pythonPath = 'python';
            $scriptPath = base_path('python/predict.py');
            $teks       = escapeshellarg($request->isi_pengaduan);

            // Jalankan script Python langsung (tidak perlu server uvicorn)
            $output = shell_exec("$pythonPath $scriptPath $teks 2>&1");

            if (!$output) {
                return back()->with('error', 'Gagal menjalankan proses klasifikasi. Pastikan Python sudah terinstall.');
            }

            $aiResult = json_decode($output, true);

            if (!$aiResult || isset($aiResult['error'])) {
                return back()->with('error', 'Gagal memproses klasifikasi: ' . ($aiResult['error'] ?? 'Output tidak valid'));
            }

            // Mapping label AI ke kode kategori internal
            $labelMap = [
                'Bidang Sumber Daya Air(SDA)'              => 'bidangSDA',
                'bidang Jalan'                              => 'bidangJALAN',
                'Bidang Tata Ruang'                        => 'bidangTATARUANG',
                'bidang Bina Kontruksi dan Teknik(Binkon)' => 'bidangBINKON',
                'bukan pupr'                               => 'BUKAN PUTR',
            ];
            $aiResult['kategori_label'] = $labelMap[$aiResult['kategori_label']] ?? $aiResult['kategori_label'];


            $kode = 'PGD-' . substr(time(), -6);

            $pengaduan = new Pengaduan();
            $pengaduan->nama_pelapor  = $request->nama_pelapor;
            $pengaduan->no_wa         = $request->no_wa;
            $pengaduan->email        = $request->email;
            $pengaduan->isi_pengaduan = $request->isi_pengaduan;
            $pengaduan->lokasi        = $request->lokasi;
            $pengaduan->kode_pengaduan = $kode;
            $pengaduan->status        = 'Pending';
            $pengaduan->kategori_ai      = $aiResult['kategori_label'];
            $pengaduan->confidence_score      = $aiResult['confidence_score'];

            if ($request->hasFile('foto_bukti')) {
                $path = $request->file('foto_bukti')->store('bukti', 'public');
                $pengaduan->foto_bukti = $path;
            }

            $pengaduan->save();

            return redirect()->route('pengaduan.create')
            ->with('success', 'Pengaduan berhasil dikirim! Kode: <strong>' . $kode . '</strong>');


            } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat klasifikasi: ' . $e->getMessage());
        }


    }
}

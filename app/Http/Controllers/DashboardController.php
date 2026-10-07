<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;

class DashboardController extends Controller
{
    /**
     * Tampilan Dashboard Admin Dinas
     */
    public function dinas()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Akses ditolak.');
        }

        // 1. Statistik Angka
        $totalTerverifikasi = Pengaduan::whereIn('status', ['Diterima', 'Ditolak'])->count();
        $totalPending = Pengaduan::where('status', 'Pending')->count();
        $totalDitolak = Pengaduan::where('status', 'Ditolak')->count();

        // 2. Data Grafik (Sintaks SQLite)
        // Grafik Per Bulan (Tahun Ini)
        $grafikPerBulan = Pengaduan::selectRaw("strftime('%m', created_at) as bulan, count(*) as total")
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('total', 'bulan');

        // Grafik Per Tahun (5 Tahun Terakhir)
        $grafikPerTahun = Pengaduan::selectRaw("strftime('%Y', created_at) as tahun, count(*) as total")
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->limit(5)
            ->pluck('total', 'tahun');

        // Grafik Per Hari (7 Hari Terakhir)
        $grafikPerHari = Pengaduan::selectRaw("strftime('%d', created_at) as hari, count(*) as total")
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('hari')
            ->orderBy('hari', 'asc')
            ->pluck('total', 'hari');

        return view('admin_dinas.dashboard', compact(
            'totalTerverifikasi',
            'totalPending',
            'totalDitolak',
            'grafikPerBulan',
            'grafikPerTahun',
            'grafikPerHari'
        ));
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dinas.dashboard');
        }
        if ($user->hasAnyRole(['bidangSDA', 'bidangBINKON', 'bidangTATARUANG', 'bidangJALAN'])) {
            return redirect()->route('admin.bidang.dashboard');
        }
        Auth::logout();
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses.');
    }

    public function bidang()
    {
        if (!$this->isBidangAdmin(Auth::user())) {
            abort(403, 'Akses ditolak.');
        }

        return view('admin_bidang.dashboard');
    }

    private function isBidangAdmin($user)
    {
        return $user->hasAnyRole(['bidangSDA', 'bidangBINKON', 'bidangTATARUANG', 'bidangJALAN']);
    }
}

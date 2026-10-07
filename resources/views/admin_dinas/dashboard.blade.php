@extends('layouts.admin_layout')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Dashboard Admin Dinas</h1>
    <p class="text-gray-600 mb-8">Selamat datang, {{ auth()->user()->name }}. Berikut adalah ringkasan informasi pengaduan.</p>

    {{-- Statistik Cards (TETAP SEPERTI INI) --}}
   <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-900 text-white p-6 rounded-lg shadow">
            <h3 class="font-bold">Total Aduan Terverifikasi</h3>
            <p class="text-3xl font-extrabold mt-2">{{ $statistik['diterima'] ?? 0 }}</p>
        </div>

        <div class="bg-yellow-600 text-white p-6 rounded-lg shadow">
            <h3 class="font-bold">Total Aduan Belum Terverifikasi</h3>
            <p class="text-3xl font-extrabold mt-2">{{ $statistik['pending'] ?? 0 }}</p>
        </div>

        <div class="bg-red-700 text-white p-6 rounded-lg shadow">
            <h3 class="font-bold">Total Aduan Ditolak</h3>
            <p class="text-3xl font-extrabold mt-2">{{ $statistik['ditolak'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Container Grafik (HARUS ADA AGAR GRAFIK MUNCUL) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-blue-900">
            <h3 class="font-bold text-gray-700 mb-4">Aduan Per Tahun</h3>
            <div class="relative w-full h-48"><canvas id="grafikTahun"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-yellow-600">
            <h3 class="font-bold text-gray-700 mb-4">Aduan Per Bulan</h3>
            <div class="relative w-full h-48"><canvas id="grafikBulan"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-red-700">
            <h3 class="font-bold text-gray-700 mb-4">Aduan 7 Hari Terakhir</h3>
            <div class="relative w-full h-48"><canvas id="grafikHari"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof buatChart === 'function') {
            buatChart('grafikTahun', {!! json_encode($grafikTahun->keys()) !!}, {!! json_encode($grafikTahun->values()) !!}, 'Jumlah', '#1e3a8a');
            buatChart('grafikBulan', ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'], {!! json_encode(array_values(array_pad($grafikBulan->toArray(), 12, 0))) !!}, 'Jumlah', '#d97706');
            buatChart('grafikHari', {!! json_encode($grafikHari->keys()) !!}, {!! json_encode($grafikHari->values()) !!}, 'Jumlah', '#b91c1c');
        }
    });
</script>
@endpush

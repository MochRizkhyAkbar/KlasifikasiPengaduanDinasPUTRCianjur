@extends('layouts.admin_layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-900">Dashboard Admin Bidang</h1>
        <p class="text-gray-500">Ringkasan performa dan statistik pengaduan di bidang Anda.</p>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-800 p-6 rounded-2xl shadow-lg transition-transform hover:scale-105 text-white">
            <div class="text-3xl mb-2">📥</div>
            <p class="text-sm font-medium opacity-80">Total Aduan Diterima :</p>
            <h2 class="text-4xl font-bold">{{ $statistik['diterima'] }}</h2>
        </div>
        <div class="bg-yellow-500 p-6 rounded-2xl shadow-lg transition-transform hover:scale-105 text-white">
            <div class="text-3xl mb-2">⚙️</div>
            <p class="text-sm font-medium opacity-80">Total Aduan Diproses :</p>
            <h2 class="text-4xl font-bold">{{ $statistik['diproses'] }}</h2>
        </div>
        <div class="bg-green-600 p-6 rounded-2xl shadow-lg transition-transform hover:scale-105 text-white">
            <div class="text-3xl mb-2">✅</div>
            <p class="text-sm font-medium opacity-80">Total Aduan Selesai :</p>
            <h2 class="text-4xl font-bold">{{ $statistik['selesai'] }}</h2>
        </div>
        <div class="bg-red-600 p-6 rounded-2xl shadow-lg transition-transform hover:scale-105 text-white">
            <div class="text-3xl mb-2">❌</div>
            <p class="text-sm font-medium opacity-80">Total Aduan Ditolak :</p>
            <h2 class="text-4xl font-bold">{{ $statistik['ditolak'] }}</h2>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aduan Per Tahun</h3>
            <div class="relative w-full h-[250px]"><canvas id="grafikTahun"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aduan Per Bulan</h3>
            <div class="relative w-full h-[250px]"><canvas id="grafikBulan"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aduan 7 Hari Terakhir</h3>
            <div class="relative w-full h-[250px]"><canvas id="grafikHari"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Menggunakan fungsi global yang sudah didefinisikan di app.js
        buatChart('grafikTahun', {!! json_encode(array_keys($grafikTahun)) !!}, {!! json_encode(array_values($grafikTahun)) !!}, 'Jumlah', '#1e40af');
        buatChart('grafikBulan', ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], {!! json_encode(array_values($grafikBulan)) !!}, 'Jumlah', '#d97706');
        buatChart('grafikHari', {!! json_encode(array_keys($grafikHari)) !!}, {!! json_encode(array_values($grafikHari)) !!}, 'Jumlah', '#b91c1c');
    });
</script>
@endpush

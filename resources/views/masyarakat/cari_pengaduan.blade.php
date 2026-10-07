<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Pengaduan - PUTR Cianjur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="sticky top-0 z-50 flex justify-between items-center p-6 bg-white shadow-sm border-b-2 border-blue-900">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/Logo PUTR.png') }}" alt="Logo PUTR" class="h-12 w-auto">
        </a>
        <div class="flex gap-6 font-semibold text-blue-900">
            <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
            <a href="{{ route('pengaduan.create') }}" class="hover:text-blue-700">Buat Pengaduan</a>
            <a href="{{ route('pengaduan.search') }}" class="border-b-2 border-blue-900">Cari Aduan</a>
        </div>
    </nav>

    <main class="max-w-lg mx-auto py-16 px-6">
        <!-- Main container dengan x-data untuk kontrol visibilitas hasil -->
        <div class="bg-white p-8 rounded-xl shadow-lg border-t-4 border-blue-900" x-data="{ showResult: true }">

            <div class="flex justify-center mb-6">
                <svg class="w-16 h-16 text-blue-900 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <h2 class="text-2xl font-bold mb-2 text-blue-900 text-center">Cari Status Pengaduan</h2>
            <p class="text-gray-500 text-center text-sm mb-6">Masukkan kode unik pengaduan Anda untuk melihat progres perbaikan.</p>

            <form action="{{ route('pengaduan.search') }}" method="GET" class="mb-8">
                <div class="flex gap-2">
                    <input type="text" name="kode" value="{{ request('kode') }}"
                        class="flex-1 border rounded-lg p-3 focus:ring-2 focus:ring-blue-900 focus:outline-none"
                        placeholder="Contoh: PGD-2026..." required>
                    <button type="submit" class="bg-blue-900 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition font-bold">
                        Cari
                    </button>
                </div>
            </form>

            @if(session('error'))
                <div class="bg-red-50 text-red-700 p-4 rounded-lg border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($pengaduan))
                <div class="border-t mt-8 pt-6 space-y-6" x-show="showResult">
                    <!-- Header Hasil -->
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-blue-900">Hasil Pencarian</h3>
                        <button @click="showResult = false" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Grid Informasi yang Rapi -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <p class="text-[10px] text-blue-800 font-bold uppercase tracking-wider mb-1">Kode Pengaduan</p>
                            <p class="font-mono font-bold text-blue-950 text-lg">{{ $pengaduan->kode_pengaduan }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Status Laporan</p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                {{ $pengaduan->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($pengaduan->status == 'Diterima' ? 'bg-green-100 text-green-800' :
                                   ($pengaduan->status == 'Diproses' ? 'bg-blue-100 text-blue-800' :
                                   ($pengaduan->status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                                {{ $pengaduan->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Kotak Isi Laporan Pengaduan -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Isi Laporan Pengaduan</p>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $pengaduan->isi_pengaduan }}</p>
                    </div>

                    <!-- Informasi Penolakan (Jika Ada) -->
                    @if($pengaduan->status == 'Ditolak' && !empty($pengaduan->alasan_penolakan))
                        <div class="p-5 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center gap-2 text-red-800 font-bold mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span>Informasi Penolakan</span>
                            </div>
                            <p class="text-red-700 text-sm leading-relaxed">{{ $pengaduan->alasan_penolakan }}</p>
                        </div>
                    @endif

                    <!-- Catatan / Tindak Lanjut dari Admin Bidang (Hanya muncul jika status Ditolak) -->
                    @if($pengaduan->status == 'Ditolak' && !empty($pengaduan->catatan_bidang))
                        <div class="p-5 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center gap-2 text-blue-900 font-bold mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Catatan / Tindak Lanjut Bidang</span>
                            </div>
                            <p class="text-blue-800 text-sm leading-relaxed">{{ $pengaduan->catatan_bidang }}</p>
                        </div>
                    @endif

                </div>
            @endif
        </div>
    </main>

</body>
</html>

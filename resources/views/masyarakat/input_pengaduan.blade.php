<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengaduan - PUTR Cianjur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Library untuk Simpan Gambar -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> --}}
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar Sticky -->
    <nav class="sticky top-0 z-50 flex justify-between items-center p-6 bg-white shadow-sm border-b-2 border-blue-900">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/Logo PUTR.png') }}" alt="Logo PUTR" class="h-12 w-auto">
        </a>
        <div class="flex gap-6 font-semibold text-blue-900">
            <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
            <a href="{{ route('pengaduan.create') }}" class="border-b-2 border-blue-900">Buat Pengaduan</a>
            <a href="{{ route('pengaduan.search') }}" class="hover:text-blue-700">Cari Aduan</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-10 px-6">


            <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

            @if (session('error'))
            <script>
                console.log('test');
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi ke server AI terputus',
                            text: 'error',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            color: '#166534'
                        });
            </script>
             @endif


        <!-- Kalimat Ajakan -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-blue-900 mb-2">Laporkan Kerusakan Infrastruktur dan Tata Ruang</h2>
            <p class="text-gray-600">Mari bantu membangun Cianjur yang lebih baik. Silakan masukkan detail pengaduan Anda di bawah ini dengan baik dan benar.</p>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-lg border-t-4 border-blue-900">
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Baris 1: Nama Lengkap & No WhatsApp (2 Kolom) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_pelapor" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border" placeholder="Masukkan nama lengkap anda" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
                            <input type="text" name="no_wa" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border" placeholder="Masukkan nomor WhatsApp anda" required>
                        </div>
                    </div>

                    <!-- Baris 2: Email (DI LUAR GRID - otomatis melebar penuh ke kanan) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border" placeholder="Masukkan alamat email aktif anda" required>
                    </div>
                    {{-- Isi Laporan --}}
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-2">Isi Laporan Pengaduan <span class="text-red-500"></span></label>
                        <textarea name="isi_pengaduan" rows="4" placeholder="Jelaskan detail kerusakan infrastruktur di sini..." class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    {{-- Foto Bukti --}}
                    <div>
                        <label class="block font-semibold mb-2">Foto Bukti Fisik</label>
                        <input type="file" name="foto_bukti" class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>
                    {{-- Lokasi --}}
                    <div>
                        <label class="block font-semibold mb-2">Lokasi <span class="text-red-500"></span></label>
                        <input type="text" name="lokasi" placeholder="Masukkan lokasi detail kerusakan" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="mt-8 text-right">
                    <button type="submit" class="bg-blue-900 text-white px-8 py-3 rounded-lg hover:bg-blue-800 transition font-bold">
                        Kirim Pengaduan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Notifikasi Modal -->
    @if(session('success'))
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div id="notifikasi-pengaduan" class="bg-white p-8 rounded-xl shadow-2xl max-w-sm w-full text-center border-t-8 border-blue-900">
                <div class="text-green-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Berhasil!</h3>
                <p class="text-gray-600 mb-6">Kode Laporan Anda:</p>
                <div class="bg-gray-100 p-3 rounded-lg font-mono font-bold text-2xl text-blue-900 mb-6">
                    {!! session('success') !!}
                </div>
                <div class="flex gap-2 justify-center">
                    <button onclick="downloadNotifikasi()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">Simpan Gambar</button>
                    <button onclick="window.location.href='{{ route('pengaduan.create') }}'" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black transition text-sm">Tutup</button>
                </div>
            </div>
        </div>

        <script>
            function downloadNotifikasi() {
                const element = document.getElementById('notifikasi-pengaduan');
                html2canvas(element).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'Kode-Pengaduan-PUTR.png';
                    link.href = canvas.toDataURL();
                    link.click();
                });
            }
        </script>
    @endif
</body>
</html>

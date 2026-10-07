<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan PUTR Cianjur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar Sticky -->
    <nav class="sticky top-0 z-50 flex justify-between items-center p-6 bg-white shadow-sm border-b-2 border-blue-900">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/Logo PUTR.png') }}" alt="Logo PUTR" class="h-12 w-auto">
        </a>
        <div class="flex gap-6 font-semibold text-blue-900">
            <a href="{{ route('home') }}" class="border-b-2 border-blue-900">Home</a>
            <a href="{{ route('pengaduan.create') }}" class="hover:text-blue-700">Buat Pengaduan</a>
            <a href="{{ route('pengaduan.search') }}" class="hover:text-blue-700">Cari Aduan</a>
        </div>
    </nav>

    <!-- Hero Section dengan Foto Latar -->
    <header class="relative py-24 px-6 bg-cover bg-center" style="background-image: linear-gradient(rgba(17, 24, 39, 0.7), rgba(17, 24, 39, 0.7)), url('{{ asset('images/PUPR.jpg') }}');">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-6">Sistem Informasi Pengaduan Dinas Pekerjaan Umum dan Tata Ruang Kabupaten Cianjur</h1>
            <p class="text-lg mb-10 max-w-2xl mx-auto opacity-90">Sampaikan Keluhan Terkait Layanan Dinas PUTR Anda Secara Langsung</p>
            <div>
                {{-- <a href="{{ route('pengaduan.create') }}" class="inline-block bg-blue-600 text-white px-10 py-4 rounded-lg hover:bg-blue-700 font-bold shadow-lg transition-transform hover:scale-105">
                    Buat Pengaduan Sekarang
                </a> --}}
            </div>
        </div>
    </header>

    <!-- Garis Pemisah yang Elegan -->
    <div class="max-w-4xl mx-auto px-6 py-6">
        <div class="h-px bg-gradient-to-r from-transparent via-blue-900 to-transparent w-full"></div>
    </div>

    <!-- Info Section: Tutorial & Bidang -->
    <section class="max-w-6xl mx-auto py-10 px-6 grid md:grid-cols-2 gap-12">
        <!-- Tutorial -->
        <div>
            <h2 class="text-2xl font-bold text-blue-900 mb-6">Cara Melakukan Pengaduan</h2>
            <ol class="space-y-4 text-gray-700 list-decimal list-inside">
                <li>Klik tombol <strong>"Buat Pengaduan"</strong> </li>
                <li>Isi formulir pengaduan dengan data diri dan detail kerusakan</li>
                <li>Unggah foto bukti fisik kerusakan infrastruktur</li>
                <li>Klik kirim dan simpan <strong>Kode Pengaduan</strong> yang muncul</li>
                <li>Gunakan kode tersebut di menu <strong>"Cari Aduan"</strong> untuk melacak status</li>
            </ol>
        </div>

        <!-- Bidang -->
        <div>
            <h2 class="text-2xl font-bold text-blue-900 mb-6">Bidang di Dinas PUTR</h2>
            <ul class="grid grid-cols-2 gap-3 text-sm">
                <li class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-blue-900 font-semibold">Bidang Sumber Daya Air</li>
                <li class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-blue-900 font-semibold">Preservasi Jalan</li>
                <li class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-blue-900 font-semibold">Pembangunan Jalan</li>
                <li class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-blue-900 font-semibold">Tata Ruang</li>
                <li class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-blue-900 font-semibold">Bina Kontruksi & Teknik</li>
                <li class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-blue-900 font-semibold">Sekretariat</li>
            </ul>
        </div>
    </section>

    <!-- Footer & Alamat -->
    <!-- Bagian Footer 3 Kolom -->
<footer class="bg-blue-900 text-white pt-12 pb-8 px-6 border-t border-blue-800 relative">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

        <!-- KOLOM KIRI: Logo & Pengertian Dinas -->
        <div class="space-y-4">
            <!-- Logo Dinas -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo PUTR.png') }}" alt="Logo Dinas PUTR" class="h-12 w-auto object-contain">
                <div>
                    <h4 class="font-bold text-sm tracking-wide leading-tight">DINAS</h4>
                    <h4 class="font-bold text-sm tracking-wide leading-tight">PEKERJAAN UMUM</h4>
                    <h4 class="font-bold text-sm tracking-wide leading-tight">DAN TATA RUANG</h4>
                    <p class="text-[10px] text-blue-300 tracking-wider">KABUPATEN CIANJUR</p>
                </div>
            </div>
            <!-- Deskripsi / Pengertian -->
            <p class="text-xs text-blue-200 leading-relaxed text-justify">
                Dinas Pekerjaan Umum dan Tata Ruang Kabupaten Cianjur mempunyai tugas membantu Bupati melaksanakan urusan pemerintahan yang menjadi kewenangan Daerah dan tugas pembantuan di bidang pekerjaan umum dan penataan ruang yang diberikan kepada Daerah Kabupaten.
            </p>
        </div>

        <!-- KOLOM TENGAH: Sosial Media & Web Resmi -->
        <div class="space-y-4 md:text-center">
            <h3 class="font-bold text-sm tracking-wider uppercase border-b border-blue-800 pb-2 inline-block md:block">
                Sosial Media & Tautan
            </h3>
            <p class="text-xs text-blue-200">
                Ikuti akun resmi kami dan kunjungi portal web untuk informasi seputar infrastruktur Kabupaten Cianjur.
            </p>

            <!-- Tombol Sosial Media & Web -->
            <div class="pt-2 flex flex-col gap-2.5 md:items-center">
                <!-- Tombol Instagram -->
                <a href="https://www.instagram.com/putr.cianjur/" target="_blank" class="inline-flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-700 text-white px-4 py-2 rounded-lg border border-blue-700 transition shadow-sm text-xs font-semibold w-full md:w-auto">
                    <svg class="w-4 h-4 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span>@putr.cianjur</span>
                </a>

                <!-- Tombol Web Dinas PUTR -->
                <a href="https://putr.cianjurkab.go.id/" target="_blank" class="inline-flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-700 text-white px-4 py-2 rounded-lg border border-blue-700 transition shadow-sm text-xs font-semibold w-full md:w-auto">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <span>Web Resmi Dinas PUTR</span>
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN: Label Oranye (Tidak Bisa Diklik) & Google Maps -->
        <div class="space-y-3">
            <div>
                <div class="w-full text-center bg-orange-500 text-white font-bold py-2.5 px-4 rounded-lg shadow text-xs tracking-wider cursor-default select-none">
                    📍 LOKASI DINAS PUTR
                </div>
            </div>

            <!-- Kotak Peta Google Maps Embed -->
            <div class="w-full h-44 rounded-lg overflow-hidden shadow-md border border-blue-800">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.543624453393!2d107.1393611!3d-6.825223099999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e685257736f1649%3A0xfb07ba023ea8193d!2sDINAS%20PUTR%20Cianjur!5e0!3m2!1sid!2sid!4v1790834522995!5m2!1sid!2sid"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin">
                </iframe>
            </div>
            <p class="text-[11px] text-blue-200 text-center">
                Jl. Adi Sucipta, Pamoyanan, Kec. Cianjur, Jawa Barat 43212
            </p>
        </div>

    </div>

    <!-- Copyright Bawah -->
    <div class="max-w-7xl mx-auto mt-10 pt-6 border-t border-blue-800 text-center text-xs text-blue-300">
        © 2026 Dinas Pekerjaan Umum dan Tata Ruang Kabupaten Cianjur.
    </div>

    <!-- TOMBOL SCROLL TO TOP (Mengambang di pojok kanan bawah) -->
    <button onclick="scrollToTop()" id="scrollTopBtn" class="fixed bottom-6 right-6 z-50 bg-gray-600 hover:bg-gray-700 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition duration-300 opacity-90 hover:opacity-100 focus:outline-none" title="Kembali ke atas">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
</footer>

<!-- Skrip JavaScript untuk fungsi Scroll ke Atas -->
<script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>

</body>
</html>

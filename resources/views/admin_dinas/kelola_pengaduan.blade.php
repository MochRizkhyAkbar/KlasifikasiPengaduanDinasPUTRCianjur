@extends('layouts.admin_layout')

@section('content')
<div class="w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">Kelola Pengaduan</h1>
            <p class="text-gray-500 mt-2">Daftar pengaduan yang telah terklasifikasi oleh sistem untuk diverifikasi.</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex justify-between items-center gap-3">
            <div class="text-sm text-gray-600 font-medium">
                Menampilkan
                <span class="mx-1 px-3 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full font-bold shadow-sm">
                    {{ $pengaduans->count() }}
                </span>
                data
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin_dinas.kelola') }}" method="GET">
                    <select name="bidang" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                        <option value="">Semua Bidang</option>
                        <option value="bidangBINKON" {{ request('bidang') == 'bidangBINKON' ? 'selected' : '' }}>BINKON</option>
                        <option value="bidangSDA" {{ request('bidang') == 'bidangSDA' ? 'selected' : '' }}>SDA</option>
                        <option value="bidangJALAN" {{ request('bidang') == 'bidangJALAN' ? 'selected' : '' }}>Jalan</option>
                        <option value="bidangTATARUANG" {{ request('bidang') == 'bidangTATARUANG' ? 'selected' : '' }}>Tata Ruang</option>
                    </select>
                </form>

                <!-- Tombol Dropdown Export Data (PDF & Excel) -->
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm inline-flex items-center gap-2 transition">
                        <span>Export Data</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50">
                        <div class="py-1">
                            <a href="{{ route('admin_dinas.export.pdf', ['bidang' => request('bidang')]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold">
                                📄 Export ke PDF
                            </a>
                            <a href="{{ route('admin_dinas.export.excel', ['bidang' => request('bidang')]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold text-green-700">
                                📊 Export ke Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
            <table id="tabelPengaduan" class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="px-6 py-4">NO</th>
                        <th class="px-6 py-4">KODE</th>
                        <th class="px-6 py-4">WAKTU</th>
                        <th class="px-6 py-4">NAMA</th>
                        <th class="px-6 py-4">NO WA</th>
                        <th class="px-6 py-4">EMAIL</th>
                        <th class="px-6 py-4">ADUAN</th>
                        <th class="px-6 py-4">KATEGORI</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                        <th class="px-6 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pengaduans as $index =>$item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 font-mono text-blue-800">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-mono text-blue-800">{{ $item->kode_pengaduan }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->created_at->format('d/m/y') }}</td>
                        <td class="px-6 py-4 font-medium">{{ $item->nama_pelapor }}</td>
                        <td class="px-6 py-4">{{ $item->no_wa }}</td>     <!-- <-- Tampilkan Data No WA -->
                        <td class="px-6 py-4">{{ $item->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ Str::limit($item->isi_pengaduan, 20) }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-blue-800">{{ $item->kategori_ai ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-center">
                            @if($item->status == 'Pending')
                                <span class="text-yellow-600">Menunggu Verifikasi</span>
                            @elseif($item->status == 'Diterima')
                                <span class="text-blue-600">Diteruskan ke {{ str_replace('bidang', '', $item->kategori_ai) }}</span>
                            @elseif($item->status == 'Didisposisikan')
                                <span class="text-orange-600">Dialihkan ke {{ str_replace('bidang', '', $item->kategori_ai) }}</span>
                            @elseif($item->status == 'Dikembalikan')
                                <span class="text-red-600 cursor-help" title="{{ $item->catatan_bidang }}">Dikembalikan (Perlu Review)</span>
                            @elseif($item->status == 'Diproses')
                                <span class="text-purple-600">Sedang Diproses Bidang</span>
                            @elseif($item->status == 'Ditolak')
                                <span class="text-red-600">Ditolak</span>
                            @else
                                <span class="text-green-600">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" onclick="openVerificationModal({{ $item->id }}, {{ json_encode($item->kategori_ai) }})" class="bg-blue-500 text-white text-xs px-3 py-1 rounded font-bold hover:bg-blue-700">Edit</button>
                            <button type="button" data-pengaduan="{{ json_encode($item) }}" onclick="showDetail(this)" class="bg-gray-500 text-white text-xs px-3 py-1 rounded font-bold hover:bg-orange-700 ml-1 transition duration-200">Detail</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modals --}}
<div id="verifModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center p-4 z-50">
    <div class="bg-white p-6 rounded-2xl w-full max-w-sm">
        <h2 class="text-lg font-bold mb-4">Verifikasi Pengaduan</h2>
        <form id="verifForm" action="" method="POST">
            @csrf @method('PUT')

            <label class="block text-xs font-bold text-gray-700 mb-1">Status Pengaduan</label>
            <select name="status" id="statusSelect" class="w-full border rounded-lg p-2 mb-3 text-sm" onchange="toggleAlasan(this.value)">
                <option value="Diterima" id="optionDiterima">Diterima</option>
                <option value="Ditolak">Ditolak</option>
            </select>

            <div class="mb-3" id="kategoriDiv">
                <label class="block text-xs font-bold text-gray-700 mb-1">Alihkan Kategori Bidang</label>
                <select name="kategori_baru" id="kategoriBaruSelect" class="w-full border rounded-lg p-2 text-sm">
                    <option value="Tetap">-- Tetap --</option>
                    <option value="bidangSDA">SDA</option>
                    <option value="bidangJALAN">Jalan</option>
                    <option value="bidangTATARUANG">Tata Ruang</option>
                    <option value="bidangBINKON">Bina Konstruksi (BINKON)</option>
                    <option value="BUKAN PUTR">Bukan PUTR</option>
                </select>
            </div>

            <div id="alasanDiv" class="hidden mb-3">
                <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" class="w-full border rounded-lg p-2 text-sm" rows="3" placeholder="Alasan penolakan..."></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('verifModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg text-sm font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Perlebar modal dengan max-w-2xl atau max-w-3xl -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <!-- Ubah menjadi max-w-xl agar ukurannya pas (tidak terlalu kecil, tidak terlalu lebar) -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl p-6 relative max-h-[90vh] overflow-y-auto">

        <!-- Judul Modal -->
        <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Lengkap Pengaduan</h3>

        <!-- Konten Detail -->
        <div id="modalContent" class="text-sm sm:text-base text-gray-800 space-y-4">
            <!-- Isi detail -->
        </div>

        <!-- Tombol Tutup -->
        <div class="mt-6 text-right">
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-2 rounded-lg transition text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script>
    // Inisialisasi DataTable dihapus dari sini karena layout utama sudah menanganinya,
    // sehingga error reinitialise tidak akan terjadi lagi.

    function openVerificationModal(id, kategori) {
        const url = "{{ route('admin_dinas.update', ':id') }}".replace(':id', id);
        document.getElementById('verifForm').action = url;

        const statusSelect = document.getElementById('statusSelect');
        const optionDiterima = document.getElementById('optionDiterima');
        const kategoriBaruSelect = document.getElementById('kategoriBaruSelect');

        kategoriBaruSelect.value = "Tetap";

        if (kategori && kategori.toUpperCase() === 'BUKAN PUTR') {
            optionDiterima.style.display = 'none';
            statusSelect.value = 'Ditolak';
            document.getElementById('alasanDiv').classList.remove('hidden');

            const textareaAlasan = document.querySelector('textarea[name="alasan_penolakan"]');
            if (textareaAlasan && !textareaAlasan.value) {
                textareaAlasan.value = 'Pengaduan ditolak otomatis oleh sistem karena dikategorikan sebagai Bukan PUTR (di luar kewenangan dinas).';
            }
        } else {
            optionDiterima.style.display = 'block';
            statusSelect.value = 'Diterima';
            document.getElementById('alasanDiv').classList.add('hidden');
        }

        document.getElementById('verifModal').classList.remove('hidden');
    }

    function toggleAlasan(val) {
        document.getElementById('alasanDiv').classList.toggle('hidden', val !== 'Ditolak');
        document.getElementById('kategoriDiv').style.display = (val === 'Ditolak') ? 'none' : 'block';
    }

    function showDetail(button) {
    const item = JSON.parse(button.getAttribute('data-pengaduan'));
    document.getElementById('detailModal').classList.remove('hidden');

    // Format tanggal agar rapi
    const tanggalFormatted = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
        day: '2-digit', month: 'long', year: 'numeric'
    }) : '-';

    document.getElementById('modalContent').innerHTML = `
        <div class="space-y-4 text-sm text-gray-700">
            <!-- Header Info Grid -->
            <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                <div>
                    <span class="block text-xs text-gray-500 font-medium">Kode Pengaduan</span>
                    <span class="font-semibold text-gray-900">${item.kode_pengaduan}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-medium">Tanggal</span>
                    <span class="font-semibold text-gray-900">${tanggalFormatted}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-medium">Nama Pelapor</span>
                    <span class="font-semibold text-gray-900">${item.nama_pelapor}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-medium">No WhatsApp</span>
                    <span class="font-semibold text-gray-900">${item.no_wa}</span>
                </div>
                <div class="col-span-2">
                    <span class="block text-xs text-gray-500 font-medium">Email</span>
                    <span class="font-semibold text-gray-900">${item.email ?? '-'}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-medium">Kategori Sistem</span>
                    <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 rounded mt-0.5">${item.kategori_ai ?? '-'}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-medium">Status</span>
                    <span class="font-semibold text-gray-900">${item.status}</span>
                </div>
            </div>

            <!-- Lokasi -->
            <div>
                <span class="block text-xs text-gray-500 font-medium">Lokasi Kejadian</span>
                <p class="font-medium text-gray-900">${item.lokasi}</p>
            </div>

            <!-- Isi Aduan -->
            <div>
                <span class="block text-xs text-gray-500 font-medium">Isi Pengaduan</span>
                <p class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-gray-800 mt-1 leading-relaxed">${item.isi_pengaduan}</p>
            </div>

            <!-- Catatan Bidang (jika ada) -->
            ${item.catatan_bidang ? `
                <div>
                    <span class="block text-xs text-gray-500 font-medium">Catatan Bidang</span>
                    <p class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-blue-900 mt-1">${item.catatan_bidang}</p>
                </div>
            ` : ''}

            <!-- Alasan Penolakan (jika ada) -->
            ${item.alasan_penolakan ? `
                <div>
                    <span class="block text-xs text-red-500 font-medium">Alasan Penolakan</span>
                    <p class="bg-red-50 p-3 rounded-lg border border-red-100 text-red-900 mt-1">${item.alasan_penolakan}</p>
                </div>
            ` : ''}

            <!-- Foto Bukti & Tombol Download -->
            <div>
                <span class="block text-xs text-gray-500 font-medium mb-1">Foto Bukti Fisik</span>
                ${item.foto_bukti ? `
                    <div class="space-y-2">
                        <img src="/storage/${item.foto_bukti}" class="w-full h-48 object-cover rounded-lg border shadow-sm">
                        <a href="/storage/${item.foto_bukti}" download="Bukti_${item.kode_pengaduan}.jpg" target="_blank" class="inline-flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow transition">
                            📥 Download Foto Bukti
                        </a>
                    </div>
                ` : `
                    <p class="text-sm text-gray-500 italic bg-gray-50 p-3 rounded-lg border">Tidak ada foto bukti yang dilampirkan.</p>
                `}
            </div>
        </div>
    `;
}
</script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            color: '#166534'
        });
    </script>
@endif

@if (session('error') || $errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') ?? $errors->first() }}',
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            color: '#991b1b'
        });
    </script>
@endif
@endpush

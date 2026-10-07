@extends('layouts.admin_layout')

@section('content')
<div class="w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">Formulir Menindaklanjuti Pengaduan</h1>
            <p class="text-gray-500 mt-2">
                Menangani Pengaduan Bidang: <span class="font-bold text-blue-800">{{ str_replace('bidang', '', Auth::user()->getRoleNames()->first()) }}</span>
            </p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex justify-between items-center gap-3">
            <div class="text-sm text-gray-600 font-medium">
                Menampilkan
                <span class="mx-1 px-3 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full font-bold shadow-sm">
                    {{ $pengaduans->count() }}
                </span>
                data
            </div>
        </div>

        <!-- Pastikan tidak ada overflow-hidden di wrapper tabel agar DataTables tidak terblokir -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <table id="tabelTindakLanjut" class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="px-6 py-4">NO</th>
                        <th class="px-6 py-4">KODE</th>
                        <th class="px-6 py-4">TANGGAL</th>
                        <th class="px-6 py-4">NAMA</th>
                        <th class="px-6 py-4">ADUAN</th>
                        <th class="px-6 py-4">LOKASI</th>
                        <th class="px-6 py-4">KATEGORI</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                        <th class="px-6 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pengaduans as $index =>$item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 font-mono text-blue-800">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-mono font-bold text-blue-700">{{ $item->kode_pengaduan }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 font-medium">{{ $item->user ? $item->user->name : ($item->nama_pelapor ?? 'Anonim') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ Str::limit($item->isi_pengaduan, 20) }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->lokasi }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-blue-800">
                            {{ str_replace('bidang', '', $item->kategori_ai) }}
                        </td>
                        <td class="px-6 py-4 font-bold text-center text-blue-700">{{ $item->status }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-1">
                                <button type="button" onclick="openModal({{ json_encode($item) }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold transition">Edit</button>
                                <button type="button" onclick="openDetailModal({{ json_encode($item) }})" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs font-bold transition ml-1">Detail</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Edit/Proses --}}
<div id="actionModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center p-4 z-50">
    <div class="bg-white p-6 rounded-2xl w-full max-w-sm shadow-xl">
        <h2 id="modalTitle" class="text-lg font-bold mb-4">Proses Pengaduan</h2>
        <form id="actionForm" method="POST">
            @csrf
            @method('PUT')

            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
            <select name="status" id="statusSelect" class="w-full border rounded-lg p-2 mb-3 text-sm" onchange="toggleCatatanRequired()">
                <option value="Diterima">Terima</option>
                <option value="Diproses">Proses</option>
                <option value="Tolak">Tolak</option>
                <option value="Selesai">Selesai</option>
            </select>

            <label class="block text-xs font-bold text-gray-700 mb-1">
                Catatan <span id="labelWajibCatatan" class="text-red-500 hidden">* Wajib diisi jika ditolak</span>
            </label>
            <textarea name="catatan" id="catatanText" class="w-full border rounded-lg p-2 mb-4 text-sm" rows="3" placeholder="Masukkan catatan..."></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('actionModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg text-sm font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detail --}}
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <div class="bg-white p-8 rounded-2xl w-full max-w-lg shadow-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-6 text-blue-900">Detail Lengkap</h2>
        <div class="space-y-3 text-gray-700">
            <p><strong>Kode:</strong> <span id="detKode" class="font-mono font-bold text-blue-700"></span></p>
            <p><strong>Nama:</strong> <span id="detNama"></span></p>
            <p><strong>Lokasi:</strong> <span id="detLokasi"></span></p>
            <p><strong>Isi Aduan:</strong> <span id="detIsi"></span></p>
            <p><strong>Catatan Bidang:</strong> <span id="detCatatan" class="text-red-600 font-medium"></span></p>
            <div class="mt-4">
                <img id="detFoto" src="" alt="Bukti Foto" class="w-full h-48 object-cover rounded-xl mt-2 border hidden">
                <p id="noFoto" class="text-sm text-gray-400 italic hidden">Tidak ada lampiran foto</p>
            </div>
        </div>
        <button type="button" onclick="document.getElementById('detailModal').classList.add('hidden')" class="mt-6 w-full bg-blue-900 text-white py-3 rounded-xl font-bold">Tutup</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script>
    function toggleCatatanRequired() {
        const statusSelect = document.getElementById('statusSelect').value;
        const labelWajib = document.getElementById('labelWajibCatatan');
        const catatanText = document.getElementById('catatanText');

        if (statusSelect === 'Tolak' || statusSelect === 'Dikembalikan') {
            labelWajib.classList.remove('hidden');
            catatanText.setAttribute('required', 'required');
        } else {
            labelWajib.classList.add('hidden');
            catatanText.removeAttribute('required');
        }
    }

    window.openModal = function(item) {
        document.getElementById('actionForm').action = `/admin-bidang/update/${item.id}`;
        let statusVal = item.status;
        if (item.status === 'Ditolak') {
            statusVal = 'Tolak';
        }
        document.getElementById('statusSelect').value = statusVal;
        document.getElementById('catatanText').value = item.catatan_bidang || '';

        toggleCatatanRequired();
        document.getElementById('actionModal').classList.remove('hidden');
    };

    window.openDetailModal = function(item) {
        const nama = item.user ? item.user.name : (item.nama_pelapor || 'Anonim');
        document.getElementById('detKode').innerText = item.kode_pengaduan || 'N/A';
        document.getElementById('detNama').innerText = nama;
        document.getElementById('detLokasi').innerText = item.lokasi;
        document.getElementById('detIsi').innerText = item.isi_pengaduan;
        document.getElementById('detCatatan').innerText = item.catatan_bidang || '-';

        const imgElem = document.getElementById('detFoto');
        const noFotoElem = document.getElementById('noFoto');

        if(item.foto_bukti) {
            imgElem.src = '/storage/' + item.foto_bukti;
            imgElem.classList.remove('hidden');
            noFotoElem.classList.add('hidden');
        } else {
            imgElem.classList.add('hidden');
            noFotoElem.classList.remove('hidden');
        }

        document.getElementById('detailModal').classList.remove('hidden');
    };
</script>

<!-- Script Inisialisasi Paksa DataTables -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof jQuery !== 'undefined') {
            $(document).ready(function () {
                if ($('#tabelTindakLanjut').length && !$.fn.DataTable.isDataTable('#tabelTindakLanjut')) {
                    $('#tabelTindakLanjut').DataTable({
                        responsive: true,
                        autoWidth: false,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: {
                                previous: "‹",
                                next: "›"
                            }
                        }
                    });
                }
            });
        }
    });
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

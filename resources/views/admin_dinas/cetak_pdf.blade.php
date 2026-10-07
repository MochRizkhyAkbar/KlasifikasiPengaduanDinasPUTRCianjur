<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; /* Diubah dari 12px ke 10px agar lebih kecil */ }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; } /* Tambahkan table-layout: fixed */
        th, td { border: 1px solid #000; padding: 5px; text-align: left; word-wrap: break-word; }
        th { background-color: #f2f2f2; }

        /* Tambahkan atur lebar kolom agar proporsional */
        th:nth-child(1), td:nth-child(1) { width: 4%; }   /* No */
        th:nth-child(2), td:nth-child(2) { width: 11%; }  /* Kode */
        th:nth-child(5), td:nth-child(5) { width: 12%; }  /* No WA */
        th:nth-child(6), td:nth-child(6) { width: 15%; }  /* Email */
    </style>
</head>
<body>
    <h2>Laporan Pengaduan PUTR</h2>
    <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Waktu</th>
                <th>Nama</th>
                <th>No WA</th>
                <th>Email</th>
                <th>Aduan</th>
                <th>Lokasi</th>
                <th>Kategori (Sistem)</th>
                {{-- <th>Confidence (%)</th> --}}
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_pengaduan }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->nama_pelapor }}</td>
                <td>{{ $item->no_wa }}</td>           <!-- Tambahan -->
                 <td>{{ $item->email }}</td>
                <td>{{ $item->isi_pengaduan }}</td>
                <td>{{ $item->lokasi }}</td>
                <td>{{ $item->kategori_ai }}</td>
                {{-- <td>{{ $item->confidence_score }}%</td> --}}
                <td>{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

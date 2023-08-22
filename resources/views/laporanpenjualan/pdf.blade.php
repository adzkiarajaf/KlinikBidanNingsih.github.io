<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Pembelian</h1>
    <p>Tanggal: {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total Item</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Nama Pasien</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ tanggal_indonesia($item->created_at) }}</td>
                    <td>{{ $item->nama_user }}</td>
                    <td>{{ $item->total_item }}</td>
                    <td>{{ format_uang($item->total_harga) }}</td>
                    <td>{{ $item->metode_pembayaran }}</td>
                    <td>{{ $item->pasien }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total Nominal Transaksi: {{ format_uang($total_penjualan) }}</p>
    <p>Jumlah Transaksi: {{ $penjualan->count() }}</p>
</body>
</html>
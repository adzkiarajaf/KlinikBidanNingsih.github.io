<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm;
            }
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: 1rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h3>
        <p>{{ strtoupper($setting->alamat) }}</p>
    </div>
    <br>
    <div>
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No: KBN0082023</p>
    <p class="text-center">===================================</p>
    
    <br>
    <table width="100%" style="border: 0;">
        @php
            $total = 0;
            $totalqty = 0;
        @endphp
        @if(session('cart'))
            @foreach(session('cart') as $produk => $item)
                @php
                    $subtotal = $item['harga_jual'] * $item['quantity'];
                    $total += $subtotal;
                    $totalqty += $item['quantity'];
                @endphp
                <tr data-id="{{ $produk }}">
                    <td colspan="3">{{ $item['nama_produk'] }}</td>
                </tr>
                <tr data-id="{{ $produk }}">
                    <td>{{ $item['quantity'] }} x {{ format_uang($item['harga_jual']) }}</td>
                    <td></td>
                    <td class="text-right">{{ format_uang($subtotal) }}</td>
                </tr>
            @endforeach
        @endif
    </table>

    @if ($penjualan)
        <p class="text-center">-----------------------------------</p>
        <table width="100%" style="border: 0;">
            <tr>
                <td>Total Harga:</td>
                <td class="text-right">Rp. {{ format_uang($penjualan->total_harga) }}</td>
            </tr>
            <tr>
                <td>Total Item:</td>
                <td class="text-right"> {{ format_uang($penjualan->total_item) }} Item</td>
            </tr>
            <tr>
                <td>Total Bayar:</td>
                <td class="text-right">Rp. {{ format_uang($penjualan->bayar) }}</td>
            </tr>
            <tr>
                <td>Kembalian:</td>
                @if ($penjualan->metode_pembayaran == 'qris')
                    <td class="text-right"> - </td>
                @else
                    <td class="text-right">Rp. {{ format_uang($penjualan->bayar - $penjualan->total_harga) }}</td>
                @endif
            </tr>
            <tr>
                <td>Metode Pembayaran:</td>
                <td class="text-right">{{ $penjualan->metode_pembayaran }}</td>
            </tr>
        </table>
        <p class="text-center">===================================</p>
    @endif

    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>

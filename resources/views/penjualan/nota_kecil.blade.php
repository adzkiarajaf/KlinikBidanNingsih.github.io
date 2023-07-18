<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <?php
    $style = '
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
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
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
    <p>No: KBN023723</p>
    <p class="text-center">===================================</p>
    
    <br>
    <table width="100%" style="border: 0;">
            <tr>
                <td colspan="3">Vitamin Etabion</td>
            </tr>
            <tr>
                <td>4 x 3000</td>
                <td></td>
                <td class="text-right">Rp. 12.000</td>
            </tr>
            <tr>
                <td colspan="3">Hufagrip</td>
            </tr>
            <tr>
                <td>2 x 35.000</td>
                <td></td>
                <td class="text-right">Rp. 70.000</td>
            </tr>
            <tr>
                <td colspan="3">Kandistain</td>
            </tr>
            <tr>
                <td>1 x 30.000</td>
                <td></td>
                <td class="text-right">Rp. 30.000</td>
            </tr>
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga:</td>
            <td class="text-right">Rp.112.000</td>
        </tr>
        <tr>
            <td>Total Item:</td>
            <td class="text-right">7 item</td>
        </tr>
        <tr>
            <td>Total Bayar:</td>
            <td class="text-right">Rp.112.000</td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td class="text-right">-----</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
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
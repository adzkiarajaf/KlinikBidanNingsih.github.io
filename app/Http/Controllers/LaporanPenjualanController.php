<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use PDF;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir != "") {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        $total_penjualan = Penjualan::whereDate('created_at', '>=', $tanggalAwal)
                                    ->whereDate('created_at', '<=', $tanggalAkhir)
                                    ->sum('total_harga');
        
        $penjualan = Penjualan::whereDate('created_at', '>=', $tanggalAwal)
                        ->whereDate('created_at', '<=', $tanggalAkhir)
                        ->orderBy('id_penjualan', 'desc')
                        ->get();


        return view('laporanpenjualan.index', compact('tanggalAwal', 'tanggalAkhir', 'total_penjualan', 'penjualan'));
    }


    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pembelian = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');

            $pendapatan = $total_penjualan - $total_pembelian;
            $total_pendapatan += $pendapatan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pembelian'] = format_uang($total_pembelian);
            $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => '',
            'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }
}
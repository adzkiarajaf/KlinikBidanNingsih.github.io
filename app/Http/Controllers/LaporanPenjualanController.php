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
                        ->orderBy('created_at', 'desc')
                        ->get();
                        

        $detailPenjualans = [];

        foreach ($penjualan as $penjualanItem) {
                        $detailPenjualan = PenjualanDetail::with('produk')->where('id_penjualan', $penjualanItem->id_penjualan)->get();
                        $detailPenjualans[$penjualanItem->id_penjualan] = $detailPenjualan;
                        }
                        
        return view('laporanpenjualan.index', compact('tanggalAwal', 'tanggalAkhir', 'total_penjualan', 'penjualan', 'detailPenjualans'));
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

    public function show($id_penjualan)
    {
        $detailPenjualans = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id_penjualan)
            ->get();
        
        return view('laporanpenjualan.detail', compact('detailPenjualans'));
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporanpenjualan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}
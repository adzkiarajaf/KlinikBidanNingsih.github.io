<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PenjualanDetail; 
use App\Models\Kategori;
use App\Models\Produk;


class PenjualanDetailController extends Controller
{
    public function index($id)
    {
        $produk = Produk::where('id_produk', $id)->first();
        $kategori = Kategori::pluck('nama_kategori', 'id_kategori');
         // Mengambil semua data kategori dan membuat array dengan format [id_kategori => nama_kategori]
        return view('penjualan_detail.index', compact('produk', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        $produk->nama_produk = $request->input('nama_produk');
        $produk->harga_jual = $request->input('harga_jual');
        $produk->path_foto = $request->input('path_foto');
        $produk->update();
        return response()->json('Data berhasil disimpan', 200);
    }
    

    public function destroy($id)
    {
        $produk = Produk::find($id)->delete();
        return response(null, 204);
    }

    public function data($id)
    {
    $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_beli']  = 'Rp. '. format_uang($item->harga_beli);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_pembelian_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('pembelian_detail.destroy', $item->id_pembelian_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_beli'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PenjualanDetail();
        $detail->id_penjualan  = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_jual;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }
}
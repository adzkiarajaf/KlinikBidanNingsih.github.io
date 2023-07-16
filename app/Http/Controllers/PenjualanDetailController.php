<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function data()
    {
        $produk = Produk::orderBy('id_produk', 'desc')->get();
        $data = [];

        foreach ($produk as $item) {
            $row = [];
            $row['nama_produk'] = $item->nama_produk;
            $row['harga_jual'] = $item->harga_jual;
            $row['stok'] = $item->stok;
            $row['path_foto'] = $item->path_foto;
            $data[] = $row;
        }

        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'kategori.nama_kategori')
            ->orderBy('kode_produk', 'asc')
            ->get();

        // ... kode lainnya yang mungkin ada ...

        return $data;
    }

}
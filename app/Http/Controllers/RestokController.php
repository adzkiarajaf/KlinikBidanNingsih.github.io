<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;

class RestokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {

        
        $produk = Produk::all(); // Mengambil semua data produk dari model Produk

        $kategori = Kategori::pluck('nama_kategori', 'id_kategori'); // Mengambil semua data kategori dan membuat array dengan format [id_kategori => nama_kategori]

        return view('restok.index', compact('produk', 'kategori'));
        
    }

    public function store(Request $request)
    {
        // Validasi dan pengaturan kode_produk

        $request->validate([
            'stok' => 'required|integer',
        ]);

        $produk = new Produk();

        $produk->stok = $request->stok;

        $produk->save();
        return redirect()->back();
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

        return $data;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
    }
    
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        $produk->stok = $request->input('stok');
        $produk->update();

        return redirect()->route('restok.index')->with('success', 'Data berhasil disimpan');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
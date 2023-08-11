<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
        {
            $kategori = Kategori::pluck('nama_kategori', 'id_kategori'); // Mengambil semua data kategori dan membuat array dengan format [id_kategori => nama_kategori]
        
            $kategoriId = request()->input('kategori');
            
            // Jika ada parameter kategori yang diberikan, ambil produk berdasarkan kategori
            if ($kategoriId) {
                $produkByKategori = Produk::where('id_kategori', $kategoriId)->get();
                $produk = $produkByKategori; // Gunakan produk berdasarkan kategori yang telah diambil
            } else {
                $produk = Produk::all(); // Tampilkan semua produk jika tidak ada kategori yang dipilih
                $produkByKategori = collect(); // Buat koleksi kosong untuk produk berdasarkan kategori
            }
        
            return view('produk.index', compact('produk', 'kategori', 'produkByKategori'));
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
     */
    public function store(Request $request)
    {

        $highestKodeProduk = Produk::max('kode_produk');
        $nextKodeProduk = 'P' . tambah_nol_didepan((int) substr($highestKodeProduk, 1) + 1, 6);

        $request->merge(['kode_produk' => $nextKodeProduk]);

        $request->validate([
            'kode_produk' => 'unique:produk',
        ]);
        
        $produk = new Produk();
        
        $produk->nama_produk = $request->nama_produk;
        $produk->id_kategori = $request->id_kategori;
        $produk->harga_jual = $request->harga_jual;
        $produk->harga_beli = $request->harga_beli;
        $produk->stok = $request->stok;
        $produk->kode_produk = $nextKodeProduk;
        
        if ($request->hasFile('path_foto')) {
            $file = $request->file('path_foto');
            $nama = 'foto-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            $produk->path_foto = "$nama";
        }
        
        $produk->save();
        return redirect()->back();
    }



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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Update atribut-atribut lainnya
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'id_kategori' => $request->id_kategori,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'stok' => $request->stok,
        ]);

        // Periksa apakah ada foto baru diunggah
        if ($request->hasFile('path_foto')) {
            $file = $request->file('path_foto');
            $nama = 'foto-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            // Hapus foto lama (jika ada) sebelum mengganti dengan foto baru
            if ($produk->path_foto && file_exists(public_path('/img/' . $produk->path_foto))) {
                unlink(public_path('/img/' . $produk->path_foto));
            }

            $produk->path_foto = $nama;
            $produk->save();
        }

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }
}
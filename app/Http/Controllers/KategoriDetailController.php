<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;

class KategoriDetailController extends Controller
{
    public function index($id){
        
        $kategori = Kategori::where('id_kategori', $id)->first();
        return view('kategoridetail.index', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id)->update($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }
    

    public function destroy($id)
    {
        $kategori = Kategori::find($id)->delete();

        return response(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierDetailController extends Controller
{
    public function index($id){
        
        $supplier = Supplier::where('id_supplier', $id)->first();
        // $produk = $kategori->produk;
        return view('supplierdetail.index', compact('supplier'));
    }

    public function showProduk($id)
    {
        $supplier = Supplier::findOrFail($id);
        // $produk = $kategori->produk;
        return view('supplierdetail.index', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id)->delete();

        return response(null, 204);
    }

    public function data()
    {
        $supplier = Supplier::orderBy('id_kategori', 'desc')->get();
        foreach ($supplier as $item) {
            $row = array();
            $row['nama_supplier'] = $item->supplier['nama_supplier'];
        }
    }

    public function show($id)
    {
        // Lakukan operasi yang diperlukan untuk menampilkan detail kategori berdasarkan $id
        return view('supplierdetail.show', compact('supplier'));
    }
    
}
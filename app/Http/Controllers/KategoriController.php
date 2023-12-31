<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all(); 
        return view('kategori.index', compact('kategori'));
    }

    public function data()
    {
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        foreach ($kategori as $item) {
            $row = array();
            $row['nama_kategori'] = $item->kategori['nama_kategori'];
        }
    }
    
    public function store(Request $request)
    {    
        $namaKategori = $request->nama_kategori;
    
        // Check for duplicate category
        $existingCategory = Kategori::where('nama_kategori', $namaKategori)->first();
        if ($existingCategory) {
            return redirect()->back()->withErrors(['nama_kategori' => 'Kategori dengan nama yang sama sudah ada.']);
        }
    
        // Create and save the new category
        $kategori = new Kategori();
        $kategori->nama_kategori = $namaKategori;
        $kategori->save();
    
        return redirect()->route('kategori.index'); 
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kategori = Kategori::find($id);

        return response()->json($kategori);
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
        $kategori = Kategori::find($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->update();


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
        $kategori = Kategori::find($id);
        $kategori->delete();

        return response(null, 204);
    }

    public function detail(Kategori $kategori)
    {
    return view('kategori.detail', compact('kategori'));
    }
}
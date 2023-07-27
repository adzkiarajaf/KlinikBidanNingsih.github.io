<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Setting;
use Illuminate\Support\Str;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        $kategori = Kategori::pluck('nama_kategori', 'id_kategori');
        return view('penjualan.index', compact('produk', 'kategori'));
    }

    public function create($id)
    {
        $penjualan = new Penjualan();
        $penjualan->id_user = $id;
        $penjualan->total_item  = 0;
        $penjualan->total_harga = 0;
        $penjualan->bayar       = 0;
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        session(['id_user' => $penjualan->id_user]);

        return redirect()->route('penjualan_detail.index');
    }

    
    public function store(Request $request)
    {
        $cartItems = session('cart');
        $total = 0;
        $totalqty = 0;

        foreach ($cartItems as $produkId => $item) {
            $subtotal = $item['harga_jual'] * $item['quantity'];
            $total += $subtotal;
            $totalqty += $item['quantity'];
            
            // Mengurangi stok produk
            $produk = Produk::findOrFail($produkId);
            $produk->stok -= $item['quantity'];
            $produk->update();
            
            // Buat entri baru untuk setiap produk dalam tabel 'penjualan'
            $penjualan = new Penjualan();
            $penjualan->uuid = (string) Str::uuid(); // Membuat UUID baru
            $penjualan->id_penjualan = $penjualan->uuid; // Menggunakan UUID sebagai nilai id_penjualan
            $penjualan->total_harga = $total;
            $penjualan->total_item = $totalqty;
            $penjualan->bayar = 0;
            $penjualan->id_user = auth()->user()->id;
            $penjualan->save();
        }

        // Lanjutkan ke halaman pembayaran atau proses sesuai kebutuhan
        return redirect()->route('penjualan.checkout', ['total' => $total]);
    }


    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function addToCart($id)
    {
        $produk = Produk::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id]))  {
            $cart[$id]['quantity']++;
        }else {
            $cart[$id] = [
                'nama_produk' => $produk->nama_produk,
                "path_foto" => $produk->path_foto,
                'harga_jual' => $produk->harga_jual,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk Berhasil di Tambahkan!');

    }

    public function Cart()
    {
        $produk = Produk::all();
        $kategori = Kategori::pluck('nama_kategori', 'id_kategori');
        return view('penjualan.cart', compact('produk', 'kategori'));
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produk berhasil dihapus');
        }
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart successfully updated!');
        }
    }

    public function checkout(Request $request)
    {
        $penjualanTerbaru = Penjualan::where('id_user', auth()->user()->id)
            ->latest()
            ->first();

        $totalHargaTerbaru = $penjualanTerbaru->total_harga;

        $total = $request->input('total'); // Definisikan variabel $total dan berikan nilai dari input form

        return view('penjualan.checkout', compact('totalHargaTerbaru', 'total'));
    }

    public function notaKecil(Request $request)
    {
        $setting = Setting::first();
        $id_penjualan = $request->session()->get('id_penjualan');
        $produk = $request->session()->get('produk');
        
        // Cek nilai $id_penjualan
        // dd($id_penjualan);
        
        $penjualan = Penjualan::find($id_penjualan);
        
        return view('penjualan.nota_kecil', compact('setting', 'id_penjualan', 'produk', 'penjualan'));
    }


}
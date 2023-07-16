<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Setting;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        $kategori = Kategori::pluck('nama_kategori', 'id_kategori');
        return view('penjualan.index', compact('produk', 'kategori'));
    }

    public function data()
    {
        $penjualan = Penjualan::orderBy('id_penjualan', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('user', function ($penjualan) {
                return $penjualan->user->name;
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
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

        foreach ($cartItems as $produk => $item) {
            $subtotal = $item['harga_jual'] * $item['quantity'];
            $total += $subtotal;

            // Cek apakah entri penjualan sudah ada dalam database
            $existingPenjualan = Penjualan::where('id_penjualan', $produk)->first();
            if ($existingPenjualan) {
                // Jika sudah ada, tambahkan jumlah item dan total harga
                $existingPenjualan->total_item += $item['quantity'];
                $existingPenjualan->total_harga += $subtotal;
                $existingPenjualan->save();
            } else {
                // Jika belum ada, buat entri baru
                $penjualan = new Penjualan();
                $penjualan->id_penjualan = $produk;
                $penjualan->total_harga = $subtotal;
                $penjualan->total_item = $item['quantity'];
                $penjualan->bayar = 0;
                $penjualan->id_user = auth()->user()->id;
                $penjualan->save();
            }
        }

        // Lanjutkan ke halaman pembayaran atau proses sesuai kebutuhan
        return redirect()->route('penjualan.checkout', ['total' => $total]);
    }



    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_beli);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
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

        return view('penjualan.checkout', compact('totalHargaTerbaru'));
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
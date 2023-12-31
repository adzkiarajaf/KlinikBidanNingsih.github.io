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
        
        $kategori = Kategori::pluck('nama_kategori', 'id_kategori');
                
                $kategoriId = request()->input('kategori');
                
                if ($kategoriId && $kategoriId !== 'all') {
                    // Jika ada parameter kategori yang diberikan dan bukan "all", ambil produk berdasarkan kategori
                    $produkByKategori = Produk::where('id_kategori', $kategoriId)->get();
                } else {
                    // Tampilkan semua produk jika tidak ada kategori yang dipilih atau kategori adalah "all"
                    $produkByKategori = Produk::all();
                }
                
                
                return view('penjualan.index', compact('kategori', 'produkByKategori'));
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
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('metode', function ($penjualan) {
                return ($penjualan->metode_pembayaran);
            })
            ->addColumn('kasir', function ($penjualan) {
                return ($penjualan->nama_user);
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
    }

    // Buat entri baru untuk penjualan
    $penjualan = new Penjualan();
    $penjualan->uuid = (string) Str::uuid();
    $penjualan->id_penjualan = $penjualan->uuid;
    $penjualan->total_harga = $total;
    $penjualan->total_item = $totalqty;
    $penjualan->bayar = 0;
    $penjualan->pasien="-";
    $penjualan-> nama_user = auth()->user()->name;

    $metodePembayaran = $request->input('metode_pembayaran');
    session(['selected_metode_pembayaran' => $metodePembayaran]);

    

    // Pastikan metode pembayaran tidak kosong sebelum menyimpan
    if ($metodePembayaran) {
        $penjualan->metode_pembayaran = $metodePembayaran;
    } else {
        // Berikan nilai default jika metode pembayaran tidak ada
        $penjualan->metode_pembayaran = 'default_value';
    }

    $penjualan->save();

    // Simpan detail penjualan
    foreach ($cartItems as $produkId => $item) {
        $detail = new PenjualanDetail();
        $detail->id_penjualan = $penjualan->uuid; // Gunakan UUID dari penjualan
        $detail->id_produk = $produkId; // Gunakan ID produk yang sesuai
        $detail->harga_jual = $item['harga_jual'];
        $detail->jumlah = $item['quantity'];
        $detail->subtotal = $item['harga_jual'] * $item['quantity'];
        $detail->save();
    }

    // Setelah melakukan semua operasi untuk menyimpan data, ambil nilai total dari objek $penjualan
    $totalPenjualan = $penjualan->total_harga;

    // Redirect ke halaman berikutnya dengan ID penjualan dan total
    return redirect()->route('penjualan.checkout', [
        'id_penjualan' => $penjualan->id_penjualan,
        'total' => $totalPenjualan
    ]);
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
        $penjualanTerbaru = Penjualan::where('nama_user', auth()->user()->name)
            ->latest()
            ->first();
    
        $totalHargaTerbaru = $penjualanTerbaru->total_harga;
    
        $total = $request->input('total');
    
        return view('penjualan.checkout', compact('penjualanTerbaru', 'totalHargaTerbaru', 'total'));
    }

    public function processCheckout(Request $request, $id_penjualan)
    {

        $total = $request->input('total');
        $penjualan = Penjualan::findOrFail($id_penjualan);

        // Update metode pembayaran
        $metode_pembayaran = $request->input('metode_pembayaran');
        $penjualan->metode_pembayaran = $metode_pembayaran;

        if ($metode_pembayaran !== 'qris') {
            // Jika metode pembayaran bukan 'qris', update nilai "bayar"
            $uang_diterima = $request->input('uangDiterima');
            $pasien = $request->input('pasien');
            $penjualan->pasien = $pasien;
            $penjualan->bayar = $uang_diterima;
        }

        $penjualan->save();

        if ($metode_pembayaran === 'qris') {
            // Jika metode pembayaran adalah 'qris', arahkan ke rute penjualan.qris
            return redirect()->route('penjualan.qr', [
                'id_penjualan' => $penjualan->id_penjualan
            ]);
        } elseif ($metode_pembayaran === 'tunai') {
            // Jika metode pembayaran adalah 'tunai', arahkan ke rute penjualan.tunai
            return redirect()->route('penjualan.tunai', [
                'id_penjualan' => $penjualan->id_penjualan
            ]);
        } else {
            // Metode pembayaran lainnya, arahkan kembali ke checkout
            return redirect()->route('penjualan.checkout', [
                'id_penjualan' => $penjualan->id_penjualan,
                'total' => $total
            ]);
        }
    }

    public function notaKecil(Request $request)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        // Mengambil data penjualan paling baru beserta detailnya menggunakan eager loading
        $penjualan = Penjualan::with(['PenjualanDetail' => function ($query) {
            $query->latest(); // Mengurutkan detail penjualan dari yang terbaru
        }])->latest()->first();

        if (!$penjualan) {
            $id_penjualan = null;
            $produk = null;
            $detail = [];
        } else {
            $id_penjualan = $penjualan->id;
            $produk = $request->session()->get('produk');
            $detail = $penjualan->penjualanDetail;
        }

        return view('penjualan.nota_kecil', compact('setting', 'id_penjualan', 'produk', 'penjualan', 'detail'));
    }


    public function kategori(Kategori $kategori)
    {
        $semuaproduk = $kategori->Produk()->get();
        return $semuaproduk;
    }

    public function tunai(Request $request)
    {
        $penjualanTerbaru = Penjualan::select('bayar', 'total_harga')
            ->orderBy('created_at', 'desc')
            ->first();

        $bayar = $penjualanTerbaru->bayar;
        $totalHarga = $penjualanTerbaru->total_harga;
        $kembalian = $bayar - $totalHarga;
                
        return view('penjualan.tunai', ['kembalian' => $kembalian]);
    }

    public function qr()
    {
        return view('penjualan.qr'); // Ganti dengan nama view yang sesuai
    }

    public function remove_cart()
    {
        // Lakukan tindakan untuk mengosongkan keranjang belanja di sini
        session()->forget('cart'); // Menghapus data keranjang belanja dari session
        session(['cart_count' => 0]);

        return response()->json(['success' => true]);
    }

    public function showDetail($id)
    {
        $detailPenjualan = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();
    
        return datatables()
            ->of($detailPenjualan)
            ->addIndexColumn()   
            ->addColumn('nama_produk', function ($detailPenjualan) {
                return $detailPenjualan->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detailPenjualan) {
                return 'Rp. ' . format_uang($detailPenjualan->harga_beli);
            })
            ->addColumn('jumlah', function ($detailPenjualan) {
                return format_uang($detailPenjualan->jumlah);
            })
            ->addColumn('subtotal', function ($detailPenjualan) {
                return 'Rp. ' . format_uang($detailPenjualan->subtotal);
            })
            ->rawColumns(['nama_produk']) 
            ->make(true);
    }

    public function resetAndRedirectToIndex()
    {
        // Reset session keranjang
        session()->put('cart', []);

        // Redirect ke penjualan.index
        return redirect()->route('penjualan.index');
    }
}
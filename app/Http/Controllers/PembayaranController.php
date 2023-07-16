<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function session()
    {
        foreach (session('cart') as $id => $details) {

            $nama_produk = $details['nama_produk'];
            echo $nama_produk;
        }
        return "success";
    }
}
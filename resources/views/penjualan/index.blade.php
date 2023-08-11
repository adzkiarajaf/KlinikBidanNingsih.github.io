@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

<style>
    .categories {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .category-btn {
        margin: 5px;
        padding: 5px 10px;
        border: 1px solid #3c8dbc; /* Default border color */
        transition: border-color 0.3s; /* Animasi perubahan border saat hover */
    }

    .category-btn:hover {
        background-color: #3c8dbc; /* Warna latar saat hover */
        color: white; /* Warna teks saat hover */
        border-color: #3c8dbc; /* Warna border saat hover */
    }
</style>

@section('content')
<div class="btn-group w-100 mb-2 categories">
    <a class="btn active" href="#" data-filter="all"> All items </a>
    @foreach ($kategori as $index => $item)
    <a class="btn kategori-btn active" href="{{ route('penjualan.index', ['kategori' => $item->id_kategori]) }}" data-filter="{{ $index }}">{{ $item->nama_kategori }}</a>
    @endforeach
</div>

@foreach ($produkByKategori as $produk)
<div class="row align-items-stretch produkContainer">
    <div class="col-mb-3">
        <div class="box box-widget widget-user-2">
            <div class="widget-user-header">
                <div class="widget-user-image">
                    <img src="{{ asset('img/' . $produk->path_foto) }}" class="rounded float-start" style="width: 12%; margin-right: 10px;">
                </div>
                <div class="widget-user-details">
                    <h3 class="widget-user-username">{{ $produk->nama_produk }}</h3>
                    <h5 class="widget-user-desc">Harga: Rp.{{ format_uang($produk->harga_jual)}}</h5>
                </div>
            </div>
            <div class="box-footer">
                <div class="d-flex justify-content-end">
                    <div class="col-5 ps-0">
                        @if ($produk->stok > 0)
                            <a href="{{ route('penjualan.add_to_cart', $produk->id_produk) }}" class="btn btn-primary" id="tambahbtn_{{ $loop->index }}" onclick="hideButton({{ $loop->index }})">Tambah</a>
                        @else
                            <button class="btn btn-primary" disabled><i class="fa fa-ban" aria-hidden="true"></i> Stok Habis</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


@if (session()->has('success'))
<a href="{{ route('penjualan.cart') }}" type="button" class="btn btn-primary btn-block btn-flat"><i class="fa fa-shopping-cart mr-3" aria-hidden="true"></i> {{ count((array) session('cart')) }} Item</a>
@endif
@endsection

@push('scripts')
<script>
    $('.kategori-btn').on('click', function(e) {
            e.preventDefault();

            // Ambil nilai data-kategori dari tautan yang diklik
            var kategoriId = $(this).data('filter'); // Ganti "data-kategori" menjadi "data-filter"

            // Lakukan permintaan AJAX untuk mengambil produk sesuai dengan kategori
            $.ajax({
                url:'{{ route('penjualan.index') }}?kategori=' + kategoriId, // Tambahkan "?kategori=" di sini
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    // Tampilkan data produk pada wadah produkContainer
                    $('#produkContainer').html(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        // Tangkap klik pada tombol "All items"
        $('.btn-info').on('click', function(e) {
            e.preventDefault();

            // Lakukan permintaan AJAX untuk mengambil semua produk
            $.ajax({
                url: '{{ route('penjualan.index') }}',
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    // Tampilkan semua produk pada wadah produkContainer
                    $('#produkContainer').html(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

    // Fungsi untuk mengubah kelas tombol kategori menjadi btn-info untuk kategori aktif
    function setActiveCategory(categoryId) {
        const buttons = document.getElementsByClassName('kategori-btn');
        for (let i = 0; i < buttons.length; i++) {
            if (buttons[i].dataset.filter === categoryId) {
                buttons[i].classList.add('btn-info');
            } else {
                buttons[i].classList.remove('btn-info');
            }
        }
    }

    // Fungsi untuk menampilkan semua produk saat tombol "All items" diklik
    function showAllItems() {
        const allItemsButton = document.querySelector('.btn[data-filter="all"]');
        const kategoriButtons = document.querySelectorAll('.kategori-btn');
        setActiveCategory('all');
        // Di sini, tambahkan logika untuk menampilkan semua produk, mungkin dengan AJAX atau sejenisnya.
        // Misalnya, Anda bisa memuat ulang halaman dengan parameter 'kategori' kosong untuk menampilkan semua produk.
        // Contoh: window.location.href = '{{ route('penjualan.index', ['kategori' => '']) }}';
    }

    // Tambahkan event listener untuk tombol "All items"
    const allItemsButton = document.querySelector('.btn[data-filter="all"]');
    allItemsButton.addEventListener('click', showAllItems);

    function hideButton(index) {
        var button = document.getElementById("tambahbtn_" + index);
        button.classList.add("hidden");
        button.removeAttribute("onclick"); // Menghapus atribut onclick agar tombol tidak bisa diklik lagi
    }

   // Ambil semua elemen tombol dalam kelas .categories
    const buttons = document.querySelectorAll('.categories a');

    // Tambahkan event listener untuk setiap tombol
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus kelas "active" dari semua tombol
            buttons.forEach(btn => btn.classList.remove('active', 'btn-info'));

            // Tambahkan kelas "active" dan "btn-info" ke tombol yang diklik
            this.classList.add('active', 'btn-info');
        });
    });
</script>
@endpush

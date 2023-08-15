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
</style>

@section('content')
<div class="btn-group w-100 mb-2 categories">
    <a class="btn kategori-btn active" href="{{ route('penjualan.index', ['kategori' => 'all']) }}" data-filter="all"> Semua Produk  </a>
    @foreach ($kategori as $index => $item)
        <a class="btn kategori-btn active" href="{{ route('penjualan.index', ['kategori' => $index]) }}" data-filter="{{ $index }}">{{ $item }}</a>
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
    // Fungsi untuk menampilkan produk berdasarkan kategori
function showProductsByCategory(KategoriId) {
    // Lakukan permintaan AJAX untuk mengambil produk sesuai dengan kategori
    $.ajax({
        url: '{{ route('penjualan.index') }}?kategori=' + KategoriId,
        type: 'GET',
        dataType: 'html',
        success: function(data) {
            // Tampilkan produk pada wadah produkContainer
            $('#produkContainer').html(data);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

// Tambahkan event listener untuk tombol "All items"
const allItemsButton = document.querySelector('.btn[data-filter="all"]');
allItemsButton.addEventListener('click', function() {
    // Panggil fungsi untuk menampilkan semua produk
    showProductsByCategory('all');
});

    // Ambil semua elemen tombol kategori dalam kelas .categories
    const kategoriButtons = document.querySelectorAll('.kategori-btn');

    kategoriButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hapus kelas "active" dan "btn-info" dari semua tombol kategori
            kategoriButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-info');
            });

            // Tambahkan kelas "active" dan "btn-info" ke tombol yang diklik
            this.classList.add('active', 'btn-info');

            // Ambil nilai data-filter dari tombol yang diklik
            const kategoriId = this.getAttribute('data-filter');
            // Lakukan permintaan AJAX untuk mengambil produk sesuai dengan kategori
            $.ajax({
                url: '{{ route('penjualan.index') }}?kategori=' + kategoriId,
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    // Tampilkan produk pada wadah produkContainer
                    $('#produkContainer').html(data);
                    
                    // Simpan status aktif tombol kategori ke dalam sessionStorage
                    sessionStorage.setItem('activeKategori', kategoriId);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        // Cek apakah ada status aktif tombol kategori yang disimpan dalam sessionStorage
        const activeKategori = sessionStorage.getItem('activeKategori');
        if (activeKategori) {
            // Temukan tombol kategori yang sesuai dengan status aktif
            const activeButton = document.querySelector(`[data-filter="${activeKategori}"]`);
            if (activeButton) {
                // Tambahkan kelas "active" dan "btn-info" ke tombol kategori 
                activeButton.classList.add('active', 'btn-info');
            }
        }
    });
</script>
@endpush

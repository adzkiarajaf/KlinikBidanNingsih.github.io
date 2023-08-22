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
    <a class="btn kategori-btn active" href="{{ route('produk.index', ['kategori' => 'all']) }}" data-filter="all"> Semua Produk  </a>
    @foreach ($kategori as $index => $item)
        <a class="btn kategori-btn active" href="{{ route('produk.index', ['kategori' => $index]) }}" data-filter="{{ $index }}">{{ $item }}</a>
    @endforeach
</div>

<br>

@if (auth()->check() && auth()->user()->level == '0')
<button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
@endif 

@foreach ($produkByKategori as $index => $item)
<div class="col-mb-3 mx-auto produkContainer">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header">
            <div class="widget-user-image">
                <img src="{{ asset('img/' . $item->path_foto) }}" class="rounded float-start" style="width: 12%; margin-right: 10px;">
            </div>
            <div class="widget-user-details">
                <h3 class="widget-user-username">{{ $item->nama_produk }}</h3>
                <h5 class="widget-user-desc">Stok: {{ $item->stok }}</h5>
            </div>
        </div>
                
        <div class="box-footer no-padding">
            @if (auth()->check() && auth()->user()->level == '0')
            <ul class="nav nav-stacked">
                <li>
                    <a href="{{ route('produkdetail.index',['id' => $item->id_produk]) }}">Detail</a>
                </li>
            </ul>
            @endif 
        </div>
    </div>
</div>
@endforeach

@includeIf('produk.form')
@endsection

@push('scripts')
<script>
function addForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah Produk');
    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=nama_produk]').focus();

    $('.tampil-foto').empty();

    $('#btnSimpan').off('click').on('click', function() {
        var namaProduk = $('#modal-form [name=nama_produk]').val(); // Ambil nilai nama produk
        // Check if any input fields are empty
        var emptyFields = $('#modal-form form').find('input, select, textarea').filter(function() {
            return !this.value.trim();
        });

        if (emptyFields.length > 0) {
            // Show SweetAlert error message if any input fields are empty
            return;
        }
        // Check for duplicate input
        var existingProduk = {!! json_encode($user->pluck('nama_produk')) !!};
        if (existingProduk.includes(namaProduk)) {
            // Jika Produk sudah ada, tampilkan pesan kesalahan menggunakan SweetAlert
            Swal.fire({
                title: 'Data Sudah Ada',
                text: 'Produk dengan nama yang sama sudah ada.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
            return; // Prevent form submission
        }
        
        // Menampilkan SweetAlert sebelum menyimpan data
        Swal.fire({
            title: 'Produk Berhasil Ditambahkan',
            text: 'Produk baru telah berhasil ditambahkan.',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000 // Durasi tampilan pesan (dalam milidetik)
        }).then(() => {
            // Arahkan pengguna ke halaman index
            window.location.href = '{{ route('produk.index') }}';
        });
    });
}

// Fungsi untuk menampilkan produk berdasarkan kategori
function showProductsByCategory(KategoriId) {
    // Lakukan permintaan AJAX untuk mengambil produk sesuai dengan kategori
    $.ajax({
        url: '{{ route('produk.index') }}?kategori=' + KategoriId,
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
                url: '{{ route('produk.index') }}?kategori=' + kategoriId,
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
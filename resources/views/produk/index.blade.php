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
        <a class="btn kategori-btn" href="{{ route('produk.index', ['kategori' => $index]) }}" data-filter="{{ $index }}">{{ $item }}</a>
    @endforeach
</div>

<br>

@if (auth()->check() && auth()->user()->level == '0')
<button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
@endif 

@foreach ($produk as $index => $item)
<div class="col-mb-3 mx-auto">
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
    let table;

    $(function () {
        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    function addForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah Produk');
    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=nama_produk]').focus();

    $('.tampil-foto').empty();

    $('#btnSimpan').on('click', function() {
        // Check if any input fields are empty
        var emptyFields = $('#modal-form form').find('input, select, textarea').filter(function() {
            return !this.value.trim();
        });

        if (emptyFields.length > 0) {
            // Show SweetAlert error message if any input fields are empty
            Swal.fire({
                title: 'Harap isi semua inputan',
                text: 'Pastikan semua inputan telah diisi sebelum menyimpan data.',
                icon: 'error',
                confirmButtonText: 'OK',
            });
            return;
        }
        
        // Menampilkan SweetAlert sebelum menyimpan data
        Swal.fire({
            title: 'Produk Berhasil Ditambahkan',
            text: 'Produk baru telah berhasil ditambahkan.',
            icon: 'success',
            showConfirmButton: false,
            timer: 5000 // Durasi tampilan pesan (dalam milidetik)
        }).then(() => {
            // Arahkan pengguna ke halaman index
            window.location.href = '{{ route('produk.index') }}';
        });
    });
}
</script>
@endpush
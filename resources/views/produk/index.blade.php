@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

@section('content')
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
    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
        
        $('.tampil-foto').empty();
        
        $('#btnSimpan').on('click', function() {
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
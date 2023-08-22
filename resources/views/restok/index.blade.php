@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Restok</li>
@endsection

@section('content')
{{-- @if (auth()->check() && auth()->user()->level == '0')
<button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
@endif  --}}

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
                <h5 class="widget-user-desc">Expired Date: {{ $item->expired  }}</h5>
            </div>
        </div>
                
        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li>
                    <button onclick="editForm('{{ route('restok.update', $item->id_produk) }}')" class="btn btn-primary btn-block">Restock</button>
                </li>
            </ul>
        </div>
    </div>
</div>
@endforeach

@includeIf('restok.form')
@endsection

@push('scripts')
<script>
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

    function editForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Restock');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama_produk]').focus();


    $.get(url)
        .done((response) => {
            $('#modal-form [name=stok]').val(response.stok);
        });
    
    // Menambahkan event listener untuk klik tombol "Simpan"
    $('#modal-form form').on('submit', function (e) {
        e.preventDefault();

        // Melakukan aksi restock ke server
        $.ajax({
            url: $('#modal-form form').attr('action'),
            method: 'POST',
            data: $('#modal-form form').serialize(),
            success: function (response) {
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Restock berhasil dilakukan.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 5000
                }).then(() => {
                    // Arahkan pengguna ke halaman yang sesuai
                    window.location.href = '{{ route('restok.index') }}';
                });
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Tidak dapat melakukan restock.',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 5000
                });
            }
        });
    });
}
</script>
@endpush
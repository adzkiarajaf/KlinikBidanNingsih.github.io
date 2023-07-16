@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">kategori</li>
@endsection

@section('content')
<div class="box-header with-border">
    <button onclick="addForm('{{  route('kategori.store') }}')" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus-circle" id="tambah"></i> Tambah </button>
</div>
<div class="list-group">
    <div class="list-group-item list-group-item-action active" aria-current="true">
        Kategori
    </div>
    <div class="list-group-item-action active">
        @foreach ($kategori as $index => $item)
        <a href="{{ route('kategoridetail.index', ['id' => $item->id_kategori]) }}" class="list-group-item">
            {{ $item->nama_kategori }}
            <i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i>
        </a>
        @endforeach
    </div>
</div>


@includeIf('kategori.form')
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
    $('#modal-form .modal-title').text('Tambah Kategori');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=nama_kategori]').focus();
    }

    // Memindahkan pengalihan ke halaman index ke luar fungsi addForm()
    $('#modal-form').on('hidden.bs.modal', function () {
        redirectToIndex();
    });

    function redirectToIndex() {
        window.location.href = '{{ route('kategori.index') }}';
    }


    function editForm(url) {
    // Mendapatkan ID dari URL menggunakan regex atau metode lainnya
    var id_kategori = url.match(/\/(\d+)$/)[1];

    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Detail Kategori');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama_kategori]').focus();

    // Menggunakan ID untuk mendapatkan data dari server
    $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_kategori]').val(response.nama_kategori);
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan data');
            return;
        });
    }
    
    function redirectToIndex() {
    window.location.href = '{{ route('kategori.index') }}';
    }
</script>
@endpush
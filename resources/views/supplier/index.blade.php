@extends('layouts.master')

@section('title')
    Daftar Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')
<div class="box-header with-border">
    <button onclick="addForm('{{  route('supplier.store') }}')" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus-circle" id="tambah"></i> Tambah </button>
</div>
<div class="list-group">
    <div class="list-group-item list-group-item-action active" aria-current="true">
        Kategori
    </div>
    <div class="list-group-item-action active">
        @foreach ($supplier as $index => $item)
        <a href="{{ route('supplierdetail.index', ['id' => $item->id_supplier]) }}" class="list-group-item">
            {{ $item->nama }}
            <i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i>
        </a>
        @endforeach
    </div>
</div>

@includeIf('supplier.form')
@endsection

@push('scripts')
<script>
    function addForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah Supplier');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=nama]').focus();

    $('#btnSimpan').on('click', function() {
        // Validasi input
        var nama = $('#modal-form [name=nama]').val();
        var alamat = $('#modal-form [name=alamat]').val();
        var telepon = $('#modal-form [name=telepon]').val();

        if (!nama || !alamat || !telepon) {
            // Menampilkan SweetAlert dengan pesan kesalahan input kosong
            Swal.fire({
                title: 'Kesalahan',
                text: 'Semua input harus terisi',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Menampilkan SweetAlert sebelum menyimpan data
        Swal.fire({
            title: 'Supplier Berhasil Ditambahkan',
            text: 'Supplier baru telah berhasil ditambahkan.',
            icon: 'success',
            showConfirmButton: false,
            timer: 4000 // Durasi tampilan pesan (dalam milidetik)
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                // Arahkan pengguna ke halaman index
                window.location.href = '{{ route('supplier.index') }}';
            }
        });
    });
}

</script>
@endpush
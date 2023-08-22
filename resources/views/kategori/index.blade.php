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
    <button onclick="addForm('{{  route('kategori.store') }}')" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus-circle"></i> Tambah Kategori </button>
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
    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_kategori]').focus();

       // ...
    $('#btnSimpan').on('click', function() {
    var namaKategori = $('#modal-form [name=nama_kategori]').val();
    
    if (!namaKategori) {
        // Jika form masih kosong, tampilkan pesan kesalahan di dalam modal
        return;
    }

    // Check for duplicate input
    var existingCategories = {!! json_encode($kategori->pluck('nama_kategori')) !!};
    if (existingCategories.includes(namaKategori)) {
        // Jika kategori sudah ada, tampilkan pesan kesalahan di dalam modal
        Swal.fire({
            title: 'Data Sudah Ada',
            text: 'Kategori dengan nama yang sama sudah ada.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        return; // Prevent form submission
    }

    // Jika form sudah diisi dan tidak ada duplicate, lanjutkan dengan menyimpan data
    // Menampilkan SweetAlert sebelum menyimpan data
    Swal.fire({
            title: 'Kategori Berhasil Ditambahkan',
            text: 'Kategori baru telah berhasil ditambahkan.',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000 // Durasi tampilan pesan (dalam milidetik)
        }).then(() => {
            // Arahkan pengguna ke halaman index
            window.location.href = '{{ route('kategori.index') }}';
        });
    });
}
</script>
@endpush


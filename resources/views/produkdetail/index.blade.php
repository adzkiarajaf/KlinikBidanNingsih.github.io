@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Produk</li>
@endsection

@section('content')
<div>
    <form>
        <fieldset disabled>
            <img src="{{ asset('img/' . $produk->path_foto) }}" class="rounded float-start mb-3" style="width: 15%">
            <br>
            <div class="mb-3">
                <br>
                <label for="disabledTextInput" class="form-label">Nama Produk</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $produk->nama_produk }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Jumlah Stok</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $produk->stok }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Kategori</label>
                <input type="text" id="disabledTextInput" class="form-control" value="@if($produk->id_kategori == 1) Jasa Pelayanan @elseif($produk->id_kategori == 2) Vitamin @elseif($produk->id_kategori == 21) Tablet @elseif($produk->id_kategori == 22) Sirup @endif" readonly>
            </div> 
            <br>           
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Harga Jual</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $produk->harga_jual }} -/pcs" readonly>
            </div>
        </fieldset>
    </form>
</div>
<br>
<div class="mb-3">
    <button onclick="editForm('{{ route('produk.update', $produk->id_produk) }}')" class="btn btn-primary btn-block">Edit</button>
    <button onclick="deleteData('{{ route('produk.destroy', $produk->id_produk) }}')" class="btn btn-primary btn-block">Hapus</button>
    <button onclick="window.location.href = '{{ route('produk.index') }}'" class="btn btn-success btn-block">Kembali Ke Produk</button>
</div>

@includeIf('produk.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('produk.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'foto'},
                {data: 'nama_produk'},
                {data: 'nama_kategori'},
                {data: 'harga_jual'},
                {data: 'stok'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        // $('#modal-form').validator().on('submit', function (e) {
        //     if (! e.preventDefault()) {
        //         console.log($('#modal-form form').serialize());
        //         $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
        //             .done((response) => {
        //                 $('#modal-form').modal('hide');
        //                 table.ajax.reload();
        //             })
        //             .fail((errors) => {
        //                 alert('Tidak dapat menyimpan data');
        //                 return;
        //             });
        //     }
        // });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
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
    }
    
    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_produk]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_produk]').val(response.nama_produk);
                $('#modal-form [name=id_kategori]').val(response.id_kategori);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
                $('#modal-form [name=harga_jual]').val(response.harga_jual);
                $('#modal-form [name=stok]').val(response.stok);
                $('#modal-form [name=path_foto]').val(response.path_foto);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    }
</script>
@endpush

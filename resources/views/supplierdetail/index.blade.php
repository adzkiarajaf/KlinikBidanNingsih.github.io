@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Supplier</li>
@endsection

@section('content')
<div>
    <form>
        <fieldset disabled>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Nama Supplier</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $supplier->nama }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Alamat</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $supplier->alamat }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">No Telpon</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $supplier->telepon }}" readonly>
            </div>
        </fieldset>
    </form>
</div>
<br>    
<div class="mb-3">
    <button onclick="editForm('{{ route('supplier.update', $supplier->id_supplier) }}')" class="btn btn-primary btn-block">Edit</button>
    <button onclick="deleteData('{{ route('supplier.destroy', $supplier->id_supplier) }}')" class="btn btn-primary btn-block">Hapus</button>
</div>

@includeIf('supplier.form')
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
    $('#modal-form .modal-title').text('Edit Supplier');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama]').focus();

    $.get(url)
        .done((response) => {
            $('#modal-form [name=nama]').val(response.nama);
            $('#modal-form [name=telepon]').val(response.telepon);
            $('#modal-form [name=alamat]').val(response.alamat);
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan data');
            return;
        });

    // Menambahkan event listener untuk klik tombol "Simpan"
    $(document).on('click', '#btnSimpan', function (event) {
        event.preventDefault();
        // Melakukan aksi penyimpanan data ke server
        $.ajax({
            url: $('#modal-form form').attr('action'),
            method: 'POST',
            data: $('#modal-form form').serialize(),
            success: function (response) {
                // Menampilkan SweetAlert dengan pesan sukses
                Swal.fire({
                    title: 'Supplier Berhasil Diubah',
                    text: 'Supplier telah berhasil diubah.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 5000
                }).then(() => {
                    // Arahkan pengguna ke halaman index
                    window.location.href = '{{ route('supplier.index') }}';
                });

                // Menutup modal
                $('#modal-form').modal('hide');

                // Lakukan operasi lain seperti reload halaman atau pembaruan tampilan
                // ...
            },
            error: function (xhr, status, error) {
                // Menampilkan SweetAlert dengan pesan gagal
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Tidak dapat menyimpan data supplier.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
}

function deleteData(url) {
        // Menampilkan SweetAlert konfirmasi sebelum menghapus data
        Swal.fire({
            title: 'Hapus Data',
            text: 'Apakah Anda yakin ingin menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan aksi penghapusan data ke server
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Menampilkan SweetAlert dengan pesan sukses
                        Swal.fire({
                            title: 'Data Terhapus',
                            text: 'Data berhasil dihapus.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000 // Durasi tampilan pesan (dalam milidetik)
                        }).then(() => {
                            // Lakukan operasi lain seperti reload halaman atau pembaruan tampilan
                            window.location.href = '{{ route('supplier.index') }}';
                        });
                    },
                    error: function(xhr, status, error) {
                        // Menampilkan SweetAlert dengan pesan gagal
                        Swal.fire({
                            title: 'Kesalahan',
                            text: 'Tidak dapat menghapus data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    }
</script>
@endpush

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
    function editForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Edit Produk');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama_produk]').focus();

    // Menggunakan ID untuk mendapatkan data produk dari server
    $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_produk]').val(response.nama_produk);
            $('#modal-form [name=id_kategori]').val(response.id_kategori);
            $('#modal-form [name=harga_beli]').val(response.harga_beli);
            $('#modal-form [name=harga_jual]').val(response.harga_jual);
            $('#modal-form [name=stok]').val(response.stok);
            $('#modal-form [name=path_foto]').val(response.path_foto);

            // Menampilkan SweetAlert dengan pesan sukses setelah mendapatkan data produk
            Swal.fire({
                title: 'Data Produk Ditampilkan',
                text: 'Data produk berhasil ditampilkan.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 // Durasi tampilan pesan (dalam milidetik)
            });
        })
        .fail((errors) => {
            // Menampilkan SweetAlert dengan pesan gagal
            Swal.fire({
                title: 'Kesalahan',
                text: 'Tidak dapat menampilkan data produk.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

    // Menambahkan event listener untuk klik tombol "Simpan"
    $('#btnSimpan').on('click', function () {
        // Validasi input
        var namaProduk = $('#modal-form [name=nama_produk]').val();
        var idKategori = $('#modal-form [name=id_kategori]').val();
        var hargaBeli = $('#modal-form [name=harga_beli]').val();
        var hargaJual = $('#modal-form [name=harga_jual]').val();
        var stok = $('#modal-form [name=stok]').val();
        var pathFoto = $('#modal-form [name=path_foto]').val();

        if (!namaProduk || !idKategori || !hargaBeli || !hargaJual || !stok || !pathFoto) {
            // Menampilkan SweetAlert dengan pesan kesalahan input kosong
            Swal.fire({
                title: 'Kesalahan',
                text: 'Semua input harus terisi',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Melakukan aksi penyimpanan data ke server
        $.ajax({
            url: $('#modal-form form').attr('action'),
            method: 'POST',
            data: $('#modal-form form').serialize(),
            success: function (response) {
                // Menampilkan SweetAlert dengan pesan sukses
                Swal.fire({
                    title: 'Produk Berhasil Diubah',
                    text: 'Produk telah berhasil diubah.',
                    icon: 'success'
                }).then(() => {
                    // Arahkan pengguna ke halaman index
                    window.location.href = '{{ route('produk.index') }}';
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
                    text: 'Tidak dapat menyimpan data kategori.',
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
                            window.location.href = '{{ route('produk.index') }}';
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

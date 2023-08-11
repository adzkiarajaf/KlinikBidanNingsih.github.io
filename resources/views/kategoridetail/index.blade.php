@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">kategori</li>
@endsection

@section('content')
<div>
    <form>
        <fieldset disabled>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Nama Kategori</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $kategori->nama_kategori }}" readonly>
            </div>
        </fieldset>
    </form>
</div>
<br>
<div class="mb-3">
    <button onclick="editForm('{{ route('kategori.update', $kategori->id_kategori) }}')" class="btn btn-primary btn-block">Edit</button>
    <button onclick="deleteData('{{ route('kategori.destroy', $kategori->id_kategori) }}')" class="btn btn-primary btn-block">Hapus</button>
</div>

@includeIf('kategori.form')
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
            // Menampilkan SweetAlert dengan pesan gagal
            Swal.fire({
                title: 'Kesalahan',
                text: 'Tidak dapat menampilkan data kategori.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

        $(document).on('click', '#btnSimpan', function (event) {
        event.preventDefault(); // Menambahkan prevent default untuk mencegah form submit

        // Validasi input
        var namaKategori = $('#modal-form [name=nama_kategori').val();
        var idKategori = $('#modal-form [name=id_kategori]').val();

        if (!namaKategori) {
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
                    title: 'Kategori Berhasil Diubah',
                    text: 'Kategori telah berhasil diubah.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000 // SweetAlert akan tampil selama 4 detik
                }).then(() => {
                    // Setelah SweetAlert tertampil selama 4 detik, arahkan pengguna ke halaman index
                    window.location.href = '{{ route('kategori.index') }}';
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
                            window.location.href = '{{ route('kategori.index') }}';
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

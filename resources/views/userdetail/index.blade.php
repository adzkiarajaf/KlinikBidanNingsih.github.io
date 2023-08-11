@extends('layouts.master')

@section('title')
    User
@endsection

@section('breadcrumb')
    @parent
    <li class="active">User</li>
@endsection

@section('content')
<div>
    <form>
        <fieldset disabled>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Nama User</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $user->name }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Email</label>
                <input type="text" id="disabledTextInput" class="form-control" value="{{ $user->email }}" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Level</label>
                <input type="text" id="disabledTextInput" class="form-control" value="@if($user->level == 1) Kasir @elseif($user->level == 0) Owner @endif" readonly>
            </div>
            <br>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Password</label>
                    <input type="text" id="disabledTextInput" class="form-control" value="********" readonly>
                
            </div>
        </fieldset>
    </form>
</div>
<br>    
<div class="mb-3">
    <button onclick="editForm('{{ route('user.update', $user->id) }}')" class="btn btn-primary btn-block">Edit</button>
    <button onclick="deleteData('{{ route('user.destroy', $user->id) }}')" class="btn btn-primary btn-block">Hapus</button>
</div>

@if ($user->level === 1)
    @includeIf('user.form')
@elseif ($user->level === 0)
    @includeIf('user.formowner')
@endif
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
        $('#modal-form .modal-title').text('Edit User');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();
        $('#password, #password_confirmation').attr('required', false);

        // Mengambil data user melalui AJAX
        $.get(url)
        .done((response) => {
            $('#modal-form [name=name]').val(response.name);
            $('#modal-form [name=email]').val(response.email);

            // Mengatur nilai inputan role berdasarkan level
            var roleInput = $('#modal-form [name=level]');
            if (response.level === '1') {
                roleInput.val('Owner');
            } else {
                roleInput.val('Owner');
            }
        })
        .fail((errors) => {
            // Menampilkan SweetAlert dengan pesan gagal
            Swal.fire({
                title: 'Kesalahan',
                text: 'Tidak dapat menampilkan data user.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });

        // Menambahkan event listener untuk klik tombol "Simpan"
        $(document).on('click', '#btnSimpan', function (event) {
        event.preventDefault();
            // Validasi input
            var name = $('#modal-form [name=name]').val();
            var email = $('#modal-form [name=email]').val();
            var password = $('#modal-form [name=password]').val();
            var password_confirmation = $('#modal-form [name=password_confirmation]').val();
            
            if (!name || !email || !password || !password_confirmation) {
                // Menampilkan SweetAlert dengan pesan kesalahan input kosong
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Semua input harus terisi',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (password !== password_confirmation) {
                // Menampilkan SweetAlert dengan pesan kesalahan password tidak sesuai
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Password tidak sesuai',
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
                        title: 'User Berhasil Diubah',
                        text: 'User telah berhasil diubah.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 5000
                    }).then(() => {
                        // Arahkan pengguna ke halaman index
                        window.location.href = '{{ route('user.index') }}';
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
                        text: 'Tidak dapat menyimpan data user.',
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
                            window.location.href = '{{ route('user.index') }}';
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

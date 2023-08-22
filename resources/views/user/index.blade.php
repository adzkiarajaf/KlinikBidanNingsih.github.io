@extends('layouts.master')

@section('title')
    Daftar User
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar User</li>
@endsection

@section('content')
<div class="box-header with-border">
    <button onclick="addForm('{{  route('user.store') }}')" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus-circle" id="tambah"></i> Tambah </button>
</div>


<ol class="list-group list-group-numbered">
    <?php foreach ($user as $index => $item): ?>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold"><?= $item->name ?></div>
                <div><?= $item->email ?></div>
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('userdetail.index', ['id' => $item->id]) }}" style="margin-left: auto;">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div>
                    <?php if ($item->level == 1): ?>
                        Kasir
                    <?php elseif ($item->level == 0): ?>
                        Owner
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ol>



@includeIf('user.form')
@endsection

@push('scripts')
<script>
    function addForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah User');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=name]').focus();

    $('#password, #password_confirmation').attr('required', true);

    $('#btnSimpan').on('click', function() {
        var namaUser = $('#modal-form [name=name]').val();
        // Periksa apakah semua input telah terisi
        var isFormValid = true;
        $('#modal-form form input').each(function() {
            if ($(this).val() === '') {
                isFormValid = false;
                return false; // Hentikan perulangan jika ada input yang kosong
            }
        });

            if ($('#selectRole').val() === '') {
                isFormValid = false;
                // Tampilkan pesan kesalahan kepada pengguna (misalnya dengan SweetAlert)
            }

        // Check for duplicate input
        var existingUser = {!! json_encode($user->pluck('name')) !!};
        if (existingUser.includes(namaUser)) {
            // Jika kategori sudah ada, tampilkan pesan kesalahan di dalam modal
            Swal.fire({
                title: 'Data Sudah Ada',
                text: 'User dengan nama yang sama sudah ada.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
            return; // Prevent form submission
        }

        if (isFormValid) {
            // Menampilkan SweetAlert jika semua input terisi
            Swal.fire({
                title: 'User Berhasil Ditambahkan',
                text: 'User baru telah berhasil ditambahkan.',
                icon: 'success',
                showConfirmButton: false,
                timer: 5000 // Durasi tampilan pesan (dalam milidetik)
            }).then(() => {
                // Arahkan pengguna ke halaman index
                window.location.href = '{{ route('user.index') }}';
            });
        } else {
            // Tampilkan pesan kesalahan kepada pengguna
            
        }
    });
}
</script>
@endpush
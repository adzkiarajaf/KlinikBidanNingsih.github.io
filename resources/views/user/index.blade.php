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
{{-- <div class="list-group">
    <div class="list-group-item list-group-item-action active" aria-current="true">
        User
    </div>
    <div class="list-group-item-action active">
        @foreach ($user as $index => $item)
        <a href="{{ route('userdetail.index', ['id' => $item->id]) }}" class="list-group-item">
            {{ $item->name }}
            <i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i>
        </a>
        @endforeach
    </div>
</div> --}}

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
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('user.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'name'},
                {data: 'email'},
                {data: 'level'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

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
        $('#modal-form .modal-title').text('Tambah User');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();

        $('#password, #password_confirmation').attr('required', true);
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit User');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();

        $('#password, #password_confirmation').attr('required', false);

        $.get(url)
            .done((response) => {
                $('#modal-form [name=name]').val(response.name);
                $('#modal-form [name=email]').val(response.email);
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
</script>
@endpush
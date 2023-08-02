@extends('layouts.master')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transaksi Baru</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>



@includeIf('pembelian.supplier')
@includeIf('pembelian.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'supplier'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-supplier').DataTable();
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                // {
                //     data: 'path_foto',
                //     render: function(data, type, row) {
                //         return '<img src="/img/' + data + '"  width="100" height="100">';
                //     }
                // },
                { data: 'nama_produk' },
                { data: 'harga_beli' },
                { data: 'jumlah' },
                { data: 'subtotal' },
            ]
        });
    });

    function addForm() {
        $('#modal-supplier').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');
        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Yakin ingin menghapus data terpilih?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                table.ajax.reload();
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Data terpilih berhasil dihapus.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            })
            .fail((errors) => {
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Tidak dapat menghapus data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}
</script>
@endpush
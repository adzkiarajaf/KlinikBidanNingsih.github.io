@extends('layouts.master')

@section('title')
    Laporan Pembelian {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection


@section('breadcrumb')
    @parent
    <li class="active"> Laporan Pembelian</li>
@endsection

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="">
            <button onclick="updatePeriode()" class="btn btn-info btn-sm btn-flat" data-toggle="modal" data-target="#modal-form">
                <i class="fa fa-plus-circle"></i> Ubah Periode
            </button>
            <a href="{{ route('laporanpembelian.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-sm btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>
        </div>
        <br>
        <h3 class="box-title">{{ tanggal_indonesia($tanggalAwal, false) }}
            s/d
            {{ tanggal_indonesia($tanggalAkhir, false) }}</h3>
        <div class="box-tools pull-right">
            <button
                type="button"
                class="btn btn-box-tool"
                data-widget="collapse"
                data-toggle="tooltip"
                title="Collapse">
                <i class="fa fa-minus"></i>
            </button>
            <button
                type="button"
                class="btn btn-box-tool"
                data-widget="remove"
                data-toggle="tooltip"
                title="Remove">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="box-body" style="font-weight: bold; color: #72afd2;">
        Pembelian Keseluruhan
    </div>
    <div class="box-footer">
        Total Nominal Transaksi
        <h1>
            {{ format_uang($total_pembelian) }}
        </h1>
    </div>
    <div class="box-footer">
        Jumlah Transaksi
        <h1>
            {{ $pembelian->count() }}
        </h1>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
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

@includeIf('laporanpembelian.form')
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
                { data: 'nama_produk' },
                { data: 'harga_beli' },
                { data: 'jumlah' },
                { data: 'subtotal' },
            ]
        });
    });

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

<script>
    $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
    });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }

    
@endpush
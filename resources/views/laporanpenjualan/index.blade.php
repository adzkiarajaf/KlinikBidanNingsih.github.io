@extends('layouts.master')

@section('title')
    Laporan Pembelian {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@section('breadcrumb')
    @parent
    <li class="active"> Laporan Penjualan</li>
@endsection

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="">
            <button onclick="updatePeriode()" class="btn btn-info btn-sm btn-flat" data-toggle="modal" data-target="#modal-form">
                <i class="fa fa-plus-circle"></i> Ubah Periode
            </button>
            
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
        Penjualan Keseluruhan
    </div>
    <div class="box-footer">
        Total Nominal Transaksi
        <h1>
            {{ format_uang($total_penjualan) }}
        </h1>
    </div>
    <div class="box-footer">
        Jumlah Transaksi
        <h1>
            {{ $penjualan->count() }}
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">

                    <br>
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('laporanpenjualan.form')
@includeIf('laporanpenjualan.detail')
@endsection

@push('scripts')
<script>
    
let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: '{{ route('penjualan.data') }}',
        },
        columns: [
            {data: 'DT_RowIndex', searchable: false, sortable: false},
            {data: 'tanggal'},
            {data: 'nama_user'},
            {data: 'total_item'},
            {data: 'total_harga'},
            {data: 'metode'},
            {data: 'aksi', searchable: false, sortable: false},
        ],
        order: [[1, 'desc']] // Mengurutkan berdasarkan kolom tanggal secara descending
    });

        tableDetail = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'id_produk' },
                { data: 'harga_beli' },
                { data: 'jumlah' },
                { data: 'subtotal' },
            ],
        });
    });

    function showDetail(url) {
        $('#modal-detail').modal('show');
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

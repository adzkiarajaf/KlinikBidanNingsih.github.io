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
    <div class="col-md-3">
        <div class="box box-default collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Penjualan Perhari</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                @foreach ($penjualan as $key => $item)
                <div class="box">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <h5>{{ tanggal_indonesia($item->created_at) }}</h5>
                    <h5>Rp. {{ format_uang($item->total_harga) }}</h5>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@includeIf('laporanpenjualan.form')
@endsection



@push('scripts')
<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }
@endpush
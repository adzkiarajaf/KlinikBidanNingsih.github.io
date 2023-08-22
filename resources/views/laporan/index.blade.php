@extends('layouts.master')

@section('title')
    Laporan Pendapatan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection


@section('breadcrumb')
    @parent
    <li class="active">Laporan</li>
@endsection

@section('content')
<div class="list-group">
    <div class="list-group-item list-group-item-action active" aria-current="true">
        Laporan
    </div>
    <div class="list-group-item-action active">
        <a href="{{ route('laporanpenjualan.index') }}" class="list-group-item">
            Laporan Penjualan
            <i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i>
        </a>
        <a href="{{ route('laporanpembelian.index') }}" class="list-group-item">
            Laporan Pembelian
            <i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i>
        </a>
        <a href="{{ route('laporankeuntungan.index') }}" class="list-group-item">
            Laporan Keuntungan
            <i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i>
        </a>
    </div>
</div>

@includeIf('laporan.form')
@endsection

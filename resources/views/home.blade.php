@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
                                <!-- Small boxes (Stat box) -->
                                <div class="row">
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-user-plus"></i>
                                                </h3>
                                                <p>User Management</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            <a href="{{ route('user.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                    </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-clone"></i>
                                                </h3>
                                                <p>Kelola Produk</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-clone"></i>
                                            </div>
                                            <a href="{{ route('produk.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-file-text-o"></i>
                                                </h3>
                                                <p>Kelola Kategori</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                            <a href="{{ route('kategori.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-refresh"></i>
                                                </h3>
                                                <p>Restock</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-refresh"></i>
                                            </div>
                                            <a href="{{ route('restok.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </h3>
                                                <p>Pembelian</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </div>
                                            <a href="{{ route('pembelian.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-book"></i>
                                                </h3>
                                                <p>Laporan</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-book"></i>
                                            </div>
                                            <a href="{{ route('laporan.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '0')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-truck"></i>
                                                </h3>
                                                <p>Supplier Management</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <a href="{{ route('supplier.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '1')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-refresh"></i>
                                                </h3>
                                                <p>Stock</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-refresh"></i>
                                            </div>
                                            <a href="{{ route('produk.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (auth()->check() && auth()->user()->level == '1')
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </h3>
                                                <p>Penjualan</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </div>
                                            <a href="{{ route('penjualan.index') }}" class="small-box-footer">More info
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
@endsection
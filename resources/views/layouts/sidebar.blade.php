<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img
                    src="{{ asset('AdminLTE-2/dist/img/ava.jpg') }}"
                    class="img-circle"
                    alt="User Image"></div>
                <div class="pull-left info">
                    <p>{{  auth()->user()->name }}</p>
                    <a href="#">
                        <i class="fa fa-circle text-success"></i>
                        Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('kategori.index') }}">
                        <i class="fa fa-file-text-o"></i>
                        <span>Kelola Kategori</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-clone"></i>
                        <span>Kelola Produk</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '1')
                <li>
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-refresh"></i>
                        <span>Stock</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('pembelian.index') }}">
                        <i class="fa fa-pencil-square-o"></i>
                        <span>Pembelian</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('restok.index') }}">
                        <i class="fa fa-refresh"></i>
                        <span>Restock</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '1')
                <li>
                    <a href="{{ route('penjualan.index') }}">
                        <i class="fa fa-pencil-square-o"></i>
                        <span>Penjualan</span>
                    </a>
                </li>
                @endif 
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('supplier.index') }}">
                        <i class="fa fa-truck"></i>
                        <span>Suplier Management</span>
                    </a>
                </li>
                @endif
                @if (auth()->check() && auth()->user()->level == '0')
                <li>
                    <a href="{{ route('laporan.index') }}">
                        <i class="fa fa-truck"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                @endif
            </ul>
            @if (auth()->check() && auth()->user()->level == '0')
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">SYSTEM</li>
                <li>
                    <a href="{{ route('user.index') }}">
                        <i class="fa fa-user-plus"></i>
                        <span>User Management</span>
                    </a>
                </li>
            </ul>
            @endif
        </section>
        <!-- /.sidebar -->
</aside>
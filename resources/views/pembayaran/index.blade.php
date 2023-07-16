@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Produk</li>
@endsection

@section('content') 
{{-- <table id="cart" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th style="width:50%">Produk</th>
            <th style="width:10%">Harga</th>
            <th style="width:8%">Quantity</th>
            <th style="width:22%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0 @endphp
        @if(session('cart'))
            @foreach(session('cart') as $produk => $item)
                @php $total += $item['harga_jual'] * $item['quantity'] @endphp
                <tr data-id="{{ $produk }}">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><img src="{{ asset('img/' . $item['path_foto']) }}" width="100" height="100" class="img-responsive"/></div>
                            <div class="col-sm-9">
                                <h5 class="nomargin">{{ $item['nama_produk'] }}</h5>
                            </div>
                        </div>
                    </td>
                    <td data-th="Harga">Rp. {{ format_uang($item['harga_jual']) }}</td>
                    <td data-th="quantity">
                        <input type="number" value="{{ $item['quantity'] }}" class="form-control quantity cart_update" min="1">
                    </td>
                    <td data-th="subtotal" class="text-center" data-subtotal="{{ $item['harga_jual'] * $item['quantity'] }}">Rp. {{ format_uang($item['harga_jual'] * $item['quantity']) }}</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm cart_remove"><i class="fa fa-trash-o"></i> Delete</button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right"><h3><strong>Total Pembayaran</a> Rp. {{ format_uang($total) }}</strong></h3></td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                <a href="{{ url('/penjualan') }}" class="btn btn-danger"> <i class="fa fa-arrow-left"></i> Lanjutkan Berbelanja</a>
                <button class="btn btn-success"><i class="fa fa-money"></i> Pembayaran</button>
            </td>
        </tr>
    </tfoot>
</table> --}}
@endsection

@push('scripts')
<script>
    $(".cart_update").change(function (e) {
    e.preventDefault();
    var ele = $(this);
    $.ajax({

        url: '{{ route('update_cart') }}',
        method: "patch",
        data: {
            _token: '{{ csrf_token() }}',
            id: ele
                .parents("tr")
                .attr("data-id"),
            quantity: ele
                .parents("tr")
                .find(".quantity")
                .val()
        },
        success: function (response) {
            window
                .location
                .reload();
        }
    });
});

    

$(".cart_remove").click(function (e) {
    e.preventDefault();

    var ele = $(this);

    if (confirm("Yakin ingin menghapus produk ini?")) {
        $.ajax({
            url: '{{ route('remove_from_cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}',
                id: ele
                    .parents("tr")
                    .attr("data-id")
            },
            success: function (response) {
                window
                    .location
                    .reload();
            }
        });
    }
});
</script>
@endpush

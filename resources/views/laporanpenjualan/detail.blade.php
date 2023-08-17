<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Penjualan</h4>
            </div>
            <div class="modal-body">
                <form id="search-form" class="form-inline">
                    <div class="form-group">
                        <label for="search-id">Cari Transaksi:</label>
                        <input type="text" class="form-control" id="search-id" placeholder="">
                    </div>
                    {{-- <button type="button" class="btn btn-primary" onclick="searchDetail()">Cari</button> --}}
                </form>
                
                @php
                    $groupedData = [];
                    $currentDate = null;
                @endphp
                
                <table class="table table-striped table-bordered table-detail">
                    <tbody hidden>
                        @foreach ($detailPenjualans as $penjualanId => $detailPenjualanItems)
                        @foreach ($detailPenjualanItems as $index => $detailItem)
                            @php
                                $tanggal = tanggal_indonesia($detailItem->created_at);
                                $produkNama = $detailItem->produk->nama_produk;
                                $harga = $detailItem->produk->harga_jual;
                                $jumlah = $detailItem->jumlah;
                                $subtotal = $detailItem->subtotal;
                                $key = $tanggal;
                                
                                if (!isset($groupedData[$key])) {
                                    $groupedData[$key] = [
                                        'tanggal' => $tanggal,
                                        'items' => [],
                                        'totalJumlah' => 0,
                                    ];
                                }
                                
                                // Search for the existing product within the same date
                                $existingItemIndex = null;
                                foreach ($groupedData[$key]['items'] as $existingIndex => $existing) {
                                    if ($existing['produkNama'] === $produkNama) {
                                        $existingItemIndex = $existingIndex;
                                        break;
                                    }
                                }
                                
                                if ($existingItemIndex !== null) {
                                    // If the product already exists, update the quantity and subtotal
                                    $groupedData[$key]['items'][$existingItemIndex]['jumlah'] += $jumlah;
                                    $groupedData[$key]['items'][$existingItemIndex]['subtotal'] += $subtotal;
                                } else {
                                    // If the product doesn't exist, add it to the items array
                                    $groupedData[$key]['items'][] = [
                                        'produkNama' => $produkNama,
                                        'harga' => $harga,
                                        'jumlah' => $jumlah,
                                        'subtotal' => $subtotal,
                                    ];
                                }
                    
                                // Update the total quantity for the date
                                $groupedData[$key]['totalJumlah'] += $jumlah;
                            @endphp
                        @endforeach
                    @endforeach
                        @foreach ($groupedData as $date => $group)
                            <tbody class="tanggal">
                                <tr>
                                    <th colspan="5">{{ $group['tanggal'] }}</th>
                                </tr>
                            </tbody>
                            <tbody class="data-detail">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Jual</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                                @foreach ($group['items'] as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['produkNama'] }}</td>
                                        <td>Rp. {{ format_uang($item['harga']) }}</td>
                                        <td>{{ $item['jumlah'] }}</td>
                                        <td>Rp. {{ format_uang($item['subtotal']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        var originalData = $('#modal-detail .table-detail').html(); // Store the original table content

        function searchDetail() {
            var searchValue = $('#search-id').val().trim().toLowerCase();

            if (searchValue === '') {
                $('#modal-detail .table-detail').html(originalData); // Restore the original table content
                return;
            }

            $('#modal-detail .tanggal, #modal-detail .data-detail').hide();

            $('#modal-detail .tanggal').each(function () {
                var rowDateText = $(this).find('th').text().trim().toLowerCase();
                var shouldShowGroup = rowDateText.includes(searchValue);

                if (shouldShowGroup) {
                    $(this).show();

                    var dataDetailTBody = $(this).nextUntil('.tanggal').filter('.data-detail');

                    dataDetailTBody.show(); // Show the entire data-detail block
                    dataDetailTBody.find('tr').each(function () {
                        var rowProductName = $(this).find('td:eq(1)').text().trim().toLowerCase();
                        if (rowProductName.includes(searchValue)) {
                            $(this).show();
                        }
                    });
                }
            });
        }

        // Bind the searchDetail() function to the input field's keyup event
        $('#search-id').keyup(function () {
            searchDetail();
        });
    });
</script>
@endpush

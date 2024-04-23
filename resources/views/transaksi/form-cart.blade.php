<div class="card card-orange card-outline">
    <div class="card-body">
        <h3 class="m-0 text-right">Rp <span id="totalJumlah" data-price-total="{{ session('price_total') }}">0</span>, -</h3>
    </div>
</div>
<form action="{{ route('transaksi.store') }}" method="POST" class="card card-orange card-outline">
    @csrf
    <div class="card-body">
        <p class="text-right">
            Tanggal : {{ $tanggal }}
        </p>
        <div class="row">
            <div class="col">
                <label for="">Nama Pelanggan</label>
                <input type="text" id="namaPelanggan" class="form-control @error('pelanggan_id') is-invalid @enderror" disabled>
                @error('pelanggan_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <input type="hidden" name="pelanggan_id" id="pelangganId">
            </div>
            <div class="col">
                <label for="">Nama Kasir</label>
                <input type="text" class="form-control" value="{{ $nama_kasir }}" disabled>
            </div>
        </div>

        <table class="table table-stripped table-hover table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Product</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                    <th>Promo</th>
                    <th></th>
                </tr>
            </thead>
            {{-- @error('lowStock')     // @error juga berfungsi
                @foreach ($errors->get('lowStock') as $lowStockMessage)
                    <div class="alert alert-dismissible alert-danger mt-2 mb-0" role="alert">
                        {{ $lowStockMessage }}
                    </div>
                @endforeach
            @enderror --}}
            @if ($errors->has('lowStock'))
                @foreach ($errors->get('lowStock') as $lowStockMessage)
                    <div class="alert alert-dismissible alert-danger mt-2 mb-0" role="alert">
                        {{ $lowStockMessage }}
                    </div>
                @endforeach
            @endif
            <tbody id="resultCart">
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data.</td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-2 offset-6">
                <p>Sub Total</p>
                <p>Pajak 15%</p>
                <p>Total Bayar</p>
            </div>
            <div class="col-4 text-right">
                <p id="subTotal">0</p>
                <p id="taxAmount">0</p>
                <p id="total">0</p>
            </div>
        </div>

        <div class="col-6 offset-6">
            <hr class="mt-0">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text">Cash</span>
                </div>
                <input type="number" name="cash" class="form-control @error('cash') is-invalid @enderror"
                    placeholder="Cash" value="{{ old('cash') }}">
                @error('cash')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <input type="hidden" id="subTotalAllItem" name="sub_total_all_item">
                <input type="hidden" id="totalPajak" name="total_pajak">
                <input type="hidden" id="totalBayar" name="total_bayar">
            </div>
        </div>

        <div class="col-12 form-inline mt-3">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mr-2">Ke Tansaksi</a>
            <a href="{{ route('cart.clear') }}" class="btn btn-danger mr-2">Kosongkan</a>
            <button type="submit" class="btn btn-success ml-auto">
                <i class="fas fa-money-bill-wave mr-2"></i> Bayar Transaksi
            </button>
        </div>
    </div>
</form>
@push('scripts')
    <script>
        $(function() {
            fetchCart();
        });

        function fetchCart() {
            $.getJSON("/cart", function(response) {
                $('#resultCart').empty();

                const {
                    items,
                    subtotal,
                    tax_amount,
                    total,
                    extra_info,
                    priceTotal,
                    pajakTotal,
                    subTotalAllItem,
                } = response;
                $('#subTotal').html(rupiah(subTotalAllItem));
                // $('#taxAmount').html(rupiah(tax_amount));
                $('#taxAmount').html(rupiah(pajakTotal));
                $('#total').html(rupiah(priceTotal));
                // $('#totalJumlah').html(rupiah(total));
                $('#totalJumlah').html(rupiah(priceTotal));
                $('#totalBayar').val(priceTotal);
                $('#totalPajak').val(pajakTotal);
                $('#subTotalAllItem').val(subTotalAllItem);

                for (const property in items) {
                    addRow(items[property]);
                }

                if (Array.isArray(items)) {
                    $('#resultCart').html(`<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>`);
                }

                // if (!Array.isArray(extra_info)) {
                //     const {
                //         id,
                //         name
                //     } = extra_info.pelanggan;
                //     $('namaPelanggan').val(name)
                //     $('#pelangganId').val(id);
                // }
                // if (!Array.isArray(extra_info)) {
                //     const {
                //         id,
                //         nama
                //     } = extra_info.pelanggan;
                //     $('#namaPelanggan').val(nama);
                //     $('#pelangganId').val(id);
                // }

                if (!Array.isArray(extra_info)) {
                    const {
                        id,
                        name
                    } = extra_info.pelanggan;
                    $('#namaPelanggan').val(name);
                    $('#pelangganId').val(id);
                }
            });
        }

        function addPelanggan(id) {
            $.post("/transaksi/pelanggan", {
                id: id
            }, function(response) {
                fetchCart();
            }, "json");
        }

        function addRow(item) {
            const {
                hash,
                title,
                quantity,
                price,
                total_price,
                extra_info,
            } = item;

            let btn = `<button type="button" class="btn btn-xs btn-success mr-2" onclick="ePut('${hash}', 1)">
                    <i class="fas fa-plus"></i>
                </button>`;

            btn += `<button type="button" class="btn btn-xs btn-primary mr-2" onclick="ePut('${hash}', -1)">
                    <i class="fas fa-minus"></i>
                </button>`;

            btn += `<button type="button" class="btn btn-xs btn-danger mr-2" onclick="eDel('${hash}')">
                    <i class="fas fa-times"></i>
                </button>`;
            
            btn += `<input type="number" id="productAmount${hash}" class="rounded ml-2" style="width: 100px;" placeholder="Qty">`;

            btn += `<button type="button" class="btn btn-xs btn-success ml-1" onclick="ePut('${hash}', document.getElementById('productAmount${hash}').value)">
                    <i class="fas fa-plus"></i>
                </button>`;

            const row = `<tr>
                    <td>${title}</td>
                    <td>${quantity}</td>
                    <td>${rupiah(price)}</td>
                    <td>${rupiah(extra_info.sub_total)}</td>
                    <td>${
                        extra_info.buy == null && extra_info.get == null && extra_info.discount == null ? '' :
                        extra_info.buy == null && extra_info.get == null ? 'Diskon ' + extra_info.discount + '%' :
                        extra_info.get == null ? 'Buy ' + extra_info.buy + ' Diskon ' + extra_info.discount + '%' :
                        'Buy ' + extra_info.buy + ' Get ' + extra_info.get}</td>
                    <td>${btn}</td>
                </tr>`;

            $('#resultCart').append(row);
        }

        function rupiah(number) {
            return new Intl.NumberFormat("id-ID").format(number);
        }

        function ePut(hash, qty) {
            $.ajax({
                type: "PUT",
                url: "/cart/" + hash,
                data: {
                    qty: qty
                },
                dataType: "json",
                success: function(response) {
                    fetchCart();
                }
            });
        }

        function eDel(hash) {
            $.ajax({
                type: "DELETE",
                url: "/cart/" + hash,
                dataType: "json",
                success: function(response) {
                    fetchCart();
                }
            });
        }
    </script>
@endpush

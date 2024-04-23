<form action="" method="" id="formBarcode" class="card card-orange card-outline">
    <div class="card-body">
        <div class="input-group">
            <input type="text" class="form-control" id="barcode" placeholder="Kode / Barcode">
            <div class="input-group-append">
                <button type="reset" class="btn btn-danger" id="resetBarcode">Clear</button>
            </div>
        </div>
        <div class="invalid-feedback" id="msgErrorBarcode"></div>
    </div>
</form>

@push('scripts')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('#resetBarcode').click(function() {
                $('#barcode').focus();
            })

            $('#formBarcode').submit(function(event) {
                event.preventDefault();
                let kodeProduct = $('#barcode').val();

                if (kodeProduct.length > 0) {
                    addItem(kodeProduct);
                }
            });
        });

        function addItem(kodeProduct) {
            $('#msgErrorBarcode').removeClass('d-block').html('');
            $('#barcode').removeClass('is-invalid').prop('disabled', true);

            $.post("/cart", {
                'kode_product': kodeProduct,
            },
            function(response) {
                fetchCart();
            }, "json")
            // .fail(function(error) {
            //     if (error.status === 422) {
            //         $('#msgErrorBarcode').addClass('d-block').html(error.responseJSON.errors.kodeProduct[0]);
            //         $('#barcode').addClass('is-invalid');
            //     }
            // })
            .fail(function(error) {
                if (error.status === 422) {
                    $('#msgErrorBarcode').addClass('d-block').html(error.responseJSON.errors.kode_product[0]);
                    $('#barcode').addClass('is-invalid');
                }
            })
            .always(function() {
                $('#barcode').val('').prop('disabled', false).focus();
            });
        }
    </script>
@endpush

<form action="" method="GET" id="formCariProduct">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Nama Product" id="searchProduct" autofocus>
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                Cari
            </button>
        </div>
    </div>    
</form>
<table class="table table-stripped table-hover table-sm mt-3">
    <thead>
        <tr>
            <th colspan="2" class="border-0">Hasil Pencarian : </th>
        </tr>
    </thead>
    <tbody id="resultProduct"></tbody>
</table>

@push('scripts')
    <script>
        $('#formCariProduct').submit(function(event) {
            event.preventDefault();
            const search = $('#searchProduct').val();
            
            if (search.length >= 3) {
                fetchCariProduct(search);
            }
        })

        function fetchCariProduct(search) {
            $.getJSON("/transaksi/product", {
                search: search
            },
            function(response) {
                $('#resultProduct').html('')
                if (response.length > 0) {
                    console.log("ada");
                    response.forEach(item => {
                        addResultProduct(item);
                    });
                } else {
                    const row = `<tr>
                            <td class="text-center text-danger font-weight-bold">Product tidak ditemukan</td>
                        </tr>`;
                    $('#resultProduct').append(row);
                }
            });
        }

        function addResultProduct(item) {
            const {
                nama_product,
                kode_product,
                stock,
            } = item

            const btn = `<button type="button" class="btn btn-xs btn-success" ${stock <= 0 ? 'disabled' : ''} onclick="addItem('${kode_product}')">
                ${stock <= 0 ? 'Product kosong' : 'Add'}
                </button>`;

            const row = `<tr>
                    <td>${nama_product}</td>
                    <td class="text-right">${btn}</td>
                </tr>`;
            $('#resultProduct').append(row);
        }
    </script>
@endpush

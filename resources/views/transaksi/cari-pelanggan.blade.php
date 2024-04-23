<form action="" method="GET" id="formCariPelanggan">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Nama Pelanggan" id="searchPelanggan">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </div>
</form>
<table class="table table-stripper table-sm table-hover mt-3">
    <thead>
        <tr>
            <th colspan="2" class="border-0">Hasil Pencarian : </th>
        </tr>
    </thead>
    <tbody id="resultPelanggan"></tbody>
</table>

@push('scripts')
    <script>
        $(function() {
            $('#formCariPelanggan').submit(function(event) {
                event.preventDefault();
                const search = $('#searchPelanggan').val();
    
                if (search.length >= 3) {
                    fetchCariPelanggan(search);
                }
            })
    
            function fetchCariPelanggan(search) {
                $.getJSON("/transaksi/pelanggan/search", {
                    search: search
                },
                
                function(response) {
                    $('#resultPelanggan').html('')

                    if (response.length > 0) {
                        response.forEach(item => {
                            addResultPelanggan(item)
                        });
                    } else {
                        console.log("gaada");
                        const row = `<tr>
                                <td class="text-center text-danger font-weight-bold">Pelanggan tidak ditemukan</td>
                            </tr>`;
                        $('#resultPelanggan').append(row);
                    }
                });
            }
    
            function addResultPelanggan(item) {
                const {
                    id,
                    name,
                } = item
    
                const btn = `<button type="button" class="btn btn-xs btn-success" onclick="addPelanggan(${id})">
                        Pilih
                    </button>`;
    
                const row = `<tr>
                        <td>${name}</td>
                        <td class="text-right">${btn}</td>
                    </tr>`;
                $('#resultPelanggan').append(row);
            }
    
            function addPelanggan(id) {
                $.post("/transaksi/pelanggan", {
                    id: id
                },
    
                function(response) {
                    fetchCart();
                }, "json");
            }
        })
    </script>
@endpush
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faktur Pembayaran</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .invoice {
            width: 70mm;
        }

        table {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        hr {
            border-top: 1px solid #8c8b8b;
        }
    </style>
</head>
<body onload="javascript:window.print()">
    <div class="invoice">
        <h3 class="center">{{ config('app.name') }}</h3>
        <p class="center">
            Jl. Raya Padaherang KM.1, Desa Padaherang <br>
            Kec. Padaherang - Kab. Pangandaran
        </p>
        <hr>
        <p>
            Nomor Transaksi : {{ $penjualan->nomor_transaksi }} <br>
            Tanggal : {{ date('d/m/y H:i:s', strtotime($penjualan->tanggal)) }} <br>
            Pelanggan : {{ $pelanggan ? $pelanggan->name : '-' }} <br>
            Kasir : {{ $user->name }} <br>
        </p>
        <hr>
        <table>
            @foreach ($detailPenjualan as $detail)
                <tr>
                    <td>{{ $detail->jumlah }} {{ $detail->nama_product }} x {{ $detail->harga_product }}</td>
                    <td class="right">{{ number_format($detail->subtotal, 0, ',', '.') }}, {{ $detail->product ? ($detail->product->promoShop ? ($detail->product->promoShop->active == 1 ? ($detail->buy == null && $detail->get == null ? 'Discount ' . $detail->discount . '%' : ($detail->get == null ? 'Buy ' . $detail->buy . ' Discount ' . $detail->discount : 'Buy ' . $detail->buy . ' Get ' . $detail->get)) : '') : '') : '' }}</td>
                </tr>
            @endforeach
        </table>
        <hr>
        <p class="right">
            Sub Total : {{ number_format($penjualan->subtotal, 0, ',', '.') }} <br>
            Pajak PPN 15 % : {{ number_format($penjualan->pajak, 0, ',', '.') }} <br>
            Total : {{ number_format($penjualan->total, 0, ',', '.') }} <br>
            Tunai : {{ number_format($penjualan->tunai, 0, ',', '.') }} <br>
            Kembalian : {{ number_format($penjualan->kembalian, 0, ',', '.') }} <br>
        </p>
        <h3 class="center">Terima Kasih</h3>
    </div>
</body>
</html>
@extends('layouts.main', ['title' => 'Invoice'])
@section('title-content')
    <i class="fas fa-file-invoice mr-2"></i> Invoice
@endsection
@section('content')
    @if (session('destroy') == 'success')
    <x-alert type="success">
        <strong>Berhasil dibatalkan!</strong> Transaksi berhasil dibatalkan.
    </x-alert>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <p>No Transaksi : {{ $penjualan->nomor_transaksi }} </p>
                    <p>Nama Pelanggan : {{ $penjualan->pelanggan ? $penjualan->pelanggan->name : '-' }} </p>
                    <p>No. Telepon : {{ $penjualan->pelanggan ? $penjualan->pelanggan->nomor_tlp : '-' }} </p>
                    <p>Alamat : {{ $penjualan->pelanggan ? $penjualan->pelanggan->alamat : '-' }} </p>
                </div>
                <div class="col">
                    <p>Tgl. Transaksi : {{ date('d/m/Y H:i:s', strtotime($penjualan->tanggal)) }} </p>
                    <p>Kasir : {{ $user->name }} </p>
                    <p>
                        Status : 
                        @if ($penjualan->status == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-stripped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Product</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                        <th>Promo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPenjualan as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $detail->product->nama_product }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ number_format($detail->harga_product, 0, ',', '.') }}</td>
                            <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td>{{ $detail->product ? ($detail->product->promoShop ? ($detail->product->promoShop->active == 1 ? ($detail->buy == null && $detail->get == null ? 'Discount ' . $detail->discount . '%' : ($detail->get == null ? 'Buy ' . $detail->buy . ' Discount ' . $detail->discount : 'Buy ' . $detail->buy . ' Get ' . $detail->get)) : '') : '') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col offset-6 text-right">
                    <p>Sub Total : {{ number_format($penjualan->subtotal, 0, ',', '.') }} </p>
                    <p>Pajak 15% : {{ number_format($penjualan->pajak, 0, ',', '.') }} </p>
                    <p>Total : {{ number_format($penjualan->total, 0, ',', '.') }} </p>
                    <p>Cash : {{ number_format($penjualan->tunai, 0, ',', '.') }} </p>
                    <p>Kembalian : {{ number_format($penjualan->kembalian, 0, ',', '.') }} </p>
                </div>
            </div>
        </div>
        <div class="card-footer form-inline">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mr-2">Ke Transaksi</a>
            @if ($penjualan->status == 'selesai')
                <button type="button" class="btn btn-danger ml-auto mr-2" data-toggle="modal" data-target="#modalBatal">
                    Dibatalkan
                </button>
            @endif
            {{-- <a href="{{ route('transaksi.cetak', $penjualan->id) }}" target="_blank" class="btn btn-primary {{ $penjualan->status == 'batal' ? 'ml-auto' : '' }}"></a> --}}
            <a href="{{ route('transaksi.cetak', $penjualan->id) }}" target="_blank" class="btn btn-primary @if($penjualan->status == 'batal') ml-auto @endif">
                <i class="fas fa-print mr-2"></i> Cetak
            </a>
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="modalBatal" tab-index="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-tile">Dibatalkan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah yakin dibatalkan ?</p>
                    <form action="{{ route('transaksi.destroy', $penjualan->id) }}" method="POST" style="display: none;" id="formBatal">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="yesBatal">Ya, Batal!</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $(function() {
            $('#yesBatal').click(function() {
                $('#formBatal').submit();
            })
        })
    </script>
@endpush

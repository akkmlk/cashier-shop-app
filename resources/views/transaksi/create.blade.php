@extends('layouts.main', ['title' => 'Transaksi'])
@section('title-content')
    <i class="fas fa-cash-register"></i> Transaksi
@endsection
@section('content')
    <div class="row">
        <div class="col-4">
            @include('transaksi.form-code')
            <div class="card card-orange card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="cari-tab" role="tablist">
                        <li class="nav-item">
                            <a href="#cari-product" class="nav-link active" id="cari-product-tab" data-toggle="pill" role="tab">
                                Cari Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#cari-pelanggan" class="nav-link" id="cari-pelanggan-tab" data-toggle="pill">
                                Cari Pelanggan
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="cari-tabContent">
                        <div class="tab-pane fade active show" id="cari-product" role="tabpanel">
                            @include('transaksi.cari-product')
                        </div>
                        <div class="tab-pane fade show" id="cari-pelanggan" role="tabpanel">
                            @include('transaksi.cari-pelanggan')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            @include('transaksi.form-cart')
        </div>
    </div>
@endsection

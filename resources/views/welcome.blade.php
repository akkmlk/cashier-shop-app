@extends('layouts.main', ['title' => 'Home'])
@section('title-content')
    <i class="fas fa-home mr-2"></i> Home
@endsection
@section('content')
    <div class="row">
        @can('admin')
            <x-box title="User" background="bg-danger" icon="fas fa-user-tie" :route="route('user.index')" :jumlah="$user->jumlah" />
            <x-box title="Kategori" background="bg-warning" icon="fas fa-list" :route="route('kategori.index')" :jumlah="$kategori->jumlah" />
        @endcan
        <x-box title="Pelanggan" background="bg-primary" icon="fas fa-users" :route="route('pelanggan.index')" :jumlah="$pelanggan->jumlah" />
        <x-box title="Product" background="bg-success" icon="fas fa-box-open" :route="route('product.index')" :jumlah="$product->jumlah" />
    </div>
    <div class="card">
        <div class="card-body">
            <canvas id="myChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',

            data: {
                labels: <?= $cart['labels'] ?>,
                datasets: [{
                    label: "{{ $cart['label'] }}",
                    data: <?= $cart['data'] ?>,
                    borderWidth: 3
                }]
            }, 
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endpush
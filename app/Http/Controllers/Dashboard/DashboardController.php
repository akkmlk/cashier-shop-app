<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::selectRaw('count(*) AS jumlah')->first();
        $pelanggan = Pelanggan::selectRaw('count(*) AS jumlah')->first();
        $kategori = Kategori::selectRaw('count(*) AS jumlah')->first();
        $product = Product::selectRaw('count(*) AS jumlah')->first();

        $penjualan = Penjualan::query()->select(
                DB::raw('SUM(total) AS jumlah_total'),
                DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') tgl")
            )
            ->where('status', 'selesai')
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->groupBy('tgl')
            ->get();

        $namaBulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $label = 'Transaksi ' . $namaBulan[date('m') - 1] . ' ' . date('Y');
        $labels = [];
        $data = [];

        foreach ($penjualan as $row) {
            $labels[] = substr($row->tgl, 0, 2);
            $data[] = $row->jumlah_total;
        }

        return view('welcome', [
            'user' => $user,
            'pelanggan' => $pelanggan,
            'kategori' => $kategori,
            'product' => $product,
            'cart' => [
                'label' => $label,
                'labels' => json_encode($labels),
                'data' => json_encode($data),
            ]
        ]);
    }
}

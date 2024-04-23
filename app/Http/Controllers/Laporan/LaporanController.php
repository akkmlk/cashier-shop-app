<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $officers = User::query()->where('role', 'petugas')->get();
        $officerList = [
            ['value' => '', 'option' => 'Pilih Petugas']
        ];
        foreach ($officers as $officer) {
            $officerList[] = ['value' => $officer->id, 'option' => $officer->name];
        }
        
        return view('laporan.form', compact('officerList'));
    }

    public function harian(Request $request)
    {
        if ($request->user_id != null) {
            $penjualan = Penjualan::query()->join('users', 'users.id', 'penjualans.user_id')
                ->leftJoin('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
                ->where('user_id', $request->user_id)
                ->whereDate('tanggal', $request->tanggal)
                ->select('penjualans.*', 'pelanggans.name AS nama_pelanggan', 'users.name AS nama_kasir')
                ->orderBy('id')
                ->get();

            $totalStatusSelesai = Penjualan::where('status', 'selesai')
                ->where('user_id', $request->user_id)
                ->whereDate('tanggal', $request->tanggal)
                ->sum('total');
        } else {
            $penjualan = Penjualan::query()->join('users', 'users.id', 'penjualans.user_id')
                ->leftJoin('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
                ->whereDate('tanggal', $request->tanggal)
                ->select('penjualans.*', 'pelanggans.name AS nama_pelanggan', 'users.name AS nama_kasir')
                ->orderBy('id')
                ->get();

            $totalStatusSelesai = Penjualan::where('status', 'selesai')
                ->whereDate('tanggal', $request->tanggal)
                ->sum('total');
        }

        return view('laporan.harian', [
            'penjualan' => $penjualan,
            'total' => $totalStatusSelesai,
        ]);
    }

    public function bulanan(Request $request)
    {
        $officer = User::find($request->user_id);
        if ($request->user_id != null) {
            $penjualan = Penjualan::select(
                DB::raw('COUNT(id) AS jumlah_transaksi'),
                DB::raw('SUM(total) as jumlah_total'),
                DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl")
            )
            ->where('user_id', $request->user_id)
            ->where('status', 'selesai')
            ->whereMonth('tanggal', $request->bulan)
            ->whereYear('tanggal', $request->tahun)
            ->groupBy('tgl')
            ->get();
        } else {
            $penjualan = Penjualan::select(
                DB::raw('COUNT(id) AS jumlah_transaksi'),
                DB::raw('SUM(total) as jumlah_total'),
                DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl")
            )
            ->where('status', 'selesai')
            ->whereMonth('tanggal', $request->bulan)
            ->whereYear('tanggal', $request->tahun)
            ->groupBy('tgl')
            ->get();
        }
        // dd($officer);

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

        $bulan = isset($namaBulan[$request->bulan - 1]) ? $namaBulan[$request->bulan - 1] : null;

        return view('laporan.bulanan', [
            'officer' => $officer,
            'penjualan' => $penjualan,
            'bulan' => $bulan,
        ]);
    }
}

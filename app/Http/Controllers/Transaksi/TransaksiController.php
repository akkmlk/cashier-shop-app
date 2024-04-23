<?php

namespace App\Http\Controllers\Transaksi;

use App\Models\User;
use App\Models\Product;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use Jackiedo\Cart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $penjualans = Penjualan::query()->join('users', 'users.id', 'penjualans.user_id')
            ->leftJoin('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
            ->when($search, function($q, $search) {
                $q->where('nomor_transaksi', 'like', "%$search%");
            })
            ->select('penjualans.*', 'users.name AS nama_kasir', 'pelanggans.name AS nama_pelanggan')
            ->orderBy('id', 'desc')
            ->paginate(10);

        if ($search) $penjualans->appends(['search', $search]);

        return view('transaksi.index', [
            'penjualans' => $penjualans,
        ]);
    }

    public function create(Request $request)
    {
        return view('transaksi.create', [
            'nama_kasir' => $request->user()->name,
            'tanggal' => date('d F Y'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => ['nullable', 'exists:pelanggans,id'],
            'cash' => ['required', 'numeric', 'gte:total_bayar'],
        ], [], [
            'pelanggan_id' => 'pelanggan',
        ]);

        $user = $request->user();
        $lastPenjualan = Penjualan::query()->orderBy('id', 'desc')->first();

        $cart = Cart::name($user->id);
        $cartDetails = $cart->getDetails();
        
        // $total = $cartDetails->get('total');
        $subTotalAllItem = $request->sub_total_all_item;
        $pajak = $request->total_pajak;
        $total = $request->total_bayar;
        $kembalian =  $request->cash - $total;
        
        $no = $lastPenjualan ? $lastPenjualan->id + 1 : 1;
        $no = sprintf("%04d", $no);
        // dd($subTotalAllItem);

        DB::transaction(function() use($user, $request, $no, $total, $kembalian, $cartDetails, &$penjualan, $pajak, $cart, $subTotalAllItem) {
            $penjualan = Penjualan::create([
                'user_id' => $user->id,
                // 'pelanggan_id' => $cart->getExtraInfo('pelanggan.id'),
                'pelanggan_id' => $request->pelanggan_id,
                'nomor_transaksi' => date('Ymd') . $no,
                'tanggal' => date('Y-m-d H:i:s'),
                'total' => $total,
                'tunai' => $request->cash,
                'kembalian' => $kembalian,
                'pajak' => $pajak,
                // 'pajak' => $cartDetails->get('tax_amount'),
                'subtotal' => $subTotalAllItem,
            ]);
    
            $allItems = $cartDetails->get('items');
            
            $lowStockMessages = [];
            foreach ($allItems as $key => $value) {
                $item = $allItems->get($key);
                $inItem = $cart->getItem($item->get('hash'));
                $subTotalPerItem = $inItem->get('extra_info.sub_total');
                Log::info( "Sub total per item : " . $subTotalPerItem);

                // dd($inItem->get('extra_info.sub_total'));
                $product = Product::find($item->id);
                Log::info($product);
                Log::info($key);
                Log::info($item);
                if ($inItem->getQuantity() > $product->stock) {
                    $lowStockMessage = 'Oops! Stok ' . $item->title . ' tidak cukup. Hanya tersisa ' . $product->stock . ', Kurangi atau batalkan!';
                    $lowStockMessages[] = $lowStockMessage;
                } else {
                    Log::info("stock tersedia");
                    $newStock = $product->stock - $inItem->getQuantity();
                    $product->update([
                        'stock' => $newStock,
                    ]);
        
                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'product_id' => $item->id,
                        'jumlah' => $inItem->getQuantity(),
                        'harga_product' => $item->price,
                        'subtotal' => $subTotalPerItem,
                    ]);
                }   
            }
    
            if (!empty($lowStockMessages)) {
                throw ValidationException::withMessages([
                    'lowStock' => $lowStockMessages,
                ]);
            }
        });

        $cart->destroy();

        return redirect()->route('transaksi.show', $penjualan->id);
    }

    public function show(Request $request, Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);
        $detailPenjualan = DetailPenjualan::query()->join('products', 'products.id', 'detail_penjualans.product_id')
            ->leftJoin('promo_shops', 'promo_shops.id', 'products.promo_shop_id')
            ->where('penjualan_id', $transaksi->id)
            ->select('detail_penjualans.*', 'nama_product', 'promo_shops.name AS promo_name', 'promo_shops.buy', 'promo_shops.get', 'promo_shops.discount')
            ->get();

        return view('transaksi.invoice', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan,
            'user' => $user,
            'detailPenjualan' => $detailPenjualan,
        ]);
    }

    public function destroy(Penjualan $transaksi)
    {
        $detailPenjualans = DetailPenjualan::query()->where('penjualan_id', $transaksi->id)->get();
        foreach ($detailPenjualans as $detail) {
            $product = Product::find($detail->product_id);
            $newStock = $product->stock + $detail->jumlah;
            
            $product->update([
                'stock' => $newStock,
            ]);
        }

        $transaksi->update([
            'status' => 'batal',
        ]);

        return back()->with('destroy', 'success');
    }

    public function product(Request $request)
    {
        $search = $request->search;
        $products = Product::query()->when($search, function($q, $search) {
            $q->where('nama_product', 'like', "%$search%");
        })
        ->select('id', 'kode_product', 'nama_product', 'stock')
        ->orderBy('nama_product')
        ->take(15)
        ->get();

        return response()->json($products);
    }

    public function pelanggan(Request $request)
    {
        $search = $request->search;
        $pelanggans = Pelanggan::query()->when($search, function($q, $search) {
            $q->where('name', 'like', "%$search%");
        })
        ->select('id', 'name')
        ->orderBy('name')
        ->take(15)
        ->get();

        return response()->json($pelanggans);
    }

    public function addPelanggan(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:pelanggans,id']
        ]);

        $pelanggan = Pelanggan::find($request->id);

        $cart = Cart::name($request->user()->id);

        $cart->setExtraInfo([
            'pelanggan' => [
                'id' => $pelanggan->id,
                'name' => $pelanggan->name,
            ]
        ]);

        return response()->json(['message' => 'Berhasil.']);
    }

    public function cetak(Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);
        $detailPenjualan = DetailPenjualan::query()->join('products', 'products.id', 'detail_penjualans.product_id')
            ->leftJoin('promo_shops', 'promo_shops.id', 'products.promo_shop_id')
            ->where('penjualan_id', $transaksi->id)
            ->select('detail_penjualans.*', 'nama_product', 'promo_shops.name', 'promo_shops.buy', 'promo_shops.get', 'discount')
            ->get();
            // dd($detailPenjualan);

        return view('transaksi.cetak', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan,
            'user' => $user,
            'detailPenjualan' => $detailPenjualan,
        ]);
    }
}

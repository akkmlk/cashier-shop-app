<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $stocks = Stock::query()->join('products', 'products.id', 'stocks.product_id')
            ->select('stocks.*', 'nama_product')
            ->when($search, function($q, $search) {
                $q->where('tanggal', 'like', "%$search%");
            })
            ->orderBy('stocks.id', 'desc')
            ->paginate(10);

        if ($search) $stocks->appends(['search', $search]);

        return view('stock.index', [
            'stocks' => $stocks,
        ]);
    }

    public function create()
    {
        return view('stock.create');
    }

    public function product(Request $request)
    {
        $products = Product::query()->where('nama_product', 'like', "%$request->search%")
            ->select('id', 'nama_product')
            ->take(15)
            ->orderBy('nama_product')
            ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'jumlah' => ['required', 'numeric', 'gte:100'],
            'nama_suplier' => ['required', 'max:255'],
        ], [
            'jumlah.gte' => 'Harus lebih dari atau sama dengan 100',
        ], [
            'product_id' => 'Nama Product',
        ]);
        
        $request->merge(['tanggal' => now()->format('Y-m-d')]);

        Stock::create($request->all());

        $product = Product::find($request->product_id);

        $product->update([
            'stock' => $product->stock + $request->jumlah,
        ]);

        return redirect()->route('stock.index')->with('store', 'success');
    }

    public function destroy(Stock $stock)
    {
        $product = Product::find($stock->product_id);
        $stockProduct = $product->stock - $stock->jumlah;
        
        $product->update([
            'stock' => $stockProduct,
        ]);
        $stock->delete();

        return back()->with('destroy', 'success');
    }
}

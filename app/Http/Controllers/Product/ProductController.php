<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $products = Product::query()->when($search, function($q, $search) {
            $q->where('kode_product', 'like', "%$search%")
                ->orWhere('nama_product', 'like', "%$search%");
        })
        ->join('kategoris', 'kategoris.id', 'products.kategori_id')
        ->select('products.*', 'nama_kategori')
        ->orderBy('id')
        ->paginate(10);

        if ($search) $products->appends(['search' => $search]);

        return view('products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dataKategori = Kategori::query()->orderBy('id')->get();
        $kategoris = [
            ['value' => '', 'option' => 'Pilih Kategori :'],
        ];

        foreach ($dataKategori as $kategori) {
            $kategoris[] = ['value' => $kategori->id, 'option' => $kategori->nama_kategori];
        }

        return view('products.create', [
            'kategoris' => $kategoris,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_product' => ['required', 'max:255', 'unique:products,kode_product', 'numeric'],
            'nama_product' => ['required', 'max:255'],
            'harga' => ['required', 'numeric'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
        ], [
            'kode_product.unique' => 'Kode Product sudah tersedia',
            'kode_product.numeric' => 'Kode Product hanya boleh berupa angka',
        ]);

        Product::create($request->all());

        return redirect()->route('product.index')->with('store', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $dataKategori = Kategori::query()->orderBy('id')->get();
        $kategoris = [
            ['value' => '', 'option' => 'Pilih Kategori :'],
        ];

        foreach ($dataKategori as $kategori) {
            $kategoris[] = ['value' => $kategori->id, 'option' => $kategori->nama_kategori];
        }

        return view('products.edit', [
            'kategoris' => $kategoris,
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all());
        $request->validate([
            'kode_product' => ['required', 'max:255', 'unique:products,kode_product,' . $product->id],
            'nama_product' => ['required', 'max:255'],
            'harga' => ['required', 'numeric'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
        ]);

        $product->update($request->all());

        return redirect()->route('product.index')->with('update', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('destroy', 'success');
    }
}

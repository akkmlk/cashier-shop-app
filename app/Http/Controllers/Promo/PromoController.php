<?php

namespace App\Http\Controllers\Promo;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PromoShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $promos = PromoShop::query()->when($search, function($query, $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })
        ->orderBy('id', 'asc')
        ->paginate(5);

        return view('promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::query()->where('promo_shop_id', null)->get();

        $typePromo = [
            ['value' => '', 'option' => 'Pilih type promo'],
            ['value' => 'buyget', 'option' => 'buyget (Buy ... Get ...)'],
            ['value' => 'discount', 'option' => 'discount (Produk Sedang Diskon)'],
        ];

        return view('promos.create', compact('products', 'typePromo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'type' => ['required'],
            'description' => ['required', 'min:3', 'max:255'],
            'buy' => ['nullable', 'numeric'],
            'get' => ['nullable', 'numeric'],
            // 'start' => ['required', 'date'],
            // 'end' => ['required', 'date'],
            'discount' => ['nullable', 'numeric'],
            'products' => ['nullable'],
        ]);
        
        if ($request->products == null) {
            PromoShop::create($request->all());
        } else {
            DB::transaction(function() use($request) {
                $createPromo = PromoShop::create($request->all());
    
                $products = array_map('intval', explode(',', $request->products));
                foreach ($products as $id) {
                    $product = Product::find($id);
                    $product->update([
                        'promo_shop_id' => $createPromo->id,
                    ]);
                    Log::info($product);
                }
            });
        }
        return redirect()->route('promo.index')->with('store', 'success');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PromoShop $promo)
    {
        $products = Product::query()->where('promo_shop_id', null)
            ->orWhere('promo_shop_id', $promo->id)
            ->get();
        $selectedProducts = $promo->products;
        $arraySelectedNameProducts = [];
        $arraySelectedIdProducts = [];
        foreach ($selectedProducts as $selected) {
            $arraySelectedIdProducts[] = $selected->id;
            $arraySelectedNameProducts[] = $selected->nama_product;
        }
        $stringSelectedIdProducts = implode(", ", $arraySelectedIdProducts);
        $stringSelectedNameProducts = implode(", ", $arraySelectedNameProducts);
        
        $typePromo = [
            ['value' => '', 'option' => 'Pilih type promo'],
            ['value' => 'buyget', 'option' => 'buyget (Buy ... Get ...)'],
            ['value' => 'discount', 'option' => 'discount (Produk Sedang Diskon)'],
        ];
        
        $status = [
            ['value' => '', 'option' => 'Atur Status Promo'],
            ['value' => '1', 'option' => 'Aktif'],
            ['value' => '0', 'option' => 'Tidak Aktif'],
        ];

        return view('promos.edit', compact('promo', 'products', 'stringSelectedIdProducts', 'stringSelectedNameProducts', 'typePromo', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromoShop $promo)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'type' => ['required'],
            'description' => ['required', 'min:3', 'max:255'],
            'buy' => ['nullable', 'numeric'],
            'get' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'products' => ['nullable'],
        ]);
        // dd($request->all());

        DB::transaction(function() use($request, $promo) {
            $products = array_map('intval', explode(',', $request->products));
            foreach ($products as $id) {
                $product = Product::find($id);
                if ($product) {
                    $product->update([
                        'promo_shop_id' => $promo->id,
                    ]);
                    Log::info($product);
                }
            }
            $promo->update($request->all());
        });
        return redirect()->route('promo.index')->with('update', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoShop $promo)
    {
        $promo->delete();
        return back()->with('destroy', 'success');
    }
}

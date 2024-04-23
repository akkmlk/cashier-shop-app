<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jackiedo\Cart\Facades\Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::name($request->user()->id);

        $cart->applyTax([
            'id' => 1,
            'rate' => 15,
            'title' => 'Pajak PPN 15%',
        ]);

        $cartDetails = $cart->getDetails();
        Log::info($cartDetails);
        Log::info($cartDetails->get('items'));
        $subTotalAllItem = 0;
        $pajakTotal = 0;
        foreach ($cartDetails->get('items') as $value) {
            $hash = $value->get('hash');
            $item = $cart->getItem($hash);

            $product = Product::find($item->get('id'));
            $discount = $item->get('extra_info.discount') / 100 * $product->harga;
            $discountTotal = $discount * $item->getQuantity();
            Log::info("Total discount : " . $discount * $discountTotal);

            $subTotalItem = $item->get('extra_info.sub_total');
            $subTotalAllItem = $subTotalAllItem + $subTotalItem;
            $pajakTotal = $pajakTotal + $item->get('extra_info.pajak');
            
            Log::info($value);
            Log::info($subTotalItem);
            // Log::info($value->get('extra_info.sub_total'));
        }
        Log::info("Sub Total semua item : " . $subTotalAllItem);
        Log::info("Total Pajak : " . $pajakTotal);
        Log::info('Price total + pajak : ' . $subTotalAllItem + $pajakTotal);


        $cartJson = $cart->getDetails();
        $cartJson['subTotalAllItem'] = $subTotalAllItem;
        $cartJson['pajakTotal'] = $pajakTotal;
        $cartJson['priceTotal'] = $subTotalAllItem + $pajakTotal;
        Log::info($cartJson);
        return $cartJson;
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_product' => ['required', 'exists:products'],
        ]);

        $product = Product::query()->where('kode_product', $request->kode_product)->first();
        $promoProduct = $product->promoShop;
        $pajak = (15 / 100) * $product->harga;

        $cart = Cart::name($request->user()->id);

        // $cartDetails = $cart->getDetails();
        // Log::info($cartDetails->get('items'));
        // foreach ($cartDetails->get('items') as $value) {
        //     $subTotalItem = $value->get('extra_info.sub_total');
        //     Log::info($value);
        // }

        if ($product->promo_shop_id == null) {
            $cart->addItem([
                'id' => $product->id,
                'title' => $product->nama_product,
                'quantity' => 1,
                'price' => intval($product->harga),
                'extra_info' => [
                    'promo_name' => null,
                    'buy' => null,
                    'get' => null,
                    'discount' => null,
                    'pajak' => $pajak,
                    'sub_total' => $product->harga,
                ]
            ]);
        } else {
            if ($promoProduct->active == 0) {
                $cart->addItem([
                    'id' => $product->id,
                    'title' => $product->nama_product,
                    'quantity' => 1,
                    'price' => intval($product->harga),
                    'extra_info' => [
                        'promo_name' => null,
                        'buy' => null,
                        'get' => null,
                        'discount' => null,
                        'pajak' => $pajak,
                        'sub_total' => $product->harga,
                    ]
                ]);
            } else {
                if ($promoProduct->type == "discount") {
                    $discount = ($promoProduct->discount / 100) * $product->harga;
                    $priceDiscount = $product->harga - $discount;
        
                    $cart->addItem([
                        'id' => $product->id,
                        'title' => $product->nama_product,
                        'quantity' => 1,
                        'price' => intval($product->harga),
                        'buy' => $promoProduct->buy,
                        'extra_info' => [
                            'promo_name' => $promoProduct->name,
                            'buy' => $promoProduct->buy,
                            'get' => $promoProduct->get,
                            'discount' => $promoProduct->discount,
                            'pajak' => $pajak,
                            'sub_total' => $priceDiscount,
                        ]
                    ]);
                } elseif ($promoProduct->type == 'buyget') {
                    $cart->addItem([
                        'id' => $product->id,
                        'title' => $product->nama_product,
                        'quantity' => 1,
                        'price' => intval($product->harga),
                        'buy' => $promoProduct->buy,
                        'extra_info' => [
                            'promo_name' => $promoProduct->name,
                            'buy' => $promoProduct->buy,
                            'get' => $promoProduct->get,
                            'discount' => $promoProduct->discount,
                            'price_cut' => '0',
                            'pajak' => $pajak,
                            'sub_total' => $product->harga,
                        ]
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Berhasil Ditambahkan.']);
    }

    public function update(Request $request, $hash)
    {
        $request->validate([
            'qty' => ['required']
        ]);
        
        $cart = Cart::name($request->user()->id);
        $item = $cart->getItem($hash);
        $cartDetails = $cart->getDetails();
        
        $product = Product::find($item->get('id'));
        $promoProduct = $product->promoShop;
        $pajak = (15 / 100) * ($product->harga * $request->qty);

        if (!$item) {
            return abort(404);
        }
        
        Log::info($item->getQuantity());
        if ($request->qty == -1) {
            if ($item->getQuantity() == 1) {
                $cart->removeItem($hash);
            } else {
                $cart->updateItem($item->getHash(), [
                    'quantity' => $item->getQuantity() + $request->qty,
                ]);
            }
        } else {
            $cart->updateItem($item->getHash(), [
                'quantity' => $item->getQuantity() + $request->qty,
            ]);
        }

        if ($cart->hasItem($hash)) {
            if ($product->promo_shop_id == null) {
                if ($request->qty == -1) {
                    $pajak = $pajak * -1;
                    $cart->updateItem($item->getHash(), [
                        'extra_info' => [
                            'pajak' => $item->get('extra_info.pajak') - $pajak,
                            'sub_total' => $item->get('extra_info.sub_total') - $product->harga,
                        ]
                    ]);
                } elseif ($request->qty == 1) {
                    $cart->updateItem($item->getHash(), [
                        'extra_info' => [
                            'pajak' => $item->get('extra_info.pajak') + $pajak,
                            'sub_total' => $item->get('extra_info.sub_total') + $product->harga,
                        ]
                    ]);
                } else {
                    $cart->updateItem($item->getHash(), [
                        'extra_info' => [
                            'pajak' => $item->get('extra_info.pajak') + $pajak,
                            'sub_total' => $item->get('extra_info.sub_total') + $product->harga * $request->qty,
                        ]
                    ]);
                }
            }  else {
                if ($promoProduct->active == 0) {
                    if ($request->qty == -1) {
                        $pajak = $pajak * -1;
                        $cart->updateItem($item->getHash(), [
                            'extra_info' => [
                                'pajak' => $item->get('extra_info.pajak') - $pajak,
                                'sub_total' => $item->get('extra_info.sub_total') - $product->harga,
                            ]
                        ]);
                    } elseif ($request->qty == 1) {
                        $cart->updateItem($item->getHash(), [
                            'extra_info' => [
                                'pajak' => $item->get('extra_info.pajak') + $pajak,
                                'sub_total' => $item->get('extra_info.sub_total') + $product->harga,
                            ]
                        ]);
                    } else {
                        $cart->updateItem($item->getHash(), [
                            'extra_info' => [
                                'pajak' => $item->get('extra_info.pajak') + $pajak,
                                'sub_total' => $item->get('extra_info.sub_total') + $product->harga * $request->qty,
                            ]
                        ]);
                    }
                } else {
                    if ($promoProduct->type == "buyget") {
                        if ($request->qty == -1) {
                            $pajak = $pajak * -1;
                            $cart->updateItem($item->getHash(), [
                                'extra_info' => [
                                    'pajak' => $item->get('extra_info.pajak') - $pajak,
                                    'sub_total' => $item->get('extra_info.sub_total') - ($product->harga),
                                ]
                            ]);
            
                            if ($item->get('quantity') >= $promoProduct->buy && $item->get('quantity') < $promoProduct->buy + $promoProduct->get) {
                                $cart->updateItem($item->getHash(), [
                                    'extra_info' => [
                                        'pajak' => $item->get('extra_info.pajak') + $pajak,
                                        'sub_total' => $item->get('extra_info.sub_total') + ($product->harga),
                                    ]
                                ]);
                            } 
                        } elseif ($request->qty == 1) {
                            $cart->updateItem($item->getHash(), [
                                'extra_info' => [
                                    'pajak' => $item->get('extra_info.pajak') + $pajak,
                                    'sub_total' => $item->get('extra_info.sub_total') + $product->harga,
                                    // 'sub_total' => $item->get('extra_info.sub_total') + ($product->harga + $pajak),
                                ] 
                            ]);
        
                        } else {
                            $cart->updateItem($item->getHash(), [
                                'extra_info' => [
                                    'pajak' => $item->get('extra_info.pajak') + $pajak,
                                    'sub_total' => $item->get('extra_info.sub_total') + ($product->harga * $request->qty),
                                ]
                            ]);
        
                            Log::info("Ini qty sebelumnya : " . $item->getQuantity() - $request->qty);
                            Log::info("Ini qty sekarang : " . $item->getQuantity());
                            $previousQty = $item->getQuantity() - $request->qty;
                            if ($previousQty < $promoProduct->buy && $item->getQuantity() > $promoProduct->buy) {
                                // $oprMinFordinamisCondition = $item->getQuantity() - $promoProduct->buy;
                                // if ($item->getQuantity() - $oprMinFordinamisCondition == $promoProduct->buy) {
                                    $pajakPromoProduct = (15 / 100) * ($product->harga * $promoProduct->get);
                                    $cart->updateItem($item->getHash(), [
                                        'quantity' => $item->getQuantity() + $promoProduct->get,
                                        // 'extra_info' => [
                                        //     'pajak' => $item->get('extra_info.pajak') - $pajakPromoProduct,
                                        //     'sub_total' => $item->get('extra_info.sub_total') - ($product->harga * $promoProduct->get),
                                        // ]
                                    ]);
                                // }
                            }
        
                        }
                        // Subtotal ketika mendapatkan buy ... get .. tidak perlu diisi, karena sudah diatur oleh code diatasnya.
                        // (Juga karenasaat mendapat qty berapapun, qty tersebut bernilai Rp.0 karena gratis setelah membeli sejumlah qty yang ditentukan).
                        if ($item->get('quantity') == $promoProduct->buy) {
                            $cart->updateItem($item->getHash(), [
                                'quantity' => $item->getQuantity() + $promoProduct->get,
                                'extra_info' => [
                                    'price_cut' => $product->harga,
                                    // 'sub_total' => $item->get('extra_info.sub_total') + $product->harga,
                                ]
                            ]);
                        } 
                    } elseif ($promoProduct->type == "discount") {
                        $discount = ($promoProduct->discount / 100) * $product->harga;
                        $priceDiscount = $product->harga - $discount;
            
                        if ($request->qty == -1) {
                            $pajak = $pajak * -1;
                            $cart->updateItem($item->getHash(), [
                                'extra_info' => [
                                    'pajak' => $item->get('extra_info.pajak') - $pajak,
                                    'sub_total' => $item->get('extra_info.sub_total') - $priceDiscount,
                                    // 'sub_total' => $item->get('extra_info.sub_total') - ($priceDiscount + $pajak),
                                ]
                            ]);
                        } elseif ($request->qty == 1) {
                            $cart->updateItem($item->getHash(), [
                                'extra_info' => [
                                    'pajak' => $item->get('extra_info.pajak') + $pajak,
                                    'sub_total' => $item->get('extra_info.sub_total') + $priceDiscount,
                                    // 'sub_total' => $item->get('extra_info.sub_total') + ($priceDiscount + $pajak),
                                ]
                            ]);
                        } else {
                            $cart->updateItem($item->getHash(), [
                                'extra_info' => [
                                    'pajak' => $item->get('extra_info.pajak') + $pajak,
                                    'sub_total' => $item->get('extra_info.sub_total') + $priceDiscount * $request->qty,
                                ]
                            ]);
                        }
                        // elseif ($promoProduct->type == "buydisc") {
                        //     $discount = ($promoProduct->discount / 100) * $product->harga;
                        //     $priceDiscount = $product->harga - $discount;
                        //     if ($request->qty == -1 ) {
                        //         Log::info( "Ini qty : " . $item->getQuantity());
                        //         $pajak = $pajak * -1;
                        //         $previousQtyMin = $item->getQuantity() + ($request->qty * -1);
                        //         if ($item->getQuantity() >= $promoProduct->buy || $previousQtyMin == $promoProduct->buy) {
                        //             $cart->updateItem($item->getHash(), [
                        //                 'extra_info' => [
                        //                     'pajak' => $item->get('extra_info.pajak') - $pajak,
                        //                     'sub_total' => $item->get('extra_info.sub_total') - $priceDiscount,
                        //                 ]
                        //             ]);
                        //         } else {
                        //             $cart->updateItem($item->getHash(), [
                        //                 'extra_info'=> [
                        //                     'pajak' => $item->get('extra_info.pajak') - $pajak,
                        //                     'sub_total' => $item->get('extra_info.sub_total') - $product->harga,
                        //                 ]
                        //             ]);
                        //         }
                        //     } elseif ($request->qty == 1) {
                        //         Log::info( "Ini qty : " . $item->getQuantity());
                        //         if ($item->getQuantity() >= $promoProduct->buy) {
                        //             $cart->updateItem($item->getHash(), [
                        //                 'extra_info' => [
                        //                     'pajak' => $item->get('extra_info.pajak') + $pajak,
                        //                     'sub_total' => $item->get('extra_info.sub_total') + $priceDiscount,
                        //                 ]
                        //             ]);
                        //         } else {
                        //             $cart->updateItem($item->getHash(), [
                        //                 'extra_info' => [
                        //                     'pajak' => $item->get('extra_info.pajak') + $pajak,
                        //                     'sub_total' => $item->get('extra_info.sub_total') + $product->harga,
                        //                 ]
                        //             ]);
                        //         }
                        //     } else {
                        //         $previousQty = $item->getQuantity() - $request->qty;
                        //         Log::info("Ini Qty sebelumnya : " . $previousQty);

                        //         if ($previousQty >= $promoProduct->buy) {
                        //             $cart->updateItem($item->getHash(), [
                        //                 'extra_info' => [
                        //                     'pajak' => $item->get('extra_info.pajak') + $pajak,
                        //                     'sub_total' => $item->get('extra_info.sub_total') + ($priceDiscount * $request->qty),
                        //                 ]
                        //             ]);
                        //         } else {
                        //             // $differenceQty = $promoProduct->buy - $previousQty;
                        //             $differenceQty = $item->getQuantity() - $previousQty;
                        //             $qtyUnderPromo = $promoProduct->buy - ($previousQty + 1);
                        //             $qtyOnPromo = $differenceQty - $qtyUnderPromo;
                        //             Log::info("ini difference qty : " . $differenceQty);
                        //             // Log::info("nyoba hehe : " . $priceDiscount * $differenceQty + $product->harga);
                        //             Log::info("nyoba hehe : " .  $item->get('extra_info.sub_total') + $priceDiscount * $differenceQty);
                        //             Log::info("pajak hehe : " . $pajak);
                        //             // $productPriceUnderPromo = $product->harga * $differenceQty;
                        //             $cart->updateItem($item->getHash(), [
                        //                 'extra_info' => [
                        //                     'pajak' => $item->get('extra_info.pajak') + $pajak,
                        //                     // 'sub_total' => $item->get('extra_info.sub_total') + $priceDiscount * $differenceQty,
                        //                     'sub_total' => $item->get('extra_info.sub_total') + ($product->harga * $qtyUnderPromo) + ($priceDiscount * $qtyOnPromo),
                        //                 ]
                        //             ]);
                        //         }
                        //     }
                        // }
                    } 
                }
            }
        } else {
            Log::error("item notfound");
        }

        return response()->json(['message' => 'Berhasil diupdate.']);
    }

    public function destroy(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $cart->removeItem($hash);
        return response()->json(['message' => 'Berhasil dihapus.']);
    }

    public function clear(Request $request)
    {
        $cart = Cart::name($request->user()->id);
        $cart->destroy();
        return back();
    }
}

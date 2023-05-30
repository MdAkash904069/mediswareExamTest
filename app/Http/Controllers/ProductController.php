<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data['title'] = $title = $request->title;
        $data['price_from'] = $price_from = $request->price_from;
        $data['price_to'] = $price_to = $request->price_to;
        $data['date'] = $date = $request->date;
        $data['variant'] = $variantData = $request->variant;

        $data['variants'] = $variants = Variant::all();
        foreach($variants as $variant){
            $variant->variantProducts = ProductVariant::where('variant_id', $variant->id)
                ->groupBy('product_variants.variant')
                ->select('product_variants.variant')
                ->get();
        }

        $data['products'] = $products = Product::where(function ($query) use ($title){
                $query->orWhere('products.title', 'like', '%'.$title.'%');
            })
            ->where(function ($query) use ($date){
                if($date){
                    $query->whereDate('products.created_at', '=', date('Y-m-d', strtotime($date)));
                }
            })->paginate(2);

        foreach($products as $product){
            $product->variants = ProductVariantPrice::where('product_variant_prices.product_id', $product->id)
                ->leftJoin('product_variants as a', 'a.id', '=', 'product_variant_prices.product_variant_one')
                ->leftJoin('product_variants as b', 'b.id', '=', 'product_variant_prices.product_variant_two')
                ->leftJoin('product_variants as c', 'c.id', '=', 'product_variant_prices.product_variant_three')
                ->where(function ($query) use ($price_from){
                    if($price_from){
                        $query->orWhere('product_variant_prices.price', '>=', $price_from);
                    }
                })
                ->where(function ($query) use ($price_to){
                    if($price_to){
                        $query->orWhere('product_variant_prices.price', '<=', $price_to);
                    }
                })
                ->where(function ($query) use ($variantData){
                    if($variantData != ''){
                        $query->orWhere('a.variant', 'like', '%'.$variantData.'%')
                                ->orWhere('b.variant', 'like', '%'.$variantData.'%')
                                ->orWhere('c.variant', 'like', '%'.$variantData.'%');
                    }
                })
                ->select('product_variant_prices.id','product_variant_prices.stock','product_variant_prices.price', 'a.variant as variant_a', 'b.variant as variant_b', 'c.variant as variant_c')
                ->get();
        }


        return view('products.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        dd($request->all());
        $product = new Product();
        $product->fill($request->all());
        $product->save();  
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}

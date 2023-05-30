<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariantPrice;
use App\Models\Variant;

class ProductVariant extends Model
{
    // public function productVariantPrice()
    // {
    //     return $this->belongsTo(ProductVariantPrice::class, 'id');
    // }
    public function variant()
    {
        return $this->belongsTo(Variant::class, 'id');
    }
}

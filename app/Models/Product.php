<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'Product';
    protected $primaryKey = 'IDProduct';

    protected $guarded = [];
    public $timestamps = false;

    public static function getProductDetailByID(int $IDProduct)
    {
        //get product
        $product = Product::find($IDProduct);
        if ($product != null) {
            //get the product current retail price
            $product->RetailPrice = RetailPrice::showCurrent($IDProduct) ?? $product->ListPrice;
            //get the product brand
            $product->Brand = Brand::find($product->IDBrand);
            //get the product images
            $product->Images = ProductImage::getAllImagesOfProduct($IDProduct);

            //get the product avg rating
            $product->Rating = Review::getAvgRatingOfProduct($IDProduct) ?? 0;

            //get the product reviews
            $product->Reviews = Review::getAllReviewsOfProduct($IDProduct) ?? [];
        }
        return $product;
    }
}

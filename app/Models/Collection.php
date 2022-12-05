<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CollectionProduct;

class Collection extends Model
{
    use HasFactory;
    protected $table = 'Collection';
    protected $primaryKey = 'IDCollection';
    protected $guarded = [];
    public $timestamps = false;

    public static function getAllProductsOfCollection(int $IDCollection)
    {
        //only get product is not deleted
        return CollectionProduct::where('IDCollection', $IDCollection)->join('Product', 'IDCollection.IDProduct', '=', 'Product.IDProduct')->get();
    }

    public static function getCollectionDetailByID(int $IDCollection)
    {

        $collection = Collection::find($IDCollection);
        if ($collection != null) {
            $productsInCol = [];
            //get the collection products
            $productsInCol_temp = CollectionProduct::where('IDCollection', $IDCollection)->get();
            foreach ($productsInCol_temp as $product) {
                $productDetail = Product::getProductDetailByID($product->IDProduct);
                //remove the product is deleted
                if ($productDetail != null && $productDetail->isDeleted == false)
                    array_push($productsInCol, $productDetail);
            }
            $collection->Products = $productsInCol ?? [];
        }
        return $collection;
    }
}

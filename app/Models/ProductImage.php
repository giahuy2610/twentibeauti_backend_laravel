<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'ProductImage';
    protected $primaryKey = 'IDProductImage';
    protected $guarded = [];
    public $timestamps = false;

    public static function getAllImagesOfProduct(int $IDProduct)
    {
        return (ProductImage::select('Path')->where('IDProduct', $IDProduct)->get());
    }
}

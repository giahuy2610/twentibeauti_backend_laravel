<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'Review';
    protected $primaryKey = 'IDReview';
    protected $guarded = [];

    public static function getAllReviewsOfProduct(int $IDProduct)
    {
        $reviews = Review::where('IDProduct', $IDProduct)->where('IsDeleted', 0)->get();
        foreach ($reviews  as $review) {
            $review->Images = ReviewImage::where('IdReview', $review->IDReview)->get();
        }
        return ($reviews);
    }

    public static function getAvgRatingOfProduct(int $IDProduct)
    {
        return (Review::where('IDProduct', $IDProduct)->where('IsDeleted', 0)->get()->avg('Rating'));
    }
}

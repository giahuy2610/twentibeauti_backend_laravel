<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    use HasFactory;
    protected $table = 'ReviewImage';
    protected $primaryKey = 'IDReviewImage';
    protected $guarded = [];
}

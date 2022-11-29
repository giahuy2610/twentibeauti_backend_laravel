<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'Review';
    protected $primaryKey = 'IDReview';

    public $timestamps = false;
    use HasFactory;
}

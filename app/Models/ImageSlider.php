<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageSlider extends Model
{
    use HasFactory;
    protected $table = 'ImageSlider';
    protected $primaryKey = 'IDImage';
    protected $guarded = [];
    public $timestamps = false;
}

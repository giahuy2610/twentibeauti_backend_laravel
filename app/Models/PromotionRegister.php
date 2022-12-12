<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionRegister extends Model
{
    use HasFactory;

    protected $table = 'PromotionRegister';
    protected $primaryKey = 'IDRegister';
    protected $guarded = [];
    public $timestamps = false;
}

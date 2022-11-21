<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'Coupon';
    protected $primaryKey = 'IDCoupon';
    protected $guarded = [];
    public $timestamps = false;
}

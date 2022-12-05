<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'Cart';
    protected $primaryKeys = ['IDCus', 'IDProduct'];
    protected $guarded = [];
    public $timestamps = false;
}

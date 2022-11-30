<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailPrice extends Model
{
    use HasFactory;
    protected $table = 'RetailPrice';
    protected $primaryKey = 'IDRetailPrice';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduct extends Model
{
    use HasFactory;
    protected $table = 'TypeProduct';
    protected $primaryKey = 'IDType';
    protected $guarded = [];
    public $timestamps = false;
}

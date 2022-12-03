<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionProduct extends Model
{
    use HasFactory;
    protected $table = 'CollectionProduct';
    protected $primaryKey = 'IDCollection';
    protected $guarded = [];
    public $timestamps = false;
}

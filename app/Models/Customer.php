<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'Customer';
    protected $primaryKey = 'IDCus';
    protected $guarded = [];
    public $timestamps = false;

    // public function create() {
    //     //a
    // }

    //public function update() {}
}
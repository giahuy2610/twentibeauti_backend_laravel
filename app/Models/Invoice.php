<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'Invoice';
    protected $primaryKey = 'IDInvoice';
    protected $guarded = [];
    public $timestamps = false;
}

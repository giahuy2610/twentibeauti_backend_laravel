<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $table = 'InvoiceDetail';
    protected $primaryKey = ['IDInvoice','IDProduct'];
    protected $guarded = [];
    public $timestamps = false;
}

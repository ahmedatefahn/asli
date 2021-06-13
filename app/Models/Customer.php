<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['telephone'];

    public function Barcode()
    {
     
        return $this->belongsToMany(Barcode::class, 'customer_barcodes','customer_id','barcode_id');
        
    }
}

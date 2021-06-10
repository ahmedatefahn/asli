<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['brand_id','name','photo','description','website_url','features'];
    public function Brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function Barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}

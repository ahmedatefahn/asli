<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'scanned_by') ?? 'undefined';
    }

    /**
     * @param $creationDate
     * @return mixed
     */
    public function getCreatedAtAttribute($creationDate)
    {
        return $this->custom_creation_date ? $this->custom_creation_date : $creationDate;
    }
}

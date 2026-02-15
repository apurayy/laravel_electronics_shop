<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'street_address',
        'city',
        'postal_code',
        'country',
    ];

    // Define the relationship with the Order model
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

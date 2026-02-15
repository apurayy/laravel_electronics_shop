<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_price',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'order_notes',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the OrderItem model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Define the relationship with the Address model
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}

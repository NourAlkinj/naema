<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'meal_id',
        'restaurant_id',
        'OrderStatus_id',
        'quantity',
        'total_price'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'OrderStatus_id');
    }
}

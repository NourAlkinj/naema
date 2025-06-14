<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    protected $fillable = [
        'meal_name',
        'restaurant_id',
        'images',
        'quantity',
        'created_date',
        'expire_date',
        'price',
        
    ];
    protected $casts = [
        'images' => 'array',  
        'created_date' => 'datetime',
        'expire_date' => 'datetime',
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'meal_id'); 
    }
}

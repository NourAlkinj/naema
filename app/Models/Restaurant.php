<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Restaurant extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'avatar',
        'location',
        'lang',
        'lat',
        'brief',
        'images',
    ];
    protected $hidden = [
        'password',
    ];
    protected function casts(): array
    {
        return [
            'images' => 'array',  
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function meals()
    {
        return $this->hasMany(Meal::class, 'restaurant_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'restaurant_id');
    }
}
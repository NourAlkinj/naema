<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meal1 = Meal::create([
            'meal_name'=> 'شاورما',
            'restaurant_id'=>1,
            'quantity'=>10,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'1000',
        ]);
        $meal2 = Meal::create([
            'meal_name'=> 'شاورما',
            'restaurant_id'=>2,
            'quantity'=>8,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'1100',
        ]);
        $meal3 = Meal::create([
            'meal_name'=> 'زينجر',
            'restaurant_id'=>1,
            'quantity'=>5,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'2000',
        ]);
        $meal4 = Meal::create([
            'meal_name'=> 'كريسبي',
            'restaurant_id'=>1,
            'quantity'=>3,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'2500',
        ]);
        $meal5 = Meal::create([
            'meal_name'=> 'بطاطا',
            'restaurant_id'=>3,
            'quantity'=>5,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'500',
        ]);
        $meal6 = Meal::create([
            'meal_name'=> 'زينجر',
            'restaurant_id'=>2,
            'quantity'=>6,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'1800',
        ]);
        $meal7 = Meal::create([
            'meal_name'=> 'بطاطا وجبنه',
            'restaurant_id'=>3,
            'quantity'=> 4,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'1500',
        ]);
        $meal8 = Meal::create([
            'meal_name'=> 'سكالوب',
            'restaurant_id'=>3,
            'quantity'=> 2,
            'created_date' => now(),
            'expire_date' => now()->addDays(3),
            'price'=>'2500',
        ]);
    }
}

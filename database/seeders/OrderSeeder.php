<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Meal;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
   public function run(): void
   {
   $meal1=Meal::find(1);
   $meal2=Meal::find(2);
   $meal3=Meal::find(3);
   $meal4=Meal::find(4);
   $meal5=Meal::find(5);
   $meal6=Meal::find(6);
   $meal7=Meal::find(7);

    $Order1 = Order::create([
      'user_id' => 1,
      'meal_id' => 1,
      'restaurant_id' => 1,
      'OrderStatus_id' => 1,
      'quantity' => 3,
      'total_price'=> ($meal1-> price )* 3,
     ]);
     $Order2 = Order::create([
      'user_id' => 1,
      'meal_id' => 2,
      'restaurant_id' => 2,
      'OrderStatus_id' => 1,
      'quantity' => 6,
      'total_price'=> ($meal2-> price )* 6,
     ]);
     $Order3 = Order::create([
      'user_id' => 1,
      'meal_id' => 6,
      'restaurant_id' => 2,
      'OrderStatus_id' => 1,
      'quantity' => 5,
      'total_price'=> ($meal6-> price )* 5,
     ]);
     $Order4 = Order::create([
      'user_id' => 2,
      'meal_id' => 5,
      'restaurant_id' => 3,
      'OrderStatus_id' => 1,
      'quantity' => 2,
      'total_price'=> ($meal5-> price )* 2,
     ]);
     $Order5 = Order::create([
      'user_id' => 2,
      'meal_id' => 7,
      'restaurant_id' => 3,
      'OrderStatus_id' => 1,
      'quantity' => 2,
      'total_price'=> ($meal7-> price )* 2,
     ]);
     $Order6 = Order::create([
      'user_id' => 3,
      'meal_id' => 4,
      'restaurant_id' => 1,
      'OrderStatus_id' => 2,
      'quantity' => 3,
      'total_price'=> ($meal4-> price )* 3,
     ]);

     $Order7 = Order::create([
      'user_id' => 1,
      'meal_id' => 4,
      'restaurant_id' => 1,
      'OrderStatus_id' => 4,
      'quantity' => 2,
      'total_price'=> ($meal4-> price )* 2,
     ]);
     $Order8 = Order::create([
      'user_id' => 1,
      'meal_id' => 3,
      'restaurant_id' => 1,
      'OrderStatus_id' => 4,
      'quantity' => 2,
      'total_price'=> ($meal3-> price )* 2,
     ]);
     $Order9 = Order::create([
      'user_id' => 1,
      'meal_id' => 7,
      'restaurant_id' => 3,
      'OrderStatus_id' => 4,
      'quantity' => 3,
      'total_price'=> ($meal7-> price )* 3,
     ]);
     $Order10 = Order::create([
      'user_id' => 1,
      'meal_id' => 2,
      'restaurant_id' => 2,
      'OrderStatus_id' => 4,
      'quantity' => 2,
      'total_price'=> ($meal2-> price )* 2,
     ]);
     $Order11 = Order::create([
      'user_id' => 2,
      'meal_id' => 5,
      'restaurant_id' => 3,
      'OrderStatus_id' => 3,
      'quantity' => 2,
      'total_price'=> ($meal1-> price )* 2,
     ]);
    
    }
}

<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurant1 = Restaurant::create([
            'name'=> 'دجاجتي',
            'phone_number'=>'3347882',
            'email'=>'دجاجتي@gmail.com',
            'password'=> Hash::make('دجاجتي1'),
            'location'=>'lattakia',
            'brief'=>'أفضل مطعم شاورما في المدينة'
        ]);
        $restaurant2 = Restaurant::create([
            'name'=> 'لفاح',
            'phone_number'=>'356677',
            'email'=>'لفاح@gmail.com',
            'password'=> Hash::make('لفاح1'),
            'location'=>'jableh',
            'brief'=>'سناك عربي غربي'    
          ]);
        $restaurant3 = Restaurant::create([
            'name'=> 'العربي',
            'phone_number'=>'903224',
            'email'=>'العربي@gmail.com',
            'password'=> Hash::make('العربي1'),
            'location'=>'lattakia',
            'brief'=>'أفضل الوجبات الشهية والسريعة'
        ]);
    }
}

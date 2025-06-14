<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Statue1 = OrderStatus::create([
            'status_title'=> 'قيد المعالجة',
        ]);
        $Statue2 = OrderStatus::create([
            'status_title'=> 'قيد التوصيل',
        ]);
        $Statue3 = OrderStatus::create([
           'status_title' => 'تم التوصيل',
          ]);
        $Statue4 = OrderStatus::create([
            'status_title' => 'مرفوض',
          ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $User1 = User::create([
            'name'=> 'costumer1',
            'phone_number'=>'3347882',
            'email'=>'costumer1@gmail.com',
            'password'=> Hash::make('costumer1'),
            'location'=>'lattakia',
        ]);
        $User2 = User::create([
            'name'=> 'costumer2',
            'phone_number'=>'356677',
            'email'=>'costumer2@gmail.com',
            'password'=> Hash::make('costumer2'),
            'location'=>'lattakia',
          ]);
        $User3 = User::create([
            'name'=> 'costumer3',
            'phone_number'=>'903224',
            'email'=>'costumer3@gmail.com',
            'password'=> Hash::make('costumer3'),
            'location'=>'lattakia',
        ]);
        
    }
}

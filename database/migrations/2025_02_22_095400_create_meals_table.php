<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('meal_name');
            $table->unsignedBigInteger('restaurant_id');
            $table->json('images')->nullable();
            $table->integer('quantity')->default(0);
            $table->dateTime('created_date');
            $table->dateTime('expire_date');
            $table->double('price');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};

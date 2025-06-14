<?php

use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('restaurant')->group(function () {
    Route::post('register-restaurant', [RestaurantController::class, 'registerRestaurant'])->name('restaurant.RegisterRestaurant');
    Route::post('login-restaurant', [RestaurantController::class, 'loginRestaurant'])->name('restaurant.LoginRestaurant');
    Route::get('get-restaurant-with-meals/{id}', [RestaurantController::class, 'getRestaurantWithMeals'])->name('restaurant.GetRestaurantWithMeals');
    Route::post('update-restaurant/{id}', [RestaurantController::class, 'updateRestaurant'])->name('restaurant.UpdateRestaurant');

});

Route::prefix('meal')->group(function () {
    Route::get('get-latest-meals', [MealController::class, 'getLatestMeals'])->name('meal.GetLatestMeals');
    Route::get('get-meal/{mealId}', [MealController::class, 'getMealById'])->name('meal.GetMealById');
    Route::post('add-new-meal', [MealController::class, 'addNewMeal'])->name('meal.AddNewMeal');
    Route::get('delete-meal/{mealId}', [MealController::class, 'deleteMeal'])->name('meal.DeleteMeal');
    Route::post('update-meal/{id}', [MealController::class, 'updateMeal'])->name('meal.UpdateMeal');

});

Route::prefix('user')->group(function () {
    Route::get('get-user/{id}', [UserController::class, 'getUserById'])->name('user.GetUserById');
    Route::post('register-user', [UserController::class, 'registerUser'])->name('user.RegisterUser');
    Route::post('login-user', [UserController::class, 'loginUser'])->name('user.LoginUser');
    Route::get('get-all-restaurants', [UserController::class, 'getAllRestaurants'])->name('user.GetAllRestaurants');
    Route::get('get-all-restaurants-with-meals', [UserController::class, 'getAllRestaurantsWithMeals'])->name('user.GetAllRestaurantsWithMeals');
    Route::post('update-user/{id}', [UserController::class, 'updateUser'])->name('meal.UpdateUser');

});

Route::prefix('order')->group(function () {
    Route::get('get-orders-by-userId/{id}', [OrderController::class, 'getOrdersByUserId'])->name('order.GetOrdersByUserId');
    Route::get('delete-order/{id}', [OrderController::class, 'deleteOrder'])->name('order.DeleteOrder');
    Route::get('get-orders-by-restaurantId/{restaurantId}', [OrderController::class, 'getOrdersByRestaurantId'])->name('order.GetOrdersByRestaurantId');
    Route::post('update-order-status', [OrderController::class, 'updateOrderStatus'])->name('order.UpdateOrderStatus');
    Route::get('get-all-orders', [OrderController::class, 'getAllOrders'])->name('order.GetAllOrders');
    Route::post('create-order', [OrderController::class, 'createOrder'])->name('order.CreateOrder');

});



Route::prefix('image')->group(function () {
    Route::post('upload-images', [ImageUploadController::class, 'uploadImages'])->name('image.UploadImages');
    Route::get('get-image-path', [ImageUploadController::class, 'getImagePath'])->name('image.GetImagePath');
});









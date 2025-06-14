<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function getLatestMeals()
    {
        $meals = Meal::orderBy('created_date', 'desc')->get();

        $formattedMeals = $meals->map(function ($meal) {
            return [
                'id' => $meal->id,
                'meal_name' => $meal->meal_name,
                'price' => $meal->price,
                'quantity' => $meal->quantity,
                'images' => $meal->images ? array_map(function ($img) {
                return asset('meals/' . $img);
            }, json_decode($meal->images)) : null,
                'created_date' => $meal->created_date,
                'expire_date' => $meal->expire_date,
                'restaurant' => [
                    'id' => $meal->restaurant->id ?? null,
                    'name' => $meal->restaurant->name ?? null,
                    'location' => $meal->restaurant->location ?? null,
                ],
            ];
        });

        return response()->json($formattedMeals, 200);
    }

    public function getMealById($mealId)
    {
        $meal = Meal::with('restaurant')->find($mealId);

        if (!$meal) {
            return response()->json(['message' => 'Meal not found'], 404);
        }

        return response()->json([
            'id' => $meal->id,
            'meal_name' => $meal->meal_name,
            'price' => $meal->price,
            'quantity' => $meal->quantity,
            'images' => $meal->images ? array_map(function ($img) {
                return asset('meals/' . $img);
            }, json_decode($meal->images)) : null,
            'created_date' => $meal->created_date,
            'expire_date' => $meal->expire_date,
            'restaurant' => [
                'id' => $meal->restaurant->id ?? null,
                'name' => $meal->restaurant->name ?? null,
                'location' => $meal->restaurant->location ?? null,
                'phone_number' => $meal->restaurant->phone_number ?? null,
            ],
        ], 200);
    }

    public function addNewMeal(Request $request)
    {
        $request->validate([
            'meal_name' => 'required|string|max:255',
            'restaurant_id' => 'required|exists:restaurants,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:1',
            'created_date' => 'required|date',
            'expire_date' => 'required|date|after_or_equal:created_date',
            'price' => 'required|numeric|min:0',
        ]);

        $imageNames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('../../public_html/meals'), $imageName);
                $imageNames[] = $imageName;
            }
        }

        $meal = Meal::create([
            'meal_name' => $request->meal_name,
            'restaurant_id' => $request->restaurant_id,
            'quantity' => $request->quantity,
            'created_date' => $request->created_date,
            'expire_date' => $request->expire_date,
            'price' => $request->price,
            'images' => $imageNames ? json_encode($imageNames) : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Meal added successfully',
            'data' => [
                'id' => $meal->id,
                'meal_name' => $meal->meal_name,
                'restaurant_id' => $meal->restaurant_id,
                'quantity' => $meal->quantity,
                'created_date' => $meal->created_date,
                'expire_date' => $meal->expire_date,
                'price' => $meal->price,
                'images' => $imageNames ? array_map(fn($img) => url('meals/' . $img), $imageNames) : null,
            ]
        ], 201);
    }

    public function updateMeal(Request $request, $id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            return response()->json(['message' => 'Meal not found'], 404);
        }

        $request->validate([
            'meal_name' => 'sometimes|string|max:255',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'sometimes|integer|min:1',
            'created_date' => 'sometimes|date',
            'expire_date' => 'sometimes|date|after_or_equal:created_date',
            'price' => 'sometimes|numeric|min:0',
        ]);

        if ($request->has('meal_name')) $meal->meal_name = $request->meal_name;
        if ($request->has('restaurant_id')) $meal->restaurant_id = $request->restaurant_id;
        if ($request->has('quantity')) $meal->quantity = $request->quantity;
        if ($request->has('created_date')) $meal->created_date = $request->created_date;
        if ($request->has('expire_date')) $meal->expire_date = $request->expire_date;
        if ($request->has('price')) $meal->price = $request->price;

        $existingImages = $meal->images ? json_decode($meal->images) : [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('../../public_html/meals'), $imageName);
                $existingImages[] = $imageName;
            }
        }

        $meal->images = $existingImages ? json_encode($existingImages) : null;
        $meal->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Meal updated successfully',
            'data' => [
                'id' => $meal->id,
                'meal_name' => $meal->meal_name,
                'restaurant_id' => $meal->restaurant_id,
                'quantity' => $meal->quantity,
                'created_date' => $meal->created_date,
                'expire_date' => $meal->expire_date,
                'price' => $meal->price,
                'images' => $existingImages ? array_map(fn($img) => url('meals/' . $img), $existingImages) : null,
            ]
        ], 200);
    }

    public function deleteMeal($id)
    {
        $meal = Meal::find($id);
        if (!$meal) {
            return response()->json(['message' => 'Meal not found'], 404);
        }

        if ($meal->images) {
            foreach (json_decode($meal->images) as $img) {
                $imagePath = public_path('../../public_html/meals/' . $img);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        $meal->orders()->delete();
        $meal->delete();

        return response()->json(['message' => 'Meal and its associated orders have been successfully deleted']);
    }
}

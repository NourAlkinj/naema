<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{
    public function registerRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:restaurants,email',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'required|string|unique:restaurants,phone_number',
            'location' => 'required|string',
            'avatar' => 'nullable|string',
            'lang' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'brief' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $uploadPath = base_path('../public_html/restaurants');
                $image->move($uploadPath, $filename);
                $imagePaths[] = 'restaurants/' . $filename;
            }
        }

        $restaurant = Restaurant::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'location' => $request->location,
            'avatar' => $request->avatar,
            'lang' => $request->lang,
            'lat' => $request->lat,
            'brief' => $request->brief,
            'images' => empty($imagePaths) ? null : json_encode($imagePaths, JSON_UNESCAPED_SLASHES),
        ]);

        $responseData = [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'email' => $restaurant->email,
            'phone_number' => $restaurant->phone_number,
            'location' => $restaurant->location,
            'avatar' => $restaurant->avatar,
            'lang' => $restaurant->lang,
            'lat' => $restaurant->lat,
            'brief' => $restaurant->brief,
            'images' => $restaurant->images ? array_map(function ($img) {
                return asset($img);
            }, json_decode($restaurant->images)) : null,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant registered successfully',
            'data' => $responseData
        ], 201);
    }

    public function loginRestaurant(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $restaurant = Restaurant::where('email', $request->email)->first();

        if ($restaurant && Hash::check($request->password, $restaurant->password)) {
            $responseData = [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'email' => $restaurant->email,
                'phone_number' => $restaurant->phone_number,
                'location' => $restaurant->location,
                'avatar' => $restaurant->avatar,
                'lang' => $restaurant->lang,
                'lat' => $restaurant->lat,
                'brief' => $restaurant->brief,
               'images' => $restaurant->images ? array_map(function ($img) {
                  return asset($img);
                  }, json_decode($restaurant->images)) : null,
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Restaurant logged in successfully',
                'data' => $responseData
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function getRestaurantWithMeals($restaurantId)
    {
        $restaurant = Restaurant::with('meals')->find($restaurantId);

        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        $formattedMeals = [];
        foreach ($restaurant->meals as $meal) {
            $formattedMeals[] = [
                'id' => $meal->id,
                'meal_name' => $meal->meal_name,
                'price' => $meal->price,
                'quantity' => $meal->quantity,
                'images' => $meal->images ? array_map(function ($img) {
                return asset('meals/' . $img);
            }, json_decode($meal->images)) : null,
                'created_date' => $meal->created_date,
                'expire_date' => $meal->expire_date,
            ];
        }

        $formattedRestaurant = [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'phone_number' => $restaurant->phone_number,
            'email' => $restaurant->email,
            'avatar' => $restaurant->avatar,
            'location' => $restaurant->location,
            'lang' => $restaurant->lang,
            'lat' => $restaurant->plat,
            'brief' => $restaurant->brief,
            'images' => $restaurant->images ? array_map(function ($img) {
                return asset($img);
            }, json_decode($restaurant->images)) : null,
            'meals' => $formattedMeals,
        ];

        return response()->json($formattedRestaurant, 200);
    }

    public function updateRestaurant(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:restaurants,email,' . $id,
            'password' => 'sometimes|string|min:6|confirmed',
            'phone_number' => 'sometimes|string|unique:restaurants,phone_number,' . $id,
            'location' => 'sometimes|string',
            'avatar' => 'nullable|string',
            'lang' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'brief' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->has('name')) $restaurant->name = $request->name;
        if ($request->has('email')) $restaurant->email = $request->email;
        if ($request->has('password')) $restaurant->password = Hash::make($request->password);
        if ($request->has('phone_number')) $restaurant->phone_number = $request->phone_number;
        if ($request->has('location')) $restaurant->location = $request->location;
        if ($request->has('avatar')) $restaurant->avatar = $request->avatar;
        if ($request->has('lang')) $restaurant->lang = $request->lang;
        if ($request->has('lat')) $restaurant->lat = $request->lat;
        if ($request->has('brief')) $restaurant->brief = $request->brief;

        $imagePaths = $restaurant->images ? json_decode($restaurant->images, true) : [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = 'restaurants/' . $filename;
                if (!in_array($path, $imagePaths)) {
                    $uploadPath = base_path('../public_html/restaurants');
                    $image->move($uploadPath, $filename);
                    $imagePaths[] = $path;
                }
            }
        }

        $restaurant->images = empty($imagePaths) ? null : json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
        $restaurant->save();

        $responseData = [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'email' => $restaurant->email,
            'phone_number' => $restaurant->phone_number,
            'location' => $restaurant->location,
            'avatar' => $restaurant->avatar,
            'lang' => $restaurant->lang,
            'lat' => $restaurant->lat,
            'brief' => $restaurant->brief,
            'images' => $restaurant->images ? array_map(function ($img) {
                return asset($img);
            }, json_decode($restaurant->images)) : null,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant updated successfully',
            'data' => $responseData
        ], 200);
    }
}

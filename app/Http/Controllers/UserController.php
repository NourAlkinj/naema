<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function moveImageToPublicHtml($image)
    {
        $filename = Str::random(10) . '_' . $image->getClientOriginalName();
        $destinationPath = base_path('../public_html/users');
        $image->move($destinationPath, $filename);
        return $filename;
    }

    private function generateImageUrl($filename)
    {
        return 'https://operation4tech.com/users/' . $filename;
    }

    public function getAllRestaurants()
    {
        $allRestaurants = Restaurant::get();
        $formattedAllRestaurants = [];

        foreach ($allRestaurants as $restaurant) {
            $formattedAllRestaurants[] = [
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
            ];
        }

        return response()->json($formattedAllRestaurants, 200);
    }

    public function getAllRestaurantsWithMeals()
    {
        $allRestaurantsWithMeals = Restaurant::with('meals')->get();
        $formattedAllRestaurants = [];

        foreach ($allRestaurantsWithMeals as $restaurant) {
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

            $formattedAllRestaurants[] = [
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
        }

        return response()->json($formattedAllRestaurants, 200);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $formattedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number ?? null,
            'avatar' => $user->avatar ?? null,
            'location' => $user->location,
            'lang' => $user->lang,
            'lat' => $user->lat,
          'images' => $user->images ? array_map(function ($img) {
                return 'https://operation4tech.com/users/' . basename($img);
            }, json_decode($user->images)) : null,
        ];

        return response()->json($formattedUser, 200);
    }

    public function registerUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users,phone_number',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|string',
            'location' => 'nullable|string',
            'lang' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $this->moveImageToPublicHtml($image);
                $imagePaths[] = 'users/' . $filename;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $request->avatar,
            'location' => $request->location,
            'lang' => $request->lang,
            'lat' => $request->lat,
            'images' => empty($imagePaths) ? null : json_encode($imagePaths, JSON_UNESCAPED_SLASHES),
        ]);

        $responseData = [
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'location' => $user->location,
            'lang' => $user->lang,
            'lat' => $user->lat,
          'images' => $user->images ? array_map(function ($img) {
                return 'https://operation4tech.com/users/' . basename($img);
            }, json_decode($user->images)) : null,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => $responseData
        ], 201);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $responseData = [
                'id' => $user->id,
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'email' => $user->email,
                'location' => $user->location,
                'avatar' => $user->avatar,
                'lang' => $user->lang,
                'lat' => $user->lat,
              'images' => $user->images ? array_map(function ($img) {
                    return 'https://operation4tech.com/users/' . basename($img);
                }, json_decode($user->images)) : null,
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'data' => $responseData
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|unique:users,phone_number,' . $id,
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6|confirmed',
            'avatar' => 'nullable|string',
            'location' => 'nullable|string',
            'lang' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        if ($request->has('phone_number')) $user->phone_number = $request->phone_number;
        if ($request->has('avatar')) $user->avatar = $request->avatar;
        if ($request->has('location')) $user->location = $request->location;
        if ($request->has('lang')) $user->lang = $request->lang;
        if ($request->has('lat')) $user->lat = $request->lat;

        $imagePaths = $user->images ? json_decode($user->images, true) : [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $this->moveImageToPublicHtml($image);
                $path = 'users/' . $filename;

                if (!in_array($path, $imagePaths)) {
                    $imagePaths[] = $path;
                }
            }
        }

        $user->images = empty($imagePaths) ? null : json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
        $user->save();

        $responseData = [
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'location' => $user->location,
            'lang' => $user->lang,
            'lat' => $user->lat,
           'images' => $user->images ? array_map(function ($img) {
                return 'https://operation4tech.com/users/' . basename($img);
            }, json_decode($user->images)) : null,
                    ];

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $responseData
        ], 200);
    }
}

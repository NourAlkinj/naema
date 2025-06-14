<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Meal;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrdersByUserId($userId)
    {
        $user = User::with(['orders.meal.restaurant', 'orders.status'])->find($userId);
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'user' => $user->name,
            'orders' => $user->orders->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'status' => $order->status->status_title,
                    'created_at' => $order->created_at->format('Y-m-d '),
                    'meal' => [
                        'meal_id' => $order->meal->id,
                        'meal_name' => $order->meal->meal_name,
                        'price' => $order->meal->price,
                        'quantity' => $order->quantity,
                        'total_price' => $order->total_price,
                        'restaurant' => [
                            'id' => $order->meal->restaurant->id,
                            'name' => $order->meal->restaurant->name,
                            'location' => $order->meal->restaurant->location,
                            'phone_number' => $order->meal->restaurant->phone_number,
                        ]
                    ]
                ];
            })
        ]);
    }
    
public function deleteOrder($orderId)
{
    $order = Order::find($orderId);

    if (!$order) {
        return response()->json([
            'status' => 'error',
            'message' => 'الطلب غير موجود'
        ], 404);
    }
    $order->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'تم حذف الطلب بنجاح'
    ], 200);
}


public function getOrdersByRestaurantId($restaurantId)
{
    $restaurant = Restaurant::find($restaurantId);

    if (!$restaurant) {
        return response()->json([
            'status' => 'error',
            'message' => 'المطعم غير موجود'
        ], 404);
    }

   
    $orders = Order::where('restaurant_id', $restaurantId)
        ->with([
            'user',       
            'status',    
            'meal'        
        ])->get();

    if ($orders->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'لا توجد طلبات لهذا المطعم'
        ], 404);
    }

   
    $response = [
        'status' => 'success',
        'restaurant' => [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'phone_number' => $restaurant->phone_number,
            'email' => $restaurant->email,
            'avatar' => $restaurant->avatar,
            'location' => $restaurant->location,
            'latitude' => $restaurant->lat,
            'longitude' => $restaurant->lang,
            'brief' => $restaurant->brief,
        ],
        'orders' => $orders->map(function ($order) {
            return [
                'orderId' => $order->id,
                'user' => [
                    'userId' => $order->user->id,
                    'userName' => $order->user->name,
                    'userPhone_number' => $order->user->phone_number,
                    'userEmail' => $order->user->email,
                    'userLocation' => $order->user->location,
                ],
                'status' => [
                    'statusId' => $order->status->id,
                    'statusTitle' => $order->status->status_title,
                ],
                'total_price' => $order->total_price,
                'orderCreated_at' => $order->created_at->format('Y-m-d '),
                'orderMeal' => [
                    'mealId' => $order->meal->id,
                    'mealName' => $order->meal->meal_name,
                    'mealPrice' => $order->meal->price,
                    'mealQuantity' => $order->quantity,
                    'mealTotalPrice' => $order->total_price,
                    'mealImages' =>$order->meal->images ? array_map(function ($img) {return url('storage/' . $img);}, json_decode($order->meal->images)) : null ,

                ]
            ];
        })
    ];

    return response()->json($response, 200);
}

public function updateOrderStatus(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'status_id' => 'required|exists:order_status,id',
    ]);

    $order = Order::find($request->order_id);
    $order->OrderStatus_id = $request->status_id;
    $order->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Order status updated successfully',
        'data' => [
            'order_id' => $order->id,
            'new_status' => [
                'status_id' => $order->status->id,
                'status_title' => $order->status->status_title
            ]
        ]
    ], 200);
}

public function getAllOrders()
{
   
    $orders = Order::with([
        'user',              
        'meal.restaurant',   
        'status'              
    ])->get();

   
    if ($orders->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'لا توجد طلبات'
        ], 404);
    }

   
    $response = [
        'status' => 'success',
        'orders' => $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'user' => [
                    'user_id' => $order->user->id,
                    'user_name' => $order->user->name,
                    'user_phone_number' => $order->user->phone_number,
                    'user_email' => $order->user->email,
                    'user_location' => $order->user->location,
                ],
                'status' => [
                    'status_id' => $order->status->id,
                    'status_title' => $order->status->status_title,
                ],
                'total_price' => $order->total_price,  // إجمالي السعر للطلب
                'order_created_at' => $order->created_at->format('Y-m-d'),  // تاريخ إنشاء الطلب
                'order_meal' => [
                    'meal_id' => $order->meal->id,
                    'meal_name' => $order->meal->meal_name,
                    'meal_price' => $order->meal->price,
                    'meal_quantity' => $order->quantity,  // كمية الوجبة في الطلب
                    'meal_total_price' => $order->total_price,  // إجمالي سعر الوجبة في الطلب
                    'meal_images' =>$order->meal->images ? array_map(function ($img) {return url('storage/' . $img);}, json_decode($order->meal->images)) : null ,
                    'restaurant' => [
                        'id' => $order->meal->restaurant->id,
                        'name' => $order->meal->restaurant->name,
                        'phone_number' => $order->meal->restaurant->phone_number,
                        'email' => $order->meal->restaurant->email,
                        'avatar' => $order->meal->restaurant->avatar,
                        'location' => $order->meal->restaurant->location,
                        'latitude' => $order->meal->restaurant->lat,
                        'longitude' => $order->meal->restaurant->lang,
                        'brief' => $order->meal->restaurant->brief,
                    ]
                ]
            ];
        })
    ];

    return response()->json($response, 200);
}

public function createOrder(Request $request)
{
    // التحقق من صحة البيانات المدخلة
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'meal_id' => 'required|exists:meals,id',
        'restaurant_id' => 'required|exists:restaurants,id',
        'quantity' => 'required|integer|min:1'
    ]);

    // جلب الوجبة من المطعم المحدد
    $meal = Meal::where('id', $request->meal_id)
                ->where('restaurant_id', $request->restaurant_id)
                ->first();

    // التحقق مما إذا كانت الوجبة موجودة في هذا المطعم
    if (!$meal) {
        return response()->json([
            'message' => 'الوجبة غير موجودة في هذا المطعم'
        ], 404);
    }

    // التحقق من توفر الكمية المطلوبة
    if ($meal->quantity === null || $meal->quantity < $request->quantity) {
        return response()->json([
            'message' => 'المخزون غير كافٍ لتحضير الطلب'
        ], 400);
    }

    // تحديث المخزون
    $meal->update(['quantity' => $meal->quantity - $request->quantity]);

    // حساب السعر الكلي
    $total_price = $meal->price * $request->quantity;

    // إنشاء الطلب
    $order = Order::create([
        'user_id' => $request->user_id,
        'meal_id' => $request->meal_id,
        'restaurant_id' => $request->restaurant_id,
        'OrderStatus_id' => 2, // حالة "قيد التحضير"
        'quantity' => $request->quantity,
        'total_price' => $total_price,
    ]);

    // جلب الطلب بعد الإنشاء مع العلاقات
    $order = Order::with(['user', 'meal.restaurant', 'status'])->find($order->id);

    // تنسيق الاستجابة بنفس الشكل المطلوب
    $response = [
        'status' => 'success',
        'order' => [
            'order_id' => $order->id,
            'user' => [
                'user_id' => $order->user->id,
                'user_name' => $order->user->name,
                'user_phone_number' => $order->user->phone_number,
                'user_email' => $order->user->email,
                'user_location' => $order->user->location,
            ],
            'status' => [
                'status_id' => $order->status->id,
                'status_title' => $order->status->status_title,
            ],
            'total_price' => $order->total_price,
            'order_created_at' => $order->created_at->format('Y-m-d'),
            'order_meal' => [
                'meal_id' => $order->meal->id,
                'meal_name' => $order->meal->meal_name,
                'meal_price' => $order->meal->price,
                'meal_quantity' => $order->quantity,
                'meal_total_price' => $order->total_price,
                'meal_images' =>$order->meal->images ? array_map(function ($img) {return url('storage/' . $img);}, json_decode($order->meal->images)) : null ,
                'restaurant' => [
                    'id' => $order->meal->restaurant->id,
                    'name' => $order->meal->restaurant->name,
                    'phone_number' => $order->meal->restaurant->phone_number,
                    'email' => $order->meal->restaurant->email,
                    'avatar' => $order->meal->restaurant->avatar,
                    'location' => $order->meal->restaurant->location,
                    'latitude' => $order->meal->restaurant->lat,
                    'longitude' => $order->meal->restaurant->lang,
                    'brief' => $order->meal->restaurant->brief,
                ]
            ]
        ]
    ];

    return response()->json($response, 201);
}

}






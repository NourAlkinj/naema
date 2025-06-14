<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class ImageUploadController extends Controller
{

public function uploadImages(Request $request)
{
    $request->validate([
        'entity_type' => 'required|in:meal,restaurant,user',
        'entity_id' => 'required|integer',
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // تحديد الكيان المستهدف
    switch ($request->entity_type) {
        case 'meal':
            $entity = Meal::find($request->entity_id);
            $folder = 'meals';
            break;
        case 'restaurant':
            $entity = Restaurant::find($request->entity_id);
            $folder = 'restaurants';
            break;
        case 'user':
            $entity = User::find($request->entity_id);
            $folder = 'users';
            break;
        default:
            return response()->json(['message' => 'نوع الكيان غير مدعوم'], 400);
    }

    if (!$entity) {
        return response()->json(['message' => 'الكيان غير موجود'], 404);
    }

    $paths = $entity->images ? json_decode($entity->images, true) : [];

    // رفع الصور وتجنب التكرار
    foreach ($request->file('images') as $image) {
        $originalName = $image->getClientOriginalName();
        $filePath = $folder . '/' . $originalName;

        if (!in_array($filePath, $paths)) {
            $image->storeAs($folder, $originalName, 'public'); // حفظ الصورة بالاسم الأصلي
            $paths[] = $filePath;
        }
    }

    // حفظ المسارات بصيغة صحيحة في قاعدة البيانات
    $entity->update(['images' => json_encode($paths, JSON_UNESCAPED_SLASHES)]);

    return response()->json([
        'message' => 'تم رفع الصور بنجاح',
        'images' => $paths
    ], 201);
}


public function getImagePath(Request $request)
{
    $request->validate([
        'image_name' => 'required|string'
    ]);

    $imageName = $request->image_name;

    // تحديد المجلدات التي تحتوي على الصور
    $folders = ['users', 'meals', 'restaurants'];

    foreach ($folders as $folder) {
        // التحقق من وجود الصورة في المجلد
        if (Storage::disk('public')->exists("{$folder}/{$imageName}")) {
            // إذا كانت الصورة موجودة، إرجاع المسار
            $imagePath = "storage/{$folder}/{$imageName}";
            return response()->json(['image_path' => url($imagePath)]);
        }
    }

    return response()->json(['message' => 'الصورة غير موجودة في التخزين'], 404);
}




}



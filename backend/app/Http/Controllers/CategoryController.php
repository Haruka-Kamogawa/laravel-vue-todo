<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $categories = Category::where('user_id', $user->id)->get();
        return response()->json(['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $data['user_id'] = $request->user()->id;

        $category = Category::create($data);
        return response()->json(['category' => $category], 201);
    }

        /**
     * カテゴリー更新
     */
    public function update(Request $request, Category $category)
    {
        $user = $request->user();

        // 自分のカテゴリーかチェック
        if ($category->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);
        return response()->json(['category' => $category]);
    }

    /**
     * カテゴリー削除
     */
    public function destroy(Request $request, Category $category)
    {
        $user = $request->user();

        // 自分のカテゴリーかチェック
        if ($category->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}

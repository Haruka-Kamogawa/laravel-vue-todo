<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Category::select('categories.id','categories.name','categories.user_id','users.username','categories.created_at')->leftJoin('users', 'categories.user_id', '=', 'users.id');

        if ($request->search) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('categories.name', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->get();

        return response()->json(['categories' => $categories], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted'], 200);

    }
}

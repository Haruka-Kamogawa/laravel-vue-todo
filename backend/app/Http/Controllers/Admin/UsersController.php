<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::select('id','username','email','birth_date','created_at');

        if ($request->search) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->get();

        return response()->json(['users' => $users], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }
}

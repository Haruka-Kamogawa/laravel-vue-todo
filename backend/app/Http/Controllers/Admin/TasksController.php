<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::select('tasks.id','tasks.title','tasks.user_id','users.username','categories.name','tasks.status')
            ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id');

            if ($request->search) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('tasks.title', 'LIKE', "%{$search}%");
            });
        }

        $tasks = $query->get();

        return response()->json(['tasks' => $tasks], 200);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted'], 200);
    }
}

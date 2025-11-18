<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Log::info('Request all:', $request->all());
        // Log::info('Request query:', $request->query());
        // Log::info('User:', ['user' => $request->user()]);

        $user = $request->user();
        $status = $request->query('status');

        $query = Task::where('user_id', $user->id)->orderBy('created_at', 'desc');

        if($status && in_array($status, ['pending', 'in_progress', 'completed'])) {
            $query->where('status', $status);
        }

        $tasks = $query->get();

        return response()->json(['tasks' => $tasks]);
    }

    public function counts(Request $request)
    {
        $user = $request->user();
        
        $counts = [
            'pending' => Task::where('user_id', $user->id)->where('status', 'pending')->count(),
            'in_progress' => Task::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'completed' => Task::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        return response()->json(['counts' => $counts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'cost' => 'nullable|numeric',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $data['user_id'] = $request->user()->id;
        
        $task = Task::create($data);
        return response()->json(['task' => $task], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) abort(403);

        $data = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'due_date' => 'sometimes|date',
            'priority' => 'sometimes|in:low,medium,high',
            'cost' => 'nullable|numeric',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        $task->update($data);
        return response()->json(['task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) abort(403);

        $task->delete();
        return response()->json([], 204);
    }

    
    public function stats(Request $request)
    {
        $user = $request->user();

        $total = Task::where('user_id', $user->id)->count();
        $completed = Task::where('user_id', $user->id)->where('status', 'completed')->count();

        // Priority 別
        $priorityTotal = Task::where('user_id', $user->id)
            ->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        $priorityCompleted = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->selectRaw('priority, COUNT(*) as completed')
            ->groupBy('priority')
            ->pluck('completed', 'priority')
            ->toArray();

        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'priority' => [
                'low' => [
                    'completed' => $priorityCompleted['low'] ?? 0,
                    'total' => $priorityTotal['low'] ?? 0,
                ],
                'medium' => [
                    'completed' => $priorityCompleted['medium'] ?? 0,
                    'total' => $priorityTotal['medium'] ?? 0,
                ],
                'high' => [
                    'completed' => $priorityCompleted['high'] ?? 0,
                    'total' => $priorityTotal['high'] ?? 0,
                ]
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'status' => ['string', Rule::in([
                    'open',
                    'completed'
                ])]
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $status = $request->query('status');

            $tasks = $status ? Task::where([
                ['user_id', Auth::id()],
                ['status', $status]])->get() : Task::where('user_id', Auth::id())->get();

            return TaskResource::collection($tasks);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null,
                'error' => $th,
                'user' => Auth::id()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            $newTask = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'open',
                'user_id' => Auth::id(),
            ]);
    
            return response()->json([
                'message' => 'Task created successfully',
                'data' => $newTask
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null,
                'error' => $th,
                'user' => Auth::id()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $taskId)
    {
        try {
            $task = Task::find($taskId);
            if (!$task) {
                return response()->json(['error' => 'No task exists with that id'], 404);
            }

            if ($task->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unathorized'], 401);
            }

            $validator = validator($request->all(), [
                'title' => ['string', 'max:255'],
                'description' => ['string', 'max:255'],
                'status' => ['string', Rule::in([
                    'open',
                    'completed'
                ])]
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $task->title = $request->title ?? $task->title;
            $task->description =  $request->description ?? $task->description;

            if (isset($request->status) && $request->status === 'completed') {
                $task->status = $request->status ?? $task->status;
                $task->completed_at = date('Y-m-d');
            }
    
            $task->save();
    
            return response()->json([
                'message' => 'Task updated successfully',
                'data' => $task
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null,
                'error' => $th,
                'user' => Auth::id()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($taskId)
    {
        try {
            $task = Task::find($taskId);
            if (!$task) {
                return response()->json(['error' => 'No task exists with that id'], 404);
            }

            if ($task->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unathorized'], 401);
            }

            $task->delete();
    
            return response()->json([
                'message' => 'Task deleted successfully',
                'data' => null
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'data' => null,
                'error' => $th,
                'user' => Auth::id()
            ], 500);
        }
    }
}

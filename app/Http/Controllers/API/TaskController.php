<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function home()
    {
        $tasks = Task::where('user_id', auth()->id())->get();

        $completed_count = $tasks
            ->where('status', Task::STATUS_COMPLETED)
            ->count();

        $uncompleted_count = $tasks
            ->whereNotIn('status', Task::STATUS_COMPLETED)
            ->count();

        
        
        return response()->json([
            'code'=>200,
            'message'=>'Data Berhasil',
            'data'=>['completed_count' => $completed_count,
                    'uncompleted_count' => $uncompleted_count,]
        ]);
    }

    public function index()
    {
        // $pageTitle = 'Task List';
        if (Gate::allows('viewAnyTask', Task::class)) {
            $tasks = Task::all();
        } else {
            $tasks = Task::where('user_id', Auth::user()->id)->get();
        }
        // return view('tasks.index', ['pageTitle' => $pageTitle,'tasks' => $tasks]);
        return response()->json([
            'code'=>200,
            'message'=>'Data Berhasil',
            'data'=>TaskResource::collection($tasks),
        ]);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        if (Gate::denies('performAsTaskOwner', $task)) {
            Gate::authorize('updateAnyTask', Task::class);
        }
        return response()->json([
            'code'=>200,
            'message'=>'Data Berhasil',
            'data'=>new TaskResource($task),
        ]);
    }
}

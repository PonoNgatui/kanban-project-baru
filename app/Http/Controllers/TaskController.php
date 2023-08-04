<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    

    public function __construct()
    {
        
    }

    public function index()
    {
        $pageTitle = 'Task List';
        $tasks = Task::all();
        return view('tasks.index', ['pageTitle' => $pageTitle,'tasks' => $tasks]);
    }
    
    public function edit($id)
    {
        $pageTitle = 'Edit Task';
        $task = Task::find($id);
        return view('tasks.edit', ['pageTitle' => $pageTitle, 'task' => $task]);
    }

    public function create($status = null)
    {
        $pageTitle = 'Create Task';
        
        return view('tasks.create', ['pageTitle' => $pageTitle,'status' => $status]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'due_date' => 'required',
                'status' => 'required',
            ],
            $request->all()
        );
        
        Task::create([
            'name' => $request->name,
            'detail' => $request->detail,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('tasks.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'due_date' => 'required',
                'status' => 'required',
            ],
            $request->all()
        );
        
        $task = Task::find($id);
        $task->update([
            'name' => $request->name,
            'detail' => $request->detail,
            'due_date' => $request->due_date,
            'status' => $request->status,
            // data task yang berasal dari formulir
        ]);
        return redirect()->route('tasks.index');
        // Code untuk melakukan redirect menuju GET /tasks
    }

    public function delete($id)
    {
        // Menyebutkan judul dari halaman yaitu "Delete Task"
        $pageTitle = 'Delete Task';
        //  Memperoleh data task menggunakan $id
        $task = Task::find($id);
        // Menghasilkan nilai return berupa file view dengan halaman dan data task di atas 
        return view('tasks.delete', ['pageTitle' => $pageTitle, 'task' => $task]);
    }
    public function destroy($id)
    {
        $task = Task::find($id);// Memperoleh task tertentu menggunakan $id
        $task->delete();
        // Melakukan redirect menuju tasks.index
        return redirect()->route('tasks.index');
    }

    public function progress()
    {
    $title = 'Task Progress';

    $tasks = Task::all();

    $filteredTasks = $tasks->groupBy('status');

    $tasks = [
        Task::STATUS_NOT_STARTED => $filteredTasks->get(
            Task::STATUS_NOT_STARTED, []
        ),
        Task::STATUS_IN_PROGRESS => $filteredTasks->get(
            Task::STATUS_IN_PROGRESS, []
        ),
        Task::STATUS_IN_REVIEW => $filteredTasks->get(
            Task::STATUS_IN_REVIEW, []
        ),
        Task::STATUS_COMPLETED => $filteredTasks->get(
            Task::STATUS_COMPLETED, []
        ),
    ];

    
    return view('tasks.progress', [
        'pageTitle' => $title,
        'tasks' => $tasks,
    ]);
    }

    public function move(int $id, Request $request)
    {
    $task = Task::findOrFail($id);

    $task->update([
        'status' => $request->status,
    ]);

    return redirect()->route('tasks.progress');
    }

    public function complete(int $id, Request $request)
    {
    $task = Task::findOrFail($id);

    $task->update([
        'status' => $request->status,
    ]);

    return redirect()->route('tasks.progress');
    }

    public function completeList(int $id, Request $request)
    {
    $task = Task::findOrFail($id);

    $task->update([
        'status' => $request->status,
    ]);

    return redirect()->route('tasks.index');
    }

}
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\StatusMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskManager extends Controller
{
    function listTask()
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        $status = StatusMaster::select('id', 'name')->get();

        // Get the ID of the "Complete" status
        $completeId = $status->firstWhere('name', 'Complete')?->id;
        $overdueId = $status->firstWhere('name', 'Overdue')?->id;

        // Update overdue tasks
        foreach ($tasks as $task) {
            if (
                $task->deadline &&
                Carbon::parse($task->deadline)->lt(Carbon::today()) &&
                $task->status_master_id !== $completeId &&
                $task->status_master_id !== $overdueId
            ) {
                $task->status_master_id = $overdueId;
                $task->save();
            }
        }

        //Sorting
        $tasks = $tasks
            ->sortByDesc('deadline') // sort by most recent
            ->sortBy(fn($t) => $t->status_master_id == $completeId ? 1 : 0); // completed = 1 (lower priority)



        // dd($tasks);
        return view('welcome', compact('tasks', 'status'));
    }

    function addTask()
    {
        return view('task.addTask');
    }

    function addTaskPost(Request $request)
    {
        $request->validate([
            'title'         =>  'required',
            'deadline'      =>  'required',
            'description'      =>  'required',
        ], [
            'title'    =>  [
                'required' => 'This field is required'
            ],
            'deadline'  =>  [
                'required'  =>  'This field is required'
            ],
            'description'  =>  [
                'required'  =>  'This field is required'
            ]
        ]);

        //Get Status id
        $status_id = StatusMaster::where('name', 'Incomplete')->pluck('id')->first();

        $task = new Task();
        $data = [
            'user_id'   =>  auth()->id(),
            'status_master_id'  => $status_id,
            'title' => $request->title,
            'deadline'  =>  $request->deadline,
            'description'   =>  $request->description
        ];
        if ($task->create($data)) {
            return back()
                ->with('toast', [
                    'type'      =>  'success',
                    'message'   =>  'Task has been added successfully.',
                    'title'     =>  'Success'
                ]);
        }
        return back()
            ->with('toast', [
                'type'      =>  'error',
                'message'   =>  'Task not added.',
                'title'     =>  'Fail'
            ]);
    }

    function updateTaskStatus($id, $status_id)
    {
        Task::where('id', $id)->update(['status_master_id'    =>  $status_id]);

        return back()
            ->with('toast', [
                'type'      =>  'success',
                'message'   =>  'Task has been updated.',
                'title'     =>  'Success'
            ]);
    }

    public function getInfo($id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'deadline' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->title = $request->title;
        $task->deadline = $request->deadline;
        $task->description = $request->description;
        $task->save();

        return response()->json(['message' => 'Task updated successfully.']);
    }

    function deleteTask($id)
    {
        $curTask = Task::find($id);
        if ($curTask->delete()) {
            return redirect(route('home'))
                ->with('toast', [
                    'type'      =>  'success',
                    'message'   =>  'Task has been deleted.',
                    'title'     =>  'Success'
                ]);
        }

        return redirect(route('home'))
            ->with('toast', [
                'type'      =>  'error',
                'message'   =>  'Task not deleted.',
                'title'     =>  'Fail'
            ]);
    }
}

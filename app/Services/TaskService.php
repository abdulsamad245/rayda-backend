<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use Illuminate\Validation\ValidationException;


class TaskService
{


    public function getAllTasks(int $page = 1, int $perPage = 5)
    {
        $tasks = Auth::user()->tasks()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => TaskResource::collection($tasks),
            'current_page' => $tasks->currentPage(),
            'last_page' => $tasks->lastPage(),
        ];
    }


    public function createTask(array $data)
    {

        $exists = Task::where('title', $data['title'])
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'title' => 'A task with this title already exists'
            ]);
        }

        $data['user_id'] = Auth::id();
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data)
    {
        $exists = Task::where('title', $data['title'])
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'title' => 'A task with this title already exists'
            ]);


        }
        $task->update($data);
        return $task;

    }

    public function deleteTask(Task $task)
    {
        $task->delete();
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Log;

#[OA\Schema(
    schema: "UpdateTaskRequest",
    properties: [
        new OA\Property(property: "title", type: "string", maxLength: 255, description: "Task title"),
        new OA\Property(property: "description", type: "string", description: "Task description", nullable: true),
        new OA\Property(property: "due_date", type: "string", format: "date", example: "2025-02-22T18:17:03.000000Z", description: "Task due date (YYYY-MM-DD)", nullable: true),
        new OA\Property(property: "status", type: "string", enum: ["pending", "in_progress", "completed"], description: "Task status"),
    ]
)]
class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $taskId = $this->route('task') ? $this->route('task')->id : null;
        return [
            'title' => 'sometimes|required|string|max:255|unique:tasks,title,' . $taskId,
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|string|in:pending,in_progress,completed',
        ];
    }
}

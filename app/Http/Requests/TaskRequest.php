<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "TaskRequest",
    required: ["title"],
    properties: [
        new OA\Property(property: "title", type: "string", maxLength: 255, description: "Task title"),
        new OA\Property(property: "description", type: "string", nullable: true, description: "Task description"),
        new OA\Property(property: "status", type: "string", enum: ["pending", "in_progress", "completed"], description: "Task status"),
        new OA\Property(property: "due_date", type: "string", format: "date", example: "2025-02-22T18:17:03.000000Z", nullable: true, description: "Task due date"),
    ]
)]
class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ];
    }
}

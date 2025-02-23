<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Components;
use OpenApi\Attributes\SecurityScheme;

#[OA\Info(
    version: "1.0.0",
    title: "Task Management API",
    description: "API for managing tasks"
)]

#[OA\OpenApi(
    components: new Components(
        securitySchemes: [
            new SecurityScheme(
                securityScheme: "bearerAuth",
                type: "http",
                scheme: "bearer",
                bearerFormat: "JWT"
            )
        ]
    )
)]

class TaskController extends Controller
{
    protected $taskService;
    use AuthorizesRequests;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[OA\Get(
        path: "/api/v1/tasks",
        summary: "Get all tasks for the authenticated user with pagination",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "Page number (default is 1)",
                required: false,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                description: "Number of tasks per page (default is 10)",
                required: false,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "List of paginated tasks"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function index()
    {
        try {
            $page = intval(request()->query('page', 1));
            $perPage = intval(request()->query('per_page', 5));
            Log::error('Error retrieving tasks: ' . request()->query('page'));

            $tasks = $this->taskService->getAllTasks($page, $perPage);

            return ApiResponse::success('Tasks retrieved successfully', $tasks);
        } catch (\Exception $e) {
            Log::error('Error retrieving tasks: ' . $e->getMessage());
            return ApiResponse::error('Error retrieving tasks', $e->getMessage(), 500);
        }
    }


    #[OA\Post(
        path: "/api/v1/tasks",
        summary: "Create a new task",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TaskRequest")
        ),
        responses: [
            new OA\Response(response: 201, description: "Task created successfully"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function store(TaskRequest $request)
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return ApiResponse::success('Task created successfully', new TaskResource($task), 201);
        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return ApiResponse::error('Error creating task', $e->getMessage(), 500);
        }
    }

    #[OA\Get(
        path: "/api/v1/tasks/{task}",
        summary: "Get a specific task",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "task", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Task details"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Task not found"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);
            return ApiResponse::success('Task retrieved successfully', new TaskResource($task));
        } catch (\Exception $e) {
            Log::error('Error retrieving task: ' . $e->getMessage());
            return ApiResponse::error('Error retrieving task', null, 500);
        }
    }

    #[OA\Put(
        path: "/api/v1/tasks/{task}",
        summary: "Update a specific task",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "task", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/TaskRequest")
        ),
        responses: [
            new OA\Response(response: 200, description: "Task updated successfully"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Task not found"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $this->authorize('update', $task);
            $updatedTask = $this->taskService->updateTask($task, $request->validated());
            return ApiResponse::success('Task updated successfully', new TaskResource($updatedTask));
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return ApiResponse::error('Error updating task', $e->getMessage(), 500);
        }
    }

    #[OA\Delete(
        path: "/api/v1/tasks/{task}",
        summary: "Delete a specific task",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "task", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Task deleted successfully"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Task not found"),
            new OA\Response(response: 500, description: "Server error")
        ]
    )]
    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $this->taskService->deleteTask($task);
            return ApiResponse::success('Task deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return ApiResponse::error('Error deleting task', $e->getMessage(), 500);
        }
    }


}


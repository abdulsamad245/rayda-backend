Task Management API

This API provides endpoints for managing tasks and user authentication.

Setup Instructions:
1. Clone the repository
2. Install dependencies: composer install
3. Copy .env.example to .env and configure your database settings
4. Generate application key: php artisan key:generate
5. Run migrations: php artisan migrate
6. Install Passport: php artisan passport:install
7. Generate Swagger documentation: php artisan l5-swagger:generate
8. Generate API route list: php artisan tinker, then require_once base_path('routes/api-list.php')

API Documentation:
- Swagger UI: http://your-app-url/api/documentation
- API Route List: http://your-app-url/api-routes.html

Available Endpoints:
- POST /api/v1/register - Register a new user
- POST /api/v1/login - Log in a user
- POST /api/v1/logout - Log out a user (requires authentication)
- GET /api/v1/tasks - Get all tasks for the authenticated user
- POST /api/v1/tasks - Create a new task
- GET /api/v1/tasks/{task} - Get a specific task
- PUT /api/v1/tasks/{task} - Update a specific task
- DELETE /api/v1/tasks/{task} - Delete a specific task
- POST /api/v1/tasks/{task}/complete - Mark a task as completed

All task-related endpoints require authentication using a bearer token.

For detailed information on request/response formats and parameters, please refer to the Swagger documentation.


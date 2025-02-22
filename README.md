# Task Management API

This API provides endpoints for managing tasks and user authentication.

## Setup Instructions

1. Clone the repository:
   ```sh
   git clone https://github.com/your-repo/task-management-api.git
   cd task-management-api
   ```

2. Install dependencies:
   ```sh
   composer install
   ```

3. Copy `.env.example` to `.env` and configure your database settings:
   ```sh
   cp .env.example .env
   ```

4. Generate the application key:
   ```sh
   php artisan key:generate
   ```

5. Run database migrations:
   ```sh
   php artisan migrate
   ```

6. Generate Swagger API documentation:
   ```sh
   php artisan l5-swagger:generate
   ```

7. Set the `NEXT_PUBLIC_API_URL` environment variable:
   Add the following line to your `.env` file:
   ```env
   NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api/v1
   ```

8. Run the server:
   ```sh
   php artisan serve
   ```
   The server will start at `http://127.0.0.1:8000`.

---

## API Documentation

- **Swagger UI:** [http://127.0.0.1:8000/api/v1/documentation](http://127.0.0.1:8000/api/v1/documentation)

---

## Available Endpoints

### Authentication Endpoints
- **POST** `/api/v1/register` - Register a new user  
- **POST** `/api/v1/login` - Log in a user  
- **POST** `/api/v1/logout` - Log out a user (requires authentication)  

### Task Management Endpoints
- **GET** `/api/v1/tasks` - Get all tasks for the authenticated user  
- **POST** `/api/v1/tasks` - Create a new task  
- **GET** `/api/v1/tasks/{task}` - Get a specific task  
- **PUT** `/api/v1/tasks/{task}` - Update a specific task  
- **DELETE** `/api/v1/tasks/{task}` - Delete a specific task  

All task-related endpoints require authentication using a **Bearer Token**.

---

## Sample Requests & Responses

### 1. User Registration

**Request:**
```http
POST /api/v1/register
Content-Type: application/json
```
```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "SecureP@ssw0rd!",
    "password_confirmation": "SecureP@ssw0rd!"
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "token": "your-auth-token"
    }
}
```

---

### 2. User Login

**Request:**
```http
POST /api/v1/login
Content-Type: application/json
```
```json
{
    "email": "john.doe@example.com",
    "password": "SecureP@ssw0rd!"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "User logged in successfully",
    "data": {
        "token": "your-auth-token"
    }
}
```

**Response (401 Unauthorized - Invalid Credentials):**
```json
{
    "success": false,
    "message": "Invalid credentials",
    "errors": null
}
```

---

### 3. User Logout

**Request:**
```http
POST /api/v1/logout
Authorization: Bearer your-auth-token
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "User logged out successfully"
}
```

---

### 4. Create a Task

**Request:**
```http
POST /api/v1/tasks
Authorization: Bearer your-auth-token
Content-Type: application/json
```
```json
{
    "title": "Complete the project",
    "description": "Work on the Laravel backend for the task management system.",
    "status": "pending",
    "due_date": "2025-03-01T10:00:00Z"
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Task created successfully",
    "data": {
        "id": 1,
        "title": "Complete the project",
        "description": "Work on the Laravel backend for the task management system.",
        "status": "pending",
        "due_date": "2025-03-01T10:00:00Z",
        "completed": false,
        "created_at": "2025-02-21T12:00:00Z",
        "updated_at": "2025-02-21T12:00:00Z"
    }
}
```

---

### 5. Get All Tasks

**Request:**
```http
GET /api/v1/tasks
Authorization: Bearer your-auth-token
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Tasks retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Complete the project",
            "description": "Work on the Laravel backend for the task management system.",
            "status": "pending",
            "due_date": "2025-03-01T10:00:00Z",
            "completed": false,
            "created_at": "2025-02-21T12:00:00Z",
            "updated_at": "2025-02-21T12:00:00Z"
        }
    ]
}
```

---

### 6. Mark Task as Completed

**Request:**
```http
POST /api/v1/tasks/1/complete
Authorization: Bearer your-auth-token
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Task marked as completed",
    "data": {
        "id": 1,
        "title": "Complete the project",
        "description": "Work on the Laravel backend for the task management system.",
        "status": "completed",
        "due_date": "2025-03-01T10:00:00Z",
        "completed": true,
        "created_at": "2025-02-21T12:00:00Z",
        "updated_at": "2025-02-21T12:10:00Z"
    }
}
```

---

### 7. Error Response Example

If a user tries to create a task without a title:

**Response (422 Unprocessable Entity):**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "title": ["The title field is required."]
    }
}
```

---

## Authentication

All task-related endpoints require authentication via a **Bearer Token**. Include the token in the `Authorization` header:

```http
Authorization: Bearer your-auth-token
```

Tokens are issued when a user logs in or registers.

---

## Running the Server

To run the server, use the following command:
```sh
php artisan serve
```
The server will start at `http://127.0.0.1:8000`.

---

## Setting `NEXT_PUBLIC_API_URL`

To ensure your frontend application can communicate with the API, set the `NEXT_PUBLIC_API_URL` environment variable in your `.env` file:
```env
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api/v1
```


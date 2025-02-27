# Task Management API

This API provides endpoints for managing tasks and user authentication.

## Setup Instructions

### Local Development (Without Docker)

1. Clone the repository:
   ```sh
   git clone https://github.com/abdulsamad245/rayda-backend.git
   cd rayda-backend
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

### Docker Setup Instructions

To set up the project using Docker, follow these steps:

1. **Clone the repository** (if not already done):
   ```sh
   git clone https://github.com/abdulsamad245/rayda-backend.git
   cd rayda-backend
   ```

2. **Build and start the containers**:
   - For **development**:
     ```sh
     make fresh
     ```
   - For **production**:
     ```sh
     make fresh-prod
     ```

3. **Access the PHP container**:
   To SSH into the PHP container, run:
   ```sh
   make ssh
   ```

4. **Run Composer Install**:
   Install dependencies inside the PHP container:
   ```sh
   make install
   ```

5. **Run Migrations**:
   Apply database migrations:
   ```sh
   make migrate
   ```

6. **Run Tests**:
   Execute the test suite:
   ```sh
   make tests
   ```

7. **Stop the Containers**:
   To stop all running containers:
   ```sh
   make stop
   ```

8. **Destroy the Containers**:
   To stop and remove all containers:
   ```sh
   make destroy
   ```

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
- **GET** `/api/v1/tasks/{taskId}` - Get a specific task  
- **PUT** `/api/v1/tasks/{taskId}` - Update a specific task  
- **DELETE** `/api/v1/tasks/{taskId}` - Delete a specific task  

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
    "name": "Adamu Kwame",
    "email": "adamu.kwame@example.com",
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
        "id": 20,
        "name": "Adamu Kwame",
        "email": "adamu.kwame@example.com",
        "created_at": "2025-02-22 23:20:42",
        "updated_at": "2025-02-22 23:20:42"
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
    "email": "adamu.kwame@example.com",
    "password": "SecureP@ssw0rd!"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "User logged in successfully",
    "data": {
        "token": "36|upTnxXscSU5yP6CGXHyRtuFqnRDxckkU3MZFx3tGc461fd88",
        "user": {
            "id": 20,
            "name": "Adamu Kwame",
            "email": "adamu.kwame@example.com",
            "created_at": "2025-02-22 23:20:42",
            "updated_at": "2025-02-22 23:20:42"
        }
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

### 6. Get Task by ID

**Request:**
```http
GET /api/v1/tasks/1
Authorization: Bearer your-auth-token
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Task retrieved successfully",
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

### 7. Update Task

**Request:**
```http
PUT /api/v1/tasks/1
Authorization: Bearer your-auth-token
Content-Type: application/json
```
```json
{
    "title": "Complete the project (Updated)",
    "description": "Work on the Laravel backend for the task management system. (Updated)",
    "status": "in_progress",
    "due_date": "2025-03-05T10:00:00Z"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Task updated successfully",
    "data": {
        "id": 1,
        "title": "Complete the project (Updated)",
        "description": "Work on the Laravel backend for the task management system. (Updated)",
        "status": "in_progress",
        "due_date": "2025-03-05T10:00:00Z",
        "completed": false,
        "created_at": "2025-02-21T12:00:00Z",
        "updated_at": "2025-02-21T12:05:00Z"
    }
}
```

---

### 8. Delete Task

**Request:**
```http
DELETE /api/v1/tasks/1
Authorization: Bearer your-auth-token
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Task deleted successfully",
    "data": null
}
```

---

### 9. Error Response Example

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

To ensure the frontend application can communicate with the API, set the `NEXT_PUBLIC_API_URL` environment variable in the `.env.local` file:
```env
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api/v1
```

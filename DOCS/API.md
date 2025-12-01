# API Documentation

## Base URL

```
http://localhost:8080/api
```

## Authentication

Currently, the API does not require authentication. All endpoints are publicly accessible.

## Response Format

All API responses are in JSON format.

### Success Response

```json
{
  "id": 1,
  "title": "Task title",
  "status": "active",
  "created_at": null,
  "updated_at": null
}
```

### Error Response

```json
{
  "message": "Error message",
  "error": {
    "exception": "ExceptionClass",
    "file": "/path/to/file.php",
    "line": 123
  }
}
```

**Note**: In production (`APP_DEBUG=false`), the `error` field is `null` for security reasons.

## Endpoints

### Get All Todos

Retrieve all tasks.

**Endpoint**: `GET /api/todos`

**Response**: `200 OK`

```json
[
  {
    "id": 1,
    "title": "Task 1",
    "status": "active",
    "created_at": null,
    "updated_at": null
  },
  {
    "id": 2,
    "title": "Task 2",
    "status": "completed",
    "created_at": null,
    "updated_at": null
  }
]
```

### Get Todo by ID

Retrieve a specific task by its ID.

**Endpoint**: `GET /api/todos/{id}`

**Parameters**:
- `id` (integer, required) - The ID of the task

**Response**: `200 OK`

```json
{
  "id": 1,
  "title": "Task 1",
  "status": "active",
  "created_at": null,
  "updated_at": null
}
```

**Error Responses**:
- `404 Not Found` - Task not found

### Create Todo

Create a new task.

**Endpoint**: `POST /api/todos`

**Request Body**:
```json
{
  "title": "New task"
}
```

**Parameters**:
- `title` (string, required, max: 255) - The title of the task

**Response**: `201 Created`

```json
{
  "id": 3,
  "title": "New task",
  "status": "active",
  "created_at": null,
  "updated_at": null
}
```

**Error Responses**:
- `422 Unprocessable Entity` - Validation error

```json
{
  "message": "The title field is required.",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

### Update Todo

Update an existing task.

**Endpoint**: `PUT /api/todos/{id}`

**Parameters**:
- `id` (integer, required) - The ID of the task

**Request Body**:
```json
{
  "title": "Updated task title"
}
```

**Parameters**:
- `title` (string, required, max: 255) - The new title of the task

**Response**: `200 OK`

```json
{
  "id": 1,
  "title": "Updated task title",
  "status": "active",
  "created_at": null,
  "updated_at": null
}
```

**Error Responses**:
- `404 Not Found` - Task not found
- `422 Unprocessable Entity` - Validation error

### Toggle Todo Status

Toggle the status of a task between `active` and `completed`.

**Endpoint**: `PATCH /api/todos/{id}/status`

**Parameters**:
- `id` (integer, required) - The ID of the task

**Response**: `200 OK`

```json
{
  "id": 1,
  "title": "Task 1",
  "status": "completed",
  "created_at": null,
  "updated_at": null
}
```

**Error Responses**:
- `404 Not Found` - Task not found

### Delete Todo

Delete a specific task.

**Endpoint**: `DELETE /api/todos/{id}`

**Parameters**:
- `id` (integer, required) - The ID of the task

**Response**: `204 No Content`

**Error Responses**:
- `404 Not Found` - Task not found

### Delete Completed Todos

Delete all completed tasks.

**Endpoint**: `DELETE /api/todos/completed`

**Response**: `200 OK`

```json
{
  "message": "Deleted 3 completed tasks"
}
```

## Status Values

Tasks can have one of two status values:

- `active` - The task is active (default)
- `completed` - The task is completed

## HTTP Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created successfully
- `204 No Content` - Request successful, no content to return
- `400 Bad Request` - Invalid request
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Access denied
- `404 Not Found` - Resource not found
- `405 Method Not Allowed` - HTTP method not allowed for this endpoint
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

## Examples

### cURL Examples

#### Get All Todos

```bash
curl -X GET http://localhost:8080/api/todos
```

#### Get Todo by ID

```bash
curl -X GET http://localhost:8080/api/todos/1
```

#### Create Todo

```bash
curl -X POST http://localhost:8080/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title": "New task"}'
```

#### Update Todo

```bash
curl -X PUT http://localhost:8080/api/todos/1 \
  -H "Content-Type: application/json" \
  -d '{"title": "Updated task"}'
```

#### Toggle Todo Status

```bash
curl -X PATCH http://localhost:8080/api/todos/1/status
```

#### Delete Todo

```bash
curl -X DELETE http://localhost:8080/api/todos/1
```

#### Delete Completed Todos

```bash
curl -X DELETE http://localhost:8080/api/todos/completed
```

### JavaScript Examples

#### Get All Todos

```javascript
fetch('http://localhost:8080/api/todos')
  .then(response => response.json())
  .then(data => console.log(data));
```

#### Create Todo

```javascript
fetch('http://localhost:8080/api/todos', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    title: 'New task'
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

#### Toggle Todo Status

```javascript
fetch('http://localhost:8080/api/todos/1/status', {
  method: 'PATCH'
})
  .then(response => response.json())
  .then(data => console.log(data));
```

## Rate Limiting

Currently, there is no rate limiting implemented. This may be added in the future.

## CORS

CORS is configured to allow requests from any origin. In production, this should be restricted to specific domains.

## Versioning

The API is currently at version 1. There is no versioning in the URL path yet, but this may be added in the future.


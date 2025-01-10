# Project Name: Menu Service

This project is a restaurant menu service where users can fetch menus by cuisine, apply filters, and view detailed information about each menu. It is designed for efficient querying, fast response times, and secure API usage.

## Table of Contents
- [Project Setup](#project-setup)
- [Database Optimization](#database-optimization)
- [API Rate Limiting](#api-rate-limiting)
- [Security Improvements](#security-improvements)
- [Performance Optimization](#performance-optimization)
- [State Management](#state-management)
- [API Response Time](#api-response-time)

## Project Setup

To get the project up and running on your local machine, follow these steps:

### Prerequisites
Make sure you have the following installed:
- **Node.js** (version 14 or higher)
- **npm** (version 6 or higher)
- **PHP** (version 8.1 or higher)
- **Composer** (for PHP dependency management)
- **MySQL** (or any other supported database)

### 1. Clone the Repository

```bash

git clone https://github.com/lukatabinns/y-app.git
cd menu-service

```
### 2. Install Backend Dependencies

Navigate to the backend folder and install the dependencies:

```bash

cd backend
composer install

```
### 3. Configure Environment Variables

Create a .env file in the root directory of the backend project and configure your environment settings, such as database credentials, API keys, and other necessary configurations.

```bash

APP_NAME=MenuService
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=menu_service
DB_USERNAME=root
DB_PASSWORD=secret

```
### 4. Run Database Migrations

Run the database migrations to create the necessary tables:

```bash

php artisan migrate

```
### 5. Start the Backend Server

Start the Laravel backend server:

```bash

php artisan serve

```
### 6. Install Frontend Dependencies

Navigate to the frontend directory and install the dependencies:

```bash

npm install

```
## 7. Start the Frontend Development Server

Start the React development server:

```bash

npm run dev

```
The frontend will be available at http://localhost:3000.

## Database Optimization

To ensure faster querying, the following optimizations have been applied:

### Indexing
- The most frequently queried columns like `menus.status`, `menus.number_of_orders`, and `cuisines.slug` are indexed to speed up search and filtering operations.
- Pivot tables like `menu_cuisine` have also been indexed on the `menu_id` and `cuisine_id` fields for faster joins.

### Query Optimization
- Using **cursor pagination** instead of offset-based pagination for better performance with large datasets.
- The database schema uses **optimized foreign key relationships**, reducing redundant queries by only fetching necessary data.

### Selective Field Retrieval
- The `select` method is used to fetch only the required columns, reducing the amount of data retrieved from the database.

### Caching
- Caching frequently used data like cuisine filters is implemented to avoid hitting the database on every request.

## API Rate Limiting

The API is rate-limited to **1 request per second** to avoid overloading the server and ensure fair usage.

Throttle middleware is applied to rate-limit API requests using the built-in Laravel throttle middleware.

### Example:

```php
    RateLimiter::for('api-collect-menus', function () {
        return Limit::perSecond(1); // 1 request per second
    });
```
This ensures that users can only make one request per second, preventing abuse and ensuring system stability.

## Security Improvements

To enhance the security of the application:

### Authentication:
- Authentication is secured using **OAuth2** and **JWT tokens** for API requests, ensuring that only authorized users can access certain endpoints.
- Sensitive routes are protected by middleware that checks for valid tokens.

### Input Validation:
- All user inputs, including cuisine slugs, menu IDs, and filter parameters, are validated using Laravel's built-in validation to prevent SQL injection and other malicious input attacks.

### Encryption:
- Passwords and sensitive data are stored securely using **bcrypt** encryption.
- API responses are sanitized, ensuring no sensitive data like passwords or internal API keys are exposed.

### CORS:
- **Cross-Origin Resource Sharing (CORS)** is properly configured to only allow trusted domains to access the API, preventing unauthorized domains from making requests.

### HTTPS:
- Ensure that all communication between the frontend and backend is encrypted using **HTTPS**. This can be done by setting up **SSL certificates** on the server.

## Performance Optimization

To ensure fast API response times:

### Database Query Optimization:
- As mentioned, we’ve optimized the queries to avoid heavy joins and reduce database load.
- The use of **cursor pagination** reduces memory usage and speeds up response times.

### HTTP Caching:
- **HTTP caching headers** are set for static resources like images and CSS files.
- API responses are cached when appropriate, especially for data that doesn't change frequently (e.g., cuisines list).

### Frontend Optimization:
- The **React frontend** is optimized by lazy loading components and using code splitting to ensure only necessary resources are loaded.
- **React Redux** is used to manage application state efficiently and prevent unnecessary re-renders.

### API Response Time:
- **Asynchronous processing** is used for long-running tasks, reducing the wait time for users.
- API endpoints return data in **<100ms** for most queries, depending on the size of the dataset.

## State Management

We use **Redux** for state management in the frontend application to handle the following:

### Menus:
- The **menus** slice stores the list of menus, including pagination information and filters.

### Cuisines:
- The **cuisines** slice stores available cuisine options for filtering.

### Pagination:
- The **pagination** slice tracks the current page, last page, and the next/previous cursor for pagination.

### Loading State:
- The **status** state tracks the loading status, error messages, and other API states.

### Redux Actions

- **`fetchMenus`**: Fetches the menus based on the current `cuisineSlug` and pagination state (`nextCursor`).
- **`setPage`**: Changes the current page for pagination.

By using Redux, we ensure that state management is centralized and can be easily debugged and maintained.

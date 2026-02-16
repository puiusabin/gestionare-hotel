# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PHP-based hotel reservation system built for a university web development course. Traditional server-side rendered MVC application with session-based authentication and role-based access control.

## Technology Stack

- PHP 7.4+ (no strict version enforcement)
- MySQL with utf8mb4 encoding
- Apache with mod_rewrite
- PDO for database access
- No build tools, package managers, or frontend frameworks

## Database Setup

Import the schema with sample data:

```bash
mysql -u root -p < docs/schema.sql
```

Database credentials are hardcoded in [src/config/database.php](src/config/database.php):
- Host: localhost
- Database: hotel_reservation
- Username: root
- Password: (empty string)

Test credentials from sample data:
- Admin: admin@hotel.com / admin123
- Guest: guest@example.com / guest123

## Architecture Pattern

### Front Controller Routing

All requests flow through [public/index.php](public/index.php) which implements a simple routing table:

```php
$routes = [
    'GET' => [
        '/' => ['controller' => 'HomeController', 'action' => 'index'],
        '/rooms' => ['controller' => 'RoomController', 'action' => 'index'],
    ],
    'POST' => [
        '/login' => ['controller' => 'AuthController', 'action' => 'login'],
    ]
];
```

To add a new route:
1. Add entry to the appropriate method array in [public/index.php](public/index.php)
2. Create or update the controller in [src/controllers/](src/controllers/)
3. Create the view file in [src/views/](src/views/)

URL rewriting is handled by [public/.htaccess](public/.htaccess) which routes all requests to index.php while preserving query strings.

### MVC Components

**Models** ([src/models/](src/models/)):
- Instantiate Database class and get PDO connection via `getConnection()`
- Use prepared statements with named parameters (`:email`, `:id`)
- Return associative arrays (PDO::FETCH_ASSOC is the default)
- Example pattern:

```php
$stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
return $stmt->fetch();
```

**Controllers** ([src/controllers/](src/controllers/)):
- Controller action methods don't return values - they render views directly
- Use `require_once` to include view files
- Failed validations: set flash message and redirect
- Successful operations: set flash message and redirect
- Admin-only actions: call `requireAdmin()` at the start

**Views** ([src/views/](src/views/)):
- Organized by feature (auth/, rooms/, layout/)
- Always include header and footer: `require_once __DIR__ . '/../layout/header.php';`
- Sanitize all user data with `htmlspecialchars()` to prevent XSS
- Access session data directly via `$_SESSION['user_first_name']` etc.

### Database Connection

The [Database](src/config/database.php) class uses singleton pattern:
- Connection created lazily on first `getConnection()` call
- PDO configured with:
  - `ERRMODE_EXCEPTION` - throw exceptions on errors
  - `FETCH_ASSOC` - return associative arrays by default
  - `EMULATE_PREPARES => false` - use native prepared statements

## Authentication & Sessions

Session management is centralized in [src/includes/session.php](src/includes/session.php):

**Helper Functions:**
- `startSession()` - Initialize session if not already started
- `isLoggedIn()` - Check if user is authenticated
- `requireAuth()` - Redirect to login if not authenticated
- `requireAdmin()` - Redirect to home if not admin role
- `getCurrentUser()` - Get current user data from session
- `setFlashMessage($message, $type)` - Set one-time message
- `getFlashMessage()` - Retrieve and clear flash message

**Session Data Structure:**
```php
$_SESSION = [
    'user_id' => 1,
    'user_email' => 'admin@hotel.com',
    'user_first_name' => 'Admin',
    'user_last_name' => 'User',
    'user_role' => 'admin'  // 'guest' or 'admin'
];
```

**Password Security:**
- Registration: `password_hash($password, PASSWORD_DEFAULT)` (bcrypt)
- Login: `password_verify($plainPassword, $hashedPassword)`
- Never store plaintext passwords

## Security Conventions

**SQL Injection Prevention:**
All database queries MUST use PDO prepared statements with named parameters:

```php
// Correct - parameterized query
$stmt->prepare("SELECT * FROM rooms WHERE room_type = :type");
$stmt->execute(['type' => $type]);

// NEVER - string concatenation
$stmt->query("SELECT * FROM rooms WHERE room_type = '$type'");
```

**XSS Prevention:**
All user data displayed in views MUST be escaped:

```php
<p>Welcome, <?php echo htmlspecialchars($user['first_name']); ?></p>
```

**CSRF Protection:**
Currently NOT implemented. Forms do not include CSRF tokens.

## Database Schema

**users** - Authentication and profiles
- Roles: 'guest' (default) or 'admin'
- Passwords hashed with PASSWORD_DEFAULT
- Email is unique constraint

**rooms** - Hotel inventory
- room_type: 'single', 'double', or 'suite'
- is_available: boolean flag
- Indexed on room_number and room_type

**reservations** - Bookings
- Foreign keys: user_id → users.id, room_id → rooms.id
- Status: 'pending', 'confirmed', 'cancelled', 'completed'
- DELETE RESTRICT on rooms (cannot delete room with reservations)
- DELETE CASCADE on users (deletes user's reservations)

## Controller Action Pattern

Standard controller action flow:

```php
public function store()
{
    requireAdmin();  // Authorization check

    // Validation
    if (empty($_POST['room_number'])) {
        setFlashMessage('Room number is required', 'error');
        header('Location: /rooms/create');
        exit;
    }

    // Process data
    $room = new Room();
    $room->create($_POST);

    // Success response
    setFlashMessage('Room created successfully', 'success');
    header('Location: /rooms');
    exit;
}
```

## Flash Message System

One-time messages stored in session and cleared after display:

```php
// In controller
setFlashMessage('Operation successful', 'success');

// In view (header.php displays automatically)
$flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif;
```

## Query String vs Route Parameters

This codebase uses query strings for entity IDs rather than URL segments:

```php
// Correct pattern
/rooms/edit?id=5
/rooms/delete?id=5

// NOT used in this codebase
/rooms/5/edit
/rooms/5/delete
```

Access via `$_GET['id']` in controller actions.

## Navigation and Layout

The header template ([src/views/layout/header.php](src/views/layout/header.php)) conditionally displays navigation based on authentication state:

```php
<?php if (isLoggedIn()): ?>
    <!-- Show: Rooms, Add Room (admin only), Logout -->
<?php else: ?>
    <!-- Show: Login, Register -->
<?php endif; ?>
```

All pages should include header and footer:

```php
require_once __DIR__ . '/../layout/header.php';
// Page content
require_once __DIR__ . '/../layout/footer.php';
```

## Common Patterns

**Creating a new CRUD controller:**
1. Create controller class extending nothing (no base controller)
2. Instantiate model in each action method
3. Use `requireAdmin()` for admin-only actions
4. Always redirect after POST (never render directly)
5. Use flash messages for user feedback

**Adding model methods:**
1. Get PDO connection in constructor: `$this->conn = (new Database())->getConnection();`
2. Use prepared statements with named parameters
3. Return associative arrays or boolean for success/failure
4. Let PDO exceptions bubble up (they're caught by the front controller)

**Form handling:**
1. GET action shows form view
2. POST action validates, processes, and redirects
3. Validation errors: flash message + redirect to form
4. Success: flash message + redirect to list/detail page

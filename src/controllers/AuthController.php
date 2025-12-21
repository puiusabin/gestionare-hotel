<?php

require_once __DIR__ . '/../models/User.php';

// Handles user authentication (register, login, logout)
class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Display registration form
    public function showRegister()
    {
        $title = 'Register - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Process registration form submission
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        // Validate CSRF token
        if (!validateCsrfToken()) {
            setFlashMessage('error', 'Invalid security token. Please try again.');
            header('Location: /register');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];

        // Validate email
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } elseif ($this->userModel->emailExists($email)) {
            $errors[] = 'Email already registered';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }

        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }

        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }

        if (!empty($errors)) {
            setFlashMessage('error', implode('. ', $errors));
            header('Location: /register');
            exit;
        }

        $userId = $this->userModel->create($email, $password, $firstName, $lastName, $phone);

        if ($userId) {
            setFlashMessage('success', 'Registration successful! Please login.');
            header('Location: /login');
        } else {
            setFlashMessage('error', 'Registration failed. Please try again.');
            header('Location: /register');
        }
        exit;
    }

    // Display login form
    public function showLogin()
    {
        $title = 'Login - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Process login form submission
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        // Validate CSRF token
        if (!validateCsrfToken()) {
            setFlashMessage('error', 'Invalid security token. Please try again.');
            header('Location: /login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlashMessage('error', 'Email and password are required');
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            setFlashMessage('error', 'Invalid email or password');
            header('Location: /login');
            exit;
        }


        regenerateSessionOnLogin();

        // Create session with user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_first_name'] = $user['first_name'];
        $_SESSION['user_last_name'] = $user['last_name'];
        $_SESSION['user_role'] = $user['role'];

        setFlashMessage('success', 'Welcome back, ' . $user['first_name'] . '!');
        header('Location: /');
        exit;
    }

    // Logout user and destroy session
    public function logout()
    {
        logout();
    }
}

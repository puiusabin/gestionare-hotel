<?php

require_once __DIR__ . '/../models/Room.php';

// Handles room CRUD operations
class RoomController
{
    private $roomModel;

    public function __construct()
    {
        $this->roomModel = new Room();
    }

    // Display list of rooms with optional type filter
    public function index()
    {
        $filterType = $_GET['type'] ?? null;

        if ($filterType) {
            $rooms = $this->roomModel->findByType($filterType);
        } else {
            $rooms = $this->roomModel->findAll();
        }

        $title = 'Rooms - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/rooms/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Display create room form (admin only)
    public function create()
    {
        requireAdmin();
        $title = 'Add Room - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/rooms/create.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Process create room form submission (admin only)
    public function store()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /rooms/create');
            exit;
        }

        // Validate CSRF token
        if (!validateCsrfToken()) {
            setFlashMessage('error', 'Invalid security token. Please try again.');
            header('Location: /rooms/create');
            exit;
        }

        // Validate request origin
        if (!validateReferer()) {
            setFlashMessage('error', 'Invalid request origin');
            header('Location: /rooms/create');
            exit;
        }

        // Prevent form field injection
        $expectedFields = ['csrf_token', 'room_number', 'room_type', 'capacity', 'price_per_night', 'description', 'is_available'];
        if (!validateExpectedFields($expectedFields)) {
            setFlashMessage('error', 'Invalid form submission');
            header('Location: /rooms/create');
            exit;
        }

        $roomNumber = trim($_POST['room_number'] ?? '');
        $roomType = trim($_POST['room_type'] ?? '');
        $capacity = trim($_POST['capacity'] ?? '');
        $pricePerNight = trim($_POST['price_per_night'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isAvailable = isset($_POST['is_available']) ? 1 : 0;

        $errors = [];

        if (empty($roomNumber)) {
            $errors[] = 'Room number is required';
        }

        // Whitelist validation for room_type
        $allowedRoomTypes = ['single', 'double', 'suite'];
        if (empty($roomType)) {
            $errors[] = 'Room type is required';
        } elseif (!in_array($roomType, $allowedRoomTypes, true)) {
            $errors[] = 'Invalid room type';
        }

        if (empty($capacity)) {
            $errors[] = 'Capacity is required';
        } elseif (!is_numeric($capacity) || $capacity < 1) {
            $errors[] = 'Capacity must be at least 1';
        }

        if (empty($pricePerNight)) {
            $errors[] = 'Price per night is required';
        } elseif (!is_numeric($pricePerNight) || $pricePerNight < 0) {
            $errors[] = 'Price must be a positive number';
        }

        if (!empty($errors)) {
            setFlashMessage('error', implode('. ', $errors));
            header('Location: /rooms/create');
            exit;
        }

        $roomId = $this->roomModel->create(
            $roomNumber,
            $roomType,
            $capacity,
            $pricePerNight,
            !empty($description) ? $description : null,
            null,
            $isAvailable
        );

        if ($roomId) {
            setFlashMessage('success', 'Room added successfully');
            header('Location: /rooms');
        } else {
            setFlashMessage('error', 'Failed to add room. Please try again.');
            header('Location: /rooms/create');
        }
        exit;
    }

    // Display edit room form (admin only)
    public function edit()
    {
        requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('error', 'Room ID is required');
            header('Location: /rooms');
            exit;
        }

        $room = $this->roomModel->findById($id);

        if (!$room) {
            setFlashMessage('error', 'Room not found');
            header('Location: /rooms');
            exit;
        }

        $title = 'Edit Room - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/rooms/edit.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Process edit room form submission (admin only)
    public function update()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /rooms');
            exit;
        }

        // Validate CSRF token
        if (!validateCsrfToken()) {
            setFlashMessage('error', 'Invalid security token. Please try again.');
            header('Location: /rooms');
            exit;
        }

        // Validate request origin
        if (!validateReferer()) {
            setFlashMessage('error', 'Invalid request origin');
            header('Location: /rooms');
            exit;
        }

        // Validate only expected fields are present
        $expectedFields = ['csrf_token', 'id', 'room_number', 'room_type', 'capacity', 'price_per_night', 'description', 'is_available'];
        if (!validateExpectedFields($expectedFields)) {
            setFlashMessage('error', 'Invalid form submission');
            header('Location: /rooms');
            exit;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            setFlashMessage('error', 'Room ID is required');
            header('Location: /rooms');
            exit;
        }

        $room = $this->roomModel->findById($id);
        if (!$room) {
            setFlashMessage('error', 'Room not found');
            header('Location: /rooms');
            exit;
        }

        $roomNumber = trim($_POST['room_number'] ?? '');
        $roomType = trim($_POST['room_type'] ?? '');
        $capacity = trim($_POST['capacity'] ?? '');
        $pricePerNight = trim($_POST['price_per_night'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isAvailable = isset($_POST['is_available']) ? 1 : 0;

        $errors = [];

        if (empty($roomNumber)) {
            $errors[] = 'Room number is required';
        }

        // Whitelist validation for room_type
        $allowedRoomTypes = ['single', 'double', 'suite'];
        if (empty($roomType)) {
            $errors[] = 'Room type is required';
        } elseif (!in_array($roomType, $allowedRoomTypes, true)) {
            $errors[] = 'Invalid room type';
        }

        if (empty($capacity)) {
            $errors[] = 'Capacity is required';
        } elseif (!is_numeric($capacity) || $capacity < 1) {
            $errors[] = 'Capacity must be at least 1';
        }

        if (empty($pricePerNight)) {
            $errors[] = 'Price per night is required';
        } elseif (!is_numeric($pricePerNight) || $pricePerNight < 0) {
            $errors[] = 'Price must be a positive number';
        }

        if (!empty($errors)) {
            setFlashMessage('error', implode('. ', $errors));
            header('Location: /rooms/edit?id=' . $id);
            exit;
        }

        $success = $this->roomModel->update(
            $id,
            $roomNumber,
            $roomType,
            $capacity,
            $pricePerNight,
            !empty($description) ? $description : null,
            null,
            $isAvailable
        );

        if ($success) {
            setFlashMessage('success', 'Room updated successfully');
            header('Location: /rooms');
        } else {
            setFlashMessage('error', 'Failed to update room. Please try again.');
            header('Location: /rooms/edit?id=' . $id);
        }
        exit;
    }

    // Delete room by ID (admin only)
    public function delete()
    {
        requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage('error', 'Room ID is required');
            header('Location: /rooms');
            exit;
        }

        $room = $this->roomModel->findById($id);

        if (!$room) {
            setFlashMessage('error', 'Room not found');
            header('Location: /rooms');
            exit;
        }

        $success = $this->roomModel->delete($id);

        if ($success) {
            setFlashMessage('success', 'Room deleted successfully');
        } else {
            setFlashMessage('error', 'Failed to delete room. It may have existing reservations.');
        }

        header('Location: /rooms');
        exit;
    }
}

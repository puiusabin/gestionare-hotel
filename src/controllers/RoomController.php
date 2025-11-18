<?php

require_once __DIR__ . '/../models/Room.php';

class RoomController
{
    private $roomModel;

    public function __construct()
    {
        $this->roomModel = new Room();
    }

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

    public function create()
    {
        requireAdmin();
        $title = 'Add Room - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/rooms/create.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function store()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

        if (empty($roomType)) {
            $errors[] = 'Room type is required';
        } elseif (!in_array($roomType, ['single', 'double', 'suite'])) {
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

    public function update()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /rooms');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $roomNumber = trim($_POST['room_number'] ?? '');
        $roomType = trim($_POST['room_type'] ?? '');
        $capacity = trim($_POST['capacity'] ?? '');
        $pricePerNight = trim($_POST['price_per_night'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isAvailable = isset($_POST['is_available']) ? 1 : 0;

        $errors = [];

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

        if (empty($roomNumber)) {
            $errors[] = 'Room number is required';
        }

        if (empty($roomType)) {
            $errors[] = 'Room type is required';
        } elseif (!in_array($roomType, ['single', 'double', 'suite'])) {
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

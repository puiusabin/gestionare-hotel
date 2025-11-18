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
    }

    public function edit()
    {
        requireAdmin();
    }

    public function update()
    {
        requireAdmin();
    }

    public function delete()
    {
        requireAdmin();
    }
}

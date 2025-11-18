<?php

class HomeController {
    public function index() {
        $title = 'Home - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/home.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}

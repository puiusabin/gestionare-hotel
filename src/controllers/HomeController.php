<?php

class HomeController
{
    public function index()
    {
        $weather = getWeatherData(WEATHER_CITY);
        $title = 'Home - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/home.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}

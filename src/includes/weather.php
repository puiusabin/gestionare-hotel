<?php

function getWeatherData($city = 'Bucharest')
{
    $apiKey = env('OPENWEATHER_API_KEY');
    if (!$apiKey) {
        return null;
    }

    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&units=metric&appid=" . $apiKey;

    try {
        $response = file_get_contents($url);
        if ($response === false) {
            error_log("Failed to fetch weather data from {$url}");
            return null;
        }

        $data = json_decode($response, true);
        if (!$data || $data['cod'] != 200) {
            return null;
        }

        return [
            'temp' => round($data['main']['temp']),
            'description' => ucfirst($data['weather'][0]['description']),
            'icon' => $data['weather'][0]['icon'],
            'city' => $data['name']
        ];
    } catch (Exception $e) {
        return null;
    }
}

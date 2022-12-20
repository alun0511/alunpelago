<?php


if (isset($_POST['name'], $_POST['arrival'], $_POST['departure'])) {
    $name = trim($_POST['name']);
    $arrivalDate = trim($_POST['arrival']);
    $departureDate = trim($_POST['departure']);

    $booking = [
        "name" => $name,
        "arrival_date" => $arrivalDate,
        "departure_date" => $departureDate,
    ];

    $booking = json_encode($booking);
}

echo '<pre>' . var_dump($booking) . '</pre>';

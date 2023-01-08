<?php

declare(strict_types=1);

header('Content-Type: application/json');


function successfulBooking($arrivalDate, $departureDate, $totalCost)
{

    $bookingResponse = [
        "island" => "Pelagon",
        "hotel" => "Moster Dagnys",
        "arrival_date" => $arrivalDate,
        "departure_date" => $departureDate,
        "total_cost" => $totalCost,
        "stars" => "1",
        "features" => ["name" => "", "cost" => ""],
        "addtional_info" => "Thank you for making the right choice by staying at Moster Dagnys. We hope you will enjoy your stay."
    ];


    // header('Location: ' . $actual_link);
    echo json_encode($bookingResponse);
}

successfulBooking($arrivalDate, $departureDate, $totalCost);
// POST arrival, departure and room

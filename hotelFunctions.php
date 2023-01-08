<?php

declare(strict_types=1);


require __DIR__ . '/calendar.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\AppendStream;
use GuzzleHttp\Psr7\Request;

// header('Content-Type: application/json');

/*
Here's something to start your career as a hotel manager.

One function to connect to the database you want (it will return a PDO object which you then can use.)
    For instance: $db = connect('hotel.db');
                  $db->prepare("SELECT * FROM bookings");

one function to create a guid,
and one function to control if a guid is valid.
*/


function connect(string $dbName): object
{
    $dbPath = __DIR__ . '/' . $dbName;
    $db = "sqlite:$dbPath";

    // Open the database file and catch the exception if it fails.
    try {
        $db = new PDO($db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Failed to connect to the database";
        throw $e;
    }
    return $db;
}


function guidv4(string $data = null): string
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function isValidUuid(string $uuid): bool
{
    if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
        return false;
    }
    return true;
}


function roomType($roomID)
{
    if ($roomID === 1) {
        return "enkelrum";
    } elseif ($roomID === 2) {
        return "dubbelrum";
    } elseif ($roomID === 3) {
        return "svit";
    }
}

function selectDate(string $name, string $arrivalDate, string $departureDate, int $roomID, $totalCost, $actual_link)
{
    if (testDate($name, $arrivalDate, $departureDate, $roomID)) {
        echo "The " . roomType($roomID) . " is free between " . $arrivalDate . " and " . $departureDate . "! <br>";
        // insertDate($name, $arrivalDate, $departureDate, $roomID);
        successfulBooking($arrivalDate, $departureDate, $totalCost, $actual_link);
    } else {
        echo "The room is unfortunately not available.";
    }
}

function testDate(string $name, string $arrivalDate, string $departureDate, int $roomID): bool
{
    $dbName = 'database.db';
    $db = connect($dbName);

    $stmt = $db->prepare("SELECT reservations.arrival_date, reservations.departure_date, room_reservation.room_id FROM room_reservation
    INNER JOIN rooms
        ON rooms.id = room_reservation.room_id
    INNER JOIN reservations
        ON reservations.id = room_reservation.reservation_id
    WHERE
            (reservations.arrival_date <= :departure_date
        AND reservations.departure_date >= :arrival_date)
        AND room_reservation.room_id = :room_id");

    $stmt->bindParam(':arrival_date', $arrivalDate);
    $stmt->bindParam(':departure_date', $departureDate);
    $stmt->bindParam(':room_id', $roomID);

    $stmt->execute();

    $unavailable = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($unavailable)) {
        return true;
    } else {
        return false;
    }
}


// insert reservation
function insertDate(string $name, string $arrivalDate, string $departureDate, int $roomID)
{

    $dbName = 'database.db';
    $db = connect($dbName);

    $stmtInsert = $db->prepare(
        "

    INSERT INTO reservations
    (name, arrival_date, departure_date)
    VALUES
    (:name, :arrival_date, :departure_date)"
    );

    $stmtInsert->bindParam(':name', $name);
    $stmtInsert->bindParam(':arrival_date', $arrivalDate);
    $stmtInsert->bindParam(':departure_date', $departureDate);

    $stmtInsert->execute();
    $reservationId = $db->lastInsertId();

    $stmtInsert2 = $db->prepare(
        "
            INSERT INTO room_reservation (reservation_id, room_id)
            VALUES (:reservation_id, :room_id)
            "
    );

    $stmtInsert2->bindParam(':room_id', $roomID);
    $stmtInsert2->bindParam(':reservation_id', $reservationId);
    $stmtInsert2->execute();
}


// selects all reservations from the database on the provided roomID.
function getBookings(int $roomID): array
{

    $dbName = 'database.db';
    $db = connect($dbName);

    $stmtSelect = $db->prepare("SELECT reservations.arrival_date, reservations.departure_date, rooms.id FROM room_reservation
    INNER JOIN rooms
        ON rooms.id = room_reservation.room_id
    INNER JOIN reservations
        ON reservations.id = room_reservation.reservation_id
    WHERE room_reservation.room_id = :room_id");

    $stmtSelect->bindParam(':room_id', $roomID);

    $stmtSelect->execute();

    $roomBookings = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
    return $roomBookings;
}


// uses the array of dates from provided roomID (getBookings($roomID)) and adds these to three seperate calendars for each room.

function drawCalendar(int $roomID, object $calendarID)
{

    foreach (getBookings($roomID) as $roomBooking) {

        $calendarID->addEvent(
            $roomBooking['arrival_date'],
            $roomBooking['departure_date'],
            "",
            true,

        );
    }
    echo $calendarID->draw(date('2023-01-01'));
}

function transferCodeValidator($transfercode, $totalCost)
{

    if (!isValidUuid($transfercode)) {
        echo "Transfercode not valid. <br>";
    } else {


        $client = new \GuzzleHttp\Client();

        $options = [
            'form_params' => [
                'transferCode' => $transfercode,
                'totalcost' => $totalCost
            ]
        ];

        $response = $client->post("https://www.yrgopelago.se/centralbank/transferCode", $options);
        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);

        if (!array_key_exists("amount", $response) || $response["amount"] < $totalCost) {
            echo "Sorry, not enough money has been assigned to this transfercode. Please check the price displayed. <br>";
        }

        return true;
    }
}

function countTotalCost($arrivalDate, $departureDate, $roomID)
{
    $arrivalDateTime = new DateTime($arrivalDate);
    $departureDateTime = new DateTime($departureDate);
    $interval = $arrivalDateTime->diff($departureDateTime);
    $daysInterval = $interval->days;

    if ($roomID === "1") {
        return ($daysInterval * 2);
    } elseif ($roomID === "2") {
        return ($daysInterval * 6);
    } elseif ($roomID === "3") {
        return ($daysInterval * 8);
    }
}

function successfulBooking($arrivalDate, $departureDate, $totalCost, $actual_link)
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

    $logbookDir = __DIR__ . '/logbook.json';
    $logbook = file_get_contents($logbookDir);

    $logbook = json_decode($logbook, true);

    $logbook['vacation'][] = $bookingResponse;

    json_encode($logbook);


    if (file_put_contents($logbookDir, $logbook)) {
        echo "JSON created succesfully";
    } else echo "JSON created unsuccesfully";



    // header('Location: ' . $actual_link);
}





/*

    island
    hotel
    arrival_date
    departure_date
    total_cost
    stars
    features
    additional_info. (This last property is where you can put in a personal greeting from your hotel, an image URL, link to a youtube video or whatever you like.)
 */

// function checkTransferCode($transferCode, $totalCost): string | bool
// {
//     if (!isValidUuid($transferCode)) {
//         return "Invalid transferCode format";
//     } else {
//         $client = new GuzzleHttp\Client();
//         $options = [
//             'form_params' => [
//                 "transferCode" => $transferCode, "totalCost" => $totalCost
//             ]
//         ];


//         try {
//             $response = $client->post("https://www.yrgopelago.se/centralbank/transferCode", $options);
//             $response = $response->getBody()->getContents();
//             $response = json_decode($response, true);
//         } catch (\Exception $e) {
//             return "Error occured!" . $e;
//         }
//         if (array_key_exists("error", $response)) {
//             if ($response["error"] == "Not a valid GUID") {
//                 //The banks error message for a transferCode not being valid for enough can be misleading.
//                 return "An error has occured! $response[error]. This could be due to your Transfercode not being vaild for enough credit.";
//             }
//             return "An error has occured! $response[error]";
//         }
//         if (!array_key_exists("amount", $response) || $response["amount"] < $totalCost) {

//             return "Transfer code is not valdid for enough money.";
//         }
//     }

//     return true;
// }

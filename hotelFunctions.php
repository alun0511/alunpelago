<?php

declare(strict_types=1);


require __DIR__ . '/calendar.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\AppendStream;
use GuzzleHttp\Psr7\Request;


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

function roomType($roomID)
{
    if ($roomID = 1) {
        return "enkelrum";
    } elseif ($roomID = 2) {
        return "dubbelrum";
    } elseif ($roomID = 3) {
        return "svit";
    }
}

// testDate is called within following function selectDate

function testDate(string $name, string $arrivalDate, string $departureDate, string $roomID): bool | string
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

    $conflictingBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($conflictingBookings)) {
        return true;
    } else {
        return "The room is unfortunately not free between " . $arrivalDate . " and " . $departureDate . ".";
    }
}

function selectDate(string $name, string $arrivalDate, string $departureDate, string $roomID): bool | string
{
    if ($arrivalDate !== $departureDate) {
        if ($arrivalDate < $departureDate) {
            if (testDate($name, $arrivalDate, $departureDate, $roomID) === true) {
                return true;
            } else {
                return "The room is unfortunately not available between these dates";
            }
        } else {
            return "Date of departure has to be after the arrival.";
        }
    } else {
        return "Date of arrival can't be the same as date of departure";
    }
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

function transferCodeValidator(string $transfercode, int $totalCost): bool | string
{

    if (!isValidUuid($transfercode)) {
        return "Transfercode not valid.";
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
            return "Sorry, not enough money has been assigned to this transfercode. Please check the price displayed.";
        }

        return true;
    }
}

function countTotalCost(string $arrivalDate, string $departureDate, string $roomID): int
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


if (isset($_POST['name'], $_POST['arrival'], $_POST['departure'], $_POST['room'], $_POST['transfercode'])) {

    $name = htmlspecialchars($_POST['name']);
    $arrivalDate = htmlspecialchars($_POST['arrival']);
    $departureDate = htmlspecialchars($_POST['departure']);
    $roomID = htmlspecialchars($_POST['room']);

    $totalCost = countTotalCost($arrivalDate, $departureDate, $roomID);

    selectDate($name, $arrivalDate, $departureDate, $roomID, $totalCost);

    $transfercode = $_POST['transfercode'];

    $result = transferCodeValidator($transfercode, $totalCost);
    if ($result !== true) $message['error'] = $result;
    $result = selectDate($name, $arrivalDate, $departureDate, $roomID);
    if ($result !== true) $message['error'] = $result;

    if (isset($message['error'])) {
        echo json_encode($message);
    }
    // if (
    //     transferCodeValidator($transfercode, $totalCost) === true &&
    //     selectDate($name, $arrivalDate, $departureDate, $roomID) === true
    // ) {
    //     // insertDate($name, $arrivalDate, $departureDate, $roomID);
    //     // successfulBooking($arrivalDate, $departureDate, $totalCost);
    // } else {

    // }
}

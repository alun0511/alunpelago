<?php

require __DIR__ . '/hotelFunctions.php';
require __DIR__ . '/calendar.php';

function roomType($roomID)
{
    if ($roomID === 1) {
        echo "enkelrum";
    }
    if ($roomID === 2) {
        echo "dubbelrum";
    }
    if ($roomID === 3) {
        echo "svit";
    }
}

function selectDate(string $name, string $arrivalDate, string $departureDate, string $roomID)
{
    if (testDate($name, $arrivalDate, $departureDate, $roomID)) {
        echo "room is free";
        insertDate($name, $arrivalDate, $departureDate, $roomID);
    } else {
        echo "The room is unfortunately not available.";
    }
}

function testDate(string $name, string $arrivalDate, string $departureDate, string $roomID)
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

    print_r($unavailable);
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
    print_r($roomBookings);
    return $roomBookings;
}


// uses the array of dates from provided roomID (getBookings($roomID)) and adds these to three seperate calendars for each room.
function drawCalendar($roomID, $calendarID)
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

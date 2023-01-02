<?php

require __DIR__ . '/hotelFunctions.php';
require __DIR__ . '/calendar.php';

function roomType($roomID)
{
    if ($roomID === 1) {
        return "enkelrum";
    }
    if ($roomID === 2) {
        return "dubbelrum";
    }
    if ($roomID === 3) {
        return "svit";
    }
}

function bookDate(string $arrivalDate, string $departureDate, $roomID)
{

    // echo '<script type="text/javascript">',
    // 'confirmBooking();',
    // '</script>';
    // echo "Would you like to book room: " . $roomID . " from " . $arrivalDate . " until " . $departureDate . "?";
}

function selectDate(string $arrivalDate, string $departureDate, string $roomID)
{
    $dbName = 'database.db';
    $db = connect($dbName);

    $stmt = $db->prepare("SELECT * FROM room_reservation
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
        bookDate($arrivalDate, $departureDate, $roomID);
    } else {
        echo "The room is unfortunately not available.";
    }
}

function getBookings(int $roomID): array
{

    $dbName = 'database.db';
    $db = connect($dbName);

    $stmtReservations = $db->prepare("SELECT reservations.arrival_date, reservations.departure_date, rooms.id FROM room_reservation
    INNER JOIN rooms
        ON rooms.id = room_reservation.room_id
    INNER JOIN reservations
        ON reservations.id = room_reservation.reservation_id
    WHERE room_reservation.room_id = :room_id");

    $stmtReservations->bindParam(':room_id', $roomID);

    $stmtReservations->execute();

    $roomBookings = $stmtReservations->fetchAll(PDO::FETCH_ASSOC);

    return $roomBookings;
}


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

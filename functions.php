<?php

require __DIR__ . '/hotelFunctions.php';

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
        echo "VILL DU BOKA " . $arrivalDate . " till " . $departureDate . "?";
    }
}


if (isset($_POST['arrival'], $_POST['departure'], $_POST['room'])) {
    $arrivalDate = trim($_POST['arrival']);
    $departureDate = trim($_POST['departure']);
    $roomID = trim($_POST['room']);

    $booking = [
        "arrival_date" => $arrivalDate,
        "departure_date" => $departureDate,
        "roomID" => $roomID,
    ];

    $jsonBooking = json_encode($booking);

    selectDate($arrivalDate, $departureDate, $roomID);
}

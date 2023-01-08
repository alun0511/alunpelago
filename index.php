<?php

require 'vendor/autoload.php';
require __DIR__ . '/hotelFunctions.php';

$totalCost = "";



$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]logbook.json/";

// POST arrival, departure and room

if (isset($_POST['arrival'], $_POST['departure'], $_POST['room'])) {

    $name = trim($_POST['name']);
    $arrivalDate = $_POST['arrival'];
    $departureDate = $_POST['departure'];
    $roomID = trim($_POST['room']);

    $totalCost = countTotalCost($arrivalDate, $departureDate, $roomID);
    $message = "";

    if ($arrivalDate !== $departureDate) {

        if ($arrivalDate < $departureDate) {
            selectDate($name, $arrivalDate, $departureDate, $roomID, $totalCost, $actual_link);
        } else {
            $message = "Date of departure has to be after the arrival.";
        }
    } else {
        $message = "Date of arrival can't be the same as date of departure";
    }


    echo $message;
}


if (isset($_POST['transfercode'])) {
    $transfercode = $_POST['transfercode'];
    if (transferCodeValidator($transfercode, $totalCost) === true) {
        echo "code is valid";
    } else {
        echo "code is invalid";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunpelago</title>
    <link rel="stylesheet" href="./vendor/benhall14/php-calendar/html/css/calendar.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/global.css">
</head>

<body>
    <header>
        <nav>
            <h1>Moster Dagnys Vandrarhem</h1>
        </nav>
    </header>
    <main>
        <div class="calendar-wrapper">
            <div class="rooms">
                <h3>Enkelrum</h3>
                <?php drawCalendar(1, $calendar1); ?>
            </div>
            <div class="rooms">
                <h3>Dubbelrum</h3>
                <?php drawCalendar(2, $calendar2); ?>
            </div>
            <div class="rooms">
                <h3>Svit</h3>
                <?php drawCalendar(3, $calendar3); ?>
            </div>

        </div>
        <form action="" method="post">
            <label for="room">Choose a room:</label>
            <select name="room" required>
                <option value="1">Enkelrum</option>
                <option value="2">Dubbelrum</option>
                <option value="3">Svit</option>
            </select>
            <label for="arrival">Date of arrival:</label>
            <input type="date" name="arrival" class="form-input arrival" min="2023-01-01" max="2023-01-31" required>
            <label for="departure">Date of departure:</label>
            <input type="date" name="departure" class="form-input departure" min="2023-01-01" max="2023-01-31" required>
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-input" required>
            <label for="transfercode">Transfercode:</label>
            <input type="text" name="transfercode" class="form-input">
            <button name="submit" type="submit">Submit reservation</button>
        </form>

        <div class="totalcost">
            <h3> Price total: <?= $totalCost ?> credits</h3>
        </div>

    </main>
    <script src="script.js"></script>
</body>

</html>

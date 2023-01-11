<?php

require 'vendor/autoload.php';
require __DIR__ . '/hotelFunctions.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunpelago</title>
    <link rel="stylesheet" href="./vendor/benhall14/php-calendar/html/css/calendar.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/global.css">
</head>

<body>
    <header>
        <div class="hero">
            <!-- <img src="./pelago.jpg" alt="Pelago island"> -->
        </div>
        <nav>
            <h1>Moster Dagnys Vandrarhem</h1>
        </nav>
    </header>
    <main>
        <div class="calendar-wrapper">
            <div class="visible">
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
        <form action="./booking.php" method="post">
            <label for="room">Choose a room:</label>
            <select class="roomSelect" name="room" required>
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
            <button name="submit" type="submit">SUBMIT</button>
        </form>

        <div>
            <h4 class="totalcost">
            </h4>
        </div>

    </main>
    <script src="script.js"></script>
</body>

</html>

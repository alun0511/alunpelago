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
            <span>I'm finding it hard to believe..</span>
            <span>We're in heaven</span>
        </nav>
    </header>
    <main>
        <div class="calendar-wrapper">
            <div class="visible">
                <div class="room-text">
                    <h2>Enkelrum</h2>
                    <?php drawCalendar(1, $calendar1); ?>
                </div>
                <img src="./images/enkelrum.jpg">
            </div>
            <div class="rooms">

                <div class="room-text">
                    <h2>Dubbelrum</h2>
                    <?php drawCalendar(2, $calendar2); ?>
                </div>
                <img src="./images/dubbelrum.jpg">
            </div>
            <div class="rooms">
                <div class="room-text">
                    <h2>Svit</h2>
                    <?php drawCalendar(3, $calendar3); ?>
                </div>
                <img src="./images/svit.jpg">
            </div>

        </div>
        <section>
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
                <input type="text" name="name" class="form-input name" required>
                <label for="transfercode">Transfercode:</label>
                <input type="text" name="transfercode" class="form-input transfercode">
                <button name="submit" type="submit">SUBMIT</button>
                <div>
                    <h4 class="totalcost">
                    </h4>
                </div>
        </section>

        </form>
    </main>
    <script src="script.js"></script>
</body>

</html>
<?php

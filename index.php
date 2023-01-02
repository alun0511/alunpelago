<?php

require 'vendor/autoload.php';
require __DIR__ . '/calendar.php';
require __DIR__ . '/functions.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunpelago</title>
    <link rel="stylesheet" href="/css/calendar.css"><br>
    <link rel="stylesheet" href="/css/style.css"><br>
    <link rel="stylesheet" href="/css/global.css"><br>
</head>

<body>
    <header>
        <nav>
            <h1>Moster Dagnys Vandrarhem</h1>
        </nav>
    </header>
    <main>
        <div class="calendar-wrapper">
            <?= $calendar->draw(date('2023-01-01')) ?>
        </div>
        <form action="" method="post">
            <label for="room">Choose a room:</label>
            <select name="room" required="true">
                <option value="1">Enkelrum</option>
                <option value="2">Dubbelrum</option>
                <option value="3">Svit</option>
            </select>
            <label for="arrival">Date of arrival:</label>
            <input type="date" name="arrival" class="form-input" min="2023-01-01" max="2023-01-31">
            <label for="departure">Date of departure:</label>
            <input type="date" name="departure" class="form-input" min="2023-01-01" max="2023-01-31">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-input">
            <label for="voucher">Voucher:</label>
            <input type="text" name="voucher" class="form-input">
            <button name="submit" type="submit">Submit reservation</button>
        </form>

    </main>
    <script src="script.js"></script>
</body>

</html>

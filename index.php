<?php

require 'vendor/autoload.php';
require __DIR__ . '/calendar.php';
require __DIR__ . '/form.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/hotelFunctions.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;




$stmt = connect($database)->prepare("SELECT * FROM bookings");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunpelago</title>
</head>

<body>
    <?= $calendar->draw(date('2023-01-01')) ?> <br>
    <?php
    ?>
    <pre> <?php print_r($bookings) ?> </pre> <br>

    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-input">
        <label for="arrival">Date of arrival:</label>
        <input type="date" name="arrival" class="form-input" min="2023-01-01" max="2023-01-31">
        <label for="departure">Date of departure:</label>
        <input type="date" name="departure" class="form-input" min="2023-01-01" max="2023-01-31">

        <button name="submit" type="submit">Submit reservation</button>
    </form>


</body>

</html>

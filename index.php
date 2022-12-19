<?php

require __DIR__ . '/calendar.php';

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
    <?= $calendar->draw(date('2023-01-01')) ?>

    <form action="" methord="post">
        <label for="name">Name:</label>
        <input type="text" name="name" class="form-input">
        <label for="arrival">Date of arrival:</label>
        <input type="date" name="arrival" class="form-input" min="2023-01-01" max="2023-01-31">
        <label for="departure">Date of departure:</label>
        <input type="date" name="departure" class="form-input" min="2023-01-01" max="2023-01-31">
    </form>

</body>

</html>

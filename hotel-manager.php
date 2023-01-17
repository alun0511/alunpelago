<?php

require __DIR__ . '/app/autoload.php';
require __DIR__ . '/vendor/autoload.php';
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
        <nav>
            <h1>Moster Dagnys Vandrarhem</h1>
            <span>I'm finding it hard to believe..</span>
            <span>We're in heaven</span>
        </nav>
    </header>
    <main style="display:flex; flex-direction:column; align-items:center">
        <?php if (isset($_SESSION['user'])) : ?>
            <h2>Add features</h2>
            <form action="app/posts/addFeatures.php" method="post">
                <label for="featureName">Name of the feature</label>
                <input type="text" name="featureName" id="featureName" required>
                <label for="featureCost">features cost in $</label>
                <input type="number" name="featureCost" id="featureCost" required>
                <button type="submit">Add feature</button>
            </form>
        <?php else : ?>
            <h2>Login</h2>
            <form action="app/users/login.php" method="post">
                <label for="user">Username</label>
                <input type="text" name="user" id="user" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Login</button>
            </form>
        <?php endif ?>
    </main>
</body>

</html>

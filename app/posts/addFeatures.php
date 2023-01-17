<?php

declare(strict_types=1);
require '../autoload.php';
require '../../hotelFunctions.php';

if (isset($_POST['featureName'], $_POST['featureCost'])) {
    $featureName = htmlspecialchars(trim($_POST['featureName']), ENT_QUOTES);
    $featureCost = (int)htmlspecialchars(trim($_POST['featureCost']), ENT_QUOTES);

    $dbName = 'database.db';
    $db = connect($dbName);
}

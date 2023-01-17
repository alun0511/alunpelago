<?php

declare(strict_types=1);

if (!isset($_SESSION)) {
    session_start();
}

require '../../hotelFunctions.php';

$dbName = '../database.db';
$db = connect($dbName);

<?php

require __DIR__ . '/vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;


$calendar1 = new Calendar();
$calendar2 = new Calendar();
$calendar3 = new Calendar();

$calendar1->useMondayStartingDate();
$calendar2->useMondayStartingDate();
$calendar3->useMondayStartingDate();

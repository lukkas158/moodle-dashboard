<?php

$datetime1 = new DateTime('2009-10-13 12:12:00');
$datetime2 = new DateTime('2009-10-13 10:12:00');

if ($datetime1 > $datetime2) {
	echo 'datetime1 greater than datetime2';
}

if ($datetime1 < $datetime2) {
	echo 'datetime1 lesser than datetime2';
}

if ($datetime1 === $datetime2) {
	echo 'datetime2 is equal than datetime1';
}
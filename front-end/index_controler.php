<?php

require_once "auth.php";

$data = new StdClass;
$user = new StdClass;
$user->firstname = $USER->firstname;
$user->lastname = $USER->lastname;
$data->user = $user;

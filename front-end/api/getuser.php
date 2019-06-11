<?php
require "auth_api.php";

$user = new StdClass;

$user->firstname = $USER->firstname;
$user->lastname = $USER->lastname;

echo json_encode($user);
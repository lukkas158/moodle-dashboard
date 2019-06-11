<?php

require "auth_api.php";
require 'helpers.php';

$students = get_role_users(5, $context);
$result = Helpers::arrayassoc_to_array($students);
echo json_encode($result);
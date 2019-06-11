<?php

require 'auth_api.php';
require 'helpers.php';

$forum = $DB->get_records("forum", ["course" => $course->id]);

$forum = Helpers::arrayassoc_to_array($forum);
echo json_encode($forum);
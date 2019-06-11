<?php

require 'auth_api.php';
require 'helpers.php';

$sections = $DB->get_records("course_sections", ["course" => $course->id]);

$sections = Helpers::arrayassoc_to_array($sections);
echo json_encode($sections);
<?php

require_once "auth.php";
require_once "api/helpers.php";

$data = new StdClass;
$user = new StdClass;
$user->firstname = $USER->firstname;
$user->lastname = $USER->lastname;
$data->user = $user;
$sectionid = optional_param('section', NULL, PARAM_INT);
$section = $sections = $DB->get_records("course_sections", ["id" => $sectionid]);

if ($section) {
	$data->section = Helpers::arrayassoc_to_array($section);
} else {
	$data->section = NULL;
}

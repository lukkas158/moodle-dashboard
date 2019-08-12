<?php

require 'auth_api.php';
require 'helpers.php';

$resource = required_param("resource", PARAM_ALPHA);
$section = optional_param('section', NULL, PARAM_INT);
$resource_records = NULL;

if (isset($section) && is_int($section)) {
	$sql = "SELECT resource.* FROM {{$resource}} as resource INNER JOIN mdl_course_modules ON resource.id = mdl_course_modules.instance WHERE mdl_course_modules.section=? and resource.course=?";
	$resource_records = $DB->get_records_sql($sql, [$section, $course->id]);
	$resource_records = Helpers::arrayassoc_to_array($resource_records);
	echo json_encode($resource_records);
	die();
} else {
	$resource_records = $DB->get_records($resource, ["course" => $course->id]);
}

$resource_records = Helpers::arrayassoc_to_array($resource_records);
echo json_encode($resource_records);
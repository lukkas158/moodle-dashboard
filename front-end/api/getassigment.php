<?php

require 'auth_api.php';
require 'helpers.php';

$section = optional_param('section', NULL, PARAM_INT);
$assign = NULL;

if (isset($section) && is_int($section)) {
	$sql = "SELECT * FROM mdl_assign  INNER JOIN mdl_course_modules ON mdl_assign.id = mdl_course_modules.instance WHERE mdl_course_modules.section=? and mdl_assign.course=?";
	$assign = $DB->get_records_sql($sql, [$section, $course->id]);
	$assign = Helpers::arrayassoc_to_array($assign);
	echo json_encode($assign);
	die();
} else {
	$assign = $DB->get_records("assign", ["course" => $course->id]);
}

$assign = Helpers::arrayassoc_to_array($assign);
echo json_encode($assign);
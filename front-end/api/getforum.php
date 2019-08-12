<?php

require 'auth_api.php';
require 'helpers.php';

$forum = $DB->get_records("forum", ["course" => $course->id]);

$section = optional_param('section', NULL, PARAM_INT);

if (isset($section) && is_int($section)) {
	$sql = "SELECT * FROM mdl_forum  INNER JOIN mdl_course_modules ON mdl_forum.id = mdl_course_modules.instance WHERE mdl_course_modules.section=? and mdl_forum.course=?";
	$forum = $DB->get_records_sql($sql, [$section, $course->id]);
	$forum = Helpers::arrayassoc_to_array($forum);
	echo json_encode($forum);
	die();
}

$forum = Helpers::arrayassoc_to_array($forum);
echo json_encode($forum);
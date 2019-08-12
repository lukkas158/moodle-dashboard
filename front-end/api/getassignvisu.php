<?php

require 'auth_api.php';
require 'helpers.php';

$section = optional_param('section', NULL, PARAM_INT);
$assigns = NULL;

$students = get_role_users(5, $context);
$assignid = optional_param("assignid", NULL, PARAM_INT);

$ids = [];
foreach ($students as $id => $student) {
	$ids[] = $student->id;
}

$ids = implode(",", $ids);

// Estudandes que viram e não viram certa atividades.
if (isset($assignid) && is_int($assignid)) {
	$result = [];
	foreach ($students as $student) {
		$view = 0;
		$log = $DB->get_records_sql("SELECT COUNT(*) as qtyvisu FROM {logstore_standard_log} WHERE courseid='{$course->id}' AND component='mod_assign' AND action='viewed' AND objectid= ? AND userid IN ({$student->id})", [$assignid]);
		$qty = reset($log);
		if ($qty->qtyvisu > 0) {
			$view = 1;
		}
		$result[] = array('firstname' => $student->firstname, 'lastname' => $student->lastname, 'view' => $view);
	}
	echo json_encode($result);
	die();
}

if (isset($section) && is_int($section)) {
	$sql = "SELECT mdl_assign.* FROM mdl_assign  INNER JOIN mdl_course_modules ON mdl_assign.id = mdl_course_modules.instance WHERE mdl_course_modules.section=? and mdl_assign.course=?";
	$assigns = $DB->get_records_sql($sql, [$section, $course->id]);
} else {
	$assigns = $DB->get_records("assign", ["course" => $course->id]);
}

$result = [];
foreach ($assigns as $assign) {
	$sql = "SELECT COUNT(DISTINCT userid) as qtyvisu FROM {logstore_standard_log} WHERE courseid='{$course->id}' AND component='mod_assign' AND action='viewed' AND objectid={$assign->id} AND userid IN ({$ids})";
	$log = $DB->get_records_sql($sql);

	$qty = reset($log);
	$perc = $qty->qtyvisu / count($students);
	$result[] = array('name' => $assign->name, 'perc' => $perc, 'id' => $assign->id);
}

echo json_encode($result);

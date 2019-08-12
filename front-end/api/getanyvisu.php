<?php

require 'auth_api.php';
require 'helpers.php';

$resource = required_param("resource", PARAM_ALPHA);
$section = optional_param('section', NULL, PARAM_INT);
$resource_records = NULL;

$students = get_role_users(5, $context);
$resouceid = optional_param("resouceid", NULL, PARAM_INT);

$ids = [];
foreach ($students as $id => $student) {
	$ids[] = $student->id;
}

$ids = implode(",", $ids);

// Estudandes que viram e não viram certo recurso.
if (isset($resouceid) && is_int($resouceid)) {
	$result = [];
	foreach ($students as $student) {
		$view = 0;
		$log = $DB->get_records_sql("SELECT COUNT(*) as qtyvisu FROM {logstore_standard_log} WHERE courseid='{$course->id}' AND component='mod_{$resource}' AND action='viewed' AND objectid= ? AND userid IN ({$student->id})", [$resouceid]);
		$qty = reset($log);
		if ($qty->qtyvisu > 0) {
			$view = 1;
		}
		$result[] = array('firstname' => $student->firstname, 'lastname' => $student->lastname, 'view' => $view);
	}
	echo json_encode($result);
	die();
}

// Por secção.

if (isset($section) && is_int($section)) {
	$sql = "SELECT resource.* FROM {{$resource}} as resource  INNER JOIN mdl_course_modules ON resource.id = mdl_course_modules.instance WHERE mdl_course_modules.section=? and resource.course=?";
	$resource_records = $DB->get_records_sql($sql, [$section, $course->id]);
} else {
	$resource_records = $DB->get_records($resource, ["course" => $course->id]);
}

$result = [];
foreach ($resource_records as $resource_record) {
	$sql = "SELECT COUNT(DISTINCT userid) as qtyvisu FROM {logstore_standard_log} WHERE courseid='{$course->id}' AND component='mod_{$resource}' AND action='viewed' AND objectid={$resource_record->id} AND userid IN ({$ids})";
	$log = $DB->get_records_sql($sql);

	$qty = reset($log);
	$perc = $qty->qtyvisu / count($students);
	$result[] = array('name' => $resource_record->name, 'perc' => $perc, 'id' => $resource_record->id);
}

echo json_encode($result);

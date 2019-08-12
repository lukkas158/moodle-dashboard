<?php

require 'auth_api.php';
require 'helpers.php';

$foruns = $DB->get_records("forum", ["course" => $course->id]);
$students = get_role_users(5, $context);
$forumid = optional_param("forumid", NULL, PARAM_INT);

$ids = [];
foreach ($students as $id => $student) {
	$ids[] = $student->id;
}

$ids = implode(",", $ids);

if (isset($forumid) && is_int($forumid)) {
	$result = [];
	foreach ($students as $student) {
		$view = 0;
		$log = $DB->get_records_sql("SELECT COUNT(*) as qtyvisu FROM {logstore_standard_log} WHERE courseid='{$course->id}' AND component='mod_forum' AND action='viewed' AND objectid= ? AND userid IN ({$student->id})", [$forumid]);
		$qty = reset($log);
		if ($qty->qtyvisu > 0) {
			$view = 1;
		}
		$result[] = array('firstname' => $student->firstname, 'lastname' => $student->lastname, 'view' => $view);
	}
	echo json_encode($result);
	die();
}

$result = [];
foreach ($foruns as $forum) {
	$log = $DB->get_records_sql("SELECT COUNT(DISTINCT userid) as qtyvisu FROM {logstore_standard_log} WHERE courseid='{$course->id}' AND component='mod_forum' AND action='viewed' AND objectid={$forum->id} AND userid IN ({$ids})");
	$qty = reset($log);
	$perc = $qty->qtyvisu / count($students);
	$result[] = array('name' => $forum->name, 'perc' => $perc, 'id' => $forum->id);
}

echo json_encode($result);

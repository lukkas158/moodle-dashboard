<?php

require "auth_api.php";
require "helpers.php";

$userid = optional_param("studentid", NULL, PARAM_INT);
$students = get_role_users(5, $context);

$ids = [];
foreach ($students as $id => $student) {
	$ids[] = $student->id;
}

// GET STUDENT WITH ID
if (isset($userid) && is_int($userid)) {
	if (!in_array($userid, $ids)) {
		die();
	}
	$logs = $DB->get_records_sql("SELECT timecreated FROM {logstore_standard_log} WHERE courseid=15 AND action='viewed' AND target='course' AND userid IN (?) ORDER BY timecreated ASC", [$userid]);
	echo json_encode(Helpers::frequency_count($logs));
	die();
}

// GET ALL STUDENT FREQUENCY

$ids = implode(",", $ids);

$logs = $DB->get_records_sql("SELECT timecreated FROM {logstore_standard_log} WHERE courseid=15 AND action='viewed' AND target='course' AND userid IN ({$ids}) ORDER BY timecreated ASC");

//Get the max and min Timestamp
$result = Helpers::frequency_count($logs);
echo json_encode($result);
die();

<?php
require "../../../../config.php";

function require_teacher_login() {
	require_login();
	$courseid = required_param("courseid", PARAM_INT);
	$course = $DB->get_record("course", ["id" => $courseid], "*", MUST_EXIST);

	$context = context_course::instance($course->id);
	$roles = get_user_roles($context, $USER->id);
	$teacher = false;
	if ($roles) {
		foreach ($roles as $role) {
			// Teacher ID Role é 3.
			if ($role->roleid == 3) {
				$teacher = true;
			}
		}
	}

	if (!$teacher) {
		header("Location:" . $CFG->wwwroot);
	}

}

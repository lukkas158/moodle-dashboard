<?php
require "../../../config.php";

// Object passado para index.php
$data = new StdClass;

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

// Usado para pegar um usuário em específico.
$students = get_role_users(5, $context);

$data->student_qty = count($students);
$data->course_shortname = $course->shortname;

//print_object($data);

?>
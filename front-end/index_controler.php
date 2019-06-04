<?php
require "../../../config.php";
// Object passado para index.php
$data = new StdClass;

require_login();
$courseid = required_param("courseid", PARAM_INT);
$studentid = optional_param("studentid", NULL, PARAM_INT);
$course = $DB->get_record("course", ["id" => $courseid], "*", MUST_EXIST);
$info = get_fast_modinfo($course);
print_object($info);

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
$data->courseid = $course->id;
$data->students = $students;
$comma = [];
if ($studentid && is_int($studentid)) {
	$comma = $studentid;
	$data->student = $students[$studentid];
} else {
	$students_id = [];
	foreach ($students as $student) {
		$students_id[] = $student->id;
	}
	$comma = implode(",", $students_id);
}

$logs = $DB->get_records_sql("SELECT timecreated FROM moodle.mdl_logstore_standard_log WHERE action='loggedin' AND userid IN ({$comma}) order by timecreated");

$max = end($logs);
$min = reset($logs);

$maxd = new DateTime();
$mind = new DateTime();
$maxd->setTimestamp($max->timecreated);
$mind->setTimestamp($min->timecreated);
$maxd = new DateTime($maxd->format("Y-m-d"));
$mind = new DateTime($mind->format("Y-m-d"));

$result = [];
for ($i = $mind; $i <= $maxd; $i->modify("+1 day")) {
	$result[$i->format("Y-m-d")] = 0;
	foreach ($logs as $log) {
		$date = (new DateTime())->setTimestamp($log->timecreated);
		if ($i->format("Y-m-d") == $date->format("Y-m-d")) {
			$result[$i->format("Y-m-d")] += 1;
		}
	}
}

$data->loggedin = $result;

// foreach($logs as $log) {

// }

?>
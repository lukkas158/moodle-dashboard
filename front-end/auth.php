<?php

require "../../../config.php";
$courseid = required_param("courseid", PARAM_INT);
$course = $DB->get_record("course", ["id" => $courseid], "*", MUST_EXIST);
require_login($courseid);
$context = context_course::instance($course->id);
require_capability("block/behaviorlytics:viewdash", $context);

<?php

class Helpers {
	// Moodle uses the id/first colum to associate with the register, including the id itself.
	// Example  1, foo,boo; 2, Too, Baa; it created
	// [1 => [id=> 1 , firstname=> foo, lastname=> boo ],
	//  2 => [id=> 2 , firstname=> Too, lastname=> Baa ] ]
	// So, this function strip this id=s and return a array.
	public static function arrayassoc_to_array($data) {
		$result = [];
		foreach ($data as $dt) {
			$result[] = $dt;
		}
		return $result;
	}

	public static function frequency_count($logs) {
		if (count($logs) == 0) {
			return [];
		}
		$max_log = end($logs);
		$min_log = reset($logs);

		// Create Datetime from timestamp
		$max = new DateTime();
		$min = new DateTime();
		$max->setTimestamp($max_log->timecreated);
		$min->setTimestamp($min_log->timecreated);
		// I
		$max = new DateTime($max->format("Y-m-d"));
		$min = new DateTime($min->format("Y-m-d"));

		$result = [];
		for ($i = $min; $i <= $max; $i->modify("+1 day")) {
			$result[$i->format("Y-m-d")] = 0;
			foreach ($logs as $log) {
				$date = (new DateTime())->setTimestamp($log->timecreated);
				if ($i->format("Y-m-d") == $date->format("Y-m-d")) {
					$result[$i->format("Y-m-d")] += 1;
				}
			}
		}

		return $result;
	}

}

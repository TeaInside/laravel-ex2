<?php

$r = [];
foreach (scandir("app/Models") as $key => $value) {
	if ($value !== "." and $value !== "..") {
		$a = explode("\n", file_get_contents("app/Models/".$value));
		if (isset($a[3])) {
			if (trim($a[3]) === "") {
				$a[3] = "use DB;";
			}
			file_put_contents("app/Models/{$value}", implode("\n", $a));
			print $value.PHP_EOL;;
		}
	}
}

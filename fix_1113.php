<?php
if ($argc < 2) exit("Usage: $argv[0] YYYYMMDD\n");

$date = $argv[1];

$data = explode("\n", trim(file_get_contents("data/$date.csv")));
$today = explode("\n", trim(file_get_contents("today/$date.csv")));
$output = '';

$floor = [];

foreach ($data as $row) { 
	$row = explode(",", $row);
	if (!isset($floor[$row[1]]) || $row[2] < $floor[$row[1]]) $floor[$row[1]] = $row[2];
}

foreach ($today as $row) {
	$row = explode(",", $row);
	$output .= $row[0].','.$row[1].','.($row[2] + $floor[$row[1]])."\n";
}

file_put_contents("data/$date.csv", $output);
system("php raw2table.php data/$date.csv data_table/$date.csv");
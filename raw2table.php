<?php
if ($argc < 3) exit("Usage: $argv[0] input-file output-file\n");

$fn_in = $argv[1];
$fn_out = $argv[2];

$input = explode("\n", trim(file_get_contents($fn_in)));
$output = '';

$table = [];

foreach($input as $row) {
	$row = explode(",", $row);
	if(!isset($table[$row[0]])) $table[$row[0]] = [];
	$table[$row[0]][] = $row[2];
}

$output .= 'ts,'.implode(',', range(1, count(reset($table))))."\n";

foreach($table as $key => $row) {
	$output .= $key.','.implode(',', $row)."\n";
}

file_put_contents($fn_out, $output);

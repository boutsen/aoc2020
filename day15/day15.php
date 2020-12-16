<?php

$input = "0,12,6,13,20,1,17";

$in = explode(",",$input);


function solve($arr,$max_i)
{
	$spoken = array_fill(0,$max_i,-1);
	foreach( $arr as $i => $v)
		$spoken[$v] = $i+1;
	
	$last_spoken = end($arr);
	
	for( $i = count($arr); $i < $max_i; $i++)
	{
		$next = $spoken[$last_spoken] == -1 ? 0 : $i-$spoken[$last_spoken];
		$spoken[$last_spoken] = $i;
		$last_spoken = $next;
	}
	return $last_spoken;
}

function solve_old($arr,$max_i)
{
	$spoken = [];
	foreach( $arr as $i => $v)
		$spoken[$v] = $i+1;
	
	$last_spoken = end($arr);
	
	for( $i = count($arr); $i < $max_i; $i++)
	{
		$next = !array_key_exists($last_spoken,$spoken) ? 0 : $i-$spoken[$last_spoken];
		$spoken[$last_spoken] = $i;
		$last_spoken = $next;
	}
	return $last_spoken;
}

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in,2020);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day15-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

ini_set("memory_limit", "-1");
// Part 2
$start_time = microtime(true); 
$part2 = solve($in,30000000);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day15-part2: " . $part2 . " and took " . round($execution_time,2) . " sec." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;
<?php
set_time_limit(0);
function readNumbers($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	
	return array_map("intval",$array);
}

$test = function($array,$sum)
{
	$result = 0;
	foreach( $array as $v)
	{
		$result += $v;
	}
	if ( $result == $sum )
		return true;
	return false;
};

$sol = function($array)
{
	$result = 1;
	foreach( $array as $v )
	{
		$result *= $v;
	}
	return $result;
};

function findSum($array,$sum,$depth,$testFn,$solFn, $nums = [])
{
	
	if ( $depth == 0 && $testFn($nums,$sum)  ){
		return $solFn($nums);
	}
	
	if ( $depth > 0 ){
		foreach( $array as $i => $v )
		{
			if ( !array_key_exists($i,$nums) ){
				$new = $nums;
				$new[$i] = $v;
				if ( ($found = findSum($array,$sum,$depth - 1,$testFn,$solFn, $new)) )
				{
					return $found;
				}
			}
		}
	} 
}

$measure = function($text,$nums,$sum,$depth,$testFn,$solFn) {
	$start_time = microtime(true); 
	
	$result = findSum($nums,$sum,$depth,$testFn,$solFn);
	
	$end_time = microtime(true); 
	$execution_time = ($end_time - $start_time); 
	
	echo $text . $result . " and took " . $execution_time . " sec;" .PHP_EOL;
};


$nums = readNumbers("input.txt");

$measure("Solution day1-part1: ", $nums,2020,2,$test,$sol);
$measure("Solution day1-part2: ", $nums,2020,3,$test,$sol);






?>
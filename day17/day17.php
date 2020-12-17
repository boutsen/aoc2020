<?php 

function readLines($filename,$dims)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	$cube = [];	
	$size = count($array);
	foreach( $array as $y => $line )
		for( $x = 0; $x < $size; $x++ )
			if ( $line[$x] === '#' )
				$cube[format(array_merge([$x,$y],$dims))] = 1;
	ksort($cube);
	return $cube;
}

function format($arr)
{
	return implode(",",$arr);
}

function unformat($str)
{
	return explode(",",$str);
}

function walk($cube,$dims,&$offsets)
{
	$next = [];
	$adj = [];
	
	foreach( $cube as $key => $v)
		adj(unformat($key),$adj,$offsets);
	foreach( $adj as $key => $l ){
		if( !empty($cube[$key])){
			if( $l == 2 || $l == 3)
				$next[$key] = 1;
		}
		else if ( $l == 3 )
			$next[$key] = 1;
	}
	ksort($next);	
	return $next;
}

function adj($key, &$adj, &$offsets)
{
	foreach( $offsets as $d )
	{
		$new_k = $key;
		foreach( $d as $i => $v )
			$new_k[$i] += $v;
		$new_key = format($new_k);
		$adj[$new_key] = ($adj[$new_key]??0)+1;
	}
}

function create_offsets(&$offsets,$dim,&$res,$val)
{
	if ( count($val) == $dim ){
		$all_zero = true;
		foreach($val as $v){
			if ( $v != 0 ){
				$all_zero = false;
				break;
			}
		}
		if ( !$all_zero )
			$res[] = $val;
	}
	else
		foreach( $offsets as $off)
			create_offsets($offsets,$dim,$res,array_merge($val,[$off]));
	return;
}


function solve($file,$dim,$it)
{
	$dims = [];
	$offsets = [-1,0,1];
	$calc_offsets = [];
	
	create_offsets($offsets,$dim,$calc_offsets,[]);
	
	for( $i = 0; $i < $dim - 2; $i++)
		$dims[] = 0; 
		
	$cube = readLines($file,$dims);
	
	for($i = 0; $i < $it; $i++)
		$cube = walk($cube,$dims,$calc_offsets);
	
	return array_sum($cube);
}


$file = "input.txt";

/// Part 1
$start_time = microtime(true); 
$part1 = solve($file,3,6);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day17-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve($file,4,6);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day17-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
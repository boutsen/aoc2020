<?php 

function readNumbers($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	return $array;
}

$pwds = readNumbers("input.txt");

$count = 0; 
$count2 = 0; 

foreach( $pwds as $pwd )
{
	$all = explode(" ",$pwd);
	
	$minmax = explode("-",$all[0]);
	$minmax = array_map("intval",$minmax);
	$char = explode(":",$all[1]);
	$pass = $all[2];
	
	
	$passcheck = substr_count($pass,$char[0]);
	// Part 1
	if ( $passcheck >= $minmax[0] && $passcheck <= $minmax[1] )
		$count++;
		
	// Part 2
	if( ($pass[$minmax[0]-1] == $char[0] && $pass[$minmax[1]-1] != $char[0]) || ($pass[$minmax[0]-1] != $char[0] && $pass[$minmax[1]-1] == $char[0]))
		$count2++;
	
	//die();
}

echo "Solution day2-part1: " . $count . PHP_EOL;
echo "Solution day2-part2: " . $count2 . PHP_EOL;
?>



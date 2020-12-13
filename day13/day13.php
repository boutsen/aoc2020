<?php 
function readLines($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	return $array;
}

function solve($in)
{
	$time = intval($in[0]);
	preg_match_all('/[0-9]+/', $in[1], $busIDs);
	
	$closest = [];
	foreach($busIDs[0] as $bus){
		$closest[$bus] = ceil($time/$bus)*$bus;
	}
	
	$match = min($closest);
	$bus = array_search($match, $closest);
	
	return ($match-$time)*$bus;
}

function solve2($in)
{
	$input = explode(",",$in[1]);
	$buss = array_diff($input,["x"]);
		
	$m = $buss[0];
	$i = 0;
	
	foreach( array_diff($buss,[$buss[0]]) as $index => $bus){
		while(true)
		{
			if (($i+$index) % intval($bus) == 0 ){
				$m *= intval($bus);
				break;
			}
			$i += $m;
		}
	}
		
	return $i;
}
$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day13-reading took " . $execution_time*1000 . " ms." . PHP_EOL;

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day13-part1: " . $part1 . " and took " . $execution_time*1000 . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve2($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day13-part2: " . $part2 . " and took " . $execution_time*1000 . " ms." . PHP_EOL;


?>
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


function solve(&$arr)
{
	$NS = 0;
	$EW = 0;
	$facing = 'E';
	$wind = ['N','E','S','W'];
	foreach( $arr as $instruction )
	{
		preg_match("/([A-Z]+)([0-9]+)/", $instruction, $matches);
		switch($matches[1])
		{
			case "F": 
				if ( $facing == 'N' || $facing == 'S' )
					$NS += ( $facing == 'N' ? $matches[2] : -$matches[2] );
				else 
					$EW += ( $facing == 'E' ? $matches[2] : -$matches[2] );
			break;
			case "N": $NS += $matches[2]; break;
			case "E": $EW += $matches[2]; break;
			case "S": $NS -= $matches[2]; break;
			case "W": $EW -= $matches[2]; break;
			case "L": $facing = $wind[((array_search($facing, $wind) - $matches[2] / 90) + 4) % 4]; break;
			case "R": $facing = $wind[((array_search($facing, $wind) + $matches[2] / 90) + 4) % 4]; break;
		}
	}
	return (abs($NS) + abs($EW));
}

function solve2(&$arr)
{
	$S_NS = 0;
	$S_EW = 0;
	$W_NS = 1;
	$W_EW = 10;
	
	foreach( $arr as $instruction )
	{
		preg_match("/([A-Z]+)([0-9]+)/", $instruction, $matches);
		switch($matches[1])
		{
			case "F": 
				$S_NS += $W_NS * $matches[2]; 
				$S_EW += $W_EW * $matches[2]; 
			break;
			case "N": $W_NS += $matches[2];break;
			case "E": $W_EW += $matches[2];break;
			case "S": $W_NS -= $matches[2];break;
			case "W": $W_EW -= $matches[2];break; 
			case "R":
				$matches[2] = $matches[2] == 90 ? 270 : ($matches[2] == 270 ? 90 : $matches[2]);
			case "L":  
				if ( $matches[2] == 90 ) {
					$wn = -$W_NS;
					$W_NS = $W_EW;
					$W_EW = $wn;
				}
				if ( $matches[2] == 180 ) {
					$W_EW = -$W_EW;
					$W_NS = -$W_NS;
				}
				if ( $matches[2] == 270 ) {
					$wn = $W_NS;
					$W_NS = -$W_EW;
					$W_EW = $wn;
				}
			break;
		}
	}
	
	return (abs($S_NS) + abs($S_EW));
}

$in = readLines("input.txt");

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day12-part1: " . $part1 . " and took " . $execution_time*1000 . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve2($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day12-part2: " . $part2 . " and took " . $execution_time*1000 . " ms." . PHP_EOL;
echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;

?>
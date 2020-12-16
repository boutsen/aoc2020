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



function findSeatID($str,$res)
{
	foreach( str_split($str) as $char )
	{
		switch( $char )
		{
			case 'F' :
				$res[1] = floor($res[1] - (($res[1]-$res[0])/2));
			break;
			case 'B' :
				$res[0] = ceil($res[0] + (($res[1]-$res[0])/2));
			break;
			case 'L' :
				$res[3] = floor($res[3] - (($res[3]-$res[2])/2));
			break;
			case 'R' :
				$res[2] = ceil($res[2] + (($res[3]-$res[2])/2));
			break;
		}
	}
	return ($res[0]*8) + $res[2];
}


$in = readLines("input.txt");

$max = 0;
$mySeat = 0;
$seatIds = [];

foreach( $in as $boardingpass )
{
	$seatID = findSeatID($boardingpass,[0,127,0,7]);
	$max = max($max,$seatID);
	array_push($seatIds,$seatID);
}

for( $i = 0; $i < count($seatIds); $i++)
{
	for( $j = $i; $j < count($seatIds); $j++){
		if ( $seatIds[$i] - $seatIds[$j] == 2 )
		{
			$m = ($seatIds[$i] + $seatIds[$j]) / 2;
			if ( !in_array($m,$seatIds) )
				$mySeat = $m;
		}
	}
}

echo "Solution day5-part1: " . $max . PHP_EOL;
echo "Solution day5-part2: " . $mySeat . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
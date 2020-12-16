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



$in = readLines("input.txt");

$max = 0;
$mySeat = 0;
$seatIds = [];
foreach( $in as $b )
{
	$seatId = bindec(preg_replace("/(F|L)/","0",preg_replace("/(B|R)/","1",$b)));
	$max = max($max,$seatId);
	array_push($seatIds,$seatId);
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
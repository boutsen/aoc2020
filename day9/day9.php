<?php 
function readLines($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	return array_map("intval",$array);
}

function findInvalidSum($arr, $preamble)
{
	$i = $preamble-1;	
	while( $i++ < count($arr) )
	{
		$valid = false;
		for($j=$i-$preamble;$j<$i;$j++)
			for($k=$j;$k<$i;$k++)
				if($arr[$j] + $arr[$k] == $arr[$i])
					$valid = true;
		if( !$valid )
			return $arr[$i];
	}
}

function findWeakness($arr,$invalid)
{
	for($i=0;$i<count($arr);$i++)
	{
		$sum = $arr[$i];
		$array = [$sum];
		for($j=$i+1;$j<count($arr);$j++)
		{
			$sum+=$arr[$j];
			$array[] = $arr[$j];
			if($sum == $invalid)
				return((min($array) + max($array)));
		}
	}
}


$numbers = readLines("input.txt");
$preamble = 25;


$start_time = microtime(true); 
$calc = findInvalidSum($numbers,$preamble);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

$start_time = microtime(true); 
$calc2 = findWeakness($numbers,$calc);
$end_time = microtime(true); 
$execution_time2 = ($end_time - $start_time); 

echo "Solution day9-part1: " . $calc . " and took " . $execution_time . " sec." . PHP_EOL;
echo "Solution day9-part2: " . $calc2 . " and took " . $execution_time2 . " sec." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;
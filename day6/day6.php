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

function sanitize($array) {
	$result = [];
	
	$pp = "";
	foreach($array as $line)
	{
		if ( empty(trim($line)) ){
			$d = explode(" ",preg_replace("/[^a-zA-Z ]/", "",trim($pp)));
			array_push($result,$d);
			$pp = "";
		}
		$pp .= "$line ";
	}
	return $result;
}

$in = readLines("input.txt");

$groups = sanitize($in);
$sum = 0;
$sum2 = 0;
foreach( $groups as $group )
{
	$sum += count( array_unique( str_split(implode("",$group))));
	
	
	$intersect = str_split($group[0]);
	for ( $i = 1; $i < count($group); $i++)
	{
		$intersect = array_intersect($intersect,str_split($group[$i]));
	}
	
	$sum2 += count($intersect); 
}


echo "Solution day6-part1: " . $sum. PHP_EOL;
echo "Solution day6-part2: " . $sum2 . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
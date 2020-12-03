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

function calculateTrees($array, $slope)
{
	$width = strlen($array[0])-1;
	$count = 0; 	
	for( $i = $slope[1], $j = 1; $i < count($array); $i += $slope[1], $j++)
	{
		if ( $array[$i][(($j * $slope[0]) % $width)] == '#' )
			$count++;
	}
	return $count;
}

$sol = function($array)
{
	$result = 1;
	foreach( $array as $v )
	{
		$result *= $v;
	}
	return $result;
};



$trees = readNumbers("input.txt");

$result = [];
foreach( [[1,1],[3,1],[5,1],[7,1],[1,2]] as $i => $slope )
{
	$result[$i] = calculateTrees($trees,$slope);
}

echo "Solution day3-part1: " . $result[1] . PHP_EOL;
echo "Solution day3-part2: " . $sol($result) . PHP_EOL;

?>
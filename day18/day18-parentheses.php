<?php 
function readLines($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	array_map('trim',$array);
	return $array;
}

function getIndex(&$str,&$i,$left = false)
{
	$arr = str_split($str);
	$count = 0;
	if ( $left )
	{
		for($ii = $i; $ii >= 0; $ii--) 
		{
			if ( $arr[$ii] == ")" )
				$count++;
			if ( $arr[$ii] == "(" )
				$count--;
			if ( $count == 0 )
				return $ii;
		}
	}
	else {
		for($ii = $i; $ii < count($arr); $ii++) 
		{
			if ( $arr[$ii] == "(" )
				$count++;
			if ( $arr[$ii] == ")" )
				$count--;
			if ( $count == 0 )
				return $ii;
		}
	}
}

function addparentheses($input)
{
	while( preg_match("/((.) \+ (.))/",$input,$matches) )
	{
		$i = strpos($input,$matches[1]);
		$i1 = $i;
		$i2 = $i+4;
		if ( $matches[2] == ")" ) 
			$i1 = getIndex($input,$i1,true);
		if ( $matches[3] == "(" ) 
			$i2 = getIndex($input,$i2,false);
		$input = substr($input,0,$i1) . "(" . substr($input,$i1,$i-$i1+1) . " ? " . substr($input,$i+4,$i2-$i-3) . ")" . substr($input,$i2+1);
	}
	return str_replace("?","+",$input);
}

function executeMath(&$operator,&$one,&$two)
{
	if ( $operator == "+" )
		return intval($one)+intval($two);
	if ( $operator == "*" )
		return intval($one)*intval($two);
}

function solveLTR($eq)
{
	if ( is_numeric($eq) )
		return $eq;
	$tokens = explode(' ', $eq);
    while (count($tokens) > 1) {
        $part1 = array_shift($tokens);
        $operator = array_shift($tokens);
        $part2 = array_shift($tokens);
		array_unshift($tokens,executeMath($operator,$part1,$part2));
    }
    return array_shift($tokens);
}

function solveparentheses($eq)
{
	while( strpos($eq,'(') !== false)
	{
		preg_match('/\(([^()]+)\)/', $eq, $matches);
		$eq = str_replace('(' . $matches[1] . ')',doMath($matches[1]),$eq);
	}
	return $eq;
}



function doMath($eq)
{
	$eq = solveparentheses($eq);
	$eq = solveLTR($eq);
	return intval($eq);
}

function solve($arr,$r = false)
{
	$sum = 0;
	foreach($arr as $eq)
		$sum+= doMath($r ? addparentheses($eq) : $eq);
	return $sum;
}

$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day18-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day18-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve($in,true);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day18-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;

?>
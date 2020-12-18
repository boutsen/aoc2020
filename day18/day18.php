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


function executeMath(&$operator,&$one,&$two)
{
	if ( $operator == "+" )
		return intval($one)+intval($two);
	if ( $operator == "*" )
		return intval($one)*intval($two);
}

function solveparentheses($eq,&$orderOfRules)
{
	while( strpos($eq,'(') !== false)
	{
		preg_match('/\(([^()]+)\)/', $eq, $matches);
		$eq = str_replace('(' . $matches[1] . ')',doMath($matches[1],$orderOfRules),$eq);
	}
	return $eq;
}

function solveOperation($eq,$operator)
{
	while( strpos($eq,$operator) !== false)
	{
		preg_match('/((\d+) '.preg_quote($operator).' (\d+))/', $eq, $matches);
		$eq = preg_replace('/' . preg_quote($matches[1], '/') . '/',executeMath($operator,$matches[2],$matches[3]),$eq,1);
	}
	return $eq;
}

function solveLTR($eq)
{
	$tokens = explode(' ', $eq);
    while (count($tokens) > 1) {
        $part1 = array_shift($tokens);
        $operator = array_shift($tokens);
        $part2 = array_shift($tokens);
		array_unshift($tokens,executeMath($operator,$part1,$part2));
    }
    return array_shift($tokens);
}



function doMath($eq,&$orderOfRules)
{
	foreach($orderOfRules as $rule)
	{
		if ( $rule == "()" )
			$eq = solveparentheses($eq,$orderOfRules);
		else if ( $rule == "LTR" )
			$eq = solveLTR($eq);
		else
			$eq = solveOperation($eq,$rule);
	}
	return intval($eq);
}

function solve(&$arr,$orderOfRules)
{
	$sum = 0;
	foreach($arr as $eq)
		$sum+= doMath($eq,$orderOfRules);
	return $sum;
}



$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day18-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in,["()","LTR"]);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day18-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve($in,["()","+","*"]);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day18-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;

?>
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

function parseInput(&$arr)
{
	$rules = [];
	$ticket = [];
	$ticket_nearby = [];
	
	$prev = 0;
	
	foreach( $arr as $i => $line )
	{
		if ( !empty(trim($line)) ){
			if ( substr($line,0,strlen("your ticket")) == "your ticket" )
				$prev = 1;
			else if ( substr($line,0,strlen("nearby tickets"))  == "nearby tickets" )
				$prev = 2;
			else if( preg_match("/([a-z ]*): (\d+)-(\d+) or (\d+)-(\d+)/",$line,$matches) )
			{
				//$rules[$matches[1]] = [[intval($matches[2]),intval($matches[3])],[intval($matches[4]),intval($matches[5])]];	
				$rules[$matches[1]] = [];
				for( $i = $matches[2]; $i <= $matches[3]; $i++ )
					array_push($rules[$matches[1]],$i);
				for( $i = $matches[4]; $i <= $matches[5]; $i++ )
					array_push($rules[$matches[1]],$i);
			}
			else {
				if ( $prev == 1 )
					$ticket = array_map('intval', explode(',', $line ));
				if ( $prev == 2 )
					$ticket_nearby[] = array_map('intval', explode(',', $line ));
			}
		}
		
	}
	return ["rules"=>$rules,"yt" => $ticket,"nb"=>$ticket_nearby];
}



function solve(&$in)
{
	$valid = [];
	foreach( $in["rules"] as $rule )
	{
		foreach( $rule as $i=>$r )
		{
			if ( !in_array($r,$valid) )
				array_push($valid,$r);
		}
	}
	
	$invalid = 0;
	$valid_tickets = [];
	
	foreach( $in["nb"] as $nb )
	{
		$v = true;
		foreach( $nb as $n ){
			if( !in_array($n,$valid) ){
				$invalid += $n;
				$v = false;
			}
		}
		if ( $v )
			$valid_tickets[] = $nb;
	}
	
	$in["nb"] = $valid_tickets;
	
	return $invalid;
}

function getPossibilities(&$rules,&$arr)
{
	$poss = array_keys($rules);
	
	foreach( $arr as $val )
		foreach( $rules as $k => $v )
			if( !in_array($val,$v) )
				$poss = array_diff($poss,[$k]);
	return $poss;
}

function reducePossibilities(&$sorted,$prefix)
{
	array_multisort(array_map('count', $sorted), SORT_ASC, $sorted);
	
	$keys = array_keys($sorted);
	foreach( $keys as $i )
	{
		$key = reset($sorted[$i]);
		foreach( $keys as $j )
			if ( $i != $j )
				$sorted[$j] = array_diff($sorted[$j],[$key]);
	}
	
	$indexes = [];
	foreach($sorted as $index => $key)
		$indexes[reset($key)] = intval(str_replace($prefix,"",$index));
		
		
	return $indexes;
}


function solve2($in)
{	
	$prefix = "index";
	$result = 1;
	$values = [];
	$poss = [];
	foreach( $in["nb"] as $nb )
		foreach( $nb as $i=>$v)
			$values[$i][] = $v;

	foreach( $values as $i => $value )
		$poss[$prefix.$i] = getPossibilities($in["rules"],$value);	
		
	$poss = reducePossibilities($poss,$prefix);
	
	foreach( $poss as $key => $index )
		if ( substr($key,0,strlen("departure")) == "departure" )
			$result *= $in["yt"][$index];
	
	return $result;
}


$start_time = microtime(true); 
$in = readLines("input.txt");
$in = parseInput($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day16-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day16-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve2($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day16-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;
?>
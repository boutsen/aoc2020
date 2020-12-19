<?php 

$d =0;
function readLines($filename)
{	
	$array = null;
	$result = ["rules"=>[],"input"=>[]];
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", trim(fread($fp, filesize($filename))));
	}
	
	foreach( $array as $line )
	{
		if ( preg_match("/\d+: .*/",$line,$matches) ){
			$arr = explode(": ",$matches[0]);
			if ( preg_match("/[a-z]/",$arr[1], $char) )
				$result["rules"][$arr[0]] = [[$char[0]]];
			else if ( strpos($arr[1],"|") )
				foreach ( explode(" | ",$arr[1]) as $g )
					$result["rules"][$arr[0]][] = array_reverse(array_map('intval',explode(" ",$g)));
			else
				$result["rules"][$arr[0]] = [array_reverse(array_map('intval',explode(" ",$arr[1])))];
		} 
		else if ( preg_match("/\w+/",$line,$matches) )
		{
			$result["input"][] = trim($matches[0]);
		}			
	}
	return $result;
}


function match($str,$cur_rule,&$rules)
{
	if (count($cur_rule) > strlen($str) )
		return false;
	elseif ( count($cur_rule) == 0 || strlen($str) == 0 )
		return count($cur_rule) == 0 && strlen($str) == 0;
	
	$r = array_pop($cur_rule);
	if ( is_string($r) ){
		if ( substr($str,0,1) == $r )
			return match(substr($str,1),$cur_rule,$rules);
	}
	else{
		foreach( $rules[$r] as $sub_r ){
			if( match($str, array_merge($cur_rule,$sub_r),$rules) )
				return true;
		}
	}
}




function solve(&$arr)
{
	$match = 0;
	foreach($arr["input"] as $input)
		if ( match($input,$arr["rules"][0][0],$arr["rules"]) )
			$match++;
	return $match;
}


$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day19-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day19-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2


$start_time = microtime(true); 
$in["rules"][8] = [[42], [42, 8]];
$in["rules"][11] = [[31, 42], [31, 11, 42]];
$part2 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day19-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
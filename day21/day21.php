<?php 
function readLines($filename)
{	
	$array = [];	
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   foreach( explode("\n", trim(fread($fp, filesize($filename)))) as $line )
	   {
		   $inal = explode(" (contains ",$line);
		   $in = explode(" ",$inal[0]);
		   $al = explode(", ", trim(str_replace(")","",$inal[1])));
		   $array[] = ["ingredients"=>$in,"allergens"=>$al];
	   }
	}
	return $array;
}



function solve(&$arr)
{	
	$fCount = [];
	$alin = [];
	foreach($arr as $ia )
	{
		foreach( $ia["ingredients"] as $in )
		{
			if ( !array_key_exists($in,$fCount) )
				$fCount[$in] = 0;
			$fCount[$in]++;
		}
		foreach( $ia["allergens"] as $al )
		{
			if ( !array_key_exists($al,$alin) )
				$alin[$al] = $ia["ingredients"];
			else
				$alin[$al] = array_intersect($alin[$al],$ia["ingredients"]);
		}
	}
	
	$all = [];
	array_walk_recursive($alin,function($val,$key) use(&$all) {
		$all[] = $val;
	});
	$all = array_diff(array_keys($fCount),$all);

	$count = 0;
	array_walk($fCount, function($val,$key) use(&$all,&$count){
		if ( in_array($key,$all) )
			$count+=$val;
	});
	return [$count,$alin];
}

function solve2(&$arr)
{	
	$map = [];
	
	while( count($map) < count(array_keys($arr)))
	{
		foreach( $arr as $al => $in )
		{
			$diff = array_diff($in,array_values($map));
			if ( count($diff) == 1 ){
				$map[$al] = reset($diff);
				break;
			}
		}
	}
	
	ksort($map);
	
	return implode(",",$map);
	
}



$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day21-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day21-part1: " . $part1[0] . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2


$start_time = microtime(true); 
$part2 = solve2($part1[1]);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day21-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
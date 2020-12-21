<?php 

$d =0;
function readLines($filename)
{	
	$array = [];
	$current_tile = 0;
	
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   foreach( explode("\n", trim(fread($fp, filesize($filename)))) as $line )
	   {
		   if ( preg_match("/Tile (\d+):/",$line,$match) ){
			   $current_tile = $match[1];
			   $array[$current_tile] = [];
			   $array[$current_tile][0] = [];
		   }
		   else if ( !empty(trim($line)) ){
			$array[$current_tile][0][] = array_map('intval',str_split(trim(str_replace(".",0,str_replace("#",1,$line)))));
		   }
	   }
	}
	addVariations($array);
	return $array;
}


function addVariations(&$array)
{
	foreach($array as $k => $tile){
		$array[$k][] = rotate($array[$k][0]);
		$array[$k][] = rotate($array[$k][1]);
		$array[$k][] = rotate($array[$k][2]);
		$array[$k][] = flip($array[$k][0]);
		$array[$k][] = rotate($array[$k][4]);
		$array[$k][] = rotate($array[$k][5]);
		$array[$k][] = rotate($array[$k][6]);
	}
}

function rotate(&$array)
{
	$res =[];
	foreach(array_keys($array[0]) as $c)
		$res[] = array_reverse(array_column($array,$c));
	
	return $res;
}
function flip(&$array)
{
	$res =[];
	foreach($array as $row)
		$res[] = array_reverse($row);
	return $res;
}


function printGrid($array)
{
	foreach($array as $i)
		echo implode($i) . PHP_EOL;
	echo PHP_EOL;
}

function printSeamonster($array)
{
	for($i = 0; $i < count($array); $i++)
	{
		for($j = 0; $j < count($array); $j++)
		{
			switch($array[$i][$j])
			{
				case "0":
					echo " ";
					break;
				case "1":
					echo "-";
					break;
				case "2":
					echo "#";
					break;
			}
		}
		echo PHP_EOL;
	}
}


function wait()
{
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	fclose($handle);
}

function checkFits(&$prev, &$cur,$or)
{
	switch($or)
	{
		case "T":
			return $cur[0] == $prev[count($prev)-1];
		case "L":
			return array_column($cur,0) == array_column($prev,count($prev)-1);
			break;
	}
}

function backtrack(&$tiles,&$grid,$keys_in_use=[],$row=0,$col=0,$max=0)
{
	if ( count($keys_in_use) == pow($max,2) )
		return true;
	
	foreach( $tiles as $key => $tile )
	{
		if ( in_array($key,$keys_in_use) )
			continue;
		
		array_push($keys_in_use,$key);		
		foreach( $tile as $k => $v )
		{
			$grid[$row][$col] = [$key => $k];
			$fit_left = true;
			$fit_top = true;
			if ( $row > 0 ){
				$prev = $grid[$row-1][$col];
				$fit_top = checkFits($tiles[key($prev)][reset($prev)],$v,"T");
			}
			if ( $col > 0 ){
				$prev = $grid[$row][$col-1];
				$fit_left = checkFits($tiles[key($prev)][reset($prev)],$v,"L");
			}
			
			if ( $fit_left && $fit_top ) {
				$c = ($col+1)%$max;
				$r = intval(($max*$row + $col + 1 ) / $max);
				if ( backtrack($tiles,$grid,$keys_in_use,$r,$c,$max) )
					return true;
			}
		}
		$grid[$row][$col] = null;
		$keys_in_use = array_diff($keys_in_use,[$key]);
	}
	return false;
}

function solve(&$arr)
{	
	$sqrt = sqrt(count($arr));
	$grid = [];
	for( $i = 0; $i < $sqrt; $i++ )
		$grid[] = array_fill(0,$sqrt,null);

	backtrack($arr,$grid,[],0,0,$sqrt);

	return [$grid,key($grid[0][0]) * key($grid[0][$sqrt-1]) * key($grid[$sqrt-1][0]) * key($grid[$sqrt-1][$sqrt-1])];
}

function solve2(&$in,&$grid)
{
	$seamonster_mask = createSeamonsterRule();
	$image = ["seamonster" => []];
	foreach($grid as $i => $row)
	{
		foreach( $row as $j => $col ){
			$tile = $in[key($col)][reset($col)];
			$max = count($tile)-2;
			for($ii = 1; $ii < count($tile)-1;$ii++)
			{
				for($jj = 1; $jj < count($tile)-1; $jj++)
				{
					$image["seamonster"][0][$i*$max+$ii-1][$j*$max+$jj-1] = $tile[$ii][$jj];
				}
			}
		}
	}
	
	addVariations($image);
	$rough = 0;
	$seamonsters = 0;
	foreach( $image["seamonster"] as $i => $var )
	{
		$seamonsters = findSeamonster($image,$var,$seamonster_mask,$i);
		if ( $seamonsters > 0){
			printSeamonster($image["seamonster"][$i]);
			array_walk_recursive($var,function($val,$k) use(&$rough){
				if ( $val == 1 )
					$rough++;
			},$rough);
			break;
		}
	}
	
	return $rough - $seamonsters * count($seamonster_mask[0]);
	
}

function findSeamonster(&$all_images,&$image,&$rules,$cur_key)
{
	$count = 0;
	for($i = 0; $i < count($image) - $rules[1]; $i++){
		for ( $j = 0; $j < count($image) - $rules[2]; $j++ )
		{
			$found = true;
			foreach($rules[0] as $rule)
			{
				if ( $image[$i+$rule[0]][$j+$rule[1]] == 0 )
				{
					$found = false;
					break;
				}
			}
			// Apply Seamonster Mask
			if ( $found ){
				$count++;
				foreach($rules[0] as $rule)
				{
					$all_images["seamonster"][$cur_key][$i+$rule[0]][$j+$rule[1]] = "2";
				}
			}
		}
	}
	return $count;
}

function createSeamonsterRule()
{
	$seamonster_mask = 
"                  # 
#    ##    ##    ###
 #  #  #  #  #  #   ";
	$rules = [];
	$maxi = $maxj = 0;
	
	foreach( explode("\n",$seamonster_mask) as $i => $line )
	{
		foreach (  str_split($line) as $j => $col )
		{
			if ( $col == "#" ){
				$rules[] = [$i,$j];
				$maxi = max($maxi,$i);
				$maxj = max($maxj,$j);
			}
		}
	}		
	
	return [$rules,$maxi,$maxj];
}


$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day20-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day20-part1: " . $part1[1] . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2


$start_time = microtime(true); 
$part2 = solve2($in,$part1[0]);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day20-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
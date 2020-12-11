<?php 
function readLines($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	return array_map(function($val) {
		return str_split(trim($val));
	},$array);
}

function get_LTR($arr,$wh,$xy,$maxdif=1)
{
	$LTR = [];
	$c = $xy[0] + $xy[1];
	$col = min(min($c,$xy[1]+$maxdif),$wh[1]);
	$row = $c - $col;
	
	$max_r = min($wh[0],$xy[0]+$maxdif);
	
	while( $col >= 0 && $row <= $max_r )
		$LTR[$row - $xy[0]] = $arr[$row++][$col--];
	
	return $LTR;
}

function get_RTL($arr,$wh,$xy,$maxdif=1)
{
	$RTL = [];
	$row = max(max($xy[0]-$xy[1],0),$xy[0]-$maxdif);
	$col = max(max($xy[1]-$xy[0],0),$xy[1]-$maxdif);
	
	
	$max_r = min($wh[0],$xy[0]+$maxdif);
	$max_c = min($wh[1],$xy[1]+$maxdif);
	
	
	while ( $row <= $max_r && $col <= $max_c )
		$RTL[$row - $xy[0]] = $arr[$row++][$col++];
	
	return $RTL;
}

function get_V($arr,$wh,$xy,$maxdif=1)
{
	$V = [];
	$row = max(0,$xy[0]-$maxdif);
	$max = min($wh[0],$xy[0]+$maxdif);
	while ( $row <= $max )
		$V[$row - $xy[0]] = $arr[$row++][$xy[1]];
	return $V;
}

function get_H($arr,$wh,$xy,$maxdif=1)
{
	$H = [];
	$col = max(0,$xy[1]-$maxdif);
	$max = min($wh[1],$xy[1]+$maxdif);
	while ( $col <= $max )
		$H[$col - $xy[1]] = $arr[$xy[0]][$col++];
	return $H;
}

function getGrid(&$arr,$wh,$index,$depth)
{
	$grid = [];
	$grid[] = get_LTR($arr,$wh,$index,$depth);
	$grid[] = get_RTL($arr,$wh,$index,$depth);
	$grid[] = get_V($arr,$wh,$index,$depth);
	$grid[] = get_H($arr,$wh,$index,$depth);
	return $grid;
}

function gridCheck(&$arr,$index,$wh,$tol)
{
	$occupied = 0;
	
	$grid = getGrid($arr,$wh,$index,max($tol[0],$tol[1]));
		
	array_walk($grid, function($val,$key) use (&$occupied) {
		$keys = array_keys($val);
		if ( in_array(1,$keys)){
			for( $i = 1; $i <= max($keys) && $i != 0; $i++ ){
				if( $val[$i] == "#" ){
					$occupied++;
					break;
				}
				if ( $val[$i] == "L" ){
					break;
				}
			}
		}
		if ( in_array(-1,$keys) ){
			for( $i = -1; $i >= min($keys) && $i != 0; $i-- ){
				if( $val[$i] == "#" ){
					$occupied++;
					break;
				}
				if ( $val[$i] == "L" ){
					break;
				}
			}
		}
	});
	
	return $occupied;
}


function playRules($arr,$tolerant=false)
{
	$wh = [count($arr)-1,count($arr[0])-1];
	$di = $tolerant ? count($arr)-1:1;
	$dj = $tolerant ? count($arr[0])-1:1;
	$new = $arr;
	$changed = false;
	for($i = 0; $i <= $wh[0]; $i++)
	{
		for($j = 0; $j <= $wh[1]; $j++)
		{
			switch($arr[$i][$j])
			{
				case "L" : 
					if ( gridCheck($arr,[$i,$j],$wh,[$di,$dj]) == 0 ){
						$new[$i][$j] = "#";
						$changed = true;
					}
				break;
				case "#" :
					if ( gridCheck($arr,[$i,$j],$wh,[$di,$dj]) >= ($tolerant ? 5 : 4) ){
						$new[$i][$j] = "L";
						$changed = true;
					}
				break;
				case ".":
				default:
					break;
			}
		}
	}
	return [$new,$changed];
}

$seats = readLines("input.txt");



$part1 = [$seats,true];
$part2 = [$seats,true];
$occupied = 0;
$occupied2 = 0;
$flag = '#';


$start_time = microtime(true); 
while($part1[1])
{
	$part1 = playRules($part1[0],false);
}

array_walk_recursive($part1, function($val, $key) use (&$occupied, $flag) {
  if ($val == $flag) {
      $occupied++;
  }
});
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day11-part1: " . $occupied . " and took " . $execution_time . " sec." . PHP_EOL;

$start_time = microtime(true); 
while($part2[1])
{
	$part2 = playRules($part2[0],true);
}

array_walk_recursive($part2, function($val, $key) use (&$occupied2, $flag) {
  if ($val == $flag) {
      $occupied2++;
  }
});
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day11-part2: " . $occupied2 . " and took " . $execution_time . " sec." . PHP_EOL;
//*/



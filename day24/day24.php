<?php 
function readLines($filename)
{	
	$array = [];
	$fp = @fopen($filename, 'r'); 
	// Add each line to an array
	if ($fp) {
	   foreach( explode("\n", trim(fread($fp, filesize($filename)))) as $line )
	   {
		   preg_match_all("/(e)|(se)|(sw)|(w)|(nw)|(ne)/",$line,$matches);
		   $array[] = $matches[0];
	   }
	}
	return $array;
}

function getMinMax(&$blacks,&$x,&$y)
{
	foreach( array_keys($blacks) as $i => $keys )
	{
		$k = explode(":",$keys);
		$x[0] = min($x[0],$k[0]);
		$x[1] = max($x[1],$k[0]);
		$y[0] = min($y[0],$k[1]);
		$y[1] = max($y[1],$k[1]);
	}
}

function setMinMax(&$blacks,&$x,&$y)
{
	$k = explode(":",key($blacks));
	$x[0] = $k[0];
	$x[1] = $k[0];
	$y[0] = $k[1];
	$y[1] = $k[1];
}

function countNeighbours(&$blacks,&$directions,$x,$y)
{
	$count = 0;
	foreach($directions as $dir)
	{
		$key = ($x+$dir[0]).":".($y+$dir[1]);
		if( isset($blacks["$key"]) && $blacks["$key"])
			$count++;
	}
	return $count;
}

function highest_offset(&$dir)
{
	$max = 0;
	foreach($dir as $d)
		$max = max($d[1],max($d[0],$max));
	return $max;
}



function part1(&$arr,&$direction)
{
	$black = [];
	foreach($arr as $ins){
		$x = 0;
		$y = 0;
		foreach($ins as $dir)
		{
			$x = $x + $direction[$dir][0];
			$y = $y + $direction[$dir][1];
		}
		if( !array_key_exists("$x:$y",$black) )
			$black["$x:$y"] = true;
		else
			$black["$x:$y"] = !$black["$x:$y"];
	}
		
	return [array_sum($black),$black];
}

function part2(&$blacks,&$direction,$days=1)
{
	$m_x = [];
	$m_y = [];
	setMinMax($blacks,$m_x,$m_y);
	$off = highest_offset($direction);
	
	for($day=0;$day<$days;$day++)
	{
		$d_black = $blacks;
		getMinMax($blacks,$m_x,$m_y);
		for($x = $m_x[0]-$off; $x < $m_x[1]+$off; $x++)
		{
			for($y = $m_y[0]-$off; $y < $m_y[1]+$off; $y++)
			{
				$nb = countNeighbours($blacks,$direction,$x,$y);	
				if ( !isset($blacks["$x:$y"]) )
					$blacks["$x:$y"] = false;					
				if ( $blacks["$x:$y"] && !in_array($nb,[1,2]) )
					$d_black["$x:$y"] = false;
				if ( !$blacks["$x:$y"] && $nb == 2 )
					$d_black["$x:$y"] = true;
			}
		}
		$blacks = $d_black;		
	}
	return array_sum($blacks);
}


$start_time = microtime(true); 
$in = readLines("input.txt");
$direction = ['nw'=>[-1, -1], 'sw'=>[1, -1], 'ne'=>[-1, 1],'se'=>[1, 1], 'w'=>[0, -2], 'e'=>[0, 2]];
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day24-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = part1($in,$direction);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day24-part1: " . $part1[0] . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2

$start_time = microtime(true); 
$part2 = part2($part1[1],$direction,100);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day24-part2: " . $part2 . " and took " . round($execution_time,2) . " sec." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
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

function parse_rules($rules)
{
	$result = [];
	
	foreach( $rules as $line)
	{
		$matches = null;
		preg_match_all("/(\w+ \w+ bags)|(\d+ \w+ \w+)/",$line,$matches);
		$content = [];
		for( $i = 1; $i < count($matches[0]); $i++ )
		{
			$split = preg_split("/(?<=[0-9]) (?=[a-z]+)/i",$matches[0][$i]);
			if ( !empty($split[1]) ) 
				$content[$split[1]] = intval($split[0]);
		}
		$result[str_replace(" bags","",$matches[0][0])] = $content;
	}
	
	return $result;
}

function get_count($rules, $color)
{
	$ret = 1;

	foreach ($rules[$color] as $k => $v)
		$ret += $v * get_count($rules, $k);

	return $ret;
}

function get_colors($rules, $color)
{
	$ret = [];

	foreach ($rules as $p => $c)
		if (array_key_exists($color, $c))
			$ret = array_merge($ret, [$p], get_colors($rules, $p));

	return array_unique($ret);
}



$in = readLines("input.txt");
$bags = parse_rules($in);

echo "Solution day7-part1: " . count(get_colors($bags, 'shiny gold')) . PHP_EOL;
echo "Solution day7-part2: " . (get_count($bags, 'shiny gold')-1) . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


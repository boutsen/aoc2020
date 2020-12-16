<?php 
function readLines($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	
	$ins = [];
	$current_mask = "";
	foreach( $array as $in )
	{
		if( substr($in,0,4) == "mask" ){
			$current_mask = explode(" = ",$in)[1];
			$ins[$current_mask] = [];
		}
		if ( substr($in,0,3) == "mem" ){
			preg_match_all("/\d+/",$in,$matches);
			$ins[$current_mask][] = ["addr" => intval($matches[0][0]), "val" => intval($matches[0][1])];
		}
	}
	return $ins;
}

function mask($mask,$val,$skip=[]){
	$mask = str_split($mask);
	$val = str_split($val);
	foreach( $mask as $k => $v )
	{
		if ( !in_array($v,$skip) )
			$val[$k] = $v;
	}
	return implode("",$val);
}

function get_all_masks($mask,$val,&$res){
	if ( strpos($val,"X") === false )
		$res[] = $val;
	else{
		get_all_masks($mask,preg_replace('/X/', '1', $val, 1),$res);
		get_all_masks($mask,preg_replace('/X/', '0', $val, 1),$res);
	}
	return;
}

function solve(&$in)
{
	$res = [];
	foreach( $in as $mask => $arr )
	{
		foreach ( $arr as $mem )
		{
			$val = str_pad(decbin($mem["val"]), strlen($mask)-1, "0", STR_PAD_LEFT);
			$val = mask($mask,$val,['X']);
			$res[$mem["addr"]] = bindec($val);
		}
	}
	return array_sum($res);
}

function solve2(&$in)
{
	$res = [];
	foreach( $in as $mask => $arr )
	{
		foreach ( $arr as $mem )
		{
			$addr = str_pad(decbin($mem["addr"]), strlen($mask)-1, "0", STR_PAD_LEFT);
			$addrs = [];
			get_all_masks($mask,mask($mask,$addr,['0']),$addrs);
			foreach( $addrs as $m )
			{
				$res[$m] = $mem["val"];
			}
		}
	}
	return array_sum($res);
}


$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day14-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

/// Part 1
$start_time = microtime(true); 
$part1 = solve($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day14-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2
$start_time = microtime(true); 
$part2 = solve2($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day14-part2: " . $part2 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;
echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;

?>
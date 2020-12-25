<?php 
function readLines($filename)
{	
	$array = [];
	$fp = @fopen($filename, 'r'); 
	// Add each line to an array
	if ($fp) {
	   foreach( explode("\n", trim(fread($fp, filesize($filename)))) as $line )
	   {
		   $array[] = $line;
	   }
	}
	return $array;
}


function getLoopSize(&$key)
{
    $v=1;
    $l=0;
    while ($v!=$key) {
        $v = ($v * 7)% 20201227;
        $l++;
    }
    return $l;
}

function encrypt($key,&$loops)
{
	$v = 1;
	for($i=0;$i<$loops;$i++)
		$v = ($v*$key)%20201227;
	return $v;
}

function part1(&$arr)
{
	$l = [];
	//$l[0] = getLoopSize($arr[0]);
	$l[1] = getLoopSize($arr[1]);
	return encrypt(intval($arr[0]),$l[1]);
}



$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day25-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = part1($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day25-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;
?>
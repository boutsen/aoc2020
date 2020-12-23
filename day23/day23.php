<?php 

class circ {
	public $v;
	public $r;
	function __construct($v,$r = null)
	{
		$this->v = $v;
		$this->r = $r;
	}
}

function create_circ_map(&$arr,&$circs)
{
	$prev = null;
	foreach($arr as $cup){
		$c = new circ($cup,$prev);
		$circs[$cup] = $c;
		if ( $prev )
			$prev->r = $c->v;
		$prev = $c;
	}
	$prev->r = $circs[$arr[0]]->v;
}

function solve(&$arr,&$circs,$it)
{
	create_circ_map($arr,$circs);
	
	for($i=0,$cur = $circs[$arr[0]],$circ = count($arr);$i<$it;$i++, $cur=$circs[$cur->r])
	{
		// Pick up 3
		$tomove = [$cur->r];
		for($j=1,$next=$cur->r;$j<3;$j++)
			$tomove[] = $circs[$next]->r;
		
		// Find Destination
		$dest = $cur->v-1 ? $cur->v-1 : $circ;
		while(in_array($dest,$tomove))
			$dest = $dest-1 ? $dest-1 : $circ;
		
		$cur->r = $circs[$tomove[2]]->r;
		$circs[$tomove[2]]->r = $circs[$dest]->r;
		$circs[$dest]->r = $tomove[0];
	}
}


function part1($arr)
{
	$circs = [];
	solve($arr,$circs,100);
	
	$produce = "";
	
	$i = $circs[1]->r;
	while( $i != 1 )
	{
		$produce .= $circs[$i]->v;
		$i = $circs[$i]->r;
	}		
	return $produce;
}

function part2($arr)
{
	$circs = [];
	for($i=count($arr)+1;$i<=1000000;$i++)
		$arr[$i] = $i;	
	
	solve($arr,$circs,10000000);
	
	return $circs[1]->r * $circs[$circs[1]->r]->r;
}




$start_time = microtime(true); 
$in = str_split("463528179");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day23-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = part1($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day23-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2
ini_set('memory_limit','256M');
$start_time = microtime(true);
$part2 = part2($in);
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day23-part2: " . $part2 . " and took " . round($execution_time,2) . " sec." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
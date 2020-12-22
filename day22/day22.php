<?php 
function readLines($filename)
{	
	$array = [];
	$fp = @fopen($filename, 'r'); 
	$players = -1;
	// Add each line to an array
	if ($fp) {
	   foreach( explode("\n", trim(fread($fp, filesize($filename)))) as $line )
	   {
		   if ( substr($line,0,6) == "Player" )
			   $players++;
		   else {
			   $line = intval(trim($line));
			   if ( !empty($line) )
				   $array[$players][] = $line;
		   }
	   }
	}
	return $array;
}

function calc_score(&$deck)
{
	$score = 0;
	$size = count($deck);
	array_walk($deck,function($val,$key) use(&$score,&$size) {
		$score += (($size-$key)*$val);
	});
	
	return $score;
}


function solve($arr,$solver)
{
	call_user_func_array($solver,array(&$arr[0],&$arr[1]));
	return calc_score($arr[!empty($arr[0]) ? 0 : 1]);
}


function combat(&$deck1, &$deck2)
{
	while( !empty($deck1) && !empty($deck2) )
	{
		$card1 = array_shift($deck1);
		$card2 = array_shift($deck2);
		if ( $card1 > $card2 )
			array_push($deck1,$card1,$card2);
		else
			array_push($deck2,$card2,$card1);
	}
}

function recursive_combat(&$deck1,&$deck2)
{
	$seen = [];
	while( !empty($deck1) && !empty($deck2) )
	{
		$round = http_build_query($deck1) .":". http_build_query($deck2);
		if ( in_array($round,$seen) ){
			// Player 1 WINS
			$deck2 = [];return;
		}
	
		$seen[] = $round;
		
		$card1 = array_shift($deck1);
		$card2 = array_shift($deck2);
		
		$p1wins = null;
		// Initiate subgame
		if ( count($deck1) >= $card1 && count($deck2) >= $card2 ){
			$sub1 = array_slice($deck1,0,$card1);
			$sub2 = array_slice($deck2,0,$card2);
			recursive_combat($sub1,$sub2);	
			$p1wins = !empty($sub1);
		}
		else 
			$p1wins = $card1 > $card2;
			
		if( $p1wins )
			array_push($deck1,$card1,$card2);
		else
			array_push($deck2,$card2,$card1);
	}
}



$start_time = microtime(true); 
$in = readLines("input.txt");
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 
echo "Solution day22-reading & parsing took " . round($execution_time*1000,2) . " ms." . PHP_EOL;


/// Part 1
$start_time = microtime(true); 
$part1 = solve($in,'combat');
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 

echo "Solution day22-part1: " . $part1 . " and took " . round($execution_time*1000,2) . " ms." . PHP_EOL;

// Part 2

$start_time = microtime(true); 
$part2 = solve($in,'recursive_combat');
$end_time = microtime(true); 
$execution_time = ($end_time - $start_time); 


echo "Solution day22-part2: " . $part2 . " and took " . round($execution_time,2) . " sec." . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;


?>
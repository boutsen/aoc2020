<?php 
function readLines($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	return array_map("intval",$array);
}

function calc_diff($arr)
{
	$diff = [];

	for($i = 0; $i<count($arr)-1;$i++)
	{
		$calc_diff = $arr[$i+1] - $arr[$i];
		if (!array_key_exists($calc_diff,$diff))
			$diff[$calc_diff] = 1;
		$diff[$calc_diff]++;
	}
	
	return $diff;
}

function calc_comb($arr)
{
	$comb = [1];

	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<$i;$j++){
			if($arr[$i] - $arr[$j] <= 3){
				if ( !array_key_exists($i,$comb) )
					$comb[$i] = 0;
				$comb[$i] += $comb[$j];
			}
		}
	}
	
	return $comb;
}


$in = readLines("input.txt");
sort($in);

$diff = calc_diff($in);


array_unshift($in,0);

$comb = calc_comb($in);

echo "Solution day10-part1: " . ($diff[1]*$diff[3]) . PHP_EOL;
echo "Solution day10-part2: " . $comb[count($in)-1] . PHP_EOL;



?>
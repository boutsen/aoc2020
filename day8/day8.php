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


$in = readLines("input.txt");

function runProgram($instructions)
{
	$acc = 0;
	$i = 0;
	$walked = [];
	
	while( true )
	{
		if ( in_array($i,$walked) || $i >= count($instructions) )
			break;
			
		$in = explode(" ",$instructions[$i]);
		
		array_push($walked,$i);
		
		switch( $in[0] ) {
			case "jmp":
				$i += (intval($in[1]));
				break;
			case "acc":
				$acc += intval($in[1]);
			default: 
				$i++;
		}
	}
	return [$i,$acc];
}

function fixProgram($instructions)
{
	
	for ( $j = 0; $j < count($instructions); $j++)
	{
		$new = $instructions;
		$instr = explode(" ",$instructions[$j]);
		$change_in_code = false;
		switch($instr[0]){
			case "jmp":
				$new[$j] = "nop " . $instr[1];$change_in_code = true;break;
			case "nop":
				$new[$j] = "jmp " . $instr[1];$change_in_code = true;break;
		}
		if ( $change_in_code ){
			$acc = runProgram($new);
			if( $acc[0] == count($instructions)){
				return $acc[1];
			}
		}
	}
}


echo "Solution day8-part1: " . runProgram($in)[1] . PHP_EOL;
echo "Solution day8-part2: " . fixProgram($in) . PHP_EOL;

echo "Peak usage: " . round(memory_get_peak_usage()/1024) . 'KB' . "/". round(memory_get_peak_usage(true)/1024) . 'KB' . PHP_EOL;
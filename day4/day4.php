<?php 
function readNumbers($filename)
{	
	$array = null;
	$fp = @fopen($filename, 'r'); 

	// Add each line to an array
	if ($fp) {
	   $array = explode("\n", fread($fp, filesize($filename)));
	}
	return $array;
}

function sanitize($array) {
	$result = [];
	
	$pp = "";
	foreach($array as $line)
	{
		if ( empty(trim($line)) ){
			$d = explode(" ",trim($pp));
			$p = [];
			foreach( $d as $s )
			{
				$x = explode(":",$s);
				if ( $x != null && isset($x) && isset($x[0]) && $x[0] != null && !empty($x[0]) )
					$p[$x[0]] = trim($x[1]);
			}
			array_push($result,$p);
			$pp = "";
		}
		$pp .= "$line ";
	}
	return $result;
}

function validatePPvalues($str,$rule)
{
	switch($rule[0])
	{
		case "between":
			$number = intval($str);
			return $number >= $rule[1][0] && $number <= $rule[1][1];
		case "digit":
			return preg_match("/^\d{".$rule[1]."}$/", $str);
		case "length":
			preg_match("/(\\d+)([a-zA-Z]+)/", $str, $r);
			if ( array_key_exists(1,$r) && array_key_exists(2,$r) )
			{
				$number = intval($r[1]);
				switch($r[2]){
					case "cm":
						return $number >= $rule[1][0] && $number <= $rule[1][1];
					case "in":
						return $number >= $rule[2][0] && $number <= $rule[2][1];
				}
			}
			return false;
		case "hash":
			return  preg_match("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/", $str);
		case "str":
			return in_array($str,$rule[1]);
	}	
}

function array_contains_keys($array,$required,$optional,$deepcheck=false)
{
	$valid = true;
	
	foreach($required as $k => $r)
	{
		$valid = $valid && array_key_exists($k,$array);
		if($valid && $deepcheck){
			$valid = $valid && validatePPvalues($array[$k],$r);
		}
		if (!$valid ){
			break;
		}
	}
	//Optional can be ignored
	/*foreach($optional as $o)
	{
		$valid = $valid || array_key_exists($o,$array);
	}*/
	
	return $valid;
}



$required = [
	"byr"=>['between',[1920,2002]],
	"iyr"=>['between',[2010,2020]],
	"eyr"=>['between',[2020,2030]],
	"hgt"=>['length',[150,193],[59,76]],
	"hcl"=>['hash'],
	"ecl"=>['str',['amb','blu','brn','gry','grn','hzl','oth']],
	"pid"=>['digit',9],
];
$optional = ["cid"];


$pp = sanitize(readNumbers("input.txt"));

$count = 0;
$count2 = 0;
foreach( $pp as $passport)
{
	if ( array_contains_keys($passport,$required,$optional,false) )
		$count++;
	if ( array_contains_keys($passport,$required,$optional,true) )
		$count2++;
}

echo "Solution day4-part1: " . $count . PHP_EOL;
echo "Solution day4-part2: " . $count2 . PHP_EOL;

?>
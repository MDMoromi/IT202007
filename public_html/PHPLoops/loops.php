<?php
	$array = array(2,3,4,5,6,7,8);
    $placement = 0;
	foreach($array as $value){
		echo "Number ".($placement+1)." in the array is ".$array[$placement].".<br/>";
		$placement += 1;
	}
	$placement = 0;
	echo "<br/>";
	foreach($array as $value){
		$check = $array[$placement];
		if($check%2){
			$placement += 1;
			continue;
		}
		else{
			$placement += 1;
			echo "$check is even.<br/>";
		}
	}
?>

<?php
	$array = array(2,3,4,5,6,7);
	#foreach($array as $value){
	#	echo "Value is $value <br/>";
	#}
	foreach($array as $value){
		$check = $array[$value]/2;
		echo "$check"
	}
?>

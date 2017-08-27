<?php
	include("database_connection.php");
	include("functions.php");
?>
<?php
	$string = "i am silly person and i need to #improve a lot";
	$compressed   = gzcompress($string, 9);
	$uncompressed = gzuncompress($compressed);
	echo $compressed;
	
?>

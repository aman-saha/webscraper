<?php
    @ob_start();
    include("../includes/database_connection.php");
    include("../includes/session.php");
    include("../includes/functions.php");
?>
<?php
	$data = file_get_contents("data.json");
	$json_data = json_decode($data, true);
?>
<?php
    @ob_start();
    include("includes/database_connection.php");
    include("includes/session.php");
    include("includes/functions.php");
?>
<?php
	/*$data = file_get_contents("data.json");
	$json_data = json_decode($data, true);
	$e = count($json_data);
	echo $e;
	$c = count($json_data[0]['reviews']);
	for ($i=0; $i <$c ; $i++) 
	{ 
		echo ($json_data[0]['reviews'][$i]['review_text']);
		echo "<br/>";
	}
	//echo $json_data[0]['reviews'][0]['review_header'];
	echo $c;
	//print_r($json_data);*/
?>
<?php
	// used to extract reviews from Amazon
	$filename = escapeshellcmd("Amazon-Review-Scraper/scrape_product.py");
    //chmod($filename,0777);
    $command = "python " . $filename;
    $output = shell_exec($command);
    echo $output;

    // used to extract reviews from Amazon
	$filename = escapeshellcmd("Flipkart-Review-Scraper/scrape_product.py");
    //chmod($filename,0777);
    $command = "python " . $filename;
    $output = shell_exec($command);
    echo $output;

?>
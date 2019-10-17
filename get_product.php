<?php
    @ob_start();
    include("includes/database_connection.php");
    include("includes/session.php");
    include("includes/functions.php");
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
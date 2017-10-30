<?php
    @ob_start();
    include("includes/database_connection.php");
    include("includes/session.php");
    include("includes/functions.php");
?>
<?php
	$product_asin = array("B00YD545CC","B075QJSQLH","B00NQGP5X2","B00NZ8POMI","B00YD54HZ2","B01GXAT0BK","B00NQGOMZE","B00YD53YQU","B01N9YOF3R");
	$products = array("iPhone 6 16 GB Unlocked, Gold","Apple iPhone 8 4.7 64 GB, Fully Unlocked, Gold","Apple iPhone 6 64 GB Unlocked, Space Gray","Apple iPhone 5C 8 GB Unlocked, Green","Apple iPhone 6 Plus 16 GB Unlocked, Silver (Certified Refurbished)","Apple iPhone SE 16 GB Factory Unlocked, Space Gray (Certified Refurbished)","Apple iPhone 6 Plus 16 GB Unlocked, Silver","Apple iPhone 5S 16 GB Unlocked, Silver","Apple iPhone 7 32 GB Unlocked, Rose Gold");

	for($i = 0;$i < count($product_asin);$i++)
	{
		$asin = $product_asin[$i];
		$name = $products[$i];

		$query = "INSERT INTO phones(name,asin) VALUES('$name','$asin')";
		$result = mysqli_query($connection,$query);
	}
?>
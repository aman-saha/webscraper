 <?php
/*
    Created by Aman Saha
    @darknurd
*/
    $DB_SERVER="127.0.0.1";
    $DB_USER="root";
    $DB_PASS="root";
    $DB_NAME="tag";

//set timezone
    date_default_timezone_set('Asia/Calcutta');
    
//Create a database connection
    $connection = mysqli_connect($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME);
    if (!$connection) 
    {
        die("Database connection failed: ");
    }

//Select a database to use 
    $db_select = mysqli_select_db($connection,$DB_NAME);
    if (!$db_select) 
    {
        die("Database selection failed.....: ");
    }
?>

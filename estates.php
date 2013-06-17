<?php

// Initializations of the variables used
$result = 0;

// MYSQL connection credentials
define('MYSQL_HOST',     '');
define('MYSQL_USER',     '');
define('MYSQL_PASSWORD', '');
define('MYSQL_DB',       '');

// PDO - connect to the database
try 
{
	$dbh = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD);

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_PERSISTENT, true);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
} 
catch (PDOException $e) 
{
	echo 'Error!: ' . $e->getMessage() . '<br/>';
}

// take the estates from the table named "Estates"
if(isset($_POST['minprice']) && isset($_POST['maxprice']))
{
	$minprice	= filter_var($_POST['minprice']	, FILTER_VALIDATE_INT);  
	$maxprice	= filter_var($_POST['maxprice']	, FILTER_VALIDATE_INT); 
	$query = '
							SELECT 
								count(*) as nr_estates
							FROM
								Estates			
							WHERE
								estate_price 
							BETWEEN 
								:minprice 
							AND
								:maxprice
						';
	$stmt = $dbh->prepare($query);
	try
	{
		$stmt->bindParam(':minprice', $minprice);
		$stmt->bindParam(':maxprice', $maxprice);
		$stmt->execute();
	}
	catch (PDOException $e)
	{
		print($e->getMessage());
		die;
	}
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$result = $row['nr_estates'];
}
echo json_encode($result);


?>
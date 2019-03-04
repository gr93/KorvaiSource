<?php 
	$config = include('config.php');
    $conn = mysqli_connect($config["DB_Server"], $config["DB_Username"], $config["DB_Password"], $config["DB_Database"]);
    $id = $_POST['id'];
    $query = "DELETE FROM `korvais` WHERE id = $id";
    mysqli_query($conn, $query) or die("Query failed!");

    header('Location: index.php');
?>

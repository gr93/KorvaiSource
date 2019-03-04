<?php
	$config = include('config.php');
    $conn = mysqli_connect($config["DB_Server"], $config["DB_Username"], $config["DB_Password"], $config["DB_Database"]);
	$query = "SELECT * FROM `korvais`";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_array($result)){
		$row[0] = "&nbsp;&nbsp;&nbsp;<form action='deleteKorvai.php' method='post' style='display: inline;'><input type='hidden' name='id' value=$row[0]><input type='submit' value='Delete'></form>";
		if($row['korvaiText'] != "None") {
			$kUrl =  "https://s3.amazonaws.com/korvais/k" . $row['id'] . ".txt";
			$row[6] = "<a href='$kUrl' target='_blank'>Link</a>";
		}
		else {
			$row[6] = "N/A";
		}
		if($row['recording'] != "None") {
			$rUrl = "https://s3.amazonaws.com/korvais/r" . $row['id'] . ".mp3";
			$row[7] = "<audio controls> <source src='$rUrl' type='audio/mpeg'></audio>";
		}
		else {
			$row[7] = "N/A";
		}
		$results["data"][] = $row;
	}

	$results["recordsTotal"] = count($results["data"]);
	$results["recordsFiltered"] = count($results["data"]);
	echo json_encode($results);

?>
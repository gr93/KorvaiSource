<?php 
    $config = include('config.php');
    $conn = mysqli_connect($config["DB_Server"], $config["DB_Username"], $config["DB_Password"], $config["DB_Database"]);
    $query = "SELECT * FROM `korvais` ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $maxId = $row['id'];
    $newId = $maxId + 1;

    // Include the SDK using the Composer autoloader
    require 'aws-autoloader.php';
    use Aws\S3\S3Client;
    use Aws\S3\Exception\S3Exception;

    // Set Amazon s3 credentials
    $client = S3Client::factory(
        array(
            'credentials' => array(
                'key'    => $config["S3_Key"],
                'secret' => $config["S3_Secret"]
            ),
            'region'  => 'us-east-1',
            'version' => 'latest'
        )
    );

    try {
        if(isset($_FILES['korvaiText']['tmp_name']) && $_FILES['korvaiText']['tmp_name'] != '') {
            $client->putObject(array(
                'Bucket'=>'korvais',
                'Key' =>  'k' . $newId . ".txt",
                'SourceFile' => $_FILES['korvaiText']['tmp_name'],
                'ACL' => 'public-read'
            ));
        }
        if(isset($_FILES['recording']['tmp_name']) && $_FILES['recording']['tmp_name'] != '') {
            $client->putObject(array(
                'Bucket'=>'korvais',
                'Key' =>  'r' . $newId . ".mp3",
                'SourceFile' => $_FILES['recording']['tmp_name'],
                'ACL' => 'public-read'
            ));
        }
        else if(file_exists("tempRecording.mp3")){
            echo "<script type='text/javascript'>console.log(\"adding temp rec!\");</script>";
            $client->putObject(array(
                'Bucket'=>'korvais',
                'Key' =>  'r' . $newId . ".mp3",
                'SourceFile' => "tempRecording.mp3",
                'ACL' => 'public-read'
            ));
        }

    } catch (S3Exception $e) {
        echo $e;
    }

    $aksharams = $_POST['aksharams'];
    $totalAksharams = $_POST['totalAksharams'];
    $talam = $_POST['talam'];
    $nadai = $_POST['nadai'];
    $composer = $_POST['composer'];
    if(isset($_FILES['korvaiText']['name']) && $_FILES['korvaiText']['name'] != '') {
        $korvaiText = $_FILES['korvaiText']['name'];
    }
    else {
        $korvaiText = "None";
    }
    if(isset($_FILES['recording']['name']) && $_FILES['recording']['name'] != '') {
        $recording = $_FILES['recording']['name'];
    }
    else if(file_exists("tempRecording.mp3")){
        $recording = "tempRecording.mp3";
        sleep(3);
        unlink(realpath("tempRecording.mp3"));
    }
    else {
        $recording = "None";
    }
    $notes = $_POST['notes'];
    $query = "INSERT INTO korvais VALUES($newId, '$aksharams', '$totalAksharams', '$talam', '$nadai', '$composer', '$korvaiText', '$recording', '$notes')";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    header('Location: index.php');
?>

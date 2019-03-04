<?php
    // choose a filename in case we get a file
    $filename = "tempRecording.mp3";
    // the Blob will be in the input stream, so we use php://input
    $input = fopen('php://input', 'rb');
    $file = fopen($filename, 'wb'); 
    // Note: we don't need open and stream to stream, we could've used file_get_contents and file_put_contents
    stream_copy_to_stream($input, $file);
    fclose($input);
    fclose($file);
?>
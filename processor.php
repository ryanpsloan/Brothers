<?php
/**********************************************************************************************************************

 *********************************************************************************************************************/
session_start();
//var_dump($_FILES);
//includes
if(isset($_FILES)) { //Check to see if a file is uploaded
    try {
        if (($log = fopen("log.txt", "w")) === false) { //open a log file
            //if unable to open throw exception
            throw new RuntimeException("Log File Did Not Open.");
        }
        $today = new DateTime('now'); //create a date for now
        fwrite($log, $today->format("Y-m-d H:i:s") . PHP_EOL); //post the date to the log
        fwrite($log, "--------------------------------------------------------------------------------" . PHP_EOL); //post to log
        $name = $_FILES['file']['name']; //get file name
        $_SESSION['originalFileName'] = $name;
        fwrite($log, "FileName: $name" . PHP_EOL); //write to log
        $type = $_FILES["file"]["type"];//get file type
        fwrite($log, "FileType: $type" . PHP_EOL); //write to log
        $tmp_name = $_FILES['file']['tmp_name']; //get file temp name
        fwrite($log, "File TempName: $tmp_name" . PHP_EOL); //write to log
        $tempArr = explode(".", $_FILES['file']['name']); //set file name into an array
        $extension = end($tempArr); //get file extension
        fwrite($log, "Extension: $extension" . PHP_EOL); //write to log
        //If any errors throw an exception
        if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
            fwrite($log, "Invalid Parameters - No File Uploaded." . PHP_EOL);
            throw new RuntimeException("Invalid Parameters - No File Uploaded.");
        }
        //switch statement to determine action in relationship to reported error
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                fwrite($log, "No File Sent." . PHP_EOL);
                throw new RuntimeException("No File Sent.");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                fwrite($log, "Exceeded Filesize Limit." . PHP_EOL);
                throw new RuntimeException("Exceeded Filesize Limit.");
            default:
                fwrite($log, "Unknown Errors." . PHP_EOL);
                throw new RuntimeException("Unknown Errors.");
        }
        //check file size
        if ($_FILES['file']['size'] > 2000000) {
            fwrite($log, "Exceeded Filesize Limit." . PHP_EOL);
            throw new RuntimeException('Exceeded Filesize Limit.');
        }
        //define accepted extensions and types
        $goodExts = array("csv");
        $goodTypes = array("text/csv","application/vnd.ms-excel","application/csv");
        //test to ensure that uploaded file extension and type are acceptable - if not throw exception
        if (in_array($extension, $goodExts) === false || in_array($type, $goodTypes) === false) {
            fwrite($log, "This page only accepts .csv files, please upload the correct format." . PHP_EOL);
            throw new Exception("This page only accepts .csv files, please upload the correct format.");
        }
        //move the file from temp location to the server - if fail throw exception
        $directory = "/var/www/html/Brothers/Files";
        if (move_uploaded_file($tmp_name, "$directory/$name")) {
            fwrite($log, "File Successfully Uploaded." . PHP_EOL);
        } else {
            fwrite($log, "Unable to Move File to /Files." . PHP_EOL);
            throw new RuntimeException("Unable to Move File to /Files.");
        }
        //rename the file using todays date and time
        $month = $today->format("m");
        $day = $today->format('d');
        $year = $today->format('y');
        $time = $today->format('H-i-s');
        $newName = "$directory/Brothers-$month-$day-$year-$time.$extension";
        if ((rename("$directory/$name", $newName))) {
            fwrite($log, "File Renamed to: $newName" . PHP_EOL);
            //echo "<p>File Renamed to: $newName </p>";
        } else {
            fwrite($log, "Unable to Rename File: $name" . PHP_EOL);
            throw new RuntimeException("Unable to Rename File: $name");
        }
        $handle = fopen($newName, "r");
        $firstLine = fgets($handle);
        $headers = fgets($handle);

        //var_dump($headers);
        $fileData = array();
        //read the data in line by line
        while (!feof($handle)) {
            $line_of_data = fgets($handle); //gets data from file one line at a time
            $line_of_data = trim($line_of_data); //trims the data
            $fileData[] = explode(",", $line_of_data); //breaks the line up into pieces that the array can store
        }
        //close file reading stream
        fclose($handle);
        //var_dump($fileData);

        foreach($fileData as $key => $line){
            if(count($line) < 15){
                unset($fileData[$key]);
            }
        }

        $output = $exceptions = array();

        foreach($fileData as $line){
            if($line[0] !== '') {
                if ($line[2] !== '') {
                    $output[] = array($line[0], "", "", "", "", "E", "01", $line[9], $line[2], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[3] !== '') {
                    $output[] = array($line[0], "", "", "", "", "E", "02", $line[9], $line[3], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[4] !== '') {
                    $output[] = array($line[0], "", "", "", "", "", "", $line[9], $line[4], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[5] !== '') {
                    $output[] = array($line[0], "", "", "", "", "E", "04", $line[9], $line[5], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[6] !== '') {
                    $output[] = array($line[0], "", "", "", "", "E", "08", $line[9], $line[6], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
            }else{
                if ($line[2] !== '') {
                    $exceptions[] = array("No ID", $line[1], "", "", "", "E", "01", $line[9], $line[2], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[3] !== '') {
                    $exceptions[] = array("No ID", $line[1], "", "", "", "E", "02", $line[9], $line[3], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[4] !== '') {
                    $exceptions[] = array("No ID", $line[1], "", "", "", "", "", $line[9], $line[4], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[5] !== '') {
                    $exceptions[] = array("No ID", $line[1], "", "", "", "E", "04", $line[9], $line[5], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
                if ($line[6] !== '') {
                    $exceptions[] = array("No ID", $line[1], "", "", "", "E", "08", $line[9], $line[6], "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
                }
            }
        }

        $month = $today->format("m");
        $day = $today->format('d');
        $year = $today->format('y');
        $time = $today->format('H-i-s');
        $fileName = "Files/Brothers_Apparel_Evo_Import-" . $month . "-" . $day . "-" . $year . "-". $time. ".csv";
        $handle = fopen($fileName, 'wb');


        foreach($output as $line){
            fputcsv($handle, $line);
        }

        fclose($handle);
        $_SESSION['fileName'] = $fileName;
        $_SESSION['output'] = "Import File Successfully Created";

        if(count($exceptions) > 0) {
            $exceptionFileName = "Files/Brothers_Apparel_Exceptions" . $month . "-" . $day . "-" . $year . "-" . $time . ".csv";
            $handle = fopen($exceptionFileName, 'wb');

            foreach ($exceptions as $line) {
                fputcsv($handle, $line);
            }
            fclose($handle);

            $_SESSION['exceptionFileName'] = $exceptionFileName;
            $_SESSION['exception'] = "Exception File Created";
        }

        header("Location: index.php");

    } catch (Exception $e) {
        $_SESSION['output'] = $e->getMessage();
        header('Location: index.php');
    }
}else{
    $_SESSION['output'] = "<p>No File Was Selected</p>";
    header('Location: index.php');
}




?>
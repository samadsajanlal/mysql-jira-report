<?php
/*this file contains most of the logic required for generating the report
some things (such as UTF-8 encoding) have been left out of this file on purpose
you likely do not need to modify this file. */

//if it already exists when the script starts, it needs to be deleted and recreated
if (file_exists($file)) { unlink ($file); }

// convert to UTF-8 if we enable it
if($UTF8) {
	file_put_contents($file, "\xEF\xBB\xBF", FILE_APPEND | LOCK_EX);
}

//write the headers
file_put_contents($file, $headers.$lineFeed, FILE_APPEND | LOCK_EX);

// set up mysql connection
$link = mysqli_connect($mysql_server, $mysql_username, $mysql_password, $mysql_database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s".$lineFeed, mysqli_connect_error());
    exit();
}

/* retrieve all rows for report */
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
		$resultRow = "";
		for($i = 0; $i < $number_of_columns; $i++){
			if($row[$i] != "") {
				// replace line feed characters before outputting to file
				$searchFor = array("\r\n", "\n", "\r");
				$tmp = str_replace($searchFor,$replaceLineFeedCharactersWith,$row[$i]);
				//$resultRow.= $row[$i] . $seperator;
				$resultRow.= $tmp . $seperator;
			} else {
				$resultRow.= $replaceBlanksWith.$seperator;
			}
		}
		$resultRow = substr($resultRow, 0, -1); //cut off extra seperator
		$resultRow.= $lineFeed;
		file_put_contents($file, $resultRow, FILE_APPEND | LOCK_EX);
    }
    /* free result set */
    mysqli_free_result($result);
}

/* close connection */
mysqli_close($link);

// transmit file to remote server
$remote_file = $ftp_directory.$file;

// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) { // we really should use FTP_ASCII but this adds extra blank lines
 echo "successfully uploaded $file".$lineFeed;
} else {
 echo "There was a problem while uploading $file".$lineFeed;
}

// close the connection
ftp_close($conn_id);

//finally, delete the file
if (file_exists($file)) { unlink ($file); }
?>
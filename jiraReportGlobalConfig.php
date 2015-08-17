<?php
/* global config for scripts */
$mysql_server = ''; // ip address or hostname for mysql
$mysql_username = ''; // username for mysql
$mysql_password = ''; // password for mysql
$mysql_database = ''; // database to run query on
$ftp_server = ''; // ip address or hostname for ftp server
$ftp_user_name = ''; // username for ftp
$ftp_user_pass = ''; // password for ftp
$ftp_directory = ''; // directory to store contents in. must start and end with /. Example: '/Jira_SR_Data/'

$lineFeed = "\r\n"; // invisible characters for carriage return at the end of each line.
$replaceBlanksWith = "NULL"; // if a blank cell is found, this value will be used in place of the blank
$replaceLineFeedCharactersWith = "~"; // if a UNIX or DOS linefeed character is encountered in the result, it will be replaced with the value here. For example: \r\n will be replaced by ~
$seperator = "|"; // the seperator used to denote where a cell ends and the next begins
$date = date('Ymd'); // date formatting to be used in filename. Uses PHP Date() function for formatting. http://php.net/manual/en/function.date.php

/* config for main report ONLY */
$fileIdentifierMR = "_JIRA_SR_"; // middle part of file name, after the customer pkey. Default: "_JIRA_SR_"
$extensionMR = ".csv"; // file extension to be used. default ".csv"

/* config for linked report ONLY */
$filePrefixLR = "JIRA_clone_link_"; // prefix to be used for the file name. Default  "JIRA_clone_link_"
$extensionLR = ".csv"; // file extension to be used. default ".csv"
?>
<?php
include("./jiraReportGlobalConfig.php"); // include mysql and ftp connection details, do not remove this line

/* VARIABLES REQUIRED FOR SCRIPT */
$UTF8 = true; // boolean value to determine if UTF-8 encoding should be used. Default for this report: true

// csv file header - column labels
$number_of_columns = 2; // this is the total number of columns to parse, based on headers below. default is 2
$headers = "Source_ID|Cloned_ID";

/* SQL QUERY - note the inclusion of variables here. */
$query = "select issuelink_66.SOURCE AS 'Source_ID', issuelink_66.DESTINATION AS 'Cloned_ID'
from issuelink issuelink_66
where
issuelink_66.linktype = (select ID from issuelinktype where issuelinktype.LINKNAME = 'Clones')";

/*************************************************************************************************************************/
/********************************** Logic - do NOT modify below this line! ******************************************/
/*************************************************************************************************************************/
$file = $filePrefixLR.$date.$extensionLR; //sets filename to <prefix><YYYYMMDD><extension>

include("./jiraReportFunctionality.php");
?>
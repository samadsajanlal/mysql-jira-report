<?php
// variable - full path to reportConfig.txt file (which contains query parameters)
$filepath = '/home/esamsaj/scripts/jiraCustomerReportConfig.txt';

// note: shell_exec statements below may need to be modified depending on where these scripts reside

/*************************************************************************************************************************/
/********************************** Logic - do NOT modify below this line! ******************************************/
/*************************************************************************************************************************/
// read in configuration file (lines of commands)
$lines = file($filepath);

// Loop through our array, executing shell commands as we go.
foreach ($lines as $line_num => $line) {
	$output = shell_exec('/opt/lampp/bin/php /home/esamsaj/scripts/jiraMainReport.php '.$line);
	echo $output."\r\n";
}

// as we only have one report for linked issues, just execute that query once.
$output = shell_exec('/opt/lampp/bin/php /home/esamsaj/scripts/jiraLinkedReport.php');
echo $output."\r\n";
?>
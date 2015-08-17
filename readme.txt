JIRA Reporting Application

=======================================================================================
BEFORE YOU BEGIN
=======================================================================================

PHP requires the use of escape characters in a string if the string contains double quotes. The SQL queries in this application have been modified to use single quotes, but at a later date may need updates to the query itself. Use single quotes wherever possible, and escape all double quotes!

This application uses server time to determine the date. If server time is set incorrectly (for example, UTC whereas the server is physically located in CDT), the files will be tagged with the wrong date.
Similarly, cron operations will be affected based on time zone.

This application delivery consists of 7 files:
1) jiraBatchReports.php
2) jiraCustomerReportConfig.txt
3) jiraLinkedReport.php
4) jiraMainReport.php
5) jiraReportFunctionality.php
6) jiraReportGlobalConfig.php
7) readme.txt (this file)

=======================================================================================
CONFIGURATION
=======================================================================================
Configuration parameters exist in: jiraCustomerReportConfig.txt, jiraReportGlobalConfig.php, jiraBatchReports.php, jiraMainReport.php, jiraLinkedReport.php

The configuration file for reports ("jiraCustomerReportConfig.txt") must be present on the server. The configuration file follows the format below. One line per required report. If the GSC Project name is not needed, simply put "X". All three variables are required to execute the report script. These values are passed directly to the MySQL query.

Format:
<customer project name> <gsc project name> <label>
<customer project name> <gsc project name> <label>
<customer project name> <gsc project name> <label>


Example config file:
---- start of file ---- (do not include this line)
BTC IND BTC
DIGITEL IND Digitel
---- end of file ---- (do not include this line)

A sample configuration file is included in the directory for two reports ("jiraCustomerReportConfig.txt");

"jiraBatchReports.php" must be configured with the absolute path to the "jiraCustomerReportConfig.txt" file.
"jiraReportGlobalConfig.php" must be configured with MySQL and FTP credentials, as well as some report-specific details (such as seperators)

Each report script ("jiraMainReport.php", "jiraLinkedReport.php") contains some minor configuration towards the top of the file. These files can also be modified to update the SQL query.

=======================================================================================
USAGE INSTRUCTIONS
=======================================================================================

After configuration simply execute the application via the following command (executed in shell):

 /opt/lampp/bin/php /home/esamsaj/scripts/jiraBatchReports.php
 
Optionally, a cron job can be set to execute the report automatically on a recurring basis. Example cron that executes batch reporting at 12AM server time
0 0 1 * * /opt/lampp/bin/php /home/esamsaj/scripts/jiraBatchReports.php > /dev/null 2>&1

=======================================================================================
=======================================================================================
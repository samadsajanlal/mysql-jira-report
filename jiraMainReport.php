<?php
include("./jiraReportGlobalConfig.php"); // include mysql and ftp connection details, do not remove this line

/* VARIABLES REQUIRED FOR SCRIPT */

$UTF8 = false; // boolean value to determine if UTF-8 encoding should be used. Default for this report: false

// csv file header - column labels
$number_of_columns = 23; // this is the number of columns to parse, based on headers below. default is 23
$headers = "pname|pkey|issuenum|ID|SUMMARY|Request Type|Reporter|Creator|Assignee|CREATED|Status|Priority|UPDATED|DUEDATE|LABEL|SR_Type|S_Elements|Assignee_Notes|CTL_Notes|GCH_Notes|SDM_Notes|Prev_Status|Change_Time";

/* SQL QUERY - note the inclusion of variables in line!! */
$query = "select final1.*, temp00.SR_Type, temp11.S_Elements, temp22.Assignee_Notes, temp33.CTL_Notes, temp44.GCH_Notes, temp55.SDM_Notes, Prev_Status,Change_Time
-- temp66.Orig_ID, temp66.Orig_pkey, temp66.Orig_issuenum,
-- temp66.Orig_Summary 
from
(select temp1.*, LABEL -- ,Prev_Status,Change_Time
from 
(SELECT project_0.pname, project_0.pkey, jiraissue_0.issuenum, jiraissue_0.ID, 
jiraissue_0.SUMMARY, issuetype_0.pname AS 'Request Type',  
(select distinct display_name from cwd_user where LOWER_USER_NAME = jiraissue_0.REPORTER )AS 'Reporter', 
(select distinct display_name from cwd_user where LOWER_USER_NAME = jiraissue_0.CREATOR )AS 'Creator', 
(select distinct display_name from cwd_user where LOWER_USER_NAME = jiraissue_0.ASSIGNEE )AS 'Assignee', 
jiraissue_0.CREATED,  issuestatus_0.pname AS 'Status', priority_0.pname AS 'Priority', jiraissue_0.UPDATED, jiraissue_0.DUEDATE
FROM jiradb.issuestatus issuestatus_0, jiradb.jiraissue jiraissue_0, jiradb.label label_0, jiradb.project project_0, jiradb.project_key project_key_0, jiradb.issuetype issuetype_0, priority priority_0
WHERE  issuestatus_0.ID = jiraissue_0.issuestatus
AND project_0.ID = jiraissue_0.PROJECT
AND project_key_0.PROJECT_ID = project_0.ID
AND jiraissue_0.ISSUETYPE = issuetype_0.ID
AND jiraissue_0.PRIORITY = priority_0.SEQUENCE
) temp1 
-- left outer join
--  select issueid, chgrp_0.CREATED Change_Time, chgitem_0.newstring Prev_Status from changegroup chgrp_0, changeitem chgitem_0
-- Where chgrp_0.id = chgitem_0.groupid
-- AND chgitem_0.field = 'status')temp2
-- on temp2.issueid = temp1.id
left outer join 
( select issue,LABEL from label where LABEL = '". $argv[3]."') temp3
on ISSUE = temp1.ID
GROUP BY ID
HAVING (
(pkey IN ('". $argv[2] ."')) AND LABEL IN ('". $argv[3]. "') OR pkey IN ('". $argv[1] ."')
)
) final1 
left outer join
 (select issueid, chgrp_0.CREATED Change_Time, chgitem_0.newstring Prev_Status from changegroup chgrp_0, changeitem chgitem_0
 Where chgrp_0.id = chgitem_0.groupid
 AND chgitem_0.field = 'status')temp2
on temp2.issueid = final1.id

Left outer join
-- (Select temp00.ID, temp00.SR_Type, temp11.S_Elements, -- temp22.Assignee_Notes, temp33.CTL_Notes,
-- temp44.GCH_Notes, temp55.SDM_Notes from 
(select jiraissue_0.ID AS 'ID', customfieldoption_0.customvalue AS 'SR_Type' from 
jiraissue jiraissue_0,
customfield customfield_0,
customfieldvalue customfieldvalue_0,
customfieldoption customfieldoption_0
where 
customfieldoption_0.ID = customfieldvalue_0.STRINGVALUE
AND customfieldoption_0.CUSTOMFIELD = customfieldvalue_0.CUSTOMFIELD
AND customfieldvalue_0.CUSTOMFIELD = customfield_0.ID
AND customfield_0.cfname = 'Service Request Type'
AND customfieldvalue_0.issue =jiraissue_0.ID
ORDER by  jiraissue_0.ID
) temp00
ON final1.ID = temp00.ID
left outer join
(select jiraissue_11.ID, customfieldoption_11.customvalue AS 'S_Elements' from 
jiraissue jiraissue_11,
customfield customfield_11,
customfieldvalue customfieldvalue_11,
customfieldoption customfieldoption_11
where 
customfieldoption_11.ID = customfieldvalue_11.STRINGVALUE
AND customfieldoption_11.CUSTOMFIELD = customfieldvalue_11.CUSTOMFIELD
AND customfieldvalue_11.CUSTOMFIELD = customfield_11.ID
AND customfield_11.cfname = 'Service Elements'
AND customfieldvalue_11.issue =jiraissue_11.ID
ORDER by  jiraissue_11.ID
) temp11
ON temp00.ID = temp11.ID

left outer join
(select jiraissue_22.ID AS 'ID', customfieldvalue_22.TEXTVALUE  AS 'Assignee_Notes' from 
jiraissue jiraissue_22,
customfield customfield_22,
customfieldvalue customfieldvalue_22
where 
customfieldvalue_22.CUSTOMFIELD = customfield_22.ID
AND customfield_22.cfname = 'Assignee Notes'
AND customfieldvalue_22.issue =jiraissue_22.ID
ORDER by  jiraissue_22.ID
) temp22
ON temp00.ID = temp22.ID

left outer join
(select jiraissue_33.ID AS 'ID', customfieldvalue_33.TEXTVALUE  AS 'CTL_Notes' from 
jiraissue jiraissue_33,
customfield customfield_33,
customfieldvalue customfieldvalue_33
where 
customfieldvalue_33.CUSTOMFIELD = customfield_33.ID
AND customfield_33.cfname = 'CTL Notes'
AND customfieldvalue_33.issue =jiraissue_33.ID
ORDER by  jiraissue_33.ID
) temp33
ON temp00.ID = temp33.ID

left outer join
(select jiraissue_44.ID AS 'ID', customfieldvalue_44.TEXTVALUE  AS 'GCH_Notes' from 
jiraissue jiraissue_44,
customfield customfield_44,
customfieldvalue customfieldvalue_44
where 
customfieldvalue_44.CUSTOMFIELD = customfield_44.ID
AND customfield_44.cfname = 'GCH Notes'
AND customfieldvalue_44.issue =jiraissue_44.ID
ORDER by  jiraissue_44.ID
) temp44
ON temp00.ID = temp44.ID

left outer join
(select jiraissue_55.ID AS 'ID', customfieldvalue_55.TEXTVALUE  AS 'SDM_Notes' from 
jiraissue jiraissue_55,
customfield customfield_55,
customfieldvalue customfieldvalue_55
where 
customfieldvalue_55.CUSTOMFIELD = customfield_55.ID
AND customfield_55.cfname = 'SDM Notes'
AND customfieldvalue_55.issue =jiraissue_55.ID
ORDER by  jiraissue_55.ID
) temp55
ON temp00.ID = temp55.ID

left outer join
(select issuelink_66.DESTINATION AS 'Cloned_ID', jiraissue_66.ID AS 'Orig_ID', 
project_66.pkey AS 'Orig_pkey', jiraissue_66.issuenum AS 'Orig_issuenum', jiraissue_66.SUMMARY AS 'Orig_Summary' 
from jiraissue jiraissue_66, project project_66,  
issuelink issuelink_66 
where project_66.ID = jiraissue_66.PROJECT 
AND jiraissue_66.ID = issuelink_66.SOURCE 
AND issuelink_66.linktype = (select ID from issuelinktype where issuelinktype.LINKNAME = 'Clones') 
) temp66
ON temp00.ID = temp66.Cloned_ID
ORDER BY issuenum";

/*************************************************************************************************************************/
/********************************** Logic - do NOT modify below this line! ******************************************/
/*************************************************************************************************************************/
if(isset($argv[1]) && isset($argv[2]) && isset($argv[3])){
	// check to make sure all parameters are present. script won't execute if missing parameters (exit())
	$file = $argv[1].$fileIdentifierMR.$date.$extensionMR; // sets filename to <Variable1><identifier><YYYYMMDD><extension>

	include('./jiraReportFunctionality.php');
} else {
	echo "Missing parameters! Make sure to include all three: CUSTOMER PROJECT NAME, GSC PROJECT NAME, and LABEL".$lineFeed;
	echo "Execute in format: mainReport.php CPN GPN LABEL".$lineFeed;
	exit();
}
?>
<?php
require(dirname(pathinfo(__FILE__, PATHINFO_DIRNAME)) . "/SiteSettings.php" );
AllowLoggedIn();
$Cnxn = mysql_connect('localhost', '2017Winter', 'Winter2018');
if (!$Cnxn) {
  print "Unable to connect to the MySQL server at this time...";
  exit;
}
$DB = mysql_select_db('2017Winter', $Cnxn);
if (!$DB) {
  print "Unable to select the 2017Winter database at this time...";
  exit;
}


$SQLStmt = "select RoleId, TheOrg.AcctLastName as OrgName, concat(ThePerson.AcctFirstName,' ',ThePerson.AcctLastName) as Manager,(TheCreator.AcctLastName) as  WhoCreated from Roles join Accounts as TheOrg on OrgId=TheOrg.AcctId join Accounts as TheCreator on Roles.WhoCreated= TheCreator.AcctId join Accounts as ThePerson on Roles.AcctId=ThePerson.AcctId
where TheOrg.AcctEntityType='Partner Organization' and DTCreated > '2019-01-01' and Role='Manager' and RoleStatus='Active' order by OrgName, ThePerson.AcctLastName";

$MAResults = mysql_query($SQLStmt) or die("Can't do '$SQLStmt'...");
$MACount = mysql_num_rows($MAResults);
if ($MACount == 0) {
  $UI = "<h2>Nothing to report...</h2>\n";
} else {
  $UI = "<h2>Recent Applicants</h2>\n";
  if ($MACount == 1) {
    $UI .= "There is one applicant at " . date('H:i Y-m-d') . ':';
  } else {
    $UI .= "There are $MACount applicants at " .  date('H:i Y-m-d') . ':';
  }
  $UI .= "\n\n<p>Click <a href=\"FCForm.php\">Membership Application</a> to enter another membership application.</p>";
  $UI .= "\n\n<br /><br />\n<div class=\"Row Centering\">\n<table class=\"Centered\">\n";
  $UI .= "   <tr class=\"Bottom1px\" valign=\"top\" ><th>Name/Entered By</th>
<th align=\"left\">Customer</th>
<th align=\"left\">Item Purchased</th>   
 <th align=\"center\"> Price</th>   
<th align=\"center\">Comissions</th>
   </tr>\n";
  while ($AMA = mysql_fetch_assoc($MAResults)) {
    foreach($AMA as $Key  => $Value) {
      $AMA[$Key] = htmlspecialchars($Value);
    }
    extract($AMA);
    $MAStatesVisited = str_replace('|','<br />',$MAStatesVisited);
    $UI .= "   <tr valign=\"top\" ><td class=\"AlphaData Bottom1px\" >$MAName<br />$MAUserId</td>
      <td class=\"AlphaData Bottom1px\">$Customer</td>
      <td class=\"AlphaData Bottom1px\">$ItemDescription</td>
<td class=\"AlphaData Bottom1px\">$Price</td>
<td class=\"AlphaData Bottom1px\">$Comission</td>
<td class=\"AlphaData Bottom1px\"></td>


   </tr>\n
   ";
  }
  $UI .= "</table>\n</div>\n\n<br /><br /><p>This is an incomplete listing, please show more...</p>";
}
$FormTemplate = file_get_contents('TemplateFCForm.html');
$FormTemplate = str_replace('[[[LoginAdvice]]]', LoginAdvice("<a href=\"FCController.php\">Menu</a> "), $FormTemplate);
$FormTemplate = str_replace('[[[TheForm]]]', $UI, $FormTemplate);
echo $FormTemplate;
exit;
?>


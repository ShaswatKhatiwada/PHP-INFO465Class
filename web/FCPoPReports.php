<?php
require(dirname(pathinfo(__FILE__, PATHINFO_DIRNAME)) . "/SiteSettings.php" );
AllowLoggedIn();
if (!isset($_REQUEST["View"])) {
  $_SESSION['ControllerMsg'] = "Somehow, no report was selected.  Please click a link below and don't be wandering around the data...";
  header("Location: FCController.php");
  exit;
} 
$Cnxn = mysql_connect('localhost', 'sespop', 'sespop');
if (!$Cnxn) {
  print "Unable to connect to the MySQL server at this time...";
  exit;
} 
$DB = mysql_select_db('SeSPoP', $Cnxn);
if (!$DB) {
  print "Unable to select the SeSPoP database at this time...";
  exit;
}
extract($_REQUEST);
if ($View == "ByRegion") {
  $SQLStmt = "select sum(Amount) as RegionAmount, RegionId, RegionName from Donations, Regions 
                where Regions.Id=RegionId group by RegionName order by RegionAmount desc";
  $RegionResults = mysql_query($SQLStmt) or die ("Unable to access database at this time...");
  $Report = "\n\n<div class=\"Centering\">\n  <h1>Donations by Region:</h1>
    <table class=\"Centered\" >
      <tr><th>Region</th><th>Amount</th></tr>\n";
  while ($ARow = mysql_fetch_assoc($RegionResults)) {
    extract($ARow);
    $Report .= "      <tr><td class=\"AlphaData\">$RegionName</td><td class=\"NumericData\">$RegionAmount</td></tr>\n";
  }
  $Report .= "   </table><br /><br /><br />\n\n</div>";
} elseif ($View  == "TopTen") {
  $SQLStmt = "select MemberName, Amount from Donations order by Amount desc limit 10";
  $TopTenResults = mysql_query($SQLStmt) or die ("The database is not available at this time...");
  $Report = "<div class=\"Centering\">
      <h1>Top Ten Donations</h1>
        <table class=\"Centered\">
            <tr><th>Donor</th><th>Amount</th></tr>\n";
  while ($ARow = mysql_fetch_assoc($TopTenResults)) {
     extract($ARow);
     $Report .= "            <tr><td class=\"AlphaData\">$MemberName</td><td class=\"NumericData\" >$Amount</td></tr>\n";
  }
  $Report .= "       </table><br /><br />\n      </div>\n\n";

} elseif ($View == "SelectRegion") {
    $Report = "<h1>Select Region</h1>\n<p>Select a Region and click DetailsForRegion to see the report:</p>\n";
    //Construct a select named WhichRegion
    $SQLStmt = "select Id, RegionName from Regions order by RegionName";  
    $RegionResults = mysql_query($SQLStmt) or die ("Unable to do $SQLStmt at this time...");
    $Report .= "\n\n\n<form method=POST action=\"FCPoPReports.php\" >\n\n";

    $Report .= "  &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;<select name=\"WhichRegion\" size=\"10\">\r\n";
    while ($ARegion = mysql_fetch_assoc($RegionResults)) {
      extract($ARegion);
      $Report .= "       <option value=\"$Id\">$RegionName</option>\r\n";
    }
    $Report .= "    </select>";
    $Report .= "<p>Click <input type=submit name=View value=DetailsForRegion> to report the selected region...</p>
     </form>\n\n\n\r\n";

} elseif ($View == "DetailsForRegion") {
    //Make sure WhichRegion has been supplied
    if (!isset($_POST["WhichRegion"]) or $_POST["WhichRegion"] == "") {
      $Report = "<font color=red>* </font>Please select a Region before clicking the DetailsForRegion button...";
    } else {
      //Make the SQL Statement
      $WhichRegion = addslashes($_POST["WhichRegion"]);
      $SQLStmt = "select RegionName from Regions where Id=$WhichRegion";
      $RegionResult = mysql_query($SQLStmt) or die ("Can't do '$SQLStmt'");
      $RegionRec = mysql_fetch_assoc($RegionResult);
      extract($RegionRec);
      $SQLStmt = "select VolunteerName, MemberName, Amount, AccountingDate from Donations, Volunteers
                  where RegionId='$WhichRegion' and Volunteers.Id=VolunteerId 
		  order by Amount desc";
      //Use SQLStmt in a query to get RegionResults
      $RegionResults = mysql_query($SQLStmt) or die ("Can't do '$SQLStmt'...");
      //Setup $Report's header
      $Report = "<div class=\"Centering\">\n<table class=\"Centered\">\n
      <tr><th>Volunteer</th><th>Donor</th><th>Amount</th></tr>\n";
      $TotalForRegion = 0;
      $CountForRegion = 0;
      //Concatenate each ARow onto Results 
      while ($ARow = mysql_fetch_assoc($RegionResults)) {
        extract($ARow);
	$AmountFormatted = number_format($Amount,2);
	$TotalForRegion += $Amount;
	$CountForRegion ++;
	$Report .= "    <tr><td class=\"AlphaData\">$VolunteerName</td><td class=\"AlphaData\">$MemberName</td><td class=\"NumericData\">$Amount</td></tr>\n";
        //$Report .= "$VolunteerName - $MemberName - $Amount = $AccountingDate<br>";
      }
      $Report .= "</table><br /><br />\r\n</div>";
      $TotalFormatted = number_format($TotalForRegion,2);
      $AvgSlug = '';
      if ($CountForRegion > 1) {
        $AvgSlug = 'Avg: ' . number_format($TotalForRegion/$CountForRegion,2);
      }
      $Report = "<h1>Donations for Region #$WhichRegion $RegionName</h1>
        <p>Count: $CountForRegion  Total: \$$TotalFormatted  $AvgSlug</p>\r\n $Report";
    }

} else {
    //Somehow they got here without a valid report in $WhichReport
    $Report = "No valid report was selected...";  
}
$ReportTemplate = file_get_contents('TemplateFCForm.html');
$ReportTemplate = str_replace('[[[LoginAdvice]]]', LoginAdvice(" | <a href=\"FCController.php\">Menu</a> "), $ReportTemplate);
$ReportTemplate = str_replace('[[[TheForm]]]', $Report, $ReportTemplate);
echo $ReportTemplate;
exit;

?>

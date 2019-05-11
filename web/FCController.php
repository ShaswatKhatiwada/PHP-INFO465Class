<?php
require(dirname(pathinfo(__FILE__, PATHINFO_DIRNAME)) . "/SiteSettings.php" );
AllowLoggedIn();
if (isset($_SESSION['ControllerMsg'])) {
  $MsgSlug = "<p>" .$_SESSION['ControllerMsg']."</p>\n";
  unset($_SESSION['ControllerMsg']);
} else {
  $MsgSlug = '';
}
$TheForm = "
$MsgSlug
<main class=\"Row\">
  <div class=\"Col-6\">
    <h2>Menu</h2>
    <ul>
      <li><a href=\"index.html\">Home Page</a></li>
      <li><a href=\"FCForm.php\">Membership Application</a></li>
      <li><a href=\"FCApplicatants.php\">Applicants</a></li>
      <li><a href=\"FCSalesCommissions.php\">2017Winter Commissions</a></li>
   </ul>
  </div>
  <div class=\"Col-6\">
   <h2>FCPoP Reports</h2>
    <ul>
      <li><a href=\"FCPoPReports.php?View=TopTen\">Top Ten Donations</a></li>
      <li><a href=\"FCPoPReports.php?View=ByRegion\">Donations by Region</a></li>
      <li><a href=\"FCPoPReports.php?View=SelectRegion\">Details for Selected Region</a></li>
    </ul>
  </div>
</main>
";
$FormTemplate = file_get_contents('TemplateFCForm.html');
$FormTemplate = str_replace('[[[LoginAdvice]]]', LoginAdvice(''), $FormTemplate);
$FormTemplate = str_replace('[[[TheForm]]]', $TheForm, $FormTemplate);
echo $FormTemplate;
exit;
?>

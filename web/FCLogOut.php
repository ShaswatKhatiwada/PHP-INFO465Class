<?php
require(dirname(pathinfo(__FILE__, PATHINFO_DIRNAME)) . "/SiteSettings.php" );
session_destroy();
$TheForm = "<h1>Logged Out...</h1>
   <p>Click <a href=\"index.html\">Home Page</a> to return to FC's home page.</p>
   <p>Click <a href=\"FCLogIn.php\">Log In</a> to log in again.</p>
   ";
$FormTemplate = file_get_contents('TemplateFCForm.html');
$FormTemplate = str_replace('[[[LoginAdvice]]]', '&nbsp;', $FormTemplate);
$FormTemplate = str_replace('[[[TheForm]]]', $TheForm, $FormTemplate);
echo $FormTemplate;
exit;
?>

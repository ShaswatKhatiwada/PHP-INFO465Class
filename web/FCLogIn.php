<?php
session_name('FC');
session_start();
if (isset($_SESSION['LoginId'])) {
  $_SESSION['MenuMsg'] = "<p>You are logged in as '" . $_SESSION['LoginId'] . "'...</p>";
  header("Location: FCController.php");
  exit;
}
function MakeLoginForm() {
  if (isset($_POST)) extract($_POST);
  $LoginIdSlug = '';
  if (isset($LoginId)) {
    $LoginIdSlug=" value=\"$LoginId\" ";
  }
  return "

   <form method=\"post\" action=\"FCLogIn.php\" name=\"LoginForm\">
     <fieldset name=\"LoginFields\"><legend>Log In</legend>
       <label for=\"LoginId\">Login Id: </label><input type=\"text\" name=\"LoginId\" size=\"30\" $LoginIdSlug autofocus />
       <br /><br />
       <label for=\"Password\">Password: </label><input type=\"password\" name=\"Password\" size=\"30\" />
       <br /><br />
       <label for=\"View\">Click: </label><input type=\"submit\" name=\"View\" value=\"Log In\" />
       <br />
     </fieldset>
     <br />
   </form>

  ";
}
//Mainline continues
if (isset($_POST['View'])) {
  $View = $_POST['View'];
} else {
  $View = 'First';
}
if (isset($_REQUEST['Msg'])) {
  $Msg = "<p>" . $_REQUEST['Msg'] . "</p>\n";
} else {
  $Msg = '';
}
if ($View == 'First') {
  $TheForm = "$Msg<p>Welcome to FC's Member Site!</p>
    <p>Log in using your 2017Winter credentials... </p>"
    . MakeLoginForm();
} elseif ($View == 'Log In') {
  //print_r($_POST,false); exit;
  if (!isset($_SESSION['AttemptCount'])) $_SESSION['AttemptCount'] = 1;
  extract($_POST);
  if (!isset($LoginId) or !isset($Password) ) {
    session_destroy();
    echo "Please don't do that...";
    sleep(3);
    echo "Behave yo'seff or git, git, git away...";
    exit;
  }
  if ($LoginId == '' or $Password == '') { 
    $_SESSION['AttemptCount'] ++;
    $TheForm = "<p>Please enter both your 2017 Winter login id _and_ password before clicking Log In.  
       Attempts: " . $_SESSION['AttemptCount'] . "</p>";
  } else {
    $HashedPassword = sha1($Password);
    $UserId = addslashes($UserId);
    $URL = "https://info465.us/2017Winter/AccountRemoteAuth.php?LoginId=$LoginId&HashedPassword=$HashedPassword&RequestingSite=tinstructor";
    //echo $URL; exit;
    $HTTPResponse = file_get_contents($URL) or die("No can do");
    //echo $HTTPResponse; exit;  
    $AuthResponseParts = explode("|",$HTTPResponse);
    //print_r($AuthResponseParts, false); exit;
    if ($AuthResponseParts[0] == 'Yes') {
      //Setup the session and header them to menu or where they rode into
      $_SESSION['FullName'] = $AuthResponseParts[1];
      $_SESSION['LoginId'] = $LoginId;
      $_SESSION['IPAddress'] = $AuthResponseParts[2];
      if (isset($_SESSION['URLAfterLogIn'])) {
	header("Location: ". $_SESSION['URLAfterLogIn']);
	unset($_SESSION['URLAfterLogIn']);
      } else {
        header("Location: FCController.php");
      }
      exit;
    } else {
      $_SESSION['AttemptCount'] ++;
      $TheForm = "<p>The login id and password entered were not a valid combination. Attempts: " . $_SESSION['AttemptCount'] . "</p>";
      $TheForm .= "<pre>" . print_r($AuthResponseParts) . "</pre>";
    }
  }
  if ($_SESSION['AttemptCount'] > 3) {
    session_destroy();
    echo "Please don't be beating up this thing...  ";
    sleep(6);
    echo "Find your login id and password at 2017 Winter before trying again...";
    sleep(3);
    echo "Seek the aid of Rowdy Chihuahua if need be...";
    exit;
  }
  $TheForm .= MakeLoginForm();  
} else {
  session_destroy();
  echo "Please don't do that...";
  sleep(10);
  exit;
}
$FormTemplate = file_get_contents('TemplateFCForm.html');
$FormTemplate = str_replace('[[[LoginAdvice]]]', "Logging in...", $FormTemplate);
$FormTemplate = str_replace('[[[TheForm]]]', $TheForm, $FormTemplate);
echo $FormTemplate;
exit;
?>

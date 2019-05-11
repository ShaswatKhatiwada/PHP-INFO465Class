<?php
session_name('FC');
session_start();
if (!isset($_SESSION['LoginId'])) {
  header("Location: FCLogIn.php?Msg=You must be logged in to access that page...");
  $_SESSION['URLAfterLogIn'] = $_SERVER['REQUEST_URI'];
  exit;
}
function AllowLoggedIn() {
  if (!isset($_SESSION['LoginId'])) {
    header("Location: FCLogIn.php?Msg=You must be logged in to access that page...");
    $_SESSION['URLAfterLogIn'] = $_SERVER['REQUEST_URI'];
    exit;
  }
}
function LoginAdvice($Links) {
  return $_SESSION['FullName']
         . " is logged in as " . $_SESSION['LoginId']
         . " from " . $_SESSION['IPAddress']
         . " $Links |  <a href=\"FCLogOut.php\" >Log Out...</a>";
}
//Modify these to suit your site.  They allow scripts to reference the organization's name appropriately


$OrganizationFullName = "FC Bayern Munchen";
$OrganizationFullPossessive = "FC Bayern Munchen's";
$OrganizationShortName = "FC";
$OrganizationShortPossessive = "FC's";

$DBCnxn = mysql_connect('localhost','skhatiwada', 'Swada') or die ("Unable to connect to the data base");
$DB = mysql_select_db ('skhatiwada', $DBCnxn) or die ("Unable to select database at this time...");
?>

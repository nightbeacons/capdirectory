<?php
include "/var/www/capnorthshore/pwf/db.php";
$SELF= $_SERVER['PHP_SELF'];

include_once $_SERVER['DOCUMENT_ROOT'] . "/directory/common.php";

$user=$_SERVER['PHP_AUTH_USER'];
#$user="445785";

$db=new mysqli("localhost",$SQLuser, $SQLpass, "northshore");


if ((isset($_POST['submit'])) AND ($_POST['submit'] == "Submit")) processForm();


$query="SELECT * from directory WHERE capid='$user' AND active='1'";
  if ( ($result = $db->query($query))===false )
  {
    printf("Invalid query: %s\nWhole query: %s\n", $db->error, $query);
    exit();
  }
$userData = $result->fetch_array(MYSQLI_ASSOC);
//echo "<pre>$query";
//print_r($userData);
//echo "</pre>";
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<title>Northshore Composite Squadron: Update your Listing</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="icon" type="image/png" href="/images/logo32.png">
<meta name="description"
        content=" Northshore Composite Squadron, Civil Air Patrol.">
<meta name="keywords"
        content="CAP, Civil Air Patrol, Northshore Squadron, Bothell, Washington">
<META NAME="revisit-after" content="15 days">

<script language="JavaScript" type="text/javascript">

  function reloadHandler() {
	var userid = document.members.memberPulldown.options[document.members.memberPulldown.selectedIndex].value;
        document.getElementById('datawin').src='data.php?u=' + userid;  
}
</script>
<style type="text/css">
body.custom-background { background-image: url('/wp-content/uploads/2017/06/CAP-Northshore-DEEP-BLUE-gradient-fading-away.jpg'); background-repeat: repeat; background-position: top left; background-attachment: scroll; }
</style>


</head>

<body class="custom-background">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/top.php"; ?>
<table dir="ltr" border="0" cellpadding="0" cellspacing="0" width="960" style="margin-left:30px;background-color:white;">
<tr><td colspan=3 align="center" style="background: url('/wp-content/uploads/2017/06/CAP-Northshore-DEEP-BLUE-gradient-fading-away.jpg') no-repeat center;background-size: 100%;"><br><img src="/wp-content/uploads/2017/05/headerImage.jpg"></td></tr>
<tr><td valign="top" width="1%">

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/navbar.php"; ?>

</td><td valign="top" width="24"></td><td valign="top">

	<!-- ======================= -->
	<!-- Begin Main Content Area -->
	<!-- ======================= -->
<br>
<h3><font face="Garamond">&nbsp;<font size="5" color="#CC3300">Update the Squadron Directory 
</font></font></h3>

<?php
if ((($userData['isAdmin']) AND ($userData['active'])) OR ($capid='445785')) drawPulldown($user);

echo "<IFRAME id=\"datawin\" name=\"datawin\" marginWidth=\"0\" marginHeight=\"0\" src=\"data.php?u=$user\" frameBorder=\"0\" width=\"950\" scrolling=\"no\" height=570></IFRAME>\n";
?>

	<!-- ======================= -->
	<!--  End Main Content Area  -->
	<!-- ======================= -->
</td></tr></table>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
</body>
</html>
<?php

#-----------------------------------------------------------------
#  Draw the pulldown menu
#
function drawPulldown($capid)
{
global $db;

echo "<form name=\"members\" id=\"members\" action=\"\" style=\"margin-left:40px;\">
<SELECT name=\"memberPulldown\" id=\"memberPulldown\"  onChange=\"javascript:reloadHandler();\" style=\"border:#000000;border-style:solid;border-width:1px;\">\n";

$query="SELECT name,capid,type from directory WHERE active='1' ORDER BY name";
$result=$db->query($query);
	while ($myrow=$result->fetch_array(MYSQLI_ASSOC)) {
	$bgColor="#FFFFFF";
	$SELECTED="";
	if ($myrow['type'] == "C") $bgColor="#c0c0c0";
	if ($myrow['capid'] == $capid) $SELECTED=" SELECTED ";
	echo "	<option value=\"" . $myrow['capid'] . "\" style=\"background-color: $bgColor;\" $SELECTED>" . $myrow['name'] . "</option>\n";
	}

echo "</SELECT>
</FORM>\n";


}
#-----------------------------------------------------------------

?>


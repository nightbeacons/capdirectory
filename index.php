<?php
include "/var/www/capnorthshore/pwf/db.php";
$SELF= $_SERVER['PHP_SELF'];

$capid=$_SERVER['PHP_AUTH_USER'];
setcookie ("member", $capid ,time()+86400*180, "/", ".capnorthshore.org");

include_once $_SERVER['DOCUMENT_ROOT'] . "/directory/common.php";

$directions=array("a" => "ASC", "d" => "DESC");
if (isset($_GET['key'])) {
$key=$_GET['key'];
} else{ $key=0;}
$srt=$headingCols[$key];

$DIR="ASC";
if (isset($_GET['o'])) {
$DIR=$directions[$_GET['o']];
}

$filter="E";
if (isset($_GET['f'])) {
$filter=$_GET['f'];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="en-us">
<title>Northshore Composite Squadron: Directory</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="icon" type="image/png" href="/images/logo32.png">
<meta name="description"
        content=" Northshore Composite Squadron, Civil Air Patrol.">
<meta name="keywords"
        content="CAP, Civil Air Patrol, Northshore Composite Squadron, Bothell, Washington">
<META NAME="revisit-after" content="15 days">
<style type="text/css">
tr.rowstyleC {
        display: table-row;
	color: blue;
}

tr.rowstyleS {
	display: table-row;
	color: red;
}

body.custom-background { background-image: url('http://www.capnorthshore.org/wp-content/uploads/2012/03/WPress-CAP-WIDE-Background-Stripe1.jpg'); background-repeat: repeat; background-position: top left; background-attachment: scroll; }

</style>

</head>

<body class="custom-background">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/top.php"; ?>
<table dir="ltr" border="0" cellpadding="0" cellspacing="0" width="960" align="center"><tr><td valign="top" width="1%">

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/navbar.php";
// $db=mysqli_connect("localhost",$SQLuser, $SQLpass, "northshore");
$db = new mysqli("localhost",$SQLuser, $SQLpass, "northshore");

?>

</td><td valign="top" width="24"></td><td valign="top" align="center">

	<!-- ======================= -->
	<!-- Begin Main Content Area -->
	<!-- ======================= -->
<?php
$filteredQuery = preg_replace("/&f=(S|C|E)/", "", $_SERVER['QUERY_STRING']);
$query="SELECT name from directory where capid='" . $capid . "' LIMIT 1";
//$result=mysqli_query($db, $query);
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }
$myrow=$result->fetch_array(MYSQLI_ASSOC);
// mysql_fetch_assoc($result);
$Nary = explode(",", trim($myrow['name']));
$name = trim($Nary[1], " ,") . " " . trim($Nary[0], " ,");
?>
<center><table border="0" cellspacing="0" cellpadding="0">
<tr><td align="center" style="background: url(/images/blue-black-gradient.jpg) no-repeat center;background-size: 100%;"><br><img src="/wp-content/uploads/2017/05/headerImage.jpg"></td></tr>
<tr><td align="center" style="background-color:white;"><table border="0" style="width:960px;background-color:white;">
<tr><td><h3 style="margin-bottom:0;margin-left:10px;">Squadron Directory</h3> 
<?php
echo "<p style=\"margin-top:10px;margin-left:10px;margin-bottom:6px;font-size:14px;\">Logged in as <b>$name</b></p>";
?>
<p style="margin-top:4px;margin-left:10px;font-size:16px;"><i><a href="/directory/admin/">Click here to update your listing</a></i></p>
<p style="margin-top:4px;margin-left:10px;"><a href="map.php">Show all members on a map</a></p></td>
<td align="right"><form name="memberselect" id="memberselect">
<?php
$sqlFilter="";
echo "<table border=\"0\" style=\"background-color:white;\" ><tr><td class=\"directory\"><a href=\"$SELF" . "?" . $filteredQuery . "&f=S\"><input type=\"radio\" name=\"who\" value=\"S\"";
	if ($filter == "S") {
	echo "CHECKED";
	$sqlFilter = " AND type='S' ";
	}
	echo " ></a>Show only Seniors</td><td style=\"padding-left:15px;\"><a href=\"mailto:seniors@capnorthshore.org\"><img border=\"0\" src=\"/images/allseniors.jpg\"></a></td><td title=\"Update your computer or phone address book\"><a href=\"vcard.php?id=seniors\" style=\"text-decoration:none;\"><img border=\"0\" src=\"/images/vcard.jpg\" style=\"margin-left:25px;\"> Add all Seniors to address book</a></td></tr>\n";

echo "<tr><td class=\"directory\"><a href=\"$SELF" . "?" . $filteredQuery . "&f=C\"><input type=\"radio\" name=\"who\" value=\"C\"";
        if ($filter == "C") {
	echo "CHECKED";
        $sqlFilter = " AND type='C' ";
        }
        echo " ></a>Show only Cadets</td><td style=\"padding-left:15px;\"><a href=\"mailto:cadets@capnorthshore.org\"><img border=\"0\" src=\"/images/allcadets.jpg\"></a></td><td title=\"Update your computer or phone address book\"><a href=\"vcard.php?id=cadets\" style=\"text-decoration:none;\"><img border=\"0\" src=\"/images/vcard.jpg\" style=\"margin-left:25px;\"> Add all Cadets to address book</a></td></tr>\n";

echo "<tr><td class=\"directory\"><a href=\"$SELF" . "?" . $filteredQuery . "&f=E\"><input type=\"radio\" name=\"who\" value=\"E\"";
        if ($filter == "E") echo "CHECKED";
        echo " ></a>Show All Members</td><td style=\"padding-left:15px;\"><a href=\"mailto:all@capnorthshore.org\"><img border=\"0\" src=\"/images/allmembers.jpg\"></a></td><td title=\"Update your computer or phone address book\"><a href=\"vcard.php?id=all\" style=\"text-decoration:none;\"><img border=\"0\" src=\"/images/vcard.jpg\" style=\"margin-left:25px;\"> Add all members to address book</a></td></tr>
<tr><td>&nbsp;</td><td style=\"padding-left:15px;\"><a href=\"mailto:parents@capnorthshore.org.org\"><img border=\"0\" src=\"/images/allparents.jpg\"></a></td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td style=\"padding-left:15px;\"><a href=\"mailto:all@capnorthshore.org,parents@capnorthshore.org\"><img border=\"0\" src=\"/images/everyone.jpg\"></a></td><td>&nbsp;</td></tr>
</table>\n";
?>
</form></td></tr>
</table></td></tr>

<?php
echo "<tr><td><table border=\"1\"  cellpadding=\"4\" cellspacing=\"0\" style=\"background-color:white;width:960px;\" align=\"center\">
<tr>\n";

$colHeadings = makeColHeads($key, $DIR, $filter);
echo "$colHeadings</tr>\n";



$result=mysql_query("SELECT * from directory WHERE active=1 $sqlFilter ORDER BY $srt $DIR", $db);

	while ($myrow = mysql_fetch_array($result)) {
	$Nary = explode(",", $myrow['name']);
	$FN = trim($Nary[1], " ,") . " " . trim($Nary[0], " ,");
	$rowStyle = "rowstyle" . $myrow['type'];	# Generates string called "rowstyleC" or "rowstyleS" (Cadet / Senior)
	$row = "<tr>
		<td class=\"directory\">" . $myrow['name']  . "<br><a href=\"vcard.php?id=" . $myrow['capid'] . "\"><img border=\"0\" align=\"right\" src=\"/images/vcard.jpg\" TITLE=\"Download " . $FN . "'s  vCard\"></a></td>
		<td class=\"directory\">" . $myrow['capid'] . "</td>
		<td class=\"directory\" align=\"center\">" . $myrow['rank']  . "</td>
		<td class=\"directory\" style=\"white-space:nowrap;\">" . SQL2CAPdate($myrow['joined']) . "</td>
		<td class=\"directory\" style=\"white-space:nowrap;\">" . SQL2CAPdate($myrow['renew'])  . "</td>\n";
	$mapUrl = "http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=" . str_replace(" ", "+", ($myrow['street'] . " " . $myrow['city'] . " " . $myrow['zip']));

	if ((strlen($myrow['street']) + strlen($myrow['city']) > 4 )) {
	$row .= "	<td class=\"directory\" align=\"right\" title=\" Click for Map \"><a href=\"$mapUrl\" target=\"_blank\"><nobr>" . $myrow['street'] . "</nobr><br>" . $myrow['city'] . "&nbsp;" . $myrow['zip'] . "</a></td>\n";
	} else { 
	$row .= "	<td>&nbsp; </td>\n";
	}


$row .= "                <td class=\"directory\" align=\"right\">";

		if (strlen($myrow['phone1']) > 5) {
		$row .= "<span style=\"white-space:nowrap;\"><a href=\"tel:" . $myrow['phone1'] . "\">" . $myrow['phone1'] . "</a>&nbsp;<i>(" . $myrow['phone1Type'] . ")</i></span>";
		} else {$row .= "&nbsp;"; }

		if (strlen($myrow['phone2']) > 5) { 
		$row .= "<br><span style=\"white-space:nowrap;\"><a href=\"tel:" . $myrow['phone2'] . "\">" . $myrow['phone2'] . "</a>&nbsp;<i>(" . $myrow['phone2Type'] . ")</i></span>"; 
		}

	$row .= "</td>
		<td class=\"directory\" align=\"right\">";

		if (strlen($myrow['email']) > 3) {
		$row .= "<a href=\"mailto:" . $myrow['email'] . "\">" . $myrow['email'] . "</a>";
		} else { $row .= "&nbsp; "; } 


	$row .= "</td>
		<td class=\"directory\">" . $myrow['comments'] . "&nbsp;</td></tr>\n";
	echo $row;
	}
echo "</table></td></tr></table></center>\n";

?>


	<!-- ======================= -->
	<!--  End Main Content Area  -->
	<!-- ======================= -->

</td></tr></table>
</center>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
</body>
</html>
<?php
#-----------------------------------------------------------------
# Convert SQL Date to CAP date form
#
#
function SQL2CAPdate ($dateString)
{
$monthAry=array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

$dateAry=explode("-", $dateString);
$CAPdate = ($dateAry[2]+0) . "-" . $monthAry[($dateAry[1]+0)] . "-" . $dateAry[0];

return ($CAPdate);

}

#-----------------------------------------------------------------
#
function makeColHeads($key, $DIR, $filter)
{
global $headingNames, $SELF;

$colHeadings="";

	for ($i=0; $i< count($headingNames); $i++) {
	$colHeadings .= "<th><a class=\"directory\" href=\"$SELF?key=$i&f=$filter\">$headingNames[$i]</a>";

		if ($key==$i) {

			if ($DIR=="ASC") {
			$colHeadings .=  "<a href=\"$SELF?key=$i&o=d&f=$filter\"><img border=\"0\" src=\"/images/uparrow.png\" TITLE=\" Click to Change the Sort Order \"></a>";
			}

                        if ($DIR=="DESC") {
                        $colHeadings .=  "<a href=\"$SELF?key=$i&o=a&f=$filter\"><img border=\"0\" src=\"/images/downarrow.png\" TITLE=\" Click to Change the Sort Order \"></a>";
                        }


		}
	$colHeadings .= "</th>\n";
	}

return($colHeadings);



}
#-----------------------------------------------------------------

?>

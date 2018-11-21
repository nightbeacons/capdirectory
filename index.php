<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
//print_r($headingCols);
$DIR="ASC";
if (isset($_GET['o'])) {
$DIR=$directions[$_GET['o']];
}

$filter="E";
if (isset($_GET['f'])) {
$filter=$_GET['f'];
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="en-us">
<title>Northshore Composite Squadron: Directory</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="icon" href="http://www.capnorthshore.org/favicon.ico" type="image/x-icon" />
<meta name="description"
        content=" Northshore Composite Squadron, Civil Air Patrol.">
<meta name="keywords"
        content="CAP, Civil Air Patrol, Northshore Composite Squadron, Bothell, Washington">
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<style type="text/css">
tr.rowstyleC {
        display: table-row;
	color: blue;
}

tr.rowstyleS {
	display: table-row;
	color: red;
}

body.custom-background { background-image: url('/wp-content/uploads/2017/06/CAP-Northshore-DEEP-BLUE-gradient-fading-away.jpg'); background-repeat: repeat; background-position: top left; background-attachment: scroll; }

</style>

</head>

<body class="custom-background">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/top.php"; ?>
<table dir="ltr" border="0" cellpadding="0" cellspacing="0" width="960" align="center" style="margin-bottom:3em;"><tr><td valign="top" width="1%">

<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/navbar.php";
$db = new mysqli("localhost",$SQLuser1, $SQLpass1, "cw_068");
?>

</td><td valign="top" width="24"></td><td valign="top" align="center">

	<!-- ======================= -->
	<!-- Begin Main Content Area -->
	<!-- ======================= -->
<?php
$filteredQuery = preg_replace("/&f=(S|C|E)/", "", $_SERVER['QUERY_STRING']);
// $query="SELECT name from directory where capid='" . $capid . "' LIMIT 1";
$query="SELECT CONCAT (trim( ' ' from  NameFirst), ' ',  trim( ' ' from  NameLast)) AS name from Member WHERE CAPID='" . $capid . "' LIMIT 1";
 
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }
  while ($myrow=$result->fetch_array(MYSQLI_ASSOC)) {
      $name=trim($myrow['name']);
  }
//$Nary = explode(",", trim($myrow['name']));
//$name = trim($Nary[1], " ,") . " " . trim($Nary[0], " ,");
?>
<center><table border="10" style="border-style:solid;border-color:#e0e0e0;" cellspacing="0" cellpadding="0">
<tr><td align="center" style="background: url(/images/blue-black-gradient.jpg) no-repeat center;background-size: 100%;"><img width="100%"  src="/wp-content/uploads/2018/10/NS-Oct-2018-1170-x198-Header.jpg"></td></tr>

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
	$sqlFilter = " AND Member.type='SENIOR' ";
	}
	echo " ></a>Show only Seniors</td><td style=\"padding-left:15px;\"><a href=\"mailto:seniors@capnorthshore.org\"><img border=\"0\" src=\"/images/allseniors.jpg\"></a></td><td title=\"Update your computer or phone address book\"><a href=\"vcard.php?id=seniors\" style=\"text-decoration:none;\"><img border=\"0\" src=\"/images/vcard.jpg\" style=\"margin-left:25px;\"> Add all Seniors to address book</a></td></tr>\n";

echo "<tr><td class=\"directory\"><a href=\"$SELF" . "?" . $filteredQuery . "&f=C\"><input type=\"radio\" name=\"who\" value=\"C\"";
        if ($filter == "C") {
	echo "CHECKED";
        $sqlFilter = " AND Member.type='CADET' ";
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



//$result=$db->query("SELECT * from directory WHERE active=1 $sqlFilter ORDER BY $srt $DIR");
        $query="SELECT CONCAT (trim( ' ' from  Member.NameLast), ', ',  trim( ' ' from  Member.NameFirst)) AS name,
                   CONCAT (trim( ' ' from  Member.NameFirst), ' ',  trim( ' ' from  Member.NameLast)) AS name1,
                   LEFT(Member.Type, 1) AS type,
                   trim( ' ' from Member.CAPID) as capid,
                   subquery1.email,
                   subquery2.phone1Type,
                   subquery2.phone1,
                   subquery3.phone2Type,
                   subquery3.phone2,
                   Member.rank,
                   Member.Joined as joined,
                   Member.Expiration as renew,
                   CONCAT (MbrAddresses.Addr1 , ' ' , MbrAddresses.Addr2) AS street,
                   MbrAddresses.City as city,
                   MbrAddresses.State as state,
                   MbrAddresses.Zip as zip, 
                   subquery4.Duty as comments
                    FROM Member 
                  LEFT JOIN MbrAddresses ON Member.CAPID=MbrAddresses.CAPID
                  LEFT JOIN (SELECT MbrContact.Contact AS email, MbrContact.CAPID as hold1 
                             from MbrContact where MbrContact.Type = 'EMAIL' AND MbrContact.Priority='PRIMARY') 
                             as subquery1 ON  Member.CAPID=hold1 
                  LEFT JOIN (SELECT LEFT(Type, 1) AS phone1Type,Priority,Contact as phone1, MbrContact.CAPID as hold2 from MbrContact 
                             WHERE (Type = 'CELL PHONE' OR Type = 'HOME PHONE' OR Type='WORK PHONE') AND (PRIORITY = 'PRIMARY'))
                             as subquery2 ON  Member.CAPID=hold2
                  LEFT JOIN (SELECT LEFT(Type, 1) AS phone2Type,Priority,Contact as phone2, MbrContact.CAPID as hold3 from MbrContact 
                             WHERE (Type = 'CELL PHONE' OR Type = 'HOME PHONE' OR Type='WORK PHONE') AND (PRIORITY = 'SECONDARY'))
                             as subquery3 ON  Member.CAPID=hold3
                   LEFT JOIN (SELECT GROUP_CONCAT(DutyPosition.Duty ORDER BY DutyPosition.Duty ASC) as Duty, CAPID as hold4 from DutyPosition  
				              WHERE Asst=0   GROUP BY CAPID )
				             as subquery4 ON Member.CAPID=hold4
WHERE Member.MbrStatus = 'ACTIVE' $sqlFilter GROUP BY Member.CAPID ORDER BY $srt $DIR";

        if ( ($result = $db->query($query))===false ) {
          printf("Invalid query: %s\nWhole query: %s\n", $db->error, $query);
          exit();
        } 
        while ($myrow=$result->fetch_array(MYSQLI_ASSOC)) {
           $capid=trim($myrow['capid']);
           $myrow['comments'] = preg_replace("/\s?,\s?/", ", ", $myrow['comments']);
	$FN = trim($myrow['name1']);
           if (strlen($myrow['zip']) > 8){
           $myrow['zip'] = substr($myrow['zip'], 0, 5) . "-" . substr($myrow['zip'], -4);
           }
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
	$colHeadings .= "<th style=\"white-space: nowrap;\"><a class=\"directory\" href=\"$SELF?key=$i&f=$filter\">$headingNames[$i]</a>";

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


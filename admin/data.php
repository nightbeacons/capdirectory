<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style type="text/css">
.dirform {
        font-size: 14px;
        font-family: Arial;
}

a.bottomtext {
	color:#996633;
	text-decoration: none;
}

a.bottomtext:hover {
	text-decoration: underline;
}	
</style>
</head>
<body>
<?php
include "/var/www/capnorthshore/pwf/db.php";
error_reporting(E_ALL ^ E_NOTICE);
$SELF= $_SERVER['PHP_SELF'];
$DEBUG=0;
$pw_file="/var/www/capnorthshore/pwf/directory.pwf";

include_once $_SERVER['DOCUMENT_ROOT'] . "/directory/common.php";

$uploadMsg = "";

if (isset($_GET['u'])) {
$user=$_GET['u'];
} else {
$user = $_POST['capid'];
}
#$user="445785";

$db=new mysqli("localhost",$SQLuser, $SQLpass, "northshore");

#initPW();

if ((isset($_POST['submit'])) AND ($_POST['submit'] == "Submit")) processForm();

if ((isset($_POST['submit'])) AND ($_POST['submit'] == "Upload")) $uploadMsg = handleUpload();

#echo "<pre>";
#print_r($_POST);
#print_r($_FILES);
#echo "</pre>";


$query="SELECT * from directory WHERE capid='$user' AND active='1'";
$result=$db->query($query);
$userData=$result->fetch_array(MYSQLI_ASSOC);

$p1C=$p1H=$p1W=$p2C=$p2H=$p2W=$p2N="";
	if ($userData['phone1Type'] == "C") $p1C = " SELECTED ";
        if ($userData['phone1Type'] == "H") $p1H = " SELECTED ";
        if ($userData['phone1Type'] == "W") $p1W = " SELECTED ";

        if ($userData['phone2Type'] == "C") $p2C = " SELECTED ";
        if ($userData['phone2Type'] == "H") $p2H = " SELECTED ";
        if ($userData['phone2Type'] == "W") $p2W = " SELECTED ";
        if ($userData['phone2Type'] == "")  $p2N = " SELECTED ";

echo "<table border=\"0\" width=\"950\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" align=\"left\"><form method=\"POST\" ENCTYPE=\"multipart/form-data\" ACTION=\"$SELF\">
<table border=\"1\" width=\"550\" cellpadding=\"4\" cellspacing=\"0\" style=\"margin-left: 10px;\">
<tr><td class=\"dirform\">Name:</td><td><input type=\"text\" name=\"name\" size=\"15\" VALUE=\"" . $userData['name'] . "\"></td></tr>
<tr><td class=\"dirform\">Rank:</td><td><input type=\"text\" name=\"rank\" size=\"10\" VALUE=\"" . $userData['rank'] . "\"></td></tr>
<tr><td class=\"dirform\">CAP ID:</td><td><input type=\"text\" name=\"capid\" size=\"15\" VALUE=\"" . $userData['capid'] . "\" READONLY style=\"background-color:#e0e0e0;\"></td></tr>
<tr valign=\"top\"><td class=\"dirform\">Address:</td><td class=\"dirform\"><input type=\"text\" name=\"street\" size=\"25\" VALUE=\"" . $userData['street'] . "\" > <i>Street</i><br>
<input type=\"text\" name=\"city\" size=\"25\" VALUE=\"" . $userData['city'] . "\" > <i>City</i><br>
<input type=\"text\" name=\"zip\" size=\"25\" VALUE=\"" . $userData['zip'] . "\" > <i>Zip</i></td></tr>
<tr><td class=\"dirform\">Phone #1:</td><td><input type=\"text\" name=\"phone1\" size=\"15\" VALUE=\"" . $userData['phone1'] . "\">
	<select name=\"phone1Type\">
	<option value=\"C\" $p1C>Cell</option>
	<option value=\"H\" $p1H>Home</option>
	<option value=\"W\" $p1W>Work</option>
	</select>
</td></tr>
<tr><td class=\"dirform\">Phone #2:</td><td><input type=\"text\" name=\"phone2\" size=\"15\" VALUE=\"" . $userData['phone2'] . "\">
        <select name=\"phone2Type\">
        <option value=\"\"  $p2C>None</option>
        <option value=\"C\" $p2C>Cell</option>
        <option value=\"H\" $p2H>Home</option>
        <option value=\"W\" $p2W>Work</option>
        </select>
</td></tr>
<tr><td class=\"dirform\">Cell Provider:</td><td><select name=\"cellprovider\">";
$query="SELECT name, id from cellprovider ORDER BY name";
$result1=$db->query($query);
	while($providers=$result1->fetch_array(MYSQLI_ASSOC)) {
	echo "	<OPTION VALUE=\"" . ($providers['id'] + 0) . "\" ";
		if (($providers['id'] + 0) == $userData['cellprovider']) echo "SELECTED"; 
	echo ">" . $providers['name'] . "</OPTION>\n";

	}

echo "</select>\n</td></tr>\n";

echo "<tr><td class=\"dirform\">Email:</td><td><input type=\"text\" name=\"email\" size=\"25\" VALUE=\"" . $userData['email'] . "\" READONLY></td></tr>\n";

	if ($userData['type'] == "S") {
	$checkYes=$checkNo="";
		if ($userData['alertlist'] == 1) {
		$checkYes=" CHECKED ";
		} else {
		$checkNo=" CHECKED ";
		}
	echo "<tr><td class=\"dirform\">Include on Alerting List:</td><td class=\"dirform\"><input type=\"radio\" name=\"alert\" value=\"1\" $checkYes> Yes &nbsp; &nbsp; &nbsp; <input type=\"radio\" name=\"alert\" value=\"0\" $checkNo> No</td></tr>\n";
	}

	if ($userData['type'] == "C") {
	echo "<tr><td class=\"dirform\">Parent Email #1:<td><input type=\"text\" name=\"parentemail1\" size=\"25\" VALUE=\"" . $userData['parentemail1'] . "\" </td></tr>\n";
        echo "<tr><td class=\"dirform\">Parent Email #2:<td><input type=\"text\" name=\"parentemail2\" size=\"25\" VALUE=\"" . $userData['parentemail2'] . "\" </td></tr>\n";

	}

echo "<tr><td class=\"dirform\">Comments:</td><td><input type=\"text\" name=\"comments\" size=\"25\" VALUE=\"" . $userData['comments'] . "\"></td></tr>
<tr bgcolor=\"aqua\"><td class=\"dirform\">Change Password:<br>
<small>[Optional]</small></td><td TITLE=\" Leave Blank to Keep Your Current Password \" class=\"dirform\">
<input type=\"password\" name=\"password\" size=\"12\" style=\"position:relative;left:15px;\"><input type=\"password\" name=\"password1\" size=\"12\" style=\"position:relative;left:40px;\"><br>
<span style=\"position:relative;left:15px;\"><small><i>New password</i></small></span>
<span style=\"position:relative;left:65px;\"><small><i>Type again to verify</i></small></span><br>
<span style=\"position:relative;left:15px;\"><small><b>Leave Blank to Keep Your Current Password</b></small></span></td></tr>

<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></td></tr>
</table>
";


        if ($userData['type'] != "S") {
	echo "<input type=\"hidden\" name=\"alert\" value=\"0\">\n"; 
	}
?>
</form></td>
<td valign="top" width="375" align="left">
<?php
#$userData['isAdmin']+=0;
#$userData['active']=0;
#$capid="445785";
if ((($userData['isAdmin']) AND ($userData['active'])) OR ($capid=='445785')) {

echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
<tr><th class=\"dirform\">Upload Membership.xls File<br><i>(from eServices)</i></th></tr>
<tr><td align=\"center\">
<form method=\"POST\" ENCTYPE=\"multipart/form-data\" ACTION=\"$SELF\">
<input type=\"file\" name=\"membership\" size=\"15\" style=\"text-align:center;margin:20px;background-color:#FFFF99;\"><br>
<input type=\"hidden\" name=\"capid\" value=\"$user\">
<input type=\"submit\" name=\"submit\" value=\"Upload\" >
</form>
<p class=\"dirform\"><i>$uploadMsg</i></p></td></tr></table>\n";
}
?>
</td></tr>
</table>
<p style="margin-left:10px;font-family:arial;font-size:15px;"><a class="bottomtext" href="/directory/" target="_parent">Return to Directory Listing</a></p>
<br><br>
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
function makeColHeads($key, $DIR)
{
global $headingNames, $SELF;

$colHeadings="";

	for ($i=0; $i< count($headingNames); $i++) {
	$colHeadings .= "<th><a class=\"directory\" href=\"$SELF?key=$i\">$headingNames[$i]</a>";

		if ($key==$i) {

			if ($DIR=="ASC") {
			$colHeadings .=  "<a href=\"$SELF?key=$i&o=d\"><img border=\"0\" src=\"/images/uparrow.png\" TITLE=\" Click to Change the Sort Order \"></a>";
			}

                        if ($DIR=="DESC") {
                        $colHeadings .=  "<a href=\"$SELF?key=$i&o=a\"><img border=\"0\" src=\"/images/downarrow.png\" TITLE=\" Click to Change the Sort Order \"></a>";
                        }


		}
	$colHeadings .= "</th>\n";
	}

return($colHeadings);



}
#-----------------------------------------------------------------
# Process the form
#
function processForm()
{
global $db;

$name=mysql_real_escape_string(stripslashes($_POST['name']), $db);
$rank=mysql_real_escape_string(stripslashes($_POST['rank']), $db);
$capid=trim(mysql_real_escape_string(stripslashes($_POST['capid']), $db));
$pw =$_POST['password'];
$pw1=$_POST['password1'];
$street=mysql_real_escape_string(stripslashes($_POST['street']), $db);
$city=mysql_real_escape_string(stripslashes($_POST['city']), $db);
$zip=mysql_real_escape_string(stripslashes($_POST['zip']),$db);
$phone1=mysql_real_escape_string(stripslashes($_POST['phone1']), $db);
$phone1Type=mysql_real_escape_string(stripslashes($_POST['phone1Type']), $db);
$phone2=mysql_real_escape_string(stripslashes($_POST['phone2']), $db);
$phone2Type=mysql_real_escape_string(stripslashes($_POST['phone2Type']), $db);
$cellprovider=$_POST['cellprovider'] + 0;
$email=mysql_real_escape_string(stripslashes($_POST['email']), $db);
$parentemail1=mysql_real_escape_string(stripslashes($_POST['parentemail1']), $db);
$parentemail2=mysql_real_escape_string(stripslashes($_POST['parentemail2']), $db);
$alert=$_POST['alert'] + 0;
$comments=mysql_real_escape_string(stripslashes($_POST['comments']), $db);


$pwQuery="";
if (($pw == $pw1) AND (strlen($pw)>1) ) {
$cmd = "/usr/bin/htpasswd -ndb $capid $pw | /bin/sed 's/.*://'";
$pwEnc = `$cmd`;
$pwEnc = trim($pwEnc);
$pwQuery = " password='$pwEnc', "; 

updatePWfile();
}




$query="UPDATE directory set name='$name', rank='$rank', $pwQuery street='$street', city='$city', zip='$zip', phone1='$phone1', phone1Type='$phone1Type', phone2='$phone2', phone2Type='$phone2Type', cellprovider='$cellprovider', email='$email', parentemail1='$parentemail1', parentemail2='$parentemail2', alertlist='$alert', comments='$comments' WHERE capid='$capid'";
mysql_query($query, $db);

echo "<p style=\"background-color:aqua;padding:3px;color:black;text-align:center;width:545px;margin-left:10px;margin-bottom:0;font-family:arial;font-weight:bold;\">Information Updated</p>";

	if ($pw != $pw1) {
	echo "<p style=\"background-color:red;padding:3px;color:white;text-align:center;width:530px;margin-left:10px;margin-top:0;font-family:arial;font-weight:bold;\">Password Mismatch -- not changed</p>";
	}


}
#-----------------------------------------------------------------
#
function updatePWfile()
{
global $db,$pw_file;

$data="";
$query="SELECT capid,password from directory where active='1' ORDER BY capid";
$result=mysql_query($query, $db);
	while ($pwdata=mysql_fetch_array($result)) {
	if (strlen($pwdata['password']) > 1) $data .= $pwdata['capid'] . ":" . $pwdata['password'] . "\n";
	}

$data .= "northshore:4sTCys8lB9xxw\n";

file_put_contents($pw_file, $data);


}
#-----------------------------------------------------------------
#
# INITIALIZE PASSWORD DB
#

function initPW()
{
global $db,$pw_file;

$data="";

$query="SELECT capid,password from directory where active='1' ORDER BY capid";
$result=mysql_query($query, $db);
        while ($pwdata=mysql_fetch_array($result)) {

        $capid=trim($pwdata['capid']);
        $cmd = "/usr/bin/htpasswd -ndb $capid $capid | /bin/sed 's/.*://'";
        $pwEnc = `$cmd`;
        $pwEnc = trim($pwEnc);
        $query="UPDATE directory set password='$pwEnc' where capid='$capid'";
        mysql_query($query, $db);
	$data .= $capid . ":" . $pwEnc . "\n";
	}
$data .= "northshore:iuVTAHUpi1/9Y\n";  # pw = N632CP
file_put_contents($pw_file, $data);

}
#-----------------------------------------------------------------
# Process the uploaded Membership file
#

function handleUpload(){
$msg="";
$error=$_FILES['membership']['error'];
	if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['membership']['tmp_name'];
        $membershipFile = $_SERVER['DOCUMENT_ROOT'] . "/directory/admin/xls/" . $_FILES['membership']['name'];
        move_uploaded_file($tmp_name, $membershipFile);
	$membershipAry = readXLSintoAry($membershipFile);

	$count = updateDB($membershipAry);

	updatePWF();

	$msg =  "$count records updated";
	} else { 
	$msg="Upload Failed";
	}
return($msg);

}
#-----------------------------------------------------------------
#
# Read the membership XLS file into an array
#
function readXLSintoAry($membershipFile)
{

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel_IOFactory */
require_once 'excel/Classes/PHPExcel/IOFactory.php';


#echo date('H:i:s') . " Load from Excel2007 file\n";
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
#$objPHPExcel = $objReader->load("Membership.xls");
#$objPHPExcel = PHPExcel_IOFactory::load("Membership.xls");
$objPHPExcel = PHPExcel_IOFactory::load($membershipFile);


$membershipAry = array();

$headerRow = array(
	'A'	=>	'Name',
	'B'	=>	'CAPID',
	'C'	=>	'Grade',
	'D'	=>	'Grade Date',
	'E'	=>	'Gender',
	'F'	=>	'Join Date',
	'G'	=>	'Expiration',
	'H'	=>	'Home Phone',
	'I'	=>	'Cell Phone',
	'J'	=>	'Street Address',
	'R'	=>	'Email Tag',
	'K'	=>	'Email',
	'L'	=>	'City',
	'M'	=>	'FBI Status',
	'N'	=>	'DOB tag',
	'O'	=>	'Date of Birth',
	'P'	=>	'Under 18'
);


#echo date('H:i:s') . " Iterate worksheets\n";
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
#	echo '- ' . $worksheet->getTitle() . "\r\n";

	foreach ($worksheet->getRowIterator() as $row) {
	#	echo '<hr>    - Row number: ' . $row->getRowIndex() . "<br>\r\n";
		$dataRow=array();

		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false); // Loop only non-null cells

		foreach ($cellIterator as $cell) {

			$coordinate=trim($cell->getCoordinate());
			$rowCoord = ltrim($coordinate, "\x41..\x7A") + 0;   # Remove column "letter" index, A-Z,a-z
			$colCoord = rtrim($coordinate, "\x30..\x39");       # Remove numeric row index, 0-9 	
#echo "<pre> ==== $num";
#print_r(get_class_methods($cell));
#echo strlen(serialize($cell));
#echo "</pre>"; 


			if ((!is_null($cell)) AND (strlen($cell->getCalculatedValue()) > 0) ) {
				$cellValue = trim($cell->getCalculatedValue());
				$cv = trim($cell->getValue());
		#		echo  '        - Cell: ' . $rowCoord . ":" . $colCoord . ' - ' . $cellValue .  "<br>\r\n";

				if ($rowCoord==1) {
				$headerRow[$colCoord] = $cellValue;
				} else {
				$dataRow[$headerRow[$colCoord]] = $cellValue;	

				}





			}
		}  # End of cellIterator loop

		if (count($dataRow) > 3) {
		$membershipAry[$rowCoord] = $dataRow;
		}
	}
}


#echo "<pre>";
#print_r($headerRow);
#print_r($membershipAry);

#echo "</pre>";

// Echo memory peak usage
#echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";

// Echo done
#echo date('H:i:s') . " Done writing files.\r\n";


return($membershipAry);

}

# --------------------------------------------------------------------
#
# Update the mysql database
#
function updateDB($membershipAry)
{

global $db, $DEBUG;

mysql_select_db("northshore",$db);

mysql_query("update directory set active=0", $db);

$count=0;
foreach ($membershipAry as $dataAry) {

	if ($DEBUG){
	echo "<pre>";
	print_r($dataAry);
	echo "</pre>";
	}

$type="S";  # S = member is a Senior Member. Type will be set to "C" for cadets.

$phone1=$phone2=$phone2Type="";

$name=trim($dataAry['Name']);
$name=preg_replace("/ \,/", ",", $name);
$capid=$dataAry['CAPID'];
$rank=trim($dataAry['Grade']);
	if (($rank=="CADET") OR (substr($rank, 0,2) == "C/") OR (substr($rank, 0,2) == "c/")) $type="C";
$rankDate=fix_date($dataAry['Grade Date']);
$gender=substr(trim($dataAry['Gender']), 0, 1);
$join=fix_date($dataAry['Join Date']);
$renew=fix_date($dataAry['Expiration']);
if (isset($dataAry['Home Phone'])) $phone1=$dataAry['Home Phone'];
$phone1Type="H";
	if (isset($dataAry['Cell Phone'])){
	$phone2=trim($dataAry['Cell Phone']);
	$phone2Type="C";
	}
if (strlen($phone2)<1) $phone2Type="";
$street=ucwords(strtolower((trim(preg_replace("/Address:/", "", $dataAry['Street Address'])))));
	$street=preg_replace("/ NE/i", " NE", $street);
        $street=preg_replace("/ NW/i", " NW", $street);
        $street=preg_replace("/ SE/i", " SE", $street);
        $street=preg_replace("/ SW/i", " SW", $street);
#$email=trim(preg_replace("/Email:/","",$ary[10]));
$email=trim($dataAry['Email']);
$city=trim($dataAry['City']);
$zip=preg_replace("/.*?([0-9]+)$/", "$1", $city);
$city=ucwords(strtolower(trim(preg_replace("/$zip/", "", $city))));
$city=preg_replace("/ WA$/i", " WA", $city);
	if (strlen($zip) > 5) {
	$zip=substr($zip,0,5) . "-" . substr($zip,5);
	}
$FBI=trim(preg_replace("/FBI STATUS:/i", "", $dataAry['FBI Status']));
$DOBxls=trim(preg_replace("/DATE OF BIRTH:/i", "", $dataAry['Date of Birth']));
$DOBts = mktime(0,0,0,1, ($DOBxls - 1), 1900);		# Excel uses "number of days since Jan. 1, 1900" to store its dates
$DOB = date("Y-m-d", $DOBts);                           # It also treats 1900 as a leap year when it wasn't, thus there is an extra day which must be accounted for in PHP
#$DOB="";

$latlon = getLatLon($street, $city, $zip);
$lat = $latlon['lat'];
$lon = $latlon['lon'];

$query1="name=\"$name\", type=\"$type\", capid=\"$capid\", gender=\"$gender\", rank=\"$rank\", rankDate=\"$rankDate\", joined=\"$join\", active=\"1\", renew=\"$renew\", street=\"$street\", city=\"$city\", zip=\"$zip\", lat=\"$lat\", lon=\"$lon\", phone1=\"$phone1\", phone1Type=\"$phone1Type\", phone2=\"$phone2\", phone2Type=\"$phone2Type\", FBI=\"$FBI\", DOB=\"$DOB\", email=\"$email\"";

$count++;
	if ($DEBUG) {
	echo "$query1 \n\n";
	}


$result=mysql_query("SELECT * from directory where capid='$capid'", $db);
	if (mysql_num_rows($result) < 1) {
	$try = mysql_query("INSERT INTO directory SET $query1", $db);
	} else {
	$query1 = "type=\"$type\", rank=\"$rank\", rankDate=\"$rankDate\", renew=\"$renew\", active=\"1\", street=\"$street\", city=\"$city\", zip=\"$zip\", lat=\"$lat\", lon=\"$lon\", phone1=\"$phone1\", phone1Type=\"$phone1Type\", phone2=\"$phone2\", phone2Type=\"$phone2Type\", FBI=\"$FBI\", DOB=\"$DOB\", email=\"$email\" WHERE capid=\"$capid\"";
	$try = mysql_query("UPDATE directory SET $query1", $db);
	        if ($DEBUG) {
	        echo "$query1 \n\n";
		}


	}

}

mysql_query("update directory set DOB=NULL where DOB='1969-12-31'", $db);
#mysql_query("update directory set active=1 where renew >= NOW()", $db);
mysql_query("update directory set active=0 where renew < NOW()", $db);
 
return($count);


}

# --------------------------------------------------------------------

function fix_date($date)
{

$dary = explode(" ", $date);

$monthAry=array("Jan" => 1 , "Feb" => 2, "Mar" => 3, "Apr" => 4, "May" => 5, "Jun" => 6, "Jul" => 7, "Aug" => 8, "Sep" => 9, "Oct" => 10, "Nov" => 11, "Dec" => 12); 

$month = sprintf("%02d", $monthAry[$dary[1]]);
$day = sprintf("%02d", ($dary[0] + 0));
$year=$twoDyear = $dary[2] + 0;


$sqldate = $year . "-" . $month . "-" . $day; 

return($sqldate);
}

# --------------------------------------------------------------------
#
# Update the password DB and file
#
function updatePWF()
{

global $db;

$passwdFile = "/var/www/northshore/pwf/directory.pwf";

mysql_select_db("northshore",$db);
$result=mysql_query("SELECT capid,password from directory where active=1 AND password is null", $db);
	while ($myrow = mysql_fetch_array($result)) {
	$capid=trim($myrow['capid']);	
		if (strlen($capid) > 1) {
		$entry = preg_replace("/.*?:/", "", trim(`/usr/bin/htpasswd -nb $capid $capid`));
		$query="UPDATE directory SET password='" . $entry . "' WHERE capid = '" . $capid . "'";
		mysql_query($query, $db);
		}
	
	}

$fh=fopen($passwdFile, 'w');
$result=mysql_query("SELECT capid,password from directory where active=1 ORDER BY capid", $db);
        while ($myrow = mysql_fetch_array($result)) {
	$line = $myrow['capid'] . ":" . $myrow['password'] . "\n";
	fwrite($fh, $line);
	}
fwrite($fh, "northshore:iuVTAHUpi1/9Y\n");  # pw = N632CP
fwrite($fh, "445785:T2HiwHdc0yEwg\n");      # Charlie's entry
fclose($fh);


}

# --------------------------------------------------------------------
#
# Determine the latitude and longitude for a street address
#
function getLatLon($street, $city, $zip)
{
global $DEBUG;

$baseURL = "http://maps.googleapis.com/maps/api/geocode/xml?address=";

$location=urlencode("$street $city $zip");
$url = $baseURL . $location . "&sensor=false";
	if ($DEBUG) echo "URL = $url<br>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 GTB7.0");
$xml = curl_exec($ch);
curl_close($ch);
#sleep (1);

$p = xml_parser_create();
xml_parse_into_struct($p, $xml, $vals, $index);
xml_parser_free($p);
$lat = $vals[$index['LAT'][0]]['value'];
$lon = $vals[$index['LNG'][0]]['value'];

$latlon=array('lat' => $lat, 'lon' => $lon);


return($latlon);

}

# --------------------------------------------------------------------


?>


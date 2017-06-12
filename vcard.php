<?php
/**
 * Return the contact information in vcard form
 */
include "/var/www/capnorthshore/pwf/db.php";

if (isset($_GET['id'])) {
$id=$_GET['id'];

$db = new mysqli("localhost",$SQLuser, $SQLpass, "northshore");

	if (is_numeric($id)) {
	doVcard($id, "single");
	} else {
	$whereClause=" WHERE active=2 AND capid=0";
	$FN_header="SQ068-" . $id . ".vcf";
	$header1="Content-Disposition: attachment; filename=$FN_header";
	$header2="Content-Type: text/x-vcard; charset=utf-8; name=$FN_header";
	header($header1);
	header($header2);
		if ($id=="all") $whereClause="WHERE active=1";
		if ($id=="cadets") $whereClause="WHERE active=1 AND type='C'";
		if ($id=="seniors") $whereClause="WHERE active=1 AND type='S'";
	$query = "SELECT capid from directory " . $whereClause;
	$result=$db->query($query);
		while ($myrow=$result->fetch_array(MYSQLI_ASSOC)) {
		$capid=trim($myrow['capid']);
		doVcard($capid, "multi");
		}
	}

}

#---------------------------------------------------------
#
function doVcard($capid, $multi)
{
global $db;

$query="SELECT * from directory WHERE active=1 AND capid='" . $capid . "'";
$result=$db->query($query);

        while ($myrow=$result->fetch_array(MYSQLI_ASSOC)) {
	$N=preg_replace("/, /", ";", trim($myrow['name']));
	$Nary = explode(";", $N);
	$FN = trim($Nary[1], " ,") . " " . trim($Nary[0], " ,");
	$FN_header = preg_replace("/ /", "-", $FN) . ".vcf";
	$TITLE = $myrow['rank'];
	
	
	$TEL1ary = makePhone($myrow, 1);
	$TEL2ary = makePhone($myrow, 2);

#		if (strlen($TEL2ary['3.0']) > 1) $TEL1ary['3.0'] .= "
#" . $TEL2ary['3.0'];
		if (count($TEL2ary) > 1){
                	if (strlen($TEL2ary['2.1']) > 8) $TEL1ary['2.1'] .= "
" . $TEL2ary['2.1'];
		}

	$adrAry = explode(",", $myrow['city']);
	$city = trim($adrAry[0]);
	$state = strtoupper(trim($adrAry[1]));
	$ADR =  $myrow['street'] . ";" . $city . ";" . $state . ";" . $myrow['zip'] . ";";
	$EMAIL = $myrow['email'];
	$lon = $myrow['lon'];
	$lat = $myrow['lat'];
	$UID = "46068-" . $capid;
	$REV = preg_replace("/-/", "", date(DATE_ATOM));	
	$REV = preg_replace("/:/", "", $REV);
	}

	if ($multi == "single"){
	$header1="Content-Disposition: attachment; filename=$FN_header";
	$header2="Content-Type: text/x-vcard; charset=utf-8; name=$FN_header";
	header($header1);
	header($header2);
	}

if ($multi == "xmulti") {

$vcard = "BEGIN:VCARD
VERSION:3.0
N:$N
FN:$FN
ORG:CAP;
TITLE:$TITLE
EMAIL;type=INTERNET;type=HOME;type=pref:$EMAIL
" . $TEL1ary['3.0'] . "
ADR;TYPE=HOME:;;$ADR
GEO;TYPE=work;geo:$lat,$lon
UID:$UID
REV:$REV
END:VCARD
";

} else {
	
$vcard = "BEGIN:VCARD
VERSION:2.1
N:$N;;;
FN:$FN
" . $TEL1ary['2.1'] . "
EMAIL;PREF:$EMAIL
ADR;PREF;HOME:;;$ADR
ADR;HOME:;;$ADR
ORG:CAP
TITLE:$TITLE
GEO:$lat,$lon
UID:$UID
REV:$REV
END:VCARD
";
	
}


echo $vcard;

}


# ========================================================
# makePhone
#    Take the $myrow array, pull-out the phone information
#    and return a TEL line for vcard
#

function makePhone($sqlAry, $which)
{
$TEL=array();

	if (!(($which > 1) AND ($sqlAry['phone1'] == $sqlAry['phone2']))) {

	$type="";
	$numVar = "phone" . $which;
	$typeVar = $numVar . "Type";

	$PhNumber = $sqlAry[$numVar];
	$PhType   = trim($sqlAry[$typeVar]);

		if ($PhType == "H") $type="HOME";
		if ($PhType == "W") $type="WORK";
                if ($PhType == "C") $type="CELL";


	$TEL['3.0'] = "TEL;TYPE=" . $type. ",VOICE:" . $PhNumber;
	$TEL['2.1'] = "TEL;" . $type. ":" . $PhNumber;
	}

return($TEL);
}

# ========================================================


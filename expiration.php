#!/usr/bin/php
<?php

include "/var/www/capnorthshore/pwf/db.php";

$db = new mysqli("localhost",$SQLuser, $SQLpass, "northshore");

$q="select email, name, rank, type, DATEDIFF(renew, NOW()) as remaining, renew, date_format(renew, '%e-%M') as dspdate from directory where active=1  AND ((renew - INTERVAL 30 DAY) <= NOW())";

$r1 = $db->query($q);

	while ($myrow=$r1->fetch_array(MYSQLI_ASSOC)){
	$to = $myrow['email'];
	$fullname = $myrow['name'];
	$expire = $myrow['renew'];
	$type   = $myrow['type'];
	$rank   = trim($myrow['rank']);
		if ($rank == "CADET") $rank = "Cadet"; 
	$remaining = $myrow['remaining'];
	$ln = trim(preg_replace("/(.*?),.*/", "$1", $fullname));
	$rankname = $rank . " " . $ln;
	$cc = '"Mike Murray" <commander@capnorthshore.org>, "Will Nickelson" <william.nickelson@wawg.cap.gov>';
		if ($type == "CX") $cc .= ', "Ryan Bauman" <ryan.bauman01@gmail.com >';

	$to = "\"$rankname\" <" . $to . ">";

	$subject = "[CAP] Your CAP Membership expires in $remaining days";
	$headers = "From: \"Northshore Composite Squadron\" <info@capnorthshore.org>\r\nCC: $cc\r\nReply-to: $cc\r\n"; 

	$message = $rankname . "\n\n(This is an automated reminder service from the Northshore Composite Squadron web site.)\n\nYour CAP Membership expires on " . $myrow['dspdate'] . ".\nIf you have not already done so, we hope you will renew your membership and continue working with our squadron.\n\nYou may renew online through eServices -- https://www.capnhq.gov/ \n\n\n Thank you,\n\nMike Murray, LtCol  CAP\nCommander\nNorthshore Composite Squadron\nhttp://www.capnorthsore.org\n";

		if ($remaining >= 0) {

//		$result = mail($to, $subject, $message, $headers);
echo $message;
		}

	}

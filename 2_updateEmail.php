#!/usr/bin/php
<?php

include "/var/www/capnorthshore/pwf/db.php";

$db = new mysqli("localhost",$SQLuser, $SQLpass, "northshore");

$cadetList="/var/www/capnorthshore/lists/cadets.lst";
$seniorList="/var/www/capnorthshore/lists/seniors.lst";
$members="/var/www/capnorthshore/lists/members.lst";
$alert="/var/www/capnorthshore/lists/alert.lst";
$parentsList="/var/www/capnorthshore/lists/parents.lst";

$seniorsAccept="/var/www/capnorthshore/lists/seniorsAccept.lst";
$cadetsAccept="/var/www/capnorthshore/lists/cadetsAccept.lst";
$allAccept="/var/www/capnorthshore/lists/allAccept.lst";

# -----------------------------------------------------------
# Write the Cadet mailing list

$fh=fopen($cadetList, "w");
$f1=fopen($cadetsAccept, "w");

$cAccept=$sAccept=$aAccept =  "accept_these_nonmembers = [";

$query = "select name,email from directory where active='1' and type='C' ORDER BY name";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }
   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if (strlen($myrow['email']) > 3) {
      $entry = '"' . trim($myrow['name']) . '" <' . trim($myrow['email']) . '>';
      fwrite($fh, $entry . "\n");
      $cAccept .=  "'" . trim($myrow['email']) . "', ";
      }
   }
fwrite($fh, "\"Charles Jackson\" <nightbeacons@gmail.com>\n");
fwrite($fh, "\"Curt Powers\" <curt_powers@hotmail.com>\n");
fwrite($fh, "\"Curt Powers\" <Major.Powers@live.com>\n");
fwrite($fh, "\"Jeramee Scherer\" <j.scherer3@gmail.com>\n");
$cAccept .= "'northshorediningout@gmail.com'";
fclose($fh);
$cAccept=trim($cAccept, ', ') . "]\n";
fwrite($f1, $cAccept);
fclose($f1);

# -----------------------------------------------------------
# Write the Parents mailing list

$fh=fopen($parentsList, "w");
#$f1=fopen($parentsAccept, "w");

$cAccept=$sAccept=$pAccept = "accept_these_nonmembers = [";

$query = "select name,parentemail1,parentemail2 from directory where active='1' and type='C' ORDER BY name";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if (strlen($myrow['parentemail1']) > 3) {
      $entry = '"Parents of ' . trim($myrow['name']) . '" <' . trim($myrow['parentemail1']) . '>';
      fwrite($fh, $entry . "\n");
      $pAccept .=  "'" . trim($myrow['parentemail1']) . "', ";
      }
      if ((strlen($myrow['parentemail2']) > 3) AND ($myrow['parentemail1'] != $myrow['parentemail2'])) {
      $entry = '"Parents of ' . trim($myrow['name']) . '" <' . trim($myrow['parentemail2']) . '>';
      fwrite($fh, $entry . "\n");
      $pAccept .=  "'" . trim($myrow['parentemail2']) . "', ";
      }

   }
fwrite($fh, "\"Curt Powers\" <Major.Powers@live.com>\n");
fwrite($fh, "\"Charles Jackson\" <nightbeacons@gmail.com>\n");
fwrite($fh, "\"Jeramee Scherer\" <j.scherer3@gmail.com>\n");
fwrite($fh, "\"Troy Hacking\" <troy.hacking@gmail.com>\n");
fwrite($fh, "\"Northshore Dining Out\" <northshorediningout@gmail.com>\n");

fclose($fh);
$cAccept=trim($pAccept, ', ') . "]\n";
#fwrite($f1, $pAccept);
#fclose($f1);

# -----------------------------------------------------------
# Write the Seniors mailing list

$fh=fopen($seniorList, "w");
$f1=fopen($seniorsAccept, "w");
$query="select name, email from directory where active='1' and type='S' ORDER BY name";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if (strlen($myrow['email']) > 3) {
      $entry = '"' . trim($myrow['name']) . '" <' . trim($myrow['email']) . '>';
      fwrite($fh, $entry . "\n");
      $sAccept .=  "'" . trim($myrow['email']) . "', ";
      }
   }
#fwrite($fh, "\"Stephanie Washburn\" <stephanie.washburn@email.wsu.edu>\n");
$sAccept .= "'northshorediningout@gmail.com'";

fclose($fh);
$sAccept=trim($sAccept, ', ') . "]\n";
fwrite($f1, $sAccept);
fclose($f1);


# -----------------------------------------------------------
# Write the Members mailing list

$aAccept = "accept_these_nonmembers = [";

$fh=fopen($members, "w");
$f1=fopen($allAccept, "w");
$query="select name, email from directory where active='1' ORDER BY name";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if (strlen($myrow['email']) > 3) {
      $entry = '"' . trim($myrow['name']) . '" <' . trim($myrow['email']) . '>';
      fwrite($fh, $entry . "\n");
      $aAccept .=  "'" . trim($myrow['email']) . "', ";
      }
   }
$aAccept .=  "'northshorediningout@gmail.com'";
fclose($fh);
$aAccept=trim($aAccept, ', ') . "]\n";
fwrite($f1, $aAccept);
fclose($f1);


# -----------------------------------------------------------
# Write the Alert mailing list


$phones=array();
$fh=fopen($alert, "w");
$query="select directory.name, phone1, suffix from directory left join cellprovider on directory.cellprovider=cellprovider.id where (phone1Type='C' AND alertlist='1' AND active='1') ORDER BY name";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if ((strlen($myrow['phone1']) > 3) AND (strlen($myrow['suffix'])>1)) {
      $email=trim(preg_replace("/\D+/", "", $myrow['phone1'])) . "@" . trim($myrow['suffix']);
      # $entry = '"' . trim($myrow['name']) . '" <' . $email . '>';
      $entry=$email;
      fwrite($fh, $entry . "\n");
      }
   }
$query="select directory.name, phone2, suffix from directory left join cellprovider on directory.cellprovider=cellprovider.id where (phone2Type='C' AND alertlist='1' AND active='1') ORDER BY name";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if ((strlen($myrow['phone2']) > 3) AND (strlen($myrow['suffix'])>1)) {
      $email=trim(preg_replace("/\D+/", "", $myrow['phone2'])) . "@" . trim($myrow['suffix']);
      # $entry = '"' . trim($myrow['name']) . '" <' . $email . '>';
      $entry=$email;
      fwrite($fh, $entry . "\n");
      }
   }


fclose($fh);


//$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/seniors.lst sq68-seniors`;
//$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/cadets.lst sq68-cadets`;
//$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/parents.lst sq68-parents`;
#$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/alert.lst sq68-alert`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/cadetsAccept.lst sq68-seniors`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/seniorsAccept.lst sq68-cadets`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/seniorsAccept.lst sq68-parents`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/allAccept.lst sq68-seniors-guest`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/allAccept.lst sq68-cadets-guest`;


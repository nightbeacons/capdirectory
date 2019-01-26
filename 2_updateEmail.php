#!/usr/bin/php
<?php
/**
 * Root cron to pull name+email info from MySQL
 * and update mailman mailing lists.
 */

include "/var/www/capnorthshore/pwf/db.php";
date_default_timezone_set('America/Los_Angeles');

$db = new mysqli("localhost",$SQLuser1, $SQLpass1, "cw_068");

$cAccept=$sAccept=$aAccept =  "accept_these_nonmembers = [";

$cadetList="/var/www/capnorthshore/lists/cadets.lst";
$seniorList="/var/www/capnorthshore/lists/seniors.lst";
$alert="/var/www/capnorthshore/lists/xalert.lst";
$parentsList="/var/www/capnorthshore/lists/parents.lst";

$seniorsAccept="/var/www/capnorthshore/lists/seniorsAccept.lst";
$cadetsAccept="/var/www/capnorthshore/lists/cadetsAccept.lst";
$allAccept="/var/www/capnorthshore/lists/allAccept.lst";

$cadetBuffer=$seniorBuffer = "";
$cadetAcceptBuffer=$seniorAcceptBuffer="accept_these_nonmembers = [";

        $query="SELECT CONCAT (trim( ' ' from  Member.NameLast), ', ',  trim( ' ' from  Member.NameFirst)) AS name,
                   CONCAT (trim( ' ' from  Member.NameFirst), ' ',  trim( ' ' from  Member.NameLast)) AS name1,
                   LEFT(Member.Type, 1) AS type,
                   trim( ' ' from Member.CAPID) as capid,
                   Member.Rank,
                   subquery1.email
                    FROM Member
                  LEFT JOIN (SELECT MbrContact.Contact AS email, MbrContact.CAPID as hold1
                             from MbrContact where MbrContact.Type = 'EMAIL' AND MbrContact.Priority='PRIMARY')
                             as subquery1 ON  Member.CAPID=hold1
                  WHERE (Member.MbrStatus = 'ACTIVE' AND Member.Expiration > NOW())";

        if ( ($result = $db->query($query))===false ) {
          printf("Invalid query: %s\nWhole query: %s\n", $db->error, $query);
          exit();
        }
        while ($myrow=$result->fetch_array(MYSQLI_ASSOC)) {
           $capid=trim($myrow['capid']);

           $type=$myrow['type'];
//           $name = $myrow['Rank'] . " " . trim($myrow['name1']);
           $name = trim($myrow['name']);
           $email = trim($myrow['email']);

           if ($type=='C'){
               $cadetBuffer .= '"' . $name . '" <' . $email . ">\n";
               $cadetAcceptBuffer .= "'" . $email . "', ";
           }

           if ($type=='S'){
               $seniorBuffer .= '"' . $name . '" <' . $email . ">\n";
               $seniorAcceptBuffer .= "'" . $email . "', ";
           }
        }

$cadetAcceptBuffer .= "'northshorediningout@gmail.com'";
$seniorAcceptBuffer .= "'northshorediningout@gmail.com'";

$cadetAcceptBuffer = trim($cadetAcceptBuffer, ', ') . "]\n";
$seniorAcceptBuffer = trim($seniorAcceptBuffer, ', ') . "]\n";

$fh = fopen($cadetList, 'w');
fwrite($fh, $cadetBuffer);
fclose($fh);

$fh = fopen($seniorList, 'w');
fwrite($fh, $seniorBuffer);
fclose($fh);

$fh = fopen($cadetsAccept, 'w');
fwrite($fh, $cadetAcceptBuffer);
fclose($fh);

$fh = fopen($seniorsAccept, 'w');
fwrite($fh, $seniorAcceptBuffer);
fclose($fh);

# -----------------------------------------------------------
# Write the Parents mailing list

$fh=fopen($parentsList, "w");
$fh1 = fopen($cadetList, 'a');


$cAccept=$sAccept=$pAccept = "accept_these_nonmembers = [";

$query = "SELECT DISTINCT Contact AS parentemail1, 
          Member.NameLast AS name
          from MbrContact 
          LEFT JOIN Member ON MbrContact.CAPID=Member.CAPID
          where MbrContact.Type='CADET PARENT EMAIL' 
          AND Member.MbrStatus = 'ACTIVE' AND Member.Expiration > NOW()
          ORDER BY Contact";

   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      if (strlen($myrow['parentemail1']) > 3) {
      $entry = '"Parents of Cadet ' . trim($myrow['name']) . '" <' . trim($myrow['parentemail1']) . '>';
      fwrite($fh, $entry . "\n");
      $pAccept .=  "'" . trim($myrow['parentemail1']) . "', ";
      }

   }

// Add Command Steff
$query = "select DutyPosition.Duty,  
       CONCAT (Member.Rank, ' ' ,trim( ' ' from  Member.NameFirst), ' ',  trim( ' ' from  Member.NameLast)) AS name1, 
       CONCAT (trim( ' ' from  Member.NameLast), ', ',  trim( ' ' from  Member.NameFirst)) AS name,
       subquery1.email
FROM DutyPosition 
       LEFT JOIN Member on DutyPosition.CAPID=Member.CAPID
       LEFT JOIN (SELECT MbrContact.Contact AS email, MbrContact.CAPID as hold1
                  from MbrContact where MbrContact.Type = 'EMAIL' AND MbrContact.Priority='PRIMARY')
                  as subquery1 ON  DutyPosition.CAPID=hold1
WHERE DutyPosition.Duty regexp 'Commander' AND DutyPosition.Asst=0";
   if ( ($result = $db->query($query))===false )
   {
     printf("Invalid query: %s\nWhole query: %s\n", $db->error, $SQL);
     exit();
   }

   while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) {
      $entry = '"' . trim($myrow['name']) . '" <' . trim($myrow['email']) . '>';
      fwrite($fh, $entry . "\n");
      fwrite($fh1, $entry . "\n");
   }

fwrite($fh, "\"Jackson, Charles\" <nightbeacons@gmail.com>\n");
fwrite($fh1, "\"Jackson, Charles\" <nightbeacons@gmail.com>\n");


fclose($fh);
fclose($fh1);


# -----------------------------------------------------------
# Write the Alert mailing list

// Deactivate this code
if (FALSE) {
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
}


$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/seniors.lst sq68-seniors`;
$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/cadets.lst sq68-cadets`;
$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/parents.lst sq68-parents`;
#$tmp=`/usr/lib/mailman/bin/sync_members -w=no -g=no -d=no -a=no -f /var/www/capnorthshore/lists/alert.lst sq68-alert`;
$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/cadetsAccept.lst sq68-seniors`;
$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/seniorsAccept.lst sq68-cadets`;
$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/seniorsAccept.lst sq68-parents`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/allAccept.lst sq68-seniors-guest`;
//$tmp=`/usr/lib/mailman/bin/config_list -i /var/www/capnorthshore/lists/allAccept.lst sq68-cadets-guest`;


<?php
$ch = curl_init("http://www.livingston.org/Page/18817");
ob_start(); 
curl_exec($ch); 
curl_close($ch); 
$retrievedhtml = ob_get_contents(); 
ob_end_clean(); 
 
// $bodyandend = stristr($retrievedhtml,'<html>'); 
$bodyandend = stristr($retrievedhtml,'<div id="sw-content-layout-wrapper" class="ui-sp ui-print">'); 
$positionendstartbodytag = strpos($bodyandend,">") + 1; 
// $positionendendbodytag=strpos($bodyandend,'</html>'); 
$positionendendbodytag=strpos($bodyandend,'<div class="swlastmodifeddate">'); 
$grabbedbody=substr($bodyandend, $positionendstartbodytag, $positionendendbodytag); 
$grabbedbody=substr_replace($grabbedbody ,"",-37);
 
if ($grabbedbody != "") {
    echo $grabbedbody; 
}
else
{
    echo "More Information to Come...";
}
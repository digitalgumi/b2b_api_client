<?php
/************************************************************************************************************************/
/*              B2B CSV API kliens példa kód a Digital Gumi Ügyviteli Rendszer B2B portáljához                          */
/*    B2B CSV API  client example code to connect and process data from the Didigal Tyre ERP System B2B Portal          */
/*                                                                                                                      */
/*                                                                                                                      */
/*                                              https://gumiugyvitel.hu                                                 */
/*                                                                                                                      */
/************************************************************************************************************************/

define("URL", "https://xxxxxxxxxxxxxxxxx/xxxxxxxxx");   //B2B API URL 
define("IGNORE_SSL", true);                     //logikai kapcsoló, true vagy false idézőjel nélkül, SSL tanusitvany figyelmen kivul hagyása, nem ajánlott a használata     logical value, true or false without quote, SSL certificate ignoreance, not advised to use it
define("USERNAME", "");           //B2B felhasználónév  B2B username
define("PASSWORD", "");                   //B2B jelszó  B2B password

error_reporting(E_ERROR);

if(!defined("URL") || URL == ""){ die("No B2B API URL set in line 11!"); }
if(!defined("USERNAME") || USERNAME == ""){ die("No B2B API username set in line 13!"); }
if(!defined("PASSWORD") || PASSWORD == ""){ die("No B2B API password set in line 14!"); }

$curl = curl_init(URL);
curl_setopt($curl, CURLOPT_URL, URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$headers = array( 'Authorization: Basic '. base64_encode(USERNAME.":".PASSWORD) );

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

if(defined("IGNORE_SSL") && IGNORE_SSL === true){
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
}

$resp = curl_exec($curl);

if($resp === false){
    echo 'Curl error: ' . curl_error($curl);
    die();
}

curl_close($curl);

$csv = preg_split('/\n|\r\n?/', $resp);

$result = array();
$header = array();

for($i=0; $i<sizeof($csv); $i++){
    $row = explode(';', $csv[$i]);

    if($i == 0){
        for($ii = 0; $ii<sizeof($row); $ii++){
            $header = $row;
        }
    }else{
        $result_row = array();

        for($ii = 0; $ii<sizeof($row); $ii++){
    
           $result_row[$header[$ii]] = $row[$ii];
        }

        $result []= $result_row;
    }

}

//innentol a $result egy ket dimenzion tömb, aminek a sorai asszociatívan tartalmazzák az adatokat
//$result variable is a two dimension array, the rows contain de data associatively

//print_r($result);
?>

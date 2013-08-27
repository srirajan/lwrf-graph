<?
/*


Mysql table
CREATE TABLE `energy` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `watts` int(11) NOT NULL,
  `diff_watts` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
/* DB creds*/
require "/home/sri/sriramrajan.com/ssl/sri/conf.php";

$url = "https://lightwaverfhost.co.uk/mobile/meter_free_load.php?email=$LWRF_EMAIL&mac=$LWRF_MAC";

$curl_options = array (CURLOPT_RETURNTRANSFER => true, // return web page
    CURLOPT_HEADER => false, // don't return headers
    CURLOPT_ENCODING => "", // handle compressed
    CURLOPT_USERAGENT => "Curl", // who am i
    CURLOPT_CONNECTTIMEOUT => 30, // timeout on connect
    CURLOPT_TIMEOUT => 120, // timeout on response
    CURLOPT_MAXREDIRS => 3 ); // stop after 10 redirects


$ch = curl_init ( $url );
curl_setopt_array ( $ch, $curl_options );
$content = curl_exec ( $ch );
$err = curl_errno ( $ch );
$errmsg = curl_error ( $ch );
$header = curl_getinfo ( $ch );
$httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
curl_close ( $ch );

#print $content;
list($cur_kw,$max_24,$tot_today,$tot_yest) = explode(",",$content);

#print $tot_today;

$query_ins = "INSERT into $mysql_table_energy (`time`,`watts`) values(NOW(),$tot_today)";
$result_ins = mysql_query($query_ins) or die (mysql_error());

?>

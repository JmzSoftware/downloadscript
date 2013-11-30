<?
/*
 * Androtransfer.com JSON API
 * Script created by Jimmy Rousseau
 * Copyright (C) 2012   Jimmy Rousseau (LifeOfCoding)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'config.php';

if(isset($_GET['md5'])) {
$md5 = $_GET['md5'];
$md5 = substr($md5, 6);
$query = sprintf("SELECT * FROM md5sums WHERE filename='$md5'",
mysql_real_escape_string($file));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
echo $row['md5'];
die();
}


if(isset($_GET['first'])) {
$row_array = array();
$return_arr = array();
$json = array();
if(is_dir($baseDir)){

    if($dh = opendir($baseDir)){
        while(($file = readdir($dh)) != false){

            if($file == "." or $file == ".." || $file == ".htaccess" || $file == ".users"){
            } else {
                $row_array['dev'] = $file;
                array_push($return_arr,$row_array);
            }
        }
        sort($return_arr);
        $json['dev_info'] = $return_arr;
    }
    echo json_encode($json);
}
exit;
}

function extstr3($content,$start,$end){
  if($content && $start && $end) {
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
  }
}

$dev = $_GET['dev'];
$dev = urldecode($dev);

$device = $_GET['device'];
$device = urldecode($device);

$current_addr =  $_SERVER['HTTP_HOST'];
$current_addr = "http://" .$current_addr. "" .$_SERVER['REQUEST_URI'];

if($dev == ""){
echo 'Error (1): Developer param is blank';
die;
}

$return_arr = array();

if($device == ""){

$url = "http://androidfiles.org/?developer=".$dev;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;

$res = strstr($gurl, "<div id='sidebar'>");


    if ($res)
    {

      while ($i <= 50)

      {

        $res = strstr ($res, "<li class=''>");
   
	$device = extstr3($res,"folder=","'>");

        $res = strstr ($res, '<li>');


				if ($device == '')
				{
				  if($i == 1){
				  $url = "http://androidfiles.org/?developer=".$dev;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;

$res = strstr($gurl, "<div style='float: left; margin-left: 10px; width: 668px'>");


    if ($res)
    {

      while ($i <= 50)

      {

        $res = strstr ($res, "<tr class='download'>");
   
	$filename = extstr3($res,".zip'>","</a>");
	$url = extstr3($res,"<a style='display: block' href='","'>");
	$url = 'http://androidfiles.org/'.$url;
	$md5 = extstr3($res,"<span style='font-family: Courier'>","</span>");

        $dateres = strstr ($res, "<span style='font-family: Courier'>");
	$date = extstr3($dateres,"<td>","</td>");
        $dateres = strstr ($res, "<td style='font-size: 24px; text-align: right;'>");

        $sizeres = strstr ($res, "".$date."</td>");
	$filesize = extstr3($sizeres,"<td>","</td>");
        $sizeres = strstr ($res, "<td style='font-size: 24px; text-align: right;'>");

	$rep = array("(",")");

	$filesize = trim(str_replace($rep, " ", $filesize));

	$dl_count = extstr3($res,"<td style='font-size: 24px; text-align: right;'>","</td>");

	$dl_count = trim(str_replace($rep, " ", $dl_count));

        $res = strstr ($res, '</tr>');



				if ($filename == '')
				{
				  if($i == 1){
						echo 'Error (2): Results not found';
						die;
				  }
				}
				else
				{
						++$ts;

        $row_array['filename'] = $filename;
        $row_array['url'] = $url;

        array_push($return_arr,$row_array);
        $json['dev_info'] = $return_arr;

				}
				++$i;
		}
}
						//echo 'Error (3): Results not found';
						//die;
				  }
				}
				else
				{
						++$ts;

        $row_array['device'] = $device;
        $row_array['url'] = $current_addr.'&device='.$device.'';

        array_push($return_arr,$row_array);
        $json['dev_info'] = $return_arr;

				}
				++$i;
		}
}

}else{

$url = "http://androidfiles.org/?developer=".$dev."&folder=".$device;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"$url");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, "10");
curl_setopt($ch, CURLOPT_TIMEOUT, "10");
$gurl = curl_exec($ch);
curl_close($ch);

  $i = 1;

$res = strstr($gurl, "<div style='float: left; margin-left: 10px; width: 668px'>");


    if ($res)
    {

      while ($i <= 50)

      {

        $res = strstr ($res, "<tr class='download'>");
   
	$filename = extstr3($res,".zip'>","</a>");
	$url = extstr3($res,"<a style='display: block' href='","'>");
	$url = 'http://androidfiles.org/'.$url;
	$md5 = extstr3($res,"<span style='font-family: Courier'>","</span>");

        $dateres = strstr ($res, "<span style='font-family: Courier'>");
	$date = extstr3($dateres,"<td>","</td>");
        $dateres = strstr ($res, "<td style='font-size: 24px; text-align: right;'>");

        $sizeres = strstr ($res, "".$date."</td>");
	$filesize = extstr3($sizeres,"<td>","</td>");
        $sizeres = strstr ($res, "<td style='font-size: 24px; text-align: right;'>");

	$rep = array("(",")");

	$filesize = trim(str_replace($rep, " ", $filesize));

	$dl_count = extstr3($res,"<td style='font-size: 24px; text-align: right;'>","</td>");

	$dl_count = trim(str_replace($rep, " ", $dl_count));

        $res = strstr ($res, '</tr>');



				if ($filename == '')
				{
				  if($i == 1){
						echo 'Error (2): Results not found';
						die;
				  }
				}
				else
				{
						++$ts;

        $row_array['filename'] = $filename;
        $row_array['url'] = $url;

        array_push($return_arr,$row_array);
        $json['dev_info'] = $return_arr;

				}
				++$i;
		}
}
}
echo json_encode($json);
?>

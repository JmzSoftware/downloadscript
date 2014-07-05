<?php
/*
 * Copyright (C) 2014   Jmz Software LLC
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

function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

foreach(array_keys($_GET) as $key)
{
    $clean[$key] = mysql_real_escape_string($_GET[$key]);	
}

if(isset($clean['basedir'])) {
echo $baseDir;
}

if(isset($clean['update'])) {
$romname = $clean['update'];
$json = array();
mysql_select_db("romupdate");
$q=mysql_query("SELECT * FROM romupdate WHERE romname ='$romname' ORDER BY romver DESC");
$dataArray = array();
while($r = mysql_fetch_array($q, MYSQL_ASSOC))
{
    $dataArray[] = $r;
    $json['dev_info'] = $dataArray;
}

print json_encode($json);

mysql_close();
}
if(isset($_GET['md5'])) {
$md5 = $clean['md5'];
$query = sprintf("SELECT * FROM md5sums WHERE filename='$md5'",
mysql_real_escape_string($file));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if (!$row['md5']) {
                $md5sum = md5_file("files/" + $md5);
                $sqlread = mysql_query("INSERT INTO md5sums (filename,md5) VALUES(\"$md5\",\"$md5sum\")") or die(mysql_error());
                echo $md5;
        }else{
                echo $row['md5'];
}
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

if(isset($_GET['device'])) {
$device = $clean['device'];
$dev = $clean['dev'];
if(is_dir($baseDir . "/" . $dev . "/" . $device)){
$row_array = array();
$return_arr = array();
$json = array();

if ($handle = opendir($baseDir . "/" . $dev . "/" . $device)) {
    while (false !== ($file = readdir($handle))) {
        if($file == "." or $file == ".." || $file == ".htaccess" || $file == ".users"){
        } else {
                $row_array['filename'] = $file;
                if(is_file($baseDir . "/" . $dev . "/" . "$device" . "/" . $file)) {
                	$filesize = filesize($baseDir . "/" . $dev . "/" . "$device" . "/" . $file);
                	$row_array['filesize'] = formatBytes($filesize);
                	$path = "$dev" .  "/" . "$device" . "/" . "$file";
                	$query = sprintf("SELECT * FROM md5sums WHERE filename= '$path'",
                	mysql_real_escape_string($downloads));
                	$result = mysql_query($query) or die(mysql_error());
                	$row = mysql_fetch_array($result);
                	$row_array['downloads'] = $row['downloads'];
                }
                array_push($return_arr,$row_array);
        }
    }
    sort($return_arr);
    $json['dev_info'] = $return_arr;
    closedir($handle);
}
echo json_encode($json);
}
exit;
}


if(isset($_GET['dev'])) {
$dev = $clean['dev'];
if(is_dir($baseDir . "/" . $dev)){
$row_array = array();
$return_arr = array();
$json = array();


if ($handle = opendir($baseDir . "/" . $dev)) {
    while (false !== ($file = readdir($handle))) {
	if($file == "." or $file == ".." || $file == ".htaccess" || $file == ".users"){
	} else {
		$row_array['device'] = $file;
                if(is_file($baseDir . "/" . "$dev" . "/" . $file)) {
                $filesize = filesize($baseDir . "/" . $dev . "/" . $file);
                $row_array['filesize'] = formatBytes($filesize);
                $path = "$dev" . "$device" . "/" . "$file";
                $query = sprintf("SELECT * FROM md5sums WHERE filename= '$path'",
                mysql_real_escape_string($downloads));
                $result = mysql_query($query) or die(mysql_error());
                $row = mysql_fetch_array($result);
                $row_array['downloads'] = $row['downloads'];
                }
                array_push($return_arr,$row_array);
	}
    }
    sort($return_arr);
    $json['dev_info'] = $return_arr;
    closedir($handle);
}
echo json_encode($json);
}
exit;
}

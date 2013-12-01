<?php
require_once 'config.php';

if(isset($_GET['basedir'])) {
echo $baseDir;
}

if(isset($_GET['md5'])) {
$md5 = $_GET['md5'];
$md5stripped = substr($md5, 6);
$query = sprintf("SELECT * FROM md5sums WHERE filename='$md5stripped'",
mysql_real_escape_string($file));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
if (!$row['md5']) {
                $md5 = md5_file($md5);
                $sqlread = mysql_query("INSERT INTO md5sums (filename,md5) VALUES(\"$md5stripped\",\"$md5\")") or die(mysql_error());
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
$device = $_GET['device'];
$dev = $_GET['dev'];
if(is_dir($baseDir . "/" . $dev . "/" . $device)){
$row_array = array();
$return_arr = array();
$json = array();


if ($handle = opendir($baseDir . "/" . $dev . "/" . $device)) {
    while (false !== ($file = readdir($handle))) {
        if($file == "." or $file == ".." || $file == ".htaccess" || $file == ".users"){
        } else {
                $row_array['filename'] = $file;
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
$dev = $_GET['dev'];
if(is_dir($baseDir . "/" . $dev)){
$row_array = array();
$return_arr = array();
$json = array();


if ($handle = opendir($baseDir . "/" . $dev)) {
    while (false !== ($file = readdir($handle))) {
	if($file == "." or $file == ".." || $file == ".htaccess" || $file == ".users"){
	} else {
		$row_array['device'] = $file;
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

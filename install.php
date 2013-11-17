<!DOCTYPE html>
<head>
  <title>Install File Lister</title>
</head>
<body>
  <p>Please input MySQL info and continue</p>
  <form action="install.php" method="post">
    Username: <input type="text" name="user" value="root">
    <br>Password: <input type="password" name="password">
    <br>Host: <input type="text" name="host" value="localhost">
    <br>Database Name <input type="text" name="database">
    <br><input type="checkbox" name="yourls" value="ON">Use YOURLS?
    <br>Yourls API location: <input type="text" name="yourlsapi">
    <br>Yourls Username: <input type="text" name="yourlsuser">
    <br>Yourls Password: <input type="text" name="yourlspass">
    <br>Adsense Pub ID: <input type="text" name="adsense">
    <br>Site Title: <input type="text" name="title">
    <br>Domain without www: <input type="text" name="domain">
    <br>Twitter Username: <input type="text" name="twit">
    <br><input type="submit" value="Start Install!">
  </form>
</body>
</html>

<?php
$domain = $_POST['domain'];
$twitter = $_POST['twit'];
if(isset($_POST['yourls'])) {
$yourls = "true";
} else {
$yourls = "false";
}
$yours_api = $_POST['yourlsapi'];
$yours_user = $_POST['yourlsuser'];
$yours_pass = $_POST['yourlspass'];
$adsense = $_POST['adsense'];
$sitename = $_POST['title'];
$hostname = $_POST['host'];
$username = $_POST['user'];
$password = $_POST['password'];
$database = $_POST['database'];

if ($password == "")
{
  echo "<br>Password must not be empty!";
}

if ($username != "" && $hostname != "" && $password != "") {
$con = mysql_connect($hostname,$username,$password);
if (!$con)
{
  die('Could not connect: ' . mysql_error());
}


if (mysql_query("CREATE DATABASE IF NOT EXISTS " . $database, $con))
{
  echo "<br>Database was created as " . $database;
} else {
  echo "<br>Error creating database: " . mysql_error();
}

mysql_select_db($database, $con);

if (mysql_query("CREATE TABLE downloadkey
(
uniqueid varchar(500) NOT NULL default '',
timestamp varchar(100) NOT NULL default '',
filename varchar(255) NOT NULL default ''
)", $con))
{
      echo "<br> Created table downloadkey";
    } else {
      echo "<br> Error creating table downloadkey. " . mysql_error();
}

if (mysql_query("CREATE TABLE md5sums
(
filename varchar(255) NOT NULL default '',
md5 varchar(255) NOT NULL default '',
`downloads` int(11) NOT NULL,
UNIQUE KEY `filename` (`filename`)
)", $con))
{
    echo "<br> Created table md5sums";
} else {
    echo "<br> Error creating table md5sums " . mysql_error();
}

mysql_close($con);

echo "<br>WARNING YOU MUST REMOVE THIS FILE OR SUFFER THE CONSEQUINCES!!!";

$path_to_file = 'default.config.php';
$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("databasename", $database,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("mysqlusername", $username,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("mysqlpassword", $password,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("twitterusername", $twitter,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("false", $yourls,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("yourlsurl", $yourls_api,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("yourlsuser", $yourls_user,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("yourlspass", $yourls_pass,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("adsenseid", $adsense,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("titleofsite", $sitename,$file_contents);
file_put_contents($path_to_file,$file_contents);

$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("domain.com", $domain,$file_contents);
file_put_contents($path_to_file,$file_contents);

copy('default.config.php', 'config.php');

}
?>

<?php
/*
 * Androtransfer.com Download Center
 * Copyright (C) 2012   Daniel Bateman
 * Copyright (C) 2013   James Taylor
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

require ('config.php');

	// Get the filename given by directory linker
	$fileget = $_GET["file"];
        
        if (substr($fileget, 0, 1) == '/' || !isset($_GET['file']) || (substr($fileget, 0, 3) == '../')) {
                header( 'Location: http://www.' . $siteName ) ;
        } else {
                $file = $fileget;
        }

	if(empty($_SERVER['REQUEST_URI'])) {
    	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
	}

	// Strip off query string so dirname() doesn't get confused
	$url = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
	$folderpath = 'http://'.$_SERVER['HTTP_HOST'].'/'.ltrim(dirname($url), '/');
//yay add some salt
        $s1 = md5('GetAndroidFilesToday1343645291935HelloSaltyummy12!@$@');
        $s2 = rand();
        $s3 = md5($s2.rand().$s1);
// Generate the unique download key
	$key = $s1.$s2.uniqid(md5(rand())).$s3;

// Get the activation time
	$time = date('U');

// Write the key and activation time to the database as a new row.
	$registerid = mysql_query("INSERT INTO downloadkey (uniqueid,timestamp,filename) VALUES(\"$key\",\"$time\",\"$file\")") or die(mysql_error());
?>

<DOCUTYPE html>
<head>
<title><?php echo "Downloading " . basename($file); ?></title>
</head>
<body>
<center>
<div id='header'>
        <a href="http://<?php echo $siteName ?>" ><img src="/images/header.jpg" width="900" height="182" /></a>
    </div>
<br>
 <script type="text/javascript"><!--
google_ad_client = "<?php echo $adsense ?>";
/* Files */
google_ad_slot = "3775827379";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br><br><br>

<?php
$files2 = "files/" . $file;
if (file_exists($files2)) {

if ($yourls == true) {
	$url = $siteName . "/getdownload.php?file=" . $file;
	$format = 'simple';
	$api_url = $yourls_url . '/yourls-api.php';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
	curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
                'url'      => $url,
                'format'   => $format,
                'action'   => 'shorturl',
                'username' => $username,
                'password' => $password2
        ));
}

// Fetch and return content
if ($yourls == true) {
$data = curl_exec($ch);
curl_close($ch);
} else {
$data = $siteName . "/getdownload.php?file=" . $file;
}
$pageURL = $siteName . "/getdownload.php?file=" . $file;
$filename = basename($file);
$ext = pathinfo($filename, PATHINFO_EXTENSION);
echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-text=" . $filename . " data-url=" . $data . " data-via=\"$twitusername\" data-size=\"large\">Tweet</a>\n";
echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";f$\n";
echo "</script>";
echo "<a href=\"https://twitter.com/" . $twitusername . "\" class=\"twitter-follow-button\" data-show-count=\"false\" data-size=\"large\">Follow @" . $twitusername . "</a>\n";
?>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";
fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<?php
echo "<br><h1>";
echo "Downloading";
echo "<br><br>";
if ($yourls == true) {
	echo '<a href=' . $data . '>' . $filename . '</a>';
} else {
	echo '<a href=' . $pageURL . '>' . $filename . '</a>';
}
echo "<br><br>";
$query = sprintf("SELECT * FROM md5sums WHERE filename= '%s'",
mysql_real_escape_string($file));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
$something = "files/" . $file;
        if (!$row['md5']) {
                $md5 = md5_file("$something");
                $sqlread = mysql_query("INSERT INTO md5sums (filename,md5) VALUES(\"$file\",\"$md5\")") or die(mysql_error());
                echo "MD5: " . $md5;
        }else{
                echo "MD5: " . $row['md5'];
}
echo "<br><br>";
echo "Downloads: " . $row['downloads'];
mysql_close();
?>
<br><br>
<?php
// Redirect to the download
echo '<META HTTP-EQUIV="Refresh" Content="2; URL=download.php?id=' . $key . '">';
//show HTML below for 5 seconds
} else {
echo "<h1>File doesn't exist";
echo "<br><a href=" . $siteName . ">Return to homepage</a></h1>";
}
?>
<br><br>
<script type="text/javascript"><!--
google_ad_client = "<?php echo $adsense ?>";
/* Files */
google_ad_slot = "3775827379";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br><br>

</center>
</body>
</html>

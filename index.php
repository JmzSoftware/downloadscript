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
require_once 'config.php';
require_once 'markdown.php';
$users = array();
$g     = scandir($baseDir);
foreach ($g as $x) {
    if (is_dir($x))
        $users[$x] = scandir($x);
    else
        $users[] = $x;
}
$currentFolder = NULL;
$currentDevice = NULL;
if (isset($_GET['device'])) {
    $currentDevice = $_GET['device'];
    if (!in_array($currentDevice, $users))
        die("Access denied.");
} else {
    $currentDevice = false;
}
if (isset($_GET['folder'])) {
    $currentFolder = $_GET['folder'];
    if (strpos($currentFolder, '..') !== false)
        die("Access denied.");
    $totalPath = null;
}
if (isset($_GET['folder2'])) {
    $currentFolder2 = $_GET['folder2'];
    if (strpos($currentFolder2, '..') !== false)
        die("Access denied.");
    $totalPath = null;
}
$fileMTimes = array();
define("FILE_FILTER_FILES", 0x1);
define("FILE_FILTER_DIRS", 0x2);
define("FILE_FILTER_ALL", FILE_FILTER_DIRS | FILE_FILTER_FILES);
function getAllInFolder($folder, $filter = FILE_FILTER_ALL)
{
    global $globalBlacklist;
    $handle  = opendir($folder);
    $entries = array();
    if ($handle) {
        while (false !== ($entry = readdir($handle))) {
            $entryPath = $folder . "/" . $entry;
            if ($entry[0] == '.')
                continue;
            if (in_array($entry, $globalBlacklist))
                continue;
            if ((is_dir($entryPath) && $filter & FILE_FILTER_DIRS) || (!is_dir($entryPath) && $filter & FILE_FILTER_FILES)) {
                $entries[] = $entry;
            }
        }
        closedir($handle);
    }
    return $entries;
}
function sizePretty($bytes)
{
    if ($bytes >= GB)
        return number_format($bytes / GB) . " GB";
    else if ($bytes >= MB)
        return number_format($bytes / MB) . " MB";
    else if ($bytes >= KB)
        return number_format($bytes / KB) . " KB";
    return number_format($bytes) . " bytes";
}
if ($currentDevice) {
    $devPath    = $baseDir . "/" . $currentDevice;
    $subFolders = getAllInFolder($devPath, FILE_FILTER_DIRS);
    sort($subFolders);
    if (!$currentFolder) {
        $currentFolder = '.';
    }
    if ($currentFolder) {
        $folderPath = $devPath . "/" . $currentFolder;
        $totalPath  = $folderPath;
        $files      = getAllInFolder($folderPath, FILE_FILTER_FILES);
        $handle     = opendir($folderPath);
    }
}
if (!empty($files)) {
    sort($files);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $siteName ?></title>
    <link type='text/css' rel='stylesheet' href='style.css'/>
</head>
<body>
<center>
<?php
if ($useAdsense == true) {
?>
<script type="text/javascript"><!--
google_ad_client = "<?php echo $adsense; ?>";
/* Top Bar */
google_ad_slot = "3775827379";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php
}
?>

    <div id='header'>
        <a href="http://<?php
echo $siteName;
?>" ><img src="/images/header.jpg" width="900" height="182" /></a>
    </div>
    <br>
    <div id='links' class='block'>
        <h2>Select a device</h2>
        <?php
foreach ($users as $user):
?>
        <?php
    if ((string) $user != "Array") {
?>
        <a href='?device=<?= $user ?>'><?= $user ?></a>
        <?php
    }
?>
        <?php
endforeach;
?>
        <div style='clear: both'></div>
    </div>

    <div id='page'>
        <?php
if ($currentDevice):
?>
            <div id='sidebar'>
                <div class='block'>
                    <h2><?= htmlspecialchars($currentDevice) ?></h2>
                    <ul>
                    <?php
    foreach ($subFolders as $folder):
?>
                        <li class='<?= $currentFolder == $folder ? "active" : "" ?>'><a href='?device=<?= rawurlencode($currentDevice) ?>&amp;folder=<?= rawurlencode($folder) ?>'><?= $folder ?></a><li>
                    <?php
    endforeach;
?>
                    </ul>
                </div>
            </div>

            <?php
    if ($currentFolder):
?>
                <div style='float: left; margin-left: 10px; width: 668px'>
                    <div class='block'>
                        <h2><?= htmlspecialchars($currentFolder) ?></h2>
                        <?php
        if (count($files) > 0):
?>
                            <table>
                                    <tr>
					<th align='left'>File</th>
                                        <th align='left' width='120px' style='padding-right: 50px'>Last Mod.</th>
                                        <th align='left' width='80px'>Size</th>
                                        <th align='right' width='80px'>Downloads</th>
                                    </tr>
                                    <?php
            foreach ($files as $file):
?>
                                        <?php
                $rp           = realpath($totalPath . "/" . $file);
                $resolvedPath = substr($rp, strpos($rp, "/files") + strlen("/files/"));
                $filePath     = $baseDir . "/" . $resolvedPath;
                $query        = sprintf("SELECT * FROM md5sums WHERE filename= '$resolvedPath'", mysql_real_escape_string($file));
                $result = mysql_query($query) or die(mysql_error());
                $row      = mysql_fetch_array($result);
                $filedown = $row['downloads'];
?>
                                        <tr class='download'>
                                            <td>
                                                <div class='name'><a style='display: block' href='getdownload.php?file=<?= $resolvedPath ?>'><?= $file ?><br></a></div>
                                            </td>
						<td><?= date("F dS Y", filemtime("files/" . $resolvedPath)) ?></td>
                                                <td><?= sizePretty(filesize($filePath)) ?></td>
						<td><?= $filedown ?></td>
                                        </tr>
                                    <?php
            endforeach;
?>
                            </table>
                        <?php
        else:
?>
                            No files here.
                        <?php
        endif;
?>
                    </div>

            <?php
    endif;
?>

        <?php
else:
?>
            <div id='content'>
                Click a link at the top to view each device's files.
            </div>
        <?php
endif;
?>
        <div style='clear: both'></div>
    </div>
</a></div>
<center>
<br>
<?php
$result = mysql_query('SELECT SUM(downloads) AS value_sum FROM md5sums');
$row    = mysql_fetch_assoc($result);
$sum    = $row['value_sum'];
echo "Total Downloads: " . $sum;
mysql_close();
?>
<br>
<br>
<?php
if ($yourls == true) {
?>
<script type="text/javascript"><!--
google_ad_client = "<?php echo $adsense; ?>";
/* Top Bar */
google_ad_slot = "3775827379";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php
}
?>
<br>
<center>
</body>
</html>

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

require('config.php');

$maxdownloads = "2";
$maxtime      = "7200";

function readfile_chunked($fname)
{
    $chunksize = 1 * (1024 * 1024);
    $buffer    = '';
    $handle    = fopen($fname, 'rb');
    if ($handle === false) {
        return false;
    }
    while (!feof($handle)) {
        $buffer = fread($handle, $chunksize);
        print $buffer;
    }
    return fclose($handle);
}

if (get_magic_quotes_gpc()) {
    $id = stripslashes($_GET['id']);
} else {
    $id = $_GET['id'];
}

//  the filename, key, timestamp, and number of downloads from the database

$query = sprintf("SELECT * FROM downloadkey WHERE uniqueid= '%s'", mysql_real_escape_string($id, $link));
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if (!$row) {
    echo "The download key you are using is invalid.";
} else {
    $fname     = $row['filename'];
    $timecheck = date('U') - $row['timestamp'];
    if ($timecheck >= $maxtime) {
        echo "This key has expired (exceeded time allotted).<br/>";
    } else {
        $downloads = $row['downloads'];
        $downloads += 1;
        if ($downloads > $maxdownloads) {
            echo "This key has expired (exceeded allowed downloads).<br />";
        } else {
            $path = getcwd();
            //Check for various invalid files, and loop holes like ../ and ./
            if ($fname == '.' || $fname == './' || $fname == "download.php" || $fname == "index.php" || empty($fname) || preg_match('/\..\/|\.\/\.|resources/', $fname)) {
                echo "Invalid File or File Not Specified";
                exit(0);
            }
            
            function fetch($content, $start, $end)
            {
                if ($content && $start && $end) {
                    $r = explode($start, $content);
                    if (isset($r[1])) {
                        $r = explode($end, $r[1]);
                        return $r[0];
                    }
                    return '';
                }
            }
            
            function fileExists($path)
            {
                return (@fopen($path, "r") == true);
            }
            
            $path      = $fname;
            $filename  = basename($path);
            $dir       = dirname($path);
            $blacklist = array(
                'php'
            );
            
            if (in_array($ext, $blacklist)) {
                die($ext . " is not an allowed extension.");
            }
            if (strpos($path, '../') !== false || strpos($path, '..\\') !== false) {
                die("not allowed:2");
            }
            $dlink  = $siteName . $baseDir . '' . $path;
            $server = parse_url($dlink, PHP_URL_HOST);
            $query6 = mysql_query("UPDATE md5sums SET downloads = downloads + 1 WHERE filename = '$fname'") or die(mysql_error());
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: binary");
            header('Content-Disposition: attachment; filename=\"".$filename."\"');
            header("Content-Length: " . filesize($baseDir . "/" . $path));
            $fp = fopen($baseDir . "/" . $path, "r");
            fpassthru($fp);
            fclose($fp);
        }
    }
}
mysql_close();
?>

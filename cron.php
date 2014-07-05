<?php
/*
 * Copyright (C) 2014 Jmz Software LLC
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

set_time_limit(0);
require ('config.php');
function readDirs($path){
  $dirHandle = opendir($path);
  while($item = readdir($dirHandle)) {
    $newPath = $path."/".$item;
    if(is_dir($newPath) && $item != '.' && $item != '..') {
       readDirs($newPath);
    } else if($item != '.' && $item != '..' && $item[0] != '.') {
      $newPath2 = substr($newPath, 6);
      $query = sprintf("SELECT * FROM md5sums WHERE filename= '%s'", mysql_real_escape_string($newPath2));
      $result = mysql_query($query) or die(mysql_error());
      $row       = mysql_fetch_array($result);
      if (!$row['filename']) {
        $md5 = md5_file($newPath);
        $sqlread = mysql_query("INSERT INTO md5sums (filename,md5) VALUES(\"$newPath2\",\"$md5\")") or die(mysql_error());
        echo "MD5 not found, creating it " . $newPath2 . "<br>";
     } else {
        $md5 = md5_file($newPath);
        if($md5 != $row['md5']) {
             $sqlread = mysql_query("UPDATE md5sums SET md5 = \"$md5\" WHERE filename = \"$newPath2\"");
             echo "MD5 not correct, correcting " . $newPath2 . "<br>";
        } else {
             echo "Good " . $newPath2 . "<br>";
        }
      }
    }
  }
}

readDirs("files");
?>

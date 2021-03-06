<?php
/*
 * Androtransfer.com Download Center
 * Copyright (C) 2012   Daniel Bateman
 * Copyright (C) 2013   Jmz Software LLC
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

// Sizes
define("ONE_K", 1024);
define("KB", ONE_K);
define("MB", ONE_K * KB);
define("GB", ONE_K * MB);
define("TB", ONE_K * GB);

// Site
$siteName        = "domain.com"; // Without www
$baseDir         = "files"; // This can be changed.  This is where you will put the files to download
$globalBlacklist = array(
    "images",
    "private"
);

$host         = 'localhost';
$database     = 'databasename'; // MySQL Database Name
$user         = 'mysqlusername'; // MySQL username
$password     = 'mysqlpassword'; // MySQL password
$yourls       = false; // Use yourls
$yourls_url   = 'yourlsurl'; // Path to Yourls API without yourls-api.php
$username     = 'yourlsuser'; // yourls username
$password2    = 'yourlspass'; // yourls password
$useAdsense   = false; // Use Adsense
$adsense      = 'adsenseid'; // Adsense publisher ID.  Starts with ca-pub-
$siteTitle    = 'titleofsite'; // Title of site to show in window
$twitusername = 'twitterusername'; // Twitter username without @

$link = mysql_connect($host, $user, $password) or die("Could not connect: " . mysql_error());
mysql_select_db($database) or die(mysql_error());
?>

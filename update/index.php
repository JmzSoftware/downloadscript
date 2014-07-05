<?php
/*
 * Copyright 2012 - VisualAppeal GbR - www.visualappeal.de
 * Copyright 2014 - Jmz Software LLC - www.jmzsoftware.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */

/*
 * Index.php in the folder update
 */

require('update.php');

$update = new AutoUpdate(true); //Enable logging

$update->currentVersion = 2;

/*
 * In this example the folder 'updateUrl' includes 3 files, 0.1.zip, 0.2.zip and update.ini
 */
$update->updateUrl = 'http://jmzsoftware.com/updates';

$latest = $update->checkUpdate(false);

if ($latest !== false) {
    if ($latest > $update->currentVersion) {
        //Install new update
        echo "New Version: ".$update->latestVersionName."<br>";

        //You can also stop here and let the user decide when to update.
        echo "Installing Update...<br>";
        if ($update->update()) {
            echo "Update successful!";
        }
        else {
            echo "Update failed!";
        }

    }
    else {
        echo "Current Version is up to date";
    }
}
else {
    echo $update->getLastError();
}
?>

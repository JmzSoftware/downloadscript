<?php
/*
 * Index.php in the folder update
 */

require('update.php');

$update = new AutoUpdate(true); //Enable logging

$update->currentVersion = 1;

/*
 * In this example the folder 'updateUrl' includes 3 files, 0.1.zip, 0.2.zip and update.ini
 */
$update->updateUrl = 'http://jmzsoftware.com/updates';

$latest = $update->checkUpdate();

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

<?php
/*
Name: Link Checker
Author: Renemari Padillo
Author URI: http://about.me/renemari.padillo
Version: 1.0.0
License: MIT
*/

require_once("link-checker.php");

ini_set('max_execution_time', 0); // Remove the execution timeout to allow our script to run when checking many links

$fileLink = fopen("links", "r");
$output = fopen("page_not_found", "w");

if (!$fileLink)
    echo "File link doesn't exist.";
else {
    //
    // Start Checking
    //
    while (!feof($fileLink)) {
        $link = fgets($fileLink);

        if ($link !== FALSE) {
            $isPageNotFound = LinkChecker::IsPageNotFound($link);

            if ($isPageNotFound)
                fwrite($output, $link);
        }
    }

    echo "All links have been checked! see page_not_found file for the list of Error 404 links.<br />";
}

fclose($fileLink);
fclose($output);

?>
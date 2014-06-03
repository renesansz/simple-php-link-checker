<?php
/*
Name: Link Checker
Author: Renemari Padillo
Author URI: http://about.me/renemari.padillo
Version: 1.0.0
License: MIT
*/

class LinkChecker
{
    /**
     * Checks the link if it exist
     * 
     * @param String $url [Link URL to be checked]
     *
     * @return Boolean [Indicator whether the page exist or not]
     */
    public static function IsPageNotFound($url)
    {
        $result = false;

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        //
        // Get the HTML or whatever is linked in $url
        //
        $response = curl_exec($handle);
        //
        // Let's check for 404 (File not found)
        //
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($httpCode == 404)
            $result = true;

        return $result;
    }
}
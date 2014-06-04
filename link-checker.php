<?php
/*
Name: Link Checker
Author: Renemari Padillo
Author URI: http://about.me/renemari.padillo
Version: 1.1.0
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

    /**
     * This will get the final url of the link (if there are any redirection(s) along)
     *
     * @param String $url [Link URL to be checked]
     *
     * @return String [The final URL]
     */
    public static function GetFinalUrl($url)
    {
        $result = "";
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $link = curl_exec($handle);

        $result = curl_getinfo($handle, CURLINFO_EFFECTIVE_URL);

        return $result;
    }
}
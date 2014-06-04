<?php
/*
Name: Link Checker
Author: Renemari Padillo
Author URI: http://about.me/renemari.padillo
Version: 1.1.0
License: MIT
*/

if ( isset($_POST["data"]) ) {
    if ( $_FILES["file"]["type"] != "application/octet-stream" && $_FILES["file"]["type"] != "text/plain" )
        echo "Invalid file type";
    else {
        $data = $_POST["data"];
        $file = $_FILES["file"];
        // Throw away after copying...
        unset($_POST["data"]);
        unset($_FILES["file"]);

        if ( $file["error"] !== UPLOAD_ERR_OK && ! is_uploaded_file($file["tmp_name"]) )
            echo "File link doesn't exist.";
        else {
            require_once("link-checker.php");

            ini_set('max_execution_time', 0); // Remove the execution timeout to allow our script to run when checking many links
            
            $uploadDest = __DIR__.'/links';
            // remove duplicate upload
            if ( file_exists($uploadDest) )
                unlink($uploadDest);

            move_uploaded_file($file["tmp_name"], $uploadDest);
            
            $fstream = fopen($uploadDest, "r");
            $output = fopen("output.txt", "w");;
            //
            // Start Checking
            //
            while ( ! feof($fstream) ) {
                $link = fgets($fstream);

                if ( $link !== FALSE ) {
                    $result = null;

                    switch ($data["option"]) {
                        case "check_404":
                            $isPageNotFound = LinkChecker::IsPageNotFound($link);
                            
                            if ( $isPageNotFound )
                                $result = $link;

                            break;
                        case "get_final_url":
                            $result = LinkChecker::GetFinalUrl($link);
                            break;
                    }
                    //
                    // Display output to file
                    //
                    if ( ! is_null($result) )
                        fwrite($output, $result."\n");
                }
            }

            fclose($fstream);
            fclose($output);
            echo 'All links have been checked! see <a href="output.txt">output file</a> for the result.<br /><br />';
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Simple PHP Link Checker</title>
</head>
<body>
    <header>
        <h1>Simple PHP Link Checker</h1>
        <p>Created by <a href="http://about.me/renemari.padillo">Renemari Padillo</a>.</p>
    </header>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <label for="data[option]">
                Options:
            </label><br />
            <select name="data[option]">        
                <option value="check_404">Check forError  404</option>
                <option value="get_final_url">Get the absolute url (This is useful if you want to get the redirection link)</option>
            </select>
        </div><br />
        <div class="input-group">
            <label for="file">
                The file that contains the links you wanted to be checked:<br />
            </label>
            <input name="file" type="file" required /> (Plain text only)
        </div>
        <button type="submit">Go!</button>
    </form><br />
    <footer>Fork this plugin on <a href="https://github.com/padz535/simple-php-link-checker">Github</a>. Created by Renemari Padillo.</footer>
</body>
</html>
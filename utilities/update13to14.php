<?php
/**
 * VFM - veno file manager: update.php
 * updates users.php file from v1.3.x to 1.4
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   VenoFileManager
 * @author    Nicola Franchini <info@veno.it>
 * @copyright 2013-2014 Nicola Franchini
 * @license   Regular License http://codecanyon.net/licenses/regular
 * @link      http://filemanager.veno.it/
 */
require "vfm-admin/users.php"; ?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<title>Update VFM users from v1.3.x to v1.4</title>
<style type="text/css">
html, body{
    margin: 0;
    padding: 0;
}
body{
    font-family: Arial, Helvetica, sans-serif;
}
#wrapper{
    width: 90%;
    max-width: 800px;
    margin: 30px auto;
}
</style>
</head>

<body>
    <div id="wrapper">
    <?php


    /**
    * Check if string is json_encoded;
    *
    * @param string $string to check
    *
    * @return true/false
    */
    function isJson($string) 
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    print "Start updating...";

    $doupdate = false;
    $users = $_USERS;
    $usersnew = array();
    foreach ($users as $user) {

        $usernew = array();
        $usernew['name'] = $user['name'];
        $usernew['pass'] = $user['pass'];
        $usernew['role'] = $user['role'];

    if (isset($user['dir'])) {
        $doupdate = true;
        json_decode($user['dir']);

        if (isJson($user['dir']) == true) {
            $doupdate = false;
            $usernew['dir'] = $user['dir'];
        } else {
            $folderarray = array();
            array_push($folderarray, rtrim($user['dir'], "/"));
            $usernew['dir'] = json_encode($folderarray);
        }
        
    }
    if (isset($user['quota'])) {
        $usernew['quota'] = $user['quota'];
    }
    if (isset($user['email'])) {
        $usernew['email'] = $user['email'];
    }
        array_push($usersnew, $usernew);
    }


    if ($doupdate == true) {
        $usrs = '$_USERS = ';
    if ( false == (file_put_contents(
        'vfm-admin/users.php', "<?php\n\n $usrs".var_export($usersnew, true).";\n"
    ))
    ) {
            print "<h1>ERROR SAVING USERS</h1>";
    } else {
            print "<h1>USERS UPDATED</h1>";
            print "<a style=\"padding:10px 20px; background:#64a4b7; color:#fff; font-weight:bold; text-decoration:none;\" href=\"./\">GO TO LOGIN</a>";
            unlink(__FILE__);
    }
    } else {
            print "<h1>Users already updated</h1>";
            print "<a style=\"padding:10px 20px; background:#64a4b7; color:#fff; font-weight:bold; text-decoration:none;\" href=\"./\">GO TO LOGIN</a>";
            unlink(__FILE__);
    }
    ?>
    </div>
</body>
</html>
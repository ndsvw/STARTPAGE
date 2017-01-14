<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");

$user = new User();

if(isset($_GET['userboxid'])){
    if($user->logged_in){
        $q = mysql_query("
            SELECT
                id, link
            FROM
                startpage_boxen
            WHERE
                user = $user->id
            AND
                userboxid = '" . mysql_real_escape_string($_GET['userboxid']) . "'
        ");

        mysql_query("
            INSERT INTO startpage_boxen_click
                (box_id)
            VALUES
                (mysql_fetch_array($q)[0])
        ");

        header("Location: " . urldecode(mysql_fetch_array($q)[1]));
    }
} else if($_GET['link']){
    header("Location: " . urldecode($_GET['link']));
}

?>

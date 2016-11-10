<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");

$user = new User();

if(isset($_GET['userboxid']) && isset($_GET['link'])){
    $a = mysql_fetch_array(mysql_query("
        SELECT
            id
        FROM
            startpage_boxen
        WHERE
            user = $user->id
        AND
            userboxid = '" . mysql_real_escape_string($_GET['userboxid']) . "'
    "));

    mysql_query("
        INSERT INTO startpage_boxen_click
            (box_id)
        VALUES
            ($a[0])
    ");

    header("Location: " . urldecode($_GET['link']));
}
?>

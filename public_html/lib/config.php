<?php
//this file you'll have to create directly on the VM or use FTP to move it
//this should not and will not be on github, -points if it's seen on github.

//for Heroku use the below segment and delete the duplicate below
$cleardb_url      = parse_url(getenv("JAWSDB_URL"));
$dbhost   = $cleardb_url["host"];
$dbuser = $cleardb_url["ejm34"];
$dbpass = $cleardb_url[""];
$dbdatabase = substr($cleardb_url["path"],1);
?>

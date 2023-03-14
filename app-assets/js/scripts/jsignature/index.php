<?php
//if()
session_name("JOBSHEET");
session_start();
header("Location:https://".$_SERVER['HTTP_HOST']."/jobsheet/login.php");

?>
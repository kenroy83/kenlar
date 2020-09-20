<?php
/*********************************************************************
    logout.php

    Destroy clients session.

    
**********************************************************************/

require('client.inc.php');
//We are checking to make sure the user is logged in before a logout to avoid session reset tricks on excess logins
$_SESSION['_client']=array();
session_unset();
session_destroy();
header('Location: index.php');
require('index.php');
?>

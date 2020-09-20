<?php
/*********************************************************************
    ostconfig.php

    Static osTicket configuration file. Mainly useful for mysql login info.
    Created during installation process and shouldn't change even on upgrades.
   

**********************************************************************/
#Disable direct access.
if(!strcasecmp(basename($_SERVER['SCRIPT_NAME']),basename(__FILE__)) || !defined('ROOT_PATH')) die('Kenlar Enterprise!');

#Install flag
define('OSTINSTALLED',TRUE);
if(OSTINSTALLED!=TRUE){
 Header('Location: '.ROOT_PATH.'setup/');
 exit;
}

#Default admin email. Used only on db connection issues and related alerts.
define('ADMIN_EMAIL','info@kenlar.net');

#Mysql Login info
define('DBTYPE','mysql');
define('DBHOST','localhost'); 
define('DBNAME','kenlarn1_ost');
define('DBUSER','kenlarn1_admin');
define('DBPASS','4taskammo');

#Table prefix
define('TABLE_PREFIX','ost_');

?>

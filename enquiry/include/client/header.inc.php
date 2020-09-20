<?php

$title=($cfg && is_object($cfg))?$cfg->getTitle():'osTicket :: Support Ticket System';



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <title><?=Format::htmlchars($title)?></title>

    <link rel="stylesheet" href="./styles/main.css" media="screen">

    <!--<link rel="stylesheet" href="./styles/colors.css" media="screen">-->

    <link href="http://kenlar.net/library/css/global.css" rel="stylesheet" type="text/css" />

</head>

<body>

<!--<div id="container">-->

    <!--<div id="header">

        <a id="logo" href="index.php" title="Support Center"><img src="./images/logo2.jpg" border=0 alt="Support Center"></a>

        <p><span>SUPPORT TICKET</span> SYSTEM</p>

    </div>-->

<div id="container">

<div id="24h"></div>

<div style="height:110px; background:none">

<div id="plus"></div>



<div style="width: 250px; position: absolute; left: 6px; top: 20px; color: #FFFFFF; height: 97px;"><img src="../../../library/images/Kenlar-LOGO-2.png" width="250" height="97" alt="Home" longdesc="http://www.kenlar.net" /></div>

</div>

<div id="topnav">

<ul class="menu">

        <li class="m"><a href="http://www.kenlar.net/"><b><span style="font-weight:bold">Home</span></b></a></li>

      <li class="m"><a href="http://www.kenlar.net/about/"><b><span style="font-weight:bold">About Us</span></b></a></li>

      <li class="m"><a href="http://www.kenlar.net/residential/"><b><span style="font-weight:bold">Residential Services</span></b></a></li>

      <li class="m"><a href="http://www.kenlar.net/business/"><b><span style="font-weight:bold">Business Services</span></b></a></li>

      <li class="m"><a href="http://www.kenlar.net/contact/"><b><span style="font-weight:bold">Contact Us</span></b></a></li>

      <li class="current"><a href="http://www.kenlar.net/enquiry/"><b><span style="font-weight:bold">Book a Repair</span></b></a></li>

    </ul>

</div>

    

    <!--<ul id="nav">

         <?php                    

         if(is_object($thisclient) && $thisclient->isValid()) {?>

         <li><a class="log_out" href="logout.php">Log Out</a></li>

         <li><a class="my_tickets" href="view.php">My Tickets</a></li>

         <?php }else {?>

         <li><a class="ticket_status" href="view.php">Ticket Status</a></li>

         <?php }?>

         <li><a class="new_ticket" href="open.php">New Ticket</a></li>

         <li><a class="home" href="index.php">Home</a></li>

    </ul>-->

    


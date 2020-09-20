<?php
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki wangu?'); //Say bye to our friend..

//Please customize the message below to fit your organization speak!
?>
<div style="background:#FFFFFF">
<div id="breadcrumb">Your are here:&nbsp;&nbsp;<a href="http://www.kenlar.net/">Home</a> &gt; <a href="http://www.kenlar.net/enquiry/">Book a Repair</a> &gt; New Request</div>
<br />
<table width="940" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="310" valign="top">
    <div style=" width:280px; background:#E6E6E6 url(../library/images/subnav_hd.gif) top no-repeat; padding-top:30px; position: relative">
      <div style="padding:0px 25px 25px 25px"><span style="font-size: 20px; color:#454545">Repair Center</span><br />
        <br />
        
        <ul class="sub_menu">
         <li class="current"><a class="new_ticket" href="open.php">New Repair</a></li>
		 <?php                    
         if(is_object($thisclient) && $thisclient->isValid()) {?>
         <li class="m"><a class="my_tickets" href="view.php">My Repairs</a></li>
         <li class="m"><a class="log_out" href="logout.php">Log Out</a></li>         
         <?php }else {?>
         <li class="m"><a class="ticket_status" href="view.php">Repair Status</a></li>
         <?php }?>         
    </ul>
 </div>
    </div>
    <div style=" width:280px; height:10px; background: url(../library/images/subnav_ft.gif); margin-bottom:25px"></div>    </td>
    <td valign="top" style="border-left:1px dotted #CCCCCC;"><div style="padding-left:25px">
      <p>&nbsp;</p>
      <h1>Ticket Created</h1>
      
<div>
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" id="infomessage"><?=$msg?></p>
    <?}elseif($warn) {?>
        <p id="warnmessage"><?=$warn?></p>
    <?}?>
</div>
<div style="margin:5px 0 100px 0;">
    <strong>Hello <?=Format::htmlchars($ticket->getName())?></strong>,<br><br />

    <p>
     Thank you for contacting us. A representative of Kenlar will contact you shortly if necessary, your support ticket has now been generated.<br>
     An email will be sent to you with your ticket number. You'll need the ticket number along with your email to view status and progress online. </p>
          
    <?if($cfg->autoRespONNewTicket()){ ?>
    <p>An email with the ticket number has been sent to <b><?=$ticket->getEmail()?></b>.
        You'll need the ticket number along with your email to view status and progress online.
    </p>
    <p>
     If you wish to send additional comments or information regarding same issue, please follow the instructions on the email.
    </p>
    <?}?>
    <br />
    <p>Kenlar Enterprise - Repair Team. </p>
</div>
<?
unset($_POST); //clear to avoid re-posting on back button??
?>

</div></td>
  </tr>
</table>






<p>&nbsp;</p>
  <p>&nbsp;</p>
</div>

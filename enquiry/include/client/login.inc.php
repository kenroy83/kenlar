<?php
if(!defined('OSTCLIENTINC')) die('Kwaheri');

$e=Format::htmlchars($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$t=Format::htmlchars($_POST['lticket']?$_POST['lticket']:$_GET['t']);
?>
<div style="background:#FFFFFF">
<div id="breadcrumb">Your are here:&nbsp;&nbsp;<a href="http://www.kenlar.net/">Home</a> &gt; <a href="http://www.kenlar.net/enquiry/">Book a Repair</a> &gt; Repair Status</div>
<br />
<table width="940" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="310" valign="top">
    <div style=" width:280px; background:#E6E6E6 url(../library/images/subnav_hd.gif) top no-repeat; padding-top:30px; position: relative">
      <div style="padding:0px 25px 25px 25px"><span style="font-size: 17px; color:#454545">Repair Center</span><br />
        <br />
        
        <ul class="sub_menu">
         
		 <?php                    
         if(is_object($thisclient) && $thisclient->isValid()) {?>
         <li class="m"><a class="my_tickets" href="view.php">My Repairs</a></li>          
         <?php }else {?>
         <li class="current"><a class="ticket_status" href="view.php">Repair Status</a></li>
         <?php }?>  
         <li class="m"><a class="new_ticket" href="open.php">New Repair</a></li>  
          <?php                    
         if(is_object($thisclient) && $thisclient->isValid()) {?> 
         <li class="m"><a class="log_out" href="logout.php">Log Out</a></li>
          <?php }?>    
    </ul>
 </div>
    </div>
    <div style=" width:280px; height:10px; background: url(../library/images/subnav_ft.gif); margin-bottom:25px"></div>    </td>
    <td valign="top" style="border-left:1px dotted #CCCCCC;"><div style="padding-left:25px">
      <p>&nbsp;</p>
      <h1>Repair Status</h1>

<div>
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($warn) {?>
        <p class="warnmessage"><?=$warn?></p>
    <?}?>
</div>
<div style="margin:5px 0px 100px 0; width:100%;">
    <p>
        To view the status of a ticket, provide us with your login details below.<br/>
        If this is your first time contacting us or you've lost the ticket ID, please <a href="open.php">click here</a> to open a new ticket.
        <br />
        <br />
    </p>
    <span class="error"><?=$loginmsg?></span><br /><br />
    <form action="login.php" method="post">
    <table width="600" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr> 
            <td width="50" height="0">E-Mail:</td>
            <td width="165"><input type="text" name="lemail" size="25" value="<?=$e?>" style="width:150px"></td>
            <td width="65">Ticket ID:</td>
            <td width="165"><input type="text" name="lticket" size="10" value="<?=$t?>" style="width:150px"></td>
            <td width="105"><input type="submit" value="View Status"></td>
        </tr>
    </table>
    </form>
</div>

    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
  <p>&nbsp;</p>
</div>

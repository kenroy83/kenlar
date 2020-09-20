<?php
if(!defined('OSTCLIENTINC') || !is_object($thisclient) || !is_object($ticket)) die('Kwaheri'); //bye..see ya
//Double check access one last time...
if(strcasecmp($thisclient->getEmail(),$ticket->getEmail())) die('Access Denied');

$info=($_POST && $errors)?Format::htmlchars($_POST):array(); //Re-use the post info on error...savekeyboards.org

$dept = $ticket->getDept();
//Making sure we don't leak out internal dept names
$dept=($dept && $dept->isPublic())?$dept:$cfg->getDefaultDept();
//We roll like that...
?>
<div style="background:#FFFFFF">
<div id="breadcrumb">Your are here:&nbsp;&nbsp;<a href="http://www.kenlar.net/">Home</a> &gt; <a href="http://www.kenlar.net/enquiry/">Book a Repair</a> &gt; <a href="view.php">My Repairs</a> &gt; Ticket #<?=$ticket->getExtId()?></div>
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
         <li class="m"><a class="new_ticket" href="open.php">New Repair</a></li>
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
      <h1>Ticket #<?=$ticket->getExtId()?>&nbsp;<a href="view.php?id=<?=$ticket->getExtId()?>" title="Reload"><span class="Icon refresh">&nbsp;</span></a></h1>
      
<table width="100%" cellpadding="1" cellspacing="0" border="0">
     
    <tr>
       <td width=50%>	
        <table align="center" class="infotable" cellspacing="1" cellpadding="3" width="100%" border=0>
	        <tr>
				<th width="100" >Ticket Status:</th>
				<td><?=$ticket->getStatus()?></td>
			</tr>
            <tr>
                <th>Department:</th>
                <td><?=Format::htmlchars($dept->getName())?></td>
            </tr>
			<tr>
                <th>Create Date:</th>
                <td><?=Format::db_datetime($ticket->getCreateDate())?></td>
            </tr>
		</table>
	   </td>
	   <td width=50% valign="top">
        <table align="center" class="infotable" cellspacing="1" cellpadding="3" width="100%" border=0>
            <tr>
                <th width="100">Name:</th>
                <td><?=Format::htmlchars($ticket->getName())?></td>
            </tr>
            <tr>
                <th width="100">Email:</th>
                <td><?=$ticket->getEmail()?></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td><?=Format::phone($ticket->getPhone())?></td>
            </tr>
        </table>
       </td>
    </tr>
</table>
<div class="msg">Subject: <?=Format::htmlchars($ticket->getSubject())?></div>
<div>
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" id="infomessage"><?=$msg?></p>
    <?}?>
</div>
<br>
<div align="left">
    <span class="Icon thread">Ticket Thread</span>
    <div id="ticketthread">
        <?
	    //get messages
        $sql='SELECT msg.*, count(attach_id) as attachments  FROM '.TICKET_MESSAGE_TABLE.' msg '.
            ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  msg.ticket_id=attach.ticket_id AND msg.msg_id=attach.ref_id AND ref_type=\'M\' '.
            ' WHERE  msg.ticket_id='.db_input($ticket->getId()).
            ' GROUP BY msg.msg_id ORDER BY created';
	    $msgres =db_query($sql);
	    while ($msg_row = db_fetch_array($msgres)):
		    ?>
		    <table align="center" class="message" cellspacing="0" cellpadding="1" width="605" border=0>
		        <tr><th><?=Format::db_daydatetime($msg_row['created'])?></th></tr>
                <?if($msg_row['attachments']>0){ ?>
                <tr class="header"><td><?=$ticket->getAttachmentStr($msg_row['msg_id'],'M')?></td></tr> 
                <?}?>
                <tr class="info">
                    <td><?=Format::display($msg_row['message'])?></td></tr>
		    </table>
            <?
            //get answers for messages
            $sql='SELECT resp.*,count(attach_id) as attachments FROM '.TICKET_RESPONSE_TABLE.' resp '.
                ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  resp.ticket_id=attach.ticket_id AND resp.response_id=attach.ref_id AND ref_type=\'R\' '.
                ' WHERE msg_id='.db_input($msg_row['msg_id']).' AND resp.ticket_id='.db_input($ticket->getId()).
                ' GROUP BY resp.response_id ORDER BY created';
            //echo $sql;
		    $resp =db_query($sql);
		    while ($resp_row = db_fetch_array($resp)) {
                $respID=$resp_row['response_id'];
                ?>
    		    <table align="center" class="response" cellspacing="0" cellpadding="1" width="100%" border=0>
    		        <tr>
    			        <th><?=Format::db_daydatetime($resp_row['created'])?>&nbsp;-&nbsp;<?=Format::htmlchars($resp_row['staff_name'])?></th></tr>
                    <?if($resp_row['attachments']>0){ ?>
                    <tr class="header">
                        <td><?=$ticket->getAttachmentStr($respID,'R')?></td></tr>
                                    
                    <?}?>
			        <tr class="info">
				        <td> <?=Format::display($resp_row['response'])?></td></tr>
		        </table>
		    <?
		    } //endwhile...response loop.
            $msgid =$msg_row['msg_id'];
        endwhile; //message loop.
     ?>
    </div>
</div>
<div>
    <div align="center">
        <?if($_POST && $errors['err']) {?>
            <p align="center" id="errormessage"><?=$errors['err']?></p>
        <?}elseif($msg) {?>
            <p align="center" id="infomessage"><?=$msg?></p>
        <?}?>
    </div> 
    <div id="reply" style="padding:10px 0 20px 40px;">
        <?if($ticket->isClosed()) {?>
        <div class="msg">Ticket will be reopened on message post</div>
        <?}?>
        <form action="view.php?id=<?=$id?>#reply" name="reply" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$ticket->getExtId()?>">
            <input type="hidden" name="respid" value="<?=$respID?>">
            <input type="hidden" name="a" value="postmessage">
            <div align="left">
                Enter Message <font class="error">*&nbsp;<?=$errors['message']?></font><br/>
                <textarea name="message" id="message" cols="60" rows="7" wrap="soft"><?=$info['message']?></textarea>
            </div>
            <? if($cfg->allowOnlineAttachments()) {?>
            <div align="left">
                Attach File<br><input type="file" name="attachment" id="attachment" size=30px value="<?=$info['attachment']?>" /> 
                    <font class="error">&nbsp;<?=$errors['attachment']?></font>
            </div>
            <?}?>
            <div align="left"  style="padding:10px 0 10px 0;">
                <input class="button" type='submit' value='Post Reply' />
                <input class="button" type='reset' value='Reset' />
                <input class="button" type='button' value='Cancel' onClick='window.location.href="view.php"' />
            </div>
        </form>
    </div>
</div>

</div></td>
  </tr>
</table>
<p>&nbsp;</p>
  <p>&nbsp;</p>
</div>

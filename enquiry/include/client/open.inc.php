<?php
if(!defined('OSTCLIENTINC')) die('Kwaheri rafiki wangu?'); //Say bye to our friend..

$info=($_POST && $errors)?Format::htmlchars($_POST):array(); //on error...use the post data
?>
<div style="background:#FFFFFF">
<div id="breadcrumb">Your are here:&nbsp;&nbsp;<a href="http://www.kenlar.net/">Home</a> &gt; <a href="http://www.kenlar.net/enquiry/">Book a Repair</a> &gt; New Request</div>
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
         <li class="m"><a class="ticket_status" href="view.php">Repair Status</a></li>
         <?php }?>  
         <li class="current"><a class="new_ticket" href="open.php">New Repair</a></li>  
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
      <h1>Submit a new repair request</h1>
      
<div>
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" id="infomessage"><?=$msg?></p>
    <?}elseif($warn) {?>
        <p id="warnmessage"><?=$warn?></p>
    <?}?>
</div>
<div>Please fill in the form below to open a new ticket.</div><br>
<form action="open.php" method="POST" enctype="multipart/form-data">
<table align="left" cellpadding=2 cellspacing=1 width="100%">
    <tr>
        <th width="20%" align="left">Full Name:</th>
        <td>
            <?if ($thisclient && ($name=$thisclient->getName())) {
                ?>
                <input type="hidden" name="name" value="<?=$name?>"><?=$name?>
            <?}else {?>
                <input name="name" type="text" value="<?=$info['name']?>" size="25" maxlength="128" style="width:180px">
	        <?}?>
            &nbsp;<font class="error">*&nbsp;<?=$errors['name']?></font>
        </td>
    </tr>
    <tr>
        <th align="left" nowrap >Email Address:</th>
        <td>
            <?if ($thisclient && ($email=$thisclient->getEmail())) {
                ?>
                <input type="hidden" name="email" size="25" value="<?=$email?>"><?=$email?>
            <?}else {?>             
                <input name="email" type="text" value="<?=$info['email']?>" size="25" maxlength="128" style="width:180px">
            <?}?>
            &nbsp;<font class="error">*&nbsp;<?=$errors['email']?></font>
        </td>
    </tr>
    <tr>
        <th align="left">Telephone:</th>
        <td><input name="phone" type="text" value="<?=$info['phone']?>" size="25" maxlength="64" style="width:180px">
          &nbsp;<font class="error">&nbsp;<?=$errors['phone']?></font></td>
    </tr>
    <tr align="left" height=2px><td colspan=2 >&nbsp;</td>
    </tr>
    <tr>
        <th align="left">Help Topic:</th>
        <td>
            <select name="topicId">
                <option value="" selected >Select One</option>
                <?
                 $services= db_query('SELECT topic_id,topic FROM '.TOPIC_TABLE.' WHERE isactive=1 ORDER BY topic');
                 while (list($topicId,$topic) = db_fetch_row($services)){
                    $selected = ($info['topicId']==$topicId)?'selected':''; ?>
                    <option value="<?=$topicId?>"<?=$selected?>><?=$topic?></option>
                <?
                 }?>
                <option value="0" >General Inquiry</option>
            </select>
            &nbsp;<font class="error">*&nbsp;<?=$errors['topicId']?></font>
        </td>
    </tr>
    <tr>
        <th align="left">Subject:</th>
        <td>
            <input name="subject" type="text" value="<?=$info['subject']?>" size="35" maxlength="128" style="width:350px">
            &nbsp;<font class="error">*&nbsp;<?=$errors['subject']?></font>
        </td>
    </tr>
    <tr>
        <th align="left" valign="top">Message:</th>
        <td>
            <? if($errors['message']) {?> <font class="error"><b>&nbsp;<?=$errors['message']?></b></font><br/><?}?>
            <textarea name="message" cols="35" rows="8" wrap="soft" style="width:450px"><?=$info['message']?></textarea></td>
    </tr>
    <?
    if($cfg->allowPriorityChange() ) {
      $sql='SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE.' WHERE ispublic=1 ORDER BY priority_urgency DESC';
      if(($priorities=db_query($sql)) && db_num_rows($priorities)){ ?>
      <tr>
        <th>Priority:</th>
        <td>
            <select name="pri">
              <?
                $info['pri']=$info['pri']?$info['pri']:$cfg->getDefaultPriorityId(); //use system's default priority.
                while($row=db_fetch_array($priorities)){ ?>
                    <option value="<?=$row['priority_id']?>" <?=$info['pri']==$row['priority_id']?'selected':''?> ><?=$row['priority_desc']?></option>
              <?}?>
            </select>
        </td>
       </tr>
    <? }
    }?>

    <?if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())  
                || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))){
        
        ?>
    <tr>
        <th>Attachment:</th>
        <td>
            <input type="file" name="attachment"><font class="error">&nbsp;<?=$errors['attachment']?></font>
        </td>
    </tr>
    <?}?>
    <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
    <tr>
        <td></td>
        <td>
            <input class="button" type="submit" name="submit_x" value="Open Ticket">
            <input class="button" type="reset" value="Reset">
            <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="index.php"'>    
        </td>
    </tr>
</table>
</form>
    </div></td>
  </tr>
</table>






<p>&nbsp;</p>
  <p>&nbsp;</p>
</div>

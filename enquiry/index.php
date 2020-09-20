<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Kenroy Larmond <kenroy83@hotmail.com>

**********************************************************************/
require('client.inc.php');
require(CLIENTINC_DIR.'header.inc.php');
?>
<div style="background:#FFFFFF">
<div id="breadcrumb">Your are here:&nbsp;&nbsp;<a href="http://www.kenlar.net/enquiry">Home</a> &gt; Book a Repair</div>
<div style="width:940px; height:230px; color:#FFFFFF; background:#eabc20 url(../library/images/ad_repaircenter.jpg) no-repeat; margin:auto; position:relative">
 <div style="width:540px; position:absolute; left:30px; top:120px"><strong>To streamline repair requests and better serve you, we utilise a ticket system.</strong> <br />
<br />
Every repair request is assigned a unique ticket number which you can use to track the progress and responses online. For your reference we provide complete archives and history of all your repair requests. </div>    
  </div>
<br />
<br />
<table width="940" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="470" valign="top"><div style="padding:20px; border:1px solid #DDDDDD; width:410px;">
      <img src="../library/images/support_repair.jpg" width="190" height="80" border="0" />
      <br />
      <strong><br />
      Check status of previously opened ticket</strong>. we provide archives and history of all your support requests complete with responses. <br />
      <br />
      <form action="view.php" method="post">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><strong>
              <label>Email:</label>
            </strong></td>
            <td><strong>
              <label>Ticket#:</label>
            </strong></td>
          </tr>
          <tr>
            <td><input type="text" name="lemail" style="width:190px" /></td>
            <td><input type="text" name="lticket" style="width:190px" /></td>
          </tr>
        </table>
        <br />
            <input type="image" src="../library/images/bt_check_status.gif" alt="Check Status" width="120" height="23" border="0" />
        
      </form>
    </div></td>
    <td width="470" align="left" valign="top"><div style="padding:20px; border:1px solid #DDDDDD; width:410px; float:right"> 
      <div align="right"><img src="../library/images/support_new.jpg" width="190" height="80" border="0" /></div>
      <br />
        <strong>Submit a new repair request</strong>. Please provide as much detail as possible so we can best assist you. To update a previously submitted ticket, please use the form to the left. A valid email address is required. <br />
        <br />
        <div align="right"><a href="open.php"><img src="../library/images/bt_new_request.gif" alt="New Request" width="120" height="23" border="0" /></a> </div>
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
  <p>&nbsp;</p>
</div>




 
<?php require('../includes/footer.php'); ?>

 </body>
</html>
<?php // require(CLIENTINC_DIR.'footer.inc.php'); ?>

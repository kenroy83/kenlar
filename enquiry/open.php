<?php
/*********************************************************************
    open.php

    New tickets handle.

    
**********************************************************************/
include('client.inc.php');
define('SOURCE','Web'); //Ticket source.
$inc='open.inc.php';    //default include.
$errors=array();
if($_POST):
    $_POST['deptId']=$_POST['emailId']=0; //Just Making sure we don't accept crap...only topicId is expected.
    //Ticket::create...checks for errors..
    if(($ticket=Ticket::create($_POST,$errors,SOURCE))){
        $msg='Support ticket request created';
        if($thisclient && $thisclient->isValid()) //Logged in...simply view the newly created ticket.
            @header('Location: view.php?id='.$ticket->getExtId());
        //Thank the user and promise speedy resolution!
        $inc='thankyou.inc.php';
    }else{
        $errors['err']=$errors['err']?$errors['err']:'Unable to create a ticket. Please correct errors below and try again!';
    }
endif;

//page
require(CLIENTINC_DIR.'header.inc.php');
require(CLIENTINC_DIR.$inc);
//require(CLIENTINC_DIR.'footer.inc.php');
require('../includes/footer.php');
echo '</div>
 </body>
</html>';
?>

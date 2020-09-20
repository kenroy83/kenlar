<?php $IEM = $tpl->Get('IEM'); ?><table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr>
		<td class="Heading1">
			<?php print GetLang('Send_Resend'); ?>
		</td>
	</tr>
	<tr>
		<td class="body pageinfo">
			<p>
				<?php if(isset($GLOBALS['Send_ResendCount'])) print $GLOBALS['Send_ResendCount']; ?>
				<?php print GetLang('Send_Resend_Intro'); ?>
			</p>
			<ul style="margin-bottom:0px">
				<li><?php if(isset($GLOBALS['Send_NewsletterName'])) print $GLOBALS['Send_NewsletterName']; ?></li>
				<li><?php if(isset($GLOBALS['Send_NewsletterSubject'])) print $GLOBALS['Send_NewsletterSubject']; ?></li>
				<li><?php if(isset($GLOBALS['Send_SubscriberList'])) print $GLOBALS['Send_SubscriberList']; ?></li>
				<li><?php if(isset($GLOBALS['Send_TotalRecipients'])) print $GLOBALS['Send_TotalRecipients']; ?></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="body">
			<br/>
			<input type="button" value="<?php print GetLang('StartSending'); ?>" class="SmallButton" onclick="javascript: PopupWindow();">&nbsp;<input type="button" value="<?php print GetLang('Cancel'); ?>" class="FormButton" onclick="document.location='index.php?Page=Newsletters';">
		</td>
	</tr>
</table>
<script>
	function PopupWindow() {
		var top = screen.height / 2 - (170);
		var left = screen.width / 2 - (140);

		window.open("index.php?Page=Send&Action=Send&Job=<?php if(isset($GLOBALS['JobID'])) print $GLOBALS['JobID']; ?>&Resend","sendWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=360,height=200");
	}
</script>





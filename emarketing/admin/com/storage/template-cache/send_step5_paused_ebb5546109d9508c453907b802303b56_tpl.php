<?php $IEM = $tpl->Get('IEM'); ?><table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr>
		<td class="Heading1">
			<?php print GetLang('Send_Paused_Heading'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<input type="button" value="<?php print GetLang('OK'); ?>" onclick="javascript: document.location='index.php?Page=Newsletters';" class="FormButton">
		</td>
	</tr>
</table>





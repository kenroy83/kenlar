<?php $IEM = $tpl->Get('IEM'); ?>	<table border='0' cellspacing='0' cellpadding='0' width="95%" align="center">
		<tr>
			<td class='Message' width='20'><img src='images/error.gif' width='18' height='18' hspace='10'></td><td class='Message' width='100%'><?php if(isset($GLOBALS['ErrorMessage'])) print $GLOBALS['ErrorMessage']; ?></td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<input type="button" class="FormButton" value="<?php print GetLang('GoBack'); ?>" onclick="javascript: history.go(-1);" class="Button">
			</td>
		</tr>
	</table>
	<br/>





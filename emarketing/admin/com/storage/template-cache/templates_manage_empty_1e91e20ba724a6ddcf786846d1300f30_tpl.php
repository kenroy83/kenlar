<?php $IEM = $tpl->Get('IEM'); ?><table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr>
		<td class="Heading1"><?php print GetLang('TemplatesManage'); ?></td>
	</tr>
	<tr>
		<td class="body pageinfo"><p><?php print GetLang('Help_TemplatesManage'); ?></p></td>
	</tr>
	<tr>
		<td class="body">
			<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
		</td>
	</tr>
	<tr>
		<td class="body">
			<?php if(isset($GLOBALS['Templates_AddButton'])) print $GLOBALS['Templates_AddButton']; ?>
		</td>
	</tr>
</table>






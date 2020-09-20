<?php $IEM = $tpl->Get('IEM'); ?><tr class="GridRow">
	<td valign="top" align="center">
		<input type="checkbox" name="templates[]" value="<?php if(isset($GLOBALS['id'])) print $GLOBALS['id']; ?>">
	</td>
	<td>
		<img src="images/m_templates.gif">
	</td>
	<td>
		<?php if(isset($GLOBALS['Name'])) print $GLOBALS['Name']; ?>
	</td>
	<td>
		<?php if(isset($GLOBALS['Created'])) print $GLOBALS['Created']; ?>
	</td>
	<td>
		<?php if(isset($GLOBALS['Format'])) print $GLOBALS['Format']; ?>
	</td>
	<td align="center">
		<?php if(isset($GLOBALS['ActiveAction'])) print $GLOBALS['ActiveAction']; ?>
	</td>
	<td align="center">
		<?php if(isset($GLOBALS['IsGlobalAction'])) print $GLOBALS['IsGlobalAction']; ?>
	</td>
	<td>
		<?php if(isset($GLOBALS['TemplateAction'])) print $GLOBALS['TemplateAction']; ?>
	</td>
</tr>





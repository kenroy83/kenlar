<?php $IEM = $tpl->Get('IEM'); ?>	<tr>
		<td colspan="2" class="EmptyRow">
			&nbsp;
		</td>
	</tr>
<tr>
	<td colspan="2" class="Heading2">
		&nbsp;&nbsp;<?php print GetLang('VisibleFields'); ?>
	</td>
</tr>
<tr>
	<td class="FieldLabel">
		<?php print GetLang('ShowTheseFields'); ?>:
	</td>
	<td>
		<select id="fields" name="VisibleFields[]" multiple="multiple" class="ISSelectReplacement">
			<?php if(isset($GLOBALS['VisibleFields'])) print $GLOBALS['VisibleFields']; ?>
		</select>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('VisibleFields')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_VisibleFields')); ?></span></span>
	</td>
</tr>




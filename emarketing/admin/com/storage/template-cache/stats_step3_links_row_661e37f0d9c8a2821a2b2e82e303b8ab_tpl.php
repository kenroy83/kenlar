<?php $IEM = $tpl->Get('IEM'); ?><tr class="GridRow">
	<td nowrap height="22" align="left">
		&nbsp;<?php if(isset($GLOBALS['EmailAddress'])) print $GLOBALS['EmailAddress']; ?>
	</td>
	<td nowrap align="left">
		<a title="<?php if(isset($GLOBALS['FullURL'])) print $GLOBALS['FullURL']; ?>" href="<?php if(isset($GLOBALS['FullURL'])) print $GLOBALS['FullURL']; ?>" target="_blank"><?php if(isset($GLOBALS['LinkClicked'])) print $GLOBALS['LinkClicked']; ?></a>
	</td>
	<td nowrap align="left">
		<?php if(isset($GLOBALS['DateClicked'])) print $GLOBALS['DateClicked']; ?>
	</td>
</tr>





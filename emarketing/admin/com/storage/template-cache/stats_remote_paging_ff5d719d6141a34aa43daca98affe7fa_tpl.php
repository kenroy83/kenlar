<?php $IEM = $tpl->Get('IEM'); ?><div align="right">
	<table border=0 cellspacing=0 cellpadding=0><tr><td class=body><?php print GetLang('ResultsPerPage'); ?>:&nbsp;</td>
	<td><select name="PerPageDisplay<?php if(isset($GLOBALS['PPDisplayName'])) print $GLOBALS['PPDisplayName']; ?>" id="PerPageDisplay<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>" style="margin-bottom: 4px; width: 55px" onChange="REMOTE_parameters = '&amp;PerPageDisplay=' + $('#PerPageDisplay<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>').val();REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['TableToken'])) print $GLOBALS['TableToken']; ?>',1,'<?php if(isset($GLOBALS['SortColumn'])) print $GLOBALS['SortColumn']; ?>','<?php if(isset($GLOBALS['SortDirection'])) print $GLOBALS['SortDirection']; ?>');"><?php if(isset($GLOBALS['PerPageDisplayOptions'])) print $GLOBALS['PerPageDisplayOptions']; ?></select>&nbsp;</td></tr></table>
</div>
<div align=right class="Text" style="padding-bottom:0px"><?php if(isset($GLOBALS['DisplayPage'])) print $GLOBALS['DisplayPage']; ?></div>





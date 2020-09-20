<?php $IEM = $tpl->Get('IEM'); ?><script>
	$(function() {
		if($('#GlobalMessage').html() == '')
			$('#GlobalMessageContainer').hide();
	});
</script>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr>
		<td class="Heading1"><?php print GetLang('TemplatesManage'); ?></td>
	</tr>
	<tr>
		<td class="body pageinfo"><?php print GetLang('Help_TemplatesManage'); ?><?php if(isset($GLOBALS['CreateTemplatePreview'])) print $GLOBALS['CreateTemplatePreview']; ?></td>
	</tr>
	<tr id="GlobalMessageContainer">
		<td id="GlobalMessage"><?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?></td>
	</tr>
	<tr>
		<td class=body>
			<table width="100%" border="0">
				<tr>
					<td valign="bottom">
						<div style="padding-top:10px; padding-bottom:10px">
							<?php if(isset($GLOBALS['Templates_AddButton'])) print $GLOBALS['Templates_AddButton']; ?>
						</div>
						<form name="ActionTemplatesForm" method="post" action="index.php?Page=Templates&Action=Change" onsubmit="return ConfirmChanges();" style="margin: 0px; padding: 0px;">
							<select name="ChangeType">
								<option value="" SELECTED><?php print GetLang('ChooseAction'); ?></option>
								<?php if(isset($GLOBALS['Option_DeleteTemplate'])) print $GLOBALS['Option_DeleteTemplate']; ?>
								<?php if(isset($GLOBALS['Option_ActivateTemplate'])) print $GLOBALS['Option_ActivateTemplate']; ?>
								<?php if(isset($GLOBALS['Option_GlobalTemplate'])) print $GLOBALS['Option_GlobalTemplate']; ?>
							</select>
							<input type="submit" value="<?php print GetLang('Go'); ?>" class="Text">
					</td>
					<td align="right" valign="bottom">
						%%TPL_Paging%%
					</td>
				</tr>
			</table>
			<table border=0 cellspacing="0" cellpadding="0" width=100% class="Text">
				<tr class="Heading3">
					<td width="5" nowrap align="center">
						<input type="checkbox" name="toggle" onClick="javascript: toggleAllCheckboxes(this);">
					</td>
					<td width="5">&nbsp;</td>
					<td width="55%">
						<?php print GetLang('TemplateName'); ?>&nbsp;<a href='index.php?Page=<?php print $IEM['CurrentPage']; ?>&SortBy=Name&Direction=Up&<?php if(isset($GLOBALS['SearchDetails'])) print $GLOBALS['SearchDetails']; ?>'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=<?php print $IEM['CurrentPage']; ?>&SortBy=Name&Direction=Down&<?php if(isset($GLOBALS['SearchDetails'])) print $GLOBALS['SearchDetails']; ?>'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="20%">
						<?php print GetLang('Created'); ?>&nbsp;<a href='index.php?Page=<?php print $IEM['CurrentPage']; ?>&SortBy=Date&Direction=Up&<?php if(isset($GLOBALS['SearchDetails'])) print $GLOBALS['SearchDetails']; ?>'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=<?php print $IEM['CurrentPage']; ?>&SortBy=Date&Direction=Down&<?php if(isset($GLOBALS['SearchDetails'])) print $GLOBALS['SearchDetails']; ?>'><img src="images/sortdown.gif" border=0></a>
					</td>
					<td width="15%">
						<?php print GetLang('Format'); ?>
					</td>
					<td width="5%" align="center"><?php print GetLang('Active'); ?></td>
					<td width="5%" align="center"><?php print GetLang('Global'); ?></td>
					<td width="180">
						<?php print GetLang('Action'); ?>
					</td>
				</tr>
			%%TPL_Templates_Manage_Row%%
			</table>
			%%TPL_Paging_Bottom%%
		</td>
	</tr>
</table>

<script>
	function ConfirmChanges() {
		formObj = document.ActionTemplatesForm;

		if (formObj.ChangeType.selectedIndex == 0) {
			alert("<?php print GetLang('PleaseChooseAction'); ?>");
			formObj.ChangeType.focus();
			return false;
		}

		selectedValue = formObj.ChangeType[formObj.ChangeType.selectedIndex].value;

		templates_found = false;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					templates_found = true;
					break;
				}
			}
		}

		if (!templates_found) {
			alert("<?php print GetLang('ChooseTemplates'); ?>");
			return false;
		}

		if (confirm("<?php print GetLang('ConfirmChanges'); ?>")) {
			return true;
		}

		return false;
	}

	function ConfirmDelete(TemplateID) {
		if (!TemplateID) {
			return false;
		}
		if (confirm("<?php print GetLang('DeleteTemplatePrompt'); ?>")) {
			document.location='index.php?Page=<?php print $IEM['CurrentPage']; ?>&Action=Delete&id=' + TemplateID;
		}
	}
</script>





<?php $IEM = $tpl->Get('IEM'); ?><script src="includes/js/jquery/thickbox.js"></script>
<link rel="stylesheet" href="includes/styles/thickbox.css" type="text/css">
<link rel="stylesheet" type="text/css" href="includes/styles/tabs.css" />
<form method="post" action="index.php?Page=Templates&Action=<?php if(isset($GLOBALS['Action'])) print $GLOBALS['Action']; ?>" onsubmit="return CheckForm()" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1">
				<?php if(isset($GLOBALS['Heading'])) print $GLOBALS['Heading']; ?>
			</td>
		</tr>
		<tr>
			<td class="body pageinfo">
				<?php if(isset($GLOBALS['Intro'])) print $GLOBALS['Intro']; ?>
				<br /><br /><?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<input class="FormButton" type="button" value="<?php print GetLang('Save'); ?>" onclick='Save();'>
				<input class="FormButton_wide" type="submit" value="<?php print GetLang('SaveAndExit'); ?>">
				<input class="FormButton" type="button" value="<?php print GetLang('Cancel'); ?>" onClick='if(confirm("<?php if(isset($GLOBALS['CancelButton'])) print $GLOBALS['CancelButton']; ?>")) { document.location="index.php?Page=Templates" }'>
				<br />
				<br />
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('EditTemplateHeading'); ?>
						</td>
					</tr>
					<?php if(isset($GLOBALS['Editor'])) print $GLOBALS['Editor']; ?>
					<tr>
						<td colspan="2" class="EmptyRow"></td>
					</tr>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('EmailValidation'); ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel" width="150">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('EmailClientCompatibility'); ?>:
						</td>
						<td>
							<input type="button" class="Field250" value="<?php print GetLang('EmailClientCompatibility_Button'); ?>" onclick="javascript: ViewCompatibility();">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow"></td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowMiscOptions'])) print $GLOBALS['ShowMiscOptions']; ?>">
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('MiscellaneousOptions'); ?>
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowActive'])) print $GLOBALS['ShowActive']; ?>">
						<td class="FieldLabel" width="150">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('TemplateIsActive'); ?>:
						</td>
						<td>
							<label for="active">
							<input type="checkbox" name="active" id="active" value="1"<?php if(isset($GLOBALS['IsActive'])) print $GLOBALS['IsActive']; ?>>
							<?php print GetLang('TemplateIsActiveExplain'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TemplateIsActive')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TemplateIsActive')); ?></span></span>
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowGlobal'])) print $GLOBALS['ShowGlobal']; ?>">
						<td class="FieldLabel" width="150">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('TemplateIsGlobal'); ?>:
						</td>
						<td>
							<label for="isglobal">
							<input type="checkbox" name="isglobal" id="isglobal" value="1" />
							<?php print GetLang('TemplateIsGlobalExplain'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TemplateIsGlobal')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TemplateIsGlobal')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td width="10%">
							<img src="images/blank.gif" width="220" height="1" />
						</td>
						<td height="35" width="90%">
							<input class="FormButton" type="button" value="<?php print GetLang('Save'); ?>" onclick='Save();'>
							<input class="FormButton_wide" type="submit" value="<?php print GetLang('SaveAndExit'); ?>">
							<input class="FormButton" type="button" value="<?php print GetLang('Cancel'); ?>" onClick='if(confirm("<?php if(isset($GLOBALS['CancelButton'])) print $GLOBALS['CancelButton']; ?>")) { document.location="index.php?Page=Templates" }'>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script>

	var f = document.forms[0];

	function CheckForm() {
		return true;
	}

	function Save() {
		if (CheckForm()) {
			f.action = 'index.php?Page=Templates&Action=<?php if(isset($GLOBALS['SaveAction'])) print $GLOBALS['SaveAction']; ?>';
			f.submit();
		}
	}

	function ViewCompatibility() {
		f.target = "_blank";

		prevAction = f.action;
		f.action = "index.php?Page=Templates&Action=ViewCompatibility&ShowBroken=1";
		f.submit();

		f.target = "";
		f.action = prevAction;
	}

</script>





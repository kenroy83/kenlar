<?php $IEM = $tpl->Get('IEM'); ?><form method="post" action="index.php?Page=Subscribers&Action=Export&SubAction=Step2" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1">
				<?php print GetLang('Subscribers_Export'); ?>
			</td>
		</tr>
		<tr>
			<td class="body pageinfo">
				<p>
					<?php print GetLang('Subscribers_Export_Intro'); ?>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<input class="FormButton" type="submit" value="<?php print GetLang('Next'); ?>">
				<input class="FormButton" type="button" value="<?php print GetLang('Cancel'); ?>" onClick='if(confirm("<?php print GetLang('Subscribers_Export_CancelPrompt'); ?>")) { document.location="index.php?Page=Subscribers" }'>
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel" <?php if(isset($GLOBALS['FilterOptions'])) print $GLOBALS['FilterOptions']; ?>>
					<tr <?php if(isset($GLOBALS['FilterOptions'])) print $GLOBALS['FilterOptions']; ?>>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('FilterOptions_Subscribers'); ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ShowFilteringOptionsLabel_Export'); ?>&nbsp;
						</td>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="350px;">
										<label for="DoNotShowFilteringOptions"><input type="radio" name="ShowFilteringOptions" id="DoNotShowFilteringOptions" value="2" checked="checked" /><?php print GetLang('SubscribersExportDoNotShowFilteringOptionsExplain'); ?></label>
									</td>
									<td>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ShowFilteringOptions')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ShowFilteringOptions')); ?></span></span>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="ShowFilteringOptions"><input type="radio" name="ShowFilteringOptions" id="ShowFilteringOptions" value="1" /><?php print GetLang('SubscribersExportShowFilteringOptionsExplain'); ?></label>
									</td>
								</tr>
							</table>
						</td>

					</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel" id="FilteringOptions" <?php if(isset($GLOBALS['FilteringOptions_Display'])) print $GLOBALS['FilteringOptions_Display']; ?>>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('MailingListDetails'); ?>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('MailingList'); ?>:&nbsp;
						</td>
						<td>
							<select id="lists" name="lists[]" multiple="multiple" class="ISSelectReplacement ISSelectSearch" onDblClick="this.form.submit()">
								<?php if(isset($GLOBALS['SelectList'])) print $GLOBALS['SelectList']; ?>
							</select>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MailingList')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MailingList')); ?></span></span>
						</td>
					</tr>
				</table>
				<table width="100%" cellspacing="0" cellpadding="2" border="0" class="PanelPlain">
					<tr>
						<td width="200" class="FieldLabel">&nbsp;</td>
						<td valign="top" height="30">
							<input class="FormButton" type="submit" value="<?php print GetLang('Next'); ?>">
							<input class="FormButton" type="button" value="<?php print GetLang('Cancel'); ?>" onClick='if(confirm("<?php print GetLang('Subscribers_Export_CancelPrompt'); ?>")) { document.location="index.php?Page=Subscribers" }'>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script>
	function CheckForm() {
		var f = document.forms[0];
		var listbox = document.getElementById('lists');
		if (listbox.selectedIndex < 0) {
			alert("<?php print GetLang('SelectList'); ?>");
			listbox.focus();
			return false;
		}
		return true;
	}
</script>




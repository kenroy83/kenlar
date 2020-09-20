<?php $IEM = $tpl->Get('IEM'); ?><script>
	function ImportCheck(importtype) {
		if (importtype.toLowerCase() == 'html') {
			var checker = document.getElementById('newsletterurl');
		} else {
			var checker = document.getElementById('textnewsletterurl');
		}

		if (checker.value.length <= 7) {
			alert('<?php print GetLang('Editor_ChooseWebsiteToImport'); ?>');
			checker.focus();
			return false;
		}
		return true;
	}

	$(function() { if(<?php if(isset($GLOBALS['UsingWYSIWYG'])) print $GLOBALS['UsingWYSIWYG']; ?> == 0) UsingWYSIWYG = false; });
</script>
	<tr>
		<td colspan="2">
			<table id="tabContents_HTMLEditor" width="100%">
				<tr>
					<td valign="top" width="150" class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('HTMLContent'); ?>:
					</td>
					<td valign="top">
						<table width="<?php if(isset($GLOBALS['EditorWidth'])) print $GLOBALS['EditorWidth']; ?>" border="0" class="WISIWYG_Editor_Choices">
							<tr>
								<td width="20">
									<input onClick="switchContentSource('html', 1)" id="hct1" name="hct" type="radio" checked>
								</td>
								<td>
									<label for="hct1"><?php print GetLang('HTML_Using_Editor'); ?></label>
								</td>
							</tr>
							<tr>
								<td width="20">
									<input onClick="switchContentSource('html', 2)" id="hct2" name="hct" type="radio">
								</td>
								<td>
									<label for="hct2"><?php print GetLang('Editor_Upload_File'); ?></label>
								</td>
							</tr>
							<tr id="htmlNLFile" style="display:none">
								<td></td>
								<td>
									<img src="images/nodejoin.gif" alt="." align="top" />
									<iframe src="<?php if(isset($GLOBALS['APPLICATION_URL'])) print $GLOBALS['APPLICATION_URL']; ?>/admin/functions/remote.php?DisplayFileUpload&ImportType=HTML" frameborder="0" height="30" width="500" id="newsletterfile"></iframe>
								</td>
							</tr>
							<tr>
								<td width="20">
									<input onClick="switchContentSource('html', 3)" id="hct3" name="hct" type="radio">
								</td>
								<td>
									<label for="hct3"><?php print GetLang('Editor_Import_Website'); ?></label>
								</td>
							</tr>
							<tr id="htmlNLImport" style="display:none">
								<td></td>
								<td>
									<img src="images/nodejoin.gif" alt="." />
									<input type="text" name="newsletterurl" id="newsletterurl" value="http://" class="Field" style="width:200px">
									<input class="FormButton" type="button" name="upload" value="<?php print GetLang('ImportWebsite'); ?>" onclick="ImportWebsite(this, '<?php print GetLang('Editor_Import_Website_Wait'); ?>', 'html', '<?php print GetLang('Editor_ImportButton'); ?>', '<?php print GetLang('Editor_ProblemImportingWebsite'); ?>')" class="Field" style="width:60px">
								</td>
							</tr>
						</table>
						<div id="htmlEditor" style="padding-top: 5px;">
							<?php if(isset($GLOBALS['HTMLContent'])) print $GLOBALS['HTMLContent']; ?>
						</div>
					</td>
				</tr>
				<tr id="htmlCF">
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button" onclick="javascript: ShowCustomFields('html', 'myDevEditControl', '<?php print $IEM['CurrentPage']; ?>'); return false;" value="<?php print GetLang('ShowCustomFields'); ?>..." class="FormButton" style="width: 150px;" />
						<input type="button" onclick="javascript: InsertUnsubscribeLink('html'); return false;" value="<?php print GetLang('InsertUnsubscribeLink'); ?>" class="FormButton" style="width: 150px;" />
					</td>
				</tr>
			</table>
			<table id="tabContents_TextEditor" width="100%">
				<tr>
					<td valign="top" width="150" class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('TextContent'); ?>:
					</td>
					<td valign="top">
						<table width="100%" border="0" class="WISIWYG_Editor_Choices">
							<tr>
								<td width="20">
									<input onClick="switchContentSource('text', 1)" id="tct1" name="tct" type="radio" checked>
								</td>
								<td>
									<label for="tct1"><?php print GetLang('Text_Type'); ?></label>
								</td>
							</tr>
							<tr>
								<td width="20">
									<input onClick="switchContentSource('text', 2)" id="tct2" name="tct" type="radio">
								</td>
								<td>
									<label for="tct2"><?php print GetLang('Editor_Upload_File'); ?></label>
								</td>
							</tr>
							<tr id="textNLFile" style="display:none">
								<td></td>
								<td>
									<img src="images/nodejoin.gif" alt="." align="top" />
									<iframe src="<?php if(isset($GLOBALS['APPLICATION_URL'])) print $GLOBALS['APPLICATION_URL']; ?>/admin/functions/remote.php?DisplayFileUpload&ImportType=Text" frameborder="0" height="30" width="500" id="newsletterfile"></iframe>
								</td>
							</tr>
							<tr>
								<td width="20">
									<input onClick="switchContentSource('text', 3)" id="tct3" name="tct" type="radio">
								</td>
								<td>
									<label for="tct3"><?php print GetLang('Editor_Import_Website'); ?></label>
								</td>
							</tr>
							<tr id="textNLImport" style="display:none">
								<td></td>
								<td>
									<img src="images/nodejoin.gif" alt="." />
									<input type="text" name="textnewsletterurl" id="textnewsletterurl" value="http://" class="Field" style="width:200px">
									<input class="FormButton" type="button" name="upload" value="<?php print GetLang('ImportWebsite'); ?>" onclick="ImportWebsite(this, '<?php print GetLang('Editor_Import_Website_Wait'); ?>', 'text');" class="Field" style="width:60px">
								</td>
							</tr>
						</table>
						<div id="textEditor" style="padding-top: 5px;">
							<textarea name="TextContent" id="TextContent" rows="10" cols="60" class="ContentsTextEditor"><?php if(isset($GLOBALS['TextContent'])) print $GLOBALS['TextContent']; ?></textarea>
							<div class="aside"><?php print GetLang('TextWidthLimit_Explaination'); ?></div>
						</div>
					</td>
				</tr>
				<tr id="textCF">
					<td>
						&nbsp;
					</td>
					<td>
						<input type="button" onclick="javascript: ShowCustomFields('TextContent', null, '<?php print $IEM['CurrentPage']; ?>'); return false;" value="<?php print GetLang('ShowCustomFields'); ?>..." class="FormButton" style="width: 150px;" />
						<input type="button" onclick="javascript: InsertUnsubscribeLink('TextContent'); return false;" value="<?php print GetLang('InsertUnsubscribeLink'); ?>" class="FormButton" style="width: 150px;" />
						<input class="FormButton" type="button" name="" value="<?php print GetLang('GetTextContent'); ?>" style="width:170px" onClick="grabTextContent('TextContent','myDevEditControl');"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>




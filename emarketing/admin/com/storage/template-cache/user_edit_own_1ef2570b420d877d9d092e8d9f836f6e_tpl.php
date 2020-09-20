<?php $IEM = $tpl->Get('IEM'); ?><script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<script>
	$(function() {
		$(document.settings).submit(function() {
			if (this.ss_p.value != "") {
				if (this.ss_p_confirm.value == "") {
					alert("<?php print GetLang('PasswordConfirmAlert'); ?>");
					this.ss_p_confirm.focus();
					return false;
				}
				if (this.ss_p.value != this.ss_p_confirm.value) {
					alert("<?php print GetLang('PasswordsDontMatch'); ?>");
					this.ss_p_confirm.select();
					this.ss_p_confirm.focus();
					return false;
				}
			}
			return true;
		});

		$('.CancelButton', document.settings).click(function() { if(confirm('Are you sure you want to cancel?')) document.location.href='index.php?Page=ManageAccount'; });

		$('#usewysiwyg').click(function() { $('#sectionUseXHTML').toggle(); });

		$(document.settings.smtptype).click(function() {
			$('.SMTPDetails')[document.settings.smtptype[1].checked? 'show' : 'hide']();
			if(document.settings.smtptype[2]) $('.sectionSignuptoSMTP')[document.settings.smtptype[2].checked? 'show' : 'hide']();
		});

		$(document.settings.cmdTestSMTP).click(function() {
			var f = document.forms[0];
			if (f.smtp_server.value == '') {
				alert("<?php print GetLang('EnterSMTPServer'); ?>");
				f.smtp_server.focus();
				return false;
			}

			if (f.smtp_test.value == '') {
				alert("<?php print GetLang('EnterTestEmail'); ?>");
				f.smtp_test.focus();
				return false;
			}

			tb_show('<?php print GetLang('SendPreview'); ?>', 'index.php?Page=ManageAccount&Action=SendPreviewDisplay&keepThis=true&TB_iframe=tue&height=250&width=420', '');
			return true;
		});

		document.settings.smtptype[0].checked = !(document.settings.smtptype[1].checked = (document.settings.smtp_server.value != ''));

		if('<?php if(isset($GLOBALS['ShowSMTPInfo'])) print $GLOBALS['ShowSMTPInfo']; ?>' != 'none') {
			$('.SMTPDetails')[document.settings.smtptype[1].checked? 'show' : 'hide']();
			if(document.settings.smtptype[2]) $('.sectionSignuptoSMTP')[document.settings.smtptype[2].checked? 'show' : 'hide']();
		}
	});

	function getSMTPPreviewParameters() {
		var values = {};
		$($('.smtpSettings', document.settings).fieldSerialize().split('&')).each(function(i,n) {
			var temp = n.split('=');
			if(temp.length == 2) values[temp[0]] = temp[1];
		});
		return values;
	}

	function closePopup() {
		tb_remove();
	}


	$(document).ready(function(){
		$('#cmdTestGoogleCalendar').click(function() {
			if ($('#googlecalendarusername').val() == '') {
				alert('<?php print GetLang('EnterGoogleCalendarUsername'); ?>');
				$('#googlecalendarusername').focus();
				return false;
			} else if ($('#googlecalendarpassword').val() == '') {
				alert('<?php print GetLang('EnterGoogleCalendarPassword'); ?>');
				$('#googlecalendarpassword').focus();
				return false;
			}
			params = '&gcusername=' + escape($('#googlecalendarusername').val()) + '&gcpassword=' + escape($('#googlecalendarpassword').val());

			$('#spanTestGoogleCalendar').show();
			$(this).attr('disabled', true);

			$.ajax({	type:		'GET',
						url:		'index.php',
						data:		{	Page: 		'ManageAccount',
										Action:		'TestGoogleCalendar',
										gcusername:	escape($('#googlecalendarusername').val()),
										gcpassword:	escape($('#googlecalendarpassword').val())},
						timeout:	10000,
						success:	function(data) {
										try {
											var d = eval('(' + data + ')');
											alert(d.message);
										} catch(e) { alert('<?php echo GetLang('GooglecalendarTestError'); ?>'); }
									},
						error:		function() { alert('<?php echo GetLang('GooglecalendarTestError'); ?>'); },
						complete:	function() {
										$('#spanTestGoogleCalendar').hide();
										$('#cmdTestGoogleCalendar').attr('disabled', false);
									}});

			return false;
		});
	});
</script>
<form name="settings" method="post" action="index.php?Page=<?php print $IEM['CurrentPage']; ?>&<?php if(isset($GLOBALS['FormAction'])) print $GLOBALS['FormAction']; ?>">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1"><?php print GetLang('MyAccount'); ?></td>
		</tr>
		<tr>
			<td class="body pageinfo"><p><?php print GetLang('Help_MyAccount'); ?></p></td>
		</tr>
		<tr>
			<td>
				<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			</td>
		</tr>
		<tr>
			<td class=body>
				<input class="FormButton" type="submit" value="<?php print GetLang('Save'); ?>">
				<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>">
			</td>
		</tr>
		<tr>
			<td><br>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					<tr><td class=Heading2 colspan=2><?php print GetLang('UserDetails'); ?></td></tr>
					<tr>
						<td class="FieldLabel" width="10%">
							<img src="images/blank.gif" width="200" height="1" /><br />
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('UserName'); ?>:
						</td>
						<td width="90%">
							<?php if(isset($GLOBALS['UserName'])) print $GLOBALS['UserName']; ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('Password'); ?>:
						</td>
						<td>
							<input type="password" name="ss_p" value="" class="Field250" autocomplete="off" />
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('PasswordConfirm'); ?>:
						</td>
						<td>
							<input type="password" name="ss_p_confirm" value="" class="Field250" autocomplete="off" />
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('FullName'); ?>:
						</td>
						<td>
							<input type="text" name="fullname" value="<?php if(isset($GLOBALS['FullName'])) print $GLOBALS['FullName']; ?>" class="Field250">
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('EmailAddress'); ?>:
						</td>
						<td>
							<input type="text" name="emailaddress" value="<?php if(isset($GLOBALS['EmailAddress'])) print $GLOBALS['EmailAddress']; ?>" class="Field250">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailAddress')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('TimeZone'); ?>:
						</td>
						<td>
							<select name="usertimezone">
								<?php if(isset($GLOBALS['TimeZoneList'])) print $GLOBALS['TimeZoneList']; ?>
							</select>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TimeZone')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TimeZone')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ShowInfoTips'); ?>:
						</td>
						<td>
							<label for="infotips"><input type="checkbox" id="infotips" name="infotips" value="1"<?php if(isset($GLOBALS['InfoTipsChecked'])) print $GLOBALS['InfoTipsChecked']; ?>> <?php print GetLang('YesShowInfoTips'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ShowInfoTips')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ShowInfoTips')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('UseWysiwygEditor'); ?>:
						</td>
						<td>
							<div>
								<label for="usewysiwyg">
									<input type="checkbox" name="usewysiwyg" id="usewysiwyg" value="1"<?php if(isset($GLOBALS['UseWysiwyg'])) print $GLOBALS['UseWysiwyg']; ?> />
									<?php print GetLang('YesUseWysiwygEditor'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseWysiwygEditor')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseWysiwygEditor')); ?></span></span>
							</div>
							<div id="sectionUseXHTML"<?php if(isset($GLOBALS['UseXHTMLDisplay'])) print $GLOBALS['UseXHTMLDisplay']; ?>>
								<img width="20" height="20" src="images/nodejoin.gif"/>
								<label for="usexhtml">
									<input type="checkbox" name="usexhtml" id="usexhtml" value="1"<?php if(isset($GLOBALS['UseXHTMLCheckbox'])) print $GLOBALS['UseXHTMLCheckbox']; ?> />
									<?php print GetLang('YesUseXHTML'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseWysiwygXHTML')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseWysiwygXHTML')); ?></span></span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php echo GetLang('EnableActivityLog'); ?>:
						</td>
						<td>
							<label for="enableactivitylog"><input type="checkbox" name="enableactivitylog" id="enableactivitylog" value="1" <?php if(isset($GLOBALS['EnableActivityLog'])) print $GLOBALS['EnableActivityLog']; ?>> <?php echo GetLang('YesEnableActivityLog'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EnableActivityLog')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EnableActivityLog')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('HTMLFooter'); ?>:
						</td>
						<td>
							<textarea name="htmlfooter" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['HTMLFooter'])) print $GLOBALS['HTMLFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('HTMLFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_HTMLFooter')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('TextFooter'); ?>:
						</td>
						<td>
							<textarea name="textfooter" rows="3" cols="28" wrap="virtual"><?php if(isset($GLOBALS['TextFooter'])) print $GLOBALS['TextFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TextFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TextFooter')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php echo GetLang('EventTypeList'); ?>:
						</td>
						<td>
							<textarea name="eventactivitytype" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['EventActivityType'])) print $GLOBALS['EventActivityType']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EventTypeList')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EventTypeList')); ?></span></span>
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowSMTPInfo'])) print $GLOBALS['ShowSMTPInfo']; ?>">
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowSMTPInfo'])) print $GLOBALS['ShowSMTPInfo']; ?>">
						<td colspan="2" class="Heading2">
							<?php print GetLang('SmtpServerIntro'); ?>
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowSMTPInfo'])) print $GLOBALS['ShowSMTPInfo']; ?>">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SmtpServer'); ?>:
						</td>
						<td>
							<label for="usedefaultsmtp">
								<input type="radio" name="smtptype" id="usedefaultsmtp" value="0"/>
								<?php print GetLang('SmtpDefault'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseDefaultMail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseDefaultMail')); ?></span></span>
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowSMTPInfo'])) print $GLOBALS['ShowSMTPInfo']; ?>">
						<td class="FieldLabel">&nbsp;</td>
						<td>
							<label for="usecustomsmtp">
								<input type="radio" name="smtptype" id="usecustomsmtp" value="1"/>
								<?php print GetLang('SmtpCustom'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTP_User')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTP_User')); ?></span></span>
						</td>
					<tr style="display:none" class="SMTPDetails">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SmtpServerName'); ?>:
						</td>
						<td>
							<img src="images/nodejoin.gif" width="20" height="20">
							<input type="text" name="smtp_server" value="<?php if(isset($GLOBALS['SmtpServer'])) print $GLOBALS['SmtpServer']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerName')); ?></span></span>
						</td>
					</tr>
					<tr style="display:none" class="SMTPDetails">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SmtpServerUsername'); ?>:
						</td>
						<td>
							<img src="images/blank.gif" width="20" height="20">
							<input type="text" name="smtp_u" value="<?php if(isset($GLOBALS['SmtpUsername'])) print $GLOBALS['SmtpUsername']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerUsername')); ?></span></span>
						</td>
					</tr>
					<tr style="display:none" class="SMTPDetails">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SmtpServerPassword'); ?>:
						</td>
						<td>
							<img src="images/blank.gif" width="20" height="20">
							<input type="password" name="smtp_p" value="<?php if(isset($GLOBALS['SmtpPassword'])) print $GLOBALS['SmtpPassword']; ?>" class="Field250 smtpSettings" autocomplete="off" /> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPassword')); ?></span></span>
						</td>
					</tr>
					<tr style="display:none" class="SMTPDetails">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SmtpServerPort'); ?>:
						</td>
						<td>
							<img src="images/blank.gif" width="20" height="20">
							<input type="text" name="smtp_port" value="<?php if(isset($GLOBALS['SmtpPort'])) print $GLOBALS['SmtpPort']; ?>" class="field50 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPort')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPort')); ?></span></span>
						</td>
					</tr>
					<tr style="display:none" class="SMTPDetails">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('TestSMTPSettings'); ?>:
						</td>
						<td>
							<img src="images/blank.gif" width="20" height="20">
							<input type="text" name="smtp_test" id="smtp_test" value="" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TestSMTPSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TestSMTPSettings')); ?></span></span>
						</td>
					</tr>
					<tr style="display:none" class="SMTPDetails">
						<td class="FieldLabel">
							&nbsp;
						</td>
						<td>
							<input type="button" name="cmdTestSMTP" value="<?php print GetLang('TestSMTPSettings'); ?>" class="FormButton" style="width: 120px;">
						</td>
					</tr>
					<tr style="display:<?php if(isset($GLOBALS['ShowSMTPCOMOption'])) print $GLOBALS['ShowSMTPCOMOption']; ?>">
						<td class="FieldLabel">&nbsp;</td>
						<td>
							<label for="signtosmtp">
								<input type="radio" name="smtptype" id="signtosmtp" value="2"/>
								<?php print GetLang('SMTPCOM_UseSMTPOption'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTPCOM')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTPCOM')); ?></span></span>
						</td>
					</tr>
					<tr class="sectionSignuptoSMTP" style="display: none;">
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr class="sectionSignuptoSMTP" style="display: none;">
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('SMTPCOM_Header'); ?>
						</td>
					</tr>
					<tr class="sectionSignuptoSMTP" style="display: none;">
						<td colspan="2" style="padding-left: 20px;"><?php print GetLang('SMTPCOM_Explain'); ?></td>
					</tr>

					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>

					<tr><td class=Heading2 colspan=2><?php print GetLang('GoogleCalendarIntro'); ?></td></tr>
					<tr>

						<td class="FieldLabel" width="10%">
							<img src="images/blank.gif" width="200" height="1" /><br />
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('GoogleCalendarUsername'); ?>:
						</td>
						<td width="90%">
							<label for="googlecalendarusername">
								<input type="text" class="Field250 googlecalendar" name="googlecalendarusername" id="googlecalendarusername" value="<?php if(isset($GLOBALS['googlecalendarusername'])) print $GLOBALS['googlecalendarusername']; ?>" autocomplete="off" />
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GoogleCalendarUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GoogleCalendarUsername')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('GoogleCalendarPassword'); ?>:
						</td>
						<td>
							<label for="googlecalendarpassword">
								<input type="password" class="Field250 googlecalendar" name="googlecalendarpassword" id="googlecalendarpassword" value="<?php if(isset($GLOBALS['googlecalendarpassword'])) print $GLOBALS['googlecalendarpassword']; ?>" autocomplete="off" />
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GoogleCalendarPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GoogleCalendarPassword')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="button" id="cmdTestGoogleCalendar" value="<?php print GetLang('TestLogin'); ?>" class="FormButton" />
							<span id="spanTestGoogleCalendar" style="display:none;">&nbsp;&nbsp;<img src="images/searching.gif" alt="wait" /></span>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input class="FormButton" type="submit" value="<?php print GetLang('Save'); ?>">
							<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>





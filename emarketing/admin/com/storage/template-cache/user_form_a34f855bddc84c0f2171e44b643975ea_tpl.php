<?php $IEM = $tpl->Get('IEM'); ?><script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<script>
	$(function() {
		$(document.users).submit(function() {
			if ($('#username').val().length < 3) {
				ShowTab(1);
				alert("<?php print GetLang('EnterUsername'); ?>");
				$('#username').focus();
				return false;
			}

			if ($('#ss_p').val() != "") {
				if ($('#ss_p').val().length < 3) {
					ShowTab(1);
					alert("<?php print GetLang('EnterPassword'); ?>");
					$('#ss_p').focus().select();
					return false;
				}

				if ($('#ss_p').val() != $('#ss_p_confirm').val()) {
					ShowTab(1);
					alert("<?php print GetLang('PasswordsDontMatch'); ?>");
					$('#ss_p_confirm').focus().select();
					return false;
				}
			}

			if ($('#emailaddress').val().indexOf('@') == -1 || $('#emailaddress').val().indexOf('.') == -1) {
				ShowTab(1);
				alert("<?php print GetLang('EnterEmailaddress'); ?>");
				$('#emailaddress').focus().select();
				return false;
			}

			var gu = $('#googlecalendarusername');
			var gp = $('#googlecalendarpassword');
			if ((gu.val() != '' && gp.val() == '') || (gu.val() == '' && gp.val() != '')) {
				if (gu.val() == '') {
					alert('<?php print GetLang('EnterGoogleCalendarUsername'); ?>');
					ShowTab(5);
					gu.focus();
					return false;
				} else if (gp.val() == '') {
					alert('<?php print GetLang('EnterGoogleCalendarPassword'); ?>');
					ShowTab(5);
					gp.focus();
					return false;
				}
			}

			return true;
		});

		$('.CancelButton', document.users).click(function() { if(confirm('<?php print GetLang('ConfirmCancel'); ?>')) document.location.href='index.php?Page=Users'; });

		$(document.users.usewysiwyg).click(function() { $('#sectionUseXHTML')[this.checked? 'show' : 'hide'](); });
		$(document.users.limitmaxlists).click(function() { $('#DisplayMaxLists').toggle(); });
		$(document.users.limitperhour).click(function() { $('#DisplayEmailsPerHour').toggle(); });
		$(document.users.limitpermonth).click(function() { $('#DisplayEmailsPerMonth').toggle(); });
		$(document.users.unlimitedmaxemails).click(function() { $('#DisplayEmailsMaxEmails').toggle(); });

		$(document).ready(function() {
			populatePermBoxes();
		});

		$(document.users.admintype).change(function() {
			populatePermBoxes();
			document.users.user_smtpcom.disabled = !document.users.user_smtp.checked;
			document.users.user_smtpcom.checked = document.users.user_smtp.checked;
		});

		$('.PermissionOptionItems').click(function() {
			calcUserType();
		});

		$(document.users.xmlapi).click(function() {
			$('#sectionXMLToken').toggle();
			if(document.users.xmltoken.value == '') $('#hrefRegenerateXMLToken').click();
		});

		$('.SelectOnFocus').focus(function() { this.select(); });

		$('#hrefRegenerateXMLToken').click(function() {
			$.post('index.php?Page=Users&Action=GenerateToken',
					{	'username':	document.users.username.value,
						'fullname':	document.users.fullname.value,
						'emailaddress': document.users.emailaddress.value},
					function(token) { $("#xmltoken").val(token); });
			return false;
		});

		$(document.users.user_smtp).click(function() {
			document.users.user_smtpcom.disabled = !this.checked;
			document.users.user_smtpcom.checked = this.checked;
			calcUserType();
		});

		$(document.users.listadmintype).change(function() { $('#PrintLists')[this.selectedIndex == 0? 'hide' : 'show'](); });
		$(document.users.segmentadmintype).change(function() { $('#PrintSegments')[this.selectedIndex == 0? 'hide' : 'show'](); });
		$(document.users.templateadmintype).change(function() { $('#PrintTemplates')[this.selectedIndex == 0? 'hide' : 'show'](); });

		$('#subscribers_add, #subscribers_edit, #subscribers_delete').click(function() {
			$('#subscribers_manage').attr('checked', ($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0));
		});

		$('#subscribers_manage').click(function(event) {
			if($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

		$('#segment_create, #segment_edit, #segment_delete, #segment_send').click(function() {
			$('#segment_view').attr('checked', ($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0));
		});

		$('#segment_view').click(function(event) {
			if($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

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

			$('#spanTestGoogleCalendar').show();
			$(this).attr('disabled', true);

			$.ajax({	type:		'GET',
						url:		'index.php',
						data:		{	Page: 		'Users',
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

		$(document.users.smtptype).click(function() {
			$('.SMTPOptions')[document.users.smtptype[1].checked? 'show' : 'hide']();
			$('.sectionSignuptoSMTP')[document.users.smtptype[2].checked? 'show' : 'hide']();
		});

		$(document.users.cmdTestSMTP).click(function() {
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

			tb_show('<?php print GetLang('SendPreview'); ?>', 'index.php?Page=Users&Action=SendPreviewDisplay&keepThis=true&TB_iframe=true&height=250&width=420', '');
			return true;
		});

		document.users.user_smtpcom.disabled = !document.users.user_smtp.checked;
		document.users.smtptype[0].checked = !(document.users.smtptype[1].checked = (document.users.smtp_server.value != ''));

		if($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0) {
			$('#subscribers_manage').attr('checked', true);
		}

		if($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0) {
			$('#segment_view').attr('checked', true);
		}

		$('.SMTPOptions')[document.users.smtptype[1].checked? 'show' : 'hide']();
		$('.sectionSignuptoSMTP')[document.users.smtptype[2].checked? 'show' : 'hide']();
	});

	function getSMTPPreviewParameters() {
		var values = {};
		$($('.smtpSettings', document.users).fieldSerialize().split('&')).each(function(i,n) {
			var temp = n.split('=');
			if (temp.length == 2) values[temp[0]] = temp[1];
		});
		return values;
	}

	function closePopup() {
		tb_remove();
	}

	/**
	 * Fills in the checkboxes based on the selected user type when not
	 * 'Custom'.
	 */
	function populatePermBoxes()
	{
		$('.PermissionOptionItems').each(function() {
			switch (document.users.admintype.selectedIndex) {
				case 0: this.checked = true; break;
				case 1: this.checked = !!this.name.match(/list/); break;
				case 2: this.checked = !!this.name.match(/newsletter/); break;
				case 3: this.checked = !!this.name.match(/template/); break;
				case 4: this.checked = !!this.name.match(/user/); break;
			}
		});
	}

	/**
	 * Checks that all $(name)s matching 'pattern' are checked, or if
	 * reversed, checks that all $(name)s not matching 'pattern' are
	 * not checked.
	 */
	function allItemsChecked(opts, pattern, reverse)
	{
		var all_checked = true;
		$(opts).each(function() {
			if ((!reverse && this.name.match(pattern) && !this.checked) || (reverse && !this.name.match(pattern) && this.checked)) {
				all_checked = false;
				return false;
			}
		});
		return all_checked;
	}

	/**
	 * Loads/caches the checked state of boxes into bucket.
	 */
	function loadCheckboxes(opts)
	{
		var bucket = [];
		opts.each(function() {
			bucket.push({"name": this.name, "checked": this.checked});
		});
		return bucket;
	}

	/**
	 * Calculates what type the user is based on which boxes are checked.
	 */
	function calcUserType()
	{
		document.users.admintype.selectedIndex = 5; // Custom
		var patterns = [/./, /list/, /newsletter/, /template/, /user/];
		var bucket = loadCheckboxes($('input.PermissionOptionItems', $('div#div3')));
		for (i=patterns.length-1; i>=0; i--) {
			if (allItemsChecked(bucket, patterns[i], false) && allItemsChecked(bucket, patterns[i], true)) {
				document.users.admintype.selectedIndex = i;
			}
		}
	}

	// This is called by the ShowTab function in javascript.js
	function onShowTab(tab) {
		// Google tab
		if (tab == 5) {
			$('#cmdTestGoogleCalendar').show();
		} else {
			$('#cmdTestGoogleCalendar').hide();
		}
	}

</script>
<style type="text/css">
	.PermissionColumn1 {
		width: 200px;
	}
	.PermissionColumn2 {
		width: 35px;
	}
	.PermissionColumn3 {
		width: 200px;
	}
	.PermissionColumn4 {
		width: 35px;
	}
</style>
<form name="users" method="post" action="index.php?Page=<?php print $IEM['CurrentPage']; ?>&<?php if(isset($GLOBALS['FormAction'])) print $GLOBALS['FormAction']; ?>">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1"><?php if(isset($GLOBALS['Heading'])) print $GLOBALS['Heading']; ?></td>
		</tr>
		<tr>
			<td class="body pageinfo"><p><?php if(isset($GLOBALS['Help_Heading'])) print $GLOBALS['Help_Heading']; ?></p></td>
		</tr>
		<tr>
			<td>
				<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			</td>
		</tr>
		<tr>
			<td class=body>
				<input class="FormButton" type="submit" value="<?php print GetLang('Save'); ?>"/>
				<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<br />
					<ul id="tabnav">
						<li><a href="#" class="active" id="tab1" onclick="ShowTab(1); return false;"><span><?php print GetLang('UserSettings_Heading'); ?></span></a></li>
						<li><a href="#" id="tab2" onclick="ShowTab(2); return false;"><span><?php print GetLang('UserRestrictions_Heading'); ?></span></a></li>
						<li><a href="#" id="tab3" onclick="ShowTab(3); return false;"><span><?php print GetLang('UserPermissions_Heading'); ?></span></a></li>
						<li><a href="#" id="tab4" onclick="ShowTab(4); return false;"><span><?php print GetLang('EmailSettings_Heading'); ?></span></a></li>
						<li><a href="#" id="tab5" onclick="ShowTab(5); return false;"><span><?php print GetLang('GoogleSettings_Heading'); ?></span></a></li>
					</ul>

					<div id="div1" style="padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class=Heading2 colspan=2 style="padding-left:10px">
									<?php print GetLang('UserDetails'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('UserName'); ?>:
								</td>
								<td>
									<input type="text" name="username" id="username" value="<?php if(isset($GLOBALS['UserName'])) print $GLOBALS['UserName']; ?>" id="username" class="Field250">
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
									<input type="password" name="ss_p" id="ss_p" value="" class="Field250" autocomplete="off" />
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
									<input type="password" name="ss_p_confirm" id="ss_p_confirm" value="" class="Field250" autocomplete="off" />
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
									<input type="text" name="fullname" value="<?php if(isset($GLOBALS['FullName'])) print $GLOBALS['FullName']; ?>" id="fullname" class="Field250">
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
									<input type="text" name="emailaddress" id="emailaddress" value="<?php if(isset($GLOBALS['EmailAddress'])) print $GLOBALS['EmailAddress']; ?>" class="Field250">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailAddress')); ?></span></span>
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
									<?php print GetLang('HTMLFooter'); ?>:
								</td>
								<td>
									<textarea name="htmlfooter" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['HTMLFooter'])) print $GLOBALS['HTMLFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('HTMLFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_HTMLFooter')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td><?php echo GetLang('ViewKB_ExplainDefaultFooter'); ?></td>
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
									<textarea name="textfooter" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['TextFooter'])) print $GLOBALS['TextFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TextFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TextFooter')); ?></span></span>
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
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('Active'); ?>:
								</td>
								<td>
									<label for="status"><input type="checkbox" name="status" id="status" value="1"<?php if(isset($GLOBALS['StatusChecked'])) print $GLOBALS['StatusChecked']; ?>> <?php print GetLang('YesIsActive'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Active')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Active')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('EditOwnSettings'); ?>:
								</td>
								<td>
									<label for="editownsettings"><input type="checkbox" name="editownsettings" id="editownsettings" value="1"<?php if(isset($GLOBALS['EditOwnSettingsChecked'])) print $GLOBALS['EditOwnSettingsChecked']; ?>> <?php print GetLang('YesEditOwnSettings'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditOwnSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditOwnSettings')); ?></span></span>
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
									<label for="infotips"><input type="checkbox" name="infotips" id="infotips" value="1"<?php if(isset($GLOBALS['InfoTipsChecked'])) print $GLOBALS['InfoTipsChecked']; ?>> <?php print GetLang('YesShowInfoTips'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ShowInfoTips')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ShowInfoTips')); ?></span></span>
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
									<div><label for="usewysiwyg"><input type="checkbox" name="usewysiwyg" id="usewysiwyg" value="1" <?php if(isset($GLOBALS['UseWysiwyg'])) print $GLOBALS['UseWysiwyg']; ?>> <?php print GetLang('YesUseWysiwygEditor'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseWysiwygEditor')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseWysiwygEditor')); ?></span></span></div>
									<div id="sectionUseXHTML"<?php if(isset($GLOBALS['UseXHTMLDisplay'])) print $GLOBALS['UseXHTMLDisplay']; ?>><img src="images/nodejoin.gif" width="20" height="20"><label for="usexhtml"><input type="checkbox" name="usexhtml" id="usexhtml" value="1"<?php if(isset($GLOBALS['UseXHTMLCheckbox'])) print $GLOBALS['UseXHTMLCheckbox']; ?>> <?php print GetLang('YesUseXHTML'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseWysiwygXHTML')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseWysiwygXHTML')); ?></span></span></div>
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
						</table>
					</div>

					<div id="div2" style="display:none; padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">

							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									<?php print GetLang('UserRestrictions'); ?>
								</td>
							</tr>

							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('LimitLists'); ?>:
								</td>
								<td>
									<div>
										<label for="limitmaxlists">
											<input type="checkbox" name="limitmaxlists" id="limitmaxlists" value="1"<?php if(isset($GLOBALS['LimitListsChecked'])) print $GLOBALS['LimitListsChecked']; ?>/>
											<?php print GetLang('LimitListsExplain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('LimitLists')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_LimitLists')); ?></span></span>
									</div>
									<div id="DisplayMaxLists" style="display: <?php if(isset($GLOBALS['DisplayMaxLists'])) print $GLOBALS['DisplayMaxLists']; ?>;">
										<img src="images/nodejoin.gif" width="20" height="20">&nbsp;<?php print GetLang('MaximumLists'); ?>:
										<input type="text" name="maxlists" value="<?php if(isset($GLOBALS['MaxLists'])) print $GLOBALS['MaxLists']; ?>" class="Field50"/>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaximumLists')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaximumLists')); ?></span></span>
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
									<?php print GetLang('LimitEmailsPerHour'); ?>:
								</td>
								<td>
									<div>
										<label for="limitperhour">
											<input type="checkbox" name="limitperhour" id="limitperhour" value="1"<?php if(isset($GLOBALS['LimitPerHourChecked'])) print $GLOBALS['LimitPerHourChecked']; ?>/>
											<?php print GetLang('LimitEmailsPerHourExplain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('LimitEmailsPerHour')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_LimitEmailsPerHour')); ?></span></span>
									</div>
									<div id="DisplayEmailsPerHour" style="display: <?php if(isset($GLOBALS['DisplayEmailsPerHour'])) print $GLOBALS['DisplayEmailsPerHour']; ?>;">
										<img src="images/nodejoin.gif" width="20" height="20">&nbsp;<?php print GetLang('EmailsPerHour'); ?>:
										<input type="text" name="perhour" value="<?php if(isset($GLOBALS['PerHour'])) print $GLOBALS['PerHour']; ?>" class="Field50">
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailsPerHour')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailsPerHour')); ?></span></span>
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
									<?php print GetLang('LimitEmailsPerMonth'); ?>:
								</td>
								<td>
									<div>
										<label for="limitpermonth">
											<input type="checkbox" name="limitpermonth" id="limitpermonth" value="1"<?php if(isset($GLOBALS['LimitPerMonthChecked'])) print $GLOBALS['LimitPerMonthChecked']; ?>/>
											<?php print GetLang('LimitEmailsPerMonthExplain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('LimitEmailsPerMonth')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_LimitEmailsPerMonth')); ?></span></span>
									</div>
									<div id="DisplayEmailsPerMonth" style="display: <?php if(isset($GLOBALS['DisplayEmailsPerMonth'])) print $GLOBALS['DisplayEmailsPerMonth']; ?>;">
										<img src="images/nodejoin.gif" width="20" height="20">&nbsp;<?php print GetLang('EmailsPerMonth'); ?>:
										<input type="text" name="permonth" value="<?php if(isset($GLOBALS['PerMonth'])) print $GLOBALS['PerMonth']; ?>" class="Field50">
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailsPerMonth')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailsPerMonth')); ?></span></span>
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
									<?php print GetLang('LimitMaximumEmails'); ?>:
								</td>
								<td>
									<div>
										<label for="unlimitedmaxemails">
											<input type="checkbox" name="unlimitedmaxemails" id="unlimitedmaxemails" value="1" <?php if(isset($GLOBALS['LimitMaximumEmailsChecked'])) print $GLOBALS['LimitMaximumEmailsChecked']; ?>/>
											<?php echo GetLang('LimitMaximumEmailsExplain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('LimitMaximumEmails')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_LimitMaximumEmails')); ?></span></span>
									</div>
									<div id="DisplayEmailsMaxEmails" style="display: <?php if(isset($GLOBALS['DisplayEmailsMaxEmails'])) print $GLOBALS['DisplayEmailsMaxEmails']; ?>;">
										<img src="images/nodejoin.gif" width="20" height="20">&nbsp;<?php echo GetLang('MaximumEmails'); ?>:
										<input type="text" name="maxemails" value="<?php if(isset($GLOBALS['MaxEmails'])) print $GLOBALS['MaxEmails']; ?>" class="Field50">
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaximumEmails')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaximumEmails')); ?></span></span>
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
									<?php print GetLang('ForceDoubleOptIn'); ?>:
								</td>
								<td>
									<div>
										<label for="forcedoubleoptin">
											<input type="checkbox" name="forcedoubleoptin" id="forcedoubleoptin" value="1" <?php if(isset($GLOBALS['ForceDoubleOptInChecked'])) print $GLOBALS['ForceDoubleOptInChecked']; ?>/>
											<?php echo GetLang('ForceDoubleOptIn_Explain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ForceDoubleOptIn')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ForceDoubleOptIn')); ?></span></span>
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
									<?php print GetLang('ForceSpamCheck'); ?>:
								</td>
								<td>
									<div>
										<label for="forcespamcheck">
											<input type="checkbox" name="forcespamcheck" id="forcespamcheck" value="1" <?php if(isset($GLOBALS['ForceSpamCheckChecked'])) print $GLOBALS['ForceSpamCheckChecked']; ?>/>
											<?php echo GetLang('ForceSpamCheck_Explain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ForceSpamCheck')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ForceSpamCheck')); ?></span></span>
									</div>
								</td>
							</tr>
						</table>
					</div>

					<div id="div3" style="display:none; padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class=Heading2 colspan=2 style="padding-left:10px">
									<?php echo GetLang('AccessPermissions'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('AdministratorType'); ?>:
								</td>
								<td>
									<select name="admintype" class="Field250">
										<?php if(isset($GLOBALS['PrintAdminTypes'])) print $GLOBALS['PrintAdminTypes']; ?>
									</select>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdministratorType')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdministratorType')); ?></span></span>
								</td>
							</tr>
							<tr class="CustomPermissionOptions AutoresponderPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('AutoresponderPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="autoresponders_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[autoresponders][create]" id="autoresponders_create"<?php if(isset($GLOBALS['Permissions_Autoresponders_Create'])) print $GLOBALS['Permissions_Autoresponders_Create']; ?>>&nbsp;<?php print GetLang('CreateAutoresponders'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreateAutoresponderHelp')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreateAutoresponderHelp')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="autoresponders_approve"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[autoresponders][approve]" id="autoresponders_approve"<?php if(isset($GLOBALS['Permissions_Autoresponders_Approve'])) print $GLOBALS['Permissions_Autoresponders_Approve']; ?>>&nbsp;<?php echo GetLang('ApproveAutoresponders'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ApproveAutoresponderHelp')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ApproveAutoresponderHelp')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="autoresponders_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[autoresponders][edit]" id="autoresponders_edit"<?php if(isset($GLOBALS['Permissions_Autoresponders_Edit'])) print $GLOBALS['Permissions_Autoresponders_Edit']; ?>>&nbsp;<?php echo GetLang('EditAutoresponders'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditAutoresponderHelp')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditAutoresponderHelp')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="autoresponders_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[autoresponders][delete]" id="autoresponders_delete"<?php if(isset($GLOBALS['Permissions_Autoresponders_Delete'])) print $GLOBALS['Permissions_Autoresponders_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteAutoresponders'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteAutoresponderHelp')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteAutoresponderHelp')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions FormPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('FormPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="forms_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[forms][create]" id="forms_create"<?php if(isset($GLOBALS['Permissions_Forms_Create'])) print $GLOBALS['Permissions_Forms_Create']; ?>>&nbsp;<?php echo GetLang('CreateForms'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreateForms')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreateForms')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="forms_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[forms][edit]" id="forms_edit"<?php if(isset($GLOBALS['Permissions_Forms_Edit'])) print $GLOBALS['Permissions_Forms_Edit']; ?>>&nbsp;<?php echo GetLang('EditForms'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditForms')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditForms')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="forms_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[forms][delete]" id="forms_delete"<?php if(isset($GLOBALS['Permissions_Forms_Delete'])) print $GLOBALS['Permissions_Forms_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteForms'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteForms')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteForms')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions ListPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('ListPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="lists_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[lists][create]" id="lists_create"<?php if(isset($GLOBALS['Permissions_Lists_Create'])) print $GLOBALS['Permissions_Lists_Create']; ?>>&nbsp;<?php echo GetLang('CreateMailingLists'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreateMailingLists')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreateMailingLists')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="lists_bounce"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[lists][bounce]" id="lists_bounce"<?php if(isset($GLOBALS['Permissions_Lists_Bounce'])) print $GLOBALS['Permissions_Lists_Bounce']; ?>>&nbsp;<?php echo GetLang('MailingListsBounce'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MailingListsBounce')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MailingListsBounce')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="lists_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[lists][edit]" id="lists_edit"<?php if(isset($GLOBALS['Permissions_Lists_Edit'])) print $GLOBALS['Permissions_Lists_Edit']; ?>>&nbsp;<?php echo GetLang('EditMailingLists'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditMailingLists')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditMailingLists')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="lists_bouncesettings"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[lists][bouncesettings]" id="lists_bouncesettings"<?php if(isset($GLOBALS['Permissions_Lists_Bouncesettings'])) print $GLOBALS['Permissions_Lists_Bouncesettings']; ?>>&nbsp;<?php echo GetLang('MailingListsBounceSettings'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MailingListsBounceSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MailingListsBounceSettings')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="lists_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[lists][delete]" id="lists_delete"<?php if(isset($GLOBALS['Permissions_Lists_Delete'])) print $GLOBALS['Permissions_Lists_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteMailingLists'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteMailingLists')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteMailingLists')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions SegmentPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('SegmentPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="segment_view"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[segments][view]" id="segment_view"<?php if(isset($GLOBALS['Permissions_Segments_View'])) print $GLOBALS['Permissions_Segments_View']; ?>>&nbsp;<?php echo GetLang('SegmentViewPermission'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SegmentViewPermission')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SegmentViewPermission')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="segment_send"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[segments][send]" id="segment_send"<?php if(isset($GLOBALS['Permissions_Segments_Send'])) print $GLOBALS['Permissions_Segments_Send']; ?>>&nbsp;<?php echo GetLang('SegmentSendPermission'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SegmentSendPermission')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SegmentSendPermission')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="segment_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[segments][create]" id="segment_create"<?php if(isset($GLOBALS['Permissions_Segments_Create'])) print $GLOBALS['Permissions_Segments_Create']; ?>>&nbsp;<?php echo GetLang('SegmentCreatePermission'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SegmentCreatePermission')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SegmentCreatePermission')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="segment_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[segments][edit]" id="segment_edit"<?php if(isset($GLOBALS['Permissions_Segments_Edit'])) print $GLOBALS['Permissions_Segments_Edit']; ?>>&nbsp;<?php echo GetLang('SegmentEditPermission'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SegmentEditPermission')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SegmentEditPermission')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="segment_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[segments][delete]" id="segment_delete"<?php if(isset($GLOBALS['Permissions_Segments_Delete'])) print $GLOBALS['Permissions_Segments_Delete']; ?>>&nbsp;<?php echo GetLang('SegmentDeletePermission'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SegmentDeletePermission')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SegmentDeletePermission')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions CustomFieldPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('CustomFieldPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="customfields_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[customfields][create]" id="customfields_create"<?php if(isset($GLOBALS['Permissions_Customfields_Create'])) print $GLOBALS['Permissions_Customfields_Create']; ?>>&nbsp;<?php echo GetLang('CreateCustomFields'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreateCustomFields')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreateCustomFields')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="customfields_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[customfields][edit]" id="customfields_edit"<?php if(isset($GLOBALS['Permissions_Customfields_Edit'])) print $GLOBALS['Permissions_Customfields_Edit']; ?>>&nbsp;<?php echo GetLang('EditCustomFields'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditCustomFields')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditCustomFields')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="customfields_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[customfields][delete]" id="customfields_delete"<?php if(isset($GLOBALS['Permissions_Customfields_Delete'])) print $GLOBALS['Permissions_Customfields_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteCustomFields'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteCustomFields')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteCustomFields')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions NewsletterPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('NewsletterPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="newsletters_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[newsletters][create]" id="newsletters_create"<?php if(isset($GLOBALS['Permissions_Newsletters_Create'])) print $GLOBALS['Permissions_Newsletters_Create']; ?>>&nbsp;<?php echo GetLang('CreateNewsletters'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreateNewsletters')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreateNewsletters')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="newsletters_approve"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[newsletters][approve]" id="newsletters_approve"<?php if(isset($GLOBALS['Permissions_Newsletters_Approve'])) print $GLOBALS['Permissions_Newsletters_Approve']; ?>>&nbsp;<?php echo GetLang('ApproveNewsletters'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ApproveNewsletters')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ApproveNewsletters')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="newsletters_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[newsletters][edit]" id="newsletters_edit"<?php if(isset($GLOBALS['Permissions_Newsletters_Edit'])) print $GLOBALS['Permissions_Newsletters_Edit']; ?>>&nbsp;<?php echo GetLang('EditNewsletters'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditNewsletters')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditNewsletters')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="newsletters_send"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[newsletters][send]" id="newsletters_send"<?php if(isset($GLOBALS['Permissions_Newsletters_Send'])) print $GLOBALS['Permissions_Newsletters_Send']; ?>>&nbsp;<?php echo GetLang('SendNewsletters'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SendNewsletters')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SendNewsletters')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="newsletters_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[newsletters][delete]" id="newsletters_delete"<?php if(isset($GLOBALS['Permissions_Newsletters_Delete'])) print $GLOBALS['Permissions_Newsletters_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteNewsletters'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteNewsletters')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteNewsletters')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions TriggeremailsPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('Permissions_Triggeremails'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="triggeremails_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[triggeremails][create]" id="triggeremails_create"<?php if(isset($GLOBALS['Permissions_Triggeremails_Create'])) print $GLOBALS['Permissions_Triggeremails_Create']; ?>>&nbsp;<?php echo GetLang('Permissions_Triggeremails_Create'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Permissions_Triggeremails_Create')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Permissions_Triggeremails_Create')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="triggeremails_activate"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[triggeremails][activate]" id="triggeremails_activate"<?php if(isset($GLOBALS['Permissions_Triggeremails_Activate'])) print $GLOBALS['Permissions_Triggeremails_Activate']; ?>>&nbsp;<?php echo GetLang('Permissions_Triggeremails_Activate'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Permissions_Triggeremails_Activate')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Permissions_Triggeremails_Activate')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="triggeremails_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[triggeremails][edit]" id="triggeremails_edit"<?php if(isset($GLOBALS['Permissions_Triggeremails_Edit'])) print $GLOBALS['Permissions_Triggeremails_Edit']; ?>>&nbsp;<?php echo GetLang('Permissions_Triggeremails_Edit'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Permissions_Triggeremails_Edit')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Permissions_Triggeremails_Edit')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="triggeremails_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[triggeremails][delete]" id="triggeremails_delete"<?php if(isset($GLOBALS['Permissions_Triggeremails_Delete'])) print $GLOBALS['Permissions_Triggeremails_Delete']; ?>>&nbsp;<?php echo GetLang('Permissions_Triggeremails_Delete'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Permissions_Triggeremails_Delete')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Permissions_Triggeremails_Delete')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												&nbsp;
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions SubscriberPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('SubscriberPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="subscribers_manage"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][manage]" id="subscribers_manage"<?php if(isset($GLOBALS['Permissions_Subscribers_Manage'])) print $GLOBALS['Permissions_Subscribers_Manage']; ?>>&nbsp;<?php echo GetLang('ManageSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ManageSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ManageSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="subscribers_import"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][import]" id="subscribers_import"<?php if(isset($GLOBALS['Permissions_Subscribers_Import'])) print $GLOBALS['Permissions_Subscribers_Import']; ?>>&nbsp;<?php echo GetLang('ImportSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ImportSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ImportSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="subscribers_add"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][add]" id="subscribers_add"<?php if(isset($GLOBALS['Permissions_Subscribers_Add'])) print $GLOBALS['Permissions_Subscribers_Add']; ?>>&nbsp;<?php echo GetLang('AddSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AddSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AddSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="subscribers_export"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][export]" id="subscribers_export"<?php if(isset($GLOBALS['Permissions_Subscribers_Export'])) print $GLOBALS['Permissions_Subscribers_Export']; ?>>&nbsp;<?php echo GetLang('ExportSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ExportSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ExportSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="subscribers_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][edit]" id="subscribers_edit"<?php if(isset($GLOBALS['Permissions_Subscribers_Edit'])) print $GLOBALS['Permissions_Subscribers_Edit']; ?>>&nbsp;<?php echo GetLang('EditSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="subscribers_banned"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][banned]" id="subscribers_banned"<?php if(isset($GLOBALS['Permissions_Subscribers_Banned'])) print $GLOBALS['Permissions_Subscribers_Banned']; ?>>&nbsp;<?php echo GetLang('ManageBannedSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ManageBannedSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ManageBannedSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="subscribers_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][delete]" id="subscribers_delete"<?php if(isset($GLOBALS['Permissions_Subscribers_Delete'])) print $GLOBALS['Permissions_Subscribers_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteSubscribers'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteSubscribers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteSubscribers')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="subscribers_event_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][eventdelete]" id="subscribers_event_delete"<?php if(isset($GLOBALS['Permissions_Subscribers_Eventdelete'])) print $GLOBALS['Permissions_Subscribers_Eventdelete']; ?>>&nbsp;<?php echo GetLang('EventDelete'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EventDelete')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EventDelete')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="subscribers_event_save"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][eventsave]" id="subscribers_event_save"<?php if(isset($GLOBALS['Permissions_Subscribers_Eventsave'])) print $GLOBALS['Permissions_Subscribers_Eventsave']; ?>>&nbsp;<?php echo GetLang('EventAdd'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EventAdd')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EventAdd')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="subscribers_event_update"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[subscribers][eventupdate]" id="subscribers_event_update"<?php if(isset($GLOBALS['Permissions_Subscribers_Eventupdate'])) print $GLOBALS['Permissions_Subscribers_Eventupdate']; ?>>&nbsp;<?php echo GetLang('EventEdit'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EventEdit')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EventEdit')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions TemplatePermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('TemplatePermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="templates_create"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[templates][create]" id="templates_create"<?php if(isset($GLOBALS['Permissions_Templates_Create'])) print $GLOBALS['Permissions_Templates_Create']; ?>>&nbsp;<?php echo GetLang('CreateTemplates'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreateTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreateTemplates')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="templates_approve"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[templates][approve]" id="templates_approve"<?php if(isset($GLOBALS['Permissions_Templates_Approve'])) print $GLOBALS['Permissions_Templates_Approve']; ?>>&nbsp;<?php echo GetLang('ApproveTemplates'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ApproveTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ApproveTemplates')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="templates_edit"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[templates][edit]" id="templates_edit"<?php if(isset($GLOBALS['Permissions_Templates_Edit'])) print $GLOBALS['Permissions_Templates_Edit']; ?>>&nbsp;<?php echo GetLang('EditTemplates'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditTemplates')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3" colspan="2">
												<label for="templates_global"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[templates][global]" id="templates_global"<?php if(isset($GLOBALS['Permissions_Templates_Global'])) print $GLOBALS['Permissions_Templates_Global']; ?>>&nbsp;<?php echo GetLang('GlobalTemplates'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GlobalTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GlobalTemplates')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="templates_delete"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[templates][delete]" id="templates_delete"<?php if(isset($GLOBALS['Permissions_Templates_Delete'])) print $GLOBALS['Permissions_Templates_Delete']; ?>>&nbsp;<?php echo GetLang('DeleteTemplates'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DeleteTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DeleteTemplates')); ?></span></span>
											</td>
											<td class="PermissionColumn2">
												&nbsp;
											</td>
											<td class="PermissionColumn3">
												<label for="templates_builtin"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[templates][builtin]" id="templates_builtin"<?php if(isset($GLOBALS['Permissions_Templates_Builtin'])) print $GLOBALS['Permissions_Templates_Builtin']; ?>>&nbsp;<?php echo GetLang('BuiltInTemplates'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('BuiltInTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_BuiltInTemplates')); ?></span></span>
											</td>
											<td class="PermissionColumn4">
												&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions StatisticPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('StatisticsPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="statistics_newsletter"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[statistics][newsletter]" id="statistics_newsletter"<?php if(isset($GLOBALS['Permissions_Statistics_Newsletter'])) print $GLOBALS['Permissions_Statistics_Newsletter']; ?>>&nbsp;<?php echo GetLang('NewsletterStatistics'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NewsletterStatistics')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NewsletterStatistics')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
											<td class="PermissionColumn3">
												<label for="statistics_user"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[statistics][user]" id="statistics_user"<?php if(isset($GLOBALS['Permissions_Statistics_User'])) print $GLOBALS['Permissions_Statistics_User']; ?>>&nbsp;<?php echo GetLang('UserStatistics'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UserStatistics')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UserStatistics')); ?></span></span>
											</td>
											<td class="PermissionColumn4">&nbsp;</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="statistics_autoresponder"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[statistics][autoresponder]" id="statistics_autoresponder"<?php if(isset($GLOBALS['Permissions_Statistics_Autoresponder'])) print $GLOBALS['Permissions_Statistics_Autoresponder']; ?>>&nbsp;<?php echo GetLang('AutoresponderStatistics'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AutoresponderStatistics')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AutoresponderStatistics')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
											<td class="PermissionColumn3">
												<label for="statistics_list"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[statistics][list]" id="statistics_list"<?php if(isset($GLOBALS['Permissions_Statistics_List'])) print $GLOBALS['Permissions_Statistics_List']; ?>>&nbsp;<?php echo GetLang('ListStatistics'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListStatistics')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListStatistics')); ?></span></span>
											</td>
											<td class="PermissionColumn4">&nbsp;</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="statistics_triggeremails"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[statistics][triggeremails]" id="statistics_triggeremails"<?php if(isset($GLOBALS['Permissions_Statistics_Triggeremails'])) print $GLOBALS['Permissions_Statistics_Triggeremails']; ?>>&nbsp;<?php echo GetLang('Permissions_Triggeremails_Statistics'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Permissions_Triggeremails_Statistics')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Permissions_Triggeremails_Statistics')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
											<td class="PermissionColumn3">&nbsp;
											</td>
											<td class="PermissionColumn4">&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions AdminPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('AdminPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="system_system"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[system][system]" id="system_system"<?php if(isset($GLOBALS['Permissions_System_System'])) print $GLOBALS['Permissions_System_System']; ?>>&nbsp;<?php echo GetLang('SystemAdministrator'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SystemAdministrator')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SystemAdministrator')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
											<td class="PermissionColumn3">
												<label for="system_list"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[system][list]" id="system_list"<?php if(isset($GLOBALS['Permissions_System_List'])) print $GLOBALS['Permissions_System_List']; ?>>&nbsp;<?php echo GetLang('ListAdministrator'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListAdministrator')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListAdministrator')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="system_user"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[system][user]" id="system_user"<?php if(isset($GLOBALS['Permissions_System_User'])) print $GLOBALS['Permissions_System_User']; ?>>&nbsp;<?php echo GetLang('UserAdministrator'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UserAdministrator')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UserAdministrator')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
											<td class="PermissionColumn3">
												<label for="system_template"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[system][template]" id="system_template"<?php if(isset($GLOBALS['Permissions_System_Template'])) print $GLOBALS['Permissions_System_Template']; ?>>&nbsp;<?php echo GetLang('TemplateAdministrator'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TemplateAdministrator')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TemplateAdministrator')); ?></span></span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="CustomPermissionOptions OtherPermissionOptions" style="display: <?php if(isset($GLOBALS['DisplayPermissions'])) print $GLOBALS['DisplayPermissions']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('OtherPermissions'); ?>:
								</td>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="PermissionColumn1">
												<label for="user_smtp"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[user][smtp]" id="user_smtp"<?php if(isset($GLOBALS['Permissions_User_Smtp'])) print $GLOBALS['Permissions_User_Smtp']; ?>>&nbsp;<?php echo GetLang('User_SMTP'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('User_SMTP')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_User_SMTP')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
										</tr>
										<tr>
											<td class="PermissionColumn1">
												<label for="user_smtpcom"><input type="checkbox" class="PermissionOptionItems" value="1" name="permissions[user][smtpcom]" id="user_smtpcom"<?php if(isset($GLOBALS['Permissions_User_Smtpcom'])) print $GLOBALS['Permissions_User_Smtpcom']; ?>>&nbsp;<?php echo GetLang('SettingsShowSMTPCOMLabel'); ?></label>
												<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SettingsShowSMTPCOM')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SettingsShowSMTPCOM')); ?></span></span>
											</td>
											<td class="PermissionColumn2">&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('UseXMLAPI'); ?>:
								</td>
								<td>
									<label for="xmlapi"><input type="checkbox" name="xmlapi" id="xmlapi" value="1" <?php if(isset($GLOBALS['Xmlapi'])) print $GLOBALS['Xmlapi']; ?>> <?php echo GetLang('YesUseXMLAPI'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseXMLAPI')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseXMLAPI')); ?></span></span><br/>
									<table id="sectionXMLToken"<?php if(isset($GLOBALS['XMLTokenDisplay'])) print $GLOBALS['XMLTokenDisplay']; ?> border="0" cellspacing="0" cellpadding="2" class="Panel">
										<tr>
											<td width="100">
												<img src="images/nodejoin.gif" width="20" height="20">&nbsp;<?php echo GetLang('XMLPath'); ?>:
											</td>
											<td>
												<input type="text" name="xmlpath" id="xmlpath" value="<?php if(isset($GLOBALS['XmlPath'])) print $GLOBALS['XmlPath']; ?>" class="Field250 SelectOnFocus" readonly/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('XMLPath')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_XMLPath')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="images/blank.gif" width="20" height="20">&nbsp;<?php echo GetLang('XMLUsername'); ?>:
											</td>
											<td>
												<input type="text" name="xmlusername" id="xmlusername" value="<?php if(isset($GLOBALS['UserName'])) print $GLOBALS['UserName']; ?>" class="Field250 SelectOnFocus" readonly/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('XMLUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_XMLUsername')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="images/blank.gif" width="20" height="20">&nbsp;<?php echo GetLang('XMLToken'); ?>:
											</td>
											<td>
												<input type="text" name="xmltoken" id="xmltoken" value="<?php if(isset($GLOBALS['XmlToken'])) print $GLOBALS['XmlToken']; ?>" class="Field250 SelectOnFocus" readonly/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('XMLToken')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_XMLToken')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td>
												&nbsp;
											</td>
											<td>
												<a href="#" id="hrefRegenerateXMLToken" style="color: gray;"><?php print GetLang('XMLToken_Regenerate'); ?></a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="EmptyRow" colspan=2>
									&nbsp;
								</td>
							</tr>
							<?php if(isset($GLOBALS['AddonPermissionDisplay'])) print $GLOBALS['AddonPermissionDisplay']; ?>
							<tr>
								<td class=Heading2 colspan=2>
									<?php print GetLang('MailingListAccessPermissions'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('ChooseMailingLists'); ?>:
								</td>
								<td height="35">
									<select name="listadmintype">
										<?php if(isset($GLOBALS['PrintListAdminList'])) print $GLOBALS['PrintListAdminList']; ?>
									</select>&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ChooseMailingLists')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ChooseMailingLists')); ?></span></span>
								</td>
							</tr>
							<tr id="PrintLists" style="display: <?php if(isset($GLOBALS['ListDisplay'])) print $GLOBALS['ListDisplay']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								</td>
								<td style="padding-bottom:10px">
									<?php if(isset($GLOBALS['PrintMailingLists'])) print $GLOBALS['PrintMailingLists']; ?>
								</td>
							</tr>

							<tr>
								<td class="EmptyRow" colspan=2>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td class=Heading2 colspan=2>
									<?php print GetLang('SegmentAccessPermissions'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('ChooseAllowedSegments'); ?>:
								</td>
								<td height="35">
									<select name="segmentadmintype">
										<?php if(isset($GLOBALS['PrintSegmentAdminList'])) print $GLOBALS['PrintSegmentAdminList']; ?>
									</select>&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ChooseAllowedSegments')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ChooseAllowedSegments')); ?></span></span>
								</td>
							</tr>
							<tr id="PrintSegments" style="display: <?php if(isset($GLOBALS['SegmentDisplay'])) print $GLOBALS['SegmentDisplay']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								</td>
								<td style="padding-bottom:10px">
									<?php if(isset($GLOBALS['PrintSegmentLists'])) print $GLOBALS['PrintSegmentLists']; ?>
								</td>
							</tr>

							<tr>
								<td class="EmptyRow" colspan=2>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td class=Heading2 colspan=2>
									<?php print GetLang('TemplateAccessPermissions'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel" height="35">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('ChooseTemplates'); ?>:
								</td>
								<td>
									<select name="templateadmintype">
										<?php if(isset($GLOBALS['PrintTemplateAdminList'])) print $GLOBALS['PrintTemplateAdminList']; ?>
									</select>&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ChooseTemplates')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ChooseTemplates')); ?></span></span>
								</td>
							</tr>
							<tr id="PrintTemplates" style="display: <?php if(isset($GLOBALS['TemplateDisplay'])) print $GLOBALS['TemplateDisplay']; ?>">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								</td>
								<td style="padding-bottom:10px">
									<?php if(isset($GLOBALS['PrintTemplateLists'])) print $GLOBALS['PrintTemplateLists']; ?>
								</td>
							</tr>
						</table>
					</div>

					<div id="div4" style="display:none; padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									<?php print GetLang('SmtpServerIntro'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel" width="10%">
									<img src="images/blank.gif" width="200" height="1" /><br />
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('SmtpServer'); ?>:
								</td>
								<td width="90%">
									<label for="usedefaultsmtp">
										<input type="radio" name="smtptype" id="usedefaultsmtp" value="0"/>
										<?php print GetLang('SmtpDefault'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseDefaultMail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseDefaultMail')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td>
									<label for="usecustomsmtp">
										<input type="radio" name="smtptype" id="usecustomsmtp" value="1"/>
										<?php print GetLang('SmtpCustom'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTP_User')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTP_User')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('SmtpServerName'); ?>:
								</td>
								<td>
									<img width="20" height="20" src="images/nodejoin.gif"/>
									<input type="text" name="smtp_server" value="<?php if(isset($GLOBALS['SmtpServer'])) print $GLOBALS['SmtpServer']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerName')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('SmtpServerUsername'); ?>:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_u" value="<?php if(isset($GLOBALS['SmtpUsername'])) print $GLOBALS['SmtpUsername']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerUsername')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('SmtpServerPassword'); ?>:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="password" name="smtp_p" value="<?php if(isset($GLOBALS['SmtpPassword'])) print $GLOBALS['SmtpPassword']; ?>" class="Field250 smtpSettings" autocomplete="off" /> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPassword')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('SmtpServerPort'); ?>:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_port" value="<?php if(isset($GLOBALS['SmtpPort'])) print $GLOBALS['SmtpPort']; ?>" class="field50 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPort')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPort')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('TestSMTPSettings'); ?>:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_test" id="smtp_test" value="" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TestSMTPSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TestSMTPSettings')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									&nbsp;
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="button" name="cmdTestSMTP" value="<?php print GetLang('TestSMTPSettings'); ?>" class="FormButton" style="width: 120px;"/>
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
								<td colspan="2" style="padding-left: 10px; padding-top:10px"><?php print GetLang('SMTPCOM_Explain'); ?></td>
							</tr>
						</table>
					</div>
				</div>

					<div id="div5" style="display:none; padding-top:10px">
					<p class="HelpInfo"><?php print GetLang('GoogleCalendarIntroText'); ?></p>
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									<?php print GetLang('GoogleCalendarIntro'); ?>
								</td>
							</tr>
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
								<td class="FieldLabel" width="10%">
									<img src="images/blank.gif" width="200" height="1" /><br />
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('GoogleCalendarPassword'); ?>:
								</td>
								<td width="90%">
									<label for="googlecalendarpassword">
										<input type="password" class="Field250 googlecalendar" name="googlecalendarpassword" id="googlecalendarpassword" value="<?php if(isset($GLOBALS['googlecalendarpassword'])) print $GLOBALS['googlecalendarpassword']; ?>" autocomplete="off" />
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GoogleCalendarPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GoogleCalendarPassword')); ?></span></span>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<table border="0" cellspacing="0" cellpadding="2" width="100%" class=PanelPlain>
					<tr>
						<td width="200" class="FieldLabel">
							&nbsp;
						</td>
						<td height="30" valign="top">
							<input type="button" id="cmdTestGoogleCalendar" value="<?php print GetLang('TestLogin'); ?>" class="FormButton" style="display: none;"/>
							<input class="FormButton" type="submit" value="<?php print GetLang('Save'); ?>">
							<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>"/>
							<span id="spanTestGoogleCalendar" style="display:none;">&nbsp;&nbsp;<img src="images/searching.gif" alt="wait" /></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>






<?php $IEM = $tpl->Get('IEM'); ?><script>
	var PAGE = {
		init:						function() {
			$(document.frmManageSubscriberStep1).submit(function(event) {
				event.preventDefault();
				event.stopPropagation();
			});

			if(document.frmManageSubscriberStep1['segments[]'].options.length == 0)
				$('#ShowSegmentOptions').attr('disabled', true);

			$('.CancelButton').click(function() { PAGE.cancel(); });

			$('.SubmitButton').click(function() { PAGE.submit(); });

			$('#segments, #lists').dblclick(function() { PAGE.submit(); });

			$('.SendFilteringOption').click(function() { PAGE.selectSendingOption(this.value); });
		},
		submit:					function() {
			var filteringOptions = parseInt($('.SendFilteringOption:checked').val());

			switch(filteringOptions) {
				case 1:
				case 2:
					var list = $('#lists').get(0);
					if(list.selectedIndex == -1) {
						alert("<?php print GetLang('SelectList'); ?>");
						return;
					}

					if(filteringOptions == 2) {
						var url = 'index.php?Page=Subscribers&Action=Manage&SubAction=step3';

						for (var i = 0, j = list.options.length; i < j; i++) {
							if (list.options[i].selected){
								url += '&Lists[]=' + list.options[i].value;
							}
						}

						$(document.frmManageSubscriberStep1).attr('action', url);
					}
				break;
				case 3:
					var segments = $('#segments').get(0);
					if(segments.selectedIndex == -1) {
						alert("<?php print GetLang('SelectSegment'); ?>");
						return;
					}

					var url = 'index.php?Page=Subscribers&Action=Manage&SubAction=step3';

					for (var i = 0, j = segments.options.length; i < j; i++) {
						if (segments.options[i].selected){
							url += '&Segment[]=' + segments.options[i].value;
						}
					}

					$(document.frmManageSubscriberStep1).attr('action', url);
				break;
			}

			document.frmManageSubscriberStep1.submit();
		},
		cancel:					function() {
			if(confirm("<?php print GetLang('Subscribers_Manage_CancelPrompt'); ?>"))
				document.location="index.php?Page=Subscribers";
		},
		selectSendingOption:	function(sendingOption) {
			if(sendingOption == 3) this.showSegment();
			else this.showMailingList();
		},
		showSegment:			function(transition) {
			$('#FilteringOptions').hide();
			$('#SegmentOptions').show();
		},
		showMailingList:		function(transition) {
			$('#SegmentOptions').hide(transition? 'slow' : '');
			$('#FilteringOptions').show(transition? 'slow' : '');
		}
	};

	$(function() { PAGE.init(); });
</script>
<form name="frmManageSubscriberStep1" method="post" action="index.php?Page=Subscribers&Action=Manage&SubAction=step2">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1">
				<?php print GetLang('Subscribers_AdvancedSearch'); ?>
			</td>
		</tr>
		<tr>
			<td class="body pageinfo">
				<p>
					<?php print GetLang('Subscribers_Manage_Intro'); ?>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<input class="FormButton SubmitButton" type="button" value="<?php print GetLang('Next'); ?>" />
				<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					<tr>
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
							<?php print GetLang('ShowFilteringOptionsLabel_Manage'); ?>&nbsp;
						</td>
						<td valign="top">
							<table width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="2">
										<label for="ShowFilteringOptions"><input type="radio" name="ShowFilteringOptions" id="ShowFilteringOptions" class="SendFilteringOption" value="1" checked="checked" /><?php print GetLang('SubscribersShowFilteringOptionsExplain'); ?> </label>
									</td>
								</tr>
								<tr style="display:<?php if(isset($GLOBALS['DisplaySegmentOption'])) print $GLOBALS['DisplaySegmentOption']; ?>;">
									<td colspan="2">
										<label class="SendFilteringOption_Label" for="ShowSegmentOptions"><input type="radio" name="ShowFilteringOptions" id="ShowSegmentOptions" class="SendFilteringOption" value="3" /><?php print GetLang('SubscribersShowSegmentsExplain'); ?></label>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div id="FilteringOptions">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('MailingListDetails'); ?>
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('MailingList'); ?>:&nbsp;
							</td>
							<td>
								<select id="lists" name="lists[]" multiple="multiple" class="ISSelectReplacement ISSelectSearch" style="<?php if(isset($GLOBALS['SelectListStyle'])) print $GLOBALS['SelectListStyle']; ?>">
									<?php if(isset($GLOBALS['SelectList'])) print $GLOBALS['SelectList']; ?>
								</select>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MailingList')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MailingList')); ?></span></span>
							</td>
						</tr>
					</table>
				</div>
				<div id="SegmentOptions" style="display:none;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('SubscriberSegmentDetails'); ?>
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('Segments'); ?>:&nbsp;
							</td>
							<td>
								<select id="segments" name="segments[]" multiple="multiple" class="SelectedSegments ISSelectReplacement">
									<?php if(isset($GLOBALS['SelectSegment'])) print $GLOBALS['SelectSegment']; ?>
								</select>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SubscriberFilterBySegments')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SubscriberFilterBySegments')); ?></span></span>
							</td>
						</tr>
					</table>
				</div>
				<table width="100%" cellspacing="0" cellpadding="2" border="0" class="PanelPlain">
					<tr>
						<td width="200" class="FieldLabel">&nbsp;</td>
						<td valign="top" height="30">
							<input class="FormButton SubmitButton" type="submit" value="<?php print GetLang('Next'); ?>" />
							<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>





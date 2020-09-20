<?php $IEM = $tpl->Get('IEM'); ?><script>
	$(function() {
		Application.Ui.CheckboxSelection(	'input.UICheckboxToggleSelector',
											'input.UICheckboxToggleRows');

		$('table tr td a.ActionType_disguise').click(function(e) {
			var matches = $(this).attr('href').match(/(.*?)&newID=(\d+)$/);

			if (matches.length == 3) {
				Application.Util.submitPost(matches[1], {newUserID:matches[2]});
			}

			e.stopPropagation();
			e.preventDefault();
			return false;
		});

		$('div#deleteAccount a').click(function(e) {
			e.preventDefault();

			var deleteData = ($(this).attr('href').match(/^#Y/) == '#Y');
			var selectedIDs = [];
			var selectedRows = $('table tr.UserRecordRow input.UICheckboxToggleRows[type=checkbox]:checked');
			for(var i = 0, j = selectedRows.size(); i < j; ++i) selectedIDs.push(selectedRows.get(i).value);

			if (selectedRows.length == 0) {
				alert("<?php echo GetLang('ChooseUsersToDelete'); ?>");
				return true;
			}

			if ($.inArray('<?php echo $tpl->Get('PAGE','currentuserid'); ?>', selectedIDs) != -1) {
				alert("<?php echo GetLang('User_CantDeleteOwn'); ?>");
				return true;
			}

			if (!confirm(deleteData ? "<?php echo GetLang('ConfirmRemoveUsersWithData'); ?>" : "<?php echo GetLang('ConfirmRemoveUsers'); ?>")) {
				return true;
			}

			$('button#deleteAccountButton', document.userform).attr('disabled', true);
			Application.Util.submitPost('index.php?Page=Users&Action=Delete', {users:selectedIDs, deleteData:(deleteData ? '1' : '0')});
			return true;
		});

		$('input#createAccountButton', document.userform).click(function() {
			document.location="index.php?Page=Users&Action=Add";
		});

		Application.Ui.Menu.PopDown('button#deleteAccountButton', {topMarginPixel: -3});
	});

	function DeleteSelectedUsers(formObj) {
		users_found = 0;
		for (var i=0;i < formObj.length;i++)
		{
			fldObj = formObj.elements[i];
			if (fldObj.type == 'checkbox')
			{
				if (fldObj.checked) {
					users_found++;
					break;
				}
			}
		}

		if (users_found <= 0) {
			alert("<?php print GetLang('ChooseUsersToDelete'); ?>");
			return false;
		}

		if (confirm("<?php print GetLang('ConfirmRemoveUsers'); ?>")) {
			return true;
		}
		return false;
	}
</script>
<style>
	tr.Heading3 td { white-space:nowrap; }
</style>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr><td class="Heading1"><?php echo GetLang('Users'); ?></td></tr>
	<tr><td class="body pageinfo"><p><?php echo GetLang('Help_Users'); ?></p></td></tr>
	<?php if(trim($tpl->Get('PAGE','messages')) != ''): ?><tr><td><?php echo $tpl->Get('PAGE','messages'); ?></td></tr><?php endif; ?>
	<tr>
		<td>
			<div class="UserInfo">
				<img src="images/user.gif" style="margin-top: -3px;" align="left"><?php echo $tpl->Get('PAGE','userreport'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="body">
			<form name="userform" method="post" action="" onsubmit="return false;">
				<table border="0" width="100%" style="padding-top:5px">
					<tr>
						<td>
							<input id="createAccountButton" type="button" class="SmallButton" value="<?php echo GetLang('UserAdd'); ?>" style="width:150px" />
							<!-- input type="submit" name="DeleteUserButton" value="<?php echo GetLang('Delete_User_Selected'); ?>" class="SmallButton" /-->
							<button id="deleteAccountButton" class="SmallButton" style="white-space:nowrap;"><?php echo GetLang('UserDeletePopDown'); ?></button>
							<div id="deleteAccount" style="display: none; width: 130px;" class="DropDownMenu DropShadow">
								<ul>
									<li><a href="#N" title="<?php echo GetLang('UserDeleteNoData_Summary'); ?>"><?php echo GetLang('UserDeleteNoData'); ?></a></li>
									<li><a href="#Y" title="<?php echo GetLang('UserDeleteWithData_Summary'); ?>"><?php echo GetLang('UserDeleteWithData'); ?></a></li>
								</ul>
							</div>
						</td>
						<td align="right" valign="bottom">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Paging");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						</td>
					</tr>
				</table>
				<table border=0 cellspacing="0" cellpadding="0" width=100% class="Text">
					<tr class="Heading3">
						<td width="5" nowrap align="center">
							<input type="checkbox" name="toggle" class="UICheckboxToggleSelector" />
						</td>
						<td width="5">&nbsp;</td>
						<td width="25%">
							<?php echo GetLang('UserName'); ?>
							<a href="index.php?Page=Users&SortBy=username&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=username&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="25%">
							<?php echo GetLang('FullName'); ?>
							<a href="index.php?Page=Users&SortBy=fullname&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=fullname&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="15%">
							<?php echo GetLang('UserType'); ?>
							<a href="index.php?Page=Users&SortBy=admintype&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=admintype&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="17%">
							<?php echo GetLang('UserCreatedOn'); ?>
							<a href="index.php?Page=Users&SortBy=createdate&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=createdate&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="17%">
							<?php echo GetLang('LastLoggedIn'); ?>
							<a href="index.php?Page=Users&SortBy=lastloggedin&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=lastloggedin&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="15">
							<?php echo GetLang('UserStatusColumn'); ?>
							<a href="index.php?Page=Users&SortBy=status&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=status&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="70">
							<?php echo GetLang('Action'); ?>
						</td>
					</tr>
					<?php $array = $tpl->Get('records'); if(is_array($array)): foreach($array as $__key=>$each): $tpl->Assign('__key', $__key, false); $tpl->Assign('each', $each, false);  ?>
						<tr class="GridRow UserRecordRow">
							<td valign="top" align="center"><input type="checkbox" name="users[]" value="<?php echo $tpl->Get('each','userid'); ?>" class="UICheckboxToggleRows" /></td>
							<td><img src="images/user.gif"></td>
							<td><?php echo $tpl->Get('each','username'); ?></td>
							<td><?php if(trim($tpl->Get('each','fullname')) == ''): ?><?php echo GetLang('N/A'); ?><?php else: ?><?php echo $tpl->Get('each','fullname'); ?><?php endif; ?></td>
							<td>
								<?php if($tpl->Get('each','admintype') == 'a'): ?>
									<?php echo GetLang('AdministratorType_SystemAdministrator'); ?>
								<?php elseif($tpl->Get('each','triggertype') == 'l'): ?>
									<?php echo GetLang('AdministratorType_ListAdministrator'); ?>
								<?php elseif($tpl->Get('each','triggertype') == 'n'): ?>
									<?php echo GetLang('AdministratorType_NewsletterAdministrator'); ?>
								<?php elseif($tpl->Get('each','triggertype') == 't'): ?>
									<?php echo GetLang('AdministratorType_TemplateAdministrator'); ?>
								<?php elseif($tpl->Get('each','triggertype') == 'u'): ?>
									<?php echo GetLang('AdministratorType_UserAdministrator'); ?>
								<?php else: ?>
									<?php echo GetLang('AdministratorType_RegularUser'); ?>
								<?php endif; ?>
							</td>
							<td><?php echo $tpl->Get('each','processed_CreateDate'); ?></td>
							<td style="white-space:nowrap;"><?php echo $tpl->Get('each','processed_LastLoggedIn'); ?></td>
							<td align="center">
								<?php if($tpl->Get('each','status') == '0'): ?>
									<img alt="<?php echo GetLang('Inactive'); ?>" src="images/cross.gif" border="0" title="<?php echo GetLang('Inactive'); ?>" />
								<?php elseif($tpl->Get('each','status') == '1'): ?>
									<img alt="<?php echo GetLang('Active'); ?>" src="images/tick.gif" border="0" title="<?php echo GetLang('Active'); ?>" />
								<?php else: ?>
									-
								<?php endif; ?>
							</td>
							<td style="white-space:nowrap;">
								<a href="index.php?Page=Users&Action=Edit&UserID=<?php echo $tpl->Get('each','userid'); ?>"><?php echo GetLang('Edit'); ?></a>
								<?php if($tpl->Get('each','userid') != $tpl->Get('PAGE','currentuserid')): ?>
									&nbsp;<a href="index.php?page=AdminTools&action=disguise&newID=<?php echo $tpl->Get('each','userid'); ?>" class="ActionLink ActionType_disguise"><?php echo GetLang('LoginAsUser'); ?></a>
								<?php endif; ?>
							</td>
						</tr>
					 <?php endforeach; endif; ?>
				</table>
			</form>
			
			<?php if(count($tpl->Get('records')) != 0): ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Paging_Bottom");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php endif; ?>
		</td>
	</tr>
</table>





<script>
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
				alert("{$lang.ChooseUsersToDelete}");
				return true;
			}

			if ($.inArray('{$PAGE.currentuserid}', selectedIDs) != -1) {
				alert("{$lang.User_CantDeleteOwn}");
				return true;
			}

			if (!confirm(deleteData ? "{$lang.ConfirmRemoveUsersWithData}" : "{$lang.ConfirmRemoveUsers}")) {
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
			alert("%%LNG_ChooseUsersToDelete%%");
			return false;
		}

		if (confirm("%%LNG_ConfirmRemoveUsers%%")) {
			return true;
		}
		return false;
	}
</script>
<style>
	tr.Heading3 td { white-space:nowrap; }
</style>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr><td class="Heading1">{$lang.Users}</td></tr>
	<tr><td class="body pageinfo"><p>{$lang.Help_Users}</p></td></tr>
	{if trim($PAGE.messages) != ''}<tr><td>{$PAGE.messages}</td></tr>{/if}
	<tr>
		<td>
			<div class="UserInfo">
				<img src="images/user.gif" style="margin-top: -3px;" align="left">{$PAGE.userreport}
			</div>
		</td>
	</tr>
	<tr>
		<td class="body">
			<form name="userform" method="post" action="" onsubmit="return false;">
				<table border="0" width="100%" style="padding-top:5px">
					<tr>
						<td>
							<input id="createAccountButton" type="button" class="SmallButton" value="{$lang.UserAdd}" style="width:150px" />
							<!-- input type="submit" name="DeleteUserButton" value="{$lang.Delete_User_Selected}" class="SmallButton" /-->
							<button id="deleteAccountButton" class="SmallButton" style="white-space:nowrap;">{$lang.UserDeletePopDown}</button>
							<div id="deleteAccount" style="display: none; width: 130px;" class="DropDownMenu DropShadow">
								<ul>
									<li><a href="#N" title="{$lang.UserDeleteNoData_Summary}">{$lang.UserDeleteNoData}</a></li>
									<li><a href="#Y" title="{$lang.UserDeleteWithData_Summary}">{$lang.UserDeleteWithData}</a></li>
								</ul>
							</div>
						</td>
						<td align="right" valign="bottom">
							{template="Paging"}
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
							{$lang.UserName}
							<a href="index.php?Page=Users&SortBy=username&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=username&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="25%">
							{$lang.FullName}
							<a href="index.php?Page=Users&SortBy=fullname&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=fullname&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="15%">
							{$lang.UserType}
							<a href="index.php?Page=Users&SortBy=admintype&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=admintype&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="17%">
							{$lang.UserCreatedOn}
							<a href="index.php?Page=Users&SortBy=createdate&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=createdate&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="17%">
							{$lang.LastLoggedIn}
							<a href="index.php?Page=Users&SortBy=lastloggedin&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=lastloggedin&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="15">
							{$lang.UserStatusColumn}
							<a href="index.php?Page=Users&SortBy=status&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
							<a href="index.php?Page=Users&SortBy=status&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
						</td>
						<td width="70">
							{$lang.Action}
						</td>
					</tr>
					{foreach from=$records item=each}
						<tr class="GridRow UserRecordRow">
							<td valign="top" align="center"><input type="checkbox" name="users[]" value="{$each.userid}" class="UICheckboxToggleRows" /></td>
							<td><img src="images/user.gif"></td>
							<td>{$each.username}</td>
							<td>{if trim($each.fullname) == ''}{$lang.N/A}{else}{$each.fullname}{/if}</td>
							<td>
								{if $each.admintype == 'a'}
									{$lang.AdministratorType_SystemAdministrator}
								{elseif $each.triggertype == 'l'}
									{$lang.AdministratorType_ListAdministrator}
								{elseif $each.triggertype == 'n'}
									{$lang.AdministratorType_NewsletterAdministrator}
								{elseif $each.triggertype == 't'}
									{$lang.AdministratorType_TemplateAdministrator}
								{elseif $each.triggertype == 'u'}
									{$lang.AdministratorType_UserAdministrator}
								{else}
									{$lang.AdministratorType_RegularUser}
								{/if}
							</td>
							<td>{$each.processed_CreateDate}</td>
							<td style="white-space:nowrap;">{$each.processed_LastLoggedIn}</td>
							<td align="center">
								{if $each.status == '0'}
									<img alt="{$lang.Inactive}" src="images/cross.gif" border="0" title="{$lang.Inactive}" />
								{elseif $each.status == '1'}
									<img alt="{$lang.Active}" src="images/tick.gif" border="0" title="{$lang.Active}" />
								{else}
									-
								{/if}
							</td>
							<td style="white-space:nowrap;">
								<a href="index.php?Page=Users&Action=Edit&UserID={$each.userid}">{$lang.Edit}</a>
								{if $each.userid != $PAGE.currentuserid}
									&nbsp;<a href="index.php?page=AdminTools&action=disguise&newID={$each.userid}" class="ActionLink ActionType_disguise">{$lang.LoginAsUser}</a>
								{/if}
							</td>
						</tr>
					{/foreach}
				</table>
			</form>
			{* Bottom pagination -- Only print if records are available *}
			{if count($records) != 0}{template="Paging_Bottom"}{/if}
		</td>
	</tr>
</table>





<?php $IEM = $tpl->Get('IEM'); ?>		<table cellpadding="5" border="0" cellspacing="1" width="100%" class="Text" style="padding-top: 0px; margin-top: 0px;">
			<tr>
				<td width="100%" colspan="3">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top" align="left">
							   <div style="display: <?php if(isset($GLOBALS['DisplayStatsLinkList'])) print $GLOBALS['DisplayStatsLinkList']; ?>;">
								<?php if(isset($GLOBALS['StatsLinkDropDown'])) print $GLOBALS['StatsLinkDropDown']; ?>
                                                            </div>
							</td>
							<td valign="top" align="right">
								<?php if(isset($GLOBALS['Paging'])) print $GLOBALS['Paging']; ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="Heading3">
				<td width="30%" nowrap align="left">
					<?php print GetLang('EmailAddress'); ?>
					<a href="javascript:REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['Token'])) print $GLOBALS['Token']; ?>','<?php if(isset($GLOBALS['CurrentPage'])) print $GLOBALS['CurrentPage']; ?>','email','up')"><img src="images/sortup.gif" width="8" height="10" style="border: 0px;"></a>
					<a href="javascript:REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['Token'])) print $GLOBALS['Token']; ?>','<?php if(isset($GLOBALS['CurrentPage'])) print $GLOBALS['CurrentPage']; ?>','email','down')"><img src="images/sortdown.gif" width="8" height="10" style="border: 0px;"></a>
				</td>
				<td width="50%" nowrap align="left">
					<?php print GetLang('LinkClicked'); ?>
					<a href="javascript:REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['Token'])) print $GLOBALS['Token']; ?>','<?php if(isset($GLOBALS['CurrentPage'])) print $GLOBALS['CurrentPage']; ?>','url','up')"><img src="images/sortup.gif" width="8" height="10" style="border: 0px;"></a>
					<a href="javascript:REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['Token'])) print $GLOBALS['Token']; ?>','<?php if(isset($GLOBALS['CurrentPage'])) print $GLOBALS['CurrentPage']; ?>','url','down')"><img src="images/sortdown.gif" width="8" height="10" style="border: 0px;"></a>
				</td>
				<td width="20%" nowrap align="left">
					<?php print GetLang('LinkClickTime'); ?>
                                        <a href="javascript:REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['Token'])) print $GLOBALS['Token']; ?>','<?php if(isset($GLOBALS['CurrentPage'])) print $GLOBALS['CurrentPage']; ?>','clicked','up')"><img src="images/sortup.gif" width="8" height="10" style="border: 0px;"></a>
					<a href="javascript:REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['Token'])) print $GLOBALS['Token']; ?>','<?php if(isset($GLOBALS['CurrentPage'])) print $GLOBALS['CurrentPage']; ?>','clicked','down')"><img src="images/sortdown.gif" width="8" height="10" style="border: 0px;"></a>
				</td>
			</tr>
			<?php if(isset($GLOBALS['Stats_Step3_Links_List'])) print $GLOBALS['Stats_Step3_Links_List']; ?>
			<tr>
				<td align="right" colspan="3">
					<?php if(isset($GLOBALS['PagingBottom'])) print $GLOBALS['PagingBottom']; ?>
				</td>
			</tr>
		</table> 





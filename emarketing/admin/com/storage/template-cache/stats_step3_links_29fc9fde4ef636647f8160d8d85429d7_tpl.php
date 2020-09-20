<?php $IEM = $tpl->Get('IEM'); ?><div id="div3" style="display:none">
	<div class="body">
		<br><?php if(isset($GLOBALS['DisplayLinksIntro'])) print $GLOBALS['DisplayLinksIntro']; ?>
		<br><br>
	</div>

	<div>
		<?php if(isset($GLOBALS['Calendar'])) print $GLOBALS['Calendar']; ?>
	</div>
	<br>

	<table width="100%" border="0" class="Text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;<?php print GetLang('LinkClicks_Summary'); ?></div>
			<UL class="Text"> 
				<LI><?php print GetLang('Stats_TotalClicks'); ?>: <span id="totalclicks"></span></li>
				<LI><?php print GetLang('Stats_TotalUniqueClicks'); ?>: <?php if(isset($GLOBALS['TotalUniqueClicks'])) print $GLOBALS['TotalUniqueClicks']; ?></li>
				<LI><?php print GetLang('Stats_UniqueClicks'); ?>: <span id="uniqueclicks"><?php if(isset($GLOBALS['UniqueClicks'])) print $GLOBALS['UniqueClicks']; ?></span></li>
				<LI><?php print GetLang('Stats_MostPopularLink'); ?>: <a href="<?php if(isset($GLOBALS['MostPopularLink'])) print $GLOBALS['MostPopularLink']; ?>" title="<?php if(isset($GLOBALS['MostPopularLink'])) print $GLOBALS['MostPopularLink']; ?>" target="_blank"><?php if(isset($GLOBALS['MostPopularLink_Short'])) print $GLOBALS['MostPopularLink_Short']; ?></a></li>
				<LI><?php print GetLang('Stats_AverageClicks'); ?>: <span id="averageclicks"></span></li>
				<li><?php print GetLang('Stats_Clickthrough'); ?>: <span id="clickthrough"></span></li>
			</UL>
		</td>
		<td align="center">
                  <?php if(isset($GLOBALS['LinksChart'])) print $GLOBALS['LinksChart']; ?>
		</td>
		</tr>
	</table>
	<script>
	function ChangeLink(page,column,sort) {
						chooselink_id = document.getElementById('chooselink');
						selected = chooselink_id.selectedIndex;
						linkid = chooselink_id[selected].value;
						REMOTE_parameters = '&link=' + linkid;
						REMOTE_admin_table($('#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>'),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['TableToken'])) print $GLOBALS['TableToken']; ?>',page,column,sort);

						UpdateLinkSummary();
					}
					
					function UpdateLinkSummary() {
						if (document.getElementById('chooselink')) {
							chooselink_id = document.getElementById('chooselink');
							selected = chooselink_id.selectedIndex;
							linkid = chooselink_id[selected].value;
						} else {
							linkid = 'a';
						}

						// Update link stats
						$.get('remote_stats.php?Action=get_linkstats&link=' + linkid + '&token=<?php if(isset($GLOBALS['TableToken'])) print $GLOBALS['TableToken']; ?>','',
							function(data) {
								eval(data);
								$('#totalclicks').html(linksjson.linkclicks);
								$('#clickthrough').html(linksjson.clickthrough);
								$('#averageclicks').html(linksjson.averageclicks);
								$('#uniqueclicks').html(linksjson.uniqueclicks);
							}
						);
					}
					UpdateLinkSummary();
				</script>
								<?php if(isset($GLOBALS['Loading_Indicator'])) print $GLOBALS['Loading_Indicator']; ?>
								<div id="adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>"></div>
				
								<script>
									REMOTE_admin_table($("#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>"),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['TableToken'])) print $GLOBALS['TableToken']; ?>',1,'email','up');
								</script>
		<br><br>
	</div>
</div>





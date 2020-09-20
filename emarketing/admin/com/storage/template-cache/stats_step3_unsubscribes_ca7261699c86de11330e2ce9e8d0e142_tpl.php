<?php $IEM = $tpl->Get('IEM'); ?><div id="div5" style="display:none">
	<div class="body">
		<br><?php if(isset($GLOBALS['DisplayUnsubscribesIntro'])) print $GLOBALS['DisplayUnsubscribesIntro']; ?>
		<br><br>
	</div>

	<div>
		<?php if(isset($GLOBALS['Calendar'])) print $GLOBALS['Calendar']; ?>
	</div>
	<br>

<table width="100%" border="0" class="Text">
		<tr><td valign=top width="250" rowspan="2">
		<div class="MidHeading" style="width:100%"><img src="images/m_stats.gif" width="20" height="20" align="absMiddle">&nbsp;<?php print GetLang('Unsubscribe_Summary'); ?></div>
						<UL class="Text"> 
							<LI><?php print GetLang('Stats_TotalUnsubscribes'); ?>: <?php if(isset($GLOBALS['TotalUnsubscribes'])) print $GLOBALS['TotalUnsubscribes']; ?></li>
							<LI><?php print GetLang('Stats_MostUnsubscribes'); ?>: <?php if(isset($GLOBALS['MostUnsubscribes'])) print $GLOBALS['MostUnsubscribes']; ?></li>
						</UL>
		</td>
		<td align="center">
		  <?php if(isset($GLOBALS['UnsubscribeChart'])) print $GLOBALS['UnsubscribeChart']; ?>
		</td>
		</tr>
	</table>
		<!-- stats_step3_unsubscribes_table -->
		<?php if(isset($GLOBALS['Loading_Indicator'])) print $GLOBALS['Loading_Indicator']; ?>
                <div id="adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>"></div>
        
                <script>
                  REMOTE_admin_table($("#adminTable<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>"),'<?php if(isset($GLOBALS['TableURL'])) print $GLOBALS['TableURL']; ?>','','<?php if(isset($GLOBALS['TableType'])) print $GLOBALS['TableType']; ?>','<?php if(isset($GLOBALS['TableToken'])) print $GLOBALS['TableToken']; ?>',1,'email','up');
                </script>
		<br><br>
	</div>
</div>





<?php $IEM = $tpl->Get('IEM'); ?><html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="javascript" type="text/javascript">
		function checkboxValidation()
		{
			valid = false;
			for(i = 0; i < document.forms[0].elements.length; i++) {
				element = document.forms[0].elements[i];
				if (element.type == 'checkbox') {
					if (element.checked) {
						valid = true;
					}
				}
			}
			
			if (!valid) {
				alert ('<?php print GetLang('Unsubscribe_InvalidList'); ?>');
				return false;
			} else {
				 document.unsubscribe_form.submit();
				return true;
			}

			
		}
	</script>
	</head>
		<body>
		<style type="text/css">
		
			.myForm td, input, select, textarea, checkbox, div {
				font-family: tahoma;
				font-size: 12px;
			}
		
			.required {
				color: red;
			}
			
			.FlashMessage {
				background-color:#FFF1A8;
				margin-bottom:15px;
				margin-left:5px;
				padding:8px 5px 8px 10px;
			}
		
		</style>

		<form name="unsubscribe_form" action="unsubscribe_confirmed.php" method="get">  
			<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			<?php $array = $tpl->Get('page'); if(is_array($array)): foreach($array as $index=>$each): $tpl->Assign('index', $index, false); $tpl->Assign('each', $each, false);  ?>
			<input type="hidden" name="<?php echo $tpl->Get('index'); ?>" value="<?php echo $tpl->Get('each'); ?>" />  
			 <?php endforeach; endif; ?>
			<table border="0" cellpadding="2" class="myForm">
				<tr valign="top">
					<td>
					<span class="required">*</span>&nbsp;Contact Lists:
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
						<?php $array = $tpl->Get('list'); if(is_array($array)): foreach($array as $__key=>$each): $tpl->Assign('__key', $__key, false); $tpl->Assign('each', $each, false);  ?>
							<tr valign="top">
								<td>
									<label for="lists_<?php echo $tpl->Get('each','listid'); ?>">
									<input type="checkbox" id="lists_<?php echo $tpl->Get('each','listid'); ?>" name="lists[<?php echo $tpl->Get('each','subscriberid'); ?>][<?php echo $tpl->Get('each','listid'); ?>]" value="<?php echo $tpl->Get('each','cc'); ?>" /><?php echo $tpl->Get('each','name'); ?></label>
								</td>
							</tr>
						 <?php endforeach; endif; ?>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" value="<?php print GetLang('Unsubscribe_Yes'); ?>" onclick="checkboxValidation();" />
					</td>
				</tr>
			</table>

		         
		</form>  
	</body>
</html>




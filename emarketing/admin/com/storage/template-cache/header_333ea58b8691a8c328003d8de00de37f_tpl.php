<?php $IEM = $tpl->Get('IEM'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<title><?php print $IEM['ApplicationTitle']; ?></title>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/vnd.microsoft.icon">
	<link rel="icon" href="images/favicon.ico" type="image/vnd.microsoft.icon">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php if(isset($GLOBALS['CHARSET'])) print $GLOBALS['CHARSET']; ?>">
	<link rel="stylesheet" href="includes/styles/stylesheet.css" type="text/css">
	<link rel="stylesheet" href="includes/styles/tabmenu.css" type="text/css">
	<link rel="stylesheet" href="includes/styles/thickbox.css" type="text/css">
	
	<!--[if IE]>
	<style type="text/css">
		@import url("includes/styles/ie.css");
	</style>
	<![endif]-->
	
	<script src="includes/js/jquery.js"></script>
	<script src="includes/js/jquery/thickbox.js"></script>
	<script src="includes/js/javascript.js"></script>
	<script>
		var UnsubLinkPlaceholder = "<?php print GetLang('UnsubLinkPlaceholder'); ?>";
		var UsingWYSIWYG = '<?php if(isset($GLOBALS['UsingWYSIWYG'])) print $GLOBALS['UsingWYSIWYG']; ?>';
		var Searchbox_Type_Prompt = "<?php print GetLang('Searchbox_Type_Prompt'); ?>";
		var Searchbox_List_Info = '<?php if(isset($GLOBALS['Searchbox_List_Info'])) print $GLOBALS['Searchbox_List_Info']; ?>';
		var Application_Title = '<?php print GetLang('ApplicationTitle'); ?>';
	
		Application.Misc.specifyDocumentMinWidth(935);
		Application.Misc.setPingServer('ping.php', 120000);
	</script>
</head>

<body>
<div class="Header">
	<div class="Header_Top"></div>

	<div class="logo">
		<a href="index.php"><img id="logo" src="images/logo.jpg" alt="logo" border="0" /></a>
	</div>

	<div class="textlinks" align="right">
		<div class="MenuText">
			<?php if(IEM::getCurrentUser()) print $IEM['TextLinks']; ?>
			<div class="loggedinas">
				<?php if(isset($GLOBALS['UserLoggedInAs'])) print $GLOBALS['UserLoggedInAs']; ?><?php if(isset($GLOBALS['SystemDateTime'])) print $GLOBALS['SystemDateTime']; ?>
			</div>
			<span class="emailcredits"><?php if(isset($GLOBALS['MonthlyEmailCredits'])) print $GLOBALS['MonthlyEmailCredits']; ?></span>
			<span class="emailcredits"><?php if(isset($GLOBALS['TotalEmailCredits'])) print $GLOBALS['TotalEmailCredits']; ?></span>
		</div>
	</div>

	<div class="Header_Bottom"></div>
</div>

<div class="menuBar">
	<div style="height:0px;">&nbsp;</div>
	<div><?php if(IEM::getCurrentUser()) print $IEM['MenuLinks']; ?></div>
</div>

<div class="ContentContainer">
	<div class="BodyContainer">
		<?php if($tpl->Get('ShowTestModeWarning')): ?>
			<div class="TestModeEnabled"><div style="valign: top"><img src="images/critical.gif"  align="left" hspace="5"><?php echo $tpl->Get('SendTestWarningMessage'); ?></div></div>
		<?php endif; ?>

<?php if(isset($GLOBALS['BodyAddons'])) print $GLOBALS['BodyAddons']; ?>





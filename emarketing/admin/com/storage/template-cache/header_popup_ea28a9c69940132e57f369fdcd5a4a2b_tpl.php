<?php $IEM = $tpl->Get('IEM'); ?><html>
<head>
<title><?php print GetLang('ControlPanel'); ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php if(isset($GLOBALS['CHARSET'])) print $GLOBALS['CHARSET']; ?>">
<link rel="stylesheet" href="includes/styles/stylesheet.css" type="text/css">

<script>
	var UnsubLinkPlaceholder = "<?php print GetLang('UnsubLinkPlaceholder'); ?>";
	var ModifyLinkPlaceholder = "<?php print GetLang('ModifyLinkPlaceholder'); ?>";
	var SendToFriendLinkPlaceholder = "<?php print GetLang('SendToFriendLinkPlaceholder'); ?>";
	var UsingWYSIWYG = '<?php if(isset($GLOBALS['UsingWYSIWYG'])) print $GLOBALS['UsingWYSIWYG']; ?>';
</script>

<script src="includes/js/jquery.js"></script>
<script src="includes/js/javascript.js"></script>
<script defer>
	// Hack for IE
	if(navigator.userAgent.indexOf('MSIE') > -1) {
		document.getElementById('popContainer').style.width = '100%';
	}
</script>

</head>

<body class="popupBody">
	<div class="popupContainer" id="popContainer">
<!-- END PAGE HEADER -->





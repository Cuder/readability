{strip}<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html lang="en"><head>
<title>{$title}</title>

{* BASE for keeping relative paths correct *}
<BASE href="{$smarty.server.PHP_SELF}">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="view/css/main.css">

{* JS scripts and CSS for tooltips *}
{if isset($stage) && $stage != "input"}
	<link rel="stylesheet" type="text/css" href="view/css/tooltip.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.tooltip.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
{/if}

{if isset($stage) && $stage == "result"}
	<link rel="stylesheet" type="text/css" href="view/css/readability.css">
{/if}

</head>
<body>

{* Google analytics script *}
<script type="text/javascript" src="js/ganalytics.js"></script>
{/strip}

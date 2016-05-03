{strip}

{* Header *}
{include file="header.tpl"}

{* The main layout table begins in here *}
<table cellpadding='0' cellspacing='0' class='maintable'>
	
{* Project title *}
<tr><td class='header'>{$title}<sup> β</sup></td></tr>

{* The small table layout (for menu + main window) *}
<tr><td><table cellpadding='0' cellspacing='0' class='smalltable'><tr>

{* Menu *}
<td class='menu'>{include file="menu.tpl" nocache}</td>

{* Beginning of the main window of the script *}
<td class='mainwindow'>
	
{if !isset($error)}
	{if isset($stage)}
		{include file="{$stage}.tpl" nocache}
	{else}
		{include file="about.tpl"}
	{/if}
{else}
	{* Errors *}
	<div class='error'>{$errorTitle}</div>{$errorDescr}
	{if $error == 'ldfailed' || $error == 'language' || $error == 'smalltext' || $error == 'rubbish' || $error == 'toolarge'}
		<div class='buttonarea'>
			<input type='button' onclick='location.href="input"' class='button' value='{if $error == 'language'}{$buttons.1}{else}{$buttons.0}{/if}'>
		</div>
	{/if}
{/if}

{* Footer *}
{include file="footer.tpl" nocache}
{/strip}

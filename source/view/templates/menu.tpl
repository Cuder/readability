{strip}

{* Menu item 1 *}
{if !isset($session) && !isset($stage)}
	<a href='input'>{$menuItems.0}</a>
{elseif $stage != "input"}
	<a href='{$session}/input'>{$menuItems.0}</a>
{else}
	{$menuItems.0}
{/if}

<br>

{* Menu item 2 *}
{if isset($session) && isset($stage) && $stage != "readability"}
	<a href='{$session}/readability'>{$menuItems.1}</a>
{else}
	{$menuItems.1}
{/if}

<br>

{* Menu item 3 *}
{if isset($session) && isset($stage) && $stage != "otheroptions"}
	<a href='{$session}/otheroptions'>{$menuItems.2}</a>
{else}
	{$menuItems.2}
{/if}

<br>

{* Menu item 4 *}
{if isset($stage) && $stage != "result" && $ifSessionComplete == 1}
	<a href='{$session}/result'>{$menuItems.3}</a>
{else}
	{$menuItems.3}
{/if}

<br><br>

{* Menu item 5 *}
<a href='http://{$smarty.server.SERVER_NAME}/'{if isset($session)} target='_blank'{/if}>{$menuItems.4}</a><br>

{/strip}

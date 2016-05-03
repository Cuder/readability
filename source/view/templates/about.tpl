{strip}
<div class='step'>{$captions[0]}</div>

{* Overview *}
<div class='resultcaption'>{$captions[1]}</div>
<div class='result'>
    {$overview[0]}
    <ul>
        {foreach from=$tests item=test}
            <li>{$test}</li>
        {/foreach}
    </ul>
    {$overview[1]}
</div>

{* Credits *}
<div class='resultcaption'>{$captions[2]}</div>
<ul>
    {foreach from=$credits item=credit}
        <li>{$credit}</li>
    {/foreach}
</ul>

{* Feedback *}
<div class='resultcaption'>{$captions[3]}</div>
<div class='result'>
    {$overview[2]}<br>{$overview[3]}
</div>
{/strip}

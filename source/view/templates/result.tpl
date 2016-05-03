{strip}
{if isset($showopt)}
    <div class='step'>{$resultsTitle}</div>

    {* Displaying readability scores *}
    {if $showopt[0] == 1}
        <div class='resultcaption'>{$rdbCaption}</div>
        <div class='result'>
            <table class='readabilityTable'>
                {include file='readability.test.tpl' nocache}
            </table>
        </div>
    {/if}

    {* Displaying the text *}
    {if $showopt[2] == 1}
        <div class='resultcaption'>{$rdbText}</div>
        <div class='result'>{$text}</div>
    {/if}

    {* Displaying the text statistics *}
    {if $showopt[1] == 1}
        <div class='resultcaption'>{$rdbStatistics}</div>
        <div class='result'>
            <table class='readabilityTable'>
                <tr>
                    <td width='40%'>{$statisticsStrings.0}:</td>
                    <td width='30%'>{$statistics.6}</td>
                    <td width='30%'></td>
                </tr>
                <tr class='grey'>
                    <td width='40%'>{$statisticsStrings.1}:</td>
                    <td width='30%'>{$statistics.1} ({$statisticsStrings.4})</td>
                    <td width='30%'>{$statistics.0} ({$statisticsStrings.5})</td>
                </tr>
                <tr>
                    <td width='40%'>{$statisticsStrings.2}:</td>
                    <td width='30%'>{$statistics.3} ({$statisticsStrings.4})</td>
                    <td width='30%'>{$statistics.2} ({$statisticsStrings.6})</td>
                </tr>
                <tr class='grey'>
                    <td width='40%'>{$statisticsStrings.3}:</td>
                    <td width='30%'>{$statistics.4} ({$statisticsStrings.4})</td>
                    <td width='30%'>{$statistics.5}% ({$statisticsStrings.7})</td>
                </tr>
            </table>
            <table class='readabilityTable'>
                <tr>
                    <td class='functionWords'>
                        <div title='::{$statisticsTooltip}'>{$statisticsStrings.8}:</div>
                    </td>
                    <td width='60%'>{$popularWords}</td>
                </tr>
            </table>
        </div>
    {/if}

    {* Save results *}
    <div class='resultcaption'>{$saveResults}</div>
    <div class='result'>
        {$download}<br>{$download2}<br>
        <input style='width: 100%;' title='' value='http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}' onclick='this.select();' onfocus='this.select();' readonly='readonly' />
        <br>{$download3}
    </div>
{else}
    <div class='step'>{$nothingToShow}</div>{$nothingToShow2}
{/if}

{* Buttons *}
<div class='buttonarea'>
    <input type='button' onclick='location.href="{$session}/otheroptions"' class='button' value='{$buttons.0}'>
    <input type='button' onclick='if(confirm("{$prompt}")) { location.href="input"; }' class='button' value='{$buttons.1}'>
</div>
{/strip}

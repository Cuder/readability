{strip}
{for $i=0 to 5}
    {if $showopt2[$i] == 1}
        <tr>
            <td class='rdbParent'>{$rdbTitle[$i]}</td>
            <td class='rdbParent'>
                <table class='readability' title='{$rdbTooltip[$i]}'>
                    <tr>
                    {if $result[$i]>0 && $result[$i]<$limit}
                        <td style='background-color:{$color[$i]};width:{$percent[$i]}%;'></td>
                        <td width='{$td[$i]}%'>
                    {elseif $result[$i]>=$limit}
                        <td class='rdbMinimum'></td><td class='rdbMinimum99'>
                    {else}
                        <td class='rdbmaximum'>
                    {/if}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    {/if}
{/for}
{/strip}

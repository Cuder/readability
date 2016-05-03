{strip}
{* Input text form *}
<div class='step'>{$stepText} 1. {$menuItems.0}</div>
<div class='inputarea'>
    <form name='submit_form' method='post' action='input' onsubmit='return empty_form()'>
        <textarea name='text' id='text' maxlength='{$maxsymbols}' placeholder='{$inputStrings.0}'>{$text}</textarea>
        <table cellspacing='0' cellpadding='0' class='countertable'>
            <tr>
                {* Counters *}
                <td>
                    {$inputStrings.1}: <span id='syllablesNumber'>0/{$maxsymbols}</span> ({$inputStrings.2} {$minsymbols}) <span id='okSpan'></span><br>
                    {$inputStrings.3}: <span id='wordsNumber'>0<span>
                </td>
                {* Buttons *}
                <td>
                    <div class='inputbuttonarea'>
                        <input type='submit' value='' class=hbutton> {* Hidden button for Opera border fix *}
                        <input id='clear' type='reset' class='button' name='clear_text' value='{$buttons.0}'>
                        <input id='submit' type='submit' class='button' name='preview_text' value='{$buttons.1}'>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>

{* JS for counting words/charachters *}
<script type='text/javascript' src='js/textareaCountNew.js'></script>
{/strip}

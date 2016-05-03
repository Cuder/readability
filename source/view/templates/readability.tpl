{strip}
<form name='submit_form' id='wrappingForm' method='post' action='{$session}/readability' onsubmit='return empty_form()'>
    <div class='step'>{$stepText} 2. {$menuItems.1}</div>

    {* Readability tests checkboxes *}
    <input type='checkbox' title='' name='reading_ease'{$checked[0]}> {$tests[0]}<br>
    <input type='checkbox' title='' name='grade_level'{$checked[1]}> {$tests[1]}<br>
    <input type='checkbox' title='' class='group1' name='gunning_fog'{$checked[2]}> {$tests[2]}<br>
    <input type='checkbox' title='' name='coleman'{$checked[3]}> {$tests[3]}<br>
    <input type='checkbox' title='' class='group1' name='smog'{$checked[4]}> {$tests[4]}<br>
    <input type='checkbox' title='' name='automated_readability'{$checked[5]}> {$tests[5]}<br>

    {* Options which influence readability *}
    <div class='options' title='::{$tooltips[0]}'>{$optionsTitle}</div>
    <div title="::{$tooltips[1]}"><input type='checkbox' title='' class='group2' name='proper'> {$options[0]}</div>
    <div title="::{$tooltips[2]}"><input type='checkbox' title='' class='group2' name='common'> {$options[1]}</div>
    <div title="::{$tooltips[3]}"><input type='checkbox' title='' class='group2' name='formal'> {$options[2]}</div>

    {* Buttons *}
    <div class='buttonarea'>
        <input type='button' onclick='location.href="{$session}/input"' class='button' value='{$buttons[0]}'>
        <input type='submit' class='button' name='proceed' value='{$buttons[1]}'>
    </div>
</form>

{* JS for check box state processing*}
<script type="text/javascript" src="js/checkbox.processing.js"></script>
{/strip}

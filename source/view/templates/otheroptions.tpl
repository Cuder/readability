{strip}
<form name='submit_form' method='post' action='{$session}/otheroptions' onsubmit='return empty_form()'>
    <div class='step'>{$stepText} 3. {$menuItems.2}</div>

    {* Other options check boxes *}
    <div title='::{$tooltips[0]}'><input type='checkbox' title='' name='showstats'{$showopt[1]}> {$options[0]}</div>
    <div title='::{$tooltips[1]}'><input type='checkbox' title='' class='group1' name='textshow'{$showopt[2]}> {$options[1]}</div>
    <div title='::{$tooltips[2]}'><input type='checkbox' title='' class='group2' name='longwords'{$showopt[3]}> {$options[2]}</div>
    <div title='::{$tooltips[3]}'><input type='checkbox' title='' class='group2' name='fwords'{$showopt[4]}> {$options[3]}</div>

    {* Buttons *}
    <div class='buttonarea'>
        <input type='button' onclick='location.href="{$session}/readability"' class='button' value='{$buttons[0]}'>
        <input type='submit' class='button' name='showtext' value='{$buttons[1]}'>
    </div>
</form>

{* JS for check box state processing*}
<script type="text/javascript" src="js/checkbox.processing.js"></script>
{/strip}

<?php
if (preg_match("/classes.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");
function clean_text($strText) {
            // all these tags should be preceeded by a full stop.
            $fullStopTags = array('li', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'dd');
            foreach ($fullStopTags as $tag) {
                $strText = str_ireplace('</$tag>', '.', $strText);
            }
            $strText = strip_tags($strText);
            $strText = preg_replace('/[",:;()]/', ' ', $strText); // Replace commas, hyphens, quotes etc (count them as spaces)
            $strText = preg_replace('/[\.!?]/', '.', $strText); // Unify terminators
            $strText = trim($strText) . '.'; // Add final terminator, just in case it's missing.
            $strText = preg_replace('/[ ]*(\n|\r\n|\r)[ ]*/', ' ', $strText); // Replace new lines with spaces
           // $strText = preg_replace('/([\.])[\. ]+/', '$1', $strText); // Check for duplicated terminators
            $strText = trim(preg_replace('/[ ]*([\.])/', '$1 ', $strText)); // Pad sentence terminators         
            // Remove "words" comprised only of numbers
            while (preg_match('/ [0-9]+ /', $strText)) {
				$strText = preg_replace('/ [0-9]+ /', ' ', $strText);
			}
			$strText = preg_replace('/ [0-9]+./', '.', $strText);
            $strText = preg_replace('/[ ]+/', ' ', $strText); // Remove multiple spaces
            $strText = preg_replace_callback('/\. [^ ]+/', create_function('$matches', 'return strtolower($matches[0]);'), $strText); // Lower case all words following terminators (for gunning fog score)
            // trim spaces at the beginning and the end
            $strText = trim($strText);
            // remove initial terminator (period) and number (if any)
            if (substr($strText, 0, 1) == ".") $strText = preg_replace('(.)', '', $strText, 1);
            if (strrpos($strText, '/[0-9]+./') == 0) $strText = preg_replace('/[0-9]+./', '', $strText, 1);
            
            // исправить висящие точки
            $strText = preg_replace('/ [.] /', '. ', $strText);
            
            // удалим все лишние символы, которые мешают нам спокойно жить
            $strText = preg_replace('/[^A-Za-z0-9 .&\'`,-]+/', '', $strText);
            
            $strText = preg_replace('/([\.])[\. ]+/', '$1 ', $strText); // Check for duplicated terminators
            return $strText;
}
?>

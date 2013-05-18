<?php
// forbid to open this file directly from the browser
if (preg_match("/footer.php/i", $_SERVER['PHP_SELF'])) header("Location: index.php");

// конец выполнения скрипта, подсчет времени
$exec_time = round((microtime(true) - $start_time), 4);
echo "&copy; 2012-2013 Nikita Kovin | v.1.0.0b | Script runtime: $exec_time sec.";
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-40669713-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php
$str = '<embed class="embedded-thing" src="http://www.livingston.org/Page/18817"/>Hello';

$layout = new BasicLayout($str);
$layout->render();
?>

<script>

var contents = $("embed");


</script>
<?php

/* 
 * This internediate page is used to post value to IST before to redirect
 */
require_once("config.php");
?>

<form action='<?php echo IST_MEMO_DETAIL; ?>' method='post' name='frm'>
<?php
foreach ($_GET as $a => $b) {
echo "<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."' />";
}
?>
</form>
<script>
document.frm.submit();
</script>


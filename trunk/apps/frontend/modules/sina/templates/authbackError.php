<?php
if (!$errorMsg) {
	$errorMsg = '添加帐号失败';
}
?>
<script>
	window.opener.parent.jQuery.sirius.statusMessage('<?php echo $errorMsg?>', 'error');
	window.close();
</script>
<?php
if (isset($_GET['image']))
{
	header('Content-type: image');
	readfile($_GET['image']);
	exit();
}
?>
<fieldset>
<legend><?php echo $_GET['dir']; ?>/<?php echo $_GET['file']; ?></legend>
<img src="ext/png.php?image=<?php echo $_GET['dir']; ?>/<?php echo $_GET['file']; ?>" />
</fieldset>
